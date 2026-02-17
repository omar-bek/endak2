@extends('layouts.app')

@section('title', 'عروضي')

@section('content')
<div class="offers-page py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="text-primary fw-bold d-flex align-items-center gap-2">
                <i class="fas fa-handshake text-gold"></i> عروضي المقدمة
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('service-offers.completed-services') }}" class="btn btn-success btn-lg shadow-sm">
                    <i class="fas fa-check-circle"></i> الخدمات المكتملة
                </a>
                <a href="{{ route('services.index') }}" class="btn btn-gold btn-lg shadow-sm">
                    <i class="fas fa-search"></i> البحث عن خدمات
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm border-start border-4 border-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger shadow-sm border-start border-4 border-danger">
                {{ session('error') }}
            </div>
        @endif

@if($offers->count() > 0)
    <div class="row">
        @foreach($offers as $offer)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card offer-card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    @if($offer->service && $offer->service->image)
                        <img src="{{ asset('storage/' . $offer->service->image) }}"
                             alt="{{ $offer->service->title }}"
                             class="card-img-top"
                             style="height: 220px; object-fit: cover;">
                    @elseif($offer->service && $offer->service->category && $offer->service->category->image_url)
                        <img src="{{ $offer->service->category->image_url }}"
                             alt="{{ $offer->service->title }}"
                             onerror="this.onerror=null; this.src='{{ asset('images/default-service.svg') }}';"
                             class="card-img-top"
                             style="height: 220px; object-fit: cover;">
                    @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center rounded-top" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif

                            <div class="card-body">
                                <!-- عنوان الخدمة -->
                                <h5 class="card-title fw-bold text-primary mb-2 text-truncate">
                                    {{ $offer->service ? $offer->service->title : 'خدمة محذوفة' }}
                                </h5>

                                <!-- القسم -->
                                @if($offer->service && $offer->service->category)
                                    <p class="text-muted mb-2 small">
                                        <i class="fas fa-folder text-gold"></i>
                                        {{ $offer->service->category->name }}
                                    </p>
                                @endif

                                <!-- صاحب الخدمة -->
                                @if($offer->service && $offer->service->user)
                                    <p class="text-muted mb-2 small">
                                        <i class="fas fa-user text-info"></i>
                                        {{ $offer->service->user->name }}
                                    </p>
                                @endif

                                <!-- السعر -->
                                <div class="price-tag mb-3">
                                    <h4 class="text-success fw-bold mb-0">{{ $offer->formatted_price }}</h4>
                                </div>

                                <!-- الحالة -->
                                <div class="mb-3">
                                    <span class="badge rounded-pill bg-{{ $offer->status_color }} px-3 py-2">
                                        {{ $offer->status_label }}
                                    </span>
                                </div>

                                <!-- التاريخ -->
                                <p class="text-muted mb-2 small">
                                    <i class="fas fa-calendar text-info"></i>
                                    {{ $offer->created_at->format('Y-m-d H:i') }}
                                </p>

                                <!-- الملاحظات -->
                                @if($offer->notes)
                                    <p class="card-text text-muted small">
                                        {{ Str::limit($offer->notes, 100) }}
                                    </p>
                                @endif
                            </div>

                            <div class="card-footer bg-transparent border-0 pt-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    @if($offer->service)
                                        <a href="{{ route('services.show', $offer->service->slug) }}"
                                           class="btn btn-outline-primary btn-sm rounded-pill">
                                            <i class="fas fa-eye"></i> عرض الخدمة
                                        </a>
                                    @else
                                        <span class="btn btn-outline-secondary btn-sm rounded-pill disabled">
                                            <i class="fas fa-ban"></i> خدمة محذوفة
                                        </span>
                                    @endif

                                    @if($offer->status === 'pending')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock"></i> في الانتظار
                                        </span>
                                    @elseif($offer->status === 'accepted')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> مقبول
                                        </span>
                                    @elseif($offer->status === 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times"></i> مرفوض
                                        </span>
                                    @endif
                                </div>

                                <!-- أزرار -->
                                <div class="mt-3 text-center">
                                    <div class="btn-group" role="group">
                                        @if($offer->status === 'pending')
                                            <a href="{{ route('service-offers.edit', $offer->id) }}"
                                               class="btn btn-warning btn-sm rounded-pill">
                                                <i class="fas fa-edit"></i> تعديل
                                            </a>
                                        @endif
                                        <a href="{{ route('messages.offer-conversation', $offer->id) }}"
                                           class="btn btn-outline-info btn-sm rounded-pill">
                                            <i class="fas fa-comments"></i> رسالة
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- الترقيم -->
            <div class="d-flex justify-content-center mt-4">
                {{ $offers->links() }}
            </div>
        @else
            <!-- لا توجد عروض -->
            <div class="text-center py-5">
                <i class="fas fa-handshake fa-4x text-muted mb-4"></i>
                <h4 class="text-muted">لا توجد عروض مقدمة</h4>
                <p class="text-muted mb-4">لم تقدم أي عروض بعد. ابدأ بالبحث عن خدمات لتقدم عليها!</p>
                <a href="{{ route('services.index') }}" class="btn btn-gold btn-lg shadow-sm">
                    <i class="fas fa-search"></i> البحث عن خدمات
                </a>
            </div>
        @endif
    </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');

body {
    font-family: 'Cairo', sans-serif;
    background-color: #f5f7fa;
}

.text-primary { color: #2f5c69 !important; }
.text-gold { color: #f3a446 !important; }
.btn-gold {
    background: linear-gradient(90deg, #f3a446, #f6b65a);
    color: #fff;
    border: none;
    border-radius: 30px;
    transition: 0.3s;
}
.btn-gold:hover {
    background: #2f5c69;
    color: #fff;
}
.btn-outline-primary {
    border-color: #2f5c69;
    color: #2f5c69;
}
.btn-outline-primary:hover {
    background: #2f5c69;
    color: #fff;
}

/* الكروت */
.offer-card {
    border-radius: 20px;
    overflow: hidden;
    transition: transform 0.3s, box-shadow 0.3s;
}
.offer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(47, 92, 105, 0.15);
}

.offers-page h2 {
    font-weight: 700;
}

.badge {
    font-size: 0.9rem;
}

.btn-sm {
    font-size: 0.85rem;
}

.card-body {
    padding: 1.25rem 1.5rem;
}

.card-footer {
    padding: 1rem 1.5rem 1.5rem;
}
</style>
@endsection
