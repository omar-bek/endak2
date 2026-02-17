<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع المدينة
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // الحصول على الخدمات في هذه المدينة
    public function services()
    {
        return $this->hasMany(Service::class, 'city_id', 'city_id')
                    ->where('user_id', $this->user_id);
    }

    // الحصول على العروض في هذه المدينة
    public function offers()
    {
        return $this->hasManyThrough(
            ServiceOffer::class,
            Service::class,
            'city_id',
            'service_id',
            'city_id',
            'id'
        )->where('provider_id', $this->user_id);
    }

    // Accessor للحصول على اسم المدينة
    public function getCityNameAttribute()
    {
        return $this->city ? $this->city->name : null;
    }
}
