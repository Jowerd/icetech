@extends('admin.layout')
@section('title', 'ბლოგის რედაქტირება • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">
    <form action="{{ route('admin.blog.update', $blog) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">ბლოგპოსტის რედაქტირება</h4>
                <div class="text-muted small">
                    <i class="bi bi-clock-history me-1"></i> ბოლო განახლება: {{ $blog->updated_at->format('d.m.Y H:i') }}
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.blog.index') }}" class="btn btn-light border rounded-1 fw-bold px-3 small">გაუქმება</a>
                <button type="submit" class="btn btn-primary rounded-1 fw-bold px-4 shadow-sm small">
                    <i class="bi bi-cloud-arrow-up me-1"></i> ცვლილებების შენახვა
                </button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card border rounded-1 shadow-none bg-white">
                    <div class="card-body p-3 p-md-4">
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-secondary">პოსტის სათაური</label>
                            <input type="text" name="title" value="{{ old('title', $blog->title) }}" 
                                   class="form-control blog-title-input shadow-none @error('title') is-invalid @enderror" 
                                   placeholder="შეიყვანეთ სათაური..." required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-bold text-uppercase text-secondary">ძირითადი კონტენტი</label>
                            <textarea name="content" id="blogContent" class="form-control">{{ old('content', $blog->content) }}</textarea>
                            @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card border rounded-1 shadow-none bg-white mb-4">
                    <div class="card-header bg-light py-2 border-bottom text-center">
                        <span class="fw-bold x-small-text text-uppercase text-secondary">მთავარი სურათი</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="blog-image-preview border rounded mb-3 bg-light overflow-hidden">
                            <img id="imagePreview" src="{{ $blog->image ? asset('storage/' . $blog->image) : 'https://via.placeholder.com/600x400?text=No+Image' }}" class="w-100">
                        </div>
                        <input type="file" name="image" id="imageInput" class="form-control form-control-sm border-2 shadow-none" onchange="previewImage(event)">
                        <p class="x-small-text text-muted mt-2 mb-0 text-center"><i class="bi bi-info-circle me-1"></i> რეკომენდირებული ზომა: 1200x800px</p>
                    </div>
                </div>

                <div class="card border rounded-1 shadow-none bg-white">
                    <div class="card-header bg-light py-2 border-bottom text-center">
                        <span class="fw-bold x-small-text text-uppercase text-secondary">მოკლე აღწერა (Excerpt)</span>
                    </div>
                    <div class="card-body p-3">
                        <textarea name="excerpt" rows="4" class="form-control border-2 shadow-none small" 
                                  placeholder="მოკლე ანოტაცია სოციალური ქსელებისთვის და სიისთვის...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                        <div class="mt-2 text-end">
                            <span id="charCount" class="x-small-text text-muted">0 / 160</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .blog-title-input {
        border: none;
        border-bottom: 2px solid #dee2e6;
        border-radius: 0;
        font-size: 1.5rem;
        font-weight: 800;
        padding-left: 0;
        padding-right: 0;
        color: #1a1a1a;
    }
    .blog-title-input:focus {
        border-bottom-color: #0d6efd;
    }

    .blog-image-preview {
        aspect-ratio: 16/10;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .blog-image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .x-small-text { font-size: 0.65rem; letter-spacing: 0.05rem; }
    
    /* TinyMCE Customize */
    .tox-tinymce { border: 2px solid #dee2e6 !important; border-radius: 4px !important; }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#blogContent',
        height: 500,
        plugins: 'advlist autolink lists link image charmap preview anchor pagebreak searchreplace wordcount visualblocks code fullscreen insertdatetime media table emoticons help',
        toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | emoticons | code fullscreen preview',
        menubar: 'edit insert view format table tools help',
        promotion: false,
        branding: false,
        content_style: 'body { font-family: Inter, sans-serif; font-size: 16px; line-height: 1.6; color: #333; } p { margin-bottom: 1rem; }'
    });

    function previewImage(event) {
        const output = document.getElementById('imagePreview');
        if (event.target.files && event.target.files[0]) {
            output.src = URL.createObjectURL(event.target.files[0]);
        }
    }

    // სიმბოლოების დათვლა მოკლე აღწერისთვის
    const excerpt = document.querySelector('textarea[name="excerpt"]');
    const charCount = document.getElementById('charCount');
    excerpt.addEventListener('input', () => {
        charCount.textContent = `${excerpt.value.length} / 160`;
    });
</script>
@endsection