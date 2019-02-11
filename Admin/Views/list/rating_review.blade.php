@extends('Admin::layout.master')

@section('css')
	
@endsection
@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{$title}}
        <!-- <a href="javascript:void(0);" class="btn btn-success pull-right" data-toggle="modal" data-target="#brandModal" onclick="addBrand();"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Brand</a> -->
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

         <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">{{$title}}  </h3>
               <!-- <a href="{{URL::to('admin/driver-export')}}" class="btn btn-danger pull-right" style="border-color: #ecf0f5;"><i class="fa fa-file-excel-o"></i> Export </a> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <p></p>
              <table id="renting_review" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="removeSortingClass">#</th>
                  <th class="removeSortingClass">Ride Id</th>
                  <th class="removeSortingClass">Driver Name</th>
                  <th class="removeSortingClass">Passenger Name</th>
                  <th class="removeSortingClass">Rating</th>
                  <th class="removeSortingClass">Feedback</th>
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

</div>

@stop

@section('script')

	<script type="text/javascript">
 	 $(document).ready(function() {
        
        window.datatable = $('#renting_review').DataTable({
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
                url : "{{url('admin/rating-review-data')}}",
                type : 'GET'
            },
        });

    });
	</script>
  
 
@endsection