@extends('Admin::layout.master')
  
@section('css')
@endsection

@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{@$title}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <!-- left column -->
         <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-success box-solid">
               <div class="box-header with-border">
                  <div class="col-md-8">
                     <h3 class="box-title">{{@$title}}</h3>
                  </div>
               </div>
             
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              
              <table id="content-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="removeSortingClass">#</th>
                  <th>Name</th>
                  <th class="removeSortingClass">Value</th>
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
         
         window.datatable = $('#content-table').DataTable({
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
                url : "{{url('admin/content-list-data')}}",
                type : 'GET'
            },
        });

    });
   
  </script>
@endsection