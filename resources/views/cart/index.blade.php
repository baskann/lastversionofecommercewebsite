@extends('layouts.app')

@section('title', 'Sepet')

@section('content')
<div class="container">
    <h2>Alışveriş Sepeti</h2>

    @if(empty($cart))
        <div class="alert alert-info">
            <h4>Sepetiniz boş!</h4>
            <p>Alışverişe başlamak için ürünlere göz atın.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Ürünleri Görüntüle</a>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Ürün</th>
                                        <th>Fiyat</th>
                                        <th>Adet</th>
                                        <th>Toplam</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item['name'] }}</strong>
                                            </td>
                                            <td>{{ number_format($item['price'], 2) }} TL</td>
                                            <td>
                                                <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="input-group" style="width: 120px;">
                                                        <input type="number" name="quantity" class="form-control form-control-sm"
                                                               value="{{ $item['quantity'] }}" min="1">
                                                        <button type="submit" class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-sync"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td>{{ number_format($item['price'] * $item['quantity'], 2) }} TL</td>
                                            <td>
                                                <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Bu ürünü sepetten kaldırmak istediğinizden emin misiniz?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Sipariş Özeti</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ara Toplam:</span>
                            <span>{{ number_format($total, 2) }} TL</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>KDV (%{{ $taxRate * 100 }}):</span>
                            <span>{{ number_format($total * $taxRate, 2) }} TL</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <strong>Toplam:</strong>
                            <strong>{{ number_format($total * (1 + $taxRate), 2) }} TL</strong>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg">
                                Ödemeye Geç
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                Alışverişe Devam Et
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
