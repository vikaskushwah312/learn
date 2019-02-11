@extends('Web::layout.master_inner')
@section('css')
  
@endsection
@section('content')
<div class="container-fluid bg-light">
   <div class="row">
   @if(count($gst) > 0)
      <div class="container">
         <div class="row my-4">
            <div class="col">
               <h3 class=""><strong>Ride Detail</strong></h3>
            </div>
         </div>
         <div class="row px-3">
            <div class="col mx-2  fancy-box">
               <div class="row">
                  <div class="col-6 mt4">
                     <div class="media">
                        <div class="quote">
                        	<!-- {!!Html::image('/public/webTheme/images/pic.png','',['class' => 'mt-4 ml-2'])!!} -->
                        	<span id="driver_image"></span>
                           <!-- <img src="images/pic.png" class="mt-4 ml-2"> -->
                        </div>
                        <div class="media-body  ml-3 mt-4">
                           <h5 class="mt-0 mb-0"><span id="driver_name"></span></h5>
                           <p class="mb-0 color-green">Ride Id: <span id="ride_id"></span></p>
                           <p class="mb-0 color-green">Ride Type: <span id="ride_type"></span></p>
                           <p class="mb-0"><span id="vehicle_name">Toyoto Pius</span></p>
                           <!-- <div class="media">
                           		{!!Html::image('/public/webTheme/images/coin.png','',['class' => 'mt-1'])!!}
                              
                              <div class="media-body">
                                 <p class="ml-2"><span id="total_amount"></span></p>
                              </div> 
                           </div> -->
                        </div>
                     </div>
                  </div>
                  <div class="col-6 mt-4 float-right">
                     <div class="float-right">
                        <p class="mb-0"><b><span id="ride_status"></span></b></p>
                        <span id = "ride_date"></span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row px-3 mt-4">
            <div class="col mx-2  fancy-box">
               <div class="col mt4">
                  <div class="media p-2">
                  	{!!Html::image('/public/webTheme/images/location.png','',['class' => 'align-self-center mr-3',"alt"=>"Generic placeholder image"])!!}
                     <!-- <img class="align-self-center mr-3" src="images/location.png" alt="Generic placeholder image"> -->
                     <div class="media-body ">
                        <input class="form-control model-input mb-4" id="pickupAddress" placeholder="Pick-up Location" type="text">
                        <input class="form-control model-input" id="dropupAdress" placeholder="Drop-up Location" type="text">
                     </div>
                  </div>
               </div>
               <div class="col mt4">
                  <div class="map-responsive">
                     <!-- <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA0s1a7phLN0iaD6-UE7m4qP-z21pH0eSc&amp;q=Eiffel+Tower+Paris+France" style="border:0" allowfullscreen="" width="100%" height="400" frameborder="0"></iframe> -->
                     <div id="map" style="width: 100%;height: 600px;"></div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row my-4">
            <div class="col">
               <h3><strong>Payment Detail</strong></h3>
            </div>
            <div class="col">
               <h3><strong>Client Feedback</strong></h3>
            </div>
         </div>
         <div class="row px-3 mb-4">
            <div class="col mx-2  fancy-box">
               <div class="row">
                  <div class="col mt4 p-4">
                     <div class="media">
                     	{!!Html::image('/public/webTheme/images/coin1.png','',['class' => 'ml-2'])!!}
                       <!--  <img src="images/coin1.png" class="ml-2"> -->
                         <div class="media-body  ml-3">
                           <h5 class="mt-0 mb-0"><span id="pay_amount"></span></h5>
                           <p class="mb-0">Cash</p>
                        </div> 
                     </div>
                     <hr>
                     <div class="col">
                        <div class="row">
                           <div class="col">
                              <h6>Trip Fare</h6>
                              <h6>Waiting Cost</h6>
                              <h6>Promo Discount</h6>
                           </div>
                           <div class="col float-right">
                              <div class="float-right">
                                 <h6><span id="trip_fare"></span></h6>
                                 <h6><span id="wating_cast"></span></h6>
                                 <h6><span id="promocode_discount"></span></h6>
                              </div>
                           </div>
                        </div>
                     </div>
                     <hr>
                     <div class="col">
                        <div class="row">
                           <div class="col">
                             <h6>Total</h6>
                              <h6>GST({{@$gst->value}}%)</h6>
                           </div>
                           <div class="col float-right">
                              <div class="float-right">
                              	<h6><span id="total_amount_withot_gst"></span></h6>
                                 <h6><span id="gst_amount"></span></h6>
                              </div>
                           </div>
                        </div>
                     </div>
                     <hr>
                     <div class="col">
                        <div class="row">
                           <div class="col">
                              <h6 class="color-green">Pay Amount</h6>
                           </div>
                           <div class="col float-right">
                              <div class="float-right">
                                 <h6 class="color-green"><span id="pay_amount_with_gst"></span></h6>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col mx-2 fancy-box">
               <div class="row">
                  <div class="col p-3 mt3">
                  	<!-- {!!Html::image('/public/webTheme/images/five-star.png','',['class' => ''])!!} -->
                     <!-- <img src="images/five-star.png"> -->
                                         
                     <p class="text-justify"><span id="rating"></span></p>
                           
                  </div>
               </div>
               <hr>
               <div class="col">
                  <div class="row">
                     <div class="col">
                        <h6>COMMENT</h6>
                        <p class="text-justify"><span id="comment"></span><!-- Lorem ipsum dolor sit amet, consectetur adipiscingelit.Nullam commodo tempus
                           interdum.Aliquam erat volutpat. Mauris eu nibh a leo volutpat lacinia Mauris eu
                           nibh a leo volutpat lacinia. -->
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endif
@endsection
@section('script')
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&language=en&key=AIzaSyD-Da1KcBqqG6I4LTakwmeISnl34V-Ex6E"></script>
<script type="text/javascript">

	function rideDetails(){

		var rideId = "{{Request::Segment(2)}}";

		$.ajax({
                url: "{{url('api/get-ride-detail')}}",
                method:'GET',
                dataType: 'JSON',
                data:{'ride_id':rideId},
                headers  : {'Authorization': "{{'Bearer '.Session::get('set_token')}}"},
                success: function(res){


                  if (res.success == true) {

                  	//get driver info
                       $(res.result).each(function( data,value) {
                       		
	                       	if(value.vehicle_name != null ){
	                       		
	                       		var vehicleName = value.vehicle_name.charAt(0).toUpperCase() + value.vehicle_name.slice(1);

							  	              $("#vehicle_name").html(vehicleName);

	                       	}else{

	                       		/*$("#vehicle_name").html('N/A');*/
	                       	}
	                       	$("#total_amount").html('{{Config::get("constants.CURRENCY")}}'+value.pay_amount+'(Cash)');
	                       	if(value.ride_status == 'Cancelled'){

	                       		
	                       		$("#ride_status").html(value.ride_status);
	                       		$('#ride_status').css('color', 'red');

	                       	}else if(value.ride_status == 'Completed'){


	                       		$("#ride_status").html(value.ride_status);
	                       		$("#ride_status").addClass('status');
	                       	}
	                       	
	                       	$("#ride_date").html(value.created_at);
	                       	$("#pickupAddress").val(value.pickup_location);
	                       	$("#dropupAdress").val(value.dropoff_location);

	                       		if (value.pickup_location != "" && value.dropoff_location != "") {

					                var directionsService = new google.maps.DirectionsService;
					                var directionsDisplay = new google.maps.DirectionsRenderer;
					                var map = new google.maps.Map(document.getElementById('map'), {
					                    zoom: 8,
					                    center: {
					                        lat: 22.8354,
					                        lng: 75.8069
					                    }
					                });
					                directionsDisplay.setMap(map);

					                calculateAndDisplayRoute(directionsService, directionsDisplay);
					            }

	                       	if(value.fare != null){
	                       		$("#trip_fare").html('{{Config::get("constants.CURRENCY")}}'+value.fare+'');
	                       	}else{

	                       		$("#trip_fare").html('{{Config::get("constants.CURRENCY")}}0.00');
	                       	}
	                       	
	                       	if(value.waiting_charges != null){

	                       		$("#wating_cast").html('{{Config::get("constants.CURRENCY")}}'+value.waiting_charges+'');
	                       	}else{

	                       		$("#wating_cast").html('{{Config::get("constants.CURRENCY")}}0.00');
	                       	}
	                       	if(value.promocode_charges != null){
	                       			
	                       		$("#promocode_discount").html('{{Config::get("constants.CURRENCY")}}'+value.promocode_charges+'');
	                       	}else{
	                       		
	                       		$("#promocode_discount").html('{{Config::get("constants.CURRENCY")}}0.00');
	                       	}
	                       	if(value.total_amount != null){

	                       		$("#total_amount_withot_gst").html('{{Config::get("constants.CURRENCY")}}'+value.total_amount+'');
	                       	}else{
	                       		
	                       		$("#total_amount_withot_gst").html('{{Config::get("constants.CURRENCY")}}'+0.00+'');
	                       	}
	                       	if(value.gst_amount != null){

	                       		$("#gst_amount").html('{{Config::get("constants.CURRENCY")}}'+value.gst_amount+'');
	                       	}else{
	                       		
	                       		$("#gst_amount").html('{{Config::get("constants.CURRENCY")}}'+0.00+'');
	                       	}
	                       	if(value.pay_amount != null){

	                       		$("#pay_amount").html('{{Config::get("constants.CURRENCY")}}'+value.pay_amount+'');
	                       		$("#pay_amount_with_gst").html('{{Config::get("constants.CURRENCY")}}'+value.pay_amount+'');

	                       	}else{
	                       		
	                       		$("#pay_amount").html('{{Config::get("constants.CURRENCY")}}0.00');
	                       		$("#pay_amount_with_gst").html('{{Config::get("constants.CURRENCY")}}0.00');
	                       	}
	                      
	                       	$("#ride_id").html(value.id);
	                       	$("#ride_type").html(value.ride_type);
	                       	
                        });
                        
                        //get feedback and rating
                       
                        $(res.result.feedback).each(function( data,val) {

                            if(val.feedback != ""){
                                $("#comment").html(val.feedback);

                            }else{

                              $("#comment").html('N/A');
                            }

                            if(val.rating != null){

                        
                        var ratingData = val.rating;
                        //document.write(ratingData);
                        var res = typeof ratingData;
                        //alert(res);
                        
                        var htmlData='';
                        for(i=1;i<=ratingData; i++){
                          
                          if(ratingData >= i){
                            //document.write(ratingData);
                              if(res != "number"){
                               // alert(res);

                                  if(i==ratingData){
                                    //document.write(ratingData);
                                     htmlData = htmlData + '<span class="fa fa-star-half-o" style="color: #80ff00 ; font-size: medium;"></span>';
                                  }else{
                                    //alert(i);
                                    //document.write(ratingData);
                                
                                     htmlData = htmlData +'<span class="fa fa-star" style="color: #FFFF66; font-size: medium;"></span>';
                                 } //document.write(htmlData);
                            }
                          }else{

                             htmlData = htmlData + '<span class="fa fa-star" style="color: black; font-size: medium;"></span>';
                          }
                        
                        }     
                         }     $("#rating").html(htmlData);
                            }else{

                              $("#rating").html('N/A');
                            }
                        });

                       //get all info
                       $(res.result.driver).each(function( index,v) {

	                       	if(v.first_name != ""){

	                       		var driverName = v.first_name.charAt(0).toUpperCase() + v.first_name.slice(1);
							  	$("#driver_name").html(driverName+' '+v.last_name);

	                       	}else{

	                       		$("#driver_name").html('N/A');
	                       	}
	                       	if(v.profile_image != ""){

	                       		var profileImage = '{!!Html::image(Config::get("constants.PROFILE_IMAGE")."'+v.profile_image+'","",["class" => "mt-4 ml-2"]) !!}';

							  	$("#driver_image").html(profileImage);

	                       	}else{

	                       		$("#driver_image").html('{!!Html::image(Config::get("constants.NO_IMAGE")."user-no-image.png","",["class" => "mt-4 ml-2"]) !!}');
	                       	}
                       		
						});
                        
                  }else{
                        

                        //show validatiom msg
                        if(res.result != undefined){

                           for(var key in res.result){
                            	
                            	//show validation message
                                /*$("#login_"+key).parent().find('.help-block').html(res.result[key]).show();*/
                            } 
                        }else{
                            
                            swal({
                              title:'Oops!',
                              text:res.msg,
                              type:'error',
                              timer:2000
                            });
                        }
                    }
                }  
          });
		
	}
	rideDetails();

	function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: {
                lat: 22.8354,
                lng: 75.8069
            }
        });

    }
    function calculateAndDisplayRoute(directionsService, directionsDisplay) {

        directionsService.route({
            origin: document.getElementById('pickupAddress').value,

            destination: document.getElementById('dropupAdress').value,
            optimizeWaypoints: true,
            travelMode: 'DRIVING'
        }, function(response, status) {
            if (status === 'OK') {
                directionsDisplay.setDirections(response);
                var route = response.routes[0];

            } else {
                //window.alert('Directions request failed due to ' + status);
            }
        });
    }
    initMap();
</script>
@endsection