<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
            'description' => 'Elektronik ürünler kategorisi',
            'is_active' => true
        ]);

        \App\Models\Product::create([
            'name' => 'Test Telefon',
            'slug' => 'test-telefon',
            'description' => 'Test amaçlı telefon ürünü',
            'price' => 5000.00,
            'stock_quantity' => 10,
            'sku' => 'TEL-001',
            'category_id' => 1,
            'is_active' => true
        ]);
    }
}
