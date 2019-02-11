 @extends('Web::layout.master')
@section('title')
  Home     
@endsection

@section('content') 




   
 

  <section id="home" class="green-bg" data-type="background" data-speed="3">
   <div class="container">
      <div class="row justify-content-md-center">
         <div class="col-md-auto mt-20">
            <div class="media">
                {!!Html::image('public/webTheme/images/mobile.png','',['class' => 'mr-3 mobile-img','alt'=>"Generic placeholder image"])!!}
               <div class="media-body">
                  <h1 class="mt-0 res-size">CALL US<span class="color-white"> 24 HOURS</span></h1>
                  <h1 class="mt-0 float-left"><b>452-2-452</b></h1>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<section id="" class="white-bg" data-type="background" data-speed="3">
   <div class="container">
      <div class="row justify-content-md-center">
         <div class="col-md-auto text-center">
            <h3 class=""><b>BOOK TAXI NOW</b></h3>
            <hr class="grn-hr">


  {!! Form::open(array('id'=>"submit_form", 'name'=>"submit_form",'files'=>true,'method' => 'POST','class' => '')) !!}

                    <div class="row">
                        <div class="col-6">
                           <div class="form-group hvr-red">
                                <input type="text" class="form-control height_mang" name="pickup" id="pickUpAddress" placeholder="Pick-up Location">
                              <p class="error help-block"></p>
                           </div>
                        </div>
                        <div class="col-6">
                           <div class="form-group hvr-red">
                               <input type="text"  class="form-control height_mang" name="dropoff" id="dropOffAddress" placeholder="Drop-up Location">
                              <p class="error help-block"></p>
                           </div>
                        </div>
                     </div>
                      <input type="hidden" name="pick_up_lat" id="pick_up_lat">
                      <input type="hidden" name="pick_up_long" id="pick_up_long">
                      <input type="hidden" name="drop_off_lat" id="drop_off_lat">
                      <input type="hidden" name="drop_off_long" id="drop_off_long">   

                  
        <div class="container">
            <div class="row justify-content-md-center">
               <div class="col-md-auto text-center">
                
                     <button type="submit" id="book_now"  class="btn theme-btn mb-2 ml-10">BOOK NOW</button>
               
               </div>
            </div>
         </div>

         </div>
        


      </div>
</section>
<section id="services" class="" data-type="background" data-speed="3">
   <div class="row justify-content-md-center">
      <div class="col-md-auto text-center">
         <h3 class=""><b>OUR SERVICES</b></h3>
         <hr class="grn-hr">
      </div>
   </div>
   <div class="container">
      <div class="row mt-12">
         <div class="col-sm-12 col-md-4 col-lg-4 img-hldr">
            <h4> Inclusive Rates </h4>
            <div class="trans-img">
             {!!Html::image('public/webTheme/images/rate.png','',['class' => 'img-services'])!!}
             </div>
            <p>Lort amet, conles ac. Morbi a elit tortor. Praesent
               accumsan turpis a
               vestibulum pellentesque.
            </p>
         </div>
         <div class="col-sm-12 col-md-4 col-lg-4 img-hldr">
            <h4> Easy Searching </h4>
            <div class="trans-img">
            {!!Html::image('public/webTheme/images/auto-1.png','',['class' => 'img-services'])!!}
            </div>
            <p>Lort amet, conles ac. Morbi a elit tortor. Praesent
               accumsan turpis a
               vestibulum pellentesque.
            </p>
         </div>
         <div class="col-sm-12 col-md-4 col-lg-4 img-hldr">
            <h4> Rental </h4>
            <div class="trans-img">
            {!!Html::image('public/webTheme/images/rent.png','',['class' => 'img-services'])!!}
            </div>
            <p>Lort amet, conles ac. Morbi a elit tortor. Praesent
               accumsan turpis a
               vestibulum pellentesque.
            </p>
         </div>
      </div>
   </div>
   </div>
