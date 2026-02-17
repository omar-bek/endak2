<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Exception;

class HomeController extends Controller
{
    /**
     * عرض الصفحة الرئيسية
     */
    public function index()
    {
        try {
            // الأقسام الرئيسية
            $categories = Category::getMainCategories();

            // الخدمات المميزة
            $featuredServicesQuery = Service::where('is_active', true)
                ->where('is_featured', true)
                ->with(['category', 'user']);

            // إذا كان المستخدم مسجل دخول وليس مزود خدمة، اعرض فقط خدماته
            if (auth()->check() && !auth()->user()->isProvider()) {
                $featuredServicesQuery->where('user_id', auth()->id());
            }

            $featuredServices = $featuredServicesQuery->latest()->limit(6)->get();

            // أحدث الخدمات
            $latestServicesQuery = Service::where('is_active', true)
                ->with(['category', 'user']);

            // إذا كان المستخدم مسجل دخول وليس مزود خدمة، اعرض فقط خدماته
            if (auth()->check() && !auth()->user()->isProvider()) {
                $latestServicesQuery->where('user_id', auth()->id());
            }

            $latestServices = $latestServicesQuery->latest()->limit(8)->get();

            return view('home', compact('categories', 'featuredServices', 'latestServices'));
        } catch (Exception $e) {
            Log::error('Error in HomeController@index: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return view('home', [
                'categories' => collect(),
                'featuredServices' => collect(),
                'latestServices' => collect()
            ])->with('error', 'حدث خطأ أثناء تحميل الصفحة الرئيسية');
        }
    }

    /**
     * صفحة اتصل بنا
     */
    public function contact()
    {
        try {
            return view('contact');
        } catch (Exception $e) {
            Log::error('Error in HomeController@contact: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'حدث خطأ أثناء تحميل الصفحة');
        }
    }
}
