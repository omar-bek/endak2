<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // الحصول على اللغة من الجلسة أو استخدام اللغة الافتراضية
        $locale = session('locale', 'ar');
        
        // تعيين اللغة للتطبيق
        app()->setLocale($locale);
        
        return $next($request);
    }
}
