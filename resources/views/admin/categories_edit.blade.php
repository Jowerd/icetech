@extends('admin.layout')
@section('title', 'კატეგორიის რედაქტირება • ICETECH')

@section('content')
<form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-dark-emphasis"><i class="bi bi-pencil-square me-2"></i> კატეგორიის რედაქტირება</h1>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card card-custom">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label for="name" class="form-label fs-5">კატეგორიის სახელი</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control form-control-lg" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label">აღწერა</label>
                        <textarea name="description" class="form-control" rows="8">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div>
                        <label for="keywords" class="form-label">საკვანძო სიტყვები (გამოყავით მძიმით)</label>
                        <textarea name="keywords" class="form-control" rows="4">{{ old('keywords', $category->keywords) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="side-panel">
                <div class="card card-custom mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">მოქმედებები</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                             <a href="{{ route('admin.categories.index') }}" class="btn btn-light">გაუქმება</a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i> განახლება
                            </button>
                        </div>
                        <hr>
                        <small class="text-muted d-block">
                            <i class="bi bi-clock me-1"></i> ბოლოს განახლდა: {{ $category->updated_at->diffForHumans() }}
                        </small>
                    </div>
                </div>

                <div class="card card-custom">
                     <div class="card-header">
                        <h6 class="mb-0">კატეგორიის ფოტო</h6>
                    </div>
                    <div class="card-body">
                        <div class="image-uploader">
                            <label for="imageInput" class="image-upload-label">
                                <img id="imagePreview" src="{{ $category->image ? asset('storage/' . $category->image) : 'https://via.placeholder.com/400x300.png/f8f9fa/6c757d?text=No+Image' }}" alt="Image Preview">
                                <div class="image-upload-overlay">
                                    <i class="bi bi-camera fs-2"></i>
                                    <span class="mt-2">ფოტოს შეცვლა</span>
                                </div>
                            </label>
                            <input type="file" name="image" id="imageInput" class="d-none" onchange="previewImage(event)">
                            
                            @if($category->image)
                                <button type="button" class="btn btn-sm btn-danger delete-image-btn" onclick="document.getElementById('delete_image_checkbox').checked = true; alert('ფოტო წაიშლება განახლების შემდეგ.');">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <input type="checkbox" name="delete_image" id="delete_image_checkbox" class="d-none">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .card-custom {
        border: 1px solid #eef2f7;
        border-radius: 0.75rem;
        box-shadow: none;
        background-color: #ffffff;
    }
    .card-header {
        background-color: #f8f9fa;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #eef2f7;
    }
    .form-control-lg {
        font-size: 1.25rem;
        padding: 0.75rem 1rem;
    }
    .form-control:focus {
        box-shadow: none;
        border-color: var(--bs-primary);
    }
    .side-panel {
        position: sticky;
        top: 20px;
    }

    /* ფოტოს ამტვირთავი */
    .image-uploader {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .image-upload-label {
        display: block;
        cursor: pointer;
        margin: 0;
    }
    #imagePreview {
        width: 100%;
        height: auto;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        transition: filter 0.2s ease;
    }
    .image-upload-overlay {
        position: absolute;
        inset: 0;
        background-color: rgba(0,0,0,0.5);
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .image-uploader:hover .image-upload-overlay {
        opacity: 1;
    }
    .image-uploader:hover #imagePreview {
        filter: brightness(0.7);
    }
    .delete-image-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        padding: 0;
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
        }
    }
</script>
@endsection