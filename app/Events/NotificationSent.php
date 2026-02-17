<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    /**
     * Create a new event instance.
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;

        Log::info('NotificationSent Event created', [
            'notification_id' => $notification->id,
            'user_id' => $notification->user_id,
            'type' => $notification->type
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        $channel = new PrivateChannel('user.' . $this->notification->user_id);

        Log::info('NotificationSent broadcasting on channel', [
            'channel' => 'user.' . $this->notification->user_id,
            'notification_id' => $this->notification->id
        ]);

        return [$channel];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'notification.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notification->id,
            'user_id' => $this->notification->user_id,
            'type' => $this->notification->type,
            'title' => $this->notification->title,
            'message' => $this->notification->message,
            'data' => $this->notification->data,
            'read_at' => $this->notification->read_at?->toDateTimeString(),
            'created_at' => $this->notification->created_at->toDateTimeString(),
            'icon' => $this->notification->icon,
            'color' => $this->notification->color,
            'unread_count' => Notification::getUnreadCountForUser($this->notification->user_id),
        ];
    }
}
