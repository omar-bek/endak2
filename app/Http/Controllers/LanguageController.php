<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        try {
            // التحقق من أن اللغة مدعومة
            $supportedLocales = ['ar', 'en'];
            
            if (!in_array($locale, $supportedLocales)) {
                $locale = 'ar';
            }
            
            // حفظ اللغة في الجلسة
            session()->put('locale', $locale);
            
            // إعادة التوجيه للصفحة السابقة
            return redirect()->back();
        } catch (Exception $e) {
            Log::error('Error in LanguageController@switch: ' . $e->getMessage(), [
                'exception' => $e,
                'locale' => $locale
            ]);
            return redirect()->back();
        }
    }
}
