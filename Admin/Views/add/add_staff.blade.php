@extends('Admin::layout.master')
  
@section('css')

@endsection
@section('content')
<div class="content-wrapper" style="min-height: 508px;">
    <section class="content-header">
        <h1>{{@$title}}</h1>
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
                            <h3 class="box-title">{{@$title}}</h3>
                        </div>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    {!! Form::open(array('url' => 'admin/add-staff','id'=>"AddStaffForm", 'name'=>"AddStaffForm",'files'=>true,'method' => 'POST','class' => '')) !!}
                    <!-- <input type="hidden" name="subCategoryId" id="subCategoryId" value="{{Request::segment(3)}}"> -->
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }} col-md-6">
                                {!! Form::label('first_name', 'First Name') !!}&nbsp;<i class="error"><strong>*</strong></i>
                                {!! Form::text('first_name', '',array('class' => 'form-control','placeholder' =>'First Name','id' => 'first_name','maxlength' => '50')) !!}

                                <p class="controll-error help-block" id="er_first_name">@if ($errors->has('first_name')) <i class="errors"></i><span style="color:red;">{{ $errors->first('first_name') }}</span>@endif</p> 

                            </div>       
                            <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }} col-md-6">
                                {!! Form::label('last_name', 'Last Name') !!}&nbsp;<i class="error"><strong>*</strong></i>
                                {!! Form::text('last_name', '',array('class' => 'form-control','placeholder' =>'Last Name','id' => 'last_name','maxlength' => '50')) !!}

                                <p class="controll-error help-block" id="er_last_name">@if ($errors->has('last_name')) <i class="errors"></i><span style="color:red;">{{ $errors->first('last_name') }}</span></p> @endif

                            </div>
                        </div>
                        <div class="row">
                      <div class="form-group {{ $errors->has('country_code') ? ' has-error' : '' }} col-md-2">
                        {!! Form::label('country_code', 'Country Code') !!}&nbsp;<i class="error"><strong>*</strong></i>
                        {!! Form::text('country_code', '91',array('class' => 'form-control','placeholder' =>'Country Code','id' => 'country_code','maxlength' => '2')) !!}
                        <p class="controll-error help-block" id="er_country_code">@if ($errors->has('country_code')) <i class="errors"></i><span style="color:red;">{{ $errors->first('country_code') }}</span></p> @endif
                     </div>
                     <div class="form-group {{ $errors->has('mobile_number') ? ' has-error' : '' }} col-md-4">
                        {!! Form::label('mobile_number', 'Mobile Number') !!}&nbsp;<i class="error"><strong>*</strong></i>
                        {!! Form::text('mobile_number', '',array('class' => 'form-control','placeholder' =>'Mobile Number','id' => 'mobile_number','maxlength' => '10','minlength' => '10')) !!}
                        <p class="controll-error help-block" id="er_mobile_number">@if ($errors->has('mobile_number')) <i class="errors"></i><span style="color:red;">{{ $errors->first('mobile_number') }}</span></p> @endif
                     </div>
                     <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('email', 'Email') !!}&nbsp;<i class="error"><strong>*</strong></i>
                        {!! Form::text('email', '',array('class' => 'form-control','placeholder' =>'Email','id' => 'email')) !!}
                        <p class="controll-error help-block" id="er_email">@if ($errors->has('email')) <i class="errors"></i><span style="color:red;">{{ $errors->first('email') }}</span></p> @endif
                     </div>
                  </div>
                   <div class="row">
                     <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('password', 'Password') !!}&nbsp;<i class="error"><strong>*</strong></i>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password"> 
                        <p class="controll-error help-block" id="er_password">@if ($errors->has('password')) <i class="errors"></i><span style="color:red;">{{ $errors->first('password') }}</span></p> @endif
                     </div>
                      <div class="form-group {{ $errors->has('gender') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('gender', 'Gender:',['class' => 'control-label']) !!}&nbsp;<i class="error"><strong>*</strong></i>
                        <select class="form-control" id="gender" name="gender">
                          <option value="">Select Gender</option>
                            <option value="male" @if(old('gender')=='male'){{'selected'}}@endif>Male</option>
                            <option value="female" @if(old('gender')=='female') {{'selected'}} @endif >Female</option>
                        </select>
                        
                        <p class="controll-error help-block" id="er_gender">
                           @if ($errors->has('gender'))<i class="errors"></i><span style="color:red;">{{ $errors->first('gender') }}</span></p> @endif
                        </p>
                     </div>
                    </div>
                    <div class="row">
                      <div class="form-group {{ $errors->has('profile_image') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('profile_image', 'Profile Image:',['class' => 'control-label']) !!}&nbsp;<i class="error"></i>
                        <input name="profile_image" id="profile_image" type="file" />
                        <span class="note-txt">Note : Profile Image Type (jpg, png, jpeg)</span>
                        <p class="controll-error help-block" id="er_profile_image">
                           @if ($errors->has('profile_image'))<i class="errors"></i><span style="color:red;">{{ $errors->first('profile_image') }}</span></p> @endif
                        </p>
                     </div>
                    </div>   
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn-flat btn btn-success">Submit</button>
                        <a href="{{url('admin/staff-list')}}" class="btn-flat btn btn-default"><i class="fa fa-arrow-left"></i> Go Back</a>

                    </div>


                    {!! Form::close() !!}
                </div><!-- /.box -->

            </div><!--/.col (left) -->
            <!-- right column -->          
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>
@stop
@section('script') 
@stop