<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'media_path',
        'voice_note_path',
        'message_type',
        'is_read',
        'read_at',
        'is_deleted',
        'metadata',
        'reply_to_message_id',
        'service_id',
        'service_offer_id',
        'conversation_id',
        'file_name',
        'file_size'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_deleted' => 'boolean',
        'read_at' => 'datetime',
        'deleted_at' => 'datetime',
        'metadata' => 'array'
    ];

    protected $appends = [
        'formatted_time',
        'media_url',
        'voice_note_url',
        'is_own_message',
        'conversation_partner'
    ];

    protected static function boot()
    {
        parent::boot();

        // إنشاء conversation_id تلقائياً
        static::creating(function ($message) {
            if (!$message->conversation_id) {
                $message->conversation_id = self::generateConversationId($message->sender_id, $message->receiver_id);
            }
        });
    }

    /**
     * إنشاء معرف المحادثة
     */
    public static function generateConversationId($user1Id, $user2Id)
    {
        $ids = [$user1Id, $user2Id];
        sort($ids);
        return 'conv_' . implode('_', $ids);
    }

    /**
     * المرسل
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * المستقبل
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * الخدمة المرتبطة
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * العرض المرتبط
     */
    public function serviceOffer(): BelongsTo
    {
        return $this->belongsTo(ServiceOffer::class);
    }

    /**
     * الرسالة المردود عليها
     */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'reply_to_message_id');
    }

    /**
     * الردود على هذه الرسالة
     */
    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to_message_id');
    }

    /**
     * تنسيق الوقت
     */
    public function getFormattedTimeAttribute(): string
    {
        $now = now();
        $messageTime = $this->created_at;

        if ($messageTime->isToday()) {
            return $messageTime->format('H:i');
        } elseif ($messageTime->isYesterday()) {
            return 'أمس ' . $messageTime->format('H:i');
        } elseif ($messageTime->diffInDays($now) < 7) {
            $days = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
            return $days[$messageTime->dayOfWeek] . ' ' . $messageTime->format('H:i');
        } else {
            return $messageTime->format('d/m/Y H:i');
        }
    }

    /**
     * رابط الوسائط
     */
    public function getMediaUrlAttribute(): ?string
    {
        if ($this->media_path) {
            return asset('storage/' . $this->media_path);
        }
        return null;
    }

    /**
     * رابط الرسالة الصوتية
     */
    public function getVoiceNoteUrlAttribute(): ?string
    {
        if ($this->voice_note_path) {
            return asset('storage/' . $this->voice_note_path);
        }
        return null;
    }

    /**
     * التحقق من أن الرسالة مملوكة للمستخدم الحالي
     */
    public function getIsOwnMessageAttribute(): bool
    {
        return $this->sender_id === auth()->id();
    }

    /**
     * شريك المحادثة (المستخدم الآخر)
     */
    public function getConversationPartnerAttribute(): ?User
    {
        $currentUserId = auth()->id();
        return $this->sender_id === $currentUserId ? $this->receiver : $this->sender;
    }

    /**
     * تحديد الرسالة كمقروءة
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
    }

    /**
     * حذف الرسالة (soft delete)
     */
    public function softDelete(): void
    {
        $this->update([
            'is_deleted' => true,
            'deleted_at' => now()
        ]);
    }

    /**
     * استعادة الرسالة المحذوفة
     */
    public function restore(): void
    {
        $this->update([
            'is_deleted' => false,
            'deleted_at' => null
        ]);
    }

    // دوال التحقق من نوع الرسالة
    public function isText(): bool
    {
        return $this->message_type === 'text';
    }

    public function isImage(): bool
    {
        return $this->message_type === 'image';
    }

    public function isVoice(): bool
    {
        return $this->message_type === 'voice';
    }

    public function isFile(): bool
    {
        return $this->message_type === 'file';
    }

    public function isLocation(): bool
    {
        return $this->message_type === 'location';
    }

    public function isContact(): bool
    {
        return $this->message_type === 'contact';
    }

    /**
     * الحصول على معلومات الموقع من metadata
     */
    public function getLocationInfo(): ?array
    {
        if ($this->isLocation() && $this->metadata) {
            return $this->metadata['location'] ?? null;
        }
        return null;
    }

    /**
     * الحصول على معلومات الاتصال من metadata
     */
    public function getContactInfo(): ?array
    {
        if ($this->isContact() && $this->metadata) {
            return $this->metadata['contact'] ?? null;
        }
        return null;
    }

    /**
     * الحصول على حجم الملف
     */
    public function getFileSize(): ?string
    {
        if ($this->isFile() && $this->media_path) {
            $path = storage_path('app/public/' . $this->media_path);
            if (file_exists($path)) {
                $size = filesize($path);
                return $this->formatFileSize($size);
            }
        }
        return null;
    }

    /**
     * تنسيق حجم الملف
     */
    private function formatFileSize($size): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * الحصول على اسم الملف
     */
    public function getFileName(): ?string
    {
        if ($this->media_path) {
            return basename($this->media_path);
        }
        return null;
    }

    /**
     * الحصول على امتداد الملف
     */
    public function getFileExtension(): ?string
    {
        if ($this->media_path) {
            return pathinfo($this->media_path, PATHINFO_EXTENSION);
        }
        return null;
    }

    /**
     * التحقق من أن الملف صورة
     */
    public function isImageFile(): bool
    {
        $extension = $this->getFileExtension();
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        return in_array(strtolower($extension), $imageExtensions);
    }

    /**
     * الحصول على مدة الرسالة الصوتية
     */
    public function getVoiceDuration(): ?string
    {
        if ($this->isVoice() && $this->metadata && isset($this->metadata['duration'])) {
            $seconds = $this->metadata['duration'];
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;
            return sprintf('%d:%02d', $minutes, $remainingSeconds);
        }
        return null;
    }
}
