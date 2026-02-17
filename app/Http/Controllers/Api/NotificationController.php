<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class NotificationController extends BaseApiController
{
    public function index(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            $notifications = Notification::query()
                ->where('user_id', $request->user()->id)
                ->latest()
                ->paginate($request->get('per_page', 20));

            return $this->success([
                'notifications' => $notifications,
                'unread_count' => Notification::getUnreadCountForUser($request->user()->id),
            ]);
        }, 'حدث خطأ أثناء جلب الإشعارات');
    }

    public function markAsRead(Notification $notification, Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($notification, $request) {
            $this->authorizeNotificationOwner($notification, $request->user()->id);
            $notification->markAsRead();

            return $this->success($notification->fresh(), 'تم تعليم الإشعار كمقروء');
        }, 'حدث خطأ أثناء تحديث الإشعار');
    }

    public function markAllAsRead(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            Notification::query()
                ->where('user_id', $request->user()->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            Log::info('API All notifications marked as read', [
                'user_id' => $request->user()->id
            ]);

            return $this->success(null, 'تم تعليم جميع الإشعارات كمقروءة');
        }, 'حدث خطأ أثناء تحديث الإشعارات');
    }

    public function destroy(Notification $notification, Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($notification, $request) {
            $this->authorizeNotificationOwner($notification, $request->user()->id);
            $notification->delete();

            Log::info('API Notification deleted', [
                'notification_id' => $notification->id,
                'user_id' => $request->user()->id
            ]);

            return $this->success(null, 'تم حذف الإشعار بنجاح');
        }, 'حدث خطأ أثناء حذف الإشعار');
    }

    private function authorizeNotificationOwner(Notification $notification, int $userId): void
    {
        if ($notification->user_id !== $userId) {
            abort(403, 'لا يمكنك تنفيذ هذا الإجراء');
        }
    }
}








