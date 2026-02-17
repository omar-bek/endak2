@extends('layouts.admin')

@section('title', 'إدارة الأقسام')
@section('page-title', 'إدارة الأقسام')

@section('content')
<style>
.toggle-btn {
    transition: all 0.3s ease;
    border-radius: 20px;
    font-size: 0.8rem;
    padding: 0.25rem 0.75rem;
}
.toggle-btn:hover {
    transform: scale(1.05);
}
.status-badge {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
    border-radius: 15px;
}
.btn-group-sm .btn {
    margin: 0 2px;
}
.category-image {
    width: 80px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
}
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>جميع الأقسام</h4>
    <div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary me-2">
            <i class="fas fa-plus me-2"></i>إضافة قسم جديد
        </a>
        <a href="{{ route('admin.sub_categories.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>إضافة قسم فرعي
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($categories->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>الصورة</th>
                        <th>الاسم</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="category-image">
                            @else
                                <span class="text-muted">لا توجد صورة</span>
                            @endif
                        </td>
                        <td>{{ $category->name_ar ?? $category->name }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success status-badge">
                                    <i class="fas fa-check-circle me-1"></i>مفعل
                                </span>
                            @else
                                <span class="badge bg-danger status-badge">
                                    <i class="fas fa-times-circle me-1"></i>معطل
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.sub_categories.create') }}" class="btn btn-success mb-3">إضافة قسم فرعي</a>
                                <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-primary">عرض</a>
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-warning">تعديل</a>

                                <form action="{{ route('admin.categories.toggle-status', $category->id) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm toggle-btn {{ $category->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                            title="{{ $category->is_active ? 'تعطيل القسم' : 'تفعيل القسم' }}"
                                            onclick="return confirm('{{ $category->is_active ? 'هل تريد تعطيل هذا القسم؟' : 'هل تريد تفعيل هذا القسم؟' }}')">
                                        @if($category->is_active)
                                            <i class="fas fa-toggle-on"></i> تعطيل
                                        @else
                                            <i class="fas fa-toggle-off"></i> تفعيل
                                        @endif
                                    </button>
                                </form>

                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                </form>
                                <a href="{{ route('admin.categories.fields.index', $category->id) }}" class="btn btn-sm btn-info">إدارة الحقول</a>
                            </div>
                        </td>
                    </tr>

                    {{-- عرض الأقسام الفرعية --}}
                    @php
                        $subCategories = $category->subCategories ?? collect();
                    @endphp
                    @if($subCategories->count() > 0)
                        @foreach($subCategories as $subCategory)
                            <tr style="background:#f9f9f9;">
                                <td>{{ $subCategory->id }}</td>
                                <td></td>
                                <td style="padding-left:40px;">&raquo; {{ $subCategory->name_ar ?? $subCategory->name_en }}</td>
                                <td>
                                    @if($subCategory->status == 'active')
                                        <span class="badge bg-success status-badge">
                                            <i class="fas fa-check-circle me-1"></i>مفعل
                                        </span>
                                    @else
                                        <span class="badge bg-danger status-badge">
                                            <i class="fas fa-times-circle me-1"></i>معطل
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.sub_categories.edit', $subCategory->id) }}" class="btn btn-warning" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.sub_categories.duplicate', $subCategory->id) }}" class="btn btn-info" title="تكرار">
                                            <i class="fas fa-copy"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.fields.create', ['category' => $category->id, 'sub_category_id' => $subCategory->id]) }}" class="btn btn-primary" title="إضافة حقل">
                                            <i class="fas fa-plus"></i>
                                        </a>

                                        <form action="{{ route('admin.sub_categories.toggle-status', $subCategory->id) }}" method="POST" style="display:inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn toggle-btn {{ $subCategory->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                    title="{{ $subCategory->status == 'active' ? 'تعطيل القسم الفرعي' : 'تفعيل القسم الفرعي' }}"
                                                    onclick="return confirm('{{ $subCategory->status == 'active' ? 'هل تريد تعطيل هذا القسم الفرعي؟' : 'هل تريد تفعيل هذا القسم الفرعي؟' }}')">
                                                @if($subCategory->status == 'active')
                                                    <i class="fas fa-toggle-on"></i>
                                                @else
                                                    <i class="fas fa-toggle-off"></i>
                                                @endif
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.sub_categories.destroy', $subCategory->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <i class="fas fa-folder-open text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted">لا توجد أقسام</h4>
            <p class="text-muted">ابدأ بإضافة قسم جديد</p>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>إضافة قسم جديد
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
