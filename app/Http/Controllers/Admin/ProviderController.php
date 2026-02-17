<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * عرض جميع مزودي الخدمات
     */
    public function index(Request $request)
    {
        $query = User::where('user_type', 'provider') // مزود خدمة
                    ->with(['providerProfile', 'services', 'offers']);

        // فلترة حسب البحث
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // فلترة حسب التحقق
        if ($request->has('verification') && $request->verification !== '') {
            $query->where('is_verified', $request->verification);
        }

        // فلترة حسب الحالة
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $providers = $query->latest()->paginate(20);

        return view('admin.providers.index', compact('providers'));
    }

    /**
     * عرض مزود خدمة معين
     */
    public function show(User $provider)
    {
        if ($provider->user_type !== 'provider') {
            abort(404);
        }

        $provider->load(['providerProfile', 'services', 'offers.service']);

        return view('admin.providers.show', compact('provider'));
    }

    /**
     * التحقق من مزود الخدمة
     */
    public function verify(User $provider)
    {
        if ($provider->user_type !== 'provider') {
            abort(404);
        }

        $provider->update(['is_verified' => !$provider->is_verified]);

        $status = $provider->is_verified ? 'التحقق من' : 'إلغاء التحقق من';

        return back()->with('success', "تم $status مزود الخدمة بنجاح");
    }
}
