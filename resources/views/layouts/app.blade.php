<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <!-- მთავარი SEO მეტა ტეგები -->
    <title>@yield('title', 'ICETECH - კომერციული სამზარეულოს და გასაცივებელი მოწყობილობები საქართველოში')</title>
    <meta name="description" content="@yield('meta_description', 'ICETECH - პროფესიონალური სამზარეულოს აღჭურვილობა, მაცივრები და კომერციული ტექნიკა ბიზნესებისთვის. საუკეთესო ხარისხის პროდუქცია საქართველოში.')">
    <meta name="keywords" content="@yield('meta_keywords', 'კომერციული სამზარეულო, სამზარეულოს აღჭურვილობა, მაცივრები, გასაცივებელი მოწყობილობები, სამზარეულოს ტექნიკა, კაფეს აღჭურვილობა, რესტორნის აღჭურვილობა, საკონდიტრო მოწყობილობები, საქართველო')">
    
    <!-- Open Graph მეტა ტეგები სოციალური მედიისთვის -->
    <meta property="og:title" content="@yield('og_title', 'ICETECH - კომერციული სამზარეულოს და გასაცივებელი მოწყობილობები')">
    <meta property="og:description" content="@yield('og_description', 'პროფესიონალური სამზარეულოს აღჭურვილობა, მაცივრები და კომერციული ტექნიკა ბიზნესებისთვის. საუკეთესო ხარისხის პროდუქცია საქართველოში.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/icetech-og-image.jpg'))">
    <meta property="og:site_name" content="ICETECH">
    <meta property="og:locale" content="ka_GE">
    
    <!-- Twitter Card მეტა ტეგები -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'ICETECH - კომერციული სამზარეულოს და გასაცივებელი მოწყობილობები')">
    <meta name="twitter:description" content="@yield('twitter_description', 'პროფესიონალური სამზარეულოს აღჭურვილობა, მაცივრები და კომერციული ტექნიკა ბიზნესებისთვის. საუკეთესო ხარისხის პროდუქცია საქართველოში.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/icetech-twitter-image.jpg'))">
    
    <!-- დამატებითი მეტა ტეგები -->
    @stack('meta')
    
    <!-- კანონიკური ლინკი -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- სტრუქტურირებული მონაცემები LocalBusiness-თვის (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "ICETECH",
        "description": "ყველაფერი შენი კომერციული საქმიანობისთვის",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('favicon.svg') }}",
        "image": "{{ asset('images/icetech-og-image.jpg') }}",
        "telephone": "+995 511 55 58 88",
        "email": "info@icetech.ge",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "ხაშური",
            "addressRegion": "შიდა ქართლი",
            "addressCountry": "GE"
        },
        "openingHours": "Mo-Fr 09:00-18:00",
        "priceRange": "₾₾₾"
    }
    </script>
    
    <!-- ლინკები -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <link rel="preload" href="/fonts/BPGMrgvlovaniCaps2010.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    @stack('styles')
<!-- Google tag (gtag.js) with Consent Mode -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0VJEBWMJL1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  // Default consent state - denied until user accepts
  gtag('consent', 'default', {
    'analytics_storage': 'denied',
    'ad_storage': 'denied'
  });

  // Initialize Google Analytics with anonymized data
  gtag('config', 'G-0VJEBWMJL1', {
    'anonymize_ip': true,
    'cookie_flags': 'secure;samesite=lax',
    'allow_google_signals': false
  });
</script>
</head>
<body>
    
    <!-- ჰედერი -->
    <header class="header">
        <!-- ბრენდის სექცია -->
        <div class="brand-section">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}" aria-label="ICETECH მთავარი გვერდი">ICETECH</a>
                <!-- მობილური მენიუს ღილაკი -->
                <button class="navbar-toggler" type="button" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>

        <!-- ნავიგაციის სექცია -->
        <div class="nav-section">
            <div class="container">
                <nav class="navbar navbar-expand-lg p-0" aria-label="მთავარი ნავიგაცია">
                    <div class="collapse navbar-collapse" id="mainNavbar">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">მთავარი</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('products*') ? 'active' : '' }}" href="{{ route('products') }}">პროდუქცია</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">ჩვენს შესახებ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">კონტაქტი</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <!-- ძიების სექცია -->
        <div class="search-section">
            <div class="container">
                <form action="{{ route('products.search') }}" method="GET" class="search-form" role="search" aria-label="პროდუქტის ძიება">
                    <div class="search-wrapper">
                        <span class="search-icon">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </span>
                        <input class="form-control searchInput" type="search" name="query" placeholder="პროდუქტის ძიება..." aria-label="Search" required autocomplete="off">
                        <button class="search-button" type="submit" aria-label="მოძებნე">
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </button>
                        <!-- Autocomplete dropdown -->
                        <div class="search-suggestions" id="searchSuggestions">
                            <!-- აქ გამოჩნდება suggestion-ები -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <!-- მობილური მენიუ -->
    <div class="mobile-menu">
        <ul class="list-unstyled m-0">
            <li>
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="bi bi-house-door me-2" aria-hidden="true"></i>მთავარი
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('products*') ? 'active' : '' }}" href="{{ route('products') }}">
                    <i class="bi bi-box me-2" aria-hidden="true"></i>პროდუქცია
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                    <i class="bi bi-file-person me-2" aria-hidden="true"></i>ჩვენს შესახებ
                </a>
            </li>
            <li>
                <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                    <i class="bi bi-envelope me-2" aria-hidden="true"></i>კონტაქტი
                </a>
            </li>
        </ul>
    </div>

    <!-- კონტენტის ნაწილი -->
    <main class="content-wrapper">
        <div class="container">
            @yield('content')
        </div>
    </main>

    
