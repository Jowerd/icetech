<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'images'   => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $service   = new ImageService();
        $sortStart = $product->images()->max('sort_order') + 1;

        foreach ($request->file('images') as $i => $file) {
            $path = $service->storeAsWebp($file, 'products', 82);
            ProductImage::create([
                'product_id' => $product->id,
                'image'      => $path,
                'sort_order' => $sortStart + $i,
            ]);
        }

        return back()->with('success', 'ფოტოები წარმატებით დაემატა!');
    }

    public function destroy(Product $product, ProductImage $image)
    {
        abort_if($image->product_id !== $product->id, 403);

        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'ფოტო წაიშალა.');
    }
}
