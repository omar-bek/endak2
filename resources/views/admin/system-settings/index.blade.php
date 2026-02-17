@extends('layouts.admin')

@section('title', 'إعدادات النظام')
@section('page-title', 'إعدادات النظام')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- إعدادات الموقع -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-globe"></i> إعدادات الموقع واللوجو
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.system-settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_logo" class="form-label">
                                        <i class="fas fa-image text-primary me-1"></i>لوجو الموقع
                                    </label>

                                    <!-- عرض الصورة الحالية -->
                                    @if (\App\Models\SystemSetting::get('site_logo', 'home.png'))
                                        <div class="mb-2 position-relative">
                                            <img id="current-logo"
                                                src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}"
                                                alt="اللوجو الحالي" class="img-thumbnail"
                                                style="max-height: 60px; max-width: 200px;">
                                            @if (\App\Models\SystemSetting::get('site_logo', 'home.png') !== 'home.png')
                                                <button type="button"
                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                    onclick="removeCurrentLogo()" title="حذف اللوجو الحالي">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- رفع صورة جديدة -->
                                    <input type="file" class="form-control @error('logo_upload') is-invalid @enderror"
                                        id="logo_upload" name="logo_upload" accept="image/*" onchange="previewLogo(this)">
                                    <input type="hidden" name="settings[site_logo][key]" value="site_logo">
                                    <input type="hidden" name="settings[site_logo][value]" id="site_logo"
                                        value="{{ \App\Models\SystemSetting::get('site_logo', 'home.png') }}">
                                    <small class="form-text text-muted">ارفع صورة جديدة أو اتركها فارغة للاحتفاظ بالصورة
                                        الحالية</small>
                                    @error('logo_upload')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">اسم الموقع (إنجليزي)</label>
                                    <input type="text" class="form-control @error('site_name') is-invalid @enderror"
                                        id="site_name" name="settings[site_name][value]"
                                        value="{{ \App\Models\SystemSetting::get('site_name', 'Endak') }}"
                                        placeholder="اسم الموقع بالإنجليزية">
                                    <input type="hidden" name="settings[site_name][key]" value="site_name">
                                    @error('site_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_name_ar" class="form-label">اسم الموقع (عربي)</label>
                                    <input type="text" class="form-control @error('site_name_ar') is-invalid @enderror"
                                        id="site_name_ar" name="settings[site_name_ar][value]"
                                        value="{{ \App\Models\SystemSetting::get('site_name_ar', 'إنداك') }}"
                                        placeholder="اسم الموقع بالعربية">
                                    <input type="hidden" name="settings[site_name_ar][key]" value="site_name_ar">
                                    @error('site_name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">معاينة اللوجو</label>
                                    <div class="text-center">
                                        <img id="logo-preview"
                                            src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}"
                                            alt="معاينة اللوجو" class="img-fluid"
                                            style="max-height: 60px; max-width: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ إعدادات الموقع
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- إعدادات التواصل -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fab fa-whatsapp"></i> إعدادات التواصل والواتساب
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.system-settings.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="whatsapp_number" class="form-label">
                                        <i class="fab fa-whatsapp text-success me-1"></i>رقم الواتساب *
                                    </label>
                                    <input type="tel"
                                        class="form-control @error('whatsapp_number') is-invalid @enderror"
                                        id="whatsapp_number" name="settings[whatsapp_number][value]"
                                        value="{{ \App\Models\SystemSetting::get('whatsapp_number', '+966501234567') }}"
                                        placeholder="مثال: +966501234567">
                                    <input type="hidden" name="settings[whatsapp_number][key]" value="whatsapp_number">
                                    <small class="form-text text-muted">أدخل رقم الواتساب مع رمز الدولة</small>
                                    @error('whatsapp_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="whatsapp_message" class="form-label">الرسالة الافتراضية</label>
                                    <input type="text"
                                        class="form-control @error('whatsapp_message') is-invalid @enderror"
                                        id="whatsapp_message" name="settings[whatsapp_message][value]"
                                        value="{{ \App\Models\SystemSetting::get('whatsapp_message', 'مرحباً، أريد الاستفسار عن خدمة') }}"
                                        placeholder="الرسالة التي تظهر عند فتح الواتساب">
                                    <input type="hidden" name="settings[whatsapp_message][key]"
                                        value="whatsapp_message">
                                    <small class="form-text text-muted">الرسالة التي تظهر تلقائياً في الواتساب</small>
                                    @error('whatsapp_message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="whatsapp_enabled"
                                            name="settings[whatsapp_enabled][value]" value="1"
                                            {{ \App\Models\SystemSetting::get('whatsapp_enabled', true) ? 'checked' : '' }}>
                                        <input type="hidden" name="settings[whatsapp_enabled][key]"
                                            value="whatsapp_enabled">
                                        <label class="form-check-label" for="whatsapp_enabled">
                                            <i class="fab fa-whatsapp text-success me-1"></i>تفعيل رابط الواتساب
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">إظهار رابط الواتساب في الموقع</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">معاينة رابط الواتساب</label>
                                    <div class="alert alert-info">
                                        <strong>الرابط:</strong><br>
                                        <code id="whatsapp-preview" class="text-break"></code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> حفظ إعدادات الواتساب
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- إعدادات مزود الخدمة -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-cog"></i> إعدادات مزود الخدمة
                    </h5>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.system-settings.provider') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="provider_max_categories" class="form-label">الحد الأقصى للأقسام</label>
                                    <input type="number" name="provider_max_categories" id="provider_max_categories"
                                        class="form-control" min="1" max="10"
                                        value="{{ \App\Models\SystemSetting::get('provider_max_categories', 3) }}"
                                        required>
                                    <small class="text-muted">عدد الأقسام التي يمكن لمزود الخدمة العمل فيها</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="provider_max_cities" class="form-label">الحد الأقصى للمدن</label>
                                    <input type="number" name="provider_max_cities" id="provider_max_cities"
                                        class="form-control" min="1" max="20"
                                        value="{{ \App\Models\SystemSetting::get('provider_max_cities', 5) }}" required>
                                    <small class="text-muted">عدد المدن التي يمكن لمزود الخدمة العمل فيها</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="provider_verification_required"
                                            id="provider_verification_required" class="form-check-input" value="1"
                                            {{ \App\Models\SystemSetting::get('provider_verification_required', false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="provider_verification_required">
                                            يتطلب التحقق من مزود الخدمة
                                        </label>
                                    </div>
                                    <small class="text-muted">هل يتطلب التحقق من مزود الخدمة قبل تفعيل الحساب</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="provider_auto_approve" id="provider_auto_approve"
                                            class="form-check-input" value="1"
                                            {{ \App\Models\SystemSetting::get('provider_auto_approve', false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="provider_auto_approve">
                                            الموافقة التلقائية على مزودي الخدمة
                                        </label>
                                    </div>
                                    <small class="text-muted">هل يتم الموافقة التلقائية على مزودي الخدمة</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ الإعدادات
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- إعدادات الصورة الافتراضية للخدمات -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-image"></i> الصورة الافتراضية للخدمات
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.system-settings.default-service-image') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="default_service_image" class="form-label">صورة افتراضية جديدة</label>
                                    <input type="file" name="default_service_image" id="default_service_image"
                                        class="form-control" accept="image/*">
                                    <small class="text-muted">اختر صورة جديدة للخدمات (JPG, PNG, GIF - الحد الأقصى
                                        2MB)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="default_service_image_enabled"
                                            id="default_service_image_enabled" class="form-check-input" value="1"
                                            {{ \App\Models\SystemSetting::isDefaultServiceImageEnabled() ? 'checked' : '' }}>
                                        <label class="form-check-label" for="default_service_image_enabled">
                                            تفعيل الصورة الافتراضية
                                        </label>
                                    </div>
                                    <small class="text-muted">عرض الصورة الافتراضية للخدمات التي لا تحتوي على صورة</small>
                                </div>
                            </div>
                        </div>

                        @php
                            $currentImage = \App\Models\SystemSetting::get(
                                'default_service_image',
                                'services/default-service.jpg',
                            );
                            $currentImageUrl = asset('storage/' . $currentImage);
                        @endphp

                        @if ($currentImage && \Illuminate\Support\Facades\Storage::disk('public')->exists($currentImage))
                            <div class="mb-3">
                                <label class="form-label">الصورة الحالية:</label>
                                <div class="current-image-container">
                                    <img src="{{ $currentImageUrl }}" alt="الصورة الافتراضية الحالية"
                                        class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="removeCurrentImage()">
                                            <i class="fas fa-trash"></i> حذف الصورة الحالية
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ إعدادات الصورة
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- جميع الإعدادات -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs"></i> جميع إعدادات النظام
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.system-settings.update') }}">
                        @csrf
                        @method('PUT')

                        @foreach ($settings as $group => $groupSettings)
                            <div class="mb-4">
                                <h6 class="text-primary">{{ ucfirst($group) }}</h6>
                                <div class="row">
                                    @foreach ($groupSettings as $setting)
                                        <div class="col-md-6 mb-3">
                                            <label for="setting_{{ $setting->id }}" class="form-label">
                                                {{ $setting->description ?? $setting->key }}
                                            </label>

                                            @if ($setting->type === 'boolean')
                                                <div class="form-check">
                                                    <input type="checkbox" name="settings[{{ $setting->key }}][value]"
                                                        id="setting_{{ $setting->id }}" class="form-check-input"
                                                        value="1"
                                                        {{ filter_var($setting->value, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="setting_{{ $setting->id }}">
                                                        تفعيل
                                                    </label>
                                                </div>
                                            @elseif($setting->type === 'integer')
                                                <input type="number" name="settings[{{ $setting->key }}][value]"
                                                    id="setting_{{ $setting->id }}" class="form-control"
                                                    value="{{ $setting->value }}">
                                            @elseif($setting->type === 'json')
                                                <textarea name="settings[{{ $setting->key }}][value]" id="setting_{{ $setting->id }}" class="form-control"
                                                    rows="3">{{ $setting->value }}</textarea>
                                            @else
                                                <input type="text" name="settings[{{ $setting->key }}][value]"
                                                    id="setting_{{ $setting->id }}" class="form-control"
                                                    value="{{ $setting->value }}">
                                            @endif

                                            <input type="hidden" name="settings[{{ $setting->key }}][key]"
                                                value="{{ $setting->key }}">
                                            <small class="text-muted">{{ $setting->description }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> حفظ جميع الإعدادات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- معلومات النظام -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> معلومات النظام
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>إصدار Laravel:</strong>
                        <span class="text-muted">{{ app()->version() }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>بيئة التشغيل:</strong>
                        <span class="badge bg-{{ app()->environment() === 'production' ? 'danger' : 'warning' }}">
                            {{ app()->environment() }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>اللغة الافتراضية:</strong>
                        <span class="text-muted">{{ config('app.locale') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>المنطقة الزمنية:</strong>
                        <span class="text-muted">{{ config('app.timezone') }}</span>
                    </div>
                </div>
            </div>

            <!-- إحصائيات مزودي الخدمة -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar"></i> إحصائيات مزودي الخدمة
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $totalProviders = \App\Models\User::where('user_type', 'provider')->count();
                        $verifiedProviders = \App\Models\ProviderProfile::where('is_verified', true)->count();
                        $activeProviders = \App\Models\ProviderProfile::where('is_active', true)->count();
                        $completeProfiles = \App\Models\ProviderProfile::whereNotNull('bio')
                            ->whereNotNull('phone')
                            ->whereNotNull('address')
                            ->count();
                    @endphp

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>إجمالي مزودي الخدمة:</span>
                            <strong>{{ $totalProviders }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>الموثقون:</span>
                            <strong>{{ $verifiedProviders }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>النشطون:</span>
                            <strong>{{ $activeProviders }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>الملفات المكتملة:</span>
                            <strong>{{ $completeProfiles }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function removeCurrentImage() {
            if (confirm('هل أنت متأكد من حذف الصورة الحالية؟')) {
                // إضافة حقل مخفي لحذف الصورة
                const form = document.querySelector('form[action*="default-service-image"]');
                const removeInput = document.createElement('input');
                removeInput.type = 'hidden';
                removeInput.name = 'remove_image';
                removeInput.value = '1';
                form.appendChild(removeInput);

                // إرسال النموذج
                form.submit();
            }
        }

        // معاينة رابط الواتساب
        function updateWhatsAppPreview() {
            const number = document.getElementById('whatsapp_number').value;
            const message = document.getElementById('whatsapp_message').value;

            if (number && message) {
                const encodedMessage = encodeURIComponent(message);
                const whatsappUrl = `https://wa.me/${number.replace(/[^0-9]/g, '')}?text=${encodedMessage}`;
                document.getElementById('whatsapp-preview').textContent = whatsappUrl;
            } else {
                document.getElementById('whatsapp-preview').textContent = 'أدخل رقم الواتساب والرسالة لرؤية المعاينة';
            }
        }

        // معاينة اللوجو
        function updateLogoPreview() {
            const logoInput = document.getElementById('site_logo');
            const logoPreview = document.getElementById('logo-preview');

            if (logoInput && logoPreview) {
                const logoValue = logoInput.value || 'home.png';
                logoPreview.src = `{{ asset('') }}${logoValue}`;
            }
        }

        // معاينة الصورة المرفوعة
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    // تحديث معاينة الصورة الحالية
                    const currentLogo = document.getElementById('current-logo');
                    if (currentLogo) {
                        currentLogo.src = e.target.result;
                    }

                    // تحديث معاينة الصورة في الجانب الآخر
                    const logoPreview = document.getElementById('logo-preview');
                    if (logoPreview) {
                        logoPreview.src = e.target.result;
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // حذف اللوجو الحالي
        function removeCurrentLogo() {
            if (confirm('هل أنت متأكد من حذف اللوجو الحالي؟')) {
                // إضافة حقل مخفي لحذف اللوجو
                const form = document.querySelector('form[action*="system-settings"]');
                const removeInput = document.createElement('input');
                removeInput.type = 'hidden';
                removeInput.name = 'remove_logo';
                removeInput.value = '1';
                form.appendChild(removeInput);

                // إرسال النموذج
                form.submit();
            }
        }

        // تحديث المعاينة عند تغيير القيم
        document.addEventListener('DOMContentLoaded', function() {
            const whatsappNumber = document.getElementById('whatsapp_number');
            const whatsappMessage = document.getElementById('whatsapp_message');
            const siteLogo = document.getElementById('site_logo');

            if (whatsappNumber && whatsappMessage) {
                // تحديث المعاينة عند التحميل
                updateWhatsAppPreview();

                // تحديث المعاينة عند تغيير القيم
                whatsappNumber.addEventListener('input', updateWhatsAppPreview);
                whatsappMessage.addEventListener('input', updateWhatsAppPreview);
            }

            if (siteLogo) {
                // تحديث معاينة اللوجو عند التحميل
                updateLogoPreview();

                // تحديث معاينة اللوجو عند تغيير القيمة
                siteLogo.addEventListener('input', updateLogoPreview);
            }
        });
    </script>
@endsection
