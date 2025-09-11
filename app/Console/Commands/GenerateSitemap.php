<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

// დარწმუნდი, რომ აქ სწორად არის მითითებული შენი მოდელები
use App\Models\Category;
use App\Models\Product;
use App\Models\Post;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.';

    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = SitemapGenerator::create(config('app.url'))
            ->getSitemap();
        
        // სტატიკური გვერდები
        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create('/products')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        $sitemap->add(Url::create('/about')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        $sitemap->add(Url::create('/contact')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        $sitemap->add(Url::create('/blog')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // დინამიური გვერდები (პროდუქტები, კატეგორიები, პოსტები)
        Category::all()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(Url::create("/categories/{$category->slug}")->setPriority(0.8));
        });

        Product::all()->each(function (Product $product) use ($sitemap) {
            $sitemap->add(Url::create("/products/{$product->slug}")->setPriority(0.8)->setLastModificationDate($product->updated_at));
        });

        Post::all()->each(function (Post $post) use ($sitemap) {
            $sitemap->add(Url::create("/blog/{$post->slug}")->setPriority(0.8)->setLastModificationDate($post->updated_at));
        });

        // sitemap-ის შენახვა public დირექტორიაში
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}