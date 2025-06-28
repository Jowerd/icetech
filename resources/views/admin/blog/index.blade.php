@extends('admin.layout')
@section('title', 'ბლოგპოსტები')

@section('content')

{{-- გვერდის სტილები თავმოყრილია აქ --}}
<style>
    :root {
        --primary-color-blog: #007bff;
        --border-color-blog: #e9ecef;
        --light-bg-blog: #f8f9fa;
        --text-muted-color-blog: #6c757d;
        --border-radius-blog: 0.5rem;
    }

    .blog-list-container .card-header {
        background-color: #fff;
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-color-blog);
    }

    .blog-list-container .btn-primary {
        background-color: var(--primary-color-blog);
        border-color: var(--primary-color-blog);
        border-radius: 25px;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
        transition: all 0.2s ease;
    }

    .blog-list-container .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }

    .blog-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .blog-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color-blog);
        transition: background-color 0.2s ease;
    }

    .blog-list-item:last-child {
        border-bottom: none;
    }

    .blog-list-item:hover {
        background-color: var(--light-bg-blog);
    }

    .blog-list-item .post-title {
        font-weight: 600;
        color: #343a40;
        margin: 0;
    }

    .blog-list-item .post-date {
        color: var(--text-muted-color-blog);
        font-size: 0.9rem;
        margin-left: 1rem;
        min-width: 90px;
        text-align: right;
    }

    .blog-list-item .action-buttons .btn {
        border-radius: 20px;
        font-size: 0.85rem;
        margin-left: 0.5rem;
    }

    .empty-blog-state {
        text-align: center;
        padding: 4rem;
        background-color: var(--light-bg-blog);
        border-radius: var(--border-radius-blog);
        border: 2px dashed var(--border-color-blog);
    }

    .empty-blog-state .icon {
        font-size: 3rem;
        color: #ced4da;
    }

    /* მობილურისთვის ადაპტაცია */
    @media (max-width: 767px) {
        .blog-list-item {
            flex-direction: column;
            align-items: flex-start;
        }
        .blog-list-item .post-date {
            display: none; /* თარიღს მაინც ვაჩენთ სათაურის ქვეშ */
        }
        .blog-list-item .action-buttons {
            margin-top: 1rem;
            width: 100%;
            display: flex;
            justify-content: flex-start;
        }
        .blog-list-item .action-buttons .btn {
            margin-left: 0;
            margin-right: 0.5rem;
        }
        .blog-list-container .card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .blog-list-container .card-header .btn {
            margin-top: 1rem;
        }
    }
</style>

{{-- გვერდის HTML სტრუქტურა --}}
<div class="container py-4">
    <div class="card shadow-sm border-0 blog-list-container">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0 h4">ბლოგპოსტების სია</h2>
            <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> ახალი პოსტის დამატება
            </a>
        </div>

        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success m-3 rounded-pill">{{ session('success') }}</div>
            @endif

            <div class="blog-list">
                @forelse($posts as $post)
                    <div class="blog-list-item">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-text text-muted fs-4 me-3"></i>
                            <div>
                                <p class="post-title">{{ $post->title }}</p>
                                {{-- თარიღი მობილურზე სათაურის ქვეშ --}}
                                <small class="text-muted d-block d-md-none">{{ $post->created_at->format('d.m.Y') }}</small>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            {{-- თარიღი დესკტოპზე --}}
                            <span class="post-date d-none d-md-block">{{ $post->created_at->format('d.m.Y') }}</span>
                            <div class="action-buttons">
                                <a href="{{ route('admin.blog.edit', $post->slug) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> რედაქტირება
                                </a>
                                <form action="{{ route('admin.blog.destroy', $post->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('დარწმუნებული ხარ რომ გინდა წაშლა?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i> წაშლა
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-blog-state m-3">
                        <div class="icon"><i class="bi bi-journal-x"></i></div>
                        <h5 class="mt-3">ბლოგპოსტები არ მოიძებნა</h5>
                        <p class="text-muted">დაამატეთ თქვენი პირველი პოსტი, რომ ის აქ გამოჩნდეს.</p>
                        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-lg"></i> პირველი პოსტის დამატება
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        @if ($posts->hasPages())
            <div class="card-footer bg-white">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection