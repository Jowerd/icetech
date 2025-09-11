@extends('admin.layout')
@section('title', 'კატეგორიები • ICETECH')

@section('content')
    <h1 class="mb-4 text-dark-emphasis"><i class="bi bi-folder-fill me-2"></i> კატეგორიების მართვა</h1>
    
    <div class="card card-custom add-category-card mb-5">
        <div class="card-body p-4">
            <h5 class="card-title mb-4"><i class="bi bi-plus-circle me-2"></i> ახალი კატეგორიის დამატება</h5>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">კატეგორიის სახელი</label>
                        <input type="text" name="name" class="form-control form-control-lg" id="name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="keywords" class="form-label">საკვანძო სიტყვები (მძიმით გამოყოფა)</label>
                        <input type="text" name="keywords" class="form-control form-control-lg" id="keywords">
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">კატეგორიის აღწერა</label>
                        <textarea name="description" class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="image" class="form-label">კატეგორიის ფოტო</label>
                        <input type="file" name="image" class="form-control" id="image">
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100"><i class="bi bi-check-circle me-2"></i> დამატება</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <h2 class="mb-4 text-dark-emphasis"><i class="bi bi-boxes me-2"></i> არსებული კატეგორიები</h2>
    <div class="row g-4">
        @forelse ($categories as $category)
            <div class="col-md-6 col-lg-4">
                <div class="card card-custom category-item-card h-100">
                    <div class="card-body d-flex align-items-center">
                        
                        <div class="image-wrapper flex-shrink-0">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                            @else
                                <div class="placeholder-icon">
                                    <i class="bi bi-folder-fill"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="ms-3 flex-grow-1">
                            <h6 class="category-name mb-1">{{ $category->name }}</h6>
                            @if($category->description)
                                <p class="category-description text-muted small mb-2">{{ Str::limit($category->description, 50) }}</p>
                            @endif
                            
                            <div class="actions-group">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>რედაქტირება</span>
                                </a>
                                
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('დარწმუნებული ხარ, რომ გსურს წაშლა?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                        <span>წაშლა</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    კატეგორიები ჯერ არ არის დამატებული.
                </div>
            </div>
        @endforelse
    </div>

    <style>
        /* ზოგადი სტილები */
        .card-custom {
            border: none;
            border-radius: 1rem; /* 16px */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background-color: #ffffff;
            transition: all 0.2s ease-in-out;
        }

        /* ფორმის ბარათის სტილი */
        .add-category-card {
            border: 1px solid #eef2f7;
        }

        .form-control, .form-select {
            border-radius: 0.5rem; /* 8px */
            border-color: #dee2e6;
        }
        .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.15);
        }
        .form-control-lg {
            font-size: 1rem;
            padding: 0.75rem 1rem;
        }
        .btn-lg {
             padding: 0.75rem 1rem;
        }

        /* კატეგორიის ბარათების სტილი */
        .category-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .image-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 0.75rem; /* 12px */
            overflow: hidden;
            background-color: #f8f9fa;
        }
        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-wrapper .placeholder-icon {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: #ced4da;
        }

        .category-name {
            font-weight: 600;
            color: #212529;
            font-size: 1.05rem;
        }

        .category-description {
            line-height: 1.4;
        }
        
        .actions-group {
            display: flex;
            align-items: center;
            gap: 0.5rem; /* 8px */
            margin-top: 0.5rem;
        }
        .actions-group .btn {
            display: flex;
            align-items: center;
            gap: 0.35rem; /* 6px */
        }

        /* მობილურისთვის ღილაკებზე ტექსტის დამალვა */
        @media (max-width: 576px) {
            .actions-group .btn span {
                display: none;
            }
        }
    </style>
@endsection