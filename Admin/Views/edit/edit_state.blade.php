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
                    {!! Form::open(array('url' => "admin/edit-state/$stateInfo->id",'id'=>"EditStateForm", 'name'=>"EditStateForm",'files'=>true,'method' => 'POST','class' => '')) !!}
                    <div class="box-body">
                       <div class="center">
                          <div class="row">
                            <div class="form-group col-md-6">
                               {!! Form::label('country', 'Country',['class' => 'control-label']) !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                               <select name="country_id" id="country_id" class="form-control" onchange="select_country()">
                                      <option value="">Select Country</option>
                                      
                                      @if(count($country) > 0)
                                        @foreach($country as $value)
                                        
                                          <option value="{{ $value->id }}" <?php  echo (!empty($stateInfo))?(($value->id == $stateInfo->country_id)?('selected'):('')):(''); ?>>{{ $value->country_name }}</option>
                                        @endforeach
                                      @endif 
                                      
                               </select>
                                <p class="controll-error help-block" id="er_country">@if ($errors->has('country_id')) <i class="errors"></i><span style="color:red;">{{ $errors->first('country_id') }}</span> @endif</p>
                            </div>
                            <div class="form-group {{ $errors->has('state_name') ? ' has-error' : '' }} col-md-6">
                               {!! Form::label('State Name', 'State Name',['class' => 'control-label']) !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                               <input type="text" name="state_name" id="state_name" placeholder="State Name" value="<?php echo (!empty($stateInfo))?($stateInfo->state_name
                                ):(old('state_name')); ?>" maxlength="50" class="form-control">
                              
                                <p class="controll-error help-block" id="er_country_name">@if ($errors->has('state_name')) <i class="errors"></i><span style="color:red;">{{ $errors->first('state_name') }} </span> @endif</p>
                            </div>
                             <input type="hidden" name="state_id" value="<?php echo (!empty($state_info))?($state_info->id):(''); ?>">
                        </div>
                      </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn-flat btn btn-success">Submit</button>
                        <a href="{{url('admin/state-list')}}" class="btn-flat btn btn-default">Go Back</a>
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
@stop