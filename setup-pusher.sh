#!/bin/bash

# Script لإعداد Pusher و Broadcasting في Laravel

echo "🚀 بدء إعداد Pusher و Broadcasting..."

# التحقق من وجود .env
if [ ! -f .env ]; then
    echo "❌ ملف .env غير موجود. يرجى نسخ .env.example إلى .env أولاً"
    exit 1
fi

# التحقق من وجود جدول jobs
echo "📋 التحقق من جدول jobs..."
php artisan migrate --path=database/migrations/0001_01_01_000002_create_jobs_table.php

# إضافة إعدادات Pusher إلى .env إذا لم تكن موجودة
echo "⚙️  إضافة إعدادات Pusher إلى .env..."

if ! grep -q "BROADCAST_CONNECTION" .env; then
    echo "" >> .env
    echo "# Broadcasting Settings" >> .env
    echo "BROADCAST_CONNECTION=pusher" >> .env
    echo "" >> .env
    echo "# Pusher Settings" >> .env
    echo "PUSHER_APP_ID=" >> .env
    echo "PUSHER_APP_KEY=" >> .env
    echo "PUSHER_APP_SECRET=" >> .env
    echo "PUSHER_APP_CLUSTER=mt1" >> .env
    echo "PUSHER_HOST=" >> .env
    echo "PUSHER_PORT=443" >> .env
    echo "PUSHER_SCHEME=https" >> .env
    echo "" >> .env
    echo "# Queue Settings (مطلوب للـ Broadcasting)" >> .env
    echo "QUEUE_CONNECTION=database" >> .env
    echo "✅ تم إضافة إعدادات Pusher إلى .env"
else
    echo "ℹ️  إعدادات Pusher موجودة بالفعل في .env"
fi

# التحقق من Queue Connection
if ! grep -q "QUEUE_CONNECTION=database" .env && ! grep -q "QUEUE_CONNECTION=redis" .env; then
    echo "⚠️  تحذير: QUEUE_CONNECTION غير مضبوط. يرجى تعيينه إلى 'database' أو 'redis'"
fi

echo ""
echo "✅ تم إعداد Pusher بنجاح!"
echo ""
echo "📝 الخطوات التالية:"
echo "1. افتح ملف .env وأضف بيانات Pusher الخاصة بك:"
echo "   - PUSHER_APP_ID"
echo "   - PUSHER_APP_KEY"
echo "   - PUSHER_APP_SECRET"
echo "   - PUSHER_APP_CLUSTER"
echo ""
echo "2. شغل Queue Worker:"
echo "   php artisan queue:work"
echo ""
echo "3. اختبر الإشعارات:"
echo "   php artisan tinker"
echo "   >>> use App\Models\Notification;"
echo "   >>> Notification::create(['user_id' => 1, 'type' => 'system', 'title' => 'اختبار', 'message' => 'هذا إشعار تجريبي']);"
echo ""
