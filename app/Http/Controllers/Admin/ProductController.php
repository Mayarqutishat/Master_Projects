<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Image;
use App\Models\Category; // Assuming you have a Category model for the category relation
use Illuminate\Http\Request;


class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    public function index()
    {
        // Fetch all products, including soft deleted ones
        $products = Product::withTrashed()->paginate(8);  
    
      $categories = Category::all();

    return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        $imagePath = $request->file('image')->store('product_images', 'public');

        // Create the product
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        
        Image::create([
            'product_id' => $product->id,
            'url' =>  $imagePath,
        ]);
        

        return response()->json(['success' => true, 'product' => $product]);
    }

    public function update(Request $request, $id)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);
    
        try {
            // Find the product by ID
            $product = Product::findOrFail($id);
    
            // Update the product's details
            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->stock = $request->input('stock');
            $product->category_id = $request->input('category_id');
    
            // Save the changes
            $product->save();
    
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category_id' => $product->category_id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the product.',
            ], 500);
        }
    }

    public function softDelete($id)
    {
        try {
            // Find the product and soft delete it
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the product.',
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            // Restore the soft deleted product
            $product = Product::onlyTrashed()->findOrFail($id);
            $product->restore();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while restoring the product.',
            ], 500);
        }
    }
}
