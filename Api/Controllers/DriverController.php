<?php

namespace App\Modules\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Users,DrivingLicense,Ride,Configuration,RideRequest,DriverInfo,Charges, RidePromocode};

use Response, Image;

/*
* Name: ApiController
* Create Date: 16 March 2018
* Purpose: This function is used for driver 
*/

class DriverController extends Controller
{

	/*
	* Name: uploadLicense
	* Create Date: 20 March 2018
	* Purpose: This function is used to upload driver license
	*/

	public function uploadLicense(Request $request){

		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_UPDATE_LICENSE_INFO');
        $res['code']    = 400;         

        $vArray = [           
        	'license_number'	=> 'required',    
        	'vehicle_type'		=> 'required',
        	'license_image'		=> 'mimes:jpeg,jpg,png|max:50240',        	
        	'issued_on'			=> 'required',
        	'expiry_date'		=> 'required',

        ];

        //Validate request
        check_validation($request, $vArray);

        $user = $request->get('user');
        
        $where = array('driver_id' => $user->id);
		
        $data = array(
        	"license_number" 	=> $request->get('license_number'),
        	"vehicle_type" 		=> $request->get('vehicle_type'),
        	"issued_on" 		=> $request->get('issued_on'),
        	"expiry_date" 		=> $request->get('expiry_date'),
        	"created_at" 		=> date('Y-m-d'),
        );

		$file = $request->file('license_image');

		if($file && $file != ""){

          $license_image  = getTimeStamp().".".$file->getClientOriginalExtension();

          Image::make($file)->resize(200, 200)->save('public/uploads/license_image/thumbnail/'.$license_image);

          $file->move('public/uploads/license_image', $license_image);
          
          $data['license_image']  = $license_image;

          //Remove old license image
          $driving_license = DrivingLicense::where($where)->first(['license_image']);
        	
          if (!empty($driving_license)) {
          	
          	delete_image("public/uploads/license_image/$driving_license->license_image");
          	delete_image("public/uploads/license_image/thumbnail/$driving_license->license_image");

          }

        }

		$check = DrivingLicense::updateOrCreate($where, $data);    
        
        if ($check) {
            
            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_DRIVING_LICENSE_INFO_UPDATED');        
            $res['code']    = 200;            
        }

        return Response::json($res);
	}

	/*
	
	 Purpose: This function is used to accept trip request by the driver
	*/

