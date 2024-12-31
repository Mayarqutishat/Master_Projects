<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // Ensure the user is authenticated
        $this->middleware('role:admin');  // Ensure the user has the 'admin' role
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch admins including soft-deleted ones
        $admins = Admin::withTrashed()->get();
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new admin
        $admin = new Admin();
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->password = bcrypt($request->input('password'));  // Encrypt the password
        $admin->save();

        // Redirect with success message
        return redirect()->route('admin.admins.index')->with('success', 'Admin created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,  // Ensure the email is unique, excluding the current admin
            'password' => 'nullable|string|min:8|confirmed',  // Password is optional during update
        ]);

        // Find and update the admin details
        $admin = Admin::findOrFail($id);
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        if ($request->filled('password')) {
            $admin->password = bcrypt($request->input('password'));
        }
        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function softDelete($id)
    {
        try {
            $admin = Admin::findOrFail($id);
            
            if ($admin->deleted_at) {
                return response()->json(['error' => 'Admin already deleted.'], 400);
            }

            // Perform the soft delete
            $admin->delete();

            return response()->json(['success' => true, 'message' => 'Admin has been soft deleted.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete admin. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Restore a soft deleted admin.
     */
    public function restore($id)
    {
        try {
            $admin = Admin::withTrashed()->findOrFail($id);
            
            // Restore the soft-deleted admin
            $admin->restore();

            return response()->json(['success' => true, 'message' => 'Admin has been restored.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restore admin. ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage permanently (hard delete).
     */
    public function destroy(string $id)
    {
        try {
            $admin = Admin::findOrFail($id);
            $admin->forceDelete();  // Permanently delete the admin

            return redirect()->route('admin.admins.index')->with('success', 'Admin deleted permanently.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete admin. ' . $e->getMessage()], 500);
        }
    }
}
