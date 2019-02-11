<div id="contact" class="container-fluid footer">
   <div class="row">
      <div class="container">
         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 about">
               {!!Html::image('public/webTheme/images/logo.png','',['class' => 'mb-3'])!!}
               <h6  class="color-white">
                  simply dummy text of the printing and Lorem industry's 1500s, when an unknown.
               </h6>
               <ul class="ctm-social">
                  <li>{!!Html::image('public/webTheme/images/facebook.png','',['class' => ''])!!}</li>
                  <li>{!!Html::image('public/webTheme/images/google-plus.png','',['class' => ''])!!}</li>
                  <li>{!!Html::image('public/webTheme/images/twitter.png','',['class' => ''])!!}</li>
                  <li>{!!Html::image('public/webTheme/images/linkedin.png','',['class' => ''])!!}</li>
               </ul>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3  ctm-margin about">
               <h5 class="color-white">Quick Links</h5>
               <ul class="mt-4">
                  <li>{!!Html::image('public/webTheme/images/arrow.png','',['class' => ''])!!}<a href="#" class="ml-2">Home</a></li>
                  <li>{!!Html::image('public/webTheme/images/arrow.png','',['class' => ''])!!}<a href="#" class="ml-2">Services</a></li>
                  <li>{!!Html::image('public/webTheme/images/arrow.png','',['class' => ''])!!}<a href="#" class="ml-2">About Us</a></li>
                  <li>{!!Html::image('public/webTheme/images/arrow.png','',['class' => ''])!!}<a href="#" class="ml-2">Downloads</a></li>
               </ul>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3  about mt-4">
               <ul class="mt-4">
                  <li>{!!Html::image('public/webTheme/images/arrow.png','',['class' => ''])!!}<a href="#" class="ml-2">Contact US</a></li>
                  <li>{!!Html::image('public/webTheme/images/arrow.png','',['class' => ''])!!}<a href="#" class="ml-2">Testimonial</a></li>
                  <li>{!!Html::image('public/webTheme/images/arrow.png','',['class' => ''])!!}<a href="#" class="ml-2">Privacy Policy</a></li>
               </ul>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3  ctm-margin about">
               <h5 class="color-white">Contact Us</h5>
               <address class="mt-4 color-white"><b>
                  Lorem 17/45, Tardeo Air Conditioned Mkt, Tardeo Rd, Tardeo, 400034, <br>
                  Mumbai,  Maharashtra, India</b>
               </address>
               <br>
               <br>
               <div class="media">
                  {!!Html::image('public/webTheme/images/message.png','',['class' => 'mr-3 mt-1','alt'=>"Generic placeholder image"])!!}
                  <div class="media-body">
                     <h6 class="color-white">dummyemail@gmail.com </h6>
                  </div>
               </div>
               <div class="media">
                   {!!Html::image('public/webTheme/images/phone.png','',['class' => 'mr-3 mt-1','alt'=>"Generic placeholder image"])!!}
                  <div class="media-body">
                     <h6 class="color-white">9547812545 </h6>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="text-center footer-end">
   <p class="color-white mb-0">All Rights Reserved | Copyright<span class="color-green"> EcoGadi </span>2018</p>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
{!! Html::script('public/webTheme/js/bootstrap.min.js')!!}
{!! Html::script('public/webTheme/js/owl.carousel.min.js')!!}
{!! Html::script('public/custom/js/custom.js')!!}

<!-- {!! Html::script('public/custom/js/owl.carousel.min.js')!!} -->


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
      });  
</script>

<script>
         
         $(document).ready(function(){
         $(".menu-open").click(function() {
            $("#sidebar-wrapper").toggleClass("active");
            $(".menu-open > .fa-bars, .menu-open > .fa-times").toggleClass("fa-bars fa-times");
            $(this).toggleClass("active");
          });
         $('#sidebar-wrapper .js-scroll-trigger').click(function() {
            $("#sidebar-wrapper").removeClass("active");
            $(".menu-open").removeClass("active");
            $(".menu-open > .fa-bars, .menu-open > .fa-times").toggleClass("fa-bars fa-times");
          });
         });
            
      </script>
<script>
      $(document).ready(function(){
  $("a").on('click', function(event) {
    if (this.hash !== "") {
      event.preventDefault();
     var hash = this.hash;
      $('html, body').animate({
       scrollTop: $(hash).offset().top
     }, 800, function(){
        window.location.hash = hash;
      });
    } // End if
  });
});
</script>


<script>
$('#slider1').owlCarousel({
			nav:false,
			loop:true,
		    margin:10,
		    responsive:{
		        0:{
		            items:1
		        },
		        600:{
		            items:2
		        },
		        1000:{
		            items:3
		        }
		    }
					
		});

</script>
<script>
//var bodyEl = $("body");
//			$(window).on("scroll", function() {
//				var scrollTop = $(this).scrollTop()+400;
//				$("section").each(function() {
//					var el = $(this),
//						className = el.attr("id");
//					if (el.offset().top < scrollTop) {
//						bodyEl.addClass(className);  
//					} else {
//						bodyEl.removeClass(className);
//					}
//				});
//			});

</script>
@yield('script')

</body>
</html>