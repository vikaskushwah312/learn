@extends('Web::layout.master_inner')
@section('css')
  {!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}  
@endsection
@section('content')
<style>
#city,#country,#state {
   -webkit-appearance: menulist-button;
   height:46px;
}


</style>
<div class="container-fluid mt-5 bg-light">
   <div class="row">
      <div class="container my-5">
         <div class="row fancy-box profile_sec mb-4">
            <div class="col-sm-12 px-0">
               <div class="media">
                  <div class="media-left profile-new text-center color-white green-bg">
                     <a href="#">
                        <div class="quote quote-profile">
                          @if($info->profile_image !="")  
                          <a  class = "fancybox" href="{{url(Config::get('constants.PROFILE_IMAGE').$info->profile_image )}}" > {!! Html::image(Config::get('constants.PROFILE_IMAGE').$info->profile_image,'',['alt' => 'User profile picture','class' => 'profile-user-img',"style" => 'width:150px;height:150px;'])!!} </a>

                          @else
                          <img src="{{url('public/webTheme/images/user-no-image.png')}}">
                          @endif
                        </div>
                     </a>

                     <h4 class="mt-3"><strong>{{ucfirst($info->first_name).' '.$info->last_name}}</strong></h4>
                     <hr class="color-white">
                     <p class="mb-0">{{'+'.$info->country_code.'-'.$info->mobile_number}}</p>
                     <p>{{$info->email}}</p>
                     <a class="btn btn-border" data-toggle="modal" data-target="#changepassword" href="#">Change Password</a>
                  </div>
                  <div class="media-body">
                     <div class="log in-form px-5 py-5 text-left">
                           {!! Form::open(array('id'=>"editProfileForm", 'name'=>"editProfileForm",'files'=>true,'method' => 'POST','class' => '' , )) !!}   
                        <div class="form-group ">
                           <div class="form-group mb-4">
                              <div class="fancy"><input name="profile_image" id="profile_image" type="file" onchange="readURL(this);" />

                                @if($info->profile_image !="")
                                <a  class = "fancybox" href="{{url(Config::get('constants.PROFILE_IMAGE').$info->profile_image )}}" > {!! Html::image(Config::get('constants.PROFILE_IMAGE').$info->profile_image,'',['alt' => 'User profile picture','class' => 'profile-user-img',"style" => 'width:100px;height:100px;'])!!} </a>

                                @else
                                <img src="{{url('public/webTheme/images/user-no-image.png')}}">
                                @endif
                              </div>
                              <span class="filename"></span>
                              <span class="note-txt">Note : Profile Image (jpg, png, jpeg)</span>
                              <p class="error help-block" id="er_profile_image">
                              </p>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-sm-6">
                              <div class="form-group mb-4">
                                 <input type="text" class="form-control height_mang" id="first_name" name="first_name" value="{{$info->first_name}}" >
                                 <p class="error help-block">{{ $errors->first('first_name') }}</p>
                              </div>
                           </div>
                           <div class="col-sm-6">
                              <div class="form-group mb-4">
                                 <input type="text" class="form-control height_mang" id="last_name" name="last_name" value="{{$info->last_name}}">
                                 <p class="error help-block">{{ $errors->first('last_name') }}</p>
                              </div>
                           </div>
                        </div>
                        <div class="form-group mb-4">
                           <input type="email" class="form-control height_mang" id="email" name="email" value="{{$info->email}}">
                           <p class="error help-block">{{ $errors->first('email') }}</p>
                        </div>
                        <div class="row">
                           <div class="col-sm-2">
                              <div class="form-group mb-4">
                                 <input type="text" class="form-control height_mang" id="country_code" name="country_code" value="{{$info->country_code }}" >
                                 <p class="error help-block">{{ $errors->first('country_code') }}</p>
                              </div>
                           </div>
                           <div class="col-sm-10">
                              <div class="form-group mb-4">
                                 <input type="text" class="form-control height_mang" id="mobile_number" name="mobile_number" value="{{$info->mobile_number}}">
                                 <p class="error help-block">{{ $errors->first('mobile_number') }}</p>
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-sm-6">
                              <div class="form-group mb-4">
                              <select class="form-control" id="country" name="country_id" onchange="select_country()">
                                  <option value="">Select Country</option>
                                  @if(count($country) > 0)
                                    @foreach($country as $key)
                                      <option value="{{$key->id}}" <?php if($key->id == $info->country_id){ echo "selected";}?> >{{ucfirst($key->country_name)}}</option>
                                    @endforeach
                                    @else
                                  @endif
                              </select> 
                              <p class="error help-block">{{ $errors->first('country_name') }}</p>                           
                           
                              </div>
                           </div>
                           <!-- ********state************* -->
                           <div class="col-sm-6">
                              <div class="form-group mb-4">

                                <select class="form-control" id="state" name="state_id"  onchange="select_state()">
                                <option value="">Select State</option>
                                @if($info->state_name)
                                  <option value="{{ $info->state_id }}" selected="selected">{{ ucfirst($info->state_name) }}</option>
                                @endif  
                              </select>
                              <p class="error help-block">{{ $errors->first('state_name') }}</p>
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-sm-6">
                              <div class="form-group mb-4">
                                <select class="form-control" id="city" name="city_id">
                                <option value="">Select City</option>
                                @if($info->city_name)
                                  <option value="{{ $info->city_id }}" selected="selected">{{ ucfirst($info->city_name) }}</option>
                                @endif
                            </select>
                            <p class="error help-block">{{ $errors->first('city_name') }}</p>
                         </div>
                           </div>

                           <div class="col-sm-6">
                              <div class="form-group mb-4">
                                 <input type="text" class="form-control height_mang" id="address" name="address" value="{{$info->address}}">
                                 <p class="error help-block">{{ $errors->first('address') }}</p>
                              </div>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col">
                              <label class="label-font ml-3">Gender</label>
                           </div>
                           @if($info->gender == 'Male')
                           <div class="col select_gender">
                              <input name="gender" id="gender" type="radio" checked="checked" value="Male">
                              <div class="media">
                                 <div class="media-left">
                                    <img src={{url('public/webTheme/images/male.png')}} >
                                 </div>
                                 <div class="media-body">
                                    <h6 class="ml-3 mt-3"> Male</h6>
                                 </div>
                              </div>
                           </div>
                           <div class="col select_gender">
                              <input name="gender" id="gender" type="radio" value="Female">
                              <div class="media">
                                 <div class="media-left">
                                    <img src={{url('public/webTheme/images/female.png')}}>
                                 </div>
                                 <div class="media-body">
                                    <h6 class="ml-3 mt-3">Female</h6>
                                 </div>
                              </div>
                           </div>
                           @elseif($info->gender == 'Female')
                           <div class="col select_gender">
                              <input name="gender" type="radio" value="Male">
                              <div class="media">
                                 <div class="media-left">
                                    <img src={{url('public/webTheme/images/male.png')}} >
                                 </div>
                                 <div class="media-body">
                                    <h6 class="ml-3 mt-3"> Male</h6>
                                 </div>
                              </div>
                           </div>
                           <div class="col select_gender">
                              <input name="gender" type="radio" checked="checked" value="Female">
                              <div class="media">
                                 <div class="media-left">
                                    <img src={{url('public/webTheme/images/female.png')}}>
                                 </div>
                                 <div class="media-body">
                                    <h6 class="ml-3 mt-3">Female</h6>
                                 </div>
                              </div>
                           </div>
                           @else
                           @endif
                        </div>
                        
                        <button type="submit" id="submit" class="btn btn-block theme-btn-new mt-3">update profile</button>
                      
                        {!! Form::close() !!}
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- modal -->
<div class="modal fade" id="changepassword" role="dialog">
    <div class="modal-dialog">
       <!-- Modal content-->
       <div class="modal-content">
          <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal">&times;</button>
             <h4 class="modal-title">CHANGE PASSWORD</h4>
          </div>
            <div class="modal-body">
             {!! Form::open(array('id' =>'changePasswordForm','name' => 'changePasswordForm','method' => '','files' => true,'class' => '')) !!}
                <div class="form-group hvr-red">
                  <input type="Password" class="form-control height_mang" id="current_password" name="current_password" placeholder="Old Password *">
                   <p class="error help-block">{{ $errors->first('current_password') }}</p> 
                </div>
                <div class="form-group hvr-red">
                  <input type="password" class="form-control height_mang" id="new_password" name="new_password" placeholder=" New Password *">
                   <p class="error help-block">{{ $errors->first('new_password') }}</p> 
                </div>
                <div class="form-group hvr-red">
                  <input type="password" class="form-control height_mang" id="confirm_new_password" name="confirm_new_password" placeholder=" Confirm Password *">
                  <p class="error help-block">{{ $errors->first('confirm_new_password') }}</p> 
                </div>
            
            <div class="modal-footer">
              <div class="col-sm-8 col-sm-offset-2 text-center">
                <button type="submit" class="btn btn-default theme_btn" style="background-color:#98bb00;">Change Password</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          {!! Form::close() !!} 
          </div> 
       </div>
    </div>
 </div>
