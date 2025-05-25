<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/BlogPost.php
class BlogPost extends Model
{
    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'image'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}

