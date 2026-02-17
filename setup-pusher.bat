@echo off
REM Script لإعداد Pusher و Broadcasting في Laravel (Windows)

echo 🚀 بدء إعداد Pusher و Broadcasting...

REM التحقق من وجود .env
if not exist .env (
    echo ❌ ملف .env غير موجود. يرجى نسخ .env.example إلى .env أولاً
    exit /b 1
)

REM التحقق من وجود جدول jobs
echo 📋 التحقق من جدول jobs...
php artisan migrate --path=database/migrations/0001_01_01_000002_create_jobs_table.php

echo.
echo ⚙️  يرجى إضافة إعدادات Pusher التالية إلى ملف .env يدوياً:
echo.
echo # Broadcasting Settings
echo BROADCAST_CONNECTION=pusher
echo.
echo # Pusher Settings
echo PUSHER_APP_ID=your-app-id
echo PUSHER_APP_KEY=your-app-key
echo PUSHER_APP_SECRET=your-app-secret
echo PUSHER_APP_CLUSTER=mt1
echo PUSHER_HOST=
echo PUSHER_PORT=443
echo PUSHER_SCHEME=https
echo.
echo # Queue Settings (مطلوب للـ Broadcasting)
echo QUEUE_CONNECTION=database
echo.

echo ✅ تم إعداد Pusher بنجاح!
echo.
echo 📝 الخطوات التالية:
echo 1. افتح ملف .env وأضف بيانات Pusher الخاصة بك
echo 2. شغل Queue Worker: php artisan queue:work
echo 3. اختبر الإشعارات باستخدام tinker
echo.

pause
