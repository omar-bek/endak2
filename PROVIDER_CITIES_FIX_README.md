# إصلاح مشكلة Provider Cities - Endak

## المشكلة

كانت هناك مشكلة في جدول `provider_cities` حيث أن عمود `city_name` كان مطلوباً (NOT NULL) لكن النظام يحاول إدراج سجلات بدون قيمة لهذا العمود، مما يؤدي إلى خطأ:

```
SQLSTATE[23000]: Integrity constraint violation: 19 NOT NULL constraint failed: provider_cities.city_name
```

## السبب

تم تحديث النظام لاستخدام `city_id` بدلاً من `city_name` المباشر، لكن عمود `city_name` ظل مطلوباً في قاعدة البيانات.

## الحل

### 1. إنشاء هجرة لإصلاح المشكلة

تم إنشاء هجرة `2025_08_30_150826_fix_provider_cities_city_name_nullable.php` التي:

- جعلت عمود `city_name` اختياري (nullable)
- أزالت القيد الفريد القديم `['user_id', 'city_name']`
- أضافت قيد فريد جديد `['user_id', 'city_id']`

### 2. تحديث النموذج

تم تحديث نموذج `ProviderCity`:

- إزالة `city_name` من `$fillable`
- إضافة accessor `getCityNameAttribute()` للحصول على اسم المدينة من العلاقة
- تحديث العلاقات لتستخدم `city_id` بدلاً من `city_name`

### 3. التحقق من وحدة التحكم

تم التأكد من أن `ProviderProfileController` يستخدم `city_id` بشكل صحيح في:

- دالة `store()` - إضافة المدن الجديدة
- دالة `addCity()` - إضافة مدينة جديدة

## التغييرات المطبقة

### قاعدة البيانات
```sql
-- جعل city_name اختياري
ALTER TABLE provider_cities MODIFY city_name VARCHAR(255) NULL;

-- إزالة القيد الفريد القديم
ALTER TABLE provider_cities DROP UNIQUE KEY user_id_city_name_unique;

-- إضافة قيد فريد جديد
ALTER TABLE provider_cities ADD UNIQUE KEY user_id_city_id_unique (user_id, city_id);
```

### النموذج (ProviderCity.php)
```php
protected $fillable = [
    'user_id',
    'city_id',        // تم الاحتفاظ به
    'is_active',
    'notes'
    // تم إزالة 'city_name'
];

// إضافة accessor
public function getCityNameAttribute()
{
    return $this->city ? $this->city->name : null;
}

// تحديث العلاقات
public function services()
{
    return $this->hasMany(Service::class, 'city_id', 'city_id')
                ->where('user_id', $this->user_id);
}

public function offers()
{
    return $this->hasManyThrough(
        ServiceOffer::class,
        Service::class,
        'city_id',
        'service_id',
        'city_id',
        'id'
    )->where('provider_id', $this->user_id);
}
```

## النتيجة

✅ **تم حل المشكلة بنجاح!**

الآن يمكن:
- إضافة المدن لمزودي الخدمات بدون أخطاء
- استخدام `city_id` للعلاقات مع جدول `cities`
- الحصول على اسم المدينة عبر العلاقة `$providerCity->city->name`
- الحصول على اسم المدينة عبر accessor `$providerCity->city_name`

## الاختبار

يمكن اختبار الإصلاح عبر:
1. تسجيل دخول كمزود خدمة
2. الذهاب إلى صفحة الملف الشخصي
3. إضافة مدينة جديدة
4. التأكد من عدم ظهور أي أخطاء

---

**تم الإصلاح بواسطة فريق Endak**  
**تاريخ الإصلاح**: 30 أغسطس 2025
