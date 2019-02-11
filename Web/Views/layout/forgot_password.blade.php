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
     <!--  <link href="css/animate.css" rel="stylesheet">
      <link href="css/owl.carousel.min.css" rel="stylesheet"> -->
      <link href="https://fonts.googleapis.com/css?family=Baumans" rel="stylesheet">
   </head>
   <body>
      <!-- Page Header -->
      <div class="container-fluid px-0" id ="forgot_password_page" style ="display: block;">
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
                  {!!Html::image('public/webTheme/images/forgot.png','',['class' => 'mt-5 img-head'])!!}
                 
                  <p class="mt-5">Don’t worry! Just enter your Email ID below<br>
                     and we’ll send you the password reset instructions.
                  </p>
               </div>
               <div class="login-form px-5  py-5 mt-5 text-left">

                {!! Form::open(array('id'=>"forgotpassword_Form", 'name'=>"forgotpassword_Form",'files'=>true,'method' => 'POST','class' => 'mt-top')) !!}
                     <div class="form-group hvr-red">
                        <input type="text" class="form-control height_mang" id="email"  name="email" placeholder="Mobile Number / Email">
                          <span  class="error help-block"></span>
                                                </div>
                     <input type="hidden" name="user_type" id="user_type" value="Passenger">
                     <div class=" mt-5">
                        <button type="submit" class="btn btn-block theme-btn-new" id="forgot_btn">Submit</button>
                        <div class="row my-4">
                       <div class="col text-center">
                       <span  class="text_bold"><a href="{{url('/login')}}"> Remamber us?</a></span>
                      </div>
                      <div class="col text-center">
                       <span  class="text_bold"><a href="{{url('/login')}}"> SignIn</a></span>
                      </div>
                     </div>
                     </div>
                  {!! Form::close() !!}
               </div>
            </div>
         </div>
      </div>
       <!-- reset password view -->
      <div class="container-fluid px-0" id="reset_password_page" style="display: none;">
         <div class="row">
            <div class="col-sm-7 md-auto bg-auto">
               <!-- <img src="images/auto-background.png" class="auto-login-back">-->
               <div class="para-box">
                  <h1 class="color-white text-uppercase"><b>Loreum ipsum doler</b></h1>
                  <h6 class="color-white">Lorem ipsum dolor sit amet consectetur adipiscing elit </h6>
               </div>
            </div>
            <div class="col-sm-5 px-0 text-center">
               <div class="bg-login">
                  {!!Html::image('public/webTheme/images/forgot.png','',['class' => 'mt-5 img-head'])!!}
                  <p class="mt-5">Don’t worry! Just enter your Email ID below<br>
                     and we’ll send you the password reset instructions.
                  </p>
               </div>
               <div class="login-form px-5  py-5 mt-5 text-left">
                {!! Form::open(array('id'=>"resetpassword_Form", 'name'=>"resetpassword_Form",'files'=>true,'method' => 'POST','class' => 'mt-top')) !!}
                     <div class="form-group mb-4 hvr-red">
                        <input type="password" class="form-control height_mang" id="reset_password" name="reset_password" placeholder="Password">
                        <p class="error help-block"></p>
                     </div>
                     <div class="form-group mb-4 hvr-red">
                       <input type="password" class="form-control height_mang" id="reset_confirm_password" name="reset_confirm_password" placeholder="Confirm Password">
                        <p class="error help-block"></p>
                     </div>
                     <div class="form-group mb-4 hvr-red">
                       <input type="text" class="form-control height_mang" id="verification_code" name="verification_code" placeholder="Verification Code" maxlength="4">
                        <p class="error help-block"></p>
                     </div>
                      <input type="hidden" name="email" id="email" value="">
                      <input type="hidden" name="mobile_number" id="mobile_number" value="">

                     <div class="mt-5">
                        <button type="submit" class="btn btn-block theme-btn-new" id="reset_btn">Reset Password</button>
                     </div>
                  {!! Form::close() !!}
               </div>
            </div>
         </div>
      </div>
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
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
$("form[name='forgotpassword_Form']").validate({
    
    rules: {

      email: {
        required: true,
      },
      
    },
   
    messages: {
     
      email: {
        required: "Please enter email / number.",
      },
    },
    
    
    submitHandler: function(form) {

      var getval = $("#email").val();

      if($.isNumeric(getval)){

        var mobile_number = getval;
        
      }else{

        var email = getval;
        
      }
      var user_type = $("#user_type").val();
      make_disable_enable('add', 'forgot_btn', 'Please Wait..');

      $.ajax({
            url: "{{url('api/forgot-password')}}",
            method:'POST',
            dataType: 'JSON',
            data:{'email':email,'mobile_number':mobile_number,'user_type':user_type},
            headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(res){

              make_disable_enable('remove', 'forgot_btn', 'Submit');

              if (res.code == 200) {
              //console.log(200);
                    
                    $.cookie('forgot_email', null);
                    $.cookie('forgot_mobile_number', null);
                    $.cookie('forgot_verification_code', null);
                    var data = res.result;
                    $.cookie("forgot_email", email);
                    $.cookie("forgot_mobile_number", mobile_number);
                    $.cookie("forgot_verification_code", res.result);
                    $("#forgot_password_page").hide();
                    $("#reset_password_page").show();
                    swal({
                          title:'success!',
                          text:res.msg,
                          type:'success',
                          timer:2000
                        });
                    
              }else{
                                      
                    //show validatiom msg
                    if(res.code == 401){
                        
                      for(var key in res.result){
                      
                        $("#email").parent().find('.help-block').html(res.result[key]).show();
                      
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
//reset password script 
  $("form[name='resetpassword_Form']").validate({
    
    rules: {

     reset_password: {
        required: true,
        minlength: 6,
      },
      reset_confirm_password: {
        required: true,
        equalTo: "#reset_password",
      },
      verification_code: {
        required: true,
        
      },
    },
   
    messages: {
     
      reset_password: {
        required: "Please enter password",
        minlength: "Your password must be at least 6 characters long.",
      },
     reset_confirm_password: {
        required: "Please enter confirm password",
        equalTo: "Confirm password should be equal to password.",
      },
       verification_code: {
        required: "Please enter verification code.",
      },
    },
    
    
    submitHandler: function(form) {

     
      if($.cookie('forgot_email') == 'undefined' || $.cookie('forgot_email') == 'null'){

      }else{

          var email = $.cookie('forgot_email');
          var requestData = {'password':$("#reset_password").val(),'email':email,'verification_code':$("#verification_code").val()};
      }
      
      if($.cookie('forgot_mobile_number') == 'undefined' || $.cookie('forgot_mobile_number') == 'null'){

      }else{

          var mobile_number = $.cookie('forgot_mobile_number');
          var requestData = {'password':$("#reset_password").val(),'mobile_number':mobile_number,'verification_code':$("#verification_code").val()};
      }
       make_disable_enable('add', 'reset_btn', 'Please Wait..');

      $.ajax({

            url: "{{url('api/reset-password')}}",
            method:'POST',
            dataType: 'JSON',
            data:requestData,
            headers  : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(res){

              make_disable_enable('remove', 'reset_btn', 'Submit');
              
              if (res.code == 200) {
                 
                $.cookie('forgot_email', null);
                $.cookie('forgot_mobile_number', null);
                $.cookie('forgot_verification_code', null);
                //window.location.href = "http://192.168.11.76/taxi/web/login/";
                swal({
                      title:'success!',
                      text:res.msg,
                      type:'success',
                      //timer:2000
                    }).then(function(){

                        window.location.href = "{{url('/login')}}";    
                    });
                  
              }else{
                    
                    //show validatiom msg
                    if(res.result != undefined){

                        for(var key in res.result){

                          
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