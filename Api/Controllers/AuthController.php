<?php

namespace App\Modules\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Users,Notification,Token,DriverInfo,Ride,RideRequest};
use App\User;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Response,Validator,Mail;


/*
* Name: ApiController
* Create Date: 16 March 2018
* Purpose: This function is used for driver 
*/

class AuthController extends Controller
{
    
    /*
    * Name:login
    * Create Date: login
    * Purpose: This function is used to login to passanger or driver
    */

    public function login(Request $request){

        //Validate request
        $vArray = ["email"          => "email", 
                   "mobile_number"  => 'digits:10',
                   "password"       => 'required',
                   "device_type"    => 'required|in:android,iphone,web',
                   "user_type"      => 'required|in:Passenger,Driver'
               ];

        check_validation($request, $vArray);

        $res = array("success" => false, "msg" => "Failed to login.", 'code' => 400);
        
        //Variables
        $email              = $request->get('email');
        $mobileNumber       = $request->get('mobile_number');
        $password           = $request->get('password');
        $userType           = $request->get('user_type');
        
        
        if ($email != "") {
            
            $credentials = array("email" => $email, "password" => $password);

        }else{

            $credentials = array("mobile_number" => $mobileNumber, "password" => $password);

        }

        $credentials['user_type'] = $userType;

        if ($token = JWTAuth::attempt($credentials)) {
                 
            $user = JWTAuth::user();
            
            if ($user->status == '1') {
                
                $data = $user;

                $data['token'] = $token;            

                $data['notification_count'] = Notification::where(['user_id' => $user->id, 'read_status' => '0'])
                    ->count();               
                
                //Update Device info
                $this->update_device_info($request, $user->id, $token);

                //Update online status
                $this->online_status($user->id);

                $data['online_status'] = "Online";
                
                //Check running ride
                //check canceled ride
                
                $rideQuery = Ride::where(function($query)use($user){
                    $query->where('rides.passenger_id', $user->id)
                        ->orwhere('rides.driver_id', $user->id);
                    })
                    ->whereIn('ride_status', ['Pending','Accepted', 'Running'])
                    ->whereDate('rides.created_at', date('Y-m-d'))
                    ->orderBy('rides.id', 'DESC');

                if($user->user_type == "Driver") {

                    $rideQuery->leftjoin('ride_requests',function($join) use($user){
                        return $join->on('ride_requests.ride_id','=','rides.id')
                            ->where(['ride_requests.driver_id' => $user->id]);
                    })
                    ->whereIn('request_status', ['Accepted', null, 'Pending']);
                }

                $data['ride'] = $rideQuery->first(['rides.id','rides.ride_status']);
               
                $res['success'] = true;
                $res['msg']     = trans('Api::language.LANG_LOGIN_SUCCESSFUL');
                $res['code']    = 200;
                $res['result']  = $data;

            }else{

                $res['success'] = false;
                $res['msg']     = trans('Api::language.LANG_YOUR_ACCOUNT_IS_INACTIVATE');
                $res['code']    = 400;
                
            }

        }else{

            $res['msg'] = trans('Api::language.LANG_INVALID_LOGIN_CREDENTIALS');
        }

        return Response::json($res);
    }


    /*
    * Name: update_device_info
    * Create Date: 9 Feb 2018
    */

    public function update_device_info($request, $userId, $token){

        $ip                 = $request->get('ip');
        $browser            = $request->get('browser');

        //Update user info
        $updatedata = array(
            'app_version'       => $request->get('app_version'),
            'notification_id'   => $request->get('notification_id'),
            'device_id'         => $request->get('device_id'),
            'device_type'       => $request->get('device_type'),            
        );
    
        //Remove notification id if other user have it.
        Users::where(array('notification_id' => $request->get('notification_id')))->update(array('notification_id' => ''));

        Users::where(array('id' => $userId))->update($updatedata);

        //Insert or update token and device info.
                        
        $tokenDeviceData = [

            'user_id'          => $userId,
            'notification_id'  => $request->get('notification_id'),
            'device_id'        => $request->get('device_id'),
            'device_type'      => $request->get('device_type'),
            'token'            => $token,
            'ip'               => $ip,
            'browser'          => $browser,

        ];

        post_token_device($tokenDeviceData);

    }
    
    /*
    * Name: forgotPassword
    * Create Date: 16 March 2018
    * Purpose: This function is used to generate verification code for reset password.
    */

