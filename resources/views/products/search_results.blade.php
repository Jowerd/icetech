@extends('layouts.app')
@section('title', $query . ' • ICETECH')

@section('content')
    @include('partials.breadcrumb', ['crumbs' => [
        ['label' => 'ძიება: ' . $query, 'url' => '']
    ]])

    <div class="py-3">
        <div class="row g-4">
            @include('partials.search.body')
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/category.css?v=' . filemtime(public_path('css/category.css'))) }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css?v=' . filemtime(public_path('css/layout.css'))) }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/category.js?v=' . filemtime(public_path('js/category.js'))) }}"></script>
@endpush
