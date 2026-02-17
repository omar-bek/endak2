@extends('layouts.admin')

@section('title', 'عرض العرض')
@section('page-title', 'عرض العرض')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.service-offers.index') }}">إدارة عروض الخدمات</a></li>
            <li class="breadcrumb-item active">عرض العرض</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Offer Details -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-handshake"></i> تفاصيل العرض</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>رقم العرض:</strong>
                                <span class="ms-2">#{{ $offer->id }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>السعر المقترح:</strong>
                                <span class="h4 text-success ms-2">{{ $offer->formatted_price }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>الحالة:</strong>
                                @if($offer->status === 'pending')
                                    <span class="badge bg-warning fs-6 ms-2">في الانتظار</span>
                                @elseif($offer->status === 'accepted')
                                    <span class="badge bg-success fs-6 ms-2">مقبول</span>
                                @elseif($offer->status === 'rejected')
                                    <span class="badge bg-danger fs-6 ms-2">مرفوض</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <strong>تاريخ التقديم:</strong>
                                <span class="ms-2">{{ $offer->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($offer->notes)
                            <div class="mb-3">
                                <strong>الملاحظات:</strong>
                                <div class="mt-2 p-3 bg-light rounded">
                                    {{ $offer->notes }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Details -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-concierge-bell"></i> تفاصيل الخدمة</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            @if($offer->service->image)
                                <img src="{{ asset('storage/' . $offer->service->image) }}" alt="{{ $offer->service->title }}"
                                     class="img-fluid rounded" style="max-width: 150px;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="width: 150px; height: 150px;">
                                    <i class="fas fa-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h5 class="mb-3">{{ $offer->service->title }}</h5>
                            <p class="text-muted mb-3">{{ $offer->service->description }}</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>القسم:</strong>
                                        <span class="badge bg-primary ms-2">{{ app()->getLocale() == 'ar' ? $offer->service->category->name : $offer->service->category->name_en }}</span>
                                    </div>
                                    @if($offer->service->subCategory)
                                    <div class="mb-2">
                                        <strong>القسم الفرعي:</strong>
                                        <span class="badge bg-info ms-2">{{ app()->getLocale() == 'ar' ? $offer->service->subCategory->name_ar : $offer->service->subCategory->name_en }}</span>
                                    </div>
                                    @endif
                                    <div class="mb-2">
                                        <strong>المدينة:</strong>
                                        @if($offer->service->city)
                                            <span class="badge bg-secondary ms-2">{{ app()->getLocale() == 'ar' ? $offer->service->city->name_ar : $offer->service->city->name_en }}</span>
                                        @else
                                            <span class="text-muted ms-2">غير محدد</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>السعر المطلوب:</strong>
                                        <span class="h5 text-primary ms-2">{{ $offer->service->formatted_price }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>حالة الخدمة:</strong>
                                        @if($offer->service->is_active)
                                            <span class="badge bg-success ms-2">نشطة</span>
                                        @else
                                            <span class="badge bg-danger ms-2">معطلة</span>
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <strong>تاريخ الخدمة:</strong>
                                        <span class="text-muted ms-2">{{ $offer->service->created_at->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Provider Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-tie"></i> معلومات مزود الخدمة</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ $offer->provider->name }}&background=667eea&color=fff"
                             alt="Avatar" class="rounded-circle" width="80" height="80">
                        <h5 class="mt-2 mb-1">{{ $offer->provider->name }}</h5>
                        <p class="text-muted mb-2">{{ $offer->provider->email }}</p>
                        <span class="badge bg-success">مزود خدمة</span>
                    </div>

                    @if($offer->provider->providerProfile)
                    <div class="mb-3">
                        <strong>نبذة:</strong>
                        <p class="text-muted mt-1">{{ Str::limit($offer->provider->providerProfile->about ?? 'غير محدد', 100) }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>العنوان:</strong>
                        <p class="text-muted mt-1">{{ $offer->provider->providerProfile->address ?? 'غير محدد' }}</p>
                    </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.show', $offer->provider->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-eye"></i> عرض الملف الشخصي
                        </a>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> الإجراءات</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('services.show', $offer->service->slug) }}" class="btn btn-outline-primary" target="_blank">
                            <i class="fas fa-external-link-alt"></i> عرض الخدمة
                        </a>

                        <form action="{{ route('admin.service-offers.toggle-status', $offer->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $offer->status === 'pending' ? 'success' : 'warning' }} w-100">
                                <i class="fas fa-{{ $offer->status === 'pending' ? 'check' : 'undo' }}"></i>
                                {{ $offer->status === 'pending' ? 'قبول العرض' : 'إعادة إلى الانتظار' }}
                            </button>
                        </form>

                        @if($offer->status === 'pending')
                        <form action="{{ route('admin.service-offers.toggle-status', $offer->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times"></i> رفض العرض
                            </button>
                        </form>
                        @endif

                        @if(!in_array($offer->status, ['accepted', 'delivered']))
                        <a href="{{ route('admin.service-offers.edit', $offer->id) }}" class="btn btn-warning w-100">
                            <i class="fas fa-edit"></i> تعديل العرض
                        </a>

                        <button type="button" class="btn btn-outline-danger w-100"
                                onclick="confirmDelete({{ $offer->id }}, '{{ $offer->service->title }}')">
                            <i class="fas fa-trash"></i> حذف العرض
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Price Comparison -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> مقارنة الأسعار</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>السعر المطلوب:</span>
                        <span class="h5 text-primary">{{ $offer->service->formatted_price }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>السعر المقترح:</span>
                        <span class="h5 text-success">{{ $offer->formatted_price }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>الفرق:</span>
                        @php
                            $difference = $offer->price - $offer->service->price;
                            $differenceFormatted = number_format(abs($difference), 2) . ' ريال';
                        @endphp
                        <span class="h6 text-{{ $difference >= 0 ? 'danger' : 'success' }}">
                            {{ $difference >= 0 ? '+' : '-' }}{{ $differenceFormatted }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(offerId, serviceTitle) {
    if (confirm(`هل أنت متأكد من حذف العرض للخدمة "${serviceTitle}"؟\n\nلا يمكن التراجع عن هذا الإجراء.`)) {
        // إنشاء نموذج حذف مخفي
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/service-offers/${offerId}`;

        // إضافة token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // إضافة method spoofing
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);

        // إرسال النموذج
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
