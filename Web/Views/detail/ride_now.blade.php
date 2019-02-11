@extends('Web::layout.master_ride')
@section('css')
<style type="text/css">
  input:checked {
    height: 50px;
    width: 50px;
}
</style>
@endsection
@section('content')
{!! Form::open(array('id'=>"ride_type_Form", 'name'=>"ride_type_Form",'files'=>true, 'class' => '' , )) !!}
<div class="container">
   <div class="row">
      <div class="col my-3">
         <h5 id="ride_now_heading"><strong> Ride Now</strong></h5>
         <h5 style="display: none;" id="request_ride_heading"><strong> Request Ride</strong></h5>
      </div>
   </div>
   <div class="row">
      <div class="col text-center  fancy-box">
         <div class="media p-2">
            {!!Html::image('public/webTheme/images/location.png','',['class' => 'mt-1'])!!}
            <div class="media-body ">
               <input class="form-control model-input mb-4" id="pickupAddress" name="pickupAddress" placeholder="Pick-up Location" type="text">
               <input class="form-control model-input" id="dropupAddress" name="dropupAddress" placeholder="Drop-off Location" type="text">

               <input type="hidden" name="pick_up_lat" id="pick_up_lat">
               <input type="hidden" name="pick_up_long" id="pick_up_long">
               <input type="hidden" name="drop_off_lat" id="drop_off_lat">
               <input type="hidden" name="drop_off_long" id="drop_off_long">
               <input type="hidden" name="rental_package_json" id="rental_package_json">
               <input type="hidden" name="esti_distance" id="esti_distance">
               <input type="hidden" name="esti_time" id="esti_time">
               <input type="hidden" name="esti_fare" id="esti_fare">

            </div>
         </div>
      </div>
   </div>
   <div class="row my-3 py-3 flex fancy-box" id="ride_now_view">
      <div class="col text-center">
         <div class="radio" data-toggle="modal" data-target="#rentalModal">
            
               <input name="ride_type" type="radio" value="rental">
               <div class="img_flex">                            
                  {!!Html::image('public/webTheme/images/rental-gray.png','',['class' => ''])!!}
               </div>
           
            <h4 class="text-center">Rental</h4>
         </div>
      </div>
      <div class="col text-center">
         <div class="radio">
            <input name="ride_type" type="radio" checked value="auto">
            <div class="img_flex">
               {!!Html::image('public/webTheme/images/auto-gray.png','',['class' => ''])!!}
            </div>
            <h4 class="text-center">Auto</h4>
         </div>
      </div>
   </div>
   <div class="fancy-box my-4 col-sm-12" style="display: none;" id="request_ride_view">
   <div class="row brdr-b">
      <div class="col py-2">
         <div class="media mt-4">
          {!!Html::image('public/webTheme/images/cash.png','',['class' => 'align-self-center mr-3','alt'=>"Generic placeholder image"])!!}
            <!-- <img class="align-self-center mr-3" src="images/cash.png" alt="Generic placeholder image"> -->
            <div class="media-body">
               <h6 class="">
                  <a  class="" data-toggle="modal" data-target="#exampleModal">
                  Cash</a>
               </h6>
               <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                     <div class="modal-content">
                        <div class="modal-header ctm-header">
                           <h2 class="modal-title color-white text-center" id="exampleModalLabel">$10 - $12</h2>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body text-center">
                           <h6><strong>Approx,Travel Time : 15 Mins</strong></h6>
                           <div class="row justify-content-md-center">
                              <div class="col-md-auto text-center mt-3 fancy-box">
                                 <div class="media p-2">
                                  {!!Html::image('public/webTheme/images/location.png','',['class' => 'align-self-center mr-3','alt'=>"Generic placeholder image"])!!}
                                  <!--   <img class="align-self-center mr-3" src="images/location.png" alt="Generic placeholder image"> -->
                                    <div class="media-body ">
                                       <input class="form-control model-input mb-4" id="inputPassword2" placeholder="Pick-up Location" type="text">
                                       <input class="form-control model-input" id="inputPassword2" placeholder="Drop-up Location" type="text">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="modal-footer  justify-content-md-center mt-2 ctm-border">
                              <button type="submit" class="btn theme-btn mb-2">done</button>
                           </div>
                           <p><strong>Note:</strong> This is an Approximate estmate.Actual
                              <br> fares may very slightly based on traffic on discounts.
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col brdr-l py-2">
         <div class="media">
          {!!Html::image('public/webTheme/images/passanger.png','',['class' => 'align-self-center mr-3 ml-2 mt-3','alt'=>"Generic placeholder image"])!!}
            <!-- <img class="align-self-center mr-3 ml-2 mt-3" src="images/passanger.png" alt="Generic placeholder image"> -->
            <div class="media-body">
               <h6 class="passanger-align">Passanger</h6>
               <select class="form-control ctm-dropdown" id="no_of_passenger" name="no_of_passenger">
                 @for($i = 1; $i<=10 ; $i++)
                 <option value="{{$i}}">{{$i}}</option>
                 @endfor
               </select>
            </div>
         </div>
      </div>
   </div>
   <div class="row ">
      <div class="col py-2">
         <div class="media">
             {!!Html::image('public/webTheme/images/fair-estmate.png','',['class' => 'align-self-center mr-3','alt'=>"Generic placeholder image"])!!}
            <!-- <img class="align-self-center mr-3" src="images/fair-estmate.png" alt="Generic placeholder image"> -->
            <div class="media-body">
               <h6 class=""> <a  class="" data-toggle="modal" data-target="#exampleModal1">
                  Fair-Estimate  </a><br><span id="show_esti_fare"></span>
               </h6>
            </div>
         </div>
      </div>
      <div class="col brdr-l py-2">
         <div class="media">
            {!!Html::image('public/webTheme/images/promo-code.png','',['class' => 'align-self-center mr-3','alt'=>"Generic placeholder image"])!!}
           <!--  <img class="align-self-center mr-3" src="images/promo-code.png" alt="Generic placeholder image"> -->
            <div class="media-body">
               <h6 class="mt-2"><a href="javascript::void(0);" data-toggle="modal" data-target="#promocodeModal" style="text-decoration: none;color: black;">Promocode</a></h6>
            </div>
         </div>
      </div>
   </div>
