@extends('admin.layout') 
@section('title', 'კატეგორიის რედაქტირება • ICETECH')  

@section('content')
    <h1 class="mb-4"><i class="bi bi-pencil-square"></i> კატეგორიის რედაქტირება</h1>
    
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-arrow-repeat"></i> რედაქტირება: {{ $category->name }}</h5>

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label"><i class="bi bi-tag"></i> კატეგორიის სახელი</label>
                    <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label"><i class="bi bi-image"></i> კატეგორიის სურათი</label>
                    <input type="file" name="image" class="form-control" id="imageInput" onchange="previewImage(event)">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label"><i class="bi bi-text-paragraph"></i> კატეგორიის აღწერა</label>
                    <textarea name="description" class="form-control" id="description" rows="6">{{ $category->description }}</textarea>
                    <small class="text-muted">შეუზღუდავი სიგრძის ტექსტი შეგიძლიათ შეიყვანოთ</small>
                </div>
                                
                <div class="mb-3">
                    <label for="keywords" class="form-label"><i class="bi bi-tags"></i> საკვანძო სიტყვები</label>
                    <textarea name="keywords" class="form-control" id="keywords" rows="3" placeholder="გამოყავით მძიმეებით">{{ $category->keywords }}</textarea>
                    <small class="text-muted">შეუზღუდავი რაოდენობის საკვანძო სიტყვები</small>
                </div>
                
                <!-- მიმდინარე სურათის წინასწარი გადახედვა -->
                @if($category->image)
                    <div class="mb-3 text-center">
                        <p class="mb-1"><i class="bi bi-eye"></i> მიმდინარე ფოტო:</p>
                        <img id="currentImage" src="{{ asset('storage/' . $category->image) }}" class="rounded shadow-sm" width="150" height="150" style="object-fit: cover;">
                    </div>
                @endif
                
                <button type="submit" class="btn btn-outline-success w-100"><i class="bi bi-check-circle"></i> განახლება</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100 mt-2"><i class="bi bi-arrow-left"></i> უკან დაბრუნება</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function previewImage(event) {
        var output = document.getElementById('currentImage');
        if (output) {
            output.src = URL.createObjectURL(event.target.files[0]);
        }
    }
</script>
@endsection