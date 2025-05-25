<div class="container-fluid px-0">
    <div class="container py-4">
        <!-- პროდუქტის დეტალები -->
        <div class="row g-2">
            <!-- პროდუქტის სურათი -->
            <div class="col-12 col-md-6">
                <div class="product-image-wrapper">
                    <img
                        src="{{ $product->image ? asset('storage/' . $product->image) : asset('default-product.png') }}"
                        alt="{{ $product->name }}"
                        class="img-fluid rounded w-100 object-fit-cover product-image"
                    >
                </div>
            </div>
            
            <!-- პროდუქტის ინფორმაცია -->
            <div class="col-12 col-md-6">
                <div class="product-details">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    <div class="price-flag-container">
                        <h3 class="product-price">₾ {{ number_format($product->price, 2) }}</h3>
                        <span class="country-flag" title="{{ $product->supplier_country }}">
                            <img src="https://flagcdn.com/24x18/{{ strtolower(substr($product->supplier_country, 0, 2)) }}.png"
                                alt="{{ $product->supplier_country }} Flag"
                                class="country-flag-img">
                        </span>
                    </div>
                            
                    
                    <div class="product-description-wrapper description-collapsed">
                        <div class="product-description">
                            {!! $product->description !!}
                        </div>
                        <div class="description-gradient"></div>
                    </div>
                    
                    <button class="show-more-description">
                        <span class="show-more-description-text">სრულად ნახვა</span>
                        <span class="show-less-description-text">ნაკლები</span>
                    </button>
                                        <!-- გასაზიარებელი ღილაკები - დაამატეთ პროდუქტის ინფორმაციის სექციაში -->
                <div class="social-share-buttons mt-4">
                    <p class="share-title mb-2">გააზიარე პროდუქტი:</p>
                <div class="share-buttons-container">
                 <!-- Facebook -->
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                     target="_blank" 
                     class="share-button facebook-share">
                    <i class="fab fa-facebook-f"></i>
                     </a>

        <!-- WhatsApp -->
        <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . request()->url()) }}" 
           target="_blank" 
           class="share-button whatsapp-share">
            <i class="fab fa-whatsapp"></i>
        </a>
        
        <!-- Telegram -->
        <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->name) }}" 
           target="_blank" 
           class="share-button telegram-share">
            <i class="fab fa-telegram-plane"></i>
        </a>
        
        <!-- Copy Link -->
        <button class="share-button copy-link" data-url="{{ request()->url() }}" title="ბმულის კოპირება">
            <i class="fas fa-link"></i>
        </button>
    </div>
</div>
                    @if($product->video_link)
                        <div class="product-video">
                            <a href="{{ $product->video_link }}" target="_blank" class="product-video-link">
                                ნახეთ პროდუქტის ვიდეო
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
        
    <!-- მსგავსი პროდუქტების სექცია -->
    @if($similarProducts && $similarProducts->count() > 0)
    <div class="container-fluid px-0">
        <div class="similar-products-section mt-5">
            <div class="container">
                <h3 class="section-title mb-4">მსგავსი პროდუქტები</h3>
                
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-2 similar-products-container">
                    @foreach($similarProducts as $key => $similarProduct)
                        <div class="col similar-product-item {{ $key >= 5 ? 'hidden-desktop' : '' }} {{ $key >= 2 ? 'hidden-mobile' : '' }}">
                            <a href="{{ route('products.show', ['product' => $similarProduct->slug]) }}" class="similar-product-card">
                                <div class="similar-product-image-wrapper">
                                    <img
                                        src="{{ $similarProduct->image ? asset('storage/' . $similarProduct->image) : asset('default-product.png') }}"
                                        alt="{{ $similarProduct->name }}"
                                        class="similar-product-image"
                                        loading="lazy"
                                    >
                                </div>
                                <div class="similar-product-info">
                                    <h5 class="similar-product-title">{{ $similarProduct->name }}</h5>
                                    <p class="similar-product-price">₾ {{ number_format($similarProduct->price, 2) }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                
                @if($similarProducts->count() > 5)
                <div class="text-center mt-4">
                    <button class="show-more-products">
                        <span class="show-more-text">მეტის ნახვა</span>
                        <span class="show-less-text">ნაკლების ნახვა</span>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>