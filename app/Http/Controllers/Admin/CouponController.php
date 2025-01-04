<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $coupons = Coupon::withTrashed()->paginate(8);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:coupons,code',
            'discount' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after:today',
        ]);

        $coupon = Coupon::create($validated);
        return response()->json(['success' => true, 'coupon' => $coupon]);
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return response()->json($coupon);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|unique:coupons,code,' . $id,
            'discount' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after:today',
        ]);

        $coupon = Coupon::findOrFail($id);
        $coupon->update($validated);
        return response()->json(['success' => true, 'coupon' => $coupon]);
    }

    public function softDelete($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return response()->json(['success' => true]);
    }

    public function restore($id)
    {
        $coupon = Coupon::withTrashed()->findOrFail($id);
        $coupon->restore();
        return response()->json(['success' => true]);
    }
}