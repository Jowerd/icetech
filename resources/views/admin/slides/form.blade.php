@extends('admin.layout')
@section('title', ($slide->exists ? 'სლაიდის რედაქტირება' : 'ახალი სლაიდი') . ' • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">

    <form action="{{ $slide->exists ? route('admin.slides.update', $slide) : route('admin.slides.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if($slide->exists) @method('PUT') @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom gap-3">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ $slide->exists ? 'სლაიდის რედაქტირება' : 'ახალი სლაიდი' }}</h4>
                <p class="text-muted small mb-0">hero ბანერის სლაიდის ინფორმაცია</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.slides.index') }}" class="btn btn-light border rounded-1 fw-bold px-3 small">გაუქმება</a>
                <button type="submit" class="btn btn-primary rounded-1 fw-bold px-4 shadow-sm small">
                    <i class="bi bi-check-lg me-1"></i> {{ $slide->exists ? 'შენახვა' : 'დამატება' }}
                </button>
            </div>
        </div>

        <div class="row g-4">

            {{-- მარცხენა: ტექსტური ველები --}}
            <div class="col-12 col-lg-7">
                <div class="card border rounded-1 shadow-none bg-white">
                    <div class="card-body p-4 d-flex flex-column gap-4">

                        <div>
                            <label class="form-label small fw-bold text-uppercase text-secondary">სათაური <span class="text-danger">*</span></label>
                            <input type="text" name="title"
                                   value="{{ old('title', $slide->title) }}"
                                   class="form-control shadow-none @error('title') is-invalid @enderror"
                                   placeholder="მაგ: პროფესიონალური სამზარეულო" required autofocus>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label class="form-label small fw-bold text-uppercase text-secondary">ქვეტექსტი</label>
                            <textarea name="description" rows="3" maxlength="500"
                                      class="form-control shadow-none @error('description') is-invalid @enderror"
                                      placeholder="მოკლე აღწერა სლაიდის ქვეშ...">{{ old('description', $slide->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-sm-5">
                                <label class="form-label small fw-bold text-uppercase text-secondary">ღილაკის ტექსტი</label>
                                <input type="text" name="button_text"
                                       value="{{ old('button_text', $slide->button_text) }}"
                                       class="form-control shadow-none"
                                       placeholder="მაგ: პროდუქციის ნახვა">
                            </div>
                            <div class="col-sm-7">
                                <label class="form-label small fw-bold text-uppercase text-secondary">ღილაკის ბმული</label>
                                <input type="text" name="button_url"
                                       value="{{ old('button_url', $slide->button_url) }}"
                                       class="form-control shadow-none"
                                       placeholder="მაგ: /products ან https://...">
                            </div>
                        </div>

                        <div class="row g-3 align-items-center">
                            <div class="col-sm-4">
                                <label class="form-label small fw-bold text-uppercase text-secondary">თანმიმდევრობა</label>
                                <input type="number" name="order" min="0" max="99"
                                       value="{{ old('order', $slide->order ?? 0) }}"
                                       class="form-control shadow-none" style="max-width:100px">
                                <span class="form-text text-muted" style="font-size:.7rem">0 = პირველი</span>
                            </div>
                            <div class="col-sm-8 pt-3">
                                <div class="form-check form-switch mt-1">
                                    <input class="form-check-input" type="checkbox" name="is_active"
                                           value="1" id="isActive"
                                           {{ old('is_active', $slide->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold small" for="isActive">სლაიდი ჩართულია</label>
                                </div>
                                <span class="form-text text-muted" style="font-size:.7rem">გამორთვით შეგიძლიათ დამალვა საიტიდან</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- მარჯვენა: სურათი --}}
            <div class="col-12 col-lg-5">
                <div class="card border rounded-1 shadow-none bg-white">
                    <div class="card-header bg-light py-2 border-bottom text-center">
                        <span class="fw-bold text-uppercase text-secondary" style="font-size:.65rem;letter-spacing:.05rem">სლაიდის სურათი</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="slide-img-preview border rounded mb-3 bg-light overflow-hidden d-flex align-items-center justify-content-center"
                             id="previewContainer">
                            @if($slide->exists && $slide->image)
                                <img id="imagePreview" src="{{ asset('storage/' . $slide->image) }}" class="w-100">
                                <div id="placeholderText" class="text-center p-4 text-muted d-none">
                                    <i class="bi bi-image fs-1 opacity-25"></i>
                                    <p class="mt-2 mb-0" style="font-size:.75rem">აირჩიეთ ფაილი</p>
                                </div>
                            @else
                                <img id="imagePreview" src="" class="w-100 d-none">
                                <div id="placeholderText" class="text-center p-4 text-muted">
                                    <i class="bi bi-image fs-1 opacity-25"></i>
                                    <p class="mt-2 mb-0" style="font-size:.75rem">აირჩიეთ სლაიდის ფოტო</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="image" id="imageInput" accept="image/*"
                               class="form-control form-control-sm border-2 shadow-none"
                               onchange="previewImage(event)">
                        <p class="text-muted mt-2 mb-0 text-center" style="font-size:.68rem">
                            <i class="bi bi-info-circle me-1"></i>JPG, PNG, WEBP — რეკომენდებული: 1920×800px
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<style>
.slide-img-preview {
    aspect-ratio: 16 / 7;
    background-color: #fcfcfc;
}
.slide-img-preview img {
    width: 100%; height: 100%; object-fit: cover;
}
</style>

@section('scripts')
<script>
function previewImage(event) {
    const output = document.getElementById('imagePreview');
    const placeholder = document.getElementById('placeholderText');
    if (event.target.files && event.target.files[0]) {
        output.src = URL.createObjectURL(event.target.files[0]);
        output.classList.remove('d-none');
        placeholder.classList.add('d-none');
    }
}
</script>
@endsection
@endsection
