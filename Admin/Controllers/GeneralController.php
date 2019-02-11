<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;                 
use App\Models\{Promocode,AppInfo,Version,Country,State,City,CancelReason,Users,Charges,Content,RentalPackage,Feedback};
use Validator,Redirect,Session,URL,Config,Excel,Response;

/*
* Name: DriverController
* Create Date: 13 March
*/
class GeneralController extends Controller
{

    /*
    * Name: promocode
    * Create Date: 13 March 2018
    */
    public function promocode(Request $request){

        $data['title'] = 'Promocode';
        $data['heading'] = 'Promocode List';
        return view("Admin::list.promocode", $data);
    }
    
    public function promocodeData(Request $request){
           
        $query =Promocode::query();

        // Datatables Variables
        $draw   = intval($request->get("draw"));
        $start  = intval($request->get("start"));
        $length = intval($request->get("length"));

        $where = array();

        $query->where($where);
       
        if ($request->get('search')['value'] != "") {

          $value = $request->get('search')['value'];

          $query->where('code',"LIKE","%$value%")
              ->orWhere('status',"LIKE","%$value%")
              ->orWhere('start_date',"LIKE","%$value%")          
              ->orWhere('expiry_date',"LIKE","%$value%");
        } 

        //Order
        $orderByField = "promocode.id";
        $orderBy = 'desc';
          
        if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {
                        
           $orderBy = $request->get('order')[0]['dir'];
        }

        if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

          if ($request->get('order')[0]['column'] == 0) {
                
             $orderByField = "promocode.id";
            
          }else if($request->get('order')[0]['column'] == 1){

                   $orderByField = "promocode.code";            
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
              
        //show status and status activity
          if($r->status == 1){

            $r->status = "Active";

            $make = $r->status == "Active" ? 'Inactive' : 'Active';

            $statusUrl = URL::to('admin/status_activity',[$r->id, $make, 'promocode']);

            $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i> Make '.$make.'</a>';
            
            $showStatus = '<span class = "badge '. ($r->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';
          
          }else{

            $r->status = "Inactive";
            $make = $r->status == "Active" ? 'Inactive' : 'Active';

            $statusUrl = URL::to('admin/status_activity',[$r->id, $make, 'promocode']);

            $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($r->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i>  Make '.$make.'</a>';
            
            $showStatus = '<span class = "badge '. ($r->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($r->status).'</span>';
          }

        //delete url  
        $delet_Url = "'admin/delete_activity/$r->id/promocode','$r->id'";
        $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i>  Delete promocode</a>';

            //edit url 
        $url = URL::to('admin/edit-promocode/'.$r->id);      
        $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit Pormocode " title="Edit promocode" href="'.$url.'"><i class="fa fa-edit"></i>Edit Promocode</a>';

          //customer Detail
        $urlDetail = URL::to('admin/delivery-boy-detail/'.$r->id);      
        $detail_url = '<a type="" class="" style="text-decoration: none;" data-title ="Delivery Boy Detail" title="Delivery Boy Detail" href="'.$urlDetail.'"><i class="fa fa-info-circle"></i>Delivery Boy Detail</a>';      

        if(!empty($r->expiry_date)){

          $expiryDate = date(Config::get('constants.DATE_FORMATE'),strtotime($r->expiry_date));

        }else{

          $expiryDate = "N/A";
        }

        if(!empty($r->start_date)){

          $startDate = date(Config::get('constants.DATE_FORMATE'),strtotime($r->start_date));

        }else{

          $startDate = "N/A";
        }
          
        if(!empty($r->amount)){

         $amount = $r->amount;

        }else{

          $amount = "N/A";
        }
        
        if (!empty($r->type)) {
            $types = $r->type;
            if ($types =='flat') {
              $type = 'Flat';
              $amount= Config::get('constants.CURRENCY').' '.$amount;
            }
            else{
              $type = 'Percentage';
              $amount= $amount." ".'%';
            }
        }
        if (!empty($r->no_of_users)) {

            $no_of_users = $r->no_of_users;
        }else{

            $no_of_users = "N/A";
        }

        $data[] = array(             
          $sno = $sno+1,
          $r->code,
          $amount,
          $startDate,
          $expiryDate,
          $no_of_users,                       
          $showStatus,
             '<div class="manage_drop_d">
              <div class="dropdown">
                <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span>
                </button>
              <ul class="dropdown-menu dropdown-menu-right" role="menu"> 

                <li>'.$status.'</li>
                <li>'.$edit_url.'</li>
                <li>'.$deleteUrl.'</li>
              </ul>
           
          </div>
        </div> ',

           );
       }//end for each 
                    
       
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
  * Name: addPassenger
  * Create Date: 14 Mar 2018
  * Purpose: 
  */
    public function addPromocode(Request $request){

      if($request->isMethod('post')){

        $validator = Validator::make($request->all(),[
                    'promocode'      => 'required',
                    'start_date'     => 'required',
                    'end_date'       => 'required',
                    'type'           => 'required',
                    'no_of_users'    => 'required|numeric|regex:/^[0-9]+$/']);

        if ($validator->fails()) {
         
            return Redirect::to("admin/add-promocode")->withErrors($validator)->withInput();

        } else {

            $insert = array('code'         => $request->promocode,
                            'status'       => '1',
                            'type'         => $request->type,
                            'amount'       => $request->value,
                            'start_date'   => $request->start_date,
                            'expiry_date'  => $request->end_date,
                            'no_of_users'  => $request->no_of_users,
                            'created_at'   => date('Y-m-d H:i'),
                          );            
            
            $getType =  $request->type;

            if($getType == 2){

                if($request->value > 100){

                  return Redirect::to("admin/add-promocode?type=$request->type")->withFail('Amount should be less than 100')->withInput();

                }else{

                  $lastId = Promocode::insertGetId($insert);                         

                  if($lastId){

                      return Redirect::to("admin/promocode")->withSuccess('Great! info has been added.');

                  }else{

                      return Redirect::to("admin/add-promocode")->withFail('Something went to wrong.');
                    }
                }
            }else{

              $lastId = Promocode::insertGetId($insert);                                    

              if($lastId){
              
                return Redirect::to("admin/promocode")->withSuccess('Great! info has been added.');

              }else{
                return Redirect::to("admin/add-promocode")->withFail('Something went to wrong.');
              } 
            }
          
          }

      }else{

        $data['title'] = "Add Pormocode";
        return view("Admin::add.add_promocode",$data);
      }
  }
    
  public function editPromocode($id,Request $request){

    if($request->isMethod('post')){

      $validator = Validator::make($request->all(), [
                    'promocode'      => 'required',
                    'start_date'     => 'required',
                    'end_date'       => 'required',
                    'type'           => 'required',
                    'no_of_users'    => 'required|integer', ]);

      if ($validator->fails()) {
              
          return Redirect::to("admin/edit-promocode/$id")->withErrors($validator)->withInput();

      } else {
          $update = array('code'         => $request->promocode,
                          'status'       => '1',
                          'type'         => $request->type,
                          'amount'       => $request->value,
                          'start_date'   => $request->start_date,
                          'expiry_date'  => $request->end_date,
                          'no_of_users'  => $request->no_of_users,
                          'created_at'   => date('Y-m-d H:i'));   

          $getType = $request->type;

            if ($getType==2) {
                           
            if($request->value>100){
                            
              return Redirect::to("admin/edit-promocode/$id")->withFail('Amount should be less than 100 ')->withInput();
                        
            }else{
              
              $update = Promocode::where(array('id' => $id))->update($update);
              
              if($update){
                
                return Redirect::to("admin/promocode")->withSuccess('Great! info has been updated.');
              }else{
                return Redirect::to("admin/edit-promocode/$id")->withFail('Something went to wrong.');
              }
            }

            }     
                  
          /*Flat insert here */
          $update = Promocode::where(array('id' => $id))->update($update);
                                  
          if($update){
              
              return Redirect::to("admin/promocode")->withSuccess('Great! info has been updated.');
                  
          }else{

            return Redirect::to("admin/edit-promocode/$id")->withFail('Something went to wrong.');
          } 

          } 
      
      
     
    }else{

      $data['title'] = "Edit Pormocode";

      $where=array('id' => $id);

      $data['promocodeInfo'] = Promocode::where($where)->select('id','code', 'start_date','expiry_date','amount','type','no_of_users')->first(); 

      return view("Admin::edit.edit_promocode",$data);
    } 
   
 
 }//edit promocode end

  public function statelist(Request $request,$id = null){
    
    $data['country_id'] = $id;

    $data['title'] = 'States List';
    return view("Admin::list.state_list", $data);

  }//StateList End here 

  public function stateListData(Request $request){
    
    $data['country_id'] = '';   
    $query = State::query();
    // Datatables Variables

    $draw   = intval($request->get("draw"));
    $start  = intval($request->get("start"));
    $length = intval($request->get("length"));       

    if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];           

        $query->where('states.state_name',"LIKE","%$value%")->orWhere('country.country_name',"LIKE","%$value%");
    }
    
    //Order
    $orderByField = "states.id";
    $orderBy = 'desc';
  
    if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {

       $orderBy = $request->get('order')[0]['dir'];

    }

    if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

    if ($request->get('order')[0]['column'] == 0) {
          
        $orderByField = "states.id";
              
    }else if($request->get('order')[0]['column'] == 1){

            $orderByField = "states.state_name";
              
    }else if($request->get('order')[0]['column'] == 1){

            $orderByField = "country.country_name";
              
    }

    }

    if($request->get('country_id') !=""){
        
      $field = array('states.id','states.state_name','states.status','country.country_name');
  
      $query->leftjoin('country','country.id','=','states.country_id')->where(array('states.country_id' => $request->get('country_id')))->orderBy($orderByField,$orderBy);

      $total = $query->count();

      $info = $query->skip($start)->take($length)->get($field); 

      $data = array();
      $sno = $start;

    }else{
      
      $field = array('states.id','states.state_name','states.status','country.country_name');

      $query->leftjoin('country','country.id','=','states.country_id')->orderBy($orderByField,$orderBy); 

      $total = $query->count();

      $info = $query->skip($start)->take($length)->get($field);

      $data = array();
      $sno = $start; 
    }  

    if(count($info) > 0){
     foreach ($info as $state) {

        $make = $state->status == "Active" ? 'Inactive' : 'Active'; 

        $statusUrl = URL::to('admin/status_activity',[$state->id, $make, 'state']);

        $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($state->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i> Make '.$make.'</a>';
        
        $showStatus = '<span class = "badge '. ($state->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($state->status).'</span>'; 
              $delet_Url = "'admin/delete_activity/$state->id/state','$state->id'";

        $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete State</a>';
        
        $editUrl = URL::to('admin/edit-state',[$state->id]);  
             
        //edit url       
        $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit State" title="Edit-state" href="'.$editUrl.'"><i class="fa fa-edit"></i>Edit State</a>';
          $viewcityUrl = URL::to('admin/city-list',[$state->id]);
      $view_city = '<a type="" class="data-title" ="View State" title="edit-state" href="'.$viewcityUrl.'"><i class="fa fa-street-view"></i> View Cities</a>';

        $data[] =array(
                $sno = $sno+1,
                ucfirst($state->country_name),
                  ucfirst($state->state_name),
                  $showStatus,
          '<div class="manage_drop_d">
          <div class="dropdown">
            <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span>
            </button>
              <ul class="dropdown-menu dropdown-menu-right" role="menu"> 

                <li>'.$status.'</li>
                <li>'.$edit_url.'</li>
                <li>'.$deleteUrl.'</li>
                <li>'.$view_city.'</li>
              </ul>
           
          </div>
        </div>   '

              ); 
             }  
           }  

        $output = array(
                     "draw"            => $draw,
                     "recordsTotal"    => $total,
                     "recordsFiltered"  => $total,
                     "data"             => $data
                  );
        echo json_encode($output);
        exit();   
          
          
  }/*state List Data End here*/

  public function addState(Request $request){

    if ($request->isMethod('post')) {
            
      $validation = validator::make($request->all(),[

        'country_id'   => 'required',
        'state_name'   => 'required|regex:/^[\pL\s\-]+$/u|unique:states',

          ]);
      if ($validation->fails()) {
        
          return Redirect::to("admin/add-state")->withErrors($validation)->withInput();

        }else{

          $data = array('state_name'  => $request->state_name,
                    'country_id'  => $request->get('country_id')
                    );
            
          $insert = State::insertGetId($data);
          
            if ($insert) {
            
              return Redirect::to("admin/state-list")->withSuccess('Data       Successfull add ');
            
            }else{
              return Redirect::to("admin/add-state")->withFail('Something went to wrong.');
              }
          }

    }else{

      $data['title'] = 'Add State'; 
      $data['state_info']='';
      $data['country']= Country::where('status','Active')->get();     
     
      return view("Admin::add.add_state", $data);
      
      }

  }/*Add state End Here*/

  public function editState(Request $request,$id){

    if ($request->isMethod('post')) {

      $validation = validator::make($request->all(),[

        'country_id'   => 'required',
        'state_name'   => 'required|regex:/^[\pL\s\-]+$/u|unique:states,state_name,'.$request->id,

          ]);

      if ($validation->fails()) {
        
          return Redirect::to("admin/edit-state/$id")->withErrors($validation)->withInput();

      }else{
          
          $data = array('state_name'  => $request->state_name,
                      'country_id'  => $request->get('country_id'));
              
          $update = State::where('id',$id)->update($data);
              
          if ($update) {

            return Redirect::to("admin/state-list")->withSuccess('Great!Info has been updated.');
            
          }else{

            return Redirect::to("admin/edit-state")->withFail('Soory! Failed to update info.');
          }
      }

      

    }else {
        $data['title'] = 'Edit State';
        $data['country']= Country::where('status','Active')->get();
        $data['stateInfo'] = State::where('id',$id)
                      ->select('id','state_name','status','country_id')
                      ->first();
        
        return view("Admin::edit.edit_state",$data);
    }

  }/*Edit State End Here*/

 public function appInfo(Request $request){

  if ($request->isMethod('post')) {

    $validation = validator::make($request->all(),[

      'contact_number'  => 'required|max:10',
      'email'           => 'required|email|',
      'address'         => 'required',
      'web_url'         => 'required' ]);

    if($validation->fails()){
          
      return Redirect::back()->withInput()->withErrors($validation);

    }else {

      $id = $request->get('id');

      $appInfo = array(
                  
                 'contact_number' => $request->get('contact_number'),
                 'email'          => $request->get('email'),
                 'address'        => $request->get('address'),
                 'web_url'        => $request->get('web_url') ); 

      if ($id !="") {
                  
          $update = AppInfo::where('id',$id)->update($appInfo);

          if ($update) {
             return Redirect::to("admin/app-info")->withSuccess('Great! Info has been updated.');

          }else{
          
            return Redirect::to("admin/app-info")->withFail('Soory! Failed to update info.');
           
            }
      }else{
        $appInfo['created_at'] = date('Y-m-d H:i:s');

        $insert = AppInfo::insert($appInfo);

        if ($insert) {
            return Redirect::to("admin/app-info")->withSuccess('Great! Info has been updated.');
        
        }else{

          return Redirect::to("admin/app-info")->withFail('Soory! Failed to update info.');
        }
     }
    }

    
  }else{
    $data['info']   = AppInfo::first();
    $data['title']  = "App Info";

    return View('Admin::detail.app_info', $data);
  }
  
 }//appInfo end 

   /*
  * Name: Charges 
  * Create Date: 27 Mar 2018
  * Purpose: For Update the Base Charges
  */

   public function baseCharges(Request $request){

      if ($request->isMethod('post')) {
        
        $validation = validator::make($request->all(),[

                    'base_charges'        => 'required',
                    'base_charges_for_km' => 'required|regex:/^[0-9]+$/',
                    'charges_per_km'      => 'required',
                    'waiting_charges_per_minute' => 'required|regex:/^[0-9]+$/',
                    ]);

        if ($validation->fails()) {
                
          return Redirect::back()->withInput()->withErrors($validation);

        }else{

          $id = $request->get('id');

          $update = array('base_charges' => $request->get('base_charges'),
                          'base_charges_for_km' => $request->get('base_charges_for_km'),

                          'charges_per_km' => $request->get('charges_per_km'),
                          'waiting_charges_per_minute' => $request->get('waiting_charges_per_minute'),          );
                          

          $check = Charges::where('id',$id)->update($update);

          if ($check) {
              return Redirect::to("admin/base-charges")->withSuccess('Great! Info has been updated.');

          }else{
          
            return Redirect::to("admin/base-charges")->withFail('Soory! Failed to update info.');
           
          }

        }

      }else{
        
        $data['title'] = 'Rate Management';
        $data['charges_info'] = Charges::first();
        return view("Admin::detail.base_charges",$data);

      }

   }/*Charges function End */

  public function rentalPackages(Request $request){

    $data['title'] = 'Rental Packages';

    return view("Admin::list.rental_packages",$data);


    //return view("Admin::add.rental_packages");
      
   }/*Rentail Packages End */

  public function rentalPackagesData(Request $request){

    $query = RentalPackage::query();
    
    // Datatables Variables
    $draw   = intval($request->get("draw"));
    $start  = intval($request->get("start"));
    $length = intval($request->get("length"));       

    if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];           

        $query->where('time',"LIKE","%$value%")
              ->orWhere('distance',"LIKE","%$value%")
              ->orWhere('charges',"LIKE","%$value%");
    }
    
    //Order
    $orderByField = "rental_packages.id";
    $orderBy = 'desc';
  
    if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {

       $orderBy = $request->get('order')[0]['dir'];

    }

    if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

    if ($request->get('order')[0]['column'] == 0) {
          
        $orderByField = "rental_packages.id";
              
    }else if($request->get('order')[0]['column'] == 1){

            $orderByField = "rental_packages.time";
              
    }else if($request->get('order')[0]['column'] == 2){

            $orderByField = "rental_packages.distance";
              
    }

    }
    
    $total = $query->count();
    
    $field = array('id','time','distance','charges','status');

    $info = $query->orderBy($orderByField,$orderBy)->skip($start)->take($length)->get($field); 
    //print_r($info);die;
    
    $data = array();
    $sno = $start;

    if(count($info) > 0){
      
      foreach ($info as $rental_packages) {
        
        if($rental_packages->status==1){

          $rental_packages->status = "Active";
           $make = $rental_packages->status == "Active" ? 'Inactive' : 'Active'; 

          $statusUrl = URL::to('admin/status_activity',[$rental_packages->id, $make, 'rentalPackages']);

          $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($rental_packages->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i>  Make '.$make.'</a>';
    
          $showStatus = '<span class = "badge '. ($rental_packages->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($rental_packages->status).'</span>'; 
        }else{
          
          $rental_packages->status = "Inactive";
           $make = $rental_packages->status == "Active" ? 'Inactive' : 'Active'; 

          $statusUrl = URL::to('admin/status_activity',[$rental_packages->id, $make, 'rentalPackages']);

          $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($rental_packages->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i>  Make '.$make.'</a>';
    
          $showStatus = '<span class = "badge '. ($rental_packages->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($rental_packages->status).'</span>'; 
        }
     
        
        $delet_Url = "'admin/delete_activity/$rental_packages->id/rental_packages','$rental_packages->id'";

        $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete </a>';
        
        $editUrl = URL::to('admin/edit-rental-packages',[$rental_packages->id]);  
             
        //edit url       
        $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit Rental Packages" title="Edit-Rental Packages" href="'.$editUrl.'"><i class="fa fa-edit"></i>Edit Rental Packages</a>';
          
        $data[] =array(
            $sno = $sno+1,
            ucfirst($rental_packages->time),
            ucfirst($rental_packages->distance),
            Config::get('constants.CURRENCY').ucfirst($rental_packages->charges),
            $showStatus,                  
              '<div class="manage_drop_d">
                <div class="dropdown">
                  <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span>
                  </button>
                    <ul class="dropdown-menu dropdown-menu-right" role="menu"> 

                      <li>'.$status.'</li>
                      <li>'.$edit_url.'</li>
                      <li>'.$deleteUrl.'</li>
                    </ul>           
                </div>
              </div> '

              );
             }  
           }  

        $output = array(
                     "draw"            => $draw,
                     "recordsTotal"    => $total,
                     "recordsFiltered"  => $total,
                     "data"             => $data
                  );
        echo json_encode($output);
        exit();   
  }/*End Rental packages Data*/

  public function addRentalPackages(Request $request){

    if ($request->isMethod('post')) {

      $validation = validator::make($request->all(),[

                        'time'        => 'required|regex:/^[0-9]+$/',
                        'distance' => 'required',
                        'charges'      => 'required',                    
                        ]);
      if ($validation->fails()) {

        return Redirect::back()->withInput()->withErrors($validation);

      }else{

        $insert = array('time'       => $request->get('time'),
                        'distance'  => $request->get('distance'),
                        'charges'   => $request->get('charges'),
                        'created_at'=> date('Y-m-d H:i')  );
        $lastId = RentalPackage::insertGetId($insert);
        //print_r($lastId);die;
        if($lastId){
            
          return Redirect::to("admin/rental-packages")->withSuccess('Packages add Successfull');
        }else{

          return Redirect::to("admin/rental-packages")->withFail('Something went to wrong.');
        } 
      }
      
    }else{
        $data['title'] = 'Rental Packages';
        return view("Admin::add.add_rental_packages",$data);
    }
        
  }/*add Rental packages*/

  public function editRentalPackages(Request $request,$id){

    if ($request->isMethod('post')) {

      $validation = validator::make($request->all(),[

                        'time'        => 'required|regex:/^[0-9]+$/',
                        'distance' => 'required|regex:/^[0-9]+$/',
                        'charges'      => 'required|regex:/^[0-9]+$/',                    
                        ]);
      if ($validation->fails()) {

        return Redirect::back()->withInput()->withErrors($validation);

      }else{

        $upd = array('time'       => $request->get('time'),
                        'distance'  => $request->get('distance'),
                        'charges'   => $request->get('charges'),
                        'created_at'=> date('Y-m-d H:i')  );
        $update = RentalPackage::where('id',$id)->update($upd);
        
        if($update){
            
          return Redirect::to("admin/rental-packages")->withSuccess('Data       Successfull update ');
        }else{

          return Redirect::to("admin/edit-rental-packages/$id")->withFail('Something went to wrong.');
        } 
      }
      
    }else{
        
        $data['title'] = 'Edit Rental Packages';
        $data['packageInfo'] = RentalPackage::where('id',$id)->first(['id','time','distance','charges']);
        //print_r($data['packageInfo']);die;
        
       return view("Admin::edit.edit_rental_packages",$data);
    }

  }/*End Edit Rental packages*/



  /*
  * Name: country List
  * Create Date: 22 Mar 2018
  * Purpose: 
  */

  public function countryList(Request $request){

    $data['title'] = 'Country List';
    return view("Admin::list.country_list");

    
  }//country List End 

  public function countryListData(Request $request){
    
    $query = Country::query();
    
    $draw   = intval($request->get("draw"));
    $start  = intval($request->get("start"));
    $length = intval($request->get("length"));

    if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];
        
        $query->where('country_name',"LIKE","%$value%");
    }
        //Order
        $orderByField = "country.id";
        $orderBy = 'desc';

    if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {                        
        $orderBy = $request->get('order')[0]['dir'];
    }

    if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

    if ($request->get('order')[0]['column'] == 0) {
                  
        $orderByField = "country.id";
              
    }else if($request->get('order')[0]['column'] == 1){

        $orderByField = "country.country_name";             
    }
    
    }

    $total = $query->count();
      
    $info = $query->orderBy($orderByField,$orderBy)->skip($start)->take($length)->get(); 
          
    $data = array();
    $sno = $start;

    foreach ($info as $country) {
    
      $make = $country->status == "Active" ? 'Inactive' : 'Active'; 

      $statusUrl = URL::to('admin/status_activity',[$country->id, $make, 'country']);

      $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($country->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i>  Make '.$make.'</a>';
    
      $showStatus = '<span class = "badge '. ($country->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($country->status).'</span>'; 
    
      $delet_Url = "'admin/delete_activity/$country->id/country','$country->id'";

      $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete Country</a>';
    
      $editUrl = URL::to('admin/edit-country',[$country->id]);  
      //edit url       
    
      $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit Country" title="edit-country" href="'.$editUrl.'"><i class="fa fa-edit"></i>Edit Country</a>';
    
      $viewstateUrl = URL::to('admin/state-list',[$country->id]);
      $view_state = '<a type="" class="data-title" ="View State" title="edit-state" href="'.$viewstateUrl.'"><i class="fa fa-street-view"></i> View States</a>';
            $data[] =array(
              $sno = $sno+1,
                ucfirst($country->country_name),
                $showStatus,
          '<div class="">
                    <div class="btn-group">
                      <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                        <li>'.$status.'</li>
                        <li>'.$edit_url.'</li>
                        <li>'.$deleteUrl.'</li>
                         <li>'.$view_state.'</li>
                      </ul>
                    </div>
                  </div>'
            ); 
          }/*foreach*/

        $output = array(
                      "draw"            => $draw,
                      "recordsTotal"    => $total,
                      "recordsFiltered"  => $total,
                      "data"             => $data
                    );
        echo json_encode($output);
        exit();       

  }/*country List Data End*/
  public function addCountry(Request $request){

    if ($request->isMethod('post')) {

        $validation = validator::make($request->all(),[


          'country_name' => 'required|regex:/^[\pL\s\-]+$/u|unique:country']);

        if ($validation->fails()) {
          
          return Redirect::to("admin/add-country")->withErrors($validation)->withInput();
          
        }else{
          
          $data = array('country_name'  => $request->country_name );
          
          $insert = Country::insertGetId($data);
          
            if ($insert) {
            
              return Redirect::to("admin/country-list")->withSuccess('Data Successfull add ');
            }else{

              return Redirect::to("admin/add-country")->withFail('Something went to wrong.');
              }
          }

      
    }else{
      
      $data['title'] = 'Add Country'; 
        
      return view("Admin::add.add_country", $data); 

    }
  }/*addCountry End */

    public function editCountry(Request $request,$id){

      if ($request->isMethod('post')) {
        
          $validation = validator::make($request->all(),[
                      'country_name' => 'required|regex:/^[\pL\s\-]+$/u|unique:country,country_name,'.$request->id ]);

          if ($validation->fails()) {
              
              return Redirect::to("admin/edit-country/$id")
                            ->withErrors($validation)
                            ->withInput();
          }else{

            $data = array('country_name' => $request->country_name);
              
            $update = Country::where('id',$id)->update($data);
            
            if ($update) {
              
              return Redirect::to("admin/country-list")->withSuccess('Great! Info has been updated.');

            }else{

              return Redirect::to("admin/edit-country/$id")->withErrors('Soory! Failed to update info.');
              }
          }         

      }else{

        $data['title'] = 'Edit Country';

        $data['countryInfo'] = Country::where('id',$id)
                                      ->select('id','country_name')
                                      ->first();
      
        return view("Admin::edit.edit_country",$data);
      }
  }/*Edit Country End*/

  public function cityList(Request $request,$id=null){

      $data['state_id'] = $id; 
      $data['title'] = 'City List';
      return view("Admin::list.city_list", $data);
  }

  public function cityListData(Request $request){

      $data['state_id'] = '';
      $query = City::query();
     // Datatables Variables
      $draw   = intval($request->get("draw"));
      $start  = intval($request->get("start"));
      $length = intval($request->get("length"));

      if ($request->get('search')['value'] != "") {

          $value = $request->get('search')['value'];

          $query->where('city_name',"LIKE","%$value%")->orWhere('country.country_name',"LIKE","%$value%")->orWhere('states.state_name',"LIKE","%$value%");
            }

          //Order
          $orderByField = "city.id";
          $orderBy = 'desc';

          if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {
                        
              $orderBy = $request->get('order')[0]['dir'];

          }

          if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

          if ($request->get('order')[0]['column'] == 0) {
                 
              $orderByField = "city.id";
                
          }else if($request->get('order')[0]['column'] == 1){

              $orderByField = "city.city_name";
             
          }

          }

          if ($request->get('state_id') != "") {
            
              
              $query->leftjoin('states','states.id','=','city.state_id')->leftjoin('country','country.id','=','states.country_id')->where(array('city.state_id' => $request->get('state_id')))->orderBy($orderByField,$orderBy); 

              $total = $query->count();
              
              $info = $query->skip($start)->take($length)->get(['states.country_id','states.state_name','city.status','city.city_name','city.id','country.country_name']); 
             
              $data = array();
              $sno = $start;

          } else {
            
              $query->leftjoin('states','states.id','=','city.state_id')->leftjoin('country','country.id','=','states.country_id')->orderBy($orderByField,$orderBy); 
             
              $total = $query->count();
              
              $info = $query->skip($start)->take($length)->get(['states.country_id','states.state_name','city.status','city.city_name','city.id','country.country_name']); 
             
              $data = array();
              $sno = $start;          

          }          
           
          foreach ($info as $city) {
            $make = $city->status == "Active" ? 'Inactive' : 'Active'; 

            $statusUrl = URL::to('admin/status_activity',[$city->id, $make, 'city']);

            $status = '<a type="" class="change_status" data-title="Confirmation" style="text-decoration: none;" href="'.$statusUrl.'" title="Make '.$make. '" data-make="'.$make.'"><i class="'.($city->status == "Active" ? 'fa fa-lock' : 'fa fa-unlock').'"></i> Make '.$make.'</a>';
            
            $showStatus = '<span class = "badge '. ($city->status == "Active" ? "bg-green" : "bg-red").'">'.ucfirst($city->status).'</span>'; 
            
            $delet_Url = "'admin/delete_activity/$city->id/city','$city->id'";

            $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete City</a>';
            
            $editUrl = URL::to('admin/edit-city',[$city->id]);  
            //edit url       
            
            $edit_url = '<a type="" class="" style="text-decoration: none;" data-title  ="Edit City" title="Edit-state" href="'.$editUrl.'"><i class="fa fa-edit"></i> Edit City</a>';
                
            $data[] =array(
                $sno = $sno+1,
                ucfirst($city->country_name),
                ucfirst($city->state_name),
                ucfirst($city->city_name),
                $showStatus,
          '<div class="manage_drop_d">
          <div class="dropdown">
            <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span>
            </button>
              <ul class="dropdown-menu dropdown-menu-right" role="menu"> 

                <li>'.$status.'</li>
                <li>'.$edit_url.'</li>
                <li>'.$deleteUrl.'</li>

              </ul>
           
          </div>
          </div>'

                ); 
             }

              $output = array(
                            "draw"            => $draw,
                            "recordsTotal"    => $total,
                           "recordsFiltered"  => $total,
                           "data"             => $data );

              echo json_encode($output);
              exit();       
  }
  
  public function addCity(Request $request){

      if ($request->isMethod('post')) {
            
          $validation = Validator::make($request->all(), [

                    'city_name'   => 'required|regex:/^[\pL\s\-]+$/u|unique:city',
                    'state'       => 'required']);

      if ($validation->fails()) {

          return Redirect::to('admin/add-city')->withErrors($validation);

      }else {

          $insdata = array( 'state_id'  => $request->get('state'),
                            'city_name' => $request->get('city_name'),
                            'status'    => 'Active',
                            'created_at'=> date('Y-m-d H:i'));
                
          $insert = City::insertGetId($insdata);
          
            if ($insert) {
                        
                return Redirect::to("admin/city-list")->withSuccess('Data Successfull add ');
            
            }else{

                return Redirect::to("admin/add-city")->withFail('Something went to wrong.');
            }
            }               

      } else {

          $data['title'] = 'Add City';
          $data['country'] = Country::where('status','Active')->get();
          $data['state'] = State::where('status','Active')->get();
            
          return view("Admin::add.add_city", $data);  
        }

    }/*Add City End*/

    public function editCity(Request $request,$id){

        if ($request->isMethod('post')) {

            $validation = Validator::make($request->all(), [
                      'city_name'   => 'required|regex:/^[\pL\s\-]+$/u|unique:city,city_name,'.$request->id,
                      'state'       => 'required' ]);

            if ($validation->fails()) {

               return Redirect::to("admin/edit-city/$id")->withErrors($validation);

            }else{                               

              $data = array('state_id'  => $request->get('state'),
                            'city_name' => $request->get('city_name'),
                            'status'    => 'Active',
                            'created_at'=> date('Y-m-d H:i') );
                
              $update = City::where('id',$id)->update($data);

                if ($update) {
                    
                    return Redirect::to("admin/city-list")->withSuccess('Great! Info has been updated.');
                
                }else{

                    return Redirect::to("admin/edit-city/$id")->withFail('Something went to wrong.');                
                }                      
            }            
            
        }else {

          $data['title']   = "Edit City";
          $data['country'] = Country::where('status','Active')->get();
          $data['state']   = State::where('status','Active')->get();
          $city_id = $id;

          $data['city_info'] = City::leftjoin('states','states.id','=','city.state_id')                        ->leftjoin('country','country.id','=',                                       'states.country_id')
                                    ->where('city.id',$city_id)
                                    ->first(['states.country_id','states.state_name','city.status','city.city_name','city.id','country.country_name','country.id as country_info_id','states.id as state_id']);

          return view("Admin::edit.edit_city", $data);
      }
    }

  /*
  * Name: cancelReasons
  * Create Date: 26 Mar 2018
  * Purpose: 
  */

  public function cancelReasons(Request $request){

       $data['title'] = 'Cancel Reasons';

       return view("Admin::list.cancel_reasons",$data);

  }/*cancel Reasons End here */

  public function cancelReasonsList(Request $request){

    $query = CancelReason::query();
    
    $draw   = intval($request->get("draw"));
    $start  = intval($request->get("start"));
    $length = intval($request->get("length"));

    if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];
        
        $query->where('reason',"LIKE","%$value%");
    }
        //Order
        $orderByField = "cancel_reasons.id";
        $orderBy = 'desc';

    if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {                        
        $orderBy = $request->get('order')[0]['dir'];
    }

    if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

    if ($request->get('order')[0]['column'] == 0) {
                  
        $orderByField = "cancel_reasons.id";
              
    }    
    }

