<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Events\Registered;
use Exception;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'profile', 'updateProfile', 'saveUserType', 'showCompleteProfile']);
    }

    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLoginForm()
    {
        try {
            return view('auth.login');
        } catch (Exception $e) {
            Log::error('Error in AuthController@showLoginForm: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الصفحة');
        }
    }

    /**
     * معالجة تسجيل الدخول
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // التحقق من الإيميل قبل محاولة تسجيل الدخول
            $user = User::where('email', $credentials['email'])->first();

            if ($user && Hash::check($credentials['password'], $user->password)) {
                // التحقق من تحقق الإيميل
                if (!$user->hasVerifiedEmail()) {
                    return back()->withErrors([
                        'email' => 'لا يمكنك الدخول إلى الموقع قبل توثيق البريد الإلكتروني. إذا كان البريد الذي سجلت به خاطئاً فلن يتم توثيق حسابك.'
                    ])->onlyInput('email');
                }

                // إذا كان الإيميل محققاً، قم بتسجيل الدخول
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                Log::info('User logged in', ['user_id' => $user->id]);

                return redirect()->intended('/');
            }

            return back()->withErrors([
                'email' => 'الإيميل أو كلمة المرور غير صحيحة.',
            ])->onlyInput('email');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in AuthController@login: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $request->email ?? null
            ]);
            return back()->with('error', 'حدث خطأ أثناء تسجيل الدخول')->withInput();
        }
    }

    /**
     * عرض صفحة التسجيل
     */
    public function showRegistrationForm()
    {
        try {
            return view('auth.register');
        } catch (Exception $e) {
            Log::error('Error in AuthController@showRegistrationForm: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الصفحة');
        }
    }

    /**
     * معالجة التسجيل بالإيميل والهاتف
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email:rfc,dns|max:255|unique:users',
                'phone' => 'required|string|max:20|unique:users',
                'password' => 'required|string|min:8',
                'user_type' => 'required|in:customer,provider',
                'terms' => 'required|accepted',
            ], [
                'email.email' => 'البريد الإلكتروني غير صحيح. الحساب الخاطئ لن يتم توثيقه ولن يستطيع الدخول إلى الموقع.',
                'email.unique' => 'هذا البريد الإلكتروني مسجل بالفعل.',
            ]);

            // Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'user_type' => $validated['user_type'],
                'terms_accepted_at' => now(),
            ]);

            // Login user temporarily to show verification notice
            Auth::login($user);

            // Send email verification notification directly (not queued)
            $emailSent = false;
            try {
                $user->sendEmailVerificationNotification();
                $emailSent = true;
            } catch (Exception $emailException) {
                Log::error('Email verification failed during registration', [
                    'user_id' => $user->id,
                    'error' => $emailException->getMessage()
                ]);
            }

            Log::info('User registered', ['user_id' => $user->id, 'user_type' => $user->user_type, 'email_sent' => $emailSent]);

            $message = 'تم إنشاء الحساب بنجاح! يرجى التحقق من الإيميل لإكمال التسجيل. إذا كان البريد الإلكتروني خاطئاً فلن يتم توثيق حسابك ولن تستطيع الدخول إلى الموقع.';
            if (!$emailSent) {
                $message = 'تم إنشاء الحساب بنجاح! لكن حدث خطأ في إرسال رابط التحقق. تأكد من صحة بريدك الإلكتروني، فالحساب الخاطئ لن يتم توثيقه ولن يدخل الموقع. يرجى الضغط على "إعادة إرسال" في الصفحة التالية.';
            }

            return redirect()->route('verification.notice')->with($emailSent ? 'success' : 'warning', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in AuthController@register: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->except(['password'])
            ]);
            return back()->with('error', 'حدث خطأ أثناء التسجيل')->withInput();
        }
    }


    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        try {
            $userId = Auth::id();
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('User logged out', ['user_id' => $userId]);

            return redirect('/');
        } catch (Exception $e) {
            Log::error('Error in AuthController@logout: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect('/');
        }
    }

    /**
     * عرض الملف الشخصي
     */
    public function profile()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول');
            }

            // إذا كان مزود خدمة، توجيه إلى صفحة مزود الخدمة
            if ($user->isProvider()) {
                return redirect()->route('provider.profile');
            }

            return view('profile', compact('user'));
        } catch (Exception $e) {
            Log::error('Error in AuthController@profile: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الملف الشخصي');
        }
    }

    /**
     * عرض صفحة إتمام الملف الشخصي (اختيار الدور والموافقة على الشروط)
     */
    public function showCompleteProfile()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول');
            }

            // إذا كان المستخدم قد اختار الدور ووافق على الشروط، لا حاجة لهذه الصفحة
            if ($user->user_type && $user->terms_accepted_at) {
                return redirect('/');
            }

            return view('auth.complete-profile');
        } catch (Exception $e) {
            Log::error('Error in AuthController@showCompleteProfile: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الصفحة');
        }
    }

    /**
     * معالجة إتمام الملف الشخصي
     */
    // public function completeProfile(Request $request)
    // {
    //     try {
    //         $user = Auth::user();

    //         if (!$user) {
    //             if ($request->ajax() || $request->wantsJson()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'يجب تسجيل الدخول أولاً'
    //                 ], 401);
    //             }
    //             return redirect()->route('login');
    //         }

    //         $request->validate([
    //             'user_type' => 'required|in:customer,provider',
    //             'terms' => 'required|accepted',
    //         ], [
    //             'user_type.required' => 'يجب اختيار نوع الحساب',
    //             'user_type.in' => 'نوع الحساب غير صحيح',
    //             'terms.required' => 'يجب الموافقة على الشروط والأحكام',
    //             'terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
    //         ]);

    //         $user->user_type = $request->user_type;
    //         $user->terms_accepted_at = now();
    //         $user->save();

    //         // Regenerate session to prevent CSRF token issues
    //         $request->session()->regenerate();

    //         // Remove session flag
    //         session()->forget('show_user_type_modal');

    //         // Return JSON response if AJAX request (from modal), otherwise redirect
    //         if ($request->ajax() || $request->wantsJson()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'تم إتمام الملف الشخصي بنجاح! مرحباً بك في إنداك'
    //             ]);
    //         }

    //         return redirect('/')->with('success', 'تم إتمام الملف الشخصي بنجاح! مرحباً بك في إنداك');
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         if ($request->ajax() || $request->wantsJson()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'التحقق من البيانات فشل',
    //                 'errors' => $e->errors()
    //             ], 422);
    //         }
    //         return back()->withErrors($e->errors())->withInput();
    //     } catch (\Exception $e) {
    //         Log::error('Complete profile error: ' . $e->getMessage());

    //         if ($request->ajax() || $request->wantsJson()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'حدث خطأ أثناء إتمام الملف الشخصي: ' . $e->getMessage()
    //             ], 500);
    //         }

    //         return back()->withErrors(['error' => 'حدث خطأ أثناء إتمام الملف الشخصي. يرجى المحاولة مرة أخرى.'])->withInput();
    //     }
    // }
    public function saveUserType(Request $request)
    {
        try {
            $request->validate([
                'user_type' => 'required|in:customer,provider',
                'terms' => 'required|accepted',
            ], [
                'user_type.required' => 'يجب اختيار نوع الحساب',
                'user_type.in' => 'قيمة غير صالحة لنوع الحساب',
                'terms.required' => 'يجب الموافقة على الشروط والأحكام',
                'terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
            ]);

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب تسجيل الدخول أولاً'
                ], 401);
            }

            $userType = $request->input('user_type');

            // Use DB::table()->update() to ensure it's saved
            $affected = DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'user_type' => $userType,
                    'terms_accepted_at' => now(),
                    'updated_at' => now()
                ]);

            // Refresh user model
            $user->refresh();

            // Remove session flag
            session()->forget('show_user_type_modal');

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث نوع الحساب بنجاح',
                    'user_type' => $user->user_type
                ]);
            }

            return redirect()->intended('/')
                ->with('success', 'تم تحديث نوع الحساب بنجاح! يمكنك الآن متابعة استخدام الموقع.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'التحقق من البيانات فشل',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Save user type error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.'
                ], 500);
            }

            return back()->withErrors([
                'error' => 'حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.'
            ])->withInput();
        }
    }


    /**
     * عرض صفحة تعديل الملف الشخصي
     */
    public function editProfile()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول');
            }

            return view('profile-edit', compact('user'));
        } catch (Exception $e) {
            Log::error('Error in AuthController@editProfile: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('profile')->with('error', 'حدث خطأ أثناء تحميل صفحة التعديل');
        }
    }

    /**
     * تحديث الملف الشخصي
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'يجب تسجيل الدخول');
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
                'bio' => 'nullable|string|max:1000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $data = $request->only(['name', 'phone', 'bio']);

            // رفع الصورة الشخصية
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة
                if ($user->image && Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }

                $data['image'] = $request->file('image')->store('users', 'public');
            }

            $user->update($data);

            Log::info('User profile updated', ['user_id' => $user->id]);

            return redirect()->route('profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Error in AuthController@updateProfile: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => Auth::id()
            ]);
            return back()->with('error', 'حدث خطأ أثناء تحديث الملف الشخصي')->withInput();
        }
    }
}
