@extends('admin.layout')
@section('title', 'პროდუქტები • ICETECH')
@section('content')
<style>
    .product-card {
        height: 100%;
        transition: transform 0.2s;
        display: flex; /* Flexbox for full card height alignment */
        flex-direction: column;
        border-radius: 0.75rem; /* Slightly rounded corners */
    }
    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; /* Enhanced shadow on hover */
    }
    .product-card .card-body {
        display: flex;
        padding: 1rem;
        flex-grow: 1; /* Allow card body to take available space */
    }
    .image-container {
        width: 100px;
        height: 100px;
        flex-shrink: 0;
        background: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden; /* Ensure image fits */
        margin-right: 1rem; /* Space between image and info */
        border: 1px solid #e9ecef; /* Light border for image container */
    }
    .product-info {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .product-info h6 {
        font-size: 1.15rem; /* Slightly larger font for product name */
        font-weight: 600; /* Bolder product name */
        margin-bottom: 0.4rem; /* Adjust margin */
        line-height: 1.3;
    }
    .product-info .text-muted.small {
        font-size: 0.85rem;
        margin-bottom: 0.3rem;
    }
    .price-country-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between; /* Space out price and country */
        width: 100%;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    .price-country-wrapper .text-success {
        font-size: 1.1rem; /* Larger price font */
        font-weight: 700;
    }
    .product-actions {
        margin-top: auto; /* Push actions to the bottom */
        padding-top: 0.75rem; /* Slightly increased padding */
        border-top: 1px solid #f1f1f1; /* Separator line */
        display: flex; /* Align buttons horizontally */
        gap: 0.6rem; /* Space between action buttons */
        justify-content: flex-end; /* Align actions to the right */
    }
    .action-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0; /* Prevent buttons from shrinking */
        border-radius: 0.5rem; /* Rounded buttons */
    }
    /* Styles for the dynamic feature fields */
    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        gap: 10px;
    }
    .feature-item .form-control {
        flex: 1;
    }
    .feature-item .btn-danger {
        flex-shrink: 0;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
</style>

<h1 class="mb-4"><i class="bi bi-box-seam"></i> პროდუქტის მართვა</h1>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-plus-circle"></i> ახალი პროდუქტის დამატება</h5>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-folder-fill"></i> აირჩიე კატეგორია</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- აირჩიე კატეგორია --</option>
                    @foreach (\App\Models\Category::all() as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">პროდუქტის ქვეკატეგორია (ტიპი)</label>
                <input type="text" name="sub_type" value="{{ old('sub_type') }}" class="form-control" placeholder="მაგ. Showcase, Upright, Countertop...">
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tag-fill"></i> პროდუქტის სახელი</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-file-text-fill"></i> აღწერა</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            {{-- New Section: Product Features --}}
            <div class="mb-4">
                <label class="form-label d-block"><i class="bi bi-list-stars"></i> პროდუქტის მახასიათებლები</label>
                <div id="features-container">
                    {{-- Dynamic fields will be added here via JS --}}
                </div>
                <button type="button" id="add-feature-btn" class="btn btn-info btn-sm mt-2">
                    <i class="bi bi-plus-lg"></i> მახასიათებლის დამატება
                </button>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-currency-exchange"></i> ფასი (₾)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-flag-fill"></i> მწარმოებელი ქვეყანა</label>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="countryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        @php
                            $selectedCountryCode = old('supplier_country', 'DE'); // Default to DE if not set
                            $selectedCountryName = config('countries.list')[$selectedCountryCode] ?? 'გერმანია';
                        @endphp
                        <img id="selectedFlag" src="https://flagcdn.com/w40/{{ strtolower($selectedCountryCode) }}.png" width="20" class="me-1"> {{ $selectedCountryName }}
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="countryDropdown">
                        @php
                            $countries = [
                                'DE' => 'გერმანია',
                                'IT' => 'იტალია',
                                'TR' => 'თურქეთი',
                                'CN' => 'ჩინეთი',
                                'AT' => 'ავსტრია'
                            ];
                        @endphp
                        @foreach ($countries as $code => $country)
                            <li>
                                <a class="dropdown-item d-flex align-items-center country-option" href="#" data-value="{{ $code }}">
                                    <img src="https://flagcdn.com/w40/{{ strtolower($code) }}.png" width="20" class="me-2">
                                    {{ $country }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <input type="hidden" name="supplier_country" id="selectedCountry" value="{{ old('supplier_country', 'DE') }}">
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-info-circle-fill"></i> პროდუქტის მდგომარეობა</label>
                <select name="condition" class="form-select" required>
                    <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>ახალი</option>
                    <option value="like_new" {{ old('condition') == 'like_new' ? 'selected' : '' }}>ახალივით</option>
                    <option value="used" {{ old('condition') == 'used' ? 'selected' : '' }}>მეორადი</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-image-fill"></i> პროდუქტის ფოტო</label>
                <input type="file" name="image" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-camera-video-fill"></i> პროდუქტის ვიდეოს ლინკი</label>
                <input type="url" name="video_link" value="{{ old('video_link') }}" class="form-control" placeholder="შეიყვანეთ ვიდეოს URL">
            </div>

            <button type="submit" class="btn btn-success w-100"><i class="bi bi-plus-circle"></i> დამატება</button>
        </form>
    </div>
</div>

<div class="mb-4">
    <h5><i class="bi bi-funnel-fill"></i> ფილტრაცია კატეგორიის მიხედვით</h5>
    <select id="categoryFilter" class="form-select">
        <option value="all">ყველა კატეგორია</option>
        @foreach (\App\Models\Category::all() as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
</div>
<a href="{{ route('admin.resetViews') }}" onclick="return confirm('დარწმუნებული ხარ, რომ გინდა ნახვების განულება?')" class="btn btn-danger">
    ნახვების განულება
</a>


<h2 class="mb-3"><i class="bi bi-boxes"></i> არსებული პროდუქტები</h2>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="productList">
    @foreach (\App\Models\Product::with('category')->get() as $product)
    <div class="col product-item" data-category="{{ $product->category_id }}">
        <div class="card shadow-sm h-100 product-card">
            <div class="card-body">
                <div class="image-container">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}"
                             class="img-fluid"
                             style="max-height: 100%; object-fit: contain">
                    @else
                        <i class="bi bi-image fs-1 text-secondary"></i>
                    @endif
                </div>

                <div class="product-info">
                    <div>
                        <h6 class="card-title">{{ $product->name }}</h6>
                        @if ($product->sub_type)
                            <p class="text-muted small mb-1">{{ $product->sub_type }}</p>
                        @endif
                        <p class="text-muted small mb-2">
                            <i class="bi bi-folder"></i> {{ $product->category->name }}
                        </p>
                        
                        <div class="price-country-wrapper">
                            <span class="text-success fw-bold">
                                {{ number_format($product->price, 2) }}₾
                            </span>
                            <img src="https://flagcdn.com/w40/{{ strtolower($product->supplier_country) }}.png" 
                                 class="ms-2" 
                                 width="24" 
                                 alt="flag">
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-{{ $product->condition == 'new' ? 'success' : ($product->condition == 'like_new' ? 'primary' : 'warning') }}">
                                <i class="bi bi-tag"></i> 
                                {{ $product->condition == 'new' ? 'ახალი' : ($product->condition == 'like_new' ? 'ახალივით' : 'მეორადი') }}
                            </span>
                            <small class="text-muted"><i class="bi bi-eye"></i> {{ $product->views_count ?? 0 }}</small>
                        </div>
                    </div>

                    <div class="product-actions">
                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                           class="btn btn-outline-warning btn-sm action-btn" title="რედაქტირება">
                            <i class="bi bi-pencil"></i>
                        </a>
                        
                        <form action="{{ route('admin.products.destroy', $product->id) }}" 
                              method="POST"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-outline-danger btn-sm action-btn"
                                    onclick="return confirm('დარწმუნებული ხარ?')" title="წაშლა">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- TinyMCE Library --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#description',
        height: 300,
        plugins: 'lists link image preview code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent | link image | preview | code',
        promotion: false,
        content_css: false,
        content_style: `
            body { 
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"; 
                line-height: 1.6; 
                margin: 15px; 
            }
            p { 
                margin-top: 0; 
                margin-bottom: 0.5em; 
            }
            p:last-child {
                margin-bottom: 0;
            }
            h1, h2, h3, h4, h5, h6 { 
                margin-top: 1em; 
                margin-bottom: 0.5em; 
            }
            ul, ol {
                margin-top: 0.5em;
                margin-bottom: 0.5em;
                padding-left: 1.5em;
            }
            li {
                margin-bottom: 0.2em;
            }
        `
    });
</script>

{{-- Custom Scripts (including feature logic) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category Filter Script
        document.getElementById('categoryFilter').addEventListener('change', function() {
            const categoryId = this.value;
            document.querySelectorAll('.product-item').forEach(item => {
                item.style.display = (categoryId === 'all' || item.dataset.category === categoryId) 
                                        ? 'block' 
                                        : 'none';
            });
        });

        // Country Dropdown Script (for new product form)
        let countryDropdown = document.querySelector('#countryDropdown');
        let selectedCountryInput = document.querySelector('#selectedCountry');
        let countryOptions = document.querySelectorAll('.country-option');

        if (countryDropdown && selectedCountryInput && countryOptions.length > 0) {
            countryOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    let selectedCountryCode = this.getAttribute('data-value');
                    let selectedCountryName = this.textContent.trim();
                    let selectedFlagUrl = this.querySelector('img').src;

                    countryDropdown.innerHTML = `<img src="${selectedFlagUrl}" width="20" class="me-1"> ${selectedCountryName}`;
                    selectedCountryInput.value = selectedCountryCode;
                });
            });
        }

        // Dynamic Features Logic (for new product form)
        const featuresContainer = document.getElementById('features-container');
        const addFeatureBtn = document.getElementById('add-feature-btn');
        let featureIndex = 0; // Index to keep field names unique for new additions

        function createFeatureFieldHTML(index, name = '', value = '') {
            return `
                <div class="feature-item" data-index="${index}">
                    <input type="text" name="features[${index}][name]" class="form-control form-control-sm" placeholder="მახასიათებლის სახელი (მაგ. ფერი)" value="${name}" required>
                    <input type="text" name="features[${index}][value]" class="form-control form-control-sm" placeholder="მნიშვნელობა (მაგ. წითელი)" value="${value}" required>
                    <button type="button" class="btn btn-danger btn-sm remove-feature-btn"><i class="bi bi-x"></i></button>
                </div>
            `;
        }

        function addFeatureField(name = '', value = '') {
            const htmlString = createFeatureFieldHTML(featureIndex, name, value);
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = htmlString.trim();
            const newFeatureItem = tempDiv.firstChild; 

            featuresContainer.appendChild(newFeatureItem); 
            
            newFeatureItem.querySelector('.remove-feature-btn').addEventListener('click', function() {
                this.closest('.feature-item').remove();
            });

            featureIndex++; 
        }

        addFeatureBtn.addEventListener('click', function() {
            addFeatureField(); 
        });

        if (featuresContainer && featuresContainer.children.length === 0) {
            addFeatureField();
        }
    });
</script>
@endsection