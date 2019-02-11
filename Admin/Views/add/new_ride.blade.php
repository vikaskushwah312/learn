@extends('Admin::layout.master')
@section('css')
{!! Html::style('public/adminTheme/plugins/select2-4/dist/css/select2.css') !!}
<style type="text/css">
   .select2-selection{
      height:34px!important;
      border-radius:0px!important;
      border-color: #d2d6de!important;
      box-shadow: none!important;
   }
   .fix_width{
      width: 156px;
   }
</style>
@endsection
@section('content')
<div class="content-wrapper">
   <section class="content-header">
      <h1>{{ @$title }}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-success box-solid">
               <div class="box-header with-border">
                  <div class="col-md-8">
                     <h3 class="box-title">{{ @$title }}</h3>
                  </div>
               </div>
               <!-- /.box-header -->
               <!-- form start -->
               {!! Form::open(array('url' => 'admin/post-new-ride','id'=>"new_ride", 'name'=>"new_ride",'files'=>true,'method' => 'POST','class' => '')) !!}
               {!! csrf_field() !!}
               <div class="box-body">
                  <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                           {!! Form::label('pickup_location', 'Pickup Location') !!}&nbsp;<i class="error"><strong>*</strong></i>
                           <input type="text" class="form-control" id="pickup_location" name="pickup_location" placeholder="Pickup Location" onchange="show_esti_info()">
                           <input type="hidden" name="pickup_lat" id="pickup_lat">
                           <input type="hidden" name="pickup_long" id="pickup_long">
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           {!! Form::label('dropoff_location', 'Dropoff Location') !!}&nbsp;<i class="error"><strong>*</strong></i>
                           <input type="text" class="form-control" id="dropoff_location" name="dropoff_location" placeholder="Dropoff Location" onchange="show_esti_info()">
                           <input type="hidden" name="dropoff_lat" id="dropoff_lat">
                           <input type="hidden" name="dropoff_long" id="dropoff_long">
                           <input type="hidden" name="NoOfSeat" id="NoOfSeat" value="">
                        </div>
                     </div>                                         
                  </div>
                  <div class="row">
                     <div class="col-sm-6">
                        <div class="form-group">
                           {!! Form::label('esti_distance', 'Esti. Distance (Km)') !!}&nbsp;<i class="error"><strong>*</strong></i>
                           <input type="text" class="form-control" id="esti_distance" name="esti_distance" placeholder="Esti. Distance (Km)" >
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="form-group">
                           {!! Form::label('esti_time', 'Esti. Time (Min)') !!}&nbsp;<i class="error"><strong>*</strong></i>
                           <input type="text" class="form-control" id="esti_time" name="esti_time" placeholder="Esti. Time (Min)" >
                        </div>
                     </div>                   
                  </div>
                  <div class="row">
                     <div class="col-sm-6">
                        <div class="form-group">
                           {!! Form::label('select_driver', 'Select Driver') !!}&nbsp;<i class="error"><strong>*</strong></i>
                           <select class="js-example-basic-multiple form-control"  id="select_driver" name="select_driver">
                              <option value="">Select Driver</option>
                              @if(count($drivers) > 0)
                              @foreach($drivers as $value)
                                 <option value="{{ $value->id }}">
                                    {{ ucfirst($value->first_name)." ".$value->last_name }} ({{ $value->mobile_number}})
                                 </option>
                              @endforeach 
                              @endif
                           </select>
                        </div>
                     </div>
                     <div class="col-sm-6">
                        <div class="col-sm-8 row">
                           <div class="form-group ">
                              {!! Form::label('select_passenger', 'Select Passenger') !!}&nbsp;<i class="error"><strong>*</strong></i>
                              <select class="js-example-basic-multiple form-control"  id="select_passenger" name="select_passenger">
                                 @if(count($passengers) > 0)
                                    <option value="">Select Passenger</option>
                                       @foreach($passengers as $value)
                                          <option value="{{ $value->id }}">
                                             {{ ucfirst($value->first_name)." ".$value->last_name }} ({{ $value->mobile_number}})
                                          </option>
                                       @endforeach 
                                 @endif
                              </select>
                              <input type="hidden" name="passengetId" id="passengetId">
                           </div>  
                        </div>
                        <div class="col-sm-4">
                           <div class="form-group">
                              <br>
                              <a href="javascript:void(0);" class="btn btn-success" data-toggle="modal" data-target="#addPassengerModal"><i class="fa fa-plus"></i> Add New Passenger</a>
                           </div>
                        </div>
                     </div> 
                  </div>
                   <div class="row">
                     <div class="col-sm-6">
                        <div class="form-group">
                           {!! Form::label('no_of_passenger', 'Number Of Passenger') !!}&nbsp;<i class="error"><strong>*</strong></i>
                           <input type="text" class="form-control" id="no_of_passenger" name="no_of_passenger" placeholder="Number Of Passenger" maxlength="2">
                        </div>
                     </div>                  
                  </div>
                                    
               </div>            
               <div class="box-footer">
                  <button type="submit" class="btn-flat btn btn-success" id="submit_new_ride">Submit</button>
                  <a href="{{url('admin/rides-list')}}" class="btn-flat btn btn-default">Go Back</a>
               </div>
               {!! Form::close() !!}
            </div>
         </div>
      </div>
   </section>
   <section class="content">
      <h4 id="driver_note" style="display: none;">Note: <ul id="note" class="text-danger"></ul></h4>
      <div class="row" id="info_sec" style="display: none;"> 
         <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-success box-solid">
               <div class="box-header with-border">
                  <div class="col-md-8">
                     <h3 class="box-title">Driver Detail</h3>
                  </div>
               </div>
               <div class="box-body">
                  <div id="data_html">
                     <div class="col-md-12">
                        <!-- Profile Image -->
                        <div class="box-body box-profile">

                           <span id="showImage"></span>
                           <p class="text-muted text-center"><span class="badge bg-green" id="online_off_status"></span>
                           </p>
                        </div>
                     </div>
                     <div class="col-md-12 table-responsive" style="height: 250px; overflow: auto;">
                        <div class="">
                           <div class="box-body box-profile">
                              <table id="customer-detail-table" class="table table-bordered table-striped">
                                 <thead>
                                    <tr>
                                       <td class="fix_width"><b>Name</b></td>
                                       <td><span id="first_name"></span>&nbsp;<span id="last_name"></span></td>
                                    </tr>
                                    <tr>
                                       <td class="fix_width"><b>Email</b></td>
                                       <td><span id="email"></span></td>
                                    </tr>
                                    <tr>
                                       <td class="fix_width"><b>Mobile Number</b></td>
                                       <td>+<span id="country_code"></span>-<span id="mobile_number"></span></td>
                                    </tr>
                                    <tr>
                                       <td class="fix_width"><b>Profile Status</b></td>
                                       <td><span id="profile_status"></span></td>
                                    </tr>
                                    <tr>
                                       <td class="fix_width"><b>Address</b></td>
                                       <td><span id="address"></span>,<span id="country_name"></span>,<span id="state_name"></span>,<span id="city_name"></span></td>
                                    </tr>
                                    <tr>
                                       <td class="fix_width"><b>Insurance Number</b></td>
                                       <td><span id="insurance_no"></span></td>
                                    </tr>
                                    <tr>
                                       <td class="fix_width"><b>Insurance Expiry Date</b></td>
                                       <td><span id="insurance_expiry_date"></span></td>
                                    </tr>
                                    <tr>
                                       <td class="fix_width"><b>Number Of Seat</b></td>
                                       <td><span id="no_of_seat"></span></td>
                                    </tr>
                                    <tr>
                                       <td class="fix_width"><b>License Number</b></td>
                                       <td><span id="license_number"></span></td>
                                    </tr>
                                    <tr>
                                       <td class="fix_width"><b>License Expiry Date</b></td>
                                       <td><span id="expiry_date"></span></td>
                                    </tr>
                                 </thead>
                                 <tbody>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div> 
                  </div>         
               </div>
            </div>
         </div>
         <div class="row" id="map_show" style="display: block;"> 
         <!-- left column -->
         <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-success box-solid">
               <div class="box-header with-border">
                  <div class="col-md-8">
                     <h3 class="box-title">Ride Detail</h3>
                  </div>
               </div>
               <div class="box-body">
                  <div id="data_html">
                     <div class="col-md-12">
                       <div id="map" style="width: 100%;height: 482px;"></div>

                     </div>
                     
                  </div>         
               </div>
            </div>
         </div>
      </div>
      </div>
   </section>
  
