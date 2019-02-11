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
                    {!! Form::open(array('url' => "admin/edit-promocode/$promocodeInfo->id",'id'=>"editpromocodeForm", 'name'=>"editpromocodeForm",'files'=>true,'method' => 'POST','class' => '')) !!}
                    <!-- <input type="hidden" name="subCategoryId" id="subCategoryId" value="{{Request::segment(3)}}"> -->
                    
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group {{ $errors->has('promocode') ? ' has-error' : '' }} col-md-6">
                                {!! Form::label('promocode', 'Promocode') !!}&nbsp;<i class="error"><strong>*</strong></i>
                                {!! Form::text('promocode',$promocodeInfo->code,array('class' => 'form-control','placeholder' =>'Promocode','id' => 'promocode','maxlength' => '10')) !!}

                                <p class="controll-error help-block" id="er_code">@if ($errors->has('promocode')) <i class="errors"></i>&nbsp;{{ $errors->first('promocode') }}</p> @endif

                                <!-- Generate Promocode  -->
                                <p>
                                    <a href="javascript:void(0)" onclick="generate_code()" class="gen_pro_code">
                                    <span id="gen_promo_code_title">Generate Promo Code</span>
                                </a>
                                </p>

                                @if ($errors->has('promo_code'))
                                    <p class="error help-block">{{ $errors->first('promo_code') }}
                                    </p>
                                @endif

                            </div>

                            <!-- select start Date here -->
                            <div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }} col-md-6">
                                
                                {!!Form::label('startdate','Start Date') !!}&nbsp;<i class="error"><strong>*</strong></i>
                                {!!Form::text('start_date',$promocodeInfo->start_date  ,array('class' => 'form-control','placeholder' => 'Start Date', 'id' => 'start_date','onkeydown'=>'return false'))!!}

                                <p class="controll-error help-block" id="er_code">@if ($errors->has('start_date')) <i class="errors"></i>&nbsp;{{ $errors->first('start_date') }}</p> @endif

                            </div>
                        </div> 

                            <!-- //select end date -->
                        <div class="row">
                            <div class="form-group {{ $errors->has('end_date') ? ' has-error' : '' }} col-md-6">
                                {!! Form::label('enddate', 'End Date') !!}&nbsp;<i class="error"><strong>*</strong></i>
                                {!! Form::text('end_date',$promocodeInfo->expiry_date,array('class' => 'form-control','placeholder' =>'End Date','id' => 'end_date','onkeydown' => 'return false')) !!}

                                <p class="controll-error help-block" id="er_code">@if ($errors->has('end_date')) <i class="errors"></i>&nbsp;{{ $errors->first('end_date') }}</p> @endif
                            </div>
                                
                                <!-- Select Type (Flat/Percentage) -->
                            
                            <div class="form-group {{ $errors->has('type') ? ' has-error' : '' }} col-md-3">
                                {!! Form::label('type', 'Select Type (Flat/Percentage)') !!}&nbsp;<i class="error"><strong>*</strong></i>

                                <select class="form-control" name="type" id="type"  >
                                    <?php if($promocodeInfo->type=='flat'){?>
                                    <option value="">Select Type</option>
                                    <option value="1" selected>Flat</option>
                                    <option value="2">Percentage (%)</option>
                                    <?php }else{ ?>
                                    <option value="">Select Type</option>
                                    <option value="1">Flat</option>
                                    <option value="2" selected>Percentage (%)</option>
                                    <?php }?>
                                    
                                </select>
                                

                                <p class="controll-error help-block" id="er_code">@if ($errors->has('type')) <i class="errors"></i>&nbsp;{{ $errors->first('type') }}</p> @endif
                            </div>

                            <div class="form-group {{ $errors->has('value') ? ' has-error' : '' }} col-md-3">
                                {!! Form::label('value', 'Value') !!}&nbsp;<i class="error"><strong>*</strong></i>
                                {!! Form::text('value',$promocodeInfo->amount,array('class' => 'form-control','placeholder' =>'Value','id' => 'value','autocomplete' => 'off')) !!}

                                <p class="controll-error help-block" id="er_code">@if ($errors->has('value')) <i class="errors"></i>&nbsp;{{ $errors->first('value') }}</p> @endif
                            </div>

                             <!-- No of users  -->
                            <div class="row" style="margin-left:1px;">
                              <div class="form-group col-md-6">
                                {!! Form::label('no_of_users', 'No.Of Users') !!}&nbsp;<i class="error"><strong>*</strong></i>
                                {!! Form::text('no_of_users',"$promocodeInfo->no_of_users",array('class' => 'form-control','placeholder' =>'No.Of Users','id' => 'no_of_users','maxlength' => '10')) !!}

                                <p class="controll-error help-block" id="er_code" style="color:red;">@if ($errors->has('no_of_users')) <i class="errors"></i>&nbsp;{{ $errors->first('no_of_users') }}</p> @endif
                                
                            </div>                            
                        </div> 
                            <!-- End no of users -->



                   </div><!-- end of box - body -->


                    <div class="box-footer">
                        <button type="submit" class="btn-flat btn btn-success" ">Submit</button>
                        <a href="{{url('admin/promocode')}}" class="btn-flat btn btn-default"><i class="fa fa-arrow-left"></i> Go Back</a>

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
 {!! Html::script('public/custom/plugnis/bootstrap-date-time-picker/bootstrap_moment.js') !!}
  {!! Html::script('public/custom/plugnis/bootstrap-date-time-picker/bootstrap_datetimepicker.js') !!}
  
  <script type="text/javascript">
   
    function generate_code(){
      
     $("#promocode").val('');

      $("#gen_promo_code_title").html('Please Wait..');

      var code = randomStr(5);
      
      $("#gen_promo_code_title").html('Generate Promo Code');

      $("#promocode").val(code);            
    
    }

    function randomStr(m) {
      var m = m || 9; s = '', r = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz1234567890';
      for (var i=0; i < m; i++) { 
        s += r.charAt(Math.floor(Math.random()*r.length)); }
      return s;
    
    };

    //Date picker

    $(function () {

      $('#start_date').datetimepicker({
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
          format: 'Y-MM-DD HH:mm',
          minDate: null,
      });

      $('#end_date').datetimepicker({
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
          format: 'Y-MM-DD HH:mm',
          minDate: null,
           useCurrent: false,

      });
        $("#start_date").on("dp.change", function (e) {
            $('#end_date').data("DateTimePicker").minDate(moment(e.date).add(3, 'hours'));
        });
   }); 

          /*Select function */

/*function select(){
  var x = document.getElementById("type").value;
  document.getElementById("v").innerHTML =

}promocodes*/

</script>


@stop