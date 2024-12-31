<?php

namespace App\Http\Controllers\Pages;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller


{






    public function newestProduct()
    {
      
      


    $products = Product::with('images')->latest()->inRandomOrder()->take(6)->get();

    // Return the view with the products
    return view('index', compact('products'));
    }



    
}
