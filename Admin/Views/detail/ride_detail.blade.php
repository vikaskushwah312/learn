@extends('Admin::layout.master')
@section('css')
<style type="text/css">
   .bg-success{
   background-color:#c1e8d6!important;
   }
   .nav-tabs-custom > .nav-tabs > li.active{
   border-top-color:#00a65a!important;
   }
   .widget-user-image>img{
   width: 70px !important;
   height: 70px !important
   }
   .fix_width{
   width: 156px;
   }
</style>
<style>
    @media print {
      /* top-level divs with ids */
      body > div[id] {
        page-break-before: always;
      }
    }
  </style>
@endsection
@section('content')
<!-- Content Wrapper -->
<div class="content-wrapper">
   <section class="content-header">
      <h1>Ride Details
         <a href="{{ url('admin/rides-list')}}" class="btn btn-success pull-right"><i class="fa fa-arrow-left"></i> Go Back</a>
      </h1>
   </section>
   <!-- Content -->
   <section class="content">
      @if(isset($rideInfo))
      {!! Form::open(['id' => 'search_form_id']) !!}
      {!! Form::close() !!}
      <!-- Row -->
      <div class="row">
         <div class="col-md-6">
            <!-- Nav Tabs -->
            <div class="nav-tabs-custom">
               <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_1" data-toggle="tab"><b>Passenger Details</b></a></li>
                  <li><a href="#tab_2" data-toggle="tab"><b>Driver Details</b></a></li>
               </ul>
               <!-- Tab content -->
               <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                     <!-- Widget: user widget style 1 -->
                     <div class="box box-widget widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-success">
                           <div class="widget-user-image">
                              @if($rideInfo->passengers_profile_image != '')
                              
                              @if(file_exists('public/uploads/profile/'.$rideInfo->passengers_profile_image))
                              {!! Html::image('public/uploads/profile/'.$rideInfo->passengers_profile_image, '', ['class' => 'img-responsive img-circle', 'height' => '150px', 'width' => '150px'])!!}
                              @else
                              {!! Html::image('public/uploads/no-image.png', '', ['class' => 'img-responsive img-circle', 'height' => '150px', 'width' => '150px'])!!}
                              @endif
                              @else
                              {!! Html::image('public/uploads/no-image.png', '', ['class' => 'img-responsive img-circle', 'height' => '150px', 'width' => '150px'])!!}
                              @endif
                           </div>
                           <!-- /.widget-user-image -->
                           <h3 class="widget-user-username">
                              @if($rideInfo->passenger_first_name)
                              {{ucfirst($rideInfo->passenger_first_name).' '.ucfirst($rideInfo->passenger_last_name)}}
                              @else
                              N/A
                              @endif
                           </h3>
                           <h5 class="widget-user-desc">
                              @if($rideInfo->passenger_email)
                              {{$rideInfo->passenger_email}}
                              @else
                              N/A
                              @endif
                           </h5>
                        </div>
                        <div class="box-footer no-padding">
                           <ul class="nav nav-stacked">
                              <li><a href="javascript:"><b>Contact Number</b> <span class="pull-right">{{'+'.$rideInfo->passenger_country_code.' '.$rideInfo->passenger_mobile_number}}</span></a></li>
                           </ul>
                        </div>
                     </div>
                     <!-- /.widget-user -->
                  </div>
                  <div class="tab-pane" id="tab_2">
                     <div class="box box-widget widget-user-2">
                        <!-- Widget: user widget style 1 -->
                        @if($rideInfo->driver_first_name != "")
                        <div class="widget-user-header bg-success">
                           <div class="widget-user-image">
                              @if($rideInfo->driver_profile_image != '')
                              @if(file_exists('public/uploads/profile/'.$rideInfo->driver_profile_image))
                              {!! Html::image('public/uploads/profile/'.$rideInfo->driver_profile_image, '', ['class' => 'img-responsive img-circle', 'height' => '150px', 'width' => '150px'])!!}
                              @else
                              {!! Html::image('public/uploads/no-image.png', '', ['class' => 'img-responsive img-circle', 'height' => '150px', 'width' => '150px'])!!}
                              @endif
                              @else
                              {!! Html::image('public/uploads/no-image.png', '', ['class' => 'img-responsive img-circle', 'height' => '150px', 'width' => '150px'])!!}
                              @endif
                           </div>
                           <!-- /.widget-user-image -->
                           <h3 class="widget-user-username">
                              @if($rideInfo->driver_first_name)
                              {{ucfirst($rideInfo->driver_first_name).' '.ucfirst($rideInfo->driver_last_name)}}
                              @else
                              N/A
                              @endif
                           </h3>
                           <h5 class="widget-user-desc">
                              @if($rideInfo->driver_email)
                              {{$rideInfo->driver_email}}
                              @else
                              N/A
                              @endif
                           </h5>
                        </div>
                        <div class="box-footer no-padding">
                           <ul class="nav nav-stacked">
                              <li><a href="javascript:"><b>Contact Number</b> <span class="pull-right">{{'+'.$rideInfo->driver_country_code.' '.$rideInfo->driver_mobile_number}}</span></a></li>
                           </ul>
                        </div>
                        @else
                        <div class="alert alert-warning alert-dismissable fade in">
                           <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                           <strong>Not assign!</strong> Drivers not assign yet.
                        </div>
                        @endif
                     </div>
                     <!-- /.widget-user -->
                  </div>
               </div>
               <!-- Tab Content End -->
            </div>
            <!-- Nav Tab End -->
         </div>
         <div class="col-md-6">
            <div class="box box-success box-solid">
               <div class="box-header">
                  <h3 class="box-title">Capture Image</h3>
                  @if(count($capturerImage) > 0)
                  <a href="{{url('admin/view_all_images/'.$rideInfo->rideId)}}" class="btn btn-success pull-right" style="border-color: #ecf0f5;">View All Images</a>
                  @endif

               </div>
               <!-- Table -->
               <div class="box-body" id="tables">
                  @if(count($capturerImage) > 0)
                  <div id="myCarousel" class="carousel slide" data-ride="carousel">
                 
                     <!-- Wrapper for slides -->
                     <div class="carousel-inner">
                        <?php $i = 0;?> 
                        @if(count($capturerImage) > 0)
                        @foreach($capturerImage as $val)
                        <?php if($i == 0){ ?>
                        <div class="item active">
                           {!! Html::image(Config::get('constants.AUTO_CAPCTURE').$val->auto_capture,'',['alt' => 'Ride Capturer Image','class' => 'profile-user-img img-responsive img-square','style' => 'width:auto;height:165px;;'])!!}
                        </div>
                        <?php $i = 1;?>
                        <?php }else { ?>
                        <div class="item">
                           {!! Html::image(Config::get('constants.AUTO_CAPCTURE').$val->auto_capture,'',['alt' => 'Ride Capturer Image','class' => 'profile-user-img img-responsive img-square','style' => 'width:auto;height:165px;;'])!!}
                        </div>
                        <?php } ?>
                        @endforeach
                        @endif  
                     </div>
                  </div>
                  @else
                  <div class="colspan=8" style="text-align: center;"><strong>Image Not Found</strong></div>
                  @endif
               </div>
               <!-- End Table -->
            </div>
         </div>
      </div>
      <!-- Row End-->
      <!-- Box -->
      <div class="clearfix"></div>
      <div class="row">
         <div class="col-md-6">
            <!-- Box -->
            <div class="box box-success box-solid">
               <div class="box-header">
                  <h3 class="box-title">Ride Details</h3>
                     <a class="btn btn-success pull-right" onclick="print_ride_detail('ride_detail')">Print / PDF </a>
                                       
               </div>
               <!-- Body -->
               <div class="box-body" id="ride_detail">
                  <table class="table table-hover table-responsive">
                     <tbody>
                        <tr>
                           <td class="fix_width"><b>Payment Type : </b></td>
                           <td>@if($rideInfo->payment_type == 1) Cash @else Online @endif</td>
                        </tr>
                        <tr>
                           <td class="fix_width"><b>Pickup Location : </b></td>
                           <td>{{ucfirst($rideInfo->pickup_location)}}</td>
                        </tr>
                        <tr>
                           <td><b>Dropoff Location : </b></td>
                           <td>{{ucfirst($rideInfo->dropoff_location)}}</td>
                        </tr>
                       
                        <tr>
                           <td class="fix_width"><b>Etimated Fare : </b></td>
                           @if($rideInfo->esti_fare != "")
                           <td><?php echo Config::get('constants.CURRENCY').($rideInfo->esti_fare);?></td>
                           @else
                           <td>N/A</td>
                           @endif    
                        </tr>

                        <tr>
                           <td class="fix_width"><b>Etimated Distance : </b></td>
                           @if($rideInfo->esti_distance != "")
                           <td>{{$rideInfo->esti_distance.' '.Config::get('constants.DISTANCE_UNIT')}}</td>
                           @else
                           <td>N/A</td>
                           @endif    
                        </tr>
                        <tr>
                           <td class="fix_width"><b>Etimated Time (Min) : </b></td>
                           @if($rideInfo->esti_time != "")
                           <td><?php echo $rideInfo->esti_time.' '.Config::get('constants.TIME_UNIT');?></td>
                           @else
                           <td>N/A</td>
                           @endif    
                        </tr>

                         @if($rideInfo->ride_status == "Completed")

                           <tr>
                              <td class="fix_width"><b>Fare : </b></td>
                              @if($rideInfo->fare != "")
                              <td><?php echo Config::get('constants.CURRENCY').$rideInfo->fare;?></td>
                              @else
                              <td>N/A</td>
                              @endif 
                           </tr>

                           @if($rideInfo->packagesId != "")
                           <tr>

                              <td class="fix_width"><b>Rental Packages : </b></td> <td><?php echo $rideInfo->distance.Config::get('constants.DISTANCE_UNIT').' - '.$rideInfo->time.' Min '.'in '.Config::get('constants.CURRENCY').' '.number_format($rideInfo->charges,2);?></td>                          
                           </tr>
                           @endif

                           <tr>
                              <td class="fix_width"><b>Actual Distance : </b></td>
                              @if(($rideInfo->actual_distance != "")&&($rideInfo->actual_distance != 'null'))
                              <td>{{$rideInfo->actual_distance.' '.Config::get('constants.DISTANCE_UNIT')}}</td>
                              @else
                              <td>N/A</td>
                              @endif    
                           </tr>
                           <tr>
                              <td class="fix_width"><b>Actual Time (Min): </b></td>
                              @if(($rideInfo->actual_time != "")&& ($rideInfo->actual_time != 'null'))
                              <td>{{ $rideInfo->actual_time.'  '.Config::get('constants.TIME_UNIT')}}</td>
                              @else
                              <td>N/A</td>
                              @endif    
                           </tr>

                           <tr>
                              <td class="fix_width"><b>Waiting Charges </b></td>
                              @if($rideInfo->waiting_charges != "")
                              <td>{{ $rideInfo->waiting_charges}}</td>
                              @else
                              <td>N/A</td>
                              @endif    
                           </tr>

                           <tr>
                              <td class="fix_width"><b>Promocode Charges </b></td>
                              @if($rideInfo->promocode_charges != "")
                              <td>{{ $rideInfo->promocode_charges}}</td>
                              @else
                              <td>N/A</td>
                              @endif    
                           </tr>
                        
                           <tr>
                              <td class="fix_width"><b>Total Amount : </b></td>
                              @if($rideInfo->total_amount != "")
                              
                                 <td>
                                    <?php echo Config::get('constants.CURRENCY').$rideInfo->total_amount;?>
                                       
                                 </td>
                              
                              @else
                                 
                                 <td>N/A</td>
                              
                              @endif    
                           </tr>

                           <tr>
                              <td class="fix_width"><b>Start Ride Date : </b></td>
                              @if($rideInfo->start_ride_date != "")
                              
                                 <td>
                                    <?php echo $rideInfo->start_ride_date;?>
                                       
                                 </td>
                              
                              @else
                                 
                                 <td>N/A</td>
                              
                              @endif    
                           </tr>

                           <tr>
                              <td class="fix_width"><b>End Ride Date : </b></td>
                              @if($rideInfo->end_ride_date != "")
                              
                                 <td>
                                    <?php echo $rideInfo->end_ride_date;?>
                                       
                                 </td>
                              
                              @else
                                 
                                 <td>N/A</td>
                              
                              @endif    
                           </tr>

                           <tr>
                              <td class="fix_width"><b>GST :</b></td>
                              @if($rideInfo->gst_amount !="")
                              <td><?php echo Config::get('constants.CURRENCY').$rideInfo->gst_amount;?></td>
                              @else
                              <td>N/A</td>
                              @endif
                           </tr>

                           <tr>
                              <td class="fix_width"><b>Pay Amount : </b></td>
                              @if($rideInfo->pay_amount != "")
                              <td><?php echo Config::get('constants.CURRENCY').$rideInfo->pay_amount;?></td>
                              @else
                              <td>N/A</td>
                              @endif    
                           </tr>

                        @endif
                        
                        <tr>
                           <td class="fix_width"><b>Ride Status : </b></td>
                           <td><?php echo ride_status($rideInfo->ride_status) ?></td>
                        </tr>
                        <tr>
                           <td class="fix_width"><b>Number Of Passenger : </b></td>
                           <td>{{$rideInfo->no_of_passenger}}</td>
                        </tr>
                        @if($rideInfo->cancel_reason != "")
                        <tr>
                           <td class="fix_width"><b>Cancel Reason : </b></td>
                           <td>{{$rideInfo->cancel_reason}}</td>
                        </tr>
                        @endif
                        <tr>
                           <td class="fix_width"><b>Create Date: </b></td>
                           <td>
                              <?php 
                                 echo date(Config::get('constants.DATE_TIME_FORMATE'),strtotime($rideInfo->ride_date));
                                 ?>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <!-- Body End -->
            </div>
            <!-- Box End -->
         </div>

         <!-- Driver Request -->
         <div class="col-md-6">
            
            <div class="box box-success box-solid">
               <div class="box-header">
                  <h3 class="box-title">Ride Requests</h3>
                     <a class="btn btn-success pull-right" onclick="print_ride_detail('ride_requests')" >Print / PDF </a>
                                       
               </div>
              
               <div class="box-body" id="ride_requests">
                  <table class="table table-hover table-responsive">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Request Status</th>
                           <th>Driver Name</th>
                           <th>Cancelled Reason</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php $no = 1;?>
                        @foreach($rideRequest as $info)
                        

                           <tr>
                              <th>{{$no}}</th>
                              <th>{{$info->request_status}}</th>
                              <th>{{$info->first_name." ".$info->last_name}}</th>
                              
                              
                              <th>@if($info->not_accepted_reason != "")
                                 {{$info->not_accepted_reason}}
                              @else
                                   --
                              @endif
                              </th>


                        <?php $no++?>
                           </tr>

                        @endforeach                        
                     </tbody>
                  </table>
               </div>
              
            </div>
            
         </div>
         <!-- end Driver Request -->
         

      </div>

      <!--Ride Detail location  -->
      <div class="row">
         <input type="hidden" name="pickup_location" id="pickup_location" value="{{$rideInfo->pickup_location}}">
         <input type="hidden" name="dropoff_location" id="dropoff_location" value="{{$rideInfo->dropoff_location}}">
         <div class="col-md-12">
            <div class="box box-success box-solid">
               <div class="box-header">
                  <h3 class="box-title">Ride Location</h3>
                  <button class="btn btn-success pull-right" onclick="print_location();">Print</button>
               </div>
               <!-- Table -->
               <div class="box-body" id="tables">
                  <div class="col-md-12">
                     <div id="map" style="width: 100%;height: 503px;"></div>
                  </div>
                  <div class="clearfix"></div>
               </div>
               <!-- End Table -->
            </div>
         </div>
      </div>

      <!-- End Ride location -->
      <!-- End Box -->
   </section>
   <!-- Content End -->
   @else
   <div class="alert alert-warning alert-dismissable">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Failed!</strong> Something went to wrong. Customer information not found.
   </div>
   @endif
