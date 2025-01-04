<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // Fetch reviews including soft deleted ones
        $reviews = Review::withTrashed()->paginate(2);  
    
        return view('admin.reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure the user exists
            'product_id' => 'required|exists:products,id', // Ensure the product exists
            'rating' => 'required|integer|between:1,5', // Ensure the rating is between 1 and 5
            'comment' => 'nullable|string|max:1000', // Optional comment field
        ]);

        // Create a new review with validated data
        $review = new Review();
        $review->user_id = $request->input('user_id');
        $review->product_id = $request->input('product_id');
        $review->rating = $request->input('rating');
        $review->comment = $request->input('comment');
        
        // Save the new review to the database
        $review->save();

        // Redirect to the reviews list page
        return redirect()->route('reviews.index')->with('success', 'Review created successfully');
    }

    public function edit(string $id)
    {
        $review = Review::find($id);
        if (!$review) {
            dd('Review not found'); // Debugging line to check if the review is found
        }

        // Fetch product and user data to display in the form (optional)
        $products = Product::all();
        $users = User::all();

        return view('admin.reviews.edit', compact('review', 'products', 'users'));
    }

    // Update method
    public function update(Request $request, $id)
    {
        // Validate the input data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // Ensure the user exists
            'product_id' => 'required|exists:products,id', // Ensure the product exists
            'rating' => 'required|integer|between:1,5', // Ensure the rating is between 1 and 5
            'comment' => 'nullable|string|max:1000', // Optional comment field
        ]);

        // Find the review by ID
        $review = Review::findOrFail($id);

        // Update the review details
        $review->user_id = $request->input('user_id');
        $review->product_id = $request->input('product_id');
        $review->rating = $request->input('rating');
        $review->comment = $request->input('comment');
        $review->save();

        return redirect()->route('reviews.index')->with('success', 'Review updated successfully');
    }

    // Soft delete a review
    public function softDelete($id)
    {
        try {
            $review = Review::findOrFail($id); // Find the review by ID
            
            if ($review->deleted_at) {
                return response()->json(['error' => 'Review already deleted.'], 400);
            }

            $review->delete(); // Perform the soft delete

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete review. ' . $e->getMessage()], 500);
        }
    }


    public function restore($id)
    {
        $review= Review::withTrashed()->findOrFail($id);
        if ($review->trashed()) {
            $review->restore();
            return response()->json(['success' => true, 'review' => $review]);
        }
        return response()->json(['success' => false]);
    }



}
