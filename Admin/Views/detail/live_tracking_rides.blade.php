@extends('Admin::layout.master')
  
@section('css')
  
  <style type="text/css">
    #map {
      height: 500px;      
    }
  </style>

@endsection

@section('content')
<div class="content-wrapper" style="min-height: 508px;">
   
  <section class="content-header">
    <div class="row">
      <div class="col-md-8" style="text-align: left;">
        <h3>{{@$title}}</h3>
      </div>
      <div class="col-md-4" style="text-align: right; margin-top: 15px;">
        <select id="select_driver" class="form-control">
          
        </select>
        <input type="hidden" id="select_driver_id" name="select_driver_id">
      </div> 
    </div>  
  </section>
  
  <section class="content">

    <div class="row">

       <div class="col-md-12">         
          <div class="box box-success box-solid">
             <div class="box-header with-border">
                <div class="col-md-8">
                   <h3 class="box-title">{{@$title}}</h3>
                </div>
             </div>
             
              <div class="box-body">
                             
                <div id="map"></div>
                  
              </div>
             </div>
             
          </div>
       </div>

    </div>

 </section>

</div>

@stop

@section('script')
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
  
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCzFTTDKuOmoRIYEUVLfVwEHAnidtBJghA&callback=initMap" async defer></script>

<script>
 
    var map;
    var markers = [];
    var ocean_marker = [];
    var ldriverId = "";
    var marker = "";

    function initMap(){

      map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 23.3429629, lng: 85.3123681},
          zoom: 10,
          draggable:true,
          scrollwheel: true,
          mapTypeId: google.maps.MapTypeId.ROADMAP
      });

    }
  
  function draw_rides(data){
    
    var directionsService = new google.maps.DirectionsService;
    
    var directionsDisplay = new google.maps.DirectionsRenderer({
    
      draggable: true,
      map: map,
      panel: document.getElementById('right-panel')
    
    });    
    
    displayRoute(directionsService,
      directionsDisplay, data);
  }

  function displayRoute(service, display, data) {
    
    var origin    = data.pickup_address;
    var destination = data.dropoff_address;

    origin        = new google.maps.LatLng(parseFloat(data.pickup_lat), parseFloat(data.pickup_long));
    destination   = new google.maps.LatLng(parseFloat(data.dropoff_lat), parseFloat(data.dropoff_long));

    var driver_lat_long  = new google.maps.LatLng(parseFloat(data.driver_lat), parseFloat(data.driver_long));

    var waypts = [];
    waypts.push({
        location: new google.maps.LatLng(parseFloat(data.driver_lat), parseFloat(data.driver_long)),
        stopover: false
    }); 
    console.log(data);

    service.route({
      origin: origin,
      destination: destination,
      //waypoints: [{location: driver_lat_long}],
      travelMode: 'DRIVING',
      optimizeWaypoints: false,
      avoidTolls: true
    
    },function(response, status) {
      
      console.log(response);

      if (status === 'OK') {
        
        display.setDirections(response);
    
      }else {
        
        console.log('Could not display directions due to: ' + status);
      
      }

    });
  }

    var markers = [];
    var ocean_marker = [];
  
    var marker = "";

    function addMarker(location, user_id, driver_name) { 
      
      console.log(user_id);            

      var checkExists = markers.hasOwnProperty(user_id);      
      
      if (checkExists) {

        ocean_marker[user_id].setPosition(location);
      
      }else{

        ocean_marker[user_id] = new google.maps.Marker({
            position: location,
            map: map,                      
            id: user_id,
            duration: 3000,
            icon: SITEURL+'public/uploads/admin/vehicle_location.png',
        });      

        var infowindow = new google.maps.InfoWindow()

        google.maps.event.addListener(ocean_marker[user_id],'click', (function(marker,driver_name,infowindow){ 
            return function() {
                infowindow.setContent(driver_name);
                infowindow.open(map,ocean_marker[user_id]);
            };
        })(ocean_marker[user_id],driver_name,infowindow)); 

        markers[user_id] = ocean_marker[user_id];
       // markers.push(marker);

      }

      console.log(ocean_marker);

    }
    
 

</script>

