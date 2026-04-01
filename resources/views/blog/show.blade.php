@extends('layouts.app')

@php
    use Illuminate\Support\Str;
    $blog_meta_desc   = $blogPost->excerpt ?? Str::limit(strip_tags($blogPost->content), 160);
    $blog_image_url   = $blogPost->image ? url('storage/' . $blogPost->image) : url('images/icetech-og-image.jpg');
    $word_count       = preg_match_all('/\S+/u', strip_tags($blogPost->content), $_m) ? $_m[0] : [];
    $word_count       = count($word_count);
    $reading_minutes  = max(1, (int) ceil($word_count / 180));
    $related          = \App\Models\BlogPost::where('id', '!=', $blogPost->id)->latest()->take(3)->get();
@endphp

@section('title', $blogPost->title . ' • ICETECH')
@section('meta_description', $blog_meta_desc)
@section('og_title',         $blogPost->title . ' • ICETECH')
@section('og_description',   $blog_meta_desc)
@section('og_image',         $blog_image_url)
@section('twitter_title',    $blogPost->title . ' • ICETECH')
@section('twitter_description', $blog_meta_desc)
@section('twitter_image',    $blog_image_url)

@push('meta')
    <meta property="og:type" content="article" />
    <meta property="article:published_time" content="{{ $blogPost->created_at->toIso8601String() }}" />
    <meta property="article:modified_time"  content="{{ $blogPost->updated_at->toIso8601String() }}" />
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "{{ addslashes($blogPost->title) }}",
        "description": "{{ addslashes($blog_meta_desc) }}",
        "image": "{{ $blog_image_url }}",
        "datePublished": "{{ $blogPost->created_at->toIso8601String() }}",
        "dateModified": "{{ $blogPost->updated_at->toIso8601String() }}",
        "wordCount": {{ (int) $word_count }},
        "timeRequired": "PT{{ $reading_minutes }}M",
        "inLanguage": "ka",
        "author": {
            "@type": "Organization",
            "name": "ICETECH",
            "url": "{{ url('/') }}"
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
            {"@type": "ListItem", "position": 1, "name": "მთავარი",               "item": "{{ url('/') }}"},
            {"@type": "ListItem", "position": 2, "name": "პროფესიონალური რჩევები","item": "{{ route('blog.index') }}"},
            {"@type": "ListItem", "position": 3, "name": "{{ addslashes($blogPost->title) }}", "item": "{{ url()->current() }}"}
        ]
    }
    </script>
@endpush

