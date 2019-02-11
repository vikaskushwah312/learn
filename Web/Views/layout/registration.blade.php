<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" href="{{ url('public/uploads/favicon.png')}}" type="image/png">
      <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
      <title>Ecogadi</title>
      <!-- Bootstrap -->
      {!!Html::Style('public/webTheme/css/bootstrap.min.css')!!}
      {!!Html::Style('public/webTheme/css/custom.css')!!}
      {!!Html::Style('public/custom/css/style.css')!!}
      {!!Html::Style('public/custom/plugnis/sweetalert/sweetalert2.min.css')!!} 
      <!-- <link href="css/animate.css" rel="stylesheet">
      <link href="css/owl.carousel.min.css" rel="stylesheet"> -->
      <link href="https://fonts.googleapis.com/css?family=Baumans" rel="stylesheet">
   </head>
   <body>
      <!-- Page Header -->
      <div class="container-fluid px-0">
         <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-7 md-auto bg-auto">
               <!-- <img src="images/auto-background.png" class="auto-login-back">-->
               <div class="para-box">
                  <h1 class="color-white text-uppercase"><b>Loreum ipsum doler</b></h1>
                  <h6 class="color-white">Lorem ipsum dolor sit amet consectetur adipiscing elit </h6>
               </div>
            </div>
             
            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-5 px-0 text-center">
               <div class="bg-login">
                  <a href="{{URL('web')}}">{!!Html::image('public/webTheme/images/logo1.png','',['class' => 'mt-3'])!!}</a>
               </div>
               <div class="row">
               <div class="col my-2">
               <h3 class="text-center text-uppercase">Create An Account</h3>
               <p class="text-center m-b-0">Already have an Account? Please<span class="text_bold"><a href="{{url('/login')}}" class="color-green"> Sign In</a></span></p>
               </div>
               </div>
               <div class="login-form px-5 text-left">
                  {!! Form::open(array('id'=>"RegistrationForm", 'name'=>"RegistrationForm",'files'=>true,'method' => 'POST','class' => '')) !!}
                     <div class="row">
                        <div class="col-6">
                           <div class="form-group hvr-red">
                               <input type="text" class="form-control height_mang" id="first_name" name="first_name" placeholder="First Name*" value="{{@$first_name}}" maxlength="50">
                              <p class="error help-block">{{ $errors->first('first_name') }}</p>
                           </div>
                        </div>
                        <div class="col-6">
                           <div class="form-group hvr-red">
                              <input type="text" class="form-control height_mang" id="last_name" name="last_name" placeholder="Last Name*" value="{{@$last_name}}" maxlength="50">
                              <p class="error help-block">{{ $errors->first('last_name') }}</p>
                           </div>
                        </div>
                     </div>
                     <div class="form-group hvr-red">
                        <input type="text" class="form-control height_mang" id="email" name="email" placeholder="Email*" value="{{@$email}}">
                         <p class="error help-block">{{ $errors->first('email') }}</p>
                     </div>
                     <div class="row">
                        <div class="col-2">
                           <div class="form-group hvr-red">
                              <input type="text" class="form-control height_mang" id="country_code" placeholder="+91" name="country_code" value="91">
                           </div>
                        </div>
                        <div class="col-10">
                           <div class="form-group hvr-red">
                               <input type="text" class="form-control height_mang" id="mobile_number" name="mobile_number" placeholder="Mobile Number*" maxlength="10">
                              <p class="error help-block">{{ $errors->first('mobile_number') }}</p>
                           </div>
                        </div>
                     </div>
                     @if($login_type == '3')
                     <div class="form-group  hvr-red">
                       <input type="password" class="form-control height_mang" id="reg_password" name="password" placeholder="Password*">
                        <p class="error help-block">{{ $errors->first('reg_password') }}</p>
                     </div>
                     @else
                     <input type="hidden" name="social_id" id="social_id" value="{{@$social_id}}">
                     @endif
                     <div class="row">
                        <!--<div class="col">
                           <label class="label-font ml-3">Gender</label>
                        </div>-->
                        <div class="col select_gender">
                           <input name="gender" type="radio" id="gender" checked>
                           <div class="media">
                              <div class="media-left">
                                 {!!Html::image('public/webTheme/images/male.png','',['class' => ''])!!}
                              </div>
                              <div class="media-body">
                                 <h6 class="ml-3 mt-3"> Male</h6>
                              </div>
                           </div>
                        </div>
                        <div class="col select_gender">
                           <input name="gender" type="radio" id="gender">
                           <div class="media">
                              <div class="media-left">
                                 {!!Html::image('public/webTheme/images/female.png','',['class' => ''])!!}
                              </div>
                              <div class="media-body">
                                 <h6 class="ml-3 mt-3">Female</h6>
                              </div>
                           </div>
                        </div>
                     </div>
                     <input type="hidden" name="user_type" id="user_type" value="Passenger">
                     <input type="hidden" name="login_type" id="login_type" value="{{@$login_type}}">
                      <input type="hidden" name="device_type" id="device_type" value="web">
                      <input type="hidden" name="browser" id="browser" value="{{ $_SERVER['HTTP_USER_AGENT'] }}">
                     <input type="hidden" name="ip" id="ip" value="{{ Request::ip() }}">

                    
                        <button type="submit" class="btn btn-block theme-btn-new mt-2" id="sign_up">sign up</button>
                     
                   {!! Form::close() !!}
                  
                  <p class="text-center mb-0 my-2">OR SIGN UP WITH</p>
                  <div class="row">
                     <div class="col">  <a href="{{ url('web/social-login/facebook') }}" class="btn btn-block fb-color color-white">{!!Html::image('public/webTheme/images/facebook-white.png','',['class' => 'mr-3'])!!}Facebook</a>
                     </div>
                     <div class=" col">  <a href="{{ url('web/social-login/google') }}" class="btn btn-block google-color color-white ">{!!Html::image('public/webTheme/images/google-plus-white.png','',['class' => 'mr-3'])!!}Google</a>
                     </div>
                  </div>               </div>
            </div>
         </div>
      </div>
      </div>	
      </div>	
      </div>
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type="text/javascript">
    var SITEURL   = '{{URL::to('')."/"}}';
    const APIURL  = SITEURL+"api/";
    const APPCURRENCY  = '$';
    const JS_DATE_FORMAT = "DD-MM-YYYY @ HH:mm";
    var USER_REDIRECT_URL = "";       
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
{!! Html::script('public/webTheme/js/bootstrap.min.js')!!}
{!! Html::script('public/custom/js/jquery.validate.min.js')!!}
{!! Html::script('public/custom/js/from_validation.js')!!}
{!! Html::script('public/custom/js/custom.js')!!}
{!! Html::script('public/custom/plugnis/sweetalert/sweetalert2.js')!!}
<!--  <script src="js/owl.carousel.min.js"></script> -->
<script>
   $(window).load(function(){
   			if($(this).scrollTop()){
   				$("body").addClass("scrolled");
   				}else{
   				$("body").removeClass("scrolled");
   				}
   			})
   		$(window).scroll(function(){
   			if($(this).scrollTop()){
   				$("body").addClass("scrolled");
   				}else{
   				$("body").removeClass("scrolled");
   				}
   			})
   
   
   
   var input_prnt = $(".line-input").parent();
   		$(".line-input").focus(function(e) {
                  $(this).parent().addClass("active");
              });
   		$(".line-input").parent().parent().siblings().focus(function(e) {
                  $(this).parent().removeClass("active");
              });
   		$('html').click(function() {
   			input_prnt.removeClass("active"); 
   		});
   
   		input_prnt.click(function(event){
   			 event.stopPropagation();
   		});
