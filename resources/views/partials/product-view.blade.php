@php
    $bc = [['label' => 'პროდუქცია', 'url' => route('products')]];
    if ($product->category) {
        $bc[] = ['label' => $product->category->name, 'url' => route('category.products', $product->category->slug)];
    }
    $bc[] = ['label' => $product->name, 'url' => ''];
@endphp
<div class="pt-3">
    @include('partials.breadcrumb', ['crumbs' => $bc])
</div>

{{-- პროდუქტის მთავარი კონტეინერი --}}
<div class="product-page-container">
    <div class="product-main-content">
        <div class="row g-4 product-details-row">

            {{-- პირველი სვეტი (დესკტოპზე მარცხენა, მობილურზე ზედა) --}}
            <div class="col-12 col-lg-7 order-lg-1 d-flex flex-column">

                {{-- პროდუქტის სურათი + გალერეა --}}
                @php
                    $mainImage    = $product->image ? asset('storage/' . $product->image) : asset('default-product.png');
                    $galleryImages = $product->images ?? collect();
                    $allImages    = collect([['src' => $mainImage, 'alt' => $product->name]])
                        ->concat($galleryImages->map(fn($img) => ['src' => asset('storage/' . $img->image), 'alt' => $product->name]));
                @endphp

                <div class="product-image-wrapper card shadow-sm mb-2">
                    <img
                        id="productMainImage"
                        src="{{ $mainImage }}"
                        alt="{{ $product->name }}"
                        class="img-fluid rounded product-image"
                    >
                    <div class="product-views">
                        <i class="bi bi-eye-fill"></i> {{ number_format($product->views_count ?? 0) }} ნახვა
                    </div>
                </div>

                @if($allImages->count() > 1)
                <div class="product-gallery-thumbs mb-4">
                    @foreach($allImages as $i => $img)
                        <button type="button"
                                class="gallery-thumb {{ $i === 0 ? 'active' : '' }}"
                                data-src="{{ $img['src'] }}"
                                title="{{ $img['alt'] }}">
                            <img src="{{ $img['src'] }}" alt="{{ $img['alt'] }}" loading="lazy">
                        </button>
                    @endforeach
                </div>
                @endif

                {{-- პროდუქტის მახასიათებლები (მხოლოდ დესკტოპი) --}}
                @if($product->features_text)
                    <div class="product-features-section card mb-4 p-3 shadow-sm d-none d-lg-block">
                        <h4 class="card-title mb-3 feature-section-title">
                            <i class="bi bi-card-list me-2"></i> პროდუქტის მახასიათებლები
                        </h4>
                        <ul class="features-list list-unstyled mb-0">
                            @foreach(explode("\n", $product->features_text) as $line)
                                @if(trim($line))
                                    @php
                                        $parts = str_contains($line, ':') ? explode(':', $line, 2) : [null, $line];
                                        $name  = trim($parts[0]);
                                        $value = trim($parts[1]);
                                    @endphp
                                    <li class="feature-item mb-2 d-flex justify-content-between align-items-baseline">
                                        @if($name)
                                            <span class="feature-name fw-semibold text-muted">{{ $name }}:</span>
                                        @endif
                                        <span class="feature-value text-end">{{ $value }}</span>
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

                    {{-- პროდუქტის მახასიათებლები (მხოლოდ მობილური) --}}
                    @if($product->features_text)
                        <div class="product-features-section card mb-4 p-3 shadow-sm d-block d-lg-none">
                            <h4 class="card-title mb-3 feature-section-title">
                                <i class="bi bi-card-list me-2"></i> პროდუქტის მახასიათებლები
                            </h4>
                            <ul class="features-list list-unstyled mb-0">
                                @foreach(explode("\n", $product->features_text) as $line)
                                    @if(trim($line))
                                        @php
                                            $parts = str_contains($line, ':') ? explode(':', $line, 2) : [null, $line];
                                            $name  = trim($parts[0]);
                                            $value = trim($parts[1]);
                                        @endphp
                                        <li class="feature-item mb-2 d-flex justify-content-between align-items-baseline">
                                            @if($name)
                                                <span class="feature-name fw-semibold text-muted">{{ $name }}:</span>
                                            @endif
                                            <span class="feature-value text-end">{{ $value }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- შედარების ღილაკი --}}
                    <button class="btn-compare-full mb-3"
                        onclick="compareToggle(this)"
                        data-id="{{ $product->id }}"
                        data-slug="{{ $product->slug }}"
                        data-name="{{ $product->name }}"
                        data-image="{{ $product->image ? asset('storage/'.$product->image) : asset('default-product.png') }}"
                        data-price="{{ number_format($product->price, 2) }}"
                        title="შედარებაში დამატება">
                        <i class="bi bi-arrow-left-right me-2"></i>
                        <span class="compare-btn-text">შედარებაში დამატება</span>
                    </button>

                    {{-- გაზიარების ღილაკები --}}
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

                    {{-- პროდუქტის ვიდეო --}}
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

