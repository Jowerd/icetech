@extends('layouts.app')
@section('title', $query . ' • ICETECH')

@section('content')
    <div class="container-fluid px-0">
        <nav aria-label="breadcrumb" class="py-2" style="background: transparent;">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">მთავარი</a></li>
                <li class="breadcrumb-item active" aria-current="page">ძიება: {{ $query }}</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid py-3">
        <h1 class="mb-4 text-center fs-2 fw-bold py-2">ძიების შედეგები: "{{ $query }}"</h1>

        <div class="row g-4">
            @include('partials.search.body')
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
