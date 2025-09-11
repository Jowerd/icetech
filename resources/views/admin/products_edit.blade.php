@extends('admin.layout')
@section('title', 'პროდუქტის რედაქტირება • ICETECH')
@section('content')
    <h1 class="mb-4"><i class="bi bi-pencil-square"></i> პროდუქტის რედაქტირება</h1>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title"><i class="bi bi-arrow-repeat"></i> რედაქტირება: {{ $product->name }}</h5>
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-shapes"></i> ტიპი</label>
                    <input type="text" name="sub_type" value="{{ old('sub_type', $product->sub_type) }}" class="form-control" placeholder="მაგ. Showcase, Upright, Countertop...">
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-tag-fill"></i> პროდუქტის სახელი</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-file-text"></i> აღწერა</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- ✅ 변경: 텍스트 기능을 위한 텍스트 영역 추가 --}}
                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-list-stars"></i> მახასიათებლები (ტექსტი)</label>
                    <textarea name="features_text" class="form-control" rows="6" placeholder="თითოეული მახასიათებელი ჩაწერეთ ახალ ხაზზე, მაგ:&#10;ზომა: 200x150x100&#10;ფერი: უჟანგავი მეტალი&#10;სიმძლავრე: 2kW">{{ old('features_text', $product->features_text) }}</textarea>
                </div>

                {{-- ❌ 이전 동적 기능 블록 제거됨 --}}

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-currency-exchange"></i> ფასი</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-flag-fill"></i> მწარმოებელი ქვეყანა</label>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle w-100 text-start" type="button" id="countryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                             @php
                                $countriesList = ['DE' => 'გერმანია', 'IT' => 'იტალია', 'TR' => 'თურქეთი', 'CN' => 'ჩინეთი', 'AT' => 'ავსტრია'];
                                $countryCode = old('supplier_country', $product->supplier_country);
                                $countryName = $countriesList[$countryCode] ?? 'აირჩიე ქვეყანა';
                            @endphp
                            <img id="selectedFlag" src="https://flagcdn.com/w40/{{ strtolower($countryCode) }}.png" width="20" class="me-1">
                            {{ $countryName }}
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="countryDropdown">
                            @foreach ($countriesList as $code => $country)
                                <li>
                                    <a class="dropdown-item d-flex align-items-center country-option" href="#" data-value="{{ $code }}">
                                        <img src="https://flagcdn.com/w40/{{ strtolower($code) }}.png" width="20" class="me-2">
                                        {{ $country }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <input type="hidden" name="supplier_country" id="selectedCountry" value="{{ $countryCode }}">
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-info-circle-fill"></i> პროდუქტის მდგომარეობა</label>
                    <select name="condition" class="form-select" required>
                        <option value="new" {{ old('condition', $product->condition) == 'new' ? 'selected' : '' }}>ახალი</option>
                        <option value="like_new" {{ old('condition', $product->condition) == 'like_new' ? 'selected' : '' }}>ახალივით</option>
                        <option value="used" {{ old('condition', $product->condition) == 'used' ? 'selected' : '' }}>მეორადი</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-image-fill"></i> პროდუქტის სურათი (აირჩიეთ ახალი, თუ გსურთ შეცვლა)</label>
                    <input type="file" name="image" class="form-control" onchange="previewImage(event)">
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-camera-video-fill"></i> პროდუქტის ვიდეოს ლინკი</label>
                    <input type="url" name="video_link" value="{{ old('video_link', $product->video_link) }}" class="form-control" placeholder="შეიყვანეთ ვიდეოს URL">
                </div>

                @if($product->image)
                    <div class="mb-3 text-center">
                        <p class="mb-1"><i class="bi bi-eye"></i> მიმდინარე ფოტო:</p>
                        <img id="currentImage" src="{{ asset('storage/' . $product->image) }}" class="rounded shadow-sm" width="150" height="150" style="object-fit: cover;">
                    </div>
                @else
                    <div class="mb-3 text-center">
                        <p class="mb-1"><i class="bi bi-eye-slash"></i> ფოტო არ არის ატვირთული</p>
                        <img id="currentImage" src="" class="rounded shadow-sm" width="150" height="150" style="object-fit: cover; display: none;">
                    </div>
                @endif

                <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-circle"></i> განახლება</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100 mt-2"><i class="bi bi-arrow-left"></i> დაბრუნება</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- TinyMCE Library --}}
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#description',
        height: 300,
        plugins: 'lists link image preview code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent | link image | preview | code',
        promotion: false,
    });
</script>

{{-- Custom Scripts --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Country Dropdown Logic
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

        // ❌ 동적 기능 JavaScript 로직 제거됨
    });

    function previewImage(event) {
        var output = document.getElementById('currentImage');
        if (output) {
            output.src = URL.createObjectURL(event.target.files[0]);
            output.style.display = 'block'; 
            output.onload = () => URL.revokeObjectURL(output.src) // free memory
        }
    }
</script>
@endsection