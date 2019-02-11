<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\{Users,Vehicle,DriverInfo,Country,DrivingLicense};
use Validator,Redirect,Response,Session,Image,File,URL,Config,DB,Excel,Mail;
/*
* Name: DriverController
* Create Date: 13 March
*/
class DriverController extends Controller
{

    /*
    * Name: passengers
    * Create Date: 13 March 2018
    */
    
    public function drivers(Request $request){
                                                      
                                                  
        $data['title'] = 'Drivers';
        $data['heading'] = 'Drivers List';
        return view("Admin::list.drivers", $data);
    }
    
    public function driversData(Request $request){

     $query = Users::query();

    // Datatables Variables
      $draw   = intval($request->get("draw"));
      $start  = intval($request->get("start"));
      $length = intval($request->get("length"));

      $where = array('user_type' => 'Driver');

      $query->where($where);

      if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];

          
          $query->where(function ($query) use ($value){

            $query->where('first_name',"LIKE","%$value%")
                ->orWhere('last_name',"LIKE","%$value%")                
                ->orWhere('email',"LIKE","%$value%")
                ->orWhere('mobile_number',"LIKE","%$value%");
          }) 
                ->get();
      }

        //Order
        $orderByField = "users.id";
        $orderBy = 'desc';
          
        if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {
                        
           $orderBy = $request->get('order')[0]['dir'];

        }

        if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

            if ($request->get('order')[0]['column'] == 0) {
                
              $orderByField = "users.id";
            
            }else if($request->get('order')[0]['column'] == 1){ 

              $orderByField = "users.first_name";
            
            }

        }
      
      $total = $query->where($where)->count();

      $info = $query->where($where)
                  ->join('driver_info','driver_info.driver_id', '=', 'users.id')
                  ->orderBy($orderByField,$orderBy)
                  ->skip($start)
                  ->take($length)
                  ->withTrashed()
                  ->get();

      $data = array();
      $sno = $start;
      $online_status = "";

      foreach($info as $r) {
                 
        //delete url  
          $delet_Url = "'admin/delete_activity/$r->driver_id/driver','$r->driver_id'";

          $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete Driver</a>';

            //edit url 
          $url = URL::to('admin/edit-driver/'.$r->driver_id);      
            $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit Driver" title="Edit Driver" href="'.$url.'">
            <i class="fa fa-edit"></i>Edit Driver</a>';

          //customer Detail
          $urlDetail = URL::to('admin/driver-detail/'.$r->driver_id);      
          $detail_url = '<a type="" class="" style="text-decoration: none;" data-title ="Driver Detail" title="Driver Detail" href="'.$urlDetail.'"><i class="fa fa-info-circle"></i> Driver Detail</a>';
          
            //showcategory image
          $file = "public/uploads/profile/".$r->profile_image;

          if($r->profile_image != ''){

              if(file_exists($file)){

                  $profileImage  = URL::to(Config::get('constants.PROFILE_IMAGE').$r->profile_image);

                  $fanxyBoxImage  = Config::get('constants.PROFILE_IMAGE').$r->profile_image;

                  $showImage = '<a class="fancybox" rel="lightbox" href="'.$profileImage.'"><img src="'.$profileImage.'" class="img-square" height ="70px;" width="70px;"></a>';

              }else{

                  $NoImage  = URL::to(Config::get('constants.NO_IMAGE').'user-no-image.png');

                  $showImage = ' <img src="'.$NoImage.'" class="img-square" height ="70px;" width="70px;">';
              }
          }else{

                  $NoImage  = URL::to(Config::get('constants.NO_IMAGE').'user-no-image.png');

                  $showImage = ' <img src="'.$NoImage.'" class="img-square" height ="70px;" width="70px;">';
          }
          
          if ($r->trashed()) {

            $showStatus = '<span class = "badge bg-red">Deleted</span>';
            
            $restore_url = "'admin/restore_element/$r->driver_id/driver','$r->driver_id'";

            $actionBtn = '<button type="button" class="btn btn-danger btn-flat dropdown-toggle" data-toggle="dropdown" title="Restore" onclick="restore_element('.$restore_url.')" href="javascript:void(0)">Restore</button>';

          }else{

            //show status and status activityp
            if($r->status == 1){

              $r->status = "Active";
              $make = $r->status == "Active" ? 'Inactive' : 'Active';

              $statusUrl = URL::to('admin/status_activity',[$r->driver_id, $make, 'driver']);

              $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i> Make '.$make.'</a>';
              
              $showStatus = '<span class = "badge '. ($r->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';
            }else{

              $r->status = "Inactive";
              $make = $r->status == "Active" ? 'Inactive' : 'Active';

              $statusUrl = URL::to('admin/status_activity',[$r->driver_id, $make, 'driver']);

              $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i> Make '.$make.'</a>';
              
              $showStatus = '<span class = "badge '. ($r->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';
            }


            if ($r->online_status == "Online") {
            
              $online_status = '<span class="badge bg-green">Online</span>';
            
            }else{

              $online_status = '<span class="badge bg-red">Offline</span>';

            }

            //Action button
            $actionBtn = '<div class="manage_drop_d">
          <div class="dropdown">
            <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span>
            </button>
              <ul class="dropdown-menu dropdown-menu-right" role="menu"> 

                <li>'.$status.'</li>
                <li>'.$edit_url.'</li>
                <li>'.$detail_url.'</li>
                <li>'.$deleteUrl.'</li>
              </ul>           
          </div>
          </div> ';
          }

           $data[] = array(             
            $sno = $sno+1,
            $showImage,
            ucfirst($r->first_name.' '.$r->last_name),
            '+'.$r->country_code.'-'.$r->mobile_number,
            $r->email,
            $r->gender,
            $showStatus,
            $online_status,
            $actionBtn                   

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
    * Name: passengers
    * Create Date: 14 March 2018
    */
    public function addDriver(Request $request){

        if($request->isMethod('post')){

        $validator = Validator::make($request->all(), [
                    'first_name'      => 'required|regex:/^[\pL\s\-]+$/u',
                    'last_name'       => 'required|regex:/^[\pL\s\-]+$/u',
                    'profile_image'   => 'mimes:jpeg,jpg,png|max:50240',
                    'country_code'    => 'required|numeric',
                    'mobile_number'   => 'required|numeric|unique:users',
                    'email'           => 'nullable|email|unique:users',
                    'password'        => 'required',
                    'gender'          => 'required',
                    'select_vehicle'  => 'required',
                    'address'         => 'required',
                    'country'         => 'required',
                    'state'           => 'required',
                    'city'            => 'required',
                    'license_number'  => 'required',
                    'vehicle_type'    => 'required',
                    'issued_on'       => 'required',
                    'expiry_date'     => 'required',
                    'license_image'   => 'required|mimes:jpeg,jpg,png|max:50240',

                    
        ]);
        if ($validator->fails()) {

            return Redirect::to('admin/add-driver')->withErrors($validator)->withInput();

        }else{

            $insert = array(
              'first_name'                  => $request->first_name,
              'last_name'                   => $request->last_name,
              'country_code'                => $request->country_code,
              'mobile_number'               => $request->mobile_number,
              'email'                       => $request->email,
              'password'                    => bcrypt($request->password),
              'gender'                      => $request->gender,
              'user_type'                   => 'Driver',
              'status'                      => '1',
              'mobile_verification_code'    => random_code(),
              'email_verification_code'     => random_code(),
              'mobile_verification_status'  => "Verified",//'NotVerified',
              'email_verification_status'   => 'NotVerified',
              'country_id'                  => $request->country,
              'state_id'                    => $request->state,
              'city_id'                     => $request->city,
              'address'                     => $request->address,
              'created_at'                  => date('Y-m-d H:i:s')
            );

            //upload image
            if($files=$request->file('profile_image')) {

                $destinationPath = 'public/uploads/profile/'; // upload path

                $profileImage = getTimeStamp().".".$files->getClientOriginalExtension(); // getting image extension

                Image::make($files)->resize(200, 200)->save('public/uploads/profile/thumbnail/'.$profileImage);     
                //resize image
                $files->move($destinationPath, $profileImage);

                $insert['profile_image'] = $profileImage;
            }

            $lastId =  Users::insertGetId($insert);

          if($lastId){

              // add driver info
              $inserInfo = array('driver_id'      => $lastId,
                                  'online_status' => 'Offline',
                                  'vehicle_id'    => $request->get('select_vehicle'),
                                  'created_at'    => date('Y-m-d H:i:s'));

              DriverInfo::insert($inserInfo);

              //add driver license info

              $insertLicenseInfo = array('driver_id'        => $lastId,
                                        'license_number'  => $request->license_number,
                                        'vehicle_type'    => $request->vehicle_type,
                                        'issued_on'       => $request->issued_on,
                                        'expiry_date'     => $request->expiry_date,
                                        'created_at'      => date('Y-m-d H:i:s'));

               //upload license image
              if($Licensefiles=$request->file('license_image')) {

                  $destinationPath = 'public/uploads/license_image/'; // upload path

                  $licenseImage = getTimeStamp().".".$Licensefiles->getClientOriginalExtension(); // getting image extension

                  Image::make($Licensefiles)->resize(200, 200)->save('public/uploads/license_image/thumbnail/'.$licenseImage);     
                  //resize image
                  $Licensefiles->move($destinationPath, $licenseImage);

                  $insertLicenseInfo['license_image'] = $licenseImage;
              }
              
              DrivingLicense::insert($insertLicenseInfo);

              //Send Mail
              if ($request->email != "") {
                
                $emailData['email'] = $request->email;
                $emailData['name']  = $request->first_name.' '.$request->last_name;

                Mail::send('email.welcome', $emailData, function ($message) use($emailData){
                    $message->to($emailData['email'])
                            ->subject('Welcome to Eco Gadi!');            
                });
              }
              
              return Redirect::to("admin/drivers-list")->withSuccess('Great! info has been added.');
              
          }else{

            return Redirect::to("admin/add-driver")->withFail('Something went to wrong.');
          }
        }            

      }else{

        $data['title'] = "Add Driver";
        
        $data['vehicleData'] = Vehicle::where(array('status' => '1'))
                                      ->leftjoin('driver_info','driver_info.vehicle_id','=','vehicles.id')
        /*->where('driver_info.id', null)*/
                                      ->where('driver_info.id', null)
                                                                            
                                      ->get(['vehicles.id','vehicles.name']);
          //print_r($data['vehicleData']);die;

        $data['country']    = Country::where(array('status' => 'Active'))
                                      ->select('id','country_name')->get();
    
        return view("Admin::add.add_driver",$data);
    
      }
    
    }

    /*
  * Name: driver Deatil
  * Create Date: 13 Mar 2018
  * Purpose: 
  */
  public function driverDetail($driverId,Request $request){     

        $data['driverInfo'] = DriverInfo::where(array('driver_id' => $driverId))
                                          ->with('assigned_vehicle','driver_info','driver_license')
                                          ->first(); 
                                                      
        $data['heading']  = "Driver Detail";
        $data['title']    = "Driver Detail";
       return view("Admin::detail.driver_detail",$data);               
  }

  /*
  * Name: Edit Driver
  * Create Date: 14 Mar 2018
  * Purpose: 
  */
  public function editDriver($driverId,Request $request){

      if($request->isMethod('post')){

          $validator = Validator::make($request->all(), [
                    'first_name'                  => 'required|regex:/^[\pL\s\-]+$/u',
                    'last_name'                   => 'required|regex:/^[\pL\s\-]+$/u',
                    'email'                       => "nullable|email|unique:users,email,$driverId",
                    'country_code'                => "required",
                    'mobile_number'               => "required|numeric|unique:users,mobile_number,$driverId",
                    'verification_status'         => 'required',
                    'profile_image'               => 'mimes:jpeg,bmp,png,jpg|max:50240',
                    'gender'                      => 'required',
                    'select_vehicle'              => 'required',
                    'country'                     => 'required',
                    'state'                       => 'required',
                    'city'                        => 'required',
                    'address'                     => 'required',
                    'license_number'              => 'required',
                    'vehicle_type'                => 'required',
                    'issued_on'                   => 'required',
                    'expiry_date'                 => 'required',
                    'license_image'               => 'mimes:jpeg,jpg,png|max:50240',

          ]);
          if ($validator->fails()) {
              
              return Redirect::to("admin/edit-driver/$driverId")->withErrors($validator)->withInput();

          } else {
              
              $update =  array('first_name'     => $request->first_name,
                                'last_name'     => $request->last_name,
                                'country_code'  => $request->country_code,
                                'mobile_number' => $request->mobile_number,
                                'email'         => $request->email,
                                'gender'        => $request->gender,
                                'mobile_verification_status' => $request->verification_status,
                                'country_id'    => $request->country,
                                'state_id'      => $request->state,
                                'city_id'       => $request->city,
                                'address'       => $request->address);

              $file = $request->file('profile_image');
              if($file && $file != "")
              {

                //old image
                  $oldFile = Users::where(array('id' => $driverId))->first(['profile_image']);

                  File::delete('public/uploads/profile/'.$oldFile->profile_image);
                  File::delete('public/uploads/profile/thumbnail/'.$oldFile->profile_image);

                $profileImage  = getTimeStamp().".".$file->getClientOriginalExtension();

                Image::make($file)->resize(200, 200)->save('public/uploads/profile/thumbnail/'.$profileImage);

                $file->move('public/uploads/profile', $profileImage);

                  $update['profile_image']  = $profileImage;

              }

             $check =  Users::where(array('id' => $driverId,'user_type' => 'Driver'))->update($update);

              if($check){
                $vehicleId = $request->select_vehicle;
                
                //update driver info
                DriverInfo::where(array('driver_id' => $driverId))->update(['vehicle_id' => $vehicleId]);

                //update driver license info
                $updateLicense = array('license_number' => $request->license_number,
                                        'vehicle_type'  => $request->vehicle_type,
                                        'issued_on'     => $request->issued_on,
                                        'expiry_date'   => $request->expiry_date);

                $licensefile = $request->file('license_image');
                if($licensefile && $licensefile != "")
                {

                  //old image
                    $oldLicenseFile = DrivingLicense::where(array('driver_id' => $driverId))->first(['license_image']);

                    File::delete('public/uploads/license_image/'.$oldLicenseFile->license_image);
                    File::delete('public/uploads/license_image/thumbnail/'.$oldLicenseFile->license_image);

                    $licenseImage  = getTimeStamp().".".$licensefile->getClientOriginalExtension();

                    Image::make($licensefile)->resize(200, 200)->save('public/uploads/license_image/thumbnail/'.$licenseImage);

                    $licensefile->move('public/uploads/license_image', $licenseImage);

                    $updateLicense['license_image']  = $licenseImage;

                }


                DrivingLicense::where(array('driver_id' => $driverId))->update($updateLicense);

                return Redirect::to("admin/drivers-list")->withSuccess('Great! info has been updated.');

              }else{

                return Redirect::to("admin/edit-driver/$driverId")->withFail('Something went to wrong.');
              }
          } 
      }else{

        $data['title']        = "Edit Driver";
        $data['heading']      = "Edit Driver";

        $data['info'] = DriverInfo::where(array('driver_id' => $driverId))
                    ->has('driver_info')
                    ->with('driver_info')
                    ->with('driver_license')                    
                    ->first();
                  // print_r($data['info']);die;
        
        $vehicleId = isset($data['info']->vehicle_id) ? $data['info']->vehicle_id: "";
        
        $data['vehicleData'] = Vehicle::where(array('status' => '1'))
                                      ->leftjoin('driver_info','driver_info.vehicle_id','=','vehicles.id')
                                      ->where(function($q)use($vehicleId){
                                        $q->where('driver_info.id', null)
                                        ->orwhere('driver_info.vehicle_id', $vehicleId);
                                      })                                      
                                      ->get(['vehicles.id','vehicles.name']);

        $data['country']    = Country::where(array('status' => 'Active'))
                                      ->select('id','country_name')->get();
              
        return view("Admin::edit.edit_driver",$data);
      }
  }

  /*
  * Name: checkDriverMoving
  * Create Date: 27 March 2018
  * Purpose: This function is used to check driver is moving or not
  */    

  public function checkDriverMoving(Request $request){

    $res = array("success" => false);

    $getLatLong = Users::where('id', $request->driver_id)
            ->first(['latitude', 'longitude']);

    if (!empty($getLatLong)) {
      
      $res = array("success" => true, "result" => $getLatLong);

    }
    return Response::json($res);

  }


  /*
  * Name: driverExport
  * Create Date: 29 March 2018
  * Purpose: Download all driver Information
  */    
  public function driverExport(Request $request){
    
    $field = array('users.first_name','users.last_name','users.email','users.mobile_number','users.status','users.gender','users.address');

    $data = DriverInfo::join('users','driver_info.driver_id','=','users.id')
                       ->get($field);/*echo"<pre>";print_r($data);die;*/
    
    $filename = 'Driver List '.date('d-m-y');

    Excel::create($filename, function($excel) use($data) {

        $excel->sheet('list', function($sheet) use($data) {
           
        $sheet->cell('A1', function($cell) {$cell->setValue('Driver Name');   });
        $sheet->cell('B1', function($cell) {$cell->setValue('Email');         });
        $sheet->cell('C1', function($cell) {$cell->setValue('Mobile Number'); });
        $sheet->cell('D1', function($cell) {$cell->setValue('Status');        });
        $sheet->cell('E1', function($cell) {$cell->setValue('Gender');        });
        $sheet->cell('F1', function($cell) {$cell->setValue('Address');       });
        
       
            if (!empty($data)) {
                
                foreach ($data as $key => $value) {

                  $dirver_name   = $value->first_name." ".$value->last_name;              
                  $dirver_email  = $value->email;
                  $mobile_number = $value->mobile_number;
                  
                  if ($value->status=='1') {

                    $status = "Active";                    
                  }else{
                    $status = "Inactive";
                  }
                  
                  $gender        = $value->gender;
                  $address       = $value->address;
            
                  $i=$key+2;
                  $sheet->cell('A'.$i,$dirver_name);
                  $sheet->cell('B'.$i,$dirver_email);
                  $sheet->cell('C'.$i,$mobile_number);
                  $sheet->cell('D'.$i,$status);
                  $sheet->cell('E'.$i,$gender);
                  $sheet->cell('F'.$i,$address);
                }
            }

        $sheet->cells('A1:F1', function($cells) {
            // Set font
            $cells->setFont(array(
                    'family' => 'Calibri',
                    'size' => '10',
                    
            
            ));
        });
                   
        $sheet->setHeight(10, 15);
                    
      });
    })->export('xls');  
    

  }/*Driver Export End*/
  
}
