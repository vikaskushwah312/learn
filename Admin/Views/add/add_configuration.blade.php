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
               </div>
               <!-- /.box-header -->
               <!-- form start -->
               {!! Form::open(array('url' => 'admin/add-configuration','id'=>"AddConfigurationForm", 'name'=>"AddConfigurationForm",'files'=>true,'method' => 'POST','class' => '')) !!}
               <!-- <input type="hidden" name="subCategoryId" id="subCategoryId" value="{{Request::segment(3)}}"> -->
               <div class="box-body">
                  <div class="row">
                     <input type="hidden" name="configuration_id" id="configuration_id" value="{{@$info->id}}">
                     <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }} col-md-12">
                        {!! Form::label('title', 'Title') !!}&nbsp;<i class="error"><strong>*</strong></i>
                        {!! Form::text('title', @$info->title,array('class' => 'form-control','placeholder' =>'Title','id' => 'title','maxlength' => '100')) !!}
                        <p class="controll-error help-block" id="er_title">@if ($errors->has('title')) <i class="fa fa-times-circle-o"></i>&nbsp;{{ $errors->first('title') }}</p>
                        @endif
                     </div>
                     <div class="form-group {{ $errors->has('value') ? ' has-error' : '' }} col-md-12">
                        {!! Form::label('value', 'Value') !!}&nbsp;<i class="error"><strong>*</strong></i>
                        {!! Form::text('value', @$info->value,array('class' => 'form-control','placeholder' =>'Value','id' => 'value','rows' => '2','maxlength' => '250')) !!}
                        <p class="controll-error help-block" id="er_value">@if ($errors->has('value')) <i class="fa fa-times-circle-o"></i>&nbsp;{{ $errors->first('value') }}</p>
                        @endif
                     </div>
                     @if(empty($info))
                     <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }} col-md-12">
                        {!! Form::label('type', 'Type') !!}&nbsp;<i class="error"><strong>*</strong></i>
                        {!! Form::text('type', @$info->type,array('class' => 'form-control','placeholder' =>'Type','id' => 'title','maxlength' => '50')) !!}
                        <p class="controll-error help-block" id="er_type">@if ($errors->has('type')) <i class="fa fa-times-circle-o"></i>&nbsp;{{ $errors->first('type') }}</p>
                        @endif
                     </div>
                     @endif
                  </div>
               </div>
               <div class="box-footer">
                  <button type="submit" class="btn-flat btn btn-success">Submit</button>
                  <a href="{{url('admin/configuration')}}" class="btn-flat btn btn-default"><i class="fa fa-arrow-left"></i> Go Back</a>
               </div>
               {!! Form::close() !!}
            </div>
            <!-- /.box -->
         </div>
         <!--/.col (left) -->
         <!-- right column -->          
      </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>
@endsection
@section('script')
@endsection

