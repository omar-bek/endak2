# دليل إعداد Facebook و Google Login - خطوة بخطوة

## 📱 إعداد Facebook Login

### الخطوة 1: إنشاء تطبيق فيسبوك
1. **اذهب إلى**: https://developers.facebook.com/
2. اضغط **"My Apps"** → **"Create App"**
3. اختر **"Consumer"**
4. أدخل:
   - **App Name**: `Endak` (أو أي اسم تريده)
   - **App Contact Email**: إيميلك
5. اضغط **"Create App"**

### الخطوة 2: إضافة Facebook Login
1. من القائمة الجانبية، اضغط **"Add Product"** أو ابحث عن **"Facebook Login"**
2. اضغط **"Set Up"** بجانب Facebook Login

### الخطوة 3: إعداد Redirect URIs
1. من القائمة الجانبية، اذهب إلى **"Settings"** تحت **"Facebook Login"**
2. في **"Valid OAuth Redirect URIs"**، أضف:
   ```
   http://localhost/auth/facebook/callback
   http://127.0.0.1/auth/facebook/callback
   ```
   (للتطوير)
   
   وأيضاً:
   ```
   https://your-domain.com/auth/facebook/callback
   ```
   (للإنتاج - استبدل your-domain.com باسم النطاق الفعلي)
3. اضغط **"Save Changes"**

### الخطوة 4: الحصول على App ID و App Secret
1. من القائمة الجانبية، اذهب إلى **"Settings"** → **"Basic"**
2. ستجد:
   - **App ID**: نسخه (رقم طويل مثل: `1234567890123456`)
   - **App Secret**: اضغط **"Show"** بجانبه ونسخه (سلسلة طويلة من الأحرف والأرقام)

### الخطوة 5: إضافة إلى `.env`
افتح ملف `.env` في المشروع وأضف:

```env
FACEBOOK_CLIENT_ID=ضع_App_ID_هنا
FACEBOOK_CLIENT_SECRET=ضع_App_Secret_هنا
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback
```

---

## 🔍 إعداد Google Login

### الخطوة 1: إنشاء مشروع في Google Cloud
1. **اذهب إلى**: https://console.cloud.google.com/
2. اضغط **"Select a project"** في الأعلى
3. اضغط **"New Project"**
4. أدخل:
   - **Project Name**: `Endak` (أو أي اسم تريده)
5. اضغط **"Create"**

### الخطوة 2: تفعيل Google+ API
1. من القائمة الجانبية، اذهب إلى **"APIs & Services"** → **"Library"**
2. في شريط البحث، ابحث عن **"Google+ API"** أو **"Google Identity"**
3. اضغط **"Enable"**

### الخطوة 3: إعداد OAuth Consent Screen
1. اذهب إلى **"APIs & Services"** → **"OAuth consent screen"**
2. اختر **"External"** (للتطوير) أو **"Internal"** (للمؤسسات فقط)
3. اضغط **"Create"**
4. املأ البيانات:
   - **App name**: `Endak`
   - **User support email**: إيميلك
   - **Developer contact information**: إيميلك
5. اضغط **"Save and Continue"**
6. في **"Scopes"**، اضغط **"Save and Continue"** (استخدم الافتراضي)
7. في **"Test users"** (اختياري)، اضغط **"Save and Continue"**
8. راجع المعلومات واضغط **"Back to Dashboard"**

### الخطوة 4: إنشاء OAuth 2.0 Credentials
1. اذهب إلى **"APIs & Services"** → **"Credentials"**
2. اضغط **"Create Credentials"** → **"OAuth client ID"**
3. اختر **"Web application"**
4. أدخل:
   - **Name**: `Endak Web Client`
   - **Authorized redirect URIs**: أضف:
     ```
     http://localhost/auth/google/callback
     http://127.0.0.1/auth/google/callback
     ```
     (للتطوير)
     
     وأيضاً:
     ```
     https://your-domain.com/auth/google/callback
     ```
     (للإنتاج)
5. اضغط **"Create"**

### الخطوة 5: الحصول على Client ID و Client Secret
1. بعد الإنشاء، ستظهر نافذة تحتوي على:
   - **Your Client ID**: نسخه (مثل: `123456789-abcdefghijklmnopqrstuvwxyz.apps.googleusercontent.com`)
   - **Your Client Secret**: نسخه (مثل: `GOCSPX-abcdefghijklmnopqrstuvwxyz`)
2. احفظ هذه البيانات في مكان آمن

### الخطوة 6: إضافة إلى `.env`
افتح ملف `.env` وأضف:

```env
GOOGLE_CLIENT_ID=ضع_Client_ID_هنا
GOOGLE_CLIENT_SECRET=ضع_Client_Secret_هنا
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

---

## 📝 مثال على ملف `.env` كامل

```env
# Facebook Login
FACEBOOK_CLIENT_ID=1234567890123456
FACEBOOK_CLIENT_SECRET=abcdef1234567890abcdef1234567890abcdef
FACEBOOK_REDIRECT_URI=http://localhost/auth/facebook/callback

# Google Login
GOOGLE_CLIENT_ID=123456789-abcdefghijklmnopqrstuvwxyz.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnopqrstuvwxyz1234567890
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

---

## ✅ اختبار الإعداد

### اختبار Facebook:
1. اذهب إلى: `http://localhost/login`
2. اضغط **"تسجيل الدخول عبر فيسبوك"**
3. يجب أن تظهر صفحة تسجيل الدخول الخاصة بفيسبوك
4. بعد الموافقة، يجب أن يتم تسجيل الدخول تلقائياً

### اختبار Google:
1. اذهب إلى: `http://localhost/login`
2. اضغط **"تسجيل الدخول عبر جوجل"**
3. يجب أن تظهر صفحة تسجيل الدخول الخاصة بجوجل
4. بعد الموافقة، يجب أن يتم تسجيل الدخول تلقائياً

---

## ⚠️ ملاحظات مهمة

1. **للتطوير (localhost)**:
   - استخدم `http://localhost` في Redirect URIs
   - لا تحتاج HTTPS

2. **للإنتاج**:
   - استخدم `https://your-domain.com` في Redirect URIs
   - يجب أن يكون لديك SSL Certificate

3. **الأمان**:
   - لا تشارك App Secret أو Client Secret مع أي شخص
   - لا ترفع ملف `.env` إلى GitHub
   - احفظ الـ credentials في مكان آمن

---

## 🐛 حل المشاكل الشائعة

### مشكلة: "Invalid OAuth Redirect URI"
**الحل:**
- تأكد من إضافة URI بالضبط كما هو في `.env`
- تأكد من أن الـ URI يبدأ بـ `http://` أو `https://`
- تأكد من عدم وجود مسافات إضافية في نهاية الـ URI

### مشكلة: "App Not Setup"
**الحل:**
- تأكد من تفعيل Facebook Login product
- تأكد من إضافة Redirect URIs في الإعدادات

### مشكلة: "Access Denied" في Google
**الحل:**
- تأكد من تفعيل Google+ API
- تأكد من إعداد OAuth consent screen بشكل صحيح
- تأكد من أن OAuth consent screen في وضع "Testing" أو "Published"

---

## 📞 روابط مفيدة

- **Facebook Developers**: https://developers.facebook.com/
- **Google Cloud Console**: https://console.cloud.google.com/
- **Facebook Login Documentation**: https://developers.facebook.com/docs/facebook-login
- **Google OAuth Documentation**: https://developers.google.com/identity/protocols/oauth2