</div>

<!-- Modal -->
<div class="modal fade" id="addPassengerModal" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Passenger</h4>
         </div>
         {!! Form::open(array('id'=>"addPassengerFormRide", 'name'=>"addPassengerFormRide",'files'=>true,'method' => 'POST','class' => '')) !!}
         <div class="modal-body">
            <div class="col-md-12">
               <div class="row">
                  <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }} col-md-6">
                     {!! Form::label('first_name', 'First Name') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                     {!! Form::text('first_name', '',array('class' => 'form-control','placeholder' =>'First Name','id' => 'first_name','maxlength' => '50')) !!}
                     <p class="controll-error help-block" id="er_first_name">@if ($errors->has('first_name')) <i class="errors"></i>&nbsp;{{ $errors->first('first_name') }}</p>
                     @endif
                  </div>
                  <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }} col-md-6">
                     {!! Form::label('last_name', 'Last Name') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                     {!! Form::text('last_name', '',array('class' => 'form-control','placeholder' =>'Last Name','id' => 'last_name','maxlength' => '50')) !!}
                     <p class="controll-error help-block" id="er_last_name">@if ($errors->has('last_name')) <i class="errors"></i>&nbsp;{{ $errors->first('last_name') }}</p>
                     @endif
                  </div>
               </div>
               <div class="row">
                     <div class="form-group {{ $errors->has('country_code') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('country_code', 'Country Code') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        {!! Form::text('country_code', '91',array('class' => 'form-control','placeholder' =>'Country Code','id' => 'country_code','maxlength' => '4')) !!}
                        <p class="controll-error help-block" id="er_country_code">@if ($errors->has('country_code')) <i class="errors"></i>&nbsp;{{ $errors->first('country_code') }}</p>
                        @endif
                     </div>
                     <div class="form-group {{ $errors->has('mobile_number') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('mobile_number', 'Mobile Number') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        {!! Form::text('mobile_number', '',array('class' => 'form-control','placeholder' =>'Mobile Number','id' => 'mobile_number','maxlength' => '10')) !!}
                        <p class="controll-error help-block" id="er_mobile_number">@if ($errors->has('mobile_number')) <i class="errors"></i>&nbsp;{{ $errors->first('mobile_number') }}</p>
                        @endif
                     </div>
                  </div>
                  <div class="row">
                      <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('email', 'Email') !!}<!-- &nbsp;<i class="error-star"><strong>*</strong></i> -->
                        {!! Form::text('email', '',array('class' => 'form-control','placeholder' =>'Email','id' => 'email')) !!}
                        <p class="controll-error help-block" id="er_email">@if ($errors->has('email')) <i class="errors"></i>&nbsp;{{ $errors->first('email') }}</p>
                        @endif
                     </div>
                       <div class="form-group {{ $errors->has('cutomer_gender') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('gender', 'Gender:',['class' => 'control-label']) !!}<!-- &nbsp;<i class="error"><strong>*</strong></i> -->
                        <select class="form-control" id="cutomer_gender" name="cutomer_gender">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        
                        <p class="controll-error help-block" id="er_cutomer_gender">
                           @if ($errors->has('cutomer_gender'))<i class="errors"></i>{{ $errors->first('cutomer_gender') }}@endif
                        </p>
                     </div>
                  </div>
                  <!-- <div class="row">
                     <div class="form-group {{ $errors->has('cutomer_password') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('cutomer_password', 'Password') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        <input type="password" name="cutomer_password" id="cutomer_password" class="form-control" placeholder="Password"> 
                           <p class="controll-error help-block" id="er_cutomer_password">@if ($errors->has('cutomer_password')) <i class="errors"></i>&nbsp;{{ $errors->first('cutomer_password') }}</p>
                           @endif
                     </div>
                    
                     <div class="form-group {{ $errors->has('profile_image') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('profile_image', 'Profile Image:',['class' => 'control-label']) !!}&nbsp;<i class="error"><strong>*</strong></i>
                        <input name="profile_image" id="profile_image" type="file" />
                        <span class="note-txt">Note : Profile Image Type (jpg, png, jpeg)</span>
                        <p class="controll-error help-block" id="er_profile_image">
                           @if ($errors->has('profile_image'))<i class="errors"></i>{{ $errors->first('profile_image') }}@endif
                        </p>
                     </div>
                  </div><!-- comment -->
            </div>
         </div>
       
         <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="submit_add_pass">Submit</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
         {!! Form::close() !!}
      </div>
   </div>
