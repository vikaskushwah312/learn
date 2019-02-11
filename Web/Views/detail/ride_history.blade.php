@extends('Web::layout.master_inner')
@section('css')
  {!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}  
@endsection
@section('content')

      <div class="container-fluid bg-light">
         <div class="row">
            <div class="container">
               <div class="row my-4">
                  <div class="col">
                     <h3 class="mt-2"><strong>Ride History</strong></h3>
                  </div>
                  <div class="col">
                     <h3 class="float-right"><button type="submit" class="btn  theme-btn-new"><strong>Filter By Date</strong> <img src="{{url('public/webTheme/images/filter.png')}}" class="ml-3"></button></h3>
                  </div>
               </div>
               <div>
               <div class="row mb-4" id="element">
             </div>

               <div id="no_recored"></div>

               <div class="col-sm-12 text-center" id="load_more_div">
                     <a href="javascript:void(0)" type="button" class="btn theme_btn" id="load_more_btn" onclick="load_more()" style="color:white;background-color:#a5c314;margin-bottom:20px;">Load More</a>
               </div>          
                
            </div>
         </div>
      </div>
      </div>
@endsection

@section('script')
<script type="text/javascript" src="http://www.datejs.com/build/date.js"></script> 
<script type="text/javascript">
 var page = 1;
function rideHistory(){
   
   $.ajax({

      url:"{{url('api/get-rides')}}",
      method:'get',
      data:{'page':page},
      dataType: 'JSON',
      headers  : {'Authorization': "{{'Bearer '.Session::get('set_token')}}"},


      success:function(res){

         var html = "";
         
         if (res.code == 200) {
            
            //console.log(res);


               for (var i = 0; i < res.result.length; i++) {
                  //console.log(res.result[i].ride_status);
                  //alert(res.result[i].total);
                  var profile_image;

                  profile_image = res.result[i].driver.profile_image;
                  
                  if(profile_image != null){

                     profile_image = SITEURL + 'public/uploads/profile/'+res.result[i].driver.profile_image;

                  } else{

                     profile_image = SITEURL + 'public/uploads/no-image.png'
                  }

                  var cone_image = SITEURL + 'public/webTheme/images/coin.png';

                  //Vehicle Name
                  var vehicle_name;
                  if(res.result[i].vehicle_name != null){

                     vehicle_name = res.result[i].vehicle_name;

                  } else {

                     vehicle_name = "";
                  }

                  if(res.result[i].ride_status == 'Cancelled'){
                        var color  = '#F31011';
                  }else{
                        var color  = '#3f9e00';
                  }
                  // For Date $ time

                  var date,parse_date,dd,mm,yy,time;

                  date = res.result[i].end_ride_date;

                  parse_date = Date.parse(date);


                  dd = (parse_date.getDate() < 10 ? '0' : '') + (parse_date.getDate());
                  
                  mm = (parse_date.getMonth() < 9 ? '0' :'') +  (parse_date.getMonth()+1);
                  //mm = mm < 10 ? '0' + mm : mm;
                  yy = parse_date.getFullYear();

                  //hh = parse_date.getHours();
                  //min = parse_date.getMinutes 

                  //alert(mm);

                  
                  html = html + 
                     '<div class="col-sm-6">'+
                     '<a href= "{{url("/ride-detail")}}/'+res.result[i].id+'" style="text-decoration:none;">'+
                     '<div class="fancy-box">'+
                     	'<div class="row p-3">'+
                        	'<div class="col-sm-6 mt4">'+
                           		'<div class="media">'+
                              		'<div class="quote">'+
                                 		'<img src="'+ profile_image +'" class="mt-4 ml-2">'+
                              		'</div>'+
                              		'<div class="media-body  ml-3 mt-4">'+
                                 		'<h5 class="mt-0 mb-0">'+res.result[i].driver.first_name+' '+res.result[i].driver.last_name+'</h5>'+
                                 		'<p class="mb-0">'+'Ride Id :'+' '+res.result[i].id+'</p>'+
                                 		'<p class="mb-0">'+'Ridy Type :'+' '+res.result[i].ride_type+'</p>'+
                                 		'<p class="mb-0">'+vehicle_name+'</p>'+
                                 		'<div class="media">'+
                                    		'<img src="'+cone_image+'">'+
                                    		'<div class="media-body">'+
                                       		'<p class="ml-2">'+'{{Config::get('constants.CURRENCY')}}'+' '+res.result[i].fare+'(cash)'+'</p>'+
                                    	'</div>'+
                                 	'</div>'+
                              	'</div>'+
                           	'</div>'+
                        '</div>'+

						
						
						
						
						
                        '<div class="col-sm-6 mt-4 float-right">'+
                           '<div class="float-right">'+ 
                              '<p class="mb-0" style="color:'+color+'"><b>'+res.result[i].ride_status+'</b></p>'+dd+'-'+mm+'-'+yy+'<br>'+time+
                        	'</div>'+
                        '</div>'+
                     '</div>'+  
					                    
                     '<div class="col">'+
                     '<hr class="my-0">'+
                     '</div>'+    
					 
					                 
                     '<div class="row p-3">'+
                        '<div class="col-sm-12">'+
                           '<div class="media p-2">'+
                              '<img class="align-self-center mr-3" src="{{url('public/webTheme/images/location.png')}}" alt="Generic placeholder image">'+
                              '<div class="media-body">'+
                                 '<input class="form-control model-input mb-4" id="inputPassword2" placeholder="Pickup Location"  value="'+res.result[i].pickup_location+'" type="text">'+
                                 '<input class="form-control model-input" id="inputPassword2" placeholder="Dropoff Location" value="'+res.result[i].dropoff_location+'" type="text">'+
								 '</div>'+
                              '</div>'+
                           '</div>'+
                        '</div>'+
                     '</div>'+
                  '</a>'+
                  '</div>';
				  
               

                 }

               var noOfRecords = 10;


                     if (page == 1) {

                        $("#element").html(html);
                         
                        if (res.total < page * noOfRecords ) {

                        $("#load_more_div").hide();

                        }
                        page = page + 1;

                     } else{

                        $("#element").append(html);

                        page = page + 1;

                        if (res.total < page * noOfRecords) {

                           $("#load_more_div").hide();

                        }
                     }

                        
          
         } else {

            //if no recored found
            $("#load_more_div").hide();
            $("#no_recored").html('<h4 style="text-align:center;">'+res.msg+'</h4>');

         }
      }
   });

}
rideHistory();

</script>
<script type="text/javascript">
   function load_more(){
      rideHistory();
   }
</script>
@endsection