</div>
   {!! Form::close() !!}
   <div class="row">
      <div class="col px-0">
        <button type="submit" class="btn btn-block theme-btn-new" id="ride_now">RIDE NOW</button>
         <button type="submit" class="btn btn-block theme-btn-new" id="confirm_booking" style="display: none;">Confirm Booking</button>
      </div>
   </div>
</div>
</div>
<div class="col-sm-12 col-xs-12 col-md-12 col-lg-8 col-xs-12">
   <div class="row">
      <div id="map" style="width: 1500px;height: 600px;"></div>
   </div>
</div>
</div>
</div>
 <div class="modal fade" id="rentalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
        <div class="modal-content">
           <div class="modal-header">
              <h5 class="modal-title text-center" id="exampleModalLabel"><strong>Select Packages</strong></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
           </div>
           <div class="modal-body">
            
              <span id="packageValue"></span>
              
              <div class="modal-footer ctm-border">
                 <div class="row">
                    <div class="col px-0">
                       <button type="button" class="btn btn-block theme-btn-new" id="get_package">Submit</button>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
  </div>


  <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                     <div class="modal-content">
                        <div class="modal-header ctm-header">
                           <h2 class="modal-title color-white text-center" id="exampleModalLabel">$10 - $12</h2>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">
                           <h6 class="text-center"><strong>Estimated Fare</strong></h6>
                           <div class="row ">
                              <div class="col mx-4 mt-3 fancy-box">
                                 <div class="media p-2">
                                    {!!Html::image('public/webTheme/images/paypal.png','',['class' => 'align-self-center mr-3','alt'=>"Generic placeholder image"])!!}

                                   <!--  <img class="align-self-center mr-3" src="images/paypal.png" alt="Generic placeholder image"> -->
                                    <div class="media-body ml-3 ">
                                       Paypal
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row ">
                              <div class="col mx-4 mt-3 fancy-box">
                                 <div class="media p-2">
                                    {!!Html::image('public/webTheme/images/credit.png','',['class' => 'align-self-center mr-3','alt'=>"Generic placeholder image"])!!}

                                   <!--  <img class="align-self-center mr-3" src="images/credit.png" alt="Generic placeholder image"> -->
                                    <div class="media-body ">
                                       Credit/debit
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row ">
                              <div class="col mx-4 mt-3 fancy-box">
                                 <div class="media p-2">
                                    {!!Html::image('public/webTheme/images/coin.png','',['class' => 'align-self-center mr-3','alt'=>"Generic placeholder image"])!!}
                                    <!-- <img class="align-self-center mr-3" src="images/coin.png" alt="Generic placeholder image"> -->
                                    <div class="media-body ml-3">
                                       Cash
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="modal-footer  justify-content-md-center mt-2 ctm-border">
                              <button type="submit" class="btn theme-btn mb-2">done</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
