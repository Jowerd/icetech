@extends('admin.layout')
@section('title', 'ახალი ბლოგპოსტის დამატება')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">ახალი ბლოგპოსტის დამატება</h2>

    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">სათაური</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="excerpt" class="form-label">მოკლე აღწერა (არჩევითი)</label>
            <textarea name="excerpt" id="excerpt" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">შინაარსი</label>
            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="8" required></textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">სურათი</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">დამატება</button>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">გაუქმება</a>
    </form>
</div>
@endsection
