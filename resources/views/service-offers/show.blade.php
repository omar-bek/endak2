@extends('layouts.app')

@section('title', 'تفاصيل العرض')

@section('content')
    <div class="offer-container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="offer-card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="offer-header text-white px-4 py-3">
                        <h4 class="mb-0 d-flex align-items-center gap-2">
                            <i class="fas fa-file-contract"></i>
                            تفاصيل العرض المقدم
                        </h4>
                    </div>

                    <div class="offer-body p-4">
                        <div class="row mb-4 align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-dark me-2">حالة العرض:</span>
                                    <span
                                        class="badge bg-{{ $offer->status === 'accepted' ? 'success' : ($offer->status === 'rejected' ? 'danger' : 'warning') }} fs-6 px-3 py-2 shadow-sm">
                                        {{ $offer->status_label }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1 text-primary"></i>
                                    تم التقديم: {{ $offer->created_at->format('Y-m-d H:i') }}
                                </small>
                            </div>
                        </div>

                        <!-- تفاصيل الخدمة -->
                        @if ($offer->service)
                            <div class="card border-0 shadow-sm mb-4 rounded-3 service-details">
                                <div class="card-header bg-light border-0 py-3 d-flex align-items-center">
                                    <i class="fas fa-concierge-bell text-primary fs-5 me-2"></i>
                                    <h5 class="mb-0 text-primary fw-bold">تفاصيل الخدمة</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="fw-bold text-dark">{{ $offer->service->title }}</h6>
                                            <p class="text-muted mb-3">{{ $offer->service->description }}</p>
                                            @if ($offer->service->category)
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-folder text-primary me-2"></i>
                                                    <span>{{ app()->getLocale() == 'ar' ? $offer->service->category->name : $offer->service->category->name_en }}</span>
                                                </div>
                                            @endif
                                            @if ($offer->service->city)
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                    <span>{{ app()->getLocale() == 'ar' ? $offer->service->city->name_ar : $offer->service->city->name_en }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> الخدمة المرتبطة بهذا العرض غير موجودة أو تم
                                حذفها
                            </div>
                        @endif

                        <!-- تفاصيل العرض -->
                        <div class="card border-0 shadow-sm mb-4 rounded-3 offer-details">
                            <div class="card-header bg-light border-0 py-3 d-flex align-items-center">
                                <i class="fas fa-handshake text-success fs-5 me-2"></i>
                                <h5 class="mb-0 text-success fw-bold">عرضك</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="fw-bold text-dark">السعر المقترح:</label>
                                            <div class="h4 text-success fw-bold">{{ $offer->formatted_price }}</div>
                                        </div>

                                        @if ($offer->notes)
                                            <div class="mb-3">
                                                <label class="fw-bold text-dark">ملاحظاتك:</label>
                                                <p class="text-muted bg-light p-2 rounded">{{ $offer->notes }}</p>
                                            </div>
                                        @endif

                                        @if ($offer->expires_at)
                                            <div class="mb-3">
                                                <label class="fw-bold text-dark">تاريخ انتهاء الصلاحية:</label>
                                                <div class="text-muted">
                                                    <i class="fas fa-calendar text-primary me-1"></i>
                                                    {{ $offer->expires_at->format('Y-m-d H:i') }}
                                                    @if ($offer->expires_at->isPast())
                                                        <span class="badge bg-danger ms-2">منتهي الصلاحية</span>
                                                    @else
                                                        <span class="badge bg-success ms-2">صالح</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        @if ($offer->status === 'pending')
                                            <span class="badge bg-warning fs-6 px-3 py-2">في انتظار الرد</span>
                                        @elseif($offer->status === 'accepted')
                                            <span class="badge bg-success fs-6 px-3 py-2">تم القبول</span>
                                        @elseif($offer->status === 'rejected')
                                            <span class="badge bg-danger fs-6 px-3 py-2">تم الرفض</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات العميل (للمزود) -->
                        @if (Auth::id() == $offer->provider_id && $customer)
                            <div class="card border-0 shadow-sm mb-4 rounded-3 customer-info">
                                <div class="card-header bg-light border-0 py-3 d-flex align-items-center">
                                    <i class="fas fa-user text-primary fs-5 me-2"></i>
                                    <h5 class="mb-0 text-primary fw-bold">معلومات العميل</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-2 text-center">
                                            <img src="{{ $customer->avatar_url }}" alt="{{ $customer->name }}"
                                                class="rounded-circle"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-7">
                                            <h5 class="fw-bold text-dark mb-1">{{ $customer->name }}</h5>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-envelope me-1"></i>{{ $customer->email }}
                                            </p>
                                        </div>
                                        <div class="col-md-3 text-md-end">
                                            <div class="d-flex flex-column gap-2">
                                                <a href="{{ route('messages.show', $customer->id) }}"
                                                    class="btn btn-primary btn-sm rounded-pill">
                                                    <i class="fas fa-envelope me-1"></i> إرسال رسالة
                                                </a>
                                                @if ($customer->isProvider() && $customer->hasCompleteProviderProfile())
                                                    <a href="{{ route('provider.profile.public', $customer->id) }}"
                                                        class="btn btn-outline-info btn-sm rounded-pill">
                                                        <i class="fas fa-user-circle me-1"></i> عرض الملف الشخصي
                                                    </a>
                                                @else
                                                    <a href="{{ route('user.profile.public', $customer->id) }}"
                                                        class="btn btn-outline-info btn-sm rounded-pill">
                                                        <i class="fas fa-user-circle me-1"></i> عرض الملف الشخصي
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- معلومات مزود الخدمة -->
                        @if (Auth::id() == $offer->service->user_id && $provider)
                            <div class="card border-0 shadow-sm mb-4 rounded-3 provider-info">
                                <div class="card-header bg-light border-0 py-3 d-flex align-items-center">
                                    <i class="fas fa-user-tie text-info fs-5 me-2"></i>
                                    <h5 class="mb-0 text-info fw-bold">معلومات مزود الخدمة</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center mb-3">
                                        <div class="col-md-2 text-center">
                                            <img src="{{ $provider->avatar_url }}" alt="{{ $provider->name }}"
                                                class="rounded-circle"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-7">
                                            <h5 class="fw-bold text-dark mb-1">{{ $provider->name }}</h5>
                                            @if ($providerProfile)
                                                @if ($providerProfile->rating)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="text-warning me-2">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i
                                                                    class="fas fa-star{{ $i <= round($providerProfile->rating) ? '' : '-o' }}"></i>
                                                            @endfor
                                                        </div>
                                                        <span
                                                            class="text-muted">({{ number_format($providerProfile->rating, 1) }})</span>
                                                        @if ($providerProfile->completed_services)
                                                            <span class="text-muted ms-2">•
                                                                {{ $providerProfile->completed_services }} خدمة
                                                                مكتملة</span>
                                                        @endif
                                                    </div>
                                                @endif
                                                @if ($providerProfile->bio)
                                                    <p class="text-muted small mb-0">
                                                        {{ Str::limit($providerProfile->bio, 150) }}</p>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="col-md-3 text-md-end">
                                            <div class="d-flex flex-column gap-2">
                                                <a href="{{ route('messages.show', $provider->id) }}"
                                                    class="btn btn-primary btn-sm rounded-pill">
                                                    <i class="fas fa-envelope me-1"></i> إرسال رسالة
                                                </a>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- التقييمات -->
                            @if ($ratings && $ratings->count() > 0)
                                <div class="card border-0 shadow-sm mb-4 rounded-3 ratings-section">
                                    <div class="card-header bg-light border-0 py-3 d-flex align-items-center">
                                        <i class="fas fa-star text-warning fs-5 me-2"></i>
                                        <h5 class="mb-0 text-warning fw-bold">تقييمات مزود الخدمة
                                            ({{ $ratings->count() }})</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($ratings as $rating)
                                                @if ($rating->service)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="rating-item p-3 bg-light rounded">
                                                            <div
                                                                class="d-flex justify-content-between align-items-start mb-2">
                                                                <div>
                                                                    <h6 class="mb-1 fw-bold">
                                                                        {{ $rating->service->user->name ?? 'مستخدم' }}</h6>
                                                                    <small
                                                                        class="text-muted">{{ $rating->service->title }}</small>
                                                                </div>
                                                                <div class="text-warning">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <i
                                                                            class="fas fa-star{{ $i <= $rating->rating ? '' : '-o' }}"></i>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                            @if ($rating->review)
                                                                <p class="text-muted small mb-0">{{ $rating->review }}</p>
                                                            @endif
                                                            <small class="text-muted d-block mt-2">
                                                                <i class="fas fa-calendar me-1"></i>
                                                                {{ $rating->created_at->format('Y-m-d') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
                            <a href="{{ route('services.index') }}" class="btn btn-secondary rounded-pill px-4 py-2">
                                <i class="fas fa-arrow-left"></i> العودة للخدمات
                            </a>

                            @if (Auth::id() == $offer->provider_id)
                                <a href="{{ route('service-offers.my-offers') }}"
                                    class="btn btn-outline-primary rounded-pill px-4 py-2">
                                    <i class="fas fa-list"></i> عروضي
                                </a>
                            @elseif($offer->service && Auth::id() == $offer->service->user_id)
                                <a href="{{ route('services.show', $offer->service->slug) }}"
                                    class="btn btn-outline-primary rounded-pill px-4 py-2">
                                    <i class="fas fa-eye"></i> عرض عروض الخدمة
                                </a>

                                @if ($offer->status === 'pending')
                                    <div class="btn-group">
                                        <!-- زر قبول العرض -->
                                        <button type="button" class="btn btn-success rounded-pill px-4 py-2"
                                            data-bs-toggle="modal" data-bs-target="#acceptModal{{ $offer->id }}">
                                            <i class="fas fa-check"></i> قبول العرض
                                        </button>

                                        <!-- زر رفض العرض -->
                                        <button type="button" class="btn btn-danger rounded-pill px-4 py-2"
                                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $offer->id }}">
                                            <i class="fas fa-times"></i> رفض العرض
                                        </button>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- Modal قبول العرض -->
                        <div class="modal fade" id="acceptModal{{ $offer->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 rounded-3 shadow-lg">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i> تأكيد قبول العرض
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p class="mb-3">هل أنت متأكد أنك تريد <span
                                                class="fw-bold text-success">قبول</span> هذا العرض؟</p>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">إلغاء</button>
                                        <form method="POST" action="{{ route('service-offers.accept', $offer->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>
                                                تأكيد</button>
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
                                        <h5 class="modal-title"><i class="fas fa-times-circle me-2"></i> تأكيد رفض العرض
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <p class="mb-3">هل أنت متأكد أنك تريد <span
                                                class="fw-bold text-danger">رفض</span> هذا العرض؟</p>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">إلغاء</button>
                                        <form method="POST" action="{{ route('service-offers.reject', $offer->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i>
                                                تأكيد</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @push('styles')
                            <style>
                                .modal-backdrop.show {
                                    backdrop-filter: blur(6px);
                                    background-color: rgba(0, 0, 0, 0.3);
                                }

                                .modal-content {
                                    background-color: #fff !important;
                                    backdrop-filter: none !important;
                                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                                }

                                .modal {
                                    z-index: 20000 !important;
                                }

                                .modal-backdrop {
                                    z-index: 19999 !important;
                                }

                                html {
                                    overflow-y: scroll;
                                }

                                .modal-open .offer-card,
                                .modal-open .offer-card * {
                                    transform: none !important;
                                    transition: none !important;
                                }

                                body.modal-open {
                                    padding-right: 0 !important;
                                }
                            </style>
                        @endpush

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .offer-container {
            background: #f5f6fa;
            font-family: 'Cairo', sans-serif;
        }

        .offer-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .offer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(47, 92, 105, 0.15);
        }

        .offer-header {
            background: linear-gradient(135deg, #2f5c69, #3c7d8b);
        }

        .card-header {
            background-color: #f8fafc !important;
        }

        .badge {
            font-size: 0.95rem;
            border-radius: 30px;
        }

        .btn {
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border-color: #3c7d8b;
            color: #3c7d8b;
        }

        .btn-outline-primary:hover {
            background-color: #3c7d8b;
            color: #fff;
        }

        .text-primary {
            color: #2f5c69 !important;
        }

        .text-success {
            color: #3b8c66 !important;
        }
    </style>
@endsection
