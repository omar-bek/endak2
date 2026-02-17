@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('warning'))
        <div class="container mt-3">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="container mt-3">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Category Header -->
    <section class="py-4 category-header-section">
        <div class="container">
            <nav aria-label="breadcrumb" class="fade-in">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                    <li class="breadcrumb-item"><a
                            href="{{ route('categories.index') }}">{{ __('messages.categories') }}</a></li>
                    @if ($category->parent)
                        <li class="breadcrumb-item"><a
                                href="{{ route('categories.show', $category->parent->slug) }}">{{ $category->parent->name }}</a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>

            <div class="row align-items-center fade-in-up">
                <div class="col-md-8 mb-4 mb-md-0">
                    <h1 class="fw-bold  mb-3">{{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}</h1>
                    <p class="lead text-light opacity-75">{{ $category->description }}</p>

                    <div class="d-flex flex-wrap gap-2 align-items-center mt-3">
                        <span class="badge bg-light text-dark fw-semibold">{{ $services->total() }}
                            {{ trans_choice('messages.service_count_unit', $services->total()) }}</span>
                        @if ($category->subCategories && $category->subCategories->count() > 0)
                            <span class="badge bg-warning text-dark fw-semibold">{{ $category->subCategories->count() }}
                                {{ trans_choice('messages.subcategory_count_unit', $category->subCategories->count()) }}</span>
                        @endif

                        @auth
                            @if (!auth()->user()->isProvider())
                                @if ($category->subCategories && $category->subCategories->count() > 0)
                                    <div class="alert alert-info mt-3 w-100 fade-in info-box">
                                        <i class="fas fa-info-circle "></i>
                                        <strong>{{ __('messages.info_alert_title') }}</strong>
                                        <br><small>{{ __('messages.info_alert_body') }}</small>
                                    </div>
                                @else
                                    @auth
                                        <a href="{{ route('services.request', $category->slug) }}"
                                            class="btn btn-warning mt-3 fw-bold text-dark shine-btn">
                                            <i class="fas fa-concierge-bell"></i> {{ __('messages.request_service_button_text') }}
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-warning mt-3 fw-bold text-dark shine-btn">
                                            <i class="fas fa-sign-in-alt"></i> {{ __('messages.request_service_button_text') }}
                                        </a>
                                    @endauth
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-light mt-3 fw-bold">
                                <i class="fas fa-sign-in-alt"></i> {{ __('messages.login_to_request_button_text') }}
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="col-md-4 text-center fade-in">
                    <div class="category-header-image">
                        <img src="{{ $category->image_url }}" class="img-fluid rounded shadow category-main-image"
                            alt="{{ $category->name }}"
                            onerror="this.onerror=null; this.src='{{ asset('images/default-service.svg') }}';">
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($category->subCategories && $category->subCategories->count() > 0)
        <section class="py-5 subcategories-section fade-in-up">
            <div class="container">
                <h3 class="mb-5 text-center section-title">
                    <i class="fas fa-layer-group me-2 text-warning"></i> {{ __('messages.subcategories_title') }}
                </h3>
                <div class="row">
                    @foreach ($category->subCategories as $subCategory)
                        @if ($subCategory->status)
                            <div class="col-6 col-md-3 mb-4">
                                @auth
                                    <a href="{{ route('services.request', $category->slug) }}?sub_category_id={{ $subCategory->id }}"
                                        class="text-decoration-none text-dark">
                                    @else
                                        <a href="{{ route('login') }}" class="text-decoration-none text-dark">
                                        @endauth
                                        <div class="card sub-category-card h-100 text-center clickable-card">
                                            <div class="subcategory-image-container">
                                                @if ($subCategory->image)
                                                    <img src="{{ asset('storage/' . $subCategory->image) }}"
                                                        class="subcategory-image"
                                                        alt="{{ $subCategory->name_ar ?? $subCategory->name_en }}">
                                                @else
                                                    <div class="subcategory-image-placeholder">
                                                        <i class="{{ __('messages.no_image_folder_icon') }}"
                                                            style="font-size: 3rem; color: #6c757d;"></i>
                                                    </div>
                                                @endif
                                                <div class="subcategory-overlay">
                                                    <h6 class="subcategory-title">
                                                        {{ app()->getLocale() == 'ar' ? $subCategory->name_ar : $subCategory->name_en }}
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @if ($subCategory->description_ar || $subCategory->description_en)
                                                    <p class="card-text text-muted small mb-3">
                                                        {{ app()->getLocale() == 'ar' ? $subCategory->description_ar : $subCategory->description_en }}
                                                    </p>
                                                @endif

                                                @php
                                                    $servicesCount = \App\Models\Service::where(
                                                        'category_id',
                                                        $category->id,
                                                    )
                                                        ->where('sub_category_id', $subCategory->id)
                                                        ->where('is_active', true)
                                                        ->count();
                                                @endphp
                                                <small class="text-muted d-block mb-2">
                                                    <i class="fas fa-tasks"></i> {{ $servicesCount }}
                                                    {{ __('messages.service_count_suffix') }}
                                                </small>

                                                @auth
                                                    @if (!auth()->user()->isProvider())
                                                        <span class="btn btn-sm btn-success disabled"
                                                            style="pointer-events: none;">
                                                            <i class="fas fa-concierge-bell"></i>
                                                            {{ __('messages.request_service_button') }}
                                                        </span>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>

        <style>
            .clickable-card {
                transition: all 0.3s ease;
                cursor: pointer;
                border: none;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            .clickable-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            }

            .subcategory-image-container {
                position: relative;
                overflow: hidden;
                border-top-left-radius: 12px;
                border-top-right-radius: 12px;
            }

            .subcategory-image {
                width: 100%;
                height: 180px;
                object-fit: cover;
                transition: transform 0.4s ease;
            }

            .clickable-card:hover .subcategory-image {
                transform: scale(1.08);
            }

            .subcategory-overlay {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: rgba(47, 92, 105, 0.8);
                color: #f3a446;
                padding: 10px;
            }

            .subcategory-title {
                margin: 0;
                font-weight: 600;
            }

            .btn-success.disabled {
                opacity: 0.7;
            }
        </style>
    @endif

    <section class="py-5 services-section fade-in-up">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-success">
                        {{ __('messages.services_in') }}
                        {{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}
                        @if ($selectedSubCategory)
                            <small class="text-muted">-
                                {{ app()->getLocale() == 'ar' ? $selectedSubCategory->name_ar : $selectedSubCategory->name_en }}</small>
                        @endif
                        <span class="badge bg-warning text-dark ms-2">{{ $services->total() }}
                            {{ trans_choice('messages.service_count_unit', $services->total()) }}</span>
                    </h3>
                    @if ($selectedSubCategory)
                        <div class="alert alert-info mt-3 fade-in info-box">
                            <i class="fas fa-filter text-primary"></i>
                            <strong>{{ __('messages.selected_subcategory') }}</strong>
                            {{ app()->getLocale() == 'ar' ? $selectedSubCategory->name_ar : $selectedSubCategory->name_en }}
                            <a href="{{ route('categories.show', $category->slug) }}"
                                class="btn btn-sm btn-outline-secondary ms-2">
                                <i class="fas fa-times"></i> {{ __('messages.cancel_filter') }}
                            </a>
                        </div>
                    @endif
                </div>

                <form class="d-flex mt-3 mt-md-0" method="GET">
                    @if (request('sub_category_id'))
                        <input type="hidden" name="sub_category_id" value="{{ request('sub_category_id') }}">
                    @endif
                    <input type="text" name="search" class="form-control me-2"
                        placeholder="{{ __('messages.search_services_placeholder') }}" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">{{ __('messages.search_button') }}</button>
                </form>
            </div>

            @if ($services->count() > 0)
                <div class="row">
                    @foreach ($services as $service)
                        <div class="col-md-6 col-lg-4 mb-4 fade-in-up">
                            <div class="card service-card h-100 shadow-sm">
                                <div class="service-img-container">
                                    <img src="{{ $service->category->image_url }}" class="card-img-top service-img"
                                        onerror="this.onerror=null; this.src='{{ asset('images/default-service.svg') }}';"
                                        alt="{{ $service->title }}">
                                    <div class="service-overlay">
                                        <span class="overlay-badge">
                                            <i class="fas fa-star text-warning"></i>
                                            {{ number_format($service->average_rating, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-primary mb-2">{{ $service->title }}</h5>
                                    <p class="card-text text-muted small mb-3">{{ Str::limit($service->description, 90) }}
                                    </p>

                                    <div class="mb-2">
                                        <span class="badge bg-primary">{{ $service->category->name }}</span>
                                        @if ($service->subCategory)
                                            <span
                                                class="badge bg-secondary">{{ app()->getLocale() == 'ar' ? $service->subCategory->name_ar : $service->subCategory->name_en }}</span>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-success fw-bold">{{ $service->formatted_price }}</span>
                                        <small class="text-muted">{{ $service->user->name }}</small>
                                    </div>

                                    @if ($service->location)
                                        <small class="text-muted d-block mb-2">
                                            <i
                                                class="fas fa-map-marker-alt me-1 text-warning"></i>{{ $service->location }}
                                        </small>
                                    @endif
                                </div>
                                <div class="card-footer bg-transparent border-0">
                                    <a href="{{ route('services.show', $service->slug) }}"
                                        class="btn btn-warning w-100 ">
                                        <i class="fas fa-eye me-1"></i> {{ __('messages.view_details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">{{ $services->links() }}</div>
            @else
                <div class="text-center py-5 fade-in">
                    <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">
                        {{ __('messages.no_services_title') }}
                    </h4>
                    <p class="text-muted">{{ __('messages.no_services_message') }}</p>
                </div>
            @endif
        </div>
    </section>

    <style>
        .services-section {
            background-color: #f8fafb;
        }

        .service-card {
            border: none;
            border-radius: 14px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(47, 92, 105, 0.15);
        }

        /* ======= صورة الخدمة ======= */
        .service-img-container {
            position: relative;
            overflow: hidden;
            border-radius: 14px 14px 0 0;
        }

        .service-img {
            height: 180px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .service-card:hover .service-img {
            transform: scale(1.07);
        }

        /* ======= الأوفرلاي فوق الصورة ======= */
        .service-overlay {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .overlay-badge {
            background: rgba(47, 92, 105, 0.9);
            color: #fff;
            padding: 4px 10px;
            font-size: 0.85rem;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .card-text {
            line-height: 1.5;
        }

        .btn-primary {
            background-color: #2f5c69;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #f3a446;
            color: #2f5c69;
        }

        .btn-outline-primary {
            border-color: #2f5c69;
            color: #2f5c69;
        }

        .btn-outline-primary:hover {
            background-color: #2f5c69;
            color: #fff;
        }

        .shine-btn {
            position: relative;
            overflow: hidden;
        }

        .shine-btn::after {
            content: "";
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.35) 0%, rgba(255, 255, 255, 0) 80%);
            transform: skewX(-25deg);
            transition: all 0.75s;
        }

        .shine-btn:hover::after {
            left: 125%;
        }

        /* ======= الألوان والباجز ======= */
        .badge.bg-primary {
            background-color: #2f5c69 !important;
        }

        .badge.bg-secondary {
            background-color: #f3a446 !important;
            color: #2f5c69 !important;
        }

        .text-primary {
            color: #2f5c69 !important;
        }

        .text-success {
            color: #f3a446 !important;
        }

        /* ======= ريسبونسيف ======= */
        @media (max-width: 768px) {
            .service-img {
                height: 150px;
            }

            .card-title {
                font-size: 1rem;
            }
        }
    </style>


    <style>
        .category-header-section {
            background: linear-gradient(120deg, #2f5c69, #3b7d8a);
            color: #fff;
            border-radius: 0 0 25px 25px;
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        .category-header-section h1 {
            font-size: 2rem;
        }

        .category-main-image {
            max-height: 200px;
            object-fit: cover;
            border: 2px solid #f3a446;
            border-radius: 15px;
            transition: transform 0.4s ease;
        }

        .category-main-image:hover {
            transform: scale(1.05);
        }

        .section-title {
            color: #2f5c69;
            font-weight: 700;
        }

        .sub-category-card,
        .service-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
        }

        .sub-category-card:hover,
        .service-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .subcategory-image {
            width: 100%;
            height: 160px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .subcategory-overlay {
            position: absolute;
            bottom: 0;
            background: rgba(47, 92, 105, 0.75);
            width: 100%;
            color: #fff;
            padding: 8px;
        }

        .shine-btn {
            position: relative;
            overflow: hidden;
        }

        .shine-btn::after {
            content: "";
            position: absolute;
            top: 0;
            left: -75%;
            width: 50%;
            height: 100%;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0) 80%);
            transform: skewX(-25deg);
            transition: all 0.75s;
        }

        .shine-btn:hover::after {
            left: 125%;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid #f3a446;
            color: #fff;
        }

        .fade-in,
        .fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.8s forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .category-header-section {
                border-radius: 0;
                text-align: center;
                padding: 2rem 1rem;
            }

            .category-main-image {
                max-height: 150px;
            }
        }
    </style>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });

            // دالة لجلب الأقسام الفرعية من API
            window.getSubCategories = async function(categoryIdOrSlug) {
                try {
                    const response = await fetch(`/api/v1/categories/${categoryIdOrSlug}/subcategories`);
                    const result = await response.json();

                    if (result.success && result.data) {
                        return result.data.subcategories || result.data;
                    }
                    return [];
                } catch (error) {
                    console.error('Error fetching subcategories:', error);
                    return [];
                }
            };

            // دالة للتحقق من صحة sub_category_id قبل الانتقال
            document.querySelectorAll('a[href*="services.request"]').forEach(link => {
                link.addEventListener('click', async function(e) {
                    const url = new URL(this.href);
                    const subCategoryId = url.searchParams.get('sub_category_id');
                    const categorySlug = url.pathname.split('/').pop();

                    if (subCategoryId) {
                        // التحقق من أن sub_category_id صحيح
                        const subCategories = await window.getSubCategories(categorySlug);
                        const isValid = subCategories.some(sub => sub.id == subCategoryId);

                        if (!isValid) {
                            e.preventDefault();
                            alert('القسم الفرعي المحدد غير صحيح');
                            return false;
                        }
                    }
                });
            });
        });
    </script>
@endsection
