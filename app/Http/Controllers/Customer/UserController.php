<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }

    public function index()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Return the view with the current user data (if only one user view is needed)
        return view('customer.users.index', compact('user'));
    }

    // Fetch user details by ID
    public function viewProfile($userId)
    {
        // Find the user by ID
        $user = User::find($userId);

        // If user not found, return a 404 error response
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Return user details as a JSON response
        return response()->json($user);
    }

    // Soft delete a user
    public function softDelete($id)
    {
        try {
            $user = User::findOrFail($id);  // Find the user by ID

            if ($user->deleted_at) {
                return response()->json(['error' => 'User already deleted.'], 400);
            }

            $user->delete();  // Perform the soft delete

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete user. ' . $e->getMessage()], 500);
        }
    }

    // Update user information
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Update the user details
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->save();

        return response()->json(['success' => true]);
    }
}