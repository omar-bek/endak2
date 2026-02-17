<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\CategoryFieldController as AdminCategoryField;
use App\Http\Controllers\Provider\ServiceController as ProviderService;
use App\Http\Controllers\ServiceOfferController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProviderProfileController;
use App\Http\Controllers\Admin\SystemSettingController;
use Illuminate\Support\Facades\Broadcast;


// Broadcasting Authentication للـ Web Routes (يدعم Session)
Broadcast::routes(['middleware' => ['web', 'auth']]);

Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// صفحة من نحن
Route::get('/about', function () {
    return view('about');
})->name('about');
// مسار تغيير اللغة
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// مسارات المصادقة
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('success', 'تم التحقق من الإيميل بنجاح!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'تم إرسال رابط التحقق مرة أخرى!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Social Login Routes
Route::get('/auth/facebook', [App\Http\Controllers\SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [App\Http\Controllers\SocialAuthController::class, 'handleFacebookCallback']);

Route::get('/auth/google', [App\Http\Controllers\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\SocialAuthController::class, 'handleGoogleCallback']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// الصفحة الرئيسية
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// الأقسام
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/{parentSlug}/subcategories', [CategoryController::class, 'subcategories'])->name('categories.subcategories');

// الخدمات
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/search', [ServiceController::class, 'search'])->name('services.search');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

// طلب وإنشاء الخدمات (يتطلب تسجيل دخول وتحقق من الإيميل والموافقة على الشروط)
Route::middleware(['auth', 'verified', 'user.type.terms'])->group(function () {
    Route::get('/services/request/{category}', [ServiceController::class, 'request'])->name('services.request');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/my-services', [ServiceController::class, 'myServices'])->name('services.my-services');

    // تعديل وحذف الخدمات (لصاحب الخدمة فقط)
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
});

// مسارات العروض (يتطلب تحقق من الإيميل والموافقة على الشروط)
Route::middleware(['auth', 'verified', 'user.type.terms'])->group(function () {
    Route::get('/services/{service}/offers', [ServiceOfferController::class, 'index'])->name('service-offers.index');
    Route::get('/services/{service}/offers/create', [ServiceOfferController::class, 'create'])->name('service-offers.create');
    Route::post('/services/{service}/offers', [ServiceOfferController::class, 'store'])->name('service-offers.store');
    Route::get('/offers/{offer}', [ServiceOfferController::class, 'show'])->name('service-offers.show');
    Route::post('/offers/{offer}/accept', [ServiceOfferController::class, 'accept'])->name('service-offers.accept');
    Route::post('/offers/{offer}/reject', [ServiceOfferController::class, 'reject'])->name('service-offers.reject');
    Route::post('/offers/{offer}/deliver', [ServiceOfferController::class, 'markAsDelivered'])->name('service-offers.deliver');
    Route::post('/offers/{offer}/review', [ServiceOfferController::class, 'review'])->name('service-offers.review');
    Route::get('/my-offers', [ServiceOfferController::class, 'myOffers'])->name('service-offers.my-offers');
    Route::get('/completed-services', [ServiceOfferController::class, 'completedServices'])->name('service-offers.completed-services');
    Route::get('/offers/{offer}/edit', [ServiceOfferController::class, 'edit'])->name('service-offers.edit');
    Route::put('/offers/{offer}', [ServiceOfferController::class, 'update'])->name('service-offers.update');
});

// مسارات الإشعارات (يتطلب تحقق من الإيميل والموافقة على الشروط)
Route::middleware(['auth', 'verified', 'user.type.terms'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
});

// مسارات الرسائل (يتطلب تحقق من الإيميل والموافقة على الشروط)
Route::middleware(['auth', 'verified', 'user.type.terms'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/new', [MessageController::class, 'newDesign'])->name('messages.new-design');
    Route::get('/messages/search', [MessageController::class, 'search'])->name('messages.search');
    Route::get('/messages/{userId}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/new-messages', [MessageController::class, 'getNewMessages'])->name('messages.new');
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/unread/count', [MessageController::class, 'getUnreadCount'])->name('messages.unread-count');
    Route::post('/messages/mark-all-read', [MessageController::class, 'markAllAsRead'])->name('messages.mark-all-read');
    Route::get('/messages/stream', [MessageController::class, 'stream'])->name('messages.stream');
    Route::get('/services/{serviceId}/conversation', [MessageController::class, 'serviceConversation'])->name('messages.service-conversation');
    Route::get('/offers/{offerId}/conversation', [MessageController::class, 'offerConversation'])->name('messages.offer-conversation');
});

// لوحة الإدارة (يتطلب تحقق من الإيميل والموافقة على الشروط)
Route::middleware(['auth', 'verified', 'user.type.terms', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // إدارة الأقسام
    Route::resource('categories', AdminCategory::class);
    Route::patch('/categories/{category}/toggle-status', [AdminCategory::class, 'toggleStatus'])->name('categories.toggle-status');

    // إدارة الأقسام الفرعية
    Route::resource('sub_categories', App\Http\Controllers\Admin\SubCategoryController::class);
    Route::get('/sub_categories/{id}/duplicate', [App\Http\Controllers\Admin\SubCategoryController::class, 'duplicate'])->name('sub_categories.duplicate');
    Route::post('/sub_categories/{id}/duplicate', [App\Http\Controllers\Admin\SubCategoryController::class, 'duplicateStore'])->name('sub_categories.duplicate-store');
    Route::patch('/sub_categories/{id}/toggle-status', [App\Http\Controllers\Admin\SubCategoryController::class, 'toggleStatus'])->name('sub_categories.toggle-status');

    // إدارة حقول الأقسام
    Route::resource('categories.fields', AdminCategoryField::class);
    Route::patch('/categories/{category}/fields/{field}/toggle-status', [AdminCategoryField::class, 'toggleStatus'])->name('categories.fields.toggle-status');
    Route::post('/categories/{category}/fields/reorder', [AdminCategoryField::class, 'reorder'])->name('categories.fields.reorder');
    Route::patch('/categories/{category}/fields/{field}/sort-order', [AdminCategoryField::class, 'updateSortOrder'])->name('categories.fields.update-sort-order');

    // إعدادات النظام
    Route::get('/system-settings', [SystemSettingController::class, 'index'])->name('system-settings.index');
    Route::put('/system-settings', [SystemSettingController::class, 'update'])->name('system-settings.update');
    Route::put('/system-settings/provider', [SystemSettingController::class, 'updateProviderSettings'])->name('system-settings.provider');
    Route::put('/system-settings/default-service-image', [SystemSettingController::class, 'updateDefaultServiceImage'])->name('system-settings.default-service-image');

    // إدارة الأقسام والمدن
    Route::get('/category-cities', [App\Http\Controllers\Admin\CategoryCityController::class, 'index'])->name('category-cities.index');
    Route::get('/category-cities/statistics', [App\Http\Controllers\Admin\CategoryCityController::class, 'statistics'])->name('category-cities.statistics');
    Route::get('/category-cities/{category}', [App\Http\Controllers\Admin\CategoryCityController::class, 'show'])->name('category-cities.show');
    Route::put('/category-cities/{category}/cities', [App\Http\Controllers\Admin\CategoryCityController::class, 'updateCities'])->name('category-cities.update-cities');
    Route::post('/category-cities/{category}/toggle-city/{city}', [App\Http\Controllers\Admin\CategoryCityController::class, 'toggleCity'])->name('category-cities.toggle-city');
    Route::get('/category-cities/{category}/cities', [App\Http\Controllers\Admin\CategoryCityController::class, 'getCitiesByCategory'])->name('category-cities.get-cities');
    Route::post('/category-cities/{category}/add-city', [App\Http\Controllers\Admin\CategoryCityController::class, 'addCityToCategory'])->name('category-cities.add-city');
    Route::delete('/category-cities/{category}/remove-city/{city}', [App\Http\Controllers\Admin\CategoryCityController::class, 'removeCityFromCategory'])->name('category-cities.remove-city');

    // إدارة المدن
    Route::resource('cities', App\Http\Controllers\Admin\CityController::class);

    // إدارة الخدمات
    Route::get('/services', [App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('services.index');
    Route::get('/services/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'show'])->name('services.show');
    Route::patch('/services/{service}/toggle-status', [App\Http\Controllers\Admin\ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    Route::delete('/services/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'destroy'])->name('services.destroy');

    // إدارة عروض الخدمات
    Route::get('/service-offers', [App\Http\Controllers\Admin\ServiceOfferController::class, 'index'])->name('service-offers.index');
    Route::get('/service-offers/{offer}', [App\Http\Controllers\Admin\ServiceOfferController::class, 'show'])->name('service-offers.show');
    Route::patch('/service-offers/{offer}/toggle-status', [App\Http\Controllers\Admin\ServiceOfferController::class, 'toggleStatus'])->name('service-offers.toggle-status');

    // إدارة المستخدمين
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('/users/{user}/toggle-role', [App\Http\Controllers\Admin\UserController::class, 'toggleRole'])->name('users.toggle-role');

    // إدارة مزودي الخدمات
    Route::get('/providers', [App\Http\Controllers\Admin\ProviderController::class, 'index'])->name('providers.index');
    Route::get('/providers/{provider}', [App\Http\Controllers\Admin\ProviderController::class, 'show'])->name('providers.show');
    Route::patch('/providers/{provider}/verify', [App\Http\Controllers\Admin\ProviderController::class, 'verify'])->name('providers.verify');

    // إدارة الطلبات
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');

    // النسخ الاحتياطية
    Route::get('/backups', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups/create', [App\Http\Controllers\Admin\BackupController::class, 'create'])->name('backups.create');
    Route::get('/backups/download/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backups.download');
    Route::delete('/backups/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'destroy'])->name('backups.destroy');

    // سجلات النظام
    Route::get('/logs', [App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/{filename}', [App\Http\Controllers\Admin\LogController::class, 'show'])->name('logs.show');
    Route::delete('/logs/{filename}', [App\Http\Controllers\Admin\LogController::class, 'destroy'])->name('logs.destroy');

    // إدارة المحادثات
    Route::get('/messages', [App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversationId}', [App\Http\Controllers\Admin\MessageController::class, 'show'])->name('messages.show');
    Route::delete('/messages/{message}', [App\Http\Controllers\Admin\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::delete('/messages/conversation/{conversationId}', [App\Http\Controllers\Admin\MessageController::class, 'destroyConversation'])->name('messages.destroy-conversation');
});

// Authentication Routes (moved to top)

// Logout لا يحتاج تحقق من الإيميل
Route::middleware('auth')->group(function () {

    Route::get('/complete-profile', [AuthController::class, 'showCompleteProfile'])
        ->name('complete-profile');

    Route::post('/save-user-type', [AuthController::class, 'saveUserType'])
        ->name('save-user-type');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/csrf-token', function () {
        return response()->json(['token' => csrf_token()]);
    })->name('csrf-token');
});


// Profile يتطلب تحقق من الإيميل والموافقة على الشروط
Route::middleware(['auth', 'verified', 'user.type.terms'])->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Provider Routes
    Route::middleware('provider')->prefix('provider')->name('provider.')->group(function () {
        Route::resource('services', ProviderService::class);
        Route::patch('/services/{service}/toggle-status', [ProviderService::class, 'toggleStatus'])->name('services.toggle-status');

        // ملف مزود الخدمة (للمزود نفسه فقط)
        Route::get('/profile/complete', [ProviderProfileController::class, 'completeProfile'])->name('complete-profile');
        Route::get('/profile/edit', [ProviderProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProviderProfileController::class, 'store'])->name('profile.store');
        Route::get('/profile', [ProviderProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProviderProfileController::class, 'update'])->name('profile.update');

        // إدارة الأقسام والمدن
        Route::post('/categories', [ProviderProfileController::class, 'addCategory'])->name('categories.add');
        Route::delete('/categories/{id}', [ProviderProfileController::class, 'removeCategory'])->name('categories.remove');
        Route::post('/cities', [ProviderProfileController::class, 'addCity'])->name('cities.add');
        Route::delete('/cities/{id}', [ProviderProfileController::class, 'removeCity'])->name('cities.remove');
    });
});

// الملف الشخصي العام لمزود الخدمة (متاح للجميع)
Route::get('/provider/{userId}/profile', [ProviderProfileController::class, 'publicProfile'])->name('provider.profile.public');

// ملف مزود الخدمة (متاح للجميع - للمستخدمين العاديين)
Route::get('/provider/profile/{userId?}', [ProviderProfileController::class, 'showPublic'])->name('provider.profile.show');

// الملف الشخصي العام للمستخدم (متاح للجميع)
Route::get('/users/{userId}/profile', [AuthController::class, 'publicProfile'])->name('user.profile.public');

// Route اختبار لحفظ sub_category_id
Route::get('/test-subcategory', function () {
    // إنشاء خدمة تجريبية مع sub_category_id
    $service = \App\Models\Service::create([
        'title' => 'خدمة تجريبية',
        'description' => 'وصف تجريبي',
        'category_id' => 1,
        'sub_category_id' => 1,
        'city_id' => 1,
        'user_id' => 1,
        'price' => 100,
        'is_active' => true,
        'custom_fields' => [],
    ]);

    return response()->json([
        'success' => true,
        'service' => $service->toArray(),
        'sub_category_id' => $service->sub_category_id,
        'subCategory' => $service->subCategory ? $service->subCategory->toArray() : null
    ]);
});

// صفحة اختبار القسم الفرعي
Route::get('/test-subcategory-page', function () {
    return view('test-subcategory');
});

// API لجلب الأقسام الفرعية
Route::get('/api/categories/{category}/subcategories', function ($category) {
    $subCategories = \App\Models\SubCategory::where('category_id', $category)
        ->where('status', true)
        ->get(['id', 'name_ar', 'name_en']);

    return response()->json($subCategories);
});