</script> 
<script type="text/javascript">
 //user registration form web validation
 
  $("form[name='RegistrationForm']").validate({
    
    rules: {

      first_name: {
        required: true,
        maxlength: 50,
      },
      last_name: {
        required: true,
        maxlength: 50,
      },
      mobile_number: {
        required: true,
        maxlength: 10,
        minlength:10,
        number:true,

      },
      email: {
        required: true,
        email: true
      },
     password: {
        required: true,
        minlength: 6,
      },
     /* confirm_password: {
        required: true,
        equalTo: "#reg_password",
      },*/
    },
   
    messages: {
     
      first_name: {
        required: "Please enter first name",
        maxlength: "Your first name maxlength should be 50 characters long.",
      },
      last_name: {
        required: "Please enter last name",
        maxlength: "Your last name maxlength should be 50 characters long.",
      },
      mobile_number: {
        required: "Please enter mobile number",
        maxlength: "Your mobile number maxlength should be 14 numeric value long.",
        minlength: "Your mobile number minlength should be 8 numeric value long.",
        number :"Please enter valid mobile number.",
      },
      email: {
        required: "Please enter email",
        email: "Please enter a valid email address.",
      },
      password: {
        required: "Please enter password",
        minlength: "Your password must be at least 6 characters long.",
      },
    /* confirm_password: {
        required: "Please enter confirm password",
        equalTo: "Confirm password should be equal to password.",
      },*/
    },
    
    
    submitHandler: function(form) {

      make_disable_enable('add', 'sign_up', "Please Wait...");
      
      $.ajax({
            url: "{{url('api/passenger-register')}}",
            method:'POST',
            dataType: 'JSON',
            data:$("form").serialize(),
            headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
            success: function(res){
              
              make_disable_enable('remove', 'sign_up', 'Sign Up');

              if (res.code == 200) {
                
                make_disable_enable('add', 'sign_up', 'Redirecting...');

                swal({
                  title:'Success!',
                  text:res.msg,
                  type:'success',
                  timer:2000
                });
                var userInfo = res.result;
                SetSession(userInfo); 
                    
              }else{
                    
                  //show validatiom msg
                  if(res.result != undefined){

                     for(var key in res.result){
                        console.log($("#"+key));
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
            }  
      });
    }
  });

</script>

   </body>
</html>