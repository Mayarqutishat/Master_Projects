<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    // List payments including soft-deleted ones
    public function index()
    {
        // Fetch payments, including soft-deleted ones
        $payments = Payment::withTrashed()->paginate(8);  
        return view('admin.payments.index', compact('payments'));
    }

    // Store new payment record
    public function store(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id', // Ensure the order exists
            'amount' => 'required|numeric|min:0', // Amount should be a positive number
            'payment_method' => 'required|string|max:50', // Payment method should be a string
            'status' => 'required|in:complete,failed', // Status should be either 'complete' or 'failed'
            'transaction_id' => 'required|unique:payments,transaction_id', // Ensure the transaction ID is unique
            'processed_at' => 'required|date', // Ensure the processed_at is a valid date
        ]);

        // Create a new payment
        $payment = new Payment();
        $payment->order_id = $request->input('order_id');
        $payment->amount = $request->input('amount');
        $payment->payment_method = $request->input('payment_method');
        $payment->status = $request->input('status');
        $payment->transaction_id = $request->input('transaction_id');
        $payment->processed_at = $request->input('processed_at');

        // Save the payment to the database
        $payment->save();

        // Redirect to the payments list
        return redirect()->route('payments.index')->with('success', 'Payment added successfully');
    }

    // Edit an existing payment
    public function edit(string $id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return redirect()->route('payments.index')->with('error', 'Payment not found');
        }

        return view('admin.payments.edit', compact('payment'));
    }

    // Update payment details
    public function update(Request $request, $id)
    {
        // Validate input data
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id', // Ensure the order exists
            'amount' => 'required|numeric|min:0', // Amount should be a positive number
            'payment_method' => 'required|string|max:50', // Payment method should be a string
            'status' => 'required|in:complete,failed', // Status should be either 'complete' or 'failed'
            'transaction_id' => 'required|unique:payments,transaction_id,' . $id, // Ensure the transaction ID is unique (ignore current payment)
            'processed_at' => 'required|date', // Ensure the processed_at is a valid date
        ]);

        // Find and update the payment record
        $payment = Payment::findOrFail($id);
        $payment->order_id = $request->input('order_id');
        $payment->amount = $request->input('amount');
        $payment->payment_method = $request->input('payment_method');
        $payment->status = $request->input('status');
        $payment->transaction_id = $request->input('transaction_id');
        $payment->processed_at = $request->input('processed_at');
        $payment->save();

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully');
    }

    // Soft delete a payment record
    public function softDelete($id)
    {
        try {
            $payment = Payment::findOrFail($id); // Find the payment by ID

            if ($payment->deleted_at) {
                return response()->json(['error' => 'Payment already deleted.'], 400);
            }

            $payment->delete(); // Perform soft delete

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete payment. ' . $e->getMessage()], 500);
        }
    }


    public function restore($couponId)
{
    // جلب الكوبون حتى لو كان محذوفًا (soft deleted)
    $payment = Payment::withTrashed()->findOrFail($couponId);

    // استعادة الكوبون
    if ($payment->restore()) {
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 500);
}

}








