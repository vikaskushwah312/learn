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
                   <form role="form" action="{{ url('admin/app-version?id='.@$info->id )}}" id="app_version_form" name="app_version_form" method="post">
              {!! csrf_field() !!}
              <div class="box-body">
                
                <div class="col-md-6 col-xs-6 col-sm-6">                
                  <div class="form-group">
                    <label for="Contact Number">App Version</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="android_version" placeholder="Android Version" name="android_version" value="{{ old('android_version', @$info->android_version) }}">
                    @if ($errors->has('android_version'))
                      <p class="error help-block">{{ $errors->first('android_version') }}
                      </p>
                    @endif
                  </div>                  
                </div>

                <div class="clearfix"></div>
                
                <div class="col-md-6 col-xs-6 col-sm-6">
                
                  <div class="form-group">
                    <label for="Contact Number">Android Play Store Url</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="android_url" placeholder="Android Play Store Url" name="android_url" value="{{ old('android_url', @$info->android_url) }}">
                    @if ($errors->has('android_url'))
                      <p class="error help-block">{{ $errors->first('android_url') }}
                      </p>
                    @endif
                  </div>                  
                </div>
                
                <div class="clearfix"></div>


               <div class="col-md-6 col-xs-6 col-sm-6">
                
                  <div class="form-group">
                    <label for="Contact Number">IOS Version</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="ios_version" placeholder="IOS Version" name="ios_version" value="{{ old('ios_version', @$info->ios_version) }}">
                    @if ($errors->has('ios_version'))
                      <p class="error help-block">{{ $errors->first('ios_version') }}
                      </p>
                    @endif
                  </div>
                  
                </div>             
                
                <div class="clearfix"></div>

                <div class="col-md-6 col-xs-6 col-sm-6">
                
                  <div class="form-group">
                    <label for="Contact Number">IOS App Store Url</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <input type="text" class="form-control" id="ios_url" placeholder="IOS App Store Url" name="ios_url" value="{{ old('ios_url', @$info->ios_url) }}">
                    @if ($errors->has('ios_url'))
                      <p class="error help-block">{{ $errors->first('ios_url') }}
                      </p>
                    @endif
                  </div>                  
                </div>

                <div class="clearfix"></div>

                <div class="col-md-6 col-xs-6 col-sm-6">
                  <div class="form-group">
                    <label for="title">App Update/Upgrade</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <select class="form-control" name="app_update" id="app_update">
                      <option value="">Please Select</option>
                      <option value="0" <?php echo (@$info->app_update == '0') ? "selected" : "" ?>>Yes</option>
                      <option value="1" <?php echo (@$info->app_update == '1') ? "selected" : "" ?>>No</option>
                    </select>
                    @if ($errors->has('app_update'))
                      <p class="error help-block">{{ $errors->first('app_update') }}
                      </p>
                    @endif
                  </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-6 col-xs-6 col-sm-6">
                
                  <div class="form-group">
                    <label for="Contact Number">Alert Message</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                    <textarea id="message" name="message" class="form-control" maxlength="200">{{ old('message', @$info->message) }}</textarea>
                    @if ($errors->has('message'))
                      <p class="error help-block">{{ $errors->first('message') }}
                      </p>
                    @endif
                  </div>                  
                </div>

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
