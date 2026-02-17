<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;
use App\Models\City;
use App\Models\ServiceOffer;
use App\Models\CategoryField;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class ServiceController extends Controller
{
    /**
     * عرض الخدمات
     */
    public function index(Request $request)
    {
        try {
            // بناء الاستعلام حسب نوع المستخدم
            $query = $this->buildServiceQuery($request);

            // تطبيق الفلاتر
            $this->applyFilters($query, $request);

            // جلب البيانات
            $services = $query->latest()->paginate(12);
            $categories = Category::where('is_active', true)->get();
            $cities = City::getActiveCities();
            $subCategories = $this->getSubCategories($request);

            return view('services.index', compact('services', 'categories', 'subCategories', 'cities'));
        } catch (Exception $e) {
            Log::error('Error in ServiceController@index: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الخدمات');
        }
    }

    /**
     * بناء استعلام الخدمات حسب نوع المستخدم
     */
    private function buildServiceQuery(Request $request)
    {
        if (!auth()->check()) {
            return Service::where('is_active', true)
                ->with(['category', 'subCategory', 'user', 'city']);
        }

        if (auth()->user()->isProvider()) {
            return Service::where('is_active', true)
                ->with(['category', 'subCategory', 'user', 'city', 'offers' => function ($q) {
                    $q->where('provider_id', auth()->id());
                }]);
        }

        return Service::where('user_id', auth()->id())
            ->with(['category', 'subCategory', 'user', 'city']);
    }

    /**
     * تطبيق الفلاتر على الاستعلام
     */
    private function applyFilters($query, Request $request): void
    {
        // البحث
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('location', 'like', "%{$searchTerm}%");
            });
        }

        // تصفية حسب القسم
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // تصفية حسب القسم الفرعي
        if ($request->filled('sub_category')) {
            $query->where('sub_category_id', $request->sub_category);
        }

        // تصفية حسب المدينة
        if ($request->filled('city')) {
            $query->where('city_id', $request->city);
        }
    }

    /**
     * جلب الأقسام الفرعية
     */
    private function getSubCategories(Request $request)
    {
        if (!$request->filled('category')) {
            return collect();
        }

        return \App\Models\SubCategory::where('category_id', $request->category)
            ->where('status', true)
            ->get();
    }

    /**
     * عرض خدمة معينة
     */
    public function show($slug)
    {
        $service = Service::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'subCategory', 'user', 'city'])
            ->firstOrFail();

        // التحقق من إمكانية مزود الخدمة تقديم عرض
        $canProviderOffer = false;
        $userOffer = null;

        if (auth()->check() && auth()->user()->isProvider() && auth()->id() !== $service->user_id) {
            $canProviderOffer = $this->canProviderOfferService(auth()->user(), $service);

            // جلب العرض المقدم من المستخدم الحالي لهذه الخدمة
            $userOffer = ServiceOffer::where('service_id', $service->id)
                ->where('provider_id', auth()->id())
                ->first();
        }

        // الخدمات المشابهة
        $relatedServicesQuery = Service::where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->where('is_active', true)
            ->with(['category', 'subCategory', 'user', 'city']);

        // إذا كان المستخدم مسجل دخول وليس مزود خدمة، اعرض فقط خدماته
        if (auth()->check() && !auth()->user()->isProvider()) {
            $relatedServicesQuery->where('user_id', auth()->id());
        }

        $relatedServices = $relatedServicesQuery->limit(6)->get();

        return view('services.show', compact('service', 'relatedServices', 'canProviderOffer', 'userOffer'));
    }

    /**
     * البحث في الخدمات
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return redirect()->route('services.index');
        }

        // إذا كان المستخدم مسجل دخول
        if (auth()->check()) {
            // إذا كان مزود خدمة، ابحث في جميع الخدمات
            if (auth()->user()->isProvider()) {
                $services = Service::where('is_active', true)
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                            ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with(['category', 'user', 'city'])
                    ->latest()
                    ->paginate(12);
            } else {
                // إذا كان مستخدم عادي، ابحث في خدماته فقط
                $services = Service::where('user_id', auth()->id())
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                            ->orWhere('description', 'like', "%{$query}%");
                    })
                    ->with(['category', 'user', 'city'])
                    ->latest()
                    ->paginate(12);
            }
        } else {
            // إذا لم يكن مسجل دخول، ابحث في جميع الخدمات النشطة
            $services = Service::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->with(['category', 'user', 'city'])
                ->latest()
                ->paginate(12);
        }

        $categories = Category::where('is_active', true)->get();
        $cities = City::getActiveCities();

        return view('services.search', compact('services', 'categories', 'cities', 'query'));
    }

    /**
     * عرض صفحة طلب الخدمة
     */
    public function request(Category $category)
    {
        // التأكد من أن المستخدم مسجل دخول
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول لطلب الخدمة');
        }

        // تحميل الحقول المخصصة مع القسم مرتبة حسب الترتيب
        $category->load(['fields' => function ($query) {
            $query->where('is_active', true)->orderBy('sort_order', 'asc');
        }, 'subCategories']);

        // التحقق من وجود أقسام فرعية
        $hasSubCategories = $category->subCategories && $category->subCategories->count() > 0;
        $selectedSubCategoryId = request('sub_category_id');
        $selectedSubCategory = null;

        // إذا كان القسم يحتوي على أقسام فرعية، يجب اختيار قسم فرعي
        if ($hasSubCategories && !$selectedSubCategoryId) {
            return redirect()->route('categories.show', $category->slug)
                ->with('error', 'يرجى اختيار قسم فرعي لطلب الخدمة');
        }

        // جلب القسم الفرعي المحدد إذا كان موجوداً
        if ($selectedSubCategoryId) {
            $selectedSubCategory = $category->subCategories()
                ->where('id', $selectedSubCategoryId)
                ->where('status', true)
                ->first();

            // التحقق من صحة القسم الفرعي
            if (!$selectedSubCategory) {
                return redirect()->route('categories.show', $category->slug)
                    ->with('error', 'القسم الفرعي المحدد غير صحيح');
            }
        }

        // جلب المدن المرتبطة بهذا القسم فقط
        $cities = $category->activeCities()->orderBy('sort_order')->orderBy('name_ar')->get();

        return view('services.request', compact('category', 'cities', 'selectedSubCategory', 'selectedSubCategoryId', 'hasSubCategories'));
    }

    /**
     * حفظ طلب الخدمة
     */
    public function store(Request $request)
    {
        try {
            // التحقق من تسجيل الدخول
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول لطلب الخدمة');
            }

            $user = auth()->user();
            if (!$user || !$user->id) {
                return redirect()->route('login')->with('error', 'خطأ في بيانات المستخدم، يرجى تسجيل الدخول مرة أخرى');
            }

            // التحقق من صحة البيانات
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'nullable|exists:sub_categories,id',
                'city_id' => 'required|exists:cities,id',
                'notes' => 'nullable|string|max:1000',
                'voice_note' => 'nullable|string|max:16777215',
                'custom_fields.*' => 'nullable',
            ]);

            // التحقق من صحة القسم والمدينة
            $validationResult = $this->validateCategoryAndCity($request);
            if ($validationResult !== true) {
                return $validationResult;
            }

            // التحقق من الحقول المخصصة المطلوبة
            $category = Category::findOrFail($request->category_id);
            $customFieldsValidation = $this->validateCustomFields($request, $category);
            if ($customFieldsValidation !== true) {
                return $customFieldsValidation;
            }

            // معالجة الحقول المخصصة
            $processedFields = $this->processCustomFields($request->custom_fields ?? []);

            // إنشاء الخدمة
            $service = $this->createService($validated, $user, $processedFields);

            Log::info('Service created successfully', [
                'service_id' => $service->id,
                'user_id' => $user->id
            ]);

            // إرسال إشعار لجميع المزودين المشتركين في نفس القسم والمدينة
            try {
                Notification::notifyProvidersForNewService($service);
            } catch (\Exception $e) {
                Log::error('Failed to notify providers for new service', [
                    'service_id' => $service->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return redirect()->route('services.show', $service->slug)
                ->with('success', 'تم إرسال طلب الخدمة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in ServiceController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->except(['custom_fields', 'voice_note']),
                'user_id' => auth()->id()
            ]);
            return back()->with('error', 'حدث خطأ أثناء إنشاء الخدمة. يرجى المحاولة مرة أخرى')->withInput();
        }
    }

    /**
     * التحقق من صحة القسم والمدينة
     */
    private function validateCategoryAndCity(Request $request)
    {
        $category = Category::findOrFail($request->category_id);
        $availableCityIds = $category->activeCities()->pluck('cities.id')->toArray();

        if (!in_array($request->city_id, $availableCityIds)) {
            return back()->withErrors(['city_id' => 'المدينة المختارة غير متاحة لهذا القسم'])->withInput();
        }

        $hasSubCategories = $category->subCategories && $category->subCategories->count() > 0;

        if ($hasSubCategories && !$request->sub_category_id) {
            return back()->withErrors(['sub_category_id' => 'يرجى اختيار قسم فرعي لطلب الخدمة'])->withInput();
        }

        if ($request->sub_category_id) {
            $subCategory = $category->subCategories()
                ->where('id', $request->sub_category_id)
                ->where('status', true)
                ->first();

            if (!$subCategory) {
                return back()->withErrors(['sub_category_id' => 'القسم الفرعي المحدد غير صحيح'])->withInput();
            }
        }

        return true;
    }

    /**
     * التحقق من الحقول المخصصة المطلوبة
     */
    private function validateCustomFields(Request $request, Category $category)
    {
        // جلب جميع الحقول المخصصة النشطة للقسم
        $allFields = CategoryField::where('category_id', $category->id)
            ->where('is_active', true)
            ->get();

        $errors = [];
        $customFields = $request->custom_fields ?? [];

        foreach ($allFields as $field) {
            $fieldName = $field->name;
            $fieldValue = $customFields[$fieldName] ?? null;

            // التحقق من الحقول المطلوبة فقط أولاً
            if ($field->is_required) {
                // التحقق من وجود القيمة
                if ($fieldValue === null || $fieldValue === '') {
                    $fieldLabel = app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en;
                    $errors["custom_fields.{$fieldName}"] = "حقل '{$fieldLabel}' مطلوب";
                    continue;
                }
            }

            // التحقق حسب نوع الحقل (لجميع الحقول - مطلوبة وغير مطلوبة)
            switch ($field->type) {
                case 'image':
                    // التحقق من الصور (حتى لو كانت اختيارية)
                    if ($fieldValue !== null && $fieldValue !== '') {
                        $imageValidation = $this->validateImageField($fieldValue, $field, $fieldName);
                        if ($imageValidation !== true) {
                            $errors = array_merge($errors, $imageValidation);
                        }
                    }
                    break;

                case 'select':
                    // للقوائم المنسدلة، يجب التحقق من أن القيمة موجودة في الخيارات
                    if (is_array($fieldValue)) {
                        // للحقول القابلة للتكرار
                        foreach ($fieldValue as $value) {
                            if ($value === null || $value === '') {
                                $fieldLabel = app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en;
                                $errors["custom_fields.{$fieldName}"] = "حقل '{$fieldLabel}' مطلوب";
                                break;
                            }
                            if ($field->options && is_array($field->options) && !in_array($value, $field->options)) {
                                $fieldLabel = app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en;
                                $errors["custom_fields.{$fieldName}"] = "القيمة المحددة لحقل '{$fieldLabel}' غير صحيحة";
                                break;
                            }
                        }
                    } else {
                        if ($field->options && is_array($field->options) && !in_array($fieldValue, $field->options)) {
                            $fieldLabel = app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en;
                            $errors["custom_fields.{$fieldName}"] = "القيمة المحددة لحقل '{$fieldLabel}' غير صحيحة";
                        }
                    }
                    break;

                case 'number':
                    // للرقم، يجب التحقق من أنه رقم صحيح
                    if (is_array($fieldValue)) {
                        foreach ($fieldValue as $value) {
                            if ($value !== null && $value !== '' && !is_numeric($value)) {
                                $fieldLabel = app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en;
                                $errors["custom_fields.{$fieldName}"] = "حقل '{$fieldLabel}' يجب أن يكون رقماً";
                                break;
                            }
                        }
                    } elseif (!is_numeric($fieldValue) && $fieldValue !== null && $fieldValue !== '') {
                        $fieldLabel = app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en;
                        $errors["custom_fields.{$fieldName}"] = "حقل '{$fieldLabel}' يجب أن يكون رقماً";
                    }
                    break;

                case 'checkbox':
                    // Checkbox يمكن أن يكون null أو 1 - لا حاجة للتحقق الإضافي
                    break;

                case 'date':
                case 'time':
                    // للتاريخ والوقت، التحقق الأساسي كافٍ
                    break;

                default:
                    // للحقول النصية، التحقق الأساسي كافٍ
                    break;
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        return true;
    }

    /**
     * التحقق من حقل الصور
     */
    private function validateImageField($fieldValue, $field, string $fieldName): array|bool
    {
        $errors = [];
        $fieldLabel = app()->getLocale() == 'ar' ? $field->name_ar : $field->name_en;
        $maxSize = 5 * 1024 * 1024; // 5MB بالبايت
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        // التحقق من وجود الملفات
        if (is_array($fieldValue)) {
            $hasValidFiles = false;
            $fileCount = 0;

            foreach ($fieldValue as $value) {
                if (is_array($value)) {
                    // ملفات متعددة في repeatable groups
                    foreach ($value as $file) {
                        if ($file && (is_object($file) && method_exists($file, 'isValid'))) {
                            $fileCount++;
                            if ($file->isValid()) {
                                $hasValidFiles = true;

                                // التحقق من نوع الملف
                                $mimeType = $file->getMimeType();
                                if (!in_array($mimeType, $allowedMimes)) {
                                    $errors["custom_fields.{$fieldName}"] = "نوع الملف غير مدعوم لحقل '{$fieldLabel}'. يجب أن يكون: " . implode(', ', $allowedExtensions);
                                    continue;
                                }

                                // التحقق من الامتداد
                                $extension = strtolower($file->getClientOriginalExtension());
                                if (!in_array($extension, $allowedExtensions)) {
                                    $errors["custom_fields.{$fieldName}"] = "امتداد الملف غير مدعوم لحقل '{$fieldLabel}'. يجب أن يكون: " . implode(', ', $allowedExtensions);
                                    continue;
                                }

                                // التحقق من حجم الملف
                                if ($file->getSize() > $maxSize) {
                                    $maxSizeMB = round($maxSize / (1024 * 1024), 1);
                                    $fileSizeMB = round($file->getSize() / (1024 * 1024), 1);
                                    $errors["custom_fields.{$fieldName}"] = "حجم الملف كبير جداً لحقل '{$fieldLabel}'. الحد الأقصى: {$maxSizeMB}MB (الحجم الحالي: {$fileSizeMB}MB)";
                                    continue;
                                }

                                // التحقق من أن الملف صورة فعلاً
                                try {
                                    $imageInfo = getimagesize($file->getRealPath());
                                    if ($imageInfo === false) {
                                        $errors["custom_fields.{$fieldName}"] = "الملف المرفوع ليس صورة صحيحة لحقل '{$fieldLabel}'";
                                        continue;
                                    }
                                } catch (Exception $e) {
                                    $errors["custom_fields.{$fieldName}"] = "حدث خطأ أثناء التحقق من صورة حقل '{$fieldLabel}'";
                                    continue;
                                }
                            } else {
                                $errors["custom_fields.{$fieldName}"] = "ملف غير صالح تم رفعه لحقل '{$fieldLabel}'";
                            }
                        }
                    }
                } elseif ($value !== null && $value !== '') {
                    // قيمة نصية (رابط صورة موجودة - لن نتحقق منها)
                    $hasValidFiles = true;
                }
            }

            if (!$hasValidFiles && $field->is_required) {
                $errors["custom_fields.{$fieldName}"] = "يجب رفع صورة واحدة على الأقل لحقل '{$fieldLabel}'";
            }

            // التحقق من عدد الصور (حد أقصى 10 صور لكل حقل)
            if ($fileCount > 10) {
                $errors["custom_fields.{$fieldName}"] = "عدد الصور كبير جداً لحقل '{$fieldLabel}'. الحد الأقصى: 10 صور";
            }
        } else {
            // قيمة واحدة - التحقق إذا كان ملف
            if ($field->is_required && ($fieldValue === null || $fieldValue === '')) {
                $errors["custom_fields.{$fieldName}"] = "حقل '{$fieldLabel}' مطلوب (يجب رفع صورة)";
            } elseif ($fieldValue instanceof \Illuminate\Http\UploadedFile) {
                // التحقق من الملف الواحد
                if (!$fieldValue->isValid()) {
                    $errors["custom_fields.{$fieldName}"] = "ملف غير صالح تم رفعه لحقل '{$fieldLabel}'";
                } else {
                    // التحقق من نوع الملف
                    $mimeType = $fieldValue->getMimeType();
                    if (!in_array($mimeType, $allowedMimes)) {
                        $errors["custom_fields.{$fieldName}"] = "نوع الملف غير مدعوم لحقل '{$fieldLabel}'. يجب أن يكون: " . implode(', ', $allowedExtensions);
                    } else {
                        // التحقق من حجم الملف
                        if ($fieldValue->getSize() > $maxSize) {
                            $maxSizeMB = round($maxSize / (1024 * 1024), 1);
                            $fileSizeMB = round($fieldValue->getSize() / (1024 * 1024), 1);
                            $errors["custom_fields.{$fieldName}"] = "حجم الملف كبير جداً لحقل '{$fieldLabel}'. الحد الأقصى: {$maxSizeMB}MB (الحجم الحالي: {$fileSizeMB}MB)";
                        } else {
                            // التحقق من أن الملف صورة فعلاً
                            try {
                                $imageInfo = getimagesize($fieldValue->getRealPath());
                                if ($imageInfo === false) {
                                    $errors["custom_fields.{$fieldName}"] = "الملف المرفوع ليس صورة صحيحة لحقل '{$fieldLabel}'";
                                }
                            } catch (Exception $e) {
                                $errors["custom_fields.{$fieldName}"] = "حدث خطأ أثناء التحقق من صورة حقل '{$fieldLabel}'";
                            }
                        }
                    }
                }
            }
        }

        return !empty($errors) ? $errors : true;
    }

    /**
     * معالجة الحقول المخصصة
     */
    private function processCustomFields(array $customFields): array
    {
        $processedFields = [];

        foreach ($customFields as $fieldName => $fieldValues) {
            if (is_array($fieldValues)) {
                $processedValues = [];
                foreach ($fieldValues as $value) {
                    if (is_array($value)) {
                        // معالجة الصور المتعددة
                        $imagePaths = [];
                        foreach ($value as $file) {
                            if ($file && $file->isValid()) {
                                $path = $file->store('custom_fields/' . $fieldName, 'public');
                                $imagePaths[] = $path;
                            }
                        }
                        if (!empty($imagePaths)) {
                            $processedValues[] = $imagePaths;
                        }
                    } elseif ($value !== null && $value !== '') {
                        $processedValues[] = $value;
                    }
                }
                if (!empty($processedValues)) {
                    $processedFields[$fieldName] = $processedValues;
                }
            } elseif ($fieldValues !== null && $fieldValues !== '') {
                $processedFields[$fieldName] = $fieldValues;
            }
        }

        return $processedFields;
    }

    /**
     * إنشاء الخدمة
     */
    private function createService(array $validated, $user, array $processedFields): Service
    {
        $category = Category::findOrFail($validated['category_id']);
        $city = City::findOrFail($validated['city_id']);

        $title = 'طلب خدمة - ' . $category->name . ' - ' . $city->name_ar;
        $slug = $this->generateUniqueSlug($title);

        return Service::create([
            'category_id' => $validated['category_id'],
            'sub_category_id' => $validated['sub_category_id'] ?? null,
            'city_id' => $validated['city_id'],
            'user_id' => $user->id,
            'title' => $title,
            'description' => $validated['notes'] ?? '',
            'price' => 0,
            'is_active' => true,
            'custom_fields' => $processedFields,
            'voice_note' => $validated['voice_note'] ?? null,
            'slug' => $slug,
        ]);
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
     * عرض صفحة تعديل الخدمة
     */
    public function edit(Service $service)
    {
        try {
            // التحقق من أن المستخدم هو صاحب الخدمة
            if (auth()->id() !== $service->user_id) {
                abort(403, 'غير مصرح لك بتعديل هذه الخدمة');
            }

            $categories = Category::where('is_active', true)->get();
            $cities = City::getActiveCities();
            $subCategories = $this->getSubCategoriesForCategory($service->category_id);
            $categoryFields = $this->getCategoryFields($service->category_id);

            return view('services.edit', compact('service', 'categories', 'subCategories', 'cities', 'categoryFields'));
        } catch (Exception $e) {
            Log::error('Error in ServiceController@edit: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $service->id ?? null
            ]);
            return redirect()->route('services.index')->with('error', 'حدث خطأ أثناء تحميل صفحة التعديل');
        }
    }

    /**
     * جلب الأقسام الفرعية للقسم
     */
    private function getSubCategoriesForCategory(?int $categoryId)
    {
        if (!$categoryId) {
            return collect();
        }

        return \App\Models\SubCategory::where('category_id', $categoryId)
            ->where('status', true)
            ->get();
    }

    /**
     * جلب الحقول المخصصة للقسم
     */
    private function getCategoryFields(?int $categoryId)
    {
        if (!$categoryId) {
            return collect();
        }

        return \App\Models\CategoryField::where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * تحديث الخدمة
     */
    public function update(Request $request, Service $service)
    {
        try {
            // التحقق من أن المستخدم هو صاحب الخدمة
            if (auth()->id() !== $service->user_id) {
                abort(403, 'غير مصرح لك بتعديل هذه الخدمة');
            }

            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'nullable|exists:sub_categories,id',
                'city_id' => 'required|exists:cities,id',
                'notes' => 'nullable|string|max:1000',
                'voice_note' => 'nullable|string|max:16777215',
                'custom_fields.*' => 'nullable',
            ]);

            // تحديث العنوان والـ slug إذا تغير القسم أو المدينة
            $data = $this->prepareUpdateData($request, $service);

            // معالجة الحقول المخصصة
            $finalCustomFields = $this->processUpdateCustomFields($request, $service);
            $data['custom_fields'] = $finalCustomFields;

            // معالجة التسجيل الصوتي والملاحظات
            if ($request->has('voice_note') && $request->voice_note) {
                $data['voice_note'] = $request->voice_note;
            }
            if ($request->has('notes')) {
                $data['description'] = $request->notes;
            }

            $service->update($data);

            Log::info('Service updated successfully', [
                'service_id' => $service->id,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('services.show', $service->slug)
                ->with('success', 'تم تحديث الخدمة بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in ServiceController@update: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $service->id ?? null,
                'request' => $request->except(['custom_fields', 'voice_note'])
            ]);
            return back()->with('error', 'حدث خطأ أثناء تحديث الخدمة')->withInput();
        }
    }

    /**
     * إعداد بيانات التحديث
     */
    private function prepareUpdateData(Request $request, Service $service): array
    {
        $data = [
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'city_id' => $request->city_id,
        ];

        $categoryChanged = $request->category_id != $service->category_id;
        $cityChanged = $request->city_id != $service->city_id;

        if ($categoryChanged || $cityChanged) {
            $category = Category::findOrFail($request->category_id);
            $city = City::findOrFail($request->city_id);

            $title = 'طلب خدمة - ' . $category->name . ' - ' . $city->name_ar;
            $data['title'] = $title;
            $data['slug'] = $this->generateUniqueSlugForUpdate($title, $service->id);
        }

        return $data;
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
     * معالجة الحقول المخصصة في التحديث
     */
    private function processUpdateCustomFields(Request $request, Service $service): array
    {
        $customFields = $request->custom_fields ?? [];
        $existingCustomFields = $service->custom_fields ?? [];
        $processedFields = $this->processCustomFields($customFields);

        // معالجة حذف الصور
        if ($request->has('delete_images')) {
            $this->handleImageDeletion($request->delete_images, $existingCustomFields, $processedFields);
        }

        // دمج الحقول
        $finalCustomFields = array_merge($existingCustomFields, $processedFields);

        // تنظيف الحقول الفارغة
        return $this->cleanEmptyFields($finalCustomFields);
    }

    /**
     * معالجة حذف الصور
     */
    private function handleImageDeletion(array $deleteImages, array &$existingCustomFields, array &$processedFields): void
    {
        foreach ($deleteImages as $fieldName => $imagePaths) {
            foreach ($imagePaths as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            // إزالة من الحقول الموجودة
            if (isset($existingCustomFields[$fieldName]) && is_array($existingCustomFields[$fieldName])) {
                $existingCustomFields[$fieldName] = array_filter($existingCustomFields[$fieldName], function ($value) use ($imagePaths) {
                    if (is_array($value)) {
                        return !array_intersect($value, $imagePaths);
                    }
                    return !in_array($value, $imagePaths);
                });
            }

            // إزالة من الحقول الجديدة
            if (isset($processedFields[$fieldName]) && is_array($processedFields[$fieldName])) {
                $processedFields[$fieldName] = array_filter($processedFields[$fieldName], function ($value) use ($imagePaths) {
                    if (is_array($value)) {
                        return !array_intersect($value, $imagePaths);
                    }
                    return !in_array($value, $imagePaths);
                });
            }
        }
    }

    /**
     * تنظيف الحقول الفارغة
     */
    private function cleanEmptyFields(array $fields): array
    {
        return array_filter($fields, function ($value) {
            if (is_array($value)) {
                return !empty(array_filter($value, function ($item) {
                    if (is_array($item)) {
                        return !empty($item);
                    }
                    return $item !== null && $item !== '';
                }));
            }
            return $value !== null && $value !== '';
        });
    }

    /**
     * حذف الخدمة
     */
    public function destroy(Service $service)
    {
        try {
            // التحقق من أن المستخدم هو صاحب الخدمة
            if (auth()->id() !== $service->user_id) {
                abort(403, 'غير مصرح لك بحذف هذه الخدمة');
            }

            // جلب جميع العروض المقبولة أو المعلقة على هذه الخدمة
            $acceptedOffers = $service->offers()
                ->whereIn('status', ['accepted', 'pending'])
                ->with('provider')
                ->get();

            // حفظ معلومات الخدمة قبل الحذف
            $serviceTitle = $service->title;
            $serviceId = $service->id;

            // حذف الصورة إذا كانت موجودة
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            // حذف الخدمة
            $service->delete();

            // إرسال إشعارات لجميع المزودين الذين قدموا عروضاً مقبولة أو معلقة
            foreach ($acceptedOffers as $offer) {
                try {
                    \App\Models\Notification::createNotification(
                        $offer->provider_id,
                        'service_deleted',
                        'تم حذف الخدمة',
                        'تم حذف الخدمة "' . $serviceTitle . '" التي كان لديك عرض ' . ($offer->status === 'accepted' ? 'مقبول' : 'معلق') . ' عليها',
                        [
                            'service_id' => $serviceId,
                            'offer_id' => $offer->id,
                            'service_title' => $serviceTitle,
                            'offer_status' => $offer->status,
                        ]
                    );
                } catch (Exception $e) {
                    Log::warning('Failed to create notification for deleted service', [
                        'offer_id' => $offer->id,
                        'provider_id' => $offer->provider_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('Service deleted', [
                'service_id' => $serviceId,
                'user_id' => auth()->id(),
                'notifications_sent' => $acceptedOffers->count()
            ]);

            return redirect()->route('services.index')
                ->with('success', 'تم حذف الخدمة بنجاح');
        } catch (Exception $e) {
            Log::error('Error in ServiceController@destroy: ' . $e->getMessage(), [
                'exception' => $e,
                'service_id' => $service->id ?? null
            ]);
            return redirect()->route('services.index')
                ->with('error', 'حدث خطأ أثناء حذف الخدمة');
        }
    }

    /**
     * عرض خدمات المستخدم
     */
    public function myServices()
    {
        try {
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول');
            }

            $services = auth()->user()->isProvider()
                ? Service::where('is_active', true)
                ->with(['category', 'offers', 'user', 'city'])
                ->latest()
                ->paginate(10)
                : Service::where('user_id', auth()->id())
                ->with(['category', 'offers', 'city'])
                ->latest()
                ->paginate(10);

            return view('services.my-services', compact('services'));
        } catch (Exception $e) {
            Log::error('Error in ServiceController@myServices: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الخدمات');
        }
    }

    /**
     * التحقق من إمكانية مزود الخدمة تقديم عرض لخدمة معينة
     */
    private function canProviderOfferService($provider, $service)
    {
        // التحقق من أن مزود الخدمة لديه ملف شخصي
        $profile = $provider->providerProfile;
        if (!$profile) {
            return false;
        }

        // التحقق من أن القسم متطابق مع اختيارات مزود الخدمة
        $providerCategoryIds = $profile->activeCategories()->pluck('category_id')->toArray();
        if (!in_array($service->category_id, $providerCategoryIds)) {
            return false;
        }

        // التحقق من أن المدن متطابقة مع اختيارات مزود الخدمة
        $providerCityIds = $profile->activeCities()->pluck('city_id')->toArray();

        // إذا كانت الخدمة لها مدينة محددة
        if ($service->city_id) {
            if (!in_array($service->city_id, $providerCityIds)) {
                return false;
            }
        } else {
            // إذا كانت الخدمة متاحة في جميع المدن، يجب أن يكون مزود الخدمة متاح في نفس المدن
            // أو يمكن تعديل هذا المنطق حسب احتياجاتك
            return true;
        }

        return true;
    }
}
