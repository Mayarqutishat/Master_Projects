<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id', // Ensure the cart exists
            'product_id' => 'required|exists:products,id', // Ensure the product exists
            'quantity' => 'required|integer|min:1', // Ensure the quantity is a positive integer
        ]);

        // Create a new cart item with validated data
        $cartItem = new CartItem();
        $cartItem->cart_id = $request->input('cart_id');
        $cartItem->product_id = $request->input('product_id');
        $cartItem->quantity = $request->input('quantity');

        // Save the new cart item to the database
        $cartItem->save();

        // Redirect to the cart items list page
        return redirect()->route('cart_items.index')->with('success', 'Cart item added successfully');
    }

    public function edit(string $id)
    {
        $cartItem = CartItem::find($id);
        if (!$cartItem) {
            dd('Cart item not found'); // Debugging line to check if the cart item is found
        }

        // Fetch product and cart data to display in the form (optional)
        $products = Product::all();
        $carts = Cart::all();

        return view('customer.cart_items.edit', compact('cartItem', 'products', 'carts'));
    }

    // Update method
    public function update(Request $request, $id)
    {
        // Validate the input data
        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id', // Ensure the cart exists
            'product_id' => 'required|exists:products,id', // Ensure the product exists
            'quantity' => 'required|integer|min:1', // Ensure the quantity is a positive integer
        ]);

        // Find the cart item by ID
        $cartItem = CartItem::findOrFail($id);

        // Update the cart item details
        $cartItem->cart_id = $request->input('cart_id');
        $cartItem->product_id = $request->input('product_id');
        $cartItem->quantity = $request->input('quantity');
        $cartItem->save();

        return redirect()->route('cart_items.index')->with('success', 'Cart item updated successfully');
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
}
