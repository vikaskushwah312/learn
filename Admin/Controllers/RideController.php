<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Models\{AutoCaptureImages,Users,Ride,Notification,Country,DriverInfo,Charges,RideRequest};
use Validator,Redirect,Session,Image,File,URL,Config,Excel, Response;

/*
* Name: DriverController
* Create Date: 14 March 
*/

class RideController extends Controller
{

    /*
    * Name: rides listing
    * Create Date: 14 March 2018
    */
    public function rideList(Request $request){

        $data['title']    = 'Rides';
        $data['heading']  = 'Rides List';
        return view("Admin::list.rides_list", $data);
    }
    
    public function rideListData(Request $request){

      $rideStatus = $request->ridestatus;

      if($rideStatus !=""){

        $where = array('rides.ride_status' => $rideStatus);

      } else {

        $where = array();

      }

     $query = Ride::query();

    // Datatables Variables
      $draw   = intval($request->get("draw"));
      $start  = intval($request->get("start"));
      $length = intval($request->get("length"));

      $query->where($where);

      if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];

          $query->where('passengers.first_name',"LIKE","%$value%")
              ->orWhere('passengers.last_name',"LIKE","%$value%")  
              ->orWhere('drivers.first_name',"LIKE","%$value%")
              ->orWhere('drivers.last_name',"LIKE","%$value%")
              ->orWhere('rides.pickup_location',"LIKE","%$value%")
              ->orWhere('rides.dropoff_location',"LIKE","%$value%")
              ->orWhere('rides.id',"LIKE","%$value%")
              ->orWhere('ride_status',"LIKE","%$value%")
              ->orWhere('rides.fare',"=",$value);
          }

      //Order
        $orderByField = "rides.id";
        $orderBy = 'desc';
          
        if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {
                        
           $orderBy = $request->get('order')[0]['dir'];

        }

        if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

            if ($request->get('order')[0]['column'] == 0) {
                
              $orderByField = "rides.id";
            
            }else if($request->get('order')[0]['column'] == 1){

              $orderByField = "rides.fare";
            
            }

        }
      
      $field = array('rides.id as rideId','rides.passenger_id','rides.driver_id','rides.actual_distance','rides.actual_time','rides.fare','rides.pickup_location','rides.dropoff_location','rides.ride_status','rides.no_of_passenger','rides.created_at as ride_date','passengers.id as passengers_id','passengers.first_name as passenger_first_name','passengers.last_name as passenger_last_name','drivers.id as driver_id','drivers.first_name as driver_first_name','drivers.last_name as driver_last_name','staff.first_name as staff_first_name','staff.last_name as staff_last_name'); 
      

      $query->where($where)
            ->leftjoin('users as passengers','rides.passenger_id','=','passengers.id')
            ->leftjoin('users as drivers','rides.driver_id','=','drivers.id')
            ->leftjoin('users as staff','rides.staff_id','=','staff.id');


      $total = count($query->get(['rides.id']));     
             
      $info = $query->orderBy($orderByField,$orderBy)
                    ->skip($start)
                    ->take($length)
                    ->get($field);
                
      $data = array();
      $sno = $start;

      foreach($info as $r) {

          //ride Detail
          $urlDetail = URL::to('admin/ride-detail/'.$r->rideId);      
          $detail_url = '<a type="" class="" style="text-decoration: none;" data-title ="Ride Detail" title="Ride Detail" href="'.$urlDetail.'"><i class="fa fa-info-circle"></i> Ride Detail</a>';

          //$cancel_function = $r->ride_status == "Pending" ? 'cancel' : 'notcancel';

          $urlCancel = URL::to('admin/ride-cancel/'.$r->rideId);
          
          
          if ($r->ride_status == "Pending") {
            
             $cancel_url = '<a type="" class="cancel_ride" title="Ride Cancel" href="'.$urlCancel.'"><i class = "fa fa-remove"> </i>  Ride Cancel</a>';
          } else{

              $cancel_url ="";
          }
        
          if(!empty($r->ride_date)){

              $createdAt = date(Config::get('constants.DATE_FORMATE'),strtotime($r->ride_date));

          }else{

            $createdAt = "N/A";
          }
          if(!empty($r->actual_distance)){

              $actualDistance = $r->actual_distance.'Km';
          }else{

              $actualDistance = "N/A";
          }
          if(!empty($r->actual_time)){

              $actualTime = $r->actual_time;
          }else{

              $actualTime = "N/A";
          }
          if(!empty($r->fare)){

              $actuaFare = Config::get('constants.CURRENCY').$r->fare;
          }else{

              $actuaFare = "N/A";
          }
          
          $data[] = array(             
             $sno = $sno+1,
             ucfirst($r->rideId),
             ucfirst($r->staff_first_name.' '.$r->staff_last_name),
             ucfirst($r->passenger_first_name.' '.$r->passenger_last_name),
             ucfirst($r->driver_first_name.' '.$r->driver_last_name),         
             ucfirst($r->pickup_location),
             ucfirst($r->dropoff_location),
             ride_status($r->ride_status),
             $createdAt,
             
			  '<div class="manage_drop_d">
          <div class="dropdown">
            <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span>
            </button>
              <ul class="dropdown-menu dropdown-menu-right" role="menu"> 

                <li>'.$detail_url.'</li>
                <li>'.$cancel_url.'</li>
              </ul>
           
          </div>
			  </div>   ',                       

           );
      }

      $output = array(
                        "draw"            => $draw,
                        "recordsTotal"    => $total,
                       "recordsFiltered"  => $total,
                       "data"             => $data
                );

      //Read all rides
      Ride::where(array('read_status' => '0'))->update(['read_status' => '1']);

      echo json_encode($output);
      exit(); 
    }

    /*
  * Name: driver Deatil
  * Create Date: 13 Mar 2018  
  * Purpose: 
  */
  public function rideDetail($rideId,Request $request){

       $field = array('rides.id as rideId','rides.passenger_id','rides.driver_id','rides.staff_id','rides.esti_distance','rides.esti_time','rides.esti_fare','rides.fare','rides.waiting_charges','rides.promocode_charges','rides.actual_distance','rides.actual_time','rides.pickup_location','rides.dropoff_location','rides.no_of_passenger','rides.created_at as ride_date','rides.start_ride_date','rides.end_ride_date','passengers.id as passengers_id','passengers.first_name as passenger_first_name','passengers.last_name as passenger_last_name','drivers.id as driver_id','drivers.first_name as driver_first_name','drivers.last_name as driver_last_name','passengers.country_code as passenger_country_code','passengers.mobile_number as passenger_mobile_number','passengers.profile_image as passenger_profile_image','passengers.email as passenger_email','drivers.country_code as driver_country_code','drivers.mobile_number as driver_mobile_number','drivers.email as driver_email','passengers.address as passenger_address','drivers.address as driver_address','rides.ride_status','rides.cancel_reason','rides.payment_type','staff.first_name as staff_first_name','staff.last_name as staff_last_name','staff.country_code as staff_country_code','staff.mobile_number as staff_mobile_number','staff.email as staff_email','rides.pickup_lat','rides.pickup_long','rides.dropoff_lat','rides.dropoff_long','rides.pay_amount','rides.gst_amount','rides.total_amount','ride_rental_packages.time','ride_rental_packages.distance','ride_rental_packages.charges','ride_rental_packages.id as packagesId');

        $data['rideInfo'] = Ride::where(array('rides.id' => $rideId))
                                ->leftjoin('users as passengers','rides.passenger_id','=','passengers.id')
                                ->leftjoin('users as drivers','rides.driver_id','=','drivers.id')
                                ->leftjoin('users as staff','rides.staff_id','=','staff.id')
                                ->leftjoin('ride_rental_packages','ride_rental_packages.id','=','rides.ride_rental_package_id')
                                ->first($field);
        //d($data['rideInfo']); die;

        $data['capturerImage'] = AutoCaptureImages::where(array('ride_id' => $rideId))
                                                  ->get(['id','auto_capture']);
        //print_r($data['capturerImage']);die;

        $data['rideRequest'] = RideRequest::where(array('ride_id' => $rideId))
                                            ->leftjoin('users as driver','ride_requests.driver_id','=','driver.id')
                                            ->get(['request_status','not_accepted_reason','driver.first_name','driver.last_name','driver.id']);

        //print_r($data['rideRequest']);die;
                                
        $data['heading']  = "Ride Detail";
        $data['title']    = "Ride Detail";


       return view("Admin::detail.ride_detail",$data);               
  }

  /*
  * Create Date: 19 April 2018
  * Purpose: Ride Cancel 
  */
  public function rideCancel(Request $request,$rideId){
      
        $update['ride_status'] = 'Cancelled';
    
        $rideCancel = Ride::where('id',$rideId)
                             ->whereIn('ride_status',['Pending'])->update($update);

            if($rideCancel){
              
              $msg = "Ride has been cancelled.";
            } else {
              $msg = "Ride Not Cancel.";
            }
      
        return redirect('admin/rides-list')->withSuccess($msg);



  }/*End Ride Cancel */



 /*
  * Create Date: 30 Mar 2018
  * Purpose: To Export the all Information of passenger with there Driver & fire,time,distance 
  */
  public function ridesExport(Request $request){

    $field = array('drivers.first_name as driver_first_name','drivers.last_name as driver_last_name','passengers.first_name as passengers_first_name','passengers.last_name as passengers_last_name','rides.esti_distance','rides.actual_distance','rides.esti_time','rides.actual_time','rides.waiting_time','rides.no_of_passenger','rides.esti_fare','rides.fare','rides.pickup_location','rides.dropoff_location');

      $data = Ride::join('users as drivers','rides.driver_id','=','drivers.id')
                  ->join('users as passengers','rides.passenger_id','=','passengers.id')
                  ->get($field);
      
      //print_r($data);die;
      
      $filename = 'Passenger List '.date('d-m-y');

      Excel::create($filename, function($excel) use($data) {

        $excel->sheet('list', function($sheet) use($data) {
           
        $sheet->cell('A1', function($cell) {$cell->setValue('Driver Name');       });
        $sheet->cell('B1', function($cell) {$cell->setValue('Passenger Name');    });
        $sheet->cell('C1', function($cell) {$cell->setValue('Estimate Distance(Km)'); });
        $sheet->cell('D1', function($cell) {$cell->setValue('Actual Distance(Km)');   });
        $sheet->cell('E1', function($cell) {$cell->setValue('Estimate Time(min)');     });
        $sheet->cell('F1', function($cell) {$cell->setValue('Actual Time(min)');       });
        $sheet->cell('G1', function($cell) {$cell->setValue('waiting Time(min)');      });
        $sheet->cell('H1', function($cell) {$cell->setValue('No Of Passenger');   });
        $sheet->cell('I1', function($cell) {$cell->setValue('Estimate fare(Rs.)');     });
        $sheet->cell('J1', function($cell) {$cell->setValue('Actual fare(Rs.)');       });
        $sheet->cell('K1', function($cell) {$cell->setValue('Pickup Location');   });
        $sheet->cell('L1', function($cell) {$cell->setValue('Dropoff Location');  });       
                
       
            if (!empty($data)) {
                
                foreach ($data as $key => $value) {

                  $driver_name    = $value->driver_first_name." ".$value->driver_last_name;
                  $passenger_name    = $value->passengers_first_name." ".$value->passengers_last_name;
                  $esti_distance  = $value->esti_distance;
                  $actual_distance  = $value->actual_distance;
                  $esti_time      = $value->esti_time;
                  $actual_time    = $value->actual_time;
                  $waiting_time   = $value->waiting_time;
                  $no_of_passenger= $value->no_of_passenger;
                  $esti_fare      = $value->esti_fare;
                  $fare           = $value->fare;
                  $pickup_location= $value->pickup_location;
                  $dropoff_location=$value->dropoff_location;
                  
            
                  $i=$key+2;
                  $sheet->cell('A'.$i,$driver_name);
                  $sheet->cell('B'.$i,$passenger_name);
                  $sheet->cell('C'.$i,$esti_distance);
                  $sheet->cell('D'.$i,$actual_distance);
                  $sheet->cell('E'.$i,$esti_time);
                  $sheet->cell('F'.$i,$actual_time);
                  $sheet->cell('G'.$i,$waiting_time);
                  $sheet->cell('H'.$i,$no_of_passenger);
                  $sheet->cell('I'.$i,$esti_fare);
                  $sheet->cell('J'.$i,$fare);
                  $sheet->cell('K'.$i,$pickup_location);
                  $sheet->cell('L'.$i,$dropoff_location);
                  
                  
                }
            }

        $sheet->cells('A1:L1', function($cells) {
            // Set font
            $cells->setFont(array(
                    'family' => 'Calibri',
                    'size' => '10',                
            
            ));
        });
                   
        $sheet->setHeight(10,15);
                    
      });
    })->export('xls');

  }/*Rides Export End*/

  public function getNewRides(Request $request){

    $response = array("success" => 1, 'rides' => 0);

    $rides    = Ride::where(['receive_status' => '0'])->get(['id']);

    $response['count']  = Ride::where(['read_status' => '0'])->count(['id']);

    if (count($rides) > 0) {
      
      Ride::where(['receive_status' => '0'])->update(['receive_status' => '1']);
      
      $response['rides'] = $rides;
      $response['rides_count'] = count($rides);
    }

    return Response::json($response);
  
  }/*End Get New Rides*/


