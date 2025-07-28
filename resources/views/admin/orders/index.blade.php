@extends('layouts.app')

@section('title', 'Sipariş Yönetimi')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Sipariş Yönetimi</h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
    </div>

    <!-- Filtreler -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Sipariş Durumu</label>
                    <select name="status" class="form-select">
                        <option value="">Tümü</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>İşleniyor</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Kargoda</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Teslim Edildi</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>İptal</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ödeme Durumu</label>
                    <select name="payment_status" class="form-select">
                        <option value="">Tümü</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Bekliyor</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Ödendi</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Başarısız</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">Filtrele</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Temizle</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sipariş No</th>
                            <th>Müşteri</th>
                            <th>Tutar</th>
                            <th>Ödeme Durumu</th>
                            <th>Sipariş Durumu</th>
                            <th>Tarih</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <strong>{{ $order->order_number }}</strong>
                                </td>
                                <td>
                                    {{ $order->customer_name }}<br>
                                    <small class="text-muted">{{ $order->customer_email }}</small>
                                </td>
                                <td>{{ number_format($order->total_amount, 2) }} TL</td>
                                <td>
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success">Ödendi</span>
                                    @elseif($order->payment_status == 'pending')
                                        <span class="badge bg-warning">Bekliyor</span>
                                    @else
                                        <span class="badge bg-danger">Başarısız</span>
                                    @endif
                                </td>
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
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i> Detay
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Sipariş bulunamadı</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
