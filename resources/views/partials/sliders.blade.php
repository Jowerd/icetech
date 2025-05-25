<div class="row">
    <!-- მთავარი სლაიდერი -->
    <div class="col-12">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <!-- მთავარი სლაიდერის ინდიკატორები წაშლილია -->
            <div class="carousel-inner text-start">
                <div class="carousel-item active position-relative">
                    <img src="/images/samz.webp" class="d-block w-100" alt="პროფესიონალური სამზარეულო">
                    <div class="overlay"></div>
                    <div class="carousel-caption caption-custom">
                        <h5 class="slide-title">პროფესიონალური სამზარეულო</h5>
                        <p class="slide-description">კომერციული სამზარეულოს სრულყოფილი აღჭურვილობა მაღალი სტანდარტებით.</p>
                    </div>
                </div>
                <div class="carousel-item position-relative">
                    <img src="/images/tuza.webp" class="d-block w-100" alt="მაქსიმალური სისუფთავე">
                    <div class="overlay"></div>
                    <div class="carousel-caption caption-custom">
                        <h5 class="slide-title">მაქსიმალური სისუფთავე</h5>
                        <p class="slide-description">ყველა ჰიგიენური მოთხოვნის დაცვით მოწყობილი სივრცე პროფესიონალებისთვის.</p>
                    </div>
                </div>
                <div class="carousel-item position-relative">
                    <img src="/images/utas.webp" class="d-block w-100" alt="მაღალი ხარისხის ტექნიკა">
                    <div class="overlay"></div>
                    <div class="carousel-caption caption-custom">
                        <h5 class="slide-title">მაღალი ხარისხის ტექნიკა</h5>
                        <p class="slide-description">უსაფრთხო, გამძლე და ეფექტური ტექნიკა პროფესიონალური სამუშაოსთვის.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">წინა</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">შემდეგი</span>
            </button>
        </div>
    </div>
</div>

<!-- კატეგორიების სლაიდერი -->
<div class="row mt-3 mb-2">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="text-uppercase mb-0">კატეგორიები</h2> <!-- fw-bold წაშლილია -->
        <div class="category-slider-controls">
            <button class="btn btn-sm category-control-prev" type="button">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="btn btn-sm category-control-next" type="button">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="category-slider-container">
            <div class="category-slider">
                @foreach(\App\Models\Category::all() as $category)
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
                                    <h5 class="category-title">{{ $category->name }}</h5>
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
            <!-- კატეგორიების სლაიდერის ინდიკატორები წაშლილია -->
        </div>
    </div>
</div>


<!-- პოპულარული პროდუქტები -->
<div class="row mt-5">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="text-uppercase mb-0">პოპულარული</h2> <!-- fw-bold წაშლილია -->
        <div class="popular-slider-controls">
            <button class="btn btn-sm popular-control-prev" type="button">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="btn btn-sm popular-control-next" type="button">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="popular-slider-container">
            <div class="popular-slider">
@foreach(\App\Models\Product::orderBy('views_count', 'desc')->take(10)->get() as $product)
    @php
        $countryCode = strtolower($product->supplier_country);
        $flagPath = "https://flagcdn.com/w40/" . $countryCode . ".png";
        
        $conditionClass = 'bg-warning';
        $conditionText = 'მეორადი';
        $conditionIcon = 'bi-tag';
        if ($product->condition === 'new') {
            $conditionClass = 'bg-success';
            $conditionText = 'ახალი';
            $conditionIcon = 'bi-star-fill';
        } elseif ($product->condition === 'like_new') {
            $conditionClass = 'bg-primary';
            $conditionText = 'ახალივით';
            $conditionIcon = 'bi-star-half';
        }
    @endphp
    <div class="popular-slide">
        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
            <div class="popular-card">
                <span class="condition-badge {{ $conditionClass }} text-white">
                    <i class="bi {{ $conditionIcon }}"></i> {{ $conditionText }}
                </span>
                <div class="product-image-wrapper">
                    <div class="product-image-container">
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('default-product.png') }}"
                             alt="{{ $product->name }}"
                             class="product-image">
                    </div>
                </div>
                <div class="product-content">
                    <div class="country-flag">
                        <img src="{{ $flagPath }}" alt="Country Flag" width="20" height="15">
                        <span class="country-code">{{ strtoupper($product->supplier_country) }}</span>
                    </div>
                    <h5 class="product-title">{{ $product->name }}</h5>
                    <p class="price">{{ number_format($product->price, 2) }} ₾</p>
                    <div class="view-more">
                        <span>დეტალები</span>
                        <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
                @endforeach
            </div>
            <!-- პოპულარული პროდუქტების სლაიდერის ინდიკატორები წაშლილია -->
        </div>
    </div>
</div>

