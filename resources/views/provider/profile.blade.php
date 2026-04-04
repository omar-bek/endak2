@extends('layouts.app')

@section('title', 'الملف الشخصي - ' . (isset($provider) ? $provider->name : 'مزود الخدمة'))

@section('content')
@php
    $displayUser = isset($provider) ? $provider : Auth::user();
@endphp

{{-- Profile Hero --}}
<div class="provider-hero">
    <div class="container">
        <div class="provider-hero-card">
            <div class="provider-hero-top">
                <div class="provider-avatar-area">
                    @include('partials.user-avatar', ['user' => $displayUser, 'size' => 110])
                </div>
                <div class="provider-hero-info">
                    <h2 class="provider-name">{{ $displayUser->name }}</h2>
                    <div class="provider-badges">
                        @if ($profile->is_verified)
                            <span class="e-badge e-badge-success"><i class="fas fa-check-circle me-1"></i>موثق</span>
                        @else
                            <span class="e-badge e-badge-warning"><i class="fas fa-clock me-1"></i>في انتظار التوثيق</span>
                        @endif
                        @if ($displayUser->isProvider())
                            <span class="e-badge e-badge-primary"><i class="fas fa-briefcase me-1"></i>مزود خدمة</span>
                        @endif
                    </div>
                    @if ($profile->bio)
                        <p class="provider-bio">{{ $profile->bio }}</p>
                    @endif
                </div>
            </div>

            {{-- Stats Row --}}
            <div class="provider-stats">
                <div class="provider-stat">
                    <div class="provider-stat-value">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $profile->rating ? 'text-warning' : '' }}" style="font-size: 0.85rem;"></i>
                        @endfor
                        <span class="ms-1">{{ number_format($profile->rating, 1) }}</span>
                    </div>
                    <div class="provider-stat-label">التقييم</div>
                </div>
                <div class="provider-stat-divider"></div>
                <div class="provider-stat">
                    <div class="provider-stat-value">{{ $profile->completed_services }}</div>
                    <div class="provider-stat-label">خدمات مكتملة</div>
                </div>
                <div class="provider-stat-divider"></div>
                <div class="provider-stat">
                    <div class="provider-stat-value">{{ $activeCategories->count() }}</div>
                    <div class="provider-stat-label">أقسام</div>
                </div>
                <div class="provider-stat-divider"></div>
                <div class="provider-stat">
                    <div class="provider-stat-value">{{ $activeCities->count() }}</div>
                    <div class="provider-stat-label">مدن</div>
                </div>
            </div>

            @if (isset($isOwner) && $isOwner)
                <div class="provider-hero-actions">
                    <a href="{{ route('provider.profile.edit') }}" class="btn btn-accent btn-sm rounded-pill px-4">
                        <i class="fas fa-edit me-1"></i>تعديل الملف الشخصي
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- المعلومات الأساسية --}}
            <div class="e-card mb-4">
                <div class="e-card-header">
                    <i class="fas fa-user-circle"></i> المعلومات الأساسية
                </div>
                <div class="e-card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-user me-2"></i>الاسم</span>
                                <span class="info-value">{{ $displayUser->name }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-envelope me-2"></i>البريد الإلكتروني</span>
                                <span class="info-value">{{ isset($isOwner) && $isOwner ? $displayUser->email : 'مخفي' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-phone me-2"></i>رقم الهاتف</span>
                                <span class="info-value">{{ isset($isOwner) && $isOwner ? ($profile->phone ?? 'غير محدد') : 'مخفي' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-map-marker-alt me-2"></i>العنوان</span>
                                <span class="info-value">{{ isset($isOwner) && $isOwner ? ($profile->address ?? 'غير محدد') : 'مخفي' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- الأقسام --}}
            <div class="e-card mb-4">
                <div class="e-card-header">
                    <i class="fas fa-folder-open"></i> الأقسام التي أعمل فيها
                    <span class="e-badge e-badge-primary ms-auto">{{ $activeCategories->count() }}/{{ $maxCategories }}</span>
                </div>
                <div class="e-card-body">
                    @if ($activeCategories->count() > 0)
                        @php
                            $groupedCategories = $activeCategories->groupBy('category_id');
                        @endphp
                        <div class="row g-3">
                            @foreach ($groupedCategories as $categoryId => $providerCategories)
                                @php
                                    $firstCategory = $providerCategories->first();
                                    $mainCategory = $firstCategory && $firstCategory->category ? $firstCategory->category : null;
                                    $subCategories = $providerCategories->filter(fn($item) => $item->sub_category_id !== null && $item->subCategory !== null);
                                @endphp
                                @if ($mainCategory && $firstCategory)
                                    <div class="col-md-6">
                                        <div class="category-item">
                                            <div class="category-item-header">
                                                <i class="{{ $mainCategory->icon ?? 'fas fa-folder' }}"></i>
                                                <strong>{{ $mainCategory->name ?? 'قسم غير محدد' }}</strong>
                                            </div>
                                            @if ($subCategories->count() > 0)
                                                <div class="d-flex flex-wrap gap-1 mt-2">
                                                    @foreach ($subCategories as $subCat)
                                                        @if ($subCat->subCategory && isset($subCat->subCategory->name_ar))
                                                            <span class="e-badge e-badge-light">{{ $subCat->subCategory->name_ar }}</span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if ($firstCategory->description)
                                                <p class="text-muted small mt-2 mb-0">{{ $firstCategory->description }}</p>
                                            @endif
                                            <div class="d-flex gap-3 mt-2">
                                                @if ($firstCategory->hourly_rate)
                                                    <small class="text-muted"><i class="fas fa-coins me-1"></i>{{ number_format($firstCategory->hourly_rate, 2) }} ريال/ساعة</small>
                                                @endif
                                                @if ($firstCategory->experience_years)
                                                    <small class="text-muted"><i class="fas fa-briefcase me-1"></i>{{ $firstCategory->experience_years }} سنوات</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-50"></i>
                            لم يتم إضافة أي أقسام بعد
                        </div>
                    @endif

                    @if (isset($isOwner) && $isOwner && $canAddCategory)
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="fas fa-plus me-1"></i>إضافة قسم جديد
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- المدن --}}
            <div class="e-card mb-4">
                <div class="e-card-header">
                    <i class="fas fa-map-marker-alt"></i> المدن التي أعمل فيها
                    <span class="e-badge e-badge-primary ms-auto">{{ $activeCities->count() }}/{{ $maxCities }}</span>
                </div>
                <div class="e-card-body">
                    @if ($activeCities->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($activeCities as $providerCity)
                                <div class="city-tag">
                                    <i class="fas fa-map-pin me-1"></i>
                                    {{ $providerCity->city && isset($providerCity->city->name_ar) ? $providerCity->city->name_ar : 'غير محدد' }}
                                    @if (isset($isOwner) && $isOwner)
                                        <button type="button" class="city-tag-remove" onclick="removeCity({{ $providerCity->id }})">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-map-marked-alt fa-2x mb-2 d-block opacity-50"></i>
                            لم يتم إضافة أي مدن بعد
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- ساعات العمل --}}
            <div class="e-card mb-4">
                <div class="e-card-header">
                    <i class="fas fa-clock"></i> ساعات العمل
                </div>
                <div class="e-card-body">
                    @if ($profile->working_hours)
                        @php
                            $days = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                        @endphp
                        @foreach ($profile->working_hours as $index => $hours)
                            @if (isset($hours['enabled']) && $hours['enabled'])
                                <div class="working-hour-item">
                                    <span class="working-hour-day">{{ $days[$index] }}</span>
                                    <span class="working-hour-time">{{ $hours['from'] ?? '--' }} - {{ $hours['to'] ?? '--' }}</span>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-clock fa-2x mb-2 d-block opacity-50"></i>
                            لم يتم تحديد ساعات العمل
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modals (keep existing) --}}
@if (isset($isOwner) && $isOwner)
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light)); color: #fff;">
                    <h5 class="modal-title">إضافة قسم جديد</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="addCategoryForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">القسم الرئيسي</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">اختر القسم</option>
                                @foreach (\App\Models\Category::where('is_active', true)->get() as $category)
                                    @if ($activeCategories->where('category_id', $category->id)->count() == 0)
                                        <option value="{{ $category->id }}" data-has-sub="{{ $category->subCategories()->where('status', true)->count() > 0 ? '1' : '0' }}">
                                            {{ $category->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" id="sub_category_container" style="display: none;">
                            <label for="sub_category_id" class="form-label">القسم الفرعي (اختياري)</label>
                            <select name="sub_category_id" id="sub_category_id" class="form-select">
                                <option value="">اختر القسم الفرعي (اختياري)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف (اختياري)</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label for="hourly_rate" class="form-label">السعر بالساعة</label>
                                <input type="number" name="hourly_rate" id="hourly_rate" class="form-control" min="0" step="0.01">
                            </div>
                            <div class="col-6">
                                <label for="experience_years" class="form-label">سنوات الخبرة</label>
                                <input type="number" name="experience_years" id="experience_years" class="form-control" min="0" max="50">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary btn-sm">إضافة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@push('styles')
<style>
    .provider-hero {
        background: linear-gradient(135deg, var(--e-primary), var(--e-primary-light));
        padding: 2rem 0 0;
    }

    .provider-hero-card {
        background: var(--e-white);
        border-radius: var(--e-radius-xl) var(--e-radius-xl) 0 0;
        padding: 2rem;
        margin-top: 1rem;
    }

    .provider-hero-top {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
    }

    .provider-avatar-area {
        flex-shrink: 0;
    }

    .provider-name {
        font-weight: 700;
        color: var(--e-gray-800);
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
    }

    .provider-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .provider-bio {
        color: var(--e-gray-500);
        font-size: 0.9rem;
        margin-bottom: 0;
        line-height: 1.6;
    }

    .provider-stats {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
        padding: 1.25rem 0;
        margin-top: 1.5rem;
        border-top: 1px solid var(--e-gray-200);
    }

    .provider-stat { text-align: center; }
    .provider-stat-value { font-weight: 700; font-size: 1.1rem; color: var(--e-gray-800); }
    .provider-stat-label { font-size: 0.75rem; color: var(--e-gray-400); margin-top: 2px; }
    .provider-stat-divider { width: 1px; height: 30px; background: var(--e-gray-200); }

    .provider-hero-actions {
        text-align: center;
        padding-top: 1rem;
    }

    /* Cards */
    .e-card {
        background: var(--e-white);
        border-radius: var(--e-radius-lg);
        border: 1px solid var(--e-gray-200);
        overflow: hidden;
        box-shadow: var(--e-shadow-sm);
    }

    .e-card-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.25rem;
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--e-primary);
        border-bottom: 1px solid var(--e-gray-100);
        background: var(--e-gray-50);
    }

    .e-card-body { padding: 1.25rem; }

    /* Badges */
    .e-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.65rem;
        border-radius: var(--e-radius-full);
        font-size: 0.72rem;
        font-weight: 600;
    }

    .e-badge-success { background: var(--e-success-light); color: #059669; }
    .e-badge-warning { background: var(--e-warning-light); color: #92400e; }
    .e-badge-primary { background: rgba(47, 92, 105, 0.1); color: var(--e-primary); }
    .e-badge-light { background: var(--e-gray-100); color: var(--e-gray-600); }

    /* Info items */
    .info-item {
        display: flex;
        flex-direction: column;
        padding: 0.75rem;
        background: var(--e-gray-50);
        border-radius: var(--e-radius);
        height: 100%;
    }

    .info-label { font-size: 0.78rem; color: var(--e-gray-400); margin-bottom: 0.25rem; }
    .info-value { font-weight: 600; color: var(--e-gray-700); }

    /* Category items */
    .category-item {
        padding: 1rem;
        background: var(--e-gray-50);
        border-radius: var(--e-radius);
        border: 1px solid var(--e-gray-200);
        height: 100%;
    }

    .category-item-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--e-primary);
        font-size: 0.95rem;
    }

    /* City tags */
    .city-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.4rem 0.85rem;
        background: linear-gradient(135deg, var(--e-primary-50), rgba(47, 92, 105, 0.08));
        color: var(--e-primary);
        border-radius: var(--e-radius-full);
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid var(--e-primary-100);
    }

    .city-tag-remove {
        background: none;
        border: none;
        color: var(--e-danger);
        cursor: pointer;
        padding: 0 2px;
        font-size: 0.7rem;
        opacity: 0.6;
        transition: opacity 0.2s;
    }

    .city-tag-remove:hover { opacity: 1; }

    /* Working hours */
    .working-hour-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0;
        border-bottom: 1px solid var(--e-gray-100);
    }

    .working-hour-item:last-child { border-bottom: none; }
    .working-hour-day { font-weight: 600; color: var(--e-gray-700); font-size: 0.9rem; }
    .working-hour-time { color: var(--e-gray-500); font-size: 0.85rem; }

    @media (max-width: 768px) {
        .provider-hero-top { flex-direction: column; align-items: center; text-align: center; }
        .provider-badges { justify-content: center; }
        .provider-stats { gap: 0.75rem; }
        .provider-stat-value { font-size: 0.95rem; }
        .provider-hero-card { padding: 1.5rem 1rem; }
    }
