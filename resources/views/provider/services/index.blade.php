@extends('layouts.app')

@section('title', 'إدارة الخدمات')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة الخدمات</h2>
        <a href="{{ route('provider.services.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>إضافة خدمة جديدة
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($services->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>العنوان</th>
                            <th>القسم</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>
                                <img src="{{ $service->image_url }}" alt="{{ $service->title }}"
                                     class="rounded" width="50" height="50" style="object-fit: cover;">
                            </td>
                            <td>
                                <h6 class="mb-0">{{ $service->title }}</h6>
                                <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $service->category->name }}</span>
                            </td>
                            <td>
                                <span class="text-primary fw-bold">{{ $service->formatted_price }}</span>
                            </td>
                            <td>
                                @if($service->is_active)
                                <span class="badge bg-success">نشط</span>
                                @else
                                <span class="badge bg-danger">غير نشط</span>
                                @endif

                                @if($service->is_featured)
                                <span class="badge bg-warning ms-1">مميز</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $service->created_at->format('Y/m/d') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('services.show', $service->slug) }}"
                                       class="btn btn-sm btn-outline-info" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('provider.services.edit', $service) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form method="POST" action="{{ route('provider.services.toggle-status', $service) }}"
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('provider.services.destroy', $service) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $services->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-cogs text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">لا توجد خدمات</h4>
                <p class="text-muted">ابدأ بإضافة خدمة جديدة</p>
                <a href="{{ route('provider.services.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>إضافة خدمة جديدة
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
