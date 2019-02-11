@extends('Admin::layout.master')
  
@section('css')
{!! Html::style('public/custom/plugnis/select2/select.min.css') !!}
 {!! Html::style('public/custom/plugnis/bootstrap-date-time-picker/bootstrap-datetimepicker.css') !!}
  {!! Html::style('public/custom/plugnis/bootstrap-date-time-picker/bootstrap-datetimepicker-standalone.css') !!}
  <style type="text/css">
  .dropdown-menu > .active > a, .dropdown-menu > .active > a:focus, .dropdown-menu > .active > a:hover {
        background-color: #00a65a!important;
    }
    .form-control:hover{

        border-color: #00a65a!important;
    }
    .bs-placeholder {
      border: 1px solid lightgray !important;
    } 
  </style>
@endsection
@section('content')
<div class="content-wrapper" style="min-height: 508px;">
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
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    {!! Form::open(array('url' => 'admin/add-vehicle','id'=>"AddVehicleForm", 'name'=>"AddVehicleForm",'files'=>true,'method' => 'POST','class' => '')) !!}
                    <!-- <input type="hidden" name="subCategoryId" id="subCategoryId" value="{{Request::segment(3)}}"> -->
                    <div class="box-body">
                      <!-- test -->
                        <div class="row">
                          <input type="hidden" name="model_id" id="model_id" value="{{@$vehicleInfo->vehicle_model_id}}">
                            <div class="form-group {{ $errors->has('vehicle_identity') ? ' has-error' : '' }} col-md-6">
                                {!! Form::label('vehicle_identity', 'Vehicle Identity') !!}&nbsp;<i class="error"><strong>*</strong></i>
                                {!! Form::text('vehicle_identity', @$vehicleInfo->vehicle_identity,array('class' => 'form-control','placeholder' =>'Vehicle Identity','id' => 'vehicle_identity','maxlength' => '50')) !!}

                                <p class="controll-error help-block" id="er_name">@if ($errors->has('vehicle_identity'))<i class="errors"></i><span style="color:red;">{{ $errors->first('vehicle_identity') }}</span></p> @endif

                            </div>       
                            </div> 
                        <!-- test -->
                        <div class="row">
                          <input type="hidden" name="vehicle_id" id="vehicle_id" value="{{@$vehicleInfo->id}}">
                          <input type="hidden" name="model_id" id="model_id" value="{{@$vehicleInfo->vehicle_model_id}}">
                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }} col-md-6">
                                {!! Form::label('name', 'Name') !!}&nbsp;<i class="error"><strong>*</strong></i>
                                {!! Form::text('name', @$vehicleInfo->name,array('class' => 'form-control','placeholder' =>'Name','id' => 'name','maxlength' => '50')) !!}

                                <p class="controll-error help-block" id="er_name">@if ($errors->has('name')) <i class="errors"></i><span style="color:red;">{{ $errors->first('name') }}</span></p> @endif

                            </div>       
                            <div class="form-group {{ $errors->has('service_type') ? ' has-error' : '' }} col-md-6">
                                {!! Form::label('service_type', 'Service Type') !!}&nbsp;<i class="error"><strong>*</strong></i>
                                <select class="form-control selectpicker" id="service_type" name="service_type" data-show-subtext="true" data-live-search="true">
                                <option value="">Select Service Type</option>
                                @if(count($service_type) > 0)
                                  @foreach($service_type as $value)
                                    <option value="{{$value->id}}" <?php if($value->id == @$vehicleInfo->service_type_id){ echo "selected";} ?>>{{ucfirst($value->title).' - '.$value->no_of_seat.' (Seats)'}}</option>
                                  @endforeach
                                  @else
                                @endif
                            </select>

                                <p class="controll-error help-block" id="er_service_type">@if ($errors->has('service_type')) <i class="errors"></i><span style="color:red;">{{ $errors->first('service_type') }}</span></p> @endif

                            </div>
                        </div>

                        <div class="row">
                         <div class="form-group {{ $errors->has('select_brand') ? ' has-error' : '' }} col-md-6">
                            {!! Form::label('select_brand', 'Brand') !!}&nbsp;<i class="error"><strong>*</strong></i>
                            <select class="form-control selectpicker" id="select_brand" name="select_brand" data-show-subtext="true" data-live-search="true" onchange ="getModel();">
                                <option value="">Select Brand</option>
                                @if(count($brand) > 0)
                                  @foreach($brand as $val)
                                    <option value="{{$val->id}}" <?php if($val->id == @$vehicleInfo->brand_id){ echo "selected";} ?>>{{ucfirst($val->name)}}</option>
                                  @endforeach
                                  @else
                                @endif
                            </select> 
                            <p class="controll-error help-block" id="er_select_brand">@if ($errors->has('select_brand'))<i class="errors"></i><span style="color:red;">{{ $errors->first('select_brand') }}</span></p> @endif
                         </div>
                         <div class="form-group {{ $errors->has('select_model') ? ' has-error' : '' }} col-md-6">
                            {!! Form::label('select_model', 'Select Model') !!}&nbsp;<i class="error"><strong>*</strong></i>
                            <select class="form-control selectpicker" id="select_model" name="select_model" data-show-subtext="true" data-live-search="true">
                                <option value="">Select Model</option>
                            </select>
                            <p class="controll-error help-block" id="er_select_model">@if ($errors->has('select_model')) <i class="errors"></i><span style="color:red;">{{ $errors->first('select_model') }}</span></p> @endif
                         </div>
                    </div>
                   <div class="row">
                       <div class="form-group {{ $errors->has('manufacturer') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('manufacturer', 'Manufacturer:',['class' => 'control-label']) !!}&nbsp;<i class="error"><strong>*</strong></i>
                        
                        {!! Form::text('manufacturer', @$vehicleInfo->manufacturer,array('class' => 'form-control','placeholder' =>'Manufacturer','id' => 'manufacturer','maxlength' => '100')) !!}
                        <p class="controll-error help-block" id="er_manufacturer">
                           @if ($errors->has('manufacturer'))<i class="errors"></i><span style="color:red;">{{ $errors->first('manufacturer') }}</span></p> @endif
                        </p>
                     </div>
                      <div class="form-group {{ $errors->has('vehicle_number') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('vehicle_number', 'Vehicle Number:',['class' => 'control-label']) !!}<i class="error"><strong>*</strong></i>
                        
                        {!! Form::text('vehicle_number', @$vehicleInfo->number_plate,array('class' => 'form-control','placeholder' =>'Vehicle Number','id' => 'vehicle_number','maxlength' => '10')) !!}
                        <p class="controll-error help-block" id="er_vehicle_number">
                           @if ($errors->has('vehicle_number'))<i class="errors"></i><span style="color:red;">{{ $errors->first('vehicle_number') }}</span></p> @endif
                        </p>
                     </div>
                    </div>
                    <div class="row">
                       <div class="form-group {{ $errors->has('insurance_no') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('insurance_no', 'Insurance Number:',['class' => 'control-label']) !!}&nbsp;<i class="error"><strong>*</strong></i>
                        
                        {!! Form::text('insurance_no', @$vehicleInfo->insurance_no,array('class' => 'form-control','placeholder' =>'Insurance Number','id' => 'insurance_no')) !!}
                        <p class="controll-error help-block" id="er_insurance_no">
                           @if ($errors->has('insurance_no'))<i class="errors"></i><span style="color:red;">{{ $errors->first('insurance_no') }}</span></p> @endif
                        </p>
                     </div>
                      <div class="form-group {{ $errors->has('insurance_expiry_date') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('insurance_expiry_date', 'Insurance Expiry Date:',['class' => 'control-label']) !!}<i class="errors"></i><span style="color:red;">{{ $errors->first('insurance_expiry_date') }}</span></p> 
                        
                        {!! Form::text('insurance_expiry_date', @$vehicleInfo->insurance_expiry_date,array('class' => 'form-control','placeholder' =>'Insurance Expiry Date','id' => 'insurance_expiry_date','autocomplete' => 'off','onkeydown'=>'return false')) !!}
                        <p class="controll-error help-block" id="er_insurance_expiry_date">
                           @if ($errors->has('insurance_expiry_date'))<i class="errors"></i><span style="color:red;">{{ $errors->first('insurance_expiry') }}</span></p> @endif
                        </p>
                     </div>
                    </div>
                    <div class="row">
                       <div class="form-group {{ $errors->has('vehicle_image') ? ' has-error' : '' }} col-md-3">
                        {!! Form::label('vehicle_image', 'Vehicle Image:',['class' => 'control-label']) !!}&nbsp;<i class="error"><strong>*</strong></i>
                        <input name="vehicle_image" id="vehicle_image" type="file" />
                        <span class="note-txt">Note : Vehicle Image Type (jpg, png, jpeg)</span>
                        <p class="controll-error help-block" id="er_vehicle_image">
                           @if ($errors->has('vehicle_image'))<i class="errors"></i><span style="color:red;">{{ $errors->first('vehicle_image') }}</span></p> @endif
                        </p>
                     </div>
                     <div class="form-group {{ $errors->has('vehicle_image') ? ' has-error' : '' }} col-md-3">
                         @if(@$vehicleInfo->vehicle_image != '')
                         <?php $file = "uploads/vehicle/".$vehicleInfo->vehicle_image;?>
                            {!! Html::image(Config::get('constants.VEHICLE_IMAGE').$vehicleInfo->vehicle_image,'',['class'=>'img-sqaure','height' => '70px','width' => '70px'])!!}
                         @endif   
                     </div>
                      <div class="form-group {{ $errors->has('luggage_capacity') ? ' has-error' : '' }} col-md-6">
                        {!! Form::label('luggage_capacity', 'Luggage Capacity:',['class' => 'control-label']) !!}&nbsp;<i class="error"><strong>*</strong></i>
                        
                        {!! Form::text('luggage_capacity', @$vehicleInfo->luggage_capacity,array('class' => 'form-control','placeholder' =>'Luggage Capacity','id' => 'luggage_capacity','maxlength' => '150')) !!}
                        <p class="controll-error help-block" id="er_luggage_capacity">
                           @if ($errors->has('luggage_capacity'))<i class="errors"></i><span style="color:red;">{{ $errors->first('luggage_capacity') }}</span></p> @endif
                        </p>
                     </div>
                    </div>
                    <div class="row">
                      
                     <div class="form-group {{ $errors->has('insurance_image') ? ' has-error' : '' }} col-md-3">
                        {!! Form::label('insurance_image', 'Insurance Image:',['class' => 'control-label']) !!}&nbsp;<i class="error"><strong>*</strong></i>
                        <input name="insurance_image" id="insurance_image" type="file" />
                        <span class="note-txt">Note : Insurance Image Type (jpg, png, jpeg)</span>
                        <p class="controll-error help-block" id="er_insurance_image">
                           @if ($errors->has('insurance_image'))<i class="errors"></i><span style="color:red;">{{ $errors->first('insurance_image') }}</span></p> @endif
                        
                     </div>
                     <div class="form-group {{ $errors->has('insurance_image') ? ' has-error' : '' }} col-md-3">
                         @if(@$vehicleInfo->insurance_image != '')
                         <?php $file = "uploads/insurance/".$vehicleInfo->insurance_image;?>
                            {!! Html::image(Config::get('constants.INSURANCE_IMAGE').$vehicleInfo->insurance_image,'',['class'=>'img-sqaure','height' => '70px','width' => '70px'])!!}
                         @endif  
                         </div>
                         
                      <!-- Maximum Passenger -->
                         
                        <div class="form-group col-md-6">
                          {!! Form::label('max_passenger', 'Maximum Passenger') !!}&nbsp;<i class="error"><strong>*</strong></i>
                          {!! Form::text('max_passenger', @$vehicleInfo->max_passenger,array('class' => 'form-control','placeholder' =>'Maximum Passenger','id' => 'max_passenger','maxlength' => '50')) !!}

                          <p class="controll-error help-block" id="max_passenger  ">@if ($errors->has('max_passenger')) <i class="errors"></i><span style="color:red;">{{ $errors->first('max_passenger') }}</span></p> @endif

                            </div>
                            
                        <!-- End Maximum Passenger --> 

                    </div>   
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn-flat btn btn-success">Submit</button>
                        <a href="{{url('admin/vehicle-list')}}" class="btn-flat btn btn-default"><i class="fa fa-arrow-left"></i> Go Back</a>

                    </div>


                    {!! Form::close() !!}
                </div><!-- /.box -->

            </div><!--/.col (left) -->
            <!-- right column -->          
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>
@endsection
@section('script')
{!! Html::script('public/custom/plugnis/select2/select.min.js') !!}
{!! Html::script('public/custom/plugnis/bootstrap-date-time-picker/bootstrap_moment.js') !!}
  {!! Html::script('public/custom/plugnis/bootstrap-date-time-picker/bootstrap_datetimepicker.js') !!} 
