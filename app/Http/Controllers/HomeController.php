<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $featuredProducts = Product::where('featured', 1)
            ->orWhere('sale_price', '>', 0)
            ->latest()
            ->take(8)
            ->get();
            
        $newArrivals = Product::latest()
            ->take(8)
            ->get();

        $carouselProducts = Product::inRandomOrder()
            ->take(8)
            ->get();

        return view('index', compact('featuredProducts', 'newArrivals', 'carouselProducts'));
    }
}
