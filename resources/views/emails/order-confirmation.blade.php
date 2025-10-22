<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Onayı</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #198754;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .order-info {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .order-item {
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .total {
            background-color: #e9ecef;
            padding: 15px;
            margin-top: 15px;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        table {
            width: 100%;
        }
        .text-right {
            text-align: right;
        }
        .text-bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Siparişiniz Alındı!</h1>
    </div>

    <div class="content">
        <p>Merhaba <strong>{{ $order->customer_name }}</strong>,</p>
        <p>Siparişiniz başarıyla alınmıştır ve işleme alınmıştır. Aşağıda sipariş detaylarınızı bulabilirsiniz:</p>

        <div class="order-info">
            <table>
                <tr>
                    <td><strong>Sipariş Numarası:</strong></td>
                    <td class="text-right">{{ $order->order_number }}</td>
                </tr>
                <tr>
                    <td><strong>Sipariş Tarihi:</strong></td>
                    <td class="text-right">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Ödeme Durumu:</strong></td>
                    <td class="text-right">
                        @if($order->payment_status === 'paid')
                            <span style="color: #198754;">✓ Ödendi</span>
                        @else
                            <span style="color: #ffc107;">Beklemede</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <h3>Sipariş Detayları</h3>
        <div class="order-info">
            @foreach($order->items as $item)
                <div class="order-item">
                    <table>
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong><br>
                                <small>{{ $item->quantity }} adet x {{ number_format($item->price, 2) }} TL</small>
                            </td>
                            <td class="text-right text-bold">
                                {{ number_format($item->total, 2) }} TL
                            </td>
                        </tr>
                    </table>
                </div>
            @endforeach
        </div>

        <div class="total">
            <table>
                <tr>
                    <td>Ara Toplam:</td>
                    <td class="text-right">{{ number_format($order->subtotal, 2) }} TL</td>
                </tr>
                <tr>
                    <td>KDV (%{{ $taxRate * 100 }}):</td>
                    <td class="text-right">{{ number_format($order->tax_amount, 2) }} TL</td>
                </tr>
                <tr style="font-size: 18px; font-weight: bold;">
                    <td>TOPLAM:</td>
                    <td class="text-right">{{ number_format($order->total_amount, 2) }} TL</td>
                </tr>
            </table>
        </div>

        <h3>Teslimat Adresi</h3>
        <div class="order-info">
            {{ $order->shipping_address }}
        </div>

        <p style="margin-top: 20px;">
            Siparişiniz hazırlanıp kargoya verildiğinde size tekrar bilgi vereceğiz.
        </p>
    </div>

    <div class="footer">
        <p>Bu e-posta otomatik olarak gönderilmiştir. Lütfen cevap vermeyiniz.</p>
        <p>&copy; {{ date('Y') }} E-Commerce System. Tüm hakları saklıdır.</p>
    </div>
</body>
</html>
