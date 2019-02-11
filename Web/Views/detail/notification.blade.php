@extends('Web::layout.master_inner')
@section('css')
  {!! Html::style('public/custom/plugnis/fancybox/source/jquery.fancybox.css') !!}  
@endsection
@section('content')
<!--<div class="container-fluid">
         <div class="row">
            <div class="col">
               <div class="row menu p-3">
                  <div class="col">
                     <a href="#" class="menu-open">
                     <i class="fa fa-bars fa-set fa-2x" aria-hidden="true" ></i>
                     </a>
                     
                  </div>
                  <div class="col text-center">
                     <img src="#" class="ride-menu">
                  </div>
                  <div class="col">
                     <img src="#" class="float-right">
                  </div>
               </div>
            </div>
         </div>
      </div>-->
      <div class="container-fluid bg-light">
         
            <div class="container">
               <div class="row">
                  <div class="col">
                     <h3 class="my-5"><strong>Notifications</strong></h3>
                  </div>
               </div>
               <div class="row py-4" id="element" >
                        
               </div>

               <div id="no_recored">
                                    
               </div>
               <div class="col-sm-12 text-center" id="load_more_div">
                     <a href="javascript:void(0)" type="button" class="btn theme_btn" id="load_more_btn" onclick="load_more()" style="color:white;background-color:#a5c314;margin-top:20px;">Load More</a>
               </div>
               <div class="row my-4">
                                   
               </div>
              
            </div>
         
      </div>
@endsection
@section('script') 

<script type="text/javascript">
var page = 12;
   function notification(){
	
	$.ajax({

		url:"{{url('api/notifications')}}",
		method:'get',
		data:{"page":page},
		dataType: 'JSON',
		headers  : {'Authorization': "{{'Bearer '.Session::get('set_token')}}"},

		success:function(res){

         var html = "";
         
			if (res.code == 200) {
            
            //console.log(res);

               for (var i = 0; i < res.result.length; i++) {

                  var counter = i + 1;
         
                  html = html + '<div class="col-sm-6 py-3">'+
                                 '<div class="fancy-box">'+
                                    '<div class="row p-3">'+
                                       '<div class="col-sm-11">'+
                                          '<div class="media">'+
                                             '{!!Html::image("public/webTheme/images/notifications.png","",["class" => "pro-img"])!!}'+
                                             '<div class="media-body  ml-3 ">'+
                                                '<h6 class="mt-0 mb-0 text-uppercase">'+res.result[i].title+'</h6>'+
                                                '<p class="color-green mb-0"> 23 Jan,2018</p>'+
                                                '<p class="mb-0 f-size ">'+res.result[i].message+'</p>'+
                                             '</div>'+
                                          '</div>'+
                                       '</div>'+
                                       '<div class="col-sm-1  float-right">'+
                                          '<div class="float-right">'+
                                             '<img src="{{url('public/webTheme/images/cross.png')}}" onclick = "removeNotification('+res.result[i].id+');">'+
                                          '</div>'+
                                       '</div>'+
                                    '</div>'+
                                 '</div>'+
                              '</div>';                  
               }

               var noOfRecords = 1;


                     if (page == 1) {

                        $("#element").html(html);
                         
                        if (res.total < page * noOfRecords ) {

                        $("#load_more_div").hide();

                        }
                        page = page + 1;

                     } else{

                        $("#element").append(html);

                        page = page + 1;

                        if (res.total < page * noOfRecords) {

                           $("#load_more_div").hide();

                        }
                     }

               //$("#element").html(html);

            
          
			} else {

            //if no recored found
            $("#load_more_div").hide();
            $("#no_recored").html('<h4 style="text-align:center;">'+res.msg+'</h4>');

			}
		}

	});
}/*function*/
notification();


/**************load_more function *********/
function load_more(){
//alert("asdf")
   notification();
}

/**********Delete The Notification************/
function removeNotification(id){
   
   swal({
              
      title: 'Are you sure?',
      text: "you want to delete this Notification",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#52b229',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Delete!'

      }).then(function () {
            $.ajax({

               url:"{{url('api/delete-notification')}}",
               method:"post",
               data:{'notification_id':id},
               headers  : {'Authorization': "{{'Bearer '.Session::get('set_token')}}"},

               success:function(res){
                  if(res.code == 200){
                   
                     swal({

                        title :"success",
                        text  :res.msg,
                        type  :'success',
                        
                     }).then(function() {

                        window.location.href = "{{url('/notification')}}";
                     });
                  } else {
                     swal({
                          title:'Oops!',
                          text:"some thing went wrong!!",
                          type:'error',
                          timer:2000
                        });
                  }
               }
            });
         
      });

}
</script>

@endsection