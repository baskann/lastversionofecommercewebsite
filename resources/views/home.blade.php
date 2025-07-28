@extends('layouts.app')

@section('title', 'Ana Sayfa - E-Ticaret Sistemi')

@section('content')
<!-- Hero Section -->
<div class="hero-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 5rem 0; margin-bottom: 2rem;">
    <div class="container">
        <div class="row align-items-center text-white">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4" style="color: white !important;">
                    Modern Alışveriş Deneyimi
                </h1>
                <p class="lead mb-4" style="color: rgba(255,255,255,0.9) !important;">
                    En kaliteli ürünler, en uygun fiyatlarla. Güvenli ödeme ve hızlı teslimat garantisi.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Alışverişe Başla
                    </a>
                    <a href="#categories" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-arrow-down me-2"></i>Kategoriler
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center mt-4 mt-lg-0">
                <i class="fas fa-shopping-cart" style="font-size: 8rem; opacity: 0.3; color: white;"></i>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Öne Çıkan Ürünler -->
    <section class="my-5">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">
                    <i class="fas fa-star text-warning me-3"></i>
                    Öne Çıkan Ürünler
                </h2>
                <p class="lead text-muted mb-4">En popüler ve kaliteli ürünlerimizi keşfedin</p>
                <div class="d-flex justify-content-center mb-4">
                    <div style="width: 100px; height: 4px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 2px;"></div>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($featuredProducts as $product)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card product-card h-100">
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="sale-badge">
                                %{{ round((($product->price - $product->sale_price) / $product->price) * 100) }} İndirim
                            </div>
                        @endif

                        <div class="product-image">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset($product->images[0]) }}" alt="{{ $product->name }}"
                                    style="width: 100%; height: 200px; object-fit: cover; border-radius: 0.75rem 0.75rem 0 0;">
                            @else
                                <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); display: flex; align-items: center; justify-content: center; border-radius: 0.75rem 0.75rem 0 0;">
                                    @if($product->category->slug == 'elektronik')
                                        <i class="fas fa-mobile-alt fa-4x text-muted"></i>
                                    @elseif($product->category->slug == 'bilgisayar')
                                        <i class="fas fa-laptop fa-4x text-muted"></i>
                                    @elseif($product->category->slug == 'aksesuar')
                                        <i class="fas fa-headphones fa-4x text-muted"></i>
                                    @else
                                        <i class="fas fa-box fa-4x text-muted"></i>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                            <p class="card-text text-muted mb-3 flex-grow-1">
                                {{ Str::limit($product->description, 80) }}
                            </p>

                            <div class="mb-3">
                                <span class="badge bg-light text-dark">{{ $product->category->name }}</span>
                                @if($product->stock_quantity < 10)
                                    <span class="badge bg-warning">Son {{ $product->stock_quantity }} adet</span>
                                @endif
                            </div>

                            <div class="price-section mb-3">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="product-price">{{ number_format($product->sale_price, 2) }} TL</span>
                                        <span class="product-sale-price">{{ number_format($product->price, 2) }} TL</span>
                                    </div>
                                @else
                                    <span class="product-price">{{ number_format($product->price, 2) }} TL</span>
                                @endif
                            </div>

                            <div class="d-flex gap-2 mt-auto">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary flex-grow-1">
                                    <i class="fas fa-eye me-1"></i>Detay
                                </a>
                                @if($product->stock_quantity > 0)
                                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-cart-plus me-1"></i>Sepete Ekle
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary flex-grow-1" disabled>
                                        <i class="fas fa-times me-1"></i>Stokta Yok
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Henüz öne çıkan ürün bulunmuyor</h4>
                        <p class="text-muted">Yakında harika ürünlerle karşınızda olacağız!</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Tüm Ürünleri Görüntüle
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($featuredProducts->count() > 0)
            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-right me-2"></i>Tüm Ürünleri Görüntüle
                </a>
            </div>
        @endif
    </section>

    <!-- Kategoriler -->
    <section id="categories" class="my-5 py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 2rem; margin: 0 -15px;">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="display-5 fw-bold mb-3">
                        <i class="fas fa-th-large text-primary me-3"></i>
                        Kategoriler
                    </h2>
                    <p class="lead text-muted">Aradığınız ürünü kolayca bulun</p>
                </div>
            </div>

            <div class="row justify-content-center">
                @foreach($categories as $category)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                            <div class="card h-100 text-center category-card" style="transition: all 0.3s ease;">
                                <div class="card-body py-4">
                                    <div class="mb-3">
                                        @if($category->slug == 'elektronik')
                                            <i class="fas fa-mobile-alt fa-3x text-primary"></i>
                                        @elseif($category->slug == 'bilgisayar')
                                            <i class="fas fa-laptop fa-3x text-info"></i>
                                        @elseif($category->slug == 'aksesuar')
                                            <i class="fas fa-headphones fa-3x text-success"></i>
                                        @else
                                            <i class="fas fa-tag fa-3x text-warning"></i>
                                        @endif
                                    </div>
                                    <h5 class="card-title fw-bold">{{ $category->name }}</h5>
                                    <p class="card-text text-muted">{{ $category->description }}</p>
                                    <div class="mt-3">
                                        <span class="btn btn-outline-primary">
                                            Ürünleri Görüntüle <i class="fas fa-arrow-right ms-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Özellikler -->
    <section class="my-5 py-5">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold mb-3">Neden Bizi Seçmelisiniz?</h2>
                <p class="lead text-muted">Müşteri memnuniyeti odaklı hizmet anlayışımız</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-shipping-fast fa-3x text-success"></i>
                    </div>
                    <h5 class="fw-bold">Hızlı Teslimat</h5>
                    <p class="text-muted">Siparişleriniz aynı gün kargoya verilir</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h5 class="fw-bold">Güvenli Ödeme</h5>
                    <p class="text-muted">256-bit SSL şifreleme ile güvenli alışveriş</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-headset fa-3x text-info"></i>
                    </div>
                    <h5 class="fw-bold">7/24 Destek</h5>
                    <p class="text-muted">Her zaman yanınızdayız</p>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.category-card:hover .fa-arrow-right {
    transform: translateX(5px);
}

.fa-arrow-right {
    transition: transform 0.3s ease;
}
</style>
@endsection
