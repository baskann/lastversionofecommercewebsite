<!DOCTYPE html>
<html>
<head>
    <title>E-Ticaret Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>E-Ticaret Sistemi</h1>

        <h3>Kategoriler</h3>
        @foreach($categories as $category)
            <div class="badge bg-primary me-2">{{ $category->name }}</div>
        @endforeach

        <h3 class="mt-4">Ürünler</h3>
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5>{{ $product->name }}</h5>
                            <p>Kategori: {{ $product->category->name }}</p>
                            <p>Fiyat: {{ number_format($product->price, 2) }} TL</p>
                            <p>Stok: {{ $product->stock_quantity }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
