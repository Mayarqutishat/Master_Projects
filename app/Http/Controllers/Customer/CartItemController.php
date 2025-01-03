<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }

    public function index()
    {
        // Fetch cart items including soft deleted ones
        $cartItems = CartItem::withTrashed()->paginate(8);  
        return view('customer.cartitems.index', compact('cartItems'));
    }

    // Soft delete a cart item
    public function softDelete($id)
    {
        try {
            $cartItem = CartItem::findOrFail($id);
            
            if ($cartItem->deleted_at) {
                return response()->json(['error' => 'Cart item already deleted.'], 400);
            }

            $cartItem->delete(); // Perform the soft delete

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete cart item. ' . $e->getMessage()], 500);
        }
    }

    // Restore a soft-deleted cart item
    public function restore($id)
    {
        try {
            $cartItem = CartItem::withTrashed()->findOrFail($id);

            if ($cartItem->trashed()) {
                $cartItem->restore();
                return response()->json(['success' => true]);
            }

            return response()->json(['error' => 'Cart item is not deleted.'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restore cart item. ' . $e->getMessage()], 500);
        }
    }
}
