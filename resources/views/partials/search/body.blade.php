<div class="col-12">
    
    @php
        $absMin = 0; 
        
        // ვიღებთ მაქსიმალურ ფასს ნაპოვნი პროდუქტებიდან. თუ ვერ იპოვა, დეფოლტად 10000.
        $absMax = $products->max('price') ?? 10000; 
        
        // არჩეული ფასები URL-დან
        $currMin = request('min_price', $absMin);
        $currMax = request('max_price', $absMax);
    @endphp

    {{-- გვერდის სათაური (ბრედქრამბის ნაცვლად) --}}
    <div class="mb-4">
        <h2 class="fw-bold text-dark h4 mb-1">ძიების შედეგები</h2>
        <p class="text-muted small">ნაპოვნია <strong>{{ $products->count() }}</strong> პროდუქტი სიტყვაზე: "<strong>{{ $query }}</strong>"</p>
    </div>

    {{-- 1. ჰორიზონტალური ფილტრი დესკტოპისთვის --}}
    <div class="filter-bar-desktop d-none d-lg-block bg-white p-4 rounded-4 mb-4 border" style="border-color: #f0f0f0 !important; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <form method="GET" action="{{ route('products.search') }}" class="row g-3 align-items-end">
            {{-- აუცილებელია ძებნის სიტყვის შენარჩუნება ფილტრაციისას --}}
            <input type="hidden" name="query" value="{{ $query }}">
            
            {{-- ფასი (ორმაგი სკალა) --}}
            <div class="col-md-3">
                <div class="d-flex justify-content-between align-items-end mb-2">
                    <label class="form-label text-muted text-uppercase fw-bold mb-0" style="font-size: 0.7rem; letter-spacing: 0.5px;">ფასი</label>
                    <span class="fw-bold text-custom" style="font-size: 0.85rem;" id="desktopPriceDisplay">{{ $currMin }} ₾ - {{ $currMax }} ₾</span>
                </div>
                <div class="multi-range-slider pt-1">
                    <div class="slider-track" id="desktopSliderTrack"></div>
                    <input type="range" id="desktopMinRange" min="{{ $absMin }}" max="{{ $absMax }}" value="{{ $currMin }}" step="10">
                    <input type="range" id="desktopMaxRange" min="{{ $absMin }}" max="{{ $absMax }}" value="{{ $currMax }}" step="10">
                    
                    <input type="hidden" name="min_price" id="desktopMinPriceHidden" value="{{ $currMin }}">
                    <input type="hidden" name="max_price" id="desktopMaxPriceHidden" value="{{ $currMax }}">
                </div>
            </div>

            {{-- მდგომარეობა --}}
            <div class="col-md-3">
                <label class="form-label text-muted text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">მდგომარეობა</label>
                <select name="condition" class="form-select custom-select-styled">
                    <option value="">ყველა</option>
                    <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>ახალი</option>
                    <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>ახალივით</option>
                    <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>მეორადი</option>
                </select>
            </div>

            {{-- ქვეყანა --}}
            <div class="col-md-3">
                <label class="form-label text-muted text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">ქვეყანა</label>
                <select name="country" class="form-select custom-select-styled">
                    <option value="">ყველა</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ strtoupper($country) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- დალაგება --}}
            <div class="col-md-2">
                <label class="form-label text-muted text-uppercase fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">დალაგება</label>
                <select name="sort" class="form-select custom-select-styled">
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>ფასი: ზრდადი</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>ფასი: კლებადი</option>
                </select>
            </div>

            {{-- ღილაკები --}}
            <div class="col-md-1 d-flex gap-2 pb-1">
                <button type="submit" class="btn btn-custom w-100 fw-bold rounded-3" title="ძიება">
                    <i class="bi bi-search"></i>
                </button>
                @if(request()->hasAny(['min_price', 'max_price', 'condition', 'country']))
                    <a href="{{ route('products.search') }}?query={{ $query }}" class="btn btn-light text-danger w-100 fw-bold rounded-3 border" title="გასუფთავება">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- 2. მობილურის პანელი --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-dark btn-sm fw-bold shadow-sm d-lg-none rounded-3 px-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
            <i class="bi bi-sliders me-1"></i> ფილტრი
        </button>
        
        <div class="d-none d-md-flex gap-2 ms-auto">
            <button class="btn btn-sm btn-view-mode active rounded-3" data-view="grid" title="ბადე"><i class="bi bi-grid-fill"></i></button>
            <button class="btn btn-sm btn-view-mode rounded-3" data-view="list" title="სია"><i class="bi bi-list-ul"></i></button>
        </div>
    </div>

    {{-- 3. პროდუქტების კონტეინერი --}}
    @if($products->isEmpty())
        <div class="alert alert-light text-center p-5 border rounded-4 shadow-sm mt-3">
            <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
            <h4 class="mt-3 text-dark fw-bold">პროდუქტები ვერ მოიძებნა</h4>
            <p class="text-muted">გთხოვთ, შეცვალოთ ფილტრის პარამეტრები ან საძიებო სიტყვა და სცადოთ თავიდან.</p>
            <a href="{{ route('products.search') }}?query={{ $query }}" class="btn btn-custom mt-2 rounded-3 px-4">ფილტრის გასუფთავება</a>
        </div>
    @else
        <div class="row g-4 products-wrapper view-grid" id="productsContainer">
            @foreach($products as $product)
                @php
                    $countryCode = strtolower($product->supplier_country);
                    $conditionMap = [
                        'new' => ['bg-success', 'ახალი', 'bi-star-fill'],
                        'like_new' => ['bg-info text-dark', 'ახალივით', 'bi-star-half'],
                        'used' => ['bg-secondary', 'მეორადი', 'bi-tag-fill'],
                    ];
                    [$conditionClass, $conditionText, $conditionIcon] = $conditionMap[$product->condition] ?? ['bg-secondary', 'მეორადი', 'bi-tag-fill'];
                @endphp
                
                <div class="col-6 col-md-4 col-lg-3 col-xl-20 product-col">
                    <div class="product-card h-100 bg-white rounded-4 border-0" style="border: 1px solid #f0f0f0 !important;">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark d-flex flex-column h-100">
                            <div class="product-image-box position-relative">
                                <span class="badge {{ $conditionClass }} condition-badge shadow-sm">
                                    <i class="bi {{ $conditionIcon }} me-1"></i>{{ $conditionText }}
                                </span>
                                <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('default-product.png') }}" alt="{{ $product->name }}" loading="lazy">
                            </div>
                            <div class="product-info d-flex flex-column flex-grow-1 p-3">
                                <div class="d-flex align-items-center mb-2 country-wrap">
                                    <img src="https://flagcdn.com/w40/{{ $countryCode }}.png" alt="flag" class="rounded-1 border" width="18">
                                    <span class="ms-2 text-muted small fw-medium">{{ strtoupper($product->supplier_country) }}</span>
                                </div>
                                <h5 class="product-title fw-bold mb-2">{{ $product->name }}</h5>
                                <div class="mt-auto pt-3 d-flex align-items-end justify-content-between border-top border-light">
                                    <div class="price-wrap w-100">
                                        <span class="price text-success fw-bolder fs-5">{{ number_format($product->price, 2) }} ₾</span>
                                    </div>
                                    <div class="view-details text-custom">
                                        <i class="bi bi-arrow-right-circle-fill fs-4"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- 4. Offcanvas მობილური ფილტრი --}}
