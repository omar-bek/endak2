<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Facebook
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook callback
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            // Find or create user
            $user = User::where('email', $facebookUser->getEmail())->first();
            $isNewUser = !$user;

            if (!$user) {
                // Create new user without user_type and terms_accepted_at - will be asked later
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'phone' => null, // Phone is optional for social login
                    'password' => Hash::make(Str::random(32)), // Random password for social login
                    'user_type' => null, // Will be set after user chooses
                    'email_verified_at' => now(), // Social accounts are considered verified
                    'avatar' => $facebookUser->getAvatar(),
                    'terms_accepted_at' => null, // Will be set after user accepts
                ]);
            } else {
                // Update avatar if exists
                if ($facebookUser->getAvatar() && !$user->avatar) {
                    $user->avatar = $facebookUser->getAvatar();
                    $user->save();
                }

                // Mark email as verified if not already
                if (!$user->hasVerifiedEmail()) {
                    $user->email_verified_at = now();
                    $user->save();
                }
            }

            // Login user (Laravel automatically regenerates session on login)
            Auth::login($user, true);

            // If new user or user hasn't accepted terms/chosen type, set session flag to show modal
            if ($isNewUser || !$user->user_type || !$user->terms_accepted_at) {
                session()->put('show_user_type_modal', true);
                return redirect('/')->with('success', 'تم إنشاء الحساب بنجاح! يرجى اختيار نوع الحساب والموافقة على الشروط والأحكام.');
            }

            // Different message for new registration vs login
            $message = $isNewUser
                ? 'تم إنشاء الحساب بنجاح! مرحباً بك في إنداك'
                : 'تم تسجيل الدخول بنجاح!';

            return redirect('/')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Facebook login error: ' . $e->getMessage());
            return redirect()->route('register')->withErrors([
                'email' => 'فشل التسجيل عبر فيسبوك. يرجى المحاولة مرة أخرى.'
            ]);
        }
    }

    /**
     * Redirect to Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if email exists
            if (!$googleUser->getEmail()) {
                Log::error('Google login error: No email provided');
                return redirect()->route('register')->withErrors([
                    'email' => 'لا يمكن الحصول على البريد الإلكتروني من حساب جوجل. يرجى التأكد من أن حسابك يحتوي على بريد إلكتروني.'
                ]);
            }

            // Find or create user
            $user = User::where('email', $googleUser->getEmail())->first();
            $isNewUser = !$user;

            if (!$user) {
                // Create new user without user_type and terms_accepted_at - will be asked later
                $user = User::create([
                    'name' => $googleUser->getName() ?: 'مستخدم',
                    'email' => $googleUser->getEmail(),
                    'phone' => null, // Phone is optional for social login
                    'password' => Hash::make(Str::random(32)), // Random password for social login
                    'user_type' => null, // Will be set after user chooses
                    'email_verified_at' => now(), // Social accounts are considered verified
                    'avatar' => $googleUser->getAvatar(),
                    'terms_accepted_at' => null, // Will be set after user accepts
                ]);
            } else {
                // Update avatar if exists
                if ($googleUser->getAvatar() && !$user->avatar) {
                    $user->avatar = $googleUser->getAvatar();
                    $user->save();
                }

                // Mark email as verified if not already
                if (!$user->hasVerifiedEmail()) {
                    $user->email_verified_at = now();
                    $user->save();
                }
            }

            // Login user (Laravel automatically regenerates session on login)
            Auth::login($user, true);

            // If new user or user hasn't accepted terms/chosen type, set session flag to show modal
            if ($isNewUser || !$user->user_type || !$user->terms_accepted_at) {
                session()->put('show_user_type_modal', true);
                return redirect('/')->with('success', 'تم إنشاء الحساب بنجاح! يرجى اختيار نوع الحساب والموافقة على الشروط والأحكام.');
            }

            // Different message for new registration vs login
            $message = $isNewUser
                ? 'تم إنشاء الحساب بنجاح! مرحباً بك في إنداك'
                : 'تم تسجيل الدخول بنجاح!';

            return redirect('/')->with('success', $message);
        } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
            Log::error('Google OAuth configuration error: ' . $e->getMessage());
            return redirect()->route('register')->withErrors([
                'email' => 'خطأ في إعدادات جوجل. يرجى التحقق من إعدادات OAuth.'
            ]);
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            Log::error('Google OAuth state error: ' . $e->getMessage());
            return redirect()->route('register')->withErrors([
                'email' => 'انتهت صلاحية الجلسة. يرجى المحاولة مرة أخرى.'
            ]);
        } catch (\Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());
            Log::error('Google login error trace: ' . $e->getTraceAsString());
            return redirect()->route('register')->withErrors([
                'email' => 'فشل التسجيل عبر جوجل: ' . $e->getMessage() . '. يرجى المحاولة مرة أخرى.'
            ]);
        }
    }
}
