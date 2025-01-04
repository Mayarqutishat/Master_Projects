<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // Fetch users including soft deleted ones
        $users = User::withTrashed()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'user_role' => 'required|in:customer,admin',
            'gender' => 'required|in:male,female,other', // Add gender validation
            'age' => 'required|integer|min:18', // Ensure age is an integer and at least 18
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'image' => 'nullable|image|max:2048', // Optional image field
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('user_images', 'public');
        } else {
            $imagePath = null;
        }

        // Create a new user with validated data
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->user_role = $request->input('user_role');
        $user->gender = $request->input('gender');
        $user->age = $request->input('age');
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->image = $imagePath;
        $user->password = bcrypt($request->input('password')); // or use a default password logic
    
        // Save the new user to the database
        $user->save();
    
        // Redirect to the users list page or wherever you want
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            dd('User not found'); // Debugging line to check if the user is found
        }
        return view('admin.users.edit', compact('user'));
    }

    // Update method
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'user_role' => 'required|in:customer,admin',
            'gender' => 'required|in:male,female,other',
            'age' => 'required|integer|min:18',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'image' => 'nullable|image|max:2048',
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);
        
        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete the old image if a new one is uploaded
            if ($user->image && file_exists(storage_path('app/public/' . $user->image))) {
                unlink(storage_path('app/public/' . $user->image));
            }
            $imagePath = $request->file('image')->store('user_images', 'public');
            $user->image = $imagePath;
        }

        // Update the user details
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->user_role = $request->input('user_role');
        $user->gender = $request->input('gender');
        $user->age = $request->input('age');
        $user->address = $request->input('address');
        $user->phone = $request->input('phone');
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully');
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
     // Restore a soft deleted user
     public function restore($id)
     {
         try {
             $user = User::withTrashed()->findOrFail($id); // Find the user even if soft deleted
 
             if (!$user->deleted_at) {
                 return response()->json(['error' => 'User is not deleted.'], 400);
             }
 
             $user->restore(); // Restore the user
 
             return response()->json(['success' => true]);
         } catch (\Exception $e) {
             return response()->json(['error' => 'Failed to restore user. ' . $e->getMessage()], 500);
         }
     }
}
