<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FixMissingSlugs extends Command
{
    protected $signature   = 'fix:slugs';
    protected $description = 'Generate slugs for categories and products that are missing them';

    public function handle(): int
    {
        // Categories
        $categories = Category::whereNull('slug')->orWhere('slug', '')->get(['id', 'name']);
        $this->info("Categories without slug: {$categories->count()}");
        foreach ($categories as $cat) {
            $slug = $this->uniqueSlug(Str::slug($cat->name), 'categories', $cat->id);
            $cat->update(['slug' => $slug]);
            $this->line("  → {$cat->name}  =>  {$slug}");
        }

        // Products
        $products = Product::whereNull('slug')->orWhere('slug', '')->get(['id', 'name']);
        $this->info("Products without slug: {$products->count()}");
        foreach ($products as $product) {
            $slug = $this->uniqueSlug(Str::slug($product->name), 'products', $product->id);
            $product->update(['slug' => $slug]);
            $this->line("  → {$product->name}  =>  {$slug}");
        }

        $this->info('Done!');
        return self::SUCCESS;
    }

    private function uniqueSlug(string $base, string $table, int $currentId): string
    {
        if (!$base) {
            $base = $table . '-' . $currentId;
        }

        $slug = $base;
        $i    = 1;

        while (
            \DB::table($table)
                ->where('slug', $slug)
                ->where('id', '!=', $currentId)
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
