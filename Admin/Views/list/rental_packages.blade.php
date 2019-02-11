@extends('Admin::layout.master')

@section('css')
	{!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}
@endsection
@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{$title}}
        <a href="{{url('admin/add-rental-packages')}}" class="btn btn-success pull-right"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Rental Packages</a>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

         <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">{{$title}}</h3>
                <!-- <a href="{{URL::to('admin/promocode-export')}}" class="btn btn-success pull-right" style="border-color:#ecf0f5;"><i class="fa fa-file-excel-o"></i> Export</a> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <p></p>
              <table id="rentalpackages-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="removeSortingClass">#</th>
                  <th class="removeSortingClass">Time({{Config::get('constants.CURRENCY')}})</th>
                  <th class="removeSortingClass">Distance({{Config::get('constants.DISTANCE_UNIT')}})</th>
                  <th class="removeSortingClass">Charges</th>
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
        
        window.datatable = $('#rentalpackages-table').DataTable({
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
                url : "{{url('admin/rentalpackages-data')}}",
                type : 'GET'
            },
        });

    });
	</script>
  
  <script type="text/javascript">
    $("#promocode-table").on('draw.dt',function(){

      $(".fancybox").fancybox();

   });

    /*function editPromocode(id){

        if(id != ""){

          $(".modal-title").html('Edit Promocode');
          $("#brandModal").modal('show');

          $.ajax({
              url: "{{url('admin/edit-brand')}}",
              method:'GET',
              data: {'id': id},
              dataType: 'JSON',
              headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              success: function(res){
                
                if (res.success == 1) {

                    var getInfo = res.data;
                    $("#name").val(getInfo.name);
                    $("#brandId").val(getInfo.id);              
                }else{

                    
                }
              }  
          })
        }else{

            $(".modal-title").html('Add Brand');
        }
    }*/
  </script>



  
@endsection