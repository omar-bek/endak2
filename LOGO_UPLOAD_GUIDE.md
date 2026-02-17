# دليل رفع اللوجو - اللوكال والسيرفر

## نظرة عامة
تم إعداد نظام رفع اللوجو ليعمل بشكل مثالي على كل من البيئة المحلية (Local) والسيرفر (Production).

## المميزات

### ✅ التحقق من صحة الملفات
- **أنواع الملفات المدعومة**: JPG, JPEG, PNG, GIF, SVG
- **الحد الأقصى للحجم**: 2MB
- **التحقق التلقائي**: قبل حفظ الملف

### ✅ حفظ آمن
- **أسماء فريدة**: `logo-{timestamp}.{extension}`
- **حذف تلقائي**: للصور القديمة عند رفع جديدة
- **حفظ في public**: مباشرة لضمان الظهور

### ✅ رسائل واضحة
- **نجاح الرفع**: "تم تحديث إعدادات النظام بنجاح وتم رفع اللوجو الجديد بنجاح"
- **فشل الرفع**: "نوع الملف غير مدعوم أو حجمه أكبر من 2MB"
- **أخطاء النظام**: "فشل في رفع الصورة"

## كيفية العمل

### 1. البيئة المحلية (Local)
```php
// المسارات
storage_path() // D:\EndakLastV\myendak\storage
public_path()  // D:\EndakLastV\myendak\public
config('app.url') // http://localhost
```

### 2. البيئة الإنتاجية (Production)
```php
// المسارات (مثال)
storage_path() // /var/www/html/storage
public_path()  // /var/www/html/public
config('app.url') // https://yourdomain.com
```

## التحقق من العمل

### 1. اختبار رفع صورة
```bash
# في terminal
php artisan tinker
>>> \App\Models\SystemSetting::get('site_logo')
# يجب أن يعرض اسم الملف الجديد
```

### 2. التحقق من وجود الملف
```bash
# التحقق من وجود الملف في public
ls public/logo-*.png
# أو في Windows
dir public\logo-*.png
```

### 3. اختبار الرابط
```bash
# اختبار الرابط في المتصفح
http://localhost/logo-{timestamp}.png
# أو على السيرفر
https://yourdomain.com/logo-{timestamp}.png
```

## استكشاف الأخطاء

### المشكلة: الصورة لا تظهر
**الحل**:
1. تحقق من وجود الملف في `public/`
2. تحقق من صلاحيات المجلد
3. تحقق من رابط الموقع

### المشكلة: فشل في الرفع
**الحل**:
1. تحقق من نوع الملف (JPG, PNG, etc.)
2. تحقق من حجم الملف (أقل من 2MB)
3. تحقق من صلاحيات الكتابة

### المشكلة: الصورة تظهر محلياً وليس على السيرفر
**الحل**:
1. تأكد من رفع الملفات الجديدة للسيرفر
2. تحقق من إعدادات السيرفر
3. تحقق من صلاحيات المجلدات

## الأكواد المهمة

### Controller
```php
// حفظ الملف
$filename = 'logo-' . time() . '.' . $file->getClientOriginalExtension();
$file->move(public_path(), $filename);

// التحقق من الحفظ
if (file_exists(public_path($filename))) {
    SystemSetting::where('key', 'site_logo')->update(['value' => $filename]);
}
```

### View
```blade
<!-- عرض اللوجو -->
<img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'home.png')) }}" 
     alt="{{ \App\Models\SystemSetting::get('site_name_ar', 'إنداك') }}">
```

## نصائح للتطوير

1. **اختبار محلياً أولاً**: تأكد من عمل كل شيء محلياً
2. **رفع الملفات**: عند النشر تأكد من رفع جميع الملفات
3. **صلاحيات المجلدات**: تأكد من صلاحيات الكتابة
4. **النسخ الاحتياطي**: احتفظ بنسخة من اللوجو الافتراضي

## الدعم
لأي مشاكل أو استفسارات، يرجى التواصل مع فريق التطوير.