    public function forgotPassword(Request $request){

        $res = array("success" => false, 
                    "msg" => "Failed to send verification code.", 
                    'code' => 400);        

        //Validate request
        $vArray = [
                   "email"          => "email", 
                   "mobile_number"  => 'digits:10',
                   "user_type"      => 'required|in:Passenger,Driver'                                     
                ];

        check_validation($request, $vArray);
        
        //Variables
        $email           = $request->get('email');
        $mobileNumber    = $request->get('mobile_number');
        $userType        = $request->get('user_type');
        $randomCode      = random_code();
        $check = false;
        
        $update = array('verification_code' => $randomCode);

        if ($email != "") {
            
            //Send email here

            $where = array('email' => $email);

            $checkEmailExists = Users::oftype($userType)->where($where)->first(['id']);

            if (!empty($checkEmailExists)) {
                
                $check = Users::where($where)->update($update);
                $res['msg']     = trans('Api::language.LANG_WE_HAVE_SENT_YOU_OTP_ON_EMAIL_FORGOT');

                //Send Mail
                $emailData['code']  = $randomCode;
                $emailData['email'] = $email;

                Mail::send('email.forgot_password', $emailData, function ($message) use($emailData){
                    $message->to($emailData['email'])
                            ->subject('Forgot Password');            
                });

            }else{

                $res['msg']     = trans('Api::language.LANG_THIS_EMAIL_ID_IS_NOT_REGISTERED_WITH_US');

            }

        }else{

            
            $where = array('mobile_number' => $mobileNumber);

            $checkMobileExists = Users::where($where)->first(['id']);

            if (!empty($checkMobileExists) > 0) {
                
                $check = Users::where($where)                            
                            ->update($update);
                
                //Send OTP here
                $message = "Please use OTP: $randomCode to verify your mobile number, Eco Gadi";

                send_sms($mobileNumber,$message);

                $res['msg']     = trans('Api::language.LANG_WE_HAVE_SENT_YOU_OTP_ON_MOBILE_FORGOT');

            }else{

                $res['msg']     = trans('Api::language.LANG_THIS_MOBILE_IS_NOT_REGISTERED_WITH_US');

            }

        }

        if ($check) {

            $res['success'] = true;
            $res['code']    = 200;
            $res['result']  = $randomCode;
            
        }

        return send_json($res);
        
    }

    /*
    * Name: resetPassword
    * Create Date: 19 March 2018
    * Purpose: This function is used to reset password by verification code
    */

    public function resetPassword(Request $request){

        //Validate request
        $vArray = [
                   "email"               => "email", 
                   "password"            => "required", 
                   "mobile_number"       => 'digits:10',
                   "verification_code"   => 'required|digits:4'                                        
                ];

        check_validation($request, $vArray);

        $res = array("success" => false, 
                    "msg" => trans('Api::language.LANG_FAILED_TO_RESET_PASSWORD'), 
                    'code' => 400);

        //Variables
        $email          = $request->get('email');
        $password       = bcrypt($request->get('password'));
        $mobileNumber   = $request->get('mobile_number');
        $otp            = $request->get('verification_code');

        if ($email != "") {
            
            $where = array('email' => $email);    
        
        }else{

            $where = array('mobile_number' => $mobileNumber);    

        }

        if ($otp == config('constants.MASTER_OTP') && config('constants.APP_MODE') == "DEVELOPEMENT") {
            
        }else{
            
            $where['verification_code'] = $otp;            
        }
        
        $update = array('password' => $password);

        $check = Users::where($where)
                    ->whereIn('user_type', ['Driver', 'Passenger'])
                    ->update($update);

        if ($check) {
            
            $res['success'] = true;
            $res['msg']     = trans('Api::language.LANG_YOUR_PASSWORD_HAS_BEEN_RESET_SUCCESSFULLY');
            $res['code']    = 200;
        }
        
        return Response::json($res);
    }

    public function verify_mobile(Request $request){

        //Validate request
        $vArray = [

            "verification_code" => 'required'
        ];

        check_validation($request, $vArray);

        $res = array("success" => false, "msg" => trans('Api::language.LANG_FAILED_TO_VERIFY_MOBILE_NUMBER'), 'code' => 400);

        //Get user
        $user = $request->get('user');
        
        //Variables
        $otp        = $request->get('verification_code');
               
        if ($user->verification_status == "Verified") {
            
            $res['msg']     = trans('Api::language.LANG_YOUR_MOBILE_NUMBER_IS_ALREADY_VERIFIED');            

        }else{

            //Condition

            if ($otp == config('constants.MASTER_OTP') && config('constants.APP_MODE') == "DEVELOPEMENT") {
                
                $where = array(
                            'id' => $user->id, 
                            'mobile_verification_status' => 'NotVerified'
                        );    
                
                $update = array('mobile_verification_status' => 'Verified');

            }else{

                $where = array(
                            'id'                            => $user->id, 
                            'mobile_verification_status'    => 'NotVerified', 
                            'mobile_verification_code'      => $otp
                        );

                $update = array('mobile_verification_status' => 'Verified');
            }           

            $check = Users::where($where)->update($update);
            
            if ($check) {
                
                $res['code'] = 200;    
                $res['success'] = true;
                $res['msg']     = trans('Api::language.LANG_YOUR_MOBILE_NUMBER_HAS_BEEN_VERIFIED_SUCCESSFULLY');

            }else{

                $res['msg']     = trans('Api::language.LANG_INCORRECT_VERIFICATION_CODE_PLEASE_TRY_AGAIN');

            }

        }

        return Response::json($res);

    }

