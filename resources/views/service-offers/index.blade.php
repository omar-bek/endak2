@extends('layouts.app')

@php $seoNoindex = true; @endphp
@section('title', 'عروض الخدمة - ' . $service->title)

@section('content')

@push('styles')
<style>
    /* ===== Page ===== */
    .of-hero {
        background: linear-gradient(135deg, var(--e-primary-dark) 0%, var(--e-primary) 50%, var(--e-primary-light) 100%);
        padding: 2rem 0 3.5rem;
        position: relative; overflow: hidden;
    }
    .of-hero::before { content:''; position:absolute; width:400px; height:400px; border-radius:50%; background:rgba(255,255,255,0.04); top:-120px; left:-80px; }

    .of-back { display:inline-flex; align-items:center; gap:0.4rem; color:rgba(255,255,255,0.7); font-size:0.82rem; text-decoration:none; margin-bottom:1rem; transition:color 0.2s; }
    .of-back:hover { color:var(--e-accent); }

    .of-service-row { display:flex; gap:1.25rem; align-items:flex-start; }
    .of-service-img { width:100px; height:100px; border-radius:var(--e-radius-lg); object-fit:cover; border:3px solid rgba(255,255,255,0.15); flex-shrink:0; }
    .of-service-info h1 { color:#fff; font-size:1.25rem; font-weight:700; margin-bottom:0.3rem; }
    .of-service-info p { color:rgba(255,255,255,0.6); font-size:0.82rem; margin-bottom:0.5rem; line-height:1.5; }
    .of-service-meta { display:flex; gap:1rem; flex-wrap:wrap; }
    .of-service-meta span { display:inline-flex; align-items:center; gap:0.35rem; font-size:0.78rem; color:rgba(255,255,255,0.55); }
    .of-service-meta i { color:var(--e-accent); font-size:0.75rem; }

    /* Content */
    .of-content { margin-top:-2rem; position:relative; z-index:1; padding-bottom:3rem; }

    /* Stats bar */
    .of-stats { display:flex; gap:0; background:var(--e-white); border-radius:var(--e-radius-lg); box-shadow:var(--e-shadow); overflow:hidden; margin-bottom:1.5rem; border:1px solid var(--e-gray-100); }
    .of-stat { flex:1; text-align:center; padding:1rem 0.5rem; border-left:1px solid var(--e-gray-100); }
    .of-stat:last-child { border-left:none; }
    .of-stat-num { font-size:1.4rem; font-weight:700; line-height:1; }
    .of-stat-label { font-size:0.7rem; color:var(--e-gray-500); margin-top:0.2rem; }

    /* Offer Card */
    .of-card {
        background:var(--e-white); border-radius:var(--e-radius-lg);
        border:1px solid var(--e-gray-100); box-shadow:var(--e-shadow-sm);
        overflow:hidden; margin-bottom:1rem; transition:box-shadow 0.25s ease;
    }
    .of-card:hover { box-shadow:var(--e-shadow-md); }
    .of-card-accent { position:absolute; top:0; right:0; bottom:0; width:4px; border-radius:0 var(--e-radius-lg) var(--e-radius-lg) 0; }
    [dir="ltr"] .of-card-accent { right:auto; left:0; border-radius:var(--e-radius-lg) 0 0 var(--e-radius-lg); }
    .of-card-inner { padding:1.25rem; position:relative; }

    .of-provider { display:flex; align-items:center; gap:0.75rem; margin-bottom:0.75rem; }
    .of-provider-avatar { width:46px; height:46px; border-radius:50%; object-fit:cover; border:2px solid var(--e-gray-200); }
    .of-provider-name { font-size:0.92rem; font-weight:700; color:var(--e-gray-800); }
    .of-provider-time { font-size:0.72rem; color:var(--e-gray-400); }

    .of-notes { font-size:0.85rem; color:var(--e-gray-600); line-height:1.6; margin-bottom:0.75rem; background:var(--e-gray-50); padding:0.75rem; border-radius:var(--e-radius); }

    .of-price { font-size:1.5rem; font-weight:800; color:var(--e-primary); line-height:1; }
    .of-price small { font-size:0.7rem; font-weight:500; color:var(--e-gray-400); }

    .of-badge { display:inline-flex; align-items:center; gap:0.3rem; padding:0.3rem 0.75rem; border-radius:var(--e-radius-full); font-size:0.72rem; font-weight:700; }
    .of-badge--pending { background:var(--e-warning-light); color:#92400e; }
    .of-badge--accepted { background:var(--e-success-light); color:#065f46; }
    .of-badge--delivered { background:var(--e-info-light); color:#1e40af; }
    .of-badge--rejected { background:var(--e-danger-light); color:#991b1b; }
    .of-badge--expired { background:var(--e-gray-100); color:var(--e-gray-500); }

    .of-timestamps { display:flex; flex-direction:column; gap:0.25rem; margin-top:0.5rem; }
    .of-timestamp { font-size:0.72rem; color:var(--e-gray-400); display:flex; align-items:center; gap:0.35rem; }

    /* Stars */
    .of-stars { display:flex; gap:2px; }
    .of-stars i { font-size:0.82rem; color:var(--e-gray-200); }
    .of-stars i.filled { color:var(--e-accent); }

    /* Actions */
    .of-actions { display:flex; gap:0.5rem; flex-wrap:wrap; margin-top:0.75rem; }
    .of-btn {
        display:inline-flex; align-items:center; gap:0.4rem;
        padding:0.45rem 1rem; border-radius:var(--e-radius-full);
        font-size:0.78rem; font-weight:600; border:none; cursor:pointer;
        transition:all 0.2s ease; text-decoration:none;
    }
    .of-btn:hover { transform:translateY(-1px); }
    .of-btn--accept { background:var(--e-success); color:#fff; box-shadow:0 2px 8px rgba(16,185,129,0.25); }
    .of-btn--accept:hover { background:#0d9668; color:#fff; }
    .of-btn--reject { background:var(--e-danger); color:#fff; box-shadow:0 2px 8px rgba(239,68,68,0.25); }
    .of-btn--reject:hover { background:#c0392b; color:#fff; }
    .of-btn--deliver { background:var(--e-accent); color:#fff; box-shadow:var(--e-shadow-accent); }
    .of-btn--deliver:hover { background:var(--e-accent-dark); color:#fff; }
    .of-btn--msg { background:transparent; border:1.5px solid var(--e-gray-200); color:var(--e-gray-600); }
    .of-btn--msg:hover { border-color:var(--e-primary); color:var(--e-primary); background:var(--e-primary-50); }

    /* Sidebar */
    .of-sidebar-card { background:var(--e-white); border-radius:var(--e-radius-lg); border:1px solid var(--e-gray-100); box-shadow:var(--e-shadow-sm); overflow:hidden; margin-bottom:1rem; }
    .of-sidebar-header { padding:0.85rem 1rem; font-size:0.85rem; font-weight:700; color:var(--e-gray-800); display:flex; align-items:center; gap:0.5rem; border-bottom:1px solid var(--e-gray-100); }
    .of-sidebar-header i { color:var(--e-primary); }
    .of-sidebar-body { padding:1rem; }

    .of-tip { display:flex; gap:0.5rem; align-items:flex-start; margin-bottom:0.65rem; }
    .of-tip:last-child { margin-bottom:0; }
    .of-tip i { color:var(--e-primary); margin-top:2px; font-size:0.75rem; flex-shrink:0; }
    .of-tip span { font-size:0.78rem; color:var(--e-gray-500); line-height:1.5; }

    /* Timeline */
    .of-timeline { position:relative; padding-right:24px; }
    [dir="ltr"] .of-timeline { padding-right:0; padding-left:24px; }
    .of-tl-item { position:relative; margin-bottom:1.25rem; }
    .of-tl-item:last-child { margin-bottom:0; }
    .of-tl-dot { position:absolute; right:-24px; top:2px; width:12px; height:12px; border-radius:50%; border:2.5px solid var(--e-white); box-shadow:0 0 0 2px var(--e-gray-300); }
    [dir="ltr"] .of-tl-dot { right:auto; left:-24px; }
    .of-tl-item:not(:last-child)::after { content:''; position:absolute; right:-19px; top:16px; width:2px; height:calc(100% + 5px); background:var(--e-gray-200); }
    [dir="ltr"] .of-tl-item:not(:last-child)::after { right:auto; left:-19px; }
    .of-tl-title { font-size:0.82rem; font-weight:700; color:var(--e-gray-700); }
    .of-tl-desc { font-size:0.72rem; color:var(--e-gray-400); }

    /* Empty */
    .of-empty { text-align:center; padding:3rem 1rem; }
    .of-empty-icon { width:80px; height:80px; border-radius:50%; background:var(--e-gray-100); display:inline-flex; align-items:center; justify-content:center; margin-bottom:1rem; }
    .of-empty-icon i { font-size:1.8rem; color:var(--e-gray-400); }
    .of-empty h5 { color:var(--e-gray-600); font-size:1rem; margin-bottom:0.3rem; }
    .of-empty p { color:var(--e-gray-400); font-size:0.85rem; }

    /* Rating Modal */
    .of-rating-input { display:flex; flex-direction:row-reverse; justify-content:center; gap:4px; }
    .of-rating-input input { display:none; }
    .of-rating-input label { cursor:pointer; font-size:1.8rem; color:var(--e-gray-200); transition:color 0.15s; }
    .of-rating-input label:hover,
    .of-rating-input label:hover ~ label,
    .of-rating-input input:checked ~ label { color:var(--e-accent); }

    /* Modal overrides */
    .modal { z-index:20000 !important; }
    .modal-backdrop { z-index:19999 !important; }
    .of-modal .modal-content { border:none; border-radius:var(--e-radius-lg); overflow:hidden; }
    .of-modal .modal-header { border-bottom:none; padding:1.25rem 1.25rem 0.75rem; }
    .of-modal .modal-footer { border-top:1px solid var(--e-gray-100); background:var(--e-gray-50); }

    @media (max-width:768px) {
        .of-service-row { flex-direction:column; align-items:center; text-align:center; }
        .of-service-meta { justify-content:center; }
        .of-stats { flex-wrap:wrap; }
        .of-stat { min-width:25%; }
        .of-card-inner { padding:1rem; }
        .of-price { font-size:1.2rem; }
    }
</style>
@endpush

{{-- Hero --}}
<div class="of-hero">
    <div class="container">
        <a href="{{ route('services.show', $service->slug) }}" class="of-back">
            <i class="fas fa-arrow-right"></i> العودة للخدمة
        </a>
        <div class="of-service-row">
            @if($service->image)
                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="of-service-img">
            @endif
            <div class="of-service-info">
                <h1>{{ $service->title }}</h1>
                <p>{{ Str::limit($service->description, 120) }}</p>
                <div class="of-service-meta">
                    <span><i class="fas fa-user"></i> {{ $service->user->name }}</span>
                    @if($service->location)
                        <span><i class="fas fa-map-marker-alt"></i> {{ $service->location }}</span>
                    @endif
                    @if($service->city)
                        <span><i class="fas fa-city"></i> {{ $service->city->name_ar ?? '' }}</span>
                    @endif
                    <span><i class="fas fa-handshake"></i> {{ $offers->count() }} عرض</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Content --}}
<div class="of-content">
    <div class="container">

        {{-- Stats --}}
        @php
            $pending = $offers->where('status', 'pending')->count();
            $accepted = $offers->where('status', 'accepted')->count();
            $delivered = $offers->where('status', 'delivered')->count();
            $rejected = $offers->where('status', 'rejected')->count();
        @endphp
        <div class="of-stats">
            <div class="of-stat"><div class="of-stat-num" style="color:var(--e-warning);">{{ $pending }}</div><div class="of-stat-label">في الانتظار</div></div>
            <div class="of-stat"><div class="of-stat-num" style="color:var(--e-success);">{{ $accepted }}</div><div class="of-stat-label">مقبول</div></div>
            <div class="of-stat"><div class="of-stat-num" style="color:var(--e-info);">{{ $delivered }}</div><div class="of-stat-label">تم التسليم</div></div>
            <div class="of-stat"><div class="of-stat-num" style="color:var(--e-danger);">{{ $rejected }}</div><div class="of-stat-label">مرفوض</div></div>
        </div>

        <div class="row g-4">
            {{-- Offers List --}}
            <div class="col-lg-8">
                @if($offers->count() > 0)
                    @foreach($offers as $offer)
                        <div class="of-card">
                            <div class="of-card-accent" style="background:var(--e-{{ $offer->status_color }});"></div>
                            <div class="of-card-inner">
                                <div class="row align-items-start">
                                    <div class="col-md-8">
                                        {{-- Provider --}}
                                        <div class="of-provider">
                                            @include('partials.user-avatar', ['user' => $offer->provider, 'size' => 46, 'class' => 'of-provider-avatar'])
                                            <div>
                                                <div class="of-provider-name">{{ $offer->provider->name }}</div>
                                                <div class="of-provider-time">{{ $offer->created_at->diffForHumans() }}</div>
                                            </div>
                                            <div class="ms-auto">
                                                <span class="of-badge of-badge--{{ $offer->status }}">
                                                    <i class="fas fa-circle" style="font-size:0.4rem;"></i>
                                                    {{ $offer->status_label }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Notes --}}
                                        @if($offer->notes)
                                            <div class="of-notes">{{ $offer->notes }}</div>
                                        @endif

                                        {{-- Timestamps --}}
                                        <div class="of-timestamps">
                                            @if($offer->expires_at)
                                                <div class="of-timestamp"><i class="fas fa-hourglass-half"></i> ينتهي في {{ $offer->expires_at->format('Y-m-d H:i') }}</div>
                                            @endif
                                            @if($offer->accepted_at)
                                                <div class="of-timestamp" style="color:var(--e-success);"><i class="fas fa-check-circle"></i> تم القبول {{ $offer->accepted_at->diffForHumans() }}</div>
                                            @endif
                                            @if($offer->delivered_at)
                                                <div class="of-timestamp" style="color:var(--e-info);"><i class="fas fa-check-double"></i> تم التسليم {{ $offer->delivered_at->diffForHumans() }}</div>
                                            @endif
                                        </div>

                                        {{-- Rating --}}
                                        @if($offer->rating)
                                            <div class="d-flex align-items-center gap-2 mt-2">
                                                <div class="of-stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $offer->rating ? 'filled' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                @if($offer->review)
                                                    <span style="font-size:0.75rem; color:var(--e-gray-400);">{{ Str::limit($offer->review, 50) }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        {{-- Price --}}
                                        <div class="of-price">{{ number_format($offer->price, 0) }} <small>ر.س</small></div>

                                        {{-- Actions --}}
                                        <div class="of-actions justify-content-md-end">
                                            @if($offer->status === 'pending' && auth()->id() === $service->user_id)
                                                <button class="of-btn of-btn--accept" data-bs-toggle="modal" data-bs-target="#acceptModal{{ $offer->id }}">
                                                    <i class="fas fa-check"></i> قبول
                                                </button>
                                                <button class="of-btn of-btn--reject" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $offer->id }}">
                                                    <i class="fas fa-times"></i> رفض
                                                </button>
                                            @endif

                                            @if($offer->status === 'accepted' && auth()->id() === $service->user_id && !$offer->delivered_at)
                                                <button class="of-btn of-btn--deliver" data-bs-toggle="modal" data-bs-target="#deliverModal{{ $offer->id }}">
                                                    <i class="fas fa-check-double"></i> تسليم وتقييم
                                                </button>
                                            @endif

                                            @if($offer->status === 'delivered' && $offer->delivered_at)
                                                <span class="of-badge of-badge--delivered"><i class="fas fa-check-double"></i> تم التسليم</span>
                                            @endif

                                            <a href="{{ route('messages.offer-conversation', $offer->id) }}" class="of-btn of-btn--msg">
                                                <i class="fas fa-comments"></i> رسالة
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Accept Modal --}}
                        @if($offer->status === 'pending')
                        <div class="modal fade of-modal" id="acceptModal{{ $offer->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title"><i class="fas fa-check-circle text-success me-2"></i> تأكيد القبول</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p style="font-size:0.88rem;">قبول عرض <strong>{{ $offer->provider->name }}</strong> بسعر <strong style="color:var(--e-primary);">{{ $offer->formatted_price }}</strong>؟</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">إلغاء</button>
                                        <form method="POST" action="{{ route('service-offers.accept', $offer) }}">
                                            @csrf
                                            <button type="submit" class="of-btn of-btn--accept"><i class="fas fa-check"></i> تأكيد القبول</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Reject Modal --}}
                        <div class="modal fade of-modal" id="rejectModal{{ $offer->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title"><i class="fas fa-times-circle text-danger me-2"></i> تأكيد الرفض</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p style="font-size:0.88rem;">رفض عرض <strong>{{ $offer->provider->name }}</strong>؟</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">إلغاء</button>
                                        <form method="POST" action="{{ route('service-offers.reject', $offer) }}">
                                            @csrf
                                            <button type="submit" class="of-btn of-btn--reject"><i class="fas fa-times"></i> تأكيد الرفض</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <div class="of-card">
                        <div class="of-empty">
                            <div class="of-empty-icon"><i class="fas fa-inbox"></i></div>
                            <h5>لا توجد عروض بعد</h5>
                            <p>لم يتم تقديم أي عروض لهذه الخدمة حتى الآن</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Info --}}
                <div class="of-sidebar-card">
                    <div class="of-sidebar-header"><i class="fas fa-info-circle"></i> معلومات</div>
                    <div class="of-sidebar-body">
                        <div class="of-tip"><i class="fas fa-check-circle"></i><span>يمكنك قبول عرض واحد فقط لهذه الخدمة</span></div>
                        <div class="of-tip"><i class="fas fa-handshake"></i><span>بعد القبول، يمكنك تأكيد التسليم وتقييم المزود</span></div>
                        <div class="of-tip"><i class="fas fa-clock"></i><span>العروض المنتهية تظهر بعلامة "منتهية"</span></div>
                        <div class="of-tip"><i class="fas fa-comments"></i><span>يمكنك التواصل مع أي مزود عبر الرسائل</span></div>
                    </div>
                </div>

                {{-- Workflow Timeline --}}
                <div class="of-sidebar-card">
                    <div class="of-sidebar-header"><i class="fas fa-tasks"></i> مراحل العمل</div>
                    <div class="of-sidebar-body">
                        <div class="of-timeline">
                            <div class="of-tl-item">
                                <div class="of-tl-dot" style="background:var(--e-warning); box-shadow:0 0 0 2px var(--e-warning-light);"></div>
                                <div class="of-tl-title">تقديم العرض</div>
                                <div class="of-tl-desc">مزود الخدمة يقدم عرضه وسعره</div>
                            </div>
                            <div class="of-tl-item">
                                <div class="of-tl-dot" style="background:var(--e-success); box-shadow:0 0 0 2px var(--e-success-light);"></div>
                                <div class="of-tl-title">قبول العرض</div>
                                <div class="of-tl-desc">تقوم بمراجعة وقبول أفضل عرض</div>
                            </div>
                            <div class="of-tl-item">
                                <div class="of-tl-dot" style="background:var(--e-info); box-shadow:0 0 0 2px var(--e-info-light);"></div>
                                <div class="of-tl-title">تسليم الخدمة</div>
                                <div class="of-tl-desc">تأكيد استلام الخدمة</div>
                            </div>
                            <div class="of-tl-item">
                                <div class="of-tl-dot" style="background:var(--e-accent); box-shadow:0 0 0 2px var(--e-accent-50);"></div>
                                <div class="of-tl-title">التقييم</div>
                                <div class="of-tl-desc">تقييم المزود ومراجعة الخدمة</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delivery + Rating Modals --}}
