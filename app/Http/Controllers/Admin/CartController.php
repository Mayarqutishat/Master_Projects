<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // Fetch carts including soft-deleted ones
        $carts =Cart::withTrashed()->paginate(8);  
        return view('admin.carts.index', compact('carts'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure the user exists
            // Add other fields relevant to cart creation if needed
        ]);

        // Create a new cart with validated data
        $cart = new Cart();
        $cart->user_id = $request->input('user_id');
        // Add other cart-related fields here if necessary
        
        // Save the new cart to the database
        $cart->save();

        // Redirect to the carts list page
        return redirect()->route('carts.index')->with('success', 'Cart created successfully');
    }

    public function edit(string $id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            dd('Cart not found'); // Debugging line to check if the cart is found
        }

        // Fetch user data to display in the form (optional)
        $users = User::all();

        return view('admin.carts.edit', compact('cart', 'users'));
    }

    // Update method
    public function update(Request $request, $id)
    {
        // Validate the input data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure the user exists
            // Add other fields to validate as needed
        ]);

        // Find the cart by ID
        $cart = Cart::findOrFail($id);

        // Update the cart details
        $cart->user_id = $request->input('user_id');
        // Add other cart fields to update as needed
        $cart->save();

        return redirect()->route('carts.index')->with('success', 'Cart updated successfully');
    }

    // Soft delete a cart
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

    // Restore a soft deleted cart
    public function restore($id)
    {
        $cart = Cart::withTrashed()->findOrFail($id); // Include soft-deleted carts

        if ($cart->trashed()) {
            $cart->restore(); // Restore the cart if it's soft-deleted
            return response()->json(['success' => true, 'cart' => $cart]);
        }

        return response()->json(['success' => false]);
    }
}
