<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\User;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }

    public function index()
    {
        // Fetch carts including soft deleted ones
        $carts = Cart::withTrashed()->paginate(8);  
        return view('customer.carts.index', compact('carts'));
    }

    public function softDelete($id)
    {
        try {
            $cart = Cart::findOrFail($id); // Find the cart by ID

            if ($cart->deleted_at) {
                return response()->json(['error' => 'Cart already deleted.'], 400);
            }

            $cart->delete(); // Perform the soft delete
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete cart. ' . $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        try {
            $cart = Cart::withTrashed()->findOrFail($id); // Include soft-deleted carts

            if ($cart->trashed()) {
                $cart->restore(); // Restore the cart if it's soft-deleted
                return response()->json(['success' => true]);
            }

            return response()->json(['error' => 'Cart is not deleted.'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restore cart. ' . $e->getMessage()], 500);
        }
    }
}
