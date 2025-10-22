<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\IyzicoService;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $taxRate = config('ecommerce.tax_rate', 0.18);

        return view('checkout.index', compact('cart', 'total', 'taxRate'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|min:3|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => ['required', 'string', 'regex:/^[0-9\s\-\+\(\)]{10,20}$/'],
            'shipping_address' => 'required|string|min:10|max:500',
            'city' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',
            'card_holder_name' => 'required|string|max:255',
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

        // Stok kontrolü - Sipariş öncesi
        $stockErrors = $this->checkStockAvailability($cart);
        if (!empty($stockErrors)) {
            return redirect()->route('cart.index')
                           ->with('error', 'Bazı ürünlerde stok yetersiz: ' . implode(', ', $stockErrors));
        }

        DB::beginTransaction();

        try {
            // Sipariş oluştur (stok henüz düşmedi)
            $order = $this->createOrder($request, $cart);

            // Test için ödemeyi başarılı kabul et
            // NOT: Gerçek üretimde burayı İyzico entegrasyonu ile değiştirin
            $order->update([
                'payment_status' => 'paid',
                'payment_transaction_id' => 'TEST-' . time(),
            ]);

            // Ödeme başarılı olduktan SONRA stok güncelle
            $this->updateStock($cart);

            // Sepeti temizle
            Session::forget('cart');

            DB::commit();

            // Loglama
            Log::info('Order completed successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_email' => $order->customer_email,
                'total_amount' => $order->total_amount,
            ]);

            // Email gönder
            try {
                $order->load('items'); // İlişkileri yükle
                Mail::to($order->customer_email)->send(new OrderConfirmation($order));

                Log::info('Order confirmation email sent', [
                    'order_id' => $order->id,
                    'email' => $order->customer_email,
                ]);
            } catch (\Exception $e) {
                // Email gönderilemezse loglayalım ama işleme devam edelim
                Log::error('Failed to send order confirmation email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return redirect()->route('checkout.success', $order)
                           ->with('success', 'Ödeme başarıyla tamamlandı!');

        } catch (\Exception $e) {
            DB::rollback();

            // Detaylı hata logla ama kullanıcıya genel mesaj göster
            Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'cart' => $cart,
                'customer_email' => $request->customer_email,
            ]);

            return redirect()->back()
                           ->with('error', 'Ödeme işlemi sırasında bir hata oluştu. Lütfen tekrar deneyin.')
                           ->withInput($request->except(['card_number', 'cvc', 'expire_month', 'expire_year']));
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
        $taxRate = config('ecommerce.tax_rate', 0.18);
        $taxAmount = $subtotal * $taxRate;
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

    /**
     * Sepetteki ürünlerin stok durumunu kontrol et
     */
    private function checkStockAvailability(array $cart)
    {
        $errors = [];

        foreach ($cart as $item) {
            $product = Product::find($item['id']);

            if (!$product) {
                $errors[] = $item['name'] . ' bulunamadı';
                continue;
            }

            if (!$product->is_active) {
                $errors[] = $item['name'] . ' artık satışta değil';
                continue;
            }

            if ($product->stock_quantity < $item['quantity']) {
                $errors[] = $item['name'] . ' (Stok: ' . $product->stock_quantity . ', İstenen: ' . $item['quantity'] . ')';
            }
        }

        return $errors;
    }
}
