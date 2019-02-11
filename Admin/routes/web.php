<?php

Route::group(['module' => 'Admin', 'prefix' => 'admin', 'middleware' => ['web'], 'namespace' => 'App\Modules\Admin\Controllers'], function() {

	Route::group(['middleware' => 'guest:admin'], function () { 	

	    Route::get('/', 'AdminController@login');
	    Route::get('login', 'AdminController@login');
	    Route::post('post-login', 'AdminController@postLogin');

	    Route::any('forgot-password','AdminController@forgotPassword');
	    Route::any('reset-forgot-password','AdminController@resetPassword');
	});

	//Country, State, City	    	
   	Route::get('get-state','GeneralController@getState');
    Route::get('get-city','GeneralController@getCity');

    //after login url  
     Route::group(['middleware' => 'CheckLogin:admin'], function () { 	
    	
	    Route::get('dashboard', 'AdminController@dashboard');
	    Route::any('logout','AdminController@logout');
	    Route::any('change-password','AdminController@changePassword');
	    Route::any('dashboard-count','AdminController@DashboardCount');

	    //passenger listing
	    Route::get('/passengers','PassengerController@passengers');  
	    Route::get('passengers-data','PassengerController@passengersData');
	    Route::get('passenger-detail/{id}','PassengerController@passengerDetail');
	    Route::any('add-passenger','PassengerController@addPassenger');
	    Route::any('edit-passenger/{id}','PassengerController@editPassenger');

	    //Drivers	    
	    Route::get('/drivers-list','DriverController@drivers');  
	    Route::get('drivers-data','DriverController@driversData');
	    Route::any('add-driver','DriverController@addDriver');
	    Route::get('driver-detail/{id}','DriverController@driverDetail');
	    Route::any('edit-driver/{id}','DriverController@editDriver');
	    Route::get('check-driver-moving','DriverController@checkDriverMoving');
	    Route::any('get-driver-info','RideController@getDriverInfo');

	    //Staff	    
	    Route::get('/staff-list','StaffController@staff');  
	    Route::get('staff-data','StaffController@staffData');
	    Route::any('add-staff','StaffController@addStaff');
	    Route::get('staff-detail/{id}','StaffController@staffDetail');
	    Route::any('edit-staff/{id}','StaffController@editStaff');

	    //configuration
	    Route::get('/configuration','AdminController@configuration');  
	    Route::get('configuration-data','AdminController@configurationData');
	    Route::any('add-configuration','AdminController@addConfiguration');

	    //Brand
	    Route::get('/brand-list','VehicleController@brandList');  
	    Route::get('brand-list-data','VehicleController@brandListData');
	    Route::post('add-brand','VehicleController@addBrand');
	    Route::get('edit-brand','VehicleController@editBrand');

	    //Model
	    Route::get('/model-list','VehicleController@modelList');  
	    Route::get('model-list-data','VehicleController@modelListData');
	    Route::post('add-model','VehicleController@addModel');
	    Route::get('edit-model','VehicleController@editModel');

	    //Vehicle
	    Route::get('/vehicle-list','VehicleController@vehicleList');  
	    Route::get('vehicle-list-data','VehicleController@vehicleListData');
	    Route::any('add-vehicle','VehicleController@addVehicle');
	    Route::get('get-model','VehicleController@getModel');

	   
	    

	    //Ride
	    Route::get('rides-list','RideController@rideList');
	    Route::get('rides-list-data','RideController@rideListData');
	    Route::get('ride-detail/{id}','RideController@rideDetail');
	    Route::post('get-new-rides','RideController@getNewRides');
	    Route::get('update-recieve-status','RideController@updateRecieveStatus');
	    Route::any('view_all_images/{id}','RideController@viewAllImagesList');
	    Route::get('ride-cancel/{id}','RideController@rideCancel');
	    Route::any('delete-capturer-image','RideController@deleteCapturerImage');
	    Route::get('new-ride', 'RideController@newRide');
	    Route::any('post-new-ride','RideController@postNewRide');

	    /*Promocode Management*/
	    Route::any('promocode','GeneralController@promocode');
	    Route::any('add-promocode','GeneralController@addPromocode');
	    Route::any('promocode-data','GeneralController@promocodeData');
	    Route::any('edit-promocode/{id}','GeneralController@editPromocode');

	    /* app-info */
	    Route::any('app-info','GeneralController@appInfo');

	    /*base-charges*/
	    Route::any('base-charges','GeneralController@baseCharges');

	    /*rentail-packages*/
	    Route::get('rental-packages','GeneralController@rentalPackages');
	    Route::get('rentalpackages-data','GeneralController@rentalPackagesData');
	    Route::any('add-rental-packages','GeneralController@addRentalPackages');
	    Route::any('edit-rental-packages/{id}','GeneralController@editRentalPackages');

	    /* app-version */
	    Route::any('app-version','GeneralController@appVersion');

	    /*country management*/
	    Route::get('country-list','GeneralController@countryList');
	    Route::any('country-list-data','GeneralController@countryListData');
	    Route::any('add-country','GeneralController@addCountry');
	    Route::any('edit-country/{id}','GeneralController@editcountry');

	    /*State Management*/
	    Route::any('state-list/{id?}','GeneralController@statelist');
	    Route::any('state-list-data','GeneralController@stateListData');
	    Route::any('add-state','GeneralController@addState');
	    Route::any('edit-state/{id}','GeneralController@editState');

	    /*City Management*/
	    Route::any('city-list/{id?}','GeneralController@cityList');
	    Route::any('city-list-data','GeneralController@cityListData');
	   	Route::any('add-city','GeneralController@addCity');
	   	Route::any('edit-city/{id}','GeneralController@editCity');

	   	/*cancel-reasons system*/
	   	Route::any('cancel-reasons','GeneralController@cancelReasons');
	   	Route::any('cancel-reasons-list','GeneralController@cancelReasonsList');
	   	Route::any('add-reason','GeneralController@addReason');
	   	Route::any('edit-reason/{id}','GeneralController@editReason');

	   	//Content Management system
	   	Route::any('contents/','ContentController@contents');
	   	Route::get('content-list-data','ContentController@contentListData');
	   	Route::any('edit-content/{id}','ContentController@editContent');
	    
	   	//Notifications			
		Route::get('notifications','NotificationController@notifications');
		Route::get('notificationListData','NotificationController@notificationListData');
		Route::post('send-notification-users','NotificationController@sendNotificationUsers');
		Route::get('resend-notification/{id}','NotificationController@resendNotification');

		// Export System
		Route::get('driver-export','DriverController@driverExport');
		Route::get('passenger-export','PassengerController@passengerExport');
		Route::get('staff-export','StaffController@staffExport');
		Route::get('rides-export','RideController@ridesExport');
		Route::get('vehicle-export','VehicleController@vehicleExport');
		Route::get('promocode-export','GeneralController@promocodeExport');

		//status updated active & inactive
	    Route::get('status_activity/{id}/{Status}/{function}','AdminController@status_activity');
	        
	    //Delete activity
	   	Route::get('delete_activity/{id}/{function}','AdminController@delete_activity');
	   	Route::get('restore_element/{id}/{function}','AdminController@restore_element');


	   	//Live Tracking
	   	Route::get('live-tracking-drivers','AdminController@liveTrackingDrivers');
	   	Route::get('live-tracking-rides','AdminController@liveTrackingRides');
	   	Route::get('earnings','AdminController@earnings');
	   	Route::get('earnings-data','AdminController@earningsData');

	   	//Roll Management
	   	Route::get('role-management/{id}','AdminController@roleManagement');
	   	Route::post('set-staff-permission','AdminController@setStaffPermission');

	   	//Passenger
        Route::post('add-passanger', 'RideController@addPassanger');

        //renting-review
        Route::get('rating-review','GeneralController@ratingReview');
        Route::get('rating-review-data','GeneralController@ratingReviewData');

        //get running rides
        Route::get('get-running-rides','RideController@getRunningRides');

	});

});