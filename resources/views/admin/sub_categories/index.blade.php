@extends('layouts.admin')

@section('title', 'الأقسام الفرعية')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-folder"></i> الأقسام الفرعية
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.sub_categories.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> إضافة قسم فرعي
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>الصورة</th>
                                        <th>الاسم (عربي)</th>
                                        <th>الاسم (إنجليزي)</th>
                                        <th>القسم الرئيسي</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subCategories as $subCategory)
                                        <tr>
                                            <td>{{ $subCategory->id }}</td>
                                            <td>
                                                @if ($subCategory->image)
                                                    <img src="{{ $subCategory->image_url }}"
                                                        alt="{{ $subCategory->name_ar }}" class="img-thumbnail"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                                        style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $subCategory->name_ar }}</td>
                                            <td>{{ $subCategory->name_en }}</td>
                                            <td>
                                                <div>
                                                    <span
                                                        class="badge badge-primary">{{ $subCategory->category->name_ar ?? $subCategory->category->name }}</span>
                                                    @if ($subCategory->category->name_en)
                                                        <br><small
                                                            class="text-muted">{{ $subCategory->category->name_en }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if ($subCategory->status)
                                                    <span class="badge badge-success">مفعل</span>
                                                @else
                                                    <span class="badge badge-danger">معطل</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.sub_categories.edit', $subCategory->id) }}"
                                                        class="btn btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('admin.sub_categories.duplicate', $subCategory->id) }}"
                                                        class="btn btn-info" title="تكرار">
                                                        <i class="fas fa-copy"></i>
                                                    </a>
                                                    <a href="{{ route('admin.categories.fields.index', ['category' => $subCategory->category_id, 'sub_category_id' => $subCategory->id]) }}"
                                                        class="btn btn-primary" title="إدارة الحقول">
                                                        <i class="fas fa-cogs"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('admin.sub_categories.destroy', $subCategory->id) }}"
                                                        method="POST" style="display:inline-block"
                                                        onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
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
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $subCategories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
