<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'provider_id',
        'price',
        'notes',
        'status',
        'expires_at',
        'accepted_at',
        'delivered_at',
        'rating',
        'review'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'delivered_at' => 'datetime',
        'rating' => 'integer',
    ];

    // العلاقة مع الخدمة
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // العلاقة مع مزود الخدمة
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    // تنسيق السعر
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' ريال';
    }

    // حالة العرض بالعربية
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'في الانتظار',
            'accepted' => 'مقبول',
            'delivered' => 'تم التسليم',
            'rejected' => 'مرفوض',
            'expired' => 'منتهي الصلاحية'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // لون حالة العرض
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'accepted' => 'success',
            'delivered' => 'info',
            'rejected' => 'danger',
            'expired' => 'secondary'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    // التحقق من انتهاء صلاحية العرض
    public function getIsExpiredAttribute()
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    // الحصول على العروض المعلقة
    public static function getPendingOffers($serviceId)
    {
        return static::where('service_id', $serviceId)
                    ->where('status', 'pending')
                    ->with('provider')
                    ->orderBy('price')
                    ->get();
    }

    // الحصول على عروض مزود الخدمة
    public static function getProviderOffers($providerId)
    {
        return static::where('provider_id', $providerId)
                    ->with(['service', 'service.category'])
                    ->latest()
                    ->get();
    }

    // تحديث حالة العرض إلى مقبول
    public function markAsAccepted()
    {
        $this->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);
    }

    // تحديث حالة العرض إلى تم التسليم
    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);
    }

    // إضافة تقييم للمزود
    public function addReview($rating, $review)
    {
        $this->update([
            'rating' => $rating,
            'review' => $review
        ]);
    }

    // التحقق من إمكانية التقييم
    public function canBeRated()
    {
        return $this->status === 'delivered' && !$this->rating;
    }

    // التحقق من إمكانية تسليم الخدمة
    public function canBeDelivered()
    {
        return $this->status === 'accepted' && !$this->delivered_at;
    }
}
