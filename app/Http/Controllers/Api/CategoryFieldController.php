<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\CategoryField;
use Illuminate\Http\Request;

class CategoryFieldController extends BaseApiController
{
    /**
     * جلب جميع الحقول النشطة لقسم معين
     * GET /api/v1/categories/{category}/fields
     */
    public function index(Request $request, $category)
    {
        return $this->executeApiWithTryCatch(function () use ($request, $category) {
            // إذا كان $category هو Category model (من route model binding)
            if ($category instanceof Category) {
                $categoryModel = $category;
            } else {
                // إذا كان رقم أو نص، نبحث عنه
                $categoryModel = is_numeric($category)
                    ? Category::where('id', $category)->firstOrFail()
                    : Category::where('slug', $category)->firstOrFail();
            }

            // التحقق من أن القسم نشط
            if (!$categoryModel->is_active) {
                return $this->error(null, 'القسم غير نشط', 404);
            }

            $query = CategoryField::where('category_id', $categoryModel->id)
                ->where('is_active', true);

            // إذا كان هناك قسم فرعي محدد
            if ($request->filled('sub_category_id')) {
                $query->where(function ($q) use ($request) {
                    $q->where('sub_category_id', $request->integer('sub_category_id'))
                        ->orWhereNull('sub_category_id');
                });
            }

            $fields = $query->orderBy('sort_order')->get();

            return $this->success([
                'category' => [
                    'id' => $categoryModel->id,
                    'name' => $categoryModel->name,
                    'name_ar' => $categoryModel->name,
                    'name_en' => $categoryModel->name_en,
                    'slug' => $categoryModel->slug,
                ],
                'fields' => $fields->map(function ($field) {
                    return [
                        'id' => $field->id,
                        'name' => $field->name,
                        'name_ar' => $field->name_ar,
                        'name_en' => $field->name_en,
                        'type' => $field->type,
                        'value' => $field->value,
                        'options' => $field->options,
                        'input_group' => $field->input_group,
                        'is_required' => $field->is_required,
                        'is_repeatable' => $field->is_repeatable,
                        'description' => $field->description,
                        'sort_order' => $field->sort_order,
                        'sub_category_id' => $field->sub_category_id,
                    ];
                }),
            ]);
        }, 'حدث خطأ أثناء جلب الحقول');
    }

    /**
     * جلب حقل معين
     * GET /api/v1/categories/{category}/fields/{field}
     */
    public function show(Request $request, $category, $field)
    {
        return $this->executeApiWithTryCatch(function () use ($request, $category, $field) {
            // إذا كان $category هو Category model (من route model binding)
            if ($category instanceof Category) {
                $categoryModel = $category;
            } else {
                // إذا كان رقم أو نص، نبحث عنه
                $categoryModel = is_numeric($category)
                    ? Category::where('id', $category)->firstOrFail()
                    : Category::where('slug', $category)->firstOrFail();
            }

            // التحقق من أن القسم نشط
            if (!$categoryModel->is_active) {
                return $this->error(null, 'القسم غير نشط', 404);
            }

            // إذا كان $field هو CategoryField model
            if ($field instanceof CategoryField) {
                $fieldModel = $field;
            } else {
                $fieldModel = CategoryField::where('id', $field)
                    ->where('category_id', $categoryModel->id)
                    ->firstOrFail();
            }

            // التحقق من أن الحقل نشط
            if (!$fieldModel->is_active) {
                return $this->error(null, 'الحقل غير نشط', 404);
            }

            return $this->success([
                'field' => [
                    'id' => $fieldModel->id,
                    'name' => $fieldModel->name,
                    'name_ar' => $fieldModel->name_ar,
                    'name_en' => $fieldModel->name_en,
                    'type' => $fieldModel->type,
                    'value' => $fieldModel->value,
                    'options' => $fieldModel->options,
                    'input_group' => $fieldModel->input_group,
                    'is_required' => $fieldModel->is_required,
                    'is_repeatable' => $fieldModel->is_repeatable,
                    'description' => $fieldModel->description,
                    'sort_order' => $fieldModel->sort_order,
                    'sub_category_id' => $fieldModel->sub_category_id,
                ],
            ]);
        }, 'حدث خطأ أثناء جلب الحقل');
    }

    /**
     * جلب الحقول المجمعة حسب input_group
     * GET /api/v1/categories/{category}/fields/grouped
     */
    public function grouped(Request $request, $category)
    {
        return $this->executeApiWithTryCatch(function () use ($request, $category) {
            // إذا كان $category هو Category model (من route model binding)
            if ($category instanceof Category) {
                $categoryModel = $category;
            } else {
                // إذا كان رقم أو نص، نبحث عنه
                $categoryModel = is_numeric($category)
                    ? Category::where('id', $category)->firstOrFail()
                    : Category::where('slug', $category)->firstOrFail();
            }

            // التحقق من أن القسم نشط
            if (!$categoryModel->is_active) {
                return $this->error(null, 'القسم غير نشط', 404);
            }

            $query = CategoryField::where('category_id', $categoryModel->id)
                ->where('is_active', true);

            // إذا كان هناك قسم فرعي محدد
            if ($request->filled('sub_category_id')) {
                $query->where(function ($q) use ($request) {
                    $q->where('sub_category_id', $request->integer('sub_category_id'))
                        ->orWhereNull('sub_category_id');
                });
            }

            $fields = $query->orderBy('sort_order')->get();

            // تجميع الحقول حسب input_group
            $grouped = [];
            foreach ($fields as $field) {
                $group = $field->input_group ?: 'default';
                if (!isset($grouped[$group])) {
                    $grouped[$group] = [];
                }
                $grouped[$group][] = [
                    'id' => $field->id,
                    'name' => $field->name,
                    'name_ar' => $field->name_ar,
                    'name_en' => $field->name_en,
                    'type' => $field->type,
                    'value' => $field->value,
                    'options' => $field->options,
                    'is_required' => $field->is_required,
                    'is_repeatable' => $field->is_repeatable,
                    'description' => $field->description,
                    'sort_order' => $field->sort_order,
                    'sub_category_id' => $field->sub_category_id,
                ];
            }

            return $this->success([
                'category' => [
                    'id' => $categoryModel->id,
                    'name' => $categoryModel->name,
                    'name_ar' => $categoryModel->name,
                    'name_en' => $categoryModel->name_en,
                    'slug' => $categoryModel->slug,
                ],
                'grouped_fields' => $grouped,
            ]);
        }, 'حدث خطأ أثناء جلب الحقول المجمعة');
    }
}
