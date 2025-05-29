@extends('layouts.app')

@section('title', 'კატეგორიები • ICETECH')

@section('meta_description', 'ICETECH-ის სრული კატეგორიების ჩამონათვალი - პროფესიონალური სამზარეულოს აღჭურვილობა, გასაცივებელი სისტემები და კომერციული მოწყობილობები საქართველოში.')

@section('meta_keywords', 'კატეგორიები, სამზარეულო აღჭურვილობა, მაცივრები, გასაცივებელი სისტემები, კომერციული ტექნიკა, რესტორნის აღჭურვილობა, კაფეს მოწყობილობები, საქართველო')

@section('og_title', 'კატეგორიები • ICETECH - პროფესიონალური აღჭურვილობა')
@section('og_description', 'გაეცანით ICETECH-ის პროდუქციის სრულ კატეგორიებს - მაღალი ხარისხის სამზარეულოსა და გასაცივებელი აღჭურვილობა ბიზნესებისთვის საქართველოში.')
@section('og_image', asset('images/categories-page.jpg'))

@section('twitter_title', 'კატეგორიები • ICETECH - პროფესიონალური აღჭურვილობა')
@section('twitter_description', 'გაეცანით ICETECH-ის პროდუქციის სრულ კატეგორიებს - მაღალი ხარისხის სამზარეულოსა და გასაცივებელი აღჭურვილობა ბიზნესებისთვის საქართველოში.')
@section('twitter_image', asset('images/categories-page.jpg'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/categories.css') }}">
@endpush

@section('content')
    <!-- კატეგორიების სათაური -->
    <div class="row">
        <div class="col-12">
            <h2 class="text-uppercase mb-4">კატეგორიები</h2>
        </div>
    </div>
    
    <!-- კატეგორიების ქარდები -->
    <div class="row g-4">
        @foreach(\App\Models\Category::all() as $category)
            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                <a href="{{ route('category.products', $category->slug) }}" class="text-decoration-none category-link">
                    <div class="category-card">
                        <div class="category-image-wrapper">
                            <div class="category-image-container">
@if($category->image)
    <img 
        src="{{ asset('storage/' . $category->image) }}" 
        alt="{{ $category->name }}" 
        class="category-image" 
        loading="lazy"
        width="102" height="102"
    >
@else
    <img 
        src="{{ asset('default-category.png') }}" 
        alt="Default Image" 
        class="category-image" 
        loading="lazy"
        width="102" height="102"
    >
@endif

                            </div>
                        </div>
                        <div class="category-content">
                            <h5 class="category-title">{{ $category->name }}</h5>
                            <div class="view-more">
                                <span>ნახვა</span>
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection