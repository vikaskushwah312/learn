@extends('Admin::layout.master')

@section('css')
  {!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}
<style type="text/css">
    .chk_selected11  {
   background-color: #36459b;
   color: #fff;
   height: 19px;
   line-height: 20px;
   margin: 2px;
   padding: 5px;
   text-align: center;
   vertical-align: middle;
   width: 30px;
   transition: all 0.3s;
   }
   .chk_selected11 {
     position: absolute;
     right: 30px!important;
     top: -1px!important;
   }
</style>
@endsection
@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{$title}}
        <a href="{{url('admin/ride-detail/'.$rideInfo->rideId)}}"><button class="btn btn-success pull-right"><i class="fa fa-arrow-left "></i> Go Back </button></a>                
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
            <!-- Box -->
            <div class="box box-success box-solid">
               <div class="box-header">
                  <h3 class="box-title">Rides Information</h3>
               </div>
               <!-- Body -->
               <div class="box-body">
                  <table class="table table-hover table-responsive">
                     <tbody>
                        <tr>
                           <td class="fix_width"><b>RideId  :</b></td>
                           <td>{{ucfirst($rideInfo->rideId)}}</td>
                        </tr>
                        <tr>
                          <td><b>Passenger Name :</b></td>
                          <td>{{ucfirst($rideInfo->first_name." ".$rideInfo->last_name)}}</td>
                        </tr>
                        <tr>
                           <td><b>Pickup Location : </b></td>
                           <td>{{ucfirst($rideInfo->pickup_location)}}</td>
                        </tr>
                        <tr>
                           <td><b>Dropoff Location :</b></td>
                           <td>{{ucfirst($rideInfo->dropoff_location)}}</td>
                        </tr>
                                              
                     </tbody>
                  </table>
       
                </div>
               <!-- Body End -->
            </div>
      <div class="row">
      <div class="col-md-12">
        <div class="box box-success box-solid">
          <div class="box-header">
              <h3 class="box-title">{{$title}}</h3>
              @if(count($img) > 0)
                <a href="javascript:void(0);" class="btn btn-default pull-right" style="border-color: #ecf0f5; margin-left: 5px;" onclick="deleteImage('delete_all');"><i class="fa fa-delete"></i> Delete All</a>

                <a href="javascript:void(0);" class="btn btn-default pull-right" style="border-color: #ecf0f5;" onclick="deleteImage('delete');"><i class="fa fa-delete"></i> Delete </a>&nbsp;&nbsp;
              @endif
              
          </div>
          <div class="box-body">
          <table class="table table-hover table-responsive">
              <tbody>
                @if(count($img) >0)
                <tr class=""> 
                <td class="">
                  @foreach($img as $val)   
                    <div class="col-md-3">
                      <a href="{{ url(Config::get('constants.AUTO_CAPCTURE').$val->auto_capture) }}" class="fancybox">
                        <img src="{{ url(Config::get('constants.AUTO_CAPCTURE').$val->auto_capture) }}" height="200px" width="200px"></a>

                        <!--download Image -->
                        <a href="{{ url(Config::get('constants.AUTO_CAPCTURE').$val->auto_capture) }}" download>
                        <button class="btn btn-link pull-right" style="margin-right:2px;" onclick="download()">Download</button>
                       </a> <p></p>
                      
                        <input type="checkbox" name="select_image" id="select_image_{{$val->id}}" class="chk_selected11" onclick="selectImgae({{$val->id}})">
                    </div>

                  @endforeach
                </td>
                </tr>  
                @else
                <tr>
                <td style="text-align: center;">Images Not Found</td>
                </tr>
                @endif                                         
              </tbody>
          </table>
          <input type="hidden" name="rideId" id="rideId" value="{{Request::segment(3)}}">
        </div>
      </div>
      </div>
            
            <!-- Box End -->
         </div>
        <!-- /.col -->
      </div>
      <!-- capture Images -->

      <!-- capture images
      -->
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

@stop

@section('script')
{!! Html::script('public/custom/plugnis/fancybox/source/jquery.fancybox.js') !!}
  <script type="text/javascript">

  function showImgae(){
      
      $(".fancybox").fancybox();
  } 
  showImgae();
  var valArray = [];
  function selectImgae(id){

    if($("#select_image_"+id).prop('checked') == true){

        valArray.push(id);
      }else{

        valArray = $.grep(valArray, function(value) {
          return value != id;
        });
      }
  }

  function deleteImage(check){

    if(check == "delete"){

        var showMessage = "Delete";
    }else{

      var showMessage = "Delete all";
    }
      swal({
              
            title: 'Are you sure?',
            text: 'You want to '+showMessage+' images',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete!'
          
          }).then(function () {
            
            var rideId = $("#rideId").val();
            $.ajax({
              url: "{{ url('admin/delete-capturer-image')}}",
              method:'POST',
              dataType: 'JSON',
              data:{"rideId":rideId,"check":check,"select_image":valArray.join()},
              headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              success: function(res){

                if (res.success == 1) {
                  
                  swal({
                    title:'Success!',
                    text:res.msg,
                    timer:2000,
                    type:'success'
                  });
                  window.location.href = "{{url('admin/view_all_images')}}/"+rideId;

                }else{

                  swal({
                    title:'Oops!',
                    text:res.msg,
                    type:'error',
                    timer:2000
                  });
                }              
              }
            });
          }); 
      //alert(valArray.join());
  }
  </script>
@endsection