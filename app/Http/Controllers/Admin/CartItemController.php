<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // Fetch cart items including soft deleted ones
        $cartItems = CartItem::withTrashed()->paginate(8);  
        return view('admin.cart_items.index', compact('cartItems'));
    }

    // Soft delete a cart item
    public function softDelete($id)
    {
        try {
            $cartItem = CartItem::findOrFail($id); // Find the cart item by ID

            if ($cartItem->deleted_at) {
                return response()->json(['error' => 'Cart item already deleted.'], 400);
            }

            $cartItem->delete(); // Perform the soft delete

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete cart item. ' . $e->getMessage()], 500);
        }
    }

    // Restore a soft deleted cart item
    public function restore($id)
    {
        $cartItem = CartItem::withTrashed()->findOrFail($id); // Include soft-deleted carts

        if ($cartItem->trashed()) {
            $cartItem->restore(); // Restore the cart if it's soft-deleted
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Cart item was not deleted.'], 400);
    }
}


