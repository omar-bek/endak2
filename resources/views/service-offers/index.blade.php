@extends('layouts.app')

@section('title', 'عروض الخدمة - ' . $service->title)

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- معلومات الخدمة -->
            <div class="card mb-4 shadow-sm border-0 rounded-3">
                <div class="card-body bg-light">
                    <div class="text-start mb-3">
                        <a href="{{ route('services.show', $service->slug) }}" class="btn btn-outline-teal">
                            <i class="fas fa-arrow-left"></i> العودة للخدمة
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            @if($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="img-fluid rounded shadow-sm">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-teal fw-bold">{{ $service->title }}</h4>
                            <p class="text-muted">{{ $service->description }}</p>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-user text-gold me-2"></i>
                                <span>{{ $service->user->name }}</span>
                            </div>
                            @if($service->location)
                                <div class="d-flex align-items-center mt-2">
                                    <i class="fas fa-map-marker-alt text-gold me-2"></i>
                                    <span>{{ $service->location }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- العروض -->
            <div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-gradient text-center" style="background: linear-gradient(90deg, #007d7b, #009688);">
        <h5 class="mb-0"><i class="fas fa-handshake me-2 text-black"></i> العروض المقدمة ({{ $offers->count() }})</h5>
    </div>

    <div class="card-body bg-light">
        @if($offers->count() > 0)
            @foreach($offers as $offer)
                <div class="card mb-3 border-start border-3 rounded-3 border-{{ $offer->status_color }} shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ $offer->provider->avatar_url }}" alt="{{ $offer->provider->name }}" class="rounded-circle me-3 shadow-sm" width="50" height="50">
                                    <div>
                                        <h6 class="mb-0 fw-bold text-teal">{{ $offer->provider->name }}</h6>
                                        <small class="text-muted"><i class="fas fa-clock me-1"></i>{{ $offer->created_at }}</small>
                                    </div>
                                </div>

                                @if($offer->notes)
                                    <p class="text-muted mb-2 fst-italic">{{ $offer->notes }}</p>
                                @endif

                                <div class="d-flex align-items-center">
                                    <span class="badge bg-{{ $offer->status_color }} me-2 px-3 py-2">{{ $offer->status_label }}</span>
                                    @if($offer->expires_at)
                                        <small class="text-muted">
                                            <i class="fas fa-hourglass-half me-1 text-gold"></i> ينتهي في {{ $offer->expires_at->format('Y-m-d H:i') }}
                                        </small>
                                    @endif
                                </div>

                                @if($offer->accepted_at)
                                    <div class="mt-2 text-success small">
                                        <i class="fas fa-check-circle"></i> تم القبول في {{ $offer->accepted_at->format('Y-m-d H:i') }}
                                    </div>
                                @endif

                                @if($offer->delivered_at)
                                    <div class="mt-2 text-info small">
                                        <i class="fas fa-check-double"></i> تم التسليم في {{ $offer->delivered_at->format('Y-m-d H:i') }}
                                    </div>
                                @endif

                                @if($offer->rating)
                                    <div class="mt-2">
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $offer->rating ? 'text-warning' : 'text-muted' }}" style="font-size: 0.9rem;"></i>
                                            @endfor
                                            <small class="text-muted ms-2">تم التقييم</small>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 text-md-end">
                                <div class="h4 text-gold mb-3 fw-bold">{{ $offer->formatted_price }}</div>

                                @if($offer->status === 'pending' && auth()->id() === $service->user_id)
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-sm rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#acceptModal{{ $offer->id }}">
                                            <i class="fas fa-check"></i> قبول
                                        </button>

                                        <button type="button" class="btn btn-danger btn-sm rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $offer->id }}">
                                            <i class="fas fa-times"></i> رفض
                                        </button>
                                    </div>
                                @endif

                                @if($offer->status === 'accepted' && auth()->id() === $service->user_id)
                                    <div class="mt-3">
                                        @if(!$offer->delivered_at)
                                            <button type="button" class="btn btn-warning btn-sm rounded-pill text-white"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deliverModal{{ $offer->id }}">
                                                    <i class="fas fa-check-double"></i> تم تسليم الخدمة
                                                </button>
                                        @else
                                            <span class="badge bg-success mb-2 rounded-pill px-3 py-2">
                                                <i class="fas fa-check-circle"></i> تم التسليم
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                @if($offer->status === 'delivered' && auth()->id() === $service->user_id && $offer->rating)
                                    <div class="mt-3 text-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $offer->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        @if($offer->review)
                                            <div><small class="text-muted">{{ Str::limit($offer->review, 50) }}</small></div>
                                        @endif
                                        <div><small class="text-muted">تم التقييم</small></div>
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <a href="{{ route('messages.offer-conversation', $offer->id) }}"
                                       class="btn btn-outline-teal btn-sm rounded-pill">
                                        <i class="fas fa-comments"></i> إرسال رسالة
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal قبول العرض -->
                <div class="modal fade" id="acceptModal{{ $offer->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-3 shadow-lg">
                            <div class="modal-header bg-teal text-white">
                                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i> تأكيد قبول العرض</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <p class="mb-3">هل أنت متأكد أنك تريد <span class="fw-bold text-success">قبول</span> هذا العرض؟</p>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <form method="POST" action="{{ route('service-offers.accept', $offer) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> تأكيد</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal رفض العرض -->
                <div class="modal fade" id="rejectModal{{ $offer->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-3 shadow-lg">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i> تأكيد رفض العرض</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <p class="mb-3">هل أنت متأكد أنك تريد <span class="fw-bold text-danger">رفض</span> هذا العرض؟</p>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <form method="POST" action="{{ route('service-offers.reject', $offer) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> تأكيد</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد عروض بعد</h5>
                <p class="text-muted">لم يتم تقديم أي عروض لهذه الخدمة حتى الآن</p>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.text-teal { color: #009688 !important; }
.bg-teal { background-color: #009688 !important; }
.btn-outline-teal {
    border-color: #009688;
    color: #009688;
}
.btn-outline-teal:hover {
    background-color: #009688;
    color: #fff;
}
.text-gold { color: #f7d354 !important; }
.bg-gradient {
    background: linear-gradient(90deg, #007d7b, #009688);
}

</style>
@endpush

        </div>

        <!-- الشريط الجانبي -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-3 rounded-3">
                <div class="card-header bg-teal text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i> إحصائيات العروض</h6>
                </div>
                <div class="card-body bg-light text-center">
                    <div class="row">
                        <div class="col-3"><div class="h4 text-warning">{{ $offers->where('status', 'pending')->count() }}</div><small>انتظار</small></div>
                        <div class="col-3"><div class="h4 text-success">{{ $offers->where('status', 'accepted')->count() }}</div><small>مقبول</small></div>
                        <div class="col-3"><div class="h4 text-info">{{ $offers->where('status', 'delivered')->count() }}</div><small>تم</small></div>
                        <div class="col-3"><div class="h4 text-danger">{{ $offers->where('status', 'rejected')->count() }}</div><small>مرفوض</small></div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-gold text-dark fw-bold"><i class="fas fa-info-circle me-2"></i> معلومات إضافية</div>
                <div class="card-body bg-light small text-muted">
                    <p><i class="fas fa-check-circle text-teal me-2"></i> يمكنك قبول عرض واحد فقط.</p>
                    <p><i class="fas fa-handshake text-teal me-2"></i> بعد القبول، يمكنك تأكيد التسليم.</p>
                    <p><i class="fas fa-clock text-teal me-2"></i> العروض المنتهية تظهر كـ "منتهية".</p>
                </div>
            </div>

            <div class="card mt-3 shadow-sm border-0 rounded-3">
                <div class="card-header bg-teal text-white"><i class="fas fa-tasks me-2"></i> مراحل العمل</div>
                <div class="card-body bg-light">
                    <div class="timeline">
                        <div class="timeline-item"><div class="timeline-marker bg-warning"></div><div class="timeline-content"><h6>تقديم العرض</h6><small>مزود الخدمة يقدم عرضه</small></div></div>
                        <div class="timeline-item"><div class="timeline-marker bg-success"></div><div class="timeline-content"><h6>قبول العرض</h6><small>العميل يقبل العرض</small></div></div>
                        <div class="timeline-item"><div class="timeline-marker bg-info"></div><div class="timeline-content"><h6>تسليم الخدمة</h6><small>تأكيد التسليم</small></div></div>
                        <div class="timeline-item"><div class="timeline-marker bg-gold"></div><div class="timeline-content"><h6>التقييم</h6><small>العميل يقيم المزود</small></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@foreach($offers as $offer)
    @if($offer->status === 'accepted' && auth()->id() === $service->user_id && !$offer->delivered_at)
        <!-- Modal تسليم الخدمة مع التقييم الإلزامي -->
        <div class="modal fade" id="deliverModal{{ $offer->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3 shadow-lg">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title"><i class="fas fa-check-double me-2"></i> تسليم الخدمة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('service-offers.deliver', $offer) }}" id="deliverForm{{ $offer->id }}">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>ملاحظة:</strong> يجب تقييم المزود قبل تسليم الخدمة. هذا التقييم إلزامي.
                            </div>
                            <div class="mb-3 text-center">
                                <label class="form-label fw-bold mb-2">التقييم <span class="text-danger">*</span></label>
                                <div class="rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="deliverRating{{ $i }}_{{ $offer->id }}" class="rating-radio" required>
                                        <label for="deliverRating{{ $i }}_{{ $offer->id }}" class="rating-label">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                <small class="text-danger d-block mt-2" id="ratingError{{ $offer->id }}" style="display: none;">يجب اختيار التقييم</small>
                            </div>
                            <div class="mb-3">
                                <label for="deliverReview{{ $offer->id }}" class="form-label fw-bold">تعليق (اختياري)</label>
                                <textarea class="form-control" id="deliverReview{{ $offer->id }}" name="review" rows="3"
                                          placeholder="اكتب تعليقك عن الخدمة والمزود..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="fas fa-check-double me-1"></i> تسليم وتقييم
                            </button>
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
    // التحقق من التقييم قبل إرسال نموذج التسليم
    @foreach($offers as $offer)
        @if($offer->status === 'accepted' && auth()->id() === $service->user_id && !$offer->delivered_at)
            const deliverForm{{ $offer->id }} = document.getElementById('deliverForm{{ $offer->id }}');
            if (deliverForm{{ $offer->id }}) {
                deliverForm{{ $offer->id }}.addEventListener('submit', function(e) {
                    const rating = deliverForm{{ $offer->id }}.querySelector('input[name="rating"]:checked');
                    const ratingError{{ $offer->id }} = document.getElementById('ratingError{{ $offer->id }}');
                    
                    if (!rating) {
                        e.preventDefault();
                        if (ratingError{{ $offer->id }}) {
                            ratingError{{ $offer->id }}.style.display = 'block';
                        }
                        alert('يجب اختيار التقييم قبل تسليم الخدمة');
                        return false;
                    } else {
                        if (ratingError{{ $offer->id }}) {
                            ratingError{{ $offer->id }}.style.display = 'none';
                        }
                    }
                });
            }
        @endif
    @endforeach
});
</script>
@endpush

@endsection

@push('styles')
<style>
.text-teal { color: #009688 !important; }
.bg-teal { background-color: #009688 !important; }
.btn-teal { background-color: #009688; color: #fff; }
.btn-teal:hover { background-color: #007d7b; color: #fff; }
.text-gold { color: #d4af37 !important; }
.bg-gold { background-color: #f7e08b !important; }

.btn-outline-teal {
    border-color: #009688;
    color: #009688;
}
.btn-outline-teal:hover {
    background-color: #009688;
    color: #fff;
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    gap: 6px;
}
.rating-radio { display: none; }
.rating-label {
    cursor: pointer;
    font-size: 2rem;
    color: #ccc;
    transition: color 0.2s ease;
}
.rating-label:hover,
.rating-label:hover ~ .rating-label,
.rating-radio:checked ~ .rating-label { color: #ffc107; }

.timeline {
    position: relative;
    padding-left: 20px;
}
.timeline-item {
    position: relative;
    margin-bottom: 15px;
}
.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}
.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -19px;
    top: 17px;
    width: 2px;
    height: 20px;
    background-color: #dee2e6;
}
.modal {
    z-index: 20000 !important;
}
.modal-backdrop {
    z-index: 19999 !important;
}
.modal-dialog {
    pointer-events: auto !important;
}
.modal-backdrop {
    pointer-events: none !important;
}

.modal-backdrop.show {
    background-color: rgba(0, 0, 0, 0.1); /* شفافية خفيفة */
}

</style>
@endpush
