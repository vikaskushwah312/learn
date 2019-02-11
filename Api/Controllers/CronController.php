<?php

namespace App\Modules\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{DrivingLicense,Ride,RideRequest,Users,Charges,Feedback,AutoCaptureImages, RidePromocode,Promocode, RentalPackage, RideRentalPackage, Configuration,Sos};

use Response,DB,Mail;

/*
* Name: CronController
* Create Date: 16 March 2018
* Purpose: This function is used for cron jobs 
*/

class CronController extends Controller
{

   public function findDriver(Request $request){

        $currentDateTime = date('Y-m-d H:i:s');
            
        $oneFiveAfter   = date('Y-m-d H:i:s', strtotime("$currentDateTime + 15 minute"));
        $oneFiveBefore  = date('Y-m-d H:i:s', strtotime("$currentDateTime - 15 minute"));
        
        $getRides = Ride::where(array('ride_status' => 'Pending', 'ride_type' => 'Later'))                    
                    ->whereBetween('ride_date_time',[$oneFiveBefore,$oneFiveAfter])
                    ->get(['rides.id','pickup_long','pickup_lat']);        
               
        foreach ($getRides as $key => $value) {
            
            $rideId         = $value->id;
            $latitude       = $value->pickup_lat;
            $longitude      = $value->pickup_long;

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

            $checkRideRequessts = RideRequest::where(array('ride_id' => $rideId))->first();

            if (!empty($drivers) && empty($checkRideRequessts)) {
            
                $got = "no";

                foreach ($drivers as $key => $driver) {
                    
                    $checkRide = Ride::where(array('driver_id' => $driver->id))
                        ->whereDate('created_at', date('Y-m-d'))
                        ->whereIn('ride_status', array('Pending', 'Accepted', 'Running'))->first(['id']);

                    if (empty($checkRide) && $got == "no") {
                        
                        $rideRequest = array(

                            "ride_id"           => $rideId,
                            "request_status"    => "Pending",
                            "driver_id"         => $driver->id,
                            "created_at"        => date('Y-m-d H:i:s')
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
            }

        }                
        /*$emailData['code']  = "4646";
        $emailData['email'] = "pankaj@kavyasoftech.com";

        Mail::send('email.forgot_password', $emailData, function ($message) use($emailData){
                $message->to($emailData['email'])
                        ->subject('Forgot Password');            
            });
        echo "working";*/
   }

}
