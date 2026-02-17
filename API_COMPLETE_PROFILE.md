# API Complete Profile Endpoint

## Endpoint
`POST /api/v1/auth/complete-profile`

## Description
API endpoint لإكمال الملف الشخصي للمستخدم. يتضمن اختيار نوع الحساب (customer/provider) والموافقة على الشروط. للمزودين، يتضمن أيضاً إكمال بيانات ProviderProfile.

## Authentication
مطلوب - يجب إرسال API token في Header:
```
Authorization: Bearer {token}
```

## Request Body

### للمستخدم العادي (Customer)
```json
{
  "user_type": "customer",
  "terms": true
}
```

### لمزود الخدمة (Provider)
```json
{
  "user_type": "provider",
  "terms": true,
  "bio": "نبذة عن مزود الخدمة",
  "phone": "0123456789",
  "address": "العنوان الكامل",
  "categories": [1, 2, 3],
  "cities": [1, 2],
  "working_hours": [
    {
      "day": "sunday",
      "start": "09:00",
      "end": "17:00",
      "is_open": true
    },
    {
      "day": "monday",
      "start": "09:00",
      "end": "17:00",
      "is_open": true
    }
  ],
  "avatar": "file_upload"
}
```

## Parameters

### Required for all users:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `user_type` | string | Yes | نوع الحساب: `customer` أو `provider` |
| `terms` | boolean | Yes | الموافقة على الشروط والأحكام (يجب أن يكون `true`) |

### Required for providers only:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `bio` | string | Yes | نبذة عن مزود الخدمة (حد أقصى 1000 حرف) |
| `phone` | string | Yes | رقم الهاتف (حد أقصى 20 حرف) |
| `address` | string | Yes | العنوان الكامل (حد أقصى 500 حرف) |
| `categories` | array | Yes | مصفوفة من IDs الأقسام (حد أدنى 1، حد أقصى حسب الإعدادات) |
| `categories.*` | integer | Yes | ID القسم (يجب أن يكون موجود في جدول categories) |
| `cities` | array | Yes | مصفوفة من IDs المدن (حد أدنى 1، حد أقصى حسب الإعدادات) |
| `cities.*` | integer | Yes | ID المدينة (يجب أن يكون موجود في جدول cities) |

### Optional for providers:
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `working_hours` | array | No | ساعات العمل |
| `working_hours.*.day` | string | Yes (if working_hours provided) | اليوم: `sunday`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday` |
| `working_hours.*.start` | string | Yes (if working_hours provided) | وقت البداية (مثال: "09:00") |
| `working_hours.*.end` | string | Yes (if working_hours provided) | وقت النهاية (مثال: "17:00") |
| `working_hours.*.is_open` | boolean | No | هل المتجر مفتوح في هذا اليوم (افتراضي: true) |
| `avatar` | file | No | الصورة الشخصية (صورة: jpeg, jpg, png, gif, webp، حد أقصى 5MB) |

## Example Request (Customer)

```bash
curl -X POST "https://endak.net/api/v1/auth/complete-profile" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "user_type": "customer",
    "terms": true
  }'
```

## Example Request (Provider)

```bash
curl -X POST "https://endak.net/api/v1/auth/complete-profile" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "user_type": "provider",
    "terms": true,
    "bio": "أنا مزود خدمة محترف",
    "phone": "0123456789",
    "address": "الرياض، حي النرجس",
    "categories": [1, 2],
    "cities": [1, 2],
    "working_hours": [
      {
        "day": "sunday",
        "start": "09:00",
        "end": "17:00",
        "is_open": true
      },
      {
        "day": "monday",
        "start": "09:00",
        "end": "17:00",
        "is_open": true
      }
    ]
  }'
```

## Example Request (Provider with Avatar)

```bash
curl -X POST "https://endak.net/api/v1/auth/complete-profile" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json" \
  -F "user_type=provider" \
  -F "terms=true" \
  -F "bio=أنا مزود خدمة محترف" \
  -F "phone=0123456789" \
  -F "address=الرياض، حي النرجس" \
  -F "categories[]=1" \
  -F "categories[]=2" \
  -F "cities[]=1" \
  -F "cities[]=2" \
  -F "avatar=@/path/to/image.jpg"