<!-- Promocode Modal -->
<div id="promocodeModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    {!! Form::open(array('id'=>"promocode_Form", 'name'=>"promocode_Form",'files'=>true,'method' => 'POST','class' => '')) !!}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Promo Code</h4>
      </div>
      <div class="modal-body">
         <input type="text" class="form-control height_mang" id="promocode"  name="promocode" placeholder="Enter Promo Code">
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">cancel</button>
        <button type="submit" class="btn btn-default theme-btn-new">Done</button>
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>
@endsection 
@section('script')
{!! Html::script('public/custom/js/jquery.validate.min.js')!!}
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&language=en&key=AIzaSyD-Da1KcBqqG6I4LTakwmeISnl34V-Ex6E"></script>
<script type="text/javascript">
   
   $("form[name='ride_type_Form']").validate({
   
      rules: {
   
        pickupAddress: {
           required: true,
                     
         },
         dropupAddress: {
          required: function(){
            
            return $('input[name=ride_type]:checked').val() == "auto";
          }

           
         },        
   
       },
      
       messages: {
        
         pickupAddress: {
           required: "Please enter Pick-up address",
         },
         dropupAddress: {
           required: "Please enter Drop-off address",
         },
        
   
       },
   
      submitHandler: function(form) {
        
        var getPackage = $("#rental_package_json").val();
        if(getPackage != ""){

          $("#ride_now").css('display','none');
          $("#confirm_booking").css('display','block');
          $("#request_ride_heading").css('display','block');
          $("#ride_now_view").css('display','none');
          $("#request_ride_view").css('display','block');
          $("#ride_now_heading").css('display','none');
          $("#request_ride_heading").css('display','block');

        }else{

            swal({
                title:'Oops!',
                text:"Please select any one package first.",
                type:'error',
                timer:2000
              });
        }     
        

      }
   });
   
