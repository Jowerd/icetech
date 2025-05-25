<!-- resources/views/admin/reviews/index.blade.php -->
@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">მომხმარებელთა შეფასებები</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ავტორი</th>
                                    <th>შეფასება</th>
                                    <th>რეიტინგი</th>
                                    <th>სტატუსი</th>
                                    <th>თარიღი</th>
                                    <th>მოქმედებები</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reviews as $review)
                                    <tr>
                                        <td>{{ $review->id }}</td>
                                        <td>
                                            <strong>{{ $review->author_name }}</strong>
                                            @if ($review->author_email)
                                                <br><small>{{ $review->author_email }}</small>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($review->content, 100) }}</td>
                                        <td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </td>
                                        <td>
                                            @if ($review->is_approved)
                                                <span class="badge bg-success">დამტკიცებული</span>
                                            @else
                                                <span class="badge bg-warning">დასამტკიცებელი</span>
                                            @endif
                                        </td>
                                        <td>{{ $review->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                @if (!$review->is_approved)
                                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="bi bi-check-lg"></i> დამტკიცება
                                                        </button>
                                                    </form>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $review->id }}">
                                                    <i class="bi bi-eye"></i> ნახვა
                                                </button>
                                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('ნამდვილად გსურთ ამ შეფასების წაშლა?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i> წაშლა
                                                    </button>
                                                </form>
                                            </div>
                                            
                                            <!-- Modal for viewing full review -->
                                            <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $review->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="reviewModalLabel{{ $review->id }}">შეფასების დეტალები</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>ავტორი:</strong> {{ $review->author_name }}</p>
                                                            <p><strong>ელ. ფოსტა:</strong> {{ $review->author_email ?: 'არ არის მითითებული' }}</p>
                                                            <p><strong>რეიტინგი:</strong> 
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $review->rating)
                                                                        <i class="bi bi-star-fill text-warning"></i>
                                                                    @else
                                                                        <i class="bi bi-star text-warning"></i>
                                                                    @endif
                                                                @endfor
                                                            </p>
                                                            <p><strong>თარიღი:</strong> {{ $review->created_at->format('d.m.Y H:i') }}</p>
                                                            <hr>
                                                            <p><strong>შეფასება:</strong></p>
                                                            <div class="review-full-content">
                                                                {{ $review->content }}
                                                            </div>
                                                            @if ($review->image)
                                                                <hr>
                                                                <p><strong>სურათი:</strong></p>
                                                                <img src="{{ asset('storage/' . $review->image) }}" alt="Review Image" class="img-fluid">
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">დახურვა</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">შეფასებები არ არის</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection