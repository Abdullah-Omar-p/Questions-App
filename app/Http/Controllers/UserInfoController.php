<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowRequest;
use App\Http\Requests\UserInfoRequest;
use App\Http\Resources\UserInfoResource;
use App\Models\User;
use App\Models\UserInfo;
use App\Http\Requests\StoreUserInfoRequest;
use App\Http\Requests\UpdateUserInfoRequest;
use App\Helpet\Helper;
use Illuminate\Http\Response;

class UserInfoController extends Controller
{
    public function index(ShowRequest $request)
    {
        $user = auth('sanctum')->user();
        if (!$user->hasPermissionTo('read-user-info')){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $page = $request->has('page') ? $request->page : 1;
        if ($request->has('search')) {
            $data = UserInfo::where('name', 'like', '%' . $request->search . '%')->paginate(8);
        }else{
            $data = UserInfo::paginate(8);
        }
        $info = UserInfoResource::collection($data);

        if ($info->isEmpty()){
            return response()->json([
                'status' => 'failed',
                'message'=> 'no questions to get ',
            ]);
        }
        return Helper::responseData('Retrieved', true,$info, Response::HTTP_OK);
    }

    public function store(StoreUserInfoRequest $request)
    {
        $user = auth('sanctum')->user();
        if (!$user){
            return  Helper::responseData('Log in to Continue', false, 301);
        }
        $input ['device'] = $request->device;
        $input ['device_details'] = $request->device_details;
        $input ['brand'] = $request->brand;
        $input ['user_id'] = $user->id;

        $info = UserInfo::create($input);
        return Helper::responseData('Created', true,UserInfoResource::make($info), Response::HTTP_CREATED);
    }

    public function show(UserInfoRequest $request)
    {
        $information = UserInfo::where('id', $request->id)->first();
        return  Helper::responseData('Retrieved', true,UserInfoResource::make($information), Response::HTTP_OK);
    }

    public function update(UpdateUserInfoRequest $request)
    {
        $information = UserInfo::find($request->id);
        $user = auth('sanctum')->user();
        if (!$user->id == $information->user_id){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $input ['id'] = $request->id;
        $input ['device'] = $request->device;
        $input ['device_details'] = $request->device_details;
        $input ['brand'] = $request->brand;
        $info_ = $information->update($input);
        $data = UserInfo::find($request->id);
        return  Helper::responseData('Updated', true,UserInfoResource::make($data), Response::HTTP_OK);
    }

    public function destroy(UserInfoRequest $request)
    {
        $info = UserInfo::find($request->id);
        $user = auth('sanctum')->user();
        if (!$user->id == $info->user_id){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $info->delete();
        return  Helper::responseData('Deleted', true,null, Response::HTTP_OK);
    }
}
