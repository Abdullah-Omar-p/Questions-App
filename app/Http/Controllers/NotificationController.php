<?php

namespace App\Http\Controllers;

use App\Helpet\Helper;
use App\Http\Requests\NotificationRequest;
use App\Http\Requests\ShowRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Models\UserNotification;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(ShowRequest $request)
    {
        // policy
        $user = auth('sanctum')->user();
        $notificationOwner = UserNotification::where('user_id',$user->id);
        if (!$user->hasPermissionTo('read-notification') || $user ==$notificationOwner){
            return  Helper::responseData('Not Allowed', true, 301);
        }

        $page = $request->has('page') ? $request->page : 1;
        if ($request->has('search')) {
            $data = Notification::where('name', 'like', '%' . $request->search . '%')->paginate(8);
        }else{
            $data = Notification::paginate(8);
        }
        $notifications =NotificationResource::collection($data);

        if ($notifications->isEmpty()){
            return response()->json([
                'status' => 'failed',
                'message'=> 'no notifications to get ',
            ]);
        }
        return Helper::responseData('Retrieved', true,$notifications, Response::HTTP_OK);
    }

    public function store(StoreNotificationRequest $request)
    {
        // policy
        $user = auth('sanctum')->user();
        if (!$user->hasPermissionTo('create-notification')){
            return  Helper::responseData('Not Allowed', true, 301);
        }

        $user = auth('sanctum')->user();
        $input['title'] = $request->title;
        $input['message'] = $request->message;
        $input['user_id'] = $user->id;
        $notification = Notification::create($input);
        return Helper::responseData('Created', true,NotificationResource::make($notification), Response::HTTP_CREATED);
    }

    public function show(NotificationRequest $request)
    {
        $notification = Notification::where('id', $request->id)->first();
        $userNotification = UserNotification::where('notification_id',$notification->id)->get('user_id');
        // policy
        $user = auth('sanctum')->user();
        if (!$user->id == $userNotification || !$user->hasPermissionTo('read-notification')){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        return  Helper::responseData('Retrieved', true,NotificationResource::make($notification), Response::HTTP_OK);
    }

    public function update(UpdateNotificationRequest $request)
    {
        // policy
        $user = auth('sanctum')->user();
        if (!$user->hasPermissionTo('update-notification')){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $notification =Notification::find($request->id);
        $input['title'] = $request->title;
        $input['message'] = $request->message;
        $notification->update($input);
        return  Helper::responseData('Updated', true,NotificationResource::make($notification), Response::HTTP_OK);
    }

    public function destroy(NotificationRequest $request)
    {
        // policy
        $user = auth('sanctum')->user();
        $notificationUser = UserNotification::where('notification_id',$request->notification_id)->get('user_id');
        if (!$user->hasPermissionTo('delete-notification'|| $user->id == $notificationUser)){
            return  Helper::responseData('Not Allowed', true, 301);
        }
        $notification__ = Notification::find($request->id);
        $notification__->delete();
        return  Helper::responseData('Deleted', true,null, Response::HTTP_OK);
    }
}
