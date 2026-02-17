<?php

namespace App\Models;

use App\Events\NotificationSent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'broadcasted_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'broadcasted_at' => 'datetime',
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // التحقق من أن الإشعار مقروء
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    // تحديد الإشعار كمقروء
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    // تحديد الإشعار كغير مقروء
    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
    }

    // الحصول على أيقونة الإشعار
    public function getIconAttribute()
    {
        $icons = [
            'offer_received' => 'fas fa-handshake text-success',
            'offer_accepted' => 'fas fa-check-circle text-success',
            'offer_rejected' => 'fas fa-times-circle text-danger',
            'service_requested' => 'fas fa-concierge-bell text-primary',
            'payment_received' => 'fas fa-money-bill-wave text-success',
            'service_completed' => 'fas fa-flag-checkered text-success',
            'message_received' => 'fas fa-comments text-primary',
            'service_deleted' => 'fas fa-trash text-danger',
            'system' => 'fas fa-info-circle text-info',
        ];

        return $icons[$this->type] ?? 'fas fa-bell text-warning';
    }

    // الحصول على لون الإشعار
    public function getColorAttribute()
    {
        $colors = [
            'offer_received' => 'success',
            'offer_accepted' => 'success',
            'offer_rejected' => 'danger',
            'service_requested' => 'primary',
            'payment_received' => 'success',
            'service_completed' => 'success',
            'message_received' => 'primary',
            'service_deleted' => 'danger',
            'system' => 'info',
        ];

        return $colors[$this->type] ?? 'warning';
    }

    // الحصول على الإشعارات غير المقروءة للمستخدم
    public static function getUnreadForUser($userId)
    {
        return static::where('user_id', $userId)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // الحصول على عدد الإشعارات غير المقروءة للمستخدم
    public static function getUnreadCountForUser($userId)
    {
        return static::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    // إنشاء إشعار جديد
    public static function createNotification($userId, $type, $title, $message, $data = null)
    {
        \Illuminate\Support\Facades\Log::info('Creating notification', [
            'user_id' => $userId,
            'type' => $type,
            'title' => $title
        ]);

        $notification = static::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        \Illuminate\Support\Facades\Log::info('Notification created, event should be dispatched', [
            'notification_id' => $notification->id
        ]);

        return $notification;
    }

    // إنشاء إشعار عند تقديم عرض
    public static function createOfferReceivedNotification($serviceOffer)
    {
        $service = $serviceOffer->service;
        $provider = $serviceOffer->provider;

        return static::createNotification(
            $service->user_id,
            'offer_received',
            __('messages.offer_received_title'),
            __('messages.offer_received_message', [
                'provider' => $provider->name,
                'service' => $service->title
            ]),
            [
                'service_id' => $service->id,
                'offer_id' => $serviceOffer->id,
                'provider_id' => $provider->id,
                'price' => $serviceOffer->price,
            ]
        );
    }

    // إنشاء إشعار عند قبول العرض
    public static function createOfferAcceptedNotification($serviceOffer)
    {
        $service = $serviceOffer->service;
        $customer = $service->user;

        return static::createNotification(
            $serviceOffer->provider_id,
            'offer_accepted',
            __('messages.offer_accepted_title'),
            __('messages.offer_accepted_message', [
                'customer' => $customer->name,
                'service' => $service->title
            ]),
            [
                'service_id' => $service->id,
                'offer_id' => $serviceOffer->id,
                'customer_id' => $customer->id,
            ]
        );
    }

    // إنشاء إشعار عند رفض العرض
    public static function createOfferRejectedNotification($serviceOffer)
    {
        $service = $serviceOffer->service;
        $customer = $service->user;

        return static::createNotification(
            $serviceOffer->provider_id,
            'offer_rejected',
            __('messages.offer_rejected_title'),
            __('messages.offer_rejected_message', [
                'customer' => $customer->name,
                'service' => $service->title
            ]),
            [
                'service_id' => $service->id,
                'offer_id' => $serviceOffer->id,
                'customer_id' => $customer->id,
            ]
        );
    }

    // إرسال إشعار لجميع المزودين المشتركين في نفس القسم والمدينة عند طلب خدمة جديدة
    public static function notifyProvidersForNewService(Service $service)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Notifying providers for new service', [
                'service_id' => $service->id,
                'category_id' => $service->category_id,
                'city_id' => $service->city_id,
            ]);

            // جلب جميع المزودين المشتركين في نفس القسم والمدينة
            $providers = User::where('user_type', 'provider')
                ->whereHas('providerCategories', function ($query) use ($service) {
                    $query->where('category_id', $service->category_id)
                        ->where('is_active', true);
                })
                ->whereHas('providerCities', function ($query) use ($service) {
                    $query->where('city_id', $service->city_id)
                        ->where('is_active', true);
                })
                ->get();

            \Illuminate\Support\Facades\Log::info('Found providers to notify', [
                'service_id' => $service->id,
                'providers_count' => $providers->count(),
            ]);

            // تحميل العلاقات إذا لم تكن محملة
            if (!$service->relationLoaded('user')) {
                $service->load('user');
            }
            if (!$service->relationLoaded('category')) {
                $service->load('category');
            }
            if (!$service->relationLoaded('city')) {
                $service->load('city');
            }

            $customer = $service->user;
            $category = $service->category;
            $city = $service->city;

            $notificationsSent = 0;
            foreach ($providers as $provider) {
                // تجنب إرسال إشعار لصاحب الخدمة نفسه
                if ($provider->id === $service->user_id) {
                    continue;
                }

                try {
                    $categoryName = $category->name ?? 'خدمة';
                    $cityName = $city->name_ar ?? 'مدينة';

                    $title = __('messages.service_requested_title', [], 'ar') ?: 'طلب خدمة جديد';
                    $message = __('messages.service_requested_message', [
                        'category' => $categoryName,
                        'city' => $cityName,
                    ], 'ar') ?: "تم طلب خدمة جديدة في قسم {$categoryName} بمدينة {$cityName}";

                    static::createNotification(
                        $provider->id,
                        'service_requested',
                        $title,
                        $message,
                        [
                            'service_id' => $service->id,
                            'category_id' => $service->category_id,
                            'city_id' => $service->city_id,
                            'customer_id' => $service->user_id,
                        ]
                    );
                    $notificationsSent++;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to send notification to provider', [
                        'provider_id' => $provider->id,
                        'service_id' => $service->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            \Illuminate\Support\Facades\Log::info('Notifications sent to providers', [
                'service_id' => $service->id,
                'notifications_sent' => $notificationsSent,
                'total_providers' => $providers->count(),
            ]);

            return $notificationsSent;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in notifyProvidersForNewService', [
                'service_id' => $service->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 0;
        }
    }
}
