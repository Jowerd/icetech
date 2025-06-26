<div class="container-fluid product-page-container px-0">
    <div class="container product-main-content py-4">
        <div class="row g-4 product-details-row">

            {{-- პირველი სვეტი (დესკტოპზე მარცხენა, მობილურზე ზედა) --}}
            <div class="col-12 col-lg-7 order-lg-1 d-flex flex-column">

                {{-- პროდუქტის სურათი --}}
                <div class="product-image-wrapper card shadow-sm mb-4">
                    <img
                        src="{{ $product->image ? asset('storage/' . $product->image) : asset('default-product.png') }}"
                        alt="{{ $product->name }}"
                        class="img-fluid rounded product-image"
                    >
                    <div class="product-views">
                        <i class="bi bi-eye-fill"></i> {{ number_format($product->views_count ?? 0) }} ნახვა
                    </div>
                </div>

                {{-- პროდუქტის მახასიათებლების სექცია (მხოლოდ დესკტოპისთვის - ფოტოს ქვემოთ) --}}
                @if(isset($product->features) && is_array($product->features) && !empty($product->features))
                    <div class="product-features-section card mb-4 p-3 shadow-sm d-none d-lg-block">
                        <h4 class="card-title mb-3 feature-section-title">
                            <i class="bi bi-card-list me-2"></i> პროდუქტის მახასიათებლები
                        </h4>
                        <ul class="features-list list-unstyled mb-0">
                            @foreach($product->features as $feature)
                                @if(!empty($feature['name']) && !empty($feature['value']))
                                    <li class="feature-item mb-2 d-flex justify-content-between align-items-baseline">
                                        <span class="feature-name fw-semibold text-muted">{{ $feature['name'] }}:</span>
                                        <span class="feature-value text-end">{{ $feature['value'] }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>

            {{-- მეორე სვეტი (დესკტოპზე მარჯვენა, მობილურზე მეორე) --}}
            <div class="col-12 col-lg-5 order-lg-2">
                <div class="product-info-panel">
                    {{-- პროდუქტის სახელი --}}
                    <h1 class="product-title mb-3">{{ $product->name }}</h1>

                    {{-- ფასი და დროშა --}}
                    <div class="price-flag-container">
                        <h3 class="product-price mb-0">₾ {{ number_format($product->price, 2) }}</h3>
                        <span class="country-flag" title="მწარმოებელი ქვეყანა: {{ config('countries.list')[strtoupper($product->supplier_country)] ?? $product->supplier_country }}">
                            <img src="https://flagcdn.com/24x18/{{ strtolower($product->supplier_country) }}.png"
                                alt="{{ $product->supplier_country }} Flag"
                                class="country-flag-img shadow-sm">
                        </span>
                    </div>

                    {{-- მდგომარეობა --}}
                    @if($product->condition)
                        <div class="product-condition-display mb-4">
                            <strong>მდგომარეობა:</strong>
                            <span class="condition-value">
                                @if($product->condition == 'new') ახალი
                                @elseif($product->condition == 'used') მეორადი
                                @elseif($product->condition == 'like_new') ახალივით
                                @else {{ $product->condition }}
                                @endif
                            </span>
                        </div>
                    @endif

                    {{-- პროდუქტის აღწერა --}}
                    <div class="product-description-wrapper description-collapsed mb-3">
                        <div class="product-description">
                            {!! $product->description !!}
                        </div>
                        <div class="description-gradient"></div>
                    </div>

                    <button class="show-more-description mb-4">
                        <span class="show-more-description-text">სრულად ნახვა</span>
                        <span class="show-less-description-text">ნაკლები</span>
                    </button>

                    {{-- პროდუქტის მახასიათებლების სექცია (მხოლოდ მობილურისთვის - ახლა აქ) --}}
                    @if(isset($product->features) && is_array($product->features) && !empty($product->features))
                        <div class="product-features-section card mb-4 p-3 shadow-sm d-block d-lg-none">
                            <h4 class="card-title mb-3 feature-section-title">
                                <i class="bi bi-card-list me-2"></i> პროდუქტის მახასიათებლები
                            </h4>
                            <ul class="features-list list-unstyled mb-0">
                                @foreach($product->features as $feature)
                                    @if(!empty($feature['name']) && !empty($feature['value']))
                                        <li class="feature-item mb-2 d-flex justify-content-between align-items-baseline">
                                            <span class="feature-name fw-semibold text-muted">{{ $feature['name'] }}:</span>
                                            <span class="feature-value text-end">{{ $feature['value'] }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- გაზიარების ღილაკები (მობილურზე ბოლოში) --}}
                    <div class="social-share-buttons card p-3 shadow-sm mb-4">
                        <p class="share-title mb-2 fw-semibold">გააზიარე პროდუქტი:</p>
                        <div class="share-buttons-container">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                            target="_blank"
                            class="share-button facebook-share" title="გააზიარეთ Facebook-ზე">
                            <i class="fab fa-facebook-f"></i>
                            </a>

                            <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . request()->url()) }}"
                            target="_blank"
                            class="share-button whatsapp-share" title="გააზიარეთ WhatsApp-ზე">
                                <i class="fab fa-whatsapp"></i>
                            </a>

                            <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->name) }}"
                            target="_blank"
                            class="share-button telegram-share" title="გააზიარეთ Telegram-ზე">
                                <i class="fab fa-telegram-plane"></i>
                            </a>

                            <button class="share-button copy-link" data-url="{{ request()->url() }}" title="ბმულის კოპირება">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>

                    {{-- პროდუქტის ვიდეო (მობილურზე ბოლოში) --}}
                    @if($product->video_link)
                        <div class="product-video text-center mb-4">
                            <a href="{{ $product->video_link }}" target="_blank" class="btn btn-primary product-video-link w-100 py-2">
                                <i class="bi bi-play-circle-fill me-2"></i> ნახეთ პროდუქტის ვიდეო
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($similarProducts) && $similarProducts->count() > 0)
<div class="container-fluid similar-products-section px-0 py-5">
    <div class="container">
        <h3 class="section-title mb-4 text-center">მსგავსი პროდუქტები</h3>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 similar-products-container">
            @foreach($similarProducts as $key => $similarProduct)
                <div class="col similar-product-item {{ $key >= 5 ? 'hidden-desktop' : '' }} {{ $key >= 2 ? 'hidden-mobile' : '' }}">
                    <a href="{{ route('products.show', ['product' => $similarProduct->slug]) }}" class="similar-product-card card h-100 shadow-sm">
                        <div class="similar-product-image-wrapper">
                            <img
                                src="{{ $similarProduct->image ? asset('storage/' . $similarProduct->image) : asset('default-product.png') }}"
                                alt="{{ $similarProduct->name }}"
                                class="similar-product-image"
                                loading="lazy"
                            >
                        </div>
                        <div class="similar-product-info card-body p-2 d-flex flex-column">
                            <h5 class="similar-product-title mb-2">{{ $similarProduct->name }}</h5>
                            <p class="similar-product-price mt-auto">₾ {{ number_format($similarProduct->price, 2) }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        @if($similarProducts->count() > 5)
        <div class="text-center mt-5">
            <button class="btn btn-outline-primary rounded-pill px-4 py-2 show-more-products">
                <span class="show-more-text">მეტის ნახვა <i class="bi bi-chevron-down ms-2"></i></span>
                <span class="show-less-text">ნაკლების ნახვა <i class="bi bi-chevron-up ms-2"></i></span>
            </button>
        </div>
        @endif
    </div>
</div>
@endif