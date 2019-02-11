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
        <a href="{{url('admin/add-vehicle')}}" class="btn btn-success pull-right"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Vehicle</a>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

         <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">{{@$heading}}  </h3>
                <a href="{{URL::to('admin/vehicle-export')}}" class="btn btn-success pull-right" style="border-color: #ecf0f5;"><i class="fa fa-file-excel-o"></i> Export </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <p></p>
              <table id="vehicle-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="removeSortingClass">#</th>
                  <th class="removeSortingClass">Image</th>
                  <th class="removeSortingClass">Assign To</th>
                  <th class="removeSortingClass">Vehicle Identity</th>
                  <th class="removeSortingClass">Name</th>
                  <th class="removeSortingClass">Brand</th>
                  <th class="removeSortingClass">Model</th>
                  <th class="removeSortingClass">Manufacturer</th>
                  <th class="removeSortingClass">Status</th>
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
        
        window.datatable = $('#vehicle-table').DataTable({
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
                url : "{{url('admin/vehicle-list-data')}}",
                type : 'GET'
            },
        });

    });
	</script>
  <script type="text/javascript">
    $("#vehicle-table").on('draw.dt',function(){

      $(".fancybox").fancybox();

   });
</script>
@endsection