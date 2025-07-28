@extends('layouts.app')

@section('title', 'Ödeme Başarılı')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>

                    <h2 class="text-success mb-3">Ödeme Başarılı!</h2>
                    <p class="lead">Siparişiniz başarıyla alındı.</p>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Sipariş Detayları</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Sipariş No:</strong> {{ $order->order_number }}</p>
                                    <p><strong>Müşteri:</strong> {{ $order->customer_name }}</p>
                                    <p><strong>E-posta:</strong> {{ $order->customer_email }}</p>
                                    <p><strong>Telefon:</strong> {{ $order->customer_phone }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Toplam Tutar:</strong> {{ number_format($order->total_amount, 2) }} TL</p>
                                    <p><strong>Ödeme Durumu:</strong>
                                        <span class="badge bg-success">Ödendi</span>
                                    </p>
                                    <p><strong>Sipariş Durumu:</strong>
                                        <span class="badge bg-primary">İşleniyor</span>
                                    </p>
                                    <p><strong>Tarih:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h6>Sipariş Ürünleri</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Ürün</th>
                                            <th>Adet</th>
                                            <th>Fiyat</th>
                                            <th>Toplam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>{{ $item->product_name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->price, 2) }} TL</td>
                                                <td>{{ number_format($item->total, 2) }} TL</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-primary me-2">
                            Alışverişe Devam Et
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            Ana Sayfa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
