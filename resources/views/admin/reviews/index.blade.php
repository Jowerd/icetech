@extends('admin.layout')
@section('title', 'შეფასებები • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">
    
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-0">მომხმარებელთა შეფასებები</h4>
            <p class="text-muted small mb-0">კონტროლი და მოდერაცია</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-1 small mb-4">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-1 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light border-bottom text-uppercase small fw-bold text-secondary">
                    <tr>
                        <th class="ps-4 py-3" style="width: 50px;">ID</th>
                        <th class="py-3">ავტორი</th>
                        <th class="py-3">სახეობა</th>
                        <th class="py-3">შეფასება</th>
                        <th class="py-3">რეიტინგი</th>
                        <th class="py-3 text-center">სტატუსი</th>
                        <th class="py-3 text-end pe-4">მოქმედება</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                    <tr class="border-bottom {{ !$review->is_approved ? 'bg-light-yellow' : '' }}">
                        <td class="ps-4 text-muted small">#{{ $review->id }}</td>
                        <td>
                            <div class="fw-bold text-dark small">{{ $review->author_name }}</div>
                            <div class="x-small-text text-muted">{{ $review->author_email ?: 'No Email' }}</div>
                        </td>
                        <td>
                            @if($review->product_id && $review->product)
                                <span class="badge bg-soft-primary text-primary border border-primary x-small-badge">პროდუქტი</span>
                                <div class="x-small-text text-muted mt-1 text-truncate" style="max-width:140px;">
                                    <a href="{{ route('products.show', $review->product->slug) }}" target="_blank" class="text-decoration-none text-muted">
                                        {{ $review->product->name }}
                                    </a>
                                </div>
                            @else
                                <span class="badge bg-soft-info text-info border border-info x-small-badge">ზოგადი</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-dark small text-truncate" style="max-width: 250px;">
                                {{ $review->content }}
                            </div>
                            <div class="x-small-text text-muted">{{ $review->created_at->format('d.m.Y H:i') }}</div>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill text-warning' : ' text-light-gray' }} x-small-star"></i>
                                @endfor
                            </div>
                        </td>
                        <td class="text-center">
                            @if ($review->is_approved)
                                <span class="badge bg-soft-success text-success border border-success x-small-badge">დამტკიცებული</span>
                            @else
                                <span class="badge bg-soft-warning text-warning border border-warning x-small-badge">მოლოდინში</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group border rounded bg-white shadow-sm">
                                <button type="button" class="btn btn-sm btn-white text-info px-2" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $review->id }}" title="ნახვა">
                                    <i class="bi bi-eye"></i>
                                </button>

                                @if (!$review->is_approved)
                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline border-start">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-white text-success px-2" title="დამტკიცება">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline border-start">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-white text-danger px-2" onclick="return confirm('ნამდვილად გსურთ წაშლა?')" title="წაშლა">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow rounded-1 text-start">
                                        <div class="modal-header bg-light py-2">
                                            <h6 class="modal-title fw-bold">შეფასების დეტალები #{{ $review->id }}</h6>
                                            <button type="button" class="btn-close small" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3 d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h5 class="fw-bold mb-0">{{ $review->author_name }}</h5>
                                                    <p class="text-muted small">{{ $review->author_email }}</p>
                                                </div>
                                                <div class="text-warning h5">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <hr>
                                            <p class="text-dark small lh-lg bg-light p-3 rounded">
                                                {{ $review->content }}
                                            </p>
                                            @if ($review->image)
                                                <div class="mt-3">
                                                    <p class="x-small-text fw-bold text-uppercase text-secondary">მიბმული ფოტო:</p>
                                                    <img src="{{ asset('storage/' . $review->image) }}" class="img-fluid rounded border shadow-sm">
                                                </div>
                                            @endif
                                            <div class="mt-3 text-muted x-small-text text-end">
                                                გამოგზავნილია: {{ $review->created_at->format('d.m.Y H:i') }}
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 bg-light py-2">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">დახურვა</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted small">შეფასებები არ არის</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
</div>

<style>
    /* Inbox Style */
    .bg-light-yellow { background-color: #fffdf2; } /* გამოყოფს დასამტკიცებელ შეფასებებს */
    .x-small-text { font-size: 0.72rem; }
    .x-small-star { font-size: 0.75rem; }
    .x-small-badge { font-size: 0.65rem; padding: 4px 8px; text-transform: uppercase; font-weight: 700; }
    
    .bg-soft-success { background-color: #e8f5e9; }
    .bg-soft-warning { background-color: #fff3e0; }
    .bg-soft-primary { background-color: #e8f0fe; }
    .bg-soft-info    { background-color: #e0f7fa; }
    .text-light-gray { color: #e0e0e0; }

    .btn-white { background: #fff; border: none; }
    .btn-white:hover { background: #f8f9fa; }

    .table-hover tbody tr:hover { background-color: #f1f4f9; }
</style>
@endsection