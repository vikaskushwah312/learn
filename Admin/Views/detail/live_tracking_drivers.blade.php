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
  
    <h1>{{@$title}}</h1>
  
  </section>
  
  <section class="content">

    <div class="row">
      
      <div class="col-md-12">
        <span class="pull-right"><i class="fa fa-dot-circle-o" style="color:green"></i> Online <span id="online_drivers">0</span></span>
      </div>

       <div class="col-md-12">         
          <div class="box box-success box-solid">
             <div class="box-header with-border">
                <div class="col-md-8">
                   <h3 class="box-title">{{@$title}}
                   </h3>
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
    
  <script type="text/javascript">
               
    var map;
    
    function initMap() {

      map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 23.3429629, lng: 85.3123681},
          zoom: 10,
          draggable:true,
          scrollwheel: true,
          mapTypeId: google.maps.MapTypeId.ROADMAP
      });

    }
    pos = {
      
      lat: 22.67,
      lng: 75.87
    
    };
    
    var markers = [];
    var ocean_marker = [];
  
    var marker = "";

    function addMarker(location, user_id, driver_name) { 
      
      //console.log(location);            

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

    }
    
    var removeMarker = function(marker1, user_id) {
      
      marker1.setMap(null); 
      delete markers[user_id]; 
    
    };

</script>

<script type="text/javascript">

  var socket = io.connect("{{ Config::get('constants.SOCKET_URL')}}");
    
    function connect_to_socket(userId){
      
      var data = JSON.stringify({"driver_id": userId});

      socket.on('connect', function(){
        
        //socket.emit('driver_login', data);

        console.log("connected");
      
      });       

    }

    connect_to_socket(1);                
          
    socket.on('connected', function(data){
    
      console.log(data);
    
    });


    socket.on('disconnected', function(data){
      
      console.log(data);

      var marker = markers[data.user_id]; 
        
      removeMarker(marker, data.user_id);
                           
    });
      
    function send_drivers_data(userId){

      var driverInfo = {'user_id': userId, pos: pos, 'driver_name': "pankaj gawande"};
      pos.lat = pos.lat + 0.00021;
      pos.lng = pos.lng + 0.00001;
      
      driverInfo = JSON.stringify(driverInfo);

      socket.emit('send_drivers_data', driverInfo, function(data){
           
      });

    }

/*    setInterval(function(){ 
      
      send_drivers_data(1);
    
    }, 500);*/

    socket.on('drivers_data_to_client', function(data){
      
      addMarker(data.pos, data.user_id, data.driver_name);    

    });

    socket.on('show_count', function(data){

      $("#online_drivers").html(data.drivers_count);
    
    });



</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCzFTTDKuOmoRIYEUVLfVwEHAnidtBJghA&callback=initMap"></script>
  
<script src="http://terikon.github.io/marker-animate-unobtrusive/demo/vendor/jquery.easing.1.3.js" ></script>

{!! Html::script('public/adminTheme/plugins/marker_animate/SlidingMarker.min.js') !!}

{!! Html::script('public/adminTheme/plugins/marker_animate/markerAnimate.js') !!}

<script type="text/javascript">  SlidingMarker.initializeGlobally();</script>

@endsection