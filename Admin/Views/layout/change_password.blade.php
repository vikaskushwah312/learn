@extends('Admin::layout.master')
  
@section('css')

@endsection
@section('content')
<div class="content-wrapper">
<section class="content-header">
   <h1> Change Password </h1>
</section>
<!-- Main content -->

   <section class="content container-fluid">
      <div class="row">
          <div class="col-md-2">
          </div>
          <div class="col-md-8">
          <!-- Horizontal Form -->
            
          <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {!! Form::open(['url' => 'admin/change-password', 'class' => 'form-horizontal', 'id' => 'changepassword_form','name' => 'changepassword_form']) !!}
              <div class="box-body">
                
                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                  <label for="inputEmail3" class="col-sm-3">Current Password  <span class="error">*</span></label>

                    <div class="col-sm-9">
                    <input class="form-control" id="password" name="password" placeholder="Current Password" type="password">
                     <p class="controll-error help-block" id="er_password">
                   @if ($errors->has('password'))<i class="errors"></i>{{ $errors->first('password') }}@endif</p> 
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('newPassword') ? ' has-error' : '' }}">
                  
                  <label for="inputPassword3" class="col-sm-3">New Password  <span class="error">*</span></label>

                    <div class="col-sm-9">
                      <input class="form-control" id="newPassword" name="newPassword" placeholder="New Password" type="password">
                       <p class="controll-error help-block" id="er_newPassword">
                   @if ($errors->has('newPassword'))<i class="errors"></i>{{ $errors->first('newPassword') }}@endif</p> 
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('confirmPassword') ? ' has-error' : '' }}">
                  <label for="inputPassword3" class="col-sm-3">Confirm Password  <span class="error">*</span></label>
                    <div class="col-sm-9">
                      <input class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" type="password">
                       <p class="controll-error help-block" id="er_confirmPassword">
                   @if ($errors->has('confirmPassword'))<i class="errors"></i>{{ $errors->first('confirmPassword') }}@endif</p> 
                    </div>
                </div>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn-flat btn btn-success pull-right">Submit</button>
              </div>
              <!-- /.box-footer -->
           {!! Form::close() !!}
          </div>
        
        </div>
      </div>
  </section>
</div>
@stop