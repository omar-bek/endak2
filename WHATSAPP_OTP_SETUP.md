# إعداد نظام OTP للواتساب

## المكونات المضافة

### 1. نموذج OTP

-   **الملف**: `app/Models/Otp.php`
-   **الوظائف**:
    -   إنشاء رمز OTP عشوائي
    -   التحقق من صحة الرمز
    -   إدارة انتهاء صلاحية الرمز
    -   تتبع المحاولات

### 2. خدمة الواتساب

-   **الملف**: `app/Services/WhatsAppOtpService.php`
-   **الوظائف**:
    -   إرسال رسائل OTP عبر واتساب
    -   تنسيق أرقام الهواتف
    -   إدارة محاولات الإرسال
    -   رسائل مخصصة حسب نوع العملية

### 3. تحديث AuthController

-   **الوظائف الجديدة**:
    -   `showVerifyOtpForm()` - عرض صفحة التحقق
    -   `verifyOtp()` - التحقق من الرمز
    -   `resendOtp()` - إعادة إرسال الرمز
    -   تحديث `register()` لدعم OTP

### 4. صفحة التحقق من OTP

-   **الملف**: `resources/views/auth/verify-otp.blade.php`
-   **المميزات**:
    -   تصميم جميل ومتجاوب
    -   إدخال تلقائي للرمز
    -   عداد زمني لإعادة الإرسال
    -   رسائل خطأ واضحة

## إعداد متغيرات البيئة

أضف هذه المتغيرات إلى ملف `.env`:

```env
# إعدادات Twilio للواتساب
TWILIO_SID=YOUR_TWILIO_ACCOUNT_SID
TWILIO_TOKEN=your_auth_token_here
TWILIO_WHATSAPP_FROM=whatsapp:+14155238886

# إعدادات واتساب (Facebook API - اختياري)
WHATSAPP_API_URL=https://graph.facebook.com/v18.0
WHATSAPP_API_KEY=your_api_key_here
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id
WHATSAPP_ACCESS_TOKEN=your_access_token
```

## كيفية الحصول على بيانات Twilio

### 1. إنشاء حساب Twilio

1. اذهب إلى [Twilio Console](https://console.twilio.com/)
2. أنشئ حساب جديد أو سجل الدخول
3. احصل على Account SID و Auth Token من لوحة التحكم

### 2. تفعيل WhatsApp Sandbox

1. اذهب إلى [Twilio WhatsApp Sandbox](https://console.twilio.com/us1/develop/sms/try-it-out/whatsapp-learn)
2. اتبع التعليمات لتفعيل Sandbox
3. احصل على رقم WhatsApp الخاص بـ Sandbox

### 3. إعداد متغيرات البيئة

```env
TWILIO_SID=YOUR_TWILIO_ACCOUNT_SID
TWILIO_TOKEN=your_auth_token_here
TWILIO_WHATSAPP_FROM=whatsapp:+14155238886
```

## كيفية الحصول على بيانات واتساب (Facebook API - اختياري)

### 1. إنشاء تطبيق Facebook

1. اذهب إلى [Facebook Developers](https://developers.facebook.com/)
2. أنشئ تطبيق جديد
3. أضف منتج "WhatsApp Business API"

### 2. الحصول على Phone Number ID

1. في لوحة التحكم، اذهب إلى WhatsApp > API Setup
2. انسخ Phone Number ID

### 3. الحصول على Access Token

1. في نفس الصفحة، انسخ Access Token
2. تأكد من أن الرمز له صلاحيات إرسال الرسائل

## تشغيل النظام

### 1. تشغيل Migration

```bash
php artisan migrate
```

### 2. اختبار النظام

1. اذهب إلى صفحة التسجيل
2. أدخل البيانات المطلوبة
3. سيتم إرسال رمز OTP إلى الواتساب
4. أدخل الرمز للتحقق

## المميزات

### 1. الأمان

-   رموز OTP عشوائية 6 أرقام
-   انتهاء صلاحية خلال 5 دقائق
-   حد أقصى 5 محاولات في الساعة
-   حذف الرموز المستخدمة

### 2. تجربة المستخدم

-   واجهة مستخدم جميلة ومتجاوبة
-   رسائل واضحة باللغة العربية
-   إدخال تلقائي للرمز
-   عداد زمني لإعادة الإرسال

### 3. المرونة

-   دعم أنواع مختلفة من OTP (تسجيل، دخول، إعادة تعيين)
-   رسائل مخصصة حسب نوع العملية
-   سهولة التخصيص والتطوير

## استكشاف الأخطاء

### 1. مشاكل الإرسال

-   تأكد من صحة Access Token
-   تحقق من Phone Number ID
-   راجع سجلات Laravel للتفاصيل

### 2. مشاكل قاعدة البيانات

-   تأكد من تشغيل Migration
-   تحقق من اتصال قاعدة البيانات

### 3. مشاكل التوجيه

-   تأكد من إضافة المسارات الجديدة
-   تحقق من أسماء المسارات

## التطوير المستقبلي

### 1. إضافة المزيد من الخدمات

-   SMS OTP
-   Email OTP
-   Push Notifications

### 2. تحسينات الأمان

-   تشفير الرموز في قاعدة البيانات
-   تسجيل محاولات الاختراق
-   حماية من Brute Force

### 3. تحسينات الأداء

-   تخزين مؤقت للرموز
-   معالجة غير متزامنة للإرسال
-   تحسين استعلامات قاعدة البيانات
