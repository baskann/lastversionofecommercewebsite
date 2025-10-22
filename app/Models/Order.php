<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'customer_name', 'customer_email',
        'customer_phone', 'shipping_address', 'subtotal',
        'tax_amount', 'total_amount', 'payment_status',
        'order_status', 'payment_method', 'payment_transaction_id'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber()
    {
        // Benzersiz sipariş numarası oluştur
        do {
            $number = config('ecommerce.order_number_prefix', 'ORD') . '-'
                    . date('Ymd') . '-'
                    . str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('order_number', $number)->exists());

        return $number;
    }
}
