# دليل رفع الصورة في Postman

## المشكلة الشائعة

عند إرسال مسار ملف محلي مثل `/C:/Users/solutions/OneDrive/Pictures/image.jpg` كـ Text في Postman، لن يعمل لأن:
- الملف موجود على جهازك وليس على الـ server
- يجب رفع الملف فعلياً وليس إرسال المسار

## الحل الصحيح في Postman

### الخطوات:

1. **افتح Postman** وأنشئ request جديد

2. **اختر Method:** `PUT`

3. **أدخل URL:**
   ```
   https://endak.net/api/v1/auth/profile
   ```

4. **أضف Header:**
   - Key: `Authorization`
   - Value: `Bearer {your_token_here}`

5. **اذهب إلى Body Tab**

6. **اختر `form-data`** (ليس raw أو x-www-form-urlencoded)

7. **أضف الحقول:**

   **للحقول النصية (Text):**
   - Key: `name`
   - Type: **Text** (افتراضي)
   - Value: `ahmed`
   
   - Key: `phone`
   - Type: **Text**
   - Value: `+966507654321`
   
   - Key: `bio`
   - Type: **Text**
   - Value: `نبذة عني`

   **لحقل الصورة (File):**
   - Key: `avatar`
   - Type: **File** ⚠️ (غير من Text إلى File!)
   - Value: اختر الملف من جهازك (Browse)

8. **للمزودين - بيانات Provider:**
   - Key: `provider[bio]`
   - Type: **Text**
   - Value: `نبذة المزود`
   
   - Key: `provider[address]`
   - Type: **Text**
   - Value: `العنوان`

9. **اضغط Send**

## ⚠️ الأخطاء الشائعة

### ❌ خطأ: إرسال مسار الملف كـ Text
```
Key: avatar
Type: Text
Value: /C:/Users/solutions/Pictures/image.jpg
```
**النتيجة:** خطأ - الملف غير موجود على الـ server

### ✅ صحيح: رفع الملف كـ File
```
Key: avatar
Type: File
Value: [Browse and select file]
```
**النتيجة:** يعمل بشكل صحيح

## مثال كامل في Postman

### Headers:
```
Authorization: Bearer your_api_token_here
```

### Body (form-data):
| Key | Type | Value |
|-----|------|-------|
| `name` | Text | `ahmed` |
| `phone` | Text | `+966507654321` |
| `bio` | Text | `نبذة` |
| `avatar` | **File** | [اختر الملف] |
| `provider[bio]` | Text | `نبذة المزود` |
| `provider[address]` | Text | `العنوان` |

## طرق بديلة لرفع الصورة

### 1. Base64 (JSON)
إذا كنت تريد استخدام JSON بدلاً من form-data:

**Body:** اختر `raw` و `JSON`

```json
{
  "name": "ahmed",
  "phone": "+966507654321",
  "avatar": "data:image/jpeg;base64,/9j/4AAQSkZJRg..."
}
```

### 2. Image URL
```json
{
  "name": "ahmed",
  "avatar": "https://example.com/image.jpg"
}
```

## ملاحظات مهمة

1. **لا ترسل مسار ملف محلي** - لن يعمل
2. **استخدم File type** في Postman لرفع الملف
3. **استخدم form-data** وليس raw JSON عند رفع ملف
4. **الحد الأقصى لحجم الصورة:** 5MB
5. **الصيغ المدعومة:** JPEG, PNG, GIF, WebP

## اختبار سريع

بعد رفع الملف، يجب أن تحصل على استجابة مثل:

```json
{
  "success": true,
  "message": "تم تحديث الملف الشخصي بنجاح",
  "data": {
    "id": 3,
    "name": "ahmed",
    "avatar": "avatars/xyz123abc456.jpg",
    ...
  }
}
```

إذا حصلت على خطأ، تحقق من:
- هل اخترت File type وليس Text؟
- هل الملف أصغر من 5MB؟
- هل الصيغة مدعومة (JPEG, PNG, GIF, WebP)؟
