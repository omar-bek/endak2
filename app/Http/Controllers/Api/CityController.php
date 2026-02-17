<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends BaseApiController
{
    /**
     * جلب جميع المدن النشطة
     * GET /api/v1/cities
     */
    public function index(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            $query = City::where('is_active', true);

            // إذا كان هناك فلتر للبحث
            if ($request->filled('search')) {
                $searchTerm = $request->get('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name_ar', 'like', "%{$searchTerm}%")
                        ->orWhere('name_en', 'like', "%{$searchTerm}%");
                });
            }

            $cities = $query->orderBy('sort_order')
                ->orderBy('name_ar')
                ->get(['id', 'name_ar', 'name_en', 'slug', 'sort_order']);

            return $this->success($cities->map(function ($city) {
                return [
                    'id' => $city->id,
                    'name_ar' => $city->name_ar,
                    'name_en' => $city->name_en,
                    'slug' => $city->slug ?? null,
                    'sort_order' => $city->sort_order ?? 0,
                ];
            }));
        }, 'حدث خطأ أثناء جلب المدن');
    }

    /**
     * جلب مدينة محددة
     * GET /api/v1/cities/{id}
     */
    public function show(int $id)
    {
        return $this->executeApiWithTryCatch(function () use ($id) {
            $city = City::where('id', $id)
                ->where('is_active', true)
                ->firstOrFail();

            return $this->success([
                'id' => $city->id,
                'name_ar' => $city->name_ar,
                'name_en' => $city->name_en,
                'slug' => $city->slug ?? null,
                'sort_order' => $city->sort_order ?? 0,
            ]);
        }, 'حدث خطأ أثناء جلب المدينة');
    }
}
