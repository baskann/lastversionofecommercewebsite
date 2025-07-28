@extends('layouts.app')

@section('title', 'Ürün Düzenle')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Ürün Düzenle: {{ $product->name }}</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Ürün Adı</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <select class="form-select @error('category_id') is-invalid @enderror"
                                name="category_id" required>
                            <option value="">Kategori Seçin</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Kısa Açıklama</label>
                    <textarea class="form-control @error('short_description') is-invalid @enderror"
                              name="short_description" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mevcut Resimler -->
                @if($product->images && count($product->images) > 0)
                    <div class="mb-3">
                        <label class="form-label">Mevcut Resimler</label>
                        <div class="row">
                            @foreach($product->images as $image)
                                <div class="col-md-3 mb-2">
                                    <img src="{{ asset($image) }}" class="img-fluid rounded" alt="Ürün Resmi">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Yeni Resimler Ekle</label>
                    <input type="file" class="form-control @error('images.*') is-invalid @enderror"
                           name="images[]" accept="image/*" multiple>
                    <div class="form-text">Birden fazla resim seçebilirsiniz (JPEG, PNG, JPG, GIF - Max: 2MB)</div>
                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Fiyat (TL)</label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                               name="price" value="{{ old('price', $product->price) }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">İndirimli Fiyat (TL)</label>
                        <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror"
                               name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                        @error('sale_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stok Adedi</label>
                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                               name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                        @error('stock_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active"
                                   id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktif
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured"
                                   id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Öne Çıkan Ürün
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-2">İptal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
