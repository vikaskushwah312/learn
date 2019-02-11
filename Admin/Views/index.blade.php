@extends('Admin::layout.master')
@section('css')
{!! Html::style('public/custom/css/daterangepicker.css')!!}
{!! Html::style('public/custom/css/morris.css')!!} 
{!! Html::style('public/custom/css/fullcalendar.min.css')!!}
{!! Html::style('public/custom/css/animate.css')!!}

<style>
.card.dashboard_block .media > div {
 margin: 0px 0px 0px;
 padding: 15px 20px;
}

.card.dashboard_block .media > .media-body {
 padding-left: 15px;
}

.card.dashboard_block .media > .media-left {
    font-size: 50px;
    padding-left: 25px;
    padding-right: 25px;
}

.card.dashboard_block .media > .media-left > .fa {
  color: #fff;
  opacity: .5;
  transition: all .3s;
  width: 50px;
  display: inline-block;
  text-align: center;
}
.card.dashboard_block {
  box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1) !important; 
  border-width: 0px;  
  border-radius: 10px;  
  overflow: hidden; 
  margin-bottom: 30px;  
  background: #fff;
}
.card.dashboard_block .media {
  margin-bottom: 0px;
  padding: 0;
  border-width: 0px;
}
.card.dashboard_block .media > .media-body h1 {
  color: #c9dbe4;
}
.card.dashboard_block:hover .media-left > .fa {
  transform: scale(1.3)rotate(10deg);
  text-shadow: 10px 10px 10px rgba(0,0,0,0.5);
}
.bg_yellow {/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#ffb22b+0,ff602b+100 */
background: rgb(255,178,43); /* Old browsers */
background: -moz-linear-gradient(-45deg,  rgba(255,178,43,1) 0%, rgba(255,96,43,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(-45deg,  rgba(255,178,43,1) 0%,rgba(255,96,43,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(135deg,  rgba(255,178,43,1) 0%,rgba(255,96,43,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffb22b', endColorstr='#ff602b',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
}
.bg_red {/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f64141+0,f745c7+100 */
background: rgb(246,65,65); /* Old browsers */
background: -moz-linear-gradient(-45deg,  rgba(246,65,65,1) 0%, rgba(247,69,199,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(-45deg,  rgba(246,65,65,1) 0%,rgba(247,69,199,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(135deg,  rgba(246,65,65,1) 0%,rgba(247,69,199,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f64141', endColorstr='#f745c7',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
}
.bg_prpl {/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#916bf2+0,dc6df2+100 */
background: rgb(145,107,242); /* Old browsers */
background: -moz-linear-gradient(-45deg,  rgba(145,107,242,1) 0%, rgba(220,109,242,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(-45deg,  rgba(145,107,242,1) 0%,rgba(220,109,242,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(135deg,  rgba(145,107,242,1) 0%,rgba(220,109,242,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#916bf2', endColorstr='#dc6df2',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
}
.bg_green {/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#17d660+0,cfe018+100 */
background: rgb(23,214,96); /* Old browsers */
background: -moz-linear-gradient(-45deg,  rgba(23,214,96,1) 0%, rgba(207,224,24,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(-45deg,  rgba(23,214,96,1) 0%,rgba(207,224,24,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(135deg,  rgba(23,214,96,1) 0%,rgba(207,224,24,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#17d660', endColorstr='#cfe018',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
}
.bg_orng {/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#ff761a+0,fc1f1b+100 */
background: rgb(255,118,26); /* Old browsers */
background: -moz-linear-gradient(-45deg,  rgba(255,118,26,1) 0%, rgba(252,31,27,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(-45deg,  rgba(255,118,26,1) 0%,rgba(252,31,27,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(135deg,  rgba(255,118,26,1) 0%,rgba(252,31,27,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff761a', endColorstr='#fc1f1b',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
}
.bg_blue {/* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#5acafe+0,00ffc3+100 */
background: rgb(90,202,254); /* Old browsers */
background: -moz-linear-gradient(-45deg,  rgba(90,202,254,1) 0%, rgba(0,255,195,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(-45deg,  rgba(90,202,254,1) 0%,rgba(0,255,195,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(135deg,  rgba(90,202,254,1) 0%,rgba(0,255,195,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#5acafe', endColorstr='#00ffc3',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
}
.chart_min_height{

    min-height: 480px;
}

.card-title{
  padding-top: 17px;
  padding-left: 13px;
  font-size: 21px;
}

.progress-animated {
  -webkit-animation-duration: 5s;
  -webkit-animation-name: myanimation;
  -webkit-transition: 5s all;
  animation-duration: 5s;
  animation-name: myanimation;
  -o-transition: 5s all;
  transition: 5s all; }

@-webkit-keyframes myanimation {
  from {
    width: 0; } }

@keyframes myanimation {
  from {
    width: 0; } }
  
</style>
@endsection 
@section('content')
  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<section class="content-header">  
  <div class="row page-titles">
    
    <div class="col-md-5 col-8 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">Dashboard</h3>
        
    <p></p>
    </div>
    <div class="col-md-7 col-4 align-self-center">
        <span id="dashboard_loading" ></span>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div id="Reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 30%">
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
            <span></span> <b class="caret"></b>
        </div>    
    </div>

</div>
</section>
<section class="content container-fluid">
<div class="row">
  <a href="{{url('admin/rides-list')}}" style="text-decoration: none;color:black">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card dashboard_block">
            <div class="card-body media">
                <div class="media-left bg_blue"><i class="fa fa-road" aria-hidden="true"></i></div>
                    <div class="media-body">
                        
                        <div id="ride_count_div" class="animated">
                            
                            <h1 id="ride_count_id"></h1>
                        
                        </div>
                     <small>Total Rides</small>
                </div>
            </div>
        </div>
    </div>
    </a>
    <a href="javascript:void(0)" style="text-decoration: none;color:black">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
          <div class="card dashboard_block">
              <div class="card-body media">
                  <div class="media-left bg_yellow"><i class="fa fa-cab" aria-hidden="true"></i><!--<i class="ion ion-stats-bars"></i>--></div>
                      <div class="media-body">
                      <div id="running_rides_count_div">
                           <h1 id="running_rides_count_id"></h1>
                          
                      </div>
                       <small>Running Rides</small>
                  </div>
              </div>
          </div>
    </div>
  </a>
  <a href="{{url('admin/vehicle-list')}}" style="text-decoration: none;color:black">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card dashboard_block">
            <div class="card-body media">
                <div class="media-left bg_orng"><i class="fa fa-cab"></i></div>
                    <div class="media-body">
                    <div id="vehicle_count_div">
                        <h1 id="vehicle_count_id"></h1>
                        
                    </div>
                     <small>Total Vehicles</small>
                </div>
            </div>
        </div>
    </div> 
    </a> 
</div>    
<div class="row" style="margin-top: 18px;">
  <a href="{{url('admin/staff-list')}}" style="text-decoration: none;color:black">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card dashboard_block">
            <div class="card-body media">
                <div class="media-left bg_prpl"><i class="fa fa-user-secret"></i></div>
                    <div class="media-body">
                    <div id="staff_count_div">
                        <h1 id="staff_count_id"></h1>
                        
                    </div>
                     <small>Total Staff</small>
                </div>
            </div>
        </div>
    </div> 
    </a>  
  <a href="{{url('admin/drivers-list')}}" style="text-decoration: none;color:black">
  <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card dashboard_block">
            <div class="card-body media">
                <div class="media-left bg_red"><i class="fa fa-drivers-license"></i></div>
                    <div class="media-body">
                        <div id="driver_count_div">
                            
                            <h1 id="driver_count_id"></h1>
                        </div>
                     <small>Total Drivers</small>
                </div>
            </div>
        </div>
    </div>
  </a>
    <a href="{{url('admin/passengers')}}" style="text-decoration: none;color:black">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card dashboard_block">
            <div class="card-body media">
                <div class="media-left bg_green"><i class="fa fa-users"></i></div>
                    <div class="media-body">
                    <div id="passenger_count_div">
                        <h1 id="passenger_count_id"></h1>
                        
                    </div>
                     <small>Total Passengers</small>
                </div>
            </div>
        </div>
    </div>
    </a>  
</div>

<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-sm-6">
        <div class="card dashboard_block chart_min_height">
            
            <div class="card-body">
                <h4 class="card-title">Rides</h4>
            
                <div id="Rides-chart" ></div>
            </div>
            <p id="Rides-chart_msg" class="text-center"></p>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-sm-6">
        <div class="card dashboard_block chart_min_height">
            
            <div class="card-body">
                <h4 class="card-title">Passengers</h4>
            
                <div id="Passengers-chart" ></div>
            </div>
            <p id="Passengers-chart_msg" class="text-center"></p>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6 col-sm-6">
        <div class="card dashboard_block chart_min_height">
            

            <div class="card-body">
                <h4 class="card-title">Drivers</h4>
            
                <div id="Drivers-chart" ></div>
            </div>
            <p id="Drivers-chart_msg" class="text-center"></p>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-sm-6">
        <div class="card dashboard_block chart_min_height">
            
            <div class="card-body">
                <h4 class="card-title">Staff</h4>
            
                <div id="Staff-chart" ></div>
            </div>
            <p id="Staff-chart_msg" class="text-center"></p>
        </div>
    </div>
</div>

</section>     
	<!-- Content Header (Page header) -->
</div>
@endsection
 @section('script')
{!! Html::script('public/custom/js/raphael-min.js')!!}
{!! Html::script('public/custom/js/morris.min.js')!!} 

{!! Html::script('public/adminTheme/bower_components/moment/moment.js')!!}

{!! Html::script('http://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js')!!}

{!! Html::script('public/adminTheme/plugins/timepicker/bootstrap-timepicker.min.js')!!}

{{--
{!! Html::script('public/custom/js/fullcalendar.min.js')!!}
{!! Html::script('public/custom/js/datetimemoment.js')!!}  --}} 

<script type="text/javascript">  
$( document ).ready(function() {  
  $(function() {

    var start   = moment().subtract(29, 'days');
    var end     = moment();
    
    function cb(start, end, type) {

        $("#dashboard_loading").html('Loading...');
        //$('#'+type+'-chart_msg').html('Loading...');
        $("#ride_count_div").removeClass('animated bounceInRight');
        $("#running_rides_count_div").removeClass('animated bounceInRight');
        $("#passenger_count_div").removeClass('animated bounceInRight');
        $("#driver_count_div").removeClass('animated bounceInRight');
        $("#staff_count_div").removeClass('animated bounceInRight');
        $("#vehicle_count_div").removeClass('animated bounceInRight');

        $("#ride_count_div").addClass('animated bounceOutLeft');
        $("#running_rides_count_div").addClass('animated bounceOutLeft');
        $("#passenger_count_div").addClass('animated bounceOutLeft');
        $("#driver_count_div").addClass('animated bounceOutLeft');
        $("#staff_count_div").addClass('animated bounceOutLeft');
        $("#vehicle_count_div").addClass('animated bounceOutLeft');
      
          var start_date = start.format('YYYY-MM-D');
          var end_date = end.format('YYYY-MM-D');
           
        $.ajax({
            url      : "{{url('admin/dashboard-count')}}",
            method   : "POST",
            dataType : "JSON",
            headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data     : {start_date: start_date, end_date: end_date},
            success  : function(res){                                           
              
                $('#Reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

                $("#dashboard_loading").html('');
               
                //Show totals
                show_count_dashboard('ride_count', res.rides);
                show_count_dashboard('running_rides_count', res.running_rides);
                show_count_dashboard('passenger_count', res.passengers);
                show_count_dashboard('driver_count', res.drivers);
                show_count_dashboard('staff_count', res.staff);
                show_count_dashboard('vehicle_count', res.vehicles);
          	               
                //Create graph
                check_data_count('Passengers', res.passengers_data);
                check_data_count('Drivers', res.drivers_data);
                check_data_count('Staff', res.staff_data);
                check_data_count('Rides', res.rides_data);         
            }
        });
    }

    function check_data_count(type, result){
        
        $('#'+type+'-chart').html('');
            
        $('#'+type+'-chart_msg').html('');
      
        if (result.length > 0) {
            
            getChart(type, result);
                                            
        }else{

            $('#'+type+'-chart_msg').html('Statistics not available.');

        }        
    }

    function show_count_dashboard(id, value){

       // $("#customer_count_div").removedClass('animated rotateIn');
        $("#"+id+"_div").removeClass('animated bounceOutLeft');
        $("#"+id+"_div").addClass('animated bounceInRight');

        $("#"+id+"_id").html(value);
    }

    function getChart(element, data) {
       
        if (element == 'Rides') {
            
            var gridColor = '#eef0f2';
            var lineColor = '#2DE5E0';
        
        }else if(element == 'Categories'){

            var gridColor = '#eef0f2';
            var lineColor = '#FF7C2B';

        }else if(element == 'Passengers'){

            var gridColor = '#eef0f2';
            var lineColor = '#A46CF2';

        }else if(element == 'Drivers'){

            var gridColor = '#eef0f2';
            var lineColor = '#F74385';

        }else if(element == 'Products'){

            var gridColor = '#eef0f2';
            var lineColor = '#6EDB3E';

        }else{

            var gridColor = '#eef0f2';
            var lineColor = '#c12655';

        }
		
		if(element == 'Wishlist'){
          
          var name = element+'-chart';  

            $("#name").html();

            Morris.Donut({
                element: name,
                data: data,
                backgroundColor: '#ccc',
                labelColor: '#060',
                colors: [
                  '#2DE5E0',
                  '#c12655',
                  '#67C69D',
                  '#A46CF2',
                  '0BA462'
                ],
  				formatter: function (x) { return x + "%"}
            }); 
        }else{

            var name = element+"-chart";

            var line = new Morris.Line({
              element: name,
              resize: true,
              data: data,
              xkey: 'date',
              ykeys: [element],
              labels: [element],
              gridLineColor: gridColor,
              lineColors: [lineColor],
              lineWidth: 1,
              hideHover: 'auto',
              parseTime: false
            });
        }

    }

    function chart_select_date(id, type){

        cb(start, end, type, type);

        $('#'+id).daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: 'YYYY/MM/DD'
            },
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, function(start, end) {

            cb(start, end, type);
        });

    }

    chart_select_date('Reportrange', 'Rides');   
    
    
});
});
</script>

 @endsection