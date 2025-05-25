@extends('admin.layout')
@section('title', 'ბლოგპოსტები')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>ბლოგპოსტების სია</h2>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary">ახალი პოსტის დამატება</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>სათაური</th>
                <th>თარიღი</th>
                <th style="width: 200px;">მოქმედება</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td>{{ $post->title }}</td>
                <td>{{ $post->created_at->format('d.m.Y') }}</td>
                <td>
                    <a href="{{ route('admin.blog.edit', $post->slug) }}" class="btn btn-warning btn-sm">რედაქტირება</a>

                    <form action="{{ route('admin.blog.destroy', $post->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('დარწმუნებული ხარ რომ გინდა წაშლა?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">წაშლა</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $posts->links() }}
</div>
@endsection
