@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي - مزود الخدمة')

@section('content')
<div class="edit-profile-hero">
    <div class="container">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('provider.profile') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">
                <i class="fas fa-arrow-right me-1"></i>رجوع
            </a>
            <div>
                <h3 class="text-white mb-0 fw-bold">تعديل الملف الشخصي</h3>
                <p class="text-white-50 mb-0 small">قم بتحديث معلوماتك الشخصية والأقسام والمدن</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-4" style="margin-top: -2rem; position: relative; z-index: 2;">
    <form method="POST" action="{{ route('provider.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- Right Side: Main Form --}}
            <div class="col-lg-8">
                {{-- المعلومات الأساسية --}}
                <div class="e-card mb-4">
                    <div class="e-card-header">
                        <i class="fas fa-user-circle"></i> المعلومات الأساسية
                    </div>
                    <div class="e-card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="bio" class="form-label fw-semibold">نبذة عنك <span class="text-danger">*</span></label>
                                <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="4"
                                    placeholder="اكتب نبذة مختصرة عن خبراتك ومهاراتك...">{{ old('bio', $profile ? $profile->bio : '') }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">رقم الهاتف</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->phone }}" readonly style="background: var(--e-gray-50);">
                                <small class="text-muted">رقم الهاتف المسجل في حسابك</small>
                            </div>

                            <div class="col-md-6">
                                <label for="address" class="form-label fw-semibold">العنوان <span class="text-danger">*</span></label>
                                <input type="text" name="address" id="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    value="{{ old('address', $profile ? $profile->address : '') }}"
                                    placeholder="عنوانك الكامل">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- الأقسام --}}
                <div class="e-card mb-4">
                    <div class="e-card-header">
                        <i class="fas fa-folder-open"></i> الأقسام التي تعمل فيها
                        <span class="e-badge e-badge-primary ms-auto">حد أقصى {{ $maxCategories }}</span>
                    </div>
                    <div class="e-card-body">
                        @error('categories')
                            <div class="alert alert-danger alert-sm mb-3">{{ $message }}</div>
                        @enderror

                        <div class="row g-3">
                            @foreach ($categories as $category)
                                @php
                                    $selectedCategories = $profile ? $profile->activeCategories()->pluck('category_id')->toArray() : [];
                                    $selectedSubCategories = $profile ? $profile->activeCategories()->where('category_id', $category->id)->pluck('sub_category_id')->filter()->toArray() : [];
                                    $subCategories = $category->subCategories()->where('status', true)->get();
                                    $hasSubCategories = $subCategories->count() > 0;
                                    $isChecked = in_array($category->id, old('categories', $selectedCategories));
                                @endphp
                                <div class="col-md-6">
                                    <div class="category-select-card {{ $isChecked ? 'is-selected' : '' }}">
                                        <div class="form-check">
                                            <input class="form-check-input category-checkbox" type="checkbox"
                                                name="categories[]" value="{{ $category->id }}"
                                                id="category_{{ $category->id }}"
                                                data-category-id="{{ $category->id }}"
                                                {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category_{{ $category->id }}">
                                                <i class="{{ $category->icon ?? 'fas fa-folder' }} category-select-icon"></i>
                                                <strong>{{ $category->name }}</strong>
                                            </label>
                                        </div>

                                        @if ($hasSubCategories)
                                            <div class="sub-categories-area" id="sub_categories_{{ $category->id }}"
                                                style="{{ $isChecked ? '' : 'display:none;' }}">
                                                <small class="text-muted d-block mb-2">الأقسام الفرعية:</small>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($subCategories as $subCategory)
                                                        <label class="sub-category-chip {{ in_array($subCategory->id, old("sub_categories.{$category->id}", $selectedSubCategories)) ? 'is-selected' : '' }}">
                                                            <input class="d-none sub-category-checkbox" type="checkbox"
                                                                name="sub_categories[{{ $category->id }}][]"
                                                                value="{{ $subCategory->id }}"
                                                                data-category-id="{{ $category->id }}"
                                                                {{ in_array($subCategory->id, old("sub_categories.{$category->id}", $selectedSubCategories)) ? 'checked' : '' }}>
                                                            <span>{{ $subCategory->name_ar }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- المدن --}}
                <div class="e-card mb-4">
                    <div class="e-card-header">
                        <i class="fas fa-map-marker-alt"></i> المدن التي تعمل فيها
                        <span class="e-badge e-badge-primary ms-auto">حد أقصى {{ $maxCities }}</span>
                    </div>
                    <div class="e-card-body">
                        @error('cities')
                            <div class="alert alert-danger alert-sm mb-3">{{ $message }}</div>
                        @enderror

                        @php
                            $selectedCities = $profile ? $profile->activeCities()->pluck('city_id')->toArray() : [];
                        @endphp
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($cities as $city)
                                <label class="city-chip {{ in_array($city->id, old('cities', $selectedCities)) ? 'is-selected' : '' }}">
                                    <input class="d-none" type="checkbox" name="cities[]" value="{{ $city->id }}"
                                        {{ in_array($city->id, old('cities', $selectedCities)) ? 'checked' : '' }}>
                                    <i class="fas fa-map-pin"></i>
                                    <span>{{ $city->name_ar }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Left Side: Avatar + Actions --}}
            <div class="col-lg-4">
                {{-- الصورة الشخصية --}}
                <div class="e-card mb-4">
                    <div class="e-card-header">
                        <i class="fas fa-camera"></i> الصورة الشخصية
                    </div>
                    <div class="e-card-body text-center">
                        <div class="avatar-upload-area mb-3">
                            @if (Auth::user()->image)
                                <img src="{{ Auth::user()->image_url ?? asset('images/default-avatar.png') }}" alt="الصورة الحالية"
                                    class="avatar-preview" id="avatarPreview">
                            @else
                                <div class="avatar-preview-placeholder" id="avatarPreview">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                            @endif
                        </div>

                        <label for="image" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                            <i class="fas fa-upload me-1"></i>{{ Auth::user()->image ? 'تغيير الصورة' : 'رفع صورة' }}
                        </label>
                        <input type="file" class="d-none @error('image') is-invalid @enderror"
                            id="image" name="image" accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted d-block mt-2">JPG, PNG, GIF - حد أقصى 2MB</small>
                        @error('image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- أزرار الحفظ --}}
                <div class="e-card mb-4 sticky-actions">
                    <div class="e-card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2 rounded-pill">
                            <i class="fas fa-save me-1"></i>حفظ التعديلات
                        </button>
                        <a href="{{ route('provider.profile') }}" class="btn btn-outline-secondary w-100 rounded-pill">
                            <i class="fas fa-times me-1"></i>إلغاء
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .edit-profile-hero {
        background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
        padding: 2rem 0 4rem;
    }

    /* Cards (same as provider profile) */
    .e-card {
        background: var(--e-white, #fff);
        border-radius: 16px;
        border: 1px solid var(--e-gray-200, #e2e8f0);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .e-card-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.25rem;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--e-primary, #2f5c69);
        border-bottom: 1px solid var(--e-gray-100, #f1f5f9);
        background: var(--e-gray-50, #f8fafc);
    }

    .e-card-body { padding: 1.25rem; }

    .e-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
    }

    .e-badge-primary { background: rgba(47,92,105,0.1); color: var(--e-primary, #2f5c69); }

    /* Avatar Upload */
    .avatar-upload-area { position: relative; display: inline-block; }

    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--e-gray-200, #e2e8f0);
        transition: 0.3s;
    }

    .avatar-preview-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: var(--e-gray-100, #f1f5f9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--e-gray-400, #94a3b8);
        border: 4px dashed var(--e-gray-300, #cbd5e1);
    }

    /* Category Select Card */
    .category-select-card {
        padding: 1rem;
        border: 2px solid var(--e-gray-200, #e2e8f0);
        border-radius: 12px;
        transition: 0.25s;
        height: 100%;
        background: var(--e-white, #fff);
    }

    .category-select-card:hover { border-color: var(--e-primary, #2f5c69); }

    .category-select-card.is-selected {
        border-color: var(--e-primary, #2f5c69);
        background: rgba(47, 92, 105, 0.03);
    }

    .category-select-icon {
        color: var(--e-primary, #2f5c69);
        margin-left: 0.25rem;
    }

    .sub-categories-area {
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid var(--e-gray-200, #e2e8f0);
    }

    /* Sub-category Chips */
    .sub-category-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.3rem 0.75rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        border: 1.5px solid var(--e-gray-300, #cbd5e1);
        background: var(--e-white, #fff);
        color: var(--e-gray-600, #475569);
        transition: 0.2s;
        user-select: none;
    }

    .sub-category-chip:hover {
        border-color: var(--e-primary, #2f5c69);
        color: var(--e-primary, #2f5c69);
    }

    .sub-category-chip.is-selected {
        background: var(--e-primary, #2f5c69);
        color: #fff;
        border-color: var(--e-primary, #2f5c69);
    }

    /* City Chips */
    .city-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 1rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid var(--e-gray-200, #e2e8f0);
        background: var(--e-white, #fff);
        color: var(--e-gray-600, #475569);
        transition: 0.2s;
        user-select: none;
    }

    .city-chip:hover {
        border-color: var(--e-primary, #2f5c69);
        color: var(--e-primary, #2f5c69);
    }

    .city-chip.is-selected {
        background: linear-gradient(135deg, var(--e-primary, #2f5c69), var(--e-primary-light, #3d7a8a));
        color: #fff;
        border-color: transparent;
        box-shadow: 0 2px 8px rgba(47,92,105,0.25);
    }

    .city-chip.is-selected i { color: rgba(255,255,255,0.7); }

    /* Sticky actions on desktop */
    @media (min-width: 992px) {
        .sticky-actions { position: sticky; top: 80px; }
    }

    /* Form Controls */
    .form-control:focus, .form-select:focus {
        border-color: var(--e-primary, #2f5c69);
        box-shadow: 0 0 0 3px rgba(47,92,105,0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .edit-profile-hero { padding: 1.5rem 0 3rem; }
        .edit-profile-hero h3 { font-size: 1.2rem; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var maxCategories = {{ $maxCategories }};
        var categoryCheckboxes = document.querySelectorAll('.category-checkbox');

        // Toggle sub-categories & card style
        categoryCheckboxes.forEach(function(cb) {
            cb.addEventListener('change', function() {
                var card = this.closest('.category-select-card');
                var subArea = document.getElementById('sub_categories_' + this.dataset.categoryId);

                if (this.checked) {
                    card.classList.add('is-selected');
                    if (subArea) subArea.style.display = 'block';
                } else {
                    card.classList.remove('is-selected');
                    if (subArea) {
                        subArea.style.display = 'none';
                        subArea.querySelectorAll('.sub-category-checkbox').forEach(function(s) {
                            s.checked = false;
                            s.closest('.sub-category-chip').classList.remove('is-selected');
                        });
                    }
                }

                // Limit max categories
                var checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
                categoryCheckboxes.forEach(function(c) { c.disabled = !c.checked && checkedCount >= maxCategories; });
            });
        });

        // Sub-category chip toggle
        document.querySelectorAll('.sub-category-checkbox').forEach(function(cb) {
            cb.addEventListener('change', function() {
                var chip = this.closest('.sub-category-chip');
                chip.classList.toggle('is-selected', this.checked);

                // Auto-check parent
                if (this.checked) {
                    var parent = document.getElementById('category_' + this.dataset.categoryId);
                    if (parent && !parent.checked) { parent.checked = true; parent.dispatchEvent(new Event('change')); }
                }
            });
        });

        // City chip toggle
        document.querySelectorAll('.city-chip input').forEach(function(cb) {
            cb.addEventListener('change', function() {
                this.closest('.city-chip').classList.toggle('is-selected', this.checked);
            });
        });

        // Init state on load
        categoryCheckboxes.forEach(function(cb) { if (cb.checked) cb.dispatchEvent(new Event('change')); });
    });

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('avatarPreview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'avatar-preview';
                    img.id = 'avatarPreview';
                    preview.parentNode.replaceChild(img, preview);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
