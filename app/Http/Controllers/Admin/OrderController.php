<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * عرض جميع الطلبات
     */
    public function index()
    {
        $orders = Order::with(['service.category', 'user', 'provider'])
                      ->latest()
                      ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * عرض طلب معين
     */
    public function show(Order $order)
    {
        $order->load(['service.category', 'user', 'provider']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * تحديث حالة الطلب
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}
