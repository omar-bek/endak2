@extends('layouts.app')

@section('title', 'العروض المقدمة على خدماتي')

@section('content')
{{-- Hero --}}
<div class="recv-hero">
    <div class="container">
        <h2 class="text-white fw-bold mb-1"><i class="fas fa-inbox me-2"></i>العروض المقدمة على خدماتي</h2>
        <p class="text-white-50 mb-0">تابع كل العروض اللي وصلتك من مزودي الخدمات</p>
    </div>
</div>

<div class="container" style="margin-top: -2rem; position: relative; z-index: 2;">
    {{-- Stats --}}
    <div class="recv-stats">
        <div class="recv-stat">
            <span class="recv-stat-num">{{ $stats['total'] }}</span>
            <span class="recv-stat-label">إجمالي</span>
        </div>
        <div class="recv-stat">
            <span class="recv-stat-num" style="color: var(--e-warning);">{{ $stats['pending'] }}</span>
            <span class="recv-stat-label">في الانتظار</span>
        </div>
        <div class="recv-stat">
            <span class="recv-stat-num" style="color: var(--e-success);">{{ $stats['accepted'] }}</span>
            <span class="recv-stat-label">مقبول</span>
        </div>
        <div class="recv-stat">
            <span class="recv-stat-num" style="color: var(--e-info);">{{ $stats['delivered'] }}</span>
            <span class="recv-stat-label">تم التسليم</span>
        </div>
        <div class="recv-stat">
            <span class="recv-stat-num" style="color: var(--e-danger);">{{ $stats['rejected'] }}</span>
            <span class="recv-stat-label">مرفوض</span>
        </div>
    </div>

    {{-- Filter --}}
    <div class="recv-filter">
        <form method="GET" class="d-flex flex-wrap gap-2 align-items-end">
            <div class="flex-grow-1" style="min-width: 180px;">
                <label class="form-label small fw-semibold text-muted mb-1">الحالة</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>الكل</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>مقبول</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                </select>
            </div>
            <div class="flex-grow-1" style="min-width: 200px;">
                <label class="form-label small fw-semibold text-muted mb-1">الخدمة</label>
                <select name="service_id" class="form-select form-select-sm">
                    <option value="">كل الخدمات</option>
                    @foreach($myServices as $svc)
                        <option value="{{ $svc->id }}" {{ request('service_id') == $svc->id ? 'selected' : '' }}>{{ Str::limit($svc->title, 35) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                <i class="fas fa-filter me-1"></i>فلترة
            </button>
            @if(request()->hasAny(['status', 'service_id']))
                <a href="{{ route('service-offers.received') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fas fa-times me-1"></i>مسح
                </a>
            @endif
        </form>
    </div>

    {{-- Offers List --}}
    @if($offers->count() > 0)
        <div class="recv-list">
            @foreach($offers as $offer)
                <div class="recv-card">
                    <div class="recv-card-accent recv-accent-{{ $offer->status }}"></div>
                    <div class="recv-card-inner">
                        {{-- Provider --}}
                        <div class="recv-provider">
                            @include('partials.user-avatar', ['user' => $offer->provider, 'size' => 44])
                            <div class="recv-provider-info">
                                <span class="recv-provider-name">{{ $offer->provider->name }}</span>
                                <span class="recv-provider-time"><i class="fas fa-clock me-1"></i>{{ $offer->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <span class="recv-badge recv-badge-{{ $offer->status }}">
                                    {{ $offer->status_label }}
                                </span>
                            </div>
                        </div>

                        {{-- Service Info --}}
                        <div class="recv-service-row">
                            @if($offer->service)
                                <div class="recv-service-info">
                                    <small class="text-muted"><i class="fas fa-concierge-bell me-1"></i>الخدمة:</small>
                                    <a href="{{ route('services.show', $offer->service->slug) }}" class="recv-service-link">{{ $offer->service->title }}</a>
                                </div>
                                @if($offer->service->city)
                                    <span class="recv-city"><i class="fas fa-map-marker-alt"></i> {{ $offer->service->city->name_ar ?? '' }}</span>
                                @endif
                            @else
                                <span class="text-muted small"><i class="fas fa-ban me-1"></i>خدمة محذوفة</span>
                            @endif
                        </div>

                        {{-- Price + Notes --}}
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <div class="recv-price">{{ $offer->formatted_price }}</div>
                            @if($offer->notes)
                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#notes-{{ $offer->id }}">
                                    <i class="fas fa-sticky-note me-1"></i>ملاحظات
                                </button>
                            @endif
                        </div>

                        @if($offer->notes)
                            <div class="collapse mt-2" id="notes-{{ $offer->id }}">
                                <div class="recv-notes">{{ $offer->notes }}</div>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="recv-actions">
                            <a href="{{ route('service-offers.show', $offer->id) }}" class="recv-btn recv-btn-view">
                                <i class="fas fa-eye"></i> تفاصيل
                            </a>

                            @if($offer->status === 'pending')
                                <form method="POST" action="{{ route('service-offers.accept', $offer->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="recv-btn recv-btn-accept" onclick="return confirm('هل تريد قبول هذا العرض؟')">
                                        <i class="fas fa-check"></i> قبول
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('service-offers.reject', $offer->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="recv-btn recv-btn-reject" onclick="return confirm('هل تريد رفض هذا العرض؟')">
                                        <i class="fas fa-times"></i> رفض
                                    </button>
                                </form>
                            @endif

                            @if($offer->status === 'accepted')
                                <form method="POST" action="{{ route('service-offers.deliver', $offer->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="recv-btn recv-btn-deliver" onclick="return confirm('هل تريد تأكيد تسليم الخدمة؟')">
                                        <i class="fas fa-box-open"></i> تسليم
                                    </button>
                                </form>
                            @endif

                            @if($offer->provider)
                                <a href="{{ route('messages.show', $offer->provider->id) }}" class="recv-btn recv-btn-msg">
                                    <i class="fas fa-comments"></i> محادثة
                                </a>
                            @endif
                        </div>

                        {{-- Rating --}}
                        @if($offer->rating)
                            <div class="recv-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $offer->rating ? 'filled' : '' }}"></i>
                                @endfor
                                @if($offer->review)
                                    <span class="ms-2 text-muted small">{{ Str::limit($offer->review, 50) }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $offers->appends(request()->query())->links() }}
        </div>
    @else
        <div class="recv-empty">
            <div class="recv-empty-icon"><i class="fas fa-inbox"></i></div>
            <h5>لا توجد عروض</h5>
            <p>{{ request()->hasAny(['status','service_id']) ? 'لا توجد عروض تطابق الفلتر المحدد' : 'لم تصلك أي عروض على خدماتك بعد' }}</p>
            @if(request()->hasAny(['status','service_id']))
                <a href="{{ route('service-offers.received') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">عرض الكل</a>
            @endif
        </div>
    @endif
</div>

@push('styles')
<style>
    .recv-hero {
        background: linear-gradient(135deg, var(--e-primary, #2f5c69), var(--e-primary-light, #3d7a8a));
        padding: 2rem 0 4rem;
    }

    /* Stats */
    .recv-stats {
        display: flex;
        background: var(--e-white, #fff);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 1.25rem;
        border: 1px solid var(--e-gray-100, #f1f5f9);
    }
    .recv-stat {
        flex: 1;
        text-align: center;
        padding: 1rem 0.5rem;
        border-left: 1px solid var(--e-gray-100, #f1f5f9);
    }
    .recv-stat:last-child { border-left: none; }
    .recv-stat-num { display: block; font-size: 1.4rem; font-weight: 700; color: var(--e-gray-800, #1e293b); line-height: 1; }
    .recv-stat-label { font-size: 0.7rem; color: var(--e-gray-400, #94a3b8); margin-top: 0.2rem; display: block; }

    /* Filter */
    .recv-filter {
        background: var(--e-white, #fff);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        border: 1px solid var(--e-gray-100, #f1f5f9);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .recv-filter .form-select { border-radius: 10px; font-size: 0.85rem; }

    /* Card */
    .recv-card {
        background: var(--e-white, #fff);
        border-radius: 14px;
        border: 1px solid var(--e-gray-100, #f1f5f9);
        overflow: hidden;
        margin-bottom: 0.85rem;
        position: relative;
        transition: box-shadow 0.25s;
    }
    .recv-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.06); }

    .recv-card-accent {
        position: absolute;
        top: 0; right: 0; bottom: 0;
        width: 4px;
        border-radius: 0 14px 14px 0;
    }
    [dir="ltr"] .recv-card-accent { right: auto; left: 0; border-radius: 14px 0 0 14px; }
    .recv-accent-pending { background: var(--e-warning, #f59e0b); }
    .recv-accent-accepted { background: var(--e-success, #10b981); }
    .recv-accent-delivered { background: var(--e-info, #3b82f6); }
    .recv-accent-rejected { background: var(--e-danger, #ef4444); }

    .recv-card-inner { padding: 1.15rem 1.25rem; }

    /* Provider */
    .recv-provider { display: flex; align-items: center; gap: 0.65rem; margin-bottom: 0.65rem; }
    .recv-provider-info { display: flex; flex-direction: column; }
    .recv-provider-name { font-weight: 700; font-size: 0.92rem; color: var(--e-gray-800, #1e293b); }
    .recv-provider-time { font-size: 0.7rem; color: var(--e-gray-400, #94a3b8); }

    /* Badge */
    .recv-badge {
        display: inline-flex; align-items: center; padding: 0.25rem 0.7rem;
        border-radius: 999px; font-size: 0.7rem; font-weight: 700;
    }
    .recv-badge-pending { background: rgba(245,158,11,0.12); color: #92400e; }
    .recv-badge-accepted { background: rgba(16,185,129,0.12); color: #065f46; }
    .recv-badge-delivered { background: rgba(59,130,246,0.12); color: #1e40af; }
    .recv-badge-rejected { background: rgba(239,68,68,0.12); color: #991b1b; }

    /* Service row */
    .recv-service-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.5rem 0.75rem; background: var(--e-gray-50, #f8fafc);
        border-radius: 10px; gap: 0.5rem; flex-wrap: wrap;
    }
    .recv-service-link { color: var(--e-primary, #2f5c69); font-weight: 600; font-size: 0.88rem; text-decoration: none; }
    .recv-service-link:hover { text-decoration: underline; }
    .recv-city { font-size: 0.72rem; color: var(--e-gray-500, #64748b); display: inline-flex; align-items: center; gap: 0.25rem; }
    .recv-city i { color: var(--e-accent, #f3a446); font-size: 0.65rem; }

    /* Price */
    .recv-price { font-size: 1.2rem; font-weight: 800; color: var(--e-primary, #2f5c69); }

    /* Notes */
    .recv-notes {
        background: var(--e-gray-50, #f8fafc); padding: 0.65rem 0.85rem;
        border-radius: 10px; font-size: 0.85rem; color: var(--e-gray-600, #475569);
        line-height: 1.6; border: 1px solid var(--e-gray-100, #f1f5f9);
    }

    /* Actions */
    .recv-actions { display: flex; gap: 0.4rem; flex-wrap: wrap; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--e-gray-100, #f1f5f9); }
    .recv-btn {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.38rem 0.85rem; border-radius: 999px;
        font-size: 0.75rem; font-weight: 600; border: none; cursor: pointer;
        transition: all 0.2s; text-decoration: none;
    }
    .recv-btn:hover { transform: translateY(-1px); }
    .recv-btn-view { background: var(--e-gray-100, #f1f5f9); color: var(--e-gray-600, #475569); }
    .recv-btn-view:hover { background: var(--e-gray-200); color: var(--e-gray-700); }
    .recv-btn-accept { background: var(--e-success, #10b981); color: #fff; box-shadow: 0 2px 6px rgba(16,185,129,0.25); }
    .recv-btn-accept:hover { background: #0d9668; color: #fff; }
    .recv-btn-reject { background: var(--e-danger, #ef4444); color: #fff; box-shadow: 0 2px 6px rgba(239,68,68,0.25); }
    .recv-btn-reject:hover { background: #dc2626; color: #fff; }
    .recv-btn-deliver { background: var(--e-accent, #f3a446); color: #fff; box-shadow: 0 2px 6px rgba(243,164,70,0.25); }
    .recv-btn-deliver:hover { background: var(--e-accent-dark); color: #fff; }
    .recv-btn-msg { background: transparent; border: 1.5px solid var(--e-gray-200, #e2e8f0); color: var(--e-gray-500); }
    .recv-btn-msg:hover { border-color: var(--e-primary); color: var(--e-primary); }

    /* Rating */
    .recv-rating { margin-top: 0.5rem; display: flex; align-items: center; gap: 2px; }
    .recv-rating i { font-size: 0.75rem; color: var(--e-gray-200, #e2e8f0); }
    .recv-rating i.filled { color: var(--e-accent, #f3a446); }

    /* Empty */
    .recv-empty { text-align: center; padding: 4rem 1rem; }
    .recv-empty-icon { width: 80px; height: 80px; border-radius: 50%; background: var(--e-gray-100); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; }
    .recv-empty-icon i { font-size: 2rem; color: var(--e-gray-400); }
    .recv-empty h5 { color: var(--e-gray-600); }
    .recv-empty p { color: var(--e-gray-400); font-size: 0.9rem; }

    @media (max-width: 768px) {
        .recv-hero { padding: 1.5rem 0 3rem; }
        .recv-hero h2 { font-size: 1.2rem; }
        .recv-stats { flex-wrap: wrap; }
        .recv-stat { min-width: 33%; }
        .recv-stat-num { font-size: 1.1rem; }
        .recv-provider { flex-wrap: wrap; }
        .recv-actions { gap: 0.3rem; }
    }
</style>
@endpush
@endsection
