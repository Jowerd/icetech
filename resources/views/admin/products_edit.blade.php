@extends('admin.layout')
@section('title', 'რედაქტირება • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">პროდუქტის რედაქტირება</h4>
                <div class="text-muted small">
                    <span class="badge bg-light text-dark border fw-normal">ID: #{{ $product->id }}</span>
                    <span class="ms-2"><i class="bi bi-eye"></i> {{ $product->views_count ?? 0 }} ნახვა</span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-light border rounded-1 fw-bold px-3 flex-grow-1 flex-md-grow-0 text-center small">გაუქმება</a>
                <button type="submit" class="btn btn-primary rounded-1 fw-bold px-4 flex-grow-1 flex-md-grow-0 shadow-sm small">
                    <i class="bi bi-check-lg me-1"></i> განახლება
                </button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-4 order-lg-2">
                <div class="card border rounded-1 shadow-none bg-white mb-4">
                    <div class="card-header bg-light py-2 border-bottom">
                        <h6 class="mb-0 fw-bold text-uppercase x-small-text text-secondary">ვიზუალი & სტატუსი</h6>
                    </div>
                    <div class="card-body p-3 text-center">
                        <div class="image-edit-wrapper border rounded mb-3 mx-auto shadow-sm">
                            <img id="imagePreview" src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/400x400.png?text=No+Image' }}" class="img-fluid">
                        </div>
                        
                        <div class="mb-3">
                            <label for="imageInput" class="btn btn-dark btn-sm fw-bold rounded-1 w-100 py-2 mb-2">
                                <i class="bi bi-camera me-2"></i>ფოტოს შეცვლა
                            </label>
                            <input type="file" name="image" id="imageInput" class="d-none" onchange="previewImage(event)">
                        </div>

                        <div class="text-start border-top pt-3">
                            <label class="form-label small fw-bold">მდგომარეობა</label>
                            <select name="condition" class="form-select border-2 shadow-none mb-3">
                                <option value="new" {{ $product->condition == 'new' ? 'selected' : '' }}>ახალი</option>
                                <option value="like_new" {{ $product->condition == 'like_new' ? 'selected' : '' }}>ახალივით</option>
                                <option value="used" {{ $product->condition == 'used' ? 'selected' : '' }}>მეორადი</option>
                            </select>

                            <label class="form-label small fw-bold">ვიდეო ლინკი (YouTube/URL)</label>
                            <input type="url" name="video_link" value="{{ old('video_link', $product->video_link) }}" class="form-control border-2 shadow-none small" placeholder="https://...">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8 order-lg-1">
                <div class="card border rounded-1 shadow-none bg-white">
                    <div class="card-header bg-light py-2 border-bottom">
                        <h6 class="mb-0 fw-bold text-uppercase x-small-text text-secondary">პროდუქტის დეტალები</h6>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold">პროდუქტის დასახელება</label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control admin-input-main shadow-none" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">კატეგორია</label>
                                <select name="category_id" class="form-select border-2 shadow-none" required>
                                    @foreach (\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">ქვეკატეგორია (ტიპი)</label>
                                <input type="text" name="sub_type" value="{{ old('sub_type', $product->sub_type) }}" class="form-control border-2 shadow-none">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">ფასი (₾)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control border-2 shadow-none fw-bold text-success" required>
                                    <span class="input-group-text border-2 bg-light">₾</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">მწარმოებელი ქვეყანა</label>
                                <select name="supplier_country" class="form-select border-2 shadow-none">
                                    @php $countries = ['DE' => 'გერმანია', 'IT' => 'იტალია', 'TR' => 'თურქეთი', 'CN' => 'ჩინეთი', 'AT' => 'ავსტრია']; @endphp
                                    @foreach($countries as $code => $name)
                                        <option value="{{ $code }}" {{ $product->supplier_country == $code ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-primary">მახასიათებლები (თითო ხაზზე თითო მახასიათებელი)</label>
                                <textarea name="features_text" class="form-control border-2 shadow-none" rows="5" placeholder="ზომა: 100x200...">{{ old('features_text', $product->features_text) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">ვრცელი აღწერა</label>
                                <textarea name="description" id="description" class="form-control">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- ============================================================
     გალერეა — დამატებითი ფოტოები
============================================================ --}}
<div class="container-fluid px-0 px-md-2 mt-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-1 small py-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border rounded-1 shadow-none bg-white">
        <div class="card-header bg-light py-2 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-uppercase x-small-text text-secondary">
                <i class="bi bi-images me-1"></i> გალერეა (დამატებითი ფოტოები)
            </h6>
            <span class="badge bg-secondary">{{ $product->images->count() }} ფოტო</span>
        </div>
        <div class="card-body p-3">

            {{-- არსებული ფოტოები --}}
            @if($product->images->isNotEmpty())
                <div class="row g-2 mb-3" id="galleryGrid">
                    @foreach($product->images as $img)
                        <div class="col-4 col-md-3 col-lg-2" id="gallery-item-{{ $img->id }}">
                            <div class="position-relative border rounded overflow-hidden" style="aspect-ratio:1/1;">
                                <img src="{{ asset('storage/' . $img->image) }}"
                                     alt="gallery"
                                     class="w-100 h-100"
                                     style="object-fit:cover;">
                                <form action="{{ route('admin.products.images.destroy', [$product->id, $img->id]) }}"
                                      method="POST"
                                      class="gallery-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 p-0 d-flex align-items-center justify-content-center"
                                            style="width:24px;height:24px;border-radius:50%;"
                                            title="წაშლა">
                                        <i class="bi bi-x" style="font-size:14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted small mb-3">დამატებითი ფოტოები არ არის.</p>
            @endif

            {{-- ახალი ფოტოების ატვირთვა --}}
            <form action="{{ route('admin.products.images.store', $product->id) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <label for="galleryInput" class="btn btn-dark btn-sm fw-bold rounded-1 mb-0">
                        <i class="bi bi-plus-lg me-1"></i> ფოტოების არჩევა
                    </label>
                    <input type="file"
                           name="images[]"
                           id="galleryInput"
                           class="d-none"
                           multiple
                           accept="image/*"
                           onchange="previewGallery(this)">
                    <button type="submit" id="galleryUploadBtn" class="btn btn-primary btn-sm fw-bold rounded-1 d-none">
                        <i class="bi bi-cloud-upload me-1"></i> ატვირთვა
                    </button>
                    <span id="galleryFileCount" class="text-muted small"></span>
                </div>

                {{-- Preview thumbnails --}}
                <div id="galleryPreview" class="row g-2 mt-2"></div>
            </form>

        </div>
    </div>
</div>

<style>
    * { transition: none !important; }
    body { background-color: #f8f9fa; }

    .admin-input-main {
        border: 2px solid #dee2e6;
        border-radius: 4px;
        padding: 0.75rem;
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a1a1a;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        background-color: #fff;
    }

    .image-edit-wrapper {
        width: 100%;
        max-width: 320px;
        aspect-ratio: 1/1;
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .image-edit-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 10px;
    }

    .x-small-text { font-size: 0.65rem; letter-spacing: 0.05rem; }

    @media (max-width: 768px) {
        .card-body { padding: 1.25rem !important; }
        .admin-input-main { font-size: 1rem; }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#description',
        height: 350,
        menubar: false,
        plugins: 'lists link code table',
        toolbar: 'undo redo | bold italic | bullist numlist | table link code',
        promotion: false,
        content_style: 'body { font-family:Inter,sans-serif; font-size:14px }'
    });

    function previewImage(event) {
        const output = document.getElementById('imagePreview');
        if (event.target.files && event.target.files[0]) {
            output.src = URL.createObjectURL(event.target.files[0]);
        }
    }

    function previewGallery(input) {
        const preview  = document.getElementById('galleryPreview');
        const countEl  = document.getElementById('galleryFileCount');
        const uploadBtn = document.getElementById('galleryUploadBtn');
        preview.innerHTML = '';

        if (!input.files || input.files.length === 0) {
            uploadBtn.classList.add('d-none');
            countEl.textContent = '';
            return;
        }

        countEl.textContent = input.files.length + ' ფოტო არჩეულია';
        uploadBtn.classList.remove('d-none');

        Array.from(input.files).forEach(file => {
            const col = document.createElement('div');
            col.className = 'col-4 col-md-3 col-lg-2';
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.className = 'w-100 border rounded';
            img.style.cssText = 'aspect-ratio:1/1;object-fit:cover;';
            col.appendChild(img);
            preview.appendChild(col);
        });
    }

    // AJAX delete — გვერდის გადატვირთვის გარეშე
    document.querySelectorAll('.gallery-delete-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!confirm('ფოტო წაიშლება. დარწმუნებული ხარ?')) return;
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('[name=_token]').value },
                body: new FormData(form)
            });
            if (res.ok || res.redirected) {
                form.closest('[id^=gallery-item-]').remove();
            }
        });
    });
</script>
@endsection