@extends('admin.layout')
@section('title', content: 'პროდუქტები • ICETECH')
@section('content')
<style>
    .product-card {
        height: 100%;
        transition: transform 0.2s;
    }
    .product-card:hover {
        transform: translateY(-3px);
    }
    .product-card .card-body {
        display: flex;
        padding: 1rem;
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
    }
    .product-info {
        flex-grow: 1;
        margin-left: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .product-actions {
        margin-top: auto;
        padding-top: 1rem;
    }
    .action-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<h1 class="mb-4"><i class="bi bi-box-seam"></i> პროდუქტის მართვა</h1>

<!-- დამატების ფორმა -->
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
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
    <label class="form-label">პროდუქტის ქვეკატეგორია (ტიპი)</label>
    <input type="text" name="sub_type" class="form-control">
</div>


            <div class="mb-3">
                <label class="form-label"><i class="bi bi-tag-fill"></i> პროდუქტის სახელი</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-file-text-fill"></i> აღწერა</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-currency-exchange"></i> ფასი (₾)</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-flag-fill"></i> მწარმოებელი ქვეყანა</label>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="countryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img id="selectedFlag" src="https://flagcdn.com/w40/de.png" width="20" class="me-1"> გერმანია
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
                <input type="hidden" name="supplier_country" id="selectedCountry" value="DE">
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-info-circle-fill"></i> პროდუქტის მდგომარეობა</label>
                <select name="condition" class="form-select" required>
                    <option value="new">ახალი</option>
                    <option value="like_new">ახალივით</option>
                    <option value="used">მეორადი</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="bi bi-image-fill"></i> პროდუქტის ფოტო</label>
                <input type="file" name="image" class="form-control">
            </div>
            <!-- ახალი ველი: პროდუქტის ვიდეოს ლინკი -->
            <div class="mb-3">
                <label class="form-label"><i class="bi bi-camera-video-fill"></i> პროდუქტის ვიდეოს ლინკი</label>
                <input type="url" name="video_link" class="form-control" placeholder="შეიყვანეთ ვიდეოს URL">
            </div>

            <button type="submit" class="btn btn-success w-100"><i class="bi bi-plus-circle"></i> დამატება</button>
        </form>
    </div>
</div>

<!-- ფილტრი -->
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


<!-- პროდუქტების სია -->
<h2 class="mb-3"><i class="bi bi-boxes"></i> არსებული პროდუქტები</h2>
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="productList">
    @foreach (\App\Models\Product::with('category')->get() as $product)
    <div class="col product-item" data-category="{{ $product->category_id }}">
        <div class="card shadow-sm h-100 product-card">
            <div class="card-body">
                <div class="d-flex">
                    <!-- სურათი -->
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

                    <!-- ინფორმაცია -->
                    <div class="product-info">
                        <div>
                            <h6 class="card-title mb-2">{{ $product->name }}</h6>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-folder"></i> {{ $product->category->name }}
                            </p>
                            
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-success fw-bold">
                                    <i class="bi bi-currency-exchange"></i> 
                                    {{ number_format($product->price, 2) }}₾
                                </span>
                                <img src="https://flagcdn.com/w40/{{ strtolower($product->supplier_country) }}.png" 
                                     class="ms-2" 
                                     width="24" 
                                     alt="flag">
                            </div>

                            <span class="badge bg-{{ $product->condition == 'new' ? 'success' : ($product->condition == 'like_new' ? 'primary' : 'warning') }}">
                                <i class="bi bi-tag"></i> 
                                {{ $product->condition == 'new' ? 'ახალი' : ($product->condition == 'like_new' ? 'ახალივით' : 'მეორადი') }}
                            </span>
                        </div>

                        <!-- ქმედებები -->
                        <div class="product-actions d-flex">
                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                               class="btn btn-outline-warning btn-sm action-btn me-2">
                                <i class="bi bi-pencil"></i>
                            </a>
                            
                            <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-outline-danger btn-sm action-btn"
                                        onclick="return confirm('დარწმუნებული ხარ?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
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
<script>
    // ფილტრაციის სკრიპტი
    document.getElementById('categoryFilter').addEventListener('change', function() {
        const categoryId = this.value;
        document.querySelectorAll('.product-item').forEach(item => {
            item.style.display = (categoryId === 'all' || item.dataset.category === categoryId) 
                               ? 'block' 
                               : 'none';
        });
    });

    // ქვეყნის არჩევის სკრიპტი
    document.querySelectorAll('.country-option').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const flag = this.querySelector('img').src;
            const country = this.textContent.trim();
            document.getElementById('selectedFlag').src = flag;
            document.getElementById('countryDropdown').innerHTML = `
                <img src="${flag}" width="20" class="me-1"> ${country}
            `;
            document.getElementById('selectedCountry').value = this.dataset.value;
        });
    });
</script>

@if(!request()->has('tinymce'))
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#description',
        height: 300,
        plugins: 'lists link image preview',
        toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent',
        promotion: false
    });
</script>
@endif
@endsection
