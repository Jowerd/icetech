@extends('layouts.app')
@section('title', 'ბლოგი')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/layout.css?v=' . filemtime(public_path('css/layout.css'))) }}">
    <style>
        .blog-section {
            padding: 40px 0;
        }

        .blog-header {
            font-size: 28px;
            font-weight: 700;
            color: #212529;
            margin-bottom: 30px;
            border-left: 5px solid #00a4bd;
            padding-left: 15px;
        }

        .blog-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .blog-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        }

        .blog-card img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .blog-card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .blog-card-body h5 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #212529;
        }

        .blog-card-body p {
            font-size: 15px;
            color: #6c757d;
            flex-grow: 1;
        }

        .blog-card-body .btn {
            align-self: flex-start;
            margin-top: 20px;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 14px;
            background-color: transparent;
            border: 1px solid #00a4bd;
            color: #00a4bd;
            transition: all 0.3s ease;
        }

        .blog-card-body .btn:hover {
            background-color: #00a4bd;
            color: #fff;
        }

        .pagination {
            justify-content: center;
        }

        @media (max-width: 576px) {
            .blog-header {
                font-size: 24px;
            }
        }
    </style>
@endpush

@section('content')
@include('partials.breadcrumb', ['crumbs' => [
    ['label' => 'პროფესიონალური რჩევები', 'url' => '']
]])
<div class="blog-section">
    <h2 class="blog-header">პროფესიონალური რჩევები</h2>

    @if($posts->isEmpty())
        <p class="text-muted">ჯერჯერობით რჩევები არ არის დამატებული.</p>
    @else
        <div class="row">
            @foreach($posts as $post)
                @php
                    preg_match_all('/\S+/u', strip_tags($post->content), $_bm);
                    $mins = max(1, (int) ceil(count($_bm[0]) / 180));
                @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-decoration-none">
                    <div class="blog-card">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" loading="lazy">
                        @endif
                        <div class="blog-card-body">
                            <div class="d-flex align-items-center gap-3 mb-2" style="font-size:0.78rem;color:#94a3b8;">
                                <span><i class="bi bi-calendar3 me-1"></i>{{ $post->created_at->format('d.m.Y') }}</span>
                                <span><i class="bi bi-clock me-1"></i>{{ $mins }} წთ</span>
                            </div>
                            <h5>{{ $post->title }}</h5>
                            <p>{{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}</p>
                            <span class="btn">სრულად ნახვა</span>
                        </div>
                    </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
