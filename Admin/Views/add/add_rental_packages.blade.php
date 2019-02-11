@extends('Admin::layout.master')
  
@section('css')
  
@endsection

@section('content')
<div class="content-wrapper" style="min-height: 508px;">
    <section class="content-header">
        <h1>Rental Packages</h1>
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
                            <h3 class="box-title">Rental Packages</h3>
                        </div>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                   <form role="form" action="{{ url('admin/add-rental-packages' )}}" id="rental_packages_form" name="rental_packages_form" method="post"><!-- admin/app-info?id='.@$info->id -->
              {!! csrf_field() !!}
              <div class="box-body">
                
                <div class="col-md-6 col-xs-6 col-sm-6">
                
                  <div class="form-group">
                    <label for="Time">Time ({{Config::get('constants.TIME_UNIT')}})</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="time" placeholder="Time" name="time" value="{{ old('time') }}">
                    @if ($errors->has('time'))
                       <p class="controll-error help-block" id="er_country">@if ($errors->has('time')) <i class="errors"></i><span style="color:red;">{{ $errors->first('time') }}</span> @endif</p>
                    @endif
                  </div>
                  
                </div>   

                 
                 <div class="clearfix"></div>
                 <div class="col-md-6 col-xs-6 col-sm-6">
                
                  <div class="form-group">
                    <label for="Distance">Distance ({{Config::get('constants.DISTANCE_UNIT')}})</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="distance" placeholder="Distance" name="distance" value="{{ old('distance') }}">
                    @if ($errors->has('distance'))
                       <p class="controll-error help-block" id="er_distance">@if ($errors->has('distance')) <i class="errors"></i><span style="color:red;">{{ $errors->first('distance') }}</span> @endif</p>
                    @endif
                  </div>
                  
                </div>                
               
                <div class="clearfix"></div>
                
                <div class="col-md-6 col-xs-6 col-sm-6">                
                  <div class="form-group">
                    <label for="charges">Charges({{Config::get('constants.CURRENCY')}})</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="charges" placeholder="Charges" name="charges" value="{{ old('charges') }}">
                    
                    @if ($errors->has('charges'))
                       <p class="controll-error help-block" id="er_charges">@if ($errors->has('charges')) <i class="errors"></i><span style="color:red;">{{ $errors->first('charges') }}</span> @endif</p>
                    @endif
                  </div>                  
                </div>

                </div> <!-- body end -->       
              
              <div class="box-footer" style="margin-left:15px;">
                        <button type="submit" class="btn-flat btn btn-success" >Submit</button>
                        <a href="{{url('admin/rental-packages')}}" class="btn-flat btn btn-default"><i class="fa fa-arrow-left"></i> Go Back</a>

                    </div>
            </form>
            
          </div>
        </div>
      </div>
    </section>
  </div>

@stop
