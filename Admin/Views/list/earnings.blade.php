@extends('Admin::layout.master')

@section('css')
  {!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}
  {!! Html::style('public/custom/plugnis/bootstrap-date-time-picker/bootstrap-datetimepicker.css') !!}
  {!! Html::style('public/custom/plugnis/bootstrap-date-time-picker/bootstrap-datetimepicker-standalone.css') !!}
@endsection

@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{@$heading}}

        <!-- <a href="{{url('admin/add-promocode')}}" class="btn btn-success pull-right"><i class="fa fa-plus"></i>&nbsp;&nbsp;{{@$heading}}</a> -->
      </h1>
    </section>    

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">{{$title}}  </h3>                
            </div>      
              <div class="row date_filter mr-10">
                <div class="col-sm-3 pull-right">  
                  <label for="start_date">End Date</label>
                  <input type="text" id="end_date" name="end_date" onkeydown=" return false" class="form-control" placeholder="End Date" autocomplete="off">
                                  
                </div>
                <div class="col-sm-3 pull-right">                  
                  <label for="end_date">Start Date</label>
                  <input type="text" id="start_date" class="form-control" name="start_date" placeholder="Start Date" autocomplete="off">
                  
                </div>                                         
              </div>
            <div class="box-body table-responsive">
              

              <table id="earnings-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="removeSortingClass">#</th>
                  <th class="removeSortingClass">Driver Name</th>
                  <th class="removeSortingClass">Completed Rides</th>
                  <th class="removeSortingClass">Total Rides</th>
                  <th class="removeSortingClass">Earnings</th>
                                 
                </tr>
                </thead>
                <tbody>
                </tbody>
               
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <style>
  .mr-10{
	  margin-top:10px;
	  margin-right:-5px;
	  
	  }
  </style>

@stop

@section('script')

{!! Html::script('public/custom/plugnis/fancybox/source/jquery.fancybox.js') !!}

{!! Html::script('public/custom/plugnis/bootstrap-date-time-picker/bootstrap_moment.js') !!}

{!! Html::script('public/custom/plugnis/bootstrap-date-time-picker/bootstrap_datetimepicker.js') !!}


  <script type="text/javascript">

    $(document).ready(function() {

      window.datatable = $('#earnings-table').DataTable({
        //"order": [[ 0, "desc" ]],
        "columnDefs": [ {
              "targets": 'removeSortingClass',
              "orderable": false,
        } ],
        "aaSorting" : [],
        "pageLength":10,
        "processing":true,
        "serverSide":true,
        "ajax": {
            url : "{{url('admin/earnings-data')}}",
            type : 'GET',
            data:{
              'start_date':function(){return $("#start_date").val(); },
              'end_date': function(){return $("#end_date").val(); }              
            }                     
        },

      });
                
      //Date picker
      $('#start_date').datetimepicker({
          //uiLibrary: 'bootstrap'
          format: 'DD-MM-Y',
          useCurrent:false,          
          //forceParse: false,
           //clearBtn: true,       
      });

      $('#end_date').datetimepicker({
                
          format: 'DD-MM-Y',       
          useCurrent:false,
           
      });

      $("#start_date").on("dp.change", function (e) {
            $('#end_date').data("DateTimePicker").minDate(e.date);
        });
      
  });

  </script>
  
  
  <script type="text/javascript">
    $("#earnings-table").on('draw.dt',function(){

      $(".fancybox").fancybox();

   });
    
  $('#start_date, #end_date').on('dp.change', function(e){ 
  
    var table = $('#earnings-table').DataTable();
    table.draw();

  });           

</script>



  
@endsection