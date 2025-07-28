@extends('layouts.app')

@section('title', 'Sipariş Detayı')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Sipariş Detayı: {{ $order->order_number }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Sipariş Ürünleri -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Sipariş Ürünleri</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ürün</th>
                                    <th>Adet</th>
                                    <th>Birim Fiyat</th>
                                    <th>Toplam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->product_name }}</strong>
                                            @if($item->product)
                                                <br><small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price, 2) }} TL</td>
                                        <td>{{ number_format($item->total, 2) }} TL</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Ara Toplam</th>
                                    <th>{{ number_format($order->subtotal, 2) }} TL</th>
                                </tr>
                                <tr>
                                    <th colspan="3">KDV (%18)</th>
                                    <th>{{ number_format($order->tax_amount, 2) }} TL</th>
                                </tr>
                                <tr class="table-success">
                                    <th colspan="3">Genel Toplam</th>
                                    <th>{{ number_format($order->total_amount, 2) }} TL</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Müşteri Bilgileri -->
            <div class="card">
                <div class="card-header">
                    <h5>Müşteri Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Ad Soyad:</strong> {{ $order->customer_name }}</p>
                            <p><strong>E-posta:</strong> {{ $order->customer_email }}</p>
                            <p><strong>Telefon:</strong> {{ $order->customer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Teslimat Adresi:</strong></p>
                            <p>{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Sipariş Durumu -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Sipariş Durumu</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Durum Güncelle</label>
                            <select name="order_status" class="form-select">
                                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>
                                    İşleniyor
                                </option>
                                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>
                                    Kargoda
                                </option>
                                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>
                                    Teslim Edildi
                                </option>
                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>
                                    İptal
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Durumu Güncelle
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sipariş Bilgileri -->
            <div class="card">
                <div class="card-header">
                    <h5>Sipariş Bilgileri</h5>
                </div>
                <div class="card-body">
                    <p><strong>Sipariş No:</strong> {{ $order->order_number }}</p>
                    <p><strong>Tarih:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Ödeme Yöntemi:</strong> {{ $order->payment_method ?? 'Kredi Kartı' }}</p>

                    <p><strong>Ödeme Durumu:</strong></p>
                    @if($order->payment_status == 'paid')
                        <span class="badge bg-success">Ödendi</span>
                    @elseif($order->payment_status == 'pending')
                        <span class="badge bg-warning">Bekliyor</span>
                    @else
                        <span class="badge bg-danger">Başarısız</span>
                    @endif

                    <p class="mt-2"><strong>Sipariş Durumu:</strong></p>
                    @if($order->order_status == 'processing')
                        <span class="badge bg-warning">İşleniyor</span>
                    @elseif($order->order_status == 'shipped')
                        <span class="badge bg-info">Kargoda</span>
                    @elseif($order->order_status == 'delivered')
                        <span class="badge bg-success">Teslim Edildi</span>
                    @else
                        <span class="badge bg-danger">İptal</span>
                    @endif

                    @if($order->payment_transaction_id)
                        <p class="mt-2"><strong>İşlem ID:</strong><br>
                        <small>{{ $order->payment_transaction_id }}</small></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