{{-- =========================================
     შეფასებები
========================================= --}}
@php
    $productReviews = $product->reviews()->get();
    $avgRating      = $productReviews->avg('rating');
    $totalReviews   = $productReviews->count();
    $starCounts     = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
    foreach ($productReviews as $r) { $starCounts[$r->rating] = ($starCounts[$r->rating] ?? 0) + 1; }
@endphp

{{-- JSON-LD: Product + AggregateRating (Google Rich Snippets) --}}
@if($totalReviews > 0)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ addslashes($product->name) }}",
    "description": "{{ addslashes(strip_tags($product->description ?? '')) }}",
    "image": "{{ $product->image ? asset('storage/' . $product->image) : asset('default-product.png') }}",
    "url": "{{ route('products.show', $product) }}",
    "brand": { "@type": "Brand", "name": "IceTech" },
    "offers": {
        "@type": "Offer",
        "priceCurrency": "GEL",
        "price": "{{ number_format($product->price, 2, '.', '') }}",
        "availability": "https://schema.org/InStock",
        "url": "{{ route('products.show', $product) }}"
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ number_format($avgRating, 1, '.', '') }}",
        "reviewCount": "{{ $totalReviews }}",
        "bestRating": "5",
        "worstRating": "1"
    },
    "review": [
        @foreach($productReviews as $review)
        {
            "@type": "Review",
            "author": { "@type": "Person", "name": "{{ addslashes($review->author_name) }}" },
            "datePublished": "{{ $review->created_at->toDateString() }}",
            "reviewBody": "{{ addslashes($review->content) }}",
            "reviewRating": {
                "@type": "Rating",
                "ratingValue": "{{ $review->rating }}",
                "bestRating": "5",
                "worstRating": "1"
            }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endif

<section class="product-reviews-section">
    <div>

        {{-- სექციის სათაური --}}
        <div class="prs-top">
            <h2 class="prs-heading">მომხმარებელთა შეფასებები</h2>
            <button class="prs-write-btn" data-bs-toggle="modal" data-bs-target="#productReviewModal">
                <i class="bi bi-pencil-square me-2"></i>შეფასების დატოვება
            </button>
        </div>

        @if($totalReviews > 0)
        {{-- რეიტინგის სარეზიუმე --}}
        <div class="prs-summary">
            <div class="prs-summary-score">
                <div class="prs-big-num">{{ number_format($avgRating, 1) }}</div>
                <div class="prs-big-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= round($avgRating) ? 'bi-star-fill' : ($i - 0.5 <= $avgRating ? 'bi-star-half' : 'bi-star') }}"></i>
                    @endfor
                </div>
                <div class="prs-total-label">{{ $totalReviews }} შეფასება</div>
            </div>
            <div class="prs-summary-bars">
                @for($star = 5; $star >= 1; $star--)
                    @php $pct = $totalReviews > 0 ? round($starCounts[$star] / $totalReviews * 100) : 0; @endphp
                    <div class="prs-bar-row">
                        <span class="prs-bar-label">{{ $star }} <i class="bi bi-star-fill"></i></span>
                        <div class="prs-bar-track">
                            <div class="prs-bar-fill" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="prs-bar-count">{{ $starCounts[$star] }}</span>
                    </div>
                @endfor
            </div>
        </div>

        {{-- კომენტარების სია --}}
        <div class="prs-comments">
            @foreach($productReviews as $review)
            <div class="prs-comment">
                <div class="prs-comment-avatar">{{ mb_strtoupper(mb_substr($review->author_name, 0, 1)) }}</div>
                <div class="prs-comment-body">
                    <div class="prs-comment-header">
                        <strong class="prs-comment-name">{{ $review->author_name }}</strong>
                        <div class="prs-comment-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                        </div>
                        <span class="prs-comment-date">{{ $review->created_at->format('d M, Y') }}</span>
                    </div>
                    <p class="prs-comment-text">{{ $review->content }}</p>
                </div>
            </div>
            @endforeach
        </div>

        @else
        {{-- ცარიელი მდგომარეობა --}}
        <div class="prs-empty">
            <div class="prs-empty-icon"><i class="bi bi-chat-heart"></i></div>
            <p class="prs-empty-title">ჯერ შეფასება არ დატოვებულა</p>
            <p class="prs-empty-sub">გაუზიარეთ სხვებს თქვენი გამოცდილება ამ პროდუქტთან</p>
            <button class="prs-write-btn" data-bs-toggle="modal" data-bs-target="#productReviewModal">
                <i class="bi bi-pencil-square me-2"></i>პირველი შეფასება დატოვეთ
            </button>
        </div>
        @endif

    </div>
</section>

{{-- შეფასების Modal --}}
<div class="modal fade" id="productReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content prs-modal-content">

            <div class="prs-modal-header">
                <div>
                    <h5 class="prs-modal-title"><i class="bi bi-star-fill me-2"></i>შეფასების დატოვება</h5>
                    <p class="prs-modal-sub">{{ $product->name }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="prs-modal-body">
                <div id="prsFormWrap">
                    <form id="prsAjaxForm" action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        {{-- ვარსკვლავები --}}
                        <div class="prs-modal-stars-wrap">
                            <p class="prs-modal-stars-label">შეარჩიეთ შეფასება</p>
                            <div class="modal-star-rating">
                                @for($s = 5; $s >= 1; $s--)
                                    <input type="radio" id="prating{{ $s }}" name="rating" value="{{ $s }}" {{ $s === 5 ? 'checked' : '' }}>
                                    <label for="prating{{ $s }}"><i class="bi bi-star-fill"></i></label>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control prs-input"
                                   name="author_name" placeholder="თქვენი სახელი *" required>
                            <div class="invalid-feedback" id="prs_err_name"></div>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control prs-input"
                                   name="author_email" placeholder="ელ. ფოსტა (სურვილისამებრ, არ გამოჩნდება)">
                        </div>
                        <div class="mb-4">
                            <textarea class="form-control prs-input" name="content" rows="4"
                                      placeholder="გაგვიზიარეთ თქვენი გამოცდილება ამ პროდუქტთან... *" required></textarea>
                            <div class="invalid-feedback" id="prs_err_content"></div>
                        </div>

                        <button type="submit" class="prs-submit-btn" id="prsSubmitBtn">
                            <i class="bi bi-send me-2"></i>გაგზავნა
                        </button>
                    </form>
                </div>

                <div id="prsSuccess" class="prs-success-wrap" style="display:none;">
                    <div class="prs-success-icon">✅</div>
                    <h5 class="prs-success-title">გმადლობთ!</h5>
                    <p class="prs-success-text">თქვენი შეფასება მიღებულია და გამოქვეყნდება განხილვის შემდეგ.</p>
                    <button type="button" class="prs-write-btn" data-bs-dismiss="modal">დახურვა</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
(function() {
    const form = document.getElementById('prsAjaxForm');
    if (!form) return;
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('prsSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>იგზავნება...';
        form.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('[name=_token]').value },
                body: new FormData(form)
            });
            if (res.ok) {
                document.getElementById('prsFormWrap').style.display = 'none';
                document.getElementById('prsSuccess').style.display = 'flex';
            } else if (res.status === 422) {
                const data = await res.json();
                Object.entries(data.errors || {}).forEach(([field, msgs]) => {
                    const input = form.querySelector('[name="' + field + '"]');
                    if (input) {
                        input.classList.add('is-invalid');
                        const key = field.replace('author_', '');
                        const err = document.getElementById('prs_err_' + key);
                        if (err) err.textContent = msgs[0];
                    }
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-send me-2"></i>გაგზავნა';
            }
        } catch {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send me-2"></i>გაგზავნა';
        }
    });

    document.getElementById('productReviewModal')?.addEventListener('hidden.bs.modal', function() {
        document.getElementById('prsFormWrap').style.display = 'block';
        document.getElementById('prsSuccess').style.display = 'none';
        form.reset();
        const btn = document.getElementById('prsSubmitBtn');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send me-2"></i>გაგზავნა';
        form.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
    });
})();
</script>

