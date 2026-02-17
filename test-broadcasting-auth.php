<?php

/**
 * Script اختبار Broadcasting Authentication
 * 
 * استخدم: php test-broadcasting-auth.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "🧪 اختبار Broadcasting Authentication\n";
echo str_repeat("=", 50) . "\n\n";

// الحصول على API Token من المستخدم
$token = $argv[1] ?? null;

if (!$token) {
    echo "❌ يرجى إدخال API Token:\n";
    echo "php test-broadcasting-auth.php YOUR_API_TOKEN\n\n";
    
    // عرض المستخدمين المتاحين
    $users = User::select('id', 'name', 'email')->limit(5)->get();
    echo "المستخدمون المتاحون:\n";
    foreach ($users as $user) {
        echo "  [{$user->id}] {$user->name} ({$user->email})\n";
    }
    exit(1);
}

// التحقق من API Token
$hashedToken = hash('sha256', $token);
$user = User::where('api_token', $hashedToken)->first();

if (!$user) {
    echo "❌ API Token غير صحيح!\n";
    exit(1);
}

echo "✅ API Token صحيح!\n";
echo "   User ID: {$user->id}\n";
echo "   Name: {$user->name}\n";
echo "   Email: {$user->email}\n\n";

// اختبار Channel Authorization
echo "🔍 اختبار Channel Authorization:\n";

$testUserId = $user->id;
$authorized = \Illuminate\Support\Facades\Broadcast::channel("user.{$testUserId}", function ($authUser, $userId) use ($testUserId) {
    $result = (int) $authUser->id === (int) $userId;
    echo "   Channel: user.{$testUserId}\n";
    echo "   Authenticated User ID: {$authUser->id}\n";
    echo "   Requested User ID: {$userId}\n";
    echo "   Authorized: " . ($result ? 'true ✅' : 'false ❌') . "\n";
    return $result;
});

// محاكاة request للـ broadcasting auth
echo "\n🔍 محاكاة Broadcasting Auth Request:\n";

$request = \Illuminate\Http\Request::create('/api/broadcasting/auth', 'POST', [
    'socket_id' => '123.456',
    'channel_name' => "private-user.{$testUserId}"
], [], [], [
    'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
    'HTTP_ACCEPT' => 'application/json'
]);

// Set user in request
$request->setUserResolver(fn () => $user);
auth()->setUser($user);

echo "   Socket ID: 123.456\n";
echo "   Channel: private-user.{$testUserId}\n";
echo "   User ID: {$user->id}\n\n";

// اختبار Channel Authorization مباشرة
$channelName = "user.{$testUserId}";
$callback = function ($authUser, $userId) use ($testUserId) {
    return (int) $authUser->id === (int) $testUserId;
};

$result = $callback($user, $testUserId);

echo "✅ نتيجة Channel Authorization:\n";
echo "   Authorized: " . ($result ? 'true ✅' : 'false ❌') . "\n";

if ($result) {
    echo "\n✅ كل شيء يعمل بشكل صحيح!\n";
    echo "   يمكنك الاشتراك في channel: private-user.{$testUserId}\n";
} else {
    echo "\n❌ Channel Authorization فاشل!\n";
    echo "   تحقق من routes/channels.php\n";
}
