<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Slide;
use App\Services\ImageService;
use Illuminate\Console\Command;

class ConvertImagesToWebp extends Command
{
    protected $signature   = 'images:convert-webp {--dry-run : Show what would be converted without doing it}';
    protected $description = 'Convert all existing stored images to WebP format';

    public function handle(ImageService $imageService): int
    {
        $dryRun = $this->option('dry-run');

        $models = [
            'Products'   => [Product::class,  'products'],
            'Categories' => [Category::class, 'category_images'],
            'Blog posts' => [BlogPost::class, 'blog'],
            'Slides'     => [Slide::class,    'slides'],
            'Reviews'    => [Review::class,   'reviews'],
        ];

        foreach ($models as $label => [$modelClass, $folder]) {
            $items = $modelClass::whereNotNull('image')
                ->where('image', 'not like', '%.webp')
                ->get(['id', 'image']);

            if ($items->isEmpty()) {
                $this->line("<fg=gray>$label: nothing to convert</>");
                continue;
            }

            $this->info("$label: {$items->count()} image(s) to convert");

            $bar = $this->output->createProgressBar($items->count());
            $bar->start();

            foreach ($items as $item) {
                if (!$dryRun) {
                    $newPath = $imageService->convertExistingToWebp($item->image);
                    if ($newPath !== $item->image) {
                        $item->update(['image' => $newPath]);
                    }
                }
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
        }

        $this->info($dryRun ? 'Dry run complete — no changes made.' : 'Done! All images converted to WebP.');

        return self::SUCCESS;
    }
}
