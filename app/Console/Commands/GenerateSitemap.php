<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;
use App\Models\Category;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap for the application.';

    public function handle()
    {
        $this->info('Generating sitemap...');

        // ვიწყებთ Sitemap-ის შექმნას
        Sitemap::create()
            // 1. ვამატებთ მთავარ გვერდს
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            
            // 2. ვამატებთ პროდუქტების და კატეგორიების ყველა გვერდს ავტომატურად
            ->add(Product::all())
            ->add(Category::all())

            // 3. ვინახავთ ფაილს
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}