<?php

/**
 *	Admin Helper  
 */

function d($array){

	echo "<pre>";
	print_r($array);die;
}

function check_set($value){

	if (isset($value) && $value != "") {
		
		return $value;
	
	}else{

		return "N/A";
	}
}

function ride_status($status){
	
	if ($status == "Pending") {

	    $rideStatusHtml = '<span class="label label-warning">Pending</span>';
	    
	}elseif($status == "Accepted"){

	    $rideStatusHtml = '<span class="label label-info">Accepted</span>';

	}elseif($status == "Cancelled"){

	    $rideStatusHtml = '<span class="label label-danger">Cancelled</span>';

	}elseif($status == "Completed"){

	    $rideStatusHtml = '<span class="label label-success">Completed</span>';

	}elseif($status == "Running"){

	    $rideStatusHtml = '<span class="label label-success">Running</span>';

	}else{

	    $rideStatusHtml = '<span class="label label-info">Accepted</span>';

	}

	return $rideStatusHtml;
}

function show_action(){
	
    //Action button
    $actionBtn = '<div class="">
        <div class="btn-group">
          <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span></button>
          <ul class="dropdown-menu dropdown-menu-right" role="menu">
            <li>'.$status.'</li>
            <li>'.$edit_url.'</li>
           <li>'.$detail_url.'</li>
           <li>'.$deleteUrl.'</li>                 
          </ul>
        </div>
      </div>';

}

function check_permission($menu){
	
	if(Session::get('staff_permission') & $menu){
		
		return 1;
	
	}else{
		
		return 0;
	}
	
}

function check_url_permission($segment){
	
	if (in_array($segment, ['drivers-list'])) {

		return check_permission(Config::get('constants.DRIVER_READ'));
		
	}elseif(in_array($segment, ['add-driver'])){

		return check_permission(Config::get('constants.DRIVER_ADD'));
	
	}elseif(in_array($segment, ['edit-driver'])){

		return check_permission(Config::get('constants.DRIVER_EDIT'));
	
	}elseif(in_array($segment, ['delete_activity'])){

		return check_permission(Config::get('constants.DRIVER_DELETE'));
	
	}elseif(in_array($segment, ['passengers'])){

		return check_permission(Config::get('constants.PASSENGER_READ'));
	
	}elseif(in_array($segment, ['add-passenger'])){

		return check_permission(Config::get('constants.PASSENGER_ADD'));
	
	}elseif(in_array($segment, ['edit-passenger'])){

		return check_permission(Config::get('constants.PASSENGER_EDIT'));
	
	}elseif(in_array($segment, ['delete_activity'])){

		return check_permission(Config::get('constants.PASSENGER_DELETE'));
	
	}elseif(in_array($segment, ['vehicle-list'])){

		return check_permission(Config::get('constants.VEHICLE_READ'));
	
	}elseif(in_array($segment, ['add-vehicle'])){

		return check_permission(Config::get('constants.VEHICLE_ADD'));
	
	}elseif(in_array($segment, ['delete_activity'])){

		return check_permission(Config::get('constants.VEHICLE_DELETE'));

	}elseif(in_array($segment, ['rides'])){

		return check_permission(Config::get('constants.RIDE_READ'));
	
	}elseif(in_array($segment, ['new-ride'])){

		return check_permission(Config::get('constants.RIDE_ADD'));
	
	}else{

		return true;
	}

}

?>