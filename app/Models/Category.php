<?php

namespace App\Models;

use App\Jobs\RegenerateSitemap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

// ✅ დამატებული ინტერფეისი: implements Sitemapable
class Category extends Model implements Sitemapable
{
    protected $fillable = ['name', 'image', 'slug', 'description', 'keywords'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::saved(function () {
            RegenerateSitemap::dispatch();
        });

        static::deleted(function () {
            RegenerateSitemap::dispatch();
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // ✅ დამატებული ფუნქცია საიტის რუკისთვის
    public function toSitemapTag(): Url | string | array
    {
        return Url::create(route('categories.show', $this))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.8);
    }
}