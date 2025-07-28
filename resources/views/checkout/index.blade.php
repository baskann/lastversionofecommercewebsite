@extends('layouts.app')

@section('title', 'Ödeme')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Ödeme Bilgileri</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('checkout.process') }}">
                        @csrf

                        <h6>Kişisel Bilgiler</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Ad Soyad</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                       name="customer_name" value="{{ old('customer_name') }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">E-posta</label>
                                <input type="email" class="form-control @error('customer_email') is-invalid @enderror"
                                       name="customer_email" value="{{ old('customer_email') }}" required>
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Telefon</label>
                                <input type="text" class="form-control @error('customer_phone') is-invalid @enderror"
                                       name="customer_phone" value="{{ old('customer_phone') }}" required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Şehir</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                       name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label class="form-label">Adres</label>
                                <textarea class="form-control @error('shipping_address') is-invalid @enderror"
                                          name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Posta Kodu</label>
                                <input type="text" class="form-control @error('zip_code') is-invalid @enderror"
                                       name="zip_code" value="{{ old('zip_code') }}" required>
                                @error('zip_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <h6>Kart Bilgileri</h6>

                        <div class="mb-3">
                            <label class="form-label">Kart Sahibi</label>
                            <input type="text" class="form-control @error('card_holder_name') is-invalid @enderror"
                                   name="card_holder_name" value="{{ old('card_holder_name') }}" required>
                            @error('card_holder_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kart Numarası</label>
                            <input type="text" class="form-control @error('card_number') is-invalid @enderror"
                                   name="card_number" value="{{ old('card_number') }}"
                                   placeholder="1234567890123456" maxlength="16" required>
                            @error('card_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Ay</label>
                                <input type="text" class="form-control @error('expire_month') is-invalid @enderror"
                                       name="expire_month" value="{{ old('expire_month') }}"
                                       placeholder="12" maxlength="2" required>
                                @error('expire_month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Yıl</label>
                                <input type="text" class="form-control @error('expire_year') is-invalid @enderror"
                                       name="expire_year" value="{{ old('expire_year') }}"
                                       placeholder="25" maxlength="2" required>
                                @error('expire_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">CVC</label>
                                <input type="text" class="form-control @error('cvc') is-invalid @enderror"
                                       name="cvc" value="{{ old('cvc') }}"
                                       placeholder="123" maxlength="3" required>
                                @error('cvc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100">
                            {{ number_format($total * 1.18, 2) }} TL Öde
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>Sipariş Özeti</h6>
                </div>
                <div class="card-body">
                    @foreach($cart as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item['name'] }} ({{ $item['quantity'] }}x)</span>
                            <span>{{ number_format($item['price'] * $item['quantity'], 2) }} TL</span>
                        </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span>Ara Toplam:</span>
                        <span>{{ number_format($total, 2) }} TL</span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>KDV (%18):</span>
                        <span>{{ number_format($total * 0.18, 2) }} TL</span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fw-bold">
                        <span>Toplam:</span>
                        <span>{{ number_format($total * 1.18, 2) }} TL</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
