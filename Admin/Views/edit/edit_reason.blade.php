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
                    {!! Form::open(array('url' => "admin/edit-reason/$reason_info->id ",'id'=>"CancelForm", 'name'=>"CancelForm",'files'=>true,'method' => 'POST','class' => '')) !!}
                    <div class="box-body">
                       <div class="center">
                            <div class="form-group col-md-6">
                               {!! Form::label('Reason', 'Reason',['class' => 'control-label']) !!}&nbsp;<i class="error-star"><strong>*</strong></i>
                               <input type="hidden" name="reason_id" value="<?php echo (!empty($reason_info))?($reason_info->id):(''); ?>">
                               <input type="area" name="reason" id="reason" placeholder="Cancel Reason" value="<?php echo (!empty($reason_info))?($reason_info->reason
                                ):(old('reason')); ?>" maxlength="50" class="form-control">
                              
                                <p class="controll-error help-block" id="er_cancel">@if ($errors->has('reason')) <i class="errors" style="color:red;"></i><span style="color:red;">{{ $errors->first('reason') }}</span> @endif</p>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn-flat btn btn-success">Submit</button>
                        <a href="{{url('admin/cancel-reasons')}}" class="btn-flat btn btn-default">Go Back</a>
                    </div>
                    {!! Form::close() !!}
                </div><!-- /.box -->
            </div><!--/.col (left) -->
            <!-- right column -->          
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div>
@stop
