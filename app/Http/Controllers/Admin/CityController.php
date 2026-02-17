<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CityController extends Controller
{
    /**
     * عرض قائمة المدن
     */
    public function index()
    {
        $cities = City::orderBy('sort_order')->orderBy('name_ar')->paginate(15);

        return view('admin.cities.index', compact('cities'));
    }

    /**
     * عرض نموذج إضافة مدينة جديدة
     */
    public function create()
    {
        return view('admin.cities.create');
    }

    /**
     * حفظ مدينة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $city = City::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
            'slug' => Str::slug($request->name_en),
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0
        ]);

        return redirect()->route('admin.cities.index')
            ->with('success', 'تم إضافة المدينة بنجاح');
    }

    /**
     * عرض تفاصيل المدينة
     */
    public function show(string $id)
    {
        $city = City::findOrFail($id);

        return view('admin.cities.show', compact('city'));
    }

    /**
     * عرض نموذج تعديل المدينة
     */
    public function edit(string $id)
    {
        $city = City::findOrFail($id);

        return view('admin.cities.edit', compact('city'));
    }

    /**
     * تحديث المدينة
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $city = City::findOrFail($id);

        $city->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,
            'slug' => Str::slug($request->name_en),
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0
        ]);

        return redirect()->route('admin.cities.index')
            ->with('success', 'تم تحديث المدينة بنجاح');
    }

    /**
     * حذف المدينة
     */
    public function destroy(string $id)
    {
        $city = City::findOrFail($id);

        // التحقق من عدم وجود خدمات مرتبطة بالمدينة
        if ($city->services()->count() > 0) {
            return redirect()->route('admin.cities.index')
                ->with('error', 'لا يمكن حذف المدينة لوجود خدمات مرتبطة بها');
        }

        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'تم حذف المدينة بنجاح');
    }
}
