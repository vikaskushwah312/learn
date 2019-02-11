<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Eco gadi | Forgot Password</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  {!! Html::style('public/adminTheme/bower_components/bootstrap/dist/css/bootstrap.min.css')!!}
  
  <!-- Font Awesome -->
  {!! Html::style('public/adminTheme/bower_components/font-awesome/css/font-awesome.min.css')!!}
  <!-- Ionicons -->
  {!! Html::style('public/adminTheme/bower_components/Ionicons/css/ionicons.min.css')!!}
  <!-- Theme style -->
  {!! Html::style('public/adminTheme/dist/css/AdminLTE.min.css')!!}
  <!-- iCheck -->
   <!-- {!! Html::style('public/adminTheme/plugins/iCheck/square/blue.css')!!} -->

   <!-- valiadtion css -->
   {!! Html::style('public/custom/css/jquery-validation.css')!!}

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  {!! Html::style('public/custom/css/before_login.css')!!}
</head>
<body class="hold-transition login-page-bkg">
<div class="login-box">
  <div class="login-logo">
      
    {!! Html::image('public/uploads/logo/logo.png', '', ['class' => 'admin_logo']) !!}
  </div>

  <!-- /.login-logo -->
  <div class="login-box-body">
    <h3 class="login-box-msg">Forgot Password</h3>
      <!--start flash messages-->
    <!--end flash messages-->
     {!! Form::open(array('url' => 'admin/forgot-password','method' => 'POST','class' => 'form-horizontal login-validation', 'id' => 'forgotPassForm','name' => 'forgotPassForm')) !!}

          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          
          <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
           {!! Form::text('email', '', ['class'=>'form-control', 'placeholder'=>'Email','id' =>'email', 'maxlength' => '30']) !!}
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

            <p class="controll-error help-block" id="er_email">@if($errors->has('email'))<i class="fa fa-times-circle-o"></i>{{ $errors->first('email') }}@endif</p>
            @if(Session::has('fail'))
              <span style="color: red">{{Session::get('fail')}}</span>
            @endif
          </div>
          <div class="row">
            <div class="col-xs-8">
            </div>
            <div class="col-xs-4">
           {!! Form::submit('Submit',array('name'=> 'submit','class'=>'btn btn-success btn-block btn-flat')) !!}
              
            </div><!-- /.col -->
          </div>
        {!! Form::close() !!}

   

  </div>
 
</div>




<!-- jQuery 3 -->
{!! Html::script('public/adminTheme/bower_components/jquery/dist/jquery.min.js')!!}
<!-- Bootstrap 3.3.7 -->
{!! Html::script('public/adminTheme/bower_components/bootstrap/dist/js/bootstrap.min.js')!!}
<!-- iCheck -->

<!-- //validation js -->
{!! Html::script('public/custom/js/jquery.validate.min.js')!!}
{!! Html::script('public/custom/js/from_validation.js')!!}
</body>
</html>