</div>
@stop
@section('script')
<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places&language=en&key=AIzaSyD-Da1KcBqqG6I4LTakwmeISnl34V-Ex6E"></script>
<script type="text/javascript">
   function initMap() {
   
     var directionsService = new google.maps.DirectionsService;
     var directionsDisplay = new google.maps.DirectionsRenderer;
     var map = new google.maps.Map(document.getElementById('map'), {
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
   initMap();   
</script>
<script type="text/javascript">

    function print_ride_detail(id){

      console.log(id);

      var mywindow = window.open('PRINT');

      mywindow.document.write('<html><head><title>' + document.title  + '</title>');
      mywindow.document.write('</head><body ><center>');
      
      mywindow.document.write('<h1>' + document.title  + '</h1>');
      
      mywindow.document.write(document.getElementById(id).innerHTML);
      
      mywindow.document.write('</center></body></html>');

      mywindow.document.close(); // necessary for IE >= 10
      
      mywindow.focus(); // necessary for IE >= 10*/

      mywindow.print();
      mywindow.close();

    return true;

    }

    function print_location(elem){

      var data = document.getElementById("map").innerHTML;

      var mywindow = window.open('', 'Ride Location',);
          mywindow.document.write('<html><head><title>my div</title>');
          mywindow.document.write('</head><body >');
          mywindow.document.write(data);
          mywindow.document.write('</body></html>');

          mywindow.print();
          mywindow.close();

          return true;
    }
</script>
@endsection

