<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\CategoryField;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->latest()->paginate(20);
        return view('admin.sub_categories.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.sub_categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sub_categories', 'public');
        }

        SubCategory::create($data);
        return redirect()->route('admin.sub_categories.index')->with('success', 'تم إضافة القسم الفرعي بنجاح');
    }

    public function edit($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $categories = Category::all();
        return view('admin.sub_categories.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sub_categories', 'public');
        }

        $subCategory->update($data);
        return redirect()->route('admin.sub_categories.index')->with('success', 'تم تحديث القسم الفرعي بنجاح');
    }

    public function destroy($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete();
        return redirect()->route('admin.sub_categories.index')->with('success', 'تم حذف القسم الفرعي بنجاح');
    }

    public function duplicate($id)
    {
        $subCategory = SubCategory::with('fields')->findOrFail($id);
        $categories = Category::all();
        return view('admin.sub_categories.duplicate', compact('subCategory', 'categories'));
    }

    public function duplicateStore(Request $request, $id)
    {
        $originalSubCategory = SubCategory::with('fields')->findOrFail($id);

        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        // نسخ الصورة إذا كانت موجودة
        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('sub_categories', 'public');
        } else {
            // نسخ الصورة الأصلية إذا لم يتم رفع صورة جديدة
            $data['image'] = $originalSubCategory->image;
        }

        // إنشاء القسم الفرعي الجديد
        $newSubCategory = SubCategory::create($data);

        // نسخ الحقول المرتبطة إذا كانت موجودة
        if($originalSubCategory->fields->count() > 0) {
            foreach($originalSubCategory->fields as $field) {
                $fieldData = $field->toArray();
                unset($fieldData['id']);
                unset($fieldData['created_at']);
                unset($fieldData['updated_at']);
                $fieldData['sub_category_id'] = $newSubCategory->id;

                // إنشاء الحقل الجديد
                CategoryField::create($fieldData);
            }
        }

        return redirect()->route('admin.sub_categories.index')->with('success', 'تم تكرار القسم الفرعي بنجاح');
    }
}