<!-- ბოლოს დამატებული პროდუქტები -->
<div class="row mt-5">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="text-uppercase mb-0">ბოლოს დამატებული</h2>
        <div class="newest-slider-controls">
            <button class="btn btn-sm newest-control-prev" type="button">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="btn btn-sm newest-control-next" type="button">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="newest-slider-container">
            <div class="newest-slider">
                @foreach(\App\Models\Product::orderBy('created_at', 'desc')->take(10)->get() as $product)
                    @php
                        $countryCode = strtolower($product->supplier_country);
                        $flagPath = "https://flagcdn.com/w40/" . $countryCode . ".png";
                        
                        $conditionClass = 'bg-warning';
                        $conditionText = 'მეორადი';
                        $conditionIcon = 'bi-tag';
                        if ($product->condition === 'new') {
                            $conditionClass = 'bg-success';
                            $conditionText = 'ახალი';
                            $conditionIcon = 'bi-star-fill';
                        } elseif ($product->condition === 'like_new') {
                            $conditionClass = 'bg-primary';
                            $conditionText = 'ახალივით';
                            $conditionIcon = 'bi-star-half';
                        }
                        
                        // დამატების თარიღის ფორმატირება
                        $createdDate = \Carbon\Carbon::parse($product->created_at);
                        $formattedDate = $createdDate->format('d.m.Y');
                        
                        // კლასი ახალი პროდუქტებისთვის
                        $dateClass = '';
                        if ($createdDate->isToday()) {
                            $dateClass = 'today';
                        } elseif ($createdDate->isYesterday()) {
                            $dateClass = 'yesterday';
                        } elseif ($createdDate->diffInDays(now()) < 7) {
                            $dateClass = 'recent';
                        }
                    @endphp
                    <div class="newest-slide">
                        <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none">
                            <div class="popular-card">
                                <span class="condition-badge {{ $conditionClass }} text-white">
                                    <i class="bi {{ $conditionIcon }}"></i> {{ $conditionText }}
                                </span>
                                
                                
                                <div class="product-image-wrapper">
                                    <div class="product-image-container">
                                        <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('default-product.png') }}"
                                             alt="{{ $product->name }}"
                                             class="product-image">
                                    </div>
                                </div>
                                <div class="product-content">
                                    <div class="country-flag">
                                        <img src="{{ $flagPath }}" alt="Country Flag" width="20" height="15">
                                        <span class="country-code">{{ strtoupper($product->supplier_country) }}</span>
                                    </div>
                                    <h5 class="product-title">{{ $product->name }}</h5>
                                    <p class="price">{{ number_format($product->price, 2) }} ₾</p>
                                    <div class="view-more">
                                        <span>დეტალები</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <!-- ბოლოს დამატებული პროდუქტების სლაიდერის ინდიკატორები წაშლილია -->
        </div>
    </div>
