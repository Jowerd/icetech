<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/webp',
        ]);
    
        $data = $request->only('name', 'description', 'keywords');
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('category_images', 'public');
            $data['image'] = $imagePath;
        }
    
        Category::create($data);
    
        return redirect()->route(route: 'admin.categories.index');
    }
    

    public function show(Request $request, Category $category)
    {
        // Create subtype slugs mapping
        $subTypes = [];
        foreach ($category->products->pluck('sub_type')->unique()->filter() as $subType) {
            $subTypes[Str::slug($subType)] = $subType;
        }
        
        // Find original subtype value if a slug is provided
        $originalSubType = null;
        if ($request->sub_type && !empty($request->sub_type)) {
            foreach ($subTypes as $slug => $value) {
                if ($slug === $request->sub_type) {
                    $originalSubType = $value;
                    break;
                }
            }
        }
        
        // Filter products
        $products = $category->products()
            ->when($request->min_price, fn($q) => $q->where('price', '>=', $request->min_price))
            ->when($request->max_price, fn($q) => $q->where('price', '<=', $request->max_price))
            ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
            ->when($request->country, fn($q) => $q->where('supplier_country', $request->country))
            ->when($originalSubType, fn($q) => $q->where('sub_type', $originalSubType)) // Now using originalSubType
            ->orderBy('price', $request->sort ?? 'asc')
            ->get();
    
        $countries = Product::distinct()->pluck('supplier_country')->filter();
    
        return view('category_products', data: compact('category', 'products', 'countries', 'subTypes'));
    }
    
    // Helper function to slugify text (optional)
    private function slugify($text) {
        return md5($text);
    }
    
    
    
    
    
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories_edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,webp,jpg,gif|max:2048',
        ]);

        $category = Category::findOrFail($id);

        $data = $request->only('name', 'description', 'keywords');

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('category_images', 'public');
            $data['image'] = $imagePath;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index');
    }
}