</style>
@endpush

@if (isset($isOwner) && $isOwner)
    @push('scripts')
        <script>
            document.getElementById('category_id').addEventListener('change', function() {
                var categoryId = this.value;
                var subCategoryContainer = document.getElementById('sub_category_container');
                var subCategorySelect = document.getElementById('sub_category_id');
                var hasSub = this.options[this.selectedIndex].getAttribute('data-has-sub') === '1';

                subCategorySelect.innerHTML = '<option value="">اختر القسم الفرعي (اختياري)</option>';

                if (hasSub && categoryId) {
                    fetch('/api/categories/' + categoryId + '/subcategories', { headers: { 'Accept': 'application/json' } })
                        .then(function(r) { return r.json(); })
                        .then(function(data) {
                            if (data && Array.isArray(data)) {
                                data.forEach(function(sub) {
                                    var opt = document.createElement('option');
                                    opt.value = sub.id;
                                    opt.textContent = sub.name_ar || sub.name_en;
                                    subCategorySelect.appendChild(opt);
                                });
                                subCategoryContainer.style.display = 'block';
                            }
                        }).catch(function() { subCategoryContainer.style.display = 'none'; });
                } else {
                    subCategoryContainer.style.display = 'none';
                }
            });

            document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
                e.preventDefault();
                var data = Object.fromEntries(new FormData(this));
                if (!data.sub_category_id) delete data.sub_category_id;

                fetch('{{ route("provider.categories.add") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                }).then(function(r) { return r.json(); }).then(function(d) {
                    d.success ? location.reload() : alert(d.error);
                }).catch(function() { alert('حدث خطأ أثناء إضافة القسم'); });
            });

            function removeCity(cityId) {
                if (confirm('هل أنت متأكد من حذف هذه المدينة؟')) {
                    fetch('{{ url("provider/cities") }}/' + cityId, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    }).then(function(r) { return r.json(); }).then(function(d) {
                        d.success ? location.reload() : alert(d.error);
                    }).catch(function() { alert('حدث خطأ أثناء حذف المدينة'); });
                }
            }
        </script>
    @endpush
@endif
@endsection