</div>
@stop

@section('script')

{!! Html::script('public/adminTheme/plugins/select2-4/dist/js/select2.full.js') !!}

<script type="text/javascript">
   
   $(".js-example-basic-multiple").select2();
   
</script>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&language=en&key=AIzaSyD-Da1KcBqqG6I4LTakwmeISnl34V-Ex6E"></script>

<script>

  /* var pickup_location = document.getElementById('pickup_location');
   var autocompletePickup = new google.maps.places.Autocomplete(pickup_location);

   var dropoff_location = document.getElementById('dropoff_location');
   var autocomplete = new google.maps.places.Autocomplete(dropoff_location);*/

   google.maps.event.addDomListener(window, 'load', function () {

      var places = new google.maps.places.Autocomplete(document.getElementById('pickup_location'));
      var DroupOffplaces = new google.maps.places.Autocomplete(document.getElementById('dropoff_location'));
      google.maps.event.addListener(places, 'place_changed', function () {

          var place     = places.getPlace();
          var palceId   = place.place_id;
          var latitude  = place.geometry.location.lat();
          var longitude = place.geometry.location.lng();
          $("#pickup_lat").val(latitude);
          $("#pickup_long").val(longitude);

      });
       google.maps.event.addListener(DroupOffplaces, 'place_changed', function () {
          var place_droupoff  = DroupOffplaces.getPlace();
          var palceId         = place_droupoff.place_id;
          var latitude        = place_droupoff.geometry.location.lat();
          var longitude       = place_droupoff.geometry.location.lng();
          $("#dropoff_lat").val(latitude);
          $("#dropoff_long").val(longitude);
         var pickup_lat    = $("#pickup_lat").val();
         var pickup_long   = $("#pickup_long").val();
         var dropoff_lat   = $("#dropoff_lat").val();
         var dropoff_long  = $("#dropoff_long").val();

      if(pickup_lat !="" && pickup_long !="" && dropoff_lat !="" && dropoff_long !=""){
            
            /*var map = new google.maps.Map(document.getElementById('googleMap'), {
               zoom: 8,
               center: {lat: 22.8354, lng: 75.8069}
            });*/
            initMap();
            
         }   
             
      });
   });   
   //Show ride estimation   
   function show_esti_info(){

      if ($("#pickup_location").val() != "" && $("#dropoff_location").val() != "") {
         
         get_ride_estimation();
         
      }
   }

