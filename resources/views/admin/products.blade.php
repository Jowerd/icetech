@extends('admin.layout')
@section('title', 'პროდუქტები • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">
    
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-0">პროდუქტების მართვა</h4>
            <p class="text-muted small mb-0">ინვენტარის სრული კონტროლი</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.resetViews') }}" onclick="return confirm('ნახვების განულება?')" class="btn btn-outline-danger btn-sm rounded-1 fw-bold">
                <i class="bi bi-arrow-counterclockwise me-1"></i> ნახვების Reset
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-1 mb-5">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-uppercase small"><i class="bi bi-plus-lg me-2"></i>ახალი პროდუქტის დამატება</h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">კატეგორია</label>
                        <select name="category_id" class="form-select border-2 shadow-none" required>
                            <option value="">-- აირჩიე --</option>
                            @foreach (\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">ქვეკატეგორია / ტიპი</label>
                        <input type="text" name="sub_type" class="form-control border-2 shadow-none" placeholder="მაგ: საყინულე">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">დასახელება</label>
                        <input type="text" name="name" class="form-control border-2 shadow-none" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold">ფასი (₾)</label>
                        <input type="number" step="0.01" name="price" class="form-control border-2 shadow-none" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">ქვეყანა</label>
                        <select name="supplier_country" class="form-select border-2 shadow-none">
                            <option value="DE">გერმანია</option>
                            <option value="IT">იტალია</option>
                            <option value="TR">თურქეთი</option>
                            <option value="CN">ჩინეთი</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">მდგომარეობა</label>
                        <select name="condition" class="form-select border-2 shadow-none">
                            <option value="new">ახალი</option>
                            <option value="like_new">ახალივით</option>
                            <option value="used">მეორადი</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">მთავარი ფოტო</label>
                        <input type="file" name="image" class="form-control border-2 shadow-none" accept="image/*">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold">გალერეა (მრავალი ფოტო)</label>
                        <input type="file" name="gallery_images[]" id="galleryInputCreate" class="form-control border-2 shadow-none" accept="image/*" multiple onchange="previewGalleryCreate(this)">
                        <div id="galleryPreviewCreate" class="d-flex flex-wrap gap-1 mt-1"></div>
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label small fw-bold">აღწერა (Description)</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label small fw-bold">ვიდეო ლინკი (YouTube/URL)</label>
                        <input type="url" name="video_link" class="form-control border-2 shadow-none" placeholder="https://...">
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label small fw-bold text-primary">მახასიათებლები (თითო ხაზზე თითო მახასიათებელი)</label>
                        <textarea name="features_text" class="form-control border-2 shadow-none" rows="4" placeholder="ზომა: 200x100&#10;სიმძლავრე: 5kW"></textarea>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary px-5 fw-bold rounded-1 py-2 mt-3">დამატება</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3">
        <h5 class="fw-bold mb-0">არსებული ბაზა</h5>
        <div class="col-md-3 col-12">
            <select id="categoryFilter" class="form-select form-select-sm border-2 shadow-none">
                <option value="all">ყველა კატეგორია</option>
                @foreach (\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-1 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light border-bottom text-uppercase small fw-bold text-secondary">
                    <tr>
                        <th class="ps-4 py-3" style="width: 70px;">IMG</th>
                        <th class="py-3">პროდუქტი</th>
                        <th class="py-3 d-none d-md-table-cell">კატეგორია</th>
                        <th class="py-3">ფასი</th>
                        <th class="py-3 text-end pe-4">მართვა</th>
                    </tr>
                </thead>
                <tbody id="productList">
                    @foreach (\App\Models\Product::with('category')->latest()->get() as $product)
                    <tr class="product-item border-bottom" data-category="{{ $product->category_id }}">
                        <td class="ps-4 py-2">
                            <div class="p-thumb border">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}">
                                @else
                                    <i class="bi bi-box text-muted opacity-50"></i>
                                @endif
                            </div>
                        </td>
                        <td class="py-2">
                            <div class="fw-bold text-dark mb-0">{{ $product->name }}</div>
                            <div class="x-small-text text-muted">
                                {{ $product->sub_type }} • <img src="https://flagcdn.com/w20/{{ strtolower($product->supplier_country) }}.png" width="14"> {{ strtoupper($product->supplier_country) }}
                            </div>
                        </td>
                        <td class="py-2 d-none d-md-table-cell">
                            <span class="badge bg-light text-dark border fw-normal">{{ $product->category->name }}</span>
                        </td>
                        <td class="py-2">
                            <span class="fw-bold text-success">{{ number_format($product->price, 2) }}₾</span>
                            <div class="x-small-text"><i class="bi bi-eye"></i> {{ $product->views_count ?? 0 }}</div>
                        </td>
                        <td class="text-end pe-4 py-2">
                            <div class="btn-group border rounded bg-white shadow-sm">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-white text-primary px-3">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline border-start">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-white text-danger px-3" onclick="return confirm('დარწმუნებული ხართ?')">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* სრული სტატიკა */
    * { transition: none !important; }
    body { background-color: #f4f7f6; font-family: 'Inter', sans-serif; }

    /* Inputs */
    .form-control, .form-select { border-color: #e2e8f0; font-size: 0.9rem; }
    .form-control:focus, .form-select:focus { border-color: #0d6efd; }

    /* Thumbnails */
    .p-thumb {
        width: 48px;
        height: 48px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        overflow: hidden;
    }
    .p-thumb img { width: 100%; height: 100%; object-fit: cover; }

    /* Table Styles */
    .table thead th { font-size: 11px; letter-spacing: 0.05rem; }
    .x-small-text { font-size: 0.75rem; }
    .btn-white { background: #fff; border: none; }
    .btn-white:hover { background: #f8f9fa; }

    /* მობილურზე კორექცია */
    @media (max-width: 768px) {
        .btn-group .btn { padding: 8px 12px; }
        .p-thumb { width: 40px; height: 40px; }
    }
</style>
@endsection

@section('scripts')
{{-- TinyMCE და ფილტრი აქ რჩება იგივე ლოგიკით --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#description',
        height: 250,
        menubar: false,
        plugins: 'lists link code',
        toolbar: 'undo redo | bold italic | bullist numlist | link code',
        promotion: false
    });

    function previewGalleryCreate(input) {
        const container = document.getElementById('galleryPreviewCreate');
        container.innerHTML = '';
        Array.from(input.files).forEach(file => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.cssText = 'width:40px;height:40px;object-fit:cover;border-radius:4px;border:1px solid #dee2e6;';
            container.appendChild(img);
        });
    }

    document.getElementById('categoryFilter').addEventListener('change', function() {
        const catId = this.value;
        document.querySelectorAll('.product-item').forEach(tr => {
            tr.style.display = (catId === 'all' || tr.dataset.category === catId) ? '' : 'none';
        });
    });
</script>
@endsection