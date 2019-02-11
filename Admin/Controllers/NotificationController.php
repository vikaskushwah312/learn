<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\Notification;
use Session;
use Illuminate\Support\Facades\Validator;
use URL,Redirect,Input,Image,File,DateTime,Config,Response,DB;
/*
* Name: NotificationController
* Create Date: 
*/
class NotificationController extends Controller{

	public function notifications(Request $request){

		$data['users'] = Users::get();
        
        $data['title'] = 'Notifications List';

        return view("Admin::list.notifications", $data);

    }
    public function notificationListData(Request $request){


	     $query = Notification::query();

	    	// Datatables Variables
	      $draw   = intval($request->get("draw"));
	      $start  = intval($request->get("start"));
	      $length = intval($request->get("length"));
	     
	      if ($request->get('search')['value'] != "") {

	          $value = $request->get('search')['value'];          
              
	          $query->where('title',"LIKE","%$value%")
                    ->orWhere('first_name',"LIKE","%$value%")
                    ->orWhere('last_name',"LIKE","%$value%")
	                ->orWhere('message',"LIKE","%$value%");         

	      }
          
	      //Order
	        $orderByField = "notifications.id";
	        $orderBy = 'desc';
	          
	        if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {
	                        
	           $orderBy = $request->get('order')[0]['dir'];

	        }

	        if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

	            if ($request->get('order')[0]['column'] == 0) {
	                
	              $orderByField = "notifications.id";
	            
	            }else if($request->get('order')[0]['column'] == 1){

	              $orderByField = "notifications.title";
	            
	            }else if($request->get('order')[0]['column'] == 1){

	              $orderByField = "notifications.message";
	            
	            }

	        }	      	   

	      $field = array('notifications.id','title','message','type','notifications.created_at','notifications.image as image',
            DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS name"));

	      $query->join('users', 'users.id', '=', 'notifications.user_id')
	            ->orderBy($orderByField,$orderBy);
	      	      
	      $total = $query->count();

	      $info =    $query->skip($start)
	                    ->take($length)
	                    ->get($field); 


	      $data = array();
	      $sno 	= $start;

	      foreach($info as $r) {
			   
			   $sno = $sno + 1;

			    //delete url    
        		$delet_Url = "'admin/delete_activity/$r->id/notification','$r->id'";

        		$deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete</a>';
        		
        		$resendUrl = url("admin/resend-notification/$r->id");

        		$resendButton = '<a type="" class="" style="text-decoration: none;" href="'.$resendUrl.'"><i class="fa fa-paper-plane"></i>Resend</a>';

                /*Img path */
                $file = "public/uploads/notification/".$r->image;
                //print_r($file_path);
                
                
                if ($r->image !='') {

                    if (file_exists($file)) {

                        $profileImage  = URL::to(Config::get('constants.NOTIFICATION_IMAGE').$r->image);          
                        //$fancyBoxImage  = Config::get('constants.NOTIFICATION_IMAGE').$r->image;
                        $showImage = '<a class="fancybox" rel="lightbox" href="'.$profileImage.'"> <img src="'.$profileImage.'" class = "imag-square" height = "70px" width = "70px;"> </a>';
                        
                    }else{

                        $showImage ="";
                    }                    
                    
                }else{
                     $showImage = "";
                }

	           $data[] = array(	          	           
	           		$sno,
                    $showImage,
	           		$r->name,
	           		$r->title,
	           		$r->message,
	           		date(Config::get('constants.DATE_TIME_FORMATE'), strtotime($r->created_at)),
			        '<div class="manage_drop_d">
                        <div class="dropdown">
                            <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span>
                            </button>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu"> 
                                    <li>'.$resendButton.'</li>   
                                    <li>'.$deleteUrl.'</li> 
                                </ul>
                        </div>
                    </div> ',
	           );
	      }
	      
	      	$output = array(
	                "draw"            => $draw,
	                "recordsTotal"    => $total,
	               "recordsFiltered"  => $total,
	               "data"             => $data
	        );

	      echo json_encode($output);
	      exit(); 
    }

    /*
    * Name: sendNotificationUsers
    * Create Date: 14 Aug 2017
    * Purpose: To send multiple notification
    */

    public function sendNotificationUsers(Request $request){

        $response['success'] = 0;
        $response['msg']     = 'Failed to send notifications.';

        //Variables
        $allWho     = $request->get('all_who');
        $users      = $request->get('users');
        $title      = $request->get('title');
        $message    = $request->get('message');
        $file       = $request->file('n_image');
        $image = "";

        
        if($file && $file != ""){

          $image  = getTimeStamp().".".$file->getClientOriginalExtension();

          Image::make($file)->resize(200, 200)->save('public/uploads/notification/thumbnail/'.$image);

          $file->move('public/uploads/notification', $image);
                    
        }

        $type = "BY_ADMIN";

        if ($allWho == "all_users") {
            
            $getUsers = Users::get(['id']);

        }elseif($allWho == "customers"){

            $getUsers = Users::where('user_type', 'Users')->get(['id']);

        }elseif($allWho == "all_drivers"){

            $getUsers = Users::where('user_type', 'Driver')->get(['id']);

        }else{

            $userArray[] = $users;
            $getUsers = Users::whereIn('id', $userArray)->get(['id']);    
        }

        if (count($getUsers) > 0) {                        
                  
            $body   = array("type"    => $type, 
                            "message" => $message,
                            "image"   => $image
                        );
            //print_r($body);die;

            send_notification($getUsers, $body, $title);
                        
            $response['success'] = 1;
            $response['msg']     = "Great! Notification sent successfully";
        }
        return Response::json($response);
    }

    /*
    * Name: resendNotification
    * Create Date: 
    * Purpose: To resend notification to user.
    */

    public function resendNotification(Request $request, $id){
	        	
    	$info = Notification::where('notifications.id', $id)
    			->join('users', 'notifications.user_id', '=', 'users.id')
    			->first(['users.notification_id','user_id', 'title', 'message', 'type','image']);
    	//d($info);
    	if (!empty($info)) {
    		
            if ($info->notification_id != "") {
    			
                $title      = $info->title;
                $message    = $info->message;
    			$image 	    = $info->image;
    			$type 	    = 'ADMIN_RESEND';
                
                $body   = array("type"    => $type, 
                                "message" => $message,
                                "image"   => $image);
                  
                send_notification([$info->user_id], $body, $title);

        		return Redirect::to('admin/notifications')
        			->withSuccess('Great! Notification has been sent successfully.');
        		exit();
	        		        	
    		}else{

        		return Redirect::to('admin/notifications')->withFail('Oops! Failed to resend notification.');
        	}	
    	
    	}else{

    		return Redirect::to('admin/notifications')->withFail('Oops! Failed to resend notification.');
    	}
    	
    }
}