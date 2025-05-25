<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.products', compact('products'));
    }

    public function create()
    {
        $categories = Category::all(); // რომ კატეგორიები გამოჩნდეს dropdown-ში
        return view('admin.products_create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric',
            'supplier_country'  => 'required|string|max:255',
            'condition'         => 'required|in:new,like_new,used',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_link'        => 'nullable|url',
            'sub_type'          => 'nullable|string|max:255', // <- დაამატე sub_type
        ]);
    
        $data = $request->only([
            'category_id', 'name', 'description', 'price', 
            'supplier_country', 'condition', 'video_link', 'sub_type' // <- sub_type დამატებულია
        ]);
    
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }
    
        Product::create($data);
    
        return redirect()->route('admin.products.index')->with('success', 'პროდუქტი წარმატებით დაემატა!');
    }
    

    
public function getSuggestions(Request $request)
{
    try {
        $query = $request->input('query');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        // ვეძებთ პროდუქტებს სახელით და კატეგორიით
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
                'url' => route('products.show', $product->slug),
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
                    'url' => route('categories.show', $category->slug),
                    'type' => 'category'
                ];
            });
        }

        return response()->json(['suggestions' => $suggestions]);

    } catch (\Exception $e) {
        \Log::error('Search suggestions error: ' . $e->getMessage());
        return response()->json(['suggestions' => []], 500);
    }
}
    
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all(); // რომ შესაძლებელია კატეგორიის შეცვლა
        return view('admin.products_edit', compact('product', 'categories'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric',
            'supplier_country'  => 'required|string|max:255',
            'condition'         => 'required|in:new,like_new,used',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_link'        => 'nullable|url',
            'sub_type'          => 'nullable|string|max:255', // <- აქაც დაამატე
        ]);
    
        $product = Product::findOrFail($id);
    
        $data = $request->only([
            'category_id', 'name', 'description', 'price', 
            'supplier_country', 'condition', 'video_link', 'sub_type' // <- sub_type დამატებულია
        ]);
    
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }
    
        $product->update($data);
    
        return redirect()->route('admin.products.index')->with('success', 'პროდუქტი წარმატებით განახლდა!');
    }
    
    
    
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
    
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
    
        $product->delete();
    
        return redirect()->route('admin.products.index')->with('success', 'პროდუქტი წარმატებით წაიშალა!');
    }
    
    public function show(Product $product)
    {
        $product->increment('views_count');
        
        // Get similar products from the same category (limit to 10)
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // Exclude current product
            ->latest() // Get newer products first
            ->limit(10)
            ->get();
        
        return view('product_show', compact('product', 'similarProducts'));
    }
    

    
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
                  });
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
}
