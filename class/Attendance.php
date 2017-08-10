<?php
/*
 * To change this tattendanceate, choose Tools | Tattendanceates
 * and open the tattendanceate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Attendance {

    public function Attendance(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        include_once 'class/Excel_reader2.php';
        $table_field = array('attendance_date','attendance_remark','attendance_empl','attendance_status',
                             'attendance_ottotal','attendance_latetotal');
        $table_value = array(format_date_database($this->attendance_date),$this->attendance_remark,$this->attendance_empl,1,
                             $this->attendance_ottotal,$this->attendance_latetotal);
        $remark = "Insert Apply Attendance.";
        if(!$this->save->SaveData($table_field,$table_value,'db_attendance','attendance_id',$remark)){
           return false;
        }else{
           $this->attendance_id = $this->save->lastInsert_id;
           $this->database = "db_attentime";
           $this->sqlupdate = " attendance_id = '$this->attendance_id',";
           $data = new Spreadsheet_Excel_Reader($_FILES["file"]["tmp_name"]);
           $this->calculateAttendance($data);
           return true;
        }
    }
    public function update(){
        include_once 'class/Excel_reader2.php';
        $table_field = array('attendance_date','attendance_remark','attendance_empl',
                             'attendance_ottotal','attendance_latetotal');
        $table_value = array(format_date_database($this->attendance_date),$this->attendance_remark,$this->attendance_empl,
                             $this->attendance_ottotal,$this->attendance_latetotal);
        $remark = "Update Apply Attendance.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_attendance','attendance_id',$remark,$this->attendance_id)){
           return false;
        }else{
           $this->database = "db_attentime";
           $this->sqlupdate = " attendance_id = '$this->attendance_id',";
           if($_FILES["file"]["size"] > 0){
                $data = new Spreadsheet_Excel_Reader($_FILES["file"]["tmp_name"]);
                $this->calculateAttendance($data);
           }
           return true;
        }
    }
    public function fetchAttendanceDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_attendance WHERE attendance_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->attendance_id = $row['attendance_id'];
            $this->attendance_date = $row['attendance_date'];
            $this->attendance_remark = $row['attendance_remark'];
            $this->attendance_empl = $row['attendance_empl'];
            $this->attendance_ottotal = $row['attendance_ottotal'];
            $this->attendance_latetotal = $row['attendance_latetotal'];
        }
        return $query;
    }
    public function delete(){
        $table_field = array('attendance_status');
        $table_value = array(0);
        $remark = "Delete Attendance.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_attendance','attendance_id',$remark,$this->attendance_id)){
           return false;
        }else{
           return true;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        include_once 'class/Empl.php';
        $e = new Empl();
        if($action == 'create'){
            $this->attendance_seqno = 10;
            $this->attendance_status = 1;
            $this->attendance_amount = 0;
            $this->attendance_date = system_date;
        }
    $this->employeestypeCrtl = $this->select->getEmployeeSelectCtrl($this->attendance_empl);
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Attendance Management</title>
    <?php
    include_once 'css.php';

    ?>    
    <style>
        .tablenoborder tbody tr td{
            border:none ;
        }
        .tablenoborder tbody tr td{
            border:none ;
        }
        .table-empl-detail td:nth-child(3){
            font-weight: bold;
        }
        .empl-icon-label a i{
            font-size:22px;
        }
    </style>
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <!-- include header-->
      <?php include_once 'header.php';?>
      <!-- Full Width Column -->
      <div class="content-wrapper">
          <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Attendance Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->attendance_id > 0){ echo "Update Attendance";}else{ echo "Apply New Attendance";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='attendance.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='attendance.php?action=createForm'">Create New</button>
                <?php }?>
              </div>

                <form id = 'attendance_form' class="form-horizontal" action = 'attendance.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="col-sm-8">  
                     <div class="form-group">
                         <label for="attendance_date" class="col-sm-2 control-label">Date</label>
                      <div class="col-sm-4">
                          <input type="text" class="form-control datepicker" id="attendance_date" name="attendance_date" value = "<?php echo format_date($this->attendance_date);?>" placeholder="Date" <?php echo $disabled;?>>
                      </div>
                     </div> 
<!--                     <div class="form-group">
                         <label for="attendance_autofilter" class="col-sm-2 control-label">Auto Filter</label>
                      <div class="col-sm-4">
                          <input type="checkbox" id="attendance_autofilter" name="attendance_autofilter" <?php echo $disabled;?>>
                      </div>
                     </div>-->
                     <div class="form-group">   
                      <label for = "attendance_empl" class="col-sm-2 control-label">Employee <?php echo $mandatory;?></label>
                      <div class="col-sm-4">
                          <select class="form-control select2" id="attendance_empl" name="attendance_empl">
                            <?php echo $this->employeestypeCrtl;?>
                          </select>
                      </div>
                     </div>
                     <div class="form-group">
                         <label for="file" class="col-sm-2 control-label">Attendance List</label>
                      <div class="col-sm-4">
                          <input type="file" class="form-control" id="file" name="file" <?php echo $disabled;?>>
                      </div>
                     </div> 
                     <div class="form-group">
                         <label for="attendance_latetotal" class="col-sm-2 control-label">Total Lateness</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="attendance_latetotal" name="attendance_latetotal" <?php echo $disabled;?> value = '<?php echo $this->attendance_latetotal;?>'>
                        </div>
                          <label for="attendance_ottotal" class="col-sm-2 control-label">Total OverTimes</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="attendance_ottotal" name="attendance_ottotal" <?php echo $disabled;?> value = '<?php echo $this->attendance_ottotal;?>' >
                        </div>
                     </div> 
                    <div class="form-group">
                      <label for="attendance_remark" class="col-sm-2 control-label">Remark</label>
                      <div class="col-sm-4">
                            <textarea class="form-control" rows="3" id="attendance_remark" name="attendance_remark" placeholder="Remark" <?php echo $disabled;?>><?php echo $this->attendance_remark;?></textarea>
                      </div>

                    </div> 
                    </div>
                      
                    <div class="col-sm-12" id = 'attendance_table'>    
                        <table class = 'table' >
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Device ID</th>
                                <th>Actual Time</th>
                                <th>Start Time</th>
                                <th>Latecomers</th>
                                <th>End Time</th>
                                <th>OverTimes</th>
                            </tr>
                    <?php
                        $sql = "SELECT at.*,ad.attendance_sottotal,ad.attendance_slatetotal
                                FROM db_attentime at
                                INNER JOIN db_attendance ad ON ad.attendance_id = at.attendance_id
                                WHERE at.attendance_id = '$this->attendance_id'";
                        $query = mysql_query($sql);
                        while($row = mysql_fetch_array($query)){
                            if($row['actual_time'] == ''){
                                $actual_time = $row['date'];
                            }else{
                                $actual_time = $row['actual_time'];
                            }
                            $attendance_ottotal = $row['attendance_sottotal'];
                            $attendance_latetotal = $row['attendance_slatetotal'];
                        ?>
                            <tr>
                                <td><?php echo $row['ID'];?></td>
                                <td><?php echo $row['name'];?></td>
                                <td><?php echo $row['device_id'];?></td>
                                <td><?php echo $actual_time;?></td>
                                <td><?php echo $row['start_work_time'];?></td>
                                <td style = 'color:red;'><?php echo $row['late_time'];?></td>
                                <td><?php echo $row['end_work_time'];?></td>
                                <td style = 'color:green;'><?php echo $row['over_time'];?></td>
                            </tr>
                        <?php

                        }
                    ?>
                            <tr>
                                <td colspan = '5' align = 'right'>Total : </td>
                                <td style = 'color:red;'><?php echo $attendance_latetotal;?> minutes</td>
                                <td></td>
                                <td style = 'color:green;'><?php echo $attendance_ottotal;?> minutes</td>
                            </tr>
                        </table>
                    </div>
                    <div style = 'clear:both'></div>  
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                      

                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Back</button>
                    &nbsp;&nbsp;&nbsp;
                      <button type="button" class="btn btn-default" id = 'check_atten'>check</button>
                       &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->attendance_id;?>" name = "attendance_id"/>
                    <?php 
                    if($this->attendance_id > 0){
                        $prm_code = "update";
                    }else{
                        $prm_code = "create";
                    }
                            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){
                    ?>
                            <button type = "submit" name = 'submit_btn' value = 'Save' class="btn btn-info">Save</button>
                            <?php 
                            }
?>
                  </div><!-- /.box-footer -->
                  
                </form>
            </div><!-- /.box -->
          </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';
    
    ?>

    <script>
    $(document).ready(function() {
       
        $("#attendance_form").validate({
                  rules: 
                  {
                      attendance_title:
                      {
                          required: true
                      },
                      attendance_empl_id:
                      {
                          required: true
                      }
                  },
                  messages:
                  {
                      attendance_title:
                      {
                          required: "Please enter Attendance Title."
                      },
                      attendance_empl_id:
                      {
                          required: "Please select Employee."
                      }
                  }
        });
        $('#check_atten').click(function(){
            var formData = new FormData();
            formData.append('file', $('#file')[0].files[0]);
            formData.append('attendance_date', $('#attendance_date').val());
            formData.append('attendance_empl', $('#attendance_empl').val());

            if($('#attendance_autofilter').is(':checked')){
               var attendance_autofilter = 1;
            }else{
               var attendance_autofilter = 0;
            }
            formData.append('attendance_autofilter',attendance_autofilter);
            $.ajax({
                   url : 'attendance.php?action=check_attendance',
                   type : 'POST',
                   data : formData,
                   processData: false,  // tell jQuery not to process the data
                   contentType: false,  // tell jQuery not to set contentType
                   success : function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       var html = "<table class = 'table' >";
                           html += "<tr>";
                           html += "<th>ID</th><th>Name</th><th>Device ID</th><th>Actual Time</th><th>Start Time</th><th>Latecomers</th><th>End Time</th><th>OverTimes</th>";
                           html += "</tr>";
                       for(var i=0;i<jsonObj.length;i++){
                           
                           html += "<tr>";
                           html += "<td>" + jsonObj[i].ID + "</td>" + "<td>" + jsonObj[i].name + "</td>" + "<td>" + jsonObj[i].device_id + "</td>" +
                                   "<td>" + jsonObj[i].actual_time + "</td>" + "<td>" + jsonObj[i].date_time + "</td>" +
                                   "<td style = 'color:red;'>" + jsonObj[i].text_message + "</td>" + "<td>" + jsonObj[i].end_work_time + "</td>" + "<td style = 'color:green;'>" + jsonObj[i].ot_message + "</td>";
                           html += "</tr>";
                       }
                       html += "<tr><td colspan = '5' align = 'right'>Total : </td><td style = 'color:red;'>" + jsonObj[0].total_late + " minutes</td><td></td><td style = 'color:green;'>" + jsonObj[0].total_ot + " minutes</td></tr>";
                       html += "</table>";
                       
                       
                       $('#attendance_table').html(html);
                   }
            });
        });
});

    </script>
  </body>
</html>
        <?php
        
    }
    public function getListing(){
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Attendance Management</title>
    <?php
    include_once 'css.php';
    
    ?>
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <!-- include header-->
      <?php include_once 'header.php';?>
      <!-- Full Width Column -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Attendance Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">

               <button class="btn btn-primary pull-right" onclick = "window.location.href='attendance.php?action=createForm'">Create New + </button>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="attendance_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:13%'>Employee</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:25%'>Remark</th>
                        <th style = 'width:10%'>OT (min)</th>
                        <th style = 'width:10%'>Late (min)</th>
                        <th style = 'width:14%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT l.*,empl.empl_name
                              FROM db_attendance l 
                              INNER JOIN db_empl empl ON empl.empl_id = l.attendance_empl
                              WHERE l.attendance_id > 0 AND l.attendance_status = 1
                              ORDER BY l.updateDateTime";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['empl_name'];?></td>
                            <td><?php echo format_date($row['attendance_date']);?></td>
                            <td><?php echo nl2br($row['attendance_remark']);?></td>
                            <td><?php echo $row['attendance_ottotal'];?></td>
                            <td><?php echo $row['attendance_latetotal'];?></td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'attendance.php?action=edit&attendance_id=<?php echo $row['attendance_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('attendance.php?action=delete&attendance_id=<?php echo $row['attendance_id'];?>','Confirm Delete?')">Delete</button>
                                <?php
                                 }
                                 ?>
                            </td>
                        </tr>
                    <?php    
                        $i++;
                      }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:13%'>Employee</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:25%'>Remark</th>
                        <th style = 'width:10%'>OT (min)</th>
                        <th style = 'width:10%'>Late (min)</th>
                        <th style = 'width:14%'></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper --><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';
    ?>
    <script>
      $(function () {
        $('#attendance_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });

      });
    </script>
  </body>
</html>
    <?php
    }
    public function calculateAttendance($data){
    include_once 'class/Empl.php';
    $e = new Empl();
            $s = 0;
            $json = array();
            
            $datetime = "";
            $type = "";
            $d = array();
//            $this->attendance_date = "2016-08-01";
            
            $this->attendance_date = format_date_database($this->attendance_date);
            $totaldatemonth = date("t", strtotime($this->attendance_date));
            $date_date = array();
            if($this->database == 'db_timetemp'){
                mysql_query("DELETE FROM $this->database");
            }else{
                mysql_query("DELETE FROM $this->database WHERE attendance_id = '$this->attendance_id'");
            }
            
            for($i=1;$i<=$totaldatemonth;$i++){
                if($i <10){
                    $h = "0" . ($i);
                }else{
                    $h = $i;
                }
//                $p = date('Y-08-' . $h);
                $p = date('Y-' . date("m", strtotime($this->attendance_date)) . '-' . $h);
                $sql = "INSERT INTO $this->database (date,attendance_id) VALUES ('$p','$this->attendance_id')";
//                mysql_query($sql);
              
            }


                 $total_otmin = 0;
                 $total_latemin = 0;
            $this->attendance_autofilter = 1;
            for($i=0;$i<count($data->sheets);$i++){

                 for($j=1;$j<=count($data->sheets[$i]["cells"]);$j++){
                    $b = false;
                    if(($data->sheets[$i]["cells"][$j][1] == 'Num') || ($data->sheets[$i]["cells"][$j][1] == '')){
                             continue;
                    }
                    if($this->attendance_autofilter > 0){
                        if($datetime != substr($data->sheets[$i]["cells"][$j][5],0,10)){
                            $datetime = substr($data->sheets[$i]["cells"][$j][5],0,10);
                        }else{
                            if($datetime == substr($data->sheets[$i]["cells"][$j][5],0,10)){
                                if($type == strtoupper($data->sheets[$i]["cells"][$j][9])){
                                    continue;
                                }
                            }
                        }
                    }
                    if($type != strtoupper($data->sheets[$i]["cells"][$j][9])){
                        $type = strtoupper($data->sheets[$i]["cells"][$j][9]);
                    }
                    if( getDataCountBySql("$this->database"," WHERE attendance_id = '$this->attendance_id' AND date = '" . substr($data->sheets[$i]["cells"][$j][5],0,10) . "'") == 0){
                        $sql = "INSERT INTO $this->database (date,attendance_id) VALUES ('" . substr($data->sheets[$i]["cells"][$j][5],0,10) . "','$this->attendance_id')";
                        mysql_query($sql);
                    }
              
                    $empl_data = $e->fetchEmplDetail(" AND empl_id = '" . $this->attendance_empl . "'", $orderstring, $wherelimit,2);
                    $starttime = substr($empl_data['empl_work_time_start'],0,5);
                    $endtime = substr($empl_data['empl_work_time_end'],0,5) + 12;
                  
                    $staff_starttime = substr($data->sheets[$i]["cells"][$j][5],11,-3);

                    $start_date = substr($data->sheets[$i]["cells"][$j][5],0,10);

                    

                    $to_time = strtotime("$start_date $starttime:00");
                    $from_time = strtotime("$start_date $staff_starttime:00");
                    $plustime = 0;
                    if($type == 'IN'){
                        if($d["OUT"] != ""){
                            
                            $ind = date("Y-m-d", strtotime($d["IN"]));
                            $outd = date("Y-m-d", strtotime($d["OUT"]));

                            $condi[1]['s'] = "$ind 18:01:00";
                            $condi[1]['e'] = "$ind 23:00:00";
                            $condi[1]['t'] = "1800";//30

                            $condi[2]['s'] = "$ind 23:01:00";
                            $condi[2]['e'] = "$outd 01:00:00";
                            $condi[2]['t'] = "5400";//1:30
                           
                            $condi[3]['s'] = "$outd 01:01:00";
                            $condi[3]['e'] = "$outd 03:00:00";
                            $condi[3]['t'] = "9200";//2:30
                            
                            $condi[4]['s'] = "$outd 03:01:00";
                            $condi[4]['e'] = "$outd 09:00:00";
                            $condi[4]['t'] = "14400";//4
                            
                            $plustime = 0;
                            for($ii=1;$ii<=sizeof($condi);$ii++){
                                
                                $start_timestamp = strtotime($condi[$ii]['s']);
                                $end_timestamp = strtotime($condi[$ii]['e']);
                                $today_timestamp = strtotime($d["OUT"]);
                                if((($today_timestamp >= $start_timestamp) && ($today_timestamp <= $end_timestamp))){
                                    $plustime = $condi[$ii]['t'];
                                    break;
                                }
                            }             
                          $to_time = $to_time + $plustime;
                          
                        }
                    }
                    
                    $buffertime = strtotime("$ind 09:30:00");
                    if($plustime == 0){
                        $compare_totime = $to_time + 1800;
                    }else{
                        $compare_totime = $to_time;
                    }
                   
                    if(round(($from_time - $compare_totime) / 60,2) > 0){

//                        echo date("Y-m-d H:i:00", $to_time) . "</br>";die;
                        $hours      = floor(($from_time - $compare_totime) / 60 / 60);
                        $minutes    = round((($from_time - $compare_totime) - ($hours * 60 * 60)) / 60);
//                      echo date("Y-m-d H:i:00", $compare_totime) . "</br>";die;
                        if($hours > 0){
                            $text_message = $hours.' Hours '.$minutes . " minutes";
                            if($type == 'IN'){
                                $total_latemin = $total_latemin + ($hours*60) + $minutes; 
                            }
                        }else{
                            if($compare_totime >= $buffertime){
                                $text_message = ($minutes+30). " minutes";
                                $total_latemin = $total_latemin + ($minutes+30);
                            }else{
                                if($minutes >= 30){
                                    $text_message = "";
                                }
                            }

                        }
                      

                    }else{
                        $text_message = "";
                    }
                    $ot_message = "";
                    if($type == 'OUT'){
                        $text_message = "";
                        

                        

                        if(date("Y-m-d", strtotime($data->sheets[$i]["cells"][$j][5])) != date("Y-m-d", strtotime($d["IN"]))){
                           
                            if(date("Y-m-d", strtotime($data->sheets[$i]["cells"][$j][5] . ' -1 day')) == date("Y-m-d", strtotime($d["IN"])) ){
                                $newendtime = date("Y-m-d H:i:00", strtotime($data->sheets[$i]["cells"][$j][5]));
                                $to_time = strtotime(date("Y-m-d", strtotime($data->sheets[$i]["cells"][$j][5] . ' -1 day')) . " $endtime:00:00");
                                $from_time = strtotime($newendtime);
//echo date("Y-m-d H:i:00", $from_time) . " | " . date("Y-m-d H:i:00",$to_time);die;
                                $d["OUT"] = date("Y-m-d H:i:00", strtotime($data->sheets[$i]["cells"][$j][5]));
                                $start_date = date("Y-m-d", strtotime($start_date . ' -1 day'));
                            }
                        }else{
                            $to_time = strtotime("$start_date $endtime:00:00");
                            $from_time = strtotime("$start_date $staff_starttime:00");
                            $d["OUT"] = $data->sheets[$i]["cells"][$j][5];
                        }
                        $compare_totime = $to_time + 3600;
                        if(round(($from_time - $compare_totime) / 60,2) > 0){
                            $hours      = floor(($from_time - ($compare_totime-1800)) / 60 / 60);
                            $minutes    = round((($from_time - ($compare_totime-1800)) - ($hours * 60 * 60)) / 60);
                            
                            if($hours > 0){
                                $ot_message = $hours.' Hours '.$minutes . " minutes";
                                $total_otmin = $total_otmin + ($hours*60) + $minutes;
                            }else{
                                $ot_message = $minutes . " minutes";
                                $total_otmin = $total_otmin + $minutes;
                            }
                          
                        }else{
                            $ot_message = "";
                        }
                        
                    }else{
                        $d["IN"] = $data->sheets[$i]["cells"][$j][5];
                        $actual_time = date("Y-m-d H:i:00",$to_time);
                        $parame = " ,actual_time = '$actual_time'";
                    }

                    if($data->sheets[$i]["cells"][$j][9] == 'In'){
                        $wherestring = ",start_work_time = '{$data->sheets[$i]["cells"][$j][5]}',late_time = '$text_message'";
                    }else{
                        $wherestring = ",end_work_time = '{$data->sheets[$i]["cells"][$j][5]}',over_time = '$ot_message'";
                    }
                    $sql = "UPDATE $this->database SET $this->sqlupdate ID = '{$data->sheets[$i]["cells"][$j][4]}',name = '{$data->sheets[$i]["cells"][$j][3]}',
                            device_id = '{$data->sheets[$i]["cells"][$j][8]}',device_type = '{$data->sheets[$i]["cells"][$j][9]}'
                            $parame $wherestring WHERE date = '$start_date' AND attendance_id = '$this->attendance_id'";
                       
                    mysql_query($sql);
                 }
            }  
           
            $sql = "SELECT * FROM $this->database ";
            $query = mysql_query($sql);
            while($row = mysql_fetch_array($query)){
                if($row['actual_time'] == ''){
                    $actual_time = $row['date'];
                }else{
                    $actual_time = $row['actual_time'];
                }
                $json[] = array('name'=>$row['name'],'ID'=>$row['ID'],'date_time'=>$row['start_work_time'],
                                'device_id'=>$row['device_id'],'device_type'=>strtoupper($row['device_type']),'text_message'=>$row['late_time'],
                                'ot_message'=>$row['over_time'],'actual_time'=>date("Y-m-d H:i:00",strtotime($actual_time)) . " (" . date("D",strtotime($actual_time)) . ") ",'end_work_time'=>$row['end_work_time'],
                                'total_ot'=>$total_otmin,'total_late'=>$total_latemin);
            }
            $this->updateAttendanceTotal($total_otmin,$total_latemin);
            if($this->database == 'db_timetemp'){
            mysql_query("DELETE FROM $this->database");
            }
            return $json;
    }
    public function updateAttendanceTotal($total_otmin,$total_latemin){
        $sql = "UPDATE db_attendance SET attendance_sottotal = '$total_otmin',attendance_slatetotal = '$total_latemin' WHERE attendance_id = '$this->attendance_id'";
        mysql_query($sql);
    }


}
?>
