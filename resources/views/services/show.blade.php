@extends('layouts.app')

@section('title', $service->title)

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
                        <span class="svc-show-price">{{ $service->formatted_price }}</span>
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
                            $groupedFields = \App\Models\CategoryField::where('category_id', $service->category_id)
                                ->whereIn('name', array_keys($service->custom_fields))
                                ->get()
                                ->groupBy('input_group');
                        @endphp

                        @foreach ($groupedFields as $groupName => $fields)
                            <div class="svc-section">
                                <h5 class="svc-section-title"><i class="fas fa-table"></i> {{ $groupName ?: __('messages.custom_field_group_default') }}</h5>
                                @php
                                    $hasRepeatableFields = $fields->where('is_repeatable', true)->count() > 0;
                                @endphp

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
                                                        <th>#</th>
                                                        @foreach ($sortedFields as $f)
                                                            <th>{{ $f->name_ar }}</th>
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
                                                                            <span class="badge bg-danger"><i class="fas fa-times"></i></span>
                                                                        @endif
                                                                    @elseif($f->type === 'image')
                                                                        @if(is_array($val) && count($val) > 0)
                                                                            @foreach($val as $img)
                                                                                <img src="{{ asset('storage/' . (is_array($img)?$img[0]:$img)) }}" class="svc-thumb" onclick="showImageModal('{{ asset('storage/' . (is_array($img)?$img[0]:$img)) }}')" data-bs-toggle="modal" data-bs-target="#imageModal">
                                                                            @endforeach
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
                                            @php $val = $service->custom_fields[$f->name] ?? null; @endphp
                                            @if ($val && $val !== '')
                                                <div class="svc-field-row">
                                                    <span class="svc-field-label">{{ app()->getLocale() == 'ar' ? $f->name_ar : $f->name_en }}</span>
                                                    <span class="svc-field-value">
                                                        @if ($f->type === 'checkbox')
                                                            @php $cv = is_array($val) ? $val[0] : $val; @endphp
                                                            @if(in_array($cv, ['1',1,true,'true','on']))
                                                                <span class="badge bg-success"><i class="fas fa-check me-1"></i>{{ __('messages.yes_checkbox') }}</span>
                                                            @else
                                                                <span class="badge bg-danger"><i class="fas fa-times me-1"></i>{{ __('messages.no_checkbox') }}</span>
                                                            @endif
                                                        @elseif($f->type === 'image')
                                                            @if(is_array($val))
                                                                @foreach($val as $img)
                                                                    <img src="{{ asset('storage/' . (is_array($img)?$img[0]:$img)) }}" class="svc-thumb" onclick="showImageModal('{{ asset('storage/' . (is_array($img)?$img[0]:$img)) }}')" data-bs-toggle="modal" data-bs-target="#imageModal">
                                                                @endforeach
                                                            @endif
                                                        @else
                                                            {{ is_array($val) ? implode(', ', array_filter($val)) : $val }}
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
    .svc-description { color: var(--e-gray-600); line-height: 1.8; white-space: pre-line; font-size: 0.92rem; }

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
    .svc-fields-list { border: 1px solid var(--e-gray-200); border-radius: 12px; overflow: hidden; }
    .svc-field-row { display: flex; justify-content: space-between; align-items: center; padding: 0.7rem 1rem; border-bottom: 1px solid var(--e-gray-100); }
    .svc-field-row:last-child { border-bottom: none; }
    .svc-field-row:nth-child(even) { background: var(--e-gray-50); }
    .svc-field-label { font-weight: 600; color: var(--e-primary); font-size: 0.85rem; }
    .svc-field-value { font-weight: 600; color: var(--e-gray-700); font-size: 0.85rem; }

    @media (max-width: 768px) {
        .svc-show-hero { padding: 1rem 0 4rem; }
        .svc-show-img img { height: 250px; }
        .svc-show-title { font-size: 1.15rem; }
        .svc-info-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 576px) {
        .svc-info-grid { grid-template-columns: 1fr; }
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
