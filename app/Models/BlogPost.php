<?php

namespace App\Models;

use App\Jobs\RegenerateSitemap;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;

// ✅ დამატებულია "implements Sitemapable"
class BlogPost extends Model implements Sitemapable
{
    // ✅ დამატებულია HasFactory, რომელიც Laravel-ის სტანდარტია
    use HasFactory;

    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'image'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            RegenerateSitemap::dispatch();
        });

        static::deleted(function () {
            RegenerateSitemap::dispatch();
        });
    }

    // ✅ გასწორებული ფუნქცია საიტის რუკისთვის
    public function toSitemapTag(): Url | string | array
    {
        // ყურადღება: 'blog.show' უნდა შეესაბამებოდეს თქვენი მარშრუტის (route) სახელს.
        // route() ფუნქციას გადავეცით $this, რაც getRouteKeyName() მეთოდის გამო სწორად იმუშავებს,
        // რადგან ის ავტომატურად გამოიყენებს 'slug'-ს.
        return Url::create(route('blog.show', $this))
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.8);
    }
}

