
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">

    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <?php echo date('Y') ?> <a href="#">Eco Gadi</a></strong> All rights reserved.
  </footer>

  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->



<!-- END GET NOTIFICATION -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
{!! Html::script('public/adminTheme/bower_components/jquery/dist/jquery.min.js') !!}

<!-- Bootstrap 3.3.7 -->
{!! Html::script('public/adminTheme/bower_components/bootstrap/dist/js/bootstrap.min.js') !!}

<!-- AdminLTE App -->
{!! Html::script('public/adminTheme/dist/js/adminlte.min.js') !!}

<!-- Validation -->
{!! Html::script('public/custom/js/jquery.validate.min.js')!!}
{!! Html::script('public/custom/js/from_validation.js')!!}

<!-- Datatables -->
{!! Html::script('public/adminTheme/bower_components/datatables.net/js/jquery.dataTables.min.js')!!}
{!! Html::script('public/adminTheme/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')!!}

{!! Html::script('public/custom/js/custom.js')!!}

@yield('script')

  <!-- GET NOTIFICATION WHEN ORDER CREATED -->
    
    <script type="text/javascript">
      
      $(document).ready(function() {
       
       var soundUrl       = "{{ url('public/sound/Desk-bell-sound.mp3')}}";
       var RidesImageUrl  = "{{ url('public/custom/images/auto_r.png')}}";
       
       function get_rides(){
        
         $.ajax({
            url: "{{ url('admin/get-new-rides')}}",
            type: 'POST',
            data: {'type':'NEW_RIDES'},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            dataType:'JSON',
            success:function(res){ 
               
                $("#admin_notification_badge").html(res.count); 
           
               for (i = 0; i < res.rides.length; i++) { 
                  notifyMe(res.rides[i]['id']);   
                }
            }
          });
       }

      get_rides();

      setInterval(function(){ 
          
        get_rides();
      }, 
      10000);

      function notifyMe(id) {

        var grant = false;

        if (!("Notification" in window)) {
        
          alert("This browser does not support desktop notification");
        
        }else if (Notification.permission === "granted") {
          
          grant = true;

        }else if (Notification.permission !== "denied") {
        
          Notification.requestPermission(function (permission) {
         
            if (permission === "granted") {
             
              grant = true;

            }
          });
        }

        if (grant) {
             
              var newRidesUrl = "{{url('admin/ride-detail/') }}/"+id;
            
              var body = "Great! You have new rides.";
              
              var options = {
                body: body,
                silent: true,
                icon: RidesImageUrl,
                sound: soundUrl,
               
              };

              var title = "New Rides";

              var notification = new Notification(title, options);
              
              notification.onclick = function(event) {
               
                event.preventDefault(); // prevent the browser from focusing the Notification's tab
                window.open(newRidesUrl, '_blank');
              }

              var audio = new Audio(soundUrl);
              audio.play();
            
        }

      }

    });
  </script>

  @yield('script2')
</body>
</html>