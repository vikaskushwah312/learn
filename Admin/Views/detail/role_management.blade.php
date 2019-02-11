@extends('Admin::layout.master') 
@section('css')
<style type="text/css">
    .widget-user-image > img{
        height: 65px !important; 
    }
</style>

@endsection
@section('content')
<!-- Content Wrapper -->
<div class="content-wrapper" style="min-height: 1126px;">
    <section class="content-header">
        <h1>{{$title}}
            <a href="{{url('admin/staff-list')}}" class="btn btn-success pull-right"><i class="fa fa-arrow-left"></i> Go Back</a>
        </h1>
    </section>
    <!-- Content -->
    <section class="content">
    @if(isset($info))
                
        <div class="row">
            
            <div class="col-md-6">
                <!-- Box -->
                <div class="box box-success box-solid" style="border: none;">
                    <div class="box-header">
                        <h3 class="box-title">{{$title}}</h3>
                        
                    </div>
                    <!-- Body -->
                    <div class="box-body">
                      <table class="table table-hover table-responsive">
                        <tbody>
        
                        <tr>
                            <td><b>Name : </b></td>
                            <td>{{ ucfirst($info->first_name." ".$info->last_name) }}</td>
                        </tr>
                        <tr>
                            <td><b>Contact Number : </b></td>
                            <td>{{ ucfirst($info->mobile_number) }}</td>
                        </tr>
                        <tr>
                            <td><b>Email : </b></td>
                            <td>{{ ucfirst($info->email) }}</td>
                        </tr>
                        <tr>
                            <td><b>Address : </b></td>
                            <td>{{ ucfirst($info->address) }}</td>
                        </tr>
                        <tr>
                            <td><b>Created At : </b></td>
                            <td>{{ ucfirst($info->created_at) }}</td>
                        </tr>
                     
                      </tbody></table>
                    </div>
                    <!-- Body End -->
                </div>
                <!-- Box End -->
            </div>

            <div class="col-md-12">
                <!-- Box -->
                <div class="box box-success box-solid" style="border: none;">
                    <div class="box-header">
                        <h3 class="box-title">{{$title}}</h3>
                        
                    </div>
                    <!-- Body -->
                    <div class="box-body">
                      <table class="table table-hover table-responsive">
                        <tbody>
                        <tr>
                            <td></td>
                            <td><b>Add</b></td>
                            <td><b>Edit</b></td>
                            <td><b>Delete</td></td>
                            <td><b>Read</b></td>

                        </tr>

                        <tr>
                             <td><b>Passenger</b></td>
                            <td>
                                <input type="checkbox" name="passenger_add" id="passenger_add" onchange="set_permission('passenger_add', {{ $info->id }}, {{ Config::get('constants.PASSENGER_ADD')}})" <?php echo ($info->permission & Config::get('constants.PASSENGER_ADD')) ? "checked" : ""; ?>>
                            </td>

                            <td>
                                <input type="checkbox" name="passenger_edit" id="passenger_edit" onchange="set_permission('passenger_edit', {{ $info->id }}, {{ Config::get('constants.PASSENGER_EDIT')}})" <?php echo ($info->permission & Config::get('constants.PASSENGER_EDIT')) ? "checked" : ""; ?>>
                            </td>

                            <td>
                                <input type="checkbox" name="passenger_delete" id="passenger_delete" onchange="set_permission('passenger_delete', {{ $info->id }}, {{ Config::get('constants.PASSENGER_DELETE')}})" <?php echo ($info->permission & Config::get('constants.PASSENGER_DELETE')) ? "checked" : ""; ?>>
                            </td>

                            <td>
                                <input type="checkbox" name="passenger_read" id="passenger_read" onchange="set_permission('passenger_read', {{ $info->id }}, {{ Config::get('constants.PASSENGER_READ')}})" <?php echo ($info->permission & Config::get('constants.PASSENGER_READ')) ? "checked" : ""; ?>>
                            </td>
                            
                        </tr>

                        <tr>
                             <td><b>Driver</b></td>
                            <td>
                                <input type="checkbox" name="driver_add" id="driver_add" onchange="set_permission('driver_add', {{ $info->id }}, {{ Config::get('constants.DRIVER_ADD')}})" <?php echo ($info->permission & Config::get('constants.DRIVER_ADD')) ? "checked" : ""; ?>>
                            </td>

                            <td>
                                <input type="checkbox" name="driver_edit" id="driver_edit" onchange="set_permission('driver_edit', {{ $info->id }}, {{ Config::get('constants.DRIVER_EDIT')}})" <?php echo ($info->permission & Config::get('constants.DRIVER_EDIT')) ? "checked" : ""; ?>>
                            </td>

                            <td>
                                
                                <input type="checkbox" name="driver_delete" id="driver_delete" onchange="set_permission('driver_delete', {{ $info->id }}, {{ Config::get('constants.DRIVER_DELETE')}})" <?php echo ($info->permission & Config::get('constants.DRIVER_DELETE')) ? "checked" : ""; ?>>

                            </td>

                            <td>
                                <input type="checkbox" name="driver_read" id="driver_read" onchange="set_permission('driver_read', {{ $info->id }}, {{ Config::get('constants.DRIVER_READ')}})" <?php echo ($info->permission & Config::get('constants.DRIVER_READ')) ? "checked" : ""; ?>>
                            </td>
                            
                        </tr>                       

                        <tr>
                             <td><b>Vehicles</b></td>
                            <td>
                                <input type="checkbox" name="vehicle_add" id="vehicle_add" onchange="set_permission('vehicle_add', {{ $info->id }}, {{ Config::get('constants.VEHICLE_ADD')}})" <?php echo ($info->permission & Config::get('constants.VEHICLE_ADD')) ? "checked" : ""; ?>>
                            </td>

                            <td>
                                <input type="checkbox" name="vehicle_edit" id="vehicle_edit" onchange="set_permission('vehicle_edit', {{ $info->id }}, {{ Config::get('constants.VEHICLE_EDIT')}})" <?php echo ($info->permission & Config::get('constants.VEHICLE_EDIT')) ? "checked" : ""; ?>>
                            </td>

                            <td>
                                <input type="checkbox" name="vehicle_delete" id="vehicle_delete" onchange="set_permission('vehicle_delete', {{ $info->id }}, {{ Config::get('constants.VEHICLE_DELETE')}})" <?php echo ($info->permission & Config::get('constants.VEHICLE_DELETE')) ? "checked" : ""; ?>>
                            </td>

                            <td>
                                <input type="checkbox" name="vehicle_read" id="vehicle_read" onchange="set_permission('vehicle_read', {{ $info->id }}, {{ Config::get('constants.VEHICLE_READ')}})" <?php echo ($info->permission & Config::get('constants.VEHICLE_READ')) ? "checked" : ""; ?>>
                            </td>
                            
                        </tr>

                        <tr>
                             <td><b>Ride</b></td>
                            <td>
                                <input type="checkbox" name="ride_add" id="ride_add" onchange="set_permission('ride_add', {{ $info->id }}, {{ Config::get('constants.RIDE_ADD')}})" <?php echo ($info->permission & Config::get('constants.RIDE_ADD')) ? "checked" : ""; ?>>
                            </td>

                            <td>
                                <input type="checkbox" name="ride_edit" id="ride_edit" onchange="set_permission('ride_edit', {{ $info->id }}, {{ Config::get('constants.RIDE_EDIT')}})" <?php echo ($info->permission & Config::get('constants.RIDE_EDIT')) ? "checked" : ""; ?>>
                            </td>

                            <td>
                                <input type="checkbox" name="ride_delete" id="ride_delete" onchange="set_permission('ride_delete', {{ $info->id }}, {{ Config::get('constants.RIDE_DELETE')}})" <?php echo ($info->permission & Config::get('constants.RIDE_DELETE')) ? "checked" : ""; ?>>
                            </td>

                            <td>
                                <input type="checkbox" name="ride_read" id="ride_read" onchange="set_permission('ride_read', {{ $info->id }}, {{ Config::get('constants.RIDE_READ')}})" <?php echo ($info->permission & Config::get('constants.RIDE_READ')) ? "checked" : ""; ?>>
                            </td>
                            
                        </tr>

                        <tr>
                             <td><b>Live Tracking</b></td>
                                 <td><p>&mdash;</p></td>

                            <td><p>&mdash;</p></td>

                            <td><p>&mdash;</p></td> 
 
                            <td>
                                <input type="checkbox" name="live_tracking_read" id="live_tracking_read" onchange="set_permission('live_tracking_read', {{ $info->id }},{{ Config::get('constants.LIVE_TRACKING_READ')}})" <?php echo ($info->permission & Config::get('constants.LIVE_TRACKING_READ')) ? "checked" : ""; ?>>
                            </td>
                            
                        </tr>

                        <tr>
                             <td><b>Rating & Review</b></td>
                                 <td><p>&mdash;</p></td>

                            <td><p>&mdash;</p></td>

                            <td><p>&mdash;</p></td> 
 
                            <td>
                                <input type="checkbox" name="live_tracking_read" id="live_tracking_read" onchange="set_permission('live_tracking_read', {{ $info->id }},{{ Config::get('constants.RATING_REVIEWS_READ')}})" <?php echo ($info->permission & Config::get('constants.RATING_REVIEWS_READ')) ? "checked" : ""; ?>>
                            </td>
                            
                        </tr>                 

                      </tbody></table>
                    </div>
                    <!-- Body End -->
                </div>
                <!-- Box End -->
            </div>
        </div>
      
    </section>
    <!-- Content End -->
    @else
    <div class="alert alert-warning alert-dismissable">
      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
      <strong>Failed!</strong> Something went to wrong. User information not found.
    </div>
    @endif
</div>

@stop
@section('script')

    
    <script type="text/javascript">

    function fancybox()
    {
      $(".fancybox").fancybox();
    }
    
    function set_permission(name,staffId, value){
        
        var type = "";
        
        if($("#"+name).is(':checked')){
          
            type = "ADD";
                  
        }else{

            type = "REMOVE";
        
        }
        
        $.ajax({
            url: "{{url('admin/set-staff-permission')}}",
            method:'POST',
            dataType: 'JSON',
            data:{ 'staffId': staffId, 'value' : value, 'type': type },
            headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(res){
              
            }  
        });  
    }


    </script> 
   
    
@endsection
