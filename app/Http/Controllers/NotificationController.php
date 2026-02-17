<?php

namespace App\Http\Controllers;

use App\Models\Notification as CustomNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class NotificationController extends Controller
{
    /**
     * عرض جميع إشعارات المستخدم
     */
    public function index()
    {
        try {
            $notifications = CustomNotification::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('notifications.index', compact('notifications'));
        } catch (Exception $e) {
            Log::error('Error in NotificationController@index: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الإشعارات');
        }
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markAsRead($id)
    {
        try {
            $notification = CustomNotification::where('user_id', Auth::id())
                ->findOrFail($id);
            $notification->markAsRead();

            return response()->json(['success' => true]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'الإشعار غير موجود'], 404);
        } catch (Exception $e) {
            Log::error('Error in NotificationController@markAsRead: ' . $e->getMessage(), [
                'exception' => $e,
                'notification_id' => $id
            ]);
            return response()->json(['success' => false, 'message' => 'حدث خطأ'], 500);
        }
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllAsRead()
    {
        try {
            CustomNotification::where('user_id', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            Log::error('Error in NotificationController@markAllAsRead: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json(['success' => false, 'message' => 'حدث خطأ'], 500);
        }
    }


    /**
     * حذف إشعار
     */
    public function destroy($id)
    {
        try {
            $notification = CustomNotification::where('user_id', Auth::id())
                ->findOrFail($id);
            $notification->delete();

            return redirect()->back()->with('success', 'تم حذف الإشعار بنجاح');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'الإشعار غير موجود');
        } catch (Exception $e) {
            Log::error('Error in NotificationController@destroy: ' . $e->getMessage(), [
                'exception' => $e,
                'notification_id' => $id
            ]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الإشعار');
        }
    }

    /**
     * الحصول على الإشعارات غير المقروءة (لـ AJAX)
     */
    public function getUnread()
    {
        // إذا كان الطلب مباشراً من المتصفح (ليس AJAX)، قم بإعادة التوجيه
        if (!request()->ajax() && !request()->wantsJson()) {
            return redirect()->route('notifications.index');
        }

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['notifications' => [], 'count' => 0]);
            }

            // استخدام نموذج Notification مباشرة للحصول على الإشعارات غير المقروءة
            $notifications = CustomNotification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'data' => $notification->data
                    ];
                });

            $count = CustomNotification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->count();

            return response()->json([
                'notifications' => $notifications,
                'count' => $count
            ]);
        } catch (Exception $e) {
            Log::error('Error in NotificationController@getUnread: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json(['notifications' => [], 'count' => 0]);
        }
    }
}
