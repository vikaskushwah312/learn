<?php

namespace App\Modules\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\{AppInfo,Users,CancelReason,Country,State,City};
use App\Models\Content;


use App\User;
use Response, Mail;

/*
* Name: ContentController
* Create Date: 19 March 2018
*/

class ContentController extends Controller
{
    /*
    * Name: getAppInfo
    * Create Date: 19 March 2018
    * Purpose: This function gives app info
    */

    public function getAppInfo(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_APP_INFO_NOT_FOUND');
        $res['code']    = 400;         

        $data = AppInfo::first();
        
        if (!empty($data)) {
            
            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_APP_INFO_FOUND');        
            $res['code']    = 200;
            $res['result']  = $data;
        }

        return Response::json($res);
    }

    /*
    * Name: getAppInfo
    * Create Date: 19 March 2018
    * Purpose: This function is used to get privacy policy and terms and condition.
    */

    public function getContent(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_RECORD_NOT_FOUND');
        $res['code']    = 400;         

        $vArray = [
            
            'type'  => 'required|in:terms_condition,privacy_policy',            
        ];

        //Validate request
        check_validation($request, $vArray);

        $data = Content::where(['type' => $request->get('type')])
                ->first(['type', 'value']);
        
        if (!empty($data)) {
            
            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_RECORD_FOUND');        
            $res['code']    = 200;
            $res['result']  = $data;
        }

        return Response::json($res);
    }

   /*
    * Name: getAppInfo
    * Create Date: 19 March 2018
    * Purpose: This function is used to update user setting
    */

    public function updateSetting(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_UPDATE_USER_SETTING');
        $res['code']    = 400;

         $vArray = [
            
            'notification_setting'  => 'required|in:0,1'
            
        ];

        //Validate request
        check_validation($request, $vArray);

        //Variables
        $user  = $request->get('user');
        
        $where = array('id' => $user->id);

        $data = array(

            "notification_setting"  => $request->get('notification_setting')
        );

        $check = Users::where($where)->update($data);

        if ($check) {
            
            $info = Users::where($where)
                        ->first(['notification_setting']);

            $res['success'] = true;
            $res['msg']     = trans('Api::language.LANG_USER_SETTING_UPDATED');
            $res['code']    = 200;            
            $res['result']  = $info;            

        }
        return Response::json($res);

    }


    /*
    * Name: cancelReasons
    * Create Date: 26 March 2018
    * Purpose: This function is used to give cancel reason listing
    */

    public function cancelReasons(){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_CANCEL_REASONS_NOT_FOUND');
        $res['code']    = 400;

        $data = CancelReason::get(['id', 'reason']);

        if (count($data) > 0) {
            
            $res['success'] = true;
            $res['msg']     = trans('Api::language.LANG_CANCEL_REASONS_FOUND');
            $res['code']    = 200;            
            $res['result']  = $data;            

        }
        return Response::json($res);
    }

    /*
    * Name: countries
    * Create Date: 27 March 2018
    * Purpose: This function is used to get countries
    */

    public function countries(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_RECORD_NOT_FOUND');
        $res['code']    = 400;

        $data = Country::where('status', 'Active')
                ->get(['id', 'country_name']);

        if (count($data) > 0) {
            
            $res['success'] = true;
            $res['msg']     = trans('Api::language.LANG_RECORD_FOUND');
            $res['code']    = 200;            
            $res['result']  = $data;            

        }
        return Response::json($res);        
    }
    
    public function states(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_RECORD_NOT_FOUND');
        $res['code']    = 400;

        $vArray = [

            'country_id'  => 'required'            
        ];

        //Validate request
        check_validation($request, $vArray);
        
        $where = array('status' => 'Active', 'country_id' => $request->country_id);

        $data = State::where($where)
                ->get(['id', 'state_name']);

        if (count($data) > 0) {
            
            $res['success'] = true;
            $res['msg']     = trans('Api::language.LANG_RECORD_FOUND');
            $res['code']    = 200;            
            $res['result']  = $data;            

        }
        return Response::json($res);        
    }

    public function cities(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_RECORD_NOT_FOUND');
        $res['code']    = 400;

        $vArray = [
            
            'state_id'  => 'required'            
        ];

        //Validate request
        check_validation($request, $vArray);
        
        $where = array('status' => 'Active', 'state_id' => $request->state_id);

        $data = City::where($where)
                ->get(['id', 'city_name']);

        if (count($data) > 0) {
            
            $res['success'] = true;
            $res['msg']     = trans('Api::language.LANG_RECORD_FOUND');
            $res['code']    = 200;            
            $res['result']  = $data;            

        }
        return Response::json($res);        
    }

    public function checkEmail(Request $request){

        $emailData['email'] = "gawande.pankajkumar@gmail.com";
        $emailData['name']  = "Pankaj Gawande";

       $data = Mail::send('email.welcome', $emailData, function ($message) use($emailData){
            $message->to($emailData['email'])
                    ->subject('Welcome to Eco Gadi!');            
        });
        if (Mail::failures()) {
            d("no");
        }else{
            d("yes");
        }        
    }

    public function checkSms(Request $request){

        send_sms('9752174241',$request->get('message'));
        
    }
}
