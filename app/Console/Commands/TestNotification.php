<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Console\Command;

class TestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:test {user_id?} {--type=system} {--title=اختبار} {--message=هذا إشعار تجريبي}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إرسال إشعار تجريبي عبر Pusher';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        // إذا لم يتم تحديد user_id، نسأل المستخدم
        if (!$userId) {
            $users = User::select('id', 'name', 'email')->limit(10)->get();
            
            if ($users->isEmpty()) {
                $this->error('لا يوجد مستخدمين في قاعدة البيانات');
                return 1;
            }
            
            $this->info('اختر مستخدم لإرسال الإشعار له:');
            foreach ($users as $user) {
                $this->line("  [{$user->id}] {$user->name} ({$user->email})");
            }
            
            $userId = $this->ask('أدخل معرف المستخدم');
        }
        
        // التحقق من وجود المستخدم
        $user = User::find($userId);
        if (!$user) {
            $this->error("المستخدم برقم {$userId} غير موجود");
            return 1;
        }
        
        $type = $this->option('type');
        $title = $this->option('title');
        $message = $this->option('message');
        
        $this->info("إرسال إشعار تجريبي...");
        $this->line("المستخدم: {$user->name} ({$user->email})");
        $this->line("النوع: {$type}");
        $this->line("العنوان: {$title}");
        $this->line("الرسالة: {$message}");
        
        try {
            $notification = Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => [
                    'test' => true,
                    'sent_at' => now()->toDateTimeString(),
                ],
            ]);
            
            $this->info("✅ تم إرسال الإشعار بنجاح!");
            $this->line("معرف الإشعار: {$notification->id}");
            $this->line("سيتم إرسال الإشعار عبر Pusher تلقائياً إذا كان Queue Worker يعمل");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ حدث خطأ أثناء إرسال الإشعار: " . $e->getMessage());
            return 1;
        }
    }
}
