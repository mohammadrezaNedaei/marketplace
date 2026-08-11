<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('status', 'active')
                                    ->with(['seller:id,username','category:id,name'])
                                    ->orderBy('views', 'desc')
                                    ->take(4)
                                    ->get();

        $categories = Category::withCount('products')
                                ->having('products_count', '>', 0)
                                ->orderBy('products_count', 'desc')
                                ->take(6)
                                ->get();

        $stats = [
            'sellers' => User::whereRole('seller')->count(),
            'buyers' => User::whereRole('buyer')->count(),
            'products' => Product::whereStatus('active')->count(),
        ];

        return view('home', compact('featuredProducts', 'categories', 'stats'));
    }
}
