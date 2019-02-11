<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Eco gadi</title>
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
    {!! Html::image('public/uploads/logo/logo.png', '', array('width' => "auto",'height' => 100)) !!}
  </div>

  <h3 class="text-center">{{ @$info->name }}</h3>
  <p class="text-center">{{ @$info->value }}</p>

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
