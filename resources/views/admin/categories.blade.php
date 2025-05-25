@extends('admin.layout')
@section('title', 'კატეგორიები • ICETECH')
@section('content')
    <h1 class="mb-4"><i class="bi bi-folder-fill"></i> კატეგორიების მართვა</h1>
    
    <!-- ახალი კატეგორიის დამატების ფორმა -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-plus-circle"></i> ახალი კატეგორიის დამატება</h5>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label"><i class="bi bi-tag"></i> კატეგორიის სახელი</label>
                    <input type="text" name="name" class="form-control" id="name" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label"><i class="bi bi-text-paragraph"></i> კატეგორიის აღწერა</label>
                    <textarea name="description" class="form-control" id="description" rows="3"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="keywords" class="form-label"><i class="bi bi-tags"></i> საკვანძო სიტყვები</label>
                    <input type="text" name="keywords" class="form-control" id="keywords" placeholder="გამოყავით მძიმეებით">
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label"><i class="bi bi-image"></i> კატეგორიის ფოტო</label>
                    <input type="file" name="image" class="form-control" id="image">
                </div>
                
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-circle"></i> დამატება</button>
            </form>
        </div>
    </div>
    
    <!-- არსებული კატეგორიების სია -->
    <h2 class="mb-3"><i class="bi bi-boxes"></i> არსებული კატეგორიები</h2>
    <div class="row">
        @foreach ($categories as $category)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 mb-3 p-2">
                    <div class="d-flex align-items-center">
                        
                        <!-- კატეგორიის ფოტო -->
                        <div class="bg-light rounded" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                            @else
                                <i class="bi bi-folder-fill fs-1 text-secondary"></i>
                            @endif
                        </div>
                        
                        <!-- კატეგორიის ინფორმაცია -->
                        <div class="ms-3 flex-grow-1">
                            <h6 class="mb-1">{{ $category->name }}</h6>
                            @if($category->description)
                                <small class="text-muted d-block text-truncate" style="max-width: 200px;">{{ $category->description }}</small>
                            @endif
                            
                            <div class="d-flex mt-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-warning btn-sm me-2">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('დარწმუნებული ხარ, რომ გსურს წაშლა?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
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