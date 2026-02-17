@extends('layouts.admin')

@section('title', 'تعديل العرض')
@section('page-title', 'تعديل العرض')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit text-primary"></i>
                    تعديل العرض
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.service-offers.update', $offer) }}">
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

                    <!-- معلومات المزود -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-user-tie"></i>
                            معلومات مزود الخدمة
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">اسم المزود</label>
                                    <input type="text" class="form-control" value="{{ $offer->provider->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="text" class="form-control" value="{{ $offer->provider->email }}" readonly>
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
                                    <label for="status" class="form-label">حالة العرض <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="pending" {{ old('status', $offer->status) == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                                        <option value="accepted" {{ old('status', $offer->status) == 'accepted' ? 'selected' : '' }}>مقبول</option>
                                        <option value="rejected" {{ old('status', $offer->status) == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                                        <option value="expired" {{ old('status', $offer->status) == 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                            <label for="notes" class="form-label">ملاحظات المزود</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" name="notes" rows="4"
                                      placeholder="ملاحظات المزود حول العرض...">{{ old('notes', $offer->notes) }}</textarea>
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
                        <a href="{{ route('admin.service-offers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                        <a href="{{ route('admin.service-offers.show', $offer) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i>
                            عرض التفاصيل
                        </a>
                        @if(!in_array($offer->status, ['accepted', 'delivered']))
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash"></i>
                                حذف العرض
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- اللوحة الجانبية -->
    <div class="col-lg-4">
        <!-- معلومات سريعة -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle"></i>
                    معلومات سريعة
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-primary mb-0">{{ $offer->id }}</h5>
                            <small class="text-muted">رقم العرض</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-success mb-0">{{ $offer->formatted_price }}</h5>
                        <small class="text-muted">السعر الحالي</small>
                    </div>
                </div>

                <hr>

                <div class="mb-2">
                    <strong>الحالة الحالية:</strong>
                    <span class="badge bg-{{ $offer->status_color }} ms-2">{{ $offer->status_label }}</span>
                </div>


                @if($offer->accepted_at)
                <div class="mb-2">
                    <strong>تاريخ القبول:</strong>
                    <br>
                    <small class="text-muted">{{ $offer->accepted_at->format('Y-m-d H:i') }}</small>
                </div>
                @endif
            </div>
        </div>

        <!-- إجراءات سريعة -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt"></i>
                    إجراءات سريعة
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('services.show', $offer->service->slug) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        عرض الخدمة
                    </a>
                    <a href="{{ route('admin.services.show', $offer->service) }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-cogs"></i>
                        إدارة الخدمة
                    </a>
                    <a href="{{ route('admin.users.show', $offer->provider) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-user"></i>
                        ملف المزود
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تأكيد الحذف -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    تأكيد الحذف
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذا العرض؟</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i>
                    <strong>تحذير:</strong> لا يمكن التراجع عن هذا الإجراء.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form method="POST" action="{{ route('admin.service-offers.destroy', $offer) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        حذف العرض
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

// تحسين تجربة المستخدم
document.addEventListener('DOMContentLoaded', function() {
    // تحديث السعر عند تغيير القيمة
    const priceInput = document.getElementById('price');
    const priceDisplay = document.querySelector('.text-success');

    priceInput.addEventListener('input', function() {
        const value = parseFloat(this.value) || 0;
        priceDisplay.textContent = value.toFixed(2) + ' ريال';
    });

    // تحذير عند تغيير الحالة
    const statusSelect = document.getElementById('status');
    const originalStatus = '{{ $offer->status }}';

    statusSelect.addEventListener('change', function() {
        if (this.value !== originalStatus) {
            const statusNames = {
                'pending': 'في الانتظار',
                'accepted': 'مقبول',
                'rejected': 'مرفوض',
                'expired': 'منتهي الصلاحية'
            };

            if (confirm(`هل أنت متأكد من تغيير حالة العرض إلى "${statusNames[this.value]}"؟`)) {
                return true;
            } else {
                this.value = originalStatus;
                return false;
            }
        }
    });

});
</script>
@endpush
