<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // الحصول على الكلمة المفتاحية
        $query = $request->input('query');
        
        // البحث في المنتجات
        $products = Product::where('name', 'like', "%{$query}%")
                           ->orWhere('description', 'like', "%{$query}%")
                           ->paginate(12); // استخدام التصفية مع التحديد للصفحات
        
        // إرجاع النتائج إلى صفحة البحث
        return view('search.results', compact('products'));
    }
}

