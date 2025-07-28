<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
                                 ->where('is_active', true)
                                 ->take(8)
                                 ->get();

        $categories = Category::where('is_active', true)->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