    function resendVerificationCode(Request $request){

        $res = array("success" => false, 
                    "msg" => trans('Api::language.LANG_FAILED_TO_SEND_VERIFICATION_CODE'), 
                    'code' => 400);

        //Get user
        $user           = $request->get('user');

        //Variables
        $randomCode = random_code();
        $mobileNumber   = $user->mobile_number;

        $where = array('id' => $user->id);

        $update = array('mobile_verification_code' => $randomCode);

        $check = Users::where($where)->update($update);

        if ($check) {

            //SMS
            $message = "Please use OTP: $randomCode to verify your mobile number, Eco Gadi";
            send_sms($mobileNumber,$message);

            $res['success'] = true;
            $res['msg']     = trans('Api::language.LANG_WE_HAVE_SENT_YOU_OTP_ON_MOBILE');
            $res['code']    = 200;
            $res['result']  = $randomCode;

        }
        return Response::json($res);

    }

    public function changeMobileNumber(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_CHANGE_MOBILE_NUMBER');
        $res['code']    = 400;          
        
        $user = $request->get('user');

        $vArray = [
            
            'mobile_number'  => 'required|unique:users,mobile_number,'.$user->id,
            
        ];

        //Validate request
        check_validation($request, $vArray);

        $randomCode     = random_code();
        $mobileNumber   = $request->get('mobile_number');

        $where = array('id' => $user->id);

        $data = array(
            'mobile_number'              => $request->get('mobile_number'),            
            'mobile_verification_code'   => $randomCode,            
            'mobile_verification_status' => "NotVerified",            
        );

        $check = Users::where($where)->update($data);
        
        if ($check) {

            //SMS
            $message = "Please use OTP: $randomCode to verify your mobile number, Eco Gadi";
            send_sms($mobileNumber,$message);

            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_WE_HAVE_SENT_YOU_OTP_ON_MOBILE');
            $res['code']    = 200;
                        
        }
        
        return Response::json($res);

    }

    /*
    * Name: socialLogin
    * Create Date: 22 March 2017
    * Purpose: This is used to login and register social login.
    */

    public function socialLogin(Request $request){
                          
        $vArray = [
            //'first_name'    => 'required',
            //'last_name'     => 'required',
            'mobile_number' => 'integer|digits:10',
            'email'         => 'email',
            'device_type'   => 'required|in:android,iphone,web',
            'user_type'     => 'required|in:Passenger',
            'social_id'     => 'required',
        ];

        //Validate request
        check_validation($request, $vArray);        

        $res = array("success" => false, "msg" => trans('Api::language.LANG_FAILED_TO_REGISTER'), "code" => 400);

        //Variables
        $socialId = $request->get('social_id');

        $checkExists = User::where(array('social_id' => $socialId))->first();
        
        if (!empty($checkExists)) {
            
            if ($checkExists->status == '0') {
                
                $res['success'] = false;
                $res['code']    = 400;
                $res['msg']     = trans('Api::language.LANG_YOUR_ACCOUNT_IS_INACTIVE_PLEASE_CONTACT_ADMIN');

            }else{

                $token = JWTAuth::fromUser($checkExists);                

                $checkExists->notification_count = Notification::where(['user_id' => $checkExists->id, 'read_status' => '0'])
                    ->count();

                //Update Device info
                $this->update_device_info($request, $checkExists->id, $token);

                $userInfo           = User::where(array('social_id' => $socialId))->first();
                $userInfo->token    = $token;

                $res['success'] = true;
                $res['code']    = 200;
                $res['result']  = $userInfo; 
                $res['msg']     = trans('Api::language.LANG_LOGIN_SUCCESSFULLY');
            }            


        }else{
            
            $res['success'] = true;
            $res['code']    = 300;
            $res['msg']     = trans('Api::language.LANG_USER_NOT_REGISTERED_PLEASE_REDIRECT_TO_SIGNUP');           
        }

        return Response::json($res);

    }

    /*
    * Name: passengerRegister,
    * Create Date: 22 March 2018
    */

