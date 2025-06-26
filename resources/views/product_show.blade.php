@extends('layouts.app')

@section('title', $product->name . ' • ICETECH')

@php
// HTML ტეგების და სპეციალური სიმბოლოების გაწმმენდა
$clean_description = html_entity_decode(strip_tags($product->description ?? ''));
$meta_desc = $product->meta_description ?? Str::limit($clean_description, 160) ?? 'პროფესიონალური ' . $product->name . ' ICETECH-ისგან. მაღალი ხარისხის კომერციული აღჭურვილობა საქართველოში.';
@endphp

@section('meta_description', $meta_desc)
@section('meta_keywords', $product->name . ', ICETECH, ' . $product->name . ' საქართველოში, კომერციული აღჭურვილობა, პროფესიონალური ტექნიკა' . ($product->keywords ? ', ' . $product->keywords : ''))

@section('og_title', $product->name . ' • ICETECH')
@section('og_description', $meta_desc)
@section('og_image', $product->image ? url('storage/' . $product->image) : url('images/icetech-og-image.jpg'))

@section('twitter_title', $product->name . ' • ICETECH')
@section('twitter_description', $meta_desc)
@section('twitter_image', $product->image ? url('storage/' . $product->image) : url('images/icetech-twitter-image.jpg'))

@push('meta')
    <meta property="og:type" content="product" />
    <meta property="og:site_name" content="ICETECH" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image:alt" content="{{ $product->name }}" />

    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ url()->current() }}" />

    <meta property="product:brand" content="ICETECH" />
    <meta property="product:name" content="{{ $product->name }}" />
    <meta property="product:condition" content="@if($product->condition == 'new') ახალი @elseif($product->condition == 'used') მეორადი @elseif($product->condition == 'like_new') ახალივით @else {{ $product->condition }} @endif" />
    <meta property="product:price:amount" content="{{ $product->price ?? '' }}" />
    <meta property="product:price:currency" content="GEL" />
    <meta property="product:retailer_item_id" content="{{ $product->id }}" />
    <meta property="product:item_group_id" content="commercial-equipment" />
    <meta property="product:supplier_country" content="{{ $product->supplier_country ?? '' }}" />
    
    @if($product->price)
    <script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "{{ $product->name }}",
        "image": "{{ $product->image ? url('storage/' . $product->image) : url('images/icetech-og-image.jpg') }}",
        "description": "{{ $clean_description }}",
        "brand": {
            "@type": "Brand",
            "name": "ICETECH"
        },
        "offers": {
            "@type": "Offer",
            "url": "{{ url()->current() }}",
            "priceCurrency": "GEL",
            "price": "{{ $product->price }}",
            "itemCondition": "https://schema.org/{{ $product->condition == 'new' ? 'NewCondition' : ($product->condition == 'used' ? 'UsedCondition' : 'NewCondition') }}",
            "availability": "https://schema.org/{{ $product->availability_status == 'in_stock' ? 'InStock' : ($product->availability_status == 'out_of_stock' ? 'OutOfStock' : 'PreOrder') }}"
        }
        @if(!empty($product->sku))
        ,"sku": "{{ $product->sku }}"
        @endif
        @if(!empty($product->model))
        ,"model": "{{ $product->model }}"
        @endif
        @if(!empty($product->manufacturer))
        ,"manufacturer": {
            "@type": "Organization",
            "name": "{{ $product->manufacturer }}"
        }
        @endif
        @if(!empty($product->weight))
        ,"weight": {
            "@type": "QuantitativeValue",
            "value": "{{ $product->weight }}",
            "unitCode": "{{ $product->weight_unit ?? 'KGM' }}"
        }
        @endif
        @if(!empty($product->category))
        ,"category": "{{ $product->category->name }}"
        @endif
        {{-- მახასიათებლების დამატება JSON-LD-ში --}}
        @if(isset($product->features) && is_array($product->features) && !empty($product->features))
        ,"additionalProperty": [
            @foreach($product->features as $key => $feature)
            {
                "@type": "PropertyValue",
                "name": "{{ $feature['name'] ?? '' }}",
                "value": "{{ $feature['value'] ?? '' }}"
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ]
        @endif
    }
    </script>
    @endif

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "მთავარი",
                "item": "{{ url('/') }}"
            }
            @if($product->category)
            ,{
                "@type": "ListItem",
                "position": 2,
                "name": "{{ $product->category->name }}",
                "item": "{{ route('categories.show', $product->category->slug) }}"
            },{
                "@type": "ListItem",
                "position": 3,
                "name": "{{ $product->name }}",
                "item": "{{ url()->current() }}"
            }
            @else
            ,{
                "@type": "ListItem",
                "position": 2,
                "name": "{{ $product->name }}",
                "item": "{{ url()->current() }}"
            }
            @endif
        ]
    }
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    {{-- Font Awesome-ის CSS ლინკი (საჭიროა გაზიარების იკონებისთვის) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
    {{-- `partials.product-view` ახლა მოიცავს პროდუქტის ძირითად ინფორმაციას, მახასიათებლებს და მსგავს პროდუქტებს --}}
    @include('partials.product-view')
@endsection

@push('scripts')
    <script src="{{ asset('js/product.js') }}"></script>
@endpush