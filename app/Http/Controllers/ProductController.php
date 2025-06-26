<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // სასარგებლოა debugging-ისთვის

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
        // ProductController-ში 'admin.products_create' დაბრუნებისას,
        // თუ რედაქტირებისთვისაც იყენებთ, შეგიძლიათ ცარიელი $product გადასცეთ.
        // მაგრამ რადგან ეს create მეთოდია, უმჯობესია ცალკე view გქონდეთ ან
        // $product-ის გარეშე გადასცეთ.
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
            'price'             => 'required|numeric|min:0', // დავამატე min:0 ფასისთვის
            'supplier_country'  => 'required|string|max:255', // თუ Country code-ია, max:2 უნდა იყოს
            'condition'         => 'required|in:new,like_new,used',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_link'        => 'nullable|url|max:255', // დავამატე max:255
            'sub_type'          => 'nullable|string|max:255',
            // --- მახასიათებლების ვალიდაცია ---
            'features'          => 'nullable|array', // features უნდა იყოს მასივი
            'features.*.name'   => 'nullable|string|max:255', // თითოეული მახასიათებლის სახელი
            'features.*.value'  => 'nullable|string|max:255', // თითოეული მახასიათებლის მნიშვნელობა
        ]);
    
        // 2. სურათის ატვირთვა
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // 3. მახასიათებლების დამუშავება
        $featuresToStore = [];
        if ($request->has('features') && is_array($request->input('features'))) {
            foreach ($request->input('features') as $feature) {
                // ვამოწმებთ, რომ name და value არ არის ცარიელი
                if (!empty($feature['name']) && !empty($feature['value'])) {
                    $featuresToStore[] = [
                        'name' => $feature['name'],
                        'value' => $feature['value'],
                    ];
                }
            }
        }
        
        // 4. პროდუქტის შექმნა
        Product::create([
            'category_id'      => $validatedData['category_id'],
            'name'             => $validatedData['name'],
            'description'      => $validatedData['description'],
            'price'            => $validatedData['price'],
            'supplier_country' => $validatedData['supplier_country'],
            'condition'        => $validatedData['condition'],
            'video_link'       => $validatedData['video_link'],
            'sub_type'         => $validatedData['sub_type'],
            'image'            => $imagePath, // სურათის გზის შენახვა
            'features'         => $featuresToStore, // დამუშავებული მახასიათებლების შენახვა
        ]);
    
        return redirect()->route('admin.products.index')->with('success', 'პროდუქტი წარმატებით დაემატა!');
    }

    /**
     * Get search suggestions (used for frontend search, assuming it's in this controller).
     */
    public function getSuggestions(Request $request)
    {
        try {
            $query = $request->input('query');
            
            if (empty($query) || strlen($query) < 2) {
                return response()->json(['suggestions' => []]);
            }

            // ვეძებთ პროდუქტებს სახელით, კატეგორიით და ქვეკატეგორიით
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhereHas('category', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhere('sub_type', 'LIKE', "%{$query}%")
                ->with('category')
                ->limit(10)
                ->get();

            $suggestions = $products->map(function ($product) {
                return [
                    'name' => $product->name,
                    'category' => $product->category ? $product->category->name : null,
                    'price' => $product->price,
                    'formatted_price' => number_format($product->price, 2) . ' ₾',
                    'image' => $product->image ? asset('storage/' . $product->image) : asset('images/no-image.jpg'),
                    'url' => route('products.show', $product->id), // თუ იყენებთ ID-ს URL-ში
                    'type' => 'product'
                ];
            });

            // თუ პროდუქტები არ მოიძებნა, შევეცადოთ მხოლოდ კატეგორიებით
            if ($suggestions->isEmpty()) {
                $categories = Category::where('name', 'LIKE', "%{$query}%")
                    ->limit(5)
                    ->get();

                $suggestions = $categories->map(function ($category) {
                    return [
                        'name' => $category->name,
                        'category' => 'კატეგორია',
                        'price' => null,
                        'formatted_price' => null,
                        'image' => $category->image ? asset('storage/' . $category->image) : asset('images/category-default.jpg'),
                        'url' => route('categories.show', $category->id), // თუ იყენებთ ID-ს URL-ში
                        'type' => 'category'
                    ];
                });
            }

            return response()->json(['suggestions' => $suggestions]);

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
            'price'             => 'required|numeric|min:0', // დავამატე min:0
            'supplier_country'  => 'required|string|max:255', // თუ Country code-ია, max:2 უნდა იყოს
            'condition'         => 'required|in:new,like_new,used',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_link'        => 'nullable|url|max:255', // დავამატე max:255
            'sub_type'          => 'nullable|string|max:255',
            // --- მახასიათებლების ვალიდაცია ---
            'features'          => 'nullable|array',
            'features.*.name'   => 'nullable|string|max:255',
            'features.*.value'  => 'nullable|string|max:255',
        ]);
    
        $product = Product::findOrFail($id);
    
        // 2. სურათის ატვირთვა/განახლება
        $imagePath = $product->image; // არსებული სურათის გზა
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image); // წაშალეთ ძველი სურათი
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // 3. მახასიათებლების დამუშავება
        $featuresToStore = [];
        if ($request->has('features') && is_array($request->input('features'))) {
            foreach ($request->input('features') as $feature) {
                if (!empty($feature['name']) && !empty($feature['value'])) {
                    $featuresToStore[] = [
                        'name' => $feature['name'],
                        'value' => $feature['value'],
                    ];
                }
            }
        }
        
        // 4. პროდუქტის განახლება
        $product->update([
            'category_id'      => $validatedData['category_id'],
            'name'             => $validatedData['name'],
            'description'      => $validatedData['description'],
            'price'            => $validatedData['price'],
            'supplier_country' => $validatedData['supplier_country'],
            'condition'        => $validatedData['condition'],
            'video_link'       => $validatedData['video_link'],
            'sub_type'         => $validatedData['sub_type'],
            'image'            => $imagePath, // განახლებული სურათის გზა
            'features'         => $featuresToStore, // განახლებული მახასიათებლების შენახვა
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
        $product->increment('views_count'); // ვვარაუდობ, რომ გაქვთ views_count ველი
        
        // Get similar products from the same category (limit to 10)
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // Exclude current product
            ->latest() // Get newer products first
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
                  ->orWhere('sub_type', 'LIKE', "%{$query}%"); // დამატებულია sub_type-ზე ძებნა
            })
            ->when($minPrice, function ($q) use ($minPrice) {
                return $q->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function ($q) use ($maxPrice) {
                return $q->where('price', '<=', $maxPrice);
            })
            ->when($country, function ($q) use ($country) {
                return $q->where('supplier_country', $country);
            })
            ->when($condition, function ($q) use ($condition) {
                return $q->where('condition', $condition);
            })
            ->with('category')
            ->orderBy('price', $sort)
            ->get();
    
        $countries = Product::select('supplier_country')->distinct()->pluck('supplier_country');
    
        return view('products.search_results', compact('products', 'query', 'sort', 'minPrice', 'maxPrice', 'country', 'condition', 'countries'));
    }

    /**
     * Reset product views.
     * (Assuming this is an admin function)
     */
    public function resetViews()
    {
        Product::query()->update(['views_count' => 0]); // დარწმუნდით, რომ views_count არის სწორი ველი
        return redirect()->route('admin.products.index')->with('success', 'ყველა პროდუქტის ნახვა განულებულია!');
    }
}