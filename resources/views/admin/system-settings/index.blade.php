@extends('layouts.admin')

@section('title', 'إعدادات النظام')
@section('page-title', 'إعدادات النظام')

@section('content')
    {{-- Toast --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show ss-toast" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <style>
        /* ===== Settings Page ===== */
        .ss-tabs { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .ss-tab {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.6rem 1.2rem; border-radius: 50px;
            border: 1.5px solid var(--e-gray-200); background: var(--e-white);
            color: var(--e-gray-600); font-size: 0.85rem; font-weight: 600;
            cursor: pointer; transition: all 0.25s ease; text-decoration: none;
        }
        .ss-tab:hover { border-color: var(--e-primary); color: var(--e-primary); background: var(--e-primary-50); }
        .ss-tab.active { background: var(--e-primary); color: #fff; border-color: var(--e-primary); box-shadow: 0 3px 10px rgba(47,92,105,0.25); }
        .ss-tab i { font-size: 0.9rem; }
        .ss-panel { display: none; animation: ssFadeIn 0.3s ease; }
        .ss-panel.active { display: block; }
        @keyframes ssFadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        .ss-card {
            background: var(--e-white); border-radius: var(--e-radius-lg);
            border: 1px solid var(--e-gray-100); box-shadow: var(--e-shadow-sm);
            overflow: hidden; margin-bottom: 1.25rem;
        }
        .ss-card-header {
            padding: 1rem 1.25rem; border-bottom: 1px solid var(--e-gray-100);
            display: flex; align-items: center; gap: 0.6rem;
        }
        .ss-card-header-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem; flex-shrink: 0;
        }
        .ss-card-header h6 { font-size: 0.95rem; font-weight: 700; color: var(--e-gray-800); margin: 0; }
        .ss-card-header small { font-size: 0.75rem; color: var(--e-gray-400); font-weight: 400; }
        .ss-card-body { padding: 1.25rem; }
        .ss-label { font-size: 0.82rem; font-weight: 600; color: var(--e-gray-700); margin-bottom: 0.35rem; }
        .ss-hint { font-size: 0.74rem; color: var(--e-gray-400); margin-top: 0.25rem; }
        .ss-input { border: 1.5px solid var(--e-gray-200); border-radius: var(--e-radius); padding: 0.6rem 0.9rem; font-size: 0.88rem; transition: all 0.2s ease; }
        .ss-input:focus { border-color: var(--e-primary); box-shadow: 0 0 0 3px var(--e-primary-100); }
        .ss-save-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.6rem 1.5rem; border-radius: 50px; font-weight: 600; font-size: 0.85rem;
            border: none; cursor: pointer; transition: all 0.25s ease;
        }
        .ss-save-btn:hover { transform: translateY(-1px); }
        .ss-save-btn--primary { background: var(--e-primary); color: #fff; box-shadow: var(--e-shadow-primary); }
        .ss-save-btn--primary:hover { background: var(--e-primary-dark); }
        .ss-save-btn--success { background: var(--e-success); color: #fff; box-shadow: 0 3px 10px rgba(16,185,129,0.25); }
        .ss-save-btn--success:hover { background: #0d9668; }
        .ss-divider { border: none; border-top: 1px solid var(--e-gray-100); margin: 1rem 0; }

        /* Toggle Switch */
        .ss-toggle { position: relative; display: inline-flex; align-items: center; gap: 0.6rem; cursor: pointer; }
        .ss-toggle input { position: absolute; opacity: 0; width: 0; height: 0; }
        .ss-toggle-track {
            width: 44px; height: 24px; border-radius: 12px;
            background: var(--e-gray-300); transition: background 0.2s ease;
            position: relative; flex-shrink: 0;
        }
        .ss-toggle-track::after {
            content: ''; position: absolute; top: 3px; right: 3px;
            width: 18px; height: 18px; border-radius: 50%;
            background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.15);
            transition: transform 0.2s ease;
        }
        .ss-toggle input:checked + .ss-toggle-track { background: var(--e-primary); }
        .ss-toggle input:checked + .ss-toggle-track::after { transform: translateX(-20px); }
        .ss-toggle-label { font-size: 0.85rem; font-weight: 500; color: var(--e-gray-700); }

        /* Google Preview */
        .ss-google-preview {
            background: #fff; border: 1px solid var(--e-gray-200); border-radius: var(--e-radius);
            padding: 1rem; max-width: 580px;
        }
        .ss-google-preview__title { color: #1a0dab; font-size: 1.1rem; line-height: 1.3; margin-bottom: 2px; font-weight: 400; }
        .ss-google-preview__url { color: #006621; font-size: 0.82rem; margin-bottom: 2px; }
        .ss-google-preview__desc { color: #545454; font-size: 0.82rem; line-height: 1.5; }

        /* Store Buttons Preview */
        .ss-store-btn-preview { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #000; color: #fff; border-radius: 8px; font-size: 0.75rem; text-decoration: none; }
        .ss-store-btn-preview i { font-size: 1.2rem; }
        .ss-store-btn-preview small { display: block; font-size: 0.6rem; opacity: 0.7; line-height: 1; }
        .ss-store-btn-preview span { font-weight: 600; font-size: 0.85rem; line-height: 1; }

        /* Info Sidebar */
        .ss-info-item { display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0; border-bottom: 1px solid var(--e-gray-100); }
        .ss-info-item:last-child { border-bottom: none; }
        .ss-info-item span:first-child { font-size: 0.82rem; color: var(--e-gray-500); }
        .ss-info-item span:last-child { font-size: 0.82rem; font-weight: 600; color: var(--e-gray-800); }

        .ss-toast { position: sticky; top: 0; z-index: 50; }

        @media (max-width: 768px) {
            .ss-tabs { gap: 0.35rem; }
            .ss-tab { padding: 0.5rem 0.85rem; font-size: 0.78rem; }
        }
    </style>

    {{-- Tabs Navigation --}}
    <div class="ss-tabs">
        <a class="ss-tab active" data-tab="site" href="#"><i class="fas fa-globe"></i> الموقع</a>
        <a class="ss-tab" data-tab="seo" href="#"><i class="fas fa-search"></i> SEO</a>
        <a class="ss-tab" data-tab="whatsapp" href="#"><i class="fab fa-whatsapp"></i> واتساب</a>
        <a class="ss-tab" data-tab="apps" href="#"><i class="fas fa-mobile-alt"></i> التطبيقات</a>
        <a class="ss-tab" data-tab="provider" href="#"><i class="fas fa-user-cog"></i> المزود</a>
        <a class="ss-tab" data-tab="image" href="#"><i class="fas fa-image"></i> صورة افتراضية</a>
        <a class="ss-tab" data-tab="all" href="#"><i class="fas fa-cogs"></i> الكل</a>
    </div>

    <div class="row">
        <div class="col-lg-8">

            {{-- ==================== 1. Site Settings ==================== --}}
            <div class="ss-panel active" id="panel-site">
                <div class="ss-card">
                    <div class="ss-card-header">
                        <div class="ss-card-header-icon" style="background: var(--e-primary-50); color: var(--e-primary);"><i class="fas fa-globe"></i></div>
                        <div><h6>إعدادات الموقع واللوجو</h6><small>اسم الموقع والهوية البصرية</small></div>
                    </div>
                    <div class="ss-card-body">
                        <form method="POST" action="{{ route('admin.system-settings.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="ss-label">لوجو الموقع</label>
                                    @if (\App\Models\SystemSetting::get('site_logo', 'home.png'))
                                        <div class="mb-2 d-flex align-items-center gap-2">
                                            <img id="current-logo" src="{{ media_site_logo_url(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}" alt="اللوجو" class="img-thumbnail" style="height: 50px; width: auto;">
                                            @if (\App\Models\SystemSetting::get('site_logo', 'home.png') !== 'home.png')
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCurrentLogo()" title="حذف"><i class="fas fa-trash-alt"></i></button>
                                            @endif
                                        </div>
                                    @endif
                                    <input type="file" class="form-control ss-input" name="logo_upload" accept="image/*" onchange="previewLogo(this)">
                                    <input type="hidden" name="settings[site_logo][key]" value="site_logo">
                                    <input type="hidden" name="settings[site_logo][value]" id="site_logo" value="{{ \App\Models\SystemSetting::get('site_logo', 'home.png') }}">
                                    <div class="ss-hint">PNG, JPG, SVG - الحد 2MB</div>
                                </div>
                                <div class="col-md-6 d-flex align-items-center justify-content-center">
                                    <img id="logo-preview" src="{{ media_site_logo_url(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}" alt="معاينة" style="max-height: 60px; max-width: 180px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="ss-label">اسم الموقع (إنجليزي)</label>
                                    <input type="text" class="form-control ss-input" name="settings[site_name][value]" value="{{ \App\Models\SystemSetting::get('site_name', 'Endak') }}" placeholder="Endak">
                                    <input type="hidden" name="settings[site_name][key]" value="site_name">
                                </div>
                                <div class="col-md-6">
                                    <label class="ss-label">اسم الموقع (عربي)</label>
                                    <input type="text" class="form-control ss-input" name="settings[site_name_ar][value]" value="{{ \App\Models\SystemSetting::get('site_name_ar', 'إنداك') }}" placeholder="إنداك">
                                    <input type="hidden" name="settings[site_name_ar][key]" value="site_name_ar">
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="ss-save-btn ss-save-btn--primary"><i class="fas fa-save"></i> حفظ إعدادات الموقع</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ==================== 2. SEO ==================== --}}
            <div class="ss-panel" id="panel-seo">
                <div class="ss-card">
                    <div class="ss-card-header">
                        <div class="ss-card-header-icon" style="background: rgba(16,185,129,0.1); color: var(--e-success);"><i class="fas fa-search"></i></div>
                        <div><h6>إعدادات SEO ومحركات البحث</h6><small>تحسين ظهور الموقع في نتائج البحث</small></div>
                    </div>
                    <div class="ss-card-body">
                        <form method="POST" action="{{ route('admin.system-settings.update-seo') }}">
                            @csrf
                            @method('PUT')

                            {{-- Description --}}
                            <div class="row g-3 mb-3">
                                <div class="col-12"><label class="ss-label"><i class="fas fa-file-alt text-primary me-1"></i> وصف الموقع (Meta Description)</label><div class="ss-hint mb-2">يظهر في نتائج بحث جوجل. الحد الموصى به 160 حرف.</div></div>
                                <div class="col-md-6">
                                    <label class="ss-label">عربي</label>
                                    <textarea class="form-control ss-input" id="site_description_ar" name="site_description_ar" rows="3" maxlength="300" placeholder="إنداك - المنصة الرائدة للخدمات...">{{ \App\Models\SystemSetting::get('site_description_ar', '') }}</textarea>
                                    <div class="ss-hint"><span id="desc_ar_count">0</span>/160 حرف</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="ss-label">English</label>
                                    <textarea class="form-control ss-input" id="site_description_en" name="site_description_en" rows="3" maxlength="300" placeholder="Endak - The leading services platform...">{{ \App\Models\SystemSetting::get('site_description_en', '') }}</textarea>
                                    <div class="ss-hint"><span id="desc_en_count">0</span>/160 chars</div>
                                </div>
                            </div>

                            <hr class="ss-divider">

                            {{-- Keywords --}}
                            <div class="row g-3 mb-3">
                                <div class="col-12"><label class="ss-label"><i class="fas fa-tags text-warning me-1"></i> الكلمات المفتاحية</label><div class="ss-hint mb-2">كلمات مفصولة بفواصل</div></div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control ss-input" name="site_keywords_ar" value="{{ \App\Models\SystemSetting::get('site_keywords_ar', '') }}" placeholder="خدمات, إنداك, طلب خدمة">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control ss-input" name="site_keywords_en" value="{{ \App\Models\SystemSetting::get('site_keywords_en', '') }}" placeholder="services, Endak, request">
                                </div>
                            </div>

                            <hr class="ss-divider">

                            {{-- Social Media --}}
                            <div class="mb-3">
                                <label class="ss-label"><i class="fas fa-share-alt text-info me-1"></i> روابط السوشيال ميديا</label>
                                <div class="ss-hint mb-2">تظهر في الفوتر وفي بيانات JSON-LD لمحركات البحث</div>
                            </div>
                            <div class="row g-3 mb-3">
                                @php
                                    $socials = [
                                        ['key' => 'social_facebook', 'icon' => 'fab fa-facebook', 'color' => '#1877f2', 'label' => 'فيسبوك', 'ph' => 'https://facebook.com/endak'],
                                        ['key' => 'social_twitter', 'icon' => 'fab fa-x-twitter', 'color' => '#000', 'label' => 'تويتر / X', 'ph' => 'https://x.com/endak'],
                                        ['key' => 'social_instagram', 'icon' => 'fab fa-instagram', 'color' => '#e1306c', 'label' => 'إنستغرام', 'ph' => 'https://instagram.com/endak'],
                                        ['key' => 'social_tiktok', 'icon' => 'fab fa-tiktok', 'color' => '#000', 'label' => 'تيك توك', 'ph' => 'https://tiktok.com/@endak'],
                                        ['key' => 'social_youtube', 'icon' => 'fab fa-youtube', 'color' => '#ff0000', 'label' => 'يوتيوب', 'ph' => 'https://youtube.com/@endak'],
                                    ];
                                @endphp
                                @foreach($socials as $s)
                                    <div class="col-md-6">
                                        <label class="ss-label"><i class="{{ $s['icon'] }}" style="color:{{ $s['color'] }}"></i> {{ $s['label'] }}</label>
                                        <input type="url" class="form-control ss-input" name="{{ $s['key'] }}" value="{{ \App\Models\SystemSetting::get($s['key'], '') }}" placeholder="{{ $s['ph'] }}">
                                    </div>
                                @endforeach
                            </div>

                            <hr class="ss-divider">

                            {{-- Google Preview --}}
                            <div class="mb-3">
                                <label class="ss-label"><i class="fab fa-google text-success me-1"></i> معاينة نتائج البحث</label>
                            </div>
                            <div class="ss-google-preview">
                                <div class="ss-google-preview__title" id="seo_preview_title">{{ \App\Models\SystemSetting::get('site_name_ar', 'إنداك') }} - {{ \App\Models\SystemSetting::get('site_name', 'Endak') }}</div>
                                <div class="ss-google-preview__url">{{ url('/') }}</div>
                                <div class="ss-google-preview__desc" id="seo_preview_desc">{{ \App\Models\SystemSetting::get('site_description_ar', 'إنداك - المنصة الرائدة للخدمات...') }}</div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="ss-save-btn ss-save-btn--success"><i class="fas fa-save"></i> حفظ إعدادات SEO</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ==================== 3. WhatsApp ==================== --}}
            <div class="ss-panel" id="panel-whatsapp">
                <div class="ss-card">
                    <div class="ss-card-header">
                        <div class="ss-card-header-icon" style="background: rgba(37,211,102,0.1); color: #25d366;"><i class="fab fa-whatsapp"></i></div>
                        <div><h6>إعدادات الواتساب</h6><small>رقم التواصل والرسالة الافتراضية</small></div>
                    </div>
                    <div class="ss-card-body">
                        <form method="POST" action="{{ route('admin.system-settings.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="ss-label">رقم الواتساب</label>
                                    <input type="tel" class="form-control ss-input" id="whatsapp_number" name="settings[whatsapp_number][value]" value="{{ \App\Models\SystemSetting::get('whatsapp_number', '+966501234567') }}" placeholder="+966501234567">
                                    <input type="hidden" name="settings[whatsapp_number][key]" value="whatsapp_number">
                                    <div class="ss-hint">مع رمز الدولة</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="ss-label">الرسالة الافتراضية</label>
                                    <input type="text" class="form-control ss-input" id="whatsapp_message" name="settings[whatsapp_message][value]" value="{{ \App\Models\SystemSetting::get('whatsapp_message', 'مرحباً، أريد الاستفسار عن خدمة') }}">
                                    <input type="hidden" name="settings[whatsapp_message][key]" value="whatsapp_message">
                                </div>
                                <div class="col-md-6">
                                    <label class="ss-toggle">
                                        <input type="checkbox" name="settings[whatsapp_enabled][value]" value="1" {{ \App\Models\SystemSetting::get('whatsapp_enabled', true) ? 'checked' : '' }}>
                                        <span class="ss-toggle-track"></span>
                                        <span class="ss-toggle-label">تفعيل رابط الواتساب</span>
                                    </label>
                                    <input type="hidden" name="settings[whatsapp_enabled][key]" value="whatsapp_enabled">
                                </div>
                                <div class="col-md-6">
                                    <label class="ss-label">المعاينة</label>
                                    <div class="p-2 bg-light rounded"><code id="whatsapp-preview" class="text-break" style="font-size:0.75rem;"></code></div>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="ss-save-btn ss-save-btn--success"><i class="fas fa-save"></i> حفظ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ==================== 4. App Links ==================== --}}
            <div class="ss-panel" id="panel-apps">
                <div class="ss-card">
                    <div class="ss-card-header">
                        <div class="ss-card-header-icon" style="background: rgba(99,102,241,0.1); color: #6366f1;"><i class="fas fa-mobile-alt"></i></div>
                        <div><h6>روابط تنزيل التطبيقات</h6><small>Google Play و App Store</small></div>
                    </div>
                    <div class="ss-card-body">
                        <form method="POST" action="{{ route('admin.system-settings.update-app-links') }}">
                            @csrf
                            @method('PUT')

                            {{-- Provider App --}}
                            <div class="p-3 border rounded-3 mb-3" style="border-color: var(--e-gray-200) !important;">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="fw-bold" style="font-size:0.9rem;"><i class="fas fa-user-tie text-primary me-1"></i> تطبيق مزود الخدمة</span>
                                    <label class="ss-toggle">
                                        <input type="checkbox" name="provider_app_enabled" value="1" {{ \App\Models\SystemSetting::get('provider_app_enabled', false) ? 'checked' : '' }}>
                                        <span class="ss-toggle-track"></span>
                                    </label>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="ss-label"><i class="fab fa-google-play text-success me-1"></i> Google Play</label>
                                        <input type="url" class="form-control ss-input" name="provider_app_google_play" value="{{ \App\Models\SystemSetting::get('provider_app_google_play', '') }}" placeholder="https://play.google.com/store/apps/...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="ss-label"><i class="fab fa-apple me-1"></i> App Store</label>
                                        <input type="url" class="form-control ss-input" name="provider_app_appstore" value="{{ \App\Models\SystemSetting::get('provider_app_appstore', '') }}" placeholder="https://apps.apple.com/app/...">
                                    </div>
                                </div>
                            </div>

                            {{-- Client App --}}
                            <div class="p-3 border rounded-3 mb-3" style="border-color: var(--e-gray-200) !important;">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="fw-bold" style="font-size:0.9rem;"><i class="fas fa-user text-success me-1"></i> تطبيق العميل</span>
                                    <label class="ss-toggle">
                                        <input type="checkbox" name="client_app_enabled" value="1" {{ \App\Models\SystemSetting::get('client_app_enabled', false) ? 'checked' : '' }}>
                                        <span class="ss-toggle-track"></span>
                                    </label>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="ss-label"><i class="fab fa-google-play text-success me-1"></i> Google Play</label>
                                        <input type="url" class="form-control ss-input" name="client_app_google_play" value="{{ \App\Models\SystemSetting::get('client_app_google_play', '') }}" placeholder="https://play.google.com/store/apps/...">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="ss-label"><i class="fab fa-apple me-1"></i> App Store</label>
                                        <input type="url" class="form-control ss-input" name="client_app_appstore" value="{{ \App\Models\SystemSetting::get('client_app_appstore', '') }}" placeholder="https://apps.apple.com/app/...">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="ss-save-btn ss-save-btn--primary"><i class="fas fa-save"></i> حفظ روابط التطبيقات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ==================== 5. Provider Settings ==================== --}}
            <div class="ss-panel" id="panel-provider">
                <div class="ss-card">
                    <div class="ss-card-header">
                        <div class="ss-card-header-icon" style="background: var(--e-accent-50); color: var(--e-accent);"><i class="fas fa-user-cog"></i></div>
                        <div><h6>إعدادات مزود الخدمة</h6><small>حدود الأقسام والمدن والتفعيل</small></div>
                    </div>
                    <div class="ss-card-body">
                        <form method="POST" action="{{ route('admin.system-settings.provider') }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="ss-label">الحد الأقصى للأقسام</label>
                                    <input type="number" class="form-control ss-input" name="provider_max_categories" min="1" max="10" value="{{ \App\Models\SystemSetting::get('provider_max_categories', 3) }}" required>
                                    <div class="ss-hint">عدد الأقسام لكل مزود</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="ss-label">الحد الأقصى للمدن</label>
                                    <input type="number" class="form-control ss-input" name="provider_max_cities" min="1" max="20" value="{{ \App\Models\SystemSetting::get('provider_max_cities', 5) }}" required>
                                    <div class="ss-hint">عدد المدن لكل مزود</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="ss-toggle">
                                        <input type="checkbox" name="provider_verification_required" value="1" {{ \App\Models\SystemSetting::get('provider_verification_required', false) ? 'checked' : '' }}>
                                        <span class="ss-toggle-track"></span>
                                        <span class="ss-toggle-label">يتطلب التحقق من المزود</span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="ss-toggle">
                                        <input type="checkbox" name="provider_auto_approve" value="1" {{ \App\Models\SystemSetting::get('provider_auto_approve', false) ? 'checked' : '' }}>
                                        <span class="ss-toggle-track"></span>
                                        <span class="ss-toggle-label">الموافقة التلقائية</span>
                                    </label>
                                </div>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="ss-save-btn ss-save-btn--primary"><i class="fas fa-save"></i> حفظ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ==================== 6. Default Service Image ==================== --}}
            <div class="ss-panel" id="panel-image">
                <div class="ss-card">
                    <div class="ss-card-header">
                        <div class="ss-card-header-icon" style="background: rgba(59,130,246,0.1); color: var(--e-info);"><i class="fas fa-image"></i></div>
                        <div><h6>الصورة الافتراضية للخدمات</h6><small>تظهر عندما لا تحتوي الخدمة على صورة</small></div>
                    </div>
                    <div class="ss-card-body">
                        <form method="POST" action="{{ route('admin.system-settings.default-service-image') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="ss-label">رفع صورة جديدة</label>
                                    <input type="file" class="form-control ss-input" name="default_service_image" accept="image/*">
                                    <div class="ss-hint">JPG, PNG, GIF - الحد 2MB</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="ss-toggle">
                                        <input type="checkbox" name="default_service_image_enabled" value="1" {{ \App\Models\SystemSetting::isDefaultServiceImageEnabled() ? 'checked' : '' }}>
                                        <span class="ss-toggle-track"></span>
                                        <span class="ss-toggle-label">تفعيل الصورة الافتراضية</span>
                                    </label>
                                </div>
                            </div>
                            @php
                                $currentImage = \App\Models\SystemSetting::get('default_service_image', 'services/default-service.jpg');
                            @endphp
                            @if ($currentImage && \Illuminate\Support\Facades\Storage::disk('public')->exists($currentImage))
                                <div class="mt-3 d-flex align-items-end gap-3">
                                    <div>
                                        <label class="ss-label">الصورة الحالية</label>
                                        <img src="{{ media_resolve_url($currentImage) }}" alt="الصورة الافتراضية" class="img-thumbnail" style="max-width: 180px; max-height: 120px;">
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeCurrentImage()"><i class="fas fa-trash-alt"></i> حذف</button>
                                </div>
                            @endif
                            <div class="text-center mt-4">
                                <button type="submit" class="ss-save-btn ss-save-btn--primary"><i class="fas fa-save"></i> حفظ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ==================== 7. All Settings ==================== --}}
            <div class="ss-panel" id="panel-all">
                <div class="ss-card">
                    <div class="ss-card-header">
                        <div class="ss-card-header-icon" style="background: var(--e-gray-100); color: var(--e-gray-600);"><i class="fas fa-cogs"></i></div>
                        <div><h6>جميع إعدادات النظام</h6><small>عرض وتعديل كل الإعدادات</small></div>
                    </div>
                    <div class="ss-card-body">
                        <form method="POST" action="{{ route('admin.system-settings.update') }}">
                            @csrf
                            @method('PUT')
                            @foreach ($settings as $group => $groupSettings)
                                <div class="mb-4">
                                    <h6 class="text-primary mb-2" style="font-size:0.85rem;"><i class="fas fa-folder-open me-1"></i> {{ ucfirst($group) }}</h6>
                                    <div class="row g-3">
                                        @foreach ($groupSettings as $setting)
                                            <div class="col-md-6">
                                                <label class="ss-label">{{ $setting->description ?? $setting->key }}</label>
                                                @if ($setting->type === 'boolean')
                                                    <label class="ss-toggle">
                                                        <input type="checkbox" name="settings[{{ $setting->key }}][value]" value="1" {{ filter_var($setting->value, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>
                                                        <span class="ss-toggle-track"></span>
                                                        <span class="ss-toggle-label">تفعيل</span>
                                                    </label>
                                                @elseif($setting->type === 'integer')
                                                    <input type="number" class="form-control ss-input" name="settings[{{ $setting->key }}][value]" value="{{ $setting->value }}">
                                                @elseif($setting->type === 'json')
                                                    <textarea class="form-control ss-input" name="settings[{{ $setting->key }}][value]" rows="2">{{ $setting->value }}</textarea>
                                                @else
                                                    <input type="text" class="form-control ss-input" name="settings[{{ $setting->key }}][value]" value="{{ $setting->value }}">
                                                @endif
                                                <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center">
                                <button type="submit" class="ss-save-btn ss-save-btn--success"><i class="fas fa-save"></i> حفظ الكل</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        {{-- ==================== Sidebar ==================== --}}
        <div class="col-lg-4">
            <div class="ss-card" style="position: sticky; top: 20px;">
                <div class="ss-card-header">
                    <div class="ss-card-header-icon" style="background: var(--e-gray-100); color: var(--e-gray-600);"><i class="fas fa-info-circle"></i></div>
                    <h6>معلومات النظام</h6>
                </div>
                <div class="ss-card-body">
                    <div class="ss-info-item">
                        <span>Laravel</span>
                        <span>{{ app()->version() }}</span>
                    </div>
                    <div class="ss-info-item">
                        <span>البيئة</span>
                        <span class="badge bg-{{ app()->environment() === 'production' ? 'danger' : 'warning' }}">{{ app()->environment() }}</span>
                    </div>
                    <div class="ss-info-item">
                        <span>اللغة</span>
                        <span>{{ config('app.locale') }}</span>
                    </div>
                    <div class="ss-info-item">
                        <span>المنطقة الزمنية</span>
                        <span>{{ config('app.timezone') }}</span>
                    </div>
                </div>
            </div>

            @php
                $totalProviders = \App\Models\User::where('user_type', 'provider')->count();
                $verifiedProviders = \App\Models\ProviderProfile::where('is_verified', true)->count();
                $activeProviders = \App\Models\ProviderProfile::where('is_active', true)->count();
            @endphp
            <div class="ss-card mt-3">
                <div class="ss-card-header">
                    <div class="ss-card-header-icon" style="background: var(--e-accent-50); color: var(--e-accent);"><i class="fas fa-chart-bar"></i></div>
                    <h6>إحصائيات المزودين</h6>
                </div>
                <div class="ss-card-body">
                    <div class="ss-info-item">
                        <span>إجمالي المزودين</span>
                        <span>{{ $totalProviders }}</span>
                    </div>
                    <div class="ss-info-item">
                        <span>الموثقون</span>
                        <span class="text-success">{{ $verifiedProviders }}</span>
                    </div>
                    <div class="ss-info-item">
                        <span>النشطون</span>
                        <span class="text-primary">{{ $activeProviders }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ===== Tabs =====
        document.querySelectorAll('.ss-tab').forEach(function(tab) {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.ss-tab').forEach(function(t) { t.classList.remove('active'); });
                document.querySelectorAll('.ss-panel').forEach(function(p) { p.classList.remove('active'); });
                this.classList.add('active');
                var panel = document.getElementById('panel-' + this.dataset.tab);
                if (panel) panel.classList.add('active');
            });
        });

        // ===== Utilities =====
        function removeCurrentImage() {
            if (!confirm('هل أنت متأكد من حذف الصورة الحالية؟')) return;
            var form = document.querySelector('form[action*="default-service-image"]');
            var input = document.createElement('input');
            input.type = 'hidden'; input.name = 'remove_image'; input.value = '1';
            form.appendChild(input);
            form.submit();
        }

        function removeCurrentLogo() {
            if (!confirm('هل أنت متأكد من حذف اللوجو الحالي؟')) return;
            var form = document.querySelector('form[action="{{ route("admin.system-settings.update") }}"]');
            var input = document.createElement('input');
            input.type = 'hidden'; input.name = 'remove_logo'; input.value = '1';
            form.appendChild(input);
            form.submit();
        }

        function previewLogo(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var el = document.getElementById('current-logo');
                    if (el) el.src = e.target.result;
                    var p = document.getElementById('logo-preview');
                    if (p) p.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // WhatsApp preview
        function updateWhatsAppPreview() {
            var n = document.getElementById('whatsapp_number');
            var m = document.getElementById('whatsapp_message');
            var p = document.getElementById('whatsapp-preview');
            if (n && m && p) {
                if (n.value && m.value) {
                    p.textContent = 'https://wa.me/' + n.value.replace(/[^0-9]/g, '') + '?text=' + encodeURIComponent(m.value);
                } else {
                    p.textContent = 'أدخل الرقم والرسالة';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var wn = document.getElementById('whatsapp_number');
            var wm = document.getElementById('whatsapp_message');
            if (wn) wn.addEventListener('input', updateWhatsAppPreview);
            if (wm) wm.addEventListener('input', updateWhatsAppPreview);
            updateWhatsAppPreview();

            // SEO counters
            var dAr = document.getElementById('site_description_ar');
            var dEn = document.getElementById('site_description_en');
            var cAr = document.getElementById('desc_ar_count');
            var cEn = document.getElementById('desc_en_count');
            var prev = document.getElementById('seo_preview_desc');

            function updateSeo() {
                if (dAr && cAr) { cAr.textContent = dAr.value.length; cAr.style.color = dAr.value.length > 160 ? '#dc3545' : '#6c757d'; }
                if (dEn && cEn) { cEn.textContent = dEn.value.length; cEn.style.color = dEn.value.length > 160 ? '#dc3545' : '#6c757d'; }
                if (dAr && prev) prev.textContent = dAr.value || 'إنداك - المنصة الرائدة للخدمات...';
            }
            if (dAr) dAr.addEventListener('input', updateSeo);
            if (dEn) dEn.addEventListener('input', updateSeo);
            updateSeo();
        });
    </script>
@endsection