    public function passengerRegister(Request $request){         
        
        $vArray = [
            'first_name'    => 'required',
            'last_name'     => 'required',                   
            'country_code'  => 'required',                   
            'mobile_number' => 'required|unique:users|integer|digits:10',
            'email'         => 'email|unique:users',
            'device_type'   => 'required|in:android,iphone,web',            
            'login_type'    => 'required|in:1,2,3'
        ];

        if ($request->get('social_id') == "") {

            $vArray['password'] = 'required';
        }

        //Validate request
        check_validation($request, $vArray);
        
        $res = array("success" => false, "msg" => trans('Api::language.LANG_FAILED_TO_REGISTER'), "code" => 400);
        
        //Variables
        $firstName      = $request->get('first_name');      
        $lastName       = $request->get('last_name');
        $gender         = $request->get('gender');
        $countryCode    = $request->get('country_code');
        $mobileNumber   = $request->get('mobile_number');
        $email          = $request->get('email');
        $password       = bcrypt($request->get('password'));
        $deviceId       = $request->get('device_id');
        $deviceType     = $request->get('device_type');
        $userType       = "Passenger";
        $notificationId = $request->get('notification_id');
        $appVersion     = $request->get('app_version');
        $socialId       = $request->get('social_id');
        $loginType      = $request->get('login_type');
        $ip             = $request->get('ip');
        $browser        = $request->get('browser');
        

        $data = array(

            "first_name"                 => $firstName,
            "last_name"                  => $lastName,
            "gender"                     => $gender,
            "country_code"               => $countryCode, 
            "mobile_number"              => $mobileNumber, 
            "email"                      => $email, 
            "device_id"                  => $deviceId, 
            "device_type"                => $deviceType,
            "notification_id"            => $notificationId,
            "app_version"                => $appVersion,
            "user_type"                  => $userType,
            "login_type"                 => $loginType,
            "mobile_verification_status" => "NotVerified",
            "mobile_verification_code"   => random_code(),
            "created_at"                 => date('Y-m-d H:i:s')
        );      
        
        if ($socialId == "") {

            $data['password'] = $password;
        
        }else{
            
            $data['social_id'] = $socialId;

        }

        //Check user already registered

        $checkExists = User::where(['mobile_number' => $mobileNumber])->first(['id']);

        if (!empty($checkExists)) {
            
            $checkUpdate = User::where('id', $checkExists->id)
                            ->update($data);

            if ($checkUpdate) {

                $check = $checkExists->id;
            }
            
        }else{

            $check = User::insertGetId($data);           
        }

        
        if ($check) {
            
            //Remove notification id if other user have it.
            User::where(array('notification_id' => $notificationId))->update(array('notification_id' => ''));
            
            $user = User::where('id', $check)->first();            

            $token = JWTAuth::fromUser($user);

            $user->token = $token;
            
            $user->notification_count = 0;

            //Update Device info
            $this->update_device_info($request, $user->id, $token);

            //Send Mail
            if ($email != "") {
                
                $emailData['email'] = $email;
                $emailData['name']  = $firstName.' '.$lastName;

                Mail::send('email.welcome', $emailData, function ($message) use($emailData){
                    $message->to($emailData['email'])
                            ->subject('Welcome to Eco Gadi!');            
                });
            }

            $res['result']  = $user;
            $res['success'] = true;
            $res['msg']     = trans('Api::language.LANG_YOU_HAVE_REGISTERED_WITH_US_SUCCESSFULLY');
            $res['code']    = 200;

        }

        return Response::json($res);
    }

    function online_status($userId){
        
        $res = false;

        $check = DriverInfo::where(array('driver_id' => $userId))->update(['online_status' => 'Online']);

        if ($check) {
            
            $res = true;

        }

        return $res;
    }

    public function changeOnlineStatus(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_CHANGE_ONLINE_STATUS');
        $res['code']    = 400;          
        
        $vArray = [
            
            'online_status'  => 'required|in:Online,Offline',
            
        ];

        //Validate request
        check_validation($request, $vArray);

        $user = $request->get('user');

        $onlineStatus = $request->get('online_status');

        $check = DriverInfo::where(array('driver_id' => $user->id))
                    ->update(['online_status' => $onlineStatus]);
        
        if ($check) {

            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_ONLINE_STATUS_UPDATED');
            $res['code']    = 200;
                        
        }
        
        return Response::json($res);

    }

    /*
    * Name: logout
    * Create Date: 18 Apr 2018
    * Purpose: This function is used to logout user
    */

    public function logout(){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_LOGOUT');
        $res['code']    = 400;          
        
        $vArray = [

            'driver_id' => 'required'                        
        ];

        $user = $request->get('user');
        
        $check = DriverInfo::where('driver_id', $request->get('driver_id'))
                    ->update('online_status', 'Offline');        
        
        if ($check) {

            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_LOGOUT_SUCCESSFULLY');
            $res['code']    = 200;
                        
        }
        
        return Response::json($res);        
    }
}
