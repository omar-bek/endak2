<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'sub_category_id',
        'is_active',
        'description',
        'hourly_rate',
        'experience_years'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hourly_rate' => 'decimal:2'
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع القسم
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // العلاقة مع القسم الفرعي
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    // الحصول على العروض في هذا القسم
    public function offers()
    {
        return $this->hasManyThrough(
            ServiceOffer::class,
            Service::class,
            'category_id',
            'service_id',
            'category_id',
            'id'
        )->where('provider_id', $this->user_id);
    }

    // الحصول على الخدمات المكتملة في هذا القسم
    public function completedServices()
    {
        return $this->hasManyThrough(
            Service::class,
            ServiceOffer::class,
            'provider_id',
            'id',
            'user_id',
            'service_id'
        )->where('status', 'accepted');
    }
}
