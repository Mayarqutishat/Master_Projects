<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }

    public function index()
    {
        $totalSales = Payment::where('status', 'complete')->sum('amount');
        $newOrdersCount = Order::where('status', 'pending')->count();
        $completedOrdersCount = Order::where('status', 'complete')->count();
        $newCustomersCount = User::where('user_role', 'customer')->count();
        $ordersData = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                            ->groupBy('month')
                            ->orderBy('month')
                            ->get();
        $stockData = Product::select('name', 'stock')->get();
        $paymentsData = Payment::with('order.user')
                                ->latest()
                                ->take(10)
                                ->get()
                                ->map(function ($payment) {
                                    $payment->processed_at = Carbon::parse($payment->processed_at);
                                    return $payment;
                                });

        return view('customer.dashboard', compact(
            'totalSales',
            'newOrdersCount',
            'completedOrdersCount',
            'newCustomersCount',
            'ordersData',
            'stockData',
            'paymentsData'
        ));
    }
}
