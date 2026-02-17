<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Route model binding للأقسام
        \Illuminate\Support\Facades\Route::bind('category', function ($value, $route) {
            // في لوحة الإدارة، نستخدم id دائماً
            if ($route->named('admin.*') || request()->is('admin/*')) {
                // إذا كان الرقم، نبحث باستخدام id فقط
                if (is_numeric($value)) {
                    return \App\Models\Category::findOrFail($value);
                }
                // إذا لم يكن رقم، نحاول slug
                return \App\Models\Category::where('slug', $value)->firstOrFail();
            }

            // في API routes، نستخدم id إذا كان رقم، وإلا slug
            if (request()->is('api/*')) {
                if (is_numeric($value)) {
                    return \App\Models\Category::findOrFail($value);
                }
                return \App\Models\Category::where('slug', $value)->firstOrFail();
            }

            // في الصفحات العامة، نستخدم slug (السلوك الافتراضي)
            return \App\Models\Category::where('slug', $value)
                ->orWhere('id', $value)
                ->firstOrFail();
        });
    }
}
