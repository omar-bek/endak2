<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\ProviderProfile;
use App\Models\ProviderCategory;
use App\Models\ProviderCity;
use App\Models\SystemSetting;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Http;

class AuthController extends BaseApiController
{
    // ==================== Public Authentication Methods ====================

    /**
     * تسجيل مستخدم جديد
     * POST /api/v1/auth/register
     */
    public function register(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'confirmed', 'min:8'],
                'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
                'user_type' => ['nullable', 'in:customer,provider'],
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'] ?? null,
                'user_type' => $data['user_type'] ?? 'customer',
            ]);

            $token = $user->generateApiToken();

            Log::info('API User registered', ['user_id' => $user->id]);

            return $this->success([
                'token' => $token,
                'user' => $user,
            ], 'تم إنشاء الحساب بنجاح', 201);
        }, 'حدث خطأ أثناء التسجيل');
    }

    /**
     * تسجيل الدخول
     * POST /api/v1/auth/login
     */
    public function login(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['بيانات تسجيل الدخول غير صحيحة'],
                ]);
            }

            $token = $user->generateApiToken();

            Log::info('API User logged in', ['user_id' => $user->id]);

            return $this->success([
                'token' => $token,
                'user' => $user->fresh(),
            ], 'تم تسجيل الدخول بنجاح');
        }, 'حدث خطأ أثناء تسجيل الدخول');
    }

    /**
     * تسجيل الدخول بجوجل
     * POST /api/v1/auth/google
     */
    public function googleLogin(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            $data = $request->validate([
                'access_token' => ['required', 'string'],
                'device_token' => ['nullable', 'string', 'max:255'],
            ]);

            try {
                $response = Http::get('https://www.googleapis.com/oauth2/v2/userinfo', [
                    'access_token' => $data['access_token'],
                ]);

                if (!$response->successful()) {
                    throw ValidationException::withMessages([
                        'access_token' => ['فشل التحقق من الـ access token مع جوجل. يرجى التأكد من صحة الـ access token.'],
                    ]);
                }

                $googleUserData = $response->json();

                if (!isset($googleUserData['email']) || empty($googleUserData['email'])) {
                    throw ValidationException::withMessages([
                        'access_token' => ['فشل الحصول على البريد الإلكتروني من جوجل. يرجى التأكد من أن حسابك يحتوي على بريد إلكتروني.'],
                    ]);
                }

                $user = User::where('email', $googleUserData['email'])->first();
                $isNewUser = !$user;

                if (!$user) {
                    $user = User::create([
                        'name' => $googleUserData['name'] ?? 'مستخدم',
                        'email' => $googleUserData['email'],
                        'phone' => null,
                        'password' => Hash::make(Str::random(32)),
                        'user_type' => null,
                        'email_verified_at' => now(),
                        'avatar' => $this->downloadAndSaveAvatar($googleUserData['picture'] ?? null, new User()),
                        'terms_accepted_at' => null,
                    ]);
                } else {
                    if (isset($googleUserData['picture']) && $googleUserData['picture'] && !$user->avatar) {
                        $user->avatar = $this->downloadAndSaveAvatar($googleUserData['picture'], $user);
                        $user->save();
                    }

                    if (!$user->hasVerifiedEmail()) {
                        $user->email_verified_at = now();
                        $user->save();
                    }
                }

                if (!empty($data['device_token'])) {
                    $user->device_token = $data['device_token'];
                    $user->save();
                }

                $token = $user->generateApiToken();

                Log::info('API User logged in with Google', [
                    'user_id' => $user->id,
                    'is_new_user' => $isNewUser,
                ]);

                return $this->success([
                    'token' => $token,
                    'user' => $user->fresh(),
                    'is_new_user' => $isNewUser,
                    'needs_profile_completion' => !$user->hasCompletedProfile(),
                ], $isNewUser ? 'تم إنشاء الحساب بنجاح' : 'تم تسجيل الدخول بنجاح');
            } catch (ValidationException $e) {
                throw $e;
            } catch (Exception $e) {
                Log::error('Google login error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                throw ValidationException::withMessages([
                    'access_token' => ['فشل تسجيل الدخول بجوجل: ' . $e->getMessage()],
                ]);
            }
        }, 'حدث خطأ أثناء تسجيل الدخول بجوجل');
    }

    /**
     * تسجيل الخروج
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            /** @var User $user */
            $user = $request->user();
            $user?->clearApiToken();

            Log::info('API User logged out', ['user_id' => $user?->id]);

            return $this->success(null, 'تم تسجيل الخروج بنجاح');
        }, 'حدث خطأ أثناء تسجيل الخروج');
    }

    // ==================== Profile Management Methods ====================

    /**
     * عرض الملف الشخصي
     * GET /api/v1/auth/profile
     */
    public function profile(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            $user = $request->user()->load([
                'providerProfile',
                'services' => fn($query) => $query->latest()->limit(10),
            ]);

            return $this->success($user);
        }, 'حدث خطأ أثناء تحميل الملف الشخصي');
    }

    /**
     * تحديث الملف الشخصي
     * POST /api/v1/auth/profile
     */
    public function updateProfile(Request $request)
    {
        return $this->executeApiWithTryCatch(function () use ($request) {
            /** @var User $user */
            $user = $request->user();

            // Normalize request data (handle JSON array format from Postman)
            $this->normalizeRequestData($request);

            // Get validation rules
            $rules = $this->getUpdateProfileValidationRules($request, $user);

            // Handle form-data fallback for PUT requests
            $requestData = $this->getRequestData($request);

            // Validate
            $validated = $request->validate($rules);

            // Update user data
            $userData = $this->prepareUserUpdateData($request, $validated, $requestData, $user);

            if (!empty($userData)) {
                $user->update($userData);
                $user->refresh();
            }

            // Update provider profile if user is provider
            if ($user->isProvider()) {
                $this->updateProviderProfile($request, $validated, $requestData, $user);
            }

            // Reload user with relationships
            $user->load(['providerProfile']);

            Log::info('API Profile updated', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($userData ?? []),
            ]);

            return $this->success($user, 'تم تحديث الملف الشخصي بنجاح');
        }, 'حدث خطأ أثناء تحديث الملف الشخصي');
    }

    // ==================== Profile Completion Methods ====================

    /**
     * جلب بيانات الملف الشخصي لإكماله
     * GET /api/v1/auth/complete-profile
     */
    public function getCompleteProfile(Request $request)
    {
        if ($request->method() !== 'GET') {
            return $this->error('يجب استخدام GET request لجلب بيانات الملف الشخصي. استخدم POST لإكمال الملف الشخصي.', 405);
        }

        return $this->executeApiWithTryCatch(function () use ($request) {
            /** @var User $user */
            $user = $request->user();

            // Load user categories and cities
            $user->load([
                'providerCategories' => function ($query) {
                    $query->with(['category:id,name,name_en,slug,icon,image']);
                },
                'providerCities' => function ($query) {
                    $query->with(['city:id,name_ar,name_en,slug']);
                }
            ]);

            // Format user categories
            $userCategories = $user->providerCategories->map(function ($providerCategory) {
                return [
                    'id' => $providerCategory->category->id,
                    'name' => $providerCategory->category->name,
                    'name_en' => $providerCategory->category->name_en,
                    'slug' => $providerCategory->category->slug,
                    'icon' => $providerCategory->category->icon,
                    'image' => $providerCategory->category->image ? asset('storage/' . $providerCategory->category->image) : null,
                ];
            });

            // Format user cities
            $userCities = $user->providerCities->map(function ($providerCity) {
                return [
                    'id' => $providerCity->city->id,
                    'name_ar' => $providerCity->city->name_ar,
                    'name_en' => $providerCity->city->name_en,
                    'slug' => $providerCity->city->slug ?? null,
                ];
            });

            return $this->success([
                'categories' => $userCategories,
                'cities' => $userCities,
            ]);
        }, 'حدث خطأ أثناء جلب بيانات الملف الشخصي');
    }

    /**
     * إكمال الملف الشخصي
     * POST /api/v1/auth/complete-profile
     */
    public function completeProfile(Request $request)
    {
        if ($request->method() !== 'POST') {
            return $this->error('يجب استخدام POST request لإكمال الملف الشخصي. استخدم GET لجلب بيانات الملف الشخصي.', 405);
        }

        return $this->executeApiWithTryCatch(function () use ($request) {
            /** @var User $user */
            $user = $request->user();

            $rules = $this->getCompleteProfileValidationRules($request);

            $validated = $request->validate($rules);

            DB::beginTransaction();
            try {
                $user->update([
                    'user_type' => $validated['user_type'],
                    'terms_accepted_at' => now(),
                ]);

                if ($validated['user_type'] === 'provider') {
                    $this->completeProviderProfile($request, $validated, $user);
                }

                DB::commit();

                // Load user categories and cities
                $user->load([
                    'providerCategories' => function ($query) {
                        $query->with(['category:id,name,name_en,slug,icon,image']);
                    },
                    'providerCities' => function ($query) {
                        $query->with(['city:id,name_ar,name_en,slug']);
                    }
                ]);

                // Format user categories
                $userCategories = $user->providerCategories->map(function ($providerCategory) {
                    return [
                        'id' => $providerCategory->category->id,
                        'name' => $providerCategory->category->name,
                        'name_en' => $providerCategory->category->name_en,
                        'slug' => $providerCategory->category->slug,
                        'icon' => $providerCategory->category->icon,
                        'image' => $providerCategory->category->image ? asset('storage/' . $providerCategory->category->image) : null,
                    ];
                });

                // Format user cities
                $userCities = $user->providerCities->map(function ($providerCity) {
                    return [
                        'id' => $providerCity->city->id,
                        'name_ar' => $providerCity->city->name_ar,
                        'name_en' => $providerCity->city->name_en,
                        'slug' => $providerCity->city->slug ?? null,
                    ];
                });

                Log::info('API User profile completed', [
                    'user_id' => $user->id,
                    'user_type' => $user->user_type,
                ]);

                return $this->success([
                    'categories' => $userCategories,
                    'cities' => $userCities,
                ], 'تم إكمال الملف الشخصي بنجاح');
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }, 'حدث خطأ أثناء إكمال الملف الشخصي');
    }

    /**
     * جلب الأقسام والمدن لإكمال الملف الشخصي (Public endpoint)
     * GET /api/v1/auth/complete-profile/data
     */
    public function getCompleteProfileData(Request $request)
    {
        return $this->executeApiWithTryCatch(function () {
            $profileData = $this->getAvailableProfileData();

            return $this->success([
                'categories' => $profileData['categories'],
                'cities' => $profileData['cities'],
                'limits' => $profileData['limits'],
            ]);
        }, 'حدث خطأ أثناء جلب بيانات إكمال الملف الشخصي');
    }

    // ==================== Private Helper Methods ====================

    /**
     * Get available categories and cities for profile completion
     */
    private function getAvailableProfileData(): array
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with([
                'children' => fn($query) => $query->where('is_active', true)->orderBy('sort_order'),
            ])
            ->orderBy('sort_order')
            ->get(['id', 'name', 'name_en', 'slug', 'icon', 'image', 'sort_order']);

        $cities = City::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name_ar')
            ->get(['id', 'name_ar', 'name_en', 'slug', 'sort_order']);

        $maxCategories = SystemSetting::get('provider_max_categories', 3);
        $maxCities = SystemSetting::get('provider_max_cities', 5);

        return [
            'categories' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'name_en' => $category->name_en,
                    'slug' => $category->slug,
                    'icon' => $category->icon,
                    'image' => $category->image ? asset('storage/' . $category->image) : null,
                    'sort_order' => $category->sort_order ?? 0,
                    'children' => $category->children->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'name_en' => $child->name_en,
                            'slug' => $child->slug,
                            'icon' => $child->icon,
                            'image' => $child->image ? asset('storage/' . $child->image) : null,
                            'sort_order' => $child->sort_order ?? 0,
                        ];
                    }),
                ];
            }),
            'cities' => $cities->map(function ($city) {
                return [
                    'id' => $city->id,
                    'name_ar' => $city->name_ar,
                    'name_en' => $city->name_en,
                    'slug' => $city->slug ?? null,
                    'sort_order' => $city->sort_order ?? 0,
                ];
            }),
            'limits' => [
                'max_categories' => (int)$maxCategories,
                'max_cities' => (int)$maxCities,
            ],
        ];
    }

    /**
     * Get validation rules for complete profile
     */
    private function getCompleteProfileValidationRules(Request $request): array
    {
        $rules = [
            'user_type' => ['required', 'in:customer,provider'],
            'terms' => ['required', 'accepted'],
        ];

        if ($request->input('user_type') === 'provider') {
            $maxCategories = SystemSetting::get('provider_max_categories', 3);
            $maxCities = SystemSetting::get('provider_max_cities', 5);

            $rules = array_merge($rules, [
                'bio' => ['nullable', 'string', 'max:1000'],
                'phone' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:500'],
                'categories' => ['required', 'array', 'min:1', 'max:' . $maxCategories],
                'categories.*' => ['required', 'exists:categories,id'],
                'cities' => ['required', 'array', 'min:1', 'max:' . $maxCities],
                'cities.*' => ['required', 'exists:cities,id'],
                'working_hours' => ['nullable', 'array'],
                'working_hours.*.day' => ['required_with:working_hours', 'string', 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday'],
                'working_hours.*.start' => ['required_with:working_hours', 'string'],
                'working_hours.*.end' => ['required_with:working_hours', 'string'],
                'working_hours.*.is_open' => ['sometimes', 'boolean'],
                'avatar' => ['nullable', 'image|mimes:jpeg,jpg,png,gif,webp|max:5120'],
            ]);
        }

        return $rules;
    }

    /**
     * Complete provider profile
     */
    private function completeProviderProfile(Request $request, array $validated, User $user): void
    {
        $maxCategories = SystemSetting::get('provider_max_categories', 3);
        $maxCities = SystemSetting::get('provider_max_cities', 5);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $avatarPath]);
        }

        // Update user phone if provided
        if (isset($validated['phone']) && $validated['phone'] !== null) {
            $user->update(['phone' => $validated['phone']]);
        }

        // Prepare provider profile data
        $providerData = [
            'max_categories' => $maxCategories,
            'max_cities' => $maxCities,
            'is_verified' => SystemSetting::get('provider_auto_approve', false),
            'is_active' => SystemSetting::get('provider_auto_approve', false),
        ];

        // Add optional fields if provided
        if (isset($validated['bio'])) {
            $providerData['bio'] = $validated['bio'];
        }
        if (isset($validated['phone'])) {
            $providerData['phone'] = $validated['phone'];
        }
        if (isset($validated['address'])) {
            $providerData['address'] = $validated['address'];
        }
        if (isset($validated['working_hours'])) {
            $providerData['working_hours'] = $validated['working_hours'];
        }

        // Create or update provider profile
        ProviderProfile::updateOrCreate(
            ['user_id' => $user->id],
            $providerData
        );

        // Delete old categories and cities
        ProviderCategory::where('user_id', $user->id)->delete();
        ProviderCity::where('user_id', $user->id)->delete();

        // Add new categories
        foreach ($validated['categories'] as $categoryId) {
            ProviderCategory::create([
                'user_id' => $user->id,
                'category_id' => $categoryId,
                'is_active' => true,
            ]);
        }

        // Add new cities
        foreach ($validated['cities'] as $cityId) {
            ProviderCity::create([
                'user_id' => $user->id,
                'city_id' => $cityId,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Normalize request data (handle JSON array format from Postman)
     */
    private function normalizeRequestData(Request $request): void
    {
        $rawContent = $request->getContent();
        if (empty($rawContent)) {
            $rawContent = file_get_contents('php://input');
        }

        if (!empty($rawContent)) {
            $jsonData = json_decode($rawContent, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData) && !empty($jsonData)) {
                // Check if it's a JSON array with key-value structure (Postman format)
                if (isset($jsonData[0]) && is_array($jsonData[0]) && (isset($jsonData[0]['key']) || isset($jsonData[0]['value']))) {
                    $mergedData = [];
                    foreach ($jsonData as $item) {
                        if (isset($item['key']) && isset($item['value'])) {
                            $key = $item['key'];
                            $value = $item['value'];
                            if (is_array($value) && !empty($value)) {
                                $value = $value[0];
                            }
                            $mergedData[$key] = $value;
                        }
                    }
                    if (!empty($mergedData)) {
                        $request->merge($mergedData);
                    }
                }
            }
        }
    }

    /**
     * Get request data with form-data fallback
     */
    private function getRequestData(Request $request): array
    {
        $requestData = $request->all();

        // Fallback to $_POST for form-data in PUT requests
        if (empty($requestData) && !empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $request->merge([$key => $value]);
            }
            $requestData = $request->all();
        }

        return $requestData;
    }

    /**
     * Get validation rules for update profile
     */
    private function getUpdateProfileValidationRules(Request $request, User $user): array
    {
        $userRules = [
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20', 'unique:users,phone,' . $user->id],
            'bio' => ['sometimes', 'nullable', 'string'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $user->id],
            'avatar' => ['sometimes', 'nullable'],
            'password' => ['sometimes', 'nullable', 'string', 'min:8', 'confirmed'],
        ];

        // Validate avatar if it's a file (separate validation)
        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => ['image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            ]);
        }

        $providerRules = [];
        if ($user->isProvider()) {
            $providerRules = [
                'provider.bio' => ['sometimes', 'nullable', 'string', 'max:1000'],
                'provider.phone' => ['sometimes', 'nullable', 'string', 'max:20'],
                'provider.address' => ['sometimes', 'nullable', 'string', 'max:500'],
                'provider.working_hours' => ['sometimes', 'nullable', 'array'],
                'provider.working_hours.*.day' => ['required_with:provider.working_hours', 'string', 'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday'],
                'provider.working_hours.*.start' => ['required_with:provider.working_hours', 'string'],
                'provider.working_hours.*.end' => ['required_with:provider.working_hours', 'string'],
                'provider.working_hours.*.is_open' => ['sometimes', 'boolean'],
            ];
        }

        return array_merge($userRules, $providerRules);
    }

    /**
     * Prepare user update data
     */
    private function prepareUserUpdateData(Request $request, array $validated, array $requestData, User $user): array
    {
        $userData = [];
        $getValue = fn($key) => $this->getFieldValue($key, $validated, $request, $requestData);

        // Name
        $nameValue = $getValue('name');
        if ($nameValue !== null) {
            $nameValue = is_string($nameValue) ? trim($nameValue) : $nameValue;
            if ($nameValue !== '' && $nameValue !== $user->name) {
                $userData['name'] = $nameValue;
            }
        }

        // Phone
        $phoneValue = $getValue('phone');
        if ($phoneValue !== null && $phoneValue !== '' && $phoneValue !== $user->phone) {
            $userData['phone'] = $phoneValue;
        }

        // Bio
        $bioValue = $getValue('bio');
        if ($bioValue !== null && $bioValue !== $user->bio) {
            $userData['bio'] = $bioValue === '' ? null : $bioValue;
        }

        // Email
        $emailValue = $getValue('email');
        if ($emailValue !== null && $emailValue !== '' && $emailValue !== $user->email) {
            $userData['email'] = $emailValue;
        }

        // Password
        $passwordValue = $getValue('password');
        if ($passwordValue !== null && $passwordValue !== '') {
            $userData['password'] = Hash::make($passwordValue);
        }

        // Avatar
        if ($request->has('avatar') || $request->hasFile('avatar')) {
            try {
                $avatarPath = $this->handleAvatarUpload($request, $user);
                if ($avatarPath !== null) {
                    $userData['avatar'] = $avatarPath;
                } elseif ($request->input('avatar') === null || $request->input('avatar') === '') {
                    $userData['avatar'] = null;
                }
            } catch (Exception $e) {
                Log::error('Avatar upload failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        }

        return $userData;
    }

    /**
     * Get field value from multiple sources
     */
    private function getFieldValue(string $key, array $validated, Request $request, array $requestData)
    {
        if (isset($requestData[$key])) {
            $value = $requestData[$key];
        } elseif ($request->has($key)) {
            $value = $request->input($key);
        } elseif (isset($validated[$key])) {
            $value = $validated[$key];
        } else {
            return null;
        }

        if (is_array($value) && !empty($value)) {
            return $value[0];
        }

        return $value;
    }

    /**
     * Update provider profile
     */
    private function updateProviderProfile(Request $request, array $validated, array $requestData, User $user): void
    {
        $provider = $user->providerProfile;

        if (!$provider) {
            $provider = ProviderProfile::create([
                'user_id' => $user->id,
                'is_active' => true,
                'is_verified' => false,
                'max_categories' => 3,
                'max_cities' => 5,
            ]);
        }

        $providerData = [];

        // Check nested provider object
        if (isset($validated['provider']) && is_array($validated['provider'])) {
            $this->extractProviderDataFromNested($validated['provider'], $provider, $providerData);
        }

        // Check flat provider fields (provider[bio], provider[address], etc.)
        foreach ($requestData as $key => $value) {
            if (preg_match('/^provider\[(.+)\]$/', $key, $matches)) {
                $fieldName = $matches[1];
                if (is_array($value) && !empty($value)) {
                    $value = $value[0];
                }
                $this->setProviderField($fieldName, $value, $provider, $providerData);
            }
        }

        // Check nested provider in request data
        if (isset($requestData['provider']) && is_array($requestData['provider'])) {
            $this->extractProviderDataFromNested($requestData['provider'], $provider, $providerData);
        }

        // Check root level provider fields (for backward compatibility)
        $this->checkRootLevelProviderFields($request, $provider, $providerData);

        if (!empty($providerData)) {
            $provider->update($providerData);
            $provider->refresh();
        }
    }

    /**
     * Extract provider data from nested array
     */
    private function extractProviderDataFromNested(array $providerInput, ProviderProfile $provider, array &$providerData): void
    {
        if (isset($providerInput['bio']) && !isset($providerData['bio']) && $providerInput['bio'] !== $provider->bio) {
            $providerData['bio'] = $providerInput['bio'];
        }
        if (isset($providerInput['phone']) && !isset($providerData['phone']) && $providerInput['phone'] !== $provider->phone) {
            $providerData['phone'] = $providerInput['phone'];
        }
        if (isset($providerInput['address']) && !isset($providerData['address']) && $providerInput['address'] !== $provider->address) {
            $providerData['address'] = $providerInput['address'];
        }
        if (isset($providerInput['working_hours']) && !isset($providerData['working_hours'])) {
            $providerData['working_hours'] = $providerInput['working_hours'];
        }
    }

    /**
     * Set provider field value
     */
    private function setProviderField(string $fieldName, $value, ProviderProfile $provider, array &$providerData): void
    {
        if (isset($providerData[$fieldName])) {
            return;
        }

        switch ($fieldName) {
            case 'bio':
                if ($value !== null && $value !== $provider->bio) {
                    $providerData['bio'] = $value;
                }
                break;
            case 'phone':
                if ($value !== null && $value !== $provider->phone) {
                    $providerData['phone'] = $value;
                }
                break;
            case 'address':
                if ($value !== null && $value !== $provider->address) {
                    $providerData['address'] = $value;
                }
                break;
            case 'working_hours':
                $providerData['working_hours'] = $value;
                break;
        }
    }

    /**
     * Check root level provider fields (for backward compatibility)
     */
    private function checkRootLevelProviderFields(Request $request, ProviderProfile $provider, array &$providerData): void
    {
        if ($request->has('provider_bio') && !isset($providerData['bio'])) {
            $newBio = $request->input('provider_bio');
            if ($newBio !== null && $newBio !== $provider->bio) {
                $providerData['bio'] = $newBio;
            }
        }
        if ($request->has('provider_phone') && !isset($providerData['phone'])) {
            $newPhone = $request->input('provider_phone');
            if ($newPhone !== null && $newPhone !== $provider->phone) {
                $providerData['phone'] = $newPhone;
            }
        }
        if ($request->has('provider_address') && !isset($providerData['address'])) {
            $newAddress = $request->input('provider_address');
            if ($newAddress !== null && $newAddress !== $provider->address) {
                $providerData['address'] = $newAddress;
            }
        }
        if ($request->has('provider_working_hours') && !isset($providerData['working_hours'])) {
            $providerData['working_hours'] = $request->input('provider_working_hours');
        }
    }

    // ==================== Avatar Upload Methods ====================

    /**
     * Handle avatar upload
     * Supports: direct file upload, base64, or URL
     */
    private function handleAvatarUpload(Request $request, User $user): ?string
    {
        try {
            // 1. Direct file upload (multipart/form-data)
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                if (!$file->isValid()) {
                    throw new Exception('الملف المرفوع غير صالح');
                }

                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                return $file->store('avatars', 'public');
            }

            // 2. Base64 image
            $avatarInput = $request->input('avatar');

            if (is_array($avatarInput)) {
                $avatarInput = !empty($avatarInput) ? $avatarInput[0] : null;

                if (is_string($avatarInput) && preg_match('/^[A-Z]:|^\/[A-Z]:|^\/C:\//i', $avatarInput)) {
                    throw new Exception('خطأ: تم إرسال مسار ملف محلي من جهازك (' . $avatarInput . '). في Postman، يجب أن تختار حقل avatar كـ File وليس Text.');
                }
            }

            if (is_string($avatarInput) && preg_match('/^data:image\/(jpeg|jpg|png|gif|webp);base64,/', $avatarInput)) {
                return $this->saveBase64Image($avatarInput, $user);
            }

            // 3. URL for image
            if (is_string($avatarInput) && filter_var($avatarInput, FILTER_VALIDATE_URL)) {
                return $this->saveImageFromUrl($avatarInput, $user);
            }

            // 4. Local file path (for development only)
            if (is_string($avatarInput) && !filter_var($avatarInput, FILTER_VALIDATE_URL)) {
                return $this->saveImageFromLocalPath($avatarInput, $user);
            }

            // 5. Delete avatar
            if ($avatarInput === null || $avatarInput === '') {
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                return null;
            }

            return null;
        } catch (Exception $e) {
            Log::error('Error handling avatar upload', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);
            throw $e;
        }
    }

    /**
     * Save base64 image
     */
    private function saveBase64Image(string $base64String, User $user): string
    {
        $extension = $this->getBase64ImageExtension($base64String);
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64String);
        $decoded = base64_decode($imageData, true);

        if ($decoded === false) {
            throw new Exception('بيانات base64 غير صالحة');
        }

        if (strlen($decoded) > 5 * 1024 * 1024) {
            throw new Exception('حجم الصورة كبير جداً. الحد الأقصى هو 5MB');
        }

        $imageInfo = @getimagesizefromstring($decoded);
        if ($imageInfo === false) {
            throw new Exception('البيانات المرسلة ليست صورة صالحة');
        }

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $fileName = Str::random(40) . '.' . $extension;
        $path = 'avatars/' . $fileName;
        Storage::disk('public')->put($path, $decoded);

        return $path;
    }

    /**
     * Save image from URL
     */
    private function saveImageFromUrl(string $url, User $user): string
    {
        $imageContent = file_get_contents($url);
        if ($imageContent === false) {
            throw new Exception('فشل تحميل الصورة من الرابط');
        }

        if (strlen($imageContent) > 5 * 1024 * 1024) {
            throw new Exception('حجم الصورة كبير جداً. الحد الأقصى هو 5MB');
        }

        $imageInfo = @getimagesizefromstring($imageContent);
        if ($imageInfo === false) {
            throw new Exception('الرابط لا يشير إلى صورة صالحة');
        }

        $extension = $this->getImageExtensionFromUrl($url, $imageInfo['mime']);

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $fileName = Str::random(40) . '.' . $extension;
        $path = 'avatars/' . $fileName;
        Storage::disk('public')->put($path, $imageContent);

        return $path;
    }

    /**
     * Save image from local path
     */
    private function saveImageFromLocalPath(string $avatarInput, User $user): ?string
    {
        $normalizedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $avatarInput);

        if (preg_match('/^\/([A-Z]):/', $normalizedPath, $matches)) {
            $normalizedPath = $matches[1] . ':' . substr($normalizedPath, 3);
        }

        if (preg_match('/^[A-Z]:/', $normalizedPath)) {
            $filePath = $normalizedPath;
        } elseif (str_starts_with($normalizedPath, DIRECTORY_SEPARATOR)) {
            $filePath = $normalizedPath;
        } else {
            $filePath = base_path($normalizedPath);
        }

        if (file_exists($filePath) && is_readable($filePath) && is_file($filePath)) {
            $imageInfo = @getimagesize($filePath);
            if ($imageInfo === false) {
                throw new Exception('الملف المحدد ليس صورة صالحة');
            }

            $fileSize = filesize($filePath);
            if ($fileSize > 5 * 1024 * 1024) {
                throw new Exception('حجم الصورة كبير جداً. الحد الأقصى هو 5MB');
            }

            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($extension, $allowedExtensions)) {
                throw new Exception('نوع الملف غير مدعوم. يجب أن يكون: ' . implode(', ', $allowedExtensions));
            }

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $fileName = Str::random(40) . '.' . $extension;
            $path = 'avatars/' . $fileName;
            $fileContent = file_get_contents($filePath);
            Storage::disk('public')->put($path, $fileContent);

            return $path;
        }

        if (preg_match('/^[A-Z]:|^\/[A-Z]:|^\/C:\//i', $avatarInput)) {
            throw new Exception('لا يمكن استخدام مسار ملف محلي من جهازك (' . $avatarInput . '). يرجى رفع الملف مباشرة في Postman: اختر Body > form-data > avatar (type: File) وليس Text.');
        }

        throw new Exception('الملف المحدد غير موجود أو غير قابل للوصول: ' . $avatarInput);
    }

    /**
     * Get image extension from base64 string
     */
    private function getBase64ImageExtension(string $base64String): string
    {
        if (preg_match('/^data:image\/(jpeg|jpg|png|gif|webp);base64,/', $base64String, $matches)) {
            $extension = $matches[1];
            return $extension === 'jpeg' ? 'jpg' : $extension;
        }

        return 'jpg';
    }

    /**
     * Get image extension from URL or MIME type
     */
    private function getImageExtensionFromUrl(string $url, string $mimeType): string
    {
        $urlPath = parse_url($url, PHP_URL_PATH);
        if ($urlPath) {
            $extension = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($extension, $allowedExtensions)) {
                return $extension === 'jpeg' ? 'jpg' : $extension;
            }
        }

        $mimeToExtension = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        return $mimeToExtension[$mimeType] ?? 'jpg';
    }

    /**
     * Download and save avatar from URL (for Google login)
     */
    private function downloadAndSaveAvatar(?string $avatarUrl, User $user): ?string
    {
        if (!$avatarUrl) {
            return null;
        }

        try {
            $imageContent = file_get_contents($avatarUrl);
            if ($imageContent === false) {
                return null;
            }

            if (strlen($imageContent) > 5 * 1024 * 1024) {
                return null;
            }

            $imageInfo = @getimagesizefromstring($imageContent);
            if ($imageInfo === false) {
                return null;
            }

            $mimeToExtension = [
                'image/jpeg' => 'jpg',
                'image/jpg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
            ];

            $extension = $mimeToExtension[$imageInfo['mime']] ?? 'jpg';

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $fileName = Str::random(40) . '.' . $extension;
            $path = 'avatars/' . $fileName;
            Storage::disk('public')->put($path, $imageContent);

            return $path;
        } catch (Exception $e) {
            Log::error('Error downloading avatar from Google', [
                'url' => $avatarUrl,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
