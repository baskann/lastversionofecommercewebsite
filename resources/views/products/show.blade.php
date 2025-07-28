@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Ana Sayfa</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Ürünler</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card" style="border-radius: 1rem; overflow: hidden;">
                @if($product->images && count($product->images) > 0)
                    <div class="product-gallery">
                        <!-- Ana Resim -->
                        <div class="main-image mb-3" style="height: 400px; overflow: hidden; border-radius: 1rem;">
                            <img id="mainImage" src="{{ asset($product->images[0]) }}" alt="{{ $product->name }}"
                                 style="width: 100%; height: 400px; object-fit: cover; cursor: zoom-in;">
                        </div>

                        <!-- Küçük Resimler -->
                        @if(count($product->images) > 1)
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($product->images as $index => $image)
                                    <div class="thumbnail {{ $index == 0 ? 'active' : '' }}"
                                         style="width: 80px; height: 80px; border-radius: 0.5rem; overflow: hidden; cursor: pointer; border: 2px solid {{ $index == 0 ? '#6366f1' : '#e2e8f0' }};"
                                         onclick="changeMainImage('{{ asset($image) }}', this)">
                                        <img src="{{ asset($image) }}" alt="{{ $product->name }}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div class="card-body text-center" style="height: 400px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);">
                        @if($product->category->slug == 'elektronik')
                            <i class="fas fa-mobile-alt fa-5x text-muted"></i>
                        @elseif($product->category->slug == 'bilgisayar')
                            <i class="fas fa-laptop fa-5x text-muted"></i>
                        @elseif($product->category->slug == 'aksesuar')
                            <i class="fas fa-headphones fa-5x text-muted"></i>
                        @else
                            <i class="fas fa-box fa-5x text-muted"></i>
                        @endif
                        <p class="mt-3 text-muted">Ürün Resmi</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-6">
            <div class="product-details">
                <div class="mb-3">
                    <span class="badge bg-primary" style="font-size: 0.9rem; padding: 0.5rem 1rem; border-radius: 2rem;">
                        {{ $product->category->name }}
                    </span>
                    @if($product->is_featured)
                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.9rem; padding: 0.5rem 1rem; border-radius: 2rem;">
                            <i class="fas fa-star me-1"></i>Öne Çıkan
                        </span>
                    @endif
                </div>

                <h1 class="display-6 fw-bold mb-3">{{ $product->name }}</h1>
                <p class="text-muted mb-4" style="font-size: 0.9rem;">SKU: {{ $product->sku }}</p>

                <!-- Fiyat Bölümü -->
                <div class="price-section mb-4 p-4" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 1rem;">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="display-5 fw-bold text-success">{{ number_format($product->sale_price, 2) }} TL</span>
                            <span class="h4 text-muted" style="text-decoration: line-through;">{{ number_format($product->price, 2) }} TL</span>
                        </div>
                        <div class="alert alert-success mb-0" style="border-radius: 0.75rem;">
                            <i class="fas fa-tag me-2"></i>
                            <strong>%{{ round((($product->price - $product->sale_price) / $product->price) * 100) }} İndirim!</strong>
                            {{ number_format($product->price - $product->sale_price, 2) }} TL tasarruf ediyorsunuz.
                        </div>
                    @else
                        <span class="display-5 fw-bold text-success">{{ number_format($product->getCurrentPrice(), 2) }} TL</span>
                    @endif
                </div>

                <!-- Stok Durumu -->
                <div class="stock-info mb-4">
                    @if($product->stock_quantity > 0)
                        @if($product->stock_quantity < 10)
                            <div class="alert alert-warning" style="border-radius: 0.75rem;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Son {{ $product->stock_quantity }} adet!</strong> Hemen sipariş verin.
                            </div>
                        @else
                            <div class="alert alert-success" style="border-radius: 0.75rem;">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Stokta var</strong> ({{ $product->stock_quantity }} adet)
                            </div>
                        @endif
                    @else
                        <div class="alert alert-danger" style="border-radius: 0.75rem;">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Stokta Yok</strong> - Yakında gelecek
                        </div>
                    @endif
                </div>

                <!-- Açıklama -->
                <div class="description mb-4">
                    <h5 class="fw-bold mb-3">Ürün Açıklaması</h5>
                    <div class="card" style="border-radius: 0.75rem;">
                        <div class="card-body">
                            <p class="mb-0" style="line-height: 1.6;">{{ $product->description }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sepete Ekleme -->
                @if($product->stock_quantity > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Adet</label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="decreaseQuantity()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" name="quantity" id="quantity" class="form-control text-center"
                                           value="1" min="1" max="{{ $product->stock_quantity }}"
                                           style="border-radius: 0;">
                                    <button type="button" class="btn btn-outline-secondary" onclick="increaseQuantity()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg"
                                    style="border-radius: 0.75rem; font-weight: 600; padding: 1rem;">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Sepete Ekle - {{ number_format($product->getCurrentPrice(), 2) }} TL
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg"
                               style="border-radius: 0.75rem; font-weight: 600;">
                                <i class="fas fa-arrow-left me-2"></i>Ürünlere Dön
                            </a>
                        </div>
                    </form>
                @else
                    <div class="d-grid gap-2">
                        <button class="btn btn-secondary btn-lg" disabled
                                style="border-radius: 0.75rem; font-weight: 600; padding: 1rem;">
                            <i class="fas fa-times me-2"></i>Stokta Yok
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg"
                           style="border-radius: 0.75rem; font-weight: 600;">
                            <i class="fas fa-arrow-left me-2"></i>Ürünlere Dön
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Benzer Ürünler -->
    @if($relatedProducts->count() > 0)
        <div class="mt-5">
            <h3 class="fw-bold mb-4">Benzer Ürünler</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100" style="border-radius: 1rem; overflow: hidden; transition: all 0.3s ease;">
                            <div style="height: 180px; overflow: hidden;">
                                @if($relatedProduct->images && count($relatedProduct->images) > 0)
                                    <img src="{{ asset($relatedProduct->images[0]) }}" alt="{{ $relatedProduct->name }}"
                                         style="width: 100%; height: 180px; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 180px; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-box fa-2x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h6 class="card-title fw-bold">{{ $relatedProduct->name }}</h6>
                                <p class="card-text text-success fw-bold">{{ number_format($relatedProduct->getCurrentPrice(), 2) }} TL</p>
                                <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="fas fa-eye me-1"></i>Görüntüle
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
function changeMainImage(src, thumbnail) {
    document.getElementById('mainImage').src = src;

    // Tüm thumbnail'ların border'ını temizle
    document.querySelectorAll('.thumbnail').forEach(t => {
        t.style.border = '2px solid #e2e8f0';
        t.classList.remove('active');
    });

    // Tıklanan thumbnail'ı aktif yap
    thumbnail.style.border = '2px solid #6366f1';
    thumbnail.classList.add('active');
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);

    if (current < max) {
        input.value = current + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const min = parseInt(input.getAttribute('min'));
    const current = parseInt(input.value);

    if (current > min) {
        input.value = current - 1;
    }
}

// Hover efektleri
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>

<style>
.thumbnail:hover {
    opacity: 0.8;
    transform: scale(1.05);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

#mainImage:hover {
    transform: scale(1.02);
    transition: transform 0.3s ease;
}
</style>
@endsection
