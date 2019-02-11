@extends('Admin::layout.master')
  
@section('css')
{!! Html::style('public/adminTheme/plugins/iCheck/all.css')!!}
<!-- <link rel="stylesheet" href="/public/adminTheme/plugins/iCheck/all.css"> -->
@endsection
@section('content')
<div class="content-wrapper" style="min-height: 508px;">
    <section class="content-header">
        <h1>{{$title}}</h1>
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
                            <h3 class="box-title">{{$title}}</h3>
                        </div>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    {!! Form::open(array('url' => 'admin/add-city','id'=>"CityForm", 'name'=>"CityForm",'files'=>true,'method' => 'POST','class' => '')) !!}
                    <div class="box-body">
                       <div class="center">
                            <div class="form-group {{ $errors->has('country_id') ? ' has-error' : '' }} col-md-6">
                               {!! Form::label('country', 'Country',['class' => 'control-label']) !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                               <select name="country" id="country" class="form-control" onchange="select_country()">
                                      <option value="">Select Country</option>
                                      
                                      @if(count($country) > 0)
                                        @foreach($country as $value)
                                          <option value="{{ $value->id }}" <?php  echo (!empty($city_info))?(($value->id == $city_info->country_info_id)?('selected'):('')):(''); ?>>{{ $value->country_name }}</option>
                                        @endforeach
                                      @endif 
                               </select>
                                <p class="controll-error help-block" id="er_country">@if ($errors->has('country_id')) <i class="errors"></i><span style="color:red;">{{ $errors->first('country_id') }}</span> @endif</p>
                            </div>
                           <div class="form-group {{ $errors->has('country_id') ? ' has-error' : '' }} col-md-6">
                             {!! Form::label('state', 'State',['class' => 'control-label']) !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                             <select name="state" id="state" class="form-control">
                                    <option value="">Select State</option>
                                    
                                    @if(count($state) > 0)
                                      @foreach($state as $value)
                                        <option value="{{ $value->id }}" <?php  echo (!empty($city_info))?(($value->id == $city_info->state_id)?('selected'):('')):(''); ?>>{{ $value->state_name }}</option>
                                      @endforeach
                                    @endif 
                             </select>
                              <p class="controll-error help-block" id="er_state">@if ($errors->has('state')) <i class="errors"></i><span style="color: red;">{{ $errors->first('state') }}</span> @endif</p>
                          </div>
                            <div class="form-group {{ $errors->has('city_name') ? ' has-error' : '' }} col-md-6">
                               {!! Form::label('City Name', 'City Name',['class' => 'control-label']) !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                               <input type="text" name="city_name" id="city_name" placeholder="City Name" value="<?php echo (!empty($city_info))?($city_info->city_name
                                ):(''); ?>" maxlength="50" class="form-control">
                              
                                <p class="controll-error help-block" id="er_city_name">@if ($errors->has('city_name')) <i class="errors"></i><span style="color: red;">{{ $errors->first('city_name') }}</span> @endif</p>
                            </div>
                             <input type="hidden" name="city_id" value="<?php echo (!empty($city_info))?($city_info->id):(''); ?>">
                       
                        
                      </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn-flat btn btn-success">Submit</button>
                        <a href="{{url('admin/city-list')}}" class="btn-flat btn btn-default">Go Back</a>
                    </div>

                    {!! Form::close() !!}
                </div><!-- /.box -->

            </div><!--/.col (left) -->
            <!-- right column -->          
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>
@stop
@section('script') 
@endsection