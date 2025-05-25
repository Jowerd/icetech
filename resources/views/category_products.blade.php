@extends('layouts.app')

@section('title', $category->name . ' • ICETECH')
@section('meta_description', $category->description)
@section('meta_keywords', $category->name . ', ICETECH, ' . $category->name . ' საქართველოში, კომერციული აღჭურვილობა, პროფესიონალური ტექნიკა, ' . $category->keywords)

{{-- Open Graph და Twitter Card მეტა ტეგები --}}
@section('og_title', $category->name . ' • ICETECH')
@section('og_description', $category->description)
@section('og_image', $category->image ? asset('storage/' . $category->image) : asset('images/icetech-og-image.jpg'))
@section('twitter_title', $category->name . ' • ICETECH')
@section('twitter_description', $category->description)
@section('twitter_image', $category->image ? asset('storage/' . $category->image) : asset('images/icetech-twitter-image.jpg'))

@push('meta')
    {{-- Open Graph დამატებითი მეტა ტეგები --}}
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="ICETECH" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $category->name }} • ICETECH" />
    <meta property="og:description" content="{{ $category->description }}" />
    <meta property="og:image" content="{{ $category->image ? asset('storage/' . $category->image) : asset('images/icetech-og-image.jpg') }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:alt" content="{{ $category->name }}" />
    
    {{-- Twitter Card დამატებითი მეტა ტეგები --}}
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ url()->current() }}" />
    <meta name="twitter:title" content="{{ $category->name }} • ICETECH" />
    <meta name="twitter:description" content="{{ $category->description }}" />
    <meta name="twitter:image" content="{{ $category->image ? asset('storage/' . $category->image) : asset('images/icetech-twitter-image.jpg') }}" />
    <meta name="twitter:image:alt" content="{{ $category->name }}" />

    {{-- პროდუქტის გამოსახულებებისთვის მეტა ტეგები, თუ კატეგორიას აქვს პროდუქტები --}}
    @if($category->products->count() > 0 && $category->products->first()->image)
        {{-- დავამატოთ პირველი პროდუქტის სურათი როგორც დამატებითი OG სურათი --}}
        <meta property="og:image" content="{{ asset('storage/' . $category->products->first()->image) }}" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
        <meta property="og:image:type" content="image/jpeg" />
        <meta property="og:image:alt" content="{{ $category->products->first()->name }}" />
        
        {{-- დავამატოთ ეს Twitter Card-შიც --}}
        <meta name="twitter:image" content="{{ asset('storage/' . $category->products->first()->image) }}" />
        <meta name="twitter:image:alt" content="{{ $category->products->first()->name }}" />
    @endif

    {{-- დამატებითი სტრუქტურირებული მონაცემები კატეგორიისთვის --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollectionPage",
        "name": "{{ $category->name }} • ICETECH",
        "description": "{{ $category->description }}",
        "url": "{{ url()->current() }}",
        "image": "{{ $category->image ? asset('storage/' . $category->image) : asset('images/icetech-og-image.jpg') }}",
        "mainEntity": {
            "@type": "ItemList",
            "itemListElement": [
                @if($category->products->count() > 0)
                    @foreach($category->products as $index => $product)
                        {
                            "@type": "ListItem",
                            "position": {{ $loop->iteration }},
                            "name": "{{ $product->name }}",
                            "url": "{{ route('products.show', $product->slug) }}",
                            "image": "{{ $product->image ? asset('storage/' . $product->image) : asset('images/product-placeholder.jpg') }}"
                        }@if(!$loop->last),@endif
                    @endforeach
                @endif
            ]
        }
    }
    </script>
@endpush

@section('content')
    <div class="category-container">
        {{-- კატეგორიის სათაური --}}
        <div class="category-header">
            <h1>{{ $category->name }}</h1>
            {{-- აღწერა წაშლილია, რადგან არ გვინდა რომ გამოჩნდეს გვერდზე --}}
        </div>
        
        <div class="row g-4">
            @include('partials.category.body')
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/category.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/category.js') }}"></script>
@endpush