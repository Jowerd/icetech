@extends('admin.layout')
@section('title', 'კატეგორიები • ICETECH')

@section('content')
<div class="container-fluid px-0 px-md-2">
    
    {{-- Header --}}
    <div class="mb-4">
        <h4 class="fw-bold text-dark border-start border-primary border-4 ps-3">კატეგორიების მართვა</h4>
    </div>

    {{-- Main Action Card: დამატების ფორმა (უფრო მკაფიო და გამოკვეთილი) --}}
    <div class="card border-0 shadow-sm rounded-3 mb-5">
        <div class="card-header bg-white border-bottom p-3">
            <span class="fw-bold text-uppercase small text-muted"><i class="bi bi-plus-circle me-2"></i>ახალი კატეგორიის რეგისტრაცია</span>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label small fw-bold text-dark">კატეგორიის დასახელება</label>
                        <input type="text" name="name" class="form-control border-2 shadow-none py-2" placeholder="მაგ: მაცივრები" required>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label small fw-bold text-dark">საკვანძო სიტყვები (SEO)</label>
                        <input type="text" name="keywords" class="form-control border-2 shadow-none py-2" placeholder="დახლები, თაროები, მაცივრები">
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <label class="form-label small fw-bold text-dark">კატეგორიის ფოტო</label>
                        <input type="file" name="image" class="form-control border-2 shadow-none py-2">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold text-dark">შიდა აღწერა (სისტემისთვის)</label>
                        <textarea name="description" class="form-control border-2 shadow-none" rows="2" placeholder="ჩაწერეთ მოკლე ინფორმაცია..."></textarea>
                    </div>
                    <div class="col-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary px-5 fw-bold py-2 rounded-2 shadow-sm">
                            <i class="bi bi-check2-circle me-2"></i>კატეგორიის დამატება
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- List Section: არსებული კატეგორიები (მაქსიმალურად კომპაქტური) --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold text-secondary mb-0">აქტიური კატეგორიები</h6>
        <span class="badge bg-light text-dark border fw-normal">{{ $categories->count() }} ჩანაწერი</span>
    </div>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light border-bottom">
                    <tr>
                        <th class="ps-4 py-3 text-muted small fw-bold text-uppercase" style="width: 80px;">ფოტო</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase">დასახელება</th>
                        <th class="py-3 text-muted small fw-bold text-uppercase">SEO Tags</th>
                        <th class="py-3 text-end pe-4 text-muted small fw-bold text-uppercase">მართვა</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-bottom">
                            <td class="ps-4 py-2">
                                <div class="category-img-preview border shadow-sm">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="">
                                    @else
                                        <i class="bi bi-folder text-muted opacity-50"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2">
                                <span class="fw-bold text-dark d-block">{{ $category->name }}</span>
                                <span class="text-muted x-small">ID: #{{ $category->id }}</span>
                            </td>
                            <td class="py-2">
                                <code class="small text-primary bg-light px-2 py-1 rounded">{{ $category->keywords ?: '---' }}</code>
                            </td>
                            <td class="text-end pe-4 py-2">
                                <div class="btn-group shadow-sm border rounded">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-white btn-sm px-3" title="რედაქტირება">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline border-start">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-white btn-sm px-3" onclick="return confirm('წავშალოთ?')">
                                            <i class="bi bi-trash3 text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted small">კატეგორიები არ არის დამატებული</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* სრული სტატიკა, არანაირი ანიმაცია */
    * { transition: none !important; }
    
    body { background-color: #f8f9fc; }

    /* Input Styling */
    .form-control {
        background-color: #ffffff;
        border-color: #e3e6f0;
        font-size: 0.9rem;
        border-radius: 8px;
    }
    .form-control:focus {
        border-color: #4e73df;
        background-color: #fff;
    }

    /* Table & Previews */
    .category-img-preview {
        width: 45px;
        height: 45px;
        background: #fff;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .category-img-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .x-small { font-size: 0.7rem; }
    
    .btn-white { background: #fff; border: none; }
    .btn-white:hover { background: #f8f9fc; }

    .table thead th {
        font-size: 11px;
        letter-spacing: 0.8px;
        color: #858796;
    }
</style>
@endsection