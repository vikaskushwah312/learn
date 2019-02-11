@extends('Admin::layout.master')
  
@section('css')
  {!! Html::style('public/custom/css/animate.css') !!}
  {!! Html::style('public/custom/plugnis/bootstrap-date-time-picker/bootstrap-datetimepicker.css') !!}
  {!! Html::style('public/custom/plugnis/bootstrap-date-time-picker/bootstrap-datetimepicker-standalone.css') !!}
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
                   <form role="form" action="{{ url('admin/app-info?id='.@$info->id )}}" id="appinfo_form" name="appinfo_form" method="post"><!-- admin/app-info?id='.@$info->id -->
              {!! csrf_field() !!}
              <div class="box-body">
                
                <div class="col-md-6 col-xs-6 col-sm-6">
                
                  <div class="form-group">
                    <label for="Contact Number">Contact Number</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="contact_number" placeholder="Contact Number" name="contact_number" value="{{ old('contact_number', @$info->contact_number) }}">
                    @if ($errors->has('contact_number'))
                      <p class="error help-block">{{ $errors->first('contact_number') }}
                      </p>
                    @endif
                  </div>
                  
                </div>
                
                <div class="clearfix"></div>

                <div class="col-md-6 col-xs-6 col-sm-6">                
                  <div class="form-group">
                    <label for="Email">Email</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="email" placeholder="Email" name="email" value="{{ old('email', @$info->email) }}">
                    @if ($errors->has('email'))
                      <p class="error help-block">{{ $errors->first('email') }}
                      </p>
                    @endif
                  </div>                  
                </div>
                
               
                <div class="clearfix"></div>
                
                <div class="col-md-6 col-xs-6 col-sm-6">                
                  <div class="form-group">
                    <label for="Address">Address</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="address" placeholder="Address" name="address" value="{{ old('address', @$info->address) }}">
                    
                    @if ($errors->has('address'))
                      <p class="error help-block">{{ $errors->first('address') }}
                      </p>
                    @endif
                  </div>                  
                </div>

                <div class="clearfix"></div>


                <div class="col-md-6 col-xs-6 col-sm-6">
                  <div class="form-group">
                    <label for="title">Web Url</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="web_url" placeholder="Web Url" name="web_url" value="{{ old('web_url', @$info->web_url) }}">
                    @if ($errors->has('web_url'))
                      <p class="error help-block">{{ $errors->first('web_url') }}
                      </p>
                    @endif
                  </div>
                </div>

                <div class="clearfix"></div>
                </div>
        
              
              <div class="box-footer">
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
