@extends('layouts.admin')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                    <p class="text-muted mb-0">إجمالي المستخدمين</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['total_categories'] }}</h3>
                    <p class="text-muted mb-0">إجمالي الأقسام</p>
                </div>
                <div class="icon">
                    <i class="fas fa-th-large"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['total_services'] }}</h3>
                    <p class="text-muted mb-0">إجمالي الخدمات</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cogs"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                    <p class="text-muted mb-0">إجمالي الطلبات</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['active_services'] }}</h3>
                    <p class="text-muted mb-0">الخدمات النشطة</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['featured_services'] }}</h3>
                    <p class="text-muted mb-0">الخدمات المميزة</p>
                </div>
                <div class="icon">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['pending_orders'] }}</h3>
                    <p class="text-muted mb-0">الطلبات المعلقة</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['completed_orders'] }}</h3>
                    <p class="text-muted mb-0">الطلبات المكتملة</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> إضافة قسم جديد
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.sub_categories.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-layer-group"></i> إضافة قسم فرعي
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.cities.create') }}" class="btn btn-info w-100">
                            <i class="fas fa-city"></i> إضافة مدينة جديدة
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.system-settings.index') }}" class="btn btn-warning w-100">
                            <i class="fas fa-cog"></i> إعدادات النظام
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Status -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-server"></i> حالة النظام</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>إصدار PHP:</span>
                    <span class="badge bg-success">{{ phpversion() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>إصدار Laravel:</span>
                    <span class="badge bg-info">{{ app()->version() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>حالة قاعدة البيانات:</span>
                    <span class="badge bg-success">متصل</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>الذاكرة المستخدمة:</span>
                    <span class="badge bg-warning">{{ number_format(memory_get_usage(true) / 1024 / 1024, 2) }} MB</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line"></i> إحصائيات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>مزودي الخدمات:</span>
                    <span class="badge bg-primary">{{ $stats['total_providers'] ?? 0 }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>العروض المعلقة:</span>
                    <span class="badge bg-warning">{{ $stats['total_offers'] ?? 0 }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>الأقسام الفرعية:</span>
                    <span class="badge bg-info">{{ $stats['total_sub_categories'] ?? 0 }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>الحقول المخصصة:</span>
                    <span class="badge bg-secondary">{{ $stats['total_fields'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Data -->
<div class="row">
    <!-- Recent Users -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">أحدث المستخدمين</h5>
            </div>
            <div class="card-body">
                @forelse($recentUsers as $user)
                <div class="d-flex align-items-center mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=667eea&color=fff"
                         alt="Avatar" class="rounded-circle me-3" width="40" height="40">
                    <div>
                        <h6 class="mb-0">{{ $user->name }}</h6>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted">لا يوجد مستخدمين حديثين</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Services -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">أحدث الخدمات</h5>
            </div>
            <div class="card-body">
                @forelse($recentServices as $service)
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $service->image_url }}" alt="Service" class="rounded me-3" width="40" height="40" style="object-fit: cover;">
                    <div>
                        <h6 class="mb-0">{{ Str::limit($service->title, 30) }}</h6>
                        <small class="text-muted">{{ $service->category->name }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted">لا توجد خدمات حديثة</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">أحدث الطلبات</h5>
            </div>
            <div class="card-body">
                @forelse($recentOrders as $order)
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-shopping-cart text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ Str::limit($order->service->title, 30) }}</h6>
                        <small class="text-muted">{{ $order->status_text }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted">لا توجد طلبات حديثة</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">إجراءات سريعة</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-primary w-100">
                            <i class="fas fa-th-large"></i> إدارة الأقسام
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.system-settings.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-cog"></i> إعدادات النظام
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('services.index') }}" class="btn btn-info w-100">
                            <i class="fas fa-cogs"></i> عرض الخدمات
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('categories.index') }}" class="btn btn-warning w-100">
                            <i class="fas fa-eye"></i> عرض الأقسام
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Categories -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">الأقسام الأكثر نشاطاً</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($topCategories as $category)
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="{{ $category->icon }} text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $category->name }}</h6>
                                <small class="text-muted">{{ $category->services_count }} خدمة</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted">لا توجد أقسام متاحة</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
