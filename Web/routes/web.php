<?php

Route::group(['module' => 'Web', 'middleware' => ['web'], 'namespace' => 'App\Modules\Web\Controllers'], function() {


	//before login
	Route::any('/web', 'WebController@index');
	Route::get('login','WebController@userLogin');
	Route::get('/registration','WebController@userRegistration');
	Route::get('web/mobile-verification/{id}','WebController@mobileVerification');
	Route::any('web/set-session','WebController@setSession');
    Route::get('web/update-session','WebController@updateSession');
    Route::get('change-mobile-number','WebController@changeMobileNumber');
    Route::any('/forgot-password','WebController@forgotPassword');


    //Social Login
    Route::any('/web/social-login/{provider}', 'WebController@redirectToProvider');
    Route::any('/web/social-login/{provider}/callback', 'WebController@handleProviderCallback');

    Route::get('get-content/{type}', 'WebController@getContent');

    //Check user is set session and verified
    Route::group(['middleware' => ['CheckWebUser']], function () {

        //after login
        Route::get('get-profile','WebController@getProfile');
        Route::any('logout','WebController@Logout');
        Route::get('ride-now','WebController@rideNow');
       

        //Country, State, City          
        Route::get('get-state','WebController@getState');
        Route::get('get-city','WebController@getCity');
    	
        //Rides
        Route::get('request-now','WebController@requestNow');
        Route::get('request-ride','WebController@requestRide');



        //Notifincation
        Route::get('notification','WebController@notification');

        //Ride-History
        Route::get('ride-history','WebController@rideHistory');
        Route::get('ride-detail/{id}','WebController@rideDetail');
    });



});
