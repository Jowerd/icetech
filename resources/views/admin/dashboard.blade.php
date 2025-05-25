@extends('admin.layout')
@section('title', 'დეშბორდი • ICETECH')
@section('content')
    <h1 class="mb-4">👋 მოგესალმებით, <strong>{{ auth()->guard('admin')->user()->name }}</strong>!</h1>

    <div class="row">
        <!-- კატეგორიების რაოდენობა -->
        <div class="col-md-6 col-lg-4">
            <div class="card text-bg-dark shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box me-3">
                        <i class="bi bi-folder-fill fs-1 text-primary"></i>
                    </div>
                    <div>
                        <h5 class="card-title">კატეგორიები</h5>
                        <p class="fs-3 mb-0">{{ \App\Models\Category::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- პროდუქტების რაოდენობა -->
        <div class="col-md-6 col-lg-4">
            <div class="card text-bg-light shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box me-3">
                        <i class="bi bi-box-seam fs-1 text-success"></i>
                    </div>
                    <div>
                        <h5 class="card-title">პროდუქტები</h5>
                        <p class="fs-3 mb-0">{{ \App\Models\Product::count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- სტილი უკეთესი ვიზუალისთვის -->
    <style>
        .icon-box {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }
    </style>
@endsection
