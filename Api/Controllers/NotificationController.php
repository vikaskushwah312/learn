<?php

namespace App\Modules\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Response;

/*
* Name: NotificationController
* Create Date: 

*/

class NotificationController extends Controller
{
	    
    /*
    * Name: notifications
    
    */

    public function notifications(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_NOTIFICATIONS_NOT_FOUND');
        $res['code']    = 400;

        $vArray = [
            'page'    => 'required',
        ];

        //Validate request
        check_validation($request, $vArray);

        $user = $request->get('user');

            // for pagination
        $per_page   = 12;
        $page       = intval($request->get('page'));
        $start_from = ($page - 1) * $per_page;

        $notifications = Notification::where(array('user_id' => $user->id))
            ->skip($start_from)
            ->take($per_page)   
            ->orderBy('notifications.id', 'DESC')
            ->get();


            
        if (count($notifications) > 0) {
            
            $total = Notification::where(array('user_id' => $user->id))->count();

            Notification::where(array('user_id' => $user->id))
                    ->update(['read_status' => '1']);
            
            $res['success'] = true;
            $res['msg']     = trans('Api::language.LANG_NOTIFICATIONS_FOUND');
            $res['code']    = 200;
            $res['result']  = $notifications;
            $res['total']   = $total;            

        }

        return Response::json($res);
    }

    /*
    * Name: readNotification
    * Create Date: 24 March 2018
    * Purpose: This function is used to read notification
    */   

    public function readNotification(Request $request){

        $res['success'] = true;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_READ_NOTIFICATIONS');
        $res['code']    = 400;

        $user = $request->get('user');

        $notifications = Notification::where(array('user_id' => $user->id))
                            ->update(['read_status' => '1']);

        if ($notifications) {
            
            $res['success'] = true;
            $res['msg']     = trans('Api::language.LANG_READ_ALL_NOTIFICATIONS');
            $res['code']    = 200;

        }
        return Response::json($res);
                
    }

    public function deleteNotification(Request $request){
        //print_r("delete call");die;

        $res['success'] = true;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_DELETE_NOTIFICATIONS');
        $res['code']    = 400;

        $vArray = [
            'notification_id'    => 'required',
        ];

        //Validate request
        check_validation($request, $vArray);

        $user            = $request->get('user');
        $notificationId  = $request->get('notification_id');

        $check = Notification::where('id', $notificationId)
                            ->forceDelete();

        if ($check) {
            
            $res['success'] = true;
            $res['msg']     = trans('Api::language.LANG_NOTIFICATIONS_DELETED');
            $res['code']    = 200;

        }
        return Response::json($res);
                
    }
   
}
