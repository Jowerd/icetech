<?php

namespace App\Models;

use App\Jobs\RegenerateSitemap;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

// ✅ დამატებული ინტერფეისი: implements Sitemapable
class Product extends Model implements Sitemapable
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'features_text',
        'price',
        'image',
        'supplier_country',
        'condition',
        'video_link',
        'slug',
        'sub_type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            $product->slug = Str::slug($product->name);
            if ($product->sub_type) {
                $product->sub_type_slug = Str::slug($product->sub_type);
            }
        });

        static::saved(function () {
            RegenerateSitemap::dispatch();
        });

        static::deleted(function () {
            RegenerateSitemap::dispatch();
        });
    }

    public function getVideoEmbedUrlAttribute()
    {
        if ($this->video_link && strpos($this->video_link, 'youtube.com/watch') !== false) {
            parse_str(parse_url($this->video_link, PHP_URL_QUERY), $query);
            if (isset($query['v'])) {
                return 'https://www.youtube.com/embed/' . $query['v'];
            }
        }
        return $this->video_link;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ✅ დამატებული ფუნქცია საიტის რუკისთვის
    public function toSitemapTag(): Url | string | array
    {
        return Url::create(route('products.show', $this))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.9);
    }
}