```

## Example Response (Success)

```json
{
  "success": true,
  "message": "تم إكمال الملف الشخصي بنجاح",
  "data": {
    "id": 1,
    "name": "اسم المستخدم",
    "email": "user@example.com",
    "phone": "0123456789",
    "user_type": "provider",
    "terms_accepted_at": "2024-01-01T12:00:00.000000Z",
    "avatar": "avatars/xyz123.jpg",
    "provider_profile": {
      "id": 1,
      "user_id": 1,
      "bio": "أنا مزود خدمة محترف",
      "phone": "0123456789",
      "address": "الرياض، حي النرجس",
      "working_hours": [
        {
          "day": "sunday",
          "start": "09:00",
          "end": "17:00",
          "is_open": true
        }
      ],
      "is_verified": false,
      "is_active": true,
      "max_categories": 3,
      "max_cities": 5
    },
    "provider_categories": [
      {
        "id": 1,
        "user_id": 1,
        "category_id": 1,
        "is_active": true,
        "category": {
          "id": 1,
          "name": "اسم القسم بالعربية",
          "name_en": "Category Name in English",
          "slug": "category-slug",
          "icon": "icon-path",
          "image": "image-path"
        }
      }
    ],
    "provider_cities": [
      {
        "id": 1,
        "user_id": 1,
        "city_id": 1,
        "is_active": true,
        "city": {
          "id": 1,
          "name_ar": "اسم المدينة بالعربية",
          "name_en": "City Name in English",
          "slug": "city-slug"
        }
      }
    ]
  }
}
```

## Error Responses

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Missing API token"
}
```

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "user_type": ["يجب اختيار نوع الحساب"],
    "terms": ["يجب الموافقة على الشروط والأحكام"],
    "bio": ["حقل النبذة مطلوب"],
    "categories": ["يجب اختيار قسم واحد على الأقل"]
  }
}
```

### 500 Server Error
```json
{
  "success": false,
  "message": "حدث خطأ أثناء إكمال الملف الشخصي"
}
```

## Notes

- يجب أن يكون المستخدم مسجل دخول (يحتاج API token)
- يمكن استدعاء هذا الـ endpoint مرة واحدة فقط (بعد التسجيل)
- للمزودين، يتم إنشاء ProviderProfile تلقائياً
- يتم حذف الأقسام والمدن القديمة وإضافة الجديدة
- الحد الأقصى للأقسام والمدن يتم تحديده من SystemSettings
- الصورة الشخصية (avatar) اختيارية للمزودين
- **الأقسام والمدن**: يتم إرجاع الأقسام والمدن مع تفاصيلها الكاملة (category و city objects) في الاستجابة

## Related Endpoints

- `GET /api/v1/auth/complete-profile/data` - جلب الأقسام والمدن لإكمال الملف الشخصي (لا يحتاج authentication)
- `GET /api/v1/auth/profile` - جلب الملف الشخصي
- `PUT /api/v1/auth/profile` - تحديث الملف الشخصي

---

# API Get Complete Profile Data Endpoint

## Endpoint
`GET /api/v1/auth/complete-profile/data`

## Description
API endpoint لجلب الأقسام والمدن المتاحة لإكمال الملف الشخصي. هذا الـ endpoint **لا يحتاج authentication** ويمكن استخدامه لعرض الخيارات المتاحة للمستخدم قبل إكمال الملف الشخصي.

## Authentication
**غير مطلوب** - هذا endpoint عام ويمكن الوصول إليه بدون token.

## Example Request

```bash
curl -X GET "https://endak.net/api/v1/auth/complete-profile/data" \
  -H "Accept: application/json"
```

## Example Response (Success)

```json
{
  "success": true,
  "message": null,
  "data": {
    "categories": [
      {
        "id": 1,
        "name": "تقنية المعلومات",
        "name_en": "Information Technology",
        "slug": "it",
        "icon": "fas fa-laptop",
        "image": "https://endak.net/storage/categories/it.jpg",
        "sort_order": 1,
        "children": [
          {
            "id": 2,
            "name": "تطوير المواقع",
            "name_en": "Web Development",
            "slug": "web-development",
            "icon": "fas fa-code",
            "image": "https://endak.net/storage/categories/web.jpg",
            "sort_order": 1
          }
        ]
      },
      {
        "id": 3,
        "name": "التصميم",
        "name_en": "Design",
        "slug": "design",
        "icon": "fas fa-paint-brush",
        "image": null,
        "sort_order": 2,
        "children": []
      }
    ],
    "cities": [
      {
        "id": 1,
        "name_ar": "الرياض",
        "name_en": "Riyadh",
        "slug": "riyadh",
        "sort_order": 1
      },
      {
        "id": 2,
        "name_ar": "جدة",
        "name_en": "Jeddah",
        "slug": "jeddah",
        "sort_order": 2
      }
    ],
    "limits": {
      "max_categories": 3,
      "max_cities": 5
    }
  }
}
```

## Response Fields

### Categories
| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | معرف القسم |
| `name` | string | اسم القسم بالعربية |
| `name_en` | string | اسم القسم بالإنجليزية |
| `slug` | string | رابط القسم |
| `icon` | string | أيقونة القسم |
| `image` | string\|null | رابط صورة القسم |
| `sort_order` | integer | ترتيب القسم |
| `children` | array | الأقسام الفرعية (نفس البنية) |

### Cities
| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | معرف المدينة |
| `name_ar` | string | اسم المدينة بالعربية |
| `name_en` | string | اسم المدينة بالإنجليزية |
| `slug` | string\|null | رابط المدينة |
| `sort_order` | integer | ترتيب المدينة |

### Limits
| Field | Type | Description |
|-------|------|-------------|
| `max_categories` | integer | الحد الأقصى لعدد الأقسام المسموح بها للمزود |
| `max_cities` | integer | الحد الأقصى لعدد المدن المسموح بها للمزود |

## Notes

- هذا endpoint **عام** ولا يحتاج authentication
- يتم إرجاع الأقسام الرئيسية فقط مع أقسامها الفرعية
- يتم إرجاع المدن النشطة فقط
- يتم إرجاع الحد الأقصى للأقسام والمدن من SystemSettings
- يمكن استخدام هذا الـ endpoint لعرض الخيارات في واجهة إكمال الملف الشخصي
