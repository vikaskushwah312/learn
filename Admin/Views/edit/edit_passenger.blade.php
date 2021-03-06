@extends('Admin::layout.master')
@section('css')

@endsection
@section('content')
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
                  <div class="col-md-8">
                     <h3 class="box-title">{{@$heading}}</h3>
                  </div>
               </div>
               <!-- /.box-header -->
               <!-- form start -->
               {!! Form::open(array('url' => "admin/edit-passenger/$passengerInfo->id",'id'=>"editPassengerForm", 'name'=>" ",'files'=>true,'method' => 'POST','class' => '')) !!}
               <!-- <input type="hidden" name="subCategoryId" id="subCategoryId" value="{{Request::segment(3)}}"> -->
               <div class="box-body">
                  <div class="row">
                      <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('first_name', 'First Name') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        {!! Form::text('first_name', @$passengerInfo->first_name,array('class' => 'form-control','placeholder' =>'First Name','id' => 'first_name','maxlength' => '50')) !!}
                        <p class="controll-error help-block" id="er_first_name">@if ($errors->has('first_name')) </i><span style="color:red;">{{ $errors->first('first_name') }}</span></p> @endif
                     </div>
                     <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('last_name', 'Last Name') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        {!! Form::text('last_name', @$passengerInfo->last_name,array('class' => 'form-control','placeholder' =>'Last Name','id' => 'last_name','maxlength' => '50')) !!}
                        <p class="controll-error help-block" id="er_last_name">@if ($errors->has('last_name')) </i><span style="color:red;">{{ $errors->first('last_name') }}</span></p> @endif
                     </div>
                  </div>
                  <div class="row">
                      <div class="form-group {{ $errors->has('country_code') ? ' has-error' : '' }} col-md-2">
                        {!! Form::label('country_code', 'Country Code') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        {!! Form::text('country_code', @$passengerInfo->country_code,array('class' => 'form-control','placeholder' =>'Country Code','id' => 'country_code','maxlength' => '2')) !!}
                        <p class="controll-error help-block" id="er_country_code">@if ($errors->has('country_code')) </i><span style="color:red;">{{ $errors->first('country_code') }}</span></p> @endif
                     </div>
                     <div class="form-group {{ $errors->has('mobile_number') ? ' has-error' : '' }} col-md-4">
                        {!! Form::label('mobile_number', 'Mobile Number') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        {!! Form::text('mobile_number', @$passengerInfo->mobile_number,array('class' => 'form-control','placeholder' =>'Mobile Number','id' => 'mobile_number','maxlength' => '10')) !!}
                        <p class="controll-error help-block" id="er_mobile_number">@if ($errors->has('mobile_number')) </i><span style="color:red;">{{ $errors->first('mobile_number') }}</span></p> @endif
                     </div>
                     <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::text('email', @$passengerInfo->email,array('class' => 'form-control','placeholder' =>'Email','id' => 'email')) !!}
                        <p class="controll-error help-block" id="er_email">@if ($errors->has('email')) </i><span style="color:red;">{{ $errors->first('email') }}</span></p> @endif
                     </div>
                  </div>
                  <div class="row">
                      <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('Gender', 'Gender') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        <select class="form-control" id="gender" name="gender">
                          <option value="">Select Gender</option>
                          <option value="Male" <?php if($passengerInfo->gender == 'Male'){ echo "selected";}?>>Male</option>
                          <option value="Female" <?php if($passengerInfo->gender == 'Female'){ echo "selected";}?>>Female</option>
                        </select>
                        <p class="controll-error help-block" id="er_gender">@if ($errors->has('gender')) </i><span style="color:red;">{{ $errors->first('gender') }}</span></p> @endif
                     </div>
                     <div class="form-group {{ $errors->has('verification_status') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('verification_status', 'Verification Status') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                        <select class="form-control" id="verification_status" name="verification_status">
                          <option value="">Select Verification Status</option>
                          
                        <option value="Verified" <?php if($passengerInfo->mobile_verification_status == 'Verified'){ echo "selected";}?> >Verified</option>
                          <option value="NotVerified" <?php if($passengerInfo->mobile_verification_status == 'NotVerified'){ echo "selected";}?>>Not Verified</option>
                        </select>
                        <p class="controll-error help-block" id="er_verification_status">@if ($errors->has('verification_status')) <i class="errors"></i>&nbsp;{{ $errors->first('verification_status') }}</p>
                        @endif
                     </div>
                  </div>
                   <div class="row">
                      <div class="form-group {{ $errors->has('profile_image') ? ' has-error' : '' }} col-md-4">
                        {!! Form::label('profile_image', 'Profile Image:',['class' => 'control-label']) !!}
                        <input name="profile_image" id="profile_image" type="file" />
                        <span class="note-txt">Note : Profile Image Type (jpg, png, jpeg)</span>
                        <p class="controll-error help-block" id="er_profile_image">
                           @if ($errors->has('profile_image'))</i><span style="color:red;">{{ $errors->first('profile_image') }}</span></p> @endif
                        </p>
                     </div>
                     <div class="form-group {{ $errors->has('mobile_number') ? ' has-error' : '' }} col-md-2">
                      @if($passengerInfo->profile_image != "")
                        {!! Html::image(Config::get('constants.PROFILE_IMAGE').$passengerInfo->profile_image,'',['class'=>'img-square','height' => '70','width' => '70',])!!}
                      @else
                        {!! Html::image(Config::get('constants.NO_IMAGE').'no-image.png','',['class'=>'img-square','height' => '70','width' => '70'])!!}
                      @endif  
                     </div>
                     
                    </div>
                  </div>
                  
                
               <!-- /.box-body -->
               <div class="box-footer">
                  <button type="submit" class="btn-flat btn btn-success">Submit</button>
                  <a href="{{url('admin/passengers')}}" class="btn-flat btn btn-default">Go Back</a>
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


@stop

