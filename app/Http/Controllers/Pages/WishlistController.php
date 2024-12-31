<?php
namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request)
    {
        $productId = $request->input('product_id');

        // تأكد أن الـ Wishlist موجودة في الجلسة
        $wishlist = Session::get('wishlist', []);

        // إضافة المنتج للـ Wishlist إذا لم يكن موجودًا
        if (!in_array($productId, $wishlist)) {
            $wishlist[] = $productId;
            Session::put('wishlist', $wishlist);
            return response()->json(['success' => true, 'message' => 'Product added to wishlist!']);
        }

        return response()->json(['success' => false, 'message' => 'Product is already in the wishlist!']);
    }
    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $wishlist = Session::get('wishlist', []);

        if (($key = array_search($productId, $wishlist)) !== false) {
            unset($wishlist[$key]);
            Session::put('wishlist', $wishlist);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function newestProduct()
    {
      
        // $products = Product::latest()->inRandomOrder()->take(6)->get();
      
        // // Return the view with the products


        // return view('index', compact('products'));



    $products = Product::with('images')->latest()->inRandomOrder()->get();

    // Return the view with the products
    return view('wishlist', compact('products'));
    }









    

}

