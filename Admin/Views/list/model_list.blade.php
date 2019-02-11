@extends('Admin::layout.master')

@section('css')
  {!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}
  {!! Html::style('public/custom/plugnis/select2/select.min.css') !!}
  <style type="text/css">
  .dropdown-menu > .active > a, .dropdown-menu > .active > a:focus, .dropdown-menu > .active > a:hover {
        background-color: #00a65a!important;
    }
    .form-control:hover{

        border-color: #00a65a!important;
    } 
  </style>
@endsection
@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{@$heading}}
        <a href="javascript:void(0);" class="btn btn-success pull-right" data-toggle="modal" data-target="#vehicleModal" onclick="addModel();"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Model</a>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

         <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">{{@$heading}}  </h3>
               <!-- <a href="{{URL::to('admin/driver-export')}}" class="btn btn-danger pull-right" style="border-color: #ecf0f5;"><i class="fa fa-file-excel-o"></i> Export </a> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <p></p>
              <table id="model-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="removeSortingClass">#</th>
                  <th class="removeSortingClass">Vehicle</th>
                  <th class="removeSortingClass">Brand</th>
                  <th class="removeSortingClass">Date</th>
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

<!-- Brand Modal -->
  <div class="modal fade" id="vehicleModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      {!! Form::open(array('url' => 'admin/add-model','id'=>"AddModelForm", 'name'=>"AddModelForm",'files'=>true,'method' => 'POST','class' => '')) !!}
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Model</h4>
        </div>
        <div class="modal-body">
          <div class="form-group {{ $errors->has('brand_name') ? ' has-error' : '' }}">
              {!! Form::label('brand_name', 'Brand') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
              <select class="form-control selectpicker" id="brand_name" name="brand_name" data-show-subtext="true" data-live-search="true">
                <option value="">Select Brand</option>
                @if(count($brand) > 0)
                  @foreach($brand as $val)
                    <option value="{{$val->id}}">{{ucfirst($val->name)}}</option>
                  @endforeach
                  @else
                @endif
              </select> 

              <p class="controll-error help-block" id="er_brand_name">@if ($errors->has('brand_name')) <i class="errors"></i>&nbsp;{{ $errors->first('brand_name') }}</p> @endif
          
          </div>
          <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
              {!! Form::label('name', 'Name') !!}&nbsp;<i class="error-star"><strong>*</strong></i>
              {!! Form::text('name', '',array('class' => 'form-control','placeholder' =>'Name','id' => 'name','maxlength' => '100')) !!}

              <p class="controll-error help-block" id="er_name">@if ($errors->has('name')) <i class="errors"></i>&nbsp;{{ $errors->first('name') }}</p> @endif
          
          </div> 
          <input type="hidden" name="modelId" id="modelId" value="">       
        </div>
        <div class="modal-footer">
          <button type="supmit" class="btn btn-success">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
  
</div>

@stop

@section('script')
{!! Html::script('public/custom/plugnis/fancybox/source/jquery.fancybox.js') !!}
{!! Html::script('public/custom/plugnis/select2/select.min.js') !!}

  <script type="text/javascript">
   $(document).ready(function() {
        
        window.datatable = $('#model-table').DataTable({
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
                url : "{{url('admin/model-list-data')}}",
                type : 'GET'
            },
        });

    });
  </script>
  <script type="text/javascript">
    $("#model-table").on('draw.dt',function(){

      $(".fancybox").fancybox();

   });

    //close modal
    $("#vehicleModal").on('hidden.bs.modal', function() {
        $(".error").html('');
        $(".help-block").html('');
        $(".form-group").removeClass('has-error');
    });
    function addModel(){

        $(".modal-title").html('Add Brand');
        $("#modelId").val('');
        $("#name").val('');
        $("#brand_name").val('');
    }
    //edit Modal
    function editModel(id){

        if(id != ""){

          $(".modal-title").html('Edit Model');
          $("#vehicleModal").modal('show');

          $.ajax({
              url: "{{url('admin/edit-model')}}",
              method:'GET',
              data: {'id': id},
              dataType: 'JSON',
              headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              success: function(res){
                
                if (res.success == 1) {

                    var getInfo = res.data;
                    $("#name").val(getInfo.name);
                    $("#modelId").val(getInfo.id); 

                    $('#brand_name').val(getInfo.brand_id).trigger('change');

                }else{

                    
                }
              }  
          })
        }else{

            $(".modal-title").html('Add Model');
        }
    }
  </script>
  @if(Request::get('add') == 1)
  <script type="text/javascript">
      $("#vehicleModal").modal('show');
  </script>
  @else
  @endif
@endsection