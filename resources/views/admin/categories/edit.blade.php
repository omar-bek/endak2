@extends('layouts.admin')

@section('title', 'تعديل القسم')
@section('page-title', 'تعديل القسم: ' . ($category->name_ar ?? $category->name))

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">تعديل معلومات القسم</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم القسم (عربي) *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $category->name_ar ?? $category->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name_en" class="form-label">اسم القسم (إنجليزي) *</label>
                            <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en"
                                name="name_en" value="{{ old('name_en', $category->name_en) }}" required>
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">وصف القسم (عربي)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4">{{ old('description', $category->description_ar ?? $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description_en" class="form-label">وصف القسم (إنجليزي)</label>
                            <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en"
                                rows="4">{{ old('description_en', $category->description_en) }}</textarea>
                            @error('description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">الأيقونة</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                        id="icon" name="icon" value="{{ old('icon', $category->icon) }}"
                                        placeholder="مثال: fas fa-home">
                                    <small class="form-text text-muted">استخدم Font Awesome icons</small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">ترتيب العرض</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                        id="sort_order" name="sort_order"
                                        value="{{ old('sort_order', $category->sort_order) }}" min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="parent_id" class="form-label">القسم الأب</label>
                            <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id"
                                name="parent_id">
                                <option value="">قسم رئيسي</option>
                                @foreach ($categories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}"
                                        {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                        {{ $parentCategory->name_ar ?? $parentCategory->name }} @if ($parentCategory->name_en)
                                            - {{ $parentCategory->name_en }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">صورة القسم</label>
                            @if ($category->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $category->image) }}"
                                        alt="{{ $category->name_ar ?? $category->name }}" class="img-thumbnail"
                                        style="max-width: 200px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                                name="image" accept="image/*">
                            <small class="form-text text-muted">الأبعاد المفضلة: 400x300 بكسل</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    تفعيل القسم
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="voice_note_enabled"
                                    name="voice_note_enabled" value="1"
                                    {{ old('voice_note_enabled', $category->voice_note_enabled) ? 'checked' : '' }}>
                                <label class="form-check-label" for="voice_note_enabled">
                                    <i class="fas fa-microphone text-primary me-1"></i>
                                    تفعيل تسجيل الصوت
                                </label>
                                <small class="form-text text-muted d-block">السماح للمستخدمين بتسجيل صوتي في هذا
                                    القسم</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_title" class="form-label">عنوان SEO</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                id="meta_title" name="meta_title"
                                value="{{ old('meta_title', $category->meta_title) }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">وصف SEO</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                name="meta_description" rows="3">{{ old('meta_description', $category->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Icon Preview -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">معاينة الأيقونة</h6>
        </div>
        <div class="card-body text-center">
            <div id="icon-preview" style="font-size: 3rem; color: #667eea;">
                <i class="{{ $category->icon }}"></i>
            </div>
            <p class="text-muted mt-2">ستظهر الأيقونة المختارة هنا</p>
        </div>
    </div>

    <!-- Category Info -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">معلومات القسم</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>تاريخ الإنشاء:</strong> {{ $category->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>آخر تحديث:</strong> {{ $category->updated_at->format('Y-m-d H:i') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>عدد الخدمات:</strong> {{ $category->services->count() }}</p>
                    <p><strong>عدد الأقسام الفرعية:</strong> {{ $category->children->count() }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('icon').addEventListener('input', function() {
            const iconClass = this.value || 'fas fa-folder';
            const preview = document.getElementById('icon-preview');
            preview.innerHTML = `<i class="${iconClass}"></i>`;
        });
    </script>
@endpush