	public function acceptRideRequest(Request $request){
        
		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_ACCEPT_REQUEST');
        $res['code']    = 400;         

        $vArray = [ 

        	'ride_id'	=> 'required'
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 	= $request->get('user');		
        $rideId = $request->get('ride_id');		
		
        //Check driver has assign vehicle and vehicle is active
        $checkVehicle = $this->check_vehicle($user->id);
            
        if ($checkVehicle['success']) {
            
            $vehicledata = $checkVehicle['result'];

            $whereRideRequest = array('ride_id'         => $rideId, 
                                      'driver_id'       => $user->id,                                    
                                      'request_status'  => 'Pending');

            $checkRequest = RideRequest::where($whereRideRequest)
                                ->update(['request_status' => 'Accepted']);
            
            if ($checkRequest) {
                
                $where = array('rides.id'       => $rideId,
                               'ride_status'    => 'Pending');

                //Variables
                $OTP = random_code();
                
                $update = array('ride_status'  => 'Accepted',    
                                'vehicle_id'   => $vehicledata->id,
                                'driver_id'    => $user->id,
                                'vehicle_name' => $vehicledata->name,
                                'otp'          => $OTP);

                $checkAccept = Ride::where($where)->update($update);

                if ($checkAccept) {
                    
                    $driverName = ucfirst($user->first_name.' '.$user->last_name);
                    
                    $getRideInfo = Ride::where(array('rides.id' => $rideId))->first(['passenger_id']);

                    //Notification
                    $message = "Great! $driverName has been accepted your ride request, he will pick you as soon as";

                    $body   = array("type" => "RIDE_ACCEPTED", 
                                "message"  => $message,
                                "ride_id"  => $rideId,
                            );
                
                    $title  = "Ride Accepted";
                
                    $userArray[] = $getRideInfo->passenger_id;        
                
                    send_notification($userArray, $body, $title);

                    
                    //get passenger info
                    $passengerInfo = Users::where(array('id' => $getRideInfo->passenger_id))->first(['country_code','mobile_number']);

                    $countryCode    = $passengerInfo->country_code;
                    $mobileNumber   = $passengerInfo->mobile_number;
                    /*$mobileNumber   = "9752174241";*/

                    //send otp to passenger
                    $message = "Great! Driver($driverName) of $vehicledata->number_plate has been accepted your ride and please share this OTP: $OTP with the driver so he can start a ride, enjoy your ride!.";

                    send_sms($mobileNumber,$message);

                    $res['success'] = true;     
                    $res['msg']     = trans('Api::language.LANG_DRIVER_HAS_ACCEPTED_YOUR_RIDE_REQUEST');       
                    $res['code']    = 200;

                }
            
            }
            
        }else{

            $res['success'] = false;
            $res['msg']     = $checkVehicle['msg'];
            $res['code']    = 400; 
        }               

		return Response::json($res);
	}

	/*
	
	* Purpose: this function is used to reject ride request
	*/

	public function rejectRideRequest(Request $request){

		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_REJECT_REQUEST');
        $res['code']    = 400;         

        $vArray = [ 

        	'ride_id'	=> 'required'
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 	= $request->get('user');		
        $rideId = $request->get('ride_id');		
		
		$where = array('rides.id' => $rideId, 'ride_status' => 'Pending');
		
		$whereRideRequest = array('ride_id' => $rideId, 
								'driver_id' => $user->id,
								'request_status' => 'Pending');

		$checkRequest = RideRequest::where($whereRideRequest)->update(['request_status' => 'Cancelled']);

		if ($checkRequest) {

			$res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_YOU_HAVE_REJECTED_RIDE_REQUEST');        
            $res['code']    = 200;
			
		}

		return Response::json($res);		
	}

    /*
    * Name: startRide
    * Create Date: 21 March 2018
    * Purpose: This function is used to start ride
    */

    public function startRide(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_START_RIDE');
        $res['code']    = 400;         

        $vArray = [ 

            'ride_id'       => 'required',            
        ];

        //Validate request
        check_validation($request, $vArray);

        $user        = $request->get('user');        
        $rideId      = $request->get('ride_id');           
        
        $where = array('rides.id' => $rideId, 'ride_status' => 'Accepted');
        
        $update = array('ride_status' => 'Running','start_ride_date' => date('Y-m-d H:i:s'));

        $checkRide = Ride::where($where)->update($update);

        if ($checkRide) {

            $driverName = ucfirst($user->first_name.' '.$user->last_name);

            $getRideInfo = Ride::where(array('rides.id' => $rideId))->first(['passenger_id']);

            //Notification
            $message = "Great! $driverName has started your ride, enjoy your ride!";

            $body   = array("type" => "START_RIDE", 
                        "message"  => $message,
                        "ride_id"  => $rideId,
                    );
        
            $title  = "Ride Started";
        
            $userArray[] = $getRideInfo->passenger_id;        
        
            send_notification($userArray, $body, $title);

            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_RIDE_HAS_BEEN_START');        
            $res['code']    = 200;
            
        }else{

            $checkCancelled = Ride::where(['id' => $rideId, 'ride_status' => 'Cancelled'])->first(['id']);
            
            if (!empty($checkCancelled)) {
                
                $res['success'] = false;     
                $res['msg']     = trans('Api::language.LANG_RIDE_HAS_BEEN_CANCELLED');        
                $res['code']    = 400;   
                $res['result']  = "Cancelled";   

            }
        }

        return Response::json($res);
    }

    /*
    * Name: check ride otp
    * Create Date: 01 June 2018
    * Purpose: This function is used to check otp before start ride
    */
    public function checkRideOtp(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_YOU_HAVE_ENTER_INCORRECT_OTP');
        $res['code']    = 400;         

        $vArray = [ 

            'ride_id'   => 'required',
            'otp'       => 'required',
        ];

        //Validate request
        check_validation($request, $vArray);

        $user   = $request->get('user');        
        $rideId = $request->get('ride_id');     
        $OTP    = $request->get('otp');

        if($OTP == config('constants.MASTER_OTP')){

            $where = array('rides.id' => $rideId, 'ride_status' => 'Accepted');
        }else{

            $where = array('rides.id' => $rideId, 'ride_status' => 'Accepted','otp' => $OTP);
        }
        

        $checkOTP = Ride::where($where)->first(['id']);

        if ($checkOTP) {

            //update start ride status
            Ride::where($where)->update(['start_ride_status' => 1]);
            
            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_RIDE_HAS_BEEN_START');        
            $res['code']    = 200;
            
        }

        return Response::json($res);        
    }

    /*
    * Name: completeRide
    * Create Date: 21 March 2018
    * Purpose: This function is used to complete ride
    */

    public function completeRide(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_COMPLETE_RIDE');
        $res['code']    = 400;         

        $vArray = [ 

            'ride_id'           => 'required',
            'actual_distance'   => 'required',
            'actual_time'       => 'required'            
        ];

        //Validate request
        check_validation($request, $vArray);

        $user                   = $request->get('user');        
        $rideId                 = $request->get('ride_id');     
        $actualDistance         = $request->get('actual_distance');     
        $actualTime             = $request->get('actual_time');             
        $waitingTime            = $request->get('waiting_time');    
        $waitingCharges         = $request->get('waiting_charges');  
        $ridePromocodeId        = $request->get('ride_promocode_id');
        $rideRentalPackageId    = $request->get('ride_rental_package_id'); 
        
        $where = array('rides.id' => $rideId, 'ride_status' => 'Running');
        
        $update = array('ride_status'       => 'Completed',
                        'actual_distance'   => $actualDistance,
                        'actual_time'       => $actualTime,                        
                        "waiting_time"      => $waitingTime,
                        "end_ride_date"     => date('Y-m-d H:i:s')
                    );     

        $fareInfo = calculate_fare($actualDistance,$waitingTime,$rideRentalPackageId,$ridePromocodeId);          

        $update['fare']                 = $fareInfo['fare'];
        $update['promocode_charges']    = $fareInfo['promocode_charges'];
        $update['waiting_charges']      = $fareInfo['waiting_charges'];
        $update['total_amount']         = $fareInfo['total_amount'];
        $update['gst_amount']           = $fareInfo['gst_amount'];
        $update['pay_amount']           = $fareInfo['pay_amount'];        
        
        $checkRide = Ride::where($where)->update($update);

        if ($checkRide) {

            $rideInfo = Ride::where(array('id' => $rideId))->first();

            $driverName = ucfirst($user->first_name.' '.$user->last_name);

            $getRideInfo = Ride::where(array('rides.id' => $rideId))->first(['passenger_id']);

            //Notification
            $message = "Great! Your ride has been completed, Thanks for riding with us!";

            $body   = array("type"      => "COMPLETE_RIDE", 
                            "message"   => $message,
                            "ride_id"   => $rideId,
                        );
        
            $title  = "Ride Completed";
        
            $userArray[] = $getRideInfo->passenger_id;        
        
            send_notification($userArray, $body, $title);

            RidePromocode::where('id', $ridePromocodeId)->update(['ride_id' => $rideId]);

            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_RIDE_HAS_BEEN_COMPLETED');        
            $res['code']    = 200;
            $res['result']  = $rideInfo;
            
        }

        return Response::json($res);
    }

    /*
    * Name: cashCollect
    * Create Date: 2 Apr 2018   
    * Purpose: This function is used to collect cash by driver
    */

    public function cashCollect(Request $request){
        
        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_COLLECT_CASH');
        $res['code']    = 400;         

        $vArray = [ 

            'ride_id'  => 'required',
        ];

        //Validate request
        check_validation($request, $vArray);

        $user   = $request->get('user');              
        $rideId = $request->get('ride_id');              
        
        $where = array('rides.id' => $rideId);
        
        $update = array('payment_status' => '1');
        
        $checkRide = Ride::where($where)->update($update);

        if ($checkRide) {

            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_CASH_COLLECTED');        
            $res['code']    = 200;
            
        }

        return Response::json($res);

    }
    
    /*
    * Name: reached
    * Create Date: 11 Apr 2018
    */

    public function reached(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_NOTIFY_PASSENGER');
        $res['code']    = 400;         

        $vArray = [ 

            'ride_id'  => 'required',
        ];

        //Validate request
        check_validation($request, $vArray);

        $user   = $request->get('user');              
        $rideId = $request->get('ride_id');              
        
        $driverName = ucfirst($user->first_name.' '.$user->last_name);
                    
        $getRideInfo = Ride::where(array('rides.id' => $rideId))->first(['passenger_id']);

        Ride::where('id', $rideId)->update(['driver_reach_n_status' => '1']);
        
        //Notification
        $message = "Great! $driverName has reached nearby by your pickup point, enjoy your ride!";

        $body   = array("type" => "DRIVER_REACHED", 
                    "message"  => $message,
                    "ride_id"  => $rideId,
                );
    
        $title  = "Driver Reached";
    
        $userArray[] = $getRideInfo->passenger_id;        
    
        send_notification($userArray, $body, $title);
        
        $res['success'] = true;     
        $res['msg']     = trans('Api::language.LANG_NOTIFIED_PASSENGER');        
        $res['code']    = 200;                    

        return Response::json($res);        
    }

    
    /*
    * Name: updateLatLong
    * Create Date: 13 Apr 2018
    * Purpose: This function is used to update driver lat long
    */

    public function updateLatLong(Request $request){

        $res['success'] = false;
        $res['code']    = 400;         

        $vArray = [ 

            'latitude'   => 'required',
            'longitude'  => 'required',
        ];

        //Validate request
        check_validation($request, $vArray);

        $user       = $request->get('user');              
        $latitude   = $request->get('latitude');             
        $longitude  = $request->get('longitude');             
        
        $update = array('latitude' => $latitude,'longitude' => $longitude);

        $check = Users::where(array('id' => $user->id))->update($update);
        
        $res['success'] = true;                     
        $res['code']    = 200;                    

        return Response::json($res);

    }

    public function notAcceptedReason(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_NOT_ACCEPTED_REASON');
        $res['code']    = 400;         

        $vArray = [ 

            'ride_id'       => 'required',            
        ];

        //Validate request
        check_validation($request, $vArray);

        $user                   = $request->get('user');        
        $rideId                 = $request->get('ride_id');
        $notAcceptedReason      = $request->get('not_accepted_reason');           
        
        $where = array('ride_id' => $rideId, 'driver_id' => $user->id);
        
        $update = array('not_accepted_reason' => $notAcceptedReason);

        $checkRide = RideRequest::where($where)->update($update);

        if ($checkRide) {
            
            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_NOT_ACCEPTED_REASON');        
            $res['code']    = 200;

        }       

        return Response::json($res);
    }


    /***************************************************************************************************/

    function check_vehicle($driverId){

        $res = array("success" => false);

        $getVehicle = DriverInfo::where(['driver_id' => $driverId])
            ->has('assigned_vehicle')
            ->with('assigned_vehicle')
            ->first();
        
        if (!empty($getVehicle)) {
            
            //Check driver status is online
            if ($getVehicle->online_status == "Offline") {
                
                $res['msg'] = "Oops! Failed to accept ride request, you are not online please turn on your online status";
            }else{

                if ($getVehicle->assigned_vehicle->status == '0') {
                  
                  $res['msg'] = "Oops! Failed to accept ride request, your vehicle is not active, please contact to staff or admin";  

                }else{

                    $res['success'] = true;
                    $res['result']  = $getVehicle->assigned_vehicle;
                }
            }

        }else{

            $res['msg'] = "Oops! Failed to accept ride request, unfortunaltely there is no vehicle assign to you";
        }

        return $res;            
    }


}
