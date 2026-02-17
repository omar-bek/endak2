@extends('layouts.app')

@section('title', 'طلب خدمة - ' . $category->name)

@section('content')
    <!-- Category Info Alert -->
    <div class="container mt-4">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('messages.categories') }}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ __('messages.request_service') }}</li>
            </ol>
        </nav>

        <div class="custom-alert text-center mx-auto my-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong>{{ __('messages.service_from_category') }}</strong>{{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}
            @if ($selectedSubCategory)
                <br><small class="text-light">{{ __('messages.subcategory') }}
                    {{ app()->getLocale() == 'ar' ? $selectedSubCategory->name_ar : $selectedSubCategory->name_en }}</small>
            @elseif($hasSubCategories)
                <br><small class="text-warning fw-semibold">{{ __('messages.select_subcategory_warning') }}</small>
            @endif
        </div>
    </div>

    <style>
        .custom-alert {
            background: linear-gradient(135deg, #2f5c69, #3b7d8a);
            color: #fff;
            border: 1px solid #f3a446;
            border-radius: 12px;
            padding: 20px;
            max-width: 700px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-alert i {
            color: #f3a446;
            font-size: 1.3rem;
        }

        .custom-alert:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .custom-alert small {
            font-size: 0.9rem;
        }
    </style>

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

        /* Validation Styles */
        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .is-valid {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
        }

        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
        }

        .field-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .required-field-marker {
            color: #dc3545;
            font-weight: bold;
        }

        .image-preview-container {
            min-height: 50px;
        }

        .add-more-images {
            text-align: center;
            padding: 10px;
            border: 1px dashed #28a745;
            border-radius: 8px;
            background-color: #f8fff9;
        }

        .add-more-images button {
            transition: all 0.3s ease;
        }

        .add-more-images button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

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
            margin-left: 5px;
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
    <div class="container mt-5">
        <div class="row g-4 align-items-start justify-content-center">
            <div class="col-lg-4 col-md-5 order-2 order-md-2">
                <div class="card category-card text-center shadow-sm border-0">
                    <div class="card-body py-5 px-4">

                        <div class="text-start mb-4">
                            <a href="{{ route('categories.index') }}" class="btn back-btn px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i> {{ __('messages.back_to_categories') }}
                            </a>
                        </div>

                        @if ($category->image)
                            <div class="category-image-wrapper mb-4">
                                <img src="{{ asset('storage/' . $category->image) }}"
                                    alt="{{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}"
                                    class="img-fluid category-image">
                            </div>
                        @endif

                        <h2 class="category-title mb-3">
                            {{ app()->getLocale() == 'ar' ? $category->name : $category->name_en }}</h2>
                        <p class="category-description mb-4">
                            {{ app()->getLocale() == 'ar' ? $category->description : $category->description_en }}</p>
                        @if ($selectedSubCategory)
                            <div class="selected-subcategory alert-custom mt-3 p-4 rounded-3 shadow-sm text-start">
                                <i class="fas fa-check-circle text-success fs-5 me-2"></i>
                                <strong> {{ __('messages.selected_subcategory_label') }}</strong>
                                <span class="text-dark ms-1">
                                    {{ app()->getLocale() == 'ar' ? $selectedSubCategory->name_ar : $selectedSubCategory->name_en }}
                                </span>
                                @if ($selectedSubCategory->description_ar || $selectedSubCategory->description_en)
                                    <br>
                                    <small class="text-light d-block mt-2">
                                        {{ app()->getLocale() == 'ar' ? $selectedSubCategory->description_ar : $selectedSubCategory->description_en }}
                                    </small>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-7 order-1 order-md-1">
                <div class="service-request-wrapper">
                    <div class="card service-request-card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-gradient-primary text-white text-center py-4 rounded-top-4">
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-concierge-bell me-2"></i> {{ __('messages.request_service_title') }}
                            </h4>
                        </div>

                        <div class="card-body p-4">
                            <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data"
                                id="serviceRequestForm" onsubmit="return validateServiceForm(event);">
                                @csrf
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                @if ($selectedSubCategoryId)
                                    <input type="hidden" name="sub_category_id" value="{{ $selectedSubCategoryId }}">
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i>
                                        <strong> {{ __('messages.selected_subcategory_label') }}</strong>
                                        {{ app()->getLocale() == 'ar' ? $selectedSubCategory->name_ar : $selectedSubCategory->name_en }}
                                        @if ($selectedSubCategory->description_ar || $selectedSubCategory->description_en)
                                            <br><small class="text-muted">
                                                {{ app()->getLocale() == 'ar' ? $selectedSubCategory->description_ar : $selectedSubCategory->description_en }}
                                            </small>
                                        @endif
                                    </div>
                                @elseif($hasSubCategories)
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>تحذير:</strong> {{ __('messages.warning_choose_subcategory') }}
                                        <br><small>{{ __('messages.choose_subcategory_note') }}</small>
                                        <br><a href="{{ route('categories.show', $category->slug) }}"
                                            class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_categories') }}
                                        </a>
                                    </div>
                                @endif
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if ($category->voice_note_enabled)
                                    @include('partials.voice-note-recorder')
                                @endif

                                <div class="mb-3">
                                    <label for="notes" class="form-label">
                                        <i class="fas fa-sticky-note"></i> {{ __('messages.additional_notes') }}
                                    </label>
                                    <textarea name="notes" id="notes" class="form-control" rows="4"
                                        placeholder="{{ __('messages.additional_notes_placeholder') }}   ">{{ old('notes') }}</textarea>
                                </div>

                                @php
                                    $imageFields = $category->fields->where('type', 'image');
                                @endphp

                                @if ($imageFields->count())


                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($imageFields as $field)
                                                @php
                                                    $fieldName =
                                                        app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en;
                                                @endphp

                                                {{-- ✅ نفس wrapper بالظبط --}}
                                                <div class="col-12 mb-3">

                                                    {{-- ✅ نفس الـ label --}}
                                                    <label for="custom_fields_{{ $field->name }}_0" class="form-label">
                                                        <i class="fas fa-image"></i>
                                                        {{ $fieldName }}
                                                        @if ($field->is_required)
                                                            <span class="text-danger">*</span>
                                                        @endif
                                                    </label>

                                                    {{-- ✅ نفس image upload container --}}
                                                    <div class="image-upload-container"
                                                        data-field-name="{{ $field->name }}">
                                                        <div class="upload-area border border-dashed border-primary rounded p-3 text-center mb-3"
                                                            style="min-height: 120px;">
                                                            <div class="upload-content">
                                                                <i
                                                                    class="fas fa-cloud-upload-alt fa-3x text-primary mb-2"></i>
                                                                <p class="text-muted mb-2">
                                                                    {{ __('messages.image_upload_drag_drop') }}
                                                                </p>
                                                                <p class="small text-muted">
                                                                    {{ __('messages.image_upload_multiple_note') }}
                                                                </p>

                                                                <input type="file"
                                                                    name="custom_fields[{{ $field->name }}][0][]"
                                                                    id="custom_fields_{{ $field->name }}_0"
                                                                    class="form-control d-none image-file-input"
                                                                    accept="image/*" multiple
                                                                    data-field-name="{{ $field->name }}"
                                                                    {{ $field->is_required ? 'required' : '' }}
                                                                    onchange="handleImageUpload(this)">

                                                                <button type="button"
                                                                    class="btn btn-outline-primary btn-sm"
                                                                    onclick="document.getElementById('custom_fields_{{ $field->name }}_0').click()">
                                                                    <i class="fas fa-plus"></i>
                                                                    {{ __('messages.choose_images') }}
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="image-preview-container row"
                                                            id="preview_{{ $field->name }}_0"></div>

                                                        <div class="add-more-images mt-2"
                                                            id="add_more_{{ $field->name }}_0" style="display: none;">
                                                            <button type="button" class="btn btn-outline-success btn-sm"
                                                                onclick="showUploadArea('{{ $field->name }}')">
                                                                <i class="fas fa-plus"></i>
                                                                {{ __('messages.add_more_images') }}
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                @endif
                                <div class="mb-3">
                                    <label for="city_id" class="form-label">
                                        <i class="fas fa-map-marker-alt text-success"></i>
                                        {{ __('messages.choose_city') }}
                                        @if ($cities->count() > 0)
                                            <small class="text-muted">({{ $cities->count() }}
                                                {{ __('messages.available_cities_count') }} )</small>
                                        @endif
                                    </label>
                                    @if ($cities->count() > 0)
                                        <select name="city_id" id="city_id" class="form-control" required>
                                            <option value=""> {{ __('messages.choose_city') }} </option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}"
                                                    {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->name_ar ?? ($city->name_en ?? 'اسم غير محدد') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ __('messages.no_cities_available') }}
                                        </div>
                                        <input type="hidden" name="city_id" value="">
                                    @endif
                                </div>

                                @php
                                    // استثناء الحقول من نوع image لأنها معروضة في قسم منفصل
                                    $fieldsWithoutImages = $category->fields->where('type', '!=', 'image');
                                    $groupedFields = $fieldsWithoutImages->groupBy('input_group');
                                @endphp

                                @foreach ($groupedFields as $group => $fields)
                                    @php
                                        $isRepeatable = $fields->first()->is_repeatable ?? false;
                                        $groupId = $group
                                            ? 'group_' . str_replace([' ', '-'], '_', $group)
                                            : 'ungrouped';
                                        $groupName =
                                            app()->getLocale() == 'ar'
                                                ? $group
                                                : $fields->first()->input_group_en ?? $group;
                                    @endphp

                                    @if ($group)
                                        <div class="repeatable-group" data-group-id="{{ $groupId }}"
                                            data-repeatable="{{ $isRepeatable ? 'true' : 'false' }}">
                                            <div class="card mb-3 group-instance" data-instance="0">
                                                <div
                                                    class="card-header bg-light d-flex justify-content-between align-items-center">
                                                    <h5 class="mb-0 group-title">{{ $groupName }}</h5>
                                                    @if ($isRepeatable)
                                                        <div class="group-controls">
                                                            <button type="button"
                                                                class="btn btn-sm btn-success add-group-instance"
                                                                data-group-id="{{ $groupId }}">
                                                                <i class="fas fa-plus"></i> {{ __('messages.add_group') }}
                                                            </button>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger remove-group-instance d-none"
                                                                data-group-id="{{ $groupId }}">
                                                                <i class="fas fa-trash"></i>
                                                                {{ __('messages.remove_group') }}
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                    @endif

                                    @foreach ($fields as $field)
                                        @php
                                            $fieldName = app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en;
                                            $fieldValue =
                                                app()->getLocale() == 'ar'
                                                    ? $field->value ?? $field->name_ar
                                                    : $field->value_en ?? $field->name_en;
                                        @endphp

                                        @if ($field->type === 'title')
                                            <div class="mb-3">
                                                <h4 class="text-dark border-bottom pb-2">
                                                    <i class="fas fa-heading"></i> {{ $fieldValue }}
                                                </h4>
                                            </div>
                                        @else
                                            <div class="col-12 mb-3">
                                                @if ($field->type === 'checkbox')
                                                    {{-- Checkbox logic handles the label inside the div --}}
                                                @else
                                                    <label for="custom_fields_{{ $field->name }}_0" class="form-label">
                                                        <i class="fas fa-{{ $field->getTypeIconAttribute() }}"></i>
                                                        {{ $fieldName }}
                                                        @if ($field->is_required)
                                                            <span class="text-danger">*</span>
                                                        @endif
                                                    </label>
                                                @endif

                                                @if ($field->type === 'select' && is_array($field->options))
                                                    <select name="custom_fields[{{ $field->name }}][0]"
                                                        id="custom_fields_{{ $field->name }}_0" class="form-control"
                                                        {{ $field->is_required ? 'required' : '' }}>
                                                        <option value="" disabled selected>
                                                            {{ __('messages.custom_fields_select_placeholder') }}</option>
                                                        @foreach ($field->options as $option)
                                                            <option value="{{ $option }}"
                                                                {{ old('custom_fields.' . $field->name . '.0') == $option ? 'selected' : '' }}>
                                                                {{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                @elseif($field->type === 'checkbox')
                                                    <div class="form-check ">
                                                        <input type="checkbox"
                                                            name="custom_fields[{{ $field->name }}][0]"
                                                            id="custom_fields_{{ $field->name }}_0" value="1"
                                                            class="form-check-input"
                                                            {{ old('custom_fields.' . $field->name . '.0') == '1' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="custom_fields_{{ $field->name }}_0">{{ $fieldName }}</label>
                                                    </div>
                                                @elseif($field->type === 'date')
                                                    <input type="date" name="custom_fields[{{ $field->name }}][0]"
                                                        id="custom_fields_{{ $field->name }}_0" class="form-control"
                                                        value="{{ old('custom_fields.' . $field->name . '.0') }}"
                                                        {{ $field->is_required ? 'required' : '' }}>
                                                @elseif($field->type === 'time')
                                                    <input type="time" name="custom_fields[{{ $field->name }}][0]"
                                                        id="custom_fields_{{ $field->name }}_0" class="form-control"
                                                        value="{{ old('custom_fields.' . $field->name . '.0') }}"
                                                        {{ $field->is_required ? 'required' : '' }}>
                                                @elseif($field->type === 'textarea')
                                                    <textarea name="custom_fields[{{ $field->name }}][0]" id="custom_fields_{{ $field->name }}_0"
                                                        class="form-control" rows="3" {{ $field->is_required ? 'required' : '' }}>{{ old('custom_fields.' . $field->name . '.0') }}</textarea>
                                                @elseif($field->type !== 'image')
                                                    <input type="{{ $field->type }}"
                                                        name="custom_fields[{{ $field->name }}][0]"
                                                        id="custom_fields_{{ $field->name }}_0" class="form-control"
                                                        value="{{ old('custom_fields.' . $field->name . '.0') }}"
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



            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm" id="submitBtn">
                <i class="fas fa-paper-plane me-1"></i> {{ __('messages.submit_request') }}
            </button>
            </form>

            <!-- Validation Error Alert -->
            <div id="validationAlert" class="alert alert-danger mt-3" style="display: none;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong id="validationMessage"></strong>
                <ul id="validationErrors" class="mb-0 mt-2"></ul>
            </div>
        </div>
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
                        <i class="fas fa-plus"></i> {{ __('messages.add_group') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-danger remove-group-instance">
                        <i class="fas fa-trash"></i> {{ __('messages.del_group') }}
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Fields will be cloned here -->
            </div>
        </div>
    </template>

    <style>
        .category-card {
            background: linear-gradient(135deg, #2f5c69, #3b7d8a);
            color: #fff;
            border-radius: 18px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .back-btn {
            border: 1px solid #f3a446;
            color: #f3a446;
            background: transparent;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #f3a446;
            color: #fff;
            transform: scale(1.05);
        }

        .category-image-wrapper {
            display: inline-block;
            overflow: hidden;
            border-radius: 15px;
            border: 3px solid #f3a446;
        }

        .category-image {
            border-radius: 12px;
            max-width: 220px;
            transition: transform 0.4s ease;
        }

        .category-image-wrapper:hover .category-image {
            transform: scale(1.07);
        }

        .category-title {
            color: #f3a446;
            font-weight: 700;
            font-size: 1.8rem;
        }

        .category-description {
            color: #e0e0e0;
            font-size: 1rem;
            line-height: 1.6;
        }

        .alert-custom {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #f3a446;
        }

        .service-request-wrapper {
            background: linear-gradient(135deg, #f9fcff 0%, #e8f2f7 100%);
            border-radius: 15px;
            padding: 10px;
        }

        .service-request-card {
            border-radius: 1.2rem;
            background-color: #fff;
        }

        .bg-gradient-primary {
            background: linear-gradient(90deg, #2f5c69, #1d3e47);
        }

        .form-control {
            border: 1px solid #ccd7df;
            border-radius: 0.7rem;
            padding: 0.7rem 1rem;
        }

        .form-control:focus {
            border-color: #2f5c69;
            box-shadow: 0 0 6px rgba(47, 92, 105, 0.3);
        }

        .btn-primary {
            background-color: #2f5c69;
            border-color: #2f5c69;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #f3a446;
            border-color: #f3a446;
            transform: scale(1.02);
        }

        @media (max-width: 768px) {

            .col-lg-4,
            .col-md-5 {
                order: -1 !important;
            }

            .category-card {
                margin-bottom: 1rem;
            }
        }
    </style>

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

            // تعريف handleImageUpload قبل استخدامها (يجب أن تكون global)
            window.handleImageUpload = function(input) {
                console.log('handleImageUpload called with input:', input);

                const files = Array.from(input.files);
                console.log('Files array:', files);

                // البحث عن fieldName بطريقة مختلفة
                let fieldName = null;
                let previewContainer = null;

                console.log('Input element:', input);
                console.log('Input id:', input.id);
                console.log('Input name:', input.name);

                // الطريقة الأولى: البحث في image-upload-container
                const container = input.closest('.image-upload-container');
                console.log('Container found:', container);
                if (container) {
                    fieldName = container.dataset.fieldName || container.getAttribute('data-field-name');
                    console.log('Field name from container:', fieldName);
                    if (fieldName) {
                        previewContainer = document.getElementById('preview_' + fieldName + '_0');
                        console.log('Preview container from container method:', previewContainer);
                    }
                }

                // الطريقة الثانية: البحث في name attribute
                if (!fieldName && input.name) {
                    const nameMatch = input.name.match(/custom_fields\[([^\]]+)\]/);
                    console.log('Name match result:', nameMatch);
                    if (nameMatch && nameMatch[1]) {
                        fieldName = nameMatch[1];
                        previewContainer = document.getElementById('preview_' + fieldName + '_0');
                        console.log('Field name from name attribute:', fieldName);
                        console.log('Preview container from name method:', previewContainer);
                    }
                }

                // الطريقة الثالثة: البحث في id attribute
                if (!fieldName && input.id) {
                    const idMatch = input.id.match(/custom_fields_([^_]+)_/);
                    console.log('ID match result:', idMatch);
                    if (idMatch && idMatch[1]) {
                        fieldName = idMatch[1];
                        previewContainer = document.getElementById('preview_' + fieldName + '_0');
                        console.log('Field name from id attribute:', fieldName);
                        console.log('Preview container from id method:', previewContainer);
                    }
                }

                console.log('Handling upload for field:', fieldName);
                console.log('Preview container:', previewContainer);
                console.log('Files to process:', files.length);

                if (!previewContainer) {
                    console.error('Preview container not found for field:', fieldName);
                    console.log('Available preview containers:');
                    const allContainers = document.querySelectorAll('[id^="preview_"]');
                    allContainers.forEach(container => console.log('-', container.id));
                    alert('خطأ: لم يتم العثور على حاوية المعاينة');
                    return;
                }

                if (files.length === 0) {
                    console.log('No files selected');
                    return;
                }

                files.forEach(function(file, index) {
                    console.log('Processing file', index + 1, ':', file.name, 'Type:', file.type);

                    if (file.type.startsWith('image/')) {
                        console.log('Processing image:', file.name);
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            console.log('Image loaded, adding preview for:', file.name);
                            addImagePreview(e.target.result, file.name, fieldName,
                            previewContainer);
                        };
                        reader.onerror = function(e) {
                            console.error('Error reading file:', file.name, e);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        console.log('Skipping non-image file:', file.name);
                        alert('تم تجاهل الملف: ' + file.name + ' (ليس صورة)');
                    }
                });
            };

            // تعريف addImagePreview قبل استخدامها
            function addImagePreview(imageSrc, fileName, fieldName, container) {
                console.log('Adding image preview for:', fileName);
                console.log('Field name:', fieldName);
                console.log('Container:', container);
                console.log('Container element:', container ? container.constructor.name : 'null');

                if (!container) {
                    console.error('Container is null or undefined!');
                    alert('خطأ: حاوية المعاينة غير موجودة');
                    return;
                }

                try {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 mb-2';

                    col.innerHTML = `
                <div class="image-preview-item">
                    <img src="${imageSrc}" alt="${fileName}" class="img-fluid">
                    <button type="button" class="remove-image-btn" data-field-name="${fieldName}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

                    console.log('Created preview element:', col);
                    container.appendChild(col);
                    console.log('Image preview added to container. Container children count:', container.children
                        .length);
                    console.log('Image preview added successfully');

                    // إضافة event listener لزر الحذف الجديد
                    const removeBtn = col.querySelector('.remove-image-btn');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            console.log('Remove button clicked for field:', fieldName);
                            removeImagePreview(this, fieldName);
                        });
                        console.log('Event listener added to new remove button');
                    }

                    // إخفاء منطقة الرفع وإظهار زر إضافة المزيد
                    const uploadArea = container.closest('.image-upload-container').querySelector('.upload-area');
                    const addMoreBtn = document.getElementById('add_more_' + fieldName + '_0');

                    if (uploadArea) {
                        uploadArea.style.display = 'none';
                        console.log('Upload area hidden');
                    }

                    if (addMoreBtn) {
                        addMoreBtn.style.display = 'block';
                        console.log('Add more button shown');
                    }

                    // إظهار رسالة نجاح
                    console.log('Image preview completed successfully');

                } catch (error) {
                    console.error('Error adding image preview:', error);
                    alert('خطأ في إضافة معاينة الصورة: ' + error.message);
                }
            }

            // تعريف removeImagePreview قبل استخدامها
            function removeImagePreview(button, fieldName) {
                console.log('Removing image preview for field:', fieldName);

                const previewItem = button.closest('.col-md-3');
                if (previewItem) {
                    previewItem.remove();
                    console.log('Preview item removed');
                }

                // إظهار منطقة الرفع مرة أخرى إذا لم تعد هناك صور
                const container = document.getElementById('preview_' + fieldName + '_0');
                if (container) {
                    const remainingImages = container.children.length;
                    console.log('Remaining images:', remainingImages);

                    if (remainingImages === 0) {
                        // لا توجد صور متبقية - إظهار منطقة الرفع
                        const uploadArea = container.closest('.image-upload-container').querySelector(
                            '.upload-area');
                        const addMoreBtn = document.getElementById('add_more_' + fieldName + '_0');

                        if (uploadArea) {
                            uploadArea.style.display = 'block';
                            console.log('Upload area shown (no images left)');
                        }

                        if (addMoreBtn) {
                            addMoreBtn.style.display = 'none';
                            console.log('Add more button hidden (no images left)');
                        }
                    } else {
                        // لا تزال هناك صور - إظهار زر إضافة المزيد
                        const addMoreBtn = document.getElementById('add_more_' + fieldName + '_0');
                        if (addMoreBtn) {
                            addMoreBtn.style.display = 'block';
                            console.log('Add more button shown (images still exist)');
                        }
                    }
                }
            }

            // رفع الصور المتعددة
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM loaded, initializing image upload...');

                // تعريف دالة لإعداد event listeners
                function setupImageUploadListeners() {
                    console.log('Setting up image upload listeners...');

                    // البحث عن جميع حقول الصور
                    const allFileInputs = document.querySelectorAll('input[type="file"]');
                    console.log('Found', allFileInputs.length, 'total file inputs');

                    allFileInputs.forEach(function(input) {
                        // التحقق من أن الـ input خاص بالصور
                        if (input.accept && (input.accept.includes('image') || input.accept ===
                                'image/*')) {
                            console.log('Setting up image input:', input.id, input.name);

                            // إضافة event listener مباشر (بدون clone)
                            input.addEventListener('change', function(e) {
                                console.log('File input changed for:', input.id);
                                console.log('Files selected:', e.target.files.length);
                                e.preventDefault();
                                e.stopPropagation();
                                handleImageUpload(e.target);
                                return false;
                            }, true);

                            console.log('Event listener added to:', input.id);
                        }
                    });
                }

                // إعداد listeners مباشرة
                setupImageUploadListeners();

                // انتظار قليل وإعادة المحاولة للتأكد
                setTimeout(function() {
                    setupImageUploadListeners();
                }, 500);

                // معالجة السحب والإفلات
                setTimeout(function() {
                    const uploadAreas = document.querySelectorAll('.upload-area');
                    console.log('Found', uploadAreas.length, 'upload areas');

                    uploadAreas.forEach(function(area) {
                        console.log('Setting up upload area');

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
                            if (e.target === this || e.target.closest(
                                    '.upload-content')) {
                                const input = this.querySelector(
                                    'input[type="file"]');
                                if (input) {
                                    input.click();
                                }
                            }
                        });
                    });
                }, 500);
            });

            // دالة لإظهار منطقة الرفع
            function showUploadArea(fieldName) {
                console.log('showUploadArea called for field:', fieldName);

                // البحث عن العناصر بطريقة مختلفة
                const container = document.querySelector(`[data-field-name="${fieldName}"]`);
                if (!container) {
                    console.error('Container not found for field:', fieldName);
                    return;
                }

                const uploadArea = container.querySelector('.upload-area');
                const addMoreBtn = document.getElementById('add_more_' + fieldName + '_0');
                const fileInput = container.querySelector('input[type="file"]');

                console.log('Upload area found:', uploadArea);
                console.log('Add more button found:', addMoreBtn);
                console.log('File input found:', fileInput);

                if (uploadArea) {
                    uploadArea.style.display = 'block';
                    console.log('Upload area shown');
                }

                if (addMoreBtn) {
                    addMoreBtn.style.display = 'none';
                    console.log('Add more button hidden');
                }

                // إعادة تعيين file input
                if (fileInput) {
                    fileInput.value = '';
                    console.log('File input reset');
                }
            }

            // دالة اختبار
            function testImageUpload() {
                console.log('Testing image upload functionality...');
                const fileInputs = document.querySelectorAll('input[type="file"][multiple]');
                const allFileInputs = document.querySelectorAll('input[type="file"]');
                const uploadAreas = document.querySelectorAll('.upload-area');
                const previewContainers = document.querySelectorAll('.image-preview-container');
                const imageContainers = document.querySelectorAll('.image-upload-container');

                console.log('Multiple file inputs found:', fileInputs.length);
                console.log('All file inputs found:', allFileInputs.length);
                console.log('Upload areas found:', uploadAreas.length);
                console.log('Preview containers found:', previewContainers.length);
                console.log('Image containers found:', imageContainers.length);

                fileInputs.forEach((input, index) => {
                    console.log(`Multiple file input ${index + 1}:`, input.id, input.name, input.type);
                });

                allFileInputs.forEach((input, index) => {
                    console.log(`All file input ${index + 1}:`, input.id, input.name, input.type);
                });

                uploadAreas.forEach((area, index) => {
                    console.log(`Upload area ${index + 1}:`, area);
                });

                previewContainers.forEach((container, index) => {
                    console.log(`Preview container ${index + 1}:`, container.id);
                });

                imageContainers.forEach((container, index) => {
                    console.log(`Image container ${index + 1}:`, container.dataset.fieldName);
                });
            }

            // تشغيل الاختبار بعد تحميل الصفحة
            setTimeout(testImageUpload, 1000);
        });

        // Pass required fields data from backend to frontend
        @php
            $requiredFieldsData = [];
            foreach ($category->fields->where('is_active', true)->where('is_required', true) as $field) {
                $requiredFieldsData[] = [
                    'name' => $field->name,
                    'type' => $field->type,
                    'name_ar' => $field->name_ar,
                    'name_en' => $field->name_en,
                    'is_repeatable' => $field->is_repeatable ?? false,
                    'input_group' => $field->input_group,
                ];
            }
        @endphp

        const requiredCustomFields = @json($requiredFieldsData);
        console.log('Required custom fields from backend:', requiredCustomFields);

        // Define validation function globally before DOMContentLoaded
        window.validateServiceForm = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            const form = document.getElementById('serviceRequestForm');
            if (!form) {
                console.error('Form not found');
                return false;
            }

            console.log('Form submit intercepted - preventing default');

            // Clear previous validation
            if (typeof clearValidation === 'function') {
                clearValidation();
            }

            // Validate form
            if (typeof validateForm === 'function') {
                const errors = validateForm();
                console.log('Validation errors:', errors.length);

                if (errors.length > 0) {
                    console.log('Showing validation errors - preventing submission');
                    // Show validation errors
                    if (typeof showValidationErrors === 'function') {
                        showValidationErrors(errors);
                    }

                    // Scroll to first error
                    const firstErrorField = document.querySelector('.is-invalid');
                    if (firstErrorField) {
                        firstErrorField.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstErrorField.focus();
                    }
                    return false;
                }
            }

            console.log('All validations passed, submitting form');
            // Remove onsubmit to allow submission
            form.onsubmit = null;
            // All validations passed, submit form
            form.submit();
            return false;
        };

        // Pass required fields data from backend to frontend
        @php
            $requiredFieldsData = [];
            foreach ($category->fields->where('is_active', true)->where('is_required', true) as $field) {
                $requiredFieldsData[] = [
                    'name' => $field->name,
                    'type' => $field->type,
                    'name_ar' => $field->name_ar,
                    'name_en' => $field->name_en,
                    'is_repeatable' => $field->is_repeatable ?? false,
                    'input_group' => $field->input_group,
                ];
            }
        @endphp

        const requiredCustomFields = @json($requiredFieldsData);
        console.log('Required custom fields:', requiredCustomFields);

        // Form Validation - Must be in separate DOMContentLoaded to ensure form exists
        document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded - Initializing form validation');

        // Form Validation
        const form = document.getElementById('serviceRequestForm');
        const validationAlert = document.getElementById('validationAlert');
        const validationMessage = document.getElementById('validationMessage');
        const validationErrors = document.getElementById('validationErrors');

        // Validation messages in both languages
        const validationMessages = {
            ar: {
                requiredField: 'هذا الحقل مطلوب',
                completeRequiredFields: 'يرجى إكمال جميع الحقول المطلوبة قبل الإرسال',
                imageRequired: 'هذا الحقل مطلوب (يجب رفع صورة واحدة على الأقل)',
                fieldRequired: 'هذا الحقل مطلوب',
                error: 'خطأ'
            },
            en: {
                requiredField: 'This field is required',
                completeRequiredFields: 'Please complete all required fields before submitting',
                imageRequired: 'This field is required (at least one image must be uploaded)',
                fieldRequired: 'This field is required',
                error: 'Error'
            }
        };

        // Get current language
        const currentLang = '{{ app()->getLocale() }}';
        const messages = validationMessages[currentLang] || validationMessages.ar;

        if (!form) {
            console.error('Form not found: serviceRequestForm');
            return;
        }

        if (!validationAlert || !validationMessage || !validationErrors) {
            console.error('Validation elements not found');
            return;
        }

        console.log('Form validation initialized');

        // Update the global validation function with access to local functions
        window.validateServiceForm = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            console.log('Form submit intercepted - preventing default');

            // Clear previous validation
            clearValidation();

            // Validate form
            const errors = validateForm();
            console.log('Validation errors:', errors.length);

            if (errors.length > 0) {
                console.log('Showing validation errors - preventing submission');
                // Show validation errors
                showValidationErrors(errors);

                // Scroll to first error
                const firstErrorField = document.querySelector('.is-invalid');
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    firstErrorField.focus();
                }
                return false;
            } else {
                console.log('All validations passed, submitting form');
                // Remove onsubmit to allow submission
                form.onsubmit = null;
                // All validations passed, submit form
                form.submit();
                return false;
            }
        };

        // Also add event listener as backup
        form.addEventListener('submit', function(e) {
            return window.validateServiceForm(e);
        });

        // Real-time validation on field change
        form.addEventListener('input', function(e) {
            if (e.target.hasAttribute('required') || e.target.closest('[data-required]')) {
                validateField(e.target);
            }
        });

        form.addEventListener('change', function(e) {
            if (e.target.hasAttribute('required') || e.target.closest('[data-required]')) {
                validateField(e.target);
            }
        });

        // Validation functions
        function validateForm() {
            const errors = [];
            const formElement = document.getElementById('serviceRequestForm');
            const currentLangForValidation = '{{ app()->getLocale() }}';

            if (!formElement) {
                console.error('Form not found in validateForm');
                return errors;
            }

            // Validate city selection
            const citySelect = formElement.querySelector('#city_id');
            if (citySelect && citySelect.hasAttribute('required')) {
                if (!citySelect.value || citySelect.value === '') {
                    const cityLabel = '{{ __('messages.choose_city') }}';
                    errors.push({
                        field: citySelect,
                        message: cityLabel + ' - ' + messages.requiredField
                    });
                }
            }

            // Validate all required text inputs (including in repeatable groups)
            const requiredInputs = formElement.querySelectorAll(
                'input[required]:not([type="file"]):not([type="hidden"]):not([type="checkbox"])');
            requiredInputs.forEach(input => {
                if (!input.value || input.value.trim() === '') {
                    const fieldName = getFieldLabel(input);
                    errors.push({
                        field: input,
                        message: fieldName + ' - ' + messages.requiredField
                    });
                }
            });

            // Validate all required select fields (including in repeatable groups)
            const requiredSelects = formElement.querySelectorAll('select[required]');
            requiredSelects.forEach(select => {
                if (!select.value || select.value === '' || select.value === null || select.value ===
                    '0') {
                    const fieldName = getFieldLabel(select);
                    errors.push({
                        field: select,
                        message: fieldName + ' - ' + messages.requiredField
                    });
                }
            });

            // Validate all required textareas (including in repeatable groups)
            const requiredTextareas = formElement.querySelectorAll('textarea[required]');
            requiredTextareas.forEach(textarea => {
                if (!textarea.value || textarea.value.trim() === '') {
                    const fieldName = getFieldLabel(textarea);
                    errors.push({
                        field: textarea,
                        message: fieldName + ' - ' + messages.requiredField
                    });
                }
            });

            // Validate required file inputs (images) - including all instances in repeatable groups
            const requiredFileInputs = formElement.querySelectorAll('input[type="file"][required]');
            requiredFileInputs.forEach(fileInput => {
                const nameAttr = fileInput.getAttribute('name');
                if (!nameAttr) return;

                // Extract field name and instance number
                const nameMatch = nameAttr.match(/custom_fields\[([^\]]+)\]\[(\d+)\]/);
                if (nameMatch) {
                    const fieldName = nameMatch[1];
                    const instanceNum = nameMatch[2];

                    // Check if files are selected
                    const hasFiles = fileInput.files && fileInput.files.length > 0;

                    // Check if preview exists
                    const previewContainer = document.getElementById('preview_' + fieldName + '_' +
                        instanceNum);
                    const hasPreviews = previewContainer && previewContainer.children.length > 0;

                    if (!hasFiles && !hasPreviews) {
                        const fieldLabel = getFieldLabel(fileInput);
                        errors.push({
                            field: fileInput,
                            message: fieldLabel + ' - ' + messages.imageRequired
                        });
                    }
                } else {
                    // Fallback for fields without instance number
                    const hasFiles = fileInput.files && fileInput.files.length > 0;
                    if (!hasFiles) {
                        const fieldLabel = getFieldLabel(fileInput);
                        errors.push({
                            field: fileInput,
                            message: fieldLabel + ' - ' + messages.imageRequired
                        });
                    }
                }
            });

            // Validate required checkboxes (including in all repeatable group instances)
            const requiredCheckboxes = formElement.querySelectorAll('input[type="checkbox"][required]');
            requiredCheckboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    const fieldName = getFieldLabel(checkbox);
                    errors.push({
                        field: checkbox,
                        message: fieldName + ' - ' + messages.requiredField
                    });
                }
            });

            // Validate all required custom fields from backend data
            if (typeof requiredCustomFields !== 'undefined' && requiredCustomFields.length > 0) {
                console.log('Validating required custom fields from backend data:', requiredCustomFields
                    .length);

                const currentLangForValidation = '{{ app()->getLocale() }}';

                requiredCustomFields.forEach(fieldData => {
                    const fieldName = fieldData.name;
                    const fieldType = fieldData.type;
                    const fieldLabel = currentLangForValidation === 'ar' ? fieldData.name_ar : fieldData
                        .name_en;
                    const isRepeatable = fieldData.is_repeatable;

                    // Find all instances of this field (including repeatable groups)
                    const fieldInputs = formElement.querySelectorAll(
                        `input[name*="custom_fields[${fieldName}]"],
                         select[name*="custom_fields[${fieldName}]"],
                         textarea[name*="custom_fields[${fieldName}]"]`
                    );

                    if (fieldInputs.length === 0) {
                        // Field not found in form - might be missing
                        console.warn('Required field not found in form:', fieldName);
                        return;
                    }

                    let hasValidValue = false;

                    // Check all instances
                    fieldInputs.forEach(input => {
                        if (fieldType === 'image') {
                            // For images, check if files are selected or preview exists
                            const nameMatch = input.getAttribute('name').match(
                                /custom_fields\[([^\]]+)\]\[(\d+)\]/);
                            if (nameMatch) {
                                const instanceNum = nameMatch[2];
                                const previewContainer = document.getElementById('preview_' +
                                    fieldName + '_' + instanceNum);
                                const hasFiles = input.files && input.files.length > 0;
                                const hasPreviews = previewContainer && previewContainer
                                    .children.length > 0;

                                if (hasFiles || hasPreviews) {
                                    hasValidValue = true;
                                }
                            }
                        } else if (fieldType === 'checkbox') {
                            // For checkboxes, check if checked
                            if (input.checked) {
                                hasValidValue = true;
                            }
                        } else if (fieldType === 'select') {
                            // For selects, check if value is selected
                            if (input.value && input.value !== '' && input.value !== null &&
                                input.value !== '0') {
                                hasValidValue = true;
                            }
                        } else {
                            // For text, number, textarea, date, time
                            if (input.value && input.value.trim() !== '') {
                                hasValidValue = true;
                            }
                        }
                    });

                    if (!hasValidValue) {
                        // Find the first input for this field to highlight
                        const firstInput = fieldInputs[0];
                        errors.push({
                            field: firstInput,
                            message: fieldLabel + ' - ' + (fieldType === 'image' ? messages
                                .imageRequired : messages.requiredField)
                        });
                    }
                });
            }

            console.log('Validation completed. Found', errors.length, 'errors');
            return errors;
        }

        function validateField(field) {
            const isValid = checkFieldValidity(field);

            if (isValid) {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                removeFieldError(field);
            } else {
                field.classList.remove('is-valid');
                field.classList.add('is-invalid');
                showFieldError(field);
            }
        }

        function checkFieldValidity(field) {
            if (field.type === 'file') {
                const fieldName = field.getAttribute('name').match(/custom_fields\[([^\]]+)\]/);
                if (fieldName) {
                    const previewContainer = document.getElementById('preview_' + fieldName[1] + '_0');
                    const hasFiles = field.files && field.files.length > 0;
                    const hasPreviews = previewContainer && previewContainer.children.length > 0;
                    return hasFiles || hasPreviews;
                }
                return field.files && field.files.length > 0;
            } else if (field.type === 'checkbox') {
                return field.checked;
            } else if (field.tagName === 'SELECT') {
                return field.value && field.value !== '' && field.value !== null;
            } else {
                return field.value && field.value.trim() !== '';
            }
        }

        function getFieldLabel(field) {
            // Try to get label from associated label element
            const label = field.closest('.col-12, .mb-3, .form-group')?.querySelector('label');
            if (label) {
                let labelText = label.textContent.trim();
                // Remove asterisk and icons
                labelText = labelText.replace(/\*/g, '').replace(/\[.*?\]/g, '').trim();
                return labelText || field.name || field.id;
            }

            // Try to get from placeholder
            if (field.placeholder) {
                return field.placeholder;
            }

            // Fallback to name or id
            return field.name || field.id || '{{ __('messages.error') }}';
        }

        function showValidationErrors(errors) {
            validationAlert.style.display = 'block';
            validationMessage.textContent = messages.error + ': ' + messages.completeRequiredFields;

            validationErrors.innerHTML = '';
            errors.forEach(error => {
                const li = document.createElement('li');
                li.textContent = error.message;
                validationErrors.appendChild(li);

                // Mark field as invalid
                error.field.classList.add('is-invalid');
                error.field.classList.remove('is-valid');

                // Show field error
                showFieldError(error.field);
            });
        }

        function showFieldError(field) {
            // Remove existing error message
            removeFieldError(field);

            // Create error message element
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.textContent = messages.fieldRequired;

            // Insert after field
            const fieldContainer = field.closest('.col-12, .mb-3, .form-group') || field.parentElement;
            if (fieldContainer) {
                fieldContainer.appendChild(errorDiv);
            }
        }

        function removeFieldError(field) {
            const fieldContainer = field.closest('.col-12, .mb-3, .form-group') || field.parentElement;
            if (fieldContainer) {
                const existingError = fieldContainer.querySelector('.field-error');
                if (existingError) {
                    existingError.remove();
                }
            }
        }

        function clearValidation() {
            // Clear all validation classes
            const invalidFields = document.querySelectorAll('.is-invalid');
            invalidFields.forEach(field => {
                field.classList.remove('is-invalid');
            });

            const validFields = document.querySelectorAll('.is-valid');
            validFields.forEach(field => {
                field.classList.remove('is-valid');
            });

            // Remove all error messages
            const errorMessages = document.querySelectorAll('.field-error');
            errorMessages.forEach(error => error.remove());

            // Hide validation alert
            validationAlert.style.display = 'none';
        }

        // إضافة event listener مباشر للعناصر الموجودة (احتياطي)
        setTimeout(function() {
            console.log('Adding backup event listeners...');

            // البحث عن جميع حقول الصور وإضافة event listeners مباشرة
            const allFileInputs = document.querySelectorAll('input[type="file"]');
            console.log('Found', allFileInputs.length, 'total file inputs for backup listeners');

            allFileInputs.forEach(function(input) {
                // التحقق من أن الـ input خاص بالصور
                if (input.accept && (input.accept.includes('image') || input.accept ===
                        'image/*')) {
                    console.log('Adding backup listener to:', input.id, input.name);

                    // إضافة event listener مباشر
                    input.addEventListener('change', function(e) {
                        console.log('Backup listener triggered for:', input.id);
                        console.log('Files selected:', e.target.files.length);
                        handleImageUpload(e.target);
                    }, true);
                }
            });

            // إضافة event listeners للأزرار
            const addMoreButtons = document.querySelectorAll('[onclick*="showUploadArea"]');
            console.log('Found', addMoreButtons.length, 'add more buttons');

            addMoreButtons.forEach(function(button) {
                console.log('Add more button found:', button);
                // إزالة onclick attribute وإضافة event listener
                const onclickAttr = button.getAttribute('onclick');
                if (onclickAttr) {
                    const fieldNameMatch = onclickAttr.match(/showUploadArea\('([^']+)'\)/);
                    if (fieldNameMatch) {
                        const fieldName = fieldNameMatch[1];
                        button.removeAttribute('onclick');
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            console.log('Add more button clicked for field:',
                                fieldName);
                            showUploadArea(fieldName);
                        });
                        console.log('Event listener added to add more button for field:',
                            fieldName);
                    }
                }
            });

            // إضافة event listeners لأزرار الحذف
            const removeButtons = document.querySelectorAll('[onclick*="removeImagePreview"]');
            console.log('Found', removeButtons.length, 'remove buttons');

            removeButtons.forEach(function(button) {
                console.log('Remove button found:', button);
                // إزالة onclick attribute وإضافة event listener
                const onclickAttr = button.getAttribute('onclick');
                if (onclickAttr) {
                    const fieldNameMatch = onclickAttr.match(
                        /removeImagePreview\(this, '([^']+)'\)/);
                    if (fieldNameMatch) {
                        const fieldName = fieldNameMatch[1];
                        button.removeAttribute('onclick');
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            console.log('Remove button clicked for field:',
                                fieldName);
                            removeImagePreview(this, fieldName);
                        });
                        console.log('Event listener added to remove button for field:',
                            fieldName);
                    }
                }
            });
        }, 2000);
        });

        // Close the validation DOMContentLoaded
        });
    </script>

    <!-- Back to Category Button -->
    <div class="container mt-4 mb-4">
        <div class="text-center">
            <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> العودة للقسم
            </a>
        </div>
    </div>
@endpush
