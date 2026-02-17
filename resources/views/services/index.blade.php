@extends('layouts.app')

@section('title', 'الخدمات')

<style>
.header-section {
    position: relative;
    background: linear-gradient(135deg, #2f5c69, #3c6f7d, #2f5c69);
    color: #fff;
    padding: 100px 0 50px;
    text-align: center;
    overflow: hidden;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
}

.header-section h1 {
    font-weight: 700;
    font-size: 2.5rem;
    letter-spacing: 0.5px;
    animation: fadeDown 1s ease-in-out;
}

.header-section p {
    color: #dce9f3;
    font-weight: 300;
    font-size: 1.1rem;
    animation: fadeUp 1.2s ease-in-out;
}

.header-section::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 120px;
    background: url('https://svgshare.com/i/18bn.svg') repeat-x;
    background-size: cover;
}

@keyframes fadeDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.services-section {
    background: linear-gradient(135deg, #e6eefc, #f1f7ff);
    color: #2f5c69;
    padding: 60px 0;
}

.services-section h2 {
    text-align: center;
    font-weight: 700;
    margin-bottom: 2.5rem;
    color: #2f5c69;
    position: relative;
}

.services-section h2::after {
    content: "";
    display: block;
    width: 70px;
    height: 4px;
    background: #f3a446;
    border-radius: 2px;
    margin: 10px auto 0;
}

.service-card {
    display: flex;
    align-items: stretch;
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(47, 92, 105, 0.08);
    overflow: hidden;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
    direction: rtl;
    margin-bottom: 1.5rem;
    animation: fadeIn 0.6s ease-in-out;
}

.service-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 10px 25px rgba(47, 92, 105, 0.15);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.service-card img {
    width: 35%;
    height: auto;
    object-fit: cover;
}

.service-card-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.service-card h5 {
    color: #2f5c69;
    font-weight: 700;
    margin-bottom: 0.8rem;
}

.service-card .card-text {
    color: #444;
    font-size: 0.92rem;
    line-height: 1.5;
}

.service-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.service-info .price {
    color: #f3a446;
    font-weight: bold;
    font-size: 1rem;
}

.service-card .card-footer {
    background: transparent;
    border: none;
    padding-top: 0;
}

.service-card .btn {
    border-radius: 25px;
    font-weight: 600;
    color: #2f5c69;
    border: 2px solid #2f5c69;
    transition: all 0.3s ease;
}

.service-card .btn:hover {
    background: #2f5c69;
    color: #fff;
    transform: scale(1.05);
}

.voice-note-mini {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 0.6rem;
    background: #edf4f7;
    border-radius: 15px;
    border: 1px solid #d3e3e7;
}

@media (max-width: 992px) {
    .service-card {
        flex-direction: column;
    }
    .service-card img {
        width: 100%;
        height: 220px;
    }
}

.filter-section {
    background: #ffffff;
    padding: 60px 0;
    box-shadow: 0 4px 20px rgba(47, 92, 105, 0.08);
    animation: fadeIn 0.8s ease-in-out;
}

.filter-form .form-control,
.filter-form .form-select {
    border-radius: 30px;
    border: 1px solid #b7d0d6;
    padding: 10px 18px;
    transition: all 0.3s ease;
}

.filter-form .form-control:focus,
.filter-form .form-select:focus {
    border-color: #2f5c69;
    box-shadow: 0 0 8px rgba(47, 92, 105, 0.2);
}

.btn-main {
    background-color: #f3a446;
    color: #fff;
    font-weight: 600;
    border-radius: 30px;
    border: none;
    transition: all 0.3s ease;
}

.btn-main:hover {
    background-color: #e2933d;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(243, 164, 70, 0.4);
}

.btn-outline-main {
    color: #2f5c69;
    border: 2px solid #2f5c69;
    border-radius: 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-main:hover {
    background-color: #2f5c69;
    color: #fff;
}

/* ===== الرسائل ===== */
.user-alert {
    border-radius: 15px;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(47, 92, 105, 0.1);
}

.user-alert i {
    color: #2f5c69;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

.service-card {
    opacity: 0;
    animation: slideUp 0.8s forwards;
}

.service-card:nth-child(1) { animation-delay: 0.1s; }
.service-card:nth-child(2) { animation-delay: 0.2s; }
.service-card:nth-child(3) { animation-delay: 0.3s; }
.service-card:nth-child(4) { animation-delay: 0.4s; }
</style>

@section('content')
<!-- Header -->
<section class="header-section">
    <div class="container text-center">
<h1 class="fw-bold">
    @guest
        {{ __('messages.services_title_guest') }}
    @else
        @if(auth()->user()->isProvider())
            {{ __('messages.services_title_provider') }}
        @else
            {{ __('messages.services_title_client') }}
        @endif
    @endguest
</h1>

<p class="mt-2">
    @guest
        {{ __('messages.services_desc_guest') }}
    @else
        @if(auth()->user()->isProvider())
            {{ __('messages.services_desc_provider') }}
        @else
            {{ __('messages.services_desc_client') }}
        @endif
    @endguest
</p>

    </div>
</section>

<section class="filter-section">
    <div class="container">
        @auth
            @if(!auth()->user()->isProvider())
                <div class="user-alert alert text-center mb-4 border-0 shadow-sm" 
                     style="background: linear-gradient(135deg, #e0f7f6, #fdf8e3); border-radius: 15px;">
                    <i class="fas fa-info-circle fa-lg mb-2" style="color: #007b8f;"></i>
<h6 class="fw-bold text-dark mb-2">{{ __('messages.filter_user_client_title') }}</h6>
<p class="mb-3 text-secondary">{!! __('messages.filter_user_client_text') !!}</p>
                    <div class="mt-3">
<a href="{{ route('categories.index') }}" class="btn text-white px-4" style="background-color: #00a6a6; border-radius: 8px;">
    <i class="fas fa-th-large"></i> {{ __('messages.filter_user_client_btn_categories') }}
</a>
<a href="{{ route('services.index') }}" class="btn btn-outline-main px-4" style="border: 2px solid #d4a017; color: #d4a017; border-radius: 8px;">
    <i class="fas fa-list"></i> {{ __('messages.filter_user_client_btn_services') }}
</a>
                    </div>
                </div>
            @else
                <div class="user-alert alert text-center mb-4 border-0 shadow-sm" 
                     style="background: linear-gradient(135deg, #e8fff9, #fff6e3); border-radius: 15px;">
                    <i class="fas fa-handshake fa-lg mb-2" style="color: #d4a017;"></i>
<h6 class="fw-bold text-dark mb-2">{{ __('messages.filter_user_provider_title') }}</h6>
<p class="mb-0 text-secondary">{{ __('messages.filter_user_provider_text') }}</p>
                </div>
            @endif
        @endauth

        <form method="GET" class="filter-form row g-3 align-items-center justify-content-center">
            <div class="col-md-3 col-sm-6">
<input type="text" name="search" class="form-control" placeholder="{{ __('messages.filter_search_placeholder') }}">
            </div>

            <div class="col-md-2 col-sm-6">
                <select name="category" id="category" class="form-select">
<option value="">{{ __('messages.filter_category_all') }}</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 col-sm-6">
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

            <div class="col-md-2 col-sm-6">
                <select name="city" class="form-select">
<option value="">{{ __('messages.filter_city_all') }}</option>
                    @if(isset($cities) && $cities->count() > 0)
                        @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                            {{ app()->getLocale() == 'ar' ? ($city->name_ar ?? '') : ($city->name_en ?? '') }}
                        </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-md-3 col-sm-12">
<button type="submit" class="btn btn-main w-100">
    <i class="fas fa-search"></i> {{ __('messages.filter_btn_search') }}
</button>
            </div>
        </form>
    </div>
</section>

<!-- Services -->
<section class="services-section">
    <div class="container">
<h2>{{ __('messages.services_title') }}</h2>
        @if($services->count() > 0)
            @foreach($services as $service)
                <div class="service-card">
                    <img src="{{ $service->category->image_url }}" alt="{{ $service->title }}"
                        onerror="this.onerror=null; this.src='{{ asset('images/default-service.svg') }}';">
                    <div class="service-card-content">
                        <div>
                            <h5>{{ $service->title }}</h5>
                            <p class="card-text">{{ Str::limit($service->description, 150) }}</p>
                            <div class="service-info">
                                <span class="price">{{ $service->formatted_price }}</span>
                                <div>
                                    <small class="text-muted d-block">{{ app()->getLocale() == 'ar' ? $service->category->name : $service->category->name_en }}</small>
                                    @if($service->subCategory)
                                        <small class="text-info d-block">
                                            <i class="fas fa-layer-group me-1"></i>
                                            {{ app()->getLocale() == 'ar' ? $service->subCategory->name_ar : $service->subCategory->name_en }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            @if($service->city)
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ app()->getLocale() == 'ar' ? $service->city->name : $service->city->name_en }}
                                </small>
                            </div>
                            @endif
                            @if($service->voice_note)
                            <div class="mt-3">
                                <div class="voice-note-mini">
                                    <i class="fas fa-microphone text-primary me-1"></i>
<small>{{ __('messages.voice_available') }}</small>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer mt-3">
                            <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline-primary w-100">
<i class="fas fa-eye"></i> {{ __('messages.view_details') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="d-flex justify-content-center mt-4">
                {{ $services->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
<h4 class="mt-3 text-muted">{{ __('messages.no_services_title') }}</h4>
<p class="text-muted">{{ __('messages.no_services_desc') }}</p>
            </div>
        @endif
    </div>
</section>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const subCategorySelect = document.getElementById('sub_category');

    if (categorySelect && subCategorySelect) {
        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;

            if (selectedCategory) {
                subCategorySelect.disabled = false;
                fetch(`/api/categories/${selectedCategory}/subcategories`)
                    .then(response => response.json())
                    .then(data => {
                        subCategorySelect.innerHTML = '<option value="">  {{ __('messages.filter_subcategory_all') }}</option>';
                        data.forEach(subCategory => {
                            const option = document.createElement('option');
                            option.value = subCategory.id;
                            option.textContent = subCategory.name_ar || subCategory.name_en;
                            subCategorySelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching subcategories:', error));
            } else {
                subCategorySelect.disabled = true;
                subCategorySelect.innerHTML = '<option value="">{{ __('messages.filter_subcategory_all') }}  </option>';
            }
        });
    }
});
</script>
@endsection
