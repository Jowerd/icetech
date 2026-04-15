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
    <div class="filter-bar-desktop d-none d-lg-block">
        <form method="GET" action="{{ route('products.search') }}" class="row g-3 align-items-end">
            <input type="hidden" name="query" value="{{ $query }}">

            <div class="col-md-3">
                <div class="d-flex justify-content-between align-items-end mb-2">
                    <label class="filter-label mb-0">ფასი</label>
                    <span class="filter-price-display" id="desktopPriceDisplay">{{ $currMin }} ₾ - {{ $currMax }} ₾</span>
                </div>
                <div class="multi-range-slider pt-1">
                    <div class="slider-track" id="desktopSliderTrack"></div>
                    <input type="range" id="desktopMinRange" min="{{ $absMin }}" max="{{ $absMax }}" value="{{ $currMin }}" step="10">
                    <input type="range" id="desktopMaxRange" min="{{ $absMin }}" max="{{ $absMax }}" value="{{ $currMax }}" step="10">
                    <input type="hidden" name="min_price" id="desktopMinPriceHidden" value="{{ $currMin }}">
                    <input type="hidden" name="max_price" id="desktopMaxPriceHidden" value="{{ $currMax }}">
                </div>
            </div>

            <div class="col-md-3">
                <label class="filter-label">მდგომარეობა</label>
                <select name="condition" class="form-select custom-select-styled">
                    <option value="">ყველა</option>
                    <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>ახალი</option>
                    <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>ახალივით</option>
                    <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>მეორადი</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="filter-label">ქვეყანა</label>
                <select name="country" class="form-select custom-select-styled">
                    <option value="">ყველა</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ strtoupper($country) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="filter-label">დალაგება</label>
                <select name="sort" class="form-select custom-select-styled">
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>ფასი: ზრდადი</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>ფასი: კლებადი</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2 pb-1">
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

    {{-- 2. ინფორმაციის პანელი და ხედის შეცვლა --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn-filter-mobile d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
            <i class="bi bi-sliders2 me-1"></i> ფილტრი
        </button>

        <div class="d-none d-lg-block">
            <p class="text-muted m-0"><small>ნაპოვნია <strong>{{ $products->count() }}</strong> პროდუქტი</small></p>
        </div>

        <div class="d-none d-md-flex gap-2">
            <button class="btn btn-sm btn-view-mode active rounded-3" data-view="grid" title="ბადე"><i class="bi bi-grid-fill"></i></button>
            <button class="btn btn-sm btn-view-mode rounded-3" data-view="list" title="სია"><i class="bi bi-list-ul"></i></button>
        </div>

        <div class="d-lg-none">
            <p class="text-muted m-0 small">ნაპოვნია <strong>{{ $products->count() }}</strong></p>
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
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 products-wrapper view-grid" id="productsContainer">
            @foreach($products as $product)
                @php
                    $countryCode = strtolower($product->supplier_country);
                    $conditionMap = [
                        'new'      => ['bg-success',        'ახალი',    'bi-star-fill'],
                        'like_new' => ['bg-info text-dark', 'ახალივით', 'bi-star-half'],
                        'used'     => ['bg-secondary',      'მეორადი',  'bi-tag-fill'],
                    ];
                    [$conditionClass, $conditionText, $conditionIcon] =
                        $conditionMap[$product->condition] ?? ['bg-secondary', 'მეორადი', 'bi-tag-fill'];
                @endphp

                <div class="col product-col">
                    <a href="{{ route('products.show', $product->slug) }}"
                       class="product-card card h-100 shadow-sm">

                        <div class="product-image-box">
                            <span class="badge {{ $conditionClass }} condition-badge shadow-sm">
                                <i class="bi {{ $conditionIcon }} me-1"></i>{{ $conditionText }}
                            </span>
                            <img
                                src="{{ $product->image ? asset('storage/' . $product->image) : asset('default-product.png') }}"
                                alt="{{ $product->name }}"
                                class="product-card-image"
                                loading="lazy"
                                width="300"
                                height="300"
                            >
                        </div>

                        <div class="product-card-info">
                            <div class="product-card-country">
                                <img src="https://flagcdn.com/w40/{{ $countryCode }}.png" alt="{{ $countryCode }}" loading="lazy" width="20" height="15">
                                <span>{{ strtoupper($product->supplier_country) }}</span>
                            </div>
                            <h5 class="product-card-title">{{ $product->name }}</h5>
                            <div class="product-card-footer">
                                <p class="product-card-price">₾ {{ number_format($product->price, 2) }}</p>
                                <i class="bi bi-arrow-right-circle-fill product-card-arrow"></i>
                            </div>
                        </div>

                    </a>
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
                    <label class="filter-label mb-0">ფასი</label>
                    <span class="filter-price-display" id="mobilePriceDisplay">{{ $currMin }} ₾ - {{ $currMax }} ₾</span>
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
                <label class="filter-label">მდგომარეობა</label>
                <select name="condition" class="form-select custom-select-styled py-2">
                    <option value="">ყველა</option>
                    <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>ახალი</option>
                    <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>ახალივით</option>
                    <option value="used" {{ request('condition') == 'used' ? 'selected' : '' }}>მეორადი</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="filter-label">ქვეყანა</label>
                <select name="country" class="form-select custom-select-styled py-2">
                    <option value="">ყველა</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ strtoupper($country) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="filter-label">დალაგება</label>
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