/*
  * Create Date: 17 April 2018
  * Purpose: To View All Images 
  */
  
  public function viewAllImagesList(Request $request,$rideId){

    $data['title'] = 'Capture Image';

    $field = array('rides.id as rideId','rides.pickup_location','rides.dropoff_location','passengers.first_name','passengers.last_name');
    
    $data['rideInfo'] = Ride::where(array('rides.id' => $rideId))
                                ->leftjoin('users as passengers','rides.passenger_id','=','passengers.id')
                                ->first($field);

    $data['img'] = AutoCaptureImages::where(array('ride_id' => $rideId))->get(['auto_capture','id','ride_id']);
    
       
    return view("Admin::list.all_images_list",$data);
  }/*End View All Images*/

  public function deleteCapturerImage(Request $request){

    $response = array("success" => 0, 'msg' => 0);

    //variable
    $check        = $request->get('check');
    $select_image = $request->get('select_image');
    $rideId       = $request->get('rideId');

    if($check == 'delete_all'){

        $getImage = AutoCaptureImages::where(array('ride_id' => $rideId))->get(['auto_capture']);

        if(count($getImage) > 0){

            foreach($getImage as $val){

              $file = 'public/uploads/auto_capture/'.$val->auto_capture;

              if(file_exists($file)){

                unlink($file);
              }
              $thumbnailfile = 'public/uploads/auto_capture/thumbnail/'.$val->auto_capture;
              if(file_exists($thumbnailfile)){

                unlink($thumbnailfile);
              }   
                  
            }
        }
        AutoCaptureImages::where(array('ride_id' => $rideId))->forceDelete(); 
        $response = array("success" => 1, 'msg' => 'Image deleted Successfully');  
    }else{

        if($select_image != ""){

            $explodeImageId = explode(',',$select_image);

            
            $getImage = AutoCaptureImages::whereIn('id',$explodeImageId)->get(['id','auto_capture']);
            
             if(count($getImage) > 0){

              foreach($getImage as $val){

                $file = 'public/uploads/auto_capture/'.$val->auto_capture;

                if(file_exists($file)){

                  unlink($file);
                }
                $thumbnailfile = 'public/uploads/auto_capture/thumbnail/'.$val->auto_capture;
                if(file_exists($thumbnailfile)){

                  unlink($thumbnailfile);
                }   
                    
              }
          }
          AutoCaptureImages::whereIn('id',$explodeImageId)->forceDelete(); 
          $response = array("success" => 1, 'msg' => 'Image deleted Successfully'); 
        }
    }

    

    return Response::json($response);
  
  }


