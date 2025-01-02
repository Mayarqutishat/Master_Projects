<?php

    namespace App\Http\Controllers\Customer;

    use App\Http\Controllers\Controller;
    use App\Models\OrderItem;
    use Illuminate\Http\Request;
    
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
            $orderItems = OrderItem::withTrashed()->paginate(8); // استرجاع جميع العناصر بما فيها المحذوفة
            return view('customer.orderitems.index', compact('orderItems'));
        }
    
        /**
         * Soft delete the specified resource.
         */
        public function softDelete($id)
        {
            try {
                $orderItem = OrderItem::findOrFail($id);
    
                if ($orderItem->deleted_at) {
                    return response()->json(['error' => 'Order item already deleted.'], 400);
                }
    
                $orderItem->delete(); // حذف ناعم
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
            $orderItem = OrderItem::withTrashed()->findOrFail($id);
            $orderItem->restore(); // استعادة العنصر المحذوف
            return response()->json(['success' => true, 'order_item' => $orderItem]);
        }
    
        /**
         * Permanently delete the specified resource.
         */
        public function destroy($id)
        {
            $orderItem = OrderItem::withTrashed()->findOrFail($id);
            $orderItem->forceDelete(); // حذف دائم
            return response()->json(['success' => true]);
        }
    }
    
