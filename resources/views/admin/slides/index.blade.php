@extends('admin.layout')
@section('title', 'ბანერის სლაიდები • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">

    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1">ბანერის სლაიდები</h4>
            <p class="text-muted small mb-0">მართეთ მთავარი გვერდის hero სლაიდები</p>
        </div>
        <a href="{{ route('admin.slides.create') }}" class="btn btn-primary rounded-1 fw-bold px-4 shadow-sm small">
            <i class="bi bi-plus-lg me-1"></i> ახალი სლაიდი
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible rounded-1 border-0 shadow-sm small fw-bold" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($slides->isEmpty())
        <div class="card border rounded-1 shadow-none text-center py-5">
            <div class="card-body">
                <i class="bi bi-images text-muted" style="font-size:3rem;opacity:.3"></i>
                <h5 class="mt-3 fw-bold text-muted">სლაიდები არ არის</h5>
                <p class="text-muted small">დაამატეთ პირველი სლაიდი ღილაკზე დაჭერით</p>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($slides as $slide)
            <div class="col-12">
                <div class="card border rounded-1 shadow-none bg-white">
                    <div class="card-body p-3 d-flex align-items-center gap-3">

                        {{-- სლაიდის ნომერი --}}
                        <div class="slide-order-badge">{{ $slide->order ?: $loop->iteration }}</div>

                        {{-- სურათი --}}
                        <div class="slide-thumb">
                            @if($slide->image)
                                <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}">
                            @else
                                <div class="slide-thumb-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                        </div>

                        {{-- ინფო --}}
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="fw-bold text-dark text-truncate">{{ $slide->title }}</span>
                                @if($slide->is_active)
                                    <span class="badge bg-success-subtle text-success rounded-pill" style="font-size:.65rem">აქტიური</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill" style="font-size:.65rem">გამორთული</span>
                                @endif
                            </div>
                            @if($slide->description)
                                <p class="text-muted small mb-1 text-truncate">{{ $slide->description }}</p>
                            @endif
                            @if($slide->button_text)
                                <span class="small text-muted"><i class="bi bi-cursor me-1"></i>{{ $slide->button_text }} → {{ $slide->button_url }}</span>
                            @endif
                        </div>

                        {{-- ღილაკები --}}
                        <div class="d-flex gap-2 flex-shrink-0">
                            <a href="{{ route('admin.slides.edit', $slide) }}"
                               class="btn btn-sm btn-light border rounded-1 px-3">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.slides.destroy', $slide) }}" method="POST"
                                  onsubmit="return confirm('დარწმუნებული ხართ წაშლაში?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger rounded-1 px-3">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.slide-order-badge {
    width: 32px; height: 32px;
    background: #f1f5f9;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .85rem; color: #64748b;
    flex-shrink: 0;
}
.slide-thumb {
    width: 120px; height: 68px;
    border-radius: 6px; overflow: hidden;
    background: #f8fafc; border: 1px solid #e2e8f0;
    flex-shrink: 0;
}
.slide-thumb img { width: 100%; height: 100%; object-fit: cover; }
.slide-thumb-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    color: #cbd5e1; font-size: 1.5rem;
}
</style>
@endsection
