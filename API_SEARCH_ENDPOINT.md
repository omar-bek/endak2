# API Search Endpoint Documentation

## Endpoint
`GET /api/v1/services/search`

## Description
Endpoint للبحث والتصفية في الخدمات. هذا الـ endpoint متاح بدون authentication (public).

## Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `search` | string | No | البحث النصي في العنوان والوصف والموقع |
| `category` or `category_id` | integer | No | تصفية حسب القسم |
| `sub_category` or `sub_category_id` | integer | No | تصفية حسب القسم الفرعي |
| `city` or `city_id` | integer | No | تصفية حسب المدينة |
| `user_id` | integer | No | تصفية حسب المستخدم |
| `min_price` | numeric | No | الحد الأدنى للسعر |
| `max_price` | numeric | No | الحد الأقصى للسعر |
| `sort_by` | string | No | نوع الترتيب: `latest`, `oldest`, `price_asc`, `price_desc` (افتراضي: `latest`) |
| `per_page` | integer | No | عدد النتائج في الصفحة (افتراضي: 12، الحد الأقصى: 100) |

## Example Request

```
GET /api/v1/services/search?search=خدمة&category=1&city=2&min_price=100&max_price=1000&sort_by=price_asc&per_page=20
```

**ملاحظة:** يجب استخدام URL encoding للأحرف العربية في query parameters:
```
GET /api/v1/services/search?search=%D8%AE%D8%AF%D9%85%D8%A9&category=1&city=2
```

## Example Response

```json
{
  "success": true,
  "message": "تم جلب نتائج البحث بنجاح",
  "data": {
    "services": [
      {
        "id": 1,
        "title": "خدمة مثال",
        "description": "وصف الخدمة",
        "price": 500,
        "category": {
          "id": 1,
          "name": "قسم",
          "slug": "category-slug"
        },
        "city": {
          "id": 2,
          "name_ar": "الرياض",
          "name_en": "Riyadh"
        },
        "user": {
          "id": 1,
          "name": "اسم المستخدم",
          "avatar": "path/to/avatar.jpg"
        }
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 5,
      "per_page": 20,
      "total": 100,
      "from": 1,
      "to": 20
    },
    "filters_applied": {
      "search": "خدمة",
      "category_id": 1,
      "city_id": 2,
      "min_price": 100,
      "max_price": 1000,
      "sort_by": "price_asc"
    }
  }
}
```

## Error Responses

### 403 Forbidden
إذا حصلت على 403 Forbidden، قد يكون السبب:
1. إعدادات الـ server (Apache/Nginx) تمنع الوصول
2. مشكلة في URL encoding للأحرف العربية

**الحل:**
- تأكد من استخدام URL encoding للأحرف العربية
- تحقق من إعدادات الـ server
- جرب الـ endpoint بدون query parameters أولاً: `GET /api/v1/services/search`

### 404 Not Found
إذا حصلت على 404، تأكد من:
- استخدام المسار الصحيح: `/api/v1/services/search`
- استخدام HTTP method الصحيح: `GET`

## Testing

يمكنك اختبار الـ endpoint باستخدام curl:

```bash
# بدون parameters
curl -X GET "https://endak.net/api/v1/services/search"

# مع parameters
curl -X GET "https://endak.net/api/v1/services/search?category=1&city=2&per_page=10"

# مع search (URL encoded)
curl -X GET "https://endak.net/api/v1/services/search?search=%D8%AE%D8%AF%D9%85%D8%A9"
```

## Notes

- الـ endpoint متاح بدون authentication
- جميع الخدمات المعروضة يجب أن تكون نشطة (`is_active = true`)
- النتائج مرتبة حسب `created_at` بشكل افتراضي (أحدث أولاً)
