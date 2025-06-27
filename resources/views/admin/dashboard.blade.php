@extends('admin.layout')
@section('title', 'დეშბორდი • ICETECH')
@section('content')
    <h1 class="mb-4 text-dark-emphasis">👋 მოგესალმებით, <strong>{{ auth()->guard('admin')->user()->name }}</strong>!</h1>

    <div class="row g-4 mb-5"> <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100"> <div class="card-body d-flex align-items-center">
                    <div class="icon-circle me-4 bg-primary-subtle">
                        <i class="bi bi-folder-fill fs-3 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-secondary mb-0">კატეგორიები</p>
                        <h2 class="card-title mb-0 text-dark">{{ \App\Models\Category::count() }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle me-4 bg-success-subtle">
                        <i class="bi bi-box-seam fs-3 text-success"></i>
                    </div>
                    <div>
                        <p class="text-secondary mb-0">პროდუქტები</p>
                        <h2 class="card-title mb-0 text-dark">{{ \App\Models\Product::count() }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle me-4 bg-warning-subtle">
                        <i class="bi bi-chat-left-dots-fill fs-3 text-warning"></i>
                    </div>
                    <div>
                        <p class="text-secondary mb-0">მიმოხილვები</p>
                        <h2 class="card-title mb-0 text-dark">{{ \App\Models\Review::count() }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-circle me-4 bg-danger-subtle">
                        <i class="bi bi-journal-text fs-3 text-danger"></i>
                    </div>
                    <div>
                        <p class="text-secondary mb-0">ბლოგი</p>
                        <h2 class="card-title mb-0 text-dark">{{ \App\Models\BlogPost::count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-dark-emphasis"><i class="bi bi-hourglass-split me-2 text-primary"></i> ბოლო პროდუქტები</h5>
                </div>
                <div class="card-body p-0"> <ul class="list-group list-group-flush border-top-0">
                        @forelse(\App\Models\Product::latest()->take(5)->get() as $product)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-decoration-none text-dark fw-medium">
                                    {{ $product->name }}
                                </a>
                                <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item py-4 text-center text-muted">პროდუქტები არ მოიძებნა.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-dark-emphasis"><i class="bi bi-chat-dots-fill me-2 text-success"></i> ბოლო მიმოხილვები</h5>
                </div>
                <div class="card-body p-0"> <ul class="list-group list-group-flush border-top-0">
                        @forelse(\App\Models\Review::latest()->take(5)->get() as $review)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <span class="text-dark fw-medium">{{ Str::limit($review->comment, 40) }}</span>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item py-4 text-center text-muted">მიმოხილვები არ მოიძებნა.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* CSS ცვლილებები */
        .card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background-color: #ffffff; /* თეთრი ფონი ბარათებისთვის */
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1) !important; /* დახვეწილი ჩრდილი hover-ზე */
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            min-width: 60px; /* რათა არ შემცირდეს პატარა ეკრანებზე */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%; /* მრგვალი ხატულების ფონი */
        }
        
        .card-body h2 {
            font-size: 2.2rem; /* უფრო დიდი რაოდენობები */
            font-weight: 700;
        }

        .card-body p {
            font-size: 0.9rem; /* უფრო პატარა აღწერა */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-header {
            background-color: #ffffff !important; /* თეთრი ჰედერი */
            border-bottom: 1px solid #e9ecef !important; /* რბილი საზღვარი */
            font-weight: 600;
            font-size: 1.1rem;
        }

        .list-group-item {
            border-color: #e9ecef; /* რბილი საზღვარი სიებში */
        }

        .list-group-item:last-child {
            border-bottom: none; /* ბოლო ელემენტს საზღვარი არ აქვს */
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .list-group-item a, .list-group-item span {
            font-weight: 500;
            color: #343a40; /* მუქი ტექსტი */
        }
    </style>
@endsection