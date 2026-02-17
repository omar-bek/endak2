@extends('layouts.admin')

@section('title', 'إدارة المدن')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-city"></i>
                            إدارة المدن
                        </h3>
                        <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            إضافة مدينة جديدة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="fas fa-check"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم بالعربية</th>
                                    <th>الاسم بالإنجليزية</th>
                                    <th>الوصف</th>
                                    <th>الحالة</th>
                                    <th>ترتيب</th>
                                    <th>عدد الخدمات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cities as $city)
                                    <tr>
                                        <td>{{ $city->id }}</td>
                                        <td>
                                            <span class="text-dark font-weight-bold">{{ $city->name_ar }}</span>
                                        </td>
                                        <td>{{ $city->name_en }}</td>
                                        <td>
                                            @if($city->description_ar)
                                                <span class="text-muted">{{ Str::limit($city->description_ar, 50) }}</span>
                                            @else
                                                <span class="text-muted">لا يوجد وصف</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($city->is_active)
                                                <span class="badge badge-success">مفعلة</span>
                                            @else
                                                <span class="badge badge-secondary">معطلة</span>
                                            @endif
                                        </td>
                                        <td>{{ $city->sort_order }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $city->services()->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.cities.show', $city->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.cities.edit', $city->id) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.cities.destroy', $city->id) }}"
                                                      method="POST"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه المدينة؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="حذف"
                                                            {{ $city->services()->count() > 0 ? 'disabled' : '' }}>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i>
                                                لا توجد مدن في قاعدة البيانات
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($cities->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $cities->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .badge {
        font-size: 0.8rem;
    }
</style>
@endpush
