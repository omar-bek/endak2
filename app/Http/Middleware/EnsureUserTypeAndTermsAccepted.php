<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserTypeAndTermsAccepted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'يجب تسجيل الدخول للوصول إلى هذه الصفحة.'], 401);
            }
            return redirect()->route('login');
        }

        if (!$user->user_type || !$user->terms_accepted_at) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'يرجى إكمال بيانات الحساب والموافقة على الشروط قبل المتابعة.'
                ], 403);
            }
            return redirect()->route('complete-profile')
                ->withErrors(['incomplete_profile' => 'يرجى إكمال بيانات الحساب والموافقة على الشروط قبل المتابعة.']);
        }
        return $next($request);
    }
}
