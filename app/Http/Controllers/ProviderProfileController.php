<?php

namespace App\Http\Controllers;

use App\Models\ProviderProfile;
use App\Models\ProviderCategory;
use App\Models\ProviderCity;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\City;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class ProviderProfileController extends Controller
{
    /**
     * عرض صفحة إكمال الملف الشخصي
     */
    public function completeProfile()
    {
        // التأكد من أن المستخدم مزود خدمة
        $user = Auth::user();
        if (!$user || !$user->isProvider()) {
            return redirect()->route('home')->with('error', 'هذه الصفحة متاحة لمزودي الخدمة فقط');
        }
        $profile = $user->providerProfile;

        // إذا كان الملف الشخصي مكتمل، توجيه إلى صفحة العرض
        if ($profile && $profile->isProfileComplete()) {
            return redirect()->route('provider.profile')->with('info', 'الملف الشخصي مكتمل بالفعل');
        }

        $categories = Category::where('is_active', true)->get();
        $cities = City::getActiveCities();
        $maxCategories = SystemSetting::get('provider_max_categories', 3);
        $maxCities = SystemSetting::get('provider_max_cities', 10);

        // تحميل العلاقات للتأكد من عملها
        if ($profile) {
            $profile->load(['activeCategories', 'activeCities']);
        }

        return view('provider.complete-profile', compact('profile', 'categories', 'cities', 'maxCategories', 'maxCities'));
    }

    /**
     * عرض صفحة تعديل الملف الشخصي
     */
    public function edit()
    {
        try {
            // التأكد من أن المستخدم مزود خدمة
            $user = Auth::user();
            if (!$user || !$user->isProvider()) {
                return redirect()->route('home')->with('error', 'هذه الصفحة متاحة لمزودي الخدمة فقط');
            }
            $profile = $user->providerProfile;

            // إذا لم يكن لديه ملف شخصي، توجيه إلى صفحة الإكمال
            if (!$profile) {
                return redirect()->route('provider.complete-profile')->with('error', 'يجب إنشاء ملف شخصي أولاً');
            }

            // تحميل العلاقات للتأكد من عملها
            $profile->load(['activeCategories', 'activeCities']);

            $categories = Category::where('is_active', true)->get();
            $cities = City::getActiveCities();
            $maxCategories = SystemSetting::get('provider_max_categories', 3);
            $maxCities = SystemSetting::get('provider_max_cities', 10);

            return view('provider.edit-profile', compact('profile', 'categories', 'cities', 'maxCategories', 'maxCities'));
        } catch (Exception $e) {
            Log::error('Error in ProviderProfileController@edit: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('provider.profile')->with('error', 'حدث خطأ أثناء تحميل الصفحة');
        }
    }

    /**
     * تحديث الملف الشخصي
     */
    public function update(Request $request)
    {
        try {
            // التحقق من أن المستخدم مزود خدمة
            $user = Auth::user();
            if (!$user || !$user->isProvider()) {
                return redirect()->route('home')->with('error', 'هذه الصفحة متاحة لمزودي الخدمة فقط');
            }

            $validated = $request->validate([
                'bio' => 'required|string|max:1000',
                'address' => 'required|string|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'categories' => 'required|array|min:1',
                'categories.*' => 'exists:categories,id',
                'cities' => 'required|array|min:1',
                'cities.*' => 'exists:cities,id',
            ]);

            $user = Auth::user();
            $profile = $user->providerProfile;
            $maxCategories = SystemSetting::get('provider_max_categories', 3);
            $maxCities = SystemSetting::get('provider_max_cities', 10);

            // التحقق من عدد الأقسام والمدن
            $this->validateCategoriesAndCities($request, $maxCategories, $maxCities);

            // معالجة الصورة
            $imagePath = $this->handleImageUpload($request, $user);

            // تحديث بيانات المستخدم
            if ($imagePath) {
                $user->update(['image' => $imagePath]);
            }

            // تحديث الملف الشخصي
            $profile->update([
                'bio' => $request->bio,
                'phone' => $user->phone,
                'address' => $request->address,
            ]);

            // حفظ الأقسام والمدن
            $this->saveCategoriesAndCities($user, $request);

            Log::info('Provider profile updated', [
                'user_id' => $user->id,
                'profile_id' => $profile->id
            ]);

            return redirect()->route('provider.profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in ProviderProfileController@update: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->except(['image']),
                'user_id' => Auth::id()
            ]);
            return back()->with('error', 'حدث خطأ أثناء تحديث الملف الشخصي')->withInput();
        }
    }

    /**
     * حفظ الملف الشخصي
     */
    public function store(Request $request)
    {
        try {
            // التحقق من أن المستخدم مزود خدمة
            if (!Auth::user()->isProvider()) {
                return redirect()->route('home')->with('error', 'هذه الصفحة متاحة لمزودي الخدمة فقط');
            }

            $validated = $request->validate([
                'bio' => 'required|string|max:1000',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'categories' => 'required|array|min:1',
                'categories.*' => 'exists:categories,id',
                'cities' => 'required|array|min:1',
                'cities.*' => 'exists:cities,id',
            ]);

            $user = Auth::user();
            $maxCategories = SystemSetting::get('provider_max_categories', 3);
            $maxCities = SystemSetting::get('provider_max_cities', 10);

            // التحقق من عدد الأقسام والمدن
            $this->validateCategoriesAndCities($request, $maxCategories, $maxCities);

            // معالجة الصورة
            $imagePath = $this->handleImageUpload($request, $user);

            // تحديث بيانات المستخدم
            $this->updateUserData($user, $request, $imagePath);

            // إنشاء أو تحديث الملف الشخصي
            $profile = $this->createOrUpdateProfile($user, $request, $maxCategories, $maxCities);

            // حفظ الأقسام والمدن
            $this->saveCategoriesAndCities($user, $request);

            Log::info('Provider profile created/updated', [
                'user_id' => $user->id,
                'profile_id' => $profile->id
            ]);

            return redirect()->route('provider.profile')->with('success', 'تم حفظ الملف الشخصي بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in ProviderProfileController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->except(['image']),
                'user_id' => Auth::id()
            ]);
            return back()->with('error', 'حدث خطأ أثناء حفظ الملف الشخصي')->withInput();
        }
    }

    /**
     * التحقق من عدد الأقسام والمدن
     */
    private function validateCategoriesAndCities(Request $request, int $maxCategories, int $maxCities): void
    {
        if (count($request->categories) > $maxCategories) {
            throw new \Exception("يمكنك اختيار حد أقصى {$maxCategories} أقسام");
        }

        if (count($request->cities) > $maxCities) {
            throw new \Exception("يمكنك اختيار حد أقصى {$maxCities} مدن");
        }
    }

    /**
     * معالجة رفع الصورة
     */
    private function handleImageUpload(Request $request, $user): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        return $request->file('image')->store('users', 'public');
    }

    /**
     * تحديث بيانات المستخدم
     */
    private function updateUserData($user, Request $request, ?string $imagePath): void
    {
        $updateData = [];

        if ($imagePath) {
            $updateData['image'] = $imagePath;
        }

        if ($request->phone && $request->phone !== $user->phone) {
            $updateData['phone'] = $request->phone;
        }

        if (!empty($updateData)) {
            $user->update($updateData);
        }
    }

    /**
     * إنشاء أو تحديث الملف الشخصي
     */
    private function createOrUpdateProfile($user, Request $request, int $maxCategories, int $maxCities): ProviderProfile
    {
        return $user->providerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => $request->bio,
                'phone' => $request->phone,
                'address' => $request->address,
                'max_categories' => $maxCategories,
                'max_cities' => $maxCities,
                'is_verified' => SystemSetting::get('provider_auto_approve', false),
                'is_active' => SystemSetting::get('provider_auto_approve', false),
            ]
        );
    }

    /**
     * حفظ الأقسام والمدن
     */
    private function saveCategoriesAndCities($user, Request $request): void
    {
        // حذف القديمة
        $user->providerCategories()->delete();
        $user->providerCities()->delete();

        // إضافة الأقسام الجديدة مع الأقسام الفرعية
        foreach ($request->categories as $categoryId) {
            if ($request->has("sub_categories.{$categoryId}") && !empty($request->input("sub_categories.{$categoryId}"))) {
                foreach ($request->input("sub_categories.{$categoryId}") as $subCatId) {
                    ProviderCategory::create([
                        'user_id' => $user->id,
                        'category_id' => $categoryId,
                        'sub_category_id' => $subCatId,
                        'is_active' => true,
                    ]);
                }
            } else {
                ProviderCategory::create([
                    'user_id' => $user->id,
                    'category_id' => $categoryId,
                    'sub_category_id' => null,
                    'is_active' => true,
                ]);
            }
        }

        // إضافة المدن الجديدة
        foreach ($request->cities as $cityId) {
            ProviderCity::create([
                'user_id' => $user->id,
                'city_id' => $cityId,
                'is_active' => true,
            ]);
        }
    }

    /**
     * عرض الملف الشخصي (للمزود نفسه فقط)
     */
    public function show()
    {
        try {
            // التحقق من المستخدم
            $user = Auth::user();

            // يجب أن يكون مسجل دخول ومزود خدمة
            if (!$user || !$user->isProvider()) {
                return redirect()->route('home')->with('error', 'هذه الصفحة متاحة لمزودي الخدمة فقط');
            }

            // الحصول على الملف الشخصي
            $profile = $user->providerProfile;
            if (!$profile) {
                return redirect()->route('provider.complete-profile')->with('error', 'يجب إكمال الملف الشخصي أولاً');
            }

            // تحديد ما إذا كان المستخدم الحالي هو صاحب الملف الشخصي
            $isOwner = true; // دائماً true لأن هذا ملف المزود نفسه

            // تحميل الأقسام النشطة مع علاقاتها
            try {
                $activeCategories = ProviderCategory::where('user_id', $user->id)
                    ->where('is_active', true)
                    ->with(['category', 'subCategory'])
                    ->get()
                    ->filter(function ($item) {
                        return $item->category !== null;
                    })
                    ->values();
            } catch (\Exception $e) {
                Log::warning('Error loading categories: ' . $e->getMessage());
                $activeCategories = collect();
            }

            // تحميل المدن النشطة مع علاقاتها
            try {
                $activeCities = ProviderCity::where('user_id', $user->id)
                    ->where('is_active', true)
                    ->with('city')
                    ->get()
                    ->filter(function ($item) {
                        return $item->city !== null;
                    })
                    ->values();
            } catch (\Exception $e) {
                Log::warning('Error loading cities: ' . $e->getMessage());
                $activeCities = collect();
            }

            // القيم الافتراضية
            $maxCategories = $profile->max_categories ?? 3;
            $maxCities = $profile->max_cities ?? 10;

            // حساب الإمكانيات
            $canAddCategory = $activeCategories->count() < $maxCategories;
            $canAddCity = $activeCities->count() < $maxCities;

            // التأكد من وجود البيانات الأساسية
            $profile->bio = $profile->bio ?? '';
            $profile->phone = $profile->phone ?? '';
            $profile->address = $profile->address ?? '';
            $profile->rating = $profile->rating ?? 0;
            $profile->completed_services = $profile->completed_services ?? 0;

            // تحديد ما إذا كان المستخدم الحالي هو صاحب الملف الشخصي
            $isOwner = true; // دائماً true لأن هذا ملف المزود نفسه
            $provider = $user; // المستخدم الحالي هو المزود

            return view('provider.profile', compact(
                'profile',
                'activeCategories',
                'activeCities',
                'canAddCategory',
                'canAddCity',
                'maxCategories',
                'maxCities',
                'isOwner',
                'provider'
            ));
        } catch (\Exception $e) {
            Log::error('Error in ProviderProfileController@show: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id()
            ]);

            if (config('app.debug')) {
                return redirect()->route('home')->with('error', 'خطأ: ' . $e->getMessage() . ' في ' . basename($e->getFile()) . ':' . $e->getLine());
            }

            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الملف الشخصي');
        }
    }

    /**
     * عرض الملف الشخصي (متاح للجميع)
     */
    public function showPublic($userId = null)
    {
        try {
            // إذا لم يتم تحديد userId، استخدم المستخدم الحالي
            if (!$userId) {
                $userId = Auth::id();
            }

            // إذا لم يكن هناك userId، توجيه للصفحة الرئيسية
            if (!$userId) {
                return redirect()->route('home')->with('error', 'يجب تحديد مزود الخدمة');
            }

            $provider = \App\Models\User::where('id', $userId)
                ->where('is_active', true)
                ->firstOrFail();

            // إذا لم يكن مزود خدمة، توجيه إلى صفحة المستخدم العادي
            if (!$provider->isProvider()) {
                return redirect()->route('user.profile.public', $userId);
            }

            $profile = $provider->providerProfile;

            // إذا لم يكن لديه ملف شخصي، عرض صفحة بسيطة
            if (!$profile) {
                return view('provider.public-profile', [
                    'provider' => $provider,
                    'profile' => null,
                    'services' => collect(),
                    'ratings' => collect(),
                    'activeCategories' => collect(),
                    'activeCities' => collect()
                ]);
            }

            // التحقق من أن الملف الشخصي نشط
            if (!$profile->is_active) {
                return redirect()->route('home')->with('error', 'الملف الشخصي غير نشط');
            }

            // تحميل الأقسام والمدن النشطة
            try {
                $activeCategories = ProviderCategory::where('user_id', $provider->id)
                    ->where('is_active', true)
                    ->with(['category', 'subCategory'])
                    ->get()
                    ->filter(function ($item) {
                        return $item->category !== null;
                    })
                    ->values();
            } catch (\Exception $e) {
                Log::warning('Error loading activeCategories in showPublic: ' . $e->getMessage());
                $activeCategories = collect();
            }

            try {
                $activeCities = ProviderCity::where('user_id', $provider->id)
                    ->where('is_active', true)
                    ->with('city')
                    ->get()
                    ->filter(function ($item) {
                        return $item->city !== null;
                    })
                    ->values();
            } catch (\Exception $e) {
                Log::warning('Error loading activeCities in showPublic: ' . $e->getMessage());
                $activeCities = collect();
            }

            // جلب الخدمات النشطة للمزود
            $services = \App\Models\Service::where('user_id', $provider->id)
                ->where('is_active', true)
                ->with(['category', 'city'])
                ->latest()
                ->take(6)
                ->get();

            // جلب التقييمات الأخيرة
            $ratings = \App\Models\ServiceOffer::where('provider_id', $provider->id)
                ->where('status', 'delivered')
                ->whereNotNull('rating')
                ->with(['service', 'service.user'])
                ->latest()
                ->take(5)
                ->get();

            // تحديد ما إذا كان المستخدم الحالي هو صاحب الملف الشخصي
            $isOwner = Auth::check() && Auth::id() == $provider->id;

            return view('provider.profile', compact(
                'profile',
                'activeCategories',
                'activeCities',
                'services',
                'ratings',
                'isOwner',
                'provider'
            ));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('home')->with('error', 'الملف الشخصي غير موجود');
        } catch (Exception $e) {
            Log::error('Error in ProviderProfileController@showPublic: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $userId
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الملف الشخصي');
        }
    }

    /**
     * عرض الملف الشخصي العام لمزود الخدمة
     */
    public function publicProfile($userId)
    {
        try {
            $provider = \App\Models\User::where('id', $userId)
                ->where('is_active', true)
                ->firstOrFail();

            // إذا لم يكن مزود خدمة، توجيه إلى صفحة المستخدم العادي
            if (!$provider->isProvider()) {
                return redirect()->route('user.profile.public', $userId);
            }

            $profile = $provider->providerProfile;

            // إذا لم يكن لديه ملف شخصي، عرض صفحة بسيطة
            if (!$profile) {
                // عرض صفحة بسيطة بدون معلومات إضافية
                return view('provider.public-profile', [
                    'provider' => $provider,
                    'profile' => null,
                    'services' => collect(),
                    'ratings' => collect(),
                    'activeCategories' => collect(),
                    'activeCities' => collect()
                ]);
            }

            // التحقق من أن الملف الشخصي نشط
            if (!$profile->is_active) {
                return redirect()->route('home')->with('error', 'الملف الشخصي غير نشط');
            }

            // تحميل الأقسام والمدن النشطة
            try {
                $activeCategories = ProviderCategory::where('user_id', $provider->id)
                    ->where('is_active', true)
                    ->with(['category', 'subCategory'])
                    ->get()
                    ->filter(function ($item) {
                        return $item->category !== null;
                    })
                    ->values();
            } catch (\Exception $e) {
                Log::warning('Error loading activeCategories in publicProfile: ' . $e->getMessage());
                $activeCategories = collect();
            }

            try {
                $activeCities = ProviderCity::where('user_id', $provider->id)
                    ->where('is_active', true)
                    ->with('city')
                    ->get()
                    ->filter(function ($item) {
                        return $item->city !== null;
                    })
                    ->values();
            } catch (\Exception $e) {
                Log::warning('Error loading activeCities in publicProfile: ' . $e->getMessage());
                $activeCities = collect();
            }

            // جلب الخدمات النشطة للمزود
            $services = \App\Models\Service::where('user_id', $provider->id)
                ->where('is_active', true)
                ->with(['category', 'city'])
                ->latest()
                ->take(6)
                ->get();

            // جلب التقييمات الأخيرة
            $ratings = \App\Models\ServiceOffer::where('provider_id', $provider->id)
                ->where('status', 'delivered')
                ->whereNotNull('rating')
                ->with(['service', 'service.user'])
                ->latest()
                ->take(5)
                ->get();

            return view('provider.public-profile', compact('provider', 'profile', 'services', 'ratings', 'activeCategories', 'activeCities'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('home')->with('error', 'الملف الشخصي غير موجود');
        } catch (Exception $e) {
            Log::error('Error in ProviderProfileController@publicProfile: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $userId
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الملف الشخصي');
        }
    }



    /**
     * إضافة قسم جديد
     */
    public function addCategory(Request $request)
    {
        try {
            // التأكد من أن المستخدم مزود خدمة
            $user = Auth::user();
            if (!$user || !$user->isProvider()) {
                return $this->errorResponse('غير مصرح', 403);
            }

            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'nullable|exists:sub_categories,id',
                'description' => 'nullable|string|max:500',
                'hourly_rate' => 'nullable|numeric|min:0',
                'experience_years' => 'nullable|integer|min:0|max:50',
            ]);

            $user = Auth::user();
            $profile = $user->providerProfile;

            if (!$profile) {
                return $this->errorResponse('يجب إكمال الملف الشخصي أولاً', 400);
            }

            if (!$profile->canAddCategory()) {
                return $this->errorResponse('لا يمكن إضافة المزيد من الأقسام', 400);
            }

            // التحقق من عدم وجود القسم مسبقاً
            $existingQuery = $user->providerCategories()->where('category_id', $validated['category_id']);

            if ($validated['sub_category_id'] ?? null) {
                if ($existingQuery->where('sub_category_id', $validated['sub_category_id'])->exists()) {
                    return $this->errorResponse('هذا القسم الفرعي مضاف مسبقاً', 400);
                }
            } else {
                if ($existingQuery->whereNull('sub_category_id')->exists()) {
                    return $this->errorResponse('هذا القسم مضاف مسبقاً', 400);
                }
            }

            ProviderCategory::create([
                'user_id' => $user->id,
                'category_id' => $validated['category_id'],
                'sub_category_id' => $validated['sub_category_id'] ?? null,
                'description' => $validated['description'] ?? null,
                'hourly_rate' => $validated['hourly_rate'] ?? null,
                'experience_years' => $validated['experience_years'] ?? null,
                'is_active' => true,
            ]);

            Log::info('Provider category added', [
                'user_id' => $user->id,
                'category_id' => $validated['category_id']
            ]);

            return $this->successResponse(null, 'تم إضافة القسم بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('بيانات غير صحيحة', 422, $e->errors());
        } catch (Exception $e) {
            Log::error('Error in ProviderProfileController@addCategory: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            return $this->errorResponse('حدث خطأ أثناء إضافة القسم', 500);
        }
    }

    /**
     * إضافة مدينة جديدة
     */
    public function addCity(Request $request)
    {
        try {
            // التأكد من أن المستخدم مزود خدمة
            $user = Auth::user();
            if (!$user || !$user->isProvider()) {
                return $this->errorResponse('غير مصرح', 403);
            }

            $validated = $request->validate([
                'city_id' => 'required|exists:cities,id',
                'notes' => 'nullable|string|max:500',
            ]);

            $user = Auth::user();
            $profile = $user->providerProfile;

            if (!$profile) {
                return $this->errorResponse('يجب إكمال الملف الشخصي أولاً', 400);
            }

            if (!$profile->canAddCity()) {
                return $this->errorResponse('لا يمكن إضافة المزيد من المدن', 400);
            }

            // التحقق من عدم وجود المدينة مسبقاً
            if ($user->providerCities()->where('city_id', $validated['city_id'])->exists()) {
                return $this->errorResponse('هذه المدينة مضافة مسبقاً', 400);
            }

            ProviderCity::create([
                'user_id' => $user->id,
                'city_id' => $validated['city_id'],
                'notes' => $validated['notes'] ?? null,
                'is_active' => true,
            ]);

            Log::info('Provider city added', [
                'user_id' => $user->id,
                'city_id' => $validated['city_id']
            ]);

            return $this->successResponse(null, 'تم إضافة المدينة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->errorResponse('بيانات غير صحيحة', 422, $e->errors());
        } catch (Exception $e) {
            Log::error('Error in ProviderProfileController@addCity: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            return $this->errorResponse('حدث خطأ أثناء إضافة المدينة', 500);
        }
    }

    /**
     * حذف قسم
     */
    public function removeCategory($id)
    {
        try {
            // التأكد من أن المستخدم مزود خدمة
            $user = Auth::user();
            if (!$user || !$user->isProvider()) {
                return $this->errorResponse('غير مصرح', 403);
            }

            $category = ProviderCategory::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$category) {
                return $this->errorResponse('القسم غير موجود', 404);
            }

            $category->delete();

            Log::info('Provider category removed', [
                'category_id' => $id,
                'user_id' => Auth::id()
            ]);

            return $this->successResponse(null, 'تم حذف القسم بنجاح');
        } catch (Exception $e) {
            Log::error('Error in ProviderProfileController@removeCategory: ' . $e->getMessage(), [
                'exception' => $e,
                'category_id' => $id
            ]);
            return $this->errorResponse('حدث خطأ أثناء حذف القسم', 500);
        }
    }

    /**
     * حذف مدينة
     */
    public function removeCity($id)
    {
        try {
            // التأكد من أن المستخدم مزود خدمة
            $user = Auth::user();
            if (!$user || !$user->isProvider()) {
                return $this->errorResponse('غير مصرح', 403);
            }

            $city = ProviderCity::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            if (!$city) {
                return $this->errorResponse('المدينة غير موجودة', 404);
            }

            $city->delete();

            Log::info('Provider city removed', [
                'city_id' => $id,
                'user_id' => Auth::id()
            ]);

            return $this->successResponse(null, 'تم حذف المدينة بنجاح');
        } catch (Exception $e) {
            Log::error('Error in ProviderProfileController@removeCity: ' . $e->getMessage(), [
                'exception' => $e,
                'city_id' => $id
            ]);
            return $this->errorResponse('حدث خطأ أثناء حذف المدينة', 500);
        }
    }
}
