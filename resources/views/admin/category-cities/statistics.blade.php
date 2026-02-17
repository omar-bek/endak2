@extends('layouts.admin')

@section('title', 'إحصائيات الأقسام والمدن')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i>
                        إحصائيات الأقسام والمدن
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.category-cities.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- الإحصائيات العامة -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $stats['total_categories'] }}</h3>
                                    <p>إجمالي الأقسام</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $stats['active_categories'] }}</h3>
                                    <p>الأقسام المفعلة</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $stats['total_cities'] }}</h3>
                                    <p>إجمالي المدن</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-city"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $stats['active_cities'] }}</h3>
                                    <p>المدن المفعلة</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات العلاقات -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary">
                                    <i class="fas fa-link"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">إجمالي العلاقات</span>
                                    <span class="info-box-number">{{ $stats['category_city_relations'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-toggle-on"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">العلاقات المفعلة</span>
                                    <span class="info-box-number">{{ $stats['active_relations'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الأقسام الأكثر نشاطاً -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-trophy"></i>
                                        الأقسام الأكثر نشاطاً
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>اسم القسم</th>
                                                    <th>عدد المدن</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topCategories as $index => $category)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $category->name }}</td>
                                                        <td>
                                                            <span class="badge badge-info">{{ $category->city_count }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-star"></i>
                                        المدن الأكثر استخداماً
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>اسم المدينة</th>
                                                    <th>عدد الأقسام</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($topCities as $index => $city)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $city->name }}</td>
                                                        <td>
                                                            <span class="badge badge-success">{{ $city->category_count }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الرسوم البيانية -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-chart-pie"></i>
                                        توزيع الأقسام
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="categoriesChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <i class="fas fa-chart-bar"></i>
                                        توزيع المدن
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="citiesChart" width="400" height="200"></canvas>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// رسم بياني للأقسام
const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
const categoriesChart = new Chart(categoriesCtx, {
    type: 'doughnut',
    data: {
        labels: ['الأقسام المفعلة', 'الأقسام المعطلة'],
        datasets: [{
            data: [{{ $stats['active_categories'] }}, {{ $stats['total_categories'] - $stats['active_categories'] }}],
            backgroundColor: [
                '#28a745',
                '#dc3545'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// رسم بياني للمدن
const citiesCtx = document.getElementById('citiesChart').getContext('2d');
const citiesChart = new Chart(citiesCtx, {
    type: 'bar',
    data: {
        labels: ['المدن المفعلة', 'المدن المعطلة'],
        datasets: [{
            label: 'عدد المدن',
            data: [{{ $stats['active_cities'] }}, {{ $stats['total_cities'] - $stats['active_cities'] }}],
            backgroundColor: [
                '#ffc107',
                '#6c757d'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush
@endsection
