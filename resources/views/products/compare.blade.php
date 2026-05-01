@extends('layouts.app')

@section('title', 'პროდუქტების შედარება • ICETECH')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/layout.css?v='  . filemtime(public_path('css/layout.css'))) }}">
    <link rel="stylesheet" href="{{ asset('css/compare.css?v=' . filemtime(public_path('css/compare.css'))) }}">
@endpush

@section('content')
@include('partials.breadcrumb', ['crumbs' => [
    ['label' => 'პროდუქცია', 'url' => route('products')],
    ['label' => 'შედარება',  'url' => ''],
]])

<div class="py-3 pb-5">

    <div class="cmp-page-header">
        <h1 class="compare-page-title mb-0">შედარება</h1>
        @if(!$products->isEmpty())
        <a href="{{ route('products') }}" class="btn-cmp-add">
            <i class="bi bi-plus-lg"></i>პროდუქტის დამატება
        </a>
        @endif
    </div>

    @if($products->isEmpty())
        <div class="compare-empty">
            <i class="bi bi-arrow-left-right"></i>
            <h4>შედარებისთვის პროდუქტები არ არის არჩეული</h4>
            <p class="text-muted">გადადით პროდუქციის გვერდზე და ქარდებზე დააჭირეთ შედარების ღილაკს</p>
            <a href="{{ route('products') }}" class="btn-cmp-empty">
                <i class="bi bi-grid me-1"></i>პროდუქციაზე გადასვლა
            </a>
        </div>
    @else

    @php
        $conditionMap = [
            'new'      => ['bg-success',        'ახალი'],
            'like_new' => ['bg-info text-dark',  'ახალივით'],
            'used'     => ['bg-secondary',       'მეორადი'],
        ];

        $allFeatures = collect();
        foreach ($products as $product) {
            if (!$product->features_text) continue;
            foreach (explode("\n", $product->features_text) as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2 && trim($parts[0])) {
                    $allFeatures->push(trim($parts[0]));
                }
            }
        }
        $featureKeys = $allFeatures->unique()->values();
    @endphp

    <div class="compare-wrap">

        {{-- პროდუქტის ბარათების რიგი --}}
        <div class="compare-cards-row">
            <div class="compare-label-col"></div>
            @foreach($products as $product)
            <div class="compare-product-col">
                <div class="cmp-card">
                    <button class="cmp-card-remove" onclick="compareRemoveOnPage({{ $product->id }})" title="ამოღება">
                        <i class="bi bi-x-lg"></i>
                    </button>
                    <a href="{{ route('products.show', $product->slug) }}" class="cmp-card-img-wrap">
                        <img
                            src="{{ $product->image ? asset('storage/'.$product->image) : asset('default-product.png') }}"
                            alt="{{ $product->name }}"
                            loading="lazy"
                        >
                    </a>
                    <div class="cmp-card-body">
                        <a href="{{ route('products.show', $product->slug) }}" class="cmp-card-name">
                            {{ $product->name }}
                        </a>
                        <div class="cmp-card-price">₾ {{ number_format($product->price, 2) }}</div>
                        <a href="{{ route('products.show', $product->slug) }}" class="cmp-card-link">
                            პროდუქტის ნახვა <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ატრიბუტების ცხრილი --}}
        <div class="compare-attrs">

            {{-- სექციის სათაური --}}
            <div class="compare-section-title">
                <i class="bi bi-info-circle me-2"></i>ძირითადი ინფო
            </div>

            {{-- კატეგორია --}}
            <div class="compare-row">
                <div class="compare-label-col">კატეგორია</div>
                @foreach($products as $product)
                <div class="compare-product-col">
                    <a href="{{ route('category.products', $product->category->slug) }}" class="text-decoration-none fw-bold text-primary small">
                        {{ $product->category->name }}
                    </a>
                </div>
                @endforeach
            </div>

            {{-- მდგომარეობა --}}
            <div class="compare-row">
                <div class="compare-label-col">მდგომარეობა</div>
                @foreach($products as $product)
                @php [$condClass, $condText] = $conditionMap[$product->condition] ?? ['bg-secondary','მეორადი']; @endphp
                <div class="compare-product-col">
                    <span class="badge {{ $condClass }} rounded-pill">{{ $condText }}</span>
                </div>
                @endforeach
            </div>

            {{-- ქვეყანა --}}
            <div class="compare-row">
                <div class="compare-label-col">ქვეყანა</div>
                @foreach($products as $product)
                <div class="compare-product-col">
                    <img src="https://flagcdn.com/w40/{{ strtolower($product->supplier_country) }}.png"
                         width="20" class="me-1 rounded-1" style="border:1px solid #eee;">
                    {{ strtoupper($product->supplier_country) }}
                </div>
                @endforeach
            </div>

            {{-- ტიპი --}}
            @if($products->filter(fn($p) => $p->sub_type)->isNotEmpty())
            <div class="compare-row">
                <div class="compare-label-col">ტიპი</div>
                @foreach($products as $product)
                <div class="compare-product-col">
                    {{ $product->sub_type ?: '' }}
                    @if(!$product->sub_type)<span class="cmp-dash">—</span>@endif
                </div>
                @endforeach
            </div>
            @endif

            {{-- მახასიათებლები --}}
            @if($featureKeys->isNotEmpty())
            <div class="compare-section-title">
                <i class="bi bi-list-check me-2"></i>მახასიათებლები
            </div>

            @foreach($featureKeys as $key)
            <div class="compare-row">
                <div class="compare-label-col">{{ $key }}</div>
                @foreach($products as $product)
                @php
                    $val = null;
                    if ($product->features_text) {
                        foreach (explode("\n", $product->features_text) as $line) {
                            $parts = explode(':', $line, 2);
                            if (count($parts) === 2 && trim($parts[0]) === $key) {
                                $val = trim($parts[1]);
                                break;
                            }
                        }
                    }
                @endphp
                <div class="compare-product-col">
                    @if($val)
                        {{ $val }}
                    @else
                        <span class="cmp-dash">—</span>
                    @endif
                </div>
                @endforeach
            </div>
            @endforeach
            @endif

        </div>{{-- /compare-attrs --}}
    </div>{{-- /compare-wrap --}}

    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/compare.js?v=' . filemtime(public_path('js/compare.js'))) }}"></script>
@endpush
