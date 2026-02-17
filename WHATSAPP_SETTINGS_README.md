# إعدادات الواتساب - دليل الإدارة

## نظرة عامة

تم إضافة نظام إدارة رقم الواتساب في الداشبورد الإداري، مما يسمح للمديرين بالتحكم في إعدادات التواصل عبر الواتساب بسهولة.

## الميزات المضافة

### 1. إعدادات الواتساب في الداشبورد

-   **رقم الواتساب**: إمكانية تغيير رقم الواتساب للتواصل
-   **الرسالة الافتراضية**: تخصيص الرسالة التي تظهر عند فتح الواتساب
-   **تفعيل/تعطيل**: إمكانية تفعيل أو تعطيل رابط الواتساب في الموقع
-   **معاينة مباشرة**: عرض رابط الواتساب في الوقت الفعلي

### 2. Helper Functions

تم إنشاء `WhatsAppHelper` للاستخدام السهل في التطبيق:

```php
// الحصول على رابط الواتساب
$url = WhatsAppHelper::getWhatsAppUrl();

// الحصول على رابط مع رسالة مخصصة
$url = WhatsAppHelper::getWhatsAppUrl('رسالة مخصصة');

// الحصول على زر HTML
$button = WhatsAppHelper::getWhatsAppButton('تواصل معنا', null, 'btn btn-success');

// الحصول على رابط نصي
$link = WhatsAppHelper::getWhatsAppLink('تواصل معنا');

// التحقق من التفعيل
$isEnabled = WhatsAppHelper::isEnabled();
```

### 3. قاعدة البيانات

تم إضافة الإعدادات التالية إلى جدول `system_settings`:

-   `whatsapp_number`: رقم الواتساب (افتراضي: +966501234567)
-   `whatsapp_message`: الرسالة الافتراضية
-   `whatsapp_enabled`: تفعيل/تعطيل الواتساب (افتراضي: true)

## كيفية الاستخدام

### 1. الوصول للإعدادات

1. ادخل إلى الداشبورد الإداري
2. اذهب إلى "إعدادات النظام"
3. ستجد قسم "إعدادات التواصل والواتساب" في الأعلى

### 2. تحديث الإعدادات

1. أدخل رقم الواتساب مع رمز الدولة (مثال: +966501234567)
2. أدخل الرسالة الافتراضية
3. فعّل أو عطّل الواتساب حسب الحاجة
4. اضغط "حفظ إعدادات الواتساب"

### 3. الاستخدام في الكود

```php
// في Controller
use App\Helpers\WhatsAppHelper;

public function contact()
{
    $whatsappUrl = WhatsAppHelper::getWhatsAppUrl();
    return view('contact', compact('whatsappUrl'));
}
```

```blade
{{-- في Blade Template --}}
@if(\App\Helpers\WhatsAppHelper::isEnabled())
    {!! \App\Helpers\WhatsAppHelper::getWhatsAppButton('تواصل معنا') !!}
@endif
```

## الملفات المضافة/المحدثة

### ملفات جديدة:

-   `database/migrations/2025_10_11_112910_add_whatsapp_number_setting.php`
-   `app/Helpers/WhatsAppHelper.php`
-   `WHATSAPP_SETTINGS_README.md`

### ملفات محدثة:

-   `resources/views/admin/system-settings/index.blade.php` - إضافة قسم الواتساب
-   `resources/views/contact.blade.php` - إضافة زر الواتساب
-   `composer.json` - إضافة WhatsAppHelper إلى autoload

## مثال عملي

تم إضافة زر الواتساب في صفحة "اتصل بنا" الذي يظهر تلقائياً عند تفعيل الواتساب من الداشبورد.

## الأمان

-   جميع الروابط تفتح في نافذة جديدة (`target="_blank"`)
-   يتم إضافة `rel="noopener noreferrer"` للأمان
-   التحقق من صحة الرقم قبل إنشاء الرابط

## الدعم

لأي استفسارات أو مشاكل، يرجى التواصل مع فريق التطوير.
