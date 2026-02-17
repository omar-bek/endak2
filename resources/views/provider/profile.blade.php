@extends('layouts.app')

@section('title', 'الملف الشخصي - ' . (isset($provider) ? $provider->name : 'مزود الخدمة'))

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user"></i> الملف الشخصي
                        </h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('info'))
                            <div class="alert alert-info">
                                {{ session('info') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary">المعلومات الأساسية</h6>
                                @php
                                    $displayUser = isset($provider) ? $provider : Auth::user();
                                @endphp
                                <p><strong>الاسم:</strong> {{ $displayUser->name }}</p>
                                <p><strong>البريد الإلكتروني:</strong>
                                    {{ isset($isOwner) && $isOwner ? $displayUser->email : 'مخفي' }}</p>
                                <p><strong>رقم الهاتف:</strong>
                                    {{ isset($isOwner) && $isOwner ? $profile->phone ?? 'غير محدد' : 'مخفي' }}</p>
                                <p><strong>العنوان:</strong>
                                    {{ isset($isOwner) && $isOwner ? $profile->address ?? 'غير محدد' : 'مخفي' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-primary">الإحصائيات</h6>
                                <p><strong>التقييم:</strong>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="fas fa-star {{ $i <= $profile->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                    ({{ number_format($profile->rating, 1) }})
                                </p>
                                <p><strong>الخدمات المكتملة:</strong> {{ $profile->completed_services }}</p>
                                <p><strong>الحالة:</strong>
                                    @if ($profile->is_verified)
                                        <span class="badge bg-success">موثق</span>
                                    @else
                                        <span class="badge bg-warning">في انتظار التوثيق</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <hr>

                        @if ($profile->bio)
                            <div class="mb-3">
                                <h6 class="text-primary">نبذة عني</h6>
                                <p>{{ $profile->bio }}</p>
                            </div>
                        @endif

                        @if (isset($isOwner) && $isOwner)
                            <div class="text-center">
                                <a href="{{ route('provider.profile.edit') }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> تعديل الملف الشخصي
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- الأقسام -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-folder"></i> الأقسام التي أعمل فيها
                            <small class="float-end">({{ $activeCategories->count() }}/{{ $maxCategories }})</small>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($activeCategories->count() > 0)
                            @php
                                // تجميع الأقسام حسب القسم الرئيسي
                                $groupedCategories = $activeCategories->groupBy('category_id');
                            @endphp
                            <div class="row">
                                @foreach ($groupedCategories as $categoryId => $providerCategories)
                                    @php
                                        $firstCategory = $providerCategories->first();
                                        $mainCategory =
                                            $firstCategory && $firstCategory->category
                                                ? $firstCategory->category
                                                : null;
                                        $subCategories = $providerCategories->filter(function ($item) {
                                            return $item->sub_category_id !== null && $item->subCategory !== null;
                                        });
                                        $hasSubCategories = $subCategories->count() > 0;
                                    @endphp
                                    @if ($mainCategory && $firstCategory)
                                        <div class="col-md-6 mb-3">
                                            <div class="card border-primary">
                                                <div class="card-body">
                                                    <h6 class="card-title">
                                                        <i
                                                            class="{{ isset($mainCategory->icon) ? $mainCategory->icon : 'fas fa-folder' }} text-primary"></i>
                                                        {{ isset($mainCategory->name) ? $mainCategory->name : 'قسم غير محدد' }}
                                                    </h6>

                                                    @if ($hasSubCategories)
                                                        <div class="mb-2">
                                                            <small class="text-muted d-block mb-1">الأقسام الفرعية:</small>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                @foreach ($subCategories as $subCat)
                                                                    @if ($subCat->subCategory && isset($subCat->subCategory->name_ar))
                                                                        <span class="badge bg-secondary">
                                                                            {{ $subCat->subCategory->name_ar }}
                                                                        </span>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if ($firstCategory && $firstCategory->description)
                                                        <p class="card-text small">{{ $firstCategory->description }}</p>
                                                    @endif

                                                    <div class="row">
                                                        @if ($firstCategory && $firstCategory->hourly_rate)
                                                            <div class="col-6">
                                                                <small class="text-muted">السعر بالساعة:</small>
                                                                <br><strong>{{ number_format($firstCategory->hourly_rate, 2) }}
                                                                    ريال</strong>
                                                            </div>
                                                        @endif
                                                        @if ($firstCategory && $firstCategory->experience_years)
                                                            <div class="col-6">
                                                                <small class="text-muted">سنوات الخبرة:</small>
                                                                <br><strong>{{ $firstCategory->experience_years }}
                                                                    سنوات</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center">لم يتم إضافة أي أقسام بعد</p>
                        @endif

                        @if (isset($isOwner) && $isOwner && $canAddCategory)
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#addCategoryModal">
                                    <i class="fas fa-plus"></i> إضافة قسم جديد
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marker-alt"></i> المدن التي أعمل فيها
                            <small class="float-end">({{ $activeCities->count() }}/{{ $maxCities }})</small>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($activeCities->count() > 0)
                            <div class="row">
                                @foreach ($activeCities as $providerCity)
                                    <div class="col-md-4 mb-2">
                                        <div class="badge bg-info p-2 d-flex justify-content-between align-items-center">
                                            <span>{{ $providerCity->city && isset($providerCity->city->name_ar) ? $providerCity->city->name_ar : 'غير محدد' }}</span>
                                            @if (isset($isOwner) && $isOwner)
                                                <button type="button" class="btn btn-sm btn-outline-light ms-2"
                                                    onclick="removeCity({{ $providerCity->id }})">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                        @if ($providerCity->notes)
                                            <small class="text-muted d-block">{{ $providerCity->notes }}</small>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center">لم يتم إضافة أي مدن بعد</p>
                        @endif


                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-clock"></i> ساعات العمل
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($profile->working_hours)
                            @php
                                $days = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                            @endphp

                            @foreach ($profile->working_hours as $index => $hours)
                                @if (isset($hours['enabled']) && $hours['enabled'])
                                    <div class="mb-2">
                                        <strong>{{ $days[$index] }}:</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $hours['from'] ?? '--' }} - {{ $hours['to'] ?? '--' }}
                                        </small>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <p class="text-muted text-center">لم يتم تحديد ساعات العمل</p>
                        @endif
                    </div>
                </div>

                <!-- الإحصائيات -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar"></i> الإحصائيات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-primary">{{ $activeCategories->count() }}</h4>
                                    <small class="text-muted">الأقسام</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-success">{{ $activeCities->count() }}</h4>
                                    <small class="text-muted">المدن</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-info">{{ $profile->completed_services }}</h4>
                                    <small class="text-muted">الخدمات المكتملة</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <h4 class="text-warning">{{ number_format($profile->rating, 1) }}</h4>
                                    <small class="text-muted">التقييم</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (isset($isOwner) && $isOwner)
        <!-- Modal إضافة قسم -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">إضافة قسم جديد</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="addCategoryForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">القسم الرئيسي</label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="">اختر القسم</option>
                                    @foreach (\App\Models\Category::where('is_active', true)->get() as $category)
                                        @if ($activeCategories->where('category_id', $category->id)->count() == 0)
                                            <option value="{{ $category->id }}"
                                                data-has-sub="{{ $category->subCategories()->where('status', true)->count() > 0 ? '1' : '0' }}">
                                                {{ $category->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3" id="sub_category_container" style="display: none;">
                                <label for="sub_category_id" class="form-label">القسم الفرعي (اختياري)</label>
                                <select name="sub_category_id" id="sub_category_id" class="form-select">
                                    <option value="">اختر القسم الفرعي (اختياري)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">الوصف (اختياري)</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label for="hourly_rate" class="form-label">السعر بالساعة</label>
                                    <input type="number" name="hourly_rate" id="hourly_rate" class="form-control"
                                        min="0" step="0.01">
                                </div>
                                <div class="col-6">
                                    <label for="experience_years" class="form-label">سنوات الخبرة</label>
                                    <input type="number" name="experience_years" id="experience_years"
                                        class="form-control" min="0" max="50">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-success">إضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if (isset($isOwner) && $isOwner)
        <div class="modal fade" id="addCityModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">إضافة مدينة جديدة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="addCityForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="city_name" class="form-label">اسم المدينة</label>
                                <input type="text" name="city_name" id="city_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">ملاحظات (اختياري)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-info">إضافة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if (isset($isOwner) && $isOwner)
        @push('scripts')
            <script>
                // تحديث الأقسام الفرعية عند اختيار قسم رئيسي
                document.getElementById('category_id').addEventListener('change', function() {
                    const categoryId = this.value;
                    const subCategoryContainer = document.getElementById('sub_category_container');
                    const subCategorySelect = document.getElementById('sub_category_id');
                    const selectedOption = this.options[this.selectedIndex];
                    const hasSub = selectedOption.getAttribute('data-has-sub') === '1';

                    // إعادة تعيين القسم الفرعي
                    subCategorySelect.innerHTML = '<option value="">اختر القسم الفرعي (اختياري)</option>';

                    if (hasSub && categoryId) {
                        // جلب الأقسام الفرعية
                        fetch(`/api/categories/${categoryId}/subcategories`, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data && Array.isArray(data)) {
                                    data.forEach(function(subCat) {
                                        const option = document.createElement('option');
                                        option.value = subCat.id;
                                        option.textContent = subCat.name_ar || subCat.name_en;
                                        subCategorySelect.appendChild(option);
                                    });
                                    subCategoryContainer.style.display = 'block';
                                } else {
                                    subCategoryContainer.style.display = 'none';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                subCategoryContainer.style.display = 'none';
                            });
                    } else {
                        subCategoryContainer.style.display = 'none';
                    }
                });

                // إضافة قسم جديد
                document.getElementById('addCategoryForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData);

                    // إذا لم يتم اختيار قسم فرعي، لا نرسله
                    if (!data.sub_category_id) {
                        delete data.sub_category_id;
                    }

                    fetch('{{ route('provider.categories.add') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('حدث خطأ أثناء إضافة القسم');
                        });
                });

                document.getElementById('addCityForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);

                    fetch('{{ route('provider.cities.add') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(Object.fromEntries(formData))
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('حدث خطأ أثناء إضافة المدينة');
                        });
                });

                function removeCity(cityId) {
                    if (confirm('هل أنت متأكد من حذف هذه المدينة؟')) {
                        fetch(`{{ url('provider/cities') }}/${cityId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    alert(data.error);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('حدث خطأ أثناء حذف المدينة');
                            });
                    }
                }
            </script>
        @endpush
    @endif
@endsection
