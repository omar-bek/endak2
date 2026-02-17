<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * عرض حقول القسم
     */
    public function index(Category $category)
    {
        $subCategoryId = request('sub_category_id');

        if ($subCategoryId) {
            $fields = CategoryField::where('category_id', $category->id)
                                 ->where('sub_category_id', $subCategoryId)
                                 ->orderBy('sort_order')
                                 ->get();
        } else {
            $fields = $category->fields()->orderBy('sort_order')->get();
        }

        $inputGroups = $fields->whereNotNull('input_group')->pluck('input_group')->unique()->toArray();

        return view('admin.category-fields.index', compact('category', 'fields', 'inputGroups'));
    }

    /**
     * عرض نموذج إنشاء حقل جديد
     */
    public function create(Category $category)
    {
        $field = null;
        $subCategoryId = request('sub_category_id');
        $inputGroups = $category->fields()->whereNotNull('input_group')->distinct()->pluck('input_group')->toArray();

        return view('admin.category-fields.create', compact('category', 'field', 'inputGroups', 'subCategoryId'));
    }

    /**
     * حفظ حقل جديد
     */
    public function store(Request $request, Category $category)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:category_fields,category_id,' . $category->id . ',category_id',
            'type' => 'required|in:title,text,number,select,checkbox,textarea,image,date,time',
            'value' => 'nullable',
            'options' => 'nullable|array',
            'input_group' => 'nullable|string|max:255',
            'is_required' => 'boolean',
            'is_repeatable' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
        ]);

        // إذا لم يتم تحديد الترتيب، اجعله آخر ترتيب + 1
        if (!$request->has('sort_order') || $request->sort_order === null) {
            $lastOrder = CategoryField::where('category_id', $category->id)
                                    ->where('sub_category_id', $request->sub_category_id)
                                    ->max('sort_order');
            $data['sort_order'] = ($lastOrder ?? 0) + 1;
        }

        $data = $request->all();
        $data['category_id'] = $category->id;

        // معالجة القيمة حسب نوع الحقل
        $data['value'] = $this->processFieldValue($request, $data['type']);

        // معالجة الخيارات للحقول من نوع select
        if ($data['type'] === 'select' && $request->has('options')) {
            $data['options'] = array_filter($request->input('options', []));
        } else {
            $data['options'] = null;
        }

        CategoryField::create($data);

        return redirect()->route('admin.categories.fields.index', $category)
                         ->with('success', 'تم إنشاء الحقل بنجاح');
    }

    /**
     * عرض نموذج تعديل حقل
     */
    public function edit(Category $category, CategoryField $field)
    {
        $inputGroups = $category->fields()->whereNotNull('input_group')->distinct()->pluck('input_group')->toArray();

        return view('admin.category-fields.edit', compact('category', 'field', 'inputGroups'));
    }



    /**
     * تحديث حقل
     */
    public function update(Request $request, Category $category, CategoryField $field)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:category_fields,category_id,' . $category->id . ',category_id,name,' . $field->name,
            'type' => 'required|in:title,text,number,select,checkbox,textarea,image,date,time',
            'value' => 'nullable',
            'options' => 'nullable|array',
            'input_group' => 'nullable|string|max:255',
            'is_required' => 'boolean',
            'is_repeatable' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();

        // معالجة القيمة حسب نوع الحقل
        $data['value'] = $this->processFieldValue($request, $data['type'], $field);

        // معالجة الخيارات للحقول من نوع select
        if ($data['type'] === 'select' && $request->has('options')) {
            $data['options'] = array_filter($request->input('options', []));
        } else {
            $data['options'] = null;
        }

        $field->update($data);

        return redirect()->route('admin.categories.fields.index', $category)
                         ->with('success', 'تم تحديث الحقل بنجاح');
    }

    /**
     * حذف حقل
     */
    public function destroy(Category $category, CategoryField $field)
    {
        // حذف الصورة إذا كان الحقل من نوع صورة
        if ($field->isImageType() && $field->value) {
            Storage::disk('public')->delete($field->value);
        }

        $field->delete();

        return redirect()->route('admin.categories.fields.index', $category)
                         ->with('success', 'تم حذف الحقل بنجاح');
    }

    /**
     * تغيير حالة الحقل
     */
    public function toggleStatus(Category $category, CategoryField $field)
    {
        $field->update(['is_active' => !$field->is_active]);

        $status = $field->is_active ? 'تفعيل' : 'إلغاء تفعيل';

        return redirect()->route('admin.categories.fields.index', $category)
                         ->with('success', "تم $status الحقل بنجاح");
    }

    /**
     * معالجة قيمة الحقل حسب نوعه
     */
    private function processFieldValue(Request $request, $type, $field = null)
    {
        switch ($type) {
            case 'image':
                if ($request->hasFile('value')) {
                    // حذف الصورة القديمة إذا كانت موجودة
                    if ($field && $field->value) {
                        Storage::disk('public')->delete($field->value);
                    }

                    return $request->file('value')->store('field-defaults', 'public');
                }
                return $field ? $field->value : null;

            case 'checkbox':
                return $request->has('value') ? 1 : 0;

            case 'select':
                return $request->input('value');

            default:
                return $request->input('value');
        }
    }

    /**
     * إعادة ترتيب الحقول
     */
    public function reorder(Request $request, Category $category)
    {
        $request->validate([
            'fields' => 'required|array',
            'fields.*' => 'required|integer|exists:category_fields,id'
        ]);

        foreach ($request->input('fields') as $index => $fieldId) {
            CategoryField::where('id', $fieldId)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * تحديث ترتيب حقل واحد
     */
    public function updateSortOrder(Request $request, Category $category, CategoryField $field)
    {
        $request->validate([
            'sort_order' => 'required|integer|min:0',
        ]);

        $field->update(['sort_order' => $request->sort_order]);

        return response()->json(['success' => true, 'message' => 'تم تحديث ترتيب الحقل بنجاح']);
    }
}
