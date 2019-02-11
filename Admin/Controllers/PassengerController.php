<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\{Users};
use Validator,Redirect,Session,URL,Config,Image,File,Excel;
/*
* Name: PassengerController
* Create Date: 13 March
*/
class PassengerController extends Controller
{

   /*
    * Name: passenger listing,
    * Create Date: 13 Mar 2018
    */
    public function passengers(Request $request){

        $data['title']    = 'Passengers';
        $data['heading']  = 'Passengers List';
        return view("Admin::list.passengers", $data);
    }
    
    public function passengersData(Request $request){

     $query = Users::query();

    // Datatables Variables
      $draw   = intval($request->get("draw"));
      $start  = intval($request->get("start"));
      $length = intval($request->get("length"));

      $where = array('user_type' => 'Passenger');

      $query->where($where);

      if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];

          /*$query->where('first_name',"LIKE","%$value%")
                ->orWhere('mobile_number',"LIKE","%$value%")
                ->orWhere('email',"LIKE","%$value%");*/
                
          $query->where(function ($query) use ($value){
                    $query->where('first_name',"LIKE","%$value%")
                          ->orWhere('mobile_number',"LIKE","%$value%")
                          ->orWhere('email',"LIKE","%$value%");
                          
                          }) 
                          ->get();


      }//search if end

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
            
            }else if($request->get('order')[0]['column'] == 2){

              $orderByField = "users.last_name";
            
            }else if($request->get('order')[0]['column'] == 2){

              $orderByField = "users.mobile_number";
            
            }else if($request->get('order')[0]['column'] == 2){

              $orderByField = "users.email";
            
            }

        }
      
      $total = $query->where($where)->count();

      $info = $query->where($where)
                  ->orderBy($orderByField,$orderBy)
                  ->skip($start)
                  ->take($length)
                  ->withTrashed()
                  ->get(); 
                  
      $data = array();
      $sno = $start;

      foreach($info as $r) {            

        //delete url  
          $delet_Url = "'admin/delete_activity/$r->id/passenger','$r->id'";

          $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete Passenger</a>';

            //edit url 
          $url = URL::to('admin/edit-passenger/'.$r->id);      
            $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit Passenger" title="Edit Passenger" href="'.$url.'"><i class="fa fa-edit"></i>Edit Passenger</a>';

          //customer Detail
          $urlDetail = URL::to('admin/passenger-detail/'.$r->id);      
          $detail_url = '<a type="" class="" style="text-decoration: none;" data-title ="Passenger Detail" title="Passenger Detail" href="'.$urlDetail.'"><i class="fa fa-info-circle"></i> Passenger Details</a>';
          
            //showcategory image
          $file = "public/uploads/profile/".$r->profile_image;

          if($r->profile_image != ''){

              if(file_exists($file)){

                  $profileImage  = URL::to(Config::get('constants.PROFILE_IMAGE').$r->profile_image);

                  $fanxyBoxImage  = Config::get('constants.PROFILE_IMAGE').$r->profile_image;

                  $showImage = '<a class="fancybox" rel="" href="'.$profileImage.'"><img src="'.$profileImage.'" class="img-square" height ="70px;" width="70px;"></a>';

              }else{

                  $NoImage  = URL::to(Config::get('constants.NO_IMAGE').'user-no-image.png');

                  $showImage = ' <img src="'.$NoImage.'" class="img-square" height ="70px;" width="70px;">';
              }
          }else{

                  $NoImage  = URL::to(Config::get('constants.NO_IMAGE').'user-no-image.png');

                  $showImage = ' <img src="'.$NoImage.'" class="img-square" height ="70px;" width="70px;">';
          }
          if(!empty($r->email)){

              $email = $r->email;
          
          }else{
          
            $email = "N/A";
          
          }
          
          if(!empty($r->gender)){

              $gender = $r->gender;
          
          }else{
          
            $gender   = "N/A";
          
          }

          $actionBtn = "";

          if ($r->trashed()) {

            $showStatus = '<span class = "badge bg-red">Deleted</span>';
            
            $restore_url = "'admin/restore_element/$r->id/passenger','$r->id'";

            $actionBtn = '<button type="button" class="btn btn-danger btn-flat dropdown-toggle" data-toggle="dropdown" title="Restore" onclick="restore_element('.$restore_url.')" href="javascript:void(0)">Restore</button>';

          }else{

          //Status
            //print_r($r->status);die;
          if($r->status == 1){

            $r->status = "Active";
            $make = $r->status == "Active" ? 'Inactive' : 'Active';

            $statusUrl = URL::to('admin/status_activity',[$r->id, $make, 'passenger']);

            $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i> Make '.$make.'</a>';
            
            
            $showStatus = '<span class = "badge '. ($r->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';

          }else{

            $r->status = "Inactive";//print_r($r->status);die;
            $make = $r->status == "Active" ? 'Inactive' : 'Active';

            $statusUrl = URL::to('admin/status_activity',[$r->id, $make, 'passenger']);

            $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i> Make '.$make.'</a>';
            
            $showStatus = '<span class = "badge '. ($r->status == "Aactive" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';
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
          </div>   ';
        }
          
           $data[] = array(             
             $sno = $sno+1,
             $showImage,
             ucfirst($r->first_name.' '.$r->last_name),
             '+'.$r->country_code.'-'.$r->mobile_number,
             $email,
             $gender,
             $showStatus,
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
  * Name: Passenger Deatil
  * Create Date: 13 Mar 2018
  * Purpose: 
  */
  public function passengerDetail($passengerId,Request $request){

     $data['title'] = "Passenger Details";

        $field = array('users.id','first_name','last_name','country_code','mobile_number','email','user_type','profile_image','users.status','verification_code','mobile_verification_code','gender','country_name','state_name','city_name','address','users.created_at','mobile_verification_status');

        $data['passengerInfo'] = Users::where(array('users.id' => $passengerId,
                                                    'user_type' => 'Passenger'))
                                                  ->leftjoin('country','users.country_id','=','country.id')
                                                  ->leftjoin('states','users.state_id','=','states.id')
                                                  ->leftjoin('city','users.city_id','=','city.id')
                                                  ->first($field); 

        $data['heading'] = "Passenger Details";
        $data['title'] = "Passenger Details";
       return view("Admin::detail.passenger_detail",$data);               
  }

  /*
  * Name: Add Passenger
  * Create Date: 14 Mar 2018
  * Purpose: 
  */
  public function addPassenger(Request $request){

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
                    
        ]);
        if ($validator->fails()) {

            return Redirect::to('admin/add-passenger')->withErrors($validator)->withInput();

        } else {

            $insert = array('first_name'      => $request->first_name,
                            'last_name'       => $request->last_name,
                            'country_code'    => $request->country_code,
                            'mobile_number'   => $request->mobile_number,
                            'email'           => $request->email,
                            'password'        => bcrypt($request->password),
                            'gender'          => $request->gender,
                            'user_type'       => 'Passenger',
                            'status'          => '1',
                            'mobile_verification_status'  => 'NotVerified',
                            'email_verification_status'   => 'NotVerified',
                            "mobile_verification_code"    => random_code(),
                            'created_at'      => date('Y-m-d H:i:s')
                          );

                          //upload multiple image
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
                          //send mail to drive
                            /*$data['email']    = $request->email;
                            $data['name']     = $request->first_name.' '.$request->last_name;
                            $dara['password'] = $request->password;
                            $name             = $request->first_name.' '.$request->last_name;
                            $userid           = $request->email;
                            $password         = $request->password;


                            Mail::send('Admin::email.driver_rgistration', ['userid' => $userid,'password' => $password,'name' => $name], function ($message) use($data)
                            {
                                $message->to($data['email'], $data['name'])->subject('Welcome to Pallardy!');
                            
                            });*/
                            return Redirect::to("admin/passengers")->withSuccess('Great! info has been added.');
                          }else{

                            return Redirect::to("admin/add-passenger")->withFail('Something went to wrong.');
                          } 
          }

      }else{

        $data['title'] = "Add Passenger";
        return view("Admin::add.add_passenger",$data);
      }
  }

  
  public function editPassenger($passengerId,Request $request){

      if($request->isMethod('post')){

          $validator = Validator::make($request->all(), [
                    'first_name'                  => 'required|regex:/^[\pL\s\-]+$/u',
                    'last_name'                   => 'required|regex:/^[\pL\s\-]+$/u',
                    'email'                       => "nullable|email|unique:users,email,$passengerId",
                    'country_code'                => "required",
                    'mobile_number'               => "required|numeric|unique:users,mobile_number,$passengerId",
                    'verification_status'         => 'required',
                    'profile_image'               => 'mimes:jpeg,bmp,png,jpg|max:50240',
                    'gender'                      => 'required'                    
          ]);
          if ($validator->fails()) {
              
              return Redirect::to("admin/edit-passenger/$passengerId")->withErrors($validator)->withInput();

          } else {
             
              $update =  array('first_name'     => $request->first_name,
                                'last_name'     => $request->last_name,
                                'country_code'  => $request->country_code,
                                'mobile_number' => $request->mobile_number,
                                'email'         => $request->email,
                                'gender'        => $request->gender,
                                'mobile_verification_status' => $request->verification_status);

              $file = $request->file('profile_image');
              if($file && $file != "")
              {

                //old image
                  $oldFile = Users::where(array('id' => $passengerId))->first(['profile_image']);

                  File::delete('public/uploads/profile/'.$oldFile->profile_image);
                  File::delete('public/uploads/profile/thumbnail/'.$oldFile->profile_image);

                $profileImage  = getTimeStamp().".".$file->getClientOriginalExtension();

                Image::make($file)->resize(200, 200)->save('public/uploads/profile/thumbnail/'.$profileImage);

                $file->move('public/uploads/profile', $profileImage);

                  $update['profile_image']  = $profileImage;

              }

             $update =  Users::where(array('id' => $passengerId))->update($update);

              if($update){

                return Redirect::to("admin/passengers")->withSuccess('Great! info has been updated.');

              }else{

                return Redirect::to("admin/edit-passenger/$passengerId")->withFail('Something went to wrong.');
              }
          } 
      }else{

        $data['title']        = "Edit Passenger";
        $data['heading']      = "Edit Passenger";
        $data['passengerInfo']   = Users::where(array('id' => $passengerId,'user_type' => 'Passenger'))->first();
        //d($data);

        return view("Admin::edit.edit_passenger",$data);
      }
  }


  /*
  * Name: Passenger Export
  * Create Date: 29 Mar 2018
  * Purpose: for all passenger information Export
  */
  public function passengerExport(Request $request){

      $field = array('users.first_name','users.last_name','users.email','users.mobile_number','users.status','users.gender','users.address');

      $data = Users::where('user_type','Passenger')->get($field);
      
      $filename = 'Passenger List '.date('d-m-y');

      Excel::create($filename, function($excel) use($data) {

        $excel->sheet('list', function($sheet) use($data) {
           
        $sheet->cell('A1', function($cell) {$cell->setValue('Passenger Name');   });
        $sheet->cell('B1', function($cell) {$cell->setValue('Email');         });
        $sheet->cell('C1', function($cell) {$cell->setValue('Mobile Number'); });
        $sheet->cell('D1', function($cell) {$cell->setValue('Status');        });
        $sheet->cell('E1', function($cell) {$cell->setValue('Gender');        });
        $sheet->cell('F1', function($cell) {$cell->setValue('Address');       });
        
       
            if (!empty($data)) {
                
                foreach ($data as $key => $value) {

                  $passenger_name= $value->first_name." ".$value->last_name;              
                  $passenger_email= $value->email;
                  $mobile_number = $value->mobile_number;
                  
                  if ($value->status=='1') {

                    $status = "Active";                    
                  }else{
                    $status = "Inactive";
                  }
                  
                  $gender        = $value->gender;
                  $address       = $value->address;
            
                  $i=$key+2;
                  $sheet->cell('A'.$i,$passenger_name);
                  $sheet->cell('B'.$i,$passenger_email);
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



  }/*Passenger Export End*/


}
