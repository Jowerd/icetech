<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
// app/Models/BlogPost.php
class BlogPost extends Model
{
    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'image'];

    public function getRouteKeyName()
    {
        return 'slug';
    }


        // ✅ დაამატეთ ეს ფუნქცია კლასის ბოლოში
    public function toSitemapTag(): Url | string | array
    {
        // ყურადღება: 'blog.show' უნდა შეესაბამებოდეს თქვენი მარშრუტის (route) სახელს.
        // თუ სხვა სახელი ჰქვია, შეცვალეთ.
        return Url::create(route('blog.show', $this)) 
            ->setLastModificationDate($this->updated_at)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.8);
    }
}

