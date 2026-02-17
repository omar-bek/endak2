@extends('layouts.admin')

@section('title', 'إدارة مزودي الخدمات')
@section('page-title', 'إدارة مزودي الخدمات')

@section('content')
<div class="container-fluid">
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="ابحث في مزودي الخدمات..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="verification" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="1" {{ request('verification') == '1' ? 'selected' : '' }}>محقق</option>
                        <option value="0" {{ request('verification') == '0' ? 'selected' : '' }}>غير محقق</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>معطل</option>
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

    <!-- Providers Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-tie"></i> قائمة مزودي الخدمات</h3>
            <div class="card-tools">
                <span class="badge bg-primary">{{ $providers->total() }} مزود خدمة</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الحالة</th>
                            <th>التحقق</th>
                            <th>الخدمات</th>
                            <th>العروض</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($providers as $provider)
                        <tr>
                            <td>{{ $provider->id }}</td>
                            <td>
                                <img src="https://ui-avatars.com/api/?name={{ $provider->name }}&background=667eea&color=fff"
                                     alt="{{ $provider->name }}" class="rounded-circle" width="40" height="40">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $provider->name }}</div>
                                @if($provider->providerProfile)
                                    <small class="text-muted">{{ Str::limit($provider->providerProfile->about ?? '', 30) }}</small>
                                @endif
                            </td>
                            <td>{{ $provider->email }}</td>
                            <td>
                                @if($provider->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">معطل</span>
                                @endif
                            </td>
                            <td>
                                @if($provider->is_verified)
                                    <span class="badge bg-success">محقق</span>
                                @else
                                    <span class="badge bg-warning">غير محقق</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $provider->services->count() }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $provider->offers->count() }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $provider->created_at->format('Y-m-d') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.providers.show', $provider->id) }}" class="btn btn-info" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.show', $provider->id) }}" class="btn btn-outline-primary" title="عرض الملف الشخصي">
                                        <i class="fas fa-user"></i>
                                    </a>
                                    <form action="{{ route('admin.providers.verify', $provider->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-{{ $provider->is_verified ? 'warning' : 'success' }}"
                                                title="{{ $provider->is_verified ? 'إلغاء التحقق' : 'التحقق من' }}">
                                            <i class="fas fa-{{ $provider->is_verified ? 'times' : 'check' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <i class="fas fa-user-tie text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">لا يوجد مزودي خدمات</h5>
                                <p class="text-muted">لم يتم تسجيل أي مزودي خدمات بعد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $providers->links() }}
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
