@extends('layouts.admin')

@section('title', 'عرض المستخدم')
@section('page-title', 'عرض المستخدم')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">إدارة المستخدمين</a></li>
            <li class="breadcrumb-item active">عرض المستخدم</li>
        </ol>
    </nav>

    <div class="row">
        <!-- User Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-user"></i> معلومات المستخدم</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=667eea&color=fff"
                                 alt="{{ $user->name }}" class="rounded-circle" width="120" height="120">
                            <h4 class="mt-3 mb-1">{{ $user->name }}</h4>
                            <p class="text-muted mb-2">{{ $user->email }}</p>

                            @if($user->user_type == 'admin')
                                <span class="badge bg-danger fs-6">مدير</span>
                            @elseif($user->user_type == 'customer')
                                <span class="badge bg-primary fs-6">مستخدم عادي</span>
                            @elseif($user->user_type == 'provider')
                                <span class="badge bg-success fs-6">مزود خدمة</span>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>الاسم:</strong>
                                        <span class="ms-2">{{ $user->name }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>البريد الإلكتروني:</strong>
                                        <span class="ms-2">{{ $user->email }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>رقم الهاتف:</strong>
                                        <span class="ms-2">{{ $user->phone ?? 'غير محدد' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>الحالة:</strong>
                                        @if($user->is_active)
                                            <span class="badge bg-success ms-2">نشط</span>
                                        @else
                                            <span class="badge bg-danger ms-2">معطل</span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <strong>تاريخ التسجيل:</strong>
                                        <span class="ms-2">{{ $user->created_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>آخر تحديث:</strong>
                                        <span class="ms-2">{{ $user->updated_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($user->providerProfile)
                            <div class="mt-4">
                                <h6><i class="fas fa-user-tie"></i> معلومات مزود الخدمة</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <strong>نبذة:</strong>
                                            <span class="ms-2">{{ $user->providerProfile->about ?? 'غير محدد' }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>العنوان:</strong>
                                            <span class="ms-2">{{ $user->providerProfile->address ?? 'غير محدد' }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>رقم الهاتف:</strong>
                                            <span class="ms-2">{{ $user->providerProfile->phone ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <strong>الأقسام المختارة:</strong>
                                            @if($user->providerCategories && $user->providerCategories->where('is_active', true)->count() > 0)
                                                <div class="mt-1">
                                                    @foreach($user->providerCategories->where('is_active', true) as $providerCategory)
                                                        <span class="badge bg-primary me-1 mb-1" title="{{ app()->getLocale() == 'ar' ? $providerCategory->category->description : $providerCategory->category->description_en }}">
                                                            {{ app()->getLocale() == 'ar' ? $providerCategory->category->name : $providerCategory->category->name_en }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted ms-2">غير محدد</span>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <strong>المدن المختارة:</strong>
                                            @if($user->providerCities && $user->providerCities->where('is_active', true)->count() > 0)
                                                <div class="mt-1">
                                                    @foreach($user->providerCities->where('is_active', true) as $providerCity)
                                                        <span class="badge bg-secondary me-1 mb-1" title="{{ app()->getLocale() == 'ar' ? $providerCity->city->name_ar : $providerCity->city->name_en }}">
                                                            {{ app()->getLocale() == 'ar' ? $providerCity->city->name_ar : $providerCity->city->name_en }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-muted ms-2">غير محدد</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Services -->
            @if($user->services && $user->services->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-concierge-bell"></i> خدمات المستخدم ({{ $user->services->count() }})</h5>
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
                                @foreach($user->services as $service)
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

            <!-- User Offers -->
            @if($user->offers && $user->offers->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-handshake"></i> عروض المستخدم ({{ $user->offers->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>الخدمة</th>
                                    <th>السعر</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->offers as $offer)
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
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                {{ $user->is_active ? 'تعطيل المستخدم' : 'تفعيل المستخدم' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.users.toggle-role', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fas fa-user-cog"></i>
                                تغيير الدور
                            </button>
                        </form>
                        @endif

                        @if($user->providerProfile)
                        <a href="{{ route('admin.providers.show', $user->id) }}" class="btn btn-outline-info">
                            <i class="fas fa-user-tie"></i> عرض ملف مزود الخدمة
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> إحصائيات المستخدم</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>الخدمات:</span>
                        <span class="badge bg-primary">{{ $user->services->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>العروض:</span>
                        <span class="badge bg-info">{{ $user->offers->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>أيام العضوية:</span>
                        <span class="badge bg-success">{{ $user->created_at->diffInDays(now()) }}</span>
                    </div>
                </div>
            </div>

            <!-- Provider Categories & Cities -->
            @if($user->providerCategories || $user->providerCities)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> الأقسام والمدن المختارة</h5>
                </div>
                <div class="card-body">
                    @if($user->providerCategories && $user->providerCategories->where('is_active', true)->count() > 0)
                    <div class="mb-3">
                        <strong class="text-primary">الأقسام:</strong>
                        <div class="mt-2">
                            @foreach($user->providerCategories->where('is_active', true) as $providerCategory)
                                <span class="badge bg-primary me-1 mb-1" title="{{ app()->getLocale() == 'ar' ? $providerCategory->category->description : $providerCategory->category->description_en }}">
                                    {{ app()->getLocale() == 'ar' ? $providerCategory->category->name : $providerCategory->category->name_en }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($user->providerCities && $user->providerCities->where('is_active', true)->count() > 0)
                    <div class="mb-3">
                        <strong class="text-secondary">المدن:</strong>
                        <div class="mt-2">
                            @foreach($user->providerCities->where('is_active', true) as $providerCity)
                                <span class="badge bg-secondary me-1 mb-1" title="{{ app()->getLocale() == 'ar' ? $providerCity->city->name_ar : $providerCity->city->name_en }}">
                                    {{ app()->getLocale() == 'ar' ? $providerCity->city->name_ar : $providerCity->city->name_en }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if((!$user->providerCategories || $user->providerCategories->where('is_active', true)->count() == 0) &&
                        (!$user->providerCities || $user->providerCities->where('is_active', true)->count() == 0))
                    <div class="text-center text-muted">
                        <i class="fas fa-info-circle"></i>
                        <p class="mb-0">لم يتم اختيار أي أقسام أو مدن</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
