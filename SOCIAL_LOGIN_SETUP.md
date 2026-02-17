# إعداد تسجيل الدخول عبر فيسبوك وجوجل - دليل شامل

## 📘 إعداد Facebook Login

### الخطوة 1: إنشاء تطبيق فيسبوك
1. اذهب إلى [Facebook Developers](https://developers.facebook.com/)
2. اضغط على **"My Apps"** في الزاوية اليمنى العليا
3. اضغط على **"Create App"**
4. اختر **"Consumer"** أو **"Business"** حسب احتياجك
5. أدخل اسم التطبيق واتصل الإيميل
6. اضغط **"Create App"**

### الخطوة 2: إضافة Facebook Login
1. في لوحة التحكم، اذهب إلى **"Add Product"** أو **"Products"**
2. ابحث عن **"Facebook Login"** واختره
3. اضغط **"Set Up"**

### الخطوة 3: إعداد OAuth Redirect URIs
1. من القائمة الجانبية، اختر **"Settings"** تحت **"Facebook Login"**
2. في قسم **"Valid OAuth Redirect URIs"**، أضف:
   ```
   http://localhost/auth/facebook/callback
   http://127.0.0.1/auth/facebook/callback
   http://your-domain.com/auth/facebook/callback
   ```
3. احفظ التغييرات

### الخطوة 4: الحصول على App ID و App Secret
1. اذهب إلى **"Settings" → "Basic"** في القائمة الجانبية
2. ستجد:
   - **App ID**: نسخه
   - **App Secret**: اضغط على **"Show"** لرؤيته ونسخه

### الخطوة 5: إضافة الإعدادات إلى `.env`
```env
FACEBOOK_CLIENT_ID=your-app-id-here
FACEBOOK_CLIENT_SECRET=your-app-secret-here
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback
```

---

## 📘 إعداد Google Login

### الخطوة 1: إنشاء مشروع في Google Cloud
1. اذهب إلى [Google Cloud Console](https://console.cloud.google.com/)
2. اضغط على **"Select a project"** في الأعلى
3. اضغط **"New Project"**
4. أدخل اسم المشروع (مثلاً: "Endak App")
5. اضغط **"Create"**

### الخطوة 2: تفعيل Google+ API
1. من القائمة الجانبية، اذهب إلى **"APIs & Services" → "Library"**
2. ابحث عن **"Google+ API"** أو **"Google Identity API"**
3. اضغط **"Enable"**

### الخطوة 3: إنشاء OAuth 2.0 Credentials
1. اذهب إلى **"APIs & Services" → "Credentials"**
2. اضغط **"Create Credentials"** → **"OAuth client ID"**
3. إذا طُلب منك، اختر **"Configure consent screen"**:
   - اختر **"External"** (للتطوير)
   - أدخل اسم التطبيق والإيميل
   - احفظ وانتقل إلى الخطوة التالية

### الخطوة 4: إعداد OAuth Client
1. اختر **"Web application"** كنوع التطبيق
2. أدخل اسم للتطبيق (مثلاً: "Endak Web Client")
3. في **"Authorized redirect URIs"**، أضف:
   ```
   http://localhost/auth/google/callback
   http://127.0.0.1/auth/google/callback
   http://your-domain.com/auth/google/callback
   ```
4. اضغط **"Create"**

### الخطوة 5: الحصول على Client ID و Client Secret
1. بعد الإنشاء، ستظهر نافذة تحتوي على:
   - **Client ID**: نسخه
   - **Client Secret**: نسخه
2. احفظ هذه البيانات في مكان آمن

### الخطوة 6: إضافة الإعدادات إلى `.env`
```env
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

---

## 🔧 إعدادات إضافية

### Facebook - إعدادات إضافية:
1. في **"Settings" → "Basic"**:
   - أضف **"App Domains"**: `your-domain.com`
   - أضف **"Privacy Policy URL"** و **"Terms of Service URL"** (إذا كانت متوفرة)
2. في **"Facebook Login" → "Settings"**:
   - فعّل **"Use Strict Mode for Redirect URIs"** (مستحسن)
   - أضف **"Client OAuth Login"** و **"Web OAuth Login"**: `Yes`

### Google - إعدادات إضافية:
1. في **"OAuth consent screen"**:
   - أضف **"Authorized domains"**: `your-domain.com`
   - أضف **"Privacy Policy URL"** و **"Terms of Service URL"**
   - أضف **"Scopes"**:
     - `email`
     - `profile`
     - `openid`

---

## ✅ اختبار الإعداد

### اختبار Facebook:
1. اذهب إلى صفحة تسجيل الدخول
2. اضغط **"تسجيل الدخول عبر فيسبوك"**
3. يجب أن تظهر صفحة تسجيل الدخول الخاصة بفيسبوك
4. بعد الموافقة، يجب أن يتم تسجيل الدخول تلقائياً

### اختبار Google:
1. اذهب إلى صفحة تسجيل الدخول
2. اضغط **"تسجيل الدخول عبر جوجل"**
3. يجب أن تظهر صفحة تسجيل الدخول الخاصة بجوجل
4. بعد الموافقة، يجب أن يتم تسجيل الدخول تلقائياً

---

## ⚠️ ملاحظات مهمة

### Facebook:
- **App ID** و **App Secret** يجب أن يبقيا سراً
- تأكد من إضافة جميع الـ Redirect URIs الصحيحة
- في بيئة الإنتاج، استخدم HTTPS URLs

### Google:
- **Client ID** و **Client Secret** يجب أن يبقيا سراً
- تأكد من تفعيل Google+ API
- في بيئة الإنتاج، استخدم HTTPS URLs

---

## 🐛 حل المشاكل الشائعة

### مشكلة: "Invalid OAuth Redirect URI"
**الحل:**
- تأكد من إضافة URI بالضبط كما هو في `.env`
- تأكد من أن الـ URI يبدأ بـ `http://` أو `https://`
- تأكد من عدم وجود مسافات إضافية

### مشكلة: "App Not Setup"
**الحل:**
- تأكد من تفعيل Facebook Login product
- تأكد من إضافة Redirect URIs في الإعدادات

### مشكلة: "Access Denied"
**الحل:**
- تأكد من تفعيل Google+ API
- تأكد من إعداد OAuth consent screen بشكل صحيح
- تأكد من إضافة الـ Scopes المطلوبة

---

## 📝 مثال على ملف `.env` كامل

```env
# Facebook Login
FACEBOOK_CLIENT_ID=1234567890123456
FACEBOOK_CLIENT_SECRET=abcdef1234567890abcdef1234567890
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback

# Google Login
GOOGLE_CLIENT_ID=123456789-abcdefghijklmnopqrstuvwxyz.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnopqrstuvwxyz
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

---

## 🔐 الأمان

1. **لا تشارك** App ID و App Secret مع أي شخص
2. **لا ترفع** ملف `.env` إلى GitHub
3. استخدم **HTTPS** في بيئة الإنتاج
4. راجع **OAuth Redirect URIs** بانتظام
5. استخدم **App Secret** في بيئة الإنتاج فقط

---

## 📞 الدعم

إذا واجهت أي مشاكل:
1. راجع logs في `storage/logs/laravel.log`
2. تأكد من أن جميع الـ URIs صحيحة
3. تأكد من أن الـ APIs مفعلة في Facebook/Google Console

