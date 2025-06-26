<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'supplier_country',
        'condition',
        'video_link',
        'slug',
        'sub_type',
        'features', // ✅ დარწმუნდით, რომ 'features' აქაც არის დამატებული!
    ];

    // --- ეს არის ყველაზე მნიშვნელოვანი ცვლილება ---
    protected $casts = [
        'features' => 'array',
    ];
    // --- დასასრული მნიშვნელოვანი ცვლილების ---

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            $product->slug = Str::slug($product->name);
            if ($product->sub_type) {
                $product->sub_type_slug = Str::slug($product->sub_type);
            }
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
}