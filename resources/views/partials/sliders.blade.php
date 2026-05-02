{{-- ===== Hero Carousel — full-width, DB-driven ===== --}}
@if(isset($slides) && $slides->isNotEmpty())
<div class="hero-carousel-wrap">
    <div class="hero-carousel-frame">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000" data-bs-pause="false">
            @if($slides->count() > 1)
            <div class="carousel-indicators hero-indicators">
                @foreach($slides as $i => $slide)
                <button type="button"
                        data-bs-target="#mainCarousel"
                        data-bs-slide-to="{{ $i }}"
                        class="{{ $i === 0 ? 'active' : '' }}"
                        @if($i === 0) aria-current="true" @endif
                        aria-label="სლაიდი {{ $i + 1 }}"></button>
                @endforeach
            </div>
            @endif
            <div class="carousel-inner text-start">
                @foreach($slides as $i => $slide)
                <div class="carousel-item {{ $i === 0 ? 'active' : '' }} position-relative">
                    @if($slide->image)
                        <img src="{{ asset('storage/' . $slide->image) }}"
                             class="d-block w-100"
                             alt="{{ $slide->title }}"
                             {{ $i === 0 ? 'fetchpriority="high"' : 'loading="lazy"' }}>
                    @else
                        <div class="carousel-placeholder"></div>
                    @endif
                    <div class="overlay"></div>
                    <div class="carousel-caption caption-custom">
                        @if($i === 0)
                            <h1 class="slide-title">{{ $slide->title }}</h1>
                        @else
                            <h2 class="slide-title">{{ $slide->title }}</h2>
                        @endif
                        @if($slide->description)
                            <p class="slide-description">{{ $slide->description }}</p>
                        @endif
                        @if($slide->button_text && $slide->button_url)
                            <a href="{{ $slide->button_url }}" class="slide-cta-btn">
                                {{ $slide->button_text }} <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

{{-- ===== დანარჩენი კონტენტი — container-ში ===== --}}
<div class="container home-content-wrap">

