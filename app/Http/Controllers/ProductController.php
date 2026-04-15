<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.products', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products_create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. ვალიდაცია
        $validatedData = $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'features_text'     => 'nullable|string', // ✅ ცვლილება: features-ის ნაცვლად
            'price'             => 'required|numeric|min:0',
            'supplier_country'  => 'required|string|max:255',
            'condition'         => 'required|in:new,like_new,used',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_link'        => 'nullable|url|max:255',
            'sub_type'          => 'nullable|string|max:255',
        ]);

        // 2. სურათის ატვირთვა
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = (new ImageService())->storeAsWebp($request->file('image'), 'products');
        }

        // ❌ ძველი მახასიათებლების დამუშავების ლოგიკა წაშლილია

        // 3. პროდუქტის შექმნა
        Product::create([
            'category_id'       => $validatedData['category_id'],
            'name'              => $validatedData['name'],
            'description'       => $validatedData['description'],
            'features_text'     => $validatedData['features_text'] ?? null,
            'price'             => $validatedData['price'],
            'supplier_country'  => $validatedData['supplier_country'],
            'condition'         => $validatedData['condition'],
            'video_link'        => $validatedData['video_link'] ?? null,
            'sub_type'          => $validatedData['sub_type'] ?? null,
            'image'             => $imagePath,
        ]);
        
        return redirect()->route('admin.products.index')->with('success', 'პროდუქტი წარმატებით დაემატა!');
    }

    /**
     * Get search suggestions.
     */
    public function getSuggestions(Request $request)
    {
        try {
            $query = trim($request->input('query', ''));

            if (strlen($query) < 2) {
                return response()->json(['suggestions' => []])
                    ->header('Cache-Control', 'no-store');
            }

            $cacheKey = 'search_suggestions_' . mb_strtolower($query);

            $suggestions = Cache::remember($cacheKey, 120, function () use ($query) {
                $results = collect();

                // 1. Products — მხოლოდ name-ით, orWhereHas subquery ძვირია
                $products = Product::where('name', 'LIKE', "%{$query}%")
                    ->whereNotNull('slug')
                    ->with('category:id,name')
                    ->limit(5)
                    ->get(['id', 'name', 'slug', 'image', 'price', 'category_id']);

                foreach ($products as $product) {
                    $results->push([
                        'name'            => $product->name,
                        'category'        => $product->category?->name,
                        'formatted_price' => number_format($product->price, 2) . ' ₾',
                        'image'           => $product->image ? asset('storage/' . $product->image) : asset('images/no-image.jpg'),
                        'url'             => route('products.show', $product->slug),
                        'type'            => 'product',
                    ]);
                }

                // 2. Categories
                $categories = Category::where('name', 'LIKE', "%{$query}%")
                    ->whereNotNull('slug')
                    ->limit(3)
                    ->get(['id', 'name', 'slug', 'image']);

                foreach ($categories as $category) {
                    $results->push([
                        'name'            => $category->name,
                        'category'        => 'კატეგორია',
                        'formatted_price' => null,
                        'image'           => $category->image ? asset('storage/' . $category->image) : asset('images/category-default.jpg'),
                        'url'             => route('categories.show', $category->slug),
                        'type'            => 'category',
                    ]);
                }

                return $results->values()->all();
            });

            return response()->json(['suggestions' => $suggestions])
                ->header('Cache-Control', 'public, max-age=120');
        } catch (\Exception $e) {
            Log::error('Search suggestions error: ' . $e->getMessage());
            return response()->json(['suggestions' => []], 500);
        }
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products_edit', compact('product', 'categories'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 1. ვალიდაცია
        $validatedData = $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'features_text'     => 'nullable|string', // ✅ ცვლილება: features-ის ნაცვლად
            'price'             => 'required|numeric|min:0',
            'supplier_country'  => 'required|string|max:255',
            'condition'         => 'required|in:new,like_new,used',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_link'        => 'nullable|url|max:255',
            'sub_type'          => 'nullable|string|max:255',
        ]);
        
        $product = Product::findOrFail($id);
        
        // 2. სურათის ატვირთვა/განახლება
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = (new ImageService())->storeAsWebp($request->file('image'), 'products');
        }

        // ❌ ძველი მახასიათებლების დამუშავების ლოგიკა წაშლილია
        
        // 3. პროდუქტის განახლება
        $product->update([
            'category_id'       => $validatedData['category_id'],
            'name'              => $validatedData['name'],
            'description'       => $validatedData['description'],
            'features_text'     => $validatedData['features_text'], // ✅ ცვლილება: features-ის ნაცვლად
            'price'             => $validatedData['price'],
            'supplier_country'  => $validatedData['supplier_country'],
            'condition'         => $validatedData['condition'],
            'video_link'        => $validatedData['video_link'],
            'sub_type'          => $validatedData['sub_type'],
            'image'             => $imagePath,
        ]);
        
        return redirect()->route('admin.products.index')->with('success', 'პროდუქტი წარმატებით განახლდა!');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')->with('success', 'პროდუქტი წარმატებით წაიშალა!');
    }
    
    /**
     * Display the specified product on the frontend.
     */
    public function show(Product $product)
    {
        $product->increment('views_count');
        
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->limit(10)
            ->get();
        
        return view('product_show', compact('product', 'similarProducts'));
    }
    
    /**
     * Search products (frontend search).
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $country = $request->input('country');
        $condition = $request->input('condition');
        $sort = $request->input('sort', 'asc');
        
        $products = Product::where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhereHas('category', function ($q) use ($query) {
                      $q->where('name', 'LIKE', "%{$query}%");
                  })
                  ->orWhere('sub_type', 'LIKE', "%{$query}%");
            })
            ->when($minPrice, fn($q) => $q->where('price', '>=', $minPrice))
            ->when($maxPrice, fn($q) => $q->where('price', '<=', $maxPrice))
            ->when($country, fn($q) => $q->where('supplier_country', $country))
            ->when($condition, fn($q) => $q->where('condition', $condition))
            ->with('category')
            ->orderBy('price', $sort)
            ->get();
        
        $countries = Product::select('supplier_country')->distinct()->pluck('supplier_country');
        
        return view('products.search_results', compact('products', 'query', 'sort', 'minPrice', 'maxPrice', 'country', 'condition', 'countries'));
    }

    /**
     * Reset product views.
     */
    public function resetViews()
    {
        Product::query()->update(['views_count' => 0]);
        return redirect()->route('admin.products.index')->with('success', 'ყველა პროდუქტის ნახვა განულებულია!');
    }
}