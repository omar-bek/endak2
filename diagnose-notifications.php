<?php

/**
 * Script تشخيص شامل للإشعارات Realtime
 * 
 * استخدم: php diagnose-notifications.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

echo "🔍 تشخيص نظام الإشعارات Realtime\n";
echo str_repeat("=", 50) . "\n\n";

// 1. التحقق من إعدادات Broadcasting
echo "1️⃣  التحقق من إعدادات Broadcasting:\n";
$broadcastConnection = Config::get('broadcasting.default');
echo "   Default Connection: {$broadcastConnection}\n";

if ($broadcastConnection === 'pusher') {
    $pusherConfig = Config::get('broadcasting.connections.pusher');
    echo "   ✅ Pusher مضبوط\n";
    echo "   App ID: " . ($pusherConfig['app_id'] ?? 'غير موجود') . "\n";
    echo "   Key: " . (substr($pusherConfig['key'] ?? '', 0, 10) . '...') . "\n";
    echo "   Cluster: " . ($pusherConfig['options']['cluster'] ?? 'غير موجود') . "\n";
} else {
    echo "   ❌ Broadcasting Connection ليس pusher: {$broadcastConnection}\n";
    echo "   يجب أن يكون: BROADCAST_CONNECTION=pusher في .env\n";
}

echo "\n";

// 2. التحقق من Queue Connection
echo "2️⃣  التحقق من Queue Connection:\n";
$queueConnection = Config::get('queue.default');
echo "   Default Connection: {$queueConnection}\n";

if ($queueConnection === 'database' || $queueConnection === 'redis') {
    echo "   ✅ Queue Connection مضبوط: {$queueConnection}\n";
} else {
    echo "   ⚠️  Queue Connection: {$queueConnection}\n";
    echo "   يُنصح باستخدام 'database' أو 'redis'\n";
}

// التحقق من وجود جدول jobs
if ($queueConnection === 'database') {
    try {
        $jobsTableExists = DB::getSchemaBuilder()->hasTable('jobs');
        if ($jobsTableExists) {
            $jobsCount = DB::table('jobs')->count();
            echo "   ✅ جدول jobs موجود ({$jobsCount} jobs في الانتظار)\n";
        } else {
            echo "   ❌ جدول jobs غير موجود! قم بتشغيل: php artisan migrate\n";
        }
    } catch (\Exception $e) {
        echo "   ⚠️  لا يمكن التحقق من جدول jobs: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// 3. التحقق من Notification Model
echo "3️⃣  التحقق من Notification Model:\n";
$reflection = new ReflectionClass(Notification::class);
$property = $reflection->getProperty('dispatchesEvents');
$property->setAccessible(true);
$dispatchesEvents = $property->getValue(new Notification());

if (isset($dispatchesEvents['created'])) {
    echo "   ✅ Event مضبوط: {$dispatchesEvents['created']}\n";
} else {
    echo "   ❌ Event غير مضبوط في Notification Model!\n";
}

echo "\n";

// 4. التحقق من Event Class
echo "4️⃣  التحقق من NotificationSent Event:\n";
if (class_exists(\App\Events\NotificationSent::class)) {
    $reflection = new ReflectionClass(\App\Events\NotificationSent::class);
    $interfaces = $reflection->getInterfaceNames();
    
    if (in_array('Illuminate\Contracts\Broadcasting\ShouldBroadcast', $interfaces)) {
        echo "   ✅ Event يستخدم ShouldBroadcast\n";
    } else {
        echo "   ❌ Event لا يستخدم ShouldBroadcast!\n";
    }
} else {
    echo "   ❌ NotificationSent Event غير موجود!\n";
}

echo "\n";

// 5. التحقق من Channel Authorization
echo "5️⃣  التحقق من Channel Authorization:\n";
$channelsFile = __DIR__ . '/routes/channels.php';
if (file_exists($channelsFile)) {
    $channelsContent = file_get_contents($channelsFile);
    if (strpos($channelsContent, "user.{userId}") !== false) {
        echo "   ✅ Channel 'user.{userId}' موجود في routes/channels.php\n";
    } else {
        echo "   ❌ Channel 'user.{userId}' غير موجود!\n";
    }
} else {
    echo "   ❌ ملف routes/channels.php غير موجود!\n";
}

echo "\n";

// 6. التحقق من Broadcasting Route
echo "6️⃣  التحقق من Broadcasting Route:\n";
try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $broadcastingRouteExists = false;
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'broadcasting/auth') !== false) {
            $broadcastingRouteExists = true;
            break;
        }
    }
    
    if ($broadcastingRouteExists) {
        echo "   ✅ Broadcasting auth route موجود\n";
    } else {
        echo "   ❌ Broadcasting auth route غير موجود!\n";
        echo "   يجب إضافة: Broadcast::routes(['middleware' => ['api', 'api.token']]);\n";
    }
} catch (\Exception $e) {
    echo "   ⚠️  لا يمكن التحقق من Routes: " . $e->getMessage() . "\n";
}

echo "\n";

// 7. اختبار إنشاء إشعار
echo "7️⃣  اختبار إنشاء إشعار:\n";
try {
    $testUser = User::first();
    if ($testUser) {
        echo "   استخدام User ID: {$testUser->id} ({$testUser->name})\n";
        
        // تفعيل Logging
        Log::info('=== بدء اختبار الإشعار ===');
        
        $notification = Notification::create([
            'user_id' => $testUser->id,
            'type' => 'system',
            'title' => 'اختبار التشخيص',
            'message' => 'هذا إشعار تجريبي من script التشخيص',
            'data' => ['diagnostic' => true, 'timestamp' => now()->toDateTimeString()]
        ]);
        
        echo "   ✅ تم إنشاء الإشعار بنجاح (ID: {$notification->id})\n";
        echo "   📝 تحقق من Logs للبحث عن:\n";
        echo "      - 'Creating notification'\n";
        echo "      - 'Notification created, event should be dispatched'\n";
        echo "      - 'NotificationSent Event created'\n";
        echo "      - 'NotificationSent broadcasting on channel'\n";
        
        // التحقق من وجود job في queue
        if ($queueConnection === 'database') {
            sleep(1); // انتظر قليلاً
            $jobsCount = DB::table('jobs')->where('queue', 'default')->count();
            if ($jobsCount > 0) {
                echo "   ✅ يوجد {$jobsCount} job(s) في Queue\n";
                echo "   ⚠️  تأكد من أن Queue Worker يعمل: php artisan queue:work\n";
            } else {
                echo "   ⚠️  لا يوجد jobs في Queue\n";
                echo "   قد يكون Event تم إرساله مباشرة أو فشل\n";
            }
        }
        
    } else {
        echo "   ❌ لا يوجد مستخدمين في قاعدة البيانات!\n";
    }
} catch (\Exception $e) {
    echo "   ❌ خطأ أثناء إنشاء الإشعار: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n";

// 8. ملخص والتوصيات
echo "📋 ملخص والتوصيات:\n";
echo str_repeat("-", 50) . "\n";

$issues = [];
if ($broadcastConnection !== 'pusher') {
    $issues[] = "BROADCAST_CONNECTION يجب أن يكون 'pusher'";
}
if ($queueConnection !== 'database' && $queueConnection !== 'redis') {
    $issues[] = "QUEUE_CONNECTION يجب أن يكون 'database' أو 'redis'";
}

if (empty($issues)) {
    echo "✅ كل الإعدادات تبدو صحيحة!\n\n";
    echo "🔧 الخطوات التالية:\n";
    echo "   1. تأكد من أن Queue Worker يعمل:\n";
    echo "      php artisan queue:work\n\n";
    echo "   2. اختبر الإشعارات:\n";
    echo "      php artisan notification:test\n\n";
    echo "   3. راقب Logs:\n";
    echo "      Get-Content storage/logs/laravel.log -Tail 50 -Wait\n\n";
    echo "   4. تحقق من Pusher Dashboard:\n";
    echo "      https://dashboard.pusher.com -> Debug Console\n\n";
    echo "   5. تأكد من Frontend:\n";
    echo "      - Laravel Echo متصل\n";
    echo "      - الاشتراك في channel: private-user.{userId}\n";
    echo "      - الاستماع للحدث: .notification.sent\n";
} else {
    echo "⚠️  المشاكل المكتشفة:\n";
    foreach ($issues as $issue) {
        echo "   - {$issue}\n";
    }
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "✅ انتهى التشخيص\n";
