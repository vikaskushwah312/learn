<?php

namespace App\Modules\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Sos,Configuration};

use Response;

/*
* Name: ApiController
* Create Date: 16 March 2018
* Purpose: This function is used for general functions 
*/

class ApiController extends Controller
{

    public function index(){
        
        $res = array("success" => true, "msg" => "Base url here");

        $configurations = Configuration::get(['type', 'value']);

        $res['result'] = array(
            
            "base_url"                  => url('/api').'/',    
            "profile"                   => url('/public/uploads/profile').'/',    
            "profile-thumbnail"         => url('/public/uploads/profile/thumbnail').'/',
            "auto_capture"              => url('/public/uploads/auto_capture').'/',
            "auto_capture_thumbnail"    => url('/public/uploads/auto_capture/thumbnail').'/',
            "insurance"                 => url('/public/uploads/insurance').'/',
            "insurance-thumbnail"       => url('/public/uploads/insurance/thumbnail').'/',
            "license_image"             => url('/public/uploads/insurance').'/',
            "license_image-thumbnail"   => url('/public/uploads/license_image/thumbnail').'/',
            "vehicle"                   => url('/public/uploads/insurance').'/',
            "vehicle-thumbnail"         => url('/public/uploads/vehicle/thumbnail').'/',
            "notification"              => url('/public/uploads/notification').'/',
            "notification-thumbnail"    => url('/public/uploads/notification/thumbnail').'/',
            
        );
        
        if (count($configurations) > 0) {
            
            foreach ($configurations as $key => $value) {
                
                $res['result'][$value->type] = $value->value;      

            }        
        }

        return Response::json($res);
    }

    /*
    * Name: addSos
    * Create Date: 19 March 2018
    * Purpose: This function is used to add sos
    */

    public function addSos(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_ADD_SOS');
        $res['code']    = 400;          
        
        $user = $request->get('user');

        $vArray = [
            
            'contact_1' => 'required',        
            'message'   => 'required',          
            
        ];

        //Validate request
        check_validation($request, $vArray);


        $where = array('user_id' => $user->id);


        $data = array(

            'contact_1'     => $request->get('contact_1'),   
            'contact_2'     => $request->get('contact_2'),   
            'contact_3'     => $request->get('contact_3'),   
            'message'       => $request->get('message'),
            'created_at'    => date('Y-m-d H:i:s')   
        );
       
        $check = Sos::updateOrCreate($where, $data);
        
        if ($check) {

            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_SOS_INFO_HAS_BEEN_ADDED');
            $res['code']    = 200;
                        
        }
        
        return Response::json($res);
    }

    /*
    * Name: sendSos
    * Create Date: 19 March 2018
    * Purpose: This function is used to send emergency message to friends
    */

    public function sendSos(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_SEND_SOS');
        $res['code']    = 400;          
        
        $user = $request->get('user');

        $where = array('user_id' => $user->id);

        $check = Sos::where($where)->first(['id']);

        if ($check) {

            //Send SMS HERE

            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_SOS_SENT');
            $res['code']    = 200;
                        
        }
        
        return Response::json($res);        
    }

}
