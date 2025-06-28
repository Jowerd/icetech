@extends('layouts.app')

@section('title', 'ICETECH • ყველაფერი კომერციული საქმიანობისთვის')

@section('meta_description', 'ICETECH • ყველაფერი კომერციული საქმიანობისთვის, მაცივრები და კომერციული ტექნიკა ბიზნესებისთვის. მაღალი ხარისხის პროდუქცია საუკეთესო ფასად ქართულ ბაზარზე.')

@section('meta_keywords', 'კომერციული სამზარეულო, მაცივრები, გასაცივებელი მოწყობილობები, პროფესიონალური სამზარეულოს აღჭურვილობა, რესტორნის აღჭურვილობა, საცხობის მოწყობილობები, კაფეს აღჭურვილობა, საქართველო')

@section('og_title', 'ICETECH • ყველაფერი კომერციული საქმიანობისთვის')
@section('og_description', 'აღმოაჩინეთ კომერციული სამზარეულოს და სამაცივრე მოწყობილობების სრული ასორტიმენტი თქვენი ბიზნესისთვის. მაღალი ხარისხი და საუკეთესო ფასები საქართველოში.')
@section('og_image', asset('images/og-welcome-image.png'))
@section('og_url', url('/'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home-sliders.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
@endpush




@section('content')
    <!-- პროდუქტების სლაიდერი Schema.org მონიშვნით -->
    <div itemscope itemtype="https://schema.org/ItemList">
        <meta itemprop="name" content="ICETECH-ის პოპულარული პროდუქტები">
        <meta itemprop="description" content="ჩვენი ყველაზე პოპულარული კომერციული აღჭურვილობა რესტორნებისთვის, კაფეებისთვის და საცხობებისთვის">
        @include('partials.sliders')
    </div>

    @include('partials.chat')
@endsection

@push('scripts')
    <script src="{{ asset('js/chat.js') }}"></script>
    <script src="{{ mix('js/home-sliders.js') }}" defer></script>
@endpush