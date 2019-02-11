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
      <div class="container-fluid pl-0">
         <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-7 md-auto bg-auto">
               <!-- <img src="images/auto-background.png" class="auto-login-back">-->
               <div class="para-box">
                  <h1 class="color-white text-uppercase"><b>Loreum ipsum doler</b></h1>
                  <h6 class="color-white">Lorem ipsum dolor sit amet consectetur adipiscing elit </h6>
               </div>
            </div>
            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-5 pl-0 text-center">
               <div class="bg-login">
                   {!!Html::image('public/webTheme/images/update.png','',['class' => 'mt-5 img-head'])!!}
                  <p class="mt-5">We use your Mobile number to send you<br>
                     ride confirmations & receipts.
                  </p>
               </div>
               <div class="login-form px-5  py-5 mt-5 text-left">
                  {!! Form::open(array('id'=>"changeNumberForm", 'name'=>"changeNumberForm",'files'=>true,'method' => 'POST','class' => 'mt-top')) !!}
                     <div class="row">
                        <div class="col-2">
                           <div class="form-group mb-4">
                              <input type="text" class="form-control height_mang" id="country_code" placeholder="91" name="country_code" value="91">
                           </div>
                        </div>
                        <div class="col-10">
                           <div class="form-group mb-4 hvr-red">
                              <input type="text" class="form-control help-block" name="mobile_number" id="mobile_number" placeholder="Mobile Number*" maxlength="10">
                           </div>
                        </div>
                     </div>
                     <div class="mt-5">
                        <button type="submit" class="btn btn-block theme-btn-new" id="submit_btn">save & verify</button>
                     </div>
                  {!! Form::close() !!}
               </div>
            </div>
         </div>
      </div>
      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
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
         $("form[name='changeNumberForm']").validate({
    
    rules: {

      mobile_number: {
        required: true,
        number:true,
        maxlength: 10,
        minlength: 10,
      },
      
    },
   
    messages: {
     
      mobile_number: {
        required: "Please enter mobile number.",
        number: "Mobile number should be numeric",
        maxlength: "Your mobile number should be 10 characters long",
        minlength: "Your mobile number should be 10 characters long",
      },
    },
    
    submitHandler: function(form) {

      make_disable_enable('add', 'submit_btn', "Please Wait...");

      $.ajax({
            url: "{{url('api/change-mobile-number')}}",
            method:'POST',
            dataType: 'JSON',
            data:$("form").serialize(),
            headers  : {'Authorization': "{{'Bearer '.Session::get('set_token')}}"},
            success: function(res){

              make_disable_enable('remove', 'submit_btn', "Submit");
              
              if (res.code == 200) {
                  
                 // updateSession();

                  swal({
                      title:'success!',
                      text:res.msg,
                      type:'success',
                      timer:2000
                  });
                  
                  setTimeout(function(){ 

                    var userId = "{{Session::get('id')}}";
                    window.location.href = "{{url('web/mobile-verification')}}/"+userId;
                     
                  }, 
                  1000);
                    
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