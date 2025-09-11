@extends('admin.layout')
@section('title', 'დეშბორდი • ICETECH')

@section('content')
    {{-- მისასალმებელი ტექსტი --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 text-dark-emphasis">👋 მოგესალმებით, <strong>{{ auth()->guard('admin')->user()->name }}</strong>!</h1>
    </div>

    {{-- სტატისტიკური ბარათები --}}
    <div class="row g-4">
        {{-- კატეგორიები --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-primary-subtle me-4">
                        <i class="bi bi-folder-fill fs-3 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-secondary mb-1">კატეგორიები</p>
                        <h2 class="card-title mb-0 text-dark">{{ \App\Models\Category::count() }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- პროდუქტები --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-success-subtle me-4">
                        <i class="bi bi-box-seam fs-3 text-success"></i>
                    </div>
                    <div>
                        <p class="text-secondary mb-1">პროდუქტები</p>
                        <h2 class="card-title mb-0 text-dark">{{ \App\Models\Product::count() }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- მიმოხილვები --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-warning-subtle me-4">
                        <i class="bi bi-chat-left-dots-fill fs-3 text-warning"></i>
                    </div>
                    <div>
                        <p class="text-secondary mb-1">მიმოხილვები</p>
                        <h2 class="card-title mb-0 text-dark">{{ \App\Models\Review::count() }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- ბლოგი --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle bg-danger-subtle me-4">
                        <i class="bi bi-journal-text fs-3 text-danger"></i>
                    </div>
                    <div>
                        <p class="text-secondary mb-1">ბლოგი</p>
                        <h2 class="card-title mb-0 text-dark">{{ \App\Models\BlogPost::count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ბოლო აქტივობების სექცია --}}
    <div class="row g-4 mt-4">
        {{-- ბოლო პროდუქტები --}}
        <div class="col-lg-6">
            <div class="card h-100 recent-activity-card">
                <div class="card-header">
                    <h5 class="mb-0 text-dark-emphasis"><i class="bi bi-hourglass-split me-2 text-primary"></i> ბოლო პროდუქტები</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse(\App\Models\Product::latest()->take(5)->get() as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-decoration-none item-link">
                                    {{ $product->name }}
                                </a>
                                <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">პროდუქტები არ მოიძებნა.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- ბოლო მიმოხილვები --}}
        <div class="col-lg-6">
            <div class="card h-100 recent-activity-card">
                <div class="card-header">
                    <h5 class="mb-0 text-dark-emphasis"><i class="bi bi-chat-dots-fill me-2 text-success"></i> ბოლო მიმოხილვები</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse(\App\Models\Review::latest()->take(5)->get() as $review)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="item-text">{{ Str::limit($review->comment, 45) }}</span>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">მიმოხილვები არ მოიძებნა.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS სტილები --}}
    <style>
        /* ზოგადი სტილები */
        body {
            background-color: #f4f7fc;
            color: #343a40;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
        }

        /* ბარათის ზოგადი სტილი */
        .card {
            border: none;
            border-radius: 1rem; /* მომრგვალებული კუთხეები */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background-color: #ffffff;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            overflow: hidden; /* შიგთავსის ჩარჩოში მოსათავსებლად */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* სტატისტიკის ბარათები */
        .stat-card .card-body {
            padding: 1.5rem;
        }
        
        .stat-card .icon-circle {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .stat-card .card-title {
            font-size: 2.1rem;
            font-weight: 700;
        }

        .stat-card p {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        /* ბოლო აქტივობების ბარათები */
        .recent-activity-card .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #eef2f7;
            padding: 1.25rem;
            font-size: 1.1rem;
        }

        .recent-activity-card .card-header i {
            font-size: 1.3rem; /* ხატულების ზომა */
        }

        .recent-activity-card .list-group-item {
            padding: 1rem 1.25rem;
            border-color: #eef2f7;
            transition: background-color 0.2s ease;
        }
        
        .recent-activity-card .list-group-item:last-child {
            border-bottom: none;
        }

        .recent-activity-card .list-group-item:hover {
            background-color: #f8f9fa;
        }
        
        .recent-activity-card .item-link,
        .recent-activity-card .item-text {
            color: #212529;
            font-weight: 500;
        }

        .recent-activity-card .item-link:hover {
            color: #0d6efd; /* ბმულის ფერი hover-ზე */
        }

        /* რესპონსიულობა */
        @media (max-width: 768px) {
            h1 {
                font-size: 1.75rem;
            }

            .stat-card .card-title {
                font-size: 1.8rem;
            }
        }
    </style>
@endsection