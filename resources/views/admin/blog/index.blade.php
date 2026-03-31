@extends('admin.layout')
@section('title', 'ბლოგპოსტები • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">ბლოგპოსტების მართვა</h4>
            <p class="text-muted small mb-0">სტატიები, სიახლეები და რჩევები</p>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary rounded-1 fw-bold px-4 shadow-sm small">
            <i class="bi bi-plus-lg me-1"></i> ახალი პოსტი
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-1 small mb-4">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-1 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light border-bottom text-uppercase small fw-bold text-secondary">
                    <tr>
                        <th class="ps-4 py-3" style="width: 80px;">თარიღი</th>
                        <th class="py-3">სათაური</th>
                        <th class="py-3 d-none d-lg-table-cell">ბმული (Slug)</th>
                        <th class="py-3 text-end pe-4">მოქმედება</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr class="border-bottom">
                        <td class="ps-4">
                            <div class="text-dark fw-bold small mb-0">{{ $post->created_at->format('d') }}</div>
                            <div class="text-muted x-small-text text-uppercase">{{ $post->created_at->format('M, Y') }}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="post-icon-box bg-light text-primary rounded me-3 d-none d-sm-flex">
                                    <i class="bi bi-journal-text"></i>
                                </div>
                                <div>
                                    <h6 class="text-dark fw-bold mb-0 small">{{ $post->title }}</h6>
                                    <div class="d-md-none x-small-text text-muted mt-1">/{{ $post->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="d-none d-lg-table-cell">
                            <code class="x-small-text text-primary">/{{ $post->slug }}</code>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group border rounded bg-white shadow-sm">
                                <a href="{{ route('admin.blog.edit', $post->slug) }}" class="btn btn-sm btn-white text-warning px-2 border-end" title="რედაქტირება">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.blog.destroy', $post->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('დარწმუნებული ხართ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-white text-danger px-2" title="წაშლა">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-journal-x display-4 text-light"></i>
                                <h6 class="text-muted mt-3">ბლოგპოსტები არ მოიძებნა</h6>
                                <a href="{{ route('admin.blog.create') }}" class="btn btn-outline-primary btn-sm mt-2 rounded-1">შექმენი პირველი პოსტი</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $posts->links() }}
    </div>
</div>

<style>
    /* Global Overrides for Industrial Look */
    .x-small-text { font-size: 0.7rem; letter-spacing: 0.02rem; }
    
    .post-icon-box {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .btn-white { background: #fff; border: none; transition: background 0.2s; }
    .btn-white:hover { background: #f8f9fa; }

    /* Table Hover Styling */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.02);
    }

    /* Column Widths */
    th { letter-spacing: 0.05rem; }

    @media (max-width: 768px) {
        .post-icon-box { width: 32px; height: 32px; font-size: 1rem; }
    }
</style>
@endsection