<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Exception;

class MessageController extends BaseApiController
{
    public function index(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            $userId = $request->user()->id;

            $latestMessages = Message::query()
                ->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->orWhere('receiver_id', $userId);
                })
                ->latest()
                ->with(['sender:id,name,avatar', 'receiver:id,name,avatar'])
                ->get()
                ->unique('conversation_id')
                ->values();

            return $this->success($latestMessages);
        }, 'حدث خطأ أثناء جلب المحادثات');
    }

    public function show(Request $request, User $user)
    {
        return $this->executeApiWithTryCatch(function () use ($request, $user) {
            $currentUserId = $request->user()->id;

            $messages = Message::query()
                ->where(function ($query) use ($currentUserId, $user) {
                    $query->where(function ($inner) use ($currentUserId, $user) {
                        $inner->where('sender_id', $currentUserId)
                            ->where('receiver_id', $user->id);
                    })->orWhere(function ($inner) use ($currentUserId, $user) {
                        $inner->where('sender_id', $user->id)
                            ->where('receiver_id', $currentUserId);
                    });
                })
                ->latest()
                ->limit(100)
                ->get()
                ->sortBy('created_at')
                ->values();

            Message::query()
                ->where('receiver_id', $currentUserId)
                ->where('sender_id', $user->id)
                ->whereNull('read_at')
                ->update(['is_read' => true, 'read_at' => now()]);

            return $this->success([
                'partner' => $user->only(['id', 'name', 'avatar']),
                'messages' => $messages,
            ]);
        }, 'حدث خطأ أثناء جلب المحادثة');
    }

    public function store(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            $data = $request->validate([
                'receiver_id' => ['required', 'exists:users,id', Rule::notIn([$request->user()->id])],
                'content' => ['required', 'string'],
                'service_id' => ['nullable', 'exists:services,id'],
                'service_offer_id' => ['nullable', 'exists:service_offers,id'],
            ]);

            $message = Message::create([
                'sender_id' => $request->user()->id,
                'receiver_id' => $data['receiver_id'],
                'content' => $data['content'],
                'message_type' => 'text',
                'service_id' => $data['service_id'] ?? null,
                'service_offer_id' => $data['service_offer_id'] ?? null,
            ]);

            Log::info('API Message sent', [
                'message_id' => $message->id,
                'sender_id' => $request->user()->id,
                'receiver_id' => $data['receiver_id']
            ]);

            return $this->success($message->load(['sender:id,name,avatar', 'receiver:id,name,avatar']), 'تم إرسال الرسالة بنجاح', 201);
        }, 'حدث خطأ أثناء إرسال الرسالة');
    }

    public function destroy(Message $message, Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($message, $request) {
            if ($message->sender_id !== $request->user()->id) {
                return $this->error('لا يمكنك حذف هذه الرسالة', 403);
            }

            $message->softDelete();

            Log::info('API Message deleted', [
                'message_id' => $message->id,
                'user_id' => $request->user()->id
            ]);

            return $this->success(null, 'تم حذف الرسالة');
        }, 'حدث خطأ أثناء حذف الرسالة');
    }
}