<div class="offcanvas offcanvas-start border-0 shadow" tabindex="-1" id="filterOffcanvas">
    <div class="offcanvas-header border-bottom border-light bg-white">
        <h5 class="fw-bold m-0"><i class="bi bi-sliders me-2 text-custom"></i> ფილტრი</h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-4 bg-white">
        <form method="GET" action="{{ route('products.search') }}">
            <input type="hidden" name="query" value="{{ $query }}">

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-end mb-2">
                    <label class="form-label text-muted text-uppercase fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 0.5px;">ფასი</label>
                    <span class="fw-bold text-custom" style="font-size: 0.9rem;" id="mobilePriceDisplay">{{ $currMin }} ₾ - {{ $currMax }} ₾</span>
                </div>
                <div class="multi-range-slider mt-2 mb-3">
                    <div class="slider-track" id="mobileSliderTrack"></div>
                    <input type="range" id="mobileMinRange" min="{{ $absMin }}" max="{{ $absMax }}" value="{{ $currMin }}" step="10">
                    <input type="range" id="mobileMaxRange" min="{{ $absMin }}" max="{{ $absMax }}" value="{{ $currMax }}" step="10">
                    <input type="hidden" name="min_price" id="mobileMinPriceHidden" value="{{ $currMin }}">
                    <input type="hidden" name="max_price" id="mobileMaxPriceHidden" value="{{ $currMax }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted text-uppercase fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">მდგომარეობა</label>
                <select name="condition" class="form-select custom-select-styled py-2">
                    <option value="">ყველა</option>
                    <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>ახალი</option>
                    <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>ახალივით</option>
                    <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>მეორადი</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted text-uppercase fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">ქვეყანა</label>
                <select name="country" class="form-select custom-select-styled py-2">
                    <option value="">ყველა</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ strtoupper($country) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted text-uppercase fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">დალაგება</label>
                <select name="sort" class="form-select custom-select-styled py-2">
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>ფასი: ზრდადი</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>ფასი: კლებადი</option>
                </select>
            </div>

            <div class="d-grid gap-2 mt-5">
                <button type="submit" class="btn btn-custom fw-bold py-2 rounded-3">ძიება</button>
                @if(request()->hasAny(['min_price', 'max_price', 'condition', 'country']))
                    <a href="{{ route('products.search') }}?query={{ $query }}" class="btn btn-light text-danger py-2 rounded-3 fw-bold border">გასუფთავება</a>
                @endif
            </div>
        </form>
    </div>
</div>