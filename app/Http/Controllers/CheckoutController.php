<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\IyzicoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    protected $iyzicoService;

    public function __construct(IyzicoService $iyzicoService)
    {
        $this->iyzicoService = $iyzicoService;
    }

    public function index()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')
                           ->with('error', 'Sepetiniz boş!');
        }

        $total = $this->calculateTotal($cart);

        return view('checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'shipping_address' => 'required|string',
            'city' => 'required|string',
            'zip_code' => 'required|string',
            'card_holder_name' => 'required|string',
            'card_number' => 'required|string|size:16',
            'expire_month' => 'required|string|size:2',
            'expire_year' => 'required|string|size:2',
            'cvc' => 'required|string|size:3',
        ]);

        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')
                           ->with('error', 'Sepetiniz boş!');
        }

        DB::beginTransaction();

        try {
            // Sipariş oluştur
            $order = $this->createOrder($request, $cart);

            // Test için ödemeyi başarılı kabul et
            $order->update([
                'payment_status' => 'paid',
                'payment_transaction_id' => 'TEST-' . time(),
            ]);

            // Stok güncelle
            $this->updateStock($cart);

            // Sepeti temizle
            Session::forget('cart');

            DB::commit();

            return redirect()->route('checkout.success', $order)
                           ->with('success', 'Ödeme başarıyla tamamlandı!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                           ->with('error', 'Bir hata oluştu: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function success(Order $order)
    {
        if ($order->payment_status !== 'paid') {
            abort(404);
        }

        return view('checkout.success', compact('order'));
    }

    private function createOrder(Request $request, array $cart)
    {
        $subtotal = $this->calculateTotal($cart);
        $taxAmount = $subtotal * 0.18; // %18 KDV
        $total = $subtotal + $taxAmount;

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => $request->shipping_address . ', ' . $request->city . ' ' . $request->zip_code,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $total,
            'payment_method' => 'credit_card',
        ]);

        // Sipariş ürünlerini kaydet
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
            ]);
        }

        return $order;
    }

    private function calculateTotal(array $cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    private function updateStock(array $cart)
    {
        foreach ($cart as $item) {
            Product::where('id', $item['id'])
                   ->decrement('stock_quantity', $item['quantity']);
        }
    }
}
