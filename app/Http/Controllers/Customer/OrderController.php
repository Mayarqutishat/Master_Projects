<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }
    public function index()
    {
        // Retrieve all orders, including soft-deleted ones
        $orders  =  Order::withTrashed()->paginate(8);  
      
        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'total_price' => 'required|numeric',
            'status' => 'required|string',
            'shipping_location' => 'required|string',
        ]);

        // Create the new order
        $order = Order::create([
            'user_id' => $request->user_id,
            'total_price' => $request->total_price,
            'status' => $request->status,
            'shipping_location' => $request->shipping_location,
            'coupon_id' => $request->coupon_id ?? null, // Optional field
        ]);

        return response()->json(['success' => true, 'order' => $order]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'total_price' => 'required|numeric',
            'status' => 'required|string',
            'shipping_location' => 'required|string',
        ]);

        // Find the order and update its details
        $order = Order::findOrFail($id);
        $order->user_id = $request->user_id;
        $order->total_price = $request->total_price;
        $order->status = $request->status;
        $order->shipping_location = $request->shipping_location;
        $order->coupon_id = $request->coupon_id ?? null; // Optional field
        $order->save();

        return response()->json(['success' => true, 'order' => $order]);
    }

    /**
     * Soft delete the specified resource.
     */
   

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Find the specific order
        $order = Order::withTrashed()->findOrFail($id);
        return response()->json(['order' => $order]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function softDelete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete(); // Soft delete

        return response()->json(['success' => true]);
    }

    public function restore($id)
    {
        $order = Order::withTrashed()->findOrFail($id);
        $order->restore(); // Restore soft-deleted record

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $order = Order::withTrashed()->findOrFail($id);
        $order->forceDelete(); // Permanently delete

        return response()->json(['success' => true]);
    }
}
