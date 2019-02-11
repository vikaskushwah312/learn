<?php

use App\Models\Token;
use App\Models\Users;
use App\Models\Notification;
use App\Models\{RidePromocode,Charges,Configuration,RentalPackage,RideRentalPackage};

//use TokenExpiredException;

function false_res($msg){
    
    return array("success" => false, "msg" => $msg, "code" => 400);
}

function true_res($msg){
    
    return array("success" => true, "msg" => $msg, "code" => 200);
}

function send_json($array){

    return Response::json($array);
}

/*
* Name: check_validation
* Create Date: 4 Dec 2017
* Created By: Pankaj Gawande  
*/


function check_validation($request, $array){

    $validator = Validator::make($request->all(), $array);

    if ($validator->fails()) {
    	
    	$res['success'] = false;
    	$res['msg'] 	= "Validation failed";
    	$res['result'] 	= $validator->errors();
    	$res['code'] 	= 401;
    	
    	echo json_encode($res);
    	exit();
   	}
}

/*
* Name: random_code
* Create Date: 5 Dec 2017
* Created By: Pankaj Gawande
* Purpose: To generate random code
*/

function random_code(){

    return rand(1111, 9999);
}


function getTimeStamp()
{

    return substr(base_convert(time(), 10, 36) . md5(microtime()), 0, 16);

}

/*
* Name: send_message
* Create Date: 21 Dec 2017
* Created By: Pankaj Gawande
* Purpose: This function is used to send sms to user
*/

function send_message($mobileNumber, $message){

    $mobileNumber = "+".$mobileNumber;   
    
}

/*
* Name: send_notification
* Create Date: 22 Dec 2017: 
* Created By: Pankaj Gawande
* Purpose: This function is used to send notification
*/

#API access key from Google API's Console
define( 'API_ACCESS_KEY', 'AAAA3UpgT2s:APA91bHHqiQsBOKymVi6VBcN7LB069RLEHjKtA56IAx57JLBgTFXEuVbkdCtjKMCHFMHRZJbXKJ_3YdWCnCHdYD2k4SArn3R5jTQQYEEKsYI5CGuSklIctw8EPeEvozdMigIDB9IkJwI');

function send_notification($userArray, $body, $title){
    
    $data = Users::whereIn('id', $userArray)
                ->chunk(100, function($users) use($body, $title){
    
        $AndroidNIds = array();
        $iosNIds     = array();

        foreach ($users as $value) {
            
            if ($value->notification_setting == '1' && $value->notification_id != "") {
                
                if ($value->device_type == "android") {
                    
                    $AndroidNIds[] = $value->notification_id;                    

                }elseif($value->device_type == "iphone"){

                    $iosNIds[] = $value->notification_id;
                    
                }

            }
            
            $image = "";
            
            if (isset($body['image']) && $body['image'] != "") {
                
                //$image = url('/').'public/uploads/notification/'.$body['image'];
                $image = $body['image'];

            }

            $notification = array(
                
                "user_id"       => $value->id,
                "title"         => $title,
                "message"       => $body['message'],
                "type"          => $body['type'],
                "image"         => $image,
                "json"          => isset($body['json']) ? json_encode($body['json']) : "",
                "read_status"   => 0,
                "ride_id"       => isset($body['ride_id']) ? $body['ride_id'] : "",
                "created_at"    => date('Y-m-d H:i:s')
            );

            Notification::insert($notification);
        }
        
        if (count($AndroidNIds) > 0) {
            
            notify_android($AndroidNIds, $body, $title);
            
        }

        if (count($iosNIds) > 0) {
            
            notify_ios($iosNIds, $body, $title);
        }

        return false;

    });
                  
}

