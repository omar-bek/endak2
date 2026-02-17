@extends('layouts.app')

@section('title', 'الخدمات المكتملة')

@section('content')
<div class="completed-services-page py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="text-primary fw-bold d-flex align-items-center gap-2">
                <i class="fas fa-check-circle text-success"></i> الخدمات المكتملة
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('service-offers.my-offers') }}" class="btn btn-outline-primary btn-lg shadow-sm">
                    <i class="fas fa-list"></i> جميع العروض
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

        <!-- الإحصائيات -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fas fa-check-circle fa-3x text-success"></i>
                        </div>
                        <h3 class="text-success fw-bold mb-1">{{ $totalCompleted }}</h3>
                        <p class="text-muted mb-0">خدمة مكتملة</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fas fa-star fa-3x text-warning"></i>
                        </div>
                        <h3 class="text-warning fw-bold mb-1">{{ $totalRated }}</h3>
                        <p class="text-muted mb-0">خدمة تم تقييمها</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-2">
                            <i class="fas fa-chart-line fa-3x text-info"></i>
                        </div>
                        <h3 class="text-info fw-bold mb-1">
                            @if($averageRating)
                                {{ number_format($averageRating, 1) }}
                            @else
                                0
                            @endif
                        </h3>
                        <p class="text-muted mb-0">متوسط التقييم</p>
                    </div>
                </div>
            </div>
        </div>

        @if($completedOffers->count() > 0)
            <div class="row">
                @foreach($completedOffers as $offer)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card service-card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
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

                                <!-- معلومات العميل -->
                                @if($offer->service && $offer->service->user)
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="{{ $offer->service->user->avatar_url }}" 
                                             alt="{{ $offer->service->user->name }}"
                                             class="rounded-circle me-2"
                                             style="width: 30px; height: 30px; object-fit: cover;">
                                        <span class="text-muted small">{{ $offer->service->user->name }}</span>
                                    </div>
                                @endif

                                <!-- القسم -->
                                @if($offer->service && $offer->service->category)
                                    <p class="text-muted mb-2 small">
                                        <i class="fas fa-folder text-gold"></i>
                                        {{ $offer->service->category->name }}
                                    </p>
                                @endif

                                <!-- السعر -->
                                <div class="price-tag mb-3">
                                    <h4 class="text-success fw-bold mb-0">{{ $offer->formatted_price }}</h4>
                                </div>

                                <!-- التقييم -->
                                @if($offer->rating)
                                    <div class="rating-section mb-3 p-3 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold">التقييم:</span>
                                            <div class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star{{ $i <= $offer->rating ? '' : '-o' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        @if($offer->review)
                                            <p class="text-muted small mb-0 mt-2">
                                                <i class="fas fa-comment me-1"></i>
                                                {{ Str::limit($offer->review, 100) }}
                                            </p>
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-info mb-3 py-2">
                                        <small class="d-flex align-items-center">
                                            <i class="fas fa-info-circle me-2"></i>
                                            لم يتم تقييم هذه الخدمة بعد
                                        </small>
                                    </div>
                                @endif

                                <!-- تاريخ التسليم -->
                                @if($offer->delivered_at)
                                    <p class="text-muted mb-2 small">
                                        <i class="fas fa-calendar-check text-success"></i>
                                        تم التسليم: {{ $offer->delivered_at->format('Y-m-d H:i') }}
                                    </p>
                                @endif
                            </div>

                            <div class="card-footer bg-transparent border-0 pt-0">
                                <div class="d-flex justify-content-between gap-2">
                                    @if($offer->service)
                                        <a href="{{ route('service-offers.show', $offer->id) }}" 
                                           class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="fas fa-eye"></i> عرض التفاصيل
                                        </a>
                                        <a href="{{ route('messages.show', $offer->service->user_id) }}" 
                                           class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $completedOffers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">لا توجد خدمات مكتملة بعد</h4>
                        <p class="text-muted mb-4">عندما تكتمل الخدمات التي قدمت عليها عروضاً، ستظهر هنا</p>
                        <a href="{{ route('services.index') }}" class="btn btn-primary">
                            <i class="fas fa-search"></i> البحث عن خدمات جديدة
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.completed-services-page {
    background: #f5f6fa;
    font-family: 'Cairo', sans-serif;
}

.service-card {
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.rating-section {
    border-left: 3px solid #ffc107;
}

.price-tag {
    padding: 10px;
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border-radius: 8px;
    text-align: center;
}

.card-img-top {
    transition: transform 0.3s ease;
}

.service-card:hover .card-img-top {
    transform: scale(1.05);
}
</style>
@endsection


