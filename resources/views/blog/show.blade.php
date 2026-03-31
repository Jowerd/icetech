@extends('layouts.app')
@section('title', $blogPost->title . ' • ICETECH')

@php
    use Illuminate\Support\Str;
    $blog_meta_desc = $blogPost->excerpt ?? Str::limit(strip_tags($blogPost->content), 160);
    $blog_image_url = $blogPost->image ? url('storage/' . $blogPost->image) : url('images/icetech-og-image.jpg');
@endphp

@section('meta_description', $blog_meta_desc)
@section('og_title', $blogPost->title . ' • ICETECH')
@section('og_description', $blog_meta_desc)
@section('og_image', $blog_image_url)
@section('twitter_title', $blogPost->title . ' • ICETECH')
@section('twitter_description', $blog_meta_desc)
@section('twitter_image', $blog_image_url)

@push('meta')
    <meta property="og:type" content="article" />
    <meta property="article:published_time" content="{{ $blogPost->created_at->toIso8601String() }}" />
    <meta property="article:modified_time" content="{{ $blogPost->updated_at->toIso8601String() }}" />
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Article",
        "headline": "{{ $blogPost->title }}",
        "description": "{{ $blog_meta_desc }}",
        "image": "{{ $blog_image_url }}",
        "datePublished": "{{ $blogPost->created_at->toIso8601String() }}",
        "dateModified": "{{ $blogPost->updated_at->toIso8601String() }}",
        "author": {
            "@type": "Organization",
            "name": "ICETECH"
        },
        "publisher": {
            "@type": "Organization",
            "name": "ICETECH",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ url('favicon.svg') }}"
            }
        },
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ url()->current() }}"
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "მთავარი", "item": "{{ url('/') }}"},
            {"@type": "ListItem", "position": 2, "name": "ბლოგი", "item": "{{ route('blog.index') }}"},
            {"@type": "ListItem", "position": 3, "name": "{{ $blogPost->title }}", "item": "{{ url()->current() }}"}
        ]
    }
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/layout.css?v=' . filemtime(public_path('css/layout.css'))) }}">
    <style>
        .blog-post-container {
            padding: 40px 0;
        }

        .blog-post-title {
            font-size: 28px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 15px;
        }

        .blog-post-meta {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 25px;
        }

        .blog-post-img {
            border-radius: 12px;
            overflow: hidden;
            max-height: 400px;
            object-fit: cover;
            margin-bottom: 30px;
            width: 100%;
        }

        .blog-content {
            font-size: 16px;
            line-height: 1.8;
            color: #343a40;
        }

        .blog-content p {
            margin-bottom: 1.2rem;
        }

        .back-link {
            display: inline-block;
            margin-top: 40px;
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 14px;
            border: 1px solid #00a4bd;
            color: #00a4bd;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-link:hover {
            background-color: #00a4bd;
            color: #fff;
        }

        @media (max-width: 576px) {
            .blog-post-title {
                font-size: 22px;
            }
        }
    </style>
@endpush

@section('content')
<div class="container blog-post-container">
    <h2 class="blog-post-title">{{ $blogPost->title }}</h2>

    <div class="blog-post-meta">
        <small>გამოქვეყნდა: {{ $blogPost->created_at->format('d.m.Y') }}</small>
    </div>

    @if($blogPost->image)
        <img src="{{ asset('storage/' . $blogPost->image) }}" alt="{{ $blogPost->title }}" class="img-fluid blog-post-img">
    @endif

    <div class="blog-content">
        {!! nl2br(e($blogPost->content)) !!}
    </div>

    <a href="{{ route('blog.index') }}" class="back-link">← დაბრუნება ბლოგში</a>
</div>
@endsection
