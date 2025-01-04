<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // جلب كل التصنيفات بما في ذلك المحذوفة (Soft Deleted)
        $categories = Category::withTrashed()->paginate(8);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        // التحقق من البيانات المُدخلة
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // تخزين الصورة في المجلد الخاص بالتصنيفات
        $imagePath = $request->file('image')->store('category_images', 'public');

        // إنشاء التصنيف
        $category = Category::create([
            'name' => $request->name,
            'image' => $imagePath,
        ]);

        return response()->json(['success' => true, 'category' => $category]);
    }

    public function update(Request $request, $id)
    {
        // التحقق من البيانات المُدخلة
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        try {
            // العثور على التصنيف
            $category = Category::findOrFail($id);

            // تحديث الاسم
            $category->name = $request->input('name');

            // التحقق من وجود صورة جديدة
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($category->image) {
                    Storage::delete('public/' . $category->image);
                }

                // حفظ الصورة الجديدة
                $imagePath = $request->file('image')->store('category_images', 'public');
                $category->image = $imagePath;
            }

            // حفظ التعديلات
            $category->save();

            // إعادة الرد الناجح مع البيانات الجديدة
            return response()->json([
                'success' => true,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'image' => $category->image ? asset('storage/' . $category->image) : null,
                ],
            ]);
        } catch (\Exception $e) {
            // في حال وجود خطأ
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the category.',
            ], 500);
        }
    }

    public function softDelete($id)
    {
        try {
            // العثور على التصنيف وحذفه حذفًا سلسًا
            $category = Category::findOrFail($id);
            $category->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the category.',
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            // استعادة التصنيف المحذوف
            $category = Category::onlyTrashed()->findOrFail($id);
            $category->restore();

            // إعادة الرد مع التصنيف المستعاد
            return response()->json([
                'success' => true,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'image' => $category->image ? asset('storage/' . $category->image) : null,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while restoring the category.',
            ], 500);
        }
    }
}