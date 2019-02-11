@extends('Admin::layout.master')
  
@section('css')
{!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}
@endsection
@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
          Cities List
         <a href="{{url('admin/add-city')}}" class="btn btn-success pull-right" ><i class="fa fa-plus"></i> Add City</a>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

         <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title text-right">Cities List</h3>
               
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <p></p>
              <table id="city-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="removeSortingClass">#</th>
                  <th>Country Name</th>
                  <th>State Name</th>
                  <th>City Name</th>
                  <th class="removeSortingClass">Status</th>
                  <th class="removeSortingClass">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
               
              </table>
                <input type="hidden" name="state_id" id="state_id" value="<?php echo ($state_id != "")?($state_id):('')?>">
                
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

      var state_id = $("#state_id").val();
      
         
         window.datatable = $('#city-table').DataTable({
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
                url : "{{url('admin/city-list-data')}}",
                type : 'GET',
                data :{state_id:state_id}
                
            },
        });

    });
   $("#city-table").on('draw.dt',function(){

      $(".fancybox").fancybox();

   });

   </script>
@endsection