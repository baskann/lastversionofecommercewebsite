<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.product')->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('order_status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'order_status' => $request->order_status,
        ]);

        return redirect()->back()
                        ->with('success', 'Sipariş durumu güncellendi!');
    }
}