@section('full_width', true)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/layout.css?v=' . filemtime(public_path('css/layout.css'))) }}">
    <style>
        /* ===== Hero ===== */
        .article-hero {
            position: relative;
            width: 100%;
            min-height: 380px;
            max-height: 520px;
            overflow: hidden;
            display: flex;
            align-items: flex-end;
            background: #1a365d;
        }

        .article-hero img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .article-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.65) 100%);
        }

        .article-hero-content {
            position: relative;
            z-index: 2;
            width: 100%;
            padding: 40px 5% 36px;
            text-align: left;
        }

        .article-hero-content h1 {
            color: #fff;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 14px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.4);
            text-align: left;
        }

        .article-hero-meta {
            display: flex;
            align-items: center;
            gap: 18px;
            color: rgba(255,255,255,0.85);
            font-size: 0.85rem;
            text-align: left;
        }

        .article-hero-meta i { color: #00a4bd; }

        /* no-image hero */
        .article-hero-plain {
            background: linear-gradient(135deg, #1a365d 0%, #00a4bd 100%);
            min-height: 220px;
        }

        /* ===== Article body ===== */
        .article-wrap {
            padding: 44px 0 72px;
            text-align: left;
        }

        /* breadcrumb inside article */
        .article-wrap .icetech-breadcrumb {
            margin-bottom: 0;
        }

        /* ===== Typography ===== */
        .article-body {
            font-size: 1.08rem;
            line-height: 1.9;
            color: #374151;
            text-align: left;
        }

        .article-body > p:first-child {
            font-size: 1.18rem;
            color: #1a365d;
            font-weight: 400;
            line-height: 1.8;
        }

        .article-body h2 {
            font-size: 1.45rem;
            font-weight: 700;
            color: #1a365d;
            margin: 2.4rem 0 0.9rem;
            padding-left: 14px;
            border-left: 4px solid #00a4bd;
            line-height: 1.3;
        }

        .article-body h3 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e3a5f;
            margin: 2rem 0 0.6rem;
        }

        .article-body p {
            margin-bottom: 1.4rem;
        }

        .article-body ul, .article-body ol {
            margin-bottom: 1.4rem;
            padding-left: 1.6rem;
        }

        .article-body li {
            margin-bottom: 0.5rem;
            line-height: 1.7;
        }

        .article-body img {
            max-width: 100%;
            border-radius: 12px;
            margin: 1.6rem 0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        .article-body a {
            color: #00a4bd;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .article-body blockquote {
            border-left: 4px solid #00a4bd;
            margin: 2rem 0;
            padding: 16px 24px;
            background: #f0f9ff;
            border-radius: 0 10px 10px 0;
            color: #1a365d;
            font-size: 1.08rem;
            font-style: italic;
            line-height: 1.7;
        }

        .article-body strong {
            color: #1a365d;
            font-weight: 700;
        }

        .article-body table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.4rem;
            font-size: 0.95rem;
        }

        .article-body th {
            background: #1a365d;
            color: #fff;
            padding: 10px 14px;
            text-align: left;
        }

        .article-body td {
            padding: 9px 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        .article-body tr:nth-child(even) td { background: #f8fafc; }

        /* ===== Divider ===== */
        .article-divider {
            border: none;
            border-top: 2px solid #e2e8f0;
            margin: 40px 0;
        }

        /* ===== Share ===== */
        .share-section {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .share-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .share-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .share-btn-fb   { background: #1877f2; color: #fff; }
        .share-btn-fb:hover { background: #1464d8; color: #fff; }
        .share-btn-copy { background: #f1f5f9; color: #1a365d; }
        .share-btn-copy:hover { background: #e2e8f0; }

        /* ===== Related ===== */
        .related-section h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a365d;
            margin-bottom: 20px;
            padding-left: 14px;
            border-left: 4px solid #00a4bd;
        }

        .related-card {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            padding: 14px;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            text-decoration: none;
            color: inherit;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .related-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            color: inherit;
        }

        .related-card-img {
            width: 72px;
            height: 56px;
            object-fit: cover;
            border-radius: 8px;
            flex-shrink: 0;
            background: #e2e8f0;
        }

        .related-card-title {
            font-size: 0.88rem;
            font-weight: 600;
            color: #1a365d;
            line-height: 1.35;
            margin-bottom: 4px;
        }

        .related-card-date {
            font-size: 0.76rem;
            color: #94a3b8;
        }

        /* back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.88rem;
            font-weight: 600;
            border: 2px solid #00a4bd;
            color: #00a4bd;
            text-decoration: none;
            transition: all 0.2s;
        }

        .back-link:hover {
            background: #00a4bd;
            color: #fff;
        }

        @media (max-width: 768px) {
            .article-hero-content h1 { font-size: 1.4rem; }
            .article-hero-content { padding: 28px 4% 24px; }
            .article-wrap { padding: 28px 0 48px; }
            .article-body { font-size: 1rem; line-height: 1.8; }
            .article-body > p:first-child { font-size: 1.05rem; }
        }
    </style>
@endpush

@section('content')

{{-- ===== Hero ===== --}}
<header class="article-hero {{ !$blogPost->image ? 'article-hero-plain' : '' }}">
    @if($blogPost->image)
        <img src="{{ asset('storage/' . $blogPost->image) }}"
             alt="{{ $blogPost->title }}"
             fetchpriority="high">
        <div class="article-hero-overlay"></div>
    @endif
    <div class="article-hero-content">
        <h1>{{ $blogPost->title }}</h1>
        <div class="article-hero-meta">
            <span><i class="bi bi-calendar3"></i> {{ $blogPost->created_at->format('d.m.Y') }}</span>
            <span><i class="bi bi-clock"></i> {{ $reading_minutes }} წთ წასაკითხად</span>
        </div>
    </div>
</header>

{{-- ===== Article body ===== --}}
<div class="container">
<div class="article-wrap">

    @include('partials.breadcrumb', ['crumbs' => [
        ['label' => 'პროფესიონალური რჩევები', 'url' => route('blog.index')],
        ['label' => Str::limit($blogPost->title, 50), 'url' => '']
    ]])

    <article class="article-body mt-4">
        {!! $blogPost->content !!}
    </article>

    <hr class="article-divider">

    {{-- Share --}}
    <div class="share-section mb-5">
        <span class="share-label">გაზიარება:</span>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
           target="_blank" rel="noopener noreferrer"
           class="share-btn share-btn-fb">
            <i class="bi bi-facebook"></i> Facebook
        </a>
        <button class="share-btn share-btn-copy" onclick="copyArticleLink(this)">
            <i class="bi bi-link-45deg"></i> ლინკის კოპირება
        </button>
    </div>

    {{-- Back --}}
    <a href="{{ route('blog.index') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> ყველა სტატია
    </a>

    {{-- Related --}}
    @if($related->isNotEmpty())
    <hr class="article-divider">
    <div class="related-section">
        <h3>სხვა სტატიები</h3>
        <div class="row g-3">
            @foreach($related as $post)
            <div class="col-12 col-md-4">
                <a href="{{ route('blog.show', $post->slug) }}" class="related-card">
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}"
                             alt="{{ $post->title }}"
                             class="related-card-img"
                             loading="lazy">
                    @else
                        <div class="related-card-img" style="background: linear-gradient(135deg,#1a365d,#00a4bd);"></div>
                    @endif
                    <div>
                        <p class="related-card-title">{{ Str::limit($post->title, 70) }}</p>
                        <span class="related-card-date"><i class="bi bi-calendar3 me-1"></i>{{ $post->created_at->format('d.m.Y') }}</span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
</div>

@endsection

@push('scripts')
<script>
function copyArticleLink(btn) {
    navigator.clipboard.writeText(window.location.href).then(() => {
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check2"></i> კოპირდა!';
        btn.style.background = '#d1fae5';
        btn.style.color = '#065f46';
        setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.style.color = ''; }, 2000);
    });
}
</script>
@endpush