<!-- კატეგორიების სლაიდერი -->
<div class="row mt-4 mb-2">
    <div class="col-12 section-header">
        <h2 class="section-title">კატეგორიები</h2>
        <div class="section-controls">
            <div class="category-slider-controls">
                <button class="btn btn-sm slider-ctrl category-control-prev" type="button" aria-label="წინა კატეგორია">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-sm slider-ctrl category-control-next" type="button" aria-label="შემდეგი კატეგორია">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="category-slider-container">
            <div class="category-slider">
                @foreach(\App\Models\Category::whereNotNull('slug')->get() as $category)
                    <div class="category-slide">
                        <a href="{{ route('category.products', $category->slug) }}" class="text-decoration-none category-link">
                            <div class="category-card">
                                <div class="category-image-wrapper">
                                    <div class="category-image-container">
                                        @if($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="category-image" loading="lazy">
                                        @else
                                            <img src="{{ asset('default-category.png') }}" alt="Default Image" class="category-image" loading="lazy">
                                        @endif
                                    </div>
                                </div>
                                <div class="category-content">
                                    <h3 class="category-title">{{ $category->name }}</h3>
                                    <div class="view-more">
                                        <span>ნახვა</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


<!-- პოპულარული პროდუქტები -->
<div class="row mt-5">
    <div class="col-12 section-header">
        <h2 class="section-title">პოპულარული</h2>
        <div class="section-controls">
            <a href="{{ route('products') }}" class="btn btn-sm btn-custom">ყველა</a>
            <div class="popular-slider-controls">
                <button class="btn btn-sm slider-ctrl popular-control-prev" type="button" aria-label="წინა პოპულარული პროდუქტი">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-sm slider-ctrl popular-control-next" type="button" aria-label="შემდეგი პოპულარული პროდუქტი">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="popular-slider-container">
            <div class="popular-slider">
                @foreach(\App\Models\Product::whereNotNull('slug')->orderBy('views_count', 'desc')->take(8)->get() as $product)
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
                    <div class="popular-slide">
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
                                    width="300" height="300"
                                >
                            </div>

                            <div class="product-card-info">
                                <div class="product-card-country">
                                    <img src="https://flagcdn.com/w40/{{ $countryCode }}.png" alt="{{ $countryCode }}" loading="lazy" width="20" height="15">
                                    <span>{{ strtoupper($product->supplier_country) }}</span>
                                </div>
                                <h3 class="product-card-title">{{ $product->name }}</h3>
                                <div class="product-card-footer">
                                    <p class="product-card-price">₾ {{ number_format($product->price, 2) }}</p>
                                    <i class="bi bi-arrow-right-circle-fill product-card-arrow"></i>
                                </div>
                            </div>

                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- ბოლოს დამატებული პროდუქტები -->
<div class="row mt-5">
    <div class="col-12 section-header">
        <h2 class="section-title">ბოლოს დამატებული</h2>
        <div class="section-controls">
            <a href="{{ route('products') }}" class="btn btn-sm btn-custom">ყველა</a>
            <div class="newest-slider-controls">
                <button class="btn btn-sm slider-ctrl newest-control-prev" type="button" aria-label="წინა დამატებული პროდუქტი">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="btn btn-sm slider-ctrl newest-control-next" type="button" aria-label="შემდეგი დამატებული პროდუქტი">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="newest-slider-container">
            <div class="newest-slider">
                @foreach(\App\Models\Product::whereNotNull('slug')->orderBy('created_at', 'desc')->take(8)->get() as $product)
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
                    <div class="newest-slide">
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
                                    width="300" height="300"
                                >
                            </div>

                            <div class="product-card-info">
                                <div class="product-card-country">
                                    <img src="https://flagcdn.com/w40/{{ $countryCode }}.png" alt="{{ $countryCode }}" loading="lazy" width="20" height="15">
                                    <span>{{ strtoupper($product->supplier_country) }}</span>
                                </div>
                                <h3 class="product-card-title">{{ $product->name }}</h3>
                                <div class="product-card-footer">
                                    <p class="product-card-price">₾ {{ number_format($product->price, 2) }}</p>
                                    <i class="bi bi-arrow-right-circle-fill product-card-arrow"></i>
                                </div>
                            </div>

                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12 section-header">
        <h2 class="section-title">მომხმარებელთა შეფასებები</h2>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="reviews-slider-container">
            <div class="reviews-slider">
                @foreach(\App\Models\Review::where('is_approved', true)->whereNull('product_id')->orderBy('created_at', 'desc')->take(10)->get() as $review)
                    <div class="reviews-slide">
                        <div class="review-card">
                            {{-- ვარსკვლავები ზევით --}}
                            <div class="review-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>
                            {{-- ტექსტი --}}
                            <div class="review-content">
                                <p>{{ $review->content }}</p>
                            </div>
                            {{-- ავტორი ქვევით --}}
                            <div class="review-header">
                                <div class="review-author">
                                    <div class="review-author-icon-wrapper">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div class="review-author-info">
                                        <h3 class="review-author-name">{{ $review->author_name }}</h3>
                                    </div>
                                </div>
                                <div class="review-date">
                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d.m.Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- პროფესიონალური რჩევები -->
<div class="blog-section container">
    <div class="row">
        <div class="col-12 section-header mb-4">
            <h2 class="section-title">პროფესიონალური რჩევები</h2>
            <a href="{{ route('blog.index') }}" class="btn btn-sm btn-custom">ყველა</a>
        </div>
    </div>
    <div class="blog-slider-container">
        <div class="blog-slider">
            @forelse(\App\Models\BlogPost::latest()->take(6)->get() as $post)
                <div class="blog-slider-item">
                    <article class="blog-card">
                        @if($post->image)
                            <div class="blog-card-img-container">
                                <img src="{{ asset('storage/' . $post->image) }}"
                                     class="blog-card-img"
                                     alt="{{ $post->title }}"
                                     loading="lazy">
                                <div class="blog-card-img-overlay"></div>
                            </div>
                        @endif
                        <div class="blog-card-body">
                            <h3 class="blog-card-title">{{ $post->title }}</h3>
                            <p class="blog-card-text">
                                {{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}
                            </p>
                            <div class="blog-card-footer">
                                <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-custom btn-sm">
                                    სრულად ნახვა
                                </a>
                                <div class="blog-date">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $post->created_at->format('d.m.Y') }}
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p>ახალი სტატიები მალე დაემატება</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ბრენდების სლაიდერი -->
<div class="row">
    <div class="col-12">
        <div class="brands-slider-container">
            <div class="brands-slider">
                <div class="brands-track">
                    <div class="brand-slide"><img src="/images/brands/images.jpg" alt="პარტნიორი ბრენდი" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/oven.png" alt="პარტნიორი ბრენდი" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/ciq.png" alt="CIQ" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/ai.png" alt="პარტნიორი ბრენდი" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/no.webp" alt="პარტნიორი ბრენდი" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/k.png" alt="პარტნიორი ბრენდი" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/aht.png" alt="AHT" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/images.jpg" alt="პარტნიორი ბრენდი" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/oven.png" alt="პარტნიორი ბრენდი" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/ciq.png" alt="CIQ" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/ai.png" alt="პარტნიორი ბრენდი" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/no.webp" alt="პარტნიორი ბრენდი" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/k.png" alt="პარტნიორი ბრენდი" class="brand-logo" loading="lazy"></div>
                    <div class="brand-slide"><img src="/images/brands/aht.png" alt="AHT" class="brand-logo" loading="lazy"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- შეფასების ღილაკი --}}
