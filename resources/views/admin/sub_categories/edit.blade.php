@extends('layouts.admin')

@section('title', 'تعديل قسم فرعي')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-edit"></i> تعديل قسم فرعي
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.sub_categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> العودة
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.sub_categories.update', $subCategory->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_ar">الاسم (عربي) <span class="text-danger">*</span></label>
                                        <input type="text" name="name_ar" id="name_ar"
                                            class="form-control @error('name_ar') is-invalid @enderror"
                                            value="{{ old('name_ar', $subCategory->name_ar) }}" required>
                                        @error('name_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_en">الاسم (إنجليزي) <span class="text-danger">*</span></label>
                                        <input type="text" name="name_en" id="name_en"
                                            class="form-control @error('name_en') is-invalid @enderror"
                                            value="{{ old('name_en', $subCategory->name_en) }}" required>
                                        @error('name_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="category_id">القسم الرئيسي <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id"
                                            class="form-control @error('category_id') is-invalid @enderror" required>
                                            <option value="">اختر القسم الرئيسي</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $subCategory->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name_ar ?? $category->name }} @if ($category->name_en)
                                                        - {{ $category->name_en }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">الحالة <span class="text-danger">*</span></label>
                                        <select name="status" id="status"
                                            class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="1"
                                                {{ old('status', $subCategory->status) == '1' ? 'selected' : '' }}>مفعل
                                            </option>
                                            <option value="0"
                                                {{ old('status', $subCategory->status) == '0' ? 'selected' : '' }}>معطل
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">الصورة</label>
                                        @if ($subCategory->image)
                                            <div class="mb-2">
                                                <img src="{{ $subCategory->image_url }}" alt="{{ $subCategory->name_ar }}"
                                                    class="img-thumbnail" style="max-width: 200px;">
                                            </div>
                                        @endif
                                        <input type="file" name="image" id="image"
                                            class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">الصيغ المدعومة: JPG, PNG, GIF. الحد الأقصى:
                                            2MB</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_ar">الوصف (عربي)</label>
                                        <textarea name="description_ar" id="description_ar" class="form-control @error('description_ar') is-invalid @enderror"
                                            rows="4">{{ old('description_ar', $subCategory->description_ar) }}</textarea>
                                        @error('description_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_en">الوصف (إنجليزي)</label>
                                        <textarea name="description_en" id="description_en" class="form-control @error('description_en') is-invalid @enderror"
                                            rows="4">{{ old('description_en', $subCategory->description_en) }}</textarea>
                                        @error('description_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ التعديلات
                                </button>
                                <a href="{{ route('admin.sub_categories.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