</script>

<script type="text/javascript">
   
   /*
* Create Date: 19 March 2018
* Created By: Pankaj Gawande
* Purpose: This function is used to get estimated time and distance
*/

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

//change driver show info
$("#select_driver").change(function(){
   
   hide_note();

   $("#NoOfSeat").val('');
   var driverId = $("#select_driver").val();
      /*$("#esti_time").val('');      
      $("#esti_distance").val('');
      $("#pickup_location").val('');      
      $("#pickup_long").val('');
      $("#pickup_lat").val('');
      $("#dropoff_location").val('');
      $("#dropoff_long").val('');
      $("#dropoff_lat").val('');*/
      initMap();

   if(driverId != ""){

      $.ajax({

         url      :"{{url('admin/get-driver-info')}}",
         method   :"GET",
         dataType :"JSON",
         data     :{'driver_id':driverId},
         headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
           success: function(res){
               
                if (res.success == 1) {                     

                     var getData             = res.info;
                     var aasignvehicleInfo   = res.info.assigned_vehicle;
                     var driverInfo          = res.info.driver_info;
                     var licenseInfo         = res.info.driver_license;
                     $("#online_off_status").html(getData.online_status);

                     $("#NoOfSeat").val(aasignvehicleInfo.no_of_seat);
                     
                     var profile_status = 'Inactive';

                     if (driverInfo.status == '1') {

                        var profile_status = 'Active';
                     
                     }

                     $("#profile_status").html(profile_status);                     
                     
                     //Check driver is available

                     if (getData.online_status == "Offline") {

                        show_note('The driver is offline now');
                     
                     }
                     if(driverInfo.status == '0'){

                        show_note('The driver status is inactive now.');
                     
                     }
                     if(licenseInfo.license_number == ""){

                        show_note('The driver`s license info not available.');
                     }

                     if(driverInfo != ""){

                        if(driverInfo.profile_image != ""){

                           var path = "{{url(Config::get('constants.PROFILE_IMAGE'))}}/"+driverInfo.profile_image;
                           $("#showImage").html('<img src="'+path+'" alt="" style = "height:auto;width:150px;" class = "profile-user-img img-responsive img-square">');

                        }else{

                           var path = "{{url(Config::get('constants.NO_IMAGE'))}}/"+'user-no-image.png';
                           $("#showImage").html('<img src="'+path+'" alt="" style = "height:auto;width:150px;" class = "profile-user-img img-responsive img-square">');
                        }
                        $.each(driverInfo, function( index, value ) {

                           if(value  != ""){

                              $("#"+index).html(value);
                           }else{

                              $("#"+index).html('N/A');
                           }
                        });    
                     }
                     if(aasignvehicleInfo != ""){

                        $.each(aasignvehicleInfo, function( index, value ) {

                           if(value  != ""){

                              $("#"+index).html(value);
                           }else{

                              $("#"+index).html('N/A');
                           }
                        });
                     }
                     if(licenseInfo != ""){

                        $.each(licenseInfo, function( index, value ) {

                           if(value  != ""){

                              $("#"+index).html(value);
                           }else{

                              $("#"+index).html('N/A');
                           }
                        });
                     }


                  var myLatLng = {lat: parseFloat(driverInfo.latitude), lng: parseFloat(driverInfo.longitude)};
                  
                  console.log(myLatLng);

                  var marker = new google.maps.Marker({
                     position: myLatLng,
                     map: map,
                     title: driverInfo.first_name+' '+driverInfo.last_name
                  });                 
                  console.log(marker);
                }else{

                 
               }
         }
      })
      $("#info_sec").css('display','block');      
   }else{

      /*$("#esti_time").html('');      
      $("#esti_distance").html('');
      $("#pickup_location").html('');      
      $("#pickup_long").html('');
      $("#pickup_lat").html('');
      $("#dropoff_location").html('');
      $("#dropoff_long").html('');
      $("#dropoff_lat").html('');
      $("#info_sec").css('display','none');*/
   }
})
   var map = "";

   function initMap() {

      var directionsService = new google.maps.DirectionsService;
      var directionsDisplay = new google.maps.DirectionsRenderer;
      map = new google.maps.Map(document.getElementById('map'), {
      zoom: 8,
      center: {lat: 22.8354, lng: 75.8069}
      });
      directionsDisplay.setMap(map);

      calculateAndDisplayRoute(directionsService, directionsDisplay);
   }

   function calculateAndDisplayRoute(directionsService, directionsDisplay) {


      directionsService.route({
         origin: document.getElementById('pickup_location').value,
         destination: document.getElementById('dropoff_location').value,
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

//close modal
$('#addPassengerModal').on('hidden.bs.modal', function () {
    $(".error").html('');
});

function show_note(msg){

   $("#submit_new_ride").prop('disabled', true);
   $("#driver_note").css('display','block');
   $("#note").append('<li><i>'+msg+'</i></li>');
}

function hide_note(){

   $("#note").html('');
   $("#submit_new_ride").prop('disabled', false);
   $("#driver_note").css('display','none');

}

</script>
@stop