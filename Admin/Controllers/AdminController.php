<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\{Users,Promocode,Brand,VehicleModel,Country,State,City,Ride,Vehicle,Configuration,CancelReason,Notification,DriverInfo,DrivingLicense,RentalPackage}; 

use Validator,Redirect,Session,DB,Response,URL,Config;
/*
* Name: AdminController
* Create Date: 13 March
*/
class AdminController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function dashboard(){

      $data['title'] = "Dashboard";
      
      return view("Admin::index", $data);
    }

    public function login(){
      
      return view("Admin::layout.login");
    }

    public function postLogin(Request $request){

        $validator = $this->validate($request, [
            'email' 	=> 'required|email', 
            'password' 	=> 'required',
        ]);
        
        $credentials = array("email" => $request->get('email'), 
        					 "password" => $request->get('password'), 
        					 'user_type' => 'Admin');
        
        if ($token = JWTAuth::attempt($credentials)) {
                 
            $user = JWTAuth::user();
            
            $sessionVariable = array('admin_email'      => $user['email'],
                                    'admin_id'          => $user['id'],
                                    'admin_user_type'   => $user['user_type']);
            
            $request->session()->put($sessionVariable);

            return redirect('admin/dashboard');

        }else{

            return redirect('admin/login')->withFail('Invalid credentials.');
        }
    }

     /*
     * Name: forgot password
     * Create Date: 13 Mar 2018
     * Purpose: 
     */
    public function forgotPassword(Request $request){

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(),[

                'email' =>'required|email',

            ]);
            if($validator->fails()){

                return Redirect::to("admin/forgot-password")->withFail($validator)->withInput();
              }
            else
            {

                $email = $request->email;

                //Select fields from DB
                $fields = array("id");

                //Query to DB
                $getId = Users::where(array('email' => $email))->first($fields);

                if(!empty($getId)){

                    //generate random number 
                    $verificationCode = random_code();

                    $checkData = Users::where(array('email' => $email))
                                ->update(array('email_verification_code' => $verificationCode));

                    if($checkData){

                        //send email (verification code)
                          $data['email']    = $request->email;
                          $name             = "Admin";
                          $code             = $verificationCode;

                          //send mail


                          return redirect('admin/reset-forgot-password')->withSuccess("We have sent you a verification code on your registered email id.");
                    }
                }else{

                return Redirect::to("admin/forgot-password")->withFail("This email id is not register.")->withInput();
                }
            }
        }else{

            $data['title'] = 'Forgot Password';
            return view("Admin::layout.forgot_password", $data);
        }
    }

     /*
     * Name: reset forgot password
     * Create Date: 13 Mar 2018
     * Purpose: 
     */
    public function resetPassword(Request $request){

        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(),[

                'verification_code'   => 'required|max:4',
                'new_password'        => 'required|max:20|min:6',
                'confirm_new_password'=> 'required|same:new_password',
            ]);
            if($validator->fails()){

                return Redirect::to("admin/reset-forgot-password")->withErrors($validator);

            }else{

                $verificationCode   = $request->verification_code;

                $newpassword        = $request->new_password;

                //Update Data
                $updateData = array('password' => bcrypt($newpassword));

                //Query to DB
                $checkData = Users::where(array('verification_code' => $verificationCode))
                          ->update($updateData);

                if($checkData){

                  return Redirect::to("admin/login")->withSuccess("Password reset successfully,please login.");

                }else{

                  return Redirect::to("admin/reset-forgot-password")->withFail("Please enter correct verification code");
                }
            }

        }else{

            $data['title'] = 'Reset Forgot Password';
            return view("Admin::layout.reset_forgot_password", $data);
        }
    }

    /*
     * Name: logout
     * Create Date: 13 Mar 2018
     * Purpose: 
     */
    public function logout(){

        Session()->flush();

        return redirect('admin/login');
    }

    public function changePassword(Request $request){

        if($request->isMethod('post')){

            $requests = $request->only('password', 'currentPassword', 'confirmPassword');

            $validator = Validator::make($request->all(), [
                        'password'        => 'required',
                        'newPassword'     => 'required',
                        'confirmPassword' => 'required|same:newPassword',
            ]);

            if ($validator->fails()) {

                return redirect('admin/change-password')->withErrors($validator);

            } else {

                  //Variables
                  $password         = $request->get('password');
                  $newPassword      = bcrypt($request->get('newPassword'));
                  $confirmPassword  = $request->get('confirmPassword');
                  $email            = Session::get('admin_email');
                  $adminId          = Session::get('admin_id');
                  $adminUserType    = Session::get('admin_user_type');

                   $credentials = array("email"     => $email, 
                                        "password"  => $request->get('password'), 
                                        'user_type' => $adminUserType);
                  

                  //Conditions
                  $where = array('email' => $email,'id' => $adminId);
                  if (!$user = JWTAuth::attempt($credentials)) {

                      return Redirect::to("admin/change-password")->withFail('Current password is wrong');

                  } else {

                      //Update
                      $update = array('password' => $newPassword);

                      //Query to DB
                      $checkUpdate = Users::where($where)->update($update);

                      if ($checkUpdate) {
                          return Redirect::to("admin/change-password")->withSuccess('Password changed successfully.');
                      } else {

                          return Redirect::to("admin/change-password")->withFail('Something went to wrong.');
                      }
                  }
            }

        }else{

            $data['title'] = 'Change Password';
            return view("Admin::layout.change_password", $data);
        }
    }


     /*
     * Name: status activity
     * Create Date: 13 Mar 2018
     * Purpose: change status by admin
     */

     //status Active & Inactive
    function status_activity($id, $Status, $function, Request $request) {

        $msg = '';
      //passenger
       
        if ($function == 'passenger') {
          //passenger status updated
          
            $where = array('id' => $id);
            
            if($Status == 'Active'){

                $update['status'] = '1';
                $update['cancel_ride'] = '0';
                
                Users::where($where)->update($update);

                $msg = "Status has been activated successfully.";
            }
            else{

                $update['status'] = '0';
                Users::where($where)->update($update);

                //Expire JWT token for this user
                //expire_user_token($id);
                
                $msg = "Status has been inactived successfully.";
             }   

            return redirect('admin/passengers')->withSuccess($msg);
        
        }elseif ($function == 'driver') {
            
            //driver status updated
            $where = array('id' => $id,'user_type' => 'Driver');
            
            if($Status == 'Active'){ 

                $update['status'] = '1';
                
                Users::where($where)->update($update);

                $msg = "Status has been activated successfully.";
            
            }
            else{
              
                $update['status'] = '0';

                
                Users::where($where)->update($update);

                $msg = "Status has been inactived successfully.";
             }   

            return redirect('admin/drivers-list')->withSuccess($msg);

        }elseif ($function == 'staff') {
            
            //driver status updated
            $where = array('id' => $id,'user_type' => 'Staff');
            
            if($Status == 'Active'){

                $update['status'] = '1';

                Users::where($where)->update($update);

                $msg = "Status has been activated successfully.";
            }
            else{

                $update['status'] = '0';
                Users::where($where)->update($update);

                //Expire JWT token for this user
                //expire_user_token($id);
                
                $msg = "Status has been inactived successfully.";
             }   

            return redirect('admin/staff-list')->withSuccess($msg);

        }elseif ($function == 'promocode') {
            
            //driver status updated
            $where = array('id' => $id);
            
            if($Status == 'Active'){

                $update['status'] = '1';

                Promocode::where($where)->update($update);

                $msg = "Status has been activated successfully.";
            }else{

                $update['status'] = '0';
                Promocode::where($where)->update($update);

                $msg = "Status has been inactived successfully.";
             }   

            return redirect('admin/promocode')->withSuccess($msg);
            

           

        }elseif ($function == 'state') {
            
            
            $where = array('id' => $id);
            
            if($Status == 'Active'){

                $update['status'] = 'Active';

                State::where($where)->update($update);

                $msg = "Status has been activated successfully.";
            }else{

                $update['status'] = 'Inactive';
                State::where($where)->update($update);

                //Expire JWT token for this user
                //expire_user_token($id);
                
                $msg = "Status has been inactived successfully.";
             }   

            return redirect('admin/state-list')->withSuccess($msg);
            

           

        }elseif ($function == 'city') {
            
            //driver status updated
            $where = array('id' => $id);
            
            if($Status == 'Active'){

                $update['status'] = 'Active';

                City::where($where)->update($update);

                $msg = "Status has been activated successfully.";
            }else{

                $update['status'] = 'Inactive';
                City::where($where)->update($update);

                //Expire JWT token for this user
                //expire_user_token($id);
                
                $msg = "Status has been inactived successfully.";
             }   

            return redirect('admin/city-list')->withSuccess($msg);
        }
        elseif ($function == 'brand') {
            
            
            $where = array('id' => $id,);
            
            if($Status == 'Active'){

                $update['status'] = '1';

                Brand::where($where)->update($update);

                $msg = "Status has been activated successfully.";
            }
            else{

                $update['status'] = '0';
                Brand::where($where)->update($update);

                //Expire JWT token for this user
                //expire_user_token($id);
                
                $msg = "Status has been inactived successfully.";
             }   

            return redirect('admin/brand-list')->withSuccess($msg);

        }elseif ($function == 'model') {
            
            
            $where = array('id' => $id,);
            
            if($Status == 'Active'){

                $update['status'] = '1';

                VehicleModel::where($where)->update($update);

                $msg = "Status has been activated successfully.";
            }
            else{

                $update['status'] = '0';
                VehicleModel::where($where)->update($update);

                //Expire JWT token for this user
                //expire_user_token($id);
                
                $msg = "Status has been inactived successfully.";
             }   

            return redirect('admin/model-list')->withSuccess($msg);

        }/*country Active*/
             elseif ($function == 'country') {
            
            //country  status updated
            $where = array('id' => $id);
            
            if($Status == 'Active'){

                $update['status'] = 'Active';

                Country::where($where)->update($update);

                $msg = "Status has been activated successfully.";
            }
            else{
                
                //$update['status'] = '0';
                  $update['status'] = 'Inactive';

                Country::where($where)->update($update);

                //Expire JWT token for this user
                //expire_user_token($id);
                
                $msg = "Status has been inactived successfully.";
             }   

            return redirect('admin/country-list')->withSuccess($msg);

    }elseif ($function == 'vehicle') {
            
            
            $where = array('id' => $id);
            
            if($Status == 'Active'){

                $update['status'] = 1;

                Vehicle::where($where)->update($update);

                $msg = "Status has been activated successfully.";
            }else{

                $update['status'] = 0;
                Vehicle::where($where)->update($update);

                //Expire JWT token for this user
                //expire_user_token($id);
                
                $msg = "Status has been inactived successfully.";
             }   

            return redirect('admin/vehicle-list')->withSuccess($msg);
      }elseif ($function == 'rentalPackages') {
            
            
            $where = array('id' => $id);
            
            if($Status == 'Active'){

                $update['status'] = 1;

                RentalPackage::where($where)->update($update);

                $msg = "Status has been activated successfully.";
            }else{

                $update['status'] = 0;
                RentalPackage::where($where)->update($update);

                //Expire JWT token for this user
                //expire_user_token($id);
                
                $msg = "Status has been inactived successfully.";
             }   

            return redirect('admin/rental-packages')->withSuccess($msg);
      }

  }

     /*
     * Name: delete activity   
     * Create Date: 06 Dec 2017
     * Purpose: delete activity  by admin
     */

     //Delete Activity
    function delete_activity($id, $function) {
          
        $responce = array('success' => 0, 'msg' => 'Fail to delete record.');

        if ($function == 'passenger') {

          //check ride status
           $rideCount = Ride::where(array('passenger_id' => $id))->whereIn('ride_status',['Pending','Running','Accepted'])->count();

            if($rideCount == 0){

              Users::where(array('id' => $id))->delete();
              //Expire JWT token for this user
              //expire_user_token($id);

              $responce['success'] = 1;
              $responce['msg'] = 'Your record has been deleted.';

            }else{

              $responce = array('success' => 0, 'msg' => 'Currently ride is running/pending this passenger so you can not remove.');
            }
        
        }elseif ($function == 'driver') {
          
          //check ride status
          $rideCount = Ride::where(array('driver_id' => $id))->whereIn('ride_status',['Pending','Running','Accepted'])->count();
          
          if($rideCount == 0){

              Users::where(array('id' => $id))->delete();
              DriverInfo::where(array('driver_id' => $id))->delete();
              //DrivingLicense::where(array('driver_id' => $id))->delete();
              //Expire JWT token for this user
              //expire_user_token($id);

              $responce['success'] = 1;
              $responce['msg'] = 'Your record has been deleted.';
          }else{

            $responce = array('success' => 0, 'msg' => 'Currently ride is running/pending this driver so you can not remove.');
          }

        }elseif ($function == 'staff') {
          
           Users::where(array('id' => $id))->delete();

            //Expire JWT token for this user
            //expire_user_token($id);

            $responce['success'] = 1;
            $responce['msg'] = 'Your record has been deleted.';
        }
        elseif ($function == 'promocode') {
          
           Promocode::where(array('id' => $id))->delete();

            //Expire JWT token for this user
            //expire_user_token($id);

            $responce['success'] = 1;
            $responce['msg'] = 'Your record has been deleted.';
                  
        }
        elseif ($function == 'country') {
          
          $getStateID = State::where('country_id',$id)->first(['states.id']);

          Country::where(array('id' => $id))->delete();
           
          State::where(array('country_id' => $id))->delete();
            
          City::where(array('state_id' => $getStateID->id))->delete();

          $responce['success'] = 1;
          $responce['msg'] = 'Your record has been deleted.';
        
        }elseif ($function == 'state') {
          
          State::where(array('id' => $id))->delete();
          City::where(array('state_id' => $id))->delete();
          $responce['success'] = 1;
          $responce['msg'] = 'Your record has been deleted.';
        
        }elseif ($function == 'city') {
          
          City::where(array('id' => $id))->delete() ;
          $responce['success'] = 1;
          $responce['msg'] ='Your record has been deleted.';

        }elseif ($function == 'cancel') {
          
          CancelReason::where(array('id' => $id))->delete() ;
          $responce['success'] = 1;
          $responce['msg'] ='Your record has been deleted.';

        }elseif ($function == 'brand') {
          
            Brand::where(array('id' => $id))->delete();

            //Expire JWT token for this user
            //expire_user_token($id);

            $responce['success'] = 1;
            $responce['msg'] = 'Your record has been deleted.';
        
        }elseif ($function == 'notification') {
          
            Notification::where(array('id' => $id))->delete();

            $responce['success'] = 1;
            $responce['msg'] = 'Your record has been deleted.';
        
        }elseif ($function == 'model') {
          
            VehicleModel::where(array('id' => $id))->delete();

            //Expire JWT token for this user
            //expire_user_token($id);

            $responce['success'] = 1;
            $responce['msg'] = 'Your record has been deleted.';
        }elseif ($function == 'rental_packages') {
          
            RentalPackage::where(array('id' => $id))->delete();

            $responce['success'] = 1;
            $responce['msg'] = 'Your record has been deleted.';
        }
        echo json_encode($responce); 
    }

    public function DashboardCount(Request $request){
     
        $start_date = $request->get('start_date');

        $end_date   = $request->get('end_date');

        $data['rides'] = Ride::whereDate('created_at','>=',$start_date)
                                ->whereDate('created_at','<=',$end_date)                                
                                ->count();

        $data['running_rides'] = Ride::whereDate('created_at','>=',$start_date)
                                ->whereDate('created_at','<=',$end_date)
                                ->where('ride_status', 'Running')                                
                                ->count(); 

        $data['passengers'] = Users::whereDate('created_at','>=',$start_date)
                                ->whereDate('created_at','<=',$end_date)
                                ->where(array('user_type' => 'Passenger'))
                                ->count();                        

        $data['drivers'] = Users::whereDate('created_at','>=',$start_date)
                                ->whereDate('created_at','<=',$end_date)
                                ->where(array('user_type' => 'Driver'))
                                ->count();

        $data['staff'] = Users::whereDate('created_at','>=',$start_date)
                                ->whereDate('created_at','<=',$end_date)
                                ->where(array('user_type' => 'Staff'))
                                ->count();

        $data['vehicles'] = Vehicle::whereDate('created_at','>=',$start_date)
                                ->whereDate('created_at','<=',$end_date)                                
                                ->count();
        
        $data['rides_data'] = Ride::select(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as date ,count(id) as Rides'))->whereDate('created_at','>=',$start_date)
                        ->whereDate('created_at','<=',$end_date)                        
                        ->groupBy(DB::raw('date(created_at)'))
                        ->get();

        $data['passengers_data'] = Users::select(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as date ,count(id) as Passengers'))->whereDate('created_at','>=',$start_date)
                        ->whereDate('created_at','<=',$end_date)
                        ->where(array('user_type' => 'Passenger'))
                        ->groupBy(DB::raw('date(created_at)'))
                        ->get();                        

        $data['drivers_data'] = Users::select(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as date ,count(id) as Drivers'))->whereDate('created_at','>=',$start_date)
                                ->whereDate('created_at','<=',$end_date)
                                ->where(array('user_type' => 'Driver'))
                                ->groupBy(DB::raw('date(created_at)'))
                                ->get();

        $data['staff_data'] = Users::select(DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as date ,count(id) as Staff'))->whereDate('created_at','>=',$start_date)
                                ->whereDate('created_at','<=',$end_date)
                                ->where(array('user_type' => 'Staff'))
                                ->groupBy(DB::raw('date(created_at)'))
                                ->get();
                                
        return Response::json($data);
    }

    /*
     * Name: configurations
     * Create Date: 27 Mar 2018
     * Purpose: 
     */
    public function configuration(Request $request){

        $data['title']    = 'Configurations';
        $data['heading']  = 'Configurations';
        return view("Admin::list.configuration", $data);
    }
    
    public function configurationData(Request $request){

     $query = Configuration::query();

    // Datatables Variables
      $draw   = intval($request->get("draw"));
      $start  = intval($request->get("start"));
      $length = intval($request->get("length"));

      $where = array();

      $query->where($where);

      if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];

          $query->where('title',"LIKE","%$value%")
              ->orWhere('value',"LIKE","%$value%")          
              ->orWhere('type',"LIKE","%$value%");
      }

      //Order
        $orderByField = "settings.id";
        $orderBy = 'desc';
          
        if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {
                        
           $orderBy = $request->get('order')[0]['dir'];

        }

        if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

            if ($request->get('order')[0]['column'] == 0) {
                
              $orderByField = "settings.id";
            
            }else if($request->get('order')[0]['column'] == 1){

              $orderByField = "settings.title";
            
            }else if($request->get('order')[0]['column'] == 2){

              $orderByField = "settings.value";
            
            }else if($request->get('order')[0]['column'] == 2){

              $orderByField = "settings.type";
            
            }
        }
      
      $total = $query->where($where)->count();

      $info = $query->where($where)
                  ->orderBy($orderByField,$orderBy)
                  ->skip($start)
                  ->take($length)
                  ->get(); 

      $data = array();
      $sno = $start;
      foreach($info as $r) {

        //delete url  earnings
            $delet_Url = "'admin/delete_activity/$r->id/configuration','$r->id'";

            $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete Configuration</a>';

            //edit url
          $configuration_id = $r->id; 
          $editUrl = "add-configuration?configurationId=$configuration_id";

          $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit Configuration" title="Edit Configuration" href="'.$editUrl.'"><i class="fa fa-edit"></i>Edit Configuration</a>';

           $data[] = array(             
             $sno = $sno+1,
             ucfirst($r->title),
             ucfirst($r->value),
             '<div class="manage_drop_d">
          <div class="dropdown">
            <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span>
            </button>
              <ul class="dropdown-menu dropdown-menu-right" role="menu"> 

                <li>'.$edit_url.'</li>
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

      echo json_encode($output);
      exit(); 
    }

    /*
     * Name: add configurations
     * Create Date: 27 Mar 2018
     * Purpose: 
     */
    public function addConfiguration(Request $request){

      $editId = $request->get('configurationId');

      if(!empty($editId)){

          $data['title']    = "Edit Configuration";
          $data['info']     =  Configuration::where(array('id' => $editId))->first();


      }else{

        $data['info'] = array();
        $data['title']    = "Add Configuration";
      }
      if($request->isMethod('post')){

        $configurationId = $request->get('configuration_id');

        $validationArr =  array('title'          => 'required',
                                'value'          => 'required');

        $validator = Validator::make($request->all(),$validationArr);

        if ($validator->fails()) {

          if(empty($configurationId)){
            
            return Redirect::to('admin/add-configuration')->withErrors($validator)->withInput();

          }else{
               
              return Redirect::to("admin/add-configuration?id=$configurationId")->withErrors($validator)->withInput();
          }

        }else {
          
          $where = array('id' => $configurationId);

          $data = array(

              'title'                   => $request->get('title'),
              'value'                   => $request->get('value'),

          );

          if ($request->get('type') !="") {
            
            $data['type'] = $request->get('type');              
          
          }
          
          if(empty($configurationId)){

            $data['created_at'] = date('Y-m-d H:i:s');
          }
          $check = Configuration::updateOrCreate($where, $data);

          if ($check) {

              if(empty($configurationId)){

                return Redirect::to("admin/configuration")->withSuccess('Great! info has been added.');

              }else{

                return Redirect::to("admin/configuration")->withSuccess('Great! info has been updated.');
              }
          }
        }
    }else{
      
      return view("Admin::add.add_configuration",$data);
    }
  }
  
  /*
  * Name: restore_element
  * Create Date: 28 march 2018
  */    

  public function restore_element($id, $function){

    $res = array('success' => 0, 'msg' => 'Sorry! Failed to restore record.');

    if ($function == 'passenger') {

      $check = Users::where('id', $id)->restore();

      $res['success'] = 1;
      $res['msg'] = 'Great! Record restored successfully.';        
    
    }

    if ($function == 'driver') {

      $check = Users::where('id', $id)->restore();
      $check = DriverInfo::where('driver_id', $id)->restore();

      $res['success'] = 1;
      $res['msg'] = 'Great! Record restored successfully.';        
    
    }   

    return Response::json($res);
  }

  public function liveTrackingDrivers(Request $request){

    $data['title'] = "Drivers Live Tracking";
     
    return view('Admin::detail.live_tracking_drivers', $data);

  }

  public function liveTrackingRides(Request $request){

    $data['title'] = "Rides Live Tracking";
     
    return view('Admin::detail.live_tracking_rides', $data);

  }

  public function earnings(Request $request){

    $data['title']   = "Earnings";
    $data['heading'] = "Earnings";

    $data['earning_info'] = Users::where('user_type','Driver')->get(['first_name','last_name']);

    return view('Admin::list.earnings', $data);

  }

  public function earningsData(Request $request){

    
    $start_date = $request->start_date;
    $end_date   = $request->end_date;  
 
    $query = Ride::query();

      // Datatables Variables
      $draw   = intval($request->get("draw"));
      $start  = intval($request->get("start"));
      $length = intval($request->get("length"));

      $where = array();

      if ($request->get('search')['value'] != "") {

          $value = $request->get('search')['value'];

          $query->where(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"),"LIKE","%$value%");

      }

      $s = "";

      if(!empty($start_date)){
        
        $start_date = date('Y-m-d', strtotime($start_date));
       // $query->where('rides.created_at','>=',$start_date);
        
        $s = " AND DATE(rides.created_at) >= '$start_date'";

      }

      if(!empty($end_date)){
        
        $end_date = date('Y-m-d', strtotime($end_date));
        //$query->where('rides.created_at','<=',$end_date);
        $s .= " AND DATE(rides.created_at) <= '$end_date'";
      
      }

      //Order
      $orderByField = "earnings";
      $orderBy = 'desc';  
      
      if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {
                    
       $orderBy = $request->get('order')[0]['dir'];
    }

        if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

            if ($request->get('order')[0]['column'] == 0) {
                
              $orderByField = "users.id";
            
            }else if($request->get('order')[0]['column'] == 1){

              $orderByField = "users.first_name";
            
            }else if($request->get('order')[0]['column'] == 2){

              $orderByField = "users.last_name";
            
            }           

        }
           
      $field = array('users.id as driver_id','users.first_name','users.last_name',
                  DB::Raw("(SELECT COUNT(rides.id) FROM rides WHERE rides.driver_id = users.id $s) AS total_rides"),
                  DB::Raw("(SELECT COUNT(rides.id) FROM rides WHERE rides.driver_id = users.id AND ride_status = 'Completed' $s) AS completed_rides"),
                  DB::Raw("(SELECT SUM(pay_amount) FROM rides WHERE rides.driver_id = users.id AND ride_status = 'Completed' $s) AS earnings"));

      $query->join('users', 'users.id', '=', 'rides.driver_id')
            //->orderBy('earnings')
            ->groupBy('rides.driver_id');
      
      $total = $query->count(['rides.id']); 
      
      $info = $query->orderBy($orderByField,$orderBy)
                ->skip($start)
                ->take($length)
                ->withTrashed()
                ->orderBy('earnings')
                ->get($field);

      $data = array();
      $sno  = $start;
      
      foreach($info as $r) {                               

          $driverDetailUrl = url('admin/driver-detail/'.$r->driver_id);
          
          $data[] = array(             
            $sno = $sno+1,
            ucfirst('<a href="'.$driverDetailUrl.'" target="_blank" title="Driver Detail">'.$r->first_name.' '.$r->last_name.'</a>'),
            $r->completed_rides,
            $r->total_rides,
            Config::get('constants.CURRENCY').number_format($r->earnings, 2)
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

    

  }//End of earnings Data function

public function roleManagement(Request $request,$id) {

  $data['title'] = 'Role Management';

  $data['info'] = Users::where('id',$id)->first();
  
  return view("Admin::detail.role_management",$data);

}/*Role Management*/

public function setStaffPermission(Request $request){

  $res = array("success" => 0);

  $where = array('id' => $request->get('staffId'));

  $value = intval($request->get('value'));


  if ($request->get('type') == 'ADD') {

    
      $check = Users::where($where)->increment('permission',$value);
      

  }else {

      $check = Users::where($where)->decrement('permission',$value);
  }

  if ($check) {

    $res = array("success" => 1);
    
  }
      
  return Response::json($res);

}/*End Set Admin Permission*/

}
