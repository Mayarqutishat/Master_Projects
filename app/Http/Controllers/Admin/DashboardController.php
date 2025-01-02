<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;



class DashboardController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // إجمالي المبيعات
        $totalSales = Payment::where('status', 'complete')->sum('amount');
        
        // عدد الطلبات الجديدة
        $newOrdersCount = Order::where('status', 'pending')->count();
    
        // عدد الطلبات المكتملة
        $completedOrdersCount = Order::where('status', 'complete')->count();
    
        // عدد العملاء الجدد
        $newCustomersCount = User::where('user_role', 'customer')->count();
    
        // بيانات المبيعات لآخر 6 أشهر
        $salesData = Payment::selectRaw('YEAR(processed_at) as year, MONTH(processed_at) as month, SUM(amount) as total')
                            ->where('status', 'complete')
                            ->where('processed_at', '>=', now()->subMonths(6))
                            ->groupBy('year', 'month')
                            ->orderBy('year', 'asc')
                            ->orderBy('month', 'asc')
                            ->get();
    
        // بيانات الطلبات لآخر 6 أشهر
        $ordersData = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                            ->where('created_at', '>=', now()->subMonths(6))
                            ->groupBy('year', 'month')
                            ->orderBy('year', 'asc')
                            ->orderBy('month', 'asc')
                            ->get();
    
        // بيانات المخزون (المنتجات)
        $stockData = Product::select('name', 'stock')->get();
    
        // بيانات المدفوعات (عرض المدفوعات الأخيرة)
        $paymentsData = Payment::with('order.user')
                                ->latest()
                                ->take(10)
                                ->get()
                                ->map(function ($payment) {
                                    $payment->processed_at = Carbon::parse($payment->processed_at);
                                    return $payment;
                                });
    
        // تمرير البيانات إلى العرض
        return view('admin.dashboard', compact(
            'totalSales',
            'newOrdersCount',
            'completedOrdersCount',
            'newCustomersCount',
            'salesData',
            'ordersData',
            'stockData',
            'paymentsData'
        ));
    }  
 }