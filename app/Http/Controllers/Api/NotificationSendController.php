<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationSendController extends Controller
{
    public function updateDeviceToken(Request $request)
    {
        $userID = config('app.roleid.super_admin');
        $user = User::where('id',$userID)->update(['device_token' => $request->token ]);
        $responseData = [
            'status'            => true,
            'message'           => 'Token successfully stored.',
        ];
        return response()->json($responseData, 200);
    }

    public function getAllNotification()
    {
        $notifications = Notification::select('id','subject','message','notification_type',DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y %h:%i %p') as date"))->orderByDesc('created_at')->get();

        $responseData = [
            'status'    => true,
            'message'   => 'success',
            'notifications'  => $notifications,
        ];
        return response()->json($responseData, 200);
    }
}