</div>
<!-- შეფასებების კარუსელი (მთავარ გვერდზე დასამატებლად) -->
<div class="row mt-5">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="text-uppercase mb-0">მომხმარებელთა შეფასებები</h2>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="reviews-slider-container">
            <div class="reviews-slider">
                @foreach(\App\Models\Review::where('is_approved', true)->orderBy('created_at', 'desc')->take(10)->get() as $review)
                    <div class="reviews-slide">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="review-author">
                                    @if($review->image)
                                        <img src="{{ asset('storage/' . $review->image) }}" alt="{{ $review->author_name }}" class="review-author-image">
                                    @else
                                        <div class="review-author-initial">{{ substr($review->author_name, 0, 1) }}</div>
                                    @endif
                                    <div class="review-author-info">
                                        <h5 class="review-author-name">{{ $review->author_name }}</h5>
                                        <div class="review-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <div class="review-date">
                                    {{ \Carbon\Carbon::parse($review->created_at)->format('d.m.Y') }}
                                </div>
                            </div>
                            <div class="review-content">
                                <p>{{ $review->content }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- შეფასებების სლაიდერის ინდიკატორები წაშლილია -->
        </div>
    </div>
</div>
<!-- პროფესიონალური რჩევები (ბლოგის სექცია მთავარ გვერდზე) -->
<div class="blog-section container">
    <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-uppercase">პროფესიონალური რჩევები</h2>
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
                            <h5 class="blog-card-title">{{ $post->title }}</h5>
                            <p class="blog-card-text">
                                {{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}
                            </p>
                            
                            <!-- ღილაკი და თარიღი ყოველთვის ბოლოშია განთავსებული -->
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
                    <!-- პირველი სლაიდერის ჯგუფი -->
                    <div class="brand-slide">
                        <img src="/images/brands/images.jpg" alt="ბრენდი 1" class="brand-logo">
                    </div>
                    <div class="brand-slide">
                        <img src="/images/brands/oven.png" alt="ბრენდი 2" class="brand-logo">
                    </div>
                    <div class="brand-slide">
                        <img src="/images/brands/ciq.png" alt="ბრენდი 3" class="brand-logo">
                    </div>
                    <div class="brand-slide">
                        <img src="/images/brands/ai.png" alt="ბრენდი 4" class="brand-logo">
                    </div>
                    <div class="brand-slide">
                        <img src="/images/brands/no.webp" alt="ბრენდი 5" class="brand-logo">
                    </div>
                    <div class="brand-slide">
                        <img src="/images/brands/k.png" alt="ბრენდი 6" class="brand-logo">
                    </div>
                    
                    
                    <!-- დუბლიკატი სლაიდერისთვის, რომ შეიქმნას უწყვეტი ეფექტი -->
                    <div class="brand-slide">
                        <img src="/images/brands/images.jpg" alt="ბრენდი 1" class="brand-logo">
                    </div>
                    <div class="brand-slide">
                        <img src="/images/brands/oven.png" alt="ბრენდი 2" class="brand-logo">
                    </div>
                    <div class="brand-slide">
                        <img src="/images/brands/ciq.png" alt="ბრენდი 3" class="brand-logo">
                    </div>
                    <div class="brand-slide">
                        <img src="/images/brands/ai.png" alt="ბრენდი 4" class="brand-logo">
                    </div>
                    <div class="brand-slide">
                        <img src="/images/brands/no.webp" alt="ბრენდი 5" class="brand-logo">
                    </div>
                    <div class="brand-slide">
                        <img src="/images/brands/k.png" alt="ბრენდი 6" class="brand-logo">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- შეფასების ფორმა (კოლაფსური) -->
<div class="review-form-container mt-5">
    <button class="btn btn-primary review-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#reviewFormCollapse" aria-expanded="false" aria-controls="reviewFormCollapse">
        <i class="bi bi-star-fill me-2"></i>დატოვეთ შეფასება
    </button>
    
    <div class="collapse mt-3" id="reviewFormCollapse">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">დატოვეთ შეფასება</h4>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="author_name" class="form-label">თქვენი სახელი</label>
                        <input type="text" class="form-control @error('author_name') is-invalid @enderror" id="author_name" name="author_name" required>
                        @error('author_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="author_email" class="form-label">ელ. ფოსტა (არ გამოჩნდება საჯაროდ)</label>
                        <input type="email" class="form-control @error('author_email') is-invalid @enderror" id="author_email" name="author_email">
                        @error('author_email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">თქვენი შეფასება</label>
                        
                        <!-- ემოჯების სელექტორი -->
                        <div class="emoji-picker">
                            <button type="button" class="emoji-btn" onclick="addEmoji('😊')">😊</button>
                            <button type="button" class="emoji-btn" onclick="addEmoji('👍')">👍</button>
                            <button type="button" class="emoji-btn" onclick="addEmoji('❤️')">❤️</button>
                            <button type="button" class="emoji-btn" onclick="addEmoji('😍')">😍</button>
                            <button type="button" class="emoji-btn" onclick="addEmoji('🔥')">🔥</button>
                            <button type="button" class="emoji-btn" onclick="addEmoji('👏')">👏</button>
                            <button type="button" class="emoji-btn" onclick="toggleEmojiSelector()">➕</button>
                        </div>
                        
                        <!-- დამატებითი ემოჯების პანელი (თავდაპირველად დამალული) -->
                        <div id="emojiSelector" class="emoji-selector" style="display: none;">
                            <button type="button" onclick="addEmoji('😃')">😃</button>
                            <button type="button" onclick="addEmoji('😁')">😁</button>
                            <button type="button" onclick="addEmoji('😂')">😂</button>
                            <button type="button" onclick="addEmoji('🤣')">🤣</button>
                            <button type="button" onclick="addEmoji('😎')">😎</button>
                            <button type="button" onclick="addEmoji('🙌')">🙌</button>
                            <button type="button" onclick="addEmoji('🎉')">🎉</button>
                            <button type="button" onclick="addEmoji('✨')">✨</button>
                            <button type="button" onclick="addEmoji('⭐')">⭐</button>
                            <button type="button" onclick="addEmoji('💯')">💯</button>
                            <button type="button" onclick="addEmoji('🤩')">🤩</button>
                            <button type="button" onclick="addEmoji('👌')">👌</button>
                        </div>
                        
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="3" required></textarea>
                        @error('content')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">შეაფასეთ</label>
                        <div class="rating">
                            <div class="star-rating">
                                <input type="radio" id="rating5" name="rating" value="5" checked>
                                <label for="rating5" title="5 ვარსკვლავი"><i class="bi bi-star-fill"></i></label>
                                
                                <input type="radio" id="rating4" name="rating" value="4">
                                <label for="rating4" title="4 ვარსკვლავი"><i class="bi bi-star-fill"></i></label>
                                
                                <input type="radio" id="rating3" name="rating" value="3">
                                <label for="rating3" title="3 ვარსკვლავი"><i class="bi bi-star-fill"></i></label>
                                
                                <input type="radio" id="rating2" name="rating" value="2">
                                <label for="rating2" title="2 ვარსკვლავი"><i class="bi bi-star-fill"></i></label>
                                
                                <input type="radio" id="rating1" name="rating" value="1">
                                <label for="rating1" title="1 ვარსკვლავი"><i class="bi bi-star-fill"></i></label>
                            </div>
                        </div>
                        @error('rating')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">სურათი (არჩევითი)</label>
                        <div class="custom-file-input-wrapper">
                            <input type="file" class="form-control custom-file-input @error('image') is-invalid @enderror" id="image" name="image">
                            <label class="custom-file-label" for="image">
                                <i class="bi bi-upload me-2"></i>აირჩიეთ ფაილი
                            </label>
                        </div>
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-send me-2"></i>გაგზავნა
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>






