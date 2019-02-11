@extends('Admin::layout.master')

@section('css')
  {!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}  
@endsection

@section('content')
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         {{@$heading}}
         <a href="{{ url('admin/drivers-list')}}" class="btn btn-success pull-right"><i class="fa fa-arrow-left"></i> Go Back</a>   
      </h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <!-- Profile Image -->
            <div class="box box-success box-solid">
               <div class="box-header with-border">
                  <h3 class="box-title text-right">{{@$heading}}</h3>
               </div>
               <div class="box-body">
                  @if(isset($driverInfo->driver_info) && !empty($driverInfo->driver_info))
                  <div class="col-md-3">
                     <!-- Profile Image -->
                     <div class="">
                        <div class="box-body box-profile">
                           @if(isset($driverInfo->driver_info->profile_image) && $driverInfo->driver_info->profile_image != '')
                           
                                                     
                           <a  class = "fancybox" href="{{url(Config::get('constants.PROFILE_IMAGE').$driverInfo->driver_info->profile_image )}}" > {!! Html::image(Config::get('constants.PROFILE_IMAGE').$driverInfo->driver_info->profile_image,'',['alt' => 'User profile picture','class' => 'profile-user-img img-responsive img-square',"style" => 'width:250px;height:150px;'])!!} </a>

                           @else
                           {!! Html::image(Config::get('constants.NO_IMAGE').'user-no-image.png','',['alt' => 'User profile picture','class' => 'profile-user-img img-responsive img-square',"style" => 'width:250px;height:150px;'])!!}
                           @endif  
                           <h3 class="profile-username text-center">{{ ucfirst(check_set($driverInfo->driver_info->first_name).' '.check_set($driverInfo->driver_info->last_name)) }}</h3>
                           <p class="text-muted text-center"><span class="badge bg-green">
                              @if($driverInfo->driver_info->status == 1) Active @else Inactive @endif</span>
                           </p>
                           
                        </div>
                     </div>
                  </div>
                  <div class="col-md-8 table-responsive">
                     <div class="">
                        <div class="box-body box-profile">
                           <table id="customer-detail-table" class="table table-bordered table-striped">
                              <thead>
                                 <tr>
                                    <td><b>Email</b></td>
                                    <td>{{ check_set($driverInfo->driver_info->email) }}</td>
                                 </tr>
                                 <tr>
                                    <td><b>Mobile Number</b></td>
                                    <td>{{'+'.check_set($driverInfo->driver_info->country_code).'-'.check_set($driverInfo->driver_info->mobile_number)}}</td>
                                 </tr>
                                 <tr>
                                    <td><b>Gender</b></td>
                                    <td>{{ucfirst(check_set($driverInfo->driver_info->gender))}}</td>
                                 </tr>
                                 <tr>
                                    <td><b>Country</b></td>
                                    <td>{{ucfirst(check_set($driverInfo->driver_info->country_name))}}</td>
                                 </tr>
                                 <tr>
                                    <td><b>State</b></td>
                                    <td>{{ucfirst(check_set($driverInfo->driver_info->state_name))}}</td>
                                 </tr>
                                 <tr>
                                    <td><b>City</b></td>
                                    <td>{{ucfirst(check_set($driverInfo->driver_info->city_name))}}</td>
                                 </tr>
                                 <tr>
                                    <td><b>Address</b></td>
                                    <td>{{ucfirst(check_set($driverInfo->driver_info->address))}}</td>
                                 </tr>
                                 <tr>
                                    <td><b>Created At</b></td>
                                    @if($driverInfo->created_at != "")
                                    <td><?php echo date(Config::get('constants.DATE_TIME_FORMATE'), strtotime($driverInfo->driver_info->created_at)); ?></td>
                                    @else
                                    <td>N/A</td>
                                    @endif
                                 </tr>
                              </thead>
                              <tbody>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  @else
                    <div class="col-span-9" style="text-align: center;"><strong>Info Not Found</strong></div>
                  @endif
               </div>
               <!-- /.box-body -->
            </div>
            <!-- /.box -->
         </div>
      </div>
      <div class="row">
         <div class="col-md-6">
            <!-- Profile Image -->
            <div class="box box-success box-solid">
               <div class="box-header with-border">
                  <h3 class="box-title text-right">Assign Vehicle Info
                  </h3>
               </div>
               <div class="box-body">
                @if(isset($driverInfo->assigned_vehicle) && !empty($driverInfo->assigned_vehicle))
                  <div class="col-md-6">
                     <!-- Profile Image -->
                     <div class="">
                        <p>
                        <h4>Vehicle Image</h4>
                        </p>
                        <div class="box-body box-profile">
                           @if($driverInfo->assigned_vehicle->vehicle_image != '')
                           
                           <a class="fancybox" href="{{url(Config::get('constants.VEHICLE_IMAGE').$driverInfo->assigned_vehicle->vehicle_image)}}">
                           {!! Html::image(Config::get('constants.VEHICLE_IMAGE').$driverInfo->assigned_vehicle->vehicle_image,'',['alt' => 'Vehicle Image','class' => 'profile-user-img img-responsive img-square',"style" => 'width:200px;height:150px;'])!!}
                           </a>
                           
                           @else
                           {!! Html::image(Config::get('constants.NO_IMAGE').'user-no-image.png','',['alt' => 'User profile picture','class' => 'profile-user-img img-responsive img-square',"style" => 'width:200px;height:150px;'])!!}
                           @endif  
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <!-- Profile Image -->
                     <div class="">
                        <p>
                        <h4>Insurance Image</h4>
                        </p>
                        <div class="box-body box-profile">
                           @if($driverInfo->assigned_vehicle->insurance_image != '')
                           <a class="fancybox" href="{{url(Config::get('constants.INSURANCE_IMAGE').$driverInfo->assigned_vehicle->insurance_image)}}">
                           {!! Html::image(Config::get('constants.INSURANCE_IMAGE').$driverInfo->assigned_vehicle->insurance_image,'',['alt' => 'Insurance Image','class' => 'profile-user-img img-responsive img-square',"style" => 'width:250px;height:150px;'])!!}
                        </a>
                           @else
                           {!! Html::image(Config::get('constants.NO_IMAGE').'user-no-image.png','',['alt' => 'User profile picture','class' => 'profile-user-img img-responsive img-square',"style" => 'width:250px;height:150px;'])!!}
                           @endif 
                        </div>
                     </div>
                  </div>
                  <div class="col-md-12 table-responsive" style="height: 250px; overflow: auto;">
                     <div class="box-body box-profile">
                        <table id="customer-detail-table" class="table table-bordered table-striped">
                           <thead>
                              <tr>
                                 <td><b>Vehicle Name</b></td>
                                 <td>{{ucfirst(check_set($driverInfo->assigned_vehicle->name))}}</td>
                              </tr>
                              <tr>
                                 <td><b>Insurance Number</b></td>
                                 <td>{{ucfirst(check_set($driverInfo->assigned_vehicle->insurance_no))}}</td>
                              </tr>
                              <tr>
                                 <td><b>Insurance Expiry Date</b></td>
                                 <td><?php echo date(Config::get('constants.DATE_FORMATE'), strtotime($driverInfo->assigned_vehicle->insurance_expiry_date)); ?></td>
                              </tr>
                              <tr>
                                 <td><b>Service Type</b></td>
                                 <td>{{ucfirst(check_set($driverInfo->assigned_vehicle->service_type_title))}}</td>
                              </tr>
                              <tr>
                                 <td><b>Brand Name</b></td>
                                 <td>{{check_set($driverInfo->assigned_vehicle->brand_name)}}</td>
                              </tr>
                              <tr>
                                 <td><b>Model Name</b></td>
                                 <td>{{check_set($driverInfo->assigned_vehicle->vehicle_model_name)}}</td>
                              </tr>
                              <tr>
                                 <td><b>Manufacturer</b></td>
                                 <td>{{ucfirst(check_set($driverInfo->assigned_vehicle->manufacturer))}}</td>
                              </tr>
                              <tr>
                                 <td><b>Vehicle Number</b></td>
                                 <td>{{check_set($driverInfo->assigned_vehicle->number_plate)}}</td>
                              </tr>
                              <tr>
                                 <td><b>Number Of Seat</b></td>
                                 <td>{{check_set($driverInfo->assigned_vehicle->no_of_seat)}}</td>
                              </tr>
                              <tr>
                                 <td><b>Luggage Capacity</b></td>
                                 <td>{{check_set($driverInfo->assigned_vehicle->luggage_capacity)}}</td>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  @else
                    <div class="col-span-9" style="text-align: center;"><strong>Info Not Found</strong></div>
                  @endif
               </div>
               <!-- /.box-body -->
            </div>
            <!-- /.box -->
         </div>
         <div class="col-md-6">
            <!-- Profile Image -->
            <div class="box box-success box-solid">
               <div class="box-header with-border">
                  <h3 class="box-title text-right">License Info</h3>
               </div>
               <div class="box-body">
                @if(isset($driverInfo->driver_license) && !empty($driverInfo->driver_license))
                  <div class="col-md-6">
                     <!-- Profile Image -->
                     <div class="">
                        <div class="box-body box-profile">
                           @if($driverInfo->driver_license->license_image != '')
                           
                           <a class="fancybox" href="{{url(Config::get('constants.LICENSE_IMAGE').$driverInfo->driver_license->license_image)}}">
                           {!! Html::image(Config::get('constants.LICENSE_IMAGE').$driverInfo->driver_license->license_image,'',['alt' => 'User profile picture','class' => 'profile-user-img img-responsive img-square',"style" => 'width:250px;height:150px;'])!!}
                           </a>
                           @else
                           {!! Html::image(Config::get('constants.NO_IMAGE').'user-no-image.png','',['alt' => 'User profile picture','class' => 'profile-user-img img-responsive img-square',"style" => 'width:250px;height:150px;'])!!}
                           @endif  
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6"></div>
                  <div class="col-md-12 table-responsive">
                     <div class="">
                        <div class="box-body box-profile">
                           <table id="customer-detail-table" class="table table-bordered table-striped">
                              <thead>
                                 <tr>
                                    <td><b>License Number</b></td>
                                    <td>{{check_set($driverInfo->driver_license->license_number)}}</td>
                                 </tr>
                                 <tr>
                                    <td><b>Vehicle Type</b></td>
                                    <td>{{check_set($driverInfo->driver_license->vehicle_type)}}</td>
                                 </tr>
                                 <tr>
                                    <td><b>Issued On</b></td>
                                    <td><?php echo date(Config::get('constants.DATE_FORMATE'), strtotime($driverInfo->driver_license->issued_on)); ?></td>
                                 </tr>
                                 <tr>
                                    <td><b>Expiry Date</b></td>
                                    <td><?php echo date(Config::get('constants.DATE_FORMATE'), strtotime($driverInfo->driver_license->expiry_date)); ?></td>
                                 </tr>
                              </thead>
                              <tbody>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  @else
                    <div class="col-span-9" style="text-align: center;"><strong>Info Not Found</strong></div>
                  @endif
               </div>
               <!-- /.box-body -->
            </div>
            <!-- /.box -->
         </div>
      </div>
      <!-- /.row -->
   </section>
</div>
@stop

@section('script')

{!! Html::script('public/custom/js/admin_validation.js')!!} 
  
{!! Html::script('public/custom/plugnis/fancybox/source/jquery.fancybox.js') !!}

 <script type="text/javascript">
   
   function viewImage(){
      $(".fancybox").fancybox();

   }
   viewImage();         
       
</script>

@endsection
