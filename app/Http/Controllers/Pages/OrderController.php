<?php
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function showPaymentForm()
    {
        $user = Auth::user();  // Get the authenticated user
    
        // Check if the user is authenticated
        if (!$user) {
            return redirect()->route('login');  // Redirect to login if not authenticated
        }
    
        // Retrieve the order for the user
        $order = Order::where('user_id', $user->id)->where('status', 'processing')->first();
    
        // Check if the order exists
        if (!$order) {
            return redirect()->route('order.error')->with('error', 'No order found or order not processing');
        }
    
        return view('payment', compact('order'));
    }
    

    public function storePayment(Request $request)
    {
        // Validate the payment method
        $request->validate([
            'payment_method' => 'required|in:paypal,cash_delivery',
        ]);

        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('status', 'processing')->first();

        // Create a payment record
        $payment = new \App\Models\Payment();
        $payment->order_id = $order->id;
        $payment->amount = $order->total_price;
        $payment->payment_method = $request->payment_method;
        $payment->status = 'pending'; // Set payment status as pending
        $payment->save();

        // Update order status
        $order->status = 'pending';
        $order->save();

        // Redirect to confirmation page or process further
        return redirect()->route('order.confirmation', ['order' => $order->id]);
    }
}
