@extends('layouts.app')

@section('title', 'კატეგორიები • ICETECH')

@section('meta_description', 'ICETECH-ის სრული კატეგორიების ჩამონათვალი - პროფესიონალური სამზარეულოს აღჭურვილობა, გასაცივებელი სისტემები და კომერციული მოწყობილობები საქართველოში.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/layout.css?v=' . filemtime(public_path('css/layout.css'))) }}">
    <link rel="stylesheet" href="{{ asset('css/categories.css?v=' . filemtime(public_path('css/categories.css'))) }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="products-page-title">კატეგორიები</h1>
            <p class="products-page-sub">აირჩიეთ კატეგორია სასურველი პროდუქციის სანახავად</p>
        </div>
    </div>
    
    <div class="row g-3 g-md-4">
        @foreach(\App\Models\Category::all() as $category)
            <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                <a href="{{ route('category.products', $category->slug) }}" class="category-link">
                    <div class="category-card">
                        <div class="category-image-wrapper">
                            <div class="category-image-container">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="category-image" loading="lazy">
                                @else
                                    <img src="{{ asset('default-category.png') }}" alt="Default Image" class="category-image opacity-50" loading="lazy">
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
</div>
@endsection