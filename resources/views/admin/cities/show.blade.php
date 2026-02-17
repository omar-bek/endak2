@extends('layouts.admin')

@section('title', 'تفاصيل المدينة - ' . $city->name_ar)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-city"></i>
                            تفاصيل المدينة: {{ $city->name_ar }}
                        </h3>
                        <div>
                            <a href="{{ route('admin.cities.edit', $city->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i>
                                تعديل
                            </a>
                            <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right"></i>
                                العودة للقائمة
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary">
                                    <i class="fas fa-font"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الاسم بالعربية</span>
                                    <span class="info-box-number">{{ $city->name_ar }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-font"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الاسم بالإنجليزية</span>
                                    <span class="info-box-number">{{ $city->name_en }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الوصف بالعربية</span>
                                    <span class="info-box-number">
                                        {{ $city->description_ar ?: 'لا يوجد وصف' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الوصف بالإنجليزية</span>
                                    <span class="info-box-number">
                                        {{ $city->description_en ?: 'لا يوجد وصف' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary">
                                    <i class="fas fa-sort-numeric-up"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">ترتيب العرض</span>
                                    <span class="info-box-number">{{ $city->sort_order }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon {{ $city->is_active ? 'bg-success' : 'bg-danger' }}">
                                    <i class="fas fa-toggle-{{ $city->is_active ? 'on' : 'off' }}"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">الحالة</span>
                                    <span class="info-box-number">
                                        {{ $city->is_active ? 'مفعلة' : 'معطلة' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-list"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">عدد الخدمات</span>
                                    <span class="info-box-number">{{ $city->services()->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-list"></i>
                                        الخدمات المرتبطة بالمدينة
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($city->services()->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>عنوان الخدمة</th>
                                                        <th>المزود</th>
                                                        <th>الحالة</th>
                                                        <th>تاريخ الإنشاء</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($city->services()->latest()->get() as $service)
                                                        <tr>
                                                            <td>{{ $service->id }}</td>
                                                            <td>{{ $service->title }}</td>
                                                            <td>{{ $service->provider->name }}</td>
                                                            <td>
                                                                @if($service->is_active)
                                                                    <span class="badge badge-success">مفعلة</span>
                                                                @else
                                                                    <span class="badge badge-secondary">معطلة</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $service->created_at->format('Y-m-d') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            لا توجد خدمات مرتبطة بهذه المدينة
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .info-box {
        margin-bottom: 1rem;
    }

    .info-box-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .info-box-content {
        padding: 1rem;
    }

    .info-box-text {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .info-box-number {
        font-size: 1.2rem;
        font-weight: bold;
        color: #495057;
    }
</style>
@endpush
