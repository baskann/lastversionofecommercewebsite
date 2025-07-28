<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'short_description',
        'price', 'sale_price', 'stock_quantity', 'sku',
        'images', 'category_id', 'is_active', 'is_featured'
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCurrentPrice()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getMainImage()
    {
        return $this->images ? $this->images[0] : '/images/no-image.jpg';
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
