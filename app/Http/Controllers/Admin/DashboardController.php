<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use Carbon\Carbon;
use App\Models\User;

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
        $totalSales = Payment::where('status', 'complete')
                            ->sum('amount');
        
        // عدد الطلبات الجديدة
        $newOrdersCount = Order::where('status', 'pending')->count();

        // عدد الطلبات المكتملة
        $completedOrdersCount = Order::where('status', 'complete')->count();

        // عدد العملاء الجدد
        $newCustomersCount = User::where('user_role', 'customer')->count();

        // بيانات الطلبات (حسب الشهر)
        $ordersData = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                            ->groupBy('month')
                            ->orderBy('month')
                            ->get();

        // بيانات المخزون (المنتجات)
        $stockData = Product::select('name', 'stock')->get();

        // بيانات المدفوعات (عرض المدفوعات الأخيرة)
        $paymentsData = Payment::with('order.user') // نحن نجلب تفاصيل الطلب و المستخدم المرتبط بالمدفوعات
                                ->latest()
                                ->take(10)  // تحديد عدد السجلات التي سيتم عرضها
                                ->get()
                                ->map(function ($payment) {
                                    // تحويل processed_at إلى كائن Carbon
                                    $payment->processed_at = Carbon::parse($payment->processed_at);
                                    return $payment;
                                });

        // تمرير البيانات إلى العرض
        return view('admin.dashboard', compact(
            'totalSales',
            'newOrdersCount',
            'completedOrdersCount',
            'newCustomersCount',
            'ordersData',
            'stockData',
            'paymentsData'  // تمرير بيانات المدفوعات
        ));
    }
}
