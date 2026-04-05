@extends('layouts.app')

@section('title', 'إكمال الملف الشخصي - مزود الخدمة')

@section('content')
{{-- Steps Hero --}}
<div class="complete-hero">
    <div class="container">
        <div class="text-center">
            <div class="complete-hero-icon"><i class="fas fa-user-check"></i></div>
            <h2 class="text-white fw-bold mb-1">إكمال الملف الشخصي</h2>
            <p class="text-white-50 mb-0">أكمل بياناتك عشان تقدر تبدأ تستقبل طلبات</p>
        </div>
        {{-- Progress Steps --}}
        <div class="steps-bar">
            <div class="step-item active"><span class="step-num">1</span><span class="step-label">البيانات</span></div>
            <div class="step-line"></div>
            <div class="step-item"><span class="step-num">2</span><span class="step-label">الأقسام</span></div>
            <div class="step-line"></div>
            <div class="step-item"><span class="step-num">3</span><span class="step-label">المدن</span></div>
        </div>
    </div>
</div>

<div class="container" style="margin-top: -2rem; position: relative; z-index: 2;">
    <form method="POST" action="{{ route('provider.profile.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                {{-- Step 1: البيانات الأساسية --}}
                <div class="e-card mb-4">
                    <div class="e-card-header"><i class="fas fa-user-circle"></i> المعلومات الأساسية</div>
                    <div class="e-card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="bio" class="form-label fw-semibold">نبذة عنك <span class="text-danger">*</span></label>
                                <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="4"
                                    placeholder="اكتب نبذة مختصرة عن خبراتك ومهاراتك...">{{ old('bio', $profile ? $profile->bio : '') }}</textarea>
                                @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold">رقم الهاتف <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                    value="{{ old('phone', Auth::user()->phone) }}" placeholder="05xxxxxxxx" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label fw-semibold">العنوان <span class="text-danger">*</span></label>
                                <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                                    value="{{ old('address', $profile ? $profile->address : '') }}" placeholder="عنوانك الكامل">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 2: الأقسام --}}
                <div class="e-card mb-4">
                    <div class="e-card-header">
                        <i class="fas fa-folder-open"></i> الأقسام التي تعمل فيها
                        <span class="e-badge e-badge-primary ms-auto">حد أقصى {{ $maxCategories }}</span>
                    </div>
                    <div class="e-card-body">
                        @error('categories')<div class="alert alert-danger mb-3">{{ $message }}</div>@enderror
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
                                                id="category_{{ $category->id }}" data-category-id="{{ $category->id }}"
                                                {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category_{{ $category->id }}">
                                                <i class="{{ $category->icon ?? 'fas fa-folder' }} category-select-icon"></i>
                                                <strong>{{ $category->name }}</strong>
                                            </label>
                                        </div>
                                        @if ($hasSubCategories)
                                            <div class="sub-categories-area" id="sub_categories_{{ $category->id }}" style="{{ $isChecked ? '' : 'display:none;' }}">
                                                <small class="text-muted d-block mb-2">الأقسام الفرعية:</small>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($subCategories as $subCategory)
                                                        <label class="sub-category-chip {{ in_array($subCategory->id, old("sub_categories.{$category->id}", $selectedSubCategories)) ? 'is-selected' : '' }}">
                                                            <input class="d-none sub-category-checkbox" type="checkbox"
                                                                name="sub_categories[{{ $category->id }}][]" value="{{ $subCategory->id }}"
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

                {{-- Step 3: المدن --}}
                <div class="e-card mb-4">
                    <div class="e-card-header">
                        <i class="fas fa-map-marker-alt"></i> المدن التي تعمل فيها
                        <span class="e-badge e-badge-primary ms-auto">حد أقصى {{ $maxCities }}</span>
                    </div>
                    <div class="e-card-body">
                        @error('cities')<div class="alert alert-danger mb-3">{{ $message }}</div>@enderror
                        @php $selectedCities = $profile ? $profile->activeCities()->pluck('city_id')->toArray() : []; @endphp
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

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- الصورة --}}
                <div class="e-card mb-4">
                    <div class="e-card-header"><i class="fas fa-camera"></i> الصورة الشخصية</div>
                    <div class="e-card-body text-center">
                        <div class="mb-3">
                            @if (Auth::user()->image)
                                <img src="{{ asset('storage/' . Auth::user()->image) }}" class="avatar-preview" id="avatarPreview">
                            @else
                                <div class="avatar-preview-placeholder" id="avatarPreview"><i class="fas fa-user fa-3x"></i></div>
                            @endif
                        </div>
                        <label for="image" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                            <i class="fas fa-upload me-1"></i>{{ Auth::user()->image ? 'تغيير' : 'رفع صورة' }}
                        </label>
                        <input type="file" class="d-none" id="image" name="image" accept="image/*" onchange="previewImg(this)">
                        <small class="text-muted d-block mt-2">JPG, PNG - حد أقصى 2MB</small>
                        @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                {{-- Tips --}}
                <div class="e-card mb-4">
                    <div class="e-card-header"><i class="fas fa-lightbulb"></i> نصائح</div>
                    <div class="e-card-body">
                        <div class="tip-item"><i class="fas fa-check-circle text-success"></i> اكتب نبذة واضحة عن خبراتك</div>
                        <div class="tip-item"><i class="fas fa-check-circle text-success"></i> اختر الأقسام المناسبة لتخصصك</div>
                        <div class="tip-item"><i class="fas fa-check-circle text-success"></i> حدد المدن اللي تقدر تخدم فيها</div>
                        <div class="tip-item"><i class="fas fa-check-circle text-success"></i> ارفع صورة شخصية واضحة</div>
                    </div>
                </div>

                {{-- حفظ --}}
                <div class="e-card sticky-actions">
                    <div class="e-card-body">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill mb-2">
                            <i class="fas fa-save me-1"></i>حفظ وإكمال الملف الشخصي
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .complete-hero {
        background: linear-gradient(135deg, var(--e-primary, #2f5c69), var(--e-primary-light, #3d7a8a));
        padding: 2rem 0 4rem;
        text-align: center;
    }
    .complete-hero-icon {
        width: 64px; height: 64px; border-radius: 50%;
        background: rgba(255,255,255,0.15); color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.5rem; margin-bottom: 1rem;
    }

    /* Steps */
    .steps-bar { display: flex; align-items: center; justify-content: center; gap: 0; margin-top: 1.5rem; }
    .step-item { display: flex; flex-direction: column; align-items: center; gap: 0.3rem; }
    .step-num {
        width: 34px; height: 34px; border-radius: 50%;
        background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.6);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem; border: 2px solid rgba(255,255,255,0.2);
    }
    .step-item.active .step-num { background: #fff; color: var(--e-primary, #2f5c69); border-color: #fff; }
    .step-label { font-size: 0.7rem; color: rgba(255,255,255,0.5); font-weight: 600; }
    .step-item.active .step-label { color: #fff; }
    .step-line { width: 50px; height: 2px; background: rgba(255,255,255,0.2); margin: 0 0.5rem; margin-bottom: 1.2rem; }

    /* Cards */
    .e-card { background: #fff; border-radius: 16px; border: 1px solid var(--e-gray-200, #e2e8f0); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    .e-card-header { display: flex; align-items: center; gap: 0.5rem; padding: 1rem 1.25rem; font-weight: 700; font-size: 0.95rem; color: var(--e-primary, #2f5c69); border-bottom: 1px solid var(--e-gray-100, #f1f5f9); background: var(--e-gray-50, #f8fafc); }
    .e-card-body { padding: 1.25rem; }
    .e-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
    .e-badge-primary { background: rgba(47,92,105,0.1); color: var(--e-primary, #2f5c69); }

    /* Category Cards */
    .category-select-card { padding: 1rem; border: 2px solid var(--e-gray-200, #e2e8f0); border-radius: 12px; transition: 0.25s; height: 100%; background: #fff; }
    .category-select-card:hover { border-color: var(--e-primary, #2f5c69); }
    .category-select-card.is-selected { border-color: var(--e-primary, #2f5c69); background: rgba(47,92,105,0.03); }
    .category-select-icon { color: var(--e-primary, #2f5c69); margin-left: 0.25rem; }
    .sub-categories-area { margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--e-gray-200, #e2e8f0); }

    .sub-category-chip {
        display: inline-flex; align-items: center; padding: 0.3rem 0.75rem; border-radius: 999px;
        font-size: 0.8rem; font-weight: 500; cursor: pointer;
        border: 1.5px solid var(--e-gray-300, #cbd5e1); background: #fff;
        color: var(--e-gray-600, #475569); transition: 0.2s; user-select: none;
    }
    .sub-category-chip:hover { border-color: var(--e-primary, #2f5c69); color: var(--e-primary, #2f5c69); }
    .sub-category-chip.is-selected { background: var(--e-primary, #2f5c69); color: #fff; border-color: var(--e-primary, #2f5c69); }

    /* City Chips */
    .city-chip {
        display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.45rem 1rem;
        border-radius: 999px; font-size: 0.85rem; font-weight: 600; cursor: pointer;
        border: 2px solid var(--e-gray-200, #e2e8f0); background: #fff;
        color: var(--e-gray-600, #475569); transition: 0.2s; user-select: none;
    }
    .city-chip:hover { border-color: var(--e-primary, #2f5c69); color: var(--e-primary, #2f5c69); }
    .city-chip.is-selected {
        background: linear-gradient(135deg, var(--e-primary, #2f5c69), var(--e-primary-light, #3d7a8a));
        color: #fff; border-color: transparent; box-shadow: 0 2px 8px rgba(47,92,105,0.25);
    }

    /* Avatar */
    .avatar-preview { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--e-gray-200, #e2e8f0); }
    .avatar-preview-placeholder {
        width: 120px; height: 120px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
        background: var(--e-gray-100, #f1f5f9); color: var(--e-gray-400, #94a3b8); border: 4px dashed var(--e-gray-300, #cbd5e1);
    }

    /* Tips */
    .tip-item { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0; font-size: 0.85rem; color: var(--e-gray-600, #475569); }
    .tip-item i { font-size: 0.75rem; }

    .form-control:focus { border-color: var(--e-primary, #2f5c69); box-shadow: 0 0 0 3px rgba(47,92,105,0.1); }

    @media (min-width: 992px) { .sticky-actions { position: sticky; top: 80px; } }
    @media (max-width: 768px) {
        .complete-hero { padding: 1.5rem 0 3rem; }
        .steps-bar { gap: 0; }
        .step-line { width: 30px; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var maxCategories = {{ $maxCategories }};
        var cbs = document.querySelectorAll('.category-checkbox');

        cbs.forEach(function(cb) {
            cb.addEventListener('change', function() {
                var card = this.closest('.category-select-card');
                var sub = document.getElementById('sub_categories_' + this.dataset.categoryId);
                if (this.checked) {
                    card.classList.add('is-selected');
                    if (sub) sub.style.display = 'block';
                } else {
                    card.classList.remove('is-selected');
                    if (sub) { sub.style.display = 'none'; sub.querySelectorAll('.sub-category-checkbox').forEach(function(s) { s.checked = false; s.closest('.sub-category-chip').classList.remove('is-selected'); }); }
                }
                var count = document.querySelectorAll('.category-checkbox:checked').length;
                cbs.forEach(function(c) { c.disabled = !c.checked && count >= maxCategories; });
            });
        });

        document.querySelectorAll('.sub-category-checkbox').forEach(function(cb) {
            cb.addEventListener('change', function() {
                this.closest('.sub-category-chip').classList.toggle('is-selected', this.checked);
                if (this.checked) { var p = document.getElementById('category_' + this.dataset.categoryId); if (p && !p.checked) { p.checked = true; p.dispatchEvent(new Event('change')); } }
            });
        });

        document.querySelectorAll('.city-chip input').forEach(function(cb) {
            cb.addEventListener('change', function() { this.closest('.city-chip').classList.toggle('is-selected', this.checked); });
        });

        cbs.forEach(function(cb) { if (cb.checked) cb.dispatchEvent(new Event('change')); });
    });

    function previewImg(input) {
        if (input.files && input.files[0]) {
            var r = new FileReader();
            r.onload = function(e) {
                var p = document.getElementById('avatarPreview');
                if (p.tagName === 'IMG') { p.src = e.target.result; }
                else { var img = document.createElement('img'); img.src = e.target.result; img.className = 'avatar-preview'; img.id = 'avatarPreview'; p.parentNode.replaceChild(img, p); }
            };
            r.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
