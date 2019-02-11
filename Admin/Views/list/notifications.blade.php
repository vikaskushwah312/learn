@extends('Admin::layout.master')

@section('css')
  {!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}  
  {!! Html::style('public/adminTheme/plugins/select2-4/dist/css/select2.css') !!}
  <style type="text/css">
  .select2-container--default .select2-selection--multiple .select2-selection__choice{
    color: #222D32 !important;
  }

  .hide_select_box{
    display: none;
  }
</style>
@endsection

@section('content')
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Notifications List
          <a href="javascript:void(0)" class="btn btn-success pull-right" onclick="open_notification_modal()">
            <i class="fa fa-plus"></i> New Notification
          </a>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
              
        <div class="col-xs-12">

         <div class="box box-success box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Notifications List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">              

              <table id="notification-table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="removeSortingClass">#</th>
                  <th class="removeSortingClass">Image</th>
                  <th>User Name</th>
                  <th class="removeSortingClass">Title</th>
                  <th class="removeSortingClass">Message</th>
                  <th>Created At</th>              
                  <th class="removeSortingClass">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
               
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <!-- Send Notification Modal -->
<div id="sendNotificationModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Notification</h4>
      </div>
      <div class="modal-body">
          
           <form id="sendNotificationForm" method="post" enctype="multipart/form-data" action="javascript:void(0)">
                    
              <div class="form-group  {{ $errors->has('title') ? ' has-error' : '' }} ">
                <label for="title">Enter Title</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                <p class="help-block red" id="er_title"></p>
              </div>                     

              <div class="form-group  {{ $errors->has('title') ? ' has-error' : '' }} ">
                <label for="message">Enter Message</label>&nbsp;<i class="error-star"><strong>*</strong></i>
                <textarea class="form-control" placeholder="Message" maxlength="250" id="message" name="message"></textarea>
                <p class="help-block red" id="er_message"></p>
              </div> 

              <div class="form-group  {{ $errors->has('n_image') ? ' has-error' : '' }} ">
                <label for="image">Image</label>&nbsp;<i class="error-star"><strong></strong></i>
                <input type="file" name="n_image" id="n_image" >
                <p class="help-block red" id="er_n_image"></p>
              </div>

              <div class="form-group  {{ $errors->has('title') ? ' has-error' : '' }} " >
                
                
                <input type="radio" name="all_who" id="all_users" class="all_select_class" value="all_users"> All <br>
                <input type="radio" name="all_who" id="all_customers" class="all_select_class" value="customers"> All Passengers <br>
                <input type="radio" name="all_who" id="all_drivers" class="all_select_class" value="all_drivers"> All Drivers <br>
                <input type="radio" name="all_who" id="choose_users" > Choose 

                <p class="help-block red" id="er_message"></p>
              </div>            

              <div class="form-group  {{ $errors->has('users') ? ' has-error' : '' }} " id="select_custom_user">
                                
                <select class="js-example-basic-multiple form-control" multiple="multiple"  style="width: 100%" id="selectUsers" name="users[]">

                  @if(count($users) > 0)
               
                    <optgroup label="Passenger">
                    @foreach($users as $value)
                    
                      @if($value->user_type == "Passenger")
                        
                          <option value="{{ $value->id }}">{{ $value->first_name." ".$value->last_name }}</option>
                        
                      @endif

                    @endforeach
                    </optgroup>
                    <optgroup label="Drivers">
                    @foreach($users as $value)
                      @if($value->user_type == "Driver")
                        <option value="{{ $value->id }}">{{ $value->first_name." ".$value->last_name }}</option>
                                            
                      @endif
                    
                    @endforeach
                  </optgroup> 
                  @endif
                </select>
                
                <p class="help-block red" id="er_selectUsers"></p>
              </div>
            
            
            <button type="submit" class="btn btn-success" id="send_btn">Send</button>
          </form> 

      </div>
    
    </div>
  </div>
</div>

@stop

@section('script')

  {!! Html::script('public/custom/js/admin_validation.js')!!} 
  {!! Html::script('public/adminTheme/plugins/select2-4/dist/js/select2.full.js') !!}
  {!! Html::script('public/custom/plugnis/fancybox/source/jquery.fancybox.js') !!}

  <script type="text/javascript">
   $(document).ready(function() {
        
        window.datatable = $('#notification-table').DataTable({
            //"order": [[ 0, "desc" ]],
            "columnDefs": [ {
                  "targets": 'removeSortingClass',
                  "orderable": false,

            } ],
            "aaSorting" : [],
            "pageLength" : 10,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url : "{{url('admin/notificationListData')}}",
                type : 'GET'
            },
        });

    });
  </script>
  <script type="text/javascript">
    $("#notification-table").on('draw.dt',function(){

      $(".fancybox").fancybox();

   });
  </script>
  <script type="text/javascript">
  
  function open_notification_modal(){

      $("#sendNotificationModal").modal('show');

  }

  $("#sendNotificationForm").submit(function(){

    var flag = true; 
    if(valid.required('title','title') == false) 
    { 
      $("#title").parents('.form-group').addClass('has-error'); 
      flag = false;
    }
    if(valid.required('message','message') == false) 
    { 
      $("#message").parents('.form-group').addClass('has-error'); 
      flag = false;
    }

    if($("#choose_users").prop('checked')){
      
      if(valid.required('selectUsers','Select users') == false) 
      { 
        $("#selectUsers").parents('.form-group').addClass('has-error'); 
        flag = false;
      }
    }
    
    $("#er_n_image").html('');
    
    if ($("#n_image").val() != "") {

      var ext = $('#n_image').val().split('.').pop().toLowerCase();
      
      if($.inArray(ext, ['png','jpg','jpeg']) == -1) {
      
        $("#er_n_image").html('Please upload only png, jpg, jpeg extension');
        flag = false;
      }

    }

    if (flag) {

      $('#loading').show();

      make_disable_enable('add', 'send_btn', 'Sending..');

      // Get form data
      var formData = new FormData();  

      formData.append('all_who',$('#all_who').val());     
      formData.append('users',$('#selectUsers').val());
      formData.append('title',$('#title').val());
      formData.append('message',$('#message').val());
      formData.append('n_image',document.getElementById("n_image").files[0]);

      $.ajax({

        type: 'POST',
        url: "{{ url('admin/send-notification-users')}}",
        data:formData,
        dataType:'JSON',
        cache:false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,
        processData: false,
            
        success:function(res){

          make_disable_enable('remove', 'send_btn', 'Send');
          
          $('#loading').hide();
          if (res.success == 1) {
             
             $("#sendNotificationModal").modal('hide');
             swal({
                  title : "success",
                  text : ""+res.msg+"",
                  type : "success",
                  timer : 2000
              }).then((value) => {
                location.reload();
              }).catch(swal.noop);
              setTimeout(function(){
             window.location.href = window.location.href;
            },2000)
          }else{
            swal({
                title : "Failed",
                text : ""+res.msg+"",
                type : "error",
                timer : 3000
            });
          }
        }
      });
    }
    return false;

});

</script>
  
<script type="text/javascript">
  $(".js-example-basic-multiple").select2();

  $(".js-example-basic-multiple").select2({
      placeholder: "Select Users",
      allowClear: true
    });
     
    $(".js-example-basic-multiple").select2({
      placeholder: "Select Users"
    });
</script>
<script type="text/javascript">
    
  $('#choose_users').on('click change', function(e) {
    
    $("#select_custom_user").removeClass('hide_select_box');
  
  });
  
  $('.all_select_class').on('click change', function(e) {
    
    $("#select_custom_user").addClass('hide_select_box');
  
  });

</script>

@endsection