<script type="text/javascript">
    function getModel(){

        var barndId = $("#select_brand").val();

        $.ajax({
              url: "{{url('admin/get-model')}}",
              method:'GET',
              data: {'brand_id': barndId},
              dataType: 'JSON',
              headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              success: function(res){
                
                    if (res.success == 1) {

                        var editModelId = $("#model_id").val();
                        
                        var option = '<option value="">Select Model</option>';
                        $.each(res.vehicle_model, function(key,value) {

                          if(editModelId != ""){

                            if(value.id == editModelId){

                              option += '<option value="'+value.id+'" selected = "selected">'+value.name+'</option>';
                            }else{

                                option += '<option value="'+value.id+'">'+value.name+'</option>';
                            }
                            

                          }else{

                            option += '<option value="'+value.id+'">'+value.name+'</option>';
                          }
                        });
                        $("#select_model").html('');
                        $("#select_model").html(option);
                        $("#select_model").selectpicker('refresh');


                    }else{

                        $("#select_model").html('<option value="">Select Model</option>');
                        $("#select_model").selectpicker('refresh');
                    }
                }  
            })
        }
  getModel();      
 $('#insurance_expiry_date').datetimepicker({
          icons: {
            time: 'fa fa-clock-o',
            date: 'fa fa-calendar',
            up: 'fa fa-chevron-up',
            down: 'fa fa-chevron-down',
            previous: 'fa fa-chevron-left',
            next: 'fa fa-chevron-right',
            today: 'fa fa-screenshot',
            clear: 'fa fa-trash',
            close: 'fa fa-remove'
          },
          widgetPositioning: {
                  horizontal: 'right',
                  vertical: 'bottom'
          },       
          format: 'Y-MM-DD',
          //minDate: null,

      });
</script>
@endsection