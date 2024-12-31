<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // Fetch coupons including soft deleted ones
        $coupons = Coupon::withTrashed()->paginate(8);       
         return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'code' => 'required|unique:coupons,code', // Ensure the coupon code is unique
            'discount' => 'required|numeric|min:0', // Ensure the discount is a positive number
            'expiry_date' => 'required|date|after:today', // Ensure the expiry date is a valid future date
        ]);
    
        // Create a new coupon with validated data
        $coupon = new Coupon();
        $coupon->code = $request->input('code');
        $coupon->discount = $request->input('discount');
        $coupon->expiry_date = $request->input('expiry_date');
        
        // Save the new coupon to the database
        $coupon->save();
    
        // Return response for AJAX or redirect based on the request
        return response()->json(['success' => true, 'coupon' => $coupon]);
    }
    
    public function update(Request $request, $id)
    {
        // Validate the input data
        $validated = $request->validate([
            'code' => 'required|unique:coupons,code,' . $id, // Ensure the coupon code is unique (ignore current coupon)
            'discount' => 'required|numeric|min:0', // Ensure the discount is a positive number
            'expiry_date' => 'required|date|after:today', // Ensure the expiry date is a valid future date
        ]);
    
        // Find the coupon by ID
        $coupon = Coupon::findOrFail($id);
    
        // Update the coupon details
        $coupon->code = $request->input('code');
        $coupon->discount = $request->input('discount');
        $coupon->expiry_date = $request->input('expiry_date');
        $coupon->save();
    
        // Return response for AJAX or redirect based on the request
        return response()->json(['success' => true, 'coupon' => $coupon]);
    }

public function softDelete($id)
{
    $coupon = Coupon::find($id);
    if ($coupon) {
        $coupon->delete();
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 404);
}

public function restore($id)
{
    $coupon = Coupon::withTrashed()->find($id);
    if ($coupon) {
        $coupon->restore();
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 404);
}

}

