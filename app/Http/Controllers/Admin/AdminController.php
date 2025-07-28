<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('order_status', 'processing')->count(),
            'total_products' => Product::count(),
            'low_stock_products' => Product::where('stock_quantity', '<', 10)->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
        ];

        $recentOrders = Order::with('items')
                            ->orderBy('created_at', 'desc')
                            ->take(10)
                            ->get();

        $lowStockProducts = Product::where('stock_quantity', '<', 10)
                                  ->orderBy('stock_quantity', 'asc')
                                  ->take(10)
                                  ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}