@stop


@section('script') 
{!! Html::script('public/custom/js/jquery.validate.min.js')!!}
{!! Html::script('public/custom/plugnis/fancybox/source/jquery.fancybox.js') !!}
<!-- //change password -->

   <script type="text/javascript">
  $("form[name='changePasswordForm']").validate({
    
    rules: {

      current_password: {
        required: true,
        minlength: 6
      },
      new_password: {
        required: true,
        minlength: 6
      },
      confirm_new_password: {
        required: true,
        equalTo: "#new_password",
      },
    },
   
    messages: {
     
      current_password: {
        required: "Please enter old password",
        minlength: "Your old password must be at least 6 characters long."
      },
      new_password: {
        required: "Please enter new password",
        email: "Your new password must be at least 6 characters long."
      },
      confirm_new_password: {
        required: "Please enter confirm new password",
        equalTo: "Confirm new password should be equal to new password.",
      },
    },
    
    
    submitHandler: function(form) {

      $.ajax({
            url: "{{url('api/change-password')}}",
            method:'POST',
            dataType: 'JSON',
            data:$("form").serialize(),
            headers  : {'Authorization': "{{'Bearer '.Session::get('set_token')}}"},

            success: function(res){
              
                if (res.code == 200) {

                  $("#changepassword").modal('hide');
                      //window.location.href = "{{url('/web/profile')}}";
                      swal({
                          title:'Success!',
                          text:res.msg,
                          type:'success',
                          timer:2000
                        });

                }else if(res.code == 400){
                    
                    //show validatiom msg
                    if(res.result != undefined){

                       for(var key in res.result){
                        
                            $("#"+key).parent().find('.help-block').html(res.result[key]).show();
                        } 
                    }else{
                        
                        $("#changepassword").modal('show');
                        swal({
                          title:'Oops!',
                          text:res.msg,
                          type:'error',
                          timer:2000
                        });
                    }
                }else if(res.code == 300){

                  window.location.href = "{{url('/web/login')}}";
                }
            }  
        });
    }
  });

    $('#changepassword').on('hidden.bs.modal', function () {

        $('.error').html('');
        $('input').removeClass('error');     
    });

