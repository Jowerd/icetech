<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

use App\Models\Category;
use App\Models\Product;
use App\Models\BlogPost;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.';

    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = SitemapGenerator::create(config('app.url'))
            ->getSitemap();
        
        // დატოვეთ მხოლოდ ეს ბლოკი, რომელიც შეიცავს სრულ ინფორმაციას
        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create('/products')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        $sitemap->add(Url::create('/about')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        $sitemap->add(Url::create('/contact')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        $sitemap->add(Url::create('/blog')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // დინამიური გვერდები (პროდუქტები, კატეგორიები, ბლოგის პოსტები)
        Category::all()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(Url::create("/categories/{$category->slug}")->setPriority(0.8));
        });

        Product::all()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(Url::create("/products/{$product->slug}")->setPriority(0.8)->setLastModificationDate($product->updated_at));
        });

        BlogPost::all()->each(function (BlogPost $post) use ($sitemap) {
            $sitemap->add(Url::create("/blog/{$post->slug}")->setPriority(0.8)->setLastModificationDate($post->updated_at));
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}