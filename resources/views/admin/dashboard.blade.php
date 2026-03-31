@extends('admin.layout')
@section('title', 'ადმინ პანელი • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">
    
    {{-- სტატისტიკის სექცია - პირდაპირ ზემოდან --}}
    <div class="row g-3 g-md-4 mb-4">
        {{-- კატეგორიები --}}
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm dashboard-stat-card">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary-soft me-3">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <div>
                            <span class="text-muted x-small fw-bold text-uppercase ls-1">კატეგორია</span>
                            <h3 class="fw-800 mb-0 mt-0">{{ \App\Models\Category::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- პროდუქტები --}}
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm dashboard-stat-card">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success-soft me-3">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <span class="text-muted x-small fw-bold text-uppercase ls-1">პროდუქტი</span>
                            <h3 class="fw-800 mb-0 mt-0">{{ \App\Models\Product::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- მიმოხილვები --}}
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm dashboard-stat-card">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning-soft me-3">
                            <i class="bi bi-chat-square-heart"></i>
                        </div>
                        <div>
                            <span class="text-muted x-small fw-bold text-uppercase ls-1">მიმოხილვა</span>
                            <h3 class="fw-800 mb-0 mt-0">{{ \App\Models\Review::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ბლოგი --}}
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm dashboard-stat-card">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info-soft me-3">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <div>
                            <span class="text-muted x-small fw-bold text-uppercase ls-1">სტატია</span>
                            <h3 class="fw-800 mb-0 mt-0">{{ \App\Models\BlogPost::count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ძირითადი კონტენტის ზონა --}}
    <div class="row g-4">
        {{-- ბოლო პროდუქტები --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>ბოლო დამატებული</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-600">ყველა</a>
                </div>
                <div class="card-body p-0 p-md-4 mt-2">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle custom-table mb-0">
                            <thead>
                                <tr class="text-muted x-small text-uppercase">
                                    <th class="ps-4 border-0">პროდუქტი</th>
                                    <th class="border-0">სტატუსი</th>
                                    <th class="border-0">თარიღი</th>
                                    <th class="text-end pe-4 border-0">მართვა</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\Product::latest()->take(6)->get() as $product)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-3 p-2 me-3 d-none d-md-block">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                                <div class="fw-bold text-dark text-truncate" style="max-width: 220px;">{{ $product->name }}</div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-success-subtle text-success rounded-pill px-2">აქტიური</span></td>
                                        <td><span class="text-muted small">{{ $product->created_at->format('d.m.Y') }}</span></td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-action-pill">
                                                რედაქტირება
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-5 text-muted">პროდუქტები არ არის</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- აქტივობა (Timeline) --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold text-dark mb-0">ბოლო მიმოხილვები</h5>
                </div>
                <div class="card-body p-4">
                    <div class="modern-timeline">
                        @forelse(\App\Models\Review::latest()->take(6)->get() as $review)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-info">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold small text-dark">მომხმარებელი</span>
                                        <span class="x-small text-muted">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mb-0 text-muted small mt-1 italic">"{{ Str::limit($review->comment, 60) }}"</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-5 text-muted small">აქტივობა არ არის</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Global Tweaks */
    .fw-800 { font-weight: 800; }
    .fw-600 { font-weight: 600; }
    .ls-1 { letter-spacing: 0.5px; }
    .x-small { font-size: 0.7rem; }

    /* Stat Cards - Horizontal Layout */
    .dashboard-stat-card {
        border-radius: 16px;
        transition: all 0.2s ease-in-out;
    }
    .dashboard-stat-card:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 20px rgba(0,0,0,0.04) !important;
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    /* Table & Action Pills */
    .custom-table tbody td {
        padding-top: 15px;
        padding-bottom: 15px;
    }
    .btn-action-pill {
        background-color: #f1f5f9;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 15px;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-action-pill:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    /* Compact Timeline */
    .modern-timeline { position: relative; padding-left: 15px; }
    .timeline-item { position: relative; padding-bottom: 20px; }
    .timeline-marker {
        position: absolute; left: -15px; top: 6px;
        width: 8px; height: 8px; border-radius: 50%;
    }
    .timeline-item::before {
        content: ''; position: absolute; left: -11.5px; top: 12px;
        width: 1px; height: 100%; background: #f1f5f9;
    }
    .timeline-item:last-child::before { display: none; }

    /* Colors */
    .bg-primary-soft { background: #e0e7ff; color: #4338ca; }
    .bg-success-soft { background: #dcfce7; color: #15803d; }
    .bg-warning-soft { background: #fef9c3; color: #a16207; }
    .bg-info-soft { background: #e0f2fe; color: #0369a1; }

    @media (max-width: 768px) {
        .dashboard-stat-card h3 { font-size: 1.1rem !important; }
        .dashboard-stat-card { border-radius: 12px; }
    }
</style>
@endsection