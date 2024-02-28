<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpet\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddAdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AddAdminController extends Controller
{
    public function addAdmin(AddAdminRequest $request)
    {
        $user = auth('sanctum')->user();
        if (!$user->hasAnyRole(['super-admin'])){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        try {
            // Create the new admin user
            $newAdmin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role_id' => 2,
                'password' => Hash::make($request->password),
            ]);

            // Assign the admin role to the new user
            $newAdmin->assignRole('admin');

            return response()->json([
                'status' => true,
                'message' => 'Admin added successfully.',
                'admin' => $newAdmin,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add admin: ' . $e->getMessage()
            ], 500);
        }
    }
}
