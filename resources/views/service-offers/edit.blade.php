@extends('layouts.app')

@section('title', 'تعديل العرض')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('service-offers.my-offers') }}">عروضي</a></li>
                    <li class="breadcrumb-item active">تعديل العرض</li>
                </ol>
            </nav>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i>
                        تعديل العرض
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('service-offers.update', $offer) }}">
                        @csrf
                        @method('PUT')

                        <!-- معلومات الخدمة -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-info-circle"></i>
                                معلومات الخدمة
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">عنوان الخدمة</label>
                                        <input type="text" class="form-control" value="{{ $offer->service->title }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">القسم</label>
                                        <input type="text" class="form-control" value="{{ $offer->service->category->name }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">صاحب الخدمة</label>
                                        <input type="text" class="form-control" value="{{ $offer->service->user->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">السعر المطلوب</label>
                                        <input type="text" class="form-control" value="{{ $offer->service->formatted_price }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- تفاصيل العرض -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-handshake"></i>
                                تفاصيل العرض
                            </h6>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price" class="form-label">السعر المقترح <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control @error('price') is-invalid @enderror"
                                                   id="price" name="price" value="{{ old('price', $offer->price) }}"
                                                   step="0.01" min="0" required>
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">حالة العرض</label>
                                        <input type="text" class="form-control" value="{{ $offer->status_label }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">تاريخ إنشاء العرض</label>
                                        <input type="text" class="form-control" value="{{ $offer->created_at->format('Y-m-d H:i') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">آخر تحديث</label>
                                        <input type="text" class="form-control" value="{{ $offer->updated_at->format('Y-m-d H:i') }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes" class="form-label">ملاحظاتك حول العرض</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes" name="notes" rows="4"
                                          placeholder="اكتب ملاحظاتك حول العرض...">{{ old('notes', $offer->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- أزرار الإجراءات -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                حفظ التغييرات
                            </button>
                            <a href="{{ route('service-offers.my-offers') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right"></i>
                                العودة لعروضي
                            </a>
                            <a href="{{ route('services.show', $offer->service->slug) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye"></i>
                                عرض الخدمة
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        معلومات إضافية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="border-end">
                                <h5 class="text-primary mb-0">{{ $offer->id }}</h5>
                                <small class="text-muted">رقم العرض</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-end">
                                <h5 class="text-success mb-0">{{ $offer->formatted_price }}</h5>
                                <small class="text-muted">السعر الحالي</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-info mb-0">{{ $offer->status_label }}</h5>
                            <small class="text-muted">الحالة</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحديث السعر عند تغيير القيمة
    const priceInput = document.getElementById('price');
    const priceDisplay = document.querySelector('.text-success');

    priceInput.addEventListener('input', function() {
        const value = parseFloat(this.value) || 0;
        priceDisplay.textContent = value.toFixed(2) + ' ريال';
    });

    // تحسين تجربة المستخدم
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const price = parseFloat(document.getElementById('price').value);
        const originalPrice = {{ $offer->price }};

        if (price !== originalPrice) {
            if (!confirm('هل أنت متأكد من تغيير السعر؟')) {
                e.preventDefault();
                return false;
            }
        }
    });
});
</script>
@endpush
