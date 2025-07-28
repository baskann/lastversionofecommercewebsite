@extends('layouts.app')

@section('title', 'Ürünler')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h6>Kategoriler</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action">
                            Tüm Ürünler
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                               class="list-group-item list-group-item-action">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Ürünler</h2>

                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2"
                           placeholder="Ürün ara..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-primary">Ara</button>
                </form>
            </div>

            <div class="row">
                @forelse($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100" style="transition: all 0.3s ease; border-radius: 1rem; overflow: hidden;">
                            <div style="height: 200px; overflow: hidden; position: relative;">
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ asset($product->images[0]) }}" alt="{{ $product->name }}"
                                         style="width: 100%; height: 200px; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); display: flex; align-items: center; justify-content: center;">
                                        @if($product->category->slug == 'elektronik')
                                            <i class="fas fa-mobile-alt fa-3x text-muted"></i>
                                        @elseif($product->category->slug == 'bilgisayar')
                                            <i class="fas fa-laptop fa-3x text-muted"></i>
                                        @elseif($product->category->slug == 'aksesuar')
                                            <i class="fas fa-headphones fa-3x text-muted"></i>
                                        @else
                                            <i class="fas fa-box fa-3x text-muted"></i>
                                        @endif
                                    </div>
                                @endif

                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <div style="position: absolute; top: 10px; right: 10px; background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                                        %{{ round((($product->price - $product->sale_price) / $product->price) * 100) }} İndirim
                                    </div>
                                @endif
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                                <p class="card-text text-muted mb-2 flex-grow-1" style="font-size: 0.9rem;">
                                    {{ Str::limit($product->description, 80) }}
                                </p>

                                <div class="mb-2">
                                    <span class="badge bg-light text-dark">{{ $product->category->name }}</span>
                                    @if($product->stock_quantity < 10 && $product->stock_quantity > 0)
                                        <span class="badge bg-warning">Son {{ $product->stock_quantity }} adet</span>
                                    @endif
                                </div>

                                <div class="price-section mb-3">
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="font-size: 1.25rem; font-weight: 700; color: #10b981;">
                                                {{ number_format($product->sale_price, 2) }} TL
                                            </span>
                                            <span style="font-size: 1rem; color: #6b7280; text-decoration: line-through;">
                                                {{ number_format($product->price, 2) }} TL
                                            </span>
                                        </div>
                                    @else
                                        <span style="font-size: 1.25rem; font-weight: 700; color: #10b981;">
                                            {{ number_format($product->getCurrentPrice(), 2) }} TL
                                        </span>
                                    @endif
                                </div>

                                @if($product->stock_quantity > 0)
                                    <p class="text-success mb-3" style="font-size: 0.9rem;">
                                        <i class="fas fa-check-circle me-1"></i>Stok: {{ $product->stock_quantity }}
                                    </p>
                                @else
                                    <p class="text-danger mb-3" style="font-size: 0.9rem;">
                                        <i class="fas fa-times-circle me-1"></i>Stokta Yok
                                    </p>
                                @endif

                                <div class="d-flex gap-2 mt-auto">
                                    <a href="{{ route('products.show', $product) }}"
                                       class="btn btn-outline-primary flex-grow-1"
                                       style="border-radius: 0.75rem; font-weight: 500;">
                                        <i class="fas fa-eye me-1"></i>Detay
                                    </a>
                                    @if($product->stock_quantity > 0)
                                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-success w-100"
                                                    style="border-radius: 0.75rem; font-weight: 500;">
                                                <i class="fas fa-cart-plus me-1"></i>Sepete Ekle
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary flex-grow-1" disabled
                                                style="border-radius: 0.75rem; font-weight: 500;">
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
                            <i class="fas fa-search fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Ürün bulunamadı</h4>
                            <p class="text-muted">Arama kriterlerinizi değiştirip tekrar deneyin.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fas fa-refresh me-2"></i>Tüm Ürünleri Görüntüle
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.pagination .page-link {
    border-radius: 0.5rem;
    margin: 0 0.25rem;
    border: none;
    color: #6366f1;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    border: none;
}
</style>
@endsection
