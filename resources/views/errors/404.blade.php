@extends('layouts.app')

@section('title', '404 - Sayfa Bulunamadı')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="min-height: 60vh; align-items: center;">
        <div class="col-md-8 text-center">
            <div class="error-content">
                <h1 class="display-1 fw-bold text-primary">404</h1>
                <h2 class="mb-4">Sayfa Bulunamadı</h2>
                <p class="lead mb-4">
                    Üzgünüz, aradığınız sayfa mevcut değil veya taşınmış olabilir.
                </p>

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i> Ana Sayfaya Dön
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i> Ürünlere Göz At
                    </a>
                </div>

                <div class="mt-5">
                    <p class="text-muted">
                        Bir hata olduğunu düşünüyorsanız, lütfen
                        <a href="mailto:destek@example.com">destek ekibimizle</a> iletişime geçin.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-content h1 {
    font-size: 8rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .error-content h1 {
        font-size: 5rem;
    }
}
</style>
@endsection