</script>

<script type="text/javascript">
   $("form[name='editProfileForm']").validate({
   
      rules: {
   
        first_name: {
           required: true,
            maxlength: 50,
           
         },
         last_name: {
           required: true,
           maxlength: 50,
           
         },
         email: {
           required: true,
           email: true
           
         },
         country_code: {
           required: true,
           
         },
         mobile_number: {
           required: true,
           maxlength: 10,
           minlength:10,
           number:true,                      
         },
         country_id:{
          required:true,
         },
         state_id:{
          required:true,
         },
         city_id:{
          required:true,
         },
   
       },
      
       messages: {
        
         first_name: {
           required: "Please enter first name",
           maxlength: "Your first name must be 50 characters long.",
         },
        last_name: {
           required: "Please enter last name",
           maxlength: "Your last name must be 50 characters long.",
         },
          email: {
           required: "Please enter email",
           email: "Please enter a valid email address.",
         },
         country_code: {
           required: "Please enter country code.",
         },
         mobile_number: {
          required: "Please enter mobile number.",
          maxlength: "Your mobile number maxlength should be 10 numeric value long.",
          minlength: "Your mobile number minlength should be 10 numeric value long.",
          number :"Please enter valid mobile number.",
         },
         country_id:{
          required:"Please select country.",
         },
         state_id:{
          required:"Please select state.",
         },
         city_id:{
          required:"Please select city.",
         },

       },
   
     submitHandler: function(form) {
   
       var formData = new FormData();
       formData.append('first_name',$('#first_name').val());
       formData.append('last_name', $('#last_name').val());
       formData.append('email', $('#email').val());
       formData.append('country_code', $('#country_code').val());
       formData.append('mobile_number', $('#mobile_number').val());
       formData.append('country_id', $('#country').val());
       formData.append('state_id', $('#state').val());
       formData.append('city_id', $('#city').val());
       formData.append('gender', $('#gender').val());
       formData.append('address', $('#address').val());
       formData.append('profile_image', document.getElementById("profile_image").files[0]);
       $.ajax({
              
               url: "{{url('api/update-profile')}}",
               method:'POST',
               dataType: 'JSON',
               data:formData,
               cache:false,
               contentType: false,
                processData: false,
               headers:{'Authorization': "{{'Bearer '.Session::get('set_token')}}"},
               success: function(res){
   
                 make_disable_enable('remove', 'submit', 'submit');
                 
                   if (res.code == 200) {
                        
                       swal({
                         title:'success!',
                         text:res.msg,
                         type:'success',
                         timer:2000
                         });
   
                             window.location.reload();
                             
                     }else{
                       
                           //show validatiom msg
                             if(res.result != undefined){
   
                              for(var key in res.result){
                                 
                                 $("#"+key).removeClass('error');
                                 $("#"+key).parent().find('.help-block').html(res.result[key]).hide();
                                 $("#"+key).addClass('error');
                                 
                                 $("#"+key).parent().find('.help-block').html(res.result[key]).show();
                             }
   
                           }else{                            
                                                       
                               swal({
                                 title:'Oops!',
                                 text:res.msg,
                                 type:'error',
                                 timer:2000
                               });
                         }
   
                   }
               }/*SUCCESS*/
               
       });
   
   
     }
   
   
   });
  
</script>

  
 <script type="text/javascript">
   
   function viewImage(){
      $(".fancybox").fancybox();

   }
   viewImage(); 

   /******************Image preview*******************/

  /*function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profile_image')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }*/
       
</script>

@endsection