@foreach($offers as $offer)
    @if($offer->status === 'accepted' && auth()->id() === $service->user_id && !$offer->delivered_at)
        <div class="modal fade of-modal" id="deliverModal{{ $offer->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"><i class="fas fa-check-double me-2" style="color:var(--e-accent);"></i> تسليم الخدمة وتقييم المزود</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('service-offers.deliver', $offer) }}" id="deliverForm{{ $offer->id }}">
                        @csrf
                        <div class="modal-body">
                            <div class="p-3 rounded-3 mb-3" style="background:var(--e-info-light); font-size:0.82rem; color:#1e40af;">
                                <i class="fas fa-info-circle me-1"></i>
                                يجب تقييم المزود قبل تأكيد التسليم. التقييم إلزامي.
                            </div>
                            <div class="text-center mb-3">
                                <label class="ss-label mb-2">التقييم <span style="color:var(--e-danger);">*</span></label>
                                <div class="of-rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="dr{{ $i }}_{{ $offer->id }}" required>
                                        <label for="dr{{ $i }}_{{ $offer->id }}"><i class="fas fa-star"></i></label>
                                    @endfor
                                </div>
                                <small id="ratingError{{ $offer->id }}" style="display:none; color:var(--e-danger); font-size:0.78rem;">يجب اختيار التقييم</small>
                            </div>
                            <div class="mb-2">
                                <label class="ss-label">تعليق (اختياري)</label>
                                <textarea class="form-control" name="review" rows="3" placeholder="اكتب تعليقك عن الخدمة..." style="border-radius:var(--e-radius); border:1.5px solid var(--e-gray-200); font-size:0.88rem;"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="of-btn of-btn--deliver"><i class="fas fa-check-double"></i> تسليم وتقييم</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach($offers as $offer)
        @if($offer->status === 'accepted' && auth()->id() === $service->user_id && !$offer->delivered_at)
            var f{{ $offer->id }} = document.getElementById('deliverForm{{ $offer->id }}');
            if (f{{ $offer->id }}) {
                f{{ $offer->id }}.addEventListener('submit', function(e) {
                    if (!this.querySelector('input[name="rating"]:checked')) {
                        e.preventDefault();
                        document.getElementById('ratingError{{ $offer->id }}').style.display = 'block';
                        return false;
                    }
                    document.getElementById('ratingError{{ $offer->id }}').style.display = 'none';
                });
            }
        @endif
    @endforeach
});
</script>
@endpush

@endsection
