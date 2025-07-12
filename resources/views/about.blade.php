@extends('layouts.app')

@section('title', 'ჩვენს შესახებ • ICETECH')

@section('meta_description', 'Icetech - 10 წელზე მეტი გამოცდილება სავაჭრო და სამზარეულოს აღჭურვილობის სფეროში. ჩვენ გთავაზობთ მაცივრებს, თაროებს, კომპრესორებს და სხვა ინვენტარს.')

@section('meta_keywords', 'Icetech, ჩვენს შესახებ, სავაჭრო აღჭურვილობა, სამზარეულოს ტექნიკა, მაცივრები, თაროები, კომპრესორები, AHT, მეორადი ტექნიკა, მარკეტის აღჭურვა')

@section('og_title', 'ჩვენს შესახებ - ICETECH')

@section('og_description', 'გაიგეთ მეტი Icetech-ის შესახებ. 10 წლიანი გამოცდილება, ხარისხიანი სავაჭრო და სამზარეულოს აღჭურვილობა და პროფესიონალური მომსახურება.')

@section('og_image', url('images/icetech-about-og-image.png'))

@section('twitter_title', 'ჩვენს შესახებ - ICETECH')

@section('twitter_description', 'გაიგეთ მეტი Icetech-ის შესახებ. 10 წლიანი გამოცდილება, ხარისხიანი სავაჭრო და სამზარეულოს აღჭურვილობა და პროფესიონალური მომსახურება.')

@section('twitter_image', url('images/icetech-about-og-image.png'))

