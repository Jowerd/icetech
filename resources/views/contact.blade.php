@extends('layouts.app')

@section('title', 'კონტაქტი • ICETECH')

@section('meta_description', 'დაგვიკავშირდით ICETECH-ს - პროფესიონალური სამზარეულოს და გასაცივებელი მოწყობილობების მიმწოდებელი საქართველოში. გაიგეთ ჩვენი მისამართი, ტელეფონი და ელ.ფოსტა.')

@section('meta_keywords', 'დაგვიკავშირდით ICETECH, კონტაქტი, ტელეფონი, ელ.ფოსტა, მისამართი, კომერციული სამზარეულო, სამზარეულოს აღჭურვილობა, მაცივრები, გასაცივებელი მოწყობილობები, ხაშური')

@section('og_title', 'დაგვიკავშირდით ICETECH - კომერციული სამზარეულოს აღჭურვილობა საქართველოში')

@section('og_description', 'გჭირდებათ პროფესიონალური სამზარეულოს აღჭურვილობა? დაგვიკავშირდით ICETECH-ს მეილზე, ტელეფონით ან გვეწვიეთ ხაშურში. ჩვენ ვართ თქვენი პარტნიორი კომერციული გასაცივებელი და სამზარეულოს მოწყობილობებში.')

@section('og_image', url('images/icetech-contact-og-image.png'))

@section('twitter_title', 'დაგვიკავშირდით ICETECH - კომერციული სამზარეულოს აღჭურვილობა')

@section('twitter_description', 'გჭირდებათ პროფესიონალური სამზარეულოს აღჭურვილობა? დაგვიკავშირდით ICETECH-ს მეილზე, ტელეფონით ან გვეწვიეთ ხაშურში.')

@section('twitter_image', url('images/icetech-contact-og-image.png'))

@push('meta')
<!-- დამატებითი სტრუქტურირებული მონაცემები კონტაქტის გვერდისთვის -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ContactPage",
  "name": "ICETECH კონტაქტი",
  "description": "დაგვიკავშირდით ICETECH-ს - პროფესიონალური სამზარეულოს და გასაცივებელი მოწყობილობების მიმწოდებელი საქართველოში",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ route('contact') }}"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+995 511 55 58 88",
    "contactType": "customer service",
    "email": "info@icetech.ge",
    "areaServed": "GE",
    "availableLanguage": {
      "@type": "Language",
      "name": "Georgian"
    }
  },
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "ბორჯომის ქუჩა #2",
    "postalCode": "5700",
    "addressLocality": "ხაშური",
    "addressRegion": "შიდა ქართლი",
    "addressCountry": {
      "@type": "Country",
      "name": "GE"
    }
  },
  "openingHoursSpecification": {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday"
    ],
    "opens": "10:00",
    "closes": "18:00"
  }
}
</script>
<!-- სოციალური მედიის მეტა ტეგები ფოტოს გამოსაჩენად -->
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:image:type" content="image/png" />
<meta name="twitter:card" content="summary_large_image" />
@endpush

@push('styles')
        <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
            <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush


@section('content')
    <!-- რუკა სრულ სიგანეზე -->
    <div class="w-100 position-relative map-container">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2389.6396612348446!2d43.622851677172334!3d41.98675927122162!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40435b3342b49101%3A0xafb5b71e404ac1b0!2z4YOQ4YOu4YOQ4YOa4YOYIOGDk-GDkCDhg5vhg5Thg53hg6Dhg5Dhg6Phg5gg4YOb4YOQ4YOq4YOY4YOV4YOg4YOU4YOR4YOYLCDhg6Hhg5Dhg6Dhg5Thg6Hhg6Lhg53hg6Dhg5zhg50g4YOY4YOc4YOV4YOU4YOc4YOi4YOQ4YOg4YOY!5e0!3m2!1ska!2sge!4v1711653410753!5m2!1ska!2sge"
            width="100%"
            height="500"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>

    <!-- საკონტაქტო ინფორმაცია სრულ სიგანეზე -->
    <div class="w-100 bg-white shadow-sm border-top">
        <div class="container py-5">
            <div class="row justify-content-between">
                <div class="col-12 mb-5">
                    <h2 class="fw-bold mb-0 d-flex align-items-center contact-title">
                        <i data-lucide="phone-call" class="me-2"></i> დაგვიკავშირდით
                    </h2>
                    <div class="mt-3 contact-divider"></div>
                </div>
                
                <!-- მისამართი -->
                <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="d-flex contact-card h-100">
                        <div class="me-3 icon-wrapper">
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center icon-circle">
                                <i data-lucide="map-pin" class="contact-icon"></i>
                            </div>
                        </div>
                        <div>
                            <strong class="d-block mb-2 fs-5">მისამართი</strong>
                            <span class="text-muted">ხაშური,ბორჯომის ქუჩა #2</span>
                        </div>
                    </div>
                </div>
                
                <!-- ელ.ფოსტა -->
                <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
                    <div class="d-flex contact-card h-100">
                        <div class="me-3 icon-wrapper">
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center icon-circle">
                                <i data-lucide="mail" class="contact-icon"></i>
                            </div>
                        </div>
                        <div>
                            <strong class="d-block mb-2 fs-5">ელ.ფოსტა</strong>
                            <a href="mailto:info@icetech.ge" class="text-decoration-none contact-link">info@icetech.ge</a>
                        </div>
                    </div>
                </div>
                
                <!-- ტელეფონი -->
                <div class="col-md-6 col-lg-3 mb-4 mb-md-0">
                    <div class="d-flex contact-card h-100">
                        <div class="me-3 icon-wrapper">
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center icon-circle">
                                <i data-lucide="phone" class="contact-icon"></i>
                            </div>
                        </div>
                        <div>
                            <strong class="d-block mb-2 fs-5">ტელეფონი</strong>
                            <a href="tel:+995511555888" class="text-decoration-none contact-link">+995 511 555 888</a>
                        </div>
                    </div>
                </div>
                
                <!-- WhatsApp -->
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex contact-card h-100">
                        <div class="me-3 icon-wrapper">
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center icon-circle">
                                <i data-lucide="message-circle" class="contact-icon"></i>
                            </div>
                        </div>
                        <div>
                            <strong class="d-block mb-2 fs-5">WhatsApp</strong>
                            <a href="https://wa.me/995511555888" target="_blank" class="text-decoration-none contact-link">მოგვწერე WhatsApp-ზე</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- სამუშაო საათები და სოციალური ქსელები -->
            <div class="row mt-5 pt-5 border-top">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h3 class="mb-4 contact-subtitle">კონტაქტის დეტალები</h3>
                    <p class="text-muted mb-4">გაქვთ კითხვები ან გჭირდებათ დამატებითი ინფორმაცია? დაგვიკავშირდით მეილზე ან დაგვირეკეთ.</p>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3 small-icon-circle">
                            <i data-lucide="clock" class="small-icon"></i>
                        </div>
                        <div>
                            <strong class="d-block">სამუშაო საათები</strong>
                            <span class="text-muted">ორშაბათი - შაბათი: 10:00 - 18:00</span>
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="#" class="me-3 social-icon">
                            <div class="d-flex align-items-center justify-content-center rounded-circle small-icon-circle">
                                <i data-lucide="facebook" class="small-icon"></i>
                            </div>
                        </a>
                        <a href="#" class="me-3 social-icon">
                            <div class="d-flex align-items-center justify-content-center rounded-circle small-icon-circle">
                                <i data-lucide="instagram" class="small-icon"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Lucide icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script>
        lucide.createIcons();
    </script>
@endpush