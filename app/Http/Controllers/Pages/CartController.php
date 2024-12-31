<?php

namespace App\Http\Controllers\Pages;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:customer']);
    }

    public function add(Request $request, $productId)
    {
        $product = Product::find($productId);

        if ($product) {
            $cart = session()->get('cart', []);

            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $request->quantity;
            } else {
                $cart[$productId] = [
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $request->quantity,
                    'image' => $product->images->isNotEmpty() ? $product->images->first()->url : 'assetsPages/assets/img/bags/bag2.jpg',
                ];
            }

            session()->put('cart', $cart);

            return redirect()->route('cart.view')->with('success', 'Product added to cart successfully!');
        }

        return redirect()->route('cart.view')->with('error', 'Product not found!');
    }

    public function viewCart()
    {
        $cartItems = session()->get('cart', []);
        $subtotal = 0;

        foreach ($cartItems as $productId => $item) {
            $product = Product::find($productId);

            if ($product && $product->images->isNotEmpty()) {
                $cartItems[$productId]['image'] = $product->images->first()->url;
            } else {
                $cartItems[$productId]['image'] = 'assetsPages/assets/img/bags/bag2.jpg';
            }

            $subtotal += $item['price'] * $item['quantity'];
        }

        $couponDiscount = 0;
        if (session()->has('coupon')) {
            $coupon = session('coupon');
            $couponDiscount = $coupon['discount'];
        }

        $total = $subtotal - $couponDiscount;

        return view('cart', compact('cartItems', 'subtotal', 'couponDiscount', 'total'));
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $couponDiscount = 0;
            if (session()->has('coupon')) {
                $coupon = session('coupon');
                $couponDiscount = $coupon['discount'];
            }

            $total = $subtotal - $couponDiscount;

            if ($total <= 0 && session()->has('coupon')) {
                session()->forget('coupon');
            }

            return redirect()->route('cart.view')->with('success', 'Product removed from cart successfully!');
        }

        return redirect()->route('cart.view')->with('error', 'Product not found in cart!');
    }

    public function applyCoupon(Request $request)
    {
        $couponCode = $request->input('coupon_code');
        $coupon = Coupon::where('code', $couponCode)
                        ->where('expiry_date', '>', now())
                        ->first();

        if (!$coupon) {
            return redirect()->route('cart.view')->with('error', 'Invalid or expired coupon code!');
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'discount' => $coupon->discount,
        ]);

        return redirect()->route('cart.view')->with('success', 'Coupon applied successfully!');
    }

    public function processCheckout(Request $request)
    {
        $cartItems = session()->get('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        session()->forget('cart');
        session()->forget('coupon');

        return redirect()->route('thanks')->with('success', 'Thank you for your order!');
    }

    public function checkout()
{
    $cartItems = session()->get('cart', []);
    $subtotal = 0;

    // حساب مجموع الأسعار
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }

    $couponDiscount = 0;
    if (session()->has('coupon')) {
        $coupon = session('coupon');
        $couponDiscount = $coupon['discount'];
    }

    $total = $subtotal - $couponDiscount;

    $user = auth()->user(); // الحصول على المستخدم الحالي

    return view('checkout', compact('cartItems', 'subtotal', 'couponDiscount', 'total', 'user'));
}

}