/*
  * Create Date: 01 May 2018
  * Purpose: Get Driver Information
  */
  public function getDriverInfo(Request $request){
      
        $res      = array("success" => 0,"msg" => 'Info not found.');
        $driverId = $request->get('driver_id');
        
        if(!empty($driverId) && is_numeric($driverId)){

          $info = DriverInfo::where(array('driver_id' => $driverId))                                
                                ->with('assigned_vehicle','driver_info','driver_license')
                                ->first();
                                       
          if(!empty($info)){

            $res = array("success" => 1,'msg' => '','info' => $info);
          
          }else{

            $res = array("success" => 0,"msg" => 'Info not found.');
          }
        }
        return Response::json($res);  
    }


/*
  * Create Date: 01 May 2018
  * Purpose: To Create New Ride
  */
  public function newRide(){

    $data['title']   = "Create New Ride";

    $data['passengers']   = Users::oftype('Passenger')->get();
    $data['drivers']      = Users::oftype('Driver')
                                ->get();

    $data['country']      = Country::get();

    return view("Admin::add.new_ride",$data);

  }/*End new Ride*/

/*
  * Create Date: 01 May 2018
  * Purpose: Add New Ride
  */
  public function postNewRide(Request $request){


    $res = array("success" => 0,"msg" => 'Failed to create ride.');
      
      $adminId = Session::get('admin_id');


      //get charge info
      $chergeInfo   = Charges::where(array('id' => 1))->first();

      $driverId       = $request->get('select_driver');
      $esti_distance  = $request->get('esti_distance');
      
      $fareInfo   = calculate_fare($esti_distance,0,"");
      
      $insert = array("passenger_id"          => $request->get('select_passenger'),
                      "driver_id"             => $driverId,
                      "staff_id"              => $adminId,
                      "pickup_location"       => $request->get('pickup_location'),                      
                      "dropoff_location"      => $request->get('dropoff_location'),                      
                      "esti_distance"         => $request->get('esti_distance'),                      
                      "esti_time"             => $request->get('esti_time'),
                      "no_of_passenger"       => $request->get('no_of_passenger'),
                      "payment_type"          => 1,
                      "ride_status"           => 'Pending',
                      "esti_fare"             => $fareInfo['fare'],
                      "pickup_lat"            => $request->get('pickup_lat'),
                      "pickup_long"           => $request->get('pickup_long'), 
                      "dropoff_lat"           => $request->get('dropoff_lat'),
                      "dropoff_long"          => $request->get('dropoff_long'),            
                      "ride_by"               => "admin",
                      "created_at"            => date('Y-m-d H:i:s')
                );

      $insert['total_amount'] = $fareInfo['total_amount'];
      $insert['gst_amount']   = $fareInfo['gst_amount'];
      $insert['pay_amount']   = $fareInfo['pay_amount'];

      $lastId = Ride::insertGetId($insert);

      if($lastId){

        $insert['ride_id'] = $lastId;

        //Ride request
        $rideRequest = [  
                        "ride_id"         => $lastId,
                        "driver_id"       => $driverId,
                        "request_status"  => "Pending",
                        "created_at"      => date('Y-m-d H:i:s'),
                      ];

        RideRequest::insert($rideRequest);

        //Notification        
        if ($driverId) {
          
          $message = "Great! A new ride has been assign to you.";

          $body   = array("type"  => "STAFF_ASSIGN_RIDE", 
                      "message"   => $message,
                      "ride_id"   => $lastId,
                      "json"      => $insert);
      
          $title  = "New Ride Request";
      
          $userArray[] = $request->get('select_driver');        
      
          send_notification($userArray, $body, $title);

        }

        $res = array("success" => 1,'msg' => 'Great! A ride has been created.');
      
      }

      return Response::json($res);

  }/*End Post New Ride */

  /* Name: add passenger by admin
  * Create Date: 2 May 2018
  
  */
    public function addPassanger(Request $request){

        $res  = array("success" => 0,"msg" => 'Info not found.');
        $vArray = array('first_name'    => 'required|regex:/^[\pL\s\-]+$/u',
                      'last_name'       => 'required|regex:/^[\pL\s\-]+$/u',
                      'country_code'    => 'required',
                      'mobile_number'   => 'required|numeric|unique:users');
        
        if(!empty($request->email)){

            $vArray['email'] = 'email|unique:users';
        }
      //Validate request
        check_validation($request, $vArray);
        
        $insert = array('first_name'          => $request->first_name,
                            'last_name'       => $request->last_name,
                            'country_code'    => $request->country_code,
                            'mobile_number'   => $request->mobile_number,
                            'email'           => $request->email,
                            'gender'          => $request->gender,
                            'user_type'       => 'Passenger',
                            'status'          => '1',
                            'mobile_verification_status'  => 'NotVerified',
                            'email_verification_status'   => 'NotVerified',
                            "mobile_verification_code"    => random_code(),
                            'created_at'      => date('Y-m-d H:i:s')
                          );

        $lastId =  Users::insertGetId($insert);

        if($lastId){

            $res  = array("success" => 1,"msg" => '',"userId" => $lastId,"user_name" => ucfirst($request->first_name).' '.$request->last_name,'number' =>  $request->mobile_number);
        }
        return Response::json($res);  
    }

  public function getRunningRides(Request $request){

    $response = array("success" => 0, 'message' => 'Ride not found');

    $field = array('rides.id','users.first_name','users.last_name','users.latitude as driver_lat','users.longitude as driver_long','rides.driver_id','rides.pickup_location as pickup_address','rides.dropoff_location as dropoff_address','rides.pickup_lat','rides.pickup_long','rides.dropoff_lat','rides.dropoff_long');

    $rides    = Ride::where(array('ride_status' => 'Running'))
                    ->join('users','rides.driver_id','=','users.id')
                    ->get($field);

    if (count($rides) > 0) {
      
      
      $response = array("success" => 1, 'message' => 'Ride found',"data" => $rides);
    }

    return Response::json($response);
  }
}