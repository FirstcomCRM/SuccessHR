<?php
/*
 * To change this tleaveate, choose Tools | Tleaveates
 * and open the tleaveate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Leave {

    public function Leave(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){

        $table_field = array('leave_type','leave_duration','leave_datefrom','leave_total_day',
                             'leave_dateto','leave_period_half','leave_period_hourly','leave_reason',
                             'leave_status','leave_empl_type','leave_empl_id','leave_approvalstatus');
        if($_SESSION['empl_group'] == "5"){
            $leave_empl_type = "1";
        }
        else{
            $leave_empl_type = "0";
        }

        $table_value = array($this->leave_type,$this->leave_duration,format_date_database($this->leave_datefrom),$this->leave_total_day,
                             format_date_database($this->leave_dateto),$this->leave_period_half,$this->leave_period_hourly,$this->leave_reason,
                             1,$leave_empl_type,$_SESSION['empl_id'],$this->leave_approvalstatus);

        $remark = "Insert Apply Leave.";
        if(!$this->save->SaveData($table_field,$table_value,'db_leave','leave_id',$remark)){
           return false;
        }else{
           $this->leave_id = $this->save->lastInsert_id;
           $this->pictureManagement();
           $this->saveNotification();
           return true;
        }
    }
    public function update(){
        $table_field = array('leave_type','leave_duration','leave_datefrom','leave_total_day',
                             'leave_dateto','leave_period_half','leave_period_hourly','leave_reason',
                             'leave_status','leave_approvalstatus');
        
        $table_value = array($this->leave_type,$this->leave_duration,format_date_database($this->leave_datefrom),$this->leave_total_day,
                             format_date_database($this->leave_dateto),$this->leave_period_half,$this->leave_period_hourly,$this->leave_reason,
                             $this->leave_status,$this->leave_approvalstatus);
        $remark = "Update Apply Leave.";

        if(!$this->save->UpdateData($table_field,$table_value,'db_leave','leave_id',$remark,$this->leave_id)){
           return false;
        }else{
           $this->pictureManagement();
           return true;
        }
    }
    public function updateApproveStatus($leave_approvalstatus){
        $table_field = array('leave_approvalstatus');
        $table_value = array($leave_approvalstatus);
        $remark = "Update Approve Status.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_leave','leave_id',$remark,$this->leave_id)){
           return false;
        }else{
           return true;
        }
    }
    public function pictureManagement(){
        if(!file_exists("dist/images/leave")){
           mkdir('dist/images/leave/');
        }


        if($this->image_input['size'] > 0 ){

            if($this->action == 'update'){
                $sql = "SELECT image_id,image_type FROM db_image WHERE ref_id = '$this->leave_id'";
                $query = mysql_query($sql);
                if($row = mysql_fetch_array($query)){
                     unlink("dist/images/leave/{$this->leave_id}.{$row['image_type']}");
                }
            }
            
            $type = end(explode(".",$this->image_input['name']));
            $table_field = array('ref_table','ref_id','image','image_type','status');
            $table_value = array('db_leave',$this->leave_id,$this->image_input['name'],$type,1);
            $remark = "manage leave image";
            $exist = getDataCountBySql("db_image"," WHERE ref_id = '$this->leave_id'");
            if($exist > 0){
                $this->save->UpdateData($table_field,$table_value,'db_image','ref_id',$remark,$this->leave_id);
            }else{
                $this->save->SaveData($table_field,$table_value,'db_image','image_id',$remark);
            }
            move_uploaded_file($this->image_input['tmp_name'],"dist/images/leave/{$this->leave_id}.$type");
        }
    }
    public function fetchLeaveDetail($wherestring,$orderstring,$wherelimit,$type){
        global $master_group;
        $sql = "SELECT * FROM db_leave WHERE leave_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->leave_id = $row['leave_id'];
            $this->leave_empl_id = $row['leave_empl_id'];
            $this->leave_type = $row['leave_type'];
            $this->leave_duration = $row['leave_duration'];
            $this->leave_datefrom = $row['leave_datefrom'];
            $this->leave_total_day = $row['leave_total_day'];
            $this->leave_dateto = $row['leave_dateto'];
            $this->leave_period_half = $row['leave_period_half'];
            $this->leave_period_hourly = $row['leave_period_hourly'];
            $this->leave_reason = $row['leave_reason'];
            $this->leave_status = $row['leave_status'];
            $this->leave_approvalstatus = $row['leave_approvalstatus'];
//            if(!in_array($_SESSION['empl_group'],$master_group)){
//                if($this->leave_empl_id != $_SESSION['empl_id']){
//                    permissionLog();
//                    rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
//                    exit();
//                }
//            }
        }
        else if($type == 2){
            return mysql_fetch_array($query);
        }
        if(mysql_num_rows($query) == 0 ){
            return false;
        }
        else{
            return $query;
        }
    }
    public function delete(){
        $table_field = array('leave_status','leave_approvalstatus');
        $table_value = array(0,'Deleted');
        $remark = "Delete Leave.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_leave','leave_id',$remark,$this->leave_id)){
           return false;
        }else{
           $this->putBackLeaveDays(); 
           return true;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        include_once 'class/Empl.php';
        $e = new Empl();
        if($action == 'create'){
            $this->leave_seqno = 10;
            $this->leave_status = 1;
            if(escape($_GET['leave_datefrom']) != ""){
                $this->leave_datefrom = format_date_database(escape($_GET['leave_datefrom']));
                $this->leave_dateto = format_date_database(escape($_GET['leave_dateto']));
            }
            else{
                $this->leave_datefrom = system_date;
                $this->leave_dateto = system_date;
            }
            $this->leave_total_day = 1;
            $this->leave_approvalstatus = 'Draft';
            $this->leave_duration = "full_day";
            $empl_code = $_SESSION['empl_code'];
            $empl_name = $_SESSION['empl_name'];
        }
        else{
            $empl_data = $e->fetchEmplDetail(" AND empl_id = '$this->leave_empl_id'","","",2);
            $empl_code = $empl_data['empl_code'];
            $empl_name = $empl_data['empl_name'];
        }
        if($this->leave_approvalstatus != "Draft"){
            $disabled = " DISABLED";
        }

        if((getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],"approved")) && ($this->leave_id > 0)){// HR
            $wherestring_leavetype = "AND el.emplleave_empl = '$this->leave_empl_id'";
        }else if($_SESSION['empl_group'] == '-1'){
            $wherestring_leavetype = "AND el.emplleave_empl = '11'";  //default pull from HR info
        }else{
            $wherestring_leavetype = "AND el.emplleave_empl = '{$_SESSION['empl_id']}'";
        }
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Leave Management</title>
    <?php
    include_once 'css.php';
    $year = date("Y");
    //$this->leavetypeCrtl = $this->select->getEmplLeaveSelectCtrl($this->leave_type,"Y"," AND el.emplleave_disabled = 0 AND el.emplleave_status = 1 $wherestring_leavetype AND el.emplleave_year = '$year'");
    if($_SESSION['empl_group'] == "5" || $_SESSION['empl_group'] == "9"){
        $this->leavetypeCrtl = $this->select->getApplicantLeaveTypeSelectCtrl($this->leave_type);  
    }else{
        $this->leavetypeCrtl = $this->select->getLeaveTypeSelectCtrl($this->leave_type);  
    }
    
    getLeaveTypeSelectCtrl
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
            <h1>Leave Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->leave_id > 0){ echo "Update Leave";}else{ echo "Apply New Leave";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = '' onclick = "window.location.href='leave.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'margin-right:10px;' onclick = "window.location.href='leave.php?action=createForm'">Create New</button>
                <?php }?>
              </div>

                <form id = 'leave_form' class="form-horizontal" action = 'leave.php' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="col-sm-8">  
                    <div class="form-group">
                      <label  class="col-sm-2 control-label">Employee Code</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control " value = "<?php echo $empl_code;?>" placeholder="Employee Code" disabled>
                      </div>
                      <?php
                      if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],"approved")){
                      ?>
                      <label class="control-label empl-icon-label">
                          <a href = '#' id = 'empl_view' data-toggle="modal" data-target="#myModal"><i class="fa fa-user" aria-hidden="true"></i></a>
                      </label>
                      <?php
                      }
                      ?>
                    </div>
                    <div class="form-group">  
                      <label  class="col-sm-2 control-label">Employee Name</label>
                      <div class="col-sm-4">
                          <?php if($this->leave_id > 0 && ($_SESSION['empl_group'] == "5" || $_SESSION['empl_group'] == "9")){
                              $sql = "select applicant_name FROM db_applicant INNER JOIN db_leave ON applicant_id = leave_empl_id WHERE leave_id = '$this->leave_id'";
                              $query = mysql_query($sql);
                              $row = mysql_fetch_array($query);
                              $empl_name = $row['applicant_name'];
                          }?>
                        <input type="text" class="form-control " value = "<?php echo $empl_name;?>" placeholder="Employee Name" disabled>
                      </div>
                    </div>                      
                    <div class="form-group">
                      <label for="leave_type" class="col-sm-2 control-label">Type of Leave <?php echo $mandatory;?></label>
                      <div class="col-sm-4">
                        <select class="form-control select2" id="leave_type" name="leave_type" <?php echo $disabled;?>>
                          <?php echo $this->leavetypeCrtl;?>
                        </select>
                          <input type = "hidden" id="balance" value = "" name = "action"/>
                      </div>
                    </div>  
                    <div class="form-group">
                      <label for="leave_duration" class="col-sm-2 control-label">Leave Duration </label>
                      <div class="col-sm-4">
                        <select class="form-control select2" id="leave_duration" name="leave_duration" <?php echo $disabled;?>>
                            <option value = "full_day" <?php if($this->leave_duration == 'full_day'){ echo 'SELECTED';}?>>Full Day Leave</option>
                            <option value = 'first_half' <?php if($this->leave_duration == 'first_half'){ echo 'SELECTED';}?>>Half Day AM</option>
                            <option value = 'second_half' <?php if($this->leave_duration == 'second_half'){ echo 'SELECTED';}?>>Half Day PM</option>
                            <!--<option value = "hourly" <?php if($this->leave_duration == 'hourly'){ echo 'SELECTED';}?>>Hourly Leave</option>-->
                        </select>
                      </div>
                    </div>  
                     <div class="form-group">
                         <label for="leave_datefrom" class="col-sm-2 control-label">Date<span id = 'from_text'> (From)</span></label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control datepicker" id="leave_datefrom" name="leave_datefrom" value = "<?php echo format_date($this->leave_datefrom);?>" placeholder="Date (From)" <?php echo $disabled;?>>
                      </div>
                      <label  class="col-sm-2 control-label">Total Days</label>
                      <div class="col-sm-2">
                        <input type="text" style = 'text-align:right' class="form-control" id="leave_total_day" value = "<?php echo $this->leave_total_day;?>" disabled>
                      </div>
                     </div>
                      <div class="form-group" id = 'date_to_div' style = '<?php if($this->leave_duration != 'full_day'){ echo 'display:none';}?>' >
                        <label for="leave_dateto" class="col-sm-2 control-label">Date (To)</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control datepicker" id="leave_dateto" name="leave_dateto" value = "<?php echo format_date($this->leave_dateto);?>" placeholder="Date (To)" <?php echo $disabled;?>>
                        </div>
                      </div>
                      <div class="form-group day_hide" id = 'half_date_selection_div' style = '<?php if($this->leave_duration != 'half_day'){ echo 'display:none';}?>' >
                        <label for="leave_period" class="col-sm-2 control-label">Leave Period</label>
                        <div class="col-sm-4">
                            <select style = 'width:100%' class="form-control select2" id = 'leave_period' name = 'leave_period_half' <?php echo $disabled;?>>

                            </select>
                        </div>
                      </div> 
                      <div class="form-group"  >
                        <label for="image_input" class="col-sm-2 control-label">Upload File</label>
                        <div class="col-sm-4">
                            <input type="file" style = 'width' id="image_input" name="image_input" placeholder="Upload File" <?php echo $disabled;?>>
                             <?php 
                             
                             $file_type = getDataCodeBySql("image_type","db_image"," WHERE ref_id = '$this->leave_id'", $orderby);
                             $file_name = getDataCodeBySql("image","db_image"," WHERE ref_id = '$this->leave_id'", $orderby);
                             if(file_exists("dist/images/leave/{$this->leave_id}.$file_type")){?>
                                <p class="help-block">
                                    <a target = '_blank' href = "dist/images/leave/<?php echo $this->leave_id . ".$file_type" ?>" ><?php echo $file_name;?></a>
                                </p>
                             <?php }?>
                        </div>
                      </div>
                      <div class="form-group day_hide" id = 'hourly_selection_div' style = '<?php if($this->leave_duration != 'hourly'){ echo 'display:none';}?>' >
                        <label for="leave_period" class="col-sm-2 control-label">Leave Period</label>
                        <div class="col-sm-4">
                            <select style = 'width:100%' class="form-control select2" id = 'leave_period' name = 'leave_period_hourly' <?php echo $disabled;?>>
                                <option value="0"> - </option>
                                <option value="1" <?php if($this->leave_period_hourly == '1'){ echo 'SELECTED';}?>>One (01) Hour</option>
                                <option value="2" <?php if($this->leave_period_hourly == '2'){ echo 'SELECTED';}?>>Two (02) Hours</option>
                                <option value="3" <?php if($this->leave_period_hourly == '3'){ echo 'SELECTED';}?>>Three (03) Hours</option>
                                <option value="4" <?php if($this->leave_period_hourly == '4'){ echo 'SELECTED';}?>>Four (04) Hours</option>
                                <option value="5" <?php if($this->leave_period_hourly == '5'){ echo 'SELECTED';}?>>Five (05) Hours</option>
                                <option value="6" <?php if($this->leave_period_hourly == '6'){ echo 'SELECTED';}?>>Six (06) Hours</option>
                                <option value="7" <?php if($this->leave_period_hourly == '7'){ echo 'SELECTED';}?>>Seven (07) Hours</option>
                                <option value="8" <?php if($this->leave_period_hourly == '8'){ echo 'SELECTED';}?>>Eight (08) Hours</option>
                            </select>
                        </div>
                      </div> 
                    <div class="form-group">
                      <label for="leave_reason" class="col-sm-2 control-label">Reason <?php echo $mandatory;?></label>
                      <div class="col-sm-4">
                            <textarea class="form-control" rows="3" id="leave_reason" name="leave_reason" placeholder="Reason" <?php echo $disabled;?>><?php echo $this->leave_reason;?></textarea>
                      </div>
                      
                      <?php
                           if($this->leave_approvalstatus != 'Draft'){
                      ?> 
                            <label for="leave_approvalstatus" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-4">
                                <label for="leave_reason" class="col-sm-3 control-label" style = 'color:red;' ><b><?php echo $this->getApprovalStatus($this->leave_approvalstatus);?></b></label>
                            </div>
                            <?php 
                           }
                      ?>
                    </div> 
                    </div>
<!--                    <div class="col-sm-4 table-responsive" style = 'text-align:center' >    
                            <h3>Balance Leave Detail</h3>
                            <table class = 'table table-bordered table-hover dataTable ' >
                                <tr>
                                <th>Leave Name</th>
                                <th>Entitled</th>
                                <th>Taken</th>
                                <th>Pending</th>
                                <th>Available Balance</th>
                                </tr>
                            <?php
                            $year_start = system_date_yearstart;
                            $year_end = system_date_yearend;
                            if((getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],"approved")) && ($this->leave_id > 0)){// HR
                                $wherestring = "AND el.emplleave_empl = '$this->leave_empl_id'";
                                $wherestring2 = "AND leave_empl_id = '$this->leave_empl_id'";
                            }else{
                                $wherestring = "AND el.emplleave_empl = '{$_SESSION['empl_id']}'";
                                $wherestring2 = "AND leave_empl_id = '{$_SESSION['empl_id']}'";
                            }
                            $sql = "SELECT lt.*,el.emplleave_days,el.emplleave_id,el.emplleave_entitled
                                    FROM db_emplleave el
                                    INNER JOIN db_leavetype lt ON el.emplleave_leavetype = lt.leavetype_id $wherestring
                                    WHERE lt.leavetype_status = 1 AND el.emplleave_status = 1 AND el.emplleave_disabled = 0  AND leavetype_id <> '10' AND el.emplleave_year = '$year'
                                    ORDER BY lt.leavetype_seqno,lt.leavetype_code";


                            $query = mysql_query($sql);
                            while($row = mysql_fetch_array($query)){

                            //leave type id 10 = "Urgent Leave";
                            //leave type id 1 = "Annual Leave";

                            // Customer mention urgent leave same with annual leave , calculate together at 2016-12-07 (Zen)
                            if($row['leavetype_id'] == 1){
                               $wheresub = " AND leave_type IN ('1','10') "; 
                            }else{
                               $wheresub = " AND leave_type = '{$row['leavetype_id']}' ";
                            }


                               $taken = getDataCodeBySql("SUM(leave_total_day)",'db_leave'," WHERE leave_approvalstatus = 'Approved' AND LEFT(leave_datefrom,4) = '$year' $wheresub $wherestring2 ");
                               $pending = getDataCodeBySql("SUM(leave_total_day)",'db_leave'," WHERE leave_approvalstatus = 'Pending' AND LEFT(leave_datefrom,4) = '$year' $wheresub $wherestring2 ");
                               if($taken == null){
                                   $taken = 0;
                               }
                               if($pending == null){
                                   $pending = 0;
                               }
                            ?>
                                <tr>
                                    <td><?php echo $row['leavetype_code'];?></td>
                                    <td><?php echo $row['emplleave_entitled'];?></td>
                                    <td><?php echo num_format($taken);?></td>
                                    <td><?php echo num_format($pending);?></td>
                                    <td><?php echo num_format($row['emplleave_entitled']-$taken);?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </table>-->
                            <div class="col-md-4">
                            <?php
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],"approved")){
                                    if($this->leave_approvalstatus != 'Draft'){
                                    if($this->checkIsSubmitApproval($this->leave_id,'leave')){//check the user already submit approval or not true:haven,false:submited
                                        $disabled = " ";
                                    }else{
                                        $disabled = " disabled";
                                    }
                                    if($this->leave_approvalstatus == 'Deleted'){
                                        $disabled = " disabled";
                                    }
                            ?>
                                    <button type = 'button' <?php echo $disabled;?> class="btn btn-block btn-success btn-lg astatus" pid = 'Approved' data-toggle="modal" data-target="#sstatusModal">Approve</button>
                                    <button type = 'button' <?php echo $disabled;?> class="btn btn-block btn-warning btn-lg astatus" pid = 'Rejected' data-toggle="modal" data-target="#sstatusModal">Reject</button>
                                <?php 
                                    }
                                } ?>
                            </div>
                   </div>
                    <div class="col-md-12">
                                  <!-- The time line -->
                                  <ul class="timeline">

                                    <?php
                                    
                                    $sql = "SELECT * FROM db_leave_approved WHERE type_id = '$this->leave_id' AND type_code = 'leave'";
                                    $query = mysql_query($sql);
                                    if($row = mysql_fetch_array($query)){
                                        $level1_empl_name = getDataCodeBySql("empl_name","db_empl"," WHERE empl_id = '{$row['level1_empl']}'", $orderby);
                                        $level2_empl_name = getDataCodeBySql("empl_name","db_empl"," WHERE empl_id = '{$row['level2_empl']}'", $orderby);
                                        $level3_empl_name = getDataCodeBySql("empl_name","db_empl"," WHERE empl_id = '{$row['level3_empl']}'", $orderby);
                                    ?>
                                    <!-- timeline time label -->
                                    <li class="time-label">
                                      <span class="bg-red">
                                        Approval Level
                                      </span>
                                    </li>
                                    <!-- /.timeline-label -->
                                    <?php if($row['level1_empl'] > 0){?>
                                    <!-- timeline item -->
                                    <li>
                                      <i class="fa bg-blue">1</i>
                                      <div class="timeline-item">
                                        <h3 class="timeline-header">
                                            <?php 
                                            $datetime = "";
                                            if($row['level1_approve_date'] != "0000-00-00 00:00:00"){ 
                                                echo " <a href='#'>$level1_empl_name </a>";
                                                echo "<font style = 'color:green;font-weight:700' > Approved</font> your request. <br>";
                                                $datetime = $row['level1_approve_date'];
                                            }else if($row['level1_rejected_date'] != "0000-00-00 00:00:00"){ 
                                                echo " <a href='#'>$level1_empl_name </a>";
                                                echo "<font style = 'color:orange;font-weight:700' > Rejected</font> your request. <br>";
                                                $datetime = $row['level1_rejected_date'];
                                            }else{ 
                                                echo " <a href='#'>$level1_empl_name </a>";
                                                echo "<font style = 'color:red;font-weight:700'>Pending</font>  your request. ";}
                                            ?>
                                        </h3>
                                        <span class="time"><i class="fa fa-clock-o"></i> <?php echo $datetime;?></span>  
                                        <div class="timeline-body">
                                          <?php
                                          echo htmlspecialchars_decode($row['level1_message']);
                                          ?>&nbsp;
                                        </div>
                                      </div>
                                    </li>
                                    <!-- END timeline item -->
                                    <?php }?>
                                    
                                    <?php if($row['level2_empl'] > 0){?>
                                    <!-- timeline item -->
                                    <li>
                                      <i class="fa bg-aqua">2</i>
                                      <div class="timeline-item">

                                        <h3 class="timeline-header">
                                            <?php 
                                            $datetime = "";
                                            if($row['level2_approve_date'] != "0000-00-00 00:00:00"){ 
                                                echo " <a href='#'>$level2_empl_name </a>";
                                                echo "<font style = 'color:green;font-weight:700' > Approved</font>  your request. <br>";
                                                $datetime = $row['level2_approve_date'];
                                            }else if($row['level2_rejected_date'] != "0000-00-00 00:00:00"){ 
                                                echo " <a href='#'>$level2_empl_name </a>";
                                                echo "<font style = 'color:orange;font-weight:700' > Rejected</font> your request. <br>";
                                                $datetime = $row['level2_rejected_date'];
                                            }else{ 
                                                echo " <a href='#'>$level2_empl_name </a>";
                                                echo "<font style = 'color:red;font-weight:700'>Pending</font>  your request. ";}
                                            ?>
                                        </h3>
                                        <span class="time"><i class="fa fa-clock-o"></i> <?php echo $datetime;?></span>  
                                        <div class="timeline-body">
                                          <?php
                                          echo htmlspecialchars_decode($row['level2_message']);
                                          ?>&nbsp;
                                        </div>
                                      </div>
                                    </li>
                                    <!-- END timeline item -->
                                    <?php }?>
                                    
                                    <?php if($row['level3_empl'] > 0){?>
                                    <!-- timeline item -->
                                    <li>
                                      <i class="fa bg-yellow">3</i>
                                      <div class="timeline-item">
                                        <h3 class="timeline-header">

                                            <?php 
                                            $datetime = "";
                                            if($row['level3_approve_date'] != "0000-00-00 00:00:00"){
                                                echo " <a href='#'>$level3_empl_name</a>";
                                                echo "<font style = 'color:green;font-weight:700' > Approved</font> your request. <br>";
                                                $datetime = $row['level3_approve_date'];
                                            }else if($row['level3_rejected_date'] != "0000-00-00 00:00:00"){ 
                                                echo " <a href='#'>$level3_empl_name</a>";
                                                echo "<font style = 'color:orange;font-weight:700' > Rejected</font> your request. <br>";
                                                $datetime = $row['level3_rejected_date'];
                                            }else{ 
                                                echo " <a href='#'>$level3_empl_name</a>";
                                                echo "<font style = 'color:red;font-weight:700'> Pending</font> your request. ";}
                                            ?>

                                        </h3>
                                        <span class="time"><i class="fa fa-clock-o"></i> <?php echo $datetime;?></span>
                                        <div class="timeline-body">
                                          <?php
                                          echo htmlspecialchars_decode($row['level3_message']);
                                          ?>&nbsp;
                                        </div>
                                      </div>
                                    </li>
                                    <!-- END timeline item -->
                                    
                                    <?php }?>
                                    <?php }?>
                    <!--                <li>
                                      <i class="fa fa-clock-o bg-gray"></i>
                                    </li>-->
                                  </ul>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                      <div id="checkbalance">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Back</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->leave_status;?>" name = "leave_status"/>
                    <input type = "hidden" value = "<?php echo $this->leave_id;?>" name = "leave_id"/>
                    <?php 
                    if($this->leave_id > 0){
                        $prm_code = "update";
                    }else{
                        $prm_code = "create";
                    }
                    if($this->leave_approvalstatus == 'Draft'){
                        if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){?>
                    
                        <button type = "submit" name = 'submit_btn' value = 'Save' class="btn btn-info">Save</button>
                        &nbsp;&nbsp;&nbsp;
                        <button type = "submit" name = 'submit_btn' value = 'Confirm' class="btn btn-danger checkbalance">Confirm</button>
                    </div>   
                      <div id="result"> <h5 style="color:red">Leave balance is not enough</h5></div>
                        <?php 
                        }
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
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $empl_data['empl_name'];?></h4>
        </div>
        <div class="modal-body">
                <table class = 'table tablenoborder table-empl-detail'  >
                    <tr>
                        <td rowspan = '5'>
                         <?php if(file_exists("dist/images/empl/{$this->leave_empl_id}.png")){?>
                         <img src="dist/images/empl/<?php echo $this->leave_empl_id;?>.png" class="img-circle" alt="User Image" style = 'width:160px;height:160px;'>
                      <?php }else{?>
                         <img src="dist/img/thumb.gif" class="img-circle" alt="img-circle">
                        <?php }?>
                        </td>
                    </tr>
                    <tr>
                        <td>Email Address</td>
                        <td> : </td>
                        <td><?php echo $empl_data['empl_email'];?></td>
                    </tr>
                    <tr>
                        <td>Department</td>
                        <td> : </td>
                        <td><?php echo getDataCodeBySql("department_code","db_department"," WHERE department_id = '{$empl_data['empl_department']}'");?></td>
                    </tr>
                    <tr>
                        <td>Mobiles</td>
                        <td> : </td>
                        <td><?php echo $empl_data['empl_mobile'];?></td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td> : </td>
                        <td><?php echo getDataCodeBySql("outl_code","db_outl"," WHERE outl_id = '{$empl_data['empl_outlet']}'");?></td>
                    </tr>
                </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
</div>
<div class="modal fade " id="sstatusModal" role="dialog">
    <div class="modal-dialog ">
        <form action = 'leave.php' method = "POST">
            <!-- Modal content-->
            <div class="modal-content ">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title sstatus_title"></h4>
              </div>
              <div class="modal-body">
                  <textarea class="form-control " rows="3" id="sstatus_message" name="sstatus_message" ></textarea>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <input type = 'hidden' name = 'sstatus_action' id = 'sstatus_action' />
                  <input type = 'hidden' name = 'action' value = 'sstatus' />
                  <input type = 'hidden' name = 'leave_id' value = '<?php echo $this->leave_id;?>' />
              </div>
            </div>
        </form>
    </div>
  </div>
    <!-- CK Editor -->
    <script src="dist/js/ckeditor/ckeditor.js"></script>
    <script>
    $(document).ready(function() {
        <?php
        if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],"approved")){
        ?>
        var sstatus_CK = CKEDITOR.replace('sstatus_message'); 
        <?php
        }
        ?>
        $("#leave_form").validate({
                  rules: 
                  {
                      leave_type:
                      {
                          required: true
                      },
                      leave_reason:
                      {
                          required: true
                      }
                  }
              });          
              
        calcBusinessDays(new Date($('#leave_datefrom').val()),new Date($('#leave_dateto').val()));      
        $('#leave_datefrom').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            pickerPosition: "bottom-left"
             }).on('changeDate', function (ev) {

              $('#leave_dateto').datepicker('setStartDate', ev.date);
              calcBusinessDays(new Date($('#leave_datefrom').val()),new Date($('#leave_dateto').val()))
        });
        $('#leave_dateto').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            startDate:$('#leave_datefrom').val(),
            pickerPosition: "bottom-left"
             }).on('changeDate', function (ev) {
                calcBusinessDays(new Date($('#leave_datefrom').val()),new Date($('#leave_dateto').val()))
        });    
         
        $('.astatus').click(function(){
                if($(this).text() == 'Approve'){
                    $('.sstatus_title').text('Approval Message');
                    var text = "Approved";
                }else{
                    $('.sstatus_title').text('Rejection Message');
                    var text = "Rejected";
                }
                sstatus_CK.focus();
                $('#sstatus_action').val(text);            

        });
        
        $('#result').hide();
        
       $('#leave_type').change(function(){
            var value = $(this);
            var data = "action=getBalance&appl_id=<?php echo $_SESSION['empl_id']?>&leavetype_id="+$(this).val();
            $.ajax({ 
                type: 'POST',
                url: 'leave.php',
                cache: false,
                data:data,
                error: function(xhr) {
                    alert("System Error.");
                    issend = false;
                },       
                success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                            document.getElementById("balance").value = jsonObj['balance'];
                            var leave_total_day = document.getElementById("leave_total_day").value
//                            console.log(jsonObj['balance']);
                            if( parseFloat(jsonObj['balance']) >= parseFloat(leave_total_day)){
                                $('#checkbalance').show();
                                $('#result').hide();
                            }
                            else
                            {
                                $('#checkbalance').hide();
                                $('#result').show();
                            }
                       issend = false;
                    }		
                 });
                 return false;
            });    
       
         
        $('#leave_duration').change(function(){
            $('.day_hide').css('display','none');
            if($(this).val() == 'full_day'){
                $('#from_text').text(' (From)');
                $('#date_to_div').css('display','');
                $('#leave_total_day').val(1);
            }else if(($(this).val() == 'first_half') || ($(this).val() == 'second_half')){
                $('#from_text').text('');
//                $('#half_date_selection_div').css('display','');
                $('#date_to_div').css('display','none');
                $('#leave_total_day').val(0.5);
            }else{
//                $('#from_text').text('');
//                $('#hourly_selection_div').css('display','');
//                $('#date_to_div').css('display','none');
//                $('#leave_total_day').val(1);
            }
        });
       
});
  function calcBusinessDays(dDate1, dDate2) { // input given as Date objects
    var iWeeks, iDateDiff, iAdjust = 0;
    if (dDate2 < dDate1){// error code if dates transposed
         $('#leave_total_day').val(0);
    } 
    var iWeekday1 = dDate1.getDay(); // day of week
    var iWeekday2 = dDate2.getDay();
    iWeekday1 = (iWeekday1 == 0) ? 7 : iWeekday1; // change Sunday from 0 to 7
    iWeekday2 = (iWeekday2 == 0) ? 7 : iWeekday2;
    if ((iWeekday1 > 5) && (iWeekday2 > 5)) iAdjust = 1; // adjustment if both days on weekend
    iWeekday1 = (iWeekday1 > 5) ? 5 : iWeekday1; // only count weekdays
    iWeekday2 = (iWeekday2 > 5) ? 5 : iWeekday2;

    // calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
    iWeeks = Math.floor((dDate2.getTime() - dDate1.getTime()) / 604800000);

    if (iWeekday1 <= iWeekday2) {
      iDateDiff = (iWeeks * 5) + (iWeekday2 - iWeekday1);
    } else 
      iDateDiff = ((iWeeks + 1) * 5) - (iWeekday1 - iWeekday2);{
    }

    iDateDiff -= iAdjust // take into account both days on weekend

    iDateDiff = iDateDiff + 1; // add 1 because dates are inclusive

        if(iDateDiff < 1){
            iDateDiff = 0;
        }
        if($('#leave_duration').val() == 'half_day'){
            iDateDiff = 0.5;
        }
        $('#leave_total_day').val(iDateDiff);

  }

    </script>
  </body>
</html>
        <?php
        
    }
    public function getListing(){
        global $master_group;
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Leave Management</title>
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
            <h1>Leave Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Leave Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='leave.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table id="leave_table" class="table table-bordered table-hover ">
                    <thead>
                      <tr>
                        <th style = 'width:4%'>No</th>
                        <th style = 'width:13%'>Employee</th>
                        <th style = 'width:10%'>Type of Leave</th>
                        <th style = 'width:13%'>Reason</th>
                        <th style = 'width:10%'>Leave Start Date</th>
                        <th style = 'width:10%'>Leave End Date</th>
                        <th style = 'width:10%'>Leave Days</th>
                        <th style = 'width:10%'>Approved / Rejected By</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                        
                    <?php
                        if($_SESSION['empl_group'] == "5"){
                        $sql = "SELECT * FROM db_leave l INNER JOIN db_leavetype lt ON l.leave_type = lt.leavetype_id INNER JOIN db_applicant a ON l.leave_empl_id = a.applicant_id WHERE l.leave_empl_id = $_SESSION[empl_id] AND l.leave_status ='1' ";
                        }
                        else if($_SESSION['empl_group'] == "9"){
                        $sql = "SELECT * FROM db_leave l INNER JOIN db_leavetype lt ON l.leave_type = lt.leavetype_id INNER JOIN db_applicant a ON l.leave_empl_id = a.applicant_id WHERE l.leave_status ='1' and (a.applicant_leave_approved1 = '$_SESSION[empl_id]' OR a.applicant_leave_approved2 = '$_SESSION[empl_id]' OR a.applicant_leave_approved3 = '$_SESSION[empl_id]')";
                        }
                        else
                        {
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],"approved")){
                                    $wherestring = "AND l.leave_approvalstatus <> 'Draft'";
                                }else{
                                    $wherestring = "AND l.leave_empl_id = '{$_SESSION['empl_id']}'";
                                }
                              $sql = "SELECT l.*,empl.empl_name,lt.leavetype_code
                                      FROM db_leave l 
                                      INNER JOIN db_empl empl ON empl.empl_id = l.leave_empl_id
                                      INNER JOIN db_leavetype lt ON lt.leavetype_id = l.leave_type
                                      WHERE l.leave_id > 0 AND l.leave_status = 1 $wherestring
                                      ORDER BY l.updateDateTime DESC";
                        }
                        $query = mysql_query($sql);
                        $i = 1;
                        while($row = mysql_fetch_array($query)){?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <?php if($_SESSION['empl_group'] == "5" || $_SESSION['empl_group'] == "9"){ ?>
                                    <td><?php echo $row['applicant_name'];?></td>
                            <?php     
                            }else{ ?>
                                <td><?php echo $row['empl_name'];?></td>
                            <?php }
                                    ?>
                            <td><?php echo $row['leavetype_code'];?></td>
                            <td><?php echo nl2br($row['leave_reason']);?></td>
                            <td><?php echo format_date($row['leave_datefrom']);?></td>
                            <td><?php echo format_date($row['leave_dateto']);?></td>
                            <td><?php echo $row['leave_total_day'];?></td>
                            <td>
                                <?php 
                                $sql2 = "SELECT * FROM db_leave_approved WHERE type_code = 'leave' AND type_id = '{$row['leave_id']}'";
                                $query2 = mysql_query($sql2);
                                $html = "";
                                if($row2 = mysql_fetch_array($query2)){
                                    if($row2['level1_empl'] > 0){
                                        if(($row2['level1_approve_date'] != '0000-00-00 00:00:00') || (($row2['level1_rejected_date'] != '0000-00-00 00:00:00'))){
                                            $html .= getDataCodeBySql('empl_name','db_empl'," WHERE empl_id = '{$row2['level1_empl']}'", $orderby) . " , ";
                                        }
                                    }
                                    
                                    if($row2['level2_empl'] > 0){
                                        if(($row2['level2_approve_date'] != '0000-00-00 00:00:00') || (($row2['level2_rejected_date'] != '0000-00-00 00:00:00'))){
                                            $html .= getDataCodeBySql('empl_name','db_empl'," WHERE empl_id = '{$row2['level2_empl']}'", $orderby) . " , ";
                                        }
                                    }
                                    
                                    if($row2['level3_empl'] > 0){
                                        if(($row2['level3_approve_date'] != '0000-00-00 00:00:00') || (($row2['level3_rejected_date'] != '0000-00-00 00:00:00'))){
                                            $html .= getDataCodeBySql('empl_name','db_empl'," WHERE empl_id = '{$row2['level3_empl']}'", $orderby) . " , ";
                                        }
                                    }
                                    
                                    echo rtrim($html,' , ');
                                }
                                ?>
                            </td>
                            <td><?php echo $this->getApprovalStatus($row['leave_approvalstatus']);?></td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                
                                    if($_SESSION['empl_group']=="9"){
                                ?>    
                                        <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'leave.php?action=edit&leave_id=<?php echo $row['leave_id'];?>&empl_type=1'">Edit</button>
                                    <?php } else {?>
                                        <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'leave.php?action=edit&leave_id=<?php echo $row['leave_id'];?>&empl_type=0'">Edit</button>
                                    <?php } }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                        if(($row['leave_approvalstatus'] == 'Draft') || ($row['leave_approvalstatus'] == 'Pending') || ($row['leave_approvalstatus'] == 'Rejected')){
                                ?>
                                        <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('leave.php?action=delete&leave_id=<?php echo $row['leave_id'];?>','Confirm Delete?')">Delete</button>
                                <?php
                                        }else if(($row['leave_approvalstatus'] == 'Approved') && (in_array($_SESSION['empl_group'],$master_group))){
                                ?>
                                        <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('leave.php?action=delete&leave_id=<?php echo $row['leave_id'];?>','Confirm Delete?')">Delete</button>
                                <?php        
                                        }
                                 }
                                 ?>
                            </td>
                        </tr>                            
                    <?php  $i++;  
                       }
                    ?>

                    </tbody>
                    <tfoot>
                      <tr>
                        <th style = 'width:4%'>No</th>
                        <th style = 'width:13%'>Employee</th>
                        <th style = 'width:10%'>Type of Leave</th>
                        <th style = 'width:13%'>Reason</th>
                        <th style = 'width:10%'>Leave Start Date</th>
                        <th style = 'width:10%'>Leave End Date</th>
                        <th style = 'width:10%'>Leave Days</th>
                        <th style = 'width:10%'>Approved / Rejected By</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
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
        
        $('#leave_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
          
        }).search("<?php echo $_REQUEST['filter'];?>").draw();

      });
    </script>
  </body>
</html>
    <?php
    }
    public function getApprovalStatus($status){
        
        switch ($status) {
            case "Pending":

                $return_status =  "<span style = 'color:red;font-weight:bold' >$status</span>";
                break;
            case "Draft":

                $return_status =  "<span style = 'color:black;font-weight:bold' >$status</span>";
                break;
            case "Approved":

                $return_status =  "<span style = 'color:Green;font-weight:bold' >$status</span>";
                break;
            case "Rejected":

                $return_status =  "<span style = 'color:#f39c12;font-weight:bold' >$status</span>";
                break;
            case "Deleted":

                $return_status =  "<span style = 'color:#795548;font-weight:bold' >$status</span>";
                break;
            default:
                $return_status =  "<span style = 'color:black;font-weight:bold' >Unknown Status</span>";
                break;
        }
        return $return_status;
    }
    public function email(){
        include_once 'Empl.php';
        $e = new Empl();
        $r = $this->fetchLeaveDetail(" AND leave_id = '$this->leave_id'","","",2);
        if($r){
            $er = $e->fetchEmplDetail(" AND empl_id = '{$r['leave_empl_id']}'","","",2);
            $days = $this->calculateLeave($r['leave_empl_id'],$r['leave_type'],$r['leave_total_day']);
            $approvel_level = $e->getApprovelEmail('leave',$r['leave_empl_id']);
//            if($approvel_level['total_need_approved'] == 0){//master no need approved level
//                $this->updateApproveStatus('Approved');
//                $this->updateEmployeeLeave($er['empl_id'],$r['leave_type'],$days);
//                if(leave_insert_cal > 0){
//                   $this->insertCal($r,$er);
//                }
//                return true;
//            }
        }

         if($this->leave_approvalstatus == 'Approved'){
             $css = 'color:green';
         }else{
             $css = 'color:red';
         }
        switch ($this->leave_approvalstatus) {
            case "Approved":
            case "Rejected":    
                $msg = "Your Leave have been <span style = '$css' >" . $this->leave_approvalstatus . "</span>.";
                break;
            default:
                break;
        }
        
        
    ob_start();
    ?>
    <html>
        <head>
          <title>Notification of Leave Status</title>
        </head>
        <body>
         <p>Greetings <?php echo $er['empl_name']?>,</p>
         <p><?php echo $msg;?></p>
         <br>
         <table width = '100%' style = 'width:100%' rules="all" style="border-color: #666;" cellpadding="10">
              <tr>
                 <td style='background: #eee;width:45%'><strong>Leave application submitted by</strong></td>
                 <td><?php echo $er['empl_name'];?></td>
             </tr>
              <tr>
                 <td style='background: #eee;'><strong>Type of leave applied</strong></td>
                 <td><?php echo getDataCodeBySql("leavetype_code","db_leavetype"," WHERE leavetype_id = '{$r['leave_type']}'");?></td>
             </tr>
              <tr>
                 <td style='background: #eee;'><strong>Period of leave application</strong></td>
                 <td><?php echo format_date($r['leave_datefrom']) . " to " . format_date($r['leave_dateto']);?></td>
             </tr>
             <tr>
                 <td style='background: #eee;'><strong>Paid leave</strong></td>
                 <td><?php echo num_format($days['paid_leave']);?></td>
             </tr>
             <tr>
                 <td style='background: #eee;'><strong>Unpaid leave</strong></td>
                 <td><?php echo num_format($days['unpaid_leave']);?></td>
             </tr>
             <tr>
                 <td style='background: #eee;'><strong>AM or PM (applicable only for 0.5 day leave):</strong></td>
                 <td>
                     <?php 
                     if($r['leave_duration'] == 'first_half'){
                        echo "Half Day AM";
                     }else if($r['leave_duration'] == 'second_half'){
                        echo "Half Day PM"; 
                     }
                     ?>
                 </td>
             </tr>
              <tr>
                 <td style='background: #eee;'><strong>Reason</strong></td>
                 <td><?php echo nl2br($r['leave_reason']);?></td>
             </tr>
         </table>
         <br>
         <p>Regards,</p>
         <p>Zal Interiors Pte Ltd</p>
        </body>
    </html>
    <?php
    $message = ob_get_clean(); 
    
    $staff_email = $er['empl_email'];
    $cc_email = "";
    if(sizeof($approvel_level) > 0){
        if($approvel_level['level1_email'] != null){
            $cc_email .= $approvel_level['level1_email'] . ",";
        }
        if($approvel_level['level2_email'] != null){
            $cc_email .=  $approvel_level['level2_email'] . ",";
        }
        if($approvel_level['level3_email'] != null){
            $cc_email .= $approvel_level['level3_email'] . ",";
        }
        $cc_email = rtrim($cc_email,",");
    }

    $query = getDataBySql('COUNT(*) as total, approved_id','db_leave_approved'," WHERE type_code = 'leave' AND type_id = '{$r['leave_id']}'");
    if($row = mysql_fetch_array($query)){
        $totalapproverecord = $row['total'];
    }

    if($totalapproverecord == 0){
        $this->insertApproveLevel('leave',$r['leave_id'],$approvel_level);
    }
//    $my_file = "Staff Claim Form.xlsx";
    $my_path = "";
    $my_name = $_SESSION['empl_name'] . " | Zal Interiors";
    $my_mail = $_SESSION['empl_email'];
    $my_subject = "Leave Request By " . $er['empl_name'];
       
    
    mail_attachment($my_file,$my_path,$staff_email,$my_mail,$my_name, $my_replyto,$my_subject,$message,$cc_email);
    return true;
    }
    public function calculateLeave($empl_id,$leave_apply_type,$leave_apply_days){

//leave type id 10 = "Urgent Leave";
//leave type id 1 = "Annual Leave";

// Customer mention urgent leave same with annual leave , calculate together at 2016-12-07 (Zen)

if($leave_apply_type == 10){
   $leave_apply_type = 1;// convert urgent leave to annual leave
}
        $sql = "SELECT emplleave_days FROM db_emplleave WHERE emplleave_leavetype = '$leave_apply_type' AND emplleave_empl = '$empl_id' AND emplleave_status = '1' AND emplleave_year = '" . date("Y") . "'";
        $query = mysql_query($sql);
        $balance_days = 0;
        if($row = mysql_fetch_array($query)){
            $balance_days = $row['emplleave_days'];
        }
        if($leave_apply_type == 3){// hospitalisation leave
            $totalLeaveTaken = $this->calTotalLeaveTaken(" AND leave_type IN ('3','2') AND leave_datefrom >= '" . system_date_yearstart . "' AND leave_dateto <= '" . system_date_yearend . "'");
        }else{
            if($balance_days <= $leave_apply_days){
                $unpaid_leave = $leave_apply_days - $balance_days;
                $paid_leave = $balance_days;
            }else{
                $unpaid_leave = 0;
                $paid_leave = $leave_apply_days;
            }
        }
        return array('paid_leave'=>$paid_leave,'unpaid_leave'=>$unpaid_leave);
    }
    public function insertApproveLevel($type_code,$type_id,$approvel_level){
      
        $table_field = array('type_code','type_id','level1_empl','level1_approve_date',
                             'level2_empl','level2_approve_date','level3_empl','level3_approve_date');
        $table_value = array($type_code,$type_id,$approvel_level['level1_id'],"0000-00-00 00:00:00",
                             $approvel_level['level2_id'],"0000-00-00 00:00:00",$approvel_level['level3_id'],"0000-00-00 00:00:00");
        $remark = "Insert $type_code Apply Level.";
        if(!$this->save->SaveData($table_field,$table_value,'db_leave_approved','approved_id',$remark)){
           return false;
        }else{
           $this->approved_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function updateApproveLevel($ap,$approved_id){
        $table_field = array($ap['field'],$ap['msg_field']);
        $table_value = array($ap['data'],$this->sstatus_message);
        $remark = "Update Apply Level.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_leave_approved','approved_id',$remark,$approved_id)){
           return false;
        }else{
           $this->updateApproveStatus('Approved');
           $this->updateEmployeeLeave($er['empl_id'],$r['leave_type'],$days);
           return true;
        }
    }
    public function checkAccess($action){
        global $master_group;
        if(($action == 'createForm') || ($action == 'create') || ($action == '')){
            return true;
        }
        if(!in_array($_SESSION['empl_group'],$master_group)){
            $empl_id = getDataCodeBySql("leave_empl_id","db_leave"," WHERE leave_id = '$this->leave_id'","");
            $record_count = getDataCountBySql("db_leave"," WHERE leave_empl_id = '$empl_id'");

            if(((($empl_id <= 0) && ($record_count == 0)) || ($empl_id != $_SESSION['empl_id']))){
                permissionLog();
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
                exit();
            }
        }
    }
    public function calculateDateDifferent($date_form,$date_to){
        $start = new DateTime($date_form);
        $end = new DateTime($date_to);
        // otherwise the  end date is excluded (bug?)
        $end->modify('+1 day');

        $interval = $end->diff($start);

        // total days
        $days = $interval->days;

        // create an iterateable period of date (P1D equates to 1 day)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

        // best stored as array, so you can add more than one
        $holidays = array('2012-09-07');

        foreach($period as $dt) {
            $curr = $dt->format('D');

            // for the updated question
            if (in_array($dt->format('Y-m-d'), $holidays)) {
               $days--;
            }

            // substract if Saturday or Sunday
            if ($curr == 'Sat' || $curr == 'Sun') {
                $days--;
            }
        }
        return $days;
    }
    public function updateEmployeeLeave($empl_id,$emplleave_leavetype,$days){
        if($days['paid_leave'] <=0){
            $days_paid = 0;
        }else{
            $days_paid = $days['paid_leave'];
        }
        if($days['unpaid_leave'] <=0){
            $days_unpaid = 0;
        }else{
            $days_unpaid = $days['unpaid_leave'];
        }
        $sql = "UPDATE db_emplleave SET emplleave_days = emplleave_days - $days_paid WHERE emplleave_empl = '$empl_id' AND emplleave_leavetype = '$emplleave_leavetype' AND emplleave_status = '1' AND emplleave_year = '" . date("Y") . "'";
        $query = mysql_query($sql);
        if($query){
             $sql = "UPDATE db_leave SET leave_unpaid = '$days_unpaid' WHERE leave_id = '$this->leave_id'";
             $query = mysql_query($sql);
            return true;
        }else{
           return false;
        }

    }
    public function checkIsSubmitApproval($leave_id,$type){
        $sql = "SELECT * FROM db_leave_approved WHERE type_id = '$leave_id' and type_code = '$type'";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
            $d = "";
            switch ($_SESSION['empl_id']) {
                case $row['level1_empl']:
                    $d = $row['level1_approve_date'];
                    $c = $row['level1_rejected_date'];
                    break;
                case $row['level2_empl']:
                    $d = $row['level2_approve_date'];
                    $c = $row['level2_rejected_date'];
                    break;
                case $row['level3_empl']:
                    $d = $row['level3_approve_date'];
                    $c = $row['level3_rejected_date'];
                    break;
                default:
                    break;
            }

            if(($d == "0000-00-00 00:00:00") && ($c == "0000-00-00 00:00:00")){
                return true;
            }else{
                return false;
            }
        }
    }
    public function checkAllApproved($leave_id,$type){
       $sql = "SELECT * FROM db_leave_approved WHERE type_id = '$leave_id' AND type_code = '$type'";
       $query = mysql_query($sql);
       $b = 0;
       $c = 0;
       $t = 0;
       if($row = mysql_fetch_array($query)){
           if($row['level1_empl'] > 0){
               $t++;
               if($row['level1_approve_date'] != '0000-00-00 00:00:00'){
                   $b++;
               }
               
               if($row['level1_rejected_date'] != '0000-00-00 00:00:00'){
                   $c++;
               }
           }
           
           if($row['level2_empl'] > 0){
               $t++;
               if($row['level2_approve_date'] != '0000-00-00 00:00:00'){
                   $b++;
               }
               
               if($row['level2_rejected_date'] != '0000-00-00 00:00:00'){
                   $c++;
               }
           }
           
           if($row['level3_empl'] > 0){
               $t++;
               if($row['level3_approve_date'] != '0000-00-00 00:00:00'){
                   $b++;
               }
               
               if($row['level3_rejected_date'] != '0000-00-00 00:00:00'){
                   $c++;
               }
           }
           
           
       }
        // check reject bigger or not.
        if($t == 3){
            if($c == 2){
                $this->sstatus_action = 'Rejected';
                return true;
            }
        }else if(($t == 1) || ($t == 2)){
            if($c >= 1){
               $this->sstatus_action = 'Rejected';
               return true;
            }
        }
        $approved_need = 0;
        if($type == 'leave'){
            $approved_need = leave_approved_need;
        }else if($type == 'claims'){
            $approved_need = claims_approved_need;
        }
        if($b >= $approved_need){
            $this->sstatus_action = 'Approved';
            return true;
        }else{
            return false;
        }
    }
    public function insertCal($r,$er){
    global $db_name,$db_user,$db_passwd,$db_name_cal,$db_user_cal,$db_passwd_cal;
        $handle  =  mysql_connect("localhost", $db_user_cal, $db_passwd_cal) or die(mysql_error());
 
        mysql_query("USE $db_name_cal",$handle);
        
        $leave_type = $r['leave_type'];
        $leave_reason = $r['leave_reason'];
        $leave_datefrom = date("d/m/Y", strtotime($r['leave_datefrom']));
        $leave_dateto = date("d/m/Y", strtotime($r['leave_dateto']));
        $date_time = system_datetime;
        
        /*
         * Leave (Annual) = 26
         * Medical (Leave) = 23
         * Medical (Hospitalization) = 35
         * Leave (Childcare) = 29
         * Leave (Marriage) = 31
         * Leave (Maternity) = 32
         */
        switch ($leave_type) {
            case 1:
                $event_title = "Leave (Annual)";
                $event_id = "26";
                break;
            case 2:
                $event_title = "Medical (Leave)";
                $event_id = "23";
                break;
            case 3:
                $event_title = "Medical (Hospitalization)";
                $event_id = "35";
                break;
            case 4:
                $event_title = "Leave (Childcare)";
                $event_id = "29";
                break;
            case 5:
                $event_title = "Marriage Leave";
                $event_id = "31";
            case 7:
                $event_title = "Maternity Leave";
                $event_id = "32";
                break;
            case 8:
                $event_title = "Off In Lieu";
                $event_id = "46";
                break;
            case 9:
                $event_title = "Leave (Unpaid)";
                $event_id = "34";
                break;
            case 10:
                $event_title = "Leave (Urgent)";
                $event_id = "25";
                break;
            case 11:
                $event_title = "Reservist";
                $event_id = "45";
                break;
            default:
                $event_title = "Leave (Unpaid)";
                $event_id = "34";
                break;

        }

            $sql = "INSERT INTO tbl_calendar (event_title,notes,location,event_type,date_from,start_time,date_to,end_time,date_added,is_repeat,reminder)
                   VALUES ('$event_title','" . escape($leave_reason) . "','','$event_id','$leave_datefrom','09:00:00','$leave_dateto','18:00:00','$date_time',0,0)";
            mysql_query($sql);
          
            // get Last insert id
            $sql = "SELECT MAX(calendar_id) as last_id FROM tbl_calendar WHERE date_added = '$date_time'";
            $query = mysql_query($sql);
            if($row = mysql_fetch_array($query)){
                $last_id = $row['last_id'];
            }else{
                $last_id = 0;
            }
            
            // get employee id at db cal
            $sql = "SELECT user_id FROM tbl_users WHERE email = '{$er['empl_email']}'";
            $query = mysql_query($sql);
            if($row = mysql_fetch_array($query)){
                $cal_user_id = $row['user_id'];
            }else{
                $cal_user_id = 0;
            }
            
            
            //insert calendar assigned table
            $sql = "INSERT INTO tbl_calendar_assigned (calendar_id,assigned_id,assigned_type,added_by,organizer_id,status,seen_status,group_id,date_added,attendees,is_invitation_sent)
                   VALUES ('$last_id','$cal_user_id','organizer','$cal_user_id','$cal_user_id','C','0','0',now(),'I',0)";

            mysql_query($sql);
            
            $handle  =  mysql_connect("localhost",$db_user,$db_passwd) or die(mysql_error());
            mysql_query("USE $db_name",$handle);
    }
    public function calTotalLeaveTaken($wherestring){
        $sql = "SELECT SUM(leave_total_day) as total FROM db_leave WHERE leave_approvalstatus = 'Approved' $wherestring";
        $query = mysql_query($sql);
        
        if($row = mysql_fetch_array($query)){
            $total = $row['total'];
        }else{
            $total = 0;
        }
        return $total;
    }
    public function doSstatus(){
        
        include_once 'Empl.php';
        $e = new Empl();
        $r = $this->fetchLeaveDetail(" AND leave_id = '$this->leave_id'","","",2);
        if($r){
            $er = $e->fetchEmplDetail(" AND empl_id = '{$r['leave_empl_id']}'","","",2);
            $days = $this->calculateLeave($r['leave_empl_id'],$r['leave_type'],$r['leave_total_day']);
            $approvel_level = $e->getApprovelEmail('leave',$r['leave_empl_id']);

            switch ($_SESSION['empl_id']) {
                case $approvel_level['level1_id']:
                    $ap['field'] = 'level1';
                    $ap['data'] = system_datetime;
                    break;
                case $approvel_level['level2_id']:
                    $ap['field'] = 'level2';
                    $ap['data'] = system_datetime;
                    break;
                case $approvel_level['level3_id']:
                    $ap['field'] = 'level3';
                    $ap['data'] = system_datetime;
                    break;
                default:
                    break;
            }
            $ap['msg_field'] = $ap['field'] . "_message";
            if($this->sstatus_action == 'Rejected'){
                $ap['field'] = $ap['field'] . "_rejected_date";
            }else{
                $ap['field'] = $ap['field'] . "_approve_date";
            }
           
            
            $query = getDataBySql('COUNT(*) as total, approved_id','db_leave_approved'," WHERE type_code = 'leave' AND type_id = '{$r['leave_id']}'");
            if($row = mysql_fetch_array($query)){
                $totalapproverecord = $row['total'];
                $approved_id = $row['approved_id'];
            }
            if($this->sstatus_action == 'Approved'){
                $this->saveApproveNotification($this->sstatus_action);
            }
            if($this->updateApproveLevel($ap,$approved_id)){
                if($this->checkAllApproved($r['leave_id'],'leave')){
                    if($this->sstatus_action == 'Approved'){
                        $this->updateEmployeeLeave($er['empl_id'],$r['leave_type'],$days);
                        if(leave_insert_cal > 0){
                           $this->insertCal($r,$er);
                        }
                    }
                    $this->updateApproveStatus($this->sstatus_action);
                    $this->saveApproveNotification($this->sstatus_action);
                    $this->emailSstatus($approvel_level,$r,$er);
                }
            }
            
            return true;
        }else{
            return false;
        }
    }
    public function emailSstatus($approvel_level,$r,$er){

         if($this->sstatus_action == 'Approved'){
             $css = 'color:green';
         }else{
             $css = 'color:red';
         }
        switch ($this->sstatus_action) {
            case "Approved":
            case "Rejected":    
                $msg = "Your Leave have been <span style = '$css' >" . $this->sstatus_action . "</span>.";
                break;
            default:
                break;
        }
        
    ob_start();
    ?>
    <html>
        <head>
          <title>Notification of Leave Status</title>
        </head>
        <body>
         <p>Greetings <?php echo $er['empl_name']?>,</p>
         <p><?php echo $msg;?></p>
         <br>
         <table width = '100%' style = 'width:100%' rules="all" style="border-color: #666;" cellpadding="10">
              <tr>
                 <td style='background: #eee;width:45%'><strong>Leave application submitted by</strong></td>
                 <td><?php echo $er['empl_name'];?></td>
             </tr>
              <tr>
                 <td style='background: #eee;'><strong>Type of leave applied</strong></td>
                 <td><?php echo getDataCodeBySql("leavetype_code","db_leavetype"," WHERE leavetype_id = '{$r['leave_type']}'");?></td>
             </tr>
              <tr>
                 <td style='background: #eee;'><strong>Period of leave application</strong></td>
                 <td><?php echo format_date($r['leave_datefrom']) . " to " . format_date($r['leave_dateto']);?></td>
             </tr>
             <tr>
                 <td style='background: #eee;'><strong>AM or PM (applicable only for 0.5 day leave):</strong></td>
                 <td>
                     <?php 
                     if($r['leave_duration'] == 'first_half'){
                        echo "Half Day AM";
                     }else if($r['leave_duration'] == 'second_half'){
                        echo "Half Day PM"; 
                     }
                     ?>
                 </td>
             </tr>
         </table>
         <br>
         <p>Regards,</p>
         <p>Zal Interiors Pte Ltd</p>
        </body>
    </html>
    <?php
    $message = ob_get_clean(); 
    
    $staff_email = $er['empl_email'];
    $cc_email = "";
    if(sizeof($approvel_level) > 0){
        if($approvel_level['level1_email'] != null){
            $cc_email .= $approvel_level['level1_email'] . ",";
        }
        if($approvel_level['level2_email'] != null){
            $cc_email .=  $approvel_level['level2_email'] . ",";
        }
        if($approvel_level['level3_email'] != null){
            $cc_email .= $approvel_level['level3_email'] . ",";
        }
        $cc_email = rtrim($cc_email,",");
    }
//    $my_file = "Staff Claim Form.xlsx";
    $my_path = "";
    $my_name = $_SESSION['empl_name'] . " | Zal Interiors";
    $my_mail = $_SESSION['empl_email'];
    $my_subject = "Leave Request By " . $er['empl_name'];
       

    mail_attachment($my_file,$my_path,$staff_email,$my_mail,$my_name, $my_replyto,$my_subject,$message,$cc_email);
    }
    public function putBackLeaveDays(){
        $r = $this->fetchLeaveDetail(" AND leave_id = '$this->leave_id'","","",2);
        $days = $r['leave_total_day'];
        if($r['leave_id'] > 0){
            $sql = "UPDATE db_emplleave SET emplleave_days = emplleave_days+$days WHERE emplleave_leavetype = '{$r['leave_type']}' AND emplleave_empl = '{$r['leave_empl_id']}' AND emplleave_status = '1' AND emplleave_year = '" . date("Y") . "'";
            mysql_query($sql);
        }
    }
    public function saveNotification($leave_approvalstatus){
        $table_field = array('noti_id','noti_to','noti_url','noti_parent_id','noti_desc','noti_view_status','noti_type');
        
        if($_SESSION['empl_group'] == "5"){
            
            $sql = "SELECT applicant_leave_approved1, applicant_leave_approved2, applicant_leave_approved3 FROM db_applicant WHERE applicant_id = '$_SESSION[empl_id]'";
            $query = mysql_query($sql);
            $row = mysql_fetch_array($query);
            
            for($i = 1; $i < 4; $i++){
                if($row['applicant_leave_approved'.$i] != "0"){
                    $table_value = array('', $row['applicant_leave_approved'.$i],'leave.php?action=edit&leave_id='.$this->leave_id."&empl_type=1", $this->leave_id,'Apply Leave',0,1);
                    $this->save->SaveData($table_field,$table_value,'db_notification','noti_id',$remark);
                    return true;
                }
            } 
        }
//        else if($_SESSION['empl_group'] == "9"){
//                    $sql = "SELECT leave_empl_id FROM db_leave WHERE leave_id = '$this->leave_id'";
//                    $query = mysql_query($sql);
//                    $row = mysql_fetch_array($query);
//                    
//                    $table_field = array('noti_id','noti_to','noti_url','noti_parent_id','noti_desc','noti_view_status');
//                    $table_value = array('',$row['leave_empl_id'] ,'leave.php?action=edit&leave_id='.$this->leave_id, $this->leave_id,'Your leave has been ' .$leave_approvalstatus,0);
//                    $this->save->SaveData($table_field,$table_value,'db_notification','noti_id',$remark);
//        }
        else{
            $sql = "SELECT empl_leave_approved1, empl_leave_approved2, empl_leave_approved3 FROM db_empl WHERE empl_id = '$_SESSION[empl_id]'";
            $query = mysql_query($sql);
            $row = mysql_fetch_array($query);
            
            for($i = 1; $i < 4; $i++){
                if($row['empl_leave_approved'.$i] != "0"){
                    $table_value = array('', $row['empl_leave_approved'.$i],'leave.php?action=edit&leave_id='.$this->leave_id."&empl_type=0", $this->leave_id,'Apply Leave',0,0);
                    $this->save->SaveData($table_field,$table_value,'db_notification','noti_id',$remark);
                    return true;
                }
            }             
        }
    }
    public function saveApproveNotification($leave_approvalstatus){
        $table_field = array('noti_id','noti_to','noti_url','noti_parent_id','noti_desc','noti_view_status','noti_type');
        $empl_type = escape($_REQUEST['empl_type']);
        
        if($_SESSION['empl_group'] == "9" && $empl_type == "0"){
                    $sql = "SELECT leave_empl_id FROM db_leave WHERE leave_id = '$this->leave_id'";
                    $query = mysql_query($sql);
                    $row = mysql_fetch_array($query);
                    
                    $table_field = array('noti_id','noti_to','noti_url','noti_parent_id','noti_desc','noti_view_status');
                    $table_value = array('',$row['leave_empl_id'] ,'leave.php?action=edit&leave_id='.$this->leave_id, $this->leave_id,'Your leave has been ' .$leave_approvalstatus,0);
                    $this->save->SaveData($table_field,$table_value,'db_notification','noti_id',$remark);
        }
        else{
                    $sql = "SELECT leave_empl_id FROM db_leave WHERE leave_id = '$this->leave_id'";
                    $query = mysql_query($sql);
                    $row = mysql_fetch_array($query);
                    
                    $table_field = array('noti_id','noti_to','noti_url','noti_parent_id','noti_desc','noti_view_status');
                    $table_value = array('',$row['leave_empl_id'] ,'leave.php?action=edit&leave_id='.$this->leave_id, $this->leave_id,'Your leave has been ' .$leave_approvalstatus,0);
                    $this->save->SaveData($table_field,$table_value,'db_notification','noti_id',$remark);           
        }
    }    
    public function getBalance(){
        $leave_type = $_REQUEST['leavetype_id'];
        $appl_id = $_REQUEST['appl_id'];
        $sql = "SELECT applleave_days FROM db_applleave WHERE applleave_appl = '$appl_id' AND applleave_leavetype = '$leave_type'";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);
        return $row['applleave_days'];
    }
}
?>
