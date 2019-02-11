@extends('Admin::layout.master')
  
@section('css')
{!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}
@endsection
@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       States List
         <a href="{{url('admin/add-state')}}" class="btn btn-success pull-right" ><i class="fa fa-plus"></i> Add State</a>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

         <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title text-right">States List</h3>
               
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <p></p>
              <table id="country-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="removeSortingClass">#</th>
                  <th>Country Name</th>
                   <th>State Name</th>
                  <th class="removeSortingClass">Status</th>
                  <th class="removeSortingClass">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
               
              </table>
              <input type="hidden" name="country_id" id="country_id" value="<?php echo ($country_id !="")?($country_id):('') ?>">

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
         var country_id = $('#country_id').val(); 

         window.datatable = $('#country-table').DataTable({
            
            "columnDefs": [ {
                  "targets": 'removeSortingClass',
                  "orderable": false,


            } ],
            "aaSorting" : [],
            "pageLength" : 10,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url : "{{url('admin/state-list-data')}}",
                type : 'GET',
                data :{country_id:country_id}
            },
        });

    });
   $("#user-table").on('draw.dt',function(){

      $(".fancybox").fancybox();

   });
    </script>
@endsection