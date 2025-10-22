<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $validStatuses = array_keys(config('ecommerce.order_statuses', [
            'pending' => 'Beklemede',
            'processing' => 'İşleniyor',
            'shipped' => 'Kargoya Verildi',
            'delivered' => 'Teslim Edildi',
            'cancelled' => 'İptal Edildi',
        ]));

        $request->validate([
            'order_status' => 'required|in:' . implode(',', $validStatuses),
        ]);

        $newStatus = $request->order_status;
        $currentStatus = $order->order_status ?? 'pending';

        // Durum geçiş kontrolü
        $validTransitions = config('ecommerce.order_status_transitions', [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => [],
            'cancelled' => [],
        ]);

        if (!isset($validTransitions[$currentStatus]) ||
            !in_array($newStatus, $validTransitions[$currentStatus])) {
            return redirect()->back()
                            ->with('error', 'Geçersiz durum geçişi! ' . ucfirst($currentStatus) . ' durumundan ' . ucfirst($newStatus) . ' durumuna geçilemez.');
        }

        $oldStatus = $order->order_status;
        $order->update([
            'order_status' => $newStatus,
        ]);

        // Loglama
        Log::info('Order status updated', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()
                        ->with('success', 'Sipariş durumu güncellendi!');
    }
}
