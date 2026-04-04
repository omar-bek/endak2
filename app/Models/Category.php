<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'name_en',
        'description',
        'description_ar',
        'icon',
        'image',
        'parent_id',
        'is_active',
        'voice_note_enabled',
        'sort_order',
        'meta_title',
        'meta_description',
        'slug'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'voice_note_enabled' => 'boolean',
        'sort_order' => 'integer',
    ];

    // العلاقة مع الأقسام الفرعية
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // العلاقة مع القسم الأب
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // العلاقة مع الأقسام الفرعية
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    // العلاقة مع الخدمات
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // العلاقة مع حقول القسم
    public function fields()
    {
        return $this->hasMany(CategoryField::class);
    }

    // العلاقة مع المدن
    public function cities()
    {
        return $this->belongsToMany(City::class, 'category_cities')
            ->withPivot('is_active', 'sort_order')
            ->withTimestamps();
    }

    // الحصول على المدن المفعلة فقط
    public function activeCities()
    {
        return $this->belongsToMany(City::class, 'category_cities')
            ->wherePivot('is_active', true)
            ->withPivot('sort_order')
            ->orderBy('category_cities.sort_order')
            ->orderBy('cities.name_ar');
    }

    // الحصول على الأقسام الرئيسية فقط
    public static function getMainCategories()
    {
        return self::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function getAllChildren()
    {
        return $this->children()->with('children')->get();
    }

    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    public function getServicesCount()
    {
        return $this->services()->count();
    }

    // إنشاء slug تلقائياً
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = \Illuminate\Support\Str::slug($category->name, '-');
            }
        });
    }

    // الحصول على المسار الكامل للقسم
    public function getFullPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }

        return implode(' > ', $path);
    }

    // الحصول على الأيقونة مع fallback
    public function getIconAttribute($value)
    {
        return $value ?: 'fas fa-folder';
    }

    // الحصول على الصورة مع fallback
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-service.svg');
    }

    // استخدام slug كـ route key
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
