<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Service;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class CategoryController extends BaseApiController
{
    public function index()
    {
        return $this->executeApiWithTryCatch(function () {
            $categories = Category::query()
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->with([
                    'children' => fn($query) => $query->where('is_active', true)->orderBy('sort_order'),
                ])
                ->withCount('services')
                ->orderBy('sort_order')
                ->get();

            return $this->success($categories);
        }, 'حدث خطأ أثناء جلب الأقسام');
    }

    public function show(string $slug, Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($slug, $request) {
            $category = Category::query()
                ->where('slug', $slug)
                ->where('is_active', true)
                ->with(['subCategories' => fn($query) => $query->where('status', true)])
                ->firstOrFail();

            $servicesQuery = Service::query()
                ->where('category_id', $category->id)
                ->where('is_active', true)
                ->with(['user:id,name,avatar', 'city:id,name_ar,name_en']);

            if ($request->filled('sub_category_id')) {
                $servicesQuery->where('sub_category_id', $request->integer('sub_category_id'));
            }

            if ($request->filled('search')) {
                $term = $request->get('search');
                $servicesQuery->where(function ($query) use ($term) {
                    $query->where('title', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            }

            if ($request->filled('city_id')) {
                $servicesQuery->where('city_id', $request->integer('city_id'));
            }

            $services = $servicesQuery->paginate($request->get('per_page', 12));

            return $this->success([
                'category' => $category,
                'services' => $services,
            ]);
        }, 'حدث خطأ أثناء جلب القسم');
    }

    public function subcategories(int $id)
    {
        return $this->executeApiWithTryCatch(function () use ($id) {
            Category::query()->where('id', $id)->where('is_active', true)->firstOrFail();

            $subcategories = SubCategory::query()
                ->where('category_id', $id)
                ->where('status', true)
                ->orderBy('name_ar')
                ->get(['id', 'name_ar', 'name_en', 'category_id']);

            return $this->success($subcategories);
        }, 'حدث خطأ أثناء جلب الأقسام الفرعية');
    }

    /**
     * جلب بيانات صفحة request مع الحقول والمدن والأقسام الفرعية
     * GET /api/v1/categories/{id}/request-data
     */
    public function requestData(int $id, Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($id, $request) {
            $categoryModel = Category::query()
                ->where('id', $id)
                ->where('is_active', true)
                ->firstOrFail();

            // جلب الحقول المخصصة النشطة مرتبة
            $fields = \App\Models\CategoryField::where('category_id', $categoryModel->id)
                ->where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get();

            // جلب الأقسام الفرعية
            $subCategories = $categoryModel->subCategories()
                ->where('status', true)
                ->orderBy('name_ar')
                ->get(['id', 'name_ar', 'name_en', 'category_id']);

            $hasSubCategories = $subCategories->count() > 0;
            $selectedSubCategoryId = $request->get('sub_category_id');
            $selectedSubCategory = null;

            // إذا كان هناك قسم فرعي محدد
            if ($selectedSubCategoryId) {
                $selectedSubCategory = $subCategories->where('id', $selectedSubCategoryId)->first();

                // إذا كان القسم الفرعي محدد، فلنجلب الحقول الخاصة به أيضاً
                if ($selectedSubCategory) {
                    $subCategoryFields = \App\Models\CategoryField::where('category_id', $categoryModel->id)
                        ->where(function ($query) use ($selectedSubCategoryId) {
                            $query->where('sub_category_id', $selectedSubCategoryId)
                                ->orWhereNull('sub_category_id');
                        })
                        ->where('is_active', true)
                        ->orderBy('sort_order', 'asc')
                        ->get();

                    // دمج الحقول
                    $fields = $subCategoryFields;
                }
            }

            // جلب المدن المرتبطة بهذا القسم
            $cities = $categoryModel->activeCities()
                ->orderBy('category_cities.sort_order')
                ->orderBy('cities.name_ar')
                ->get(['cities.id', 'cities.name_ar', 'cities.name_en']);

            // تجميع الحقول حسب input_group
            $groupedFields = [];
            foreach ($fields as $field) {
                $group = $field->input_group ?: 'default';
                if (!isset($groupedFields[$group])) {
                    $groupedFields[$group] = [];
                }
                $groupedFields[$group][] = [
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
                    'description' => $categoryModel->description,
                    'description_ar' => $categoryModel->description_ar,
                    'voice_note_enabled' => $categoryModel->voice_note_enabled ?? false,
                ],
                'has_sub_categories' => $hasSubCategories,
                'sub_categories' => $subCategories->map(function ($sub) {
                    return [
                        'id' => $sub->id,
                        'name_ar' => $sub->name_ar,
                        'name_en' => $sub->name_en,
                        'category_id' => $sub->category_id,
                    ];
                }),
                'selected_sub_category' => $selectedSubCategory ? [
                    'id' => $selectedSubCategory->id,
                    'name_ar' => $selectedSubCategory->name_ar,
                    'name_en' => $selectedSubCategory->name_en,
                ] : null,
                'cities' => $cities->map(function ($city) {
                    return [
                        'id' => $city->id,
                        'name_ar' => $city->name_ar,
                        'name_en' => $city->name_en,
                    ];
                }),
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
                'grouped_fields' => $groupedFields,
            ]);
        }, 'حدث خطأ أثناء جلب بيانات صفحة الطلب');
    }

    /**
     * جلب المدن المتاحة في فئة معينة
     * GET /api/v1/categories/{id}/cities
     */
    public function cities(int $id, Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($id, $request) {
            $categoryModel = Category::query()
                ->where('id', $id)
                ->where('is_active', true)
                ->firstOrFail();

            // جلب المدن النشطة المرتبطة بهذا القسم
            // activeCities() already has orderBy, so we don't need to add it again
            // Also filter by city is_active to ensure only active cities are returned
            $cities = $categoryModel->activeCities()
                ->orderBy('category_cities.sort_order')
                ->orderBy('cities.name_ar')
                ->get(['cities.id', 'cities.name_ar', 'cities.name_en', 'cities.slug']);

            // إذا كان هناك فلتر للبحث
            if ($request->filled('search')) {
                $searchTerm = $request->get('search');
                $cities = $cities->filter(function ($city) use ($searchTerm) {
                    return stripos($city->name_ar, $searchTerm) !== false
                        || stripos($city->name_en, $searchTerm) !== false;
                })->values();
            }

            return $this->success($cities->map(function ($city) {
                return [
                    'id' => $city->id,
                    'name_ar' => $city->name_ar,
                    'name_en' => $city->name_en,
                    'slug' => $city->slug ?? null,
                ];
            }));
        }, 'حدث خطأ أثناء جلب المدن');
    }
}
