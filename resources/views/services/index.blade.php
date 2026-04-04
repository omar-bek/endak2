@extends('layouts.app')

@section('title', 'الخدمات')

@section('content')
{{-- Hero --}}
<div class="services-hero">
    <div class="container text-center">
        <h1 class="fw-bold text-white mb-2">
            @guest
                {{ __('messages.services_title_guest') }}
            @else
                {{ auth()->user()->isProvider() ? __('messages.services_title_provider') : __('messages.services_title_client') }}
            @endguest
        </h1>
        <p class="text-white-50 mb-0">
            @guest
                {{ __('messages.services_desc_guest') }}
            @else
                {{ auth()->user()->isProvider() ? __('messages.services_desc_provider') : __('messages.services_desc_client') }}
            @endguest
        </p>
    </div>
</div>

{{-- Filter --}}
<div class="container" style="margin-top: -2.5rem; position: relative; z-index: 3;">
    <div class="filter-card">
        @auth
            @if(!auth()->user()->isProvider())
                <div class="tip-banner tip-banner-client mb-4">
                    <div class="tip-banner-icon"><i class="fas fa-lightbulb"></i></div>
                    <div class="tip-banner-content">
                        <strong>{{ __('messages.filter_user_client_title') }}</strong>
                        <p class="mb-2">{!! __('messages.filter_user_client_text') !!}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('categories.index') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                <i class="fas fa-th-large me-1"></i>{{ __('messages.filter_user_client_btn_categories') }}
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="tip-banner tip-banner-provider mb-4">
                    <div class="tip-banner-icon"><i class="fas fa-handshake"></i></div>
                    <div class="tip-banner-content">
                        <strong>{{ __('messages.filter_user_provider_title') }}</strong>
                        <p class="mb-0">{{ __('messages.filter_user_provider_text') }}</p>
                    </div>
                </div>
            @endif
        @endauth

        <form method="GET" class="row g-3 align-items-end">
            <div class="col-lg-3 col-sm-6">
                <label class="form-label small fw-semibold text-muted">{{ __('messages.filter_search_placeholder') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="{{ __('messages.filter_search_placeholder') }}">
                </div>
            </div>

            <div class="col-lg-2 col-sm-6">
                <label class="form-label small fw-semibold text-muted">القسم</label>
                <select name="category" id="category" class="form-select">
                    <option value="">{{ __('messages.filter_category_all') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-sm-6">
                <label class="form-label small fw-semibold text-muted">القسم الفرعي</label>
                <select name="sub_category" id="sub_category" class="form-select" {{ empty(request('category')) ? 'disabled' : '' }}>
                    <option value="">{{ __('messages.filter_subcategory_all') }}</option>
                    @if(isset($subCategories) && $subCategories->count() > 0)
                        @foreach($subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}" {{ request('sub_category') == $subCategory->id ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? $subCategory->name_ar : $subCategory->name_en }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-lg-2 col-sm-6">
                <label class="form-label small fw-semibold text-muted">المدينة</label>
                <select name="city" class="form-select">
                    <option value="">{{ __('messages.filter_city_all') }}</option>
                    @if(isset($cities))
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? ($city->name_ar ?? '') : ($city->name_en ?? '') }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-lg-3 col-sm-12">
                <button type="submit" class="btn btn-accent w-100 rounded-pill">
                    <i class="fas fa-search me-1"></i>{{ __('messages.filter_btn_search') }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Services List --}}
<div class="container py-4">
    <h2 class="section-title">{{ __('messages.services_title') }}</h2>

    @if($services->count() > 0)
        <div class="services-grid">
            @foreach($services as $service)
                @php
                    $offersCount = $service->offers()->count();
                    $pendingOffers = $service->offers()->where('status', 'pending')->count();
                @endphp
                <div class="svc-card">
                    <div class="svc-card-img">
                        <img src="{{ $service->image ? asset('storage/' . $service->image) : $service->category->image_url }}"
                             alt="{{ $service->title }}"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-service.svg') }}';">

                        {{-- Top badges --}}
                        <div class="svc-img-top">
                            @if($service->city)
                                <span class="svc-city-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ app()->getLocale() == 'ar' ? ($service->city->name_ar ?? $service->city->name) : ($service->city->name_en ?? $service->city->name) }}
                                </span>
                            @endif
                            @if($service->voice_note)
                                <span class="svc-icon-badge"><i class="fas fa-microphone"></i></span>
                            @endif
                        </div>

                        {{-- Price --}}
                        <span class="svc-price-badge">{{ $service->formatted_price }}</span>
                    </div>

                    <div class="svc-card-body">
                        {{-- Category + Sub --}}
                        <div class="svc-category-line">
                            <span>
                                <i class="{{ $service->category->icon ?? 'fas fa-folder' }}"></i>
                                {{ app()->getLocale() == 'ar' ? $service->category->name : $service->category->name_en }}
                            </span>
                            @if($service->subCategory)
                                <span class="svc-sub-sep">/</span>
                                <span>{{ app()->getLocale() == 'ar' ? $service->subCategory->name_ar : $service->subCategory->name_en }}</span>
                            @endif
                        </div>

                        <h5 class="svc-title">{{ $service->title }}</h5>
                        <p class="svc-desc">{{ Str::limit($service->description, 90) }}</p>

                        {{-- Info chips --}}
                        <div class="svc-chips">
                            <span class="svc-chip" title="تاريخ النشر">
                                <i class="fas fa-clock"></i> {{ $service->created_at->diffForHumans() }}
                            </span>
                            @if($offersCount > 0)
                                <span class="svc-chip svc-chip-offers" title="العروض المقدمة">
                                    <i class="fas fa-handshake"></i> {{ $offersCount }} {{ $offersCount == 1 ? 'عرض' : 'عروض' }}
                                </span>
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="svc-footer">
                            <a href="{{ $service->user->isProvider() ? route('provider.profile.public', $service->user->id) : '#' }}" class="svc-user">
                                @include('partials.user-avatar', ['user' => $service->user, 'size' => 30])
                                <div class="svc-user-info">
                                    <span class="svc-user-name">{{ $service->user->name }}</span>
                                    <span class="svc-user-type">{{ $service->user->isProvider() ? 'مزود خدمة' : 'عميل' }}</span>
                                </div>
                            </a>
                            <a href="{{ route('services.show', $service->slug) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                {{ __('messages.view_details') }} <i class="fas fa-arrow-left ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $services->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-search"></i>
            <h4>{{ __('messages.no_services_title') }}</h4>
            <p>{{ __('messages.no_services_desc') }}</p>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Hero */
    .services-hero {
        background: linear-gradient(135deg, var(--e-primary, #2f5c69), var(--e-primary-light, #3d7a8a));
        padding: 2.5rem 0 5rem;
    }

    .services-hero h1 { font-size: 1.8rem; }

    /* Filter Card */
    .filter-card {
        background: var(--e-white, #fff);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 10px;
        border: 1.5px solid var(--e-gray-200, #e2e8f0);
        font-size: 0.9rem;
        padding: 0.55rem 0.85rem;
    }

    .filter-card .input-group-text {
        border-radius: 10px 0 0 10px;
        border: 1.5px solid var(--e-gray-200, #e2e8f0);
        border-left: none;
        background: var(--e-gray-50, #f8fafc);
    }

    [dir="rtl"] .filter-card .input-group-text {
        border-radius: 0 10px 10px 0;
        border-right: none;
        border-left: 1.5px solid var(--e-gray-200, #e2e8f0);
    }

    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: var(--e-primary, #2f5c69);
        box-shadow: 0 0 0 3px rgba(47,92,105,0.1);
    }

    /* Tip Banner */
    .tip-banner {
        display: flex;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        align-items: flex-start;
    }

    .tip-banner-client {
        background: linear-gradient(135deg, rgba(47,92,105,0.06), rgba(243,164,70,0.06));
        border: 1px solid rgba(47,92,105,0.1);
    }

    .tip-banner-provider {
        background: linear-gradient(135deg, rgba(16,185,129,0.06), rgba(243,164,70,0.06));
        border: 1px solid rgba(16,185,129,0.1);
    }

    .tip-banner-icon {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--e-accent, #f3a446);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .tip-banner-content { flex: 1; }
    .tip-banner-content strong { color: var(--e-gray-800, #1e293b); font-size: 0.95rem; }
    .tip-banner-content p { color: var(--e-gray-500, #64748b); font-size: 0.85rem; margin-top: 0.25rem; }

    /* Section Title */
    .section-title {
        font-weight: 700;
        color: var(--e-gray-800, #1e293b);
        margin-bottom: 1.5rem;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title::after {
        content: '';
        flex: 1;
        height: 2px;
        background: var(--e-gray-200, #e2e8f0);
        margin-right: 0.75rem;
    }

    /* Service Cards - Grid */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.25rem;
    }

    .svc-card {
        background: var(--e-white, #fff);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--e-gray-200, #e2e8f0);
        transition: 0.3s;
        display: flex;
        flex-direction: column;
    }

    .svc-card:hover {
        box-shadow: 0 12px 32px rgba(0,0,0,0.1);
        transform: translateY(-4px);
        border-color: transparent;
    }

    /* Image Area */
    .svc-card-img {
        position: relative;
        height: 190px;
        overflow: hidden;
    }

    .svc-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .svc-card:hover .svc-card-img img { transform: scale(1.08); }

    .svc-img-top {
        position: absolute;
        top: 10px;
        right: 10px;
        left: 10px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 6px;
    }

    .svc-city-badge {
        background: rgba(0,0,0,0.55);
        backdrop-filter: blur(6px);
        color: #fff;
        padding: 0.25rem 0.65rem;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .svc-city-badge i { color: var(--e-accent, #f3a446); font-size: 0.65rem; }

    .svc-icon-badge {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(6px);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
    }

    .svc-price-badge {
        position: absolute;
        bottom: 10px;
        left: 10px;
        background: var(--e-accent, #f3a446);
        color: #fff;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.82rem;
        box-shadow: 0 3px 10px rgba(243,164,70,0.35);
    }

    /* Card Body */
    .svc-card-body {
        padding: 1rem 1.15rem 1.15rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .svc-category-line {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--e-primary, #2f5c69);
        margin-bottom: 0.5rem;
        opacity: 0.8;
    }

    .svc-category-line i { font-size: 0.65rem; margin-left: 0.2rem; }
    .svc-sub-sep { color: var(--e-gray-300); margin: 0 0.1rem; }

    .svc-title {
        font-weight: 700;
        color: var(--e-gray-800, #1e293b);
        font-size: 1rem;
        margin-bottom: 0.35rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .svc-desc {
        color: var(--e-gray-500, #64748b);
        font-size: 0.82rem;
        line-height: 1.55;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Info Chips */
    .svc-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 0.85rem;
    }

    .svc-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        font-weight: 500;
        color: var(--e-gray-500, #64748b);
        background: var(--e-gray-50, #f8fafc);
        padding: 0.25rem 0.55rem;
        border-radius: 6px;
        border: 1px solid var(--e-gray-100, #f1f5f9);
    }

    .svc-chip i { font-size: 0.6rem; color: var(--e-gray-400); }

    .svc-chip-offers {
        background: rgba(16,185,129,0.08);
        color: #059669;
        border-color: rgba(16,185,129,0.15);
    }

    .svc-chip-offers i { color: #10b981; }

    /* Footer */
    .svc-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 0.85rem;
        border-top: 1px solid var(--e-gray-100, #f1f5f9);
    }

    .svc-user {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        color: inherit;
        transition: 0.2s;
    }

    .svc-user:hover { opacity: 0.8; }

    .svc-user-info {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .svc-user-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--e-gray-700, #334155);
    }

    .svc-user-type {
        font-size: 0.65rem;
        color: var(--e-gray-400, #94a3b8);
    }

    .svc-footer .btn-primary {
        background: var(--e-primary, #2f5c69) !important;
        border-color: var(--e-primary, #2f5c69) !important;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .svc-footer .btn-primary:hover {
        background: var(--e-primary-dark, #1e3d47) !important;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: var(--e-gray-400, #94a3b8);
    }

    .empty-state i { font-size: 3.5rem; margin-bottom: 1rem; opacity: 0.3; display: block; }
    .empty-state h4 { color: var(--e-gray-600, #475569); font-weight: 600; }
    .empty-state p { font-size: 0.9rem; }

    /* Responsive */
    @media (max-width: 768px) {
        .services-hero { padding: 1.5rem 0 4rem; }
        .services-hero h1 { font-size: 1.4rem; }
        .services-grid { grid-template-columns: 1fr; }
        .filter-card { padding: 1rem; }
        .tip-banner { flex-direction: column; align-items: center; text-align: center; }
    }

    @media (max-width: 576px) {
        .services-grid { grid-template-columns: 1fr; }
        .svc-card-img { height: 170px; }
        .svc-footer { flex-direction: column; gap: 0.75rem; align-items: stretch; }
        .svc-footer .btn { width: 100%; text-align: center; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var catSelect = document.getElementById('category');
    var subSelect = document.getElementById('sub_category');

    if (catSelect && subSelect) {
        catSelect.addEventListener('change', function() {
            var val = this.value;
            if (val) {
                subSelect.disabled = false;
                fetch('/api/categories/' + val + '/subcategories', { headers: { 'Accept': 'application/json' } })
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        subSelect.innerHTML = '<option value="">{{ __("messages.filter_subcategory_all") }}</option>';
                        data.forEach(function(s) {
                            var opt = document.createElement('option');
                            opt.value = s.id;
                            opt.textContent = s.name_ar || s.name_en;
                            subSelect.appendChild(opt);
                        });
                    }).catch(function() {});
            } else {
                subSelect.disabled = true;
                subSelect.innerHTML = '<option value="">{{ __("messages.filter_subcategory_all") }}</option>';
            }
        });
    }
});
</script>
@endpush
