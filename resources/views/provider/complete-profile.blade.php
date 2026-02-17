@extends('layouts.app')

@section('title', 'إكمال الملف الشخصي - مزود الخدمة')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user-plus"></i> إكمال الملف الشخصي - مزود الخدمة
                        </h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('info'))
                            <div class="alert alert-info">
                                {{ session('info') }}
                            </div>
                        @endif



                        <form method="POST" action="{{ route('provider.profile.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- المعلومات الأساسية -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-info-circle"></i> المعلومات الأساسية
                                    </h5>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bio" class="form-label">نبذة عنك <span
                                                class="text-danger">*</span></label>
                                        <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="4"
                                            placeholder="اكتب نبذة مختصرة عن خبراتك ومهاراتك...">{{ old('bio', $profile ? $profile->bio : '') }}</textarea>
                                        @error('bio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف <span
                                                class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                            placeholder="أدخل رقم الهاتف" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">رقم الهاتف مطلوب لإكمال الملف الشخصي</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label">العنوان <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="address" id="address"
                                            class="form-control @error('address') is-invalid @enderror"
                                            value="{{ old('address', $profile ? $profile->address : '') }}"
                                            placeholder="عنوانك الكامل">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">الصورة الشخصية</label>
                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            id="image" name="image" accept="image/*">
                                        <small class="form-text text-muted">الأبعاد المفضلة: 300x300 بكسل. الأنواع المدعومة:
                                            JPG, PNG, GIF</small>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        @if (Auth::user()->image)
                                            <div class="mt-2">
                                                <small class="text-muted">الصورة الحالية:</small>
                                                <div class="mt-1">
                                                    <img src="{{ asset('storage/' . Auth::user()->image) }}"
                                                        alt="الصورة الحالية" class="rounded"
                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- اختيار الأقسام -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-folder"></i> الأقسام التي تعمل فيها
                                        <small class="text-muted">(حد أقصى {{ $maxCategories }} أقسام)</small>
                                    </h5>

                                    @error('categories')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    <div class="row">
                                        @foreach ($categories as $category)
                                                    @php
                                                        $selectedCategories = $profile
                                                            ? $profile
                                                                ->activeCategories()
                                                                ->pluck('category_id')
                                                                ->toArray()
                                                            : [];
                                                $selectedSubCategories = $profile
                                                    ? $profile
                                                        ->activeCategories()
                                                        ->where('category_id', $category->id)
                                                        ->pluck('sub_category_id')
                                                        ->filter()
                                                        ->toArray()
                                                    : [];
                                                $subCategories = $category->subCategories()->where('status', true)->get();
                                                $hasSubCategories = $subCategories->count() > 0;
                                                    @endphp
                                            <div class="col-md-6 mb-4">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <div class="form-check mb-2">
                                                    <input class="form-check-input category-checkbox" type="checkbox"
                                                        name="categories[]" value="{{ $category->id }}"
                                                        id="category_{{ $category->id }}"
                                                                data-category-id="{{ $category->id }}"
                                                        {{ in_array($category->id, old('categories', $selectedCategories)) ? 'checked' : '' }}>
                                                            <label class="form-check-label fw-bold" for="category_{{ $category->id }}">
                                                        <i class="{{ $category->icon }} text-primary"></i>
                                                        {{ $category->name }}
                                                    </label>
                                                </div>

                                                        @if ($hasSubCategories)
                                                            <div class="sub-categories-container" id="sub_categories_{{ $category->id }}" 
                                                                 style="display: {{ in_array($category->id, old('categories', $selectedCategories)) ? 'block' : 'none' }}; margin-top: 10px; padding-right: 20px;">
                                                                <small class="text-muted d-block mb-2">الأقسام الفرعية:</small>
                                                                @foreach ($subCategories as $subCategory)
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input sub-category-checkbox" 
                                                                               type="checkbox"
                                                                               name="sub_categories[{{ $category->id }}][]" 
                                                                               value="{{ $subCategory->id }}"
                                                                               id="sub_category_{{ $subCategory->id }}"
                                                                               data-category-id="{{ $category->id }}"
                                                                               {{ in_array($subCategory->id, old("sub_categories.{$category->id}", $selectedSubCategories)) ? 'checked' : '' }}>
                                                                        <label class="form-check-label" for="sub_category_{{ $subCategory->id }}">
                                                                            {{ $subCategory->name_ar }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- اختيار المدن -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-map-marker-alt"></i> المدن التي تعمل فيها
                                        <small class="text-muted">(حد أقصى {{ $maxCities }} مدن)</small>
                                    </h5>

                                    @error('cities')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror

                                    <div class="row">
                                        @foreach ($cities as $city)
                                            <div class="col-md-4 mb-3">
                                                <div class="form-check">
                                                    @php
                                                        $selectedCities = $profile
                                                            ? $profile->activeCities()->pluck('city_id')->toArray()
                                                            : [];
                                                    @endphp
                                                    <input class="form-check-input city-checkbox" type="checkbox"
                                                        name="cities[]" value="{{ $city->id }}"
                                                        id="city_{{ $city->id }}"
                                                        {{ in_array($city->id, old('cities', $selectedCities)) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="city_{{ $city->id }}">
                                                        <i class="fas fa-map-marker-alt text-info"></i>
                                                        {{ $city->name_ar }}
                                                    </label>
                                                </div>


                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>



                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> حفظ الملف الشخصي
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // إظهار/إخفاء الأقسام الفرعية عند اختيار قسم رئيسي
            document.querySelectorAll('.category-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const categoryId = this.getAttribute('data-category-id');
                    const subCategoriesContainer = document.getElementById('sub_categories_' + categoryId);
                    
                    if (subCategoriesContainer) {
                        if (this.checked) {
                            subCategoriesContainer.style.display = 'block';
                        } else {
                            subCategoriesContainer.style.display = 'none';
                            // إلغاء تحديد جميع الأقسام الفرعية عند إلغاء تحديد القسم الرئيسي
                            subCategoriesContainer.querySelectorAll('.sub-category-checkbox').forEach(function(subCheckbox) {
                                subCheckbox.checked = false;
                            });
                        }
                    }
                });
            });

            // تحديد القسم الرئيسي تلقائياً عند تحديد قسم فرعي
            document.querySelectorAll('.sub-category-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const categoryId = this.getAttribute('data-category-id');
                    const categoryCheckbox = document.getElementById('category_' + categoryId);
                    
                    if (this.checked && categoryCheckbox && !categoryCheckbox.checked) {
                        categoryCheckbox.checked = true;
                        categoryCheckbox.dispatchEvent(new Event('change'));
                    }
                });
            });

            // التحقق من عدد الأقسام المحددة
            const maxCategories = {{ $maxCategories }};
            const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
            
            categoryCheckboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
                    
                    if (checkedCount >= maxCategories) {
                        categoryCheckboxes.forEach(function(cb) {
                            if (!cb.checked) {
                                cb.disabled = true;
                            }
                        });
                    } else {
                        categoryCheckboxes.forEach(function(cb) {
                            cb.disabled = false;
                        });
                    }
                });
            });

            // تشغيل التحقق عند تحميل الصفحة
            categoryCheckboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
        });
    </script>

@endsection
