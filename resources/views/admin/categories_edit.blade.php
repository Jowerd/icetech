@extends('admin.layout')
@section('title', 'რედაქტირება • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">
    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">კატეგორიის რედაქტირება</h4>
                <div class="text-muted small">ID: #{{ $category->id }} • <span class="d-none d-sm-inline">ბოლო ცვლილება:</span> {{ $category->updated_at->diffForHumans() }}</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-light border rounded-1 fw-bold px-3 flex-grow-1 flex-md-grow-0 text-center small">გაუქმება</a>
                <button type="submit" class="btn btn-primary rounded-1 fw-bold px-4 flex-grow-1 flex-md-grow-0 shadow-sm small">
                    <i class="bi bi-save me-2"></i>შენახვა
                </button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-4 order-lg-2">
                <div class="card border rounded-1 shadow-none bg-white mb-4">
                    <div class="card-header bg-light py-2 border-bottom">
                        <h6 class="mb-0 fw-bold text-uppercase x-small-text text-secondary">კატეგორიის ფოტო</h6>
                    </div>
                    <div class="card-body p-3 text-center">
                        <div class="image-edit-wrapper border rounded mb-3 mx-auto">
                            <img id="imagePreview" src="{{ $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/400x300.png?text=No+Image' }}" class="img-fluid rounded-1">
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-12">
                                <label for="imageInput" class="btn btn-dark btn-sm fw-bold rounded-1 w-100 py-2">
                                    <i class="bi bi-camera me-2"></i>ფოტოს შეცვლა
                                </label>
                                <input type="file" name="image" id="imageInput" class="d-none" onchange="previewImage(event)">
                            </div>
                            @if($category->image)
                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-danger btn-sm fw-bold rounded-1 w-100" onclick="deleteImageAction()">
                                        <i class="bi bi-trash me-2"></i>ფოტოს წაშლა
                                    </button>
                                    <input type="checkbox" name="delete_image" id="delete_image_checkbox" class="d-none">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8 order-lg-1">
                <div class="card border rounded-1 shadow-none bg-white h-100">
                    <div class="card-header bg-light py-2 border-bottom">
                        <h6 class="mb-0 fw-bold text-uppercase x-small-text text-secondary">მონაცემები</h6>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">კატეგორიის დასახელება</label>
                            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control admin-input-main shadow-none" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-dark">საკვანძო სიტყვები (SEO)</label>
                            <textarea name="keywords" class="form-control admin-input shadow-none" rows="2" placeholder="კომპიუტერები, ტექნიკა...">{{ old('keywords', $category->keywords) }}</textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-bold text-dark">აღწერა (Description)</label>
                            <textarea name="description" class="form-control admin-input shadow-none" rows="5">{{ old('description', $category->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* სრული სისუფთავე */
    * { transition: none !important; }
    body { background-color: #f8f9fa; }

    /* მობილურზე ადაპტირებული ინპუტები */
    .admin-input-main {
        border: 2px solid #dee2e6;
        border-radius: 4px;
        padding: 0.75rem;
        font-size: 1.1rem;
        font-weight: 700;
    }
    .admin-input {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 0.6rem;
        font-size: 0.95rem;
    }
    .admin-input-main:focus, .admin-input:focus {
        border-color: #0d6efd;
        background-color: #fff;
    }

    /* ფოტოს ზომები */
    .image-edit-wrapper {
        width: 100%;
        max-width: 300px;
        aspect-ratio: 1/1;
        background-color: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .image-edit-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .x-small-text { font-size: 0.65rem; letter-spacing: 0.05rem; }

    /* მობილურის პატარა კორექციები */
    @media (max-width: 768px) {
        h4 { font-size: 1.2rem; }
        .card-body { padding: 1.25rem !important; }
        .btn-sm { padding: 0.5rem; }
    }
</style>
@endsection

@section('scripts')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
        };
        if (event.target.files && event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
            if(document.getElementById('delete_image_checkbox')) {
                document.getElementById('delete_image_checkbox').checked = false;
            }
        }
    }

    function deleteImageAction() {
        if(confirm('წავშალოთ ფოტო?')) {
            document.getElementById('delete_image_checkbox').checked = true;
            document.getElementById('imagePreview').src = 'https://via.placeholder.com/400x300.png?text=Removed';
        }
    }
</script>
@endsection