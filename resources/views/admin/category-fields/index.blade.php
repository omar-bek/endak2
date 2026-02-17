@extends('layouts.admin')

@section('title', 'حقول القسم: ' . $category->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-list"></i> حقول القسم: {{ $category->name }}
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">الأقسام</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.categories.fields.create', $category) }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>إضافة حقل جديد
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-cogs me-2"></i>إدارة حقول القسم
            </h5>
        </div>
        <div class="card-body">
            @if($fields->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>اسم الحقل</th>
                            <th>النوع</th>
                            <th>المجموعة</th>
                            <th>الحالة</th>
                            <th>الترتيب</th>
                            <th width="200">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="fields-tbody">
                        @foreach($fields as $field)
                        <tr data-field-id="{{ $field->id }}">
                            <td>{{ $field->id }}</td>
                            <td>
                                <div>
                                    <strong>{{ $field->name_ar }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $field->name_en }} ({{ $field->name }})</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    <i class="{{ $field->type_icon }} me-1"></i>{{ $field->type_label }}
                                </span>
                                @if($field->is_required)
                                <span class="badge bg-danger ms-1">مطلوب</span>
                                @endif
                                @if($field->is_repeatable)
                                <span class="badge bg-warning ms-1">قابل للتكرار</span>
                                @endif
                            </td>
                            <td>
                                @if($field->input_group)
                                <span class="badge bg-info">{{ $field->input_group }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($field->is_active)
                                <span class="badge bg-success">نشط</span>
                                @else
                                <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <input type="number"
                                           class="form-control form-control-sm sort-order-input"
                                           style="width: 70px;"
                                           value="{{ $field->sort_order }}"
                                           min="0"
                                           data-field-id="{{ $field->id }}"
                                           data-category-id="{{ $category->id }}">
                                    <button class="btn btn-sm btn-outline-success ms-1 update-sort-btn"
                                            data-field-id="{{ $field->id }}"
                                            data-category-id="{{ $category->id }}">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.categories.fields.edit', [$category, $field]) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.categories.fields.toggle-status', [$category, $field]) }}"
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.categories.fields.destroy', [$category, $field]) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا الحقل؟')">
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

            <div class="mt-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    يمكنك سحب وإفلات الصفوف لإعادة ترتيب الحقول
                </small>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-list text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">لا توجد حقول</h4>
                <p class="text-muted">ابدأ بإضافة حقول جديدة للقسم</p>
                <a href="{{ route('admin.categories.fields.create', $category) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>إضافة حقل جديد
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('fields-tbody');

    // إدارة الترتيب بالسحب والإفلات
    if (tbody) {
        new Sortable(tbody, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                const fieldIds = Array.from(tbody.querySelectorAll('tr')).map(tr => tr.dataset.fieldId);

                fetch('{{ route("admin.categories.fields.reorder", $category) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ fields: fieldIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // تحديث أرقام الترتيب في الجدول
                        tbody.querySelectorAll('tr').forEach((tr, index) => {
                            const sortInput = tr.querySelector('.sort-order-input');
                            if (sortInput) {
                                sortInput.value = index;
                            }
                        });

                        // إظهار رسالة نجاح
                        showAlert('تم تحديث ترتيب الحقول بنجاح', 'success');
                    }
                });
            }
        });
    }

    // إدارة الترتيب بالكتابة
    document.querySelectorAll('.update-sort-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const fieldId = this.dataset.fieldId;
            const categoryId = this.dataset.categoryId;
            const sortInput = this.parentElement.querySelector('.sort-order-input');
            const newSortOrder = parseInt(sortInput.value);

            if (isNaN(newSortOrder) || newSortOrder < 0) {
                showAlert('يرجى إدخال رقم صحيح أكبر من أو يساوي 0', 'danger');
                return;
            }

            // تحديث الترتيب
            fetch(`/admin/categories/${categoryId}/fields/${fieldId}/sort-order`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ sort_order: newSortOrder })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    // تحديث جميع الحقول
                    location.reload();
                } else {
                    showAlert('حدث خطأ أثناء تحديث الترتيب', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('حدث خطأ أثناء تحديث الترتيب', 'danger');
            });
        });
    });

    // دالة إظهار التنبيهات
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);

        // إخفاء التنبيه تلقائياً بعد 3 ثواني
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
});
</script>
@endpush
