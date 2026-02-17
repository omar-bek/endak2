<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * عرض جميع المستخدمين
     */
    public function index(Request $request)
    {
        $query = User::with(['providerProfile']);

        // فلترة حسب البحث
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // فلترة حسب الدور
        if ($request->has('role') && $request->role) {
            $query->where('user_type', $request->role);
        }

        // فلترة حسب الحالة
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * عرض مستخدم معين
     */
    public function show(User $user)
    {
        $user->load([
            'providerProfile',
            'services.category',
            'offers.service.category',
            'providerCategories.category',
            'providerCities.city'
        ]);

        // تحميل البيانات الإضافية إذا كان المستخدم مزود خدمة
        if ($user->user_type == 'provider') {
            $user->load([
                'providerCategories.category',
                'providerCities.city'
            ]);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * تبديل حالة المستخدم
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'تفعيل' : 'تعطيل';

        return back()->with('success', "تم $status المستخدم بنجاح");
    }

    /**
     * تبديل دور المستخدم
     */
    public function toggleRole(User $user)
    {
        $newRole = $user->user_type === 'admin' ? 'customer' : 'admin';
        $user->update(['user_type' => $newRole]);

        $role = $newRole === 'admin' ? 'مدير' : 'مستخدم عادي';

        return back()->with('success', "تم تغيير دور المستخدم إلى $role بنجاح");
    }
}
