@extends('layouts.admin')

@section('title', 'تعديل الحقل - ' . $category->name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="fas fa-edit"></i> تعديل الحقل
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">الأقسام</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.fields.index', $category) }}">{{ $category->name }}</a></li>
                    <li class="breadcrumb-item active">تعديل الحقل</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.categories.fields.index', $category) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>العودة للحقول
        </a>
    </div>

    <form action="{{ route('admin.categories.fields.update', [$category, $field]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @php
            // إذا كان نوع الحقل صورة وفي حالة الإنشاء (وليس التعديل)، اجعل القيمة الافتراضية null
            if ((old('type', $field->type ?? '') == 'image' || request('type') == 'image') && (!isset($field) || !$field->id)) {
                $value = null;
            } else {
                $value = old('value', $field->value ?? '');
            }
            $options = old('options', isset($field) && is_array($field->options) ? $field->options : []);
        @endphp

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-cog"></i> إعدادات الحقل</h5>
            </div>
            <div class="card-body">
                <!-- Field Name Arabic -->
                <div class="mb-3">
                    <label for="name_ar" class="form-label">
                        <i class="fas fa-font"></i> اسم الحقل بالعربي <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror"
                           id="name_ar" name="name_ar"
                           value="{{ old('name_ar', $field->name_ar ?? '') }}"
                           placeholder="مثال: من المدينة"
                           required
                           minlength="2"
                           maxlength="255">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> يجب أن يكون الاسم باللغة العربية وواضح
                    </small>
                    @error('name_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Field Name English -->
                <div class="mb-3">
                    <label for="name_en" class="form-label">
                        <i class="fas fa-font"></i> Field Name (English) <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror"
                           id="name_en" name="name_en"
                           value="{{ old('name_en', $field->name_en ?? '') }}"
                           placeholder="مثال: from_city"
                           required
                           minlength="2"
                           maxlength="255">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> يجب أن يكون الاسم باللغة الإنجليزية وواضح
                    </small>
                    @error('name_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Auto-generated Field Key -->
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="fas fa-key"></i> Field Key <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name"
                           value="{{ old('name', $field->name ?? '') }}"
                           placeholder="سيتم إنشاؤه تلقائياً">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        يمكن تعديل المفتاح في حالة التعديل
                    </small>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Field Type -->
                <div class="mb-3">
                    <label for="type" class="form-label">
                        <i class="fas fa-list"></i> نوع الحقل <span class="text-danger">*</span>
                    </label>
                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="">اختر نوع الحقل</option>
                        <option value="title" @selected(old('type', $field->type ?? '') == 'title')>
                            <i class="fas fa-heading"></i> عنوان فقط
                        </option>
                        <option value="text" @selected(old('type', $field->type ?? '') == 'text')>
                            <i class="fas fa-font"></i> نص
                        </option>
                        <option value="number" @selected(old('type', $field->type ?? '') == 'number')>
                            <i class="fas fa-hashtag"></i> رقم
                        </option>
                        <option value="select" @selected(old('type', $field->type ?? '') == 'select')>
                            <i class="fas fa-list"></i> قائمة منسدلة
                        </option>
                        <option value="checkbox" @selected(old('type', $field->type ?? '') == 'checkbox')>
                            <i class="fas fa-check-square"></i> تشيك بوكس
                        </option>
                        <option value="textarea" @selected(old('type', $field->type ?? '') == 'textarea')>
                            <i class="fas fa-paragraph"></i> مربع نص
                        </option>
                        <option value="image" @selected(old('type', $field->type ?? '') == 'image')>
                            <i class="fas fa-image"></i> صورة
                        </option>
                        <option value="date" @selected(old('type', $field->type ?? '') == 'date')>
                            <i class="fas fa-calendar"></i> تاريخ
                        </option>
                        <option value="time" @selected(old('type', $field->type ?? '') == 'time')>
                            <i class="fas fa-clock"></i> وقت
                        </option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input Group -->
                <div class="mb-3 position-relative">
                    <label for="input_group" class="form-label">
                        <i class="fas fa-layer-group"></i> مجموعة الإدخال (لدمج الحقول في صف واحد)
                    </label>
                    <input type="text"
                           class="form-control @error('input_group') is-invalid @enderror"
                           id="input_group"
                           name="input_group"
                           value="{{ old('input_group', $field->input_group ?? '') }}"
                           placeholder="مثال: address_group أو contact_info"
                           maxlength="255"
                           autocomplete="off"
                           list="inputGroupSuggestions">
                    <datalist id="inputGroupSuggestions">
                        @if(isset($inputGroups) && count($inputGroups))
                            @foreach($inputGroups as $group)
                                @if($group)
                                    <option value="{{ $group }}">
                                @endif
                            @endforeach
                        @endif
                    </datalist>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        استخدم نفس الاسم لدمج عدة حقول في صف واحد (مثال: address_group). يمكنك اختيار من القائمة أو كتابة اسم جديد.
                    </small>
                    @error('input_group')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Field Options -->
                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input @error('is_repeatable') is-invalid @enderror"
                               id="is_repeatable" name="is_repeatable" value="1"
                               @checked((old('is_repeatable') !== null ? old('is_repeatable') : ($field->is_repeatable ?? false)))>
                        <label class="form-check-label" for="is_repeatable">
                            <i class="fas fa-repeat"></i> قابل للتكرار (يمكن للمستخدم إضافة أكثر من مجموعة)
                        </label>
                        @error('is_repeatable')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input @error('is_required') is-invalid @enderror"
                               id="is_required" name="is_required" value="1"
                               @checked(old('is_required', $field->is_required ?? false))>
                        <label class="form-check-label" for="is_required">
                            <i class="fas fa-exclamation-triangle"></i> مطلوب؟
                        </label>
                        @error('is_required')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Dynamic Field Input -->
                <div class="mb-3" id="dynamic-field-input"></div>

                <!-- Sort Order -->
                <div class="mb-3">
                    <label for="sort_order" class="form-label">
                        <i class="fas fa-sort-numeric-up"></i> ترتيب الحقل
                    </label>
                    <input type="number"
                           class="form-control @error('sort_order') is-invalid @enderror"
                           id="sort_order"
                           name="sort_order"
                           value="{{ old('sort_order', $field->sort_order ?? 0) }}"
                           min="0"
                           placeholder="0">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        رقم الترتيب (0، 1، 2، ...). الأرقام الأقل تظهر أولاً.
                    </small>
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">
                        <i class="fas fa-sticky-note"></i> ملاحظات/وصف الحقل (اختياري)
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              name="description" id="description" rows="3"
                              placeholder="أضف وصفاً أو ملاحظات للحقل"
                              maxlength="1000">{{ old('description', $field->description ?? '') }}</textarea>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> يمكن إضافة وصف أو ملاحظات لتوضيح الغرض من الحقل
                    </small>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save"></i> تحديث الحقل
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const dynamicFieldInput = document.getElementById('dynamic-field-input');
    const nameEnInput = document.getElementById('name_en');
    const nameInput = document.getElementById('name');
    let options = @json($options);
    let value = @json($value);

    // Auto-generate field key from English name
    function generateFieldKey(englishName) {
        return englishName
            .toLowerCase()
            .replace(/[^a-z0-9\s]/g, '') // Remove special characters
            .replace(/\s+/g, '_') // Replace spaces with underscores
            .replace(/_+/g, '_') // Replace multiple underscores with single
            .replace(/^_|_$/g, ''); // Remove leading/trailing underscores
    }

    // Validate field key format
    function validateFieldKey(fieldKey) {
        return /^[a-z0-9_]+$/.test(fieldKey);
    }

    // Add validation to field key input
    nameInput.addEventListener('input', function() {
        const value = this.value;
        if (value && !validateFieldKey(value)) {
            this.classList.add('is-invalid');
            if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'Field key must contain only lowercase letters, numbers, and underscores.';
                this.parentNode.appendChild(feedback);
            }
        } else {
            this.classList.remove('is-invalid');
            const feedback = this.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.remove();
            }
        }
    });

    // Update field key when English name changes (only for new fields)
    nameEnInput.addEventListener('input', function() {
        // Only auto-generate if this is a new field (not editing)
        if (!nameInput.hasAttribute('readonly')) {
            const fieldKey = generateFieldKey(this.value);
            nameInput.value = fieldKey;
        }
    });

    function renderFieldInput() {
        let type = typeSelect.value;
        let html = '';

        // إضافة حقل مخفي لتخزين النوع الحالي
        html += `<input type="hidden" name="current_type" value="${type}">`;

        if(type === 'select') {
            html += `
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-list"></i> خيارات القائمة المنسدلة</h6>
                    </div>
                    <div class="card-body">
                        <div id="select-options-list">`;

            if (!Array.isArray(options) || options.length === 0) options = [''];
            options.forEach((opt, idx) => {
                html += `
                    <div class="input-group mb-2 select-option-item">
                        <input type="text" name="options[]" class="form-control" value="${opt}" placeholder="أدخل الخيار">
                        <button type="button" class="btn btn-outline-danger remove-option">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>`;
            });

                        html += `
                </div>
                <button type="button" class="btn btn-sm btn-primary" id="add-select-option">
                    <i class="fas fa-plus"></i> إضافة خيار
                </button>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-info-circle"></i> أضف الخيارات التي ستظهر في القائمة المنسدلة
                </small>
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label"><i class="fas fa-star"></i> القيمة الافتراضية:</label>
            <select name="value" id="select-default-value" class="form-control">`;

            options.forEach(opt => {
                html += `<option value="${opt}" ${(value == opt) ? 'selected' : ''}>${opt}</option>`;
            });

            html += `</select>
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> اختر القيمة التي ستظهر افتراضياً في القائمة المنسدلة
                </small>
            </div>`;

        } else if(type === 'checkbox') {
            html = `
                <div class="card">
                    <div class="card-body">
                        <label class="form-label"><i class="fas fa-star"></i> القيمة الافتراضية:</label>
                        <div class="form-check">
                            <input type="checkbox" name="value" value="1" class="form-check-input" ${(value==1)?'checked':''}>
                            <label class="form-check-label">مفعل افتراضياً</label>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> إذا تم تحديد هذا الخيار، سيظهر الحقل مفعلاً افتراضياً
                        </small>
                    </div>
                </div>`;

        } else if(type === 'image') {
            html = `
                <div class="card">
                    <div class="card-body">
                        <label class="form-label"><i class="fas fa-star"></i> القيمة الافتراضية:</label>
                        <input type="file" name="value" class="form-control" accept="image/*">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> هذه الصورة ستظهر كقيمة افتراضية في النموذج
                        </small>
                        ${value ? `<div class='mt-2'><img src='${value}' class='img-thumbnail' style='max-width:120px;'></div>` : ''}
                    </div>
                </div>`;

        } else if(type === 'date') {
            html = `
                <div class="card">
                    <div class="card-body">
                        <label class="form-label"><i class="fas fa-star"></i> القيمة الافتراضية:</label>
                        <input type="date" name="value" class="form-control" value="${value}">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> هذه القيمة ستظهر كقيمة افتراضية في النموذج
                        </small>
                    </div>
                </div>`;

        } else if(type === 'time') {
            html = `
                <div class="card">
                    <div class="card-body">
                        <label class="form-label"><i class="fas fa-star"></i> القيمة الافتراضية:</label>
                        <input type="time" name="value" class="form-control" value="${value}">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> هذه القيمة ستظهر كقيمة افتراضية في النموذج
                        </small>
                    </div>
                </div>`;

        } else if(type === 'number') {
            html = `
                <div class="card">
                    <div class="card-body">
                        <label class="form-label"><i class="fas fa-star"></i> القيمة الافتراضية:</label>
                        <input type="number" name="value" class="form-control" value="${value}" placeholder="أدخل رقماً" min="0" step="any">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> هذه القيمة ستظهر كقيمة افتراضية في النموذج
                        </small>
                    </div>
                </div>`;

        } else if(type === 'textarea') {
            html = `
                <div class="card">
                    <div class="card-body">
                        <label class="form-label"><i class="fas fa-star"></i> القيمة الافتراضية:</label>
                        <textarea name="value" class="form-control" rows="3" placeholder="أدخل النص الافتراضي" maxlength="1000">${value}</textarea>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> هذه القيمة ستظهر كقيمة افتراضية في النموذج
                        </small>
                    </div>
                </div>`;

        } else if(type === 'title') {
            html = `
                <div class="card">
                    <div class="card-body">
                        <label class="form-label"><i class="fas fa-star"></i> نص العنوان:</label>
                        <input type="text" name="value" class="form-control" value="${value}" placeholder="أدخل نص العنوان" maxlength="255">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> هذا النص سيظهر كعنوان في النموذج (لا يمكن للمستخدم تعديله)
                        </small>
                    </div>
                </div>`;
        } else {
            html = `
                <div class="card">
                    <div class="card-body">
                        <label class="form-label"><i class="fas fa-star"></i> القيمة الافتراضية:</label>
                        <input type="text" name="value" class="form-control" value="${value}" placeholder="أدخل النص الافتراضي" maxlength="255">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> هذه القيمة ستظهر كقيمة افتراضية في النموذج
                        </small>
                    </div>
                </div>`;
        }

        dynamicFieldInput.innerHTML = html;

        // Events for select options
        if(type === 'select') {
            document.getElementById('add-select-option').onclick = function() {
                options.push('');
                renderFieldInput();
            };

            dynamicFieldInput.querySelectorAll('.remove-option').forEach((btn, idx) => {
                btn.onclick = function() {
                    if (options.length > 1) {
                        options.splice(idx, 1);
                        renderFieldInput();
                    }
                };
            });

            dynamicFieldInput.querySelectorAll('input[name="options[]"]').forEach((input, idx) => {
                input.oninput = function() {
                    options[idx] = input.value;
                    // لا تعيد renderFieldInput هنا حتى لا تقطع الكتابة
                };
                input.onblur = function() {
                    renderFieldInput(); // عند الخروج من الحقل فقط
                };
            });
        }
    }

    renderFieldInput();

    typeSelect.addEventListener('change', function() {
        if(typeSelect.value !== 'select') options = [];
        renderFieldInput();
    });

    // Initialize field key if English name exists
    if (nameEnInput.value) {
        nameInput.value = generateFieldKey(nameEnInput.value);
    }
});
</script>
@endpush
