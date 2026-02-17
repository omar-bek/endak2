@extends('layouts.admin')

@section('title', 'إضافة قسم جديد')
@section('page-title', 'إضافة قسم جديد')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">معلومات القسم الجديد</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم القسم (عربي) *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name_en" class="form-label">اسم القسم (إنجليزي) *</label>
                            <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en"
                                name="name_en" value="{{ old('name_en') }}" required>
                            @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">وصف القسم (عربي)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description_en" class="form-label">وصف القسم (إنجليزي)</label>
                            <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en"
                                rows="4">{{ old('description_en') }}</textarea>
                            @error('description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="icon" class="form-label">الأيقونة</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                        id="icon" name="icon" value="{{ old('icon', 'fas fa-folder') }}"
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
                                        id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                                        min="0">
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
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name_ar ?? $category->name }} @if ($category->name_en)
                                            - {{ $category->name_en }}
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
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    تفعيل القسم
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="voice_note_enabled"
                                    name="voice_note_enabled" value="1"
                                    {{ old('voice_note_enabled', true) ? 'checked' : '' }}>
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
                                id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">وصف SEO</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>حفظ القسم
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
                <i class="fas fa-folder"></i>
            </div>
            <p class="text-muted mt-2">ستظهر الأيقونة المختارة هنا</p>
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
