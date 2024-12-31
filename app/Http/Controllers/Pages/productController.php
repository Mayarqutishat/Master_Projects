<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class productController extends Controller
{
   
    public function show($id)
    {
        // Fetch product along with related data (category, images, reviews)
        $product = Product::with(['category', 'images', 'reviews.user'])->findOrFail($id);
    
        // Pass the data to the view
        return view('product.single', compact('product'));
    }
    

    use App\Models\Product; // أو أي موديل ترغب بالبحث فيه
use Illuminate\Http\Request;

public function search(Request $request)
{
    $query = $request->input('query');
    
    // البحث في نموذج المنتجات، ويمكنك تعديل هذا بناءً على ما تريد البحث عنه
    $products = Product::where('name', 'like', "%{$query}%")
                       ->orWhere('description', 'like', "%{$query}%")
                       ->get();

    // إرجاع النتائج إلى عرض البحث
    return view('search.results', compact('products'));
}

   
}
