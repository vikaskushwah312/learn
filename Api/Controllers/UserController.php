<?php

namespace App\Modules\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Users};

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Response,Image;

/*
* Name: UserController
* Create Date: 19 March 2018

* Purpose: This function is used for driver 
*/

class UserController extends Controller
{
    
    /*
    * Name: getProfile
    * Create Date: 19 March 2018
    
    * Purpose: This function is used to get profile
    */

    public function getProfile(Request $request){

    	$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_GET_PROFILE');
        $res['code']    = 200;          
        

        $vArray = [           
            
        ];

        //Validate request
        check_validation($request, $vArray);

        $user = $request->get('user');

        $where = array('id' => $user->id);

        $fields = array('users.id', 'first_name', 'last_name', 'email', 'mobile_number', 'user_type', 'gender', 'profile_image', 'country_id', 'state_id', 'city_id', 'address');

        $userInfo = Users::where($where)->first($fields);
       
        if (!empty($userInfo)) {

            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_RECORD_FOUND');
            $res['code']    = 200;
            $res['result']  = $userInfo;
                        
        }
        
        return Response::json($res);
    }

    /*
    * Name: updateProfile
    * Create Date: 19 March 2018
    
    * Purpose: This function is used to update profile
    */

    public function updateProfile(Request $request){


    	$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_UPDATE_PROFILE');
        $res['code']    = 400;          
        
        $user = $request->get('user');

        $vArray = [           
            'first_name' 		=> 'required',
            'last_name' 		=> 'required',
            'email'  			=> 'unique:users,email,'.$user->id,
            'country_code'      => 'required',
            'country_id'        => 'required',
            'state_id'          => 'required',
            'city_id'           => 'required',
            'address'		    => 'required',
            'mobile_number'  	=> 'required|unique:users,mobile_number,'.$user->id,
            'gender'			=> 'required|in:Male,Female'
        ];


        //Validate request
        check_validation($request, $vArray);

        $where = array('id' => $user->id);

        $data = array('first_name'  	=> $request->get('first_name'), 
        			  'last_name'		=> $request->get('last_name'), 
        			  'email' 			=> $request->get('email'), 
        			  'country_code' 	=> $request->get('country_code'), 
        			  'mobile_number'	=> $request->get('mobile_number'),
                      'gender'          => $request->get('gender'),
                      'country_id'      => $request->get('country_id'),
                      'state_id'        => $request->get('state_id'),
                      'city_id'         => $request->get('city_id'),
        			  'address'			=> $request->get('address'),
        			);
        //print_r($data);die;

        $file = $request->file('profile_image');

        if($file && $file != ""){

          $profile_image  = getTimeStamp().".".$file->getClientOriginalExtension();
          
          Image::make($file)->resize(200, 200)->save('public/uploads/profile/'.$profile_image);

          $file->move('public/uploads/profile', $profile_image);
          
          $data['profile_image']  = $profile_image;

          //Remove old license image
          if ($user->profile_image != "") {

            delete_image("public/uploads/license_image/$user->profile_image");
            delete_image("public/uploads/license_image/thumbnail/$user->profile_image");
              
          }

        }

        //Check if mobile number is not same
        if ($request->get('mobile_number') != $user->mobile_number) {
            
            $randomCode = random_code();
            
            $data['mobile_verification_status'] = "NotVerified"; 
            $data['mobile_verification_code']   = $randomCode; 

            //SMS
            $message = "Please use OTP: $randomCode to verify your mobile number, Eco Gadi";
            send_sms($request->get('mobile_number'),$message);
        }

        $check = Users::where($where)->update($data);
        
        if ($check) {


            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_PROFILE_UPDATED');
            $res['code']    = 200;
            $res['result']  = $data;
                        
        }

        return Response::json($res);
    }

    /*
    * Name: changePassword
    * Create Date: 19 March 2018
    * Purpose: This function is used to change password 
    */

    public function changePassword(Request $request){

   		$res['success']   = false;
   		$res['code'] 	    = 400;
   		$res['msg'] 	    = trans('Api::language.LANG_FAILED_TO_CHANGE_PASSWORD');

        $vArray = [
            'current_password' => 'required',
            'new_password'     => 'required',                    
        ];
        
        //Validate request
        check_validation($request, $vArray);

        $user 				= $request->get('user');
        $currentPassword 	= $request->get('current_password');
        $newPassword 		= $request->get('new_password');

        $credentials = array('email' => $user->email, "password" => $currentPassword);

        if ($token = JWTAuth::attempt($credentials)) {

        	$check = Users::where(array('id' => $user->id))
        		->update(array('password' => bcrypt($newPassword)));

        	if ($check) {
        		
            $res['code']   = 200;
            $res['success']   = true;
        		$res['msg']	= trans('Api::language.LANG_PASSWORD_CHANGED_SUCCESSFULLY');	
        	}

        }else{

			$res['msg']	= trans('Api::language.LANG_YOU_HAVE_ENTERED_INVALID_CURRENT_PASSWORD');       	
        }
		
        return Response::json($res);
    }
}
