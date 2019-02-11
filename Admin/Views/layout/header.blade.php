<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ @$title }} | {{ Config::get('constants.PROJECT_TITLE') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="icon" href="{{ url('public/uploads/favicon.png')}}" type="image/png">

  {!! Html::style('public/adminTheme/bower_components/bootstrap/dist/css/bootstrap.min.css') !!}
  
  <!-- Font Awesome -->
  {!! Html::style('public/adminTheme/bower_components/font-awesome/css/font-awesome.min.css') !!}
  
  <!-- Ionicons -->
  {!! Html::style('public/adminTheme/bower_components/Ionicons/css/ionicons.min.css') !!}

  <!-- Theme style -->
  {!! Html::style('public/adminTheme/dist/css/AdminLTE.min.css') !!}
  
  {!! Html::style('public/adminTheme/dist/css/skins/skin-green.min.css') !!}

  {!! Html::style('public/custom/css/style.css')!!}

  {!! Html::style('public/custom/plugnis/sweetalert/sweetalert2.min.css')!!}
  
      
  {!! Html::script('public/custom/plugnis/sweetalert/sweetalert2.js')!!}  

  <!-- Data tables -->
  {!! Html::style('public/adminTheme/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')!!}
  
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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

</head>

<body class="hold-transition skin-green sidebar-mini">
  
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="{{url('admin/dashboard')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">{!! Html::image('public/uploads/logo/admin_logo.png', '', ['class' => 'admin_mini_logo']) !!}</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">
        {!! Html::image('public/uploads/logo/admin_logo.png', '', ['class' => 'admin_logo']) !!}
      </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- /.messages-menu -->

          <!-- Notifications Menu -->
          <li class="dropdown notifications-menu">
            <!-- Menu toggle button -->
            <a href="{{url('admin/rides-list')}}">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning" id="admin_notification_badge">0</span>
            </a>
          </li>
          <!-- Tasks Menu -->
          
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                       <i class="fa fa-user"></i>&nbsp;<span>Welcome Admin</span>&nbsp;&nbsp;<i class="fa fa-gears"></i>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li role="presentation">
                           <a href="{{url('admin/change-password')}}" tabindex="-1" role="menuitem"><i class="fa fa-exchange green" style="color: green;"></i>&nbsp;Change Password</a>
                        </li>
                        <li><a href="{{url('admin/logout')}}"><i class="fa fa-sign-out red" style="color: red;"></i>&nbsp;Logout</a></li>
                    </ul>
              </li>                        
          </li>
          </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Menu</li>
        <!-- Optionally, you can add icons to the links -->
        <li class="<?php if(in_array(Request::segment(2), ['dashboard'])){ echo "active"; }?>"><a href="{{url('admin/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        
        <li class="<?php if(in_array(Request::segment(2), ['passengers','passenger-detail','edit-passenger','add-passenger'])){ echo "active"; }?>"><a href="{{url('admin/passengers')}}"><i class="fa fa-users"></i> <span>Passengers</span></a></li>

        <li class="<?php if(in_array(Request::segment(2), ['drivers-list','add-driver','edit-driver','driver-detail'])){ echo "active"; }?>"><a href="{{url('admin/drivers-list')}}"><i class="fa fa-users"></i> <span>Drivers</span></a></li>

        <li class="<?php if(in_array(Request::segment(2), ['staff-list','add-staff','edit-staff','staff-detail'])){ echo "active"; }?>"><a href="{{url('admin/staff-list')}}"><i class="fa fa-users"></i> <span>Staff</span></a></li>

        <li class="<?php if(in_array(Request::segment(2), ['rides-list'])){ echo "active"; }?>"><a href="{{url('admin/rides-list')}}"><i class="fa fa-car"></i> <span>Rides</span></a></li>


        <li class="<?php if(in_array(Request::segment(2), ['vehicle-list'])){ echo "active"; }?>"><a href="{{url('admin/vehicle-list')}}"><i class="fa fa-cab"></i> <span>Vehicles</span></a></li>

        <li class="<?php if(in_array(Request::segment(2), ['notifications'])){ echo "active"; }?>"><a href="{{url('admin/notifications')}}"><i class="fa fa-bell"></i> <span>Notifications</span></a></li>

        <li class="treeview <?php if(in_array(Request::segment(2), ['live-tracking-drivers', 'live-tracking-rides'])){ echo "active"; }?>">
        <a href="#">
          <i class="fa fa-map-marker"></i>
          <span>Live Tracking</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{url('admin/live-tracking-drivers')}}" class="<?php if(in_array(Request::segment(2),array('live-tracking-drivers'))) { echo 'active'; } ?>"><i class="fa fa-user"></i>Drivers</a></li>
          <li><a href="{{url('admin/live-tracking-rides')}}" class="<?php if(in_array(Request::segment(2),array('live-tracking-rides'))) { echo 'active'; } ?>"><i class="fa fa-cab"></i>Rides</a></li>
          
        </ul>
      </li>

        <li class="<?php if(in_array(Request::segment(2), ['earnings'])){ echo "active"; }?>"><a href="{{url('admin/earnings')}}"><i class="fa fa-money"></i> <span>Earnings</span></a></li>

        <li class="<?php if(in_array(Request::segment(2), ['rating-review'])){ echo "active"; }?>"><a href="{{url('admin/rating-review')}}"><i class="fa fa-star"></i> <span>Rating & Review</span></a></li>

        <li class="treeview <?php if(in_array(Request::segment(2),array('app-info','base-charges','country-list','country-list-data','city-list','city-list-data','add-city','add-country','promocode','add-promocode','add-country','edit-country','state-list','state-list-data','add-state','edit-state','promocode-data','edit-promocode','brand-list','brand-list-data','add-brand','edit-brand','configuration','contents','model-list','cancel-reasons','edit-content','add-reason','edit-reason','rentail-packages','rental-packages'))) { echo 'active'; } ?>">
          <a href="#"><i class="fa fa-gears"></i><span>Settings</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>              
            </span>
          </a>
            <ul class="treeview-menu">
              <li><a href="{{url('admin/promocode')}}"><i class="fa fa-map-pin"><!-- <img src="{{URL::asset('public/custom/images/promocode.jpeg')}} " alt="no"> --></i> Promo codes</a></li>
              <li><a href="{{url('admin/brand-list')}}"><i class="fa fa-cab"></i> Brands</a></li>
              <li><a href="{{url('admin/model-list')}}"><i class="fa fa-cube"></i> Vehicle Models</a></li>

              <!-- App Info start here -->
              
              <li class="<?php if(in_array(Request::segment(2),array('settings','app-info'))) { echo 'active'; } ?>"><a href="{{url('admin/app-info')}}"><i class="fa fa-info"></i> App Info</a></li>
                            
              <!-- App Info End  here -->

              <!-- Base Charges  -->

                <li class="<?php if(in_array(Request::segment(2),array('settings','base-charges'))) { echo 'active'; } ?>"><a href="{{url('admin/base-charges')}}"><i class="fa fa-money"></i> Rate Management</a></li>
                <!--rentail Packages system -->
                <li class="<?php if(in_array(Request::segment(2),array('settings','rental-packages'))) { echo 'active'; } ?>"><a href="{{url('admin/rental-packages')}}"><i class="fa fa-clock-o  
"></i> Rental Packages</a></li>
                             
              <!--countries Management start here  -->
                <li class="<?php if(in_array(Request::segment(2),array('settings','country-list'))) { echo 'active'; } ?>"><a href="{{url('admin/country-list')}}"><i class="fa fa-list"></i> Country Management</a></li>              
              
              <!--State Management start -->
              <li class="<?php if(in_array(Request::segment(2),array('settings','state-list'))) { echo 'active'; } ?>"><a href="{{url('admin/state-list')}}"><i class="fa fa-list"></i> States Management</a></li>

              <!--City Management start -->
              <li class="<?php if(in_array(Request::segment(2),array('settings','city-list'))) { echo 'active'; } ?>"><a href="{{url('admin/city-list')}}"><i class="fa fa-list"></i> City Management</a></li>

              <li class="<?php if(in_array(Request::segment(2),array('settings','cancel-reasons`'))) { echo 'active'; } ?>"><a href="{{url('admin/cancel-reasons')}}"><i class="fa fa-list"></i>Cancel Reasons</a></li>

              <!--Content Management  -->
              <li class="<?php if(in_array(Request::segment(2),array('settings','contents`'))) { echo 'active'; } ?>"><a href="{{url('admin/contents')}}"><i class="fa fa-list"></i>Content Management</a></li>

              <li class="<?php if(in_array(Request::segment(2),array('settings','configuration'))) { echo 'active'; } ?>"><a href="{{url('admin/configuration')}}"><i class="fa fa-cog"></i>Configurations</a></li>

              

              
              
            </ul>          
        </li><!-- Setting -->
        

      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
  <div class="container-fluid" id='error_msg_section'>

    @if(Session::has('success'))

       <script type="text/javascript">
          swal({
              title:'Success!',
              text:"{{Session::get('success')}}",
              timer:2000,
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
            timer:2000
        }).then((value) => {
          location.reload();
        }).catch(swal.noop);
      </script>     
    
    @endif
</div>