<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch admins with pagination (including soft-deleted admins)
        $admins = User::withTrashed()->where('user_role', 'admin')->paginate(8);
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Soft delete a user.
     */
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

    /**
     * Restore a soft-deleted user.
     */
    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);  // Find the user by ID including trashed

            if (!$user->deleted_at) {
                return response()->json(['error' => 'User is not deleted.'], 400);
            }

            $user->restore();  // Restore the user
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to restore user. ' . $e->getMessage()], 500);
        }
    }
}