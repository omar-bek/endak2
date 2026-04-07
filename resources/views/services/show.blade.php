@extends('layouts.app')

@section('title', ($service->meta_title ?: $service->title) . ' | ' . config('app.name', 'Endak'))

@php
    $seoDescription = $service->meta_description ?: Str::limit(strip_tags($service->description), 160);
    $seoKeywords = $service->category->name . ', ' . ($service->city->name_ar ?? '') . ', ' . __('messages.seo_default_keywords');
    $seoImage = $service->image ? asset('storage/' . $service->image) : null;
    $seoUrl = route('services.show', $service->slug);
    $seoType = 'article';
    $seoBreadcrumbs = [
        ['name' => __('messages.home'), 'url' => route('home')],
        ['name' => $service->category->name, 'url' => route('categories.show', $service->category->slug)],
        ['name' => $service->title],
    ];
    $seoSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service->title,
        'description' => Str::limit(strip_tags($service->description), 300),
        'url' => $seoUrl,
        'provider' => [
            '@type' => 'Person',
            'name' => $service->user->name ?? '',
        ],
        'areaServed' => $service->city->name_ar ?? $service->city->name_en ?? '',
        'category' => $service->category->name,
        'image' => $seoImage,
        'datePublished' => $service->created_at?->toIso8601String(),
    ];
@endphp

