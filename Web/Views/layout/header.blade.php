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
      {!!Html::Style('public/webTheme/css/animate.css')!!}
      {!!Html::Style('public/webTheme/css/owl.carousel.min.css')!!}
      {!!Html::Style('public/webTheme/css/owl.theme.default.min.css')!!}
      
      {!!Html::Style('public/custom/css/style.css')!!}
      {!!Html::Style('public/custom/plugnis/sweetalert/sweetalert2.min.css')!!} 
      <link href="https://fonts.googleapis.com/css?family=Baumans" rel="stylesheet">
      {!! Html::script('public/custom/plugnis/sweetalert/sweetalert2.js')!!} 
      <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->

    <script type="text/javascript">
      var SITEURL   = '{{URL::to('')."/"}}';
      const APIURL  = SITEURL+"api/";
      const APPCURRENCY  = '$';
      const JS_DATE_FORMAT = "DD-MM-YYYY @ HH:mm";
      var USER_REDIRECT_URL = "";       
    </script>
   </head>
    @yield('css')
   <body>
      <nav class="navbar navbar-expand-lg navbar-light fixed-top my-header " id="mainNav">
         <div class="container">
            <a class="navbar-brand" href="{{url('/web')}}">{!!Html::image('public/webTheme/images/logo1.png','',['class' => ''])!!}</a>
            <button class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarResponsive" style="">
               <ul class="navbar-nav ml-auto">
                  <li class="nav-item">
                     <a class="nav-link" href="#home">Home</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#services">Services</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#about">About Us</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#contact">Contact us</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="#download">Downloads</a>
                  </li>
                  @if(Session::get('id'))
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="javascript::void(0);" aria-expanded="false" data-animation="scale-up" role="button" style="text-decoration: none;">
                          <i class="fa fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;<span>My Account</span>&nbsp;&nbsp;<i class="fa fa-gears"></i><span class="caret"></span>
                     </a>
                     <ul class="dropdown-menu" role="menu">
                        <li role="presentation">
                          <a href="{{url('/ride-now')}}" role="menuitem"><i class="fa fa-lock" aria-hidden="true" style="text-decoration: none;"></i> Home</a>
                        </li>
                        <li class="divider"></li>
                        <li role="presentation">
                          <a href="{{url('/get-profile')}}" role="menuitem"><i class="fa fa-lock" aria-hidden="true" style="text-decoration: none;"></i> Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li role="presentation">
                          <a href="{{url('/ride-history')}}" role="menuitem"><i class="fa fa-lock" aria-hidden="true" style="text-decoration: none;"></i> History</a>
                        </li>
                        <li class="divider"></li>
                        <li role="presentation">
                          <a href="{{url('/notification')}}" role="menuitem"><i class="fa fa-lock" aria-hidden="true" style="text-decoration: none;"></i> Notifications</a>
                        </li>
                        <li class="divider"></li>
                        <li role="presentation">
                          <a href="{{url('/logout')}}" role="menuitem"><i class="fa fa-lock" aria-hidden="true" style="text-decoration: none;"></i> Logout</a>
                        </li>
                
                     <!-- <a class="nav-link" href="{{url('/logout')}}">Logout</a> -->
                  </li>
                  @else
                  <li class="nav-item">
                     <a class="nav-link" href="{{url('/login')}}">Sign In</a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link" href="{{url('/registration')}}">Sign Up</a>
                  </li>
                  @endif
               </ul>
            </div>
         </div>
      </nav>
      <!-- Page Header -->
      <header class="masthead mysec bg1 image">
         <div class="overlay"></div>
         <div class="container">
            <div class="row">
               <div class="col-lg-8 col-md-10 mx-auto">
                  <div class="site-heading">
                     <h1 class="color-green text-center">fast & safe services</h1>
                     <h6 class="color-white text-center">Lorem ipsum dolor sit amet consectetur adipiscing elit</h6>
                  </div>
               </div>
            </div>
         </div>
         <div class="text-center">
            {!!Html::image('public/webTheme/images/auto.png','',['class' => 'auto-img'])!!}   
         </div>
      </header>

       <div class="container-fluid" id='error_msg_section'>

    <script type="text/javascript">
      
      $(document).ready(function(){
      
        $('body').removeClass('web_loader');
       
      
      });

    </script>
     @if(Session::has('success'))         
        
       <script type="text/javascript">
          swal({
              title:'Success!',
              text:"{{Session::get('success')}}",
              timer:3000,
              type:'success'
          }).then((value) => {
            location.reload();
          }).catch(swal.noop);
      </script>    
      @endif
      
     @if(Session::has('fail'))
     <script type="text/javascript">
        swal({
            title:'Oops!',
            text:"{{Session::get('fail')}}",
            type:'error',
            timer:3000
        }).then((value) => {
          location.reload();
        }).catch(swal.noop);
    </script> 

    <script type="text/javascript">
      $(document).ready(function(){
        $("a").on('click', function(event) {
            if (this.hash !== "") {
            event.preventDefault();
            var hash = this.hash;
              $('html, body').animate({
              scrollTop: $(hash).offset().top
              }, 800, function(){
                  window.location.hash = hash;      });    } // End if  });});
    </script>    
    @endif
</div> 