<script type="text/javascript">

  var socket = io.connect("{{ Config::get('constants.SOCKET_URL')}}");
      
    function connect_to_socket(userId){

      socket.on('connect', function(){
      
        console.log("connected");
      
      });       

    }

    connect_to_socket(1);                
          
    socket.on('connected', function(data){
    
      console.log(data);
    
    });


    socket.on('disconnected', function(data){
      
      //var marker = markers[data.user_id]; 
        
      //removeMarker(marker, user_id);                  
    });

    var ride_info = {

      "driver_id":"1",
      "pickup_lat":"22.123",
      "pickup_long":"75.789",
      "dropoff_lat":"22.1553",
      "dropoff_long":"75.78444449",
      "driver_lat":"22.454453",
      "driver_long":"75.8898",
      "pickup_address": "Indore Junction, Chhoti Gwaltoli, Indore, Madhya Pradesh, India",
      "dropoff_address": "Bhopal Jn., East Railway Colony, Bhopal, Madhya Pradesh, India",
    
    };
    
    socket.emit('get_rides_req_to_server');

/*    socket.on('get_rides_res_to_client', function(data){
      
      socket.emit('send_rides_to_server', ride_info);

    });*/

    /*socket.on('send_rides_to_client', function(data){

      var data = {
        "pickup_address": "Bhawarkua Main Road, Bhanwar Kuwa, Indore, Madhya Pradesh, India",
        "dropoff_address": "Palasia ibus stop, Agra Bombay Road, New Palasia, Indore, Madhya Pradesh, India",
        "dropoff_long": "75.88695140000004",
        "dropoff_lat": "22.7248948",
        "pickup_lat": "22.6925663",
        "pickup_long": "75.86757579999994",
        "driver_id": 11,
        "driver_lat": "22.6962272",
        "driver_long": "75.86538",        
      };

      var data = JSON.parse(data);

      draw_rides(data);

    });*/

    /* For Driver */
    socket.on('drivers_data_to_client', function(data){
      
      if ($("#select_driver_id").val() == data.user_id) {
      
        addMarker(data.pos, data.user_id, data.driver_name);
        
      }

    });

    
</script>
<script type="text/javascript">
  //running rides
  function runingRides(){
    $.ajax({
      url: "{{url('admin/get-running-rides')}}",
      method:'GET',
      dataType: 'JSON',
      headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      success: function(res){
        if(res.success == 1){

          var getData = res.data;
          $("#select_driver").html('');
          $("#select_driver").append('<option>Select Driver</option>');
          $.each(getData, function( index, value ) {
            
              $("#select_driver").append('<option value ="'+value.id+'" data-driver-id = "'+value.driver_id+'" data-driver-lat= "'+value.driver_lat+'" data-driver-long = "'+value.driver_long+'" data-pickup-address= "'+value.pickup_address+'" data-dropoff-address = "'+value.dropoff_address+'" data-pickup-lat = "'+value.pickup_lat+'" data-pickup-long = "'+value.pickup_long+'" data-dropoff-lat = "'+value.dropoff_lat+'" data-dropoff-long = "'+value.dropoff_long+'" data-driver-name = "'+value.first_name+' '+value.last_name+'">'+value.first_name+' '+value.last_name+' '+'(Ride-Id '+value.id+')</option>');
          });
          
        }else{
          $("#select_driver").html('');
          $("#select_driver").append('<option>'+res.message+'</option');
        }
      }
    });
  }
  runingRides();
  //setInterval(function () { runingRides(); }, 60000);
</script>

<script type="text/javascript">
  //filter by ride
  $("#select_driver").change(function(){
    
    var markers = [];
    
    var ocean_marker = [];     
    
    var filterData = {
        "pickup_address":   $('option:selected', this).attr('data-pickup-address'),
        "dropoff_address":  $('option:selected', this).attr('data-dropoff-address'),
        "dropoff_long":     $('option:selected', this).attr('data-dropoff-long'),
        "dropoff_lat":      $('option:selected', this).attr('data-dropoff-lat'),
        "pickup_lat":       $('option:selected', this).attr('data-pickup-lat'),
        "pickup_long":      $('option:selected', this).attr('data-pickup-long'),
        "driver_id":        $('option:selected', this).attr('data-driver-id'),
        "driver_lat":       $('option:selected', this).attr('data-driver-lat'),
        "driver_long":      $('option:selected', this).attr('data-driver-long')        
      };
      
      $("#select_driver_id").val($('option:selected', this).attr('data-driver-id'));
      draw_rides(filterData);
  });

</script>
@endsection