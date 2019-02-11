<?php

namespace App\Modules\Api\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Sos, DriverInfo};

use Response;

/*
* Name: VehicleController
* Create Date: 20 March 2018
* Purpose: This function is used for general functions 
*/

class VehicleController extends Controller
{
    
    /*
    * Name:getDriverVehicle
    * Create Date: 20 March 2018
    * Purpose: This function is used to get driver vehicle
    */

    public function getDriverVehicle(Request $request){

        $res['success'] = false;
        $res['msg']     = trans('Api::language.LANG_VEHICLE_NOT_ASSIGNED_YET');
        $res['code']    = 400;         

        $user = $request->get('user');
        
        $where = array('driver_id' => $user->id);

        $fields = array();

        $driver_license_fields = "id,driver_id,license_number,vehicle_type,license_image,issued_on,expiry_date";

        $getdata = DriverInfo::where($where)
                    ->with(["assigned_vehicle","driver_license:$driver_license_fields"])                    
                    ->first();
        
        if (!empty($getdata)) {
            
            $res['success'] = true;     
            $res['msg']     = trans('Api::language.LANG_VEHICLE_FOUND');        
            $res['code']    = 200;
            $res['result']  = $getdata;
        }

        return Response::json($res);
    }

}
