<?php

namespace App\Modules\Web\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Content,Users,Country,State,City,Configuration,Feedback};
use Response,Session,Redirect,Socialite;


class WebController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /*
    * Name: Front 
    * Create Date: 15 May 2018
    */
    public function index()
    {   
        return view("Web::layout.index");
    }

    public function userLogin(Request $request){

        $data['title'] = "Login";
        
        return view("Web::layout.login",$data);
    }

    public function userRegistration()
    {   
        $data['title']      = 'Registration';
        $data['login_type'] = '3';
        return view("Web::layout.registration",$data);
    }

    public function mobileVerification($userId,Request $request)
    {   
      
        $data['userData'] = Users::where(array('id' => $userId))->first();
        $data['title']    = "Mobile Verification";
        return view("Web::layout.mobile_verification",$data);
    }

    public function changeMobileNumber(Request $request)
    {   
      
        $data['title'] = "Change Mobile Number";

        return view("Web::layout.change_mobile_number",$data);
    }

     public function forgotPassword(Request $request)
    {   
      
        $data['title'] = "Forgot Password";

        return view("Web::layout.forgot_password",$data);
    }

    public function rideNow(Request $request)
    {   
      
        $data['title'] = "Ride Now";

        return view("Web::detail.ride_now",$data);
    }

    

     public function requestRide(Request $request)
    {   
      
        $data['title'] = "Ride Now";

        return view("Web::detail.request_ride",$data);
    }

    /*
    * Name: set session 
    * Create Date: 16 May 2018
    * Purpose: set session after login.
    */
    public function setSession(Request $request){
      
        $res = array('success' => 0,'msg' => 'something went to wrong');
        $info = $request->get('user_info');


        if($info != ""){        

            //set session
            $sessionVariable = array('set_token'        => $info['token'],
                                'id'                    => $info['id'],
                                'm_verification_status' => $info['mobile_verification_status'],
                                'email'                 => $info['email']);

            $request->session()->put($sessionVariable);

        // destory cart session
        //Session::flash('cart_items', '');

        //print_r(Session::get('set_token'));die;
            $res = array('success' => 1,'msg' => 'sesssion set successfully','mobile_status' => $info['mobile_verification_status']);
        }
        
         return Response::json($res);
    }
    
    /*
    * Name: logout 
    * Create Date: 16 may 2018
    * Purpose: 
    */
    public function Logout(){

        Session::flush();
        return Redirect::to('/web');
    }

    /*
    * Name: update session 
    * Create Date: 16 May 2018
    * Purpose: update session after login.
    */
    public function updateSession(Request $request){

        $res = array('success' => 0,'msg' => 'something went to wrong');

            //set session
            $getStatus = $request->get('status');

            if($getStatus == "NotVerified"){

              $request->session()->put(['m_verification_status' => 'NotVerified']);

            }else{

              $request->session()->put(['m_verification_status' => 'Verified']);
            }

            $res = array('success' => 1,'msg' => 'sesssion update successfully','update_status' => Session::get('m_verification_status'));
        
         return Response::json($res);
    }


     /*
    * Name: social login
    * Create Date: 16 May 2018
    * Purpose:  
    */
    public function redirectToProvider($provider)
    {   
        return Socialite::driver($provider)->redirect();
        
    }
    public function handleProviderCallback($provider,Request $request)
    {        
        $user = Socialite::driver($provider)->stateless()->user();
        
        if(!empty($user)){

            $name = explode(' ', $user->name);

            // Create customer data array
            $customerData = array(

                "first_name"                    => $name[0],
                "last_name"                     => $name[1],
                "email"                         => $user->email,
                "user_type"                     => "Passenger",
                "device_type"                   => "web",
                "social_id"                     => $user->id
            );
            //d($customerData);
            $request->merge($customerData);
            $result = App()->call('App\Modules\Api\Controllers\AuthController@socialLogin');

            $resultData = $result->getData();
            
            if($resultData->code == '300'){

                $data['first_name']     = $name[0];
                $data['last_name']      = $name[1];
                $data['email']          = $user->email;
                $data['social_id']      = $user->id;
                
                if($provider == "facebook"){

                    $provider = '2';

                }else{
                    
                    $provider = '3';
                }

                $data['login_type']     = $provider;
                
                return view("Web::layout.registration",$data);
            }
            else if($resultData->code == '200'){

                //set session
                $sessionVariable = array('set_token' => $resultData->result->token,
                                        'id'     => $resultData->result->id,
                                        'mobile_verification_status' => $resultData->result->mobile_verification_status,
                                        'email' => $resultData->result->email);

                $request->session()->put($sessionVariable);
                
                $user_redirect_url = Session::get('user_redirect_url');

                if ($user_redirect_url != "") {

                    return Redirect::to($user_redirect_url);

                }else{

                    return Redirect::to('/web');                
                }
            }

        }else{

            return view("Web::layout.login");
        }   
    }


    public function getContent(Request $request, $type)
    {

        $data['info'] = Content::where('type', $type)->first();

        return view("Web::detail.content", $data);
    }

    
    public function getProfile(Request $request){


        $id = Session::get('id');

        $field = array('first_name','last_name','mobile_number','profile_image','gender','country_code','email','users.country_id','users.state_id','users.city_id','country_name','state_name','city_name','address');

        $data['info'] = Users::where(array('users.id' => $id))
                            ->leftjoin('country','users.country_id','=','country.id')
                            ->leftjoin('states','users.state_id','=','states.id')
                            ->leftjoin('city','users.city_id','=','city.id')
                            ->first($field);

        $data['country']    = Country::where(array('status' => 'Active'))
                                      ->select('id','country_name')->get();             

        //print_r($data['info']);die;
        return view("Web::detail.get_profile",$data);       

    }

  
  public function requestNow(Request $request){

    $data['pickupAddress'] = $request->get('pickupAddress');
    $data['dropupAddress'] = $request->get('dropupAddress');
    $data['ride_type']     = $request->get('ride_type');

//print_r($data);die;
    return view("Web::detail.request_ride",$data);
  }
  
  /*
  * Name:Notification
  * Create Date: 30 May 2018
  * Purpose: 
  */
  public function notification(Request $request){
    
    $data['title'] = "Notification";

    return view("Web::detail.notification",$data);

  }

  /*
  * Name:Ride Histroy
  * Create Date: 31 May 2018
  * Purpose: 
  */
  public function rideHistory(Request $request){

    return view("Web::detail.ride_history");
  }

  /*
  * Name:Ride detail
  * Create Date: 31 May 2018
  * Purpose: 
  */
  public function rideDetail($rideId,Request $request){

    $data['rideId'] = $rideId;
    $data['title']  = "Ride Detail";
    $data['gst']    = Configuration::where(array('type' => 'gst'))->first(['value','type']);
        
    $data['feedback'] = Feedback::where(['ride_id' => $rideId ])->first(['id','rating']);
    //dd($data['feedback']);die; 
    return view("Web::detail.ride_detail",$data);
  }



}
