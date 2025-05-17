<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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

        // Dynamically get all images from public/images/home/demo3
        $demo3Images = collect(File::files(public_path('images/home/demo3')))
            ->map(function ($file) {
                return 'images/home/demo3/' . $file->getFilename();
            });

        $allProducts = Product::all();

        return view('index', compact('featuredProducts', 'newArrivals', 'carouselProducts', 'demo3Images', 'allProducts'));
    }
}
