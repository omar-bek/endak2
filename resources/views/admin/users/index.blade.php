@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')
@section('page-title', 'إدارة المستخدمين')

@section('content')
<div class="container-fluid">
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="ابحث في المستخدمين..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select">
                        <option value="">جميع الأدوار</option>
                        <option value="1" {{ request('role') == '1' ? 'selected' : '' }}>مدير</option>
                        <option value="2" {{ request('role') == '2' ? 'selected' : '' }}>مستخدم عادي</option>
                        <option value="3" {{ request('role') == '3' ? 'selected' : '' }}>مزود خدمة</option>
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

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> قائمة المستخدمين</h3>
            <div class="card-tools">
                <span class="badge bg-primary">{{ $users->total() }} مستخدم</span>
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
                            <th>الدور</th>
                            <th>الحالة</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <img src="https://ui-avatars.com/api/?name={{ $user->name }}&background=667eea&color=fff"
                                     alt="{{ $user->name }}" class="rounded-circle" width="40" height="40">
                            </td>
                            <td>
                                <div class="fw-bold">{{ $user->name }}</div>
                                @if($user->providerProfile)
                                    <small class="text-muted">مزود خدمة</small>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->user_type == 'admin')
                                    <span class="badge bg-danger">مدير</span>
                                @elseif($user->user_type == 'customer')
                                    <span class="badge bg-primary">مستخدم عادي</span>
                                @elseif($user->user_type == 'provider')
                                    <span class="badge bg-success">مزود خدمة</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">معطل</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $user->created_at->format('Y-m-d') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning" title="{{ $user->is_active ? 'تعطيل' : 'تفعيل' }}">
                                            <i class="fas fa-{{ $user->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-role', $user->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-secondary" title="تغيير الدور">
                                            <i class="fas fa-user-cog"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">لا يوجد مستخدمين</h5>
                                <p class="text-muted">لم يتم تسجيل أي مستخدمين بعد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
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
