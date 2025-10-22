@extends('layouts.app')

@section('title', '500 - Sunucu Hatası')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="min-height: 60vh; align-items: center;">
        <div class="col-md-8 text-center">
            <div class="error-content">
                <h1 class="display-1 fw-bold text-danger">500</h1>
                <h2 class="mb-4">Sunucu Hatası</h2>
                <p class="lead mb-4">
                    Üzgünüz, bir şeyler ters gitti. Teknik ekibimiz bu sorun üzerinde çalışıyor.
                </p>

                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Lütfen birkaç dakika sonra tekrar deneyin.
                </div>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i> Ana Sayfaya Dön
                    </a>
                    <button onclick="window.location.reload()" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-sync-alt me-2"></i> Sayfayı Yenile
                    </button>
                </div>

                <div class="mt-5">
                    <p class="text-muted">
                        Sorun devam ediyorsa, lütfen
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
