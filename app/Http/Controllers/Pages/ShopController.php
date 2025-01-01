<?php 
namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller {
    public function getProductsByCategory(Request $request) {
        $query = Product::query()->with(['category', 'images']);
        
        if ($request->filled('filter')) {
            $filter = $request->get('filter');
            if ($filter !== '*') {
                $query->whereHas('category', function($q) use ($filter) {
                    $q->where('name', $filter);
                });
            }
        }
        
        $products = $query->paginate(6);
        return view('shop', compact('products'));
    }

    public function newestProduct() {
        $products = Product::with('images')->latest()->inRandomOrder()->paginate(6);
        return view('shop', compact('products'));
    }

    public function viewProduct($id) {
        $product = Product::with('category', 'images', 'reviews')->findOrFail($id);
        $averageRating = $product->averageRating(); // حساب المعدل
        return view('single-product', compact('product', 'averageRating'));
    }

    public function search(Request $request)
{
    $query = $request->input('query');

    // الحصول على المنتجات بناءً على الاستعلام
    $products = Product::where('name', 'like', "%{$query}%")
                       ->orWhere('description', 'like', "%{$query}%")
                       ->paginate(12); // يمكنك تغيير عدد المنتجات المعروضة في الصفحة حسب الحاجة

    return view('shop.index', compact('products'));
}





}