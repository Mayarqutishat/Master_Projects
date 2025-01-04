<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    // عرض قائمة الصور مع الصور المحذوفة
    public function index()
    {
        $images = Image::withTrashed()->paginate(8);
        return view('admin.images.index', compact('images'));
    }

    // حفظ صورة جديدة
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'url' => 'required|url|max:255',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $image = new Image();
        $image->product_id = $request->input('product_id');
        $image->url = $request->input('url');
        $image->alt_text = $request->input('alt_text');
        $image->save();

        return redirect()->route('images.index')->with('success', 'Image created successfully');
    }

    // عرض نموذج تعديل الصورة
    public function edit(string $id)
    {
        $image = Image::find($id);
        if (!$image) {
            return redirect()->route('images.index')->with('error', 'Image not found');
        }

        $products = Product::all();
        return view('admin.images.edit', compact('image', 'products'));
    }

    // تحديث الصورة
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'url' => 'required|url|max:255',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $image = Image::findOrFail($id);
        $image->product_id = $request->input('product_id');
        $image->url = $request->input('url');
        $image->alt_text = $request->input('alt_text');
        $image->save();

        return redirect()->route('images.index')->with('success', 'Image updated successfully');
    }

   // Soft Delete for Image
public function softDelete($id)
{
    $image = Image::findOrFail($id);
    $image->delete();

    return response()->json([
        'success' => true,
        'message' => 'Image soft deleted successfully.'
    ]);
}

// Restore Image
public function restore($id)
{
    $image = Image::withTrashed()->findOrFail($id);
    $image->restore();

    return response()->json([
        'success' => true,
        'message' => 'Image restored successfully.'
    ]);
}
}