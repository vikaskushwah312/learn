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
        <a href="{{url('admin/add-driver')}}" class="btn btn-success pull-right"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Driver</a>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

         <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Drivers List  </h3>
                <a href="{{URL::to('admin/driver-export')}}" class="btn btn-success pull-right" style="border-color: #ecf0f5;"><i class="fa fa-file-excel-o"></i> Export </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <p></p>
              <table id="table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="removeSortingClass">#</th>
                  <th class="removeSortingClass">Profile Image</th>
                  <th>Name</th>
                  <th class="">Mobile Number</th>
                  <th class="">Email</th>
                  <th class="removeSortingClass">Gender</th>
                  <th class="removeSortingClass">Status</th>
                  <th class="removeSortingClass">Online Status</th>
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
        
        window.datatable = $('#table').DataTable({
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
                url : "{{url('admin/drivers-data')}}",
                type : 'GET'
            },
        });

    });
	</script>
  <script type="text/javascript">
    $("#table").on('draw.dt',function(){

      $(".fancybox").fancybox();

   });
  </script>
@endsection