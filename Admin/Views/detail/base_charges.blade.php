@extends('Admin::layout.master')
  
@section('css')
  
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
                   <form role="form" action="{{ url('admin/base-charges?id='.@$charges_info->id )}}" id="charges_info_form" name="charges_info_form" method="post"><!-- admin/app-info?id='.@$info->id -->
              {!! csrf_field() !!}
              <div class="box-body">
                
                <div class="col-md-6 col-xs-6 col-sm-6">
                
                  <div class="form-group">
                    <label for="Base Charges">Base Charges</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="base_charges" placeholder="Base Charges" name="base_charges" value="{{ old('base_charges', @$charges_info->base_charges) }}">
                    @if ($errors->has('base_charges'))
                      <p class="error help-block">{{ $errors->first('base_charges') }}
                      </p>
                    @endif
                  </div>
                  
                </div>
                
                <div class="clearfix"></div>

                <div class="col-md-6 col-xs-6 col-sm-6">                
                  <div class="form-group">
                    <label for="base_charges_for_km">Base Charges For km</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="base_charges_for_km" placeholder="Base Charges For km" name="base_charges_for_km" value="{{ old('base_charges_for_km', @$charges_info->base_charges_for_km) }}">
                    @if ($errors->has('base_charges_for_km'))
                      <p class="error help-block">{{ $errors->first('base_charges_for_km') }}
                      </p>
                    @endif
                  </div>                  
                </div>
                
               
                <div class="clearfix"></div>
                
                <div class="col-md-6 col-xs-6 col-sm-6">                
                  <div class="form-group">
                    <label for="charges_per_km">Charges Per km</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="charges_per_km" placeholder="Charges Per km" name="charges_per_km" value="{{ old('charges_per_km', @$charges_info->charges_per_km)}}">
                    
                    @if ($errors->has('charges_per_km'))
                      <p class="error help-block">{{ $errors->first('charges_per_km') }}
                      </p>
                    @endif
                  </div>                  
                </div>

                

                <div class="clearfix"></div>
                <div class="col-md-6 col-xs-6 col-sm-6">                
                  <div class="form-group">
                    <label for="waiting charges per minute">Waiting Charges Per Min</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="waiting_charges_per_minute" placeholder="waiting charges " name="waiting_charges_per_minute" value="{{ old('waiting_charges_per_minute', @$charges_info->waiting_charges_per_minute)}}">
                    
                    @if ($errors->has('waiting_charges_per_minute'))
                      <p class="error help-block">{{ $errors->first('waiting_charges_per_minute') }}
                      </p>
                    @endif
                  </div>                  
                </div>


                </div>        
              
              <div class="box-footer" style="margin-left:15px;">
                        <button type="submit" class="btn-flat btn btn-success" onclick="">Submit</button>
                        <a href="{{url('admin/dashboard')}}" class="btn-flat btn btn-default"><i class="fa fa-arrow-left"></i> Go Back</a>

                    </div>
            </form>
            
          </div>
        </div>
      </div>
    </section>
  </div>

@stop
