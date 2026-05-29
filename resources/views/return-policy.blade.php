@extends('layouts.app')

@section('title', 'დაბრუნების პოლიტიკა • ICETECH')
@section('meta_description', 'ICETECH-ის პროდუქციის დაბრუნებისა და გაცვლის პოლიტიკა. გაიგეთ დეფექტური პროდუქციის დაბრუნების პირობები და პროცედურა.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/layout.css?v=' . filemtime(public_path('css/layout.css'))) }}">
@endpush

@section('content')

@include('partials.breadcrumb', ['crumbs' => [
    ['label' => 'დაბრუნების პოლიტიკა', 'url' => '']
]])

<div class="py-4" style="max-width: 820px; margin: 0 auto;">

    <h1 class="fw-bold mb-1" style="color: #1a365d; font-size: 1.8rem;">დაბრუნების პოლიტიკა</h1>
    <p class="text-muted mb-5" style="font-size: 0.9rem;">ბოლო განახლება: {{ date('d.m.Y') }}</p>

    {{-- 1 --}}
    <div class="mb-5">
        <h2 class="fw-bold mb-3" style="color: #1a365d; font-size: 1.2rem;">
            <span class="me-2" style="color: #00a4bd;">1.</span> ზოგადი პირობები
        </h2>
        <p class="text-muted lh-lg">
            ICETECH პასუხისმგებელია გაყიდული პროდუქციის ხარისხზე. ჩვენ ვიღებთ
            პასუხისმგებლობას <strong>დეფექტური ან დაზიანებული</strong> პროდუქციის
            შეცვლაზე ან სარემონტო მომსახურებაზე, კანონმდებლობით განსაზღვრული
            საგარანტიო ვადების ფარგლებში.
        </p>
    </div>

    {{-- 2 --}}
    <div class="mb-5">
        <h2 class="fw-bold mb-3" style="color: #1a365d; font-size: 1.2rem;">
            <span class="me-2" style="color: #00a4bd;">2.</span> დეფექტური პროდუქციის დაბრუნება
        </h2>
        <p class="text-muted lh-lg mb-3">
            თუ მიღებული პროდუქტი <strong>ქარხნულად დეფექტურია ან არ შეესაბამება შეკვეთას</strong>,
            გაქვთ უფლება მოითხოვოთ დაბრუნება ან შეცვლა შემდეგი პირობების დაცვით:
        </p>
        <ul class="text-muted lh-lg ps-4">
            <li class="mb-2">დაბრუნების მოთხოვნა წარდგენილ უნდა იქნეს <strong>14 კალენდარული დღის</strong> განმავლობაში მიღებიდან</li>
            <li class="mb-2">პროდუქტი უნდა იყოს ორიგინალ შეფუთვაში, სრული კომპლექტაციით</li>
            <li class="mb-2">დეფექტი არ უნდა იყოს გამოწვეული მომხმარებლის მიერ არასწორი გამოყენებით</li>
            <li class="mb-2">ფოტო ან ვიდეო მტკიცებულება დეფექტის შესახებ სასარგებლოა პროცესის დასაჩქარებლად</li>
        </ul>
    </div>

    {{-- 3 --}}
    <div class="mb-5">
        <h2 class="fw-bold mb-3" style="color: #1a365d; font-size: 1.2rem;">
            <span class="me-2" style="color: #00a4bd;">3.</span> არადეფექტური პროდუქციის დაბრუნება
        </h2>
        <p class="text-muted lh-lg">
            კომერციული მოწყობილობები, რომლებიც <strong>სწორად მუშაობენ და შეესაბამებიან
            შეკვეთის სპეციფიკაციებს</strong>, დაბრუნებას არ ექვემდებარება. გთხოვთ,
            შეძენამდე დაგვიკავშირდეთ ნებისმიერი კითხვის შემთხვევაში.
        </p>
    </div>

    {{-- 4 --}}
    <div class="mb-5">
        <h2 class="fw-bold mb-3" style="color: #1a365d; font-size: 1.2rem;">
            <span class="me-2" style="color: #00a4bd;">4.</span> გაცვლა
        </h2>
        <p class="text-muted lh-lg">
            დეფექტური პროდუქტის შემთხვევაში, ICETECH გთავაზობთ <strong>ანალოგიური
            პროდუქტით შეცვლას</strong> ხელმისაწვდომობის გათვალისწინებით. გაცვლის
            პირობები იგივეა, რაც დაბრუნებისთვის.
        </p>
    </div>

    {{-- 5 --}}
    <div class="mb-5">
        <h2 class="fw-bold mb-3" style="color: #1a365d; font-size: 1.2rem;">
            <span class="me-2" style="color: #00a4bd;">5.</span> დაბრუნების პროცედურა
        </h2>
        <ol class="text-muted lh-lg ps-4">
            <li class="mb-2">დაგვიკავშირდით ელ.ფოსტაზე <a href="mailto:info@icetech.ge" style="color: #00a4bd;">info@icetech.ge</a> ან ტელეფონზე <a href="tel:+995511555888" style="color: #00a4bd;">+995 511 555 888</a></li>
            <li class="mb-2">მიუთითეთ შეკვეთის ნომერი, პრობლემის აღწერა და ფოტო/ვიდეო</li>
            <li class="mb-2">ჩვენი გუნდი დაგიკავშირდებათ <strong>2 სამუშაო დღის</strong> განმავლობაში</li>
            <li class="mb-2">მოგარგებული გადაწყვეტილების შემდეგ განხორციელდება პროდუქტის გადაგზავნა ან შეცვლა</li>
        </ol>
    </div>

    {{-- CTA --}}
    <div class="rounded-4 p-4 mt-4" style="background: linear-gradient(135deg, #f0f9fb, #e8f8fb); border: 1.5px solid #c8eef4;">
        <h3 class="fw-bold mb-2" style="color: #1a365d; font-size: 1rem;">გაქვთ კითხვები?</h3>
        <p class="text-muted mb-3" style="font-size: 0.9rem;">დაბრუნების შესახებ ნებისმიერი კითხვისთვის დაგვიკავშირდით.</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="mailto:info@icetech.ge" class="btn btn-sm rounded-3 fw-bold px-3"
               style="background:#00a4bd; color:#fff; border:none;">
                <i class="bi bi-envelope me-1"></i> info@icetech.ge
            </a>
            <a href="tel:+995511555888" class="btn btn-sm rounded-3 fw-bold px-3"
               style="background:#1a365d; color:#fff; border:none;">
                <i class="bi bi-telephone me-1"></i> +995 511 555 888
            </a>
            <a href="{{ route('contact') }}" class="btn btn-sm rounded-3 fw-bold px-3"
               style="background:#fff; color:#1a365d; border: 1.5px solid #1a365d;">
                კონტაქტი
            </a>
        </div>
    </div>

</div>

@endsection
