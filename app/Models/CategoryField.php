<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryField extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'sub_category_id', 'name', 'name_ar', 'name_en', 'type', 'value',
        'options', 'input_group', 'is_required', 'is_repeatable',
        'description', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_repeatable' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // العلاقة مع القسم الفرعي
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    // Get active fields for a category
    public static function getActiveFields($categoryId)
    {
        return static::where('category_id', $categoryId)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();
    }

    // Get fields grouped by input_group
    public static function getGroupedFields($categoryId)
    {
        $fields = static::getActiveFields($categoryId);
        $grouped = [];

        foreach ($fields as $field) {
            $group = $field->input_group ?: 'default';
            $grouped[$group][] = $field;
        }

        return $grouped;
    }

    // Get field types with labels
    public static function getFieldTypes()
    {
        return [
            'title' => 'عنوان فقط',
            'text' => 'نص',
            'number' => 'رقم',
            'select' => 'قائمة منسدلة',
            'checkbox' => 'تشيك بوكس',
            'textarea' => 'مربع نص',
            'image' => 'صورة',
            'date' => 'تاريخ',
            'time' => 'وقت',
        ];
    }

    // Get field type icon
    public function getTypeIconAttribute()
    {
        $icons = [
            'title' => 'fas fa-heading',
            'text' => 'fas fa-font',
            'number' => 'fas fa-hashtag',
            'select' => 'fas fa-list',
            'checkbox' => 'fas fa-check-square',
            'textarea' => 'fas fa-paragraph',
            'image' => 'fas fa-image',
            'date' => 'fas fa-calendar',
            'time' => 'fas fa-clock',
        ];

        return $icons[$this->type] ?? 'fas fa-question';
    }

    // Get field type label
    public function getTypeLabelAttribute()
    {
        $types = static::getFieldTypes();
        return $types[$this->type] ?? $this->type;
    }

    // Check if field is select type
    public function isSelectType()
    {
        return $this->type === 'select';
    }

    // Check if field is image type
    public function isImageType()
    {
        return $this->type === 'image';
    }

    // Check if field is checkbox type
    public function isCheckboxType()
    {
        return $this->type === 'checkbox';
    }

    // Check if field is title type
    public function isTitleType()
    {
        return $this->type === 'title';
    }
}
