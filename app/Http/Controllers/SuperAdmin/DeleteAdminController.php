<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helpet\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SelectUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class DeleteAdminController extends Controller
{
    public function deleteAdmin(SelectUserRequest $request)
    {
        $user = auth('sanctum')->user();
        if (!$user->hasAnyRole(['super-admin'])){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        try {
            // Find the user to be deleted
            $adminToDelete = User::find($request->id);

            if ($adminToDelete->hasRole('admin')) {

                $adminToDelete->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Admin deleted successfully.'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'The Selected user is not an admin.'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete admin: ' . $e->getMessage()
            ], 500);
        }
    }
}