{{-- =========================================
     მსგავსი პროდუქტები
========================================= --}}
@if(isset($similarProducts) && $similarProducts->count() > 0)
<div class="similar-products-section py-5">
    <div>
        <h3 class="section-title mb-4 text-center">მსგავსი პროდუქტები</h3>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 similar-products-container">
            @foreach($similarProducts as $key => $similarProduct)
                @php
                    $countryCode = strtolower($similarProduct->supplier_country);
                    $conditionMap = [
                        'new'      => ['bg-success',          'ახალი',    'bi-star-fill'],
                        'like_new' => ['bg-info text-dark',   'ახალივით', 'bi-star-half'],
                        'used'     => ['bg-secondary',        'მეორადი',  'bi-tag-fill'],
                    ];
                    [$conditionClass, $conditionText, $conditionIcon] =
                        $conditionMap[$similarProduct->condition] ?? ['bg-secondary', 'მეორადი', 'bi-tag-fill'];
                @endphp

                <div class="col product-col {{ $key >= 5 ? 'hidden-desktop' : '' }} {{ $key >= 2 ? 'hidden-mobile' : '' }}">
                    <a href="{{ route('products.show', ['product' => $similarProduct->slug]) }}"
                       class="product-card card h-100 shadow-sm">

                        <div class="product-image-box">
                            <span class="badge {{ $conditionClass }} condition-badge shadow-sm">
                                <i class="bi {{ $conditionIcon }} me-1"></i>{{ $conditionText }}
                            </span>
                            <img
                                src="{{ $similarProduct->image ? asset('storage/' . $similarProduct->image) : asset('default-product.png') }}"
                                alt="{{ $similarProduct->name }}"
                                class="product-card-image"
                                loading="lazy"
                            >
                        </div>

                        <div class="product-card-info">
                            <div class="product-card-country">
                                <img src="https://flagcdn.com/w40/{{ $countryCode }}.png" alt="{{ $countryCode }}">
                                <span>{{ strtoupper($similarProduct->supplier_country) }}</span>
                            </div>
                            <h5 class="product-card-title">{{ $similarProduct->name }}</h5>
                            <div class="product-card-footer">
                                <p class="product-card-price">₾ {{ number_format($similarProduct->price, 2) }}</p>
                                <i class="bi bi-arrow-right-circle-fill product-card-arrow"></i>
                            </div>
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