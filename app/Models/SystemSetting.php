<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description'
    ];

    // الحصول على قيمة إعداد
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        switch ($setting->type) {
            case 'integer':
                return (int) $setting->value;
            case 'boolean':
                return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($setting->value, true);
            default:
                return $setting->value;
        }
    }

    // تعيين قيمة إعداد
    public static function set($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        $setting = static::where('key', $key)->first();

        if ($setting) {
            $setting->update([
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type' => $type,
                'group' => $group,
                'description' => $description
            ]);
        } else {
            static::create([
                'key' => $key,
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type' => $type,
                'group' => $group,
                'description' => $description
            ]);
        }
    }

    // الحصول على جميع إعدادات مجموعة معينة
    public static function getGroup($group)
    {
        return static::where('group', $group)->get();
    }

    // الحصول على إعدادات مزود الخدمة
    public static function getProviderSettings()
    {
        return static::getGroup('provider');
    }

    // الحصول على الصورة الافتراضية للخدمات
    public static function getDefaultServiceImage()
    {
        $imagePath = static::get('default_service_image', 'services/default-service.jpg');
        $isEnabled = static::get('default_service_image_enabled', true);

        if (!$isEnabled) {
            return null;
        }

        return asset('storage/' . $imagePath);
    }

    // التحقق من تفعيل الصورة الافتراضية للخدمات
    public static function isDefaultServiceImageEnabled()
    {
        return static::get('default_service_image_enabled', true);
    }

    // تعيين الصورة الافتراضية للخدمات
    public static function setDefaultServiceImage($imagePath)
    {
        static::set('default_service_image', $imagePath, 'string', 'general', 'الصورة الافتراضية للخدمات');
    }

    // تفعيل/إلغاء تفعيل الصورة الافتراضية للخدمات
    public static function setDefaultServiceImageEnabled($enabled)
    {
        static::set('default_service_image_enabled', $enabled, 'boolean', 'general', 'تفعيل الصورة الافتراضية للخدمات');
    }
}
