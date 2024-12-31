<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Coupon;  // Added missing Coupon model import
use Illuminate\Http\Request;
use Stripe;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class StripePaymentController extends Controller
{
    public function stripe(): View
    {
        $total = session()->get('total');
        return view('stripe', compact('total'));
    }

    public function stripePost(Request $request): RedirectResponse
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
        // Start database transaction
        DB::beginTransaction();
        
        try {
            // Calculate total from cart
            $cartItems = session()->get('cart', []);
            $subtotal = collect($cartItems)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });
            
            $couponDiscount = session()->has('coupon') ? session('coupon')['discount'] : 0;
            $total = ($subtotal + 5 - $couponDiscount) * 100; // Convert to cents for Stripe
            
            // Create Stripe charge
            $charge = Stripe\Charge::create([
                "amount" => $total,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Payment for order at " . config('app.name')
            ]);
            
            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_price' => $total / 100, // Convert back to dollars
                'status' => 'pending', // Initial status
                'shipping_location' => auth()->user()->address ?? '',
                'coupon_id' => session()->has('coupon') ? Coupon::where('code', session('coupon')['code'])->first()->id : null,
            ]);
            
            // Create order items
            foreach ($cartItems as $productId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
            
            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $total / 100,
                'payment_method' => 'stripe',
                'status' => $charge->status === 'succeeded' ? 'complete' : 'failed',
                'transaction_id' => $charge->id,
                'processed_at' => now(),
            ]);
            
            // Update order status based on payment status
            $order->update([
                'status' => $payment->status === 'complete' ? 'complete' : 'failed'
            ]);
            
            // If everything is successful, commit transaction
            DB::commit();
            
            // Clear cart and coupon after successful payment
            session()->forget(['cart', 'coupon']);
            
            return redirect()->route('cart.view')->with('success', 'Payment successful! Your order has been placed.');
            
        } catch (\Exception $e) {
            // If anything goes wrong, rollback the transaction
            DB::rollBack();
            
            // Log the error
            \Log::error('Payment Processing Error: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
}