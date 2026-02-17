<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('provider');
    }

    /**
     * عرض جميع خدمات مزود الخدمة
     */
    public function index()
    {
        try {
            $services = auth()->user()->services()->with('category')->latest()->paginate(10);

            return view('provider.services.index', compact('services'));
        } catch (Exception $e) {
            Log::error('Error in Provider/ServiceController@index: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الخدمات');
        }
    }

    /**
     * عرض نموذج إنشاء خدمة جديدة
     */
    public function create()
    {
        try {
            $categories = Category::where('is_active', true)->get();

            return view('provider.services.create', compact('categories'));
        } catch (Exception $e) {
            Log::error('Error in Provider/ServiceController@create: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('provider.services.index')->with('error', 'حدث خطأ أثناء تحميل الصفحة');
        }
    }

    /**
     * حفظ خدمة جديدة
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'location' => 'nullable|string|max:255',
                'contact_phone' => 'nullable|string|max:20',
                'contact_email' => 'nullable|email',
                'is_featured' => 'boolean',
            ]);

            $data = $validated;
            $data['user_id'] = auth()->id();
            $data['is_active'] = true;
            $data['slug'] = $this->generateUniqueSlug($validated['title']);

            // رفع الصورة
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('services', 'public');
            }

            $service = Service::create($data);

            Log::info('Provider service created', [
                'service_id' => $service->id,
                'provider_id' => auth()->id()
            ]);

            return redirect()->route('provider.services.index')
                ->with('success', 'تم إنشاء الخدمة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in Provider/ServiceController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->except(['image'])
            ]);
            return back()->with('error', 'حدث خطأ أثناء إنشاء الخدمة')->withInput();
        }
    }

    /**
     * إنشاء slug فريد
     */
    private function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (Service::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * عرض نموذج تعديل خدمة
     */
    public function edit(Service $service)
    {
        try {
            // التأكد من أن الخدمة تخص المستخدم الحالي
            if ($service->user_id !== auth()->id()) {
                abort(403, 'غير مصرح لك بتعديل هذه الخدمة');
            }

            $categories = Category::where('is_active', true)->get();

            return view('provider.services.edit', compact('service', 'categories'));
        } catch (Exception $e) {
            Log::error('Error in Provider/ServiceController@edit: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $service->id ?? null
            ]);
            return redirect()->route('provider.services.index')->with('error', 'حدث خطأ أثناء تحميل الصفحة');
        }
    }

    /**
     * تحديث خدمة
     */
    public function update(Request $request, Service $service)
    {
        try {
            // التأكد من أن الخدمة تخص المستخدم الحالي
            if ($service->user_id !== auth()->id()) {
                abort(403, 'غير مصرح لك بتعديل هذه الخدمة');
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'location' => 'nullable|string|max:255',
                'contact_phone' => 'nullable|string|max:20',
                'contact_email' => 'nullable|email',
                'is_featured' => 'boolean',
            ]);

            $data = $validated;

            // إنشاء slug فريد إذا تغير العنوان
            if ($request->title !== $service->title) {
                $data['slug'] = $this->generateUniqueSlugForUpdate($validated['title'], $service->id);
            }

            // رفع صورة جديدة
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة
                if ($service->image) {
                    Storage::disk('public')->delete($service->image);
                }

                $data['image'] = $request->file('image')->store('services', 'public');
            }

            $service->update($data);

            Log::info('Provider service updated', [
                'service_id' => $service->id,
                'provider_id' => auth()->id()
            ]);

            return redirect()->route('provider.services.index')
                ->with('success', 'تم تحديث الخدمة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in Provider/ServiceController@update: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $service->id ?? null
            ]);
            return back()->with('error', 'حدث خطأ أثناء تحديث الخدمة')->withInput();
        }
    }

    /**
     * إنشاء slug فريد للتحديث
     */
    private function generateUniqueSlugForUpdate(string $title, int $serviceId): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (Service::withTrashed()->where('slug', $slug)->where('id', '!=', $serviceId)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * حذف خدمة
     */
    public function destroy(Service $service)
    {
        try {
            // التأكد من أن الخدمة تخص المستخدم الحالي
            if ($service->user_id !== auth()->id()) {
                abort(403, 'غير مصرح لك بحذف هذه الخدمة');
            }

            // حذف الصورة
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            $serviceId = $service->id;
            $service->delete();

            Log::info('Provider service deleted', [
                'service_id' => $serviceId,
                'provider_id' => auth()->id()
            ]);

            return redirect()->route('provider.services.index')
                ->with('success', 'تم حذف الخدمة بنجاح');
        } catch (Exception $e) {
            Log::error('Error in Provider/ServiceController@destroy: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $service->id ?? null
            ]);
            return redirect()->route('provider.services.index')
                ->with('error', 'حدث خطأ أثناء حذف الخدمة');
        }
    }

    /**
     * تغيير حالة الخدمة
     */
    public function toggleStatus(Service $service)
    {
        try {
            // التأكد من أن الخدمة تخص المستخدم الحالي
            if ($service->user_id !== auth()->id()) {
                abort(403, 'غير مصرح لك بتغيير حالة هذه الخدمة');
            }

            $service->update(['is_active' => !$service->is_active]);

            $status = $service->is_active ? 'تفعيل' : 'إلغاء تفعيل';

            Log::info('Provider service status toggled', [
                'service_id' => $service->id,
                'new_status' => $service->is_active,
                'provider_id' => auth()->id()
            ]);

            return redirect()->route('provider.services.index')
                ->with('success', "تم $status الخدمة بنجاح");
        } catch (Exception $e) {
            Log::error('Error in Provider/ServiceController@toggleStatus: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $service->id ?? null
            ]);
            return redirect()->route('provider.services.index')
                ->with('error', 'حدث خطأ أثناء تغيير حالة الخدمة');
        }
    }
}
