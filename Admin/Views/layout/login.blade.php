<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Eco gadi</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
   <link rel="icon" href="{{ url('public/uploads/favicon.png')}}" type="image/png">
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
    
    {!! Html::style('public/custom/css/before_login.css')!!}
  <!-- <link rel="stylesheet" href="../../plugins/iCheck/square/blue.css"> -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
 
</head>
<body class="hold-transition login-page-bkg">
<div class="login-box">
  <div class="login-logo">
    {!! Html::image('public/uploads/logo/logo.png', '', ['class' => 'admin_logo']) !!}
  </div>

  <!-- /.login-logo -->
  <div class="login-box-body">
    <h3 class="login-box-msg">Sign in</h3>
      <!--start flash messages-->
    <!--end flash messages-->
     {!! Form::open(array('url' => 'admin/post-login','method' => 'POST','class' => 'form-horizontal login-validation', 'id' => 'loginForm','name' => 'loginForm')) !!}

          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          @if(Session::has('success'))
              <p style="color: green">{{Session::get('success')}}</p>
          @endif
          <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
           {!! Form::text('email', '', ['class'=>'form-control', 'placeholder'=>'Email','id' =>'email', 'maxlength' => '30']) !!}
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

            <p class="controll-error help-block" id="er_email">@if($errors->has('email'))<i class="fa fa-times-circle-o"></i>{{ $errors->first('email') }}@endif</p>
          </div>
          <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
         
            {!! Form::password('password', array('class' => 'form-control', 'id' => 'password', 'placeholder'=>'Password', 'maxlength' => '20')) !!}
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            <p class="controll-error help-block" id="er_password">@if ($errors->has('password')) <i class="fa fa-times-circle-o"></i>{{ $errors->first('password') }} @endif</p>
            @if(Session::has('fail'))
              <span style="color: red">{{Session::get('fail')}}</span>
            @endif
          </div>
          <div class="row">
            <div class="col-xs-8">
               <div class="checkbox icheck">
                <a href="{{ url('admin/forgot-password')}}">Forgot Password</a>
                

              </div> 
               
            </div><!-- /.col -->
            <div class="col-xs-4">
           {!! Form::submit('Sign In',array('name'=> 'submit','class'=>'btn btn-success btn-block btn-flat')) !!}
              
            </div><!-- /.col -->
          </div>
        {!! Form::close() !!}

    <!-- <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div> -->
    <!-- /.social-auth-links -->
    

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<style>

</style>
<!-- jQuery 3 -->
{!! Html::script('public/adminTheme/bower_components/jquery/dist/jquery.min.js')!!}
<!-- Bootstrap 3.3.7 -->
{!! Html::script('public/adminTheme/bower_components/bootstrap/dist/js/bootstrap.min.js')!!}
<!-- iCheck -->
<!-- {!! Html::script('public/adminTheme/plugins/iCheck/icheck.min.js')!!} -->

<!-- //validation js -->
{!! Html::script('public/custom/js/jquery.validate.min.js')!!}
{!! Html::script('public/custom/js/from_validation.js')!!}

<!-- <script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });

  //form valiadtion

</script> -->
</body>
</html>
