<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $imageConfig = config('ecommerce');

        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:5000',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0.01|max:999999.99',
            'sale_price' => 'nullable|numeric|min:0.01|max:999999.99|lt:price',
            'stock_quantity' => 'required|integer|min:0|max:999999',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:' . implode(',', $imageConfig['image_allowed_mimes'])
                        . '|max:' . $imageConfig['image_max_size']
                        . '|dimensions:min_width=' . $imageConfig['image_min_width']
                        . ',min_height=' . $imageConfig['image_min_height'],
        ]);

        $data = $request->all();

        // Benzersiz slug oluştur
        $data['slug'] = $this->generateUniqueSlug($request->name);
        $data['sku'] = 'SKU-' . strtoupper(Str::random(8));

        // Resim yükleme
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('products', $filename, 'public');
                $images[] = 'storage/' . $path;
            }
            $data['images'] = $images;
        }

        $product = Product::create($data);

        // Loglama
        Log::info('Product created', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Ürün başarıyla eklendi!');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $imageConfig = config('ecommerce');

        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:5000',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0.01|max:999999.99',
            'sale_price' => 'nullable|numeric|min:0.01|max:999999.99|lt:price',
            'stock_quantity' => 'required|integer|min:0|max:999999',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:' . implode(',', $imageConfig['image_allowed_mimes'])
                        . '|max:' . $imageConfig['image_max_size']
                        . '|dimensions:min_width=' . $imageConfig['image_min_width']
                        . ',min_height=' . $imageConfig['image_min_height'],
        ]);

        $data = $request->all();

        // Slug değiştiyse benzersiz slug oluştur
        if ($request->name !== $product->name) {
            $data['slug'] = $this->generateUniqueSlug($request->name, $product->id);
        }

        // Yeni resim yükleme
        if ($request->hasFile('images')) {
            $images = $product->images ?? [];

            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('products', $filename, 'public');
                $images[] = 'storage/' . $path;
            }
            $data['images'] = $images;
        }

        $product->update($data);

        // Loglama
        Log::info('Product updated', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Ürün başarıyla güncellendi!');
    }

    public function destroy(Product $product)
    {
        // Ürün resimlerini sil
        if ($product->images) {
            foreach ($product->images as $image) {
                $imagePath = str_replace('storage/', '', $image);
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        // Loglama
        Log::info('Product deleted', [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'user_id' => auth()->id(),
        ]);

        $product->delete();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Ürün ve ilgili dosyalar başarıyla silindi!');
    }

    /**
     * Benzersiz slug oluştur
     */
    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (true) {
            $query = Product::where('slug', $slug);

            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }

            if (!$query->exists()) {
                break;
            }

            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
