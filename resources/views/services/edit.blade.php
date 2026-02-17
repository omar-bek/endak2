@extends('layouts.app')

@section('title', 'تعديل الخدمة - ' . $service->title)

@section('content')
    <!-- Category Info Alert -->
    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">الأقسام</a></li>
                <li class="breadcrumb-item"><a href="{{ route('services.show', $service->slug) }}">{{ $service->title }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">تعديل الخدمة</li>
            </ol>
        </nav>

        <div class="alert alert-info text-center">
            <i class="fas fa-edit"></i>
            <strong>تعديل خدمة من قسم:</strong> {{ $service->category->name }}
            @if ($service->subCategory)
                <br><small class="text-muted">القسم الفرعي:
                    {{ app()->getLocale() == 'ar' ? $service->subCategory->name_ar : $service->subCategory->name_en }}</small>
            @endif
        </div>
    </div>
    <style>
        .repeatable-group {
            position: relative;
        }

        .group-instance {
            transition: all 0.3s ease;
            border: 2px solid transparent;
            border-radius: 10px;
            overflow: hidden;
        }

        .group-instance:hover {
            border-color: #e9ecef;
        }

        .group-instance[data-instance="0"] {
            border-color: #28a745;
        }

        .group-controls {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .group-instance .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 2px solid #dee2e6;
            padding: 0.75rem 1rem;
        }

        .group-instance[data-instance="0"] .card-header {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-bottom: 2px solid #28a745;
        }

        .group-title {
            font-weight: 600;
            color: #495057;
            font-size: 1rem;
            margin: 0;
        }

        .group-instance[data-instance="0"] .group-title {
            color: #155724;
        }

        .add-group-instance {
            transition: all 0.2s ease;
            border-radius: 20px;
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .add-group-instance:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        .remove-group-instance {
            transition: all 0.2s ease;
            border-radius: 20px;
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .remove-group-instance:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }

        .group-instance[data-instance="0"] .form-control:focus {
            border-color: #155724;
            box-shadow: 0 0 0 0.2rem rgba(21, 87, 36, 0.25);
        }

        /* رفع الصور المتعددة */
        .upload-area {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: #007bff !important;
            background-color: #f8f9fa;
        }

        .upload-area.dragover {
            border-color: #28a745 !important;
            background-color: #d4edda;
        }

        .image-preview-container .col-md-3 {
            margin-bottom: 1rem;
        }

        .image-preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .image-preview-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .image-preview-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }

        .remove-image-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(220, 53, 69, 0.9);
            border: none;
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-image-btn:hover {
            background: #dc3545;
            transform: scale(1.1);
        }

        .existing-image-item {
            position: relative;
        }

        .existing-image-item .card {
            transition: all 0.3s ease;
        }

        .existing-image-item .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* تحسينات الاستجابة للصور */
        @media (max-width: 768px) {
            .image-preview-container .col-md-3 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 576px) {
            .image-preview-container .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* تحسين مظهر الحقول في الصف الواحد */
        .group-instance .card-body {
            padding: 1rem;
        }

        .group-instance .row {
            margin: 0;
            flex-wrap: wrap;
        }



        .group-instance .col-12 {
            padding: 0;
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .group-instance .col-md-6 {
            padding: 0 10px;
        }

        .group-instance .form-label {
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 0.2rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .group-instance .form-control {
            font-size: 1rem;
            border-radius: 8px;
            border: 2px solid #ced4da;
            transition: all 0.2s ease;
            padding: 0.75rem 1rem;
            flex: 1;
            min-height: 50px;
        }

        .group-instance .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            transform: translateY(-1px);
        }

        .group-instance select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.5rem center;
            background-size: 12px 8px;
            padding-right: 1.5rem;
        }

        .group-instance .form-check {
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-left: 0;
        }

        .group-instance .form-check-input {
            border-radius: 4px;
            border: 2px solid #ced4da;
            transition: all 0.2s ease;
        }

        .group-instance .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        .group-instance .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .group-instance .form-check-label {
            font-size: 0.8rem;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* تحسين مظهر الحقول */
        .group-instance .col-12 {
            width: 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }

        .group-instance textarea.form-control {
            min-height: 80px;
            resize: vertical;
        }

        .group-instance input[type="file"].form-control {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .group-instance input[type="date"].form-control,
        .group-instance input[type="time"].form-control {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .group-instance input[type="number"].form-control {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            text-align: center;
        }

        .group-instance input[type="text"].form-control {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    </style>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- معلومات القسم -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div class="text-start mb-3">
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_categories') }}
                            </a>
                        </div>

                        @if ($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}"
                                class="img-fluid mb-3" style="max-width: 200px; border-radius: 10px;">
                        @endif

                        <h2 class="text-primary">{{ $service->title }}</h2>
                        <p class="text-muted">{{ $service->description }}</p>

                        @if ($service->subCategory)
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-check-circle"></i>
                                <strong>القسم الفرعي المحدد:</strong>
                                {{ app()->getLocale() == 'ar' ? $service->subCategory->name_ar : $service->subCategory->name_en }}
                                @if ($service->subCategory->description_ar || $service->subCategory->description_en)
                                    <br><small
                                        class="text-muted">{{ app()->getLocale() == 'ar' ? $service->subCategory->description_ar : $service->subCategory->description_en }}</small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- نموذج تعديل الخدمة -->
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">
                            <i class="fas fa-edit"></i> {{ __('messages.edit_service_title') }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('services.update', $service->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <input type="hidden" name="category_id" value="{{ $service->category_id }}">
                            <input type="hidden" name="sub_category_id" value="{{ $service->sub_category_id }}">

                            <div class="mb-3">
                                <label for="city_id" class="form-label">
                                    <i class="fas fa-map-marker-alt text-success"></i>
                                    اختر المدينة *
                                    @if ($cities->count() > 0)
                                        <small class="text-muted">({{ $cities->count() }} مدينة متاحة)</small>
                                    @endif
                                </label>
                                @if ($cities->count() > 0)
                                    <select name="city_id" id="city_id" class="form-control" required>
                                        <option value="">اختر المدينة</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ old('city_id', $service->city_id) == $city->id ? 'selected' : '' }}>
                                                {{ $city->name_ar ?? ($city->name_en ?? 'اسم غير محدد') }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        لا توجد مدن متاحة لهذا القسم حالياً. يرجى التواصل مع الإدارة لإضافة المدن المتاحة.
                                    </div>
                                    <input type="hidden" name="city_id" value="">
                                @endif
                            </div>


                            <!-- الحقول المخصصة -->
                            @php
                                $groupedFields = $service->category->fields->groupBy('input_group');
                            @endphp

                            @foreach ($groupedFields as $group => $fields)
                                @php
                                    $isRepeatable = $fields->first()->is_repeatable ?? false;
                                    $groupId = $group ? 'group_' . str_replace([' ', '-'], '_', $group) : 'ungrouped';
                                    // ترتيب الحقول حسب sort_order
                                    $fields = $fields->sortBy('sort_order');
                                @endphp

                                @if ($group)
                                    <div class="repeatable-group" data-group-id="{{ $groupId }}"
                                        data-repeatable="{{ $isRepeatable ? 'true' : 'false' }}">
                                        <div class="card mb-3 group-instance" data-instance="0">
                                            <div
                                                class="card-header bg-light d-flex justify-content-between align-items-center">
                                                <h5 class="mb-0 group-title">{{ $group }}</h5>
                                                @if ($isRepeatable)
                                                    <div class="group-controls">
                                                        <button type="button"
                                                            class="btn btn-sm btn-success add-group-instance"
                                                            data-group-id="{{ $groupId }}">
                                                            <i class="fas fa-plus"></i> إضافة مجموعة
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger remove-group-instance d-none"
                                                            data-group-id="{{ $groupId }}">
                                                            <i class="fas fa-trash"></i> حذف المجموعة
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                @endif

                                @foreach ($fields as $field)
                                    @if ($field->type === 'title')
                                        <div class="mb-3">
                                            <h4 class="text-dark border-bottom pb-2">
                                                <i class="fas fa-heading"></i> {{ $field->value ?? $field->name_ar }}
                                            </h4>
                                        </div>
                                    @else
                                        <div class="col-12 mb-3">
                                            <label for="custom_fields_{{ $field->name }}_0" class="form-label">
                                                <i class="fas fa-{{ $field->getTypeIconAttribute() }}"></i>
                                                {{ $field->name_ar }}
                                                @if ($field->is_required)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>

                                            @php
                                                $fieldValue = $service->custom_fields[$field->name] ?? '';
                                                $fieldValue = is_array($fieldValue)
                                                    ? (isset($fieldValue[0])
                                                        ? $fieldValue[0]
                                                        : '')
                                                    : $fieldValue;
                                            @endphp

                                            @if ($field->type === 'select' && is_array($field->options))
                                                <select name="custom_fields[{{ $field->name }}][0]"
                                                    id="custom_fields_{{ $field->name }}_0" class="form-control"
                                                    {{ $field->is_required ? 'required' : '' }}>
                                                    <option value="" disabled selected>اختر</option>
                                                    @foreach ($field->options as $option)
                                                        <option value="{{ $option }}"
                                                            {{ old('custom_fields.' . $field->name . '.0', $fieldValue) == $option ? 'selected' : '' }}>
                                                            {{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($field->type === 'checkbox')
                                                @php
                                                    $isChecked =
                                                        $fieldValue === '1' ||
                                                        $fieldValue === 1 ||
                                                        $fieldValue === true ||
                                                        $fieldValue === 'true' ||
                                                        $fieldValue === 'on';
                                                @endphp
                                                <div class="form-check">
                                                    <input type="checkbox" name="custom_fields[{{ $field->name }}][0]"
                                                        id="custom_fields_{{ $field->name }}_0" value="1"
                                                        class="form-check-input"
                                                        {{ old('custom_fields.' . $field->name . '.0', $isChecked) ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="custom_fields_{{ $field->name }}_0">
                                                        {{ $field->name_ar }}
                                                    </label>
                                                </div>
                                            @elseif($field->type === 'image')
                                                <div class="image-upload-container"
                                                    data-field-name="{{ $field->name }}">
                                                    <div class="upload-area border border-dashed border-primary rounded p-3 text-center mb-3"
                                                        style="min-height: 120px;">
                                                        <div class="upload-content">
                                                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-2"></i>
                                                            <p class="text-muted mb-2">اسحب الصور هنا أو اضغط للاختيار</p>
                                                            <p class="small text-muted">يمكنك رفع عدة صور مرة واحدة</p>
                                                            <input type="file"
                                                                name="custom_fields[{{ $field->name }}][0][]"
                                                                id="custom_fields_{{ $field->name }}_0"
                                                                class="form-control d-none" accept="image/*" multiple
                                                                {{ $field->is_required ? 'required' : '' }}>
                                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                                onclick="document.getElementById('custom_fields_{{ $field->name }}_0').click()">
                                                                <i class="fas fa-plus"></i> اختيار الصور
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- معاينة الصور -->
                                                    <div class="image-preview-container row"
                                                        id="preview_{{ $field->name }}_0">
                                                        <!-- الصور ستظهر هنا -->
                                                    </div>

                                                    <!-- الصور الموجودة مسبقاً -->
                                                    @if (isset($fieldValue) && is_array($fieldValue) && count($fieldValue) > 0)
                                                        <div class="existing-images mb-3">
                                                            <h6 class="text-muted mb-2">الصور الموجودة:</h6>
                                                            <div class="row" id="existing_{{ $field->name }}_0">
                                                                @foreach ($fieldValue as $index => $imagePath)
                                                                    <div class="col-md-3 mb-2 existing-image-item"
                                                                        data-image-path="{{ $imagePath }}">
                                                                        <div class="card">
                                                                            <img src="{{ asset('storage/' . $imagePath) }}"
                                                                                class="card-img-top"
                                                                                style="height: 100px; object-fit: cover;"
                                                                                alt="صورة {{ $index + 1 }}">
                                                                            <div class="card-body p-2">
                                                                                <button type="button"
                                                                                    class="btn btn-danger btn-sm w-100"
                                                                                    onclick="removeExistingImage('{{ $field->name }}', '{{ $imagePath }}')">
                                                                                    <i class="fas fa-trash"></i> حذف
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @elseif($field->type === 'date')
                                                <input type="date" name="custom_fields[{{ $field->name }}][0]"
                                                    id="custom_fields_{{ $field->name }}_0" class="form-control"
                                                    value="{{ old('custom_fields.' . $field->name . '.0', $fieldValue) }}"
                                                    {{ $field->is_required ? 'required' : '' }}>
                                            @elseif($field->type === 'time')
                                                <input type="time" name="custom_fields[{{ $field->name }}][0]"
                                                    id="custom_fields_{{ $field->name }}_0" class="form-control"
                                                    value="{{ old('custom_fields.' . $field->name . '.0', $fieldValue) }}"
                                                    {{ $field->is_required ? 'required' : '' }}>
                                            @elseif($field->type === 'textarea')
                                                <textarea name="custom_fields[{{ $field->name }}][0]" id="custom_fields_{{ $field->name }}_0"
                                                    class="form-control" rows="3" {{ $field->is_required ? 'required' : '' }}>{{ old('custom_fields.' . $field->name . '.0', $fieldValue) }}</textarea>
                                            @else
                                                <input type="{{ $field->type }}"
                                                    name="custom_fields[{{ $field->name }}][0]"
                                                    id="custom_fields_{{ $field->name }}_0" class="form-control"
                                                    value="{{ old('custom_fields.' . $field->name . '.0', $fieldValue) }}"
                                                    {{ $field->is_required ? 'required' : '' }}>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach

                                @if ($group)
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach

        <!-- تسجيل صوتي -->
        @if ($service->category->voice_note_enabled)
            @include('partials.voice-note-recorder')
        @endif


        <!-- ملاحظات إضافية -->
        <div class="mb-3">
            <label for="notes" class="form-label">
                <i class="fas fa-sticky-note"></i> ملاحظات إضافية
            </label>
            <textarea name="notes" id="notes" value="{{ old('notes', $service->notes) }}" class="form-control"
                rows="4" placeholder="أضف أي ملاحظات إضافية هنا...">{{ old('notes', $service->notes) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100">
            <i class="fas fa-save"></i> حفظ التعديلات
        </button>
        </form>
    </div>
    </div>
    </div>
    </div>
    </div>

    <!-- Template for repeatable groups -->
    <template id="group-template">
        <div class="card mb-3 group-instance" data-instance="">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 group-title"></h5>
                <div class="group-controls">
                    <button type="button" class="btn btn-sm btn-success add-group-instance">
                        <i class="fas fa-plus"></i> إضافة مجموعة
                    </button>
                    <button type="button" class="btn btn-sm btn-danger remove-group-instance">
                        <i class="fas fa-trash"></i> حذف المجموعة
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Fields will be cloned here -->
            </div>
        </div>
    </template>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Add group instance
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('add-group-instance') || e.target.closest(
                        '.add-group-instance')) {
                    const button = e.target.classList.contains('add-group-instance') ? e.target : e.target
                        .closest('.add-group-instance');
                    const groupId = button.dataset.groupId;
                    addGroupInstance(groupId);
                }
            });

            // Remove group instance
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-group-instance') || e.target.closest(
                        '.remove-group-instance')) {
                    const button = e.target.classList.contains('remove-group-instance') ? e.target : e
                        .target.closest('.remove-group-instance');
                    const groupId = button.dataset.groupId;
                    removeGroupInstance(groupId);
                }
            });

            function addGroupInstance(groupId) {
                const groupContainer = document.querySelector(`[data-group-id="${groupId}"]`);
                const existingInstances = groupContainer.querySelectorAll('.group-instance');
                const newInstanceIndex = existingInstances.length;

                // Clone the first instance
                const firstInstance = existingInstances[0];
                const newInstance = firstInstance.cloneNode(true);

                // Update instance data
                newInstance.dataset.instance = newInstanceIndex;

                // Update all field names and IDs
                const fields = newInstance.querySelectorAll('input, select, textarea');
                fields.forEach(field => {
                    const oldName = field.name;
                    const oldId = field.id;

                    // Update name attribute
                    if (oldName) {
                        const nameMatch = oldName.match(/custom_fields\[([^\]]+)\]\[(\d+)\]/);
                        if (nameMatch) {
                            const fieldName = nameMatch[1];
                            field.name = `custom_fields[${fieldName}][${newInstanceIndex}]`;
                        }
                    }

                    // Update ID attribute
                    if (oldId) {
                        const idMatch = oldId.match(/custom_fields_([^_]+)_(\d+)/);
                        if (idMatch) {
                            const fieldName = idMatch[1];
                            field.id = `custom_fields_${fieldName}_${newInstanceIndex}`;
                        }
                    }

                    // Clear values
                    if (field.type === 'checkbox') {
                        field.checked = false;
                    } else if (field.type === 'file') {
                        field.value = '';
                    } else {
                        field.value = '';
                    }
                });

                // Update labels
                const labels = newInstance.querySelectorAll('label');
                labels.forEach(label => {
                    const forAttr = label.getAttribute('for');
                    if (forAttr) {
                        const idMatch = forAttr.match(/custom_fields_([^_]+)_(\d+)/);
                        if (idMatch) {
                            const fieldName = idMatch[1];
                            label.setAttribute('for', `custom_fields_${fieldName}_${newInstanceIndex}`);
                        }
                    }
                });

                // Show remove button for all instances
                const removeButtons = groupContainer.querySelectorAll('.remove-group-instance');
                removeButtons.forEach(btn => btn.classList.remove('d-none'));

                // Insert the new instance
                groupContainer.appendChild(newInstance);

                // Update group title with instance number
                const groupTitle = newInstance.querySelector('.group-title');
                if (groupTitle) {
                    groupTitle.textContent = groupTitle.textContent + ` (${newInstanceIndex + 1})`;
                }

                // Add smooth animation
                newInstance.style.opacity = '0';
                newInstance.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    newInstance.style.transition = 'all 0.3s ease';
                    newInstance.style.opacity = '1';
                    newInstance.style.transform = 'translateY(0)';
                }, 10);
            }

            function removeGroupInstance(groupId) {
                const groupContainer = document.querySelector(`[data-group-id="${groupId}"]`);
                const instances = groupContainer.querySelectorAll('.group-instance');

                if (instances.length > 1) {
                    // Remove the last instance
                    const lastInstance = instances[instances.length - 1];
                    lastInstance.remove();

                    // If only one instance remains, hide remove buttons
                    const remainingInstances = groupContainer.querySelectorAll('.group-instance');
                    if (remainingInstances.length === 1) {
                        const removeButtons = groupContainer.querySelectorAll('.remove-group-instance');
                        removeButtons.forEach(btn => btn.classList.add('d-none'));
                    }

                    // Update instance numbers and titles
                    remainingInstances.forEach((instance, index) => {
                        instance.dataset.instance = index;

                        // Update field names and IDs
                        const fields = instance.querySelectorAll('input, select, textarea');
                        fields.forEach(field => {
                            const oldName = field.name;
                            const oldId = field.id;

                            // Update name attribute
                            if (oldName) {
                                const nameMatch = oldName.match(
                                    /custom_fields\[([^\]]+)\]\[(\d+)\]/);
                                if (nameMatch) {
                                    const fieldName = nameMatch[1];
                                    field.name = `custom_fields[${fieldName}][${index}]`;
                                }
                            }

                            // Update ID attribute
                            if (oldId) {
                                const idMatch = oldId.match(/custom_fields_([^_]+)_(\d+)/);
                                if (idMatch) {
                                    const fieldName = idMatch[1];
                                    field.id = `custom_fields_${fieldName}_${index}`;
                                }
                            }
                        });

                        // Update labels
                        const labels = instance.querySelectorAll('label');
                        labels.forEach(label => {
                            const forAttr = label.getAttribute('for');
                            if (forAttr) {
                                const idMatch = forAttr.match(/custom_fields_([^_]+)_(\d+)/);
                                if (idMatch) {
                                    const fieldName = idMatch[1];
                                    label.setAttribute('for',
                                    `custom_fields_${fieldName}_${index}`);
                                }
                            }
                        });

                        // Update group title
                        const groupTitle = instance.querySelector('.group-title');
                        if (groupTitle && index > 0) {
                            const baseTitle = groupTitle.textContent.replace(/ \(\d+\)$/, '');
                            groupTitle.textContent = baseTitle + ` (${index + 1})`;
                        } else if (groupTitle && index === 0) {
                            const baseTitle = groupTitle.textContent.replace(/ \(\d+\)$/, '');
                            groupTitle.textContent = baseTitle;
                        }
                    });
                }
            }
        });

        // رفع الصور المتعددة
        document.addEventListener('DOMContentLoaded', function() {
            // معالجة رفع الصور
            document.querySelectorAll('input[type="file"][multiple]').forEach(function(input) {
                input.addEventListener('change', function(e) {
                    handleImageUpload(e.target);
                });
            });

            // معالجة السحب والإفلات
            document.querySelectorAll('.upload-area').forEach(function(area) {
                area.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    this.classList.add('dragover');
                });

                area.addEventListener('dragleave', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                });

                area.addEventListener('drop', function(e) {
                    e.preventDefault();
                    this.classList.remove('dragover');

                    const files = e.dataTransfer.files;
                    const input = this.querySelector('input[type="file"]');
                    if (input) {
                        input.files = files;
                        handleImageUpload(input);
                    }
                });

                // النقر على منطقة الرفع
                area.addEventListener('click', function(e) {
                    if (e.target === this || e.target.closest('.upload-content')) {
                        const input = this.querySelector('input[type="file"]');
                        if (input) {
                            input.click();
                        }
                    }
                });
            });
        });

        function handleImageUpload(input) {
            const files = Array.from(input.files);
            const fieldName = input.closest('.image-upload-container').dataset.fieldName;
            const previewContainer = document.getElementById('preview_' + fieldName + '_0');

            files.forEach(function(file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        addImagePreview(e.target.result, file.name, fieldName, previewContainer);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function addImagePreview(imageSrc, fileName, fieldName, container) {
            const col = document.createElement('div');
            col.className = 'col-md-3 mb-2';

            col.innerHTML = `
                    <div class="image-preview-item">
                        <img src="${imageSrc}" alt="${fileName}" class="img-fluid">
                        <button type="button" class="remove-image-btn" onclick="removeImagePreview(this, '${fieldName}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;

            container.appendChild(col);
        }

        function removeImagePreview(button, fieldName) {
            const previewItem = button.closest('.col-md-3');
            previewItem.remove();
        }

        function removeExistingImage(fieldName, imagePath) {
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                // إضافة حقل مخفي لحذف الصورة
                const container = document.getElementById('existing_' + fieldName + '_0');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'delete_images[' + fieldName + '][]';
                hiddenInput.value = imagePath;
                container.appendChild(hiddenInput);

                // إخفاء الصورة
                const imageItem = document.querySelector(`[data-image-path="${imagePath}"]`);
                if (imageItem) {
                    imageItem.style.display = 'none';
                }
            }
        }
    </script>

    <!-- Back to Category Button -->
    <div class="container mt-4 mb-4">
        <div class="text-center">
            <a href="{{ route('categories.show', $service->category->slug) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> العودة للقسم
            </a>
        </div>
    </div>
@endpush
