<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOffer;
use Illuminate\Http\Request;

class ServiceOfferController extends Controller
{
    /**
     * عرض جميع عروض الخدمات
     */
    public function index(Request $request)
    {
        $query = ServiceOffer::with(['service.category', 'service.user', 'provider']);

        // فلترة حسب البحث
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('service', function($serviceQuery) use ($request) {
                    $serviceQuery->where('title', 'like', '%' . $request->search . '%')
                                ->orWhere('description', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('provider', function($providerQuery) use ($request) {
                    $providerQuery->where('name', 'like', '%' . $request->search . '%')
                                 ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            });
        }

        // فلترة حسب الحالة
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // فلترة حسب الخدمة
        if ($request->has('service') && $request->service) {
            $query->where('service_id', $request->service);
        }

        $offers = $query->latest()->paginate(20);
        $services = \App\Models\Service::where('is_active', true)->get(['id', 'title']);

        return view('admin.service-offers.index', compact('offers', 'services'));
    }

    /**
     * عرض عرض معين
     */
    public function show(ServiceOffer $offer)
    {
        $offer->load(['service.category', 'service.user', 'provider']);

        return view('admin.service-offers.show', compact('offer'));
    }

        /**
     * عرض نموذج تعديل العرض
     */
    public function edit(ServiceOffer $offer)
    {
        $offer->load(['service.category', 'service.user', 'provider']);

        return view('admin.service-offers.edit', compact('offer'));
    }

    /**
     * تحديث العرض
     */
    public function update(Request $request, ServiceOffer $offer)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,accepted,rejected,expired',
        ]);

        // التحقق من أن العرض لم يتم قبوله أو تسليمه
        if (in_array($offer->status, ['accepted', 'delivered'])) {
            return back()->with('error', 'لا يمكن تعديل العرض بعد قبوله أو تسليمه');
        }

        $offer->update([
            'price' => $request->price,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.service-offers.index')
            ->with('success', 'تم تحديث العرض بنجاح');
    }

    /**
     * حذف العرض
     */
    public function destroy(ServiceOffer $offer)
    {
        // التحقق من أن العرض لم يتم قبوله أو تسليمه
        if (in_array($offer->status, ['accepted', 'delivered'])) {
            return back()->with('error', 'لا يمكن حذف العرض بعد قبوله أو تسليمه');
        }

        $offer->delete();

        return redirect()->route('admin.service-offers.index')
            ->with('success', 'تم حذف العرض بنجاح');
    }

    /**
     * تبديل حالة العرض
     */
    public function toggleStatus(Request $request, ServiceOffer $offer)
    {
        if ($request->has('status')) {
            $newStatus = $request->status;
        } else {
            $newStatus = $offer->status === 'pending' ? 'accepted' : 'pending';
        }

        $offer->update(['status' => $newStatus]);

        $status = '';
        switch ($newStatus) {
            case 'accepted':
                $status = 'قبول';
                break;
            case 'rejected':
                $status = 'رفض';
                break;
            case 'pending':
                $status = 'إعادة إلى الانتظار';
                break;
        }

        return back()->with('success', "تم $status العرض بنجاح");
    }
}
