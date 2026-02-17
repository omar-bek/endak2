@extends('layouts.admin')

@section('title', 'إدارة الأقسام والمدن')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt"></i>
                        إدارة الأقسام والمدن
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.category-cities.statistics') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-bar"></i>
                            الإحصائيات
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- الأقسام -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-list"></i>
                                        الأقسام المتاحة
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        @foreach($categories as $category)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $category->name }}</h6>
                                                    <small class="text-muted">
                                                        {{ $category->children->count() }} أقسام فرعية
                                                    </small>
                                                </div>
                                                <div>
                                                    <a href="{{ route('admin.category-cities.show', $category->id) }}"
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-cog"></i>
                                                        إدارة المدن
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المدن -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-city"></i>
                                        المدن المتاحة
                                    </h5>
                                </div>
                                                                <div class="card-body">
                                    <!-- Debug Info -->
                                    <div class="alert alert-info">
                                        <strong>عدد المدن:</strong> {{ $cities->count() }}<br>
                                        <strong>أول مدينة:</strong> {{ $cities->first()->name_ar ?? 'NULL' }}<br>
                                        <strong>أول مدينة EN:</strong> {{ $cities->first()->name_en ?? 'NULL' }}
                                    </div>

                                    <div class="row">
                                        @if($cities->count() > 0)
                                            @foreach($cities as $city)
                                                <div class="col-md-6 mb-2">
                                                    <div class="card border-{{ $city->is_active ? 'success' : 'secondary' }}" style="background-color: #f8f9fa;">
                                                        <div class="card-body p-2">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="text-dark font-weight-bold" style="color: #000 !important; font-size: 14px;">{{ $city->name_ar ?? $city->name_en ?? 'اسم غير محدد' }}</span>
                                                                <span class="badge badge-{{ $city->is_active ? 'success' : 'secondary' }}">
                                                                    {{ $city->is_active ? 'مفعلة' : 'معطلة' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12">
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    لا توجد مدن في قاعدة البيانات
                                                </div>
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
</div>
@endsection
