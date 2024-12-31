<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
class OrderItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        // Retrieve all order items, including soft-deleted ones
        $orderItems =  OrderItem::withTrashed()->paginate(8);  
        
        return view('customer.orderitems.index', compact('orderItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'order_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        // Create the new order item with validated data
        $orderItem = new OrderItem();
        $orderItem->order_id = $request->order_id;
        $orderItem->product_id = $request->product_id;
        $orderItem->quantity = $request->quantity;
        $orderItem->price = $request->price;
        $orderItem->save();

        // Return response after saving
        return response()->json(['success' => true, 'order_item' => $orderItem]);
    }

    /**
     * Soft delete the specified resource.
     */
    public function softDelete($id)
    {
        try {
            // Find the order item by ID
            $orderItem = OrderItem::findOrFail($id);

            // Check if already deleted
            if ($orderItem->deleted_at) {
                return response()->json(['error' => 'Order item already deleted.'], 400);
            }

            // Soft delete the order item
            $orderItem->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete order item. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Restore a soft-deleted resource.
     */
    public function restore($id)
    {
        // Find the soft-deleted order item and restore it
        $orderItem = OrderItem::withTrashed()->findOrFail($id);
        $orderItem->restore(); // Restore the soft-deleted order item

        return response()->json(['success' => true, 'order_item' => $orderItem]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the specific order item
        $orderItem = OrderItem::withTrashed()->findOrFail($id);
        return response()->json(['order_item' => $orderItem]);
    }

    /**
     * Permanently remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Permanently delete the order item from storage
        $orderItem = OrderItem::withTrashed()->findOrFail($id);
        $orderItem->forceDelete(); // Permanently delete the order item

        return response()->json(['success' => true]);
    }
}
?>