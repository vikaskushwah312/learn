<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\{Brand,VehicleModel,Vehicle,ServiceType};
use Validator,Redirect,Session,Image,File,URL,Config,Response,Excel;
/*
* Name: VehicleController
* Create Date: 15 March
*/
class VehicleController extends Controller
{

    /*
    * Name: brand list
    * Create Date: 15 March 2018
    */
    public function brandList(Request $request){

        $data['title'] = 'Brand';
        $data['heading'] = 'Brand List';
        return view("Admin::list.brand_list", $data);
    }
    
    public function brandListData(Request $request){

     $query = Brand::query();

    // Datatables Variables
      $draw   = intval($request->get("draw"));
      $start  = intval($request->get("start"));
      $length = intval($request->get("length"));

      $where = array();

      $query->where($where);

      if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];

        $query->where(function ($query) use ($value){

          $query->where('brand.name',"LIKE","%$value%");
               
        }) 
               ->get();
      }

      //Order
        $orderByField = "brand.id";
        $orderBy = 'desc';
          
        if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {
                        
           $orderBy = $request->get('order')[0]['dir'];

        }

        if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

            if ($request->get('order')[0]['column'] == 0) {
                
              $orderByField = "brand.id";
            
            }else if($request->get('order')[0]['column'] == 1){

              $orderByField = "brand.name";
            
            }

        }
      

      $info = $query->where($where)
                  ->orderBy($orderByField,$orderBy)
                  ->skip($start)
                  ->take($length)
                  ->get(); 

      $total = count($query->get());

      $data = array();
      $sno = $start;
      foreach($info as $r) {

        //show status and status activity

          if($r->status == 1){

            $r->status = "Active";
            $make = $r->status == "Active" ? 'Inactive' : 'Active';

            $statusUrl = URL::to('admin/status_activity',[$r->id, $make, 'brand']);

            $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i>Make '.$make.'</a>';
            
            $showStatus = '<span class = "badge '. ($r->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';
          }else{

            $r->status = "Inactive";
            $make = $r->status == "Active" ? 'Inactive' : 'Active';

            $statusUrl = URL::to('admin/status_activity',[$r->id, $make, 'brand']);

            $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i>Make '.$make.'</a>';
            
            $showStatus = '<span class = "badge '. ($r->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';
          }
            

        //delete url  
            $delet_Url = "'admin/delete_activity/$r->id/brand','$r->id'";

            $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete Brand</a>';

            //edit url 
         
            $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit Brand" title="Edit Brand" href="javascript:void(0);" onclick="editBrand('.$r->id.')"><i class="fa fa-edit"></i>Edit Brand</a>';
          if($r->created_at != ""){

              $createdAt = date(Config::get('constants.DATE_FORMATE'),strtotime($r->created_at));
          }else{
            $createdAt = "N/A";
          }

           $data[] = array(             
             $sno = $sno+1,
             ucfirst($r->name),
             $createdAt,
             $showStatus,
             '<div class="">
                <div class="btn-group">
                  <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span></button>
                  <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    <li>'.$status.'</li>
                    <li>'.$edit_url.'</li>
                   <li>'.$deleteUrl.'</li>
                   
                  </ul>
                </div>
              </div>',

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
    * Name: edit Brand
    * Create Date: 15 March 2018
    */
  public function editBrand(Request $request){

    $response = array('success' => 0,'msg' => "");
    $branId   = $request->get('id');

    if(!empty($branId) && is_numeric($branId)){

        $getData = Brand::where(array('id' => $branId))->select('id','name')->first();

        if(!empty($getData)){

            $response = array('success' => 1,'msg' => "",'data' => $getData);
        }
    }
    return Response::json($response);
  }

  /*
    * Name: add and edit Brand
    * Create Date: 15 March 2018
    */ 

  public function addBrand(Request $request){

      $brandId = $request->brandId;

      if(!empty($brandId) && is_numeric($brandId)){

          $validationArr = array('name' => "required|unique:brand,name,$brandId");

      }else{

        $validationArr = array('name' => 'required|unique:brand');
      }
      $validator = Validator::make($request->all(),$validationArr);

      if($validator->fails()) {
              
              return Redirect::to("admin/brand-list?add=1")->withErrors($validator)->withInput();

      } else {

          $insertUpadte = array('name'        => $request->name,
                                'created_at'  => date('Y-m-d H:i:s'));

          if(!empty($brandId) && is_numeric($brandId)){

              $check = Brand::where(array('id' => $brandId))->update($insertUpadte);

              $msg = "Great! info has been updated.";

          }else{

              $insertUpadte['status'] = '1';
              $check = Brand::insertGetId($insertUpadte);
              $msg = "Great! info has been added.";
          }

          if($check){

              return Redirect::to("admin/brand-list")->withSuccess($msg);

          }else{

              return Redirect::to("admin/brand-list?add=1")->withFail('Something went to wrong.');
          } 
      }
  }

  /*
    * Name: model list
    * Create Date: 15 March 2018
    */
    public function modelList(Request $request){

        $data['title']    = 'Model';
        $data['heading']  = 'Model List';

        $data['brand']    = Brand::where(array('status' => '1'))->select('id','name')
                              ->orderBy('id','desc')
                              ->get();

        return view("Admin::list.model_list", $data);
    }
    
    public function modelListData(Request $request){

     $query = VehicleModel::query();

    // Datatables Variables
      $draw   = intval($request->get("draw"));
      $start  = intval($request->get("start"));
      $length = intval($request->get("length"));

      $where = array();

      $query->where($where);

      if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];

          $query->where('brand.name',"LIKE","%$value%")
                ->orWhere('vehicle_model.name',"LIKE","%$value%");
      }

      //Order
        $orderByField = "vehicle_model.id";
        $orderBy = 'desc';
          
        if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {
                        
           $orderBy = $request->get('order')[0]['dir'];

        }

        if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

            if ($request->get('order')[0]['column'] == 0) {
                
              $orderByField = "vehicle_model.id";
            
            }else if($request->get('order')[0]['column'] == 1){

              $orderByField = "brand.name";
            
            }else if($request->get('order')[0]['column'] == 1){

              $orderByField = "vehicle_model.name";
            
            }

        }
      
      $field = array('vehicle_model.id','vehicle_model.name as vehicle_name','vehicle_model.status','vehicle_model.brand_id','vehicle_model.created_at','brand.name as brand_name');

      $info = $query->where($where)
                  ->leftjoin('brand','vehicle_model.brand_id','=','brand.id')
                  ->orderBy($orderByField,$orderBy)
                  ->skip($start)
                  ->take($length)
                  ->get($field); 

      $total = count($query->get());

      $data = array();
      $sno = $start;
      foreach($info as $r) {

        //show status and status activity

          if($r->status == 1){

            $r->status = "Active";
            $make = $r->status == "Active" ? 'Inactive' : 'Active';

            $statusUrl = URL::to('admin/status_activity',[$r->id, $make, 'model']);

            $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i>  Make '.$make.'</a>';
            
            $showStatus = '<span class = "badge '. ($r->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';
          }else{

            $r->status = "Inactive";
            $make = $r->status == "Active" ? 'Inactive' : 'Active';

            $statusUrl = URL::to('admin/status_activity',[$r->id, $make, 'model']);

            $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i>     Make '.$make.'</a>';
            
            $showStatus = '<span class = "badge '. ($r->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';
          }
            

        //delete url  
            $delet_Url = "'admin/delete_activity/$r->id/model','$r->id'";

            $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete Model</a>';

            //edit url 
         
            $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit Model" title="Edit Model" href="javascript:void(0);" onclick="editModel('.$r->id.')"><i class="fa fa-edit"></i>Edit Model</a>';
          if($r->created_at != ""){

              $createdAt = date(Config::get('constants.DATE_FORMATE'),strtotime($r->created_at));
          }else{
            $createdAt = "N/A";
          }

           $data[] = array(             
             $sno = $sno+1,
             ucfirst($r->vehicle_name),
             ucfirst($r->brand_name),
             $createdAt,
             $showStatus,
             '<div class="">
                <div class="btn-group">
                  <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span></button>
                  <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    <li>'.$status.'</li>
                    <li>'.$edit_url.'</li>
                   <li>'.$deleteUrl.'</li>
                   
                  </ul>
                </div>
              </div>',

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
    * Name: edit model
    * Create Date: 15 March 2018
    */
  public function editmodel(Request $request){

    $response = array('success' => 0,'msg' => "");
    $modelId   = $request->get('id');

    if(!empty($modelId) && is_numeric($modelId)){

        $getData = VehicleModel::where(array('id' => $modelId))
                                ->select('id','name','brand_id')
                                ->first();

        if(count($getData) > 0){

            $response = array('success' => 1,'msg' => "",'data' => $getData);
        }
    }
    return Response::json($response);
  }

  /*
    * Name: add and edit Model
    * Create Date: 15 March 2018
    */ 

  public function addModel(Request $request){

      $modelId = $request->modelId;

      if(!empty($modelId) && is_numeric($modelId)){

          $validationArr = array('name' => "required|unique:vehicle_model,name,$modelId");

      }else{

        $validationArr = array('name' => 'required|unique:vehicle_model');
      }
      $validator = Validator::make($request->all(),$validationArr);

      if($validator->fails()) {
              
              return Redirect::to("admin/model-list?add=1")->withErrors($validator)->withInput();

      } else {

          $insertUpadte = array('name'        => $request->name,
                                'brand_id'    => $request->brand_name,
                                'created_at'  => date('Y-m-d H:i:s'));

          if(!empty($modelId) && is_numeric($modelId)){

              $check = VehicleModel::where(array('id' => $modelId))->update($insertUpadte);

              $msg = "Great! info has been updated.";

          }else{

              $insertUpadte['status'] = '1';
              $check = VehicleModel::insertGetId($insertUpadte);
              $msg = "Great! info has been added.";
          }

          if($check){

              return Redirect::to("admin/model-list")->withSuccess($msg);

          }else{

              return Redirect::to("admin/model-list?add=1")->withFail('Something went to wrong.');
          } 
      }
  }


  /*
    * Name: vehicle list
    * Create Date: 16 March 2018
    */
    public function vehicleList(Request $request){

        $data['title'] = 'Vehicle List';
        $data['heading'] = 'Vehicle List';
        return view("Admin::list.vehicle_list", $data);
    }
    
    public function vehicleListData(Request $request){

     $query = Vehicle::query();

    // Datatables Variables
      $draw   = intval($request->get("draw"));
      $start  = intval($request->get("start"));
      $length = intval($request->get("length"));

      $where = array();

      $query->where($where);

      if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];

          
          $query->where(function ($query) use ($value){

            $query->where('vehicles.name',"LIKE","%$value%")
                  ->orWhere('vehicles.vehicle_identity',"LIKE","%$value%");
          }) 
                ->get();
      }

      //Order
        $orderByField = "vehicles.id";
        $orderBy = 'desc';
          
        if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {
                        
           $orderBy = $request->get('order')[0]['dir'];

        }

        if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

            if ($request->get('order')[0]['column'] == 0) {
                
              $orderByField = "vehicles.id";
            
            }else if($request->get('order')[0]['column'] == 1){

              $orderByField = "vehicles.name";
            
            }

        }
      
      $field = array('vehicles.id','vehicles.name as vehicle_name','service_type_id','vehicles.brand_id','vehicles.vehicle_model_id','manufacturer','number_plate','no_of_seat','insurance_no','insurance_expiry_date','luggage_capacity','vehicle_image','insurance_image','vehicles.status','vehicles.created_at','brand.name as brand_name','vehicle_model.name as model_name','service_type.title as service_type_name','vehicles.vehicle_identity','users.first_name','users.last_name');

      $info = $query->where($where)
                  ->join('brand','vehicles.brand_id','=','brand.id')
                  ->join('vehicle_model','vehicles.vehicle_model_id','=','vehicle_model.id')
                  ->join('service_type','vehicles.service_type_id','=','service_type.id')
                  ->leftjoin('driver_info','vehicles.id','=','driver_info.vehicle_id')
                  ->leftjoin('users','driver_info.driver_id','=','users.id')
                  ->orderBy($orderByField,$orderBy)
                  ->skip($start)
                  ->take($length)
                  ->get($field); 


      $total = count($query->get());

      $data = array();
      $sno = $start;
      
      foreach($info as $r) {

        //show status and status activity

          if($r->status == 1){

            $r->status = "Active";
            $make = $r->status == "Active" ? 'Inactive' : 'Active';

            $statusUrl = URL::to('admin/status_activity',[$r->id, $make, 'vehicle']);

            $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i> Make '.$make.'</a>';
            
            $showStatus = '<span class = "badge '. ($r->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';
          }else{

            $r->status = "Inactive";
            $make = $r->status == "Active" ? 'Inactive' : 'Active';

            $statusUrl = URL::to('admin/status_activity',[$r->id, $make, 'vehicle']);

            $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i> Make '.$make.'</a>';
            
            $showStatus = '<span class = "badge '. ($r->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';
          }
            

        //delete url  
            $delet_Url = "'admin/delete_activity/$r->id/vehicle','$r->id'";

            $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete Vehicle</a>';

            //edit url 
            $encryptedId = base64_encode($r->id);
            $editUrl = "add-vehicle?vehicle_id=$encryptedId";
            $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit Vehicle" title="Edit Vehicle" href="'.$editUrl.'"><i class="fa fa-edit"></i>Edit Vehicle</a>';
          if($r->created_at != ""){

              $createdAt = date(Config::get('constants.DATE_FORMATE'),strtotime($r->created_at));
          }else{
            $createdAt = "N/A";
          }

          //show vehicle image
          $file = "public/uploads/vehicle/".$r->vehicle_image;

          if($r->vehicle_image != ''){

              if(file_exists($file)){

                  $vehicleImage  = URL::to(Config::get('constants.VEHICLE_IMAGE').$r->vehicle_image);

                  $fanxyBoxImage  = Config::get('constants.VEHICLE_IMAGE').$r->vehicle_image;
                  //($fanxyBoxImage);die;

                  $showImage = '<a class="fancybox" rel="" href="'.$vehicleImage.'"><img src="'.$vehicleImage.'" class="img-square" height ="70px;" width="70px;"></a>';

              }else{

                  $NoImage  = URL::to(Config::get('constants.NO_IMAGE').'user-no-image.png');

                  $showImage = ' <img src="'.$NoImage.'" class="img-square" height ="70px;" width="70px;">';
              }
          }else{

                  $NoImage  = URL::to(Config::get('constants.NO_IMAGE').'user-no-image.png');

                  $showImage = ' <img src="'.$NoImage.'" class="img-square" height ="70px;" width="70px;">';
          }

           $data[] = array(             
             $sno = $sno+1,
             $showImage,
             ucfirst($r->first_name." ".$r->last_name),
             ucfirst($r->vehicle_identity),
             ucfirst($r->vehicle_name),
             ucfirst($r->brand_name),
             ucfirst($r->model_name),
             ucfirst($r->manufacturer),
             $showStatus,
             '<div class="">
                <div class="btn-group">
                  <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span></button>
                  <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    <li>'.$status.'</li>
                    <li>'.$edit_url.'</li>
                   
                  </ul>
                </div>
              </div>',

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
    * Name: add vehicle
    * Create Date: 19 March 2018
    */
    public function addVehicle(Request $request){

        $data['brand']        = Brand::where(array('status' => '1'))->select('id','name')->get();
        $data['service_type'] = ServiceType::where(array('status' => '1'))
                                            ->select('id','title','no_of_seat')
                                            ->get();
                                          
        //get edit vehicle Date
        $vehicle_Id = base64_decode($request->get('vehicle_id'));

        
        if(!empty($vehicle_Id)){

          $data['vehicleInfo']  = Vehicle::where(array('id' => $vehicle_Id))->first();
          $data['title']        = "Edit Vehicle";

        }else{

            $data['title'] = "Add Vehicle";
        }

        if($request->isMethod('post')){

          $vehicleId = $request->get('vehicle_id');

          $validationArr =  array('name'                  => 'required',
                                  'service_type'          => 'required',
                                  'select_brand'          => 'required',
                                  'select_model'          => 'required',
                                  'manufacturer'          => 'required',
                                  'insurance_expiry_date' => 'required',
                                  'luggage_capacity'      => 'required',
                                  'max_passenger'         => 'required|numeric|regex:/^[0-9]+$/');

          if(empty($vehicleId)){

              $validationArr['vehicle_identity']  = 
              'required|unique:vehicles,vehicle_identity';
              $validationArr['insurance_no']        = 'required|unique:vehicles';
              $validationArr['vehicle_number']      = 'required|unique:vehicles,number_plate';
              $validationArr['vehicle_image']       = 'required|mimes:jpeg,jpg,png|max:50240';
              $validationArr['insurance_image']     = 'required|mimes:jpeg,jpg,png|max:50240';

          }else{//post edit code


              $validationArr['vehicle_identity']  = 'required|unique:vehicles,vehicle_identity,'.$vehicleId;
              $validationArr['insurance_no']      = "required|unique:vehicles,insurance_no,$vehicleId";
              $validationArr['vehicle_number']    = "required|unique:vehicles,number_plate,$vehicleId";
              $validationArr['vehicle_image']     = 'mimes:jpeg,jpg,png|max:50240';
              $validationArr['insurance_image']   = 'mimes:jpeg,jpg,png|max:50240';
          }

          $validator = Validator::make($request->all(),$validationArr);

          if ($validator->fails()) {

            if(empty($vehicleId)){
              
              return Redirect::to('admin/add-vehicle')->withErrors($validator)->withInput();

            }else{

                $incrptedId = base64_encode($vehicleId);
                 
                return Redirect::to("admin/add-vehicle?vehicle_id=$incrptedId")->withErrors($validator)->withInput();
            }

          }else {
            
            $where = array('id' => $vehicleId);

            $data = array(

                'vehicle_identity'        => $request->get('vehicle_identity'),
                'name'                    => $request->get('name'),
                'service_type_id'         => $request->get('service_type'),
                'brand_id'                => $request->get('select_brand'),
                'vehicle_model_id'        => $request->get('select_model'),
                'manufacturer'            => $request->get('manufacturer'),
                'number_plate'            => $request->get('vehicle_number'),
                'insurance_no'            => $request->get('insurance_no'),
                'insurance_expiry_date'   => $request->get('insurance_expiry_date'),
                'luggage_capacity'        => $request->get('luggage_capacity'),
                'max_passenger'           => $request->get('max_passenger'),
                'status'                  => '1',
            );
           // print_r($data);die;

            if($files = $request->file('vehicle_image')){
                
                $vehicleImage  = getTimeStamp().".".$files->getClientOriginalExtension();

                Image::make($files)->resize(200, 200)->save('public/uploads/vehicle/thumbnail/'.$vehicleImage);

                $files->move('public/uploads/vehicle', $vehicleImage);

                $data['vehicle_image']  = $vehicleImage;

              //old image
                $oldFile = Vehicle::where(array('id' => $vehicleId))->first(['vehicle_image']);

                if (!empty($oldFile)) {
                  
                  delete_image('public/uploads/vehicle/'.$oldFile->vehicle_image);
                  delete_image('public/uploads/vehicle/thumbnail/'.$oldFile->vehicle_image);

                }
            }

            if($insurancefiles = $request->file('insurance_image')){

                $insuranceImage  = getTimeStamp().".".$insurancefiles->getClientOriginalExtension();

                Image::make($insurancefiles)->resize(200, 200)->save('public/uploads/insurance/thumbnail/'.$insuranceImage);

                $insurancefiles->move('public/uploads/insurance', $insuranceImage);

                $data['insurance_image']  = $insuranceImage;
              
              //old image
                $oldFileInsurance = Vehicle::where(array('id' => $vehicleId))->first(['insurance_image']);

                if (!empty($oldFileInsurance)) {
                  
                  File::delete('public/uploads/insurance/'.$oldFileInsurance->insurance_image);
                  File::delete('public/uploads/insurance/thumbnail/'.$oldFileInsurance->insurance_image);
                  
                }

            }            
            //echo "<pre>";print_r($data);die;
            $check = Vehicle::updateOrCreate($where, $data);

            if ($check) {

                if(empty($vehicleId)){

                  return Redirect::to("admin/vehicle-list")->withSuccess('Great! info has been added.');

                }else{

                  return Redirect::to("admin/vehicle-list")->withSuccess('Great! info has been updated.');
                }
            }
          }

      }else{
        
        return view("Admin::add.add_vehicle",$data);
      }
    }

    /*
    * Name: get Model list
    * Create Date: 19 March 2018
    */
    public function getModel(Request $request){

      $response = array('success' => 0,'msg' => 'Some thing went to wrong');

      $brandId  = $request->get('brand_id');
      $getData  = VehicleModel::where(array('brand_id' => $brandId,'status' => '1'))
                              ->select('id','name')
                              ->get();

      if(count($getData) > 0){

          $response = array('success' => 1,'msg' => 'model list','vehicle_model' => $getData);
      }
      return Response::json($response);
    }

    /*
    * Name: vehicle Export
    * Create Date: 30 March 2018
    */
    public function vehicleExport(Request $request){

      $field = array('vehicles.name','brand.name','vehicles.number_plate','vehicles.insurance_no','vehicles.insurance_expiry_date','vehicles.luggage_capacity');

      $data = Vehicle::join('brand','vehicles.brand_id','=','brand.id')->get($field);
      //print_r();die();
      
      $filename = 'Vehicle List '.date('d-m-y');

      Excel::create($filename, function($excel) use($data) {

        $excel->sheet('list', function($sheet) use($data) {
           
        $sheet->cell('A1', function($cell) {$cell->setValue('Vehicle Name');   });
        $sheet->cell('B1', function($cell) {$cell->setValue('Vehicle Number'); });
        $sheet->cell('C1', function($cell) {$cell->setValue('insurance Number ');        });
        $sheet->cell('D1', function($cell) {$cell->setValue('insurance Expiry');        });
        $sheet->cell('E1', function($cell) {$cell->setValue('Luggage Capacity');  });
        
       
            if (!empty($data)) {
                
                foreach ($data as $key => $value) {

                  $vehicle_name= $value->name;            
                  $number_plate = $value->number_plate;
                  $insurance_no   = $value->insurance_no;
                  $insurance_expiry_date=$value->insurance_expiry_date;
                  $luggage_capacity = $value->luggage_capacity;
            
                  $i=$key+2;
                  $sheet->cell('A'.$i,$vehicle_name);
                  $sheet->cell('B'.$i,$number_plate);
                  $sheet->cell('C'.$i,$insurance_no);
                  $sheet->cell('D'.$i,$insurance_expiry_date);
                  $sheet->cell('E'.$i,$luggage_capacity);
                  
                }
            }

        $sheet->cells('A1:E1', function($cells) {
            // Set font
            $cells->setFont(array(
                    'family' => 'Calibri',
                    'size' => '10',
                    
            
            ));
        });
                   
        $sheet->setHeight(10, 15);
                    
      });
    })->export('xls');



    }/*Vehicle Export End*/
    
}
