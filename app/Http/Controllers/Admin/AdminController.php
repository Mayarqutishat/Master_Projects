<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
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
         // Fetch admins with pagination (10 admins per page, you can change the number as needed)
         $admins = User::withTrashed()->where('user_role', 'admin')->paginate(8);
        
        return view('admin.admins.index', compact('admins'));
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
}