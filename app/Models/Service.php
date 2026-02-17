<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'price',
        'category_id',
        'sub_category_id',
        'city_id',
        'user_id',
        'is_active',
        'is_featured',
        'image',
        'meta_title',
        'meta_description',
        'slug',
        'location',
        'contact_phone',
        'contact_email',
        'custom_fields',
        'voice_note'
    ];



    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'custom_fields' => 'array',
    ];

    // العلاقة مع القسم
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // العلاقة مع القسم الفرعي
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    // العلاقة مع المدينة
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // العلاقة مع المستخدم (مزود الخدمة)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع الطلبات
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // العلاقة مع العروض
    public function offers()
    {
        return $this->hasMany(ServiceOffer::class);
    }

    // الحصول على العروض المعلقة
    public function getPendingOffersAttribute()
    {
        return $this->offers()->where('status', 'pending')->with('provider')->get();
    }

    // الحصول على الخدمات المميزة
    public static function getFeaturedServices($limit = 6)
    {
        return self::where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'user'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    // الحصول على الخدمات حسب القسم
    public static function getServicesByCategory($categoryId, $limit = 12)
    {
        return self::where('category_id', $categoryId)
            ->where('is_active', true)
            ->with(['category', 'user'])
            ->latest()
            ->paginate($limit);
    }

    // البحث في الخدمات
    public static function search($query, $limit = 12)
    {
        return self::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%");
            })
            ->with(['category', 'user'])
            ->latest()
            ->paginate($limit);
    }

    // إنشاء slug تلقائياً
    protected static function boot()
    {
        parent::boot();

        static::created(function ($service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title) . '-' . $service->id;
                $service->save();
            }
        });
    }


    // الحصول على الصورة مع fallback
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        // استخدام الصورة الافتراضية من إعدادات النظام
        $defaultImage = SystemSetting::getDefaultServiceImage();
        if ($defaultImage) {
            return $defaultImage;
        }

        // fallback للصورة الافتراضية القديمة
        return asset('images/default-service.jpg');
    }

    // تنسيق السعر
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' ريال';
    }

    // الحصول على تقييم الخدمة
    public function getAverageRatingAttribute()
    {
        return $this->orders()->whereNotNull('rating')->avg('rating') ?? 0;
    }

    // الحصول على عدد التقييمات
    public function getRatingsCountAttribute()
    {
        return $this->orders()->whereNotNull('rating')->count();
    }
}
