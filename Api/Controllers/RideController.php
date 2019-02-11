<?php

namespace App\Modules\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{DrivingLicense,Ride,RideRequest,Users,Charges,Feedback,AutoCaptureImages, RidePromocode,Promocode, RentalPackage, RideRentalPackage, Configuration};

use Response, Image, DB;

/*
* Name: RideController
* Create Date: 20 March 2018
 Purpose: This function is used for driver 
*/

class RideController extends Controller
{
	/*
	* Name: getCharges
	* Create Date: 24 March 2018
	* Purpose: This function is used to get charges 
	*/

	public function getCharges(Request $request){

		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_RECORD_NOT_FOUND');
        $res['code']    = 400;         

        $charges = Charges::first();
		
		if (!empty($charges)) {

			$res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_RECORD_FOUND');        
            $res['code']    = 200;
            $res['result']  = $charges;
			
		}

		return Response::json($res);
	}

	/*
	 create date: 20 March 2018
	* Purpose: this function is used to get ride detail for driver and passenger
	*/

	public function getRideDetail(Request $request){

		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_RIDE_DETAIL_NOT_FOUND');
        $res['code']    = 400;         

        $vArray = [ 

        	'ride_id'	=> 'required'
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 				= $request->get('user');		
        $rideId 			= $request->get('ride_id');		
        $ridePromocodeId 	= $request->get('ride_promocode_id');		
		
		$where = array('rides.id' => $rideId);

		$fields = ['rides.id', 'rides.passenger_id', 'rides.driver_id','rides.esti_distance', 'rides.esti_time', 'rides.actual_distance', 'rides.actual_time', 'rides.fare', 'rides.pickup_location','ride_rental_package_id', 'rides.dropoff_location', 'rides.ride_status', 'rides.created_at','rides.pickup_lat', 'rides.pickup_long', 'rides.dropoff_lat','rides.dropoff_long', 'rides.waiting_time','rides.waiting_charges','rides.promocode_charges','rides.vehicle_id', 'rides.vehicle_name','rides.gst_amount','rides.total_amount', 'rides.pay_amount','rides.ride_promocode_id','driver_reach_n_status','ride_type','ride_date_time','start_ride_date','end_ride_date',
			DB::Raw("(SELECT AVG(feedback.rating) FROM feedback WHERE feedback.driver_id = rides.driver_id) AS driver_avg_rating") 

		];
		
		$passengerFields = "id,first_name,last_name,email,country_code,profile_image,latitude,longitude,country_code,mobile_number";
		$driverFields 	= "id,first_name,last_name,email,country_code,profile_image,latitude,longitude,country_code,mobile_number";
		$feedbackFields = "id,ride_id,rating,feedback,created_at";

		$withArray = array("feedback:$feedbackFields");

		if ($user->user_type == "Driver") {
			
			$withArray[] = "passenger:$passengerFields";

		}else{

			$withArray[] = "driver:$driverFields";

		}
		
		$rideQuery = Ride::where($where)
						->with($withArray);

		if ($user->user_type == "Driver") {

			$fields[] = DB::Raw("
				(SELECT request_status  FROM ride_requests WHERE ride_id='$rideId' AND driver_id='{$user->id}' LIMIT 1) as ride_status_for_driver");
				
		}				
		
		$getRideDetail = $rideQuery->first($fields);
		
		

		if (!empty($getRideDetail)) {

			$res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_RIDE_DETAIL_FOUND');        
            $res['code']    = 200;
            $res['result']  = $getRideDetail;
           
						
		}

		return Response::json($res);		
	}

	/*
	* Name: getRides
	* Create Date: 21 March 2018
	* Purpose: This function is used to get rides for driver and passenger
	*/

	public function getRides(Request $request){

		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_RIDES_NOT_FOUND');
        $res['code']    = 400;         

        $vArray = [ 
        	'page'	=> 'required'
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 		= $request->get('user');		
        $fromDate 	= $request->get('from_date');		
        $toDate 	= $request->get('to_date');		
        
        // for pagination
        $per_page   = 10;
        $page       = intval($request->get('page'));
        $start_from = ($page - 1) * $per_page;

		$fields = ['rides.id', 'staff_id','passenger_id', 'driver_id','esti_distance', 'esti_time', 'actual_distance', 'actual_time', 'fare', 'pickup_location', 'dropoff_location', 'ride_status', 'rides.created_at', 'vehicle_id','vehicle_name','pay_amount','ride_type','ride_date_time','start_ride_date','end_ride_date'];
		
		$passengerFields 	= "id,first_name,last_name,email,country_code,profile_image";
		$driverFields 		= "id,first_name,last_name,email,country_code,profile_image";

		$query = Ride::query();
		
		//Filter by date
		if ($fromDate != "") {
			
			$query->whereDate('created_at', '>=', $fromDate);	
		}						

		if ($toDate != "") {
			
			$query->whereDate('created_at', '<=', $toDate);	
		}

		if ($user->user_type == "Passenger") {
			
			$where = array("passenger_id" => $user->id);			
			$query->with(["driver:$driverFields"]);
		
		}else{
			
			$where = array("driver_id" => $user->id);
			$query->with(["passenger:$passengerFields"]);

		}

		$getRides = $query->where($where)
				->whereIn('ride_status', array('Completed', 'Cancelled'))			
				->skip($start_from)
    			->take($per_page)
    			->orderBy('rides.id', 'DESC')
				->get($fields);

		if (count($getRides) > 0) {

			$total = Ride::where(array('passenger_id' => $user->id))
						  ->whereIn('ride_status', array('Completed', 'Cancelled'))->count();
			
			$res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_RIDES_FOUND');        
            $res['code']    = 200;
            $res['result']  = $getRides;
            $res['total'] 	= $total;
			
		}

		return Response::json($res);
	}

	/*
	* Name: createRide
	* Create Date: 21 March 2018
	* Purpose: This function is used to create ride
	*/

	public function createRide(Request $request){

		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_DRIVER_NOT_FOUND');
        $res['code']    = 400;         

        $vArray = [

        	'pickup_location'	=> 'required',
        	//'dropoff_location'	=> 'required',
        	'esti_distance'		=> 'required',
        	'esti_time'			=> 'required',
        	'esti_fare'			=> 'required',
        	'no_of_passenger'	=> 'required',
        	'payment_type'		=> 'required|in:1,2',
        	'ride_type'			=> 'in:Now,Later',       	
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 				= $request->get('user');		
		$passengerId 		= $user->id;
		$estiDistance 		= $request->get('esti_distance');		
		$estiTime 			= $request->get('esti_time');
		$estiFare 			= $request->get('esti_fare');		
		$pickupLocation 	= $request->get('pickup_location');
		$dropoffLocation 	= $request->get('dropoff_location');
		$paymentType 		= $request->get('payment_type');
		$noOfPassenger		= $request->get('no_of_passenger');

		$pickup_lat			= $request->get('pickup_lat');
		$pickup_long		= $request->get('pickup_long');
		$dropoff_lat		= $request->get('dropoff_lat');
		$dropoff_long		= $request->get('dropoff_long');
		$rideType			= $request->get('ride_type');
		$rideDateTime		= $request->get('ride_date_time');
		$ridePromocodeId    = $request->get('ride_promocode_id');		
		$rentalPackageJson	= json_decode($request->get('rental_package_json'));
			
		$ride = array(

			"passenger_id" 		=> $passengerId,
			"esti_distance" 	=> $estiDistance,
			"esti_time" 		=> $estiTime,
			"esti_fare" 		=> $estiFare,			
			"pickup_location" 	=> $pickupLocation,
			"dropoff_location" 	=> $dropoffLocation,
			"ride_status" 		=> "Pending",
			"payment_type"		=> $paymentType,
			"no_of_passenger"	=> $noOfPassenger,
			"pickup_lat"		=> $pickup_lat,
			"pickup_long"		=> $pickup_long,
			"dropoff_lat"		=> $dropoff_lat,
			"dropoff_long"		=> $dropoff_long,
			"ride_promocode_id" => $ridePromocodeId,
			"ride_type" 		=> $rideType,
			"ride_date_time" 	=> $rideDateTime,
			"created_at"		=> date('Y-m-d H:i:s')
		);
		
		//Check Any ride is pending/accepted/running
	
		if ($rideType == "Later") {
			
			$checkRide = Ride::where(array('passenger_id' => $user->id, 'ride_type' => 'Later'))
						->whereIn('ride_status', array('Pending', 'Accepted', 'Running'))
						->whereDate('rides.ride_date_time', date('Y-m-d'))			
						->first();
			
		}else{

			$checkRide = Ride::where(array('passenger_id' => $user->id,'ride_type' => 'Now'))
						->whereIn('ride_status', array('Pending', 'Accepted', 'Running'))
						->whereDate('rides.created_at', date('Y-m-d'))			
						->first();
		}
		

		if (empty($checkRide)) {
					
			//Ride Promocode
			$ridePromocodeData = ride_promocode($estiFare,$ridePromocodeId);		
			
			$promocodeCharges 	= 0;
			$promocodeId 		= "";

			if (!empty($ridePromocodeData)) {
				
				$promocodeCharges = $ridePromocodeData['amount'];
				$promocodeId 	  = $ridePromocodeData['promocode_id'];
			}

			//Calculate gst
			$getConfig = Configuration::where(['type' => 'gst'])->first(['value']);

			$gstAmount 		= 0;
			$totalAmount 	= $estiFare - $promocodeCharges;
			
			if (!empty($getConfig) && $getConfig->value != "") {
				
				$gstAmount = $totalAmount * $getConfig->value/100;			
			
			}

			$ride['promocode_charges'] 	= $promocodeCharges;
			$ride['total_amount'] 		= $totalAmount;
			$ride['gst_amount'] 		= $gstAmount;
			$ride['pay_amount'] 		= $totalAmount + $gstAmount;

			$rideId = Ride::insertGetId($ride);

			if ($rideId) {		
				
				if (!empty($rentalPackageJson)) {
					
					$rentalPackageJson->ride_id = $rideId;
					
					$this->ride_rental_package($rentalPackageJson);				
				}
				
				if ($rideType == "Later") {
					
					$res['msg']     = trans('Api::language.LANG_BOOKING_CONFIRMED_FOR_LATER');

				}else{
					
	            	$res['msg']     = trans('Api::language.LANG_BOOKING_CONFIRMED');        
				}

				$res['success'] = true;     
	            $res['code']    = 200;
	            $res['result']  = $rideId;
		            			
			}

		}else{
			
			$res['success'] = false;
	        $res['msg']     = trans('Api::language.LANG_THERE_IS_ANOTHER_RIDE_IS_RUNNING');
	        $res['code']    = 400;
		}


		return Response::json($res);
	}

	/*
	* Name: findDriver
	* Create Date: 24 March 2018
	
	*/

	public function findDriver(Request $request){

		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_DRIVER_NOT_FOUND');
        $res['code']    = 400;         

        $vArray = [

        	'ride_id'	=> 'required',
        	'latitude'	=> 'required',           	
        	'longitude'	=> 'required',           	
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 			= $request->get('user');		
		$rideId 		= $request->get('ride_id');
		$latitude 		= $request->get('latitude');
		$longitude 		= $request->get('longitude');

		$fields = array('users.id','users.first_name', 'users.last_name',
                    DB::Raw("(((acos(sin(($latitude*pi()/180)) * 
                        sin((`latitude`*pi()/180))+cos(($latitude*pi()/180)) * cos((`latitude`*pi()/180)) * cos((($longitude- `longitude`)* 
                        pi()/180))))*180/pi())*60*1.1515) as distance"),
                   );	

		//GET Configuration value for radius
		$getConfigurations = Configuration::where('type', 'new_ride_request_radius')->first(['value']);

		if (!empty($getConfigurations)) {
			
			$newRideRequestRadius = $getConfigurations->value;

		}else{

			$newRideRequestRadius = 50;
		}

		$drivers = Users::oftype('Driver')
				->join('driver_info', function($join){
					$join->on('driver_info.driver_id', '=', 'users.id')
					->where('online_status', 'Online');
				})
				->where(array('users.status' => '1'))
				->orderBy('distance', 'DESC')
				->having('distance', '<', $newRideRequestRadius)
				->having('distance', '!=', "")
				->get($fields);						
		
		if (!empty($drivers)) {
			
			$got = "no";

			foreach ($drivers as $key => $driver) {
				
				$checkRide = Ride::where(array('driver_id' => $driver->id))
					->whereDate('created_at', date('Y-m-d'))
					->whereIn('ride_status', array('Pending', 'Accepted', 'Running'))->first(['id']);

				if (empty($checkRide) && $got == "no") {
					
					$rideRequest = array(

						"ride_id" 			=> $rideId,
						"request_status"	=> "Pending",
						"driver_id"			=> $driver->id,
						"created_at"		=> date('Y-m-d H:i:s')
					);

					$checkRequestReject = RideRequest::where(array('ride_id' => $rideId, 
											 'driver_id' => $driver->id, 
											 'request_status' => 'Cancelled'))->first();
					
					if (empty($checkRequestReject)) {
							
						$check = RideRequest::insertGetId($rideRequest);				
				        //Notification
				                
				        if ($driver->id) {
				          
				          $rideInfo = Ride::where('id', $rideId)->first(['id as ride_id', 'passenger_id', 'driver_id', 'staff_id', 'pickup_location', 'dropoff_location', 'esti_distance', 'esti_time', 'no_of_passenger', 'payment_type', 'ride_status', 'esti_fare', 'pickup_lat', 'pickup_long', 'dropoff_lat', 'dropoff_long', 'created_at']);

				          $message = "Great! A new ride has been assign to you.";

				          $body   = array("type"  => "PASSENGER_ASSIGN_RIDE", 
				                      "message"   => $message,
				                      "ride_id"   => $rideId,
				                      "json"      => $rideInfo
				                  	);
				      
				          $title  = "New Ride Request";

				          $userArray = array($driver->id);        
				      
				          send_notification($userArray, $body, $title);

				        }

				        if ($check) {
				        	
				        	$got = "yes";

				        	break;
				        
				        }
					}

				}
				
			}
			
			if ($got == "yes") {
				
				$res['success'] = true;     
	            $res['msg']     = trans('Api::language.LANG_A_DRIVER_HAS_BEEN_FOUND');        
	            $res['code']    = 200;
			}	
		}		
		
		return Response::json($res);		
	}

	/*
	* Name: cancelRequest
	* Create Date: 24 March 2018
	*/

	public function cancelRequest(Request $request){

		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_CANCEL_REQUEST');
        $res['code']    = 400;         

        $vArray = [

        	'ride_id'	=> 'required',      	        	        	
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 			= $request->get('user');		
		$rideId 		= $request->get('ride_id');
		
		$checkRide = Ride::where(array('id' => $rideId, 'ride_status' => 'Pending'))
			->first(['id']);

		if (count($checkRide) > 0) {
			
			$whereRideRequest = array('ride_id' => $rideId);

			$check = RideRequest::where($whereRideRequest)->update(array('request_status' => 'Cancelled'));

			if ($check) {
				
	          	$rideInfo = Ride::where('id', $rideId)
	          		->join('ride_requests', 'ride_requests.ride_id', '=', 'ride.id')
	          		->first(['ride_requests.driver_id']);

				//Send Notification to driver
				if (isset($rideInfo->driver_id)) {

		          $message = "A passenger has been canelled ride.";

		          $body   = array("type"  => "RIDE_CANCELLED_BY_PASSENGER", 
		                      "message"   => $message,
		                      "ride_id"   => $rideId,
		                  	);
		      
		          $title  = "Ride Cancelled";

		          $userArray = array($rideInfo->driver_id);        
		      
		          send_notification($userArray, $body, $title);

		        }

				$res['success'] = true;     
	            $res['msg']     = trans('Api::language.LANG_RIDE_REQUEST_HAS_BEEN_CANCELLED');        
	            $res['code']    = 200;

			}
		
		}else{

			$res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_RIDE_IS_NOT_IN_PENDING');        
            $res['code']    = 200;

		}

		return Response::json($res);		
	}

	public function cancelBooking(Request $request){

		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_CANCEL_BOOKING');
        $res['code']    = 400;         

        $vArray = [

        	'ride_id'	=> 'required',           	
        	'driver_id'	=> 'required',           	
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 			= $request->get('user');		
		$rideId 		= $request->get('ride_id');
		$driverId 		= $request->get('driver_id');
		$cancelReason 	= $request->get('cancel_reason');	

		$where = array('id' => $rideId);

		$checkRide = Ride::where($where)
			->whereIn('ride_status', ['Pending', 'Accepted'])
			->update(array('ride_status' => 'Cancelled', 'cancel_reason' => $cancelReason));

		if ($checkRide) {
					
			$whereRideRequest = array('ride_id' => $rideId, 'driver_id' => $driverId);

			$check = RideRequest::where($whereRideRequest)->update(array('request_status' => 'Cancelled'));

			if ($check) {
				
				$rideInfo = Ride::where('rides.id', $rideId)
	          		->join('ride_requests', function($join)use($driverId){
	          			$join->on('ride_requests.ride_id', '=', 'rides.id')
	          			->where('ride_requests.driver_id', $driverId);
	          		})
	          		->first(['ride_requests.driver_id']);

				//Send Notification to driver
				if (isset($rideInfo->driver_id)) {

		          $message = "A passenger has been canelled ride.";

		          $body   = array("type"  => "RIDE_CANCELLED_BY_PASSENGER", 
			                      "message"   => $message,
			                      "ride_id"   => $rideId,
			                  	);
		      
		          $title  = "Ride Cancelled";

		          $userArray = array($rideInfo->driver_id);        
		      	
		          send_notification($userArray, $body, $title);

		        }

		        $this->cancel_inactive_passenger($user->id, $user->cancel_ride);        		

				$res['success'] = true;     
	            $res['msg']     = trans('Api::language.LANG_RIDE_BOOKING_HAS_BEEN_CANCELLED');        
	            $res['code']    = 200;
			}				
		
		}					

		return Response::json($res);
	}

	/*
	* Name: giveFeedback
	* Create Date: 24 March 2018
	* Purpose: This function is used to give feedback and rating to driver by passenger
	*/

	public function giveFeedback(Request $request){

		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_GIVE_FEEDBACK');
        $res['code']    = 400;         

        $vArray = [

        	'ride_id'	=> 'required',           	
        	'driver_id'	=> 'required',
        	'rating'	=> 'required|max:5',           	
        	//'feedback'	=> 'required',           	
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 			= $request->get('user');		
		$rideId 		= $request->get('ride_id');
		$driverId 		= $request->get('driver_id');
		$rating 		= $request->get('rating');
		$feedback 		= $request->get('feedback');
		
		$where = array('id' => $rideId);

		$data = array(
			"ride_id" 		=> $rideId,
			"driver_id" 	=> $driverId,
			"passenger_id" 	=> $user->id,
			"rating"		=> $request->get('rating'),
			"feedback"		=> $request->get('feedback'),
			"created_at"	=> date('Y-m-d H:i:s')
		);

		$check = Feedback::updateOrCreate(array('ride_id' => $rideId), $data);				
			
		if ($check) {
			
			$res['success'] = true;     
	        $res['msg']     = trans('Api::language.LANG_FEEDBACK_GIVEN');        
	        $res['code']    = 200;
			
		}		

		return Response::json($res);		
	}

	public function autoCapture(Request $request){

		$res['success'] = false;
        $res['code']    = 400;         

        $vArray = [

        	'auto_capture'	=> 'required',
        	'ride_id'		=> 'required'        	       	
        ];

        //Validate request
        check_validation($request, $vArray);

        $user = $request->get('user');		
		
		$file = $request->file('auto_capture');

	    if($file && $file != ""){

	      $auto_capture  = getTimeStamp().".".$file->getClientOriginalExtension();

	      Image::make($file)->resize(200, 200)->save('public/uploads/auto_capture/thumbnail/'.$auto_capture);

	      $file->move('public/uploads/auto_capture', $auto_capture);
	      
	      $data['auto_capture']  = $auto_capture;
	      $data['ride_id']  	 = $request->get('ride_id');

		  $check = AutoCaptureImages::insert($data);				
				
		  	if ($check) {
				
				$res['success'] = true;     
		        $res['code']    = 200;
				
			}		
	    }

		return Response::json($res);		
	}    

	/*
	* Name: checkPromocode
	* Create Date: 5 Apr 2018
	* Purpose: This function is used to check promocode
	*/

	public function checkPromocode(Request $request){

		$res['success'] = false;
        $res['code']    = 400;         
        $res['msg']     = trans('Api::language.LANG_TO_CHECK_PROMOCODE');         

        $vArray = [

        	'code'	=> 'required',
        	        	        	       	
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 		= $request->get('user');				
		$code 		= $request->get('code');
		
		//Check user already took promocode
		$check = RidePromocode::join('promocode', 'promocode.id', '=', 'ride_promocode.promocode_id')
					->where(['ride_promocode.passenger_id' => $user->id, 'code' => $code])
					->first();

		if (!empty($check)) {
			
			$res['success'] = true;     
		    $res['code']    = 200;
		    $res['msg']     = trans('Api::language.LANG_YOU_HAVE_ALREADY_TAKEN_THIS_PROMOCODE');
		
		}else{

			
			$date = date('Y-m-d');
			
			$promocode = Promocode::where(array('code' => $code, 'status' => '1'))
					->whereDate('start_date', '<=', $date)
					->whereDate('expiry_date', '>=', $date)									
					->first(['id', 'no_of_users']);
			
			if (!empty($promocode)) {
				
				$noOfUsage = RidePromocode::where(array('promocode_id' => $promocode->id))->count();
				
				if ($noOfUsage > $promocode->no_of_users){
					
					$res['success'] = true;     
		    		$res['code']    = 400;
		    		$res['msg']     = trans('Api::language.LANG_MAXIMUM_USAGE_DONE');

				}else{

					$data = array(

						'ride_id' 		=> "",
						'passenger_id' 	=> $user->id,
						'promocode_id' 	=> $promocode->id,					
						'created_at'	=> date('Y-m-d H:i:s')
					);

					$checkPromoCode = RidePromocode::insertGetId($data);

					if ($checkPromoCode) {
						
						$data['ride_promocode_id'] = $checkPromoCode;

					}

					$res['success'] = true;     
			    	$res['code']    = 200;
			    	$res['result']  = $data;
			    	$res['msg']     = trans('Api::language.LANG_PROMOCODE_APPLIED');
				}


			}else{
				
				$res['success'] = false;     
		    	$res['code']    = 400;
		    	$res['msg']     = trans('Api::language.LANG_INVALID_PROMOCODE');				
			}
		}

		return Response::json($res);		
	}

	/*
	* Name: rentalPackages
	* Create Date: 16 Apr 2018
	* Purpose: This function is used to get rentals packages
	*/

	public function rentalPackages(Request $request){

		$res['success'] = false;
        $res['code']    = 400;         
        $res['msg']     = trans('Api::language.LANG_PACKAGES_NOT_FOUND');         

        $user = $request->get('user');				
		
		$data = RentalPackage::where('status', '1')->get(['id', 'time', 'distance','charges','created_at']);

		if (count($data) > 0) {
			
			$res['success'] = true;     
		    $res['code']    = 200;
		    $res['result']  = $data;
		    $res['msg']     = trans('Api::language.LANG_PACKAGES_FOUND');
		
		}

		return Response::json($res);
	}

	public function cancelRide(Request $request){

		$res['success'] = false;
        $res['code']    = 400;         
        $res['msg']     = trans('Api::language.LANG_FAILED_TO_CANCEL_RIDE');         

        $vArray = [

        	'ride_id'	=> 'required',        	        	        	       	
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 	= $request->get('user');				
        $rideId = $request->get('ride_id');				
		
		$check = Ride::where(array('id' => $rideId, 'ride_status' => 'Pending'))
				->update(array('ride_status' => 'Cancelled'));

		if ($check) {
						
			$this->cancel_inactive_passenger($user->id, $user->cancel_ride);
			
			$res['success'] = true;     
		    $res['code']    = 200;		    
		    $res['msg']     = trans('Api::language.LANG_RIDE_CANCELLED');		    	    
		
		}

		
		return Response::json($res);
	}

	function ride_rental_package($rentalPackageJson){

		$rentalPackage = (array)$rentalPackageJson;

		if (!empty($rentalPackage)) {
			
			$id = RideRentalPackage::insertGetId($rentalPackage);
			
			Ride::where('id', $rentalPackage['ride_id'])->update(['ride_rental_package_id' =>  $id]);
		}
	}

	function cancel_inactive_passenger($passengerId,$cancel_ride){

		$cancellecRideCheck = Ride::where(array('passenger_id' => $passengerId, 'ride_status' => 'Cancelled'))
										->orderBy('rides.id', 'DESC')			        		
						        		->first(['rides.id']);
		        
        if (!empty($cancellecRideCheck)) {

	        //Update cancel ride for passenger
	        Users::where('id', $passengerId)->increment('cancel_ride');
        	
        }

        if ($cancel_ride > 3){

			//Send Notification to driver
			if (isset($passengerId)) {

	          Users::where('id', $passengerId)->update(array('status' => '0'));
	          
	          $message = "Oops! Your account has been inactivated due to you have canceled more than 3 rides continuously, please contact an administrator to activate it.";

	          $body   = array("type"  => "PASSENGER_ACCOUNT_INACTIVE", 
		                      "message"   => $message		                      
		                  	);
	      
	          $title  = "Account Inactivate";

	          $userArray = array($passengerId);        
	      	
	          send_notification($userArray, $body, $title);

	        }			

		}
	}


	/*
	* Name: laterRides
	* Create Date: 31 May 2018
	* Purpose: This function is used to get rides for driver and passenger
	*/

	public function laterRides(Request $request){

		$res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_RIDES_NOT_FOUND');
        $res['code']    = 400;         

        $vArray = [ 
        	'page'	=> 'required'
        ];

        //Validate request
        check_validation($request, $vArray);

        $user 		= $request->get('user');		
        $fromDate 	= $request->get('from_date');		
        $toDate 	= $request->get('to_date');		
        
        // for pagination
        $per_page   = 10;
        $page       = intval($request->get('page'));
        $start_from = ($page - 1) * $per_page;

		$fields = ['rides.id','staff_id','passenger_id','driver_id','esti_distance','esti_time','actual_distance','actual_time','fare','pickup_location','dropoff_location','ride_status', 'rides.created_at','vehicle_id','pay_amount','ride_type','ride_date_time','vehicle_name','start_ride_date','end_ride_date'];
		
		$passengerFields 	= "id,first_name,last_name,email,country_code,profile_image";
		$driverFields 		= "id,first_name,last_name,email,country_code,profile_image";

		$query = Ride::query();
		
		//Filter by date
		if ($fromDate != "") {
			
			$query->whereDate('created_at', '>=', $fromDate);	
		}						

		if ($toDate != "") {
			
			$query->whereDate('created_at', '<=', $toDate);	
		}

		if ($user->user_type == "Passenger") {
			
			$where = array("passenger_id" => $user->id);			
			$query->with(["driver:$driverFields"]);
		
		}else{
			
			$where = array("driver_id" => $user->id);
			$query->with(["passenger:$passengerFields"]);

		}

		$getRides = $query->where($where)
				->where(array('ride_type' => 'Later'))
				->whereIn('ride_status', array('Pending', 'Running', 'Accepted'))			
				->skip($start_from)
    			->take($per_page)
    			->orderBy('rides.id', 'DESC')
				->get($fields);

		if (count($getRides) > 0) {

			$res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_RIDES_FOUND');        
            $res['code']    = 200;
            $res['result']  = $getRides;
			
		}

		return Response::json($res);
	}
	
}		
