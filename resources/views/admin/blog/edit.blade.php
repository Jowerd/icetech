@extends('admin.layout')
@section('title', 'ბლოგპოსტის რედაქტირება')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">ბლოგპოსტის რედაქტირება</h2>

    <form action="{{ route('admin.blog.update', $blog) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- სათაური -->
        <div class="mb-3">
            <label for="title" class="form-label">სათაური</label>
            <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}"
                   class="form-control @error('title') is-invalid @enderror" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- მოკლე აღწერა -->
        <div class="mb-3">
            <label for="excerpt" class="form-label">მოკლე აღწერა</label>
            <textarea name="excerpt" id="excerpt" rows="2" class="form-control">{{ old('excerpt', $blog->excerpt) }}</textarea>
        </div>

        <!-- შინაარსი -->
        <div class="mb-3">
            <label for="content" class="form-label">შინაარსი</label>
            <textarea name="content" id="content" rows="8"
                      class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $blog->content) }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- სურათის ატვირთვა -->
        <div class="mb-3">
            <label for="image" class="form-label">სურათი (შეცვლისთვის აირჩიე)</label>
            <input type="file" name="image" id="image" class="form-control">
            @if($blog->image)
                <img src="{{ asset('storage/' . $blog->image) }}" alt="Image"
                     class="img-thumbnail mt-2" width="150">
            @endif
        </div>

        <!-- ღილაკები -->
        <button type="submit" class="btn btn-success">შენახვა</button>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">გაუქმება</a>
    </form>
</div>
@endsection
