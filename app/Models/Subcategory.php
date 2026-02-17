<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar', 'name_en', 'category_id', 'image', 'description_ar', 'description_en', 'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // العلاقة مع القسم الرئيسي
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // العلاقة مع الحقول
    public function fields()
    {
        return $this->hasMany(CategoryField::class);
    }

    // العلاقة مع الخدمات
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // الحصول على الصورة
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-subcategory.jpg');
    }

    // التحقق من الحالة
    public function isActive()
    {
        return $this->status;
    }
}
