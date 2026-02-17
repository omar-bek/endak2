<?php

namespace App\Console\Commands;

use App\Events\NotificationSent;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BroadcastPendingNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:broadcast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcast pending notifications that have not been broadcasted yet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for pending notifications...');

        // جلب الإشعارات التي لم يتم بثها بعد
        $pendingNotifications = Notification::whereNull('broadcasted_at')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($pendingNotifications->isEmpty()) {
            $this->info('No pending notifications to broadcast.');
            return 0;
        }

        $this->info("Found {$pendingNotifications->count()} pending notification(s).");

        $broadcasted = 0;
        $failed = 0;

        foreach ($pendingNotifications as $notification) {
            try {
                // بث الإشعار
                broadcast(new NotificationSent($notification));

                // تحديث الحقل broadcasted_at
                $notification->update(['broadcasted_at' => now()]);

                $broadcasted++;
                $this->line("Broadcasted notification ID: {$notification->id}");

                Log::info('Notification broadcasted via cron job', [
                    'notification_id' => $notification->id,
                    'user_id' => $notification->user_id,
                ]);
            } catch (\Exception $e) {
                $failed++;
                $this->error("Failed to broadcast notification ID: {$notification->id} - {$e->getMessage()}");

                Log::error('Failed to broadcast notification via cron job', [
                    'notification_id' => $notification->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Broadcasted: {$broadcasted}, Failed: {$failed}");

        return 0;
    }
}
