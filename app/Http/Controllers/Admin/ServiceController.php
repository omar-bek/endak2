<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * عرض جميع الخدمات
     */
    public function index(Request $request)
    {
        $query = Service::with(['category', 'subCategory', 'user', 'city']);

        // فلترة حسب البحث
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // فلترة حسب القسم
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // فلترة حسب الحالة
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $services = $query->latest()->paginate(20);
        $categories = \App\Models\Category::where('is_active', true)->get();

        return view('admin.services.index', compact('services', 'categories'));
    }

    /**
     * عرض خدمة معينة
     */
    public function show(Service $service)
    {
        $service->load(['category', 'subCategory', 'user', 'city', 'offers.provider']);

        return view('admin.services.show', compact('service'));
    }

    /**
     * تبديل حالة الخدمة
     */
    public function toggleStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);

        $status = $service->is_active ? 'مفعلة' : 'معطلة';

        return back()->with('success', "تم $status الخدمة بنجاح");
    }

    /**
     * حذف خدمة
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'تم حذف الخدمة بنجاح');
    }
}
