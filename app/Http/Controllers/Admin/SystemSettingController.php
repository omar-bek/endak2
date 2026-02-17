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
            if ($currentLogo && $currentLogo !== 'home.png' && file_exists(public_path($currentLogo))) {
                unlink(public_path($currentLogo));
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
    if ($currentLogo && $currentLogo !== 'home.png' && file_exists(public_path($currentLogo))) {
        unlink(public_path($currentLogo));
    }

    // حفظ اللوجو الجديد داخل storage/app/public/logos/
    $filename = 'logo-' . time() . '.' . $file->getClientOriginalExtension();
    $path = $file->storeAs('logos', $filename, 'public');

    // تحديث إعداد اللوجو بالمسار الصحيح القابل للعرض عبر /storage/
    SystemSetting::where('key', 'site_logo')->update([
        'value' => 'storage/' . $path
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
            if ($currentImage && Storage::disk('public')->exists($currentImage)) {
                Storage::disk('public')->delete($currentImage);
            }
            SystemSetting::setDefaultServiceImage('services/default-service.jpg');
        }

        // إذا تم رفع صورة جديدة
        if ($request->hasFile('default_service_image')) {
            $file = $request->file('default_service_image');

            // حذف الصورة القديمة
            $currentImage = SystemSetting::get('default_service_image');
            if ($currentImage && Storage::disk('public')->exists($currentImage)) {
                Storage::disk('public')->delete($currentImage);
            }

            // حفظ الصورة الجديدة
            $path = $file->store('services', 'public');
            SystemSetting::setDefaultServiceImage($path);
        }

        // تحديث حالة التفعيل
        SystemSetting::setDefaultServiceImageEnabled($request->has('default_service_image_enabled'));

        return redirect()->route('admin.system-settings.index')
            ->with('success', 'تم تحديث الصورة الافتراضية للخدمات بنجاح');
    }
}