<div class="review-form-container mt-5">
    <button class="btn btn-primary review-toggle-btn" type="button" data-bs-toggle="modal" data-bs-target="#reviewModal">
        <i class="bi bi-star-fill me-2"></i>დატოვეთ შეფასება
    </button>
</div>

{{-- შეფასების Modal --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:18px; overflow:hidden;">

            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="reviewModalLabel">
                        <i class="bi bi-star-fill text-warning me-2"></i>შეფასების დატოვება
                    </h5>
                    <p class="text-muted small mb-0">თქვენი აზრი ჩვენთვის მნიშვნელოვანია</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="დახურვა"></button>
            </div>

            <div class="modal-body px-4 pb-4 pt-3">

                {{-- ფორმა --}}
                <div id="reviewFormWrap">
                    <form id="reviewAjaxForm" action="{{ route('reviews.store') }}" method="POST">
                        @csrf

                        <div class="mb-4 text-center">
                            <div class="modal-star-rating">
                                <input type="radio" id="mrating5" name="rating" value="5" checked>
                                <label for="mrating5"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="mrating4" name="rating" value="4">
                                <label for="mrating4"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="mrating3" name="rating" value="3">
                                <label for="mrating3"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="mrating2" name="rating" value="2">
                                <label for="mrating2"><i class="bi bi-star-fill"></i></label>
                                <input type="radio" id="mrating1" name="rating" value="1">
                                <label for="mrating1"><i class="bi bi-star-fill"></i></label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="text" class="form-control rounded-3"
                                   name="author_name" id="r_name" placeholder="თქვენი სახელი" required>
                            <div class="invalid-feedback" id="err_name"></div>
                        </div>

                        <div class="mb-3">
                            <input type="email" class="form-control rounded-3"
                                   name="author_email" placeholder="ელ. ფოსტა (არ გამოჩნდება)">
                        </div>

                        <div class="mb-4">
                            <textarea class="form-control rounded-3" name="content" id="r_content"
                                      rows="4" placeholder="დაწერეთ თქვენი შეფასება..." required></textarea>
                            <div class="invalid-feedback" id="err_content"></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-3 fw-bold" id="reviewSubmitBtn">
                            <i class="bi bi-send me-2"></i>გაგზავნა
                        </button>
                    </form>
                </div>

                {{-- დადასტურება --}}
                <div id="reviewSuccess" style="display:none; text-align:center; padding: 24px 0 16px;">
                    <div style="font-size:3.5rem; margin-bottom:12px;">✅</div>
                    <h5 class="fw-bold mb-2" style="color:#1a365d;">გმადლობთ შეფასებისთვის!</h5>
                    <p class="text-muted mb-4">თქვენი შეფასება მიღებულია და განხილვის შემდეგ გამოჩნდება.</p>
                    <button type="button" class="btn btn-primary rounded-3 px-4" data-bs-dismiss="modal">
                        დახურვა
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('reviewAjaxForm')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('reviewSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>იგზავნება...';

    // clear previous errors
    document.querySelectorAll('#reviewAjaxForm .form-control').forEach(el => el.classList.remove('is-invalid'));

    try {
        const res = await fetch(this.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('[name=_token]').value },
            body: new FormData(this)
        });
        const data = await res.json();

        if (res.ok) {
            document.getElementById('reviewFormWrap').style.display = 'none';
            document.getElementById('reviewSuccess').style.display = 'block';
        } else if (res.status === 422) {
            // validation errors
            Object.entries(data.errors || {}).forEach(([field, msgs]) => {
                const input = document.querySelector(`#reviewAjaxForm [name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const errEl = document.getElementById('err_' + field.replace('author_', ''));
                    if (errEl) errEl.textContent = msgs[0];
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

// reset modal on close
document.getElementById('reviewModal')?.addEventListener('hidden.bs.modal', function() {
    document.getElementById('reviewFormWrap').style.display = 'block';
    document.getElementById('reviewSuccess').style.display = 'none';
    document.getElementById('reviewAjaxForm').reset();
    const btn = document.getElementById('reviewSubmitBtn');
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-send me-2"></i>გაგზავნა';
    document.querySelectorAll('#reviewAjaxForm .form-control').forEach(el => el.classList.remove('is-invalid'));
});
</script>

{{-- container დახურვა --}}
</div>{{-- .home-content-wrap --}}