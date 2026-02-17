@extends('layouts.admin')

@section('title', 'إدارة الخدمات')
@section('page-title', 'إدارة الخدمات')

@section('content')
<div class="container-fluid">
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="ابحث في الخدمات..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">جميع الأقسام</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>نشطة</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>معطلة</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Services Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-concierge-bell"></i> قائمة الخدمات</h3>
            <div class="card-tools">
                <span class="badge bg-primary">{{ $services->total() }} خدمة</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>الصورة</th>
                            <th>العنوان</th>
                            <th>القسم</th>
                            <th>القسم الفرعي</th>
                            <th>المستخدم</th>
                            <th>المدينة</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>
                                @if($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}"
                                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light text-center rounded" style="width: 50px; height: 50px; line-height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ Str::limit($service->title, 30) }}</div>
                                <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ app()->getLocale() == 'ar' ? $service->category->name : $service->category->name_en }}</span>
                            </td>
                            <td>
                                @if($service->subCategory)
                                    <span class="badge bg-info">{{ app()->getLocale() == 'ar' ? $service->subCategory->name_ar : $service->subCategory->name_en }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name={{ $service->user->name }}&background=667eea&color=fff"
                                         alt="Avatar" class="rounded-circle me-2" width="30" height="30">
                                    <div>
                                        <div class="fw-bold">{{ $service->user->name }}</div>
                                        <small class="text-muted">{{ $service->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($service->city)
                                    <span class="badge bg-secondary">{{ app()->getLocale() == 'ar' ? $service->city->name_ar : $service->city->name_en }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
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
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.services.show', $service->id) }}" class="btn btn-info" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline-primary" title="عرض في الموقع">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <form action="{{ route('admin.services.toggle-status', $service->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning" title="{{ $service->is_active ? 'تعطيل' : 'تفعيل' }}">
                                            <i class="fas fa-{{ $service->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" style="display:inline-block"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">لا توجد خدمات</h5>
                                <p class="text-muted">لم يتم إنشاء أي خدمات بعد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter change
    const filterForm = document.querySelector('form[method="GET"]');
    const filterInputs = filterForm.querySelectorAll('select, input[type="text"]');

    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            filterForm.submit();
        });
    });
});
</script>
@endsection
