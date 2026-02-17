# API Avatar Upload Documentation

## Endpoint
`PUT /api/v1/auth/profile`

## Description
API endpoint لتحديث الملف الشخصي مع دعم رفع الصورة الشخصية (avatar) بعدة طرق.

## Authentication
مطلوب - يجب إرسال API token في Header:
```
Authorization: Bearer {token}
```

## Avatar Upload Methods

يدعم الـ API أربع طرق لرفع الصورة الشخصية:

**ملاحظة مهمة:** لا يمكن إرسال مسار ملف محلي من جهازك (`C:\Users\...` أو `/home/user/...`) لأن الملف غير موجود على الـ server. يجب رفع الملف فعلياً.

### 1. رفع ملف مباشر (Multipart/Form-Data) ⭐ **الطريقة الموصى بها**
الطريقة الأفضل للرفع المباشر من التطبيق. يجب استخدام `multipart/form-data` وليس `application/json`.

**Request:**
```
Content-Type: multipart/form-data

PUT /api/v1/auth/profile
{
  "name": "أحمد محمد",
  "avatar": <file>
}
```

**cURL Example:**
```bash
curl -X PUT "https://endak.net/api/v1/auth/profile" \
  -H "Authorization: Bearer {token}" \
  -F "name=أحمد محمد" \
  -F "avatar=@/path/to/image.jpg"
```

**Postman Example:**
1. اختر Method: `PUT`
2. URL: `https://endak.net/api/v1/auth/profile`
3. Headers: أضف `Authorization: Bearer {token}`
4. Body: اختر `form-data`
5. أضف key: `avatar` مع type: `File` (ليس Text!)
6. اختر الملف من جهازك
7. أضف باقي الحقول كـ Text (name, phone, etc.)

**⚠️ خطأ شائع:** لا ترسل مسار الملف كـ Text! يجب رفع الملف فعلياً في حقل File.

### 2. Base64 Image
مفيد للتطبيقات التي تريد إرسال الصورة كـ string.

**Request:**
```json
{
  "name": "أحمد محمد",
  "avatar": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD..."
}
```

**JavaScript Example:**
```javascript
// Convert file to base64
const fileInput = document.querySelector('input[type="file"]');
const file = fileInput.files[0];
const reader = new FileReader();

reader.onload = function(e) {
  const base64Image = e.target.result;
  
  fetch('https://endak.net/api/v1/auth/profile', {
    method: 'PUT',
    headers: {
      'Authorization': 'Bearer ' + token,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      name: 'أحمد محمد',
      avatar: base64Image
    })
  });
};

reader.readAsDataURL(file);
```

### 3. Image URL
يمكن إرسال رابط صورة موجودة على الإنترنت.

**Request:**
```json
{
  "name": "أحمد محمد",
  "avatar": "https://example.com/image.jpg"
}
```

**cURL Example:**
```bash
curl -X PUT "https://endak.net/api/v1/auth/profile" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "أحمد محمد",
    "avatar": "https://example.com/image.jpg"
  }'
```

### 4. حذف الصورة
يمكن حذف الصورة بإرسال `null` أو string فارغ.

**Request:**
```json
{
  "name": "أحمد محمد",
  "avatar": null
}
```

## Supported Image Formats

- JPEG / JPG
- PNG
- GIF
- WebP

## Image Size Limits

- الحد الأقصى: **5MB** (5,242,880 bytes)

## Complete Example Request

```json
{
  "name": "أحمد محمد",
  "phone": "+966507654321",
  "email": "ahmed@example.com",
  "bio": "مطور تطبيقات",
  "avatar": "data:image/jpeg;base64,/9j/4AAQSkZJRg...",
  "provider": {
    "bio": "مزود خدمات تقنية",
    "address": "الرياض، حي النرجس",
    "phone": "+966507654321",
    "working_hours": [
      {
        "day": "sunday",
        "start": "09:00",
        "end": "17:00",
        "is_open": true
      }
    ]
  }
}
```

## Response

### Success Response
```json
{
  "success": true,
  "message": "تم تحديث الملف الشخصي بنجاح",
  "data": {
    "id": 3,
    "name": "أحمد محمد",
    "email": "ahmed@example.com",
    "avatar": "avatars/xyz123abc456.jpg",
    "provider_profile": {
      "id": 3,
      "bio": "مزود خدمات تقنية",
      "address": "الرياض، حي النرجس"
    }
  }
}
```

### Error Responses

#### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "avatar": ["حجم الصورة كبير جداً. الحد الأقصى هو 5MB"]
  }
}
```

#### 500 Server Error
```json
{
  "success": false,
  "message": "حدث خطأ أثناء تحديث الملف الشخصي",
  "errors": {
    "error": "فشل تحميل الصورة من الرابط: ..."
  }
}
```

## Notes

- عند رفع صورة جديدة، يتم حذف الصورة القديمة تلقائياً
- الصورة تُحفظ في `storage/app/public/avatars/`
- يمكن الوصول للصورة عبر: `https://endak.net/storage/avatars/{filename}`
- يتم إنشاء اسم ملف عشوائي لضمان عدم التكرار
- جميع الصور يتم التحقق من صحتها قبل الحفظ

## Testing

### Test with cURL (File Upload)
```bash
curl -X PUT "https://endak.net/api/v1/auth/profile" \
  -H "Authorization: Bearer {token}" \
  -F "name=أحمد محمد" \
  -F "avatar=@/path/to/image.jpg"
```

### Test with cURL (Base64)
```bash
curl -X PUT "https://endak.net/api/v1/auth/profile" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "أحمد محمد",
    "avatar": "data:image/jpeg;base64,/9j/4AAQSkZJRg..."
  }'
```

### Test with cURL (URL)
```bash
curl -X PUT "https://endak.net/api/v1/auth/profile" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "أحمد محمد",
    "avatar": "https://example.com/image.jpg"
  }'
```
