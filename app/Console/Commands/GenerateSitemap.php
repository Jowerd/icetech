<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;
use App\Models\Category;
use App\Models\BlogPost; // ✅ დაემატა BlogPost მოდელი

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap for the application.';

    public function handle()
    {
        $this->info('Generating sitemap...');

        // ვიწყებთ Sitemap-ის შექმნას
        Sitemap::create()
            // 1. მთავარი გვერდი
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            
            // --- თქვენი სტატიკური გვერდები ---
            ->add(Url::create('/products')->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
            ->add(Url::create('/about')->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY))
            ->add(Url::create('/contact')->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY))
            ->add(Url::create('/blog')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))


            // 2. დინამიური გვერდები
            ->add(Product::all())
            ->add(Category::all())
            ->add(BlogPost::all()) // ✅ დაემატა ბლოგის პოსტები

            // 3. ფაილში ჩაწერა
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}

