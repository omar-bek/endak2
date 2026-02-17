<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Service;
use App\Models\ServiceOffer;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Exception;

class MessageController extends Controller
{
    /**
     * عرض قائمة المحادثات
     */
    public function index()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول');
            }

            // الحصول على جميع المحادثات للمستخدم
            $conversations = Message::where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
                ->where('is_deleted', false)
                ->with(['sender', 'receiver', 'service'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('conversation_id')
                ->map(function ($messages) {
                    return $messages->first();
                });

            return view('messages.new_design', compact('conversations'));
        } catch (Exception $e) {
            Log::error('Error in MessageController@index: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل المحادثات');
        }
    }

    /**
     * عرض قائمة المحادثات بالتصميم الجديد
     */
    public function newDesign()
    {
        $user = Auth::user();

        // الحصول على جميع المحادثات للمستخدم
        $conversations = Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->where('is_deleted', false)
            ->with(['sender', 'receiver', 'service'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('conversation_id')
            ->map(function ($messages) {
                return $messages->first();
            });

        return view('messages.new_design', compact('conversations'));
    }

    /**
     * عرض محادثة مع مستخدم معين
     */
    public function show($userId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول');
            }

            $otherUser = User::findOrFail($userId);

            // التحقق من أن المستخدم لا يمكنه محادثة نفسه
            if ($user->id === $otherUser->id) {
                return redirect()->back()->with('error', 'لا يمكنك محادثة نفسك');
            }

            // إنشاء معرف المحادثة
            $conversationId = Message::generateConversationId($user->id, $otherUser->id);

            // الحصول على الرسائل بين المستخدمين
            $messages = Message::where('conversation_id', $conversationId)
                ->where('is_deleted', false)
                ->with(['sender', 'receiver', 'service', 'serviceOffer', 'replyTo'])
                ->orderBy('created_at', 'asc')
                ->get();

            // تحديد الرسائل كمقروءة
            $messages->where('receiver_id', $user->id)->each(function ($message) {
                $message->markAsRead();
            });

            // الحصول على جميع المحادثات للمستخدم (للعرض في الشريط الجانبي)
            $conversations = Message::where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
                ->where('is_deleted', false)
                ->with(['sender', 'receiver', 'service'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('conversation_id')
                ->map(function ($messages) {
                    return $messages->first();
                });

            return view('messages.show', compact('messages', 'otherUser', 'conversationId', 'conversations'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'المستخدم غير موجود');
        } catch (Exception $e) {
            Log::error('Error in MessageController@show: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $userId
            ]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل المحادثة');
        }
    }

    /**
     * إرسال رسالة
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'receiver_id' => 'required|exists:users,id',
                'content' => 'nullable|string|max:2000',
                'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip,rar|max:10240',
                'voice_note' => 'nullable|file|mimes:mp3,wav,m4a,ogg|max:10240',
                'location' => 'nullable|array',
                'contact' => 'nullable|array',
                'reply_to_message_id' => 'nullable|exists:messages,id',
                'service_id' => 'nullable|exists:services,id',
                'service_offer_id' => 'nullable|exists:service_offers,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول'
                ], 401);
            }

            // التحقق من أن المستخدم لا يرسل لنفسه
            if ($user->id === $request->receiver_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكنك إرسال رسالة لنفسك'
                ], 400);
            }

            $messageData = [
                'sender_id' => $user->id,
                'receiver_id' => $request->receiver_id,
                'reply_to_message_id' => $request->reply_to_message_id,
                'service_id' => $request->service_id ?: null,
                'service_offer_id' => $request->service_offer_id ?: null,
            ];

            // تحديد نوع الرسالة والمحتوى
            if ($request->hasFile('media')) {
                $file = $request->file('media');
                $path = $file->store('messages/media', 'public');
                $messageData['media_path'] = $path;
                $messageData['message_type'] = $this->getFileType($file->getMimeType());
                $messageData['file_name'] = $file->getClientOriginalName();
                $messageData['file_size'] = $file->getSize();

                // إضافة metadata للملف
                $messageData['metadata'] = [
                    'file_size' => $file->getSize(),
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType()
                ];
            } elseif ($request->hasFile('voice_note')) {
                $file = $request->file('voice_note');
                $path = $file->store('messages/voice', 'public');
                $messageData['voice_note_path'] = $path;
                $messageData['message_type'] = 'voice';
                $messageData['file_name'] = $file->getClientOriginalName();
                $messageData['file_size'] = $file->getSize();

                // إضافة metadata للرسالة الصوتية
                $messageData['metadata'] = [
                    'file_size' => $file->getSize(),
                    'file_name' => $file->getClientOriginalName(),
                    'duration' => $request->duration ?? 0
                ];
            } elseif ($request->has('voice_note_data') && !empty($request->voice_note_data)) {
                // معالجة الرسالة الصوتية من base64
                $voiceData = $request->voice_note_data;
                if (strpos($voiceData, 'data:audio') === 0) {
                    $voiceData = substr($voiceData, strpos($voiceData, ',') + 1);
                    $voiceData = base64_decode($voiceData);

                    $fileName = 'voice_' . time() . '.wav';
                    $path = 'messages/voice/' . $fileName;

                    Storage::disk('public')->put($path, $voiceData);

                    $messageData['voice_note_path'] = $path;
                    $messageData['message_type'] = 'voice';
                    $messageData['file_name'] = $fileName;
                    $messageData['file_size'] = strlen($voiceData);

                    // إضافة metadata للرسالة الصوتية
                    $messageData['metadata'] = [
                        'file_size' => strlen($voiceData),
                        'file_name' => $fileName,
                        'duration' => $request->duration ?? 0
                    ];
                }
            } elseif ($request->has('location')) {
                $messageData['message_type'] = 'location';
                $messageData['metadata'] = [
                    'location' => $request->location
                ];
            } elseif ($request->has('contact')) {
                $messageData['message_type'] = 'contact';
                $messageData['metadata'] = [
                    'contact' => $request->contact
                ];
            } else {
                $messageData['content'] = $request->input('content');
                $messageData['message_type'] = 'text';
            }

            $message = Message::create($messageData);
            $message->load(['sender', 'receiver', 'service', 'serviceOffer', 'replyTo']);

            // بث الحدث للـ real-time
            try {
                broadcast(new MessageSent($message))->toOthers();
            } catch (Exception $e) {
                Log::warning('Failed to broadcast message', [
                    'message_id' => $message->id,
                    'error' => $e->getMessage()
                ]);
            }

            // إنشاء إشعار للمستقبل
            try {
                \App\Models\Notification::createNotification(
                    $request->receiver_id,
                    'message_received',
                    'رسالة جديدة',
                    'لديك رسالة جديدة من ' . $user->name,
                    [
                        'message_id' => $message->id,
                        'sender_id' => $user->id,
                        'conversation_id' => $message->conversation_id,
                    ]
                );
            } catch (Exception $e) {
                Log::warning('Failed to create notification for message', [
                    'message_id' => $message->id,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('Message sent', [
                'message_id' => $message->id,
                'sender_id' => $user->id,
                'receiver_id' => $request->receiver_id
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'html' => view('messages.partials.message', compact('message'))->render()
            ]);
        } catch (Exception $e) {
            Log::error('Error in MessageController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->except(['media', 'voice_note'])
            ]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال الرسالة'
            ], 500);
        }
    }


    public function getNewMessages(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $conversationId = $request->input('conversation_id') ?? $request->conversation_id;
            $lastMessageId = $request->input('last_message_id') ?? $request->last_message_id ?? 0;

            if (!$conversationId) {
                return response()->json([
                    'success' => false,
                    'message' => 'conversation_id is required'
                ], 400);
            }

            $messages = Message::where('conversation_id', $conversationId)
                ->where('id', '>', $lastMessageId)
                ->where('is_deleted', false)
                ->with(['sender', 'receiver', 'service', 'serviceOffer', 'replyTo'])
                ->orderBy('created_at', 'asc')
                ->get();

            // تحديد الرسائل كمقروءة
            $messages->where('receiver_id', $user->id)->each(function ($message) {
                if (!$message->is_read) {
                    $message->markAsRead();
                }
            });

            $html = '';
            foreach ($messages as $message) {
                $html .= view('messages.partials.message', compact('message'))->render();
            }

            return response()->json([
                'success' => true,
                'messages' => $messages->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'sender_id' => $message->sender_id,
                        'receiver_id' => $message->receiver_id,
                        'content' => $message->content,
                        'message_type' => $message->message_type,
                        'created_at' => $message->created_at->toDateTimeString(),
                        'formatted_time' => $message->formatted_time,
                    ];
                }),
                'html' => $html,
                'last_message_id' => $messages->max('id') ?? $lastMessageId
            ]);
        } catch (Exception $e) {
            Log::error('Error in MessageController@getNewMessages: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الرسائل'
            ], 500);
        }
    }

    /**
     * حذف رسالة
     */
    public function destroy($id): JsonResponse
    {
        $message = Message::findOrFail($id);
        $user = Auth::user();

        // التحقق من أن المستخدم هو مرسل الرسالة
        if ($message->sender_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكنك حذف رسالة لم ترسلها'
            ], 403);
        }

        // حذف الملفات المرتبطة
        if ($message->media_path) {
            Storage::disk('public')->delete($message->media_path);
        }
        if ($message->voice_note_path) {
            Storage::disk('public')->delete($message->voice_note_path);
        }

        $message->softDelete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الرسالة بنجاح'
        ]);
    }

    /**
     * عرض محادثة مرتبطة بخدمة
     */
    public function serviceConversation($serviceId)
    {
        $user = Auth::user();
        $service = Service::with('user')->findOrFail($serviceId);

        // التحقق من أن المستخدم إما صاحب الخدمة أو طالبها
        if ($service->user_id !== $user->id && $service->requested_by !== $user->id) {
            return redirect()->back()->with('error', 'لا يمكنك الوصول لهذه المحادثة');
        }

        $otherUser = $service->user_id === $user->id ?
            User::find($service->requested_by) :
            $service->user;

        return $this->show($otherUser->id);
    }

    /**
     * عرض محادثة مرتبطة بعرض
     */
    public function offerConversation($offerId)
    {
        $user = Auth::user();
        $offer = ServiceOffer::with(['service.user', 'provider'])->findOrFail($offerId);

        // التحقق من أن المستخدم إما صاحب الخدمة أو مزود العرض
        if ($offer->service->user_id !== $user->id && $offer->provider_id !== $user->id) {
            return redirect()->back()->with('error', 'لا يمكنك الوصول لهذه المحادثة');
        }

        $otherUser = $offer->service->user_id === $user->id ?
            $offer->provider :
            $offer->service->user;

        return $this->show($otherUser->id);
    }

    /**
     * الحصول على عدد الرسائل غير المقروءة
     */
    public function getUnreadCount(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'count' => 0
                ], 401);
            }

            $count = Message::where('receiver_id', $user->id)
                ->where('is_read', false)
                ->where('is_deleted', false)
                ->count();

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (Exception $e) {
            Log::error('Error in MessageController@getUnreadCount: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }

    /**
     * تحديد جميع الرسائل كمقروءة
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = Auth::user();
        $conversationId = $request->conversation_id;

        Message::where('conversation_id', $conversationId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد جميع الرسائل كمقروءة'
        ]);
    }

    /**
     * البحث في الرسائل
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        $query = $request->get('q');

        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->where('is_deleted', false)
            ->where(function ($q) use ($query) {
                $q->where('content', 'like', "%{$query}%")
                    ->orWhereHas('sender', function ($senderQuery) use ($query) {
                        $senderQuery->where('name', 'like', "%{$query}%");
                    })
                    ->orWhereHas('receiver', function ($receiverQuery) use ($query) {
                        $receiverQuery->where('name', 'like', "%{$query}%");
                    });
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('messages.search', compact('messages', 'query'));
    }

    /**
     * تحديد نوع الملف
     */
    protected function getFileType($mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        return 'file';
    }
}
