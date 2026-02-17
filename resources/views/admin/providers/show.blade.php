@extends('layouts.admin')

@section('title', 'عرض مزود الخدمة')
@section('page-title', 'عرض مزود الخدمة')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.providers.index') }}">إدارة مزودي الخدمات</a></li>
            <li class="breadcrumb-item active">عرض مزود الخدمة</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Provider Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-user-tie"></i> معلومات مزود الخدمة</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ $provider->name }}&background=667eea&color=fff"
                                 alt="Avatar" class="rounded-circle" width="120" height="120">
                            <h4 class="mt-3 mb-1">{{ $provider->name }}</h4>
                            <p class="text-muted mb-2">{{ $provider->email }}</p>

                            <div class="mb-3">
                                @if($provider->is_active)
                                    <span class="badge bg-success fs-6">نشط</span>
                                @else
                                    <span class="badge bg-danger fs-6">معطل</span>
                                @endif

                                @if($provider->is_verified)
                                    <span class="badge bg-success fs-6 ms-2">محقق</span>
                                @else
                                    <span class="badge bg-warning fs-6 ms-2">غير محقق</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            @if($provider->providerProfile)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>نبذة:</strong>
                                        <p class="text-muted mt-1">{{ $provider->providerProfile->about ?? 'غير محدد' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <strong>رقم الهاتف:</strong>
                                        <span class="ms-2">{{ $provider->providerProfile->phone ?? 'غير محدد' }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>العنوان:</strong>
                                        <span class="ms-2">{{ $provider->providerProfile->address ?? 'غير محدد' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>الأقسام:</strong>
                                        @if($provider->providerProfile->activeCategories && $provider->providerProfile->activeCategories->count() > 0)
                                            <div class="mt-2">
                                                @foreach($provider->providerProfile->activeCategories as $category)
                                                    <span class="badge bg-primary me-1">{{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted ms-2">غير محدد</span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <strong>المدن:</strong>
                                        @if($provider->providerProfile->activeCities && $provider->providerProfile->activeCities->count() > 0)
                                            <div class="mt-2">
                                                @foreach($provider->providerProfile->activeCities as $city)
                                                    <span class="badge bg-secondary me-1">{{ app()->getLocale() == 'ar' ? $city->name_ar : $city->name_en }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted ms-2">غير محدد</span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <strong>تاريخ التسجيل:</strong>
                                        <span class="ms-2">{{ $provider->created_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                هذا المستخدم لم يكمل ملف مزود الخدمة بعد.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Provider Services -->
            @if($provider->services && $provider->services->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-concierge-bell"></i> خدمات مزود الخدمة ({{ $provider->services->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>العنوان</th>
                                    <th>القسم</th>
                                    <th>السعر</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($provider->services as $service)
                                <tr>
                                    <td>{{ Str::limit($service->title, 30) }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ app()->getLocale() == 'ar' ? $service->category->name : $service->category->name_en }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">{{ $service->formatted_price }}</span>
                                    </td>
                                    <td>
                                        @if($service->is_active)
                                            <span class="badge bg-success">نشطة</span>
                                        @else
                                            <span class="badge bg-danger">معطلة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $service->created_at->format('Y-m-d') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.services.show', $service->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Provider Offers -->
            @if($provider->offers && $provider->offers->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-handshake"></i> عروض مزود الخدمة ({{ $provider->offers->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>الخدمة</th>
                                    <th>السعر المقترح</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($provider->offers as $offer)
                                <tr>
                                    <td>{{ Str::limit($offer->service->title, 30) }}</td>
                                    <td>
                                        <span class="fw-bold text-success">{{ $offer->formatted_price }}</span>
                                    </td>
                                    <td>
                                        @if($offer->status === 'pending')
                                            <span class="badge bg-warning">في الانتظار</span>
                                        @elseif($offer->status === 'accepted')
                                            <span class="badge bg-success">مقبول</span>
                                        @elseif($offer->status === 'rejected')
                                            <span class="badge bg-danger">مرفوض</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $offer->created_at->format('Y-m-d') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.service-offers.show', $offer->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> الإجراءات</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.show', $provider->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-user"></i> عرض الملف الشخصي
                        </a>

                        <form action="{{ route('admin.providers.verify', $provider->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $provider->is_verified ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $provider->is_verified ? 'times' : 'check' }}"></i>
                                {{ $provider->is_verified ? 'إلغاء التحقق' : 'التحقق من' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.users.toggle-status', $provider->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $provider->is_active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $provider->is_active ? 'pause' : 'play' }}"></i>
                                {{ $provider->is_active ? 'تعطيل المستخدم' : 'تفعيل المستخدم' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> إحصائيات مزود الخدمة</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>الخدمات:</span>
                        <span class="badge bg-primary">{{ $provider->services->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>العروض:</span>
                        <span class="badge bg-info">{{ $provider->offers->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>العروض المقبولة:</span>
                        <span class="badge bg-success">{{ $provider->offers->where('status', 'accepted')->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>أيام العضوية:</span>
                        <span class="badge bg-secondary">{{ $provider->created_at->diffInDays(now()) }}</span>
                    </div>
                </div>
            </div>

            <!-- Profile Completion -->
            @if($provider->providerProfile)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> اكتمال الملف الشخصي</h5>
                </div>
                <div class="card-body">
                    @php
                        $completion = 0;
                        $total = 0;

                        if ($provider->providerProfile->about) { $completion++; } $total++;
                        if ($provider->providerProfile->phone) { $completion++; } $total++;
                        if ($provider->providerProfile->address) { $completion++; } $total++;
                        if ($provider->providerProfile->activeCategories && $provider->providerProfile->activeCategories->count() > 0) { $completion++; } $total++;
                        if ($provider->providerProfile->activeCities && $provider->providerProfile->activeCities->count() > 0) { $completion++; } $total++;

                        $percentage = $total > 0 ? round(($completion / $total) * 100) : 0;
                    @endphp

                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>نسبة الاكتمال:</span>
                            <span class="fw-bold">{{ $percentage }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-{{ $percentage >= 80 ? 'success' : ($percentage >= 60 ? 'warning' : 'danger') }}"
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    <small class="text-muted">
                        {{ $completion }} من {{ $total }} حقل مكتمل
                    </small>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