</script>
<script type="text/javascript">
   function initMap() {
   
       var map = new google.maps.Map(document.getElementById('map'), {
           zoom: 8,
           center: {
               lat: 22.8354,
               lng: 75.8069
           }
       });
   
   }
   //pickup address
   google.maps.event.addDomListener(window, 'load', function() {
       var places = new google.maps.places.Autocomplete(document.getElementById('pickupAddress'));
   
       google.maps.event.addListener(places, 'place_changed', function() {
           var place = places.getPlace();
           //console.log(JSON.stringify(place));
           var palceId = place.place_id;
           var getAddress = $("#pickupAddress").val();
           var address = place.formatted_address;
           var latitude = place.geometry.location.lat();
           var longitude = place.geometry.location.lng();
           $("#pick_up_lat").val(latitude);
           $("#pick_up_long").val(longitude);
   
           var mesg = "Address: " + pickupAddress;
           mesg += "\nLatitude: " + latitude;
           mesg += "\nLongitude: " + longitude;
   
       });
   });
   //droup address
   google.maps.event.addDomListener(window, 'load', function() {
       var places = new google.maps.places.Autocomplete(document.getElementById('dropupAddress'));
   
       google.maps.event.addListener(places, 'place_changed', function() {
           var place = places.getPlace();
           //console.log(JSON.stringify(place));
           var palceId = place.place_id;
           var getAddress = $("#dropupAddress").val();
           var address = place.formatted_address;
           var latitude = place.geometry.location.lat();
           var longitude = place.geometry.location.lng();
           $("#drop_off_lat").val(latitude);
           $("#drop_off_long").val(longitude);
   
           var mesg = "Address: " + dropupAddress;
           mesg += "\nLatitude: " + latitude;
           mesg += "\nLongitude: " + longitude;
   
           var pickupAddress = $("#pickupAddress").val();
           var dropupAddress = $("#dropupAddress").val();
           if (pickupAddress != "" && dropupAddress != "") {
   
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
           } else {
   
               alert('Please select picup address');
               $("#dropupAddress").val('');
               var map = new google.maps.Map(document.getElementById('map'), {
                   zoom: 8,
                   center: {
                       lat: 22.8354,
                       lng: 75.8069
                   }
               });
           }
   
       });
   });
   
   function calculateAndDisplayRoute(directionsService, directionsDisplay) {
   
       directionsService.route({
           origin: document.getElementById('pickupAddress').value,
   
           destination: document.getElementById('dropupAddress').value,
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

   //get Package'
   function getRentalPackage(){
   
       $.ajax({

          url:"{{url('api/rental-packages')}}",
          method:'get',
          dataType: 'JSON',
          headers  : {'Authorization': "{{'Bearer '.Session::get('set_token')}}"},
          success:function(res){
             
            var html = ""; 
            if (res.success == true) {
                
                $(res.result).each(function(data,value) {

                    html = html +'<div class="row">'+
                                     '<div class="col mx-4 mt-3 flex-1 fancy-box">'+
                                        '<div class="media p-2">'+
                                           '<div class="img_check">'+
                                              '<input name="package_type" id="'+value.id+'" type="radio" value="'+value.id+'" data-charge = '+value.charges+' data-time= '+value.time+' data-distance = '+value.distance+'>'+
                                              '<div class="img_check_pay"></div>'+
                                           '</div>'+
                                           '<div class="media-body">'+
                                              '<div class ="row">'+
                                                '<div class = "col-sm-6">'+
                                                    '<h6 class="mt-2 ml-2"> '+value.time+' Min '+value.distance+' Km</h6>'+
                                                  '</div>'+
                                                  '<div class = "col-sm-6">'+
                                                    '<h6 class="mt-2 ml-2 float-right"> Rs '+value.charges+'</h6>'+
                                                  '</div>'+
                                              '</div>'+
                                           '</div>'+
                                        '</div>'+
                                     '</div>'+
                                  '</div>';
                });
                 
              $("#packageValue").html(html);
            } else {

                

             }
          }
       });
    }
    getRentalPackage();
   $("#get_package").click(function(){

      var getPackageId = $('input[name=package_type]:checked').val();
      if(getPackageId != undefined){

            var obj = {};
            var time      =  $('input[name=package_type]:checked').attr('data-time');
            var distance  =  $('input[name=package_type]:checked').attr('data-distance');
            var charge    =  $('input[name=package_type]:checked').attr('data-charge');
            var obj       =  {"rental_package_id":getPackageId, "time":time, "distance":distance, "charges":charge};

            var rentalPackageJSON = JSON.stringify(obj);
            $("#rental_package_json").val(rentalPackageJSON);

            $("#esti_fare").val(charge);
            $("#show_esti_fare").html(charge);
            $("#rentalModal").modal('hide');

      }else{

          swal({
                title:'Oops!',
                text:"Please select any one package.",
                type:'error',
                timer:2000
              });
          
      }
      
   })

   //Show ride estimation   
   function show_esti_info(){

      if ($("#pickup_location").val() != "" && $("#dropoff_location").val() != "") {
         
         get_ride_estimation();
         
      }
   }

function get_ride_estimation(){
  
  var directionsService = new google.maps.DirectionsService();

  var request = {
    origin      : $("#pickup_location").val(),
    destination : $("#dropoff_location").val(),
    travelMode  : google.maps.DirectionsTravelMode.DRIVING
  };

  directionsService.route(request, function(response, status) {
    //console.log(response);
    if (status == google.maps.DirectionsStatus.OK ) {
      //the distance in metres && time in minutes

      var distance = parseInt(response.routes[0].legs[0].distance.value)/1000;//KM
      var duration = parseInt(response.routes[0].legs[0].duration.value / 60);//Minutes
      
      $("#esti_time").val('');      
      $("#esti_distance").val('');
      $("#esti_time").val(duration);      
      $("#esti_distance").val(distance);      
                 
    }

  });
  
}
   
</script>
@endsection
