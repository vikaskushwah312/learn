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
      <!-- {!!Html::Style('public/webTheme/css/animate.min.css')!!}
      {!!Html::Style('public/webTheme/css/owl.carousel.min.css')!!} -->
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
                <a href="{{URL('web')}}">
                  {!!Html::image('public/webTheme/images/logo1.png','',['class' => 'mt-3',"style" => ''])!!}
                </a>
               </div>
               <div class="row my-3">
               <div class="col">
               <h3 class="text-center text-uppercase heading_sec">Sign In To Your Account</h3>
               <p class="text-center m-b-0">Don't have an Account? Please <span class="text_bold"><a href="{{url('/registration')}}" class="color-green"> Sign Up</a></span></p>
               </div>
               </div>
               <div class="login-form px-5 text-left">
                   {!! Form::open(array('id'=>"login_Form", 'name'=>"login_Form",'files'=>true,'method' => 'POST','class' => '')) !!}
                     <div class="form-group hvr-red">
                        <input type="text" class="form-control height_mang" id="login_email"  name="email" placeholder="Mobile Number / Email*">
                          <span  class="error help-block"></span>
                          @if ($errors->has('email'))
                          <p class="error help-block">{{ $errors->first('email') }}
                          </p>
                          @endif
                        
                     </div>
                     <div class="form-group hvr-red">
                       <input type="password" class="form-control height_mang" id="login_password" name="password" placeholder="Password*">
                          <span class="error help-block"></span>
                          @if ($errors->has('password'))
                          <p class="error help-block">{{ $errors->first('password') }}
                          </p>
                          @endif
                     </div>
                     <input type="hidden" name="device_type_login" id="device_type_login" value="web">
                      <input type="hidden" name="user_type_login" id="user_type_login" value="Passenger">
                     <input type="hidden" name="login_type_login" id="login_type_login" value="3">
                      <input type="hidden" name="ip_login" id="ip_login" value="{{ Request::ip() }}">
                      <input type="hidden" name="browser_login" id="browser_login" value="{{ $_SERVER['HTTP_USER_AGENT'] }}">
                     <div class="row">
                        <div class="col-md-12">
                  <button type="submit" class="btn btn-block theme-btn-new" id="sign_in">LOG IN</button>
               </div>
               <div class="col-md-12">
                        <div class="col-md-12 text-center mt-3">
                           <a href="{{url('/forgot-password')}}"  class="color-gray">Forgot Password?</a>
                        </div>
                        </div>
                        <!--<div class="col-md-12 text-center">
                           <button type="submit" class="btn theme-btn" id="sign_in">LOG IN</button>
                        </div>-->
                  </form>
                  </div>
                  
                  <hr class="">
                  <p class="text-center">OR SIGN IN WITH</p>
                  <div class="row mt-3">
                     <div class="col">  <a href="{{ url('web/social-login/facebook') }}" class="btn btn-block fb-color color-white">{!!Html::image('public/webTheme/images/facebook-white.png','',['class' => 'mr-3'])!!}Facebook</a>
                     </div>
                     <div class=" col">  <a href="{{ url('web/social-login/google') }}" class="btn btn-block google-color color-white ">{!!Html::image('public/webTheme/images/google-plus-white.png','',['class' => 'mr-3'])!!}Google</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script type="text/javascript">
          var SITEURL   = '{{URL::to('')."/"}}';
          const APIURL  = SITEURL+"api/";
          const APPCURRENCY  = '$';
          const JS_DATE_FORMAT = "DD-MM-YYYY @ HH:mm";
          var USER_REDIRECT_URL = "";       
      </script>
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <!-- Include all compiled plugins (below), or include individual files as needed -->
      {!! Html::script('public/webTheme/js/bootstrap.min.js')!!}
      {!! Html::script('public/custom/js/jquery.validate.min.js')!!}
      {!! Html::script('public/custom/js/from_validation.js')!!}
      {!! Html::script('public/custom/js/custom.js')!!}
      {!! Html::script('public/custom/plugnis/sweetalert/sweetalert2.js')!!}
     <!--  {!! Html::script('public/webTheme/js/owl.carousel.min.js')!!} -->
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
         
  $("form[name='login_Form']").validate({
    
    rules: {

      email: {
        required: true,
        //email: true
      },
      password: {
        required: true,
        minlength: 5
      }
    },
   
    messages: {
     
      password: {
        required: "Please enter password",
        minlength: "Your password must be at least 5 characters long"
      },
      email: {
        required: "Please enter email",
        //email: "Please enter a valid email address"
      },
    },
    
    
    submitHandler: function(form) {

          var password    = $("#login_password").val();
          var device_type = $("#device_type_login").val();
          var login_type  = $("#login_type_login").val();
          var ip          = $("#ip_login").val();
          var browser     = $("#browser_login").val();
           var user_type  = $("#user_type_login").val();

          if($.isNumeric($("#login_email").val())){

            var mobile_number = $("#login_email").val();
     
            var postData = {'mobile_number':mobile_number,'password':password,'device_type':device_type,'login_type':login_type,'ip':ip,'browser':browser,'user_type':user_type};

          }else{

            var email = $("#login_email").val();
            var postData = {'email':email,'password':password,'device_type':device_type,'login_type':login_type,'ip':ip,'browser':browser,'user_type':user_type};
          }
          make_disable_enable('add', 'sign_in', "Please Wait...");
          
          $.ajax({
                url: "{{url('api/login')}}",
                method:'POST',
                dataType: 'JSON',
                data:postData,
                headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(res){

                  make_disable_enable('remove', 'sign_in', 'Sign In');

                  if (res.success == true) {

                        make_disable_enable('add', 'sign_in', 'Redirecting...');

                       var user_redirect_url = "{{ Session::get('user_redirect_url') }}";

                        SetSession(res.result, user_redirect_url);

                        if(res.result.mobile_verification_status == "Verified"){
                          //alert(user_redirect_url);
                          if (user_redirect_url != "") {
                           
                            window.location.href = user_redirect_url;

                          }else{
                            
                            window.location.href = "{{url('/ride-now')}}";
                          }

                        }else{

                          var userId = res.result.id;
                          window.location.href = "{{url('/web/mobile-verification')}}/"+userId;
                        } 
                        
                  }else{
                        

                        //show validatiom msg
                        if(res.result != undefined){

                           for(var key in res.result){
                            
                                $("#login_"+key).parent().find('.help-block').html(res.result[key]).show();

                                if(key == "mobile_number"){
                        
                                    $("#login_email").parent().find('.help-block').html(res.result[key]).show();
                                }
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