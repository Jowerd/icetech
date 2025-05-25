<?php

namespace App\Http\Controllers;
use App\Models\BlogPost;


use Illuminate\Http\Request;

// app/Http/Controllers/BlogController.php
class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->paginate(6);
        return view('blog.index', compact('posts'));
    }

    public function show(BlogPost $blogPost)
    {
        return view('blog.show', compact('blogPost'));
    }
}
