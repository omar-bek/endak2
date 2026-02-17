# دليل سريع لإعداد Social Login

## 🚀 إعداد سريع (5 دقائق)

### Facebook (خطوتين)

#### 1. إنشاء التطبيق
- اذهب إلى: https://developers.facebook.com/
- **My Apps** → **Create App** → اختر **Consumer**
- أدخل اسم التطبيق واتصل الإيميل

#### 2. الحصول على البيانات
- **Settings** → **Basic**: نسخ **App ID** و **App Secret**
- **Products** → **Facebook Login** → **Settings**
- أضف في **Valid OAuth Redirect URIs**:
  ```
  http://localhost/auth/facebook/callback
  http://your-domain.com/auth/facebook/callback
  ```

---

### Google (خطوتين)

#### 1. إنشاء المشروع
- اذهب إلى: https://console.cloud.google.com/
- **New Project** → أدخل اسم المشروع
- **APIs & Services** → **Library** → ابحث عن **Google+ API** → **Enable**

#### 2. الحصول على البيانات
- **APIs & Services** → **Credentials** → **Create Credentials** → **OAuth client ID**
- اختر **Web application**
- أضف **Authorized redirect URIs**:
  ```
  http://localhost/auth/google/callback
  http://your-domain.com/auth/google/callback
  ```
- نسخ **Client ID** و **Client Secret**

---

## 📝 إضافة إلى `.env`

افتح ملف `.env` وأضف:

```env
# Facebook
FACEBOOK_CLIENT_ID=ضع_App_ID_هنا
FACEBOOK_CLIENT_SECRET=ضع_App_Secret_هنا
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback

# Google
GOOGLE_CLIENT_ID=ضع_Client_ID_هنا
GOOGLE_CLIENT_SECRET=ضع_Client_Secret_هنا
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

---

## ✅ اختبار

1. اذهب إلى صفحة تسجيل الدخول
2. اضغط على **"تسجيل الدخول عبر فيسبوك"** أو **"تسجيل الدخول عبر جوجل"**
3. يجب أن يعمل!

---

## ⚠️ ملاحظات

- استبدل `your-domain.com` باسم النطاق الفعلي
- في بيئة الإنتاج، استخدم HTTPS
- احفظ الـ credentials في مكان آمن

