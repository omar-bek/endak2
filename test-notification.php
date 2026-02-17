<?php

/**
 * Script اختبار سريع للإشعارات
 * 
 * استخدم: php test-notification.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

echo "🧪 اختبار إنشاء إشعار...\n\n";

try {
    // إنشاء إشعار تجريبي
    $notification = Notification::create([
        'user_id' => 1, // غيّر هذا إلى user_id موجود
        'type' => 'system',
        'title' => 'اختبار Realtime',
        'message' => 'هذا إشعار تجريبي للتحقق من Broadcasting',
        'data' => ['test' => true, 'timestamp' => now()->toDateTimeString()]
    ]);
    
    echo "✅ تم إنشاء الإشعار بنجاح!\n";
    echo "   ID: {$notification->id}\n";
    echo "   User ID: {$notification->user_id}\n";
    echo "   Type: {$notification->type}\n";
    echo "   Title: {$notification->title}\n\n";
    
    echo "📡 Event يجب أن يكون تم dispatch تلقائياً...\n";
    echo "   تحقق من Logs: storage/logs/laravel.log\n";
    echo "   تحقق من Queue: php artisan queue:work\n";
    echo "   تحقق من Pusher Dashboard\n\n";
    
    echo "⏳ انتظر قليلاً ثم تحقق من:\n";
    echo "   1. Queue Worker يعمل\n";
    echo "   2. Pusher Dashboard - Debug Console\n";
    echo "   3. Frontend - يجب أن يستلم الإشعار\n";
    
} catch (\Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
