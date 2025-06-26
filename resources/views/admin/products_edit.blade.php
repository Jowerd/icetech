@extends('admin.layout')
@section('title', 'პროდუქტის რედაქტირება • ICETECH')
@section('content')
    <style>
        /* Styles for the dynamic feature fields */
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            gap: 10px;
        }
        .feature-item .form-control {
            flex: 1;
        }
        .feature-item .btn-danger {
            flex-shrink: 0;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
    </style>

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

                {{-- New Section: Product Features --}}
                <div class="mb-4">
                    <label class="form-label d-block"><i class="bi bi-list-stars"></i> პროდუქტის მახასიათებლები</label>
                    <div id="features-container">
                        {{-- Dynamic fields will be added here via JS --}}
                    </div>
                    <button type="button" id="add-feature-btn" class="btn btn-info btn-sm mt-2">
                        <i class="bi bi-plus-lg"></i> მახასიათებლის დამატება
                    </button>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-currency-exchange"></i> ფასი</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-control" required>
                </div>

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
                    <input type="hidden" name="supplier_country" id="selectedCountry" value="{{ old('supplier_country', $product->supplier_country) }}">
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
                        <img id="currentImage" src="https://via.placeholder.com/150?text=No+Image" class="rounded shadow-sm" width="150" height="150" style="object-fit: cover; display: none;">
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
        content_css: false,
        content_style: `
            body { 
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"; 
                line-height: 1.6; 
                margin: 15px; 
            }
            p { 
                margin-top: 0; 
                margin-bottom: 0.5em; 
            }
            p:last-child {
                margin-bottom: 0;
            }
            h1, h2, h3, h4, h5, h6 { 
                margin-top: 1em; 
                margin-bottom: 0.5em; 
            }
            ul, ol {
                margin-top: 0.5em;
                margin-bottom: 0.5em;
                padding-left: 1.5em;
            }
            li {
                margin-bottom: 0.2em;
            }
        `
    });
</script>

{{-- Custom Scripts (including feature logic) --}}
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

        // Dynamic Features Logic
        const featuresContainer = document.getElementById('features-container');
        const addFeatureBtn = document.getElementById('add-feature-btn');
        let featureIndex = 0; // Initialize featureIndex to 0.

        // Function to create and add a new feature field to the DOM
        function addFeatureField(name = '', value = '') {
            // Create the main div for the feature item
            const featureItemDiv = document.createElement('div');
            featureItemDiv.classList.add('feature-item');
            featureItemDiv.dataset.index = featureIndex; // Store index for potential future use

            // Create input for feature name
            const nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.name = `features[${featureIndex}][name]`; // Unique name
            nameInput.classList.add('form-control', 'form-control-sm');
            nameInput.placeholder = 'მახასიათებლის სახელი (მაგ. ფერი)';
            nameInput.value = name;
            nameInput.required = true;

            // Create input for feature value
            const valueInput = document.createElement('input');
            valueInput.type = 'text';
            valueInput.name = `features[${featureIndex}][value]`; // Unique name
            valueInput.classList.add('form-control', 'form-control-sm');
            valueInput.placeholder = 'მნიშვნელობა (მაგ. წითელი)';
            valueInput.value = value;
            valueInput.required = true;

            // Create remove button
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add('btn', 'btn-danger', 'btn-sm', 'remove-feature-btn');
            removeButton.innerHTML = '<i class="bi bi-x"></i>';

            // Add event listener to the remove button
            removeButton.addEventListener('click', function() {
                featureItemDiv.remove(); // Remove the entire feature item div
            });

            // Append all elements to the feature item div
            featureItemDiv.appendChild(nameInput);
            featureItemDiv.appendChild(valueInput);
            featureItemDiv.appendChild(removeButton);

            // Append the new feature item div to the container
            featuresContainer.appendChild(featureItemDiv);

            // Increment featureIndex for the next field
            featureIndex++; 
        }

        // Load existing features if available
        const existingFeatures = @json($product->features);
        if (Array.isArray(existingFeatures) && existingFeatures.length > 0) {
            existingFeatures.forEach(feature => {
                addFeatureField(feature.name, feature.value); 
            });
            // featureIndex will automatically be correct after this loop
        } else {
            // If no features initially, add one empty field
            addFeatureField(); 
        }

        // Event listener for "Add Feature" button
        addFeatureBtn.addEventListener('click', function() {
            addFeatureField(); // Add a new empty field
        });
    });

    function previewImage(event) {
        var output = document.getElementById('currentImage');
        if (output) {
            output.src = URL.createObjectURL(event.target.files[0]);
            output.style.display = 'block'; 
        }
    }
</script>
@endsection