<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // العلاقة مع الخدمات
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // العلاقة مع مزودي الخدمة
    public function providers()
    {
        return $this->hasMany(ProviderCity::class);
    }

    // الحصول على الخدمات النشطة
    public function activeServices()
    {
        return $this->services()->where('is_active', true);
    }

    // الحصول على مزودي الخدمة النشطين
    public function activeProviders()
    {
        return $this->providers()->where('is_active', true);
    }

    // الحصول على المدن النشطة مرتبة
    public static function getActiveCities()
    {
        return static::where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name_ar')
                    ->get();
    }

    // البحث في المدن
    public static function search($query)
    {
        return static::where('is_active', true)
                    ->where(function($q) use ($query) {
                        $q->where('name_ar', 'like', "%{$query}%")
                          ->orWhere('name_en', 'like', "%{$query}%");
                    })
                    ->orderBy('sort_order')
                    ->orderBy('name_ar')
                    ->get();
    }

    // العلاقة مع الأقسام
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_cities')
                    ->withPivot('is_active', 'sort_order')
                    ->withTimestamps();
    }

    // الحصول على الأقسام المفعلة فقط
    public function activeCategories()
    {
        return $this->belongsToMany(Category::class, 'category_cities')
                    ->wherePivot('is_active', true)
                    ->withPivot('sort_order')
                    ->orderBy('category_cities.sort_order')
                    ->orderBy('categories.name');
    }
}
