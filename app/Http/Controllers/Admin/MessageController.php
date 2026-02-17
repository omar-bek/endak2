<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * عرض جميع المحادثات
     */
    public function index(Request $request)
    {
        $query = Message::with(['sender:id,name,email,avatar', 'receiver:id,name,email,avatar'])
            ->latest();

        // البحث
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                    ->orWhereHas('sender', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('receiver', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // فلترة حسب نوع الرسالة
        if ($request->filled('message_type')) {
            $query->where('message_type', $request->get('message_type'));
        }

        // فلترة حسب حالة القراءة
        if ($request->filled('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }

        // فلترة حسب التاريخ
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $messages = $query->paginate(20);

        // إحصائيات
        $stats = [
            'total_messages' => Message::count(),
            'unread_messages' => Message::where('is_read', false)->count(),
            'text_messages' => Message::where('message_type', 'text')->count(),
            'image_messages' => Message::where('message_type', 'image')->count(),
            'voice_messages' => Message::where('message_type', 'voice')->count(),
            'file_messages' => Message::where('message_type', 'file')->count(),
        ];

        // المحادثات النشطة (آخر 10 محادثات)
        $activeConversations = Message::query()
            ->select('conversation_id')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->selectRaw('COUNT(*) as message_count')
            ->groupBy('conversation_id')
            ->orderBy('last_message_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.messages.index', compact('messages', 'stats', 'activeConversations'));
    }

    /**
     * عرض تفاصيل محادثة معينة
     */
    public function show($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)
            ->with(['sender:id,name,email,avatar', 'receiver:id,name,email,avatar', 'service', 'serviceOffer'])
            ->orderBy('created_at', 'asc')
            ->get();

        if ($messages->isEmpty()) {
            return redirect()->route('admin.messages.index')
                ->with('error', 'المحادثة غير موجودة');
        }

        // الحصول على معلومات المحادثة
        $firstMessage = $messages->first();
        $participants = [
            'sender' => $firstMessage->sender,
            'receiver' => $firstMessage->receiver,
        ];

        return view('admin.messages.show', compact('messages', 'participants', 'conversationId'));
    }

    /**
     * حذف رسالة
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return redirect()->back()
            ->with('success', 'تم حذف الرسالة بنجاح');
    }

    /**
     * حذف محادثة كاملة
     */
    public function destroyConversation($conversationId)
    {
        Message::where('conversation_id', $conversationId)->delete();

        return redirect()->route('admin.messages.index')
            ->with('success', 'تم حذف المحادثة بنجاح');
    }
}
