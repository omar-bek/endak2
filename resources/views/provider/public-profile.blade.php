@extends('layouts.app')

@section('title', 'الملف الشخصي - ' . $provider->name)

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user-tie"></i> ملف مزود الخدمة الشخصي
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mb-4">
                            <div class="col-md-3 text-center">
                                @if ($provider->image && file_exists(public_path('storage/' . $provider->image)))
                                    <img src="{{ asset('storage/' . $provider->image) }}" alt="{{ $provider->name }}"
                                        class="rounded-circle mb-3" width="120" height="120"
                                        style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center bg-primary text-white"
                                        style="width: 120px; height: 120px; font-size: 48px; font-weight: bold;">
                                        {{ strtoupper(substr($provider->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h3 class="mb-2">{{ $provider->name }}</h3>
                                @if ($profile && $profile->rating)
                                    <div class="mb-2">
                                        <div class="text-warning mb-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= round($profile->rating) ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="text-muted">({{ number_format($profile->rating, 1) }})</span>
                                        @if ($profile->completed_services)
                                            <span class="text-muted ms-2">• {{ $profile->completed_services }} خدمة
                                                مكتملة</span>
                                        @endif
                                    </div>
                                @endif
                                @if ($profile && $profile->is_verified)
                                    <span class="badge bg-success mb-2">
                                        <i class="fas fa-check-circle"></i> موثق
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if ($profile && $profile->bio)
                            <div class="mb-4">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-info-circle"></i> نبذة عني
                                </h6>
                                <p class="text-muted">{{ $profile->bio }}</p>
                            </div>
                        @endif

                        <div class="row mb-4">
                            @if ($profile && $profile->phone)
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-primary mb-1">
                                        <i class="fas fa-phone"></i> رقم الهاتف
                                    </h6>
                                    <p class="text-muted">{{ $profile->phone }}</p>
                                </div>
                            @endif
                            @if ($profile && $profile->address)
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-primary mb-1">
                                        <i class="fas fa-map-marker-alt"></i> العنوان
                                    </h6>
                                    <p class="text-muted">{{ $profile->address }}</p>
                                </div>
                            @endif
                        </div>

                        @if (Auth::check())
                            <div class="mb-3">
                                <a href="{{ route('messages.show', $provider->id) }}" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-envelope me-1"></i> إرسال رسالة
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- الأقسام -->
                @if ($profile && isset($activeCategories) && $activeCategories->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-folder"></i> الأقسام التي يعمل فيها
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $groupedCategories = $activeCategories->groupBy('category_id');
                            @endphp
                            <div class="row">
                                @foreach ($groupedCategories as $categoryId => $providerCategories)
                                    @php
                                        $firstCategory = $providerCategories->first();
                                        $mainCategory =
                                            $firstCategory && $firstCategory->category
                                                ? $firstCategory->category
                                                : null;
                                        $subCategories = $providerCategories->filter(function ($item) {
                                            return $item->sub_category_id !== null && $item->subCategory !== null;
                                        });
                                        $hasSubCategories = $subCategories->count() > 0;
                                    @endphp
                                    @if ($mainCategory && $firstCategory)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-primary">
                                                <div class="card-body">
                                                    <h6 class="card-title">
                                                        <i
                                                            class="{{ isset($mainCategory->icon) ? $mainCategory->icon : 'fas fa-folder' }} text-primary"></i>
                                                        {{ app()->getLocale() == 'ar' ? $mainCategory->name ?? 'قسم غير محدد' : $mainCategory->name_en ?? 'قسم غير محدد' }}
                                                    </h6>
                                                    @if ($hasSubCategories)
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block mb-1">الأقسام الفرعية:</small>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @foreach ($subCategories as $subCat)
                                                                    @if ($subCat->subCategory && isset($subCat->subCategory->name_ar))
                                                                        <span class="badge bg-secondary">
                                                                            {{ app()->getLocale() == 'ar' ? $subCat->subCategory->name_ar : $subCat->subCategory->name_en ?? $subCat->subCategory->name_ar }}
                                                                        </span>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($firstCategory && $firstCategory->description)
                                                        <p class="card-text small">{{ $firstCategory->description }}</p>
                                                    @endif
                                                    <div class="row">
                                                        @if ($firstCategory && $firstCategory->hourly_rate)
                                                            <div class="col-6">
                                                                <small class="text-muted">السعر بالساعة:</small>
                                                                <br><strong>{{ number_format($firstCategory->hourly_rate, 2) }}
                                                                    ريال</strong>
                                                            </div>
                                                        @endif
                                                        @if ($firstCategory && $firstCategory->experience_years)
                                                            <div class="col-6">
                                                                <small class="text-muted">سنوات الخبرة:</small>
                                                                <br><strong>{{ $firstCategory->experience_years }}
                                                                    سنوات</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- المدن -->
                @if ($profile && isset($activeCities) && $activeCities->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-map-marker-alt"></i> المدن التي يعمل فيها
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($activeCities as $providerCity)
                                    <span class="badge bg-info p-2">
                                        {{ $providerCity->city ? (app()->getLocale() == 'ar' ? $providerCity->city->name_ar ?? 'غير محدد' : $providerCity->city->name_en ?? 'غير محدد') : 'غير محدد' }}
                                    </span>
                                    @if ($providerCity->notes)
                                        <small class="text-muted d-block">{{ $providerCity->notes }}</small>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- الخدمات -->
                @if ($services && $services->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-concierge-bell"></i> الخدمات ({{ $services->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($services as $service)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-warning">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <a href="{{ route('services.show', $service->slug) }}"
                                                        class="text-dark">
                                                        {{ $service->title }}
                                                    </a>
                                                </h6>
                                                <p class="card-text small text-muted">
                                                    {{ Str::limit($service->description, 100) }}</p>
                                                @if ($service->price)
                                                    <p class="mb-0">
                                                        <strong
                                                            class="text-success">{{ number_format($service->price, 2) }}
                                                            ريال</strong>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- التقييمات -->
                @if ($ratings && $ratings->count() > 0)
                    <div class="card shadow mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-star"></i> التقييمات ({{ $ratings->count() }})
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach ($ratings as $rating)
                                <div class="border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $rating->service->user->name ?? 'مستخدم' }}</h6>
                                            <small class="text-muted">{{ $rating->service->title }}</small>
                                        </div>
                                        <div class="text-warning">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $rating->rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    @if ($rating->review)
                                        <p class="text-muted small mb-0">{{ $rating->review }}</p>
                                    @endif
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $rating->created_at->format('Y-m-d') }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <!-- الإحصائيات -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar"></i> الإحصائيات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-primary">
                                        {{ isset($activeCategories) ? $activeCategories->count() : 0 }}</h4>
                                    <small class="text-muted">الأقسام</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-success">{{ isset($activeCities) ? $activeCities->count() : 0 }}</h4>
                                    <small class="text-muted">المدن</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-info">{{ $profile ? $profile->completed_services ?? 0 : 0 }}</h4>
                                    <small class="text-muted">الخدمات المكتملة</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-warning">
                                        {{ number_format($profile ? $profile->rating ?? 0 : 0, 1) }}</h4>
                                    <small class="text-muted">التقييم</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
