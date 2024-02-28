<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpet\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddAdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AddSuperAdminController extends Controller
{
    public function addSuperAdmin(AddAdminRequest $request)
    {
        $user = auth('sanctum')->user();
        if (!$user->hasAnyRole(['super-admin'])){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        try {
            // Create the new user with the super admin role
            $newSuperAdmin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role_id' => 1,
                'password' => Hash::make($request->password)            ]);

            return response()->json([
                'status' => true,
                'message' => 'Super admin added successfully.',
                'user' => $newSuperAdmin
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to add super admin: ' . $e->getMessage()
            ], 500);
        }
    }
}
