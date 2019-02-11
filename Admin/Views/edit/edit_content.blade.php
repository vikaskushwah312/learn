@extends('Admin::layout.master')
@section('css')
  {!! Html::style('public/adminTheme/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')!!}
@endsection
@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{$title}}                                    
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

         <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">{{$title}}</h3>
              
            </div>
              {!! Form::open(array('url' => "admin/edit-content/$content_info->id",'id'=>"Content", 'name'=>"ContentForm",'files'=>true,'method' => 'POST','class' => '')) !!}

              {!! csrf_field() !!}
              <div class="box-body">

                <div class="form-group ">
                  <label for="title">Name</label>&nbsp;<i class="error"><strong>*</strong></i>
                  <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="<?php  echo (!empty($content_info))?($content_info->name):(''); ?>">
                  @if ($errors->has('name'))
                    <p class="error help-block">{{ $errors->first('name') }}
                    </p>
                  @endif
                </div>

                <div class="form-group ">
                  <label for="title">Value</label>&nbsp;<i class="error"><strong>*</strong></i>
                </div>
                              

                <div class="form-group ">
                  <textarea class="textarea" placeholder="<?php  echo (!empty($content_info))?($content_info->value):(''); ?>"
                            style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="value" id="value">
                    <?php  echo (!empty($content_info))?($content_info->value):(''); ?></textarea>
                  @if ($errors->has('value'))
                    <p class="error help-block">{{ $errors->first('value') }}
                    </p>
                  @endif
                </div>   
              </div>
              <input type="hidden" name="content_id" value="<?php echo (!empty($content_info))?($content_info->id):(''); ?>"> 
              <div class="box-footer">
                <button type="submit" class="btn-flat btn btn-success">Submit</button>
              </div>
        {!! Form::close() !!}
            
          </div>
        </div>
      </div>
    </section>
  </div>

@stop
@section('script')
  {!! Html::script('public/adminTheme/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') !!}
<script>

  $(function () {

    $('.textarea').wysihtml5()
  })
</script>
@endsection