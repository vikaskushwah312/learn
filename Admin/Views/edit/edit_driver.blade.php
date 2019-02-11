@extends('Admin::layout.master')
@section('css')
{!! Html::style('public/custom/plugnis/select2/select.min.css') !!}
{!! Html::style('public/custom/plugnis/select2/select.min.css') !!}

{!! Html::style('public/custom/plugnis/bootstrap-date-time-picker/bootstrap-datetimepicker.css') !!}
  {!! Html::style('public/custom/plugnis/bootstrap-date-time-picker/bootstrap-datetimepicker-standalone.css') !!}
<style type="text/css">
  .dropdown-menu > .active > a, .dropdown-menu > .active > a:focus, .dropdown-menu > .active > a:hover {
        background-color: #00a65a!important;
    }
    .form-control:hover{

        border-color: #00a65a!important;
    } 
    .bs-placeholder {
      border: 1px solid lightgray !important;
    } 
  </style>
@endsection
@section('content')

<?php

  $driverInfo     = $info->driver_info;
  $driver_license  = $info->driver_license;

?>  
<div class="content-wrapper" style="min-height: 508px;">
   <section class="content-header">
      <h1>{{@$heading}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-success box-solid">
               <div class="box-header with-border">
                  <div class="col-md-8" >
                     <h3 class="box-title">{{@$heading}}</h3>
                  </div>
               </div>
               <!-- /.box-header -->
               <!-- form start -->
               {!! Form::open(array('url' => "admin/edit-driver/".Request::segment(3).'/','id'=>"editDriverForm", 'name'=>"editDriverForm",'files'=>true,'method' => 'POST','class' => '')) !!}
               <!-- <input type="hidden" name="subCategoryId" id="subCategoryId" value="{{Request::segment(3)}}"> -->
               <div class="box-body">
                  <div class="row">
                      <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('first_name', 'First Name') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        {!! Form::text('first_name', @$driverInfo->first_name,array('class' => 'form-control','placeholder' =>'First Name','id' => 'first_name','maxlength' => '50')) !!}
                        <p class="controll-error help-block" id="er_first_name">@if ($errors->has('first_name')) <i class="errors"></i>&nbsp;{{ $errors->first('first_name') }}</p>
                        @endif
                     </div>
                     <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('last_name', 'Last Name') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        {!! Form::text('last_name', @$driverInfo->last_name,array('class' => 'form-control','placeholder' =>'Last Name','id' => 'last_name','maxlength' => '50')) !!}
                        <p class="controll-error help-block" id="er_last_name">@if ($errors->has('last_name')) <i class="errors"></i>&nbsp;{{ $errors->first('last_name') }}</p>
                        @endif
                     </div>
                  </div>
                  <div class="row">
                      <div class="form-group {{ $errors->has('country_code') ? ' has-error' : '' }} col-md-2">
                        {!! Form::label('country_code', 'Country Code') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        {!! Form::text('country_code', @$driverInfo->country_code,array('class' => 'form-control','placeholder' =>'Country Code','id' => 'country_code','maxlength' => '2')) !!}
                        <p class="controll-error help-block" id="er_country_code">@if ($errors->has('country_code')) <i class="errors"></i>&nbsp;{{ $errors->first('country_code') }}</p>
                        @endif
                     </div>
                     <div class="form-group {{ $errors->has('mobile_number') ? ' has-error' : '' }} col-md-4">
                        {!! Form::label('mobile_number', 'Mobile Number') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        {!! Form::text('mobile_number', @$driverInfo->mobile_number,array('class' => 'form-control','placeholder' =>'Mobile Number','id' => 'mobile_number','maxlength' => '10')) !!}
                        <p class="controll-error help-block" id="er_mobile_number">@if ($errors->has('mobile_number')) <i class="errors"></i>&nbsp;{{ $errors->first('mobile_number') }}</p>
                        @endif
                     </div>
                     <div class="form-group col-md-6">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::text('email', @$driverInfo->email,array('class' => 'form-control','placeholder' =>'Email','id' => 'email')) !!}
                        <p class="controll-error help-block" id="er_email">@if ($errors->has('email')) <i class="errors"></i>&nbsp;{{ $errors->first('email') }}</p>
                        @endif
                     </div>
                  </div>
                  <div class="row">
                      <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('Gender', 'Gender') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        <select class="form-control" id="gender" name="gender">
                          <option value="">Select Gender</option>
                          <option value="Male" <?php if($driverInfo->gender == 'Male'){ echo "selected";}?>>Male</option>
                          <option value="Female" <?php if($driverInfo->gender == 'Female'){ echo "selected";}?>>Female</option>
                        </select>
                        <p class="controll-error help-block" id="er_gender">@if ($errors->has('gender')) <i class="errors"></i>&nbsp;{{ $errors->first('gender') }}</p>
                        @endif
                     </div>
                     <div class="form-group {{ $errors->has('verification_status') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('verification_status', 'Verification Status') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        <select class="form-control" id="verification_status" name="verification_status">
                          <option value="">Select Verification Status</option>
                          <option value="Verified" <?php if($driverInfo->mobile_verification_status == 'Verified'){ echo "selected";}?>>Verified</option>
                          <option value="NotVerified" <?php if($driverInfo->mobile_verification_status == 'NotVerified'){ echo "selected";}?>>Not Verified</option>
                        </select>
                        <p class="controll-error help-block" id="er_verification_status">@if ($errors->has('verification_status')) <i class="errors"></i>&nbsp;{{ $errors->first('verification_status') }}</p>
                        @endif
                     </div>
                  </div>
                  <div class="row">
                      <div class="form-group {{ $errors->has('license_number') ? ' has-error' : '' }} col-md-6">
                          {!! Form::label('license_number', 'License Number') !!}&nbsp;<i class="error"><strong>*</strong></i>
                          {!! Form::text('license_number', @$driver_license->license_number,array('class' => 'form-control','placeholder' =>'License Number','id' => 'license_number','maxlength' => '50')) !!}

                          <p class="controll-error help-block" id="er_license_number">@if ($errors->has('license_number')) <i class="errors"></i>&nbsp;{{ $errors->first('license_number') }}</p> @endif

                      </div>       
                      <div class="form-group {{ $errors->has('vehicle_type') ? ' has-error' : '' }} col-md-6">
                          {!! Form::label('vehicle_type', 'Vehicle Type') !!}&nbsp;<i class="error"><strong>*</strong></i>
                          {!! Form::text('vehicle_type', @$driver_license->vehicle_type,array('class' => 'form-control','placeholder' =>'Vehicle Type','id' => 'vehicle_type','maxlength' => '50')) !!}

                          <p class="controll-error help-block" id="er_vehicle_type">@if ($errors->has('vehicle_type')) <i class="errors"></i>&nbsp;{{ $errors->first('vehicle_type') }}</p> @endif

                      </div>
                    </div>
                    <div class="row">
                      <div class="form-group {{ $errors->has('issued_on') ? ' has-error' : '' }} col-md-6">
                          {!! Form::label('issued_on', 'Issued On') !!}&nbsp;<i class="error"><strong>*</strong></i>
                          {!! Form::text('issued_on', @$driver_license->issued_on,array('class' => 'form-control','placeholder' =>'Issued On','id' => 'issued_on','onkeydown' => 'return false;')) !!}

                          <p class="controll-error help-block" id="er_issued_on">@if ($errors->has('issued_on')) <i class="errors"></i>&nbsp;{{ $errors->first('issued_on') }}</p> @endif

                      </div>       
                      <div class="form-group {{ $errors->has('expiry_date') ? ' has-error' : '' }} col-md-6">
                          {!! Form::label('expiry_date', 'Expiry Date') !!}&nbsp;<i class="error"><strong>*</strong></i>
                          {!! Form::text('expiry_date', @$driver_license->expiry_date,array('class' => 'form-control','placeholder' =>'Expiry Date','id' => 'expiry_date','maxlength' => '50')) !!}

                          <p class="controll-error help-block" id="er_expiry_date">@if ($errors->has('expiry_date')) <i class="errors"></i>&nbsp;{{ $errors->first('expiry_date') }}</p> @endif

                      </div>
                    </div>
                   <div class="row">
                     <div class="form-group {{ $errors->has('select_vehicle') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('select_vehicle', 'Select Vehicle') !!}&nbsp;<i class="error"><strong>*</strong></i>
                        <select class="form-control selectpicker" id="select_vehicle" name="select_vehicle" data-show-subtext="true" data-live-search="true">
                            <option value="">Select Vehicle</option>
                            
                            @if(count($vehicleData) > 0)

                              @foreach($vehicleData as $val)
                            
                                <option value="{{$val->id}}" <?php if(@$info->vehicle_id == @$val->id){ echo "selected";} ?>>{{ucfirst(@$val->name)}}</option>
                            
                              @endforeach

                            @endif

                        </select> 
                        <p class="controll-error help-block" id="er_select_vehicle">@if ($errors->has('select_vehicle')) <i class="errors"></i>&nbsp;{{ $errors->first('select_vehicle') }}</p>
                        @endif
                     </div>
                      <div class="form-group {{ $errors->has('country') ? ' has-error' : '' }} col-md-6">
                              {!! Form::label('country', 'Select Country') !!}&nbsp;<i class="error"><strong>*</strong></i>
                              <select class="form-control" id="country" name="country" onchange="select_country()">
                                  <option value="">Select Country</option>
                                  @if(count($country) > 0)
                                    @foreach($country as $key)
                                      <option value="{{$key->id}}" <?php if($key->id == $driverInfo->country_id){ echo "selected";}?>>{{ucfirst($key->country_name)}}</option>
                                    @endforeach
                                    @else
                                  @endif
                              </select> 
                              <p class="controll-error help-block" id="er_country">@if ($errors->has('country')) <i class="errors"></i>&nbsp;{{ $errors->first('country') }}</p>
                              @endif
                           </div>
                    </div>
                    <div class="row">
                       <div class="form-group {{ $errors->has('state') ? ' has-error' : '' }} col-md-6">
                            {!! Form::label('select_state', 'Select State') !!}&nbsp;<i class="error"><strong>*</strong></i>
                            <select class="form-control" id="state" name="state"  onchange="select_state()">
                                <option value="">Select State</option>
                                @if($driverInfo->state_name)
                                  <option value="{{ $driverInfo->state_id }}" selected="selected">{{ ucfirst($driverInfo->state_name) }}</option>
                                @endif  
                            </select> 
                            <p class="controll-error help-block" id="er_state">@if ($errors->has('state')) <i class="errors"></i>&nbsp;{{ $errors->first('state') }}</p>
                            @endif
                         </div>
                     <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }} col-md-6">
                            {!! Form::label('select_city', 'Select City') !!}&nbsp;<i class="error"><strong>*</strong></i>
                            <select class="form-control" id="city" name="city">
                                <option value="">Select City</option>
                                @if($driverInfo->city_name)
                                  <option value="{{ $driverInfo->city_id }}" selected="selected">{{ ucfirst($driverInfo->city_name) }}</option>
                                @endif
                            </select> 
                            <p class="controll-error help-block" id="er_city">@if ($errors->has('city')) <i class="errors"></i>&nbsp;{{ $errors->first('city') }}</p>
                            @endif
                         </div>
                     
                    </div>
                    <div class="row">
                      <div class="form-group {{ $errors->has('license_image') ? ' has-error' : '' }} col-md-4">
                        {!! Form::label('license_image', 'License Image:',['class' => 'control-label']) !!}
                        <input name="license_image" id="license_image" type="file" />
                        <span class="note-txt">Note : License Image Type (jpg, png, jpeg)</span>
                        <p class="controll-error help-block" id="er_license_image">
                           @if ($errors->has('license_image'))<i class="errors"></i>{{ $errors->first('license_image') }}@endif
                        </p>
                     </div>
                     <div class="form-group {{ $errors->has('license_image') ? ' has-error' : '' }} col-md-2">
                      @if(@$driver_license->license_image != "")
                        {!! Html::image(Config::get('constants.LICENSE_IMAGE').$driver_license->license_image,'',['class'=>'img-square','height' => '70','width' => '70'])!!}
                      @else
                        {!! Html::image(Config::get('constants.NO_IMAGE').'no-image.png','',['class'=>'img-square','height' => '70','width' => '70'])!!}
                      @endif  
                     </div>
                     <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }} col-md-6">
                            {!! Form::label('address', 'Address') !!}&nbsp;<i class="error"><strong>*</strong></i>
                            
                             {!! Form::text('address', @$driverInfo->address,array('class' => 'form-control','placeholder' =>'Address','id' => 'address')) !!}

                            <p class="controll-error help-block" id="er_address">@if ($errors->has('address')) <i class="errors"></i>&nbsp;{{ $errors->first('address') }}</p>
                            @endif
                         </div>
                     
                    </div>
                    <div class="row">
                      <div class="form-group {{ $errors->has('profile_image') ? ' has-error' : '' }} col-md-4">
                        {!! Form::label('profile_image', 'Profile Image:',['class' => 'control-label']) !!}
                        <input name="profile_image" id="profile_image" type="file" />
                        <span class="note-txt">Note : Profile Image Type (jpg, png, jpeg)</span>
                        <p class="controll-error help-block" id="er_profile_image">
                           @if ($errors->has('profile_image'))<i class="errors"></i>{{ $errors->first('profile_image') }}@endif
                        </p>
                     </div>
                     <div class="form-group {{ $errors->has('mobile_number') ? ' has-error' : '' }} col-md-2">
                      @if($driverInfo->profile_image != "")
                        {!! Html::image(Config::get('constants.PROFILE_IMAGE').$driverInfo->profile_image,'',['class'=>'img-square','height' => '70','width' => '70',])!!}
                      @else
                        {!! Html::image(Config::get('constants.NO_IMAGE').'no-image.png','',['class'=>'img-square','height' => '70','width' => '70'])!!}
                      @endif  
                     </div>
                     
                    </div>
                  </div>
                  
                
               <!-- /.box-body -->
               <div class="box-footer">
                  <button type="submit" class="btn-flat btn btn-success">Submit</button>
                  <a href="{{url('admin/drivers-list')}}" class="btn-flat btn btn-default">Go Back</a>
               </div>
               {!! Form::close() !!}
            </div>
            <!-- /.box -->
         </div>
         <!--/.col (left) -->
         <!-- right column -->          
      </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>
@stop
@section('script') 
{!! Html::script('public/custom/plugnis/select2/select.min.js') !!}
{!! Html::script('public/custom/plugnis/select2/select.min.js') !!}
{!! Html::script('public/custom/plugnis/bootstrap-date-time-picker/bootstrap_moment.js') !!}
  {!! Html::script('public/custom/plugnis/bootstrap-date-time-picker/bootstrap_datetimepicker.js') !!}
  <script type="text/javascript">
    $('#issued_on').datetimepicker({
          icons: {
            time: 'fa fa-clock-o',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
          },
          widgetPositioning: {
                  horizontal: 'right',
                  vertical: 'bottom'
          },       
          format: 'Y-MM-DD',
          maxDate: null,

      });

      $('#expiry_date').datetimepicker({
          icons: {
            time: 'fa fa-clock-o',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
          },
          widgetPositioning: {
                  horizontal: 'right',
                  vertical: 'bottom'
          },       
          format: 'Y-MM-DD',
          minDate: null,
           useCurrent: false,

      });
        $("#issued_on").on("dp.change", function (e) {
            $('#expiry_date').data("DateTimePicker").minDate(moment(e.date).add(1, 'month'));
        });
  </script>

@stop