    $total = $query->count();
      
    $info = $query->orderBy($orderByField,$orderBy)->skip($start)->take($length)->get(); 
          
    $data = array();
    $sno = $start;

    foreach ($info as $cancel) {
    
      $delet_Url = "'admin/delete_activity/$cancel->id/cancel','$cancel->id'";

      $deleteUrl = '<a type="" class="recorddelete" style="text-decoration: none;" data-title ="Confirmation" data-placement = "top" title="Delete Record" onclick="remove_record('.$delet_Url.')" href="javascript:void(0)"><i class="fa fa-trash"></i> Delete Reason</a>';
    
      $editUrl = URL::to('admin/edit-reason',[$cancel->id]);  
      //edit url       
    
      $edit_url = '<a type="" class="" style="text-decoration: none;" data-title ="Edit Cancel" title="edit-cancel" href="'.$editUrl.'"><i class="fa fa-edit"></i>Edit Reason</a>';

          $data[] =array(
            $sno = $sno+1,
              ucfirst($cancel->reason),
              
          '<div class="">
                  <div class="btn-group">
                    <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span></button>
                      <ul class="dropdown-menu dropdown-menu-right" role="menu">
                      
                      <li>'.$edit_url.'</li>
                      <li>'.$deleteUrl.'</li>
                      
                    </ul>
                  </div>
                </div>'
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

  }/*cancel Reason List End */

  /*
  * Name: addReason
  * Create Date: 27 Mar 2018
  * Purpose: This function for Add Reason Cancel
  */


  public function addReason(Request $request){

    if ($request->isMethod('post')) {
      
      $validation = validator::make($request->all(),[

                    'reason' => 'required']);
    
        if ($validation->fails()) {
            
            return Redirect::to("admin/add-reason")->withErrors($validation)->withInput();
        
        }else{

            $insdata = array('reason' => $request->get('reason'),
                             'created_at'   => date('Y-m-d H:i')  );

            $insert  = CancelReason::insertGetId($insdata);
            
            if($insert){

              return Redirect::to("admin/cancel-reasons")->withSuccess('Great! info has been added.');

            }else{

              return Redirect::to("admin/add-reason")->withFail('Something went to wrong.');
            }
        }

    }else{

      $data['title'] = "Add Cancel Reason";
      return view("Admin::add.add_reason",$data);
    }

  }/*Add Reason End */

  public function editReason(Request $request,$id){

     if ($request->isMethod('post')) {
      
      $validation = validator::make($request->all(),[

                    'reason' => 'required']);
    
        if ($validation->fails()) {
            
            return Redirect::to("admin/edit-reason")->withErrors($validation)->withInput();
        }else{

            $insdata = array('reason' => $request->get('reason'),
                             'created_at'   => date('Y-m-d H:i')  );

            $update  = CancelReason::where('id',$id)->update($insdata);
            
            if($update){

              return Redirect::to("admin/cancel-reasons")->withSuccess('Great! info has been update.');

            }else{

              return Redirect::to("admin/edit-reason/$id")->withFail('Something went to wrong.');
            }
        }

    }else{

      $data['title'] = "Edit Reason";
      $data['reason_info'] = CancelReason::where(array('id'=> $id))
                                          ->select('id','reason')
                                          ->first();
      return view("Admin::edit.edit_reason",$data);
    }


  }/*Edit cancel End*/

  

/*
  * Name: getState
  * Create Date: 20 Mar 2018
  * Purpose: This function gives state list by country
  */

  public function getState(Request $request){
    

      $res = array("success" => 0);

      $states = State::where('country_id', $request->get('country_id'))->get();    
      
      if (count($states) > 0) {
        
        $html = '<option value="">Select State</option>';

        foreach ($states as $key => $value) {
          
          $html = $html . '<option value="'.$value->id.'">'.$value->state_name.'</option>';
        }

        $res['html'] = $html;
      }
      return Response::json($res);
  }

  /*
  * Name: getCity
  * Create Date: 20 Mar 2018
  * Purpose: This function gives city list by state
  */
  public function getCity(Request $request){

      $res = array("success" => 0);

      $states = City::where('state_id', $request->get('state_id'))->get();    
      
      if (count($states) > 0) {
        
        $html = '<option value="">Select City</option>';

        foreach ($states as $key => $value) {
          
          $html = $html . '<option value="'.$value->id.'">'.$value->city_name.'</option>';
        }

        $res['html'] = $html;
      }
      return Response::json($res);
  }

  /*
  * Name: Promocode Export
  * Create Date: 06 April 2018
  * Purpose: This function Export all promocodes
  */

  public function promocodeExport(Response $request){

    $field = array('promocode.code','promocode.status','promocode.type','promocode.amount','promocode.start_date','promocode.expiry_date');

    $data = Promocode::get($field);

    $filename = 'Promocode List '.date('d-m-y');

    Excel::create($filename, function($excel) use($data) {

        $excel->sheet('list', function($sheet) use($data) {
           
        $sheet->cell('A1', function($cell) {$cell->setValue('PromoCode');   });
        $sheet->cell('B1', function($cell) {$cell->setValue('Status');         });
        $sheet->cell('C1', function($cell) {$cell->setValue('Type'); });
        $sheet->cell('D1', function($cell) {$cell->setValue('Amount');        });
        $sheet->cell('E1', function($cell) {$cell->setValue('Start Date');        });
        $sheet->cell('F1', function($cell) {$cell->setValue('Expiry Date');       });
        
       
            if (!empty($data)) {
                
                foreach ($data as $key => $value) {

                  $promocode   = $value->code;
                  $status      = $value->status;
                  $type        = $value->type;
                  $amount      = $value->amount;
                  $start_date  = $value->start_date;
                  $expiry_date = $value->expiry_date;

                  
                  if ($value->status=='1') {

                    $status = "Active";                    
                  }else{
                    $status = "Inactive";
                  }
                  
                  $i=$key+2;
                  $sheet->cell('A'.$i,$promocode);
                  $sheet->cell('B'.$i,$status);
                  $sheet->cell('C'.$i,$type);
                  $sheet->cell('D'.$i,$amount);
                  $sheet->cell('E'.$i,$start_date);
                  $sheet->cell('F'.$i,$expiry_date);
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

  }//End of PromocodeExport

  public function ratingReview(){


    $data['title'] = "Rating and Reviews ";

    return view("Admin::list.rating_review",$data);
 
  }/*End of Rating Review*/

  public function ratingReviewData(Request $request){

    $query = Feedback::query();

    // Datatables Variables
    $draw   = intval($request->get("draw"));
    $start  = intval($request->get("start"));
    $length = intval($request->get("length"));

    $where = array();

    $query->where($where);

    if ($request->get('search')['value'] != "") {

        $value = $request->get('search')['value'];
        
        $query->where('feedback.ride_id',"LIKE","%$value%")
              ->orWhere("drivers.first_name","LIKE","%$value%")
              ->orWhere("drivers.last_name","LIKE","%$value%")
              ->orWhere("passengers.first_name","LIKE","%$value%")
              ->orWhere("passengers.last_name","LIKE","%$value")
              ->orWhere("feedback.feedback","LIKE","%$value")
              ->orWhere('feedback.rating',"=",$value);
    }

    //Order
    $orderByField = "feedback.id";
    $orderBy = 'desc';

    if (isset($request->get('order')[0]['dir']) && $request->get('order')[0]['dir'] != "") {
                        
           $orderBy = $request->get('order')[0]['dir'];

    }

    if (isset($request->get('order')[0]['column']) && $request->get('order')[0]['column'] != "") {

        if ($request->get('order')[0]['column'] == 0) {
            
          $orderByField = "feedback.id";
        
        }

    }

    $field = array('feedback.ride_id as rideId','feedback.rating as rating','feedback.feedback as feedback','drivers.first_name as driver_first_name','drivers.last_name as driver_last_name','passengers.first_name as passenger_first_name','passengers.last_name as passenger_last_name');

    $query->where($where)
          ->leftjoin('users as drivers','feedback.driver_id','=','drivers.id')
          ->leftjoin('users as passengers','feedback.passenger_id','=','passengers.id');

    $total = count($query->get(['feedback.id']));     
             
    $info = $query->orderBy($orderByField,$orderBy)
                  ->skip($start)
                  ->take($length)
                  ->get($field);   
                
    $data = array();
    $sno = $start;


    foreach($info as $r) {

        $ride_detail = url('admin/ride-detail/'.$r->rideId);
          $data[] = array(             
             $sno = $sno+1,
             '<a href="'.$ride_detail.'" target="_blank" title="Ride Detail">'.$r->rideId.'</a>',
             ucfirst($r->driver_first_name.' '.$r->driver_last_name), 
             ucfirst($r->passenger_first_name.' '.$r->passenger_last_name),
             ucfirst($r->rating),
             ucfirst($r->feedback)
          );
    }

      $output = array(
                        "draw"            => $draw,
                        "recordsTotal"    => $total,
                        "recordsFiltered"  => $total,
                        "data"             => $data
                );

      echo json_encode($output);
      exit();  }/*Rating Review Data */

}// main end