<!-- ფუთერი -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-brand">ICETECH</div>
                <p>შენი კომერციული საქმიანობის მხარდამჭერი.</p>
            </div>
            <div class="col-md-4">
                <h5 class="mb-3">სწრაფი ბმულები</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> მთავარი</a></li>
                    <li><a href="{{ route('products') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> პროდუქცია</a></li>
                    <li><a href="{{ route('about') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> ჩვენს შესახებ</a></li>
                    <li><a href="{{ route('contact') }}"><i class="bi bi-chevron-right" aria-hidden="true"></i> კონტაქტი</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="mb-3">საკონტაქტო ინფორმაცია</h5>
                <div class="footer-contact">
                    <p><i class="bi bi-envelope-fill" aria-hidden="true"></i> <a href="mailto:info@icetech.ge">info@icetech.ge</a></p>
                    <p><i class="bi bi-telephone-fill" aria-hidden="true"></i> <a href="tel:+995511555888">+995 511 555 888</a></p>
                    <p><i class="bi bi-geo-alt-fill" aria-hidden="true"></i>ხაშური,ბორჯომის ქუჩა #2</p>
                    <a href="#" class="text-white me-3" aria-label="Facebook"><i class="bi bi-facebook fs-5" aria-hidden="true"></i></a>
                    <a href="#" class="text-white me-3" aria-label="Instagram"><i class="bi bi-instagram fs-5" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>

        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top border-light">
            <p class="mb-0">&copy; {{ date('Y') }} ICETECH. ყველა უფლება დაცულია.</p>
            <p class="mb-0">Designed & Developed by 
                <a href="https://instagram.com/kapamagaria" target="_blank" rel="noopener noreferrer" class="designer-link">@kapamagaria</a>
            </p>
        </div>
    </div>
</footer>

<!-- სკრიპტები -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/layout.js') }}" defer></script>
<script src="{{ asset('js/cookies.js') }}" defer></script>
@stack('scripts')
<!-- ქუქის მაფრთხილებელი ბანერი -->
<div class="cookie-banner" id="cookieBanner">
    <div class="cookie-content">
        <div class="cookie-text">
            <h5><i class="bi bi-info-circle me-2"></i>ქუქების გამოყენება</h5>
            <p>ჩვენ ვიყენებთ ქუქებს საიტის მუშაობის გასაუმჯობესებლად და თქვენი გამოცდილების პერსონალიზებისთვის. საიტის გამოყენებით თქვენ ეთანხმებით ქუქების გამოყენებას. <a href="#" class="text-white text-decoration-underline">მეტი ინფორმაცია</a></p>
        </div>
        <div class="cookie-actions">
            <button class="cookie-btn cookie-btn-accept" onclick="acceptCookies()">
                <i class="bi bi-check-lg"></i> მიღება
            </button>
            <button class="cookie-btn cookie-btn-decline" onclick="declineCookies()">
                <i class="bi bi-x-lg"></i> უარყოფა
            </button>
            <button class="cookie-btn cookie-btn-settings" onclick="openCookieSettings()">
                <i class="bi bi-gear"></i> პარამეტრები
            </button>
        </div>
    </div>
</div>

<!-- ქუქის პარამეტრების მოდალი -->
<div class="cookie-modal" id="cookieModal">
    <div class="cookie-modal-content">
        <div class="cookie-modal-header">
            <h4 class="cookie-modal-title">ქუქების პარამეტრები</h4>
            <button class="cookie-modal-close" onclick="closeCookieSettings()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        
        <div class="cookie-category">
            <div class="cookie-category-header">
                <h5 class="cookie-category-title">აუცილებელი ქუქები</h5>
                <div class="cookie-toggle">
                    <input type="checkbox" id="essential" checked disabled>
                    <span class="cookie-slider"></span>
                </div>
            </div>
            <p class="cookie-category-desc">ეს ქუქები აუცილებელია საიტის სწორი მუშაობისთვის და არ შეიძლება გათიშვა.</p>
        </div>
        
        <div class="cookie-category">
            <div class="cookie-category-header">
                <h5 class="cookie-category-title">ანალიტიკური ქუქები</h5>
                <div class="cookie-toggle">
                    <input type="checkbox" id="analytics" checked>
                    <span class="cookie-slider"></span>
                </div>
            </div>
            <p class="cookie-category-desc">ეს ქუქები გვეხმარება საიტის გამოყენების სტატისტიკის შეგროვებაში (Google Analytics).</p>
        </div>
        
        <div class="cookie-modal-actions">
            <button class="cookie-btn cookie-btn-decline" onclick="declineCookies()">
                უარყოფა
            </button>
            <button class="cookie-btn cookie-btn-accept" onclick="saveSettings()">
                შენახვა
            </button>
        </div>
    </div>
</div>
</body>
</html>