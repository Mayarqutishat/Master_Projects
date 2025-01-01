<?php

namespace App\Http\Controllers\Pages;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
class HomeController extends Controller


{






    public function newestProduct()
    {
      
      


    $products = Product::with('images')->latest()->inRandomOrder()->take(6)->get();

       // Fetch testimonials randomly from the reviews table where user_role is 'customer'
      $testimonials = Review::with('user')
        ->whereHas('user', function ($query) {
            $query->where('user_role', 'customer');
        })
        ->inRandomOrder()
        ->take(3) // Take 4 random testimonials, adjust as needed
        ->get();


    // Return the view with the products
    return view('index', compact('products', 'testimonials'));
    }



    
}
