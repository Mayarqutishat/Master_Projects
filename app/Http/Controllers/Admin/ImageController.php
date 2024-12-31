<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // Fetch images including soft deleted ones
        $images  = Image::withTrashed()->paginate(8);  
    
        return view('admin.images.index', compact('images'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id', // Ensure the product exists
            'url' => 'required|url|max:255', // Ensure the URL is valid
            'alt_text' => 'nullable|string|max:255', // Optional alt_text field
        ]);

        // Create a new image record
        $image = new Image();
        $image->product_id = $request->input('product_id');
        $image->url = $request->input('url');
        $image->alt_text = $request->input('alt_text');
        
        // Save the new image to the database
        $image->save();

        // Redirect to the images list page
        return redirect()->route('images.index')->with('success', 'Image created successfully');
    }

    public function edit(string $id)
    {
        $image = Image::find($id);
        if (!$image) {
            dd('Image not found'); // Debugging line to check if the image is found
        }

        // Fetch product data to display in the form (optional)
        $products = Product::all();

        return view('admin.images.edit', compact('image', 'products'));
    }

    // Update method
    public function update(Request $request, $id)
    {
        // Validate the input data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id', // Ensure the product exists
            'url' => 'required|url|max:255', // Ensure the URL is valid
            'alt_text' => 'nullable|string|max:255', // Optional alt_text field
        ]);

        // Find the image by ID
        $image = Image::findOrFail($id);

        // Update the image details
        $image->product_id = $request->input('product_id');
        $image->url = $request->input('url');
        $image->alt_text = $request->input('alt_text');
        $image->save();

        return redirect()->route('images.index')->with('success', 'Image updated successfully');
    }

    // Soft delete an image
    public function softDelete($id)
    {
        try {
            $image = Image::findOrFail($id); // Find the image by ID
            
            if ($image->deleted_at) {
                return response()->json(['error' => 'Image already deleted.'], 400);
            }

            $image->delete(); // Perform the soft delete

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete image. ' . $e->getMessage()], 500);
        }
    }









    
}
