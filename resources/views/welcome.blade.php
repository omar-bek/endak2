@extends('layouts.app')

@section('title', __('messages.welcome_title'))

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content slide-in-left">
                <h1 class="hero-title mb-4">
                    {{ __('messages.welcome_title') }}
                </h1>
                <p class="hero-subtitle mb-5">
                    {{ __('messages.welcome_subtitle') }}
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-rocket me-2"></i>
                        {{ __('messages.get_started') }}
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline btn-lg">
                        <i class="fas fa-th-large me-2"></i>
                        {{ __('messages.explore_services') }}
                    </a>
                </div>
            </div>
            <div class="col-lg-6 slide-in-right">
                <div class="text-center">
                    <div class="position-relative">
                        <div class="glass-card p-5 rounded-4">
                            <i class="fas fa-tools fa-8x text-gradient mb-4"></i>
                            <h3 class="text-gradient fw-bold mb-3">منصة الخدمات الذكية</h3>
                            <p class="text-muted">ربط العملاء بمزودي الخدمات المميزين</p>
                        </div>
                        <!-- Floating Elements -->
                        <div class="position-absolute top-0 start-0" style="animation: float 3s ease-in-out infinite;">
                            <div class="bg-primary rounded-circle p-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-star text-white"></i>
                            </div>
                        </div>
                        <div class="position-absolute top-50 end-0" style="animation: float 4s ease-in-out infinite;">
                            <div class="bg-success rounded-circle p-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-check text-white"></i>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 start-50" style="animation: float 3.5s ease-in-out infinite;">
                            <div class="bg-warning rounded-circle p-3" style="width: 45px; height: 45px;">
                                <i class="fas fa-heart text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="text-gradient fw-bold mb-3">{{ __('messages.why_choose_us') }}</h2>
                <p class="text-muted fs-5">اكتشف المميزات التي تجعلنا الخيار الأفضل لك</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4 fade-in-up">
                <div class="card h-100 text-center border-0 shadow-custom">
                    <div class="card-body p-4">
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-shield-alt fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">{{ __('messages.trusted_providers') }}</h4>
                        <p class="text-muted">نختار بعناية مزودي الخدمات لضمان الجودة والموثوقية</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 fade-in-up" style="animation-delay: 0.2s;">
                <div class="card h-100 text-center border-0 shadow-custom">
                    <div class="card-body p-4">
                        <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-award fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">{{ __('messages.quality_services') }}</h4>
                        <p class="text-muted">خدمات عالية الجودة مع ضمان الرضا التام للعملاء</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 fade-in-up" style="animation-delay: 0.4s;">
                <div class="card h-100 text-center border-0 shadow-custom">
                    <div class="card-body p-4">
                        <div class="bg-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-lock fa-2x text-white"></i>
                        </div>
                        <h4 class="fw-bold mb-3">{{ __('messages.secure_payments') }}</h4>
                        <p class="text-muted">مدفوعات آمنة ومشفرة لحماية معلوماتك المالية</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="text-gradient fw-bold mb-3">{{ __('messages.popular_categories') }}</h2>
                <p class="text-muted fs-5">استكشف الأقسام الشائعة وابحث عن الخدمة التي تحتاجها</p>
            </div>
        </div>

        <div class="row g-4">
            @php
                $categories = \App\Models\Category::where('is_active', true)->take(6)->get();
            @endphp

            @forelse($categories as $category)
                <div class="col-md-4 col-lg-2 fade-in-up">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="{{ $category->icon ?? 'fas fa-cog' }}"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $category->name }}</h5>
                        <p class="text-muted small">{{ Str::limit($category->description, 60) }}</p>
                        <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-outline btn-sm mt-3">
                            <i class="fas fa-arrow-left me-1"></i>
                            استكشف
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="glass-card p-5">
                        <i class="fas fa-folder-open fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">لا توجد أقسام متاحة حالياً</h4>
                        <p class="text-muted">سيتم إضافة الأقسام قريباً</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($categories->count() > 0)
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-th-large me-2"></i>
                        عرض جميع الأقسام
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="text-gradient fw-bold mb-3">{{ __('messages.featured_services') }}</h2>
                <p class="text-muted fs-5">أحدث الخدمات المطلوبة من عملائنا</p>
            </div>
        </div>

        <div class="row g-4">
            @php
                $services = \App\Models\Service::where('is_active', true)->with(['category', 'user'])->latest()->take(6)->get();
            @endphp

            @forelse($services as $service)
                <div class="col-md-6 col-lg-4 fade-in-up">
                    <div class="card h-100 border-0 shadow-custom">
                        @if($service->image)
                            <img src="{{ Storage::url($service->image) }}" class="card-img-top" alt="{{ $service->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-white opacity-50"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-primary">{{ $service->category->name }}</span>
                                <small class="text-muted">{{ $service->created_at->diffForHumans() }}</small>
                            </div>
                            <h5 class="card-title fw-bold mb-2">{{ Str::limit($service->title, 50) }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($service->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $service->location }}
                                </small>
                                <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline btn-sm">
                                    عرض التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="glass-card p-5">
                        <i class="fas fa-concierge-bell fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">لا توجد خدمات متاحة حالياً</h4>
                        <p class="text-muted">كن أول من يطلب خدمة!</p>
                        <a href="{{ route('categories.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            طلب خدمة
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($services->count() > 0)
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-list me-2"></i>
                        عرض جميع الخدمات
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-gradient text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="glass p-4 rounded-4">
                    <i class="fas fa-users fa-3x mb-3 opacity-75"></i>
                    <h3 class="fw-bold mb-2">{{ \App\Models\User::count() }}+</h3>
                    <p class="mb-0 opacity-75">مستخدم مسجل</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="glass p-4 rounded-4">
                    <i class="fas fa-concierge-bell fa-3x mb-3 opacity-75"></i>
                    <h3 class="fw-bold mb-2">{{ \App\Models\Service::count() }}+</h3>
                    <p class="mb-0 opacity-75">خدمة مطلوبة</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="glass p-4 rounded-4">
                    <i class="fas fa-th-large fa-3x mb-3 opacity-75"></i>
                    <h3 class="fw-bold mb-2">{{ \App\Models\Category::count() }}+</h3>
                    <p class="mb-0 opacity-75">قسم متاح</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="glass p-4 rounded-4">
                    <i class="fas fa-handshake fa-3x mb-3 opacity-75"></i>
                    <h3 class="fw-bold mb-2">{{ \App\Models\ServiceOffer::count() }}+</h3>
                    <p class="mb-0 opacity-75">عرض مقدم</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="glass-card p-5 rounded-4">
                    <h2 class="text-gradient fw-bold mb-4">ابدأ رحلتك معنا اليوم</h2>
                    <p class="text-muted fs-5 mb-4">انضم إلى مجتمعنا المتنامي واستفد من أفضل الخدمات</p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        @auth
                            <a href="{{ route('services.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-rocket me-2"></i>
                                استكشف الخدمات
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                انضم الآن
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                تسجيل الدخول
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
