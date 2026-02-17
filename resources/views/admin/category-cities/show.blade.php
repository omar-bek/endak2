@extends('layouts.admin')

@section('title', 'إدارة مدن القسم: ' . $category->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog"></i>
                        إدارة مدن القسم: {{ $category->name }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.category-cities.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- معلومات القسم -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-info-circle"></i>
                                        معلومات القسم
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>اسم القسم:</strong></td>
                                            <td>{{ $category->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الوصف:</strong></td>
                                            <td>{{ $category->description ?? 'لا يوجد وصف' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>الحالة:</strong></td>
                                            <td>
                                                <span class="badge badge-{{ $category->is_active ? 'success' : 'danger' }}">
                                                    {{ $category->is_active ? 'مفعل' : 'معطل' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>الأقسام الفرعية:</strong></td>
                                            <td>{{ $category->children->count() }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-chart-pie"></i>
                                        إحصائيات المدن
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info">
                                                    <i class="fas fa-city"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">المدن المفعلة</span>
                                                    <span class="info-box-number" id="enabledCitiesCount">{{ count($enabledCities) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-warning">
                                                    <i class="fas fa-list"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">إجمالي المدن</span>
                                                    <span class="info-box-number">{{ $cities->count() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إدارة المدن -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-edit"></i>
                                        إدارة المدن المتاحة
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.category-cities.update-cities', $category->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="row">
                                            @foreach($cities as $city)
                                                <div class="col-md-3 mb-3">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox"
                                                               class="custom-control-input city-checkbox"
                                                               id="city_{{ $city->id }}"
                                                               name="cities[]"
                                                               value="{{ $city->id }}"
                                                               {{ in_array($city->id, $enabledCities) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="city_{{ $city->id }}">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span style="color: #000 !important; font-size: 14px;">{{ $city->name_ar ?? $city->name_en ?? 'اسم غير محدد' }}</span>
                                                                <span class="badge badge-{{ $city->is_active ? 'success' : 'secondary' }} ml-2">
                                                                    {{ $city->is_active ? 'مفعلة' : 'معطلة' }}
                                                                </span>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i>
                                                حفظ التغييرات
                                            </button>
                                            <button type="button" class="btn btn-success" onclick="selectAll()">
                                                <i class="fas fa-check-double"></i>
                                                تحديد الكل
                                            </button>
                                            <button type="button" class="btn btn-warning" onclick="deselectAll()">
                                                <i class="fas fa-times"></i>
                                                إلغاء التحديد
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المدن المفعلة -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-list-check"></i>
                                        المدن المفعلة حالياً
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row" id="enabledCitiesList">
                                        @foreach($cities as $city)
                                            @if(in_array($city->id, $enabledCities))
                                                <div class="col-md-3 mb-2">
                                                    <div class="card border-success" style="background-color: #f8f9fa;">
                                                        <div class="card-body p-2">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="text-dark font-weight-bold" style="color: #000 !important; font-size: 14px;">{{ $city->name_ar ?? $city->name_en ?? 'اسم غير محدد' }}</span>
                                                                <div>
                                                                    <button class="btn btn-sm btn-success toggle-city-btn"
                                                                            data-city-id="{{ $city->id }}"
                                                                            data-active="true">
                                                                        <i class="fas fa-toggle-on"></i>
                                                                    </button>
                                                                    <button class="btn btn-sm btn-danger remove-city-btn"
                                                                            data-city-id="{{ $city->id }}">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function selectAll() {
    $('.city-checkbox').prop('checked', true);
}

function deselectAll() {
    $('.city-checkbox').prop('checked', false);
}

// تفعيل/تعطيل مدينة
$(document).on('click', '.toggle-city-btn', function() {
    const cityId = $(this).data('city-id');
    const isActive = $(this).data('active');
    const button = $(this);

    $.ajax({
        url: '{{ route("admin.category-cities.toggle-city", ["category" => $category->id, "city" => ":city"]) }}'.replace(':city', cityId),
        method: 'POST',
        data: {
            is_active: !isActive,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                button.data('active', !isActive);
                if (!isActive) {
                    button.removeClass('btn-success').addClass('btn-secondary');
                    button.find('i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
                } else {
                    button.removeClass('btn-secondary').addClass('btn-success');
                    button.find('i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
                }

                // تحديث العداد
                updateEnabledCitiesCount();

                toastr.success(response.message);
            }
        },
        error: function() {
            toastr.error('حدث خطأ أثناء تحديث حالة المدينة');
        }
    });
});

// إزالة مدينة
$(document).on('click', '.remove-city-btn', function() {
    const cityId = $(this).data('city-id');
    const cityCard = $(this).closest('.col-md-3');

    if (confirm('هل أنت متأكد من إزالة هذه المدينة من القسم؟')) {
        $.ajax({
            url: '{{ route("admin.category-cities.remove-city", ["category" => $category->id, "city" => ":city"]) }}'.replace(':city', cityId),
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    cityCard.remove();
                    updateEnabledCitiesCount();
                    toastr.success(response.message);
                }
            },
            error: function() {
                toastr.error('حدث خطأ أثناء إزالة المدينة');
            }
        });
    }
});

function updateEnabledCitiesCount() {
    const count = $('.toggle-city-btn[data-active="true"]').length;
    $('#enabledCitiesCount').text(count);
}

// تحديث العداد عند تحميل الصفحة
$(document).ready(function() {
    updateEnabledCitiesCount();
});
</script>
@endpush
@endsection