</section>
<section id ="about"class="masthead mysec bg2 image">
   <div class="container">
      <div class="row justify-content-md-center">
         <div class="col-md-auto text-center">
            <h3 class=""><b>ABOUT US</b></h3>
            <hr class="grn-hr">
         </div>
      </div>
   </div>
   </div>
   <div class="container">
      <div class="row mt-12">
         <div class="col-sm-6">
            <h5 class="color-green"> Lorem ipsum dolor sit amet </h5>
            <p class="text-justify  mt-12">Lort amet, conles ac. Morbi a elit tortor. Praesent
               accumsan turpis a
               vestibulum pellentesque.<br><br>
               Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi maximus ac eros et cursus. 
               Sed ultrices magna sed efficitur sagittis. 
               Nunc blandit nibh et laoreet malesuada. 
               Pellentesque ac nisl orci. <br><br>Sed vitae ligula lectus. Vivamus leo metus,
               lacinia sollicitudin metus vehicula, luctus malesuada est. Pellentesque vel ligula at mauris dapibus gravida.<br><br> 
               Aliquam placerat lorem vitae mi efficitur,
               sed eleifend ligula convallis.<br><br> Maecenas tempor dignissim leo, vitae lacinia mauris .
            </p>
         </div>
         <div class="col-sm-6">
            {!!Html::image('public/webTheme/images/round-auto.png','',['class' => 'rnd-auto'])!!}
         </div>
      </div>
   </div>
</section>
<section id="download" class="masthead mysec bg3 image">
   <div class="container">
      <div class="row justify-content-md-center">
         <div class="col-md-auto text-center">
            <h3 class="color-white"><b>DOWNLOAD THE APP</b></h3>
            <hr class="grn-hr">
         </div>
      </div>
   </div>
   </div>
   <div class="container">
      <div class="row mt-12">
         <div class="col-sm-12 col-md-12 col-lg-4">
            <div class="col-sm-12 mb-40">
               <div class="media">
                  {!!Html::image('public/webTheme/images/one.png','',['class' => 'mr-3','alt'=>"Generic placeholder image"])!!}
                  <div class="media-body">
                     <h5 class="color-green"><b>LOREUM IPSUM</b></h5>
                     <p class="color-white"> Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                  </div>
               </div>
            </div>
           
            <div class="col-sm-12">
               <div class="media">
                  {!!Html::image('public/webTheme/images/two.png','',['class' => 'mr-3','alt'=>"Generic placeholder image"])!!}
                  <div class="media-body">
                     <h5 class="color-green"><b>LOREUM IPSUM</b></h5>
                     <p class="color-white"> Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                  </div>
               </div>
            </div>
         </div>
         <div class="col-sm-12  d-block d-sm-none">
                  <div class="col-sm-12">
                     <div class="media">
                        {!!Html::image('public/webTheme/images/two.png','',['class' => 'mr-3','alt'=>"Generic placeholder image"])!!}
                        <div class="media-body">
                           <h5 class="color-green"><b>LOREUM IPSUM</b></h5>
                           <p class="color-white"> Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-sm-12">
                     <div class="media">
                       {!!Html::image('public/webTheme/images/two.png','',['class' => 'mr-3','alt'=>"Generic placeholder image"])!!}
                        <div class="media-body">
                           <h5 class="color-green"><b>LOREUM IPSUM</b></h5>
                           <p class="color-white"> Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
                        </div>
                     </div>
                  </div>
               </div>
         <div class="col-sm-12 col-md-12 col-lg-4 d-none d-md-none d-md-none d-lg-block text-center">
            {!!Html::image('public/webTheme/images/download.png','',['class' => 'download'])!!}
         </div>
         <div class="col-sm-12 col-md-12 col-lg-4 d-none d-sm-block">
            <div class="col-sm-12 mb-40">
               <div class="media">
                  <div class="media-body">
                     <h5 class="color-green text-right"><b>LOREUM IPSUM</b></h5>
                     <p class="color-white text-right"> Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                  </div>
                    {!!Html::image('public/webTheme/images/three.png','',['class' => 'mr-3 ml-3','alt'=>"Generic placeholder image"])!!}
               </div>
            </div>
           
            <div class="col-sm-12">
               <div class="media">
                  <div class="media-body">
                     <h5 class="color-green text-right"><b>LOREUM IPSUM</b></h5>
                     <p class="color-white text-right"> Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                  </div>
                    {!!Html::image('public/webTheme/images/four.png','',['class' => 'mr-3  ml-3','alt'=>"Generic placeholder image"])!!}
               </div>
            </div>
         </div>
         
         <div class="col-sm-12  d-block d-sm-none d-none d-sm-block d-md-none  text-center">
                  {!!Html::image('public/webTheme/images/download.png','',['class' => 'download'])!!}
               </div>
               <div class="col-sm-12 d-none d-md-block d-lg-none text-center">
                  {!!Html::image('public/webTheme/images/download.png','',['class' => 'download'])!!}
               </div>
      </div>
   </div>
