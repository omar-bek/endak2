<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSettingController extends Controller
{
    /**
     * التحقق من صحة الملف
     */
    private function validateFile($file)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            return false;
        }

        if ($file->getSize() > 2 * 1024 * 1024) { // 2MB
            return false;
        }

        return true;
    }

    /**
     * عرض إعدادات النظام
     */
    public function index()
    {
        $settings = SystemSetting::all()->groupBy('group');
        return view('admin.system-settings.index', compact('settings'));
    }

    /**
     * تحديث إعدادات النظام
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required',
            'logo_upload' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'remove_logo' => 'boolean',
        ]);

        // معالجة حذف اللوجو
        if ($request->has('remove_logo') && $request->remove_logo) {
            $currentLogo = SystemSetting::get('site_logo', 'home.png');
            if ($currentLogo && $currentLogo !== 'home.png') {
                media_delete_public_file($currentLogo);
            }
            SystemSetting::where('key', 'site_logo')->update(['value' => 'home.png']);
        }

        // معالجة رفع لوجو الموقع
      // معالجة رفع لوجو الموقع
if ($request->hasFile('logo_upload')) {
    $file = $request->file('logo_upload');

    // التحقق من صحة الملف
    if (!$this->validateFile($file)) {
        return redirect()->back()->with('error', 'نوع الملف غير مدعوم أو حجمه أكبر من 2MB');
    }

    // حذف اللوجو القديم إذا كان موجود
    $currentLogo = SystemSetting::get('site_logo', 'home.png');
    if ($currentLogo && $currentLogo !== 'home.png') {
        media_delete_public_file($currentLogo);
    }

    // حفظ اللوجو الجديد داخل storage/app/public/logos/
    $filename = 'logo-' . time() . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('logos', $filename, 'public');

    SystemSetting::where('key', 'site_logo')->update([
        'value' => media_public_url_from_path($path),
    ]);
}

        foreach ($request->settings as $setting) {
            if ($setting['key'] !== 'site_logo') { // تجنب تحديث اللوجو هنا لأنه تم التعامل معه أعلاه
                SystemSetting::where('key', $setting['key'])->update([
                    'value' => is_array($setting['value']) ? json_encode($setting['value']) : (string) $setting['value']
                ]);
            }
        }

        $message = 'تم تحديث إعدادات النظام بنجاح';

        // إضافة رسالة خاصة إذا تم رفع لوجو
        if ($request->hasFile('logo_upload')) {
            $message .= ' وتم رفع اللوجو الجديد بنجاح';
        }

        return redirect()->route('admin.system-settings.index')
            ->with('success', $message);
    }

    /**
     * تحديث إعدادات SEO والسوشيال ميديا
     */
    public function updateSeo(Request $request)
    {
        $request->validate([
            'site_description_ar' => 'nullable|string|max:300',
            'site_description_en' => 'nullable|string|max:300',
            'site_keywords_ar' => 'nullable|string|max:500',
            'site_keywords_en' => 'nullable|string|max:500',
            'social_facebook' => 'nullable|url|max:500',
            'social_twitter' => 'nullable|url|max:500',
            'social_instagram' => 'nullable|url|max:500',
            'social_tiktok' => 'nullable|url|max:500',
            'social_youtube' => 'nullable|url|max:500',
        ]);

        $fields = [
            'site_description_ar' => ['type' => 'string', 'group' => 'seo', 'desc' => 'وصف الموقع بالعربي'],
            'site_description_en' => ['type' => 'string', 'group' => 'seo', 'desc' => 'وصف الموقع بالإنجليزي'],
            'site_keywords_ar' => ['type' => 'string', 'group' => 'seo', 'desc' => 'كلمات مفتاحية بالعربي'],
            'site_keywords_en' => ['type' => 'string', 'group' => 'seo', 'desc' => 'كلمات مفتاحية بالإنجليزي'],
            'social_facebook' => ['type' => 'string', 'group' => 'social', 'desc' => 'رابط فيسبوك'],
            'social_twitter' => ['type' => 'string', 'group' => 'social', 'desc' => 'رابط تويتر/X'],
            'social_instagram' => ['type' => 'string', 'group' => 'social', 'desc' => 'رابط إنستغرام'],
            'social_tiktok' => ['type' => 'string', 'group' => 'social', 'desc' => 'رابط تيك توك'],
            'social_youtube' => ['type' => 'string', 'group' => 'social', 'desc' => 'رابط يوتيوب'],
        ];

        foreach ($fields as $key => $meta) {
            SystemSetting::set($key, $request->input($key, ''), $meta['type'], $meta['group'], $meta['desc']);
        }

        return redirect()->route('admin.system-settings.index')
            ->with('success', 'تم تحديث إعدادات SEO والسوشيال ميديا بنجاح');
    }

    /**
     * تحديث روابط التطبيقات
     */
    public function updateAppLinks(Request $request)
    {
        $request->validate([
            'provider_app_google_play' => 'nullable|url|max:500',
            'provider_app_appstore' => 'nullable|url|max:500',
            'client_app_google_play' => 'nullable|url|max:500',
            'client_app_appstore' => 'nullable|url|max:500',
        ]);

        SystemSetting::set('provider_app_google_play', $request->input('provider_app_google_play', ''), 'string', 'apps', 'رابط تطبيق مزود الخدمة - Google Play');
        SystemSetting::set('provider_app_appstore', $request->input('provider_app_appstore', ''), 'string', 'apps', 'رابط تطبيق مزود الخدمة - App Store');
        SystemSetting::set('client_app_google_play', $request->input('client_app_google_play', ''), 'string', 'apps', 'رابط تطبيق العميل - Google Play');
        SystemSetting::set('client_app_appstore', $request->input('client_app_appstore', ''), 'string', 'apps', 'رابط تطبيق العميل - App Store');
        SystemSetting::set('provider_app_enabled', $request->has('provider_app_enabled'), 'boolean', 'apps', 'تفعيل رابط تطبيق المزود');
        SystemSetting::set('client_app_enabled', $request->has('client_app_enabled'), 'boolean', 'apps', 'تفعيل رابط تطبيق العميل');

        return redirect()->route('admin.system-settings.index')
            ->with('success', 'تم تحديث روابط التطبيقات بنجاح');
    }

    /**
     * تحديث إعدادات مزود الخدمة
     */
    public function updateProviderSettings(Request $request)
    {
        $request->validate([
            'provider_max_categories' => 'required|integer|min:1|max:10',
            'provider_max_cities' => 'required|integer|min:1|max:20',
            'provider_verification_required' => 'boolean',
            'provider_auto_approve' => 'boolean',
        ]);

        SystemSetting::set('provider_max_categories', $request->provider_max_categories, 'integer', 'provider');
        SystemSetting::set('provider_max_cities', $request->provider_max_cities, 'integer', 'provider');
        SystemSetting::set('provider_verification_required', $request->provider_verification_required, 'boolean', 'provider');
        SystemSetting::set('provider_auto_approve', $request->provider_auto_approve, 'boolean', 'provider');

        return redirect()->route('admin.system-settings.index')
            ->with('success', 'تم تحديث إعدادات مزود الخدمة بنجاح');
    }

    /**
     * تحديث الصورة الافتراضية للخدمات
     */
    public function updateDefaultServiceImage(Request $request)
    {
        $request->validate([
            'default_service_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'default_service_image_enabled' => 'boolean',
            'remove_image' => 'boolean'
        ]);

        // إذا تم طلب حذف الصورة
        if ($request->has('remove_image') && $request->remove_image) {
            $currentImage = SystemSetting::get('default_service_image');
            $old = $currentImage ? media_public_disk_path($currentImage) : null;
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }
            SystemSetting::setDefaultServiceImage(media_public_url_from_path('services/default-service.jpg'));
        }

        // إذا تم رفع صورة جديدة
        if ($request->hasFile('default_service_image')) {
            $file = $request->file('default_service_image');

            // حذف الصورة القديمة
            $currentImage = SystemSetting::get('default_service_image');
            $old = $currentImage ? media_public_disk_path($currentImage) : null;
            if ($old && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }

            // حفظ الصورة الجديدة
            $path = $file->store('services', 'public');
            SystemSetting::setDefaultServiceImage(media_public_url_from_path($path));
        }

        // تحديث حالة التفعيل
        SystemSetting::setDefaultServiceImageEnabled($request->has('default_service_image_enabled'));

        return redirect()->route('admin.system-settings.index')
            ->with('success', 'تم تحديث الصورة الافتراضية للخدمات بنجاح');
    }
}
