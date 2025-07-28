@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Admin Paneli</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>

    <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['total_orders'] }}</h4>
                            <p class="mb-0">Toplam Sipariş</p>
                        </div>
                        <div>
                            <i class="fas fa-shopping-bag fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['pending_orders'] }}</h4>
                            <p class="mb-0">Bekleyen Sipariş</p>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['total_revenue'], 0) }} TL</h4>
                            <p class="mb-0">Toplam Ciro</p>
                        </div>
                        <div>
                            <i class="fas fa-lira-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $stats['total_products'] }}</h4>
                            <p class="mb-0">Toplam Ürün</p>
                        </div>
                        <div>
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Son Siparişler -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Son Siparişler</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Sipariş No</th>
                                    <th>Müşteri</th>
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ number_format($order->total_amount, 2) }} TL</td>
                                        <td>
                                            @if($order->order_status == 'processing')
                                                <span class="badge bg-warning">İşleniyor</span>
                                            @elseif($order->order_status == 'shipped')
                                                <span class="badge bg-info">Kargoda</span>
                                            @elseif($order->order_status == 'delivered')
                                                <span class="badge bg-success">Teslim Edildi</span>
                                            @else
                                                <span class="badge bg-danger">İptal</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Sipariş bulunamadı</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Düşük Stok Uyarısı -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Düşük Stok Uyarısı</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">Tümünü Gör</a>
                </div>
                <div class="card-body">
                    @forelse($lowStockProducts as $product)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small class="fw-bold">{{ $product->name }}</small><br>
                                <small class="text-muted">{{ $product->sku }}</small>
                            </div>
                            <span class="badge bg-danger">{{ $product->stock_quantity }}</span>
                        </div>
                        @if(!$loop->last)<hr class="my-2">@endif
                    @empty
                        <p class="text-muted mb-0">Düşük stoklu ürün yok</p>
                    @endforelse
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Hızlı İşlemler</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Yeni Ürün Ekle
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-box"></i> Ürünleri Yönet
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-shopping-bag"></i> Siparişleri Yönet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
