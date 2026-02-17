@extends('layouts.app')

@section('title', $service->title)

<style>
    .voice-note-player {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 2rem;
        margin-top: 1rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .voice-note-player audio {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        height: 70px;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        display: block;
        background: #fff;
        border: 2px solid #e9ecef;
    }

    .voice-note-player audio::-webkit-media-controls-panel {
        background-color: #f8f9fa;
    }

    .voice-note-player audio::-webkit-media-controls-play-button {
        background-color: #007bff;
        border-radius: 50%;
        margin: 0 10px;
    }

    .voice-note-player audio::-webkit-media-controls-current-time-display,
    .voice-note-player audio::-webkit-media-controls-time-remaining-display {
        font-size: 14px;
        font-weight: bold;
        color: #495057;
    }

    .voice-note-player h4 {
        color: #495057;
        margin-bottom: 1rem;
    }

    .voice-note-player h4 i {
        margin-left: 0.5rem;
    }
</style>

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('categories.index') }}">{{ __('messages.categories') }}</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('categories.show', $service->category->slug) }}">{{ $service->category->name }}</a>
                                </li>
                                <li class="breadcrumb-item active">{{ $service->title }}</li>
                            </ol>
                        </nav>

                        @if ($service->image)
                            <img src="{{ $service->category->image_url }}" alt="{{ $service->title }}"
                                class="img-fluid rounded mb-3" style="max-height: 400px; width: 100%; object-fit: cover;"
                                onerror="this.onerror=null; this.src='{{ asset('images/default-service.svg') }}';">
                        @endif

                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h1 class="mb-0">{{ $service->title }}</h1>
                            @auth
                                @if (auth()->id() === $service->user_id)
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> {{ __('messages.edit_button') }}
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="if(confirm('{{ __('messages.delete_confirm_prompt') }}')) { document.getElementById('delete-form').submit(); }">
                                            <i class="fas fa-trash"></i> {{ __('messages.delete_button') }}
                                        </button>
                                        <form id="delete-form" action="{{ route('services.destroy', $service->id) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-0 shadow-sm h-100" style="background-color: #e9f8f7;">
                                            <div class="card-body text-center">
                                                <i class="fas fa-user mb-2" style="font-size: 1.6rem; color: #008b8b;"></i>
                                                <h6 class="text-muted mb-1">{{ __('messages.provider_title') }}</h6>
                                                <span class="fw-bold"
                                                    style="color: #d4af37;">{{ $service->user->name }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="card border-0 shadow-sm h-100" style="background-color: #e9f8f7;">
                                            <div class="card-body text-center">
                                                <i class="fas fa-calendar mb-2"
                                                    style="font-size: 1.6rem; color: #008b8b;"></i>
                                                <h6 class="text-muted mb-1">{{ __('messages.publish_date') }}</h6>
                                                <span class="fw-bold"
                                                    style="color: #d4af37;">{{ $service->created_at->format('Y-m-d') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="card border-0 shadow-sm h-100" style="background-color: #e9f8f7;">
                                            <div class="card-body text-center">
                                                <i class="fas fa-folder mb-2"
                                                    style="font-size: 1.6rem; color: #008b8b;"></i>
                                                <h6 class="text-muted mb-1">{{ __('messages.category_title') }}</h6>
                                                <span class="fw-bold"
                                                    style="color: #d4af37;">{{ $service->category->name }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($service->subCategory)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-0 shadow-sm h-100" style="background-color: #e9f8f7;">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-layer-group mb-2"
                                                        style="font-size: 1.6rem; color: #008b8b;"></i>
                                                    <h6 class="text-muted mb-1">{{ __('messages.subcategory_title') }}</h6>
                                                    <span class="fw-bold" style="color: #d4af37;">
                                                        {{ app()->getLocale() == 'ar' ? $service->subCategory->name_ar : $service->subCategory->name_en }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($service->location)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-0 shadow-sm h-100" style="background-color: #e9f8f7;">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-map-marker-alt mb-2"
                                                        style="font-size: 1.6rem; color: #008b8b;"></i>
                                                    <h6 class="text-muted mb-1">{{ __('messages.location_title') }}</h6>
                                                    <span class="fw-bold"
                                                        style="color: #d4af37;">{{ $service->location }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>


                        @if ($service->description)
                            <div class="mb-4">
                                <h4>{{ __('messages.service_description_title') }}</h4>
                                <p class="text-muted">{{ $service->description }}</p>
                            </div>
                        @endif

                        @if ($service->voice_note)
                            <div class="mb-4">
                                <h4>
                                    <i class="fas fa-microphone text-primary"></i> {{ __('messages.voice_note_title') }}
                                </h4>
                                <div class="voice-note-player">
                                    <audio controls class="w-100">
                                        <source src="{{ $service->voice_note }}" type="audio/wav">
                                        {{ __('messages.audio_not_supported') }}
                                    </audio>
                                </div>
                            </div>
                        @endif

                        @if ($service->custom_fields && is_array($service->custom_fields))
                            @php
                                $groupedFields = \App\Models\CategoryField::where('category_id', $service->category_id)
                                    ->whereIn('name', array_keys($service->custom_fields))
                                    ->get()
                                    ->groupBy('input_group');
                            @endphp

                            @foreach ($groupedFields as $groupName => $fields)
                                <div class="mb-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header"
                                            style="background: linear-gradient(90deg, #007b7f, #009f9c); color: #fff;">
                                            <h5 class="mb-0">
                                                <i class="fas fa-table me-2 text-warning"></i>
                                                {{ $groupName ?: __('messages.custom_field_group_default') }}
                                            </h5>
                                        </div>

                                        <div class="card-body p-0">
                                            @php
                                                $hasRepeatableFields =
                                                    $fields->where('is_repeatable', true)->count() > 0;
                                            @endphp

                                            @if ($hasRepeatableFields)
                                                @php
                                                    $repeatableFields = $fields->where('is_repeatable', true);

                                                    $fieldOrder = [
                                                        'furniture_type' => 1,
                                                        'furniture_name' => 1,
                                                        'type' => 1,
                                                        'quantity' => 2,
                                                        'count' => 2,
                                                        'number' => 2,
                                                        'disassemble' => 3,
                                                        'dismantle' => 3,
                                                        'install' => 4,
                                                        'assembly' => 4,
                                                        'setup' => 4,
                                                    ];

                                                    $sortedFields = $repeatableFields->sortBy(function ($field) use (
                                                        $fieldOrder,
                                                    ) {
                                                        $fieldName = strtolower($field->name);
                                                        $fieldNameAr = strtolower($field->name_ar);
                                                        foreach ($fieldOrder as $key => $priority) {
                                                            if (strpos($fieldName, $key) !== false) {
                                                                return $priority;
                                                            }
                                                        }
                                                        if (
                                                            strpos($fieldNameAr, 'نوع') !== false ||
                                                            strpos($fieldNameAr, 'عفش') !== false
                                                        ) {
                                                            return 1;
                                                        }
                                                        if (
                                                            strpos($fieldNameAr, 'عدد') !== false ||
                                                            strpos($fieldNameAr, 'كمية') !== false
                                                        ) {
                                                            return 2;
                                                        }
                                                        if (strpos($fieldNameAr, 'فك') !== false) {
                                                            return 3;
                                                        }
                                                        if (
                                                            strpos($fieldNameAr, 'تركيب') !== false ||
                                                            strpos($fieldNameAr, 'تجميع') !== false
                                                        ) {
                                                            return 4;
                                                        }
                                                        return 999;
                                                    });

                                                    $maxItems = 0;
                                                    $fieldData = [];
                                                    foreach ($sortedFields as $field) {
                                                        $fieldName = $field->name;
                                                        $fieldValues = $service->custom_fields[$fieldName] ?? [];
                                                        if (is_array($fieldValues)) {
                                                            $fieldData[$fieldName] = $fieldValues;
                                                            $maxItems = max($maxItems, count($fieldValues));
                                                        }
                                                    }
                                                @endphp

                                                @if ($maxItems > 0)
                                                    <div class="table-responsive">
                                                        <table class="table mb-0 align-middle">
                                                            <thead style="background-color: #007b7f; color: white;">
                                                                <tr>
                                                                    <th class="text-center"><i class="fas fa-hashtag"></i>
                                                                    </th>
                                                                    @foreach ($sortedFields as $field)
                                                                        <th class="text-center">
                                                                            <i
                                                                                class="fas fa-{{ $field->type === 'checkbox' ? 'check-square' : ($field->type === 'number' ? 'calculator' : 'edit') }} me-1 text-warning"></i>
                                                                            {{ $field->name_ar }}
                                                                        </th>
                                                                    @endforeach
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @for ($i = 0; $i < $maxItems; $i++)
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <span class="badge rounded-circle text-white"
                                                                                style="background-color: #009f9c; width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">
                                                                                {{ $i + 1 }}
                                                                            </span>
                                                                        </td>
                                                                        @foreach ($sortedFields as $field)
                                                                            @php
                                                                                $fieldName = $field->name;
                                                                                $fieldType = $field->type;
                                                                                $value =
                                                                                    $fieldData[$fieldName][$i] ?? '';
                                                                            @endphp
                                                                            <td class="text-center">
                                                                                @if ($fieldType === 'checkbox')
                                                                                    @php
                                                                                        $isChecked = in_array($value, [
                                                                                            '1',
                                                                                            1,
                                                                                            true,
                                                                                            'true',
                                                                                            'on',
                                                                                        ]);
                                                                                    @endphp
                                                                                    @if ($isChecked)
                                                                                        <span class="badge bg-success"><i
                                                                                                class="fas fa-check me-1"></i>{{ __('messages.yes_checkbox') }}</span>
                                                                                    @else
                                                                                        <span class="badge bg-danger"><i
                                                                                                class="fas fa-times me-1"></i>{{ __('messages.no_checkbox') }}</span>
                                                                                    @endif
                                                                                @elseif($fieldType === 'image')
                                                                                    @if (is_array($value) && count($value) > 0)
                                                                                        @foreach ($value as $imagePath)
                                                                                            <img src="{{ asset('storage/' . (is_array($imagePath) ? $imagePath[0] : $imagePath)) }}"
                                                                                                alt="{{ __('messages.image_alt') }}"
                                                                                                class="img-thumbnail me-1 mb-1"
                                                                                                style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #009f9c;"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#imageModal"
                                                                                                onclick="showImageModal('{{ asset('storage/' . (is_array($imagePath) ? $imagePath[0] : $imagePath)) }}')">
                                                                                        @endforeach
                                                                                    @else
                                                                                        <span class="text-muted">-</span>
                                                                                    @endif
                                                                                @else
                                                                                    <span class="fw-bold text-dark">
                                                                                        @if (is_array($value))
                                                                                            {{ implode(', ', array_filter($value)) }}
                                                                                        @else
                                                                                            {{ $value ?: '-' }}
                                                                                        @endif
                                                                                    </span>
                                                                                @endif
                                                                            </td>
                                                                        @endforeach
                                                                    </tr>
                                                                @endfor
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table mb-0 align-middle">
                                                        <thead style="background-color: #007b7f; color: white;">
                                                            <tr>
                                                                <th class="text-center"><i
                                                                        class="fas fa-tag me-1 text-warning"></i>
                                                                    {{ __('messages.custom_field_type') }}</th>
                                                                <th class="text-center"><i
                                                                        class="fas fa-info-circle me-1 text-warning"></i>
                                                                    {{ __('messages.custom_field_value') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($fields as $field)
                                                                @php
                                                                    $fieldName = $field->name;
                                                                    $fieldValues =
                                                                        $service->custom_fields[$fieldName] ?? null;
                                                                    // تم استبدال $displayName بالشرط اللغوي لضمان التبديل
                                                                    $fieldType = $field->type;
                                                                @endphp

                                                                @if ($fieldValues && $fieldValues !== '')
                                                                    <tr>
                                                                        <td class="text-center fw-bold text-primary">
                                                                            <i
                                                                                class="fas fa-{{ $fieldType === 'checkbox' ? 'check-square' : ($fieldType === 'textarea' ? 'align-left' : ($fieldType === 'number' ? 'calculator' : 'edit')) }} me-1 text-warning"></i>

                                                                            {{ app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en }}
                                                                        </td>
                                                                        <td class="text-center">
                                                                            @if ($fieldType === 'checkbox')
                                                                                @php
                                                                                    $value = is_array($fieldValues)
                                                                                        ? $fieldValues[0]
                                                                                        : $fieldValues;
                                                                                    $isChecked = in_array($value, [
                                                                                        '1',
                                                                                        1,
                                                                                        true,
                                                                                        'true',
                                                                                        'on',
                                                                                    ]);
                                                                                @endphp
                                                                                @if ($isChecked)
                                                                                    <span class="badge bg-success"><i
                                                                                            class="fas fa-check me-1"></i>{{ __('messages.yes_checkbox') }}</span>
                                                                                @else
                                                                                    <span class="badge bg-danger"><i
                                                                                            class="fas fa-times me-1"></i>{{ __('messages.no_checkbox') }}</span>
                                                                                @endif
                                                                            @elseif($fieldType === 'image')
                                                                                @if (is_array($fieldValues) && count($fieldValues) > 0)
                                                                                    @foreach ($fieldValues as $imagePath)
                                                                                        <img src="{{ asset('storage/' . (is_array($imagePath) ? $imagePath[0] : $imagePath)) }}"
                                                                                            alt="{{ __('messages.image_alt') }}"
                                                                                            class="img-thumbnail me-1 mb-1"
                                                                                            style="width: 50px; height: 50px; border: 2px solid #009f9c;"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#imageModal"
                                                                                            onclick="showImageModal('{{ asset('storage/' . (is_array($imagePath) ? $imagePath[0] : $imagePath)) }}')">
                                                                                    @endforeach
                                                                                @else
                                                                                    <span class="text-muted">-</span>
                                                                                @endif
                                                                            @else
                                                                                <span class="fw-bold text-dark">
                                                                                    @if (is_array($fieldValues))
                                                                                        {{ implode(', ', array_filter($fieldValues)) }}
                                                                                    @else
                                                                                        {{ $fieldValues ?: '-' }}
                                                                                    @endif
                                                                                </span>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        @endif



                        <!-- معلومات الاتصال وحالة الخدمة -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <h5 class="mb-3">
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    {{ __('messages.contact_info_title') }}
                                </h5>
                                <div class="row">
                                    @if ($service->contact_phone)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-phone text-success mb-2"
                                                        style="font-size: 1.5rem;"></i>
                                                    <h6 class="text-muted mb-2">{{ __('messages.phone_number') }}</h6>
                                                    <a href="tel:{{ $service->contact_phone }}"
                                                        class="btn btn-success btn-sm">
                                                        <i class="fas fa-phone me-1"></i>{{ $service->contact_phone }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($service->contact_email)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-envelope text-primary mb-2"
                                                        style="font-size: 1.5rem;"></i>
                                                    <h6 class="text-muted mb-2">{{ __('messages.email_address') }}</h6>
                                                    <a href="mailto:{{ $service->contact_email }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i
                                                            class="fas fa-envelope me-1"></i>{{ __('messages.send_message_button') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    {{ __('messages.service_status_title') }}
                                </h5>
                                <div class="d-flex flex-column gap-2">
                                    @if ($service->is_featured)
                                        <span class="badge bg-warning fs-6 p-2">
                                            <i class="fas fa-star me-1"></i>{{ __('messages.status_featured') }}
                                        </span>
                                    @endif
                                    @if ($service->is_active)
                                        <span class="badge bg-success fs-6 p-2">
                                            <i class="fas fa-check-circle me-1"></i>{{ __('messages.status_available') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger fs-6 p-2">
                                            <i
                                                class="fas fa-times-circle me-1"></i>{{ __('messages.status_unavailable') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الشريط الجانبي -->
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('messages.provider_info_title') }}</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $service->user->avatar_url }}" alt="{{ $service->user->name }}"
                            class="rounded-circle mb-3" width="80" height="80">
                        <h6>{{ $service->user->name }}</h6>
                        @if ($service->user->bio)
                            <p class="text-muted small">{{ $service->user->bio }}</p>
                        @endif
                    </div>
                </div>

                @auth
                    @if (auth()->user()->isProvider() && $userOffer)
                        @if ($userOffer->status === 'rejected')
                            <div class="alert alert-warning mb-3">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>{{ __('messages.offer_rejected_title') }}</strong>
                                {{ __('messages.offer_rejected_body') }}
                            </div>
                        @elseif($userOffer->is_expired)
                            <div class="alert alert-danger mb-3">
                                <i class="fas fa-clock"></i>
                                <strong>{{ __('messages.offer_expired_title') }}</strong>
                                {{ __('messages.offer_expired_body') }}
                            </div>
                        @elseif($userOffer->status === 'accepted')
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-check-circle"></i>
                                <strong>{{ __('messages.offer_accepted_title') }}</strong>
                                {{ __('messages.offer_accepted_body') }}
                            </div>
                        @elseif($userOffer->status === 'pending')
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-clock"></i>
                                <strong>{{ __('messages.offer_pending_title') }}</strong>
                                {{ __('messages.offer_pending_body') }}
                            </div>
                        @endif
                        <div
                            class="card mb-3 @if ($userOffer->status === 'accepted') border-success @elseif($userOffer->status === 'rejected') border-danger @elseif($userOffer->is_expired) border-warning @else border-info @endif">
                            <div
                                class="card-header @if ($userOffer->status === 'accepted') bg-success @elseif($userOffer->status === 'rejected') bg-danger @elseif($userOffer->is_expired) bg-warning @else bg-info @endif text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-handshake"></i> {{ __('messages.current_offer_title') }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6
                                            class="@if ($userOffer->status === 'accepted') text-success @elseif($userOffer->status === 'rejected') text-danger @elseif($userOffer->is_expired) text-warning @else text-info @endif">
                                            {{ __('messages.offer_price') }}</h6>
                                        <p
                                            class="h5 @if ($userOffer->status === 'accepted') text-success @elseif($userOffer->status === 'rejected') text-danger @elseif($userOffer->is_expired) text-warning @else text-info @endif">
                                            {{ $userOffer->formatted_price }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted">{{ __('messages.offer_status') }}</h6>
                                        <span class="badge bg-{{ $userOffer->status_color }}">
                                            {{ $userOffer->status_label }}
                                        </span>
                                        @if ($userOffer->status === 'rejected')
                                            <div class="mt-1">
                                                <small class="text-danger">
                                                    <i class="fas fa-times-circle"></i> {{ __('messages.status_rejected') }}
                                                </small>
                                            </div>
                                        @elseif($userOffer->status === 'accepted')
                                            <div class="mt-1">
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle"></i> {{ __('messages.status_accepted') }}
                                                </small>
                                            </div>
                                        @elseif($userOffer->status === 'pending')
                                            <div class="mt-1">
                                                <small class="text-warning">
                                                    <i class="fas fa-clock"></i> {{ __('messages.status_pending') }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if ($userOffer->notes)
                                    <div class="mt-3">
                                        <h6 class="text-muted">{{ __('messages.provider_notes') }}</h6>
                                        <p class="text-muted">{{ $userOffer->notes }}</p>
                                    </div>
                                @endif
                                @if ($userOffer->expires_at)
                                    <div class="mt-2">
                                        @if ($userOffer->is_expired)
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                {{ __('messages.offer_expired_note') }}
                                            </small>
                                        @else
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> {{ __('messages.offer_expires_at') }}
                                                {{ $userOffer->expires_at->format('Y-m-d H:i') }}
                                            </small>
                                        @endif
                                    </div>
                                @endif
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> {{ __('messages.offer_submitted_at') }}
                                        {{ $userOffer->created_at }}
                                    </small>
                                </div>
                                @if ($userOffer->status === 'rejected' || $userOffer->is_expired)
                                    <div class="mt-3">
                                        <a href="{{ route('service-offers.create', $service) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-redo"></i> {{ __('messages.submit_new_offer') }}
                                        </a>
                                    </div>
                                @endif
                                <div class="mt-2">
                                    <a href="{{ route('service-offers.my-offers') }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-list"></i> {{ __('messages.view_all_offers') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth

                <div class="card mb-3 shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #007b8f, #00a6a6);">
                        <h5 class="mb-0"><i
                                class="fas fa-bolt me-2 text-warning"></i>{{ __('messages.quick_actions_title') }}</h5>
                    </div>
                    <div class="card-body bg-light">
                        <div class="d-grid gap-2">
                            @auth
                                @if (auth()->id() !== $service->user_id)
                                    @if (auth()->user()->isProvider())
                                        @if ($userOffer)
                                            @if ($userOffer->status === 'rejected' || $userOffer->is_expired)
                                                <a href="{{ route('service-offers.create', $service) }}"
                                                    class="btn text-white" style="background-color: #d4a017;">
                                                    <i class="fas fa-redo"></i> {{ __('messages.submit_new_offer') }}
                                                </a>
                                            @else
                                                <button class="btn text-white" style="background-color: #00a6a6;" disabled>
                                                    <i class="fas fa-check"></i> {{ __('messages.offer_submitted_button') }}
                                                </button>
                                            @endif
                                            <a href="{{ route('service-offers.my-offers') }}" class="btn btn-outline-info"
                                                style="border-color: #00a6a6; color: #007b8f;">
                                                <i class="fas fa-eye"></i> {{ __('messages.view_all_offers') }}
                                            </a>
                                        @elseif($canProviderOffer)
                                            <a href="{{ route('service-offers.create', $service) }}" class="btn text-white"
                                                style="background-color: #00a6a6;">
                                                <i class="fas fa-handshake"></i> {{ __('messages.submit_offer_button') }}
                                            </a>
                                        @else
                                            <button class="btn btn-outline-secondary" disabled
                                                title="{{ __('messages.cannot_offer_tooltip') }}">
                                                <i class="fas fa-times"></i> {{ __('messages.cannot_submit_offer') }}
                                            </button>
                                        @endif
                                        <a href="{{ route('messages.service-conversation', $service->id) }}"
                                            class="btn btn-outline-primary" style="border-color: #007b8f; color: #007b8f;">
                                            <i class="fas fa-comments"></i> {{ __('messages.send_message_button_action') }}
                                        </a>
                                    @else
                                        <a href="{{ route('service-offers.index', $service) }}" class="btn text-white"
                                            style="background-color: #007b8f;">
                                            <i class="fas fa-eye"></i> {{ __('messages.view_offers_button') }}
                                        </a>
                                        <a href="{{ route('messages.service-conversation', $service->id) }}"
                                            class="btn btn-outline-primary" style="border-color: #00a6a6; color: #00a6a6;">
                                            <i class="fas fa-comments"></i> {{ __('messages.send_message_button_action') }}
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('service-offers.index', $service) }}" class="btn text-white"
                                        style="background-color: #007b8f;">
                                        <i class="fas fa-list"></i> {{ __('messages.view_offers_count_button') }}
                                        ({{ $service->pending_offers->count() }})
                                    </a>
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn text-white"
                                        style="background-color: #d4a017;">
                                        <i class="fas fa-edit"></i> {{ __('messages.edit_service_button') }}
                                    </a>
                                    <button type="button" class="btn text-white" style="background-color: #c82333;"
                                        onclick="confirmDelete()">
                                        <i class="fas fa-trash"></i> {{ __('messages.delete_service_button') }}
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn text-white" style="background-color: #00a6a6;">
                                    <i class="fas fa-sign-in-alt"></i> {{ __('messages.login_to_offer') }}
                                </a>
                            @endauth

                            <button class="btn btn-outline-primary" style="border-color: #00a6a6; color: #00a6a6;"
                                onclick="window.print()">
                                <i class="fas fa-print"></i> {{ __('messages.print_button') }}
                            </button>

                            <button class="btn btn-outline-secondary" style="border-color: #d4a017; color: #d4a017;"
                                onclick="shareService()">
                                <i class="fas fa-share"></i> {{ __('messages.share_button') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .btn:hover {
                    opacity: 0.9;
                    transform: translateY(-1px);
                    transition: all 0.2s ease-in-out;
                }

                .card-header i {
                    vertical-align: middle;
                }
            </style>


            <!-- Delete Form (Hidden) -->
            @auth
                @if (auth()->id() === $service->user_id)
                    <form id="deleteForm" action="{{ route('services.destroy', $service->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            @endauth

        </div>
    </div>
    </div>

    <script>
        function shareService() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $service->title }}',
                    text: '{{ $service->description }}',
                    url: window.location.href
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const url = window.location.href;
                const text = '{{ $service->title }}';

                if (navigator.clipboard) {
                    navigator.clipboard.writeText(url).then(() => {
                        alert('تم نسخ الرابط إلى الحافظة');
                    });
                } else {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = url;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('تم نسخ الرابط إلى الحافظة');
                }
            }
        }

        function confirmDelete() {
            if (confirm(
                    'هل أنت متأكد من حذف هذه الخدمة؟\n\nهذا الإجراء لا يمكن التراجع عنه وسيتم حذف جميع العروض المرتبطة بالخدمة.'
                    )) {
                document.getElementById('deleteForm').submit();
            }
        }

        function showImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
        }
    </script>

    <!-- Modal لعرض الصور -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">معاينة الصورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="صورة" class="img-fluid" style="max-height: 70vh;">
                </div>
            </div>
        </div>
    </div>

@endsection
