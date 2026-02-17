<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\ProviderCity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryCityController extends Controller
{
    /**
     * عرض صفحة إدارة الأقسام والمدن
     */
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        $cities = City::orderBy('sort_order')->orderBy('name_ar')->get();

        return view('admin.category-cities.index', compact('categories', 'cities'));
    }

    /**
     * عرض تفاصيل قسم مع المدن المتاحة
     */
    public function show(Category $category)
    {
        $category->load(['children' => function ($query) {
            $query->orderBy('sort_order');
        }]);

        $cities = City::orderBy('sort_order')->orderBy('name_ar')->get();

        // الحصول على المدن المفعلة في هذا القسم
        $enabledCities = DB::table('category_cities')
            ->where('category_id', $category->id)
            ->pluck('city_id')
            ->toArray();

        return view('admin.category-cities.show', compact('category', 'cities', 'enabledCities'));
    }

    /**
     * تحديث المدن المفعلة في قسم معين
     */
    public function updateCities(Request $request, Category $category)
    {
        $request->validate([
            'cities' => 'array',
            'cities.*' => 'exists:cities,id'
        ]);

        $selectedCities = $request->input('cities', []);

        // حذف جميع المدن المرتبطة بالقسم
        DB::table('category_cities')->where('category_id', $category->id)->delete();

        // إضافة المدن المحددة
        foreach ($selectedCities as $cityId) {
            DB::table('category_cities')->insert([
                'category_id' => $category->id,
                'city_id' => $cityId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث المدن المتاحة في القسم بنجاح');
    }

    /**
     * تفعيل/تعطيل مدينة في قسم معين
     */
    public function toggleCity(Request $request, Category $category, $cityId)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $isActive = $request->input('is_active');

        DB::table('category_cities')
            ->where('category_id', $category->id)
            ->where('city_id', $cityId)
            ->update([
                'is_active' => $isActive,
                'updated_at' => now()
            ]);

        $status = $isActive ? 'مفعلة' : 'معطلة';
        return response()->json([
            'success' => true,
            'message' => "تم $status المدينة في القسم بنجاح"
        ]);
    }

    /**
     * الحصول على المدن المتاحة في قسم معين (API)
     */
    public function getCitiesByCategory(Category $category)
    {
        $cities = DB::table('category_cities')
            ->join('cities', 'category_cities.city_id', '=', 'cities.id')
            ->where('category_cities.category_id', $category->id)
            ->where('category_cities.is_active', true)
            ->select('cities.*', 'category_cities.is_active as category_active')
            ->orderBy('cities.sort_order')
            ->orderBy('cities.name_ar')
            ->get();

        return response()->json([
            'success' => true,
            'cities' => $cities
        ]);
    }

    /**
     * إضافة مدينة لقسم معين
     */
    public function addCityToCategory(Request $request, Category $category)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id'
        ]);

        $cityId = $request->input('city_id');

        // التحقق من عدم وجود المدينة مسبقاً
        $exists = DB::table('category_cities')
            ->where('category_id', $category->id)
            ->where('city_id', $cityId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'المدينة موجودة مسبقاً في هذا القسم'
            ]);
        }

        DB::table('category_cities')->insert([
            'category_id' => $category->id,
            'city_id' => $cityId,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المدينة للقسم بنجاح'
        ]);
    }

    /**
     * إزالة مدينة من قسم معين
     */
    public function removeCityFromCategory(Category $category, $cityId)
    {
        DB::table('category_cities')
            ->where('category_id', $category->id)
            ->where('city_id', $cityId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم إزالة المدينة من القسم بنجاح'
        ]);
    }

    /**
     * عرض إحصائيات الأقسام والمدن
     */
    public function statistics()
    {
        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
            'total_cities' => City::count(),
            'active_cities' => City::where('is_active', true)->count(),
            'category_city_relations' => DB::table('category_cities')->count(),
            'active_relations' => DB::table('category_cities')->where('is_active', true)->count()
        ];

        // الأقسام الأكثر نشاطاً
        $topCategories = DB::table('category_cities')
            ->join('categories', 'category_cities.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('count(*) as city_count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('city_count', 'desc')
            ->limit(10)
            ->get();

        // المدن الأكثر استخداماً
        $topCities = DB::table('category_cities')
            ->join('cities', 'category_cities.city_id', '=', 'cities.id')
            ->select('cities.name_ar', DB::raw('count(*) as category_count'))
            ->groupBy('cities.id', 'cities.name_ar')
            ->orderBy('category_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.category-cities.statistics', compact('stats', 'topCategories', 'topCities'));
    }
}
