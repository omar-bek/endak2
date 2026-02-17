<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use App\Models\Order;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * عرض لوحة الإدارة
     */
    public function index()
    {
        // إحصائيات عامة
        $stats = [
            'total_users' => User::count(),
            'total_categories' => Category::count(),
            'total_services' => Service::count(),
            'total_orders' => Order::count(),
            'active_services' => Service::where('is_active', true)->count(),
            'featured_services' => Service::where('is_featured', true)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_providers' => User::where('user_type', 'provider')->count(),
            'total_offers' => \App\Models\ServiceOffer::where('status', 'pending')->count(),
            'total_sub_categories' => \App\Models\SubCategory::count(),
            'total_fields' => \App\Models\CategoryField::count(),
        ];

        // أحدث المستخدمين
        $recentUsers = User::latest()->limit(5)->get();

        // أحدث الخدمات
        $recentServices = Service::with(['category', 'user'])->latest()->limit(5)->get();

        // أحدث الطلبات
        $recentOrders = Order::with(['service', 'user'])->latest()->limit(5)->get();

        // الأقسام الأكثر نشاطاً
        $topCategories = Category::withCount('services')
                                ->orderBy('services_count', 'desc')
                                ->limit(5)
                                ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentServices', 'recentOrders', 'topCategories'));
    }
}
