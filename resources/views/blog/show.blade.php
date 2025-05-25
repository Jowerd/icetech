@extends('layouts.app')
@section('title', $blogPost->title)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
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