@push('meta')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AboutPage",
  "name": "ICETECH-ის შესახებ",
  "description": "Icetech არის კომპანია, რომელიც უკვე 10 წელზე მეტია ეხმარება ბიზნესებს ხარისხიანი სავაჭრო და სამზარეულოს აღჭურვილობის შერჩევაში.",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ route('about') }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "ICETECH",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  }
}
</script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')
    <div class="w-100 bg-white shadow-sm border-top">
        <div class="container py-5">
            <div class="row">
                <div class="col-12">
                    <h1 class="fw-bold mb-3 about-title">
                        ჩვენი ისტორია და მისია
                    </h1>
                    <div class="about-divider mb-4"></div>
                    <div class="row">
                        <div class="col-lg-10 col-xl-9">
                            <p class="text-muted lead mb-4">
                                Icetech არის კომპანია, რომელიც უკვე 10 წელზე მეტია ეხმარება მარკეტებს, მაღაზიებს, სუპერმარკეტებსა და რესტორნებს ხარისხიანი სავაჭრო და სამზარეულოს აღჭურვილობის შერჩევაში.
                            </p>
                            <p class="text-muted">
                                ჩვენ გთავაზობთ მაცივრებს, თაროებს, კომპრესორებს, სამზარეულოს ტექნიკასა და სხვა ყველა საჭირო ინვენტარს როგორც ახალ, ისე მეორად მდგომარეობაში. ჩვენ ვმუშაობთ წამყვან საერთაშორისო ბრენდებთან და ვახორციელებთ როგორც გაყიდვებს, ისე ტექნიკურ მხარდაჭერას და კონსულტაციას. სწორედ AHT-ის გამოყენებული მაცივრებით დავიწყეთ ჩვენი ისტორია და დღეს უკვე ვემსახურებით ასობით კლიენტს საქართველოს მასშტაბით.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="w-100 values-section">
        <div class="container py-5">
            <div class="row text-center">
                <div class="col-12 mb-5">
                    <h2 class="fw-bold mb-0 about-subtitle">ჩვენი ფასეულობები</h2>
                    <div class="mt-3 about-divider mx-auto"></div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="value-card h-100 p-4">
                        <div class="icon-circle mx-auto mb-3">
                            <i data-lucide="award" class="about-icon"></i>
                        </div>
                        <h4 class="fw-bold">ხარისხი</h4>
                        <p class="text-muted">ჩვენთვის მთავარია შემოგთავაზოთ მხოლოდ მაღალი ხარისხის, სანდო და გამძლე პროდუქცია.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="value-card h-100 p-4">
                        <div class="icon-circle mx-auto mb-3">
                            <i data-lucide="users" class="about-icon"></i>
                        </div>
                        <h4 class="fw-bold">გამოცდილება</h4>
                        <p class="text-muted">10 წლიანი გამოცდილება გვაძლევს საშუალებას, ზუსტად გავიგოთ თქვენი საჭიროებები.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="value-card h-100 p-4">
                        <div class="icon-circle mx-auto mb-3">
                            <i data-lucide="shield-check" class="about-icon"></i>
                        </div>
                        <h4 class="fw-bold">სანდოობა</h4>
                        <p class="text-muted">ჩვენ ვართ თქვენი სანდო პარტნიორი, რომელიც ზრუნავს თქვენი ბიზნესის წარმატებაზე.</p>
                    </div>
                </div>
            </div>
             <div class="text-center mt-4">
                 <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">
                     დაგვიკავშირდით და დააკომპლექტეთ თქვენი ბიზნესი პროფესიონალურად!
                 </a>
            </div>
        </div>
    </div>

    <div class="w-100 bg-white">
        <div class="container py-5">
            <div class="row">
                <div class="col-12 mb-5 text-center">
                    <h2 class="fw-bold mb-0 about-subtitle">ხშირად დასმული კითხვები</h2>
                    <div class="mt-3 about-divider mx-auto"></div>
                </div>

                <div class="col-lg-8 mx-auto">
                    <div class="accordion" id="faqAccordion">
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    <i data-lucide="package" class="faq-icon me-2"></i>
                                    როგორ ინვენტარს ყიდით?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    ჩვენ ვყიდით როგორც ახალ, ასევე მეორად კომერციულ ინვენტარს: მაცივრები, თაროები, გაყინვის სისტემები, სამზარეულოს ტექნიკა, კომპრესორები და სხვა.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i data-lucide="truck" class="faq-icon me-2"></i>
                                    შესაძლებელია თუ არა ადგილზე მიტანა?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    დიახ, თბილისსა და საქართველოს სხვადასხვა რეგიონში ვთავაზობთ ადგილზე მიტანას შეთანხმებით.
                                </div>
                            </div>
                        </div>

                         <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <i data-lucide="refrigerator" class="faq-icon me-2"></i>
                                    იყიდება მეორადი მაცივრებიც?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    დიახ, ჩვენ დავიწყეთ სწორედ მეორადი AHT მაცივრებით და დღემდე ვთავაზობთ მეორად, სრულად გამართულ ტექნიკას გარანტიით.
                                </div>
                            </div>
                        </div>

                         <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    <i data-lucide="shield-check" class="faq-icon me-2"></i>
                                    გაქვთ თუ არა გარანტია პროდუქტებზე?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    ახალ პროდუქციაზე ვაძლევთ მწარმოებლის გარანტიას. მეორად ტექნიკაზე გარანტიის პირობები ინდივიდუალურად განისაზღვრება.
                                </div>
                            </div>
                        </div>

                         <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    <i data-lucide="shopping-cart" class="faq-icon me-2"></i>
                                    შეიძლება თუ არა კონკრეტული აღჭურვილობის შეკვეთა?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    დიახ, შეგვიძლია კონკრეტული ბრენდის ან მოდელის მოწოდება წინასწარი შეთანხმებით.
                                </div>
                            </div>
                        </div>

                         <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSix">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                    <i data-lucide="globe" class="faq-icon me-2"></i>
                                    საიდან ჩამოგაქვთ პროდუქცია?
                                </button>
                            </h2>
                            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    პროდუქცია მოგვაქვს სხვადასხვა ქვეყნიდან, მათ შორის: თურქეთი, გერმანია და იტალია. ვმუშაობთ ევროპულ ბრენდებთან, რაც უზრუნველყოფს ხარისხს და სანდოობას.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSeven">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                    <i data-lucide="map-pin" class="faq-icon me-2"></i>
                                    მუშაობთ მხოლოდ თბილისში?
                                </button>
                            </h2>
                            <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    არა, ვემსახურებით კლიენტებს მთელი საქართველოს მასშტაბით. გვაქვს გამოცდილება რეგიონული ბაზრების მოწყობაშიც.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingEight">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                    <i data-lucide="store" class="faq-icon me-2"></i>
                                    შეგიძლიათ მარკეტის სრული აღჭურვა?
                                </button>
                            </h2>
                            <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    დიახ, შეგვიძლია სრულად დავაკომპლექტოთ მარკეტი ან მაღაზია: თაროებიდან მაცივრებამდე და სალაროს ზონამდე.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script>
        lucide.createIcons();
    </script>
@endpush