</section>
<div class="container-fluid">
   <div class="row">
      <div class="map-responsive">
         <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA0s1a7phLN0iaD6-UE7m4qP-z21pH0eSc&q=Eiffel+Tower+Paris+France" width="1500" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
      </div>
   </div>
</div>
<section id="" class="bg-color" data-type="background" data-speed="3">
   <div class="row justify-content-md-center">
      <div class="col-md-auto text-center">
         <h3 class=""><b>TESTIMONIAL</b></h3>
         <hr class="grn-hr">
      </div>
   </div>
   <div class="container">
   <div class="row">
   
   <div id="slider1"class="owl-carousel owl-theme">
  
   <div class="item">
            <div class="img-hldr">

    <div class="test-section">
                     <p class="mb-0">Lort amet, conles ac. Morbi a elit tortor. Praesent
                        accumsan turpis a
                        vestibulum pellentesque.
                     </p>
                     <div class="testimonial-quote">
                     {!!Html::image('public/webTheme/images/testimonial.png','',['class' => 'mt-4'])!!}
                     </div>
                     <h6 class="my-4"><b>Mary Jhon</b></h6>
                  </div>
                  <div class="quote">
                {!!Html::image('public/webTheme/images/pic.png','',['class' => 'quote_img_'])!!}
            </div>
    </div>
    </div>
    
    
    <div class="item"><div class="img-hldr">

    <div class="test-section">
                     <p class="mb-0">Lort amet, conles ac. Morbi a elit tortor. Praesent
                        accumsan turpis a
                        vestibulum pellentesque.
                     </p>
                     <div class="testimonial-quote">
                     {!!Html::image('public/webTheme/images/testimonial.png','',['class' => 'mt-4'])!!}
                     </div>
                     <h6 class="my-4"><b>Mary Jhon</b></h6>
                  </div>
                  <div class="quote">
                {!!Html::image('public/webTheme/images/pic.png','',['class' => 'quote_img_'])!!}
            </div>
    </div></div>
    <div class="item"><div class="img-hldr">

    <div class="test-section">
                     <p class="mb-0">Lort amet, conles ac. Morbi a elit tortor. Praesent
                        accumsan turpis a
                        vestibulum pellentesque.
                     </p>
                     <div class="testimonial-quote">
                     {!!Html::image('public/webTheme/images/testimonial.png','',['class' => 'mt-4'])!!}
                     </div>
                     <h6 class="my-4"><b>Mary Jhon</b></h6>
                  </div>
                  <div class="quote">
                {!!Html::image('public/webTheme/images/pic.png','',['class' => 'quote_img_'])!!}
            </div>
    </div></div>
  
    
     </div>
     </div>
    
   
  <!-- <div class="container">
      <div class="row mt-12">
         <div class="col-sm-12 col-md-4 col-lg-4 img-hldr">
            <div class="test-section">
               <p>Lort amet, conles ac. Morbi a elit tortor. Praesent
                  accumsan turpis a
                  vestibulum pellentesque.
               </p>
                {!!Html::image('public/webTheme/images/testimonial.png','',['class' => 'img-test'])!!}
               <h6 class="quote-img"><b>Mary Jhon</b></h6>
            </div>
            <div class="quote">
                {!!Html::image('public/webTheme/images/pic.png','',['class' => ''])!!}
            </div>
         </div>
         <div class="col-sm-12 col-md-4 col-lg-4 img-hldr">
            <div class="test-section">
               <p>Lort amet, conles ac. Morbi a elit tortor. Praesent
                  accumsan turpis a
                  vestibulum pellentesque.
               </p>
               {!!Html::image('public/webTheme/images/testimonial.png','',['class' => 'img-test'])!!}
               <h6 class="quote-img"><b>Mary Jhon</b></h6>
            </div>
            <div class="quote">
                {!!Html::image('public/webTheme/images/pic.png','',['class' => ''])!!}
            </div>
         </div>
         <div class="col-sm-12 col-md-4 col-lg-4 img-hldr">
            <div class="test-section">
               <p>Lort amet, conles ac. Morbi a elit tortor. Praesent
                  accumsan turpis a
                  vestibulum pellentesque.
               </p>
               {!!Html::image('public/webTheme/images/testimonial.png','',['class' => 'img-test'])!!}
               <h6 class="quote-img"><b>Mary Jhon</b></h6>
            </div>
            <div class="quote">
                {!!Html::image('public/webTheme/images/pic.png','',['class' => ''])!!}
            </div>
         </div>
      </div>
   </div>-->
   </div>
   </div>
</section>
@endsection
@section('script')
{!! Html::script('public/custom/js/jquery.validate.min.js')!!}

<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&language=en&key=AIzaSyD-Da1KcBqqG6I4LTakwmeISnl34V-Ex6E"></script>

<script type="text/javascript">
         
  $("form[name='submit_form']").validate({
    
    rules: {

      pickup: {
        required: true,
        
      },
      dropoff: {
        required: true,
       
      }
    },
   
    messages: {
     
      pickup: {
        required: "Please enter Pick-Up Address",
       
      },
      dropoff: {
        required: "Please enter Drop-Off Address",
        
      },
    },
  
  
});






$("#book_now").click(function(){ 

    
 if ($("#pickUpAddress").val() != "" && $("#dropOffAddress").val() != "") {


var pick_lat = $pick_up_lat;
           

                      window.location.href = "{{url('/ride-now')}} +pick_lat";
                    
    
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
       var places = new google.maps.places.Autocomplete(document.getElementById('pickUpAddress'));
   
       google.maps.event.addListener(places, 'place_changed', function() {
           var place = places.getPlace();
          
           var palceId = place.place_id;
           var getAddress = $("#pickUpAddress").val();
           var address = place.formatted_address;
           var latitude = place.geometry.location.lat();
           var longitude = place.geometry.location.lng();
           $("#pick_up_lat").val(latitude);
           $("#pick_up_long").val(longitude);
   
           var mesg = "Address: " + pickUpAddress;
           mesg += "\nLatitude: " + latitude;
           mesg += "\nLongitude: " + longitude;
   
       });
   });
   //droup address
   google.maps.event.addDomListener(window, 'load', function() {
       var places = new google.maps.places.Autocomplete(document.getElementById('dropOffAddress'));
   
       google.maps.event.addListener(places, 'place_changed', function() {
           var place = places.getPlace();
           //console.log(JSON.stringify(place));
           var palceId = place.place_id;
           var getAddress = $("#dropOffAddress").val();
           var address = place.formatted_address;
           var latitude = place.geometry.location.lat();
           var longitude = place.geometry.location.lng();
           $("#drop_off_lat").val(latitude);
           $("#drop_off_long").val(longitude);
   
           var mesg = "Address: " + dropOffAddress;
           mesg += "\nLatitude: " + latitude;
           mesg += "\nLongitude: " + longitude;
   
           var pickUpAddress = $("#pickUpAddress").val();
           var dropOffAddress = $("#dropOffAddress").val();
           if (pickUpAddress != "" && dropOffAddress != "") {
   
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
               $("#dropOffAddress").val('');
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
   </script>
@endsection

    