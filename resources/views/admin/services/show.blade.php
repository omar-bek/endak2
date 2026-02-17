@extends('layouts.admin')

@section('title', 'عرض الخدمة')
@section('page-title', 'عرض الخدمة')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.services.index') }}">إدارة الخدمات</a></li>
            <li class="breadcrumb-item active">عرض الخدمة</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Service Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-concierge-bell"></i> تفاصيل الخدمة</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            @if($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}"
                                     class="img-fluid rounded" style="max-width: 200px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="width: 200px; height: 200px;">
                                    <i class="fas fa-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3 class="mb-3">{{ $service->title }}</h3>
                            <p class="text-muted mb-3">{{ $service->description }}</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>القسم:</strong>
                                        <span class="badge bg-primary ms-2">{{ app()->getLocale() == 'ar' ? $service->category->name : $service->category->name_en }}</span>
                                    </div>
                                    @if($service->subCategory)
                                    <div class="mb-2">
                                        <strong>القسم الفرعي:</strong>
                                        <span class="badge bg-info ms-2">{{ app()->getLocale() == 'ar' ? $service->subCategory->name_ar : $service->subCategory->name_en }}</span>
                                    </div>
                                    @endif
                                    <div class="mb-2">
                                        <strong>المدينة:</strong>
                                        @if($service->city)
                                            <span class="badge bg-secondary ms-2">{{ app()->getLocale() == 'ar' ? $service->city->name_ar : $service->city->name_en }}</span>
                                        @else
                                            <span class="text-muted ms-2">غير محدد</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>السعر:</strong>
                                        <span class="h5 text-success ms-2">{{ $service->formatted_price }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>الحالة:</strong>
                                        @if($service->is_active)
                                            <span class="badge bg-success ms-2">نشطة</span>
                                        @else
                                            <span class="badge bg-danger ms-2">معطلة</span>
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <strong>التاريخ:</strong>
                                        <span class="text-muted ms-2">{{ $service->created_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($service->custom_fields)
                            <div class="mt-3">
                                <h6>الحقول المخصصة:</h6>
                                <div class="row">
                                    @foreach($service->custom_fields as $field => $value)
                                    <div class="col-md-6 mb-2">
                                        <strong>{{ $field }}:</strong>
                                        <span class="text-muted ms-2">
                                            @if(is_array($value))
                                                {{ implode(', ', $value) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Offers -->
            @if($service->offers && $service->offers->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-handshake"></i> عروض الخدمة ({{ $service->offers->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>المزود</th>
                                    <th>السعر</th>
                                    <th>الملاحظات</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($service->offers as $offer)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ $offer->provider->name }}&background=667eea&color=fff"
                                                 alt="Avatar" class="rounded-circle me-2" width="30" height="30">
                                            <div>
                                                <div class="fw-bold">{{ $offer->provider->name }}</div>
                                                <small class="text-muted">{{ $offer->provider->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">{{ $offer->formatted_price }}</span>
                                    </td>
                                    <td>
                                        @if($offer->notes)
                                            {{ Str::limit($offer->notes, 50) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
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
            <!-- User Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> معلومات المستخدم</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ $service->user->name }}&background=667eea&color=fff"
                             alt="Avatar" class="rounded-circle" width="80" height="80">
                        <h5 class="mt-2 mb-1">{{ $service->user->name }}</h5>
                        <p class="text-muted mb-2">{{ $service->user->email }}</p>
                        <span class="badge bg-primary">{{ $service->user->role_name }}</span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.show', $service->user->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i> عرض الملف الشخصي
                        </a>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> الإجراءات</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-external-link-alt"></i> عرض في الموقع
                        </a>

                        <form action="{{ route('admin.services.toggle-status', $service->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $service->is_active ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $service->is_active ? 'pause' : 'play' }}"></i>
                                {{ $service->is_active ? 'تعطيل الخدمة' : 'تفعيل الخدمة' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> حذف الخدمة
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
