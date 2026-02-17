@extends('layouts.admin')

@section('title', 'إدارة عروض الخدمات')
@section('page-title', 'إدارة عروض الخدمات')

@section('content')
<div class="container-fluid">
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="ابحث في العروض..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>مقبول</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="service" class="form-select">
                        <option value="">جميع الخدمات</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ request('service') == $service->id ? 'selected' : '' }}>
                            {{ Str::limit($service->title, 30) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Offers Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-handshake"></i> قائمة عروض الخدمات</h3>
            <div class="card-tools">
                <span class="badge bg-primary">{{ $offers->total() }} عرض</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>الخدمة</th>
                            <th>مزود الخدمة</th>
                            <th>السعر المقترح</th>
                            <th>السعر المطلوب</th>
                            <th>الملاحظات</th>
                            <th>الحالة</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($offers as $offer)
                            @continue(!$offer->service) {{-- يتخطى العرض لو الخدمة محذوفة --}}
                            <tr>
                                <td>{{ $offer->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($offer->service->image)
                                            <img src="{{ asset('storage/' . $offer->service->image) }}" alt="{{ $offer->service->title }}"
                                                 class="img-thumbnail me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light text-center rounded me-2" style="width: 40px; height: 40px; line-height: 40px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ Str::limit($offer->service->title, 25) }}</div>
                                            <small class="text-muted">{{ app()->getLocale() == 'ar' ? $offer->service->category->name : $offer->service->category->name_en }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ $offer->provider->name }}&background=667eea&color=fff"
                                             alt="Avatar" class="rounded-circle me-2" width="30" height="30">
                                        <div>
                                            <div class="fw-bold">{{ $offer->provider->name }}</div>
                                            <small class="text-muted">{{ $offer->provider->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">{{ $offer->formatted_price }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-primary">{{ $offer->service->formatted_price }}</span>
                                </td>
                                <td>
                                    @if($offer->notes)
                                        {{ Str::limit($offer->notes, 30) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($offer->status === 'pending')
                                        <span class="badge bg-warning">في الانتظار</span>
                                    @elseif($offer->status === 'accepted')
                                        <span class="badge bg-success">مقبول</span>
                                    @elseif($offer->status === 'rejected')
                                        <span class="badge bg-danger">مرفوض</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $offer->created_at->format('Y-m-d') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.service-offers.show', $offer->id) }}" class="btn btn-info" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!in_array($offer->status, ['accepted', 'delivered']))
                                            <a href="{{ route('service-offers.edit', $offer->id) }}" class="btn btn-warning" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('services.show', $offer->service->slug) }}" class="btn btn-outline-primary" title="عرض الخدمة">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <form action="{{ route('admin.service-offers.toggle-status', $offer->id) }}" method="POST" style="display:inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success" title="{{ $offer->status === 'pending' ? 'قبول' : 'إعادة إلى الانتظار' }}">
                                                <i class="fas fa-{{ $offer->status === 'pending' ? 'check' : 'undo' }}"></i>
                                            </button>
                                        </form>
                                        @if(!in_array($offer->status, ['accepted', 'delivered']))
                                            <button type="button" class="btn btn-danger" title="حذف"
                                                    onclick="confirmDelete({{ $offer->id }}, '{{ $offer->service->title }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-handshake text-muted" style="font-size: 3rem;"></i>
                                <h5 class="mt-3 text-muted">لا توجد عروض</h5>
                                <p class="text-muted">لم يتم تقديم أي عروض بعد</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $offers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form on filter change
    const filterForm = document.querySelector('form[method="GET"]');
    const filterInputs = filterForm.querySelectorAll('select, input[type="text"]');

    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            filterForm.submit();
        });
    });
});

function confirmDelete(offerId, serviceTitle) {
    if (confirm(`هل أنت متأكد من حذف العرض للخدمة "${serviceTitle}"؟\n\nلا يمكن التراجع عن هذا الإجراء.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/service-offers/${offerId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
