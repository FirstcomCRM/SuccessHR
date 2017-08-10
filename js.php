    <!-- jQuery 2.1.4 -->
    <script type="text/javascript" src="<?php echo include_webroot;?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script type="text/javascript" src="<?php echo include_webroot;?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo include_webroot;?>plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo include_webroot;?>plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo include_webroot;?>plugins/datepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="<?php echo include_webroot;?>plugins/jqueryvalidation/jquery.validate.1.8.js"></script>
    <script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>
    <script src="<?php echo include_webroot;?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- bootstrap time picker -->
    <script src="<?php echo include_webroot;?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
     <!--SlimScroll--> 
    <script src="<?php echo include_webroot;?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo include_webroot;?>/plugins/fastclick/fastclick.min.js"></script>
    <!-- Select2 -->
    <script type="text/javascript" src="<?php echo include_webroot;?>plugins/select2/select2.full.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="<?php echo include_webroot;?>/plugins/fullcalendar/fullcalendar.min.js"></script>    
    <!-- SlimScroll -->
    <!--<script src="<?php echo include_webroot;?>plugins/slimScroll/jquery.slimscroll.min.js"></script>-->
    <!-- Select2 -->
    <script type="text/javascript" src="<?php echo include_webroot;?>plugins/select2/select2.full.js"></script>


    <!-- AdminLTE App -->
    <script type="text/javascript" src="<?php echo include_webroot;?>dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    
    <script>
  
      var system_gst_percent = "<?php  echo system_gst_percent;?>";
      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        $('.datepicker').datepicker({
            format: 'dd-M-yyyy',
            autoclose: true,
            pickerPosition: "bottom-left"
        });
        $(".timepicker").timepicker({
          showInputs: false
        });
        
        
        $('.notice_linking').click(function(){
            var data = "action=updateNotification&noti_id="+$(this).attr("pid");
            var linkHref = $(this).attr("id");
            window.location.href = linkHref;
            $.ajax({ 
                type: 'POST',
                url: 'applicant.php',
                cache: false,
                data:data,
                error: function(xhr) {
                    alert("System Error.");
                    issend = false;
                },
                success: function(data) { 
                    //var jsonObj = eval ("(" + data + ")");
                   issend = false;

                }		
             });
             return false;                       
         });    
          
        });
      
      
      
      function confirmAlertHref(url,message){
            if(confirm(message)){
                window.location.href = url;
            }else{
                return false;
            }
      }
      function changeNumberFormat(nStr){
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
       }
       function RoundNum(num, length) { 
            var number = parseFloat(Math.round(num * Math.pow(10, length)) / Math.pow(10, length)).toFixed(2);
            return number;
       }
    </script>
