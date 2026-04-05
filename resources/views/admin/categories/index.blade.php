@extends('layouts.admin')

@section('title', 'إدارة الأقسام')
@section('page-title', 'إدارة الأقسام')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="mb-1 fw-bold">الأقسام</h4>
        <p class="text-muted mb-0 small">إدارة الأقسام الرئيسية والفرعية</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="fas fa-plus me-1"></i>قسم رئيسي
        </a>
        <a href="{{ route('admin.sub_categories.create') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="fas fa-plus me-1"></i>قسم فرعي
        </a>
    </div>
</div>

@if($categories->count() > 0)
    <div class="ac-grid">
        @foreach($categories as $category)
            @php $subCategories = $category->subCategories ?? collect(); @endphp
            <div class="ac-card">
                {{-- Header --}}
                <div class="ac-card-header">
                    <div class="ac-card-img">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                        @else
                            <div class="ac-card-img-placeholder"><i class="{{ $category->icon ?? 'fas fa-folder' }}"></i></div>
                        @endif
                    </div>
                    <div class="ac-card-info">
                        <h6 class="ac-card-title">
                            <i class="{{ $category->icon ?? 'fas fa-folder' }} me-1" style="color: var(--e-primary-light, #3d7a8a);"></i>
                            {{ $category->name_ar ?? $category->name }}
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            @if($category->is_active)
                                <span class="ac-status ac-status-active"><i class="fas fa-check-circle"></i> مفعل</span>
                            @else
                                <span class="ac-status ac-status-inactive"><i class="fas fa-times-circle"></i> معطل</span>
                            @endif
                            <span class="ac-count">{{ $subCategories->count() }} فرعي</span>
                        </div>
                    </div>
                    <span class="ac-id">#{{ $category->id }}</span>
                </div>

                {{-- Sub Categories --}}
                @if($subCategories->count() > 0)
                    <div class="ac-subs">
                        @foreach($subCategories as $sub)
                            <div class="ac-sub-item">
                                <div class="ac-sub-info">
                                    <span class="ac-sub-name">{{ $sub->name_ar ?? $sub->name_en }}</span>
                                    @if($sub->status == 'active' || $sub->status === true || $sub->status === 1)
                                        <span class="ac-dot ac-dot-active"></span>
                                    @else
                                        <span class="ac-dot ac-dot-inactive"></span>
                                    @endif
                                </div>
                                <div class="ac-sub-actions">
                                    <a href="{{ route('admin.sub_categories.edit', $sub->id) }}" class="ac-icon-btn" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.sub_categories.duplicate', $sub->id) }}" class="ac-icon-btn" title="تكرار"><i class="fas fa-copy"></i></a>
                                    <form action="{{ route('admin.sub_categories.toggle-status', $sub->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="ac-icon-btn" title="{{ $sub->status == 'active' ? 'تعطيل' : 'تفعيل' }}">
                                            <i class="fas fa-{{ $sub->status == 'active' ? 'toggle-on text-success' : 'toggle-off text-muted' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.sub_categories.destroy', $sub->id) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف القسم الفرعي؟')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="ac-icon-btn ac-icon-btn-danger" title="حذف"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Actions --}}
                <div class="ac-card-footer">
                    <a href="{{ route('admin.categories.show', $category->id) }}" class="ac-btn"><i class="fas fa-eye"></i> عرض</a>
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="ac-btn"><i class="fas fa-edit"></i> تعديل</a>
                    <a href="{{ route('admin.categories.fields.index', $category->id) }}" class="ac-btn ac-btn-info"><i class="fas fa-list-alt"></i> الحقول</a>
                    <form action="{{ route('admin.categories.toggle-status', $category->id) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button type="submit" class="ac-btn {{ $category->is_active ? 'ac-btn-warn' : 'ac-btn-success' }}"
                            onclick="return confirm('{{ $category->is_active ? 'تعطيل القسم؟' : 'تفعيل القسم؟' }}')">
                            <i class="fas fa-{{ $category->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                            {{ $category->is_active ? 'تعطيل' : 'تفعيل' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف القسم وجميع الأقسام الفرعية؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="ac-btn ac-btn-danger"><i class="fas fa-trash"></i> حذف</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <div style="width:80px;height:80px;border-radius:50%;background:var(--e-gray-100,#f1f5f9);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
            <i class="fas fa-folder-open fa-2x" style="color:var(--e-gray-400);"></i>
        </div>
        <h5 class="text-muted">لا توجد أقسام</h5>
        <p class="text-muted small">ابدأ بإضافة قسم جديد</p>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm rounded-pill px-4"><i class="fas fa-plus me-1"></i>إضافة قسم</a>
    </div>
@endif

<style>
    .ac-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 1rem; }

    .ac-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid var(--e-gray-200, #e2e8f0);
        overflow: hidden;
        transition: box-shadow 0.25s;
    }
    .ac-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.06); }

    .ac-card-header { display: flex; align-items: center; gap: 0.85rem; padding: 1rem 1.15rem; }

    .ac-card-img { width: 56px; height: 56px; border-radius: 12px; overflow: hidden; flex-shrink: 0; }
    .ac-card-img img { width: 100%; height: 100%; object-fit: cover; }
    .ac-card-img-placeholder {
        width: 100%; height: 100%;
        background: var(--e-gray-100, #f1f5f9);
        display: flex; align-items: center; justify-content: center;
        color: var(--e-gray-400); font-size: 1.2rem;
    }

    .ac-card-info { flex: 1; min-width: 0; }
    .ac-card-title { font-weight: 700; font-size: 0.95rem; color: var(--e-gray-800, #1e293b); margin: 0 0 0.25rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ac-id { font-size: 0.7rem; color: var(--e-gray-400); flex-shrink: 0; align-self: flex-start; }

    .ac-status {
        display: inline-flex; align-items: center; gap: 0.25rem;
        font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 999px;
    }
    .ac-status-active { background: rgba(16,185,129,0.1); color: #059669; }
    .ac-status-inactive { background: rgba(239,68,68,0.1); color: #dc2626; }

    .ac-count { font-size: 0.7rem; color: var(--e-gray-400); }

    /* Sub categories */
    .ac-subs { border-top: 1px solid var(--e-gray-100, #f1f5f9); }

    .ac-sub-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.55rem 1.15rem;
        border-bottom: 1px solid var(--e-gray-50, #f8fafc);
        transition: background 0.2s;
    }
    .ac-sub-item:hover { background: var(--e-gray-50, #f8fafc); }
    .ac-sub-item:last-child { border-bottom: none; }

    .ac-sub-info { display: flex; align-items: center; gap: 0.5rem; }
    .ac-sub-name { font-size: 0.82rem; color: var(--e-gray-600); font-weight: 500; }

    .ac-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
    .ac-dot-active { background: #10b981; }
    .ac-dot-inactive { background: #ef4444; }

    .ac-sub-actions { display: flex; gap: 2px; opacity: 0.4; transition: opacity 0.2s; }
    .ac-sub-item:hover .ac-sub-actions { opacity: 1; }

    .ac-icon-btn {
        background: none; border: none; cursor: pointer;
        width: 28px; height: 28px; border-radius: 6px;
        display: inline-flex; align-items: center; justify-content: center;
        color: var(--e-gray-500); font-size: 0.75rem; transition: all 0.2s;
        text-decoration: none;
    }
    .ac-icon-btn:hover { background: var(--e-gray-100); color: var(--e-primary, #2f5c69); }
    .ac-icon-btn-danger:hover { background: rgba(239,68,68,0.1); color: #dc2626; }

    /* Footer actions */
    .ac-card-footer {
        display: flex; flex-wrap: wrap; gap: 0.35rem;
        padding: 0.75rem 1.15rem;
        border-top: 1px solid var(--e-gray-100, #f1f5f9);
        background: var(--e-gray-50, #f8fafc);
    }

    .ac-btn {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.3rem 0.65rem; border-radius: 8px;
        font-size: 0.72rem; font-weight: 600; border: none; cursor: pointer;
        background: var(--e-gray-100, #f1f5f9); color: var(--e-gray-600, #475569);
        transition: all 0.2s; text-decoration: none;
    }
    .ac-btn:hover { background: var(--e-gray-200); color: var(--e-gray-700); }
    .ac-btn-info { background: rgba(59,130,246,0.1); color: #2563eb; }
    .ac-btn-info:hover { background: rgba(59,130,246,0.2); color: #1d4ed8; }
    .ac-btn-success { background: rgba(16,185,129,0.1); color: #059669; }
    .ac-btn-success:hover { background: rgba(16,185,129,0.2); }
    .ac-btn-warn { background: rgba(245,158,11,0.1); color: #92400e; }
    .ac-btn-warn:hover { background: rgba(245,158,11,0.2); }
    .ac-btn-danger { background: rgba(239,68,68,0.08); color: #dc2626; }
    .ac-btn-danger:hover { background: rgba(239,68,68,0.15); }

    @media (max-width: 768px) {
        .ac-grid { grid-template-columns: 1fr; }
        .ac-card-footer { justify-content: center; }
    }
</style>
@endsection