function notify_android($registrationIds, $body, $title){

    $msg = ['title' => $title,            
            'icon'  => 'myicon',
            'sound' => 'mySound'];
    
    $array = array_merge($body, $msg);
    
    $fields = array('registration_ids'  => $registrationIds,
                    //'notification'      => $msg,
                    'data'              => $array);       
    
    $headers = array(
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-Type: application/json');
    
    execute_notify($headers, $fields);

}

function notify_ios($registrationIds, $body, $title){
    
    $msg = ['body'  => $body['message'],
            'title' => $title,            
            'icon'  => 'myicon',
            'sound' => 'mySound'];

    $fields = array('registration_ids'  => $registrationIds,
                    'notification'      => $msg,
                    'data'              => $body);        
    
    $headers = array(
                'Authorization: key=' . API_ACCESS_KEY,
                'Content-Type: application/json');
    
    execute_notify($headers, $fields);

}

function execute_notify($headers, $fields){
    
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    //d(json_encode( $fields ));
    $result = curl_exec($ch );
    curl_close( $ch );
    
    //echo $result;die;

}


/*
* Name: get_order_number
* Create Date: 22 Dec 2017
* Created By: Pankaj Gawande
* Purpose: This function is used to return order number (This is luhn algorithm used to generate credit card, debit card number etc).
*/

function get_order_number($customerId, $orderId){

    $number = rand(1,9).$customerId.rand(1,9).$orderId;

    $stack = 0;
    $number = str_split(strrev($number));

    foreach ($number as $key => $value)
    {
        if ($key % 2 == 0)
        {
            $value = array_sum(str_split($value * 2));
        }
        $stack += $value;
    }

    $stack %= 10;

    if ($stack != 0)
    {
        $stack -= 10;     $stack = abs($stack);
    }

    $number = implode('', array_reverse($number));
    $number = strval($stack) . $number;

    return $number;  
}

/*
* Name: post_token_device
* Create Date: 12 Jan 2018
* Created By: Pankaj Gawande
*/

function post_token_device($data){

    //Delete notification id if exists
    Token::where(['notification_id' => $data['notification_id']])
            ->update(['notification_id' => '']);

    $where = array('user_id' => $data['user_id']);

    if ($data['device_type'] == "web") {
        
        $where['ip']        = $data['ip'];         
        $where['browser']   = $data['browser'];

        $getToken = Token::where($where)
                        ->get(['id','token']);

    }else{

        $where['device_id'] = $data['device_id'];                 
        $getToken = Token::where($where)
                        ->get(['id','token']);

    }

    if (count($getToken) > 0) {
                        
        $check = Token::where($where)
            ->update($data);

    }else{

        $data['created_at'] = date('Y-m-d H:i:s');
   
        Token::insert($data);
    }
}

/*
* Name: expire_token
* Create Date: 12 Jan 2018
* Created By: Pankaj Gawande
*/

function expire_token($token){
    
    try{

        if ($token != "") {
             
            JWTAuth::setToken($token)->invalidate();      
           
        }

    }catch(Tymon\JWTAuth\Exceptions\TokenExpiredException $e){

        //echo $e->getMessage();
    }
}

/*
* Name: get_user_token
* Create Date: 15 Jan 2018
* Created By: Pankaj Gawande
* Purpose: This function is used to get jwt token of a user and expire it. 
*/

function expire_user_token($id){

    if ($id != "") {
        
        $getToken = Token::where(['user_id' => $id])
            ->orderBy('id', 'DESC')
            ->first(['token']);
        
        if (count($getToken) > 0 && $getToken->token != "") {

            expire_token($getToken->token);                 
        }        
    }
}


/*
* This function is used to check file exists and then delete it 
*/

function delete_image($file){

    if (file_exists($file)) {
        
        unlink($file);
    }
}

function ride_promocode($fare, $ridePromocodeId){

    $where = ['ride_promocode.id' => $ridePromocodeId];

    $data = RidePromocode::where($where)                        
                    ->join('promocode', 'promocode.id', '=', 'ride_promocode.promocode_id')
                    ->first(['amount','ride_promocode.promocode_id','promocode.type']);
    $res = array();

    if (!empty($data)) {
        
        $res['promocode_id'] = $data->promocode_id;

        if ($data->type == "flat") {
            
            $res['amount'] = $data->amount;     

        }else{

            $res['amount'] = $fare * $data->amount/100;             
        }

    }
    return $res;
}

//NOTE: WE CALL THIS FUNCTION FROM COMPLETE RIDE BY DRIVER AND CREATE RIDE BY STAFF
function calculate_fare($distance,$waitingTime=0,$rideRentalPackageId="",$ridePromocodeId=""){
    
    $promocodeCharges = $waitingCharges = $payAmount = $totalAmount = $gstAmount = $fare  = 0;
    
    $chergeInfo = Charges::first();

    //Calculate waiting charges
    if (!empty($chergeInfo)) {
        
        $waitingCharges = $waitingTime * $chergeInfo->waiting_charges_per_minute;

    }

    //IF RIDE IS RENTAL
    if (!empty($rideRentalPackageId)){
        
        $rentalPackageInfo = RideRentalPackage::where('id', $rideRentalPackageId)->first();
        
        if (!empty($rentalPackageInfo)) {
            
            $fare = $rentalPackageInfo->charges;                             

        }

    }else{

        if (!empty($chergeInfo)) {
          
            $fare   = $chergeInfo->base_charges;
            
            if($distance > $chergeInfo->base_charges_for_km){

               $getDistance = $distance - $chergeInfo->base_charges_for_km;


               $fare   = $fare + ($getDistance * $chergeInfo->charges_per_km);

            }
       }

    }    


    //Calculate promocode charges
    $ridePromocodeData = ride_promocode($fare,$ridePromocodeId);
    
    if (!empty($ridePromocodeData)) {
        
        $promocodeCharges = $ridePromocodeData['amount'];

    }

    $totalAmount = ($fare + $waitingCharges) - $promocodeCharges;
            
    //Calculate GST
    $gstAmount = get_gst($totalAmount); 
    
    $payAmount = $gstAmount + $totalAmount;

    $res = array(
       "promocode_charges"  => $promocodeCharges,
       "waiting_charges"    => $waitingCharges,
       "fare"               => $fare, 
       "gst_amount"         => $gstAmount, 
       "total_amount"       => $totalAmount, 
       "pay_amount"         => $payAmount
    );  
  
  return $res;

}

function send_sms($mobile,$message){

    $curl         = curl_init();
    $apikey       = 'somerandomkey';//if you use apikey then userid and password is not required
    $userId       = 'tapadia';
    $password     = '123456EGc';
    $sendMethod   = 'simpleMsg'; //(simpleMsg|groupMsg|excelMsg)
    $messageType  = 'TEXT'; //(text|unicode|flash)
    $senderId     = 'SMSGAT';
    $mobile       = $mobile;//comma separated
    $msg          = $message;
    $scheduleTime = '';//mention time if you want to schedule else leave blank

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://www.smsgateway.center/SMSApi/rest/send",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => 1,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "userId=$userId&password=$password&senderId=$senderId&sendMethod=$sendMethod&msgType=$messageType&mobile=$mobile&msg=$msg&duplicateCheck=true&format=json&route=1",
      CURLOPT_HTTPHEADER => array(
        "apikey: $apikey",
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded"
      ),
    ));

    $response = curl_exec($curl);
    
/*    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      
      echo "cURL Error #:" . $err;

    }else {

      echo $response;

    }*/
}

function get_gst($totalAmount){
    
    $gstAmount = $totalAmount;

    //Calculate gst,total amount and pay amount
    $getConfig = Configuration::where(['type' =>'gst'])->first(['value']);

    if (!empty($getConfig) && $getConfig->value != "") {
      
        $gstAmount = $totalAmount * $getConfig->value/100;
        
        
    }

    return $gstAmount;    
}