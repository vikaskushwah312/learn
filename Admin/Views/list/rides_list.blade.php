@extends('Admin::layout.master')

@section('css')
	{!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}
@endsection
@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{@$heading}}
        <a href="{{URL('admin/new-ride')}}" class="btn btn-success pull-right"><i class="fa fa-plus"></i> New Ride</a>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

         <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">{{@$heading}}  </h3>
               <a href="{{URL::to('admin/rides-export')}}" class="btn btn-success pull-right" style="border-color: #ecf0f5;"><i class="fa fa-file-excel-o"></i> Export </a> 
            </div>
            <!-- drop down -->

            <div class="row date_filter mr-10">
                <div class="col-sm-2 ">  
                  <select id="ridestatus" class="form-control" style="margin-top: 10px;margin-left:867px;padding: 5px;font-size:15px;">
                  <option value="">Ride Status</option>
                  <option value="Running">Running</option>
                  <option value="Pending">Pending</option>
                  <option value="Completed">Completed</option>
                  <option value="Cancelled">Cancelled</option>
                </select>                                   
                </div>
                                                       
              </div>
            <!-- drop down -->
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <p></p>
              <table id="ride-list-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="removeSortingClass">#</th>
                  <th class="removeSortingClass">Ride Id</th>
                  <th class="removeSortingClass">Ride By </th>
                  <th class="removeSortingClass">Passenger</th>
                  <th class="removeSortingClass">Driver</th>
                  <th class="removeSortingClass">Pickup Address</th>
                  <th class="removeSortingClass">Dropup Address</th>
                  <th class="removeSortingClass">Ride Status</th>
                  <th class="removeSortingClass">Created At</th>
                  <th class="removeSortingClass">Actions</th> 
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

@stop

@section('script')
{!! Html::script('public/custom/plugnis/fancybox/source/jquery.fancybox.js') !!}
	<script type="text/javascript">
 	 $(document).ready(function() {
        
        window.datatable = $('#ride-list-table').DataTable({
            //"order": [[ 0, "desc" ]],
            "columnDefs": [ {
                  "targets": 'removeSortingClass',
                  "orderable": false,

            } ],
            "aaSorting" : [],
            "pageLength" : 10,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url : "{{url('admin/rides-list-data')}}",
                type : 'GET',
                data:{
              'ridestatus':function(){return $("#ridestatus").val(); },
              
            }
            },
			
        });

    });
	
/*----------------Table dropdown auto alignment------------------*/
	
	/*$(document).ready(function(){
		setTimeout(function(){
		  $(".manage_drop_d").parent().each(function(){
			var tbl_hght = $(this).parents(".dataTables_wrapper").height();
			var tbl_topos = $(this).parents(".dataTables_wrapper").offset().top;
	
			var drp_postop = $(this).offset().top;
			var drp_hght = $(this).find(".dropdown-menu").height();
	
			$(this).find(".dropdown-toggle").click(function(){
		
			  var yoyo = tbl_hght - drp_postop+drp_hght;
			  //alert(tbl_topos-drp_postop +" , "+ drp_hght);
		
			  if(tbl_hght < drp_hght+drp_postop){
				$(".dropdown").addClass("dropup");
			  }else{
				$(".dropdown").removeClass("dropup");
			  }
			 
			});
		  });
		},500);
	});*/
	
/*----------------Table dropdown auto alignment------------------*/
	
	</script>

  <script type="text/javascript">
    $("#ride-list-table").on('draw.dt',function(){

      $(".fancybox").fancybox();

   });
  </script>
  
<script type="text/javascript">

    $('#ridestatus').on('change', function(e){ 
      
      var table = $('#ride-list-table').DataTable();
      table.draw();

  });
    
  </script>
@endsection