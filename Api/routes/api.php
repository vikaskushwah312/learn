<?php

Route::group(['module' => 'Api', 'prefix' => 'api', 'middleware' => ['api'], 'namespace' => 'App\Modules\Api\Controllers'], function() {

    Route::get('base_url', 'ApiController@index');
    Route::post('login', 'AuthController@login');
    Route::post('forgot-password', 'AuthController@forgotPassword');
    Route::post('reset-password', 'AuthController@resetPassword');
    Route::post('social-login', 'AuthController@socialLogin');
    Route::post('passenger-register', 'AuthController@passengerRegister');
    
    //Content
    Route::get('get-content', 'ContentController@getContent');
    Route::get('get-charges', 'RideController@getCharges');

    //Cancel Reasons
    Route::get('cancel-reasons', 'ContentController@cancelReasons');

    //Country/City/State
    Route::get('countries', 'ContentController@countries');
    Route::get('states', 'ContentController@states');
    Route::get('cities', 'ContentController@cities');

    Route::get('check-email', 'ContentController@checkEmail');
    Route::get('check-sms', 'ContentController@checkSms');

    //CRON
    Route::get('cron/find-driver', 'CronController@findDriver');

    //AFTER LOGIN
    Route::group(['middleware' => ['check_token']], function() {
    
        Route::post('verify-mobile', 'AuthController@verify_mobile');
        Route::post('resend-verification-code', 'AuthController@resendVerificationCode');
        Route::post('change-mobile-number', 'AuthController@changeMobileNumber');
    
        //SOS
        Route::post('add-sos', 'ApiController@addSos');
        Route::post('send-sos', 'ApiController@sendSos');

        //Settings
        Route::get('app-info', 'ContentController@getAppInfo');
        Route::post('update-setting', 'ContentController@updateSetting');
        Route::post('change-online-status', 'AuthController@changeOnlineStatus');

        //User
        Route::get('get-profile', 'UserController@getProfile');
        Route::any('update-profile', 'UserController@updateProfile');
        Route::post('change-password', 'UserController@changePassword');

        //Driver
        Route::post('upload-license', 'DriverController@uploadLicense');
        Route::post('accept-ride-request', 'DriverController@acceptRideRequest');
        Route::post('reject-ride-request', 'DriverController@rejectRideRequest');
        Route::post('driver/start-ride', 'DriverController@startRide');
        Route::post('driver/check-ride-otp','DriverController@checkRideOtp');
        Route::post('driver/complete-ride', 'DriverController@completeRide');
        Route::get('driver/reached', 'DriverController@reached');
        Route::post('driver/update-lat-long', 'DriverController@updateLatLong');
        Route::post('driver/not-accepted-reason', 'DriverController@notAcceptedReason');
        
        //Vehicle
        Route::get('get-driver-vehicle', 'VehicleController@getDriverVehicle');
        
        //Ride
        Route::get('get-rides', 'RideController@getRides');
        Route::get('later-rides', 'RideController@laterRides');
        Route::get('get-ride-detail', 'RideController@getRideDetail');
        Route::post('create-ride', 'RideController@createRide');
        Route::get('find-driver', 'RideController@findDriver');
        Route::post('cancel-request', 'RideController@cancelRequest');
        Route::post('cancel-booking', 'RideController@cancelBooking');
        Route::post('give-feedback', 'RideController@giveFeedback');
        Route::post('auto-capture', 'RideController@autoCapture');
        Route::post('driver/cash-collect', 'DriverController@cashCollect');
        Route::get('check-promocode', 'RideController@checkPromocode');
        Route::get('rental-packages', 'RideController@rentalPackages');
        Route::get('cancel-ride', 'RideController@cancelRide');

        //Notifications
        Route::get('notifications', 'NotificationController@notifications');
        Route::post('read-notification', 'NotificationController@readNotification');
        Route::post('delete-notification', 'NotificationController@deleteNotification');
        
        Route::get('logout', 'AuthController@logout');
    });
});