@section('content')
{{-- Hero --}}
<div class="svc-show-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-light mb-3">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('messages.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.show', $service->category->slug) }}">{{ $service->category->name }}</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($service->title, 40) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container" style="margin-top: -3rem; position: relative; z-index: 2;">
    <div class="row">
        {{-- Main --}}
        <div class="col-lg-8 mb-4">
            <div class="e-card">
                {{-- Image --}}
                @if ($service->image || $service->category->image_url)
                    <div class="svc-show-img">
                        <img src="{{ $service->image ? asset('storage/' . $service->image) : $service->category->image_url }}"
                             alt="{{ $service->title }}"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-service.svg') }}';">
                        @if($service->city)
                            <span class="svc-show-city"><i class="fas fa-map-marker-alt"></i> {{ $service->city->name_ar ?? $service->city->name }}</span>
                        @endif
                        @if($service->price > 0)
                            <span class="svc-show-price">{{ $service->formatted_price }}</span>
                        @endif
                    </div>
                @endif

                <div class="e-card-body" style="padding: 1.5rem;">
                    {{-- Title + Actions --}}
                    <div class="d-flex justify-content-between align-items-start mb-3 gap-2 flex-wrap">
                        <h1 class="svc-show-title">{{ $service->title }}</h1>
                        @auth
                            @if (auth()->id() === $service->user_id)
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3"><i class="fas fa-edit me-1"></i>{{ __('messages.edit_button') }}</a>
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="confirmDelete()"><i class="fas fa-trash me-1"></i>{{ __('messages.delete_button') }}</button>
                                </div>
                            @endif
                        @endauth
                    </div>

                    {{-- Info Grid --}}
                    <div class="svc-info-grid">
                        <div class="svc-info-item">
                            <div class="svc-info-icon"><i class="fas fa-folder"></i></div>
                            <div>
                                <small>{{ __('messages.category_title') }}</small>
                                <strong>{{ $service->category->name }}</strong>
                            </div>
                        </div>
                        @if ($service->subCategory)
                            <div class="svc-info-item">
                                <div class="svc-info-icon"><i class="fas fa-layer-group"></i></div>
                                <div>
                                    <small>{{ __('messages.subcategory_title') }}</small>
                                    <strong>{{ app()->getLocale() == 'ar' ? $service->subCategory->name_ar : $service->subCategory->name_en }}</strong>
                                </div>
                            </div>
                        @endif
                        @if ($service->city)
                            <div class="svc-info-item">
                                <div class="svc-info-icon" style="background: rgba(243,164,70,0.1); color: var(--e-accent);"><i class="fas fa-map-marker-alt"></i></div>
                                <div>
                                    <small>المدينة</small>
                                    <strong>{{ $service->city->name_ar ?? $service->city->name }}</strong>
                                </div>
                            </div>
                        @endif
                        <div class="svc-info-item">
                            <div class="svc-info-icon" style="background: rgba(16,185,129,0.1); color: #059669;"><i class="fas fa-calendar"></i></div>
                            <div>
                                <small>{{ __('messages.publish_date') }}</small>
                                <strong>{{ $service->created_at->diffForHumans() }}</strong>
                            </div>
                        </div>
                        @if ($service->location)
                            <div class="svc-info-item">
                                <div class="svc-info-icon"><i class="fas fa-location-arrow"></i></div>
                                <div>
                                    <small>{{ __('messages.location_title') }}</small>
                                    <strong>{{ $service->location }}</strong>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if ($service->description)
                        <div class="svc-section">
                            <h5 class="svc-section-title"><i class="fas fa-align-right"></i> {{ __('messages.service_description_title') }}</h5>
                            <p class="svc-description">{{ $service->description }}</p>
                        </div>
                    @endif

                    {{-- Voice Note --}}
                    @if ($service->voice_note)
                        <div class="svc-section">
                            <h5 class="svc-section-title"><i class="fas fa-microphone"></i> {{ __('messages.voice_note_title') }}</h5>
                            <div class="svc-voice-player">
                                <audio controls class="w-100">
                                    <source src="{{ $service->voice_note }}" type="audio/wav">
                                    {{ __('messages.audio_not_supported') }}
                                </audio>
                            </div>
                        </div>
                    @endif

                    {{-- Custom Fields --}}
                    @if ($service->custom_fields && is_array($service->custom_fields))
                        @php
                            $allFields = \App\Models\CategoryField::where('category_id', $service->category_id)
                                ->whereIn('name', array_keys($service->custom_fields))
                                ->orderBy('sort_order')
                                ->get();

                            // فصل حقول الميديا (صور/فيديوهات) لعرضها كـ gallery منفصل
                            $mediaFields = $allFields->whereIn('type', ['image', 'video']);
                            $nonMediaFields = $allFields->whereNotIn('type', ['image', 'video']);
                            $groupedFields = $nonMediaFields->groupBy('input_group');

                            // دالة helper لتطبيع قيمة الملف (path string)
                            $normalizeFilePath = function ($val) {
                                if (is_array($val)) {
                                    return is_array($val[0] ?? null) ? ($val[0][0] ?? null) : ($val[0] ?? null);
                                }
                                return $val;
                            };
                        @endphp

                        {{-- معرض الصور --}}
                        @php
                            $imageFields = $mediaFields->where('type', 'image');
                            $allImages = [];
                            foreach ($imageFields as $imgField) {
                                $vals = $service->custom_fields[$imgField->name] ?? [];
                                if (!is_array($vals)) { $vals = [$vals]; }
                                foreach ($vals as $v) {
                                    if (is_array($v)) {
                                        foreach ($v as $vv) {
                                            if (is_array($vv)) {
                                                foreach ($vv as $vvv) { if ($vvv) $allImages[] = $vvv; }
                                            } elseif ($vv) {
                                                $allImages[] = $vv;
                                            }
                                        }
                                    } elseif ($v) {
                                        $allImages[] = $v;
                                    }
                                }
                            }
                        @endphp

                        @if (count($allImages) > 0)
                            <div class="svc-section">
                                <h5 class="svc-section-title">
                                    <i class="fas fa-images"></i>
                                    {{ __('messages.upload_images') }}
                                    <span class="svc-count-badge">{{ count($allImages) }}</span>
                                </h5>
                                <div class="svc-gallery">
                                    @foreach ($allImages as $img)
                                        <div class="svc-gallery-item" onclick="showImageModal('{{ asset('storage/' . $img) }}')" data-bs-toggle="modal" data-bs-target="#imageModal">
                                            <img src="{{ asset('storage/' . $img) }}" alt="" loading="lazy">
                                            <div class="svc-gallery-overlay"><i class="fas fa-search-plus"></i></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- معرض الفيديوهات --}}
                        @php
                            $videoFields = $mediaFields->where('type', 'video');
                            $allVideos = [];
                            foreach ($videoFields as $vidField) {
                                $vals = $service->custom_fields[$vidField->name] ?? null;
                                if (is_array($vals)) {
                                    foreach ($vals as $v) {
                                        if (is_string($v) && $v !== '') $allVideos[] = $v;
                                    }
                                } elseif (is_string($vals) && $vals !== '') {
                                    $allVideos[] = $vals;
                                }
                            }
                        @endphp

                        @if (count($allVideos) > 0)
                            <div class="svc-section">
                                <h5 class="svc-section-title">
                                    <i class="fas fa-video"></i>
                                    {{ __('messages.video_section_title', [], 'الفيديوهات') }}
                                    <span class="svc-count-badge">{{ count($allVideos) }}</span>
                                </h5>
                                <div class="svc-video-grid">
                                    @foreach ($allVideos as $vid)
                                        <div class="svc-video-item">
                                            <video controls preload="metadata" playsinline>
                                                <source src="{{ asset('storage/' . $vid) }}">
                                                {{ __('messages.audio_not_supported') }}
                                            </video>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- باقي الحقول مجمّعة --}}
                        @foreach ($groupedFields as $groupName => $fields)
                            @php
                                $hasRepeatableFields = $fields->where('is_repeatable', true)->count() > 0;
                            @endphp

                            <div class="svc-section">
                                <h5 class="svc-section-title">
                                    <i class="fas fa-{{ $hasRepeatableFields ? 'table' : 'list-alt' }}"></i>
                                    {{ $groupName ?: __('messages.custom_field_group_default') }}
                                </h5>

                                @if ($hasRepeatableFields)
                                    @php
                                        $repeatableFields = $fields->where('is_repeatable', true);
                                        $fieldOrder = ['furniture_type'=>1,'furniture_name'=>1,'type'=>1,'quantity'=>2,'count'=>2,'number'=>2,'disassemble'=>3,'dismantle'=>3,'install'=>4,'assembly'=>4,'setup'=>4];
                                        $sortedFields = $repeatableFields->sortBy(function($f) use ($fieldOrder) {
                                            foreach ($fieldOrder as $k => $p) { if (strpos(strtolower($f->name), $k) !== false) return $p; }
                                            if (strpos($f->name_ar,'نوع')!==false||strpos($f->name_ar,'عفش')!==false) return 1;
                                            if (strpos($f->name_ar,'عدد')!==false||strpos($f->name_ar,'كمية')!==false) return 2;
                                            if (strpos($f->name_ar,'فك')!==false) return 3;
                                            if (strpos($f->name_ar,'تركيب')!==false) return 4;
                                            return 999;
                                        });
                                        $maxItems = 0; $fieldData = [];
                                        foreach ($sortedFields as $f) {
                                            $v = $service->custom_fields[$f->name] ?? [];
                                            if (is_array($v)) { $fieldData[$f->name] = $v; $maxItems = max($maxItems, count($v)); }
                                        }
                                    @endphp
                                    @if ($maxItems > 0)
                                        <div class="table-responsive">
                                            <table class="table svc-table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th style="width:50px;">#</th>
                                                        @foreach ($sortedFields as $f)
                                                            <th>{{ app()->getLocale() == 'ar' ? $f->name_ar : $f->name_en }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for ($i = 0; $i < $maxItems; $i++)
                                                        <tr>
                                                            <td><span class="svc-row-num">{{ $i + 1 }}</span></td>
                                                            @foreach ($sortedFields as $f)
                                                                @php $val = $fieldData[$f->name][$i] ?? ''; @endphp
                                                                <td>
                                                                    @if ($f->type === 'checkbox')
                                                                        @if(in_array($val, ['1',1,true,'true','on']))
                                                                            <span class="badge bg-success"><i class="fas fa-check"></i></span>
                                                                        @else
                                                                            <span class="badge bg-light text-muted"><i class="fas fa-times"></i></span>
                                                                        @endif
                                                                    @elseif($f->type === 'select')
                                                                        @if($val)
                                                                            <span class="badge svc-badge-select">{{ $val }}</span>
                                                                        @else <span class="text-muted">-</span> @endif
                                                                    @elseif($f->type === 'date')
                                                                        @if($val)
                                                                            <i class="fas fa-calendar-alt text-muted me-1"></i>{{ \Carbon\Carbon::parse($val)->translatedFormat('d M Y') }}
                                                                        @else <span class="text-muted">-</span> @endif
                                                                    @elseif($f->type === 'time')
                                                                        @if($val)
                                                                            <i class="fas fa-clock text-muted me-1"></i>{{ $val }}
                                                                        @else <span class="text-muted">-</span> @endif
                                                                    @else
                                                                        <strong>{{ is_array($val) ? implode(', ', array_filter($val)) : ($val ?: '-') }}</strong>
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
                                    <div class="svc-fields-list">
                                        @foreach ($fields as $f)
                                            @php
                                                $val = $service->custom_fields[$f->name] ?? null;
                                                $displayVal = is_array($val) ? ($val[0] ?? null) : $val;
                                                $isLongText = $f->type === 'textarea' || (is_string($displayVal) && mb_strlen($displayVal) > 80);
                                            @endphp
                                            @if ($val !== null && $val !== '' && $val !== [])
                                                <div class="svc-field-row {{ $isLongText ? 'svc-field-row--block' : '' }}">
                                                    <span class="svc-field-label">
                                                        @if($f->type === 'title')
                                                            <i class="fas fa-heading me-1"></i>
                                                        @elseif($f->type === 'select')
                                                            <i class="fas fa-list me-1"></i>
                                                        @elseif($f->type === 'date')
                                                            <i class="fas fa-calendar-alt me-1"></i>
                                                        @elseif($f->type === 'time')
                                                            <i class="fas fa-clock me-1"></i>
                                                        @elseif($f->type === 'number')
                                                            <i class="fas fa-hashtag me-1"></i>
                                                        @elseif($f->type === 'textarea')
                                                            <i class="fas fa-align-right me-1"></i>
                                                        @elseif($f->type === 'checkbox')
                                                            <i class="fas fa-check-square me-1"></i>
                                                        @endif
                                                        {{ app()->getLocale() == 'ar' ? $f->name_ar : $f->name_en }}
                                                    </span>
                                                    <span class="svc-field-value">
                                                        @if ($f->type === 'checkbox')
                                                            @php $cv = is_array($val) ? ($val[0] ?? null) : $val; @endphp
                                                            @if(in_array($cv, ['1',1,true,'true','on']))
                                                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>{{ __('messages.yes_checkbox') }}</span>
                                                            @else
                                                                <span class="badge bg-light text-muted"><i class="fas fa-times me-1"></i>{{ __('messages.no_checkbox') }}</span>
                                                            @endif
                                                        @elseif($f->type === 'select')
                                                            <span class="badge svc-badge-select">{{ $displayVal }}</span>
                                                        @elseif($f->type === 'date')
                                                            {{ \Carbon\Carbon::parse($displayVal)->translatedFormat('d M Y') }}
                                                        @elseif($f->type === 'time')
                                                            {{ $displayVal }}
                                                        @elseif($f->type === 'number')
                                                            <strong>{{ number_format((float)$displayVal) }}</strong>
                                                        @elseif($f->type === 'textarea')
                                                            <span class="svc-field-text">{{ $displayVal }}</span>
                                                        @elseif($f->type === 'title')
                                                            <strong>{{ $displayVal }}</strong>
                                                        @else
                                                            {{ is_array($val) ? implode(', ', array_filter((array)$val)) : $val }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    {{-- Contact Info --}}
                    @if ($service->contact_phone || $service->contact_email)
                        <div class="svc-section">
                            <h5 class="svc-section-title"><i class="fas fa-phone-alt"></i> {{ __('messages.contact_info_title') }}</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @if ($service->contact_phone)
                                    <a href="tel:{{ $service->contact_phone }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        <i class="fas fa-phone me-1"></i>{{ $service->contact_phone }}
                                    </a>
                                @endif
                                @if ($service->contact_email)
                                    <a href="mailto:{{ $service->contact_email }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-envelope me-1"></i>{{ __('messages.send_message_button') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Status --}}
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @if ($service->is_featured)
                            <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>{{ __('messages.status_featured') }}</span>
                        @endif
                        @if ($service->is_active)
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>{{ __('messages.status_available') }}</span>
                        @else
                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>{{ __('messages.status_unavailable') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4 mb-4">
            {{-- Provider Card --}}
            <div class="e-card mb-3">
                <div class="e-card-body text-center" style="padding: 1.5rem;">
                    @include('partials.user-avatar', ['user' => $service->user, 'size' => 70, 'link' => true])
                    <h6 class="mt-2 mb-0 fw-bold">
                        <a href="{{ $service->user->isProvider() ? route('provider.profile.public', $service->user->id) : route('user.profile.public', $service->user->id) }}" class="text-decoration-none" style="color: var(--e-gray-800);">
                            {{ $service->user->name }}
                        </a>
                    </h6>
                    <small class="text-muted">{{ $service->user->isProvider() ? 'مزود خدمة' : 'عميل' }}</small>
                    @if ($service->user->bio)
                        <p class="text-muted small mt-2 mb-0">{{ Str::limit($service->user->bio, 80) }}</p>
                    @endif
                </div>
            </div>

            {{-- User Offer Status --}}
            @auth
                @if (auth()->user()->isProvider() && isset($userOffer) && $userOffer)
                    <div class="e-card mb-3">
                        <div class="e-card-header">
                            <i class="fas fa-handshake"></i> {{ __('messages.current_offer_title') }}
                        </div>
                        <div class="e-card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="h5 mb-0 fw-bold" style="color: var(--e-primary);">{{ $userOffer->formatted_price }}</span>
                                <span class="badge bg-{{ $userOffer->status_color }}">{{ $userOffer->status_label }}</span>
                            </div>
                            @if ($userOffer->notes)
                                <p class="text-muted small mb-2">{{ $userOffer->notes }}</p>
                            @endif
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i>{{ $userOffer->created_at->diffForHumans() }}</small>
                            @if ($userOffer->status === 'rejected' || ($userOffer->is_expired ?? false))
                                <div class="mt-2">
                                    <a href="{{ route('service-offers.create', $service) }}" class="btn btn-sm btn-accent rounded-pill w-100">
                                        <i class="fas fa-redo me-1"></i>{{ __('messages.submit_new_offer') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endauth

            {{-- Quick Actions --}}
            <div class="e-card mb-3">
                <div class="e-card-header">
                    <i class="fas fa-bolt"></i> {{ __('messages.quick_actions_title') }}
                </div>
                <div class="e-card-body">
                    <div class="d-grid gap-2">
                        @auth
                            @if (auth()->id() !== $service->user_id)
                                @if (auth()->user()->isProvider())
                                    @if (isset($userOffer) && $userOffer)
                                        @if ($userOffer->status === 'rejected' || ($userOffer->is_expired ?? false))
                                            <a href="{{ route('service-offers.create', $service) }}" class="btn btn-accent rounded-pill"><i class="fas fa-redo me-1"></i>{{ __('messages.submit_new_offer') }}</a>
                                        @else
                                            <button class="btn btn-secondary rounded-pill" disabled><i class="fas fa-check me-1"></i>{{ __('messages.offer_submitted_button') }}</button>
                                        @endif
                                    @elseif(isset($canProviderOffer) && $canProviderOffer)
                                        <a href="{{ route('service-offers.create', $service) }}" class="btn btn-primary rounded-pill"><i class="fas fa-handshake me-1"></i>{{ __('messages.submit_offer_button') }}</a>
                                    @else
                                        <button class="btn btn-outline-secondary rounded-pill" disabled><i class="fas fa-times me-1"></i>{{ __('messages.cannot_submit_offer') }}</button>
                                    @endif
                                    <a href="{{ route('messages.service-conversation', $service->id) }}" class="btn btn-outline-primary rounded-pill"><i class="fas fa-comments me-1"></i>{{ __('messages.send_message_button_action') }}</a>
                                @else
                                    <a href="{{ route('service-offers.index', $service) }}" class="btn btn-primary rounded-pill"><i class="fas fa-eye me-1"></i>{{ __('messages.view_offers_button') }}</a>
                                    <a href="{{ route('messages.service-conversation', $service->id) }}" class="btn btn-outline-primary rounded-pill"><i class="fas fa-comments me-1"></i>{{ __('messages.send_message_button_action') }}</a>
                                @endif
                            @else
                                <a href="{{ route('service-offers.index', $service) }}" class="btn btn-primary rounded-pill"><i class="fas fa-list me-1"></i>{{ __('messages.view_offers_count_button') }} ({{ $service->offers()->where('status','pending')->count() }})</a>
                                <a href="{{ route('services.edit', $service->id) }}" class="btn btn-accent rounded-pill"><i class="fas fa-edit me-1"></i>{{ __('messages.edit_service_button') }}</a>
                                <button type="button" class="btn btn-outline-danger rounded-pill" onclick="confirmDelete()"><i class="fas fa-trash me-1"></i>{{ __('messages.delete_service_button') }}</button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill"><i class="fas fa-sign-in-alt me-1"></i>{{ __('messages.login_to_offer') }}</a>
                        @endauth

                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary rounded-pill flex-fill btn-sm" onclick="window.print()"><i class="fas fa-print me-1"></i>{{ __('messages.print_button') }}</button>
                            <button class="btn btn-outline-secondary rounded-pill flex-fill btn-sm" onclick="shareService()"><i class="fas fa-share me-1"></i>{{ __('messages.share_button') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Form --}}
@auth
    @if (auth()->id() === $service->user_id)
        <form id="deleteForm" action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
    @endif
@endauth

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">معاينة الصورة</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body text-center"><img id="modalImage" src="" class="img-fluid" style="max-height:70vh;"></div></div></div></div>

@push('styles')
<style>
    .svc-show-hero {
        background: linear-gradient(135deg, var(--e-primary, #2f5c69), var(--e-primary-light, #3d7a8a));
        padding: 1.5rem 0 5rem;
    }

    .breadcrumb-light .breadcrumb-item a { color: rgba(255,255,255,0.7); text-decoration: none; }
    .breadcrumb-light .breadcrumb-item a:hover { color: #fff; }
    .breadcrumb-light .breadcrumb-item.active { color: rgba(255,255,255,0.5); }
    .breadcrumb-light .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.4); }

    .e-card { background: #fff; border-radius: 16px; border: 1px solid var(--e-gray-200, #e2e8f0); overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    .e-card-header { display: flex; align-items: center; gap: 0.5rem; padding: 1rem 1.25rem; font-weight: 700; font-size: 0.95rem; color: var(--e-primary); border-bottom: 1px solid var(--e-gray-100); background: var(--e-gray-50); }
    .e-card-body { padding: 1.25rem; }

    /* Image */
    .svc-show-img { position: relative; max-height: 400px; overflow: hidden; }
    .svc-show-img img { width: 100%; height: 400px; object-fit: cover; }
    .svc-show-city { position: absolute; top: 14px; right: 14px; background: rgba(0,0,0,0.55); backdrop-filter: blur(6px); color: #fff; padding: 0.3rem 0.75rem; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .svc-show-city i { color: var(--e-accent); margin-left: 0.3rem; }
    .svc-show-price { position: absolute; bottom: 14px; left: 14px; background: var(--e-accent); color: #fff; padding: 0.4rem 1rem; border-radius: 20px; font-weight: 700; font-size: 0.95rem; box-shadow: 0 3px 12px rgba(243,164,70,0.35); }

    .svc-show-title { font-size: 1.4rem; font-weight: 700; color: var(--e-gray-800); margin: 0; line-height: 1.4; }

    /* Info Grid */
    .svc-info-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 0.75rem; margin-bottom: 1.5rem; }
    .svc-info-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: var(--e-gray-50); border-radius: 12px; }
    .svc-info-icon { width: 38px; height: 38px; border-radius: 10px; background: rgba(47,92,105,0.1); color: var(--e-primary); display: flex; align-items: center; justify-content: center; font-size: 0.85rem; flex-shrink: 0; }
    .svc-info-item small { display: block; font-size: 0.7rem; color: var(--e-gray-400); }
    .svc-info-item strong { display: block; font-size: 0.85rem; color: var(--e-gray-700); }

    /* Sections */
    .svc-section { margin-bottom: 1.5rem; }
    .svc-section-title { font-size: 1rem; font-weight: 700; color: var(--e-gray-800); margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem; }
    .svc-section-title i { color: var(--e-primary); font-size: 0.85rem; }
    .svc-count-badge { display: inline-flex; align-items: center; justify-content: center; min-width: 22px; height: 22px; padding: 0 0.5rem; background: var(--e-primary); color: #fff; border-radius: 11px; font-size: 0.7rem; font-weight: 700; }
    .svc-description { color: var(--e-gray-600); line-height: 1.8; white-space: pre-line; font-size: 0.92rem; }

    /* Image Gallery */
    .svc-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.6rem; }
    .svc-gallery-item { position: relative; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; cursor: pointer; border: 2px solid var(--e-gray-200); transition: 0.25s; }
    .svc-gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: 0.3s; }
    .svc-gallery-item:hover { border-color: var(--e-primary); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(47,92,105,0.18); }
    .svc-gallery-item:hover img { transform: scale(1.08); }
    .svc-gallery-overlay { position: absolute; inset: 0; background: rgba(47,92,105,0); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.4rem; opacity: 0; transition: 0.25s; }
    .svc-gallery-item:hover .svc-gallery-overlay { background: rgba(47,92,105,0.45); opacity: 1; }

    /* Video Grid */
    .svc-video-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 0.85rem; }
    .svc-video-item { background: #000; border-radius: 12px; overflow: hidden; border: 1px solid var(--e-gray-200); box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
    .svc-video-item video { width: 100%; max-height: 320px; display: block; background: #000; }

    /* Voice Player */
    .svc-voice-player { background: var(--e-gray-50); border: 1px solid var(--e-gray-200); border-radius: 12px; padding: 1rem; }
    .svc-voice-player audio { width: 100%; height: 45px; border-radius: 8px; }

    /* Table */
    .svc-table { font-size: 0.85rem; }
    .svc-table thead { background: var(--e-primary); color: #fff; }
    .svc-table thead th { font-weight: 600; text-align: center; padding: 0.65rem 0.5rem; border: none; }
    .svc-table tbody td { text-align: center; vertical-align: middle; padding: 0.6rem 0.5rem; }
    .svc-table tbody tr:hover { background: var(--e-gray-50); }
    .svc-row-num { display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%; background: var(--e-primary); color: #fff; font-size: 0.7rem; font-weight: 700; }
    .svc-thumb { width: 44px; height: 44px; object-fit: cover; border-radius: 8px; border: 2px solid var(--e-gray-200); cursor: pointer; transition: 0.2s; }
    .svc-thumb:hover { transform: scale(1.1); border-color: var(--e-primary); }

    /* Fields List */
    .svc-fields-list { border: 1px solid var(--e-gray-200); border-radius: 12px; overflow: hidden; background: #fff; }
    .svc-field-row { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 0.8rem 1rem; border-bottom: 1px solid var(--e-gray-100); }
    .svc-field-row:last-child { border-bottom: none; }
    .svc-field-row:nth-child(even) { background: var(--e-gray-50); }
    .svc-field-row--block { flex-direction: column; align-items: flex-start; gap: 0.4rem; }
    .svc-field-row--block .svc-field-value { width: 100%; text-align: start; }
    .svc-field-label { font-weight: 600; color: var(--e-primary); font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.25rem; flex-shrink: 0; }
    .svc-field-value { font-weight: 600; color: var(--e-gray-700); font-size: 0.85rem; text-align: end; word-break: break-word; }
    .svc-field-text { display: block; font-weight: 500; color: var(--e-gray-600); line-height: 1.7; white-space: pre-line; padding: 0.5rem 0.75rem; background: var(--e-gray-50); border-inline-start: 3px solid var(--e-primary); border-radius: 0 8px 8px 0; }
    .svc-field-row--block .svc-field-text { background: #fff; }
    .svc-badge-select { background: rgba(47,92,105,0.1); color: var(--e-primary); padding: 0.35rem 0.75rem; font-weight: 600; font-size: 0.78rem; border-radius: 12px; }

    @media (max-width: 768px) {
        .svc-show-hero { padding: 1rem 0 4rem; }
        .svc-show-img img { height: 250px; }
        .svc-show-title { font-size: 1.15rem; }
        .svc-info-grid { grid-template-columns: 1fr 1fr; }
        .svc-gallery { grid-template-columns: repeat(3, 1fr); gap: 0.5rem; }
        .svc-video-grid { grid-template-columns: 1fr; }
        .svc-field-row { flex-direction: column; align-items: flex-start; gap: 0.3rem; }
        .svc-field-value { text-align: start; }
    }

    @media (max-width: 576px) {
        .svc-info-grid { grid-template-columns: 1fr; }
        .svc-gallery { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@push('scripts')
<script>
    function shareService() {
        if (navigator.share) {
            navigator.share({ title: '{{ addslashes($service->title) }}', url: window.location.href });
        } else if (navigator.clipboard) {
            navigator.clipboard.writeText(window.location.href).then(function() { alert('تم نسخ الرابط'); });
        }
    }
    function confirmDelete() {
        if (confirm('هل أنت متأكد من حذف هذه الخدمة؟')) document.getElementById('deleteForm').submit();
    }
    function showImageModal(src) { document.getElementById('modalImage').src = src; }
</script>
@endpush
@endsection
