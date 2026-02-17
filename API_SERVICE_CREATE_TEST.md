# اختبار إنشاء خدمة عبر API

## المشكلة

عند إرسال API لإنشاء خدمة، يحدث خطأ: `{ "success": false, "message": "حدث خطأ أثناء إنشاء الخدمة", "errors": null }`

## الحل المطبق

تم تحسين معالجة `custom_fields` في `Api\ServiceController` لدعم:

1. JSON format
2. Form-data format
3. Logging أفضل للأخطاء

## طرق الإرسال

### 1. JSON Format (مُوصى به)

```bash
curl -X POST http://127.0.0.1:8000/api/v1/services \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "خدمة نقل أثاث",
    "description": "نقل أثاث من الرياض إلى جدة",
    "category_id": 40,
    "city_id": 2,
    "custom_fields": {
      "الوقت": ["sad"]
    }
  }'
```

### 2. Form-Data Format

```bash
curl -X POST http://127.0.0.1:8000/api/v1/services \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: multipart/form-data" \
  -F "title=خدمة نقل أثاث" \
  -F "description=نقل أثاث من الرياض إلى جدة" \
  -F "category_id=40" \
  -F "city_id=2" \
  -F "custom_fields[الوقت][0]=sad"
```

### 3. x-www-form-urlencoded Format

```bash
curl -X POST http://127.0.0.1:8000/api/v1/services \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "title=خدمة نقل أثاث" \
  -d "description=نقل أثاث من الرياض إلى جدة" \
  -d "category_id=40" \
  -d "city_id=2" \
  -d "custom_fields[الوقت][0]=sad"
```

## التحقق من الأخطاء

### 1. تحقق من Logs

```bash
tail -f storage/logs/laravel.log
```

### 2. تحقق من Response

في بيئة التطوير (`APP_DEBUG=true`)، ستحصل على تفاصيل الخطأ:

```json
{
    "success": false,
    "message": "حدث خطأ أثناء إنشاء الخدمة",
    "errors": null,
    "debug": {
        "message": "Error message here",
        "file": "path/to/file.php",
        "line": 123
    }
}
```

## البيانات المطلوبة

### الحقول الإجبارية:

-   `title` (string, max:255)
-   `description` (string)
-   `category_id` (exists:categories,id)
-   `city_id` (exists:cities,id)

### الحقول الاختيارية:

-   `price` (numeric, min:0)
-   `sub_category_id` (exists:sub_categories,id)
-   `custom_fields` (array)

## أمثلة custom_fields

### حقل واحد:

```json
{
    "custom_fields": {
        "الوقت": "sad"
    }
}
```

### حقل متعدد القيم:

```json
{
    "custom_fields": {
        "الوقت": ["sad", "value2"]
    }
}
```

### حقول متعددة:

```json
{
    "custom_fields": {
        "الوقت": ["sad"],
        "المكان": "الرياض",
        "التاريخ": "2024-01-01"
    }
}
```

## ملاحظات

1. تأكد من أن الـ token صحيح ومفعل
2. تأكد من أن `category_id` و `city_id` موجودين في قاعدة البيانات
3. في حالة استخدام form-data، Laravel يقوم تلقائياً بتحويل `custom_fields[fieldName][index]` إلى array
4. استخدم JSON format للحصول على أفضل أداء
