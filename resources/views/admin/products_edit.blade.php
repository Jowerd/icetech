@extends('admin.layout')
@section('title', 'პროდუქტის დერაქტირება • ICETECH')
@section('content')
    <h1 class="mb-4"><i class="bi bi-pencil-square"></i> პროდუქტის რედაქტირება</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-arrow-repeat"></i> რედაქტირება: {{ $product->name }}</h5>
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- კატეგორიის არჩევა -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-folder-fill"></i> კატეგორიის არჩევა</label>
                    <select name="category_id" class="form-select" required>
                        @foreach (\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" @if($category->id == $product->category_id) selected @endif>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- პროდუქტის ტიპი -->
<div class="mb-3">
    <label class="form-label"><i class="bi bi-shapes"></i> ტიპი</label>
    <input type="text" name="sub_type" value="{{ $product->sub_type }}" class="form-control" placeholder="მაგ. Showcase, Upright, Countertop...">
</div>

                <!-- პროდუქტის სახელი -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-tag-fill"></i> პროდუქტის სახელი</label>
                    <input type="text" name="name" value="{{ $product->name }}" class="form-control" required>
                </div>

                <!-- აღწერა -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-file-text"></i> აღწერა</label>
                    <textarea name="description" id="description" class="form-control">{{ $product->description }}</textarea>
                </div>

                <!-- ფასი -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-currency-exchange"></i> ფასი (₾)</label>
                    <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="form-control" required>
                </div>

                <!-- მომწოდებელი ქვეყანა დროშებით -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-flag-fill"></i> მწარმოებელი ქვეყანა</label>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="countryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img id="selectedFlag" src="https://flagcdn.com/w40/{{ strtolower($product->supplier_country) }}.png" width="20" class="me-1">
                            {{ config('countries.list')[$product->supplier_country] ?? 'აირჩიე ქვეყანა' }}
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="countryDropdown">
                            @php
                                $countries = [
                                    'DE' => 'გერმანია',
                                    'IT' => 'იტალია',
                                    'TR' => 'თურქეთი',
                                    'CN' => 'ჩინეთი',
                                    'AT' => 'ავსტრია'
                                ];
                            @endphp
                            @foreach ($countries as $code => $country)
                                <li>
                                    <a class="dropdown-item d-flex align-items-center country-option" href="#" data-value="{{ $code }}">
                                        <img src="https://flagcdn.com/w40/{{ strtolower($code) }}.png" width="20" class="me-2">
                                        {{ $country }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <input type="hidden" name="supplier_country" id="selectedCountry" value="{{ $product->supplier_country }}">
                </div>

                <!-- პროდუქტის მდგომარეობა -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-info-circle-fill"></i> პროდუქტის მდგომარეობა</label>
                    <select name="condition" class="form-select" required>
                        <option value="new" {{ $product->condition == 'new' ? 'selected' : '' }}>ახალი</option>
                        <option value="like_new" {{ $product->condition == 'like_new' ? 'selected' : '' }}>ახალივით</option>
                        <option value="used" {{ $product->condition == 'used' ? 'selected' : '' }}>მეორადი</option>
                    </select>
                </div>

                <!-- პროდუქტის სურათი -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-image-fill"></i> პროდუქტის სურათი</label>
                    <input type="file" name="image" class="form-control" onchange="previewImage(event)">
                </div>

                <!-- პროდუქტის ვიდეოს ლინკი -->
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-camera-video-fill"></i> პროდუქტის ვიდეოს ლინკი</label>
                    <input type="url" name="video_link" value="{{ $product->video_link }}" class="form-control" placeholder="შეიყვანეთ ვიდეოს URL">
                </div>

                <!-- მიმდინარე სურათის წინასწარი გადახედვა -->
                @if($product->image)
                    <div class="mb-3 text-center">
                        <p class="mb-1"><i class="bi bi-eye"></i> მიმდინარე ფოტო:</p>
                        <img id="currentImage" src="{{ asset('storage/' . $product->image) }}" class="rounded shadow-sm" width="150" height="150" style="object-fit: cover;">
                    </div>
                @endif

                <!-- ღილაკები -->
                <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-circle"></i> განახლება</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100 mt-2"><i class="bi bi-arrow-left"></i> დაბრუნება</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let countryDropdown = document.getElementById("countryDropdown");
        let selectedCountryInput = document.getElementById("selectedCountry");
        let countryOptions = document.querySelectorAll(".country-option");

        countryOptions.forEach(option => {
            option.addEventListener("click", function (event) {
                event.preventDefault();
                let selectedCountryCode = this.getAttribute("data-value");
                let selectedCountryName = this.innerText.trim();
                let selectedFlagUrl = this.querySelector("img").src;

                countryDropdown.innerHTML = `<img src="${selectedFlagUrl}" width="20" class="me-1"> ${selectedCountryName}`;
                selectedCountryInput.value = selectedCountryCode;
            });
        });
    });

    function previewImage(event) {
        var output = document.getElementById('currentImage');
        if (output) {
            output.src = URL.createObjectURL(event.target.files[0]);
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#description',
        height: 300,
        plugins: 'lists link image preview',
        toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent',
        promotion: false
    });
</script>
@endsection
