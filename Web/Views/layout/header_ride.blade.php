<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" href="{{ url('public/uploads/favicon.png')}}" type="image/png">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title>Ecogadi</title>
      <!-- Bootstrap -->
      {!!Html::Style('public/webTheme/css/bootstrap.min.css')!!}
      {!!Html::Style('public/webTheme/css/custom.css')!!}
      {!!Html::Style('public/custom/css/style.css')!!}
      {!!Html::Style('public/custom/plugnis/sweetalert/sweetalert2.min.css')!!} 
      
      {!! Html::script('public/custom/plugnis/sweetalert/sweetalert2.js')!!}
      <!-- {!!Html::Style('public/webTheme/css/animate.css')!!}
      {!!Html::Style('public/webTheme/css/owl.carousel.min.css')!!} -->
      
      <link href="https://fonts.googleapis.com/css?family=Baumans" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
   </head>
   @yield('css')
    <script>
  
    var SITEURL = '{{URL::to('').'/'}}';
    window.setTimeout(function() {
          $("#success-alert").fadeTo(3000, 0).slideUp(100, function(){
              //$(this).close(); 
          });
      }, 
    3000);
  </script>
   <body>
      <!-- Page Header -->
      <div class="container-fluid">
         <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-4 col-xs-12">
            <div class="row menu p-3">
                @include('Web::layout.menu')
                  <!-- <div class="col">
                     <a href="#" class="menu-open">
                     <i class="fa fa-bars fa-set fa-2x" aria-hidden="true" ></i>
                     </a>
                     <nav id="sidebar-wrapper">
                        <ul class="sidebar-nav">
                           <li class="sidebar-brand">
                              <a class="js-scroll-trigger" href=""></a>
                           </li>
                           <li class="sidebar-nav-item">
                              <a class="js-scroll-trigger" href=""> {!!Html::image('/public/webTheme/images/home.png','',['class' => 'mr-4 mb-1'])!!}Home</a>
                           </li>
                           <li class="sidebar-nav-item">
                              <a class="js-scroll-trigger" href="history.html"> {!!Html::image('public/webTheme/images/history.png','',['class' => 'mr-4 mb-1'])!!}History</a>
                           </li>
                           <li class="sidebar-nav-item">
                              <a class="js-scroll-trigger" href="{{url('notification')}}"> {!!Html::image('public/webTheme/images/notification.png','',['class' => 'mr-4 mb-1'])!!}Notifications</a>
                           </li>
                           <li class="sidebar-nav-item">
                              <a class="js-scroll-trigger" href=""> {!!Html::image('public/webTheme/images/logout.png','',['class' => 'mr-4 mb-1'])!!}Logout</a>
                           </li>
                        </ul>
                     </nav>
                  </div> -->
                  <div class="col text-center">
                     <a href = "{{url('/web')}}" style="text-decoration: none;">{!!Html::image('public/webTheme/images/logo1.png','',['class' => 'ride-menu'])!!}</a>
                  </div>
                  <div class="col">
                    <a href="javascript:void(0);" onclick="getProfile();"> {!!Html::image('public/webTheme/images/profile.png','',['class' => 'float-right'])!!}</a>
                  </div>
               </div>
              
       
    