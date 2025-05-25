<!-- ფილტრის სვეტი -->
<div class="col-md-3 col-lg-2">
    <button class="btn btn-primary d-md-none w-100 mb-3 shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
        <i class="bi bi-funnel-fill me-1"></i> ფილტრის ჩვენება
    </button>

    <div class="d-none d-md-block bg-white p-3 rounded shadow-sm sticky-top" style="top: 20px;">
        <h5 class="text-center mb-3 fw-bold border-bottom pb-2">
            <i class="bi bi-sliders me-1"></i> ფილტრი
        </h5>
        <form method="GET" action="{{ route('category.products', $category->slug) }}">
            {{-- ტიპი --}}
            <div class="mb-3">
                <label class="form-label fw-medium">ტიპი</label>
                <select name="sub_type" class="form-select form-select-sm">
                    <option value="">ყველა ტიპი</option>
                    @foreach ($subTypes as $slug => $subType)
                        <option value="{{ $slug }}" {{ request('sub_type') == $slug ? 'selected' : '' }}>{{ $subType }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ფასი --}}
            <div class="mb-3">
                <label class="form-label fw-medium">ფასი</label>
                <div class="input-group input-group-sm mb-2">
                    <span class="input-group-text bg-light">მინ ₾</span>
                    <input type="number" name="min_price" class="form-control" value="{{ request('min_price') }}">
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">მაქს ₾</span>
                    <input type="number" name="max_price" class="form-control" value="{{ request('max_price') }}">
                </div>
            </div>

            {{-- დალაგება --}}
            <div class="mb-3">
                <label class="form-label fw-medium">დალაგება</label>
                <select name="sort" class="form-select form-select-sm">
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>ფასი: დაბალი → მაღალი</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>ფასი: მაღალი → დაბალი</option>
                </select>
            </div>

            {{-- მდგომარეობა --}}
            <div class="mb-3">
                <label class="form-label fw-medium">მდგომარეობა</label>
                <div class="d-flex flex-column gap-2">
                    @foreach ([
                        '' => 'ყველა',
                        'new' => 'ახალი',
                        'like_new' => 'ახალივით',
                        'used' => 'მეორადი'
                    ] as $value => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="condition" id="condition_{{ $value ?: 'all' }}" value="{{ $value }}" {{ request('condition') == $value ? 'checked' : '' }}>
                            <label class="form-check-label" for="condition_{{ $value ?: 'all' }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ქვეყანა --}}
            <div class="mb-3">
                <label class="form-label fw-medium">ქვეყანა</label>
                <select name="country" class="form-select form-select-sm">
                    <option value="">ყველა ქვეყანა</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                            {{ strtoupper($country) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">
                <i class="bi bi-search me-1"></i> ძიება
            </button>

            @if(request()->hasAny(['min_price', 'max_price', 'condition', 'country', 'sub_type']))
                <a href="{{ route('category.products', $category->slug) }}" class="btn btn-outline-secondary w-100 mt-2 btn-sm">
                    <i class="bi bi-x-circle me-1"></i> გასუფთავება
                </a>
            @endif
        </form>
    </div>
</div>

<!-- პროდუქტის სვეტი -->
<div class="col-md-9 col-lg-10">
    @if($products->isEmpty())
        <div class="alert alert-info text-center p-4">
            <i class="bi bi-info-circle fs-2 mb-3 d-block"></i>
            <h5>პროდუქტები ვერ მოიძებნა</h5>
            <p class="mb-0">გთხოვთ, შეცვალოთ ფილტრები და სცადოთ თავიდან.</p>
        </div>
    @else
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="m-0 text-muted"><small>ნაპოვნია {{ $products->count() }} პროდუქტი</small></p>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-secondary view-mode" data-view="grid" title="ბადისებური ხედი"><i class="bi bi-grid"></i></button>
                <button class="btn btn-sm btn-outline-secondary view-mode" data-view="list" title="სიის ხედი"><i class="bi bi-list-ul"></i></button>
            </div>
        </div>
        <div class="row g-3 products-grid">
            @foreach($products as $product)
                @php
                    $countryCode = strtolower($product->supplier_country);
                    $flagPath = "https://flagcdn.com/w40/" . $countryCode . ".png";
                    $conditionMap = [
                        'new' => ['bg-success', 'ახალი', 'bi-star-fill'],
                        'like_new' => ['bg-primary', 'ახალივით', 'bi-star-half'],
                        'used' => ['bg-warning', 'მეორადი', 'bi-tag'],
                    ];
                    [$conditionClass, $conditionText, $conditionIcon] = $conditionMap[$product->condition] ?? ['bg-warning', 'მეორადი', 'bi-tag'];
                @endphp
                <div class="col-6 col-md-6 col-lg-3 product-item">
                    <div class="popular-card h-100 position-relative">
                        <span class="condition-badge {{ $conditionClass }} text-white">
                            <i class="bi {{ $conditionIcon }}"></i> {{ $conditionText }}
                        </span>
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                            <div class="product-image-wrapper">
                                <div class="product-image-container">
                                    <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('default-product.png') }}"
                                        alt="{{ $product->name }}" class="product-image" loading="lazy">
                                </div>
                            </div>
                            <div class="product-content">
                                <div class="country-flag">
                                    <img src="{{ $flagPath }}" alt="{{ strtoupper($product->supplier_country) }} flag" width="20" height="15">
                                    <span class="country-code">{{ strtoupper($product->supplier_country) }}</span>
                                </div>
                                <h5 class="product-title">{{ $product->name }}</h5>
                                <p class="price">{{ number_format($product->price, 2) }} ₾</p>
                                <div class="view-more">
                                    <span>დეტალები</span>
                                    <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Offcanvas მობილური ფილტრი -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="filterOffcanvas">
    <div class="offcanvas-header border-bottom">
        <h5 class="fw-bold"><i class="bi bi-sliders me-2"></i> პროდუქტის ფილტრი</h5>
        <button class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        {{-- იგივე ფორმა მობილურისთვის --}}
        <form method="GET" action="{{ route('category.products', $category->slug) }}">
            {{-- ტიპი --}}
            <div class="mb-3">
                <label class="form-label fw-medium">ტიპი</label>
                <select name="sub_type" class="form-select form-select-sm">
                    <option value="">ყველა ტიპი</option>
                    @foreach ($subTypes as $slug => $subType)
                        <option value="{{ $slug }}" {{ request('sub_type') == $slug ? 'selected' : '' }}>{{ $subType }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ფასი --}}
            <div class="mb-3">
                <label class="form-label fw-medium">ფასი</label>
                <div class="input-group input-group-sm mb-2">
                    <span class="input-group-text bg-light">მინ ₾</span>
                    <input type="number" name="min_price" class="form-control" value="{{ request('min_price') }}">
                </div>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light">მაქს ₾</span>
                    <input type="number" name="max_price" class="form-control" value="{{ request('max_price') }}">
                </div>
            </div>

            {{-- დალაგება --}}
            <div class="mb-3">
                <label class="form-label fw-medium">დალაგება</label>
                <select name="sort" class="form-select form-select-sm">
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>ფასი: დაბალი → მაღალი</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>ფასი: მაღალი → დაბალი</option>
                </select>
            </div>

            {{-- მდგომარეობა --}}
            <div class="mb-3">
                <label class="form-label fw-medium">მდგომარეობა</label>
                <div class="d-flex flex-column gap-2">
                    @foreach ([
                        '' => 'ყველა',
                        'new' => 'ახალი',
                        'like_new' => 'ახალივით',
                        'used' => 'მეორადი'
                    ] as $value => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="condition" id="condition_{{ $value ?: 'all' }}" value="{{ $value }}" {{ request('condition') == $value ? 'checked' : '' }}>
                            <label class="form-check-label" for="condition_{{ $value ?: 'all' }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ქვეყანა --}}
            <div class="mb-3">
                <label class="form-label fw-medium">ქვეყანა</label>
                <select name="country" class="form-select form-select-sm">
                    <option value="">ყველა ქვეყანა</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                            {{ strtoupper($country) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">
                <i class="bi bi-search me-1"></i> ძიება
            </button>

            @if(request()->hasAny(['min_price', 'max_price', 'condition', 'country', 'sub_type']))
                <a href="{{ route('category.products', $category->slug) }}" class="btn btn-outline-secondary w-100 mt-2 btn-sm">
                    <i class="bi bi-x-circle me-1"></i> გასუფთავება
                </a>
            @endif
        </form>
    </div>
</div>
