<?php
/*
 * To change this tclaimsate, choose Tools | Tclaimsates
 * and open the tclaimsate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Claims {

    public function Claims(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('claims_title','claims_date','claims_remark','claims_status',
                             'claims_empl_id','claims_approvalstatus');
        $table_value = array($this->claims_title,format_date_database($this->claims_date),$this->claims_remark,1,
                             $_SESSION['empl_id'],$this->claims_approvalstatus);
        $remark = "Insert Apply Claims.";
        if(!$this->save->SaveData($table_field,$table_value,'db_claims','claims_id',$remark)){
           return false;
        }else{
           $this->claims_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $table_field = array('claims_title','claims_date','claims_remark','claims_status',
                             'claims_empl_id','claims_approvalstatus');
        $table_value = array($this->claims_title,format_date_database($this->claims_date),$this->claims_remark,1,
                             $_SESSION['empl_id'],$this->claims_approvalstatus);
        $remark = "Update Apply Claims.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_claims','claims_id',$remark,$this->claims_id)){
           return false;
        }else{
           $this->createUpdateClaimsLine();
           return true;
        }
    }
    public function updateApproveStatus($claims_approvalstatus){
        $table_field = array('claims_approvalstatus');
        $table_value = array($claims_approvalstatus);
        $remark = "Update Approve Status.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_claims','claims_id',$remark,$this->claims_id)){
           return false;
        }else{
           return true;
        }
    }
    public function pictureManagement($i,$action){
        if(!file_exists("dist/images/claims")){
           mkdir('dist/images/claims/');
        }


        if($this->image_input['size'][$i] > 0 ){

            if($action == 'update'){
                $sql = "SELECT image_id,image_type FROM db_image WHERE ref_id = '$this->claimsline_id'";
                $query = mysql_query($sql);
                if($row = mysql_fetch_array($query)){
                     unlink("dist/images/claims/{$this->claims_id}_{$this->claimsline_id}.{$row['image_type']}");
                }
            }
            
            $type = end(explode(".",$this->image_input['name'][$i]));
            $table_field = array('ref_table','ref_id','image','image_type','status');
            $table_value = array('db_claimsline',$this->claimsline_id,$this->image_input['name'][$i],$type,1);
            $remark = "manage claims image";
            $exist = getDataCountBySql("db_image"," WHERE ref_id = '$this->claimsline_id'");
            if($exist > 0){
                $this->save->UpdateData($table_field,$table_value,'db_image','ref_id',$remark,$this->claimsline_id);
            }else{
                $this->save->SaveData($table_field,$table_value,'db_image','image_id',$remark);
            }
            move_uploaded_file($this->image_input['tmp_name'][$i],"dist/images/claims/{$this->claims_id}_{$this->claimsline_id}.$type");
        }
    }
    public function createUpdateClaimsLine(){

        include_once 'Claimstype.php';
        $ct = new Claimstype();
        $true = true;
        $total_claims_amount = 0;
//        var_dump($this->image_input);die;

        for($i=1;$i<=sizeof($this->claimsline_type_array);$i++){
            if($this->claimsline_type_array[$i] <= 0){
                continue;//skip if user not pick
            }
            $claims_amount = str_replace(",", "",$this->claimsline_amount_array[$i]) + str_replace(",", "",$this->claimsline_amount_gst_array[$i]);
            $ct->fetchClaimstypeDetail(" AND claimstype_id = '{$this->claimsline_type_array[$i]}'","","",1);
//            if($ct->claimstype_maxamt > 0){
//                if($claims_amount > $ct->claimstype_maxamt){
//                   $claims_amount = $ct->claimstype_maxamt;
//                }
//            }
            $table_field = array('claimsline_seqno','claimsline_date','claimsline_type','claimsline_desc',
                                 'claimsline_receiptno','claimsline_amount','claimsline_amount_gst',
                                 'claimsline_claims_id','claimsline_gst_reg_no');
            $table_value = array(escape($this->claimsline_seqno_array[$i]),format_date_database(escape($this->claimsline_date_array[$i])),escape($this->claimsline_type_array[$i]),escape($this->claimsline_desc_array[$i]),
                                 escape($this->claimsline_receiptno_array[$i]),escape(str_replace(",", "",$this->claimsline_amount_array[$i])),escape(str_replace(",", "",$this->claimsline_amount_gst_array[$i])),
                                 $this->claims_id,escape($this->claimsline_gst_reg_no_array[$i]));
            
            if($this->claimsline_id_array[$i] > 0){
                $remark = "Update Claims Lines.";
                if(!$this->save->UpdateData($table_field,$table_value,'db_claimsline','claimsline_id',$remark,$this->claimsline_id_array[$i]," AND claimsline_claims_id = '$this->claims_id'")){
                   $true = false;
                }else{
                    $this->claimsline_id = $this->claimsline_id_array[$i];
                    $this->pictureManagement($i,'update');
                } 
            }else{
                $remark = "Insert Claims Lines.";
                if(!$this->save->SaveData($table_field,$table_value,'db_claimsline','claimsline_id',$remark)){
                    $true = false;
                }else{
                    $this->claimsline_id = $this->save->lastInsert_id;
                    $this->pictureManagement($i);
                } 
            }
            if(is_numeric($claims_amount)){
                $total_claims_amount = $total_claims_amount + $claims_amount;
            }
        }
        $this->UpdateClaimsAmount($total_claims_amount);

    }
    public function UpdateClaimsAmount($total_claims_amount){
            $table_field = array('claims_amount');
            $table_value = array($total_claims_amount);
            $remark = "Update Claims Amount.";
            if(!$this->save->UpdateData($table_field,$table_value,'db_claims','claims_id',$remark,$this->claims_id)){
               return false;
            }else{
                return true;
            } 
    }
    public function UpdateClaimsSingleLine(){
            $table_field = array('claimsline_seqno','claimsline_date','claimsline_type','claimsline_desc',
                                 'claimsline_receiptno','claimsline_amount');
            $table_value = array($this->claimsline_seqno_array,format_date_database($this->claimsline_date_array),$this->claimsline_type_array,$this->claimsline_desc_array,
                                 $this->claimsline_receiptno_array,$this->claimsline_amount_array);
            $remark = "Update Claims Lines.";
            if(!$this->save->UpdateData($table_field,$table_value,'db_claimsline','claimsline_id',$remark,$this->claimsline_id," AND claimsline_claims_id = '$this->claims_id'")){
               return false;
            }else{
                return true;
            } 
    }
    public function fetchClaimsDetail($wherestring,$orderstring,$wherelimit,$type){
        global $master_group;
        $sql = "SELECT * FROM db_claims WHERE claims_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->claims_id = $row['claims_id'];
            $this->claims_empl_id = $row['claims_empl_id'];
            $this->claims_title = $row['claims_title'];
            $this->claims_date = $row['claims_date'];
            $this->claims_amount = $row['claims_amount'];
            $this->claims_remark = $row['claims_remark'];
            $this->claims_status = $row['claims_status'];
            $this->claims_approvalstatus = $row['claims_approvalstatus'];
            if(!in_array($_SESSION['empl_group'],$master_group)){
                if($this->claims_empl_id != $_SESSION['empl_id']){
                    permissionLog();
                    rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
                    exit();
                }
            }
        }else if($type == 2){
            return mysql_fetch_array($query);
        }
        if(mysql_num_rows($query) == 0 ){
            return false;
        }else{
            return $query;
        }
    }
    public function delete(){
        $table_field = array('claims_status','claims_approvalstatus');
        $table_value = array(0,'Deleted');
        $remark = "Delete Claims.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_claims','claims_id',$remark,$this->claims_id)){
           return false;
        }else{
           return true;
        }
    }
    public function deleteClaimsLine(){
        if($this->save->DeleteData("db_claimsline"," WHERE claimsline_claims_id = '$this->claims_id' AND claimsline_id = '$this->claimsline_id'","Delete {$this->claims_id} Order Line.")){
            unlink("dist/images/claims/{$this->claims_id}_{$this->claimsline_id}.png");
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        include_once 'class/Empl.php';
        include_once 'class/Leave.php';
        $l = new Leave();
        $e = new Empl();
        if($action == 'create'){
            $this->claims_seqno = 10;
            $this->claims_status = 1;
            $this->claims_date = system_date;
            $this->claims_dateto = system_date;
            $this->claims_total_day = 1;
            $this->claims_approvalstatus = 'Draft';
            $this->claims_duration = "full_day";
            $empl_data = $e->fetchEmplDetail(" AND empl_id = '{$_SESSION['empl_id']}'","","",2);
            $empl_code = $_SESSION['empl_code'];
            $empl_name = $_SESSION['empl_name'];
        }else{
            $empl_data = $e->fetchEmplDetail(" AND empl_id = '$this->claims_empl_id'","","",2);
            $empl_code = $empl_data['empl_code'];
            $empl_name = $empl_data['empl_name'];
        }
        if($this->claims_approvalstatus != "Draft"){
            $disabled = " DISABLED";
        }

    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Claims Management</title>
    <?php
    include_once 'css.php';
    $this->claimstypeCrtl = $this->select->getClaimsTypeSelectCtrl();
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
            <h1>Claims Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->claims_id > 0){ echo "Update Claims";}else{ echo "Submit New Claims";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = '' onclick = "window.location.href='claims.php'">Search <i class="fa fa-fw fa-search"></i></button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'margin-right:10px;' onclick = "window.location.href='claims.php?action=createForm'">Create New <i class="fa fa-fw fa-plus"></i></button>
                
                <button type = "button" class="btn btn-primary pull-right" style = 'margin-right:10px;' id = 'duplicate_btn'>Duplicate <i class="fa fa-fw fa-copy"></i></button>
                <?php }?>
              </div>

                <form id = 'claims_form' class="form-horizontal" action = 'claims.php?action=create' method = "POST" enctype="multipart/form-data" onsubmit = 'return checkconfirm()'>
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
                        <input type="text" class="form-control " value = "<?php echo $empl_name;?>" placeholder="Employee Name" disabled>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="claims_amount" class="col-sm-2 control-label">Amount</label>
                      <div class="col-sm-4">
                          <input type="text" class="form-control" id="claims_amount" name="claims_amount" value = "<?php echo num_format($this->claims_amount);?>" placeholder="Amount" <?php echo $disabled;?> READONLY>
                      </div>
                    </div>
                     <div class="form-group">
                         <label for="claims_date" class="col-sm-2 control-label">Date</label>
                      <div class="col-sm-4">
                          <input type="text" class="form-control datepicker" id="claims_date" name="claims_date" value = "<?php echo format_date($this->claims_date);?>" placeholder="Date" <?php echo $disabled;?>>
                      </div>
                     </div>   
                    <div class="form-group">
                      <label for="claims_remark" class="col-sm-2 control-label">Remarks</label>
                      <div class="col-sm-4">
                            <textarea class="form-control" rows="3" id="claims_remark" name="claims_remark" placeholder="Remarks" <?php echo $disabled;?>><?php echo $this->claims_remark;?></textarea>
                      </div>
                      
                      <?php
                      if($this->claims_approvalstatus != 'Draft'){
                      ?>
                            <label for="claims_approvalstatus" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-2">
                                <label for="claims_reason" class="col-sm-3 control-label" style = 'color:red;' ><b><?php echo $this->getApprovalStatus($this->claims_approvalstatus);?></b></label>
                            </div>
                      <?php
                      }
                      ?>
                        <div class="col-sm-2">    
                        <?php
                            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],"approved")){
                                if($this->claims_approvalstatus != 'Draft'){
                                    if($l->checkIsSubmitApproval($this->claims_id,'claims')){//check the user already submit approval or not true:haven,false:submited
                                        $disabled = " ";
                                    }else{
                                        $disabled = " disabled";
                                    }
                                    
                                    if($this->claims_approvalstatus == 'Deleted'){
                                        $disabled = " disabled";
                                    }
                        ?>
                        <button type = 'button' <?php echo $disabled;?> class="btn btn-block btn-success btn-lg astatus" pid = 'Approved' data-toggle="modal" data-target="#sstatusModal">Approve</button>
                        <button type = 'button' <?php echo $disabled;?> class="btn btn-block btn-warning btn-lg astatus" pid = 'Rejected' data-toggle="modal" data-target="#sstatusModal">Reject</button>
                                <?php }
                                }?>
                        </div>
                    </div>
                        
                    </div>
                      
                    <div class="col-md-12">
                                  <!-- The time line -->
                                  <ul class="timeline">

                                    <?php
                                    
                                    $sql = "SELECT * FROM db_leave_approved WHERE type_id = '$this->claims_id' AND type_code = 'claims'";
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
                      
                      
                      
                    <div class="col-sm-4" style = 'text-align:center' >    
                            
                   </div>
                      <div style = 'clear:both'></div>
                      <div>
                          <div class = 'pull-left' ><h3>Reimbursement Items</h3></div>
                          <div class = 'pull-right'>
                              <?php if($this->claims_approvalstatus == 'Draft'){?>
                              <button type = 'button' class = 'btn btn-info addnewline' style = 'margin-top:15px;' >Add New Row</button>
                              <?php }?>
                          </div>
                      </div>
                      <div style = 'clear:both'></div>  
                        
                        <?php echo $this->getAddItemDetailForm();?>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Back</button>
                    
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->claims_id;?>" name = "claims_id"/>
                    <?php 
                    if($this->claims_id > 0){
                        $prm_code = "update";
                    }else{
                        $prm_code = "create";
                    }
                    if($this->claims_approvalstatus == 'Draft'){
                        
                        if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){?>&nbsp;&nbsp;&nbsp;
                        <button type = "submit" name = 'submit_btn' value = 'Save' class="btn btn-info submit_btn">Save</button>
                        &nbsp;&nbsp;&nbsp;
                        <button type = "submit" name = 'submit_btn' value = 'Confirm' class="btn btn-danger submit_btn">Confirm</button>
                        <?php 
                        }
                    }
                    
                    if((getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'print')) && ($this->claims_id > 0) ){
                    ?>&nbsp;&nbsp;&nbsp;
                        <a href="claims-print.php?claims_id=<?php echo $this->claims_id;?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                    <?php }?>    
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
        <form action = 'claims.php' method = "POST">
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
                  <input type = 'hidden' name = 'claims_id' value = '<?php echo $this->claims_id;?>' />
              </div>
            </div>
        </form>
    </div>
  </div>
    <!-- CK Editor -->
    <script src="dist/js/ckeditor/ckeditor.js"></script>
    <script>
    var line_copy = '<tr id = "line_@i" class="tbl_grid_odd" line = "@i">' +
                    '<td style = "width:5%;padding-left:5px">@i</td>' + 
                    '<td style = "width:10%;"><input type = "text" name = "claimsline_date[@i]" id = "claimsline_date_@i" class="form-control datepicker" value=""/></td>'+
                    '<td style = "width:10%;"><select style = "width:100%" name = "claimsline_type[@i]" id = "claimsline_type_@i" class="form-control select2 "><?php echo $this->claimstypeCrtl;?></select></td>'+
                    '<td class = "width:20%;"><textarea name = "claimsline_desc[@i]" id = "claimsline_desc_@i" class="form-control"></textarea></td>'+
                    '<td style = "width:15%;"><input type = "file" name = "claimsline_receiptno[@i]" id = "claimsline_receiptno_@i" class="form-control"/></td>'+
                    '<td style = "width:10%;"><input type = "text" name = "claimsline_amount[@i]" id = "claimsline_amount_@i" line = "@i" class="form-control calculate text-align-right" value = "0.00"/></td>'+
                    '<td style = "width:10%;"><input type = "text" name = "claimsline_amount_gst[@i]" id = "claimsline_amount_gst_@i" line = "@i" class="form-control calculate text-align-right" value = "0.00"/></td>'+
                    '<td style = "width:10%;"><input disabled type = "text" id = "claimsline_amount_total_@i" line = "@i" class="form-control  text-align-right" /></td>'+
                    '<td style = "width:10%;"><input type = "text" name = "claimsline_gst_reg_no[@i]" id = "claimsline_gst_reg_no_@i" line = "@i" class="form-control text-align-right" value = ""/></td>'+
                    '<td align = "center" class = "" style ="vertical-align:top;min-width:10%;padding-right:10px;padding-left:5px">' +
                    //'<a style = "margin-left:10px;margin-right:10px;" href = "#" id = "save_line_@i" claimsline_id = "" class = "save_line font-icon" line = "@i" ><i class="fa fa-plus" aria-hidden="true"></i></a>' + 
                    //'<a style = "margin-left:10px;margin-right:10px;" href = "#" id = "delete_line_@i" claimsline_id = "" class = "delete_line font-icon" line = "@i" ><i class="fa fa-trash-o" aria-hidden="true"></i></a>' + 
                    '</td>'+
                    '</tr>';
    $(document).ready(function() {
        var sstatus_CK = CKEDITOR.replace('sstatus_message'); 
        <?php if($this->claims_approvalstatus == 'Draft'){?>
        addline();
        <?php }?>
            
        $(".submit_btn").click(function() {
           $(this).parents("form").attr("clickby",$(this).val());
        });
        $('.addnewline').click(function(){
            addline();
        });
        $('.save_line').on('click',function(){
            saveline($(this).attr('line'),$(this).attr('claimsline_id'));
        });
        $('.delete_line').on('click',function(){
            deleteline($(this).attr('claimsline_id'));
        });
        $('.calculate').on('keyup',function(){
            calculate();
        });
        
        $('#duplicate_btn').click(function(){
            
            if(confirm("Confirm duplicate this claim?")){
                var data = "action=duplicate&claims_id=<?php echo $this->claims_id;?>";
                $.ajax({ 
                    type: 'POST',
                    url: 'claims.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                          alert(jsonObj.msg);
                          window.location.href = 'claims.php?action=edit&claims_id='+jsonObj.newclaims_id;
                       }else{
                          alert(jsonObj.msg);
                       }
                       issend = false;
                    }		
                 });
                 return false;
            }
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
        
        $("#claims_form").validate({
                  rules: 
                  {
                      claims_title:
                      {
                          required: true
                      }
                  },
                  messages:
                  {
                      claims_title:
                      {
                          required: "Please enter Claims Title."
                      }
                  }
        });
});
    var issend = false;
    function saveline(line,claimsline_id){
        if(issend){
            alert("Please wait...");
            return false;
        }

        issend = true;
        if(claimsline_id != ""){
            var action = 'updateline';
        }else{
            var action = 'saveline';
        }

        var data = "claimsline_date="+$('#claimsline_date_'+line).val();
            data += "&claimsline_type="+$('#claimsline_type_'+line).val();
            data += "&claimsline_desc="+encodeURIComponent($('#claimsline_desc_'+line).val());
            data += "&claimsline_receiptno="+$('#claimsline_receiptno_'+line).val();
            data += "&claimsline_amount="+$('#claimsline_amount_'+line).val();
            data += "&claimsline_gst_reg_no="+$('#claimsline_gst_reg_no_'+line).val();
            data += "&action="+action;
            data += "&claimsline_id="+claimsline_id;
            data += "&claims_id=<?php echo $this->claims_id;?>";

        $.ajax({ 
            type: 'POST',
            url: 'claims.php',
            cache: false,
            data:data,
            error: function(xhr) {
                alert("System Error.");
                issend = false;
            },
            success: function(data) {
               var jsonObj = eval ("(" + data + ")");
               if(jsonObj.status == 1){
                   window.location.reload();
               }else{
                   alert("Add/Update Fail.");
               }
               issend = false;
            }		
         });
         return false;
    }
    function deleteline(claims_id){
        var data = "action=deleteline&claims_id=<?php echo $this->claims_id;?>&claimsline_id="+claims_id;
        $.ajax({ 
            type: 'POST',
            url: 'claims.php',
            cache: false,
            data:data,
            error: function(xhr) {
                alert("System Error.");
                issend = false;
            },
            success: function(data) {
               var jsonObj = eval ("(" + data + ")");
               if(jsonObj.status == 1){
                   window.location.reload();
               }else{
                   alert("Fail to delete line.");
               }
               issend = false;
            }		
         });
         return false;
    }
    function addline(){
        var addlinevalue = $('#total_line').val();
        var nextvalue = parseInt(addlinevalue)+1;
        var newline = line_copy.replace(/@i/g,nextvalue);
        $('#detail_last_tr').before(newline);
        $('#total_line').val(nextvalue);
        $('#claimsline_seqno_'+nextvalue).val(nextvalue*10);
        $(".select2").select2();
        $('.calculate').on('keyup',function(){
            calculate();
        });
        $('.datepicker').datepicker({
            format: 'dd-M-yyyy',
            autoclose: true,
            pickerPosition: "bottom-left"
        });
    }
    function calculate(){
        var total_claims_amount = 0;
        $('.calculate').each(function(){
            var line = $(this).attr('line');
            var line_amount = $(this).val().replace(/,/gi, "");
            if(isNaN(line_amount)){
                line_amount = 0;
            }
            var amount_total = parseFloat($('#claimsline_amount_'+line).val().replace(/,/gi, "")) + parseFloat($('#claimsline_amount_gst_'+line).val().replace(/,/gi, ""));
            $('#claimsline_amount_total_'+line).val(changeNumberFormat(RoundNum(amount_total,2)));
            if($('#claimsline_type_'+line).val() > 0){
                checkClaimsLimit($('#claimsline_type_'+line).val(),line);
                total_claims_amount = parseFloat(total_claims_amount) + parseFloat(line_amount);
            }
        });
        $('#claims_amount').val(changeNumberFormat(RoundNum(total_claims_amount,2)));
    }
    function checkClaimsLimit(claimstype_id,line){

        var data = "action=checkClaimsLimit&claimstype_id=" + claimstype_id;
        $.ajax({ 
            type: 'POST',
            url: 'claimstype.php',
            cache: false,
            data:data,
            error: function(xhr) {
                alert("System Error.");
                issend = false;
            },
            success: function(data) {
               var jsonObj = eval ("(" + data + ")");
               if(jsonObj.status == 1){
                   var line_amount = parseFloat($('#claimsline_amount_'+line).val().replace(/,/gi, "")) + parseFloat($('#claimsline_amount_gst_'+line).val().replace(/,/gi, ""));
                   if(jsonObj.claims_limit > 0){
                       if(parseFloat(line_amount) > parseFloat(jsonObj.claims_limit)){
                           
                           $('#claimsline_amount_'+line).css('border','2px solid red');
                           if(!$('#claimsline_amount_'+line).hasClass('limit_error')){
                            alert('Claims Limit.');
                           }
                           $('#claimsline_amount_'+line).addClass('limit_error');
                           return false;
                       }else{
                           $('#claimsline_amount_'+line).css('border','1px solid #d2d6de');
                       }
                   }
               }

            }		
         });
    }
    function checkconfirm(){
        if($('#claims_form').attr('clickby') == 'Confirm'){
           var msg = " submit this claims to admin?";
        }else{
           var msg = " save as draft?"; 
        }
        if(confirm('confirm' + msg)){
            return true;
        }else{
            return false;
        }
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
    <title>Claims Management</title>
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
            <h1>Claims Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Claims Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='claims.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table id="claims_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:13%'>Employee</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:25%'>Remark</th>
                        <th style = 'width:10%'>Amount</th>
                        <th style = 'width:10%'>Approved / Rejected By</th>
                        <th style = 'width:13%'>Status</th>
                        <th style = 'width:14%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                        if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],"approved")){// HR
                            $wherestring = " AND (l.claims_approvalstatus <> 'Draft' OR l.insertBy = '{$_SESSION['empl_id']}')";
                        }else{
                            $wherestring = "AND l.claims_empl_id = '{$_SESSION['empl_id']}'";
                        }
                      $sql = "SELECT l.*,empl.empl_name,(SELECT COUNT(*) FROM db_payroll WHERE l.claims_date >= payroll_startdate AND l.claims_date <= payroll_enddate) as ispayrolled
                              FROM db_claims l 
                              INNER JOIN db_empl empl ON empl.empl_id = l.claims_empl_id
                             
                              WHERE l.claims_id > 0 AND l.claims_status = 1 $wherestring
                              ORDER BY l.claims_date DESC,l.updateDateTime DESC";
                      
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['empl_name'];?></td>
                            <td><?php echo format_date($row['claims_date']);?></td>
                            <td><?php echo nl2br($row['claims_remark']);?></td>
                            <td><?php echo num_format(getDataCodeBySql("SUM(claimsline_amount+claimsline_amount_gst)","db_claimsline"," WHERE claimsline_claims_id = '{$row['claims_id']}'",""));?></td>
                            <td>
                                <?php 
                                $sql2 = "SELECT * FROM db_leave_approved WHERE type_code = 'claims' AND type_id = '{$row['claims_id']}'";
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
                            <td><?php echo $this->getApprovalStatus($row['claims_approvalstatus']);?></td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'claims.php?action=edit&claims_id=<?php echo $row['claims_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                        if(($row['claims_approvalstatus'] == 'Draft') || ($row['claims_approvalstatus'] == 'Pending')){
                                            
                                ?>
                                        <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('claims.php?action=delete&claims_id=<?php echo $row['claims_id'];?>','Confirm Delete?')">Delete</button>
                                <?php
                                        }else if(($row['claims_approvalstatus'] == 'Approved') && (in_array($_SESSION['empl_group'],$master_group))){
                                ?>
                                        <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('claims.php?action=delete&claims_id=<?php echo $row['claims_id'];?>','Confirm Delete?')">Delete</button>
                                <?php
                                        }
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
                        <th style = 'width:10%'>Amount</th>
                        <th style = 'width:10%'>Approved / Rejected By</th>
                        <th style = 'width:13%'>Status</th>
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
        $('#claims_table').DataTable({
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
            case "On-Hold":

                $return_status =  "<span style = 'color:#99a5a9;font-weight:bold' >$status</span>";
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
    public function getAddItemDetailForm(){
    $line = 0;  
    if($this->claims_approvalstatus <> 'Draft'){
        $disabled = " DISABLED";
    }
    ?>    
    <table id="detail_table" class="table transaction-detail">
        <thead>
          <tr>
            <th class = "" style="width:5%;padding-left:5px">No</th>
            <th class = "" style = 'width:10%;'>Date</th>
            <th class = "" style = 'width:10%;'>Type of Claims</th>
            <th class = "" style = 'width:20%;'>Description</th>
            <th class = "" style = 'width:15%'>Attachment</th>
            <th class = "" style = 'width:10%'>Amt. Before GST</th>
            <th class = "" style = 'width:10%'>GST</th>
            <th class = "" style = 'width:10%'>Subtotal</th>
            <th class = "" style = 'width:10%'>GST Reg No. </th>
            <th class = "" style="width:10%"></th>
          </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT cl.*,img.image_type,img.image as file_name
                    FROM db_claimsline cl
                    LEFT JOIN db_image img ON img.ref_id = cl.claimsline_id
                    WHERE claimsline_id > 0 AND claimsline_claims_id > 0 AND claimsline_claims_id = '$this->claims_id' ORDER BY claimsline_seqno";
            $query = mysql_query($sql);
            while($row = mysql_fetch_array($query)){
                $line++;
                $this->claimstypelineCrtl = $this->select->getClaimsTypeSelectCtrl($row['claimsline_type']);
            ?>
                <tr id = "line_<?php echo $line;?>" class="tbl_grid_odd" line = "<?php echo $line;?>">
                    <td style="width:5%;padding-left:5px"><?php echo $line;?></td>
                    <td style="width:180px;"><input type = "text" name = "claimsline_date[<?php echo $line;?>]" id = "claimsline_date_<?php echo $line;?>" class="form-control datepicker" value="<?php echo format_date($row['claimsline_date']);?>" <?php echo $disabled;?>/></td>
                    <td style="width:80px;"><select style = 'width:100%' name = "claimsline_type[<?php echo $line;?>]" id = "claimsline_type_<?php echo $line;?>" class="form-control select2" <?php echo $disabled;?>><?php echo $this->claimstypelineCrtl;?></select></td>
                    <td style="width:250px;"><textarea name = "claimsline_desc[<?php echo $line;?>]" id = "claimsline_desc_<?php echo $line;?>" class="form-control" <?php echo $disabled;?>><?php echo $row['claimsline_desc'];?></textarea></td>
                    <td style="width:60px;text-align:center;font-weight:700">
                        <input type = "file" name = "claimsline_receiptno[<?php echo $line;?>]" line = "<?php echo $line;?>" id = "claimsline_receiptno_<?php echo $line;?>" class="form-control"  <?php echo $disabled;?>/>
                        <?php if(file_exists("dist/images/claims/{$this->claims_id}_{$row['claimsline_id']}.{$row['image_type']}")){?>
                        <a target = '_blank' href = "dist/images/claims/<?php echo $this->claims_id . "_" . $row['claimsline_id'] . ".{$row['image_type']}" ?>" ><?php echo $row['file_name'];?></a>
                        <?php }?>
                    </td>
                    <td style="width:60px;"><input type = "text" name = "claimsline_amount[<?php echo $line;?>]" line = "<?php echo $line;?>" id = "claimsline_amount_<?php echo $line;?>" class="form-control calculate text-align-right" value = "<?php echo $row['claimsline_amount'];?>" <?php echo $disabled;?>/></td>
                    <td style="width:60px;"><input type = "text" name = "claimsline_amount_gst[<?php echo $line;?>]" line = "<?php echo $line;?>" id = "claimsline_amount_gst_<?php echo $line;?>" class="form-control calculate text-align-right" value = "<?php echo $row['claimsline_amount_gst'];?>" <?php echo $disabled;?>/></td>
                    <td style="width:60px;"><input type = "text" name = "claimsline_amount_gst[<?php echo $line;?>]" line = "<?php echo $line;?>" id = "claimsline_amount_gst_<?php echo $line;?>" class="form-control calculate text-align-right" value = "<?php echo num_format($row['claimsline_amount'] + $row['claimsline_amount_gst']);?>" DISABLED/></td>
                    <td style="width:60px;"><input type = "text" name = "claimsline_gst_reg_no[<?php echo $line;?>]" line = "<?php echo $line;?>" id = "claimsline_gst_reg_no_<?php echo $line;?>" class="form-control text-align-right" value = "<?php echo $row['claimsline_gst_reg_no'];?>" /></td>
                    <td align = "center" style ="vertical-align:top;width:80px;padding-right:10px;padding-left:5px">
                        <?php 
                        if($this->claims_approvalstatus == 'Draft'){
                            if($row['claimsline_id'] > 0){
                        ?>
                            <!--<a style = "margin-left:10px;margin-right:10px;" href = "javascript:void(0)" id = "save_line_<?php echo $line;?>" claimsline_id = "<?php echo $row['claimsline_id'];?>" class = "save_line font-icon" line = "<?php echo $line;?>" ><i class="fa fa-plus" aria-hidden="true"></i></a>-->
                            <?php }else{?>
                            <!--<a style = "margin-left:10px;margin-right:10px;" href = "javascript:void(0)" id = "save_line_<?php echo $line;?>" claimsline_id = "<?php echo $row['claimsline_id'];?>" class = "save_line font-icon" line = "<?php echo $line;?>" ><i class="fa fa-plus" aria-hidden="true"></i></a>-->
                            <?php }?>
                        <a style = "margin-left:10px;margin-right:10px;" href = "javascript:void(0)" id = "delete_line_<?php echo $line;?>" claimsline_id = "<?php echo $row['claimsline_id'];?>" class = "delete_line font-icon" line = "<?php echo $line;?>" ><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                        <?php }?>
                        <input type = "hidden" name = "claimsline_id[<?php echo $line;?>]" id = "claimsline_id<?php echo $line;?>" class="form-control" value="<?php echo $row['claimsline_id'];?>"/>
                    </td>
                </tr>
            
            <?php
            }
            ?>
            <tr id = 'detail_last_tr'></tr>
        </tbody>
    </table>
    <input type = 'hidden' id = 'total_line' name = 'total_line' value = '<?php echo $line;?>'/>
    <?php    
    }
    public function email(){
        include_once 'Empl.php';
        include_once 'Leave.php';
        $e = new Empl();
        $l = new Leave();
        $r = $this->fetchClaimsDetail(" AND claims_id = '$this->claims_id'","","",2);
        if($r){
            $er = $e->fetchEmplDetail(" AND empl_id = '{$r['claims_empl_id']}'","","",2);
            $approvel_level = $e->getApprovelEmail('claims',$r['claims_empl_id']);
            if($approvel_level['total_need_approved'] == 0){//master no need approved level
                $this->updateApproveStatus('Approved');
                return true;
            }
        }
         if($this->claims_approvalstatus == 'Approved'){
             $css = 'color:green';
         }else{
             $css = 'color:red';
         }
         
        switch ($this->claims_approvalstatus) {
            case "Pending":
                $msg = "Your Claims under process.";
                break;
            case "Approved":
            case "Rejected":    
                $msg = "Your Claims have been <span style = '$css' >" . $this->claims_approvalstatus . "</span>.";
                break;
            default:
                break;
        }
    ob_start();
    ?>
    <html>
        <head>
          <title>Notification of Claims Status</title>
        </head>
        <body>
         <p>Greetings <?php echo $er['empl_name']?>,</p>
         <p><?php echo $msg;?></p>
         <br>
         <table width = '100%' style = 'width:100%' rules="all" style="border-color: #666;" cellpadding="10">
              <tr >
                 <td style='background: #eee;width:80px'><strong>Amount</strong></td>
                 <td><?php echo num_format($r['claims_amount']);?></td>
             </tr>
              <tr>
                 <td style='background: #eee;width:80px'><strong>Remarks</strong></td>
                 <td><?php echo nl2br($r['claims_remark']);?></td>
             </tr>
         </table>
         <br>
         <table width = '100%' style = 'width:100%' rules="all" style="border-color: #666;" cellpadding="10">
             <tr style='background: #eee;'>
                 <td><strong>Date</strong></td>
                 <td><strong>Type of Claims</strong></td>
                 <td><strong>Description</strong></td>
                 <td><strong>Amount</strong></td>
             </tr>
             <?php 
                $sql = "SELECT * FROM db_claimsline WHERE claimsline_id > 0 AND claimsline_claims_id > 0 AND claimsline_claims_id = '$this->claims_id' ORDER BY claimsline_seqno";
                $query = mysql_query($sql);
                while($row = mysql_fetch_array($query)){
             ?>
             <tr>
                 <td><?php echo format_date($row['claimsline_date']);?></td>
                 <td><?php echo getDataCodeBySql("claimstype_code","db_claimstype"," WHERE claimstype_id = '{$row['claimsline_type']}'");?></td>
                 <td><?php echo $row['claimsline_desc'];?></td>
                 <td><?php echo $row['claimsline_amount'];?></td>
             </tr>
             <?php
                }
             ?>
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

    $query = getDataBySql('COUNT(*) as total, approved_id','db_leave_approved'," WHERE type_code = 'claims' AND type_id = '{$r['claims_id']}'");
    if($row = mysql_fetch_array($query)){
        $totalapproverecord = $row['total'];
    }

    if($totalapproverecord == 0){
        $l->save = $this->save;
        $l->insertApproveLevel('claims',$r['claims_id'],$approvel_level);
    }
    
    
    
    
//    $my_file = "Staff Claim Form.xlsx";
    $my_path = "";
    $my_name = $_SESSION['empl_name'] . " | Zal Interiors";
    $my_mail = $_SESSION['empl_email'];
    $my_subject = "Claims Request By " . $er['empl_name'];
        

    mail_attachment($my_file,$my_path,$staff_email,$my_mail,$my_name, $my_replyto,$my_subject,$message,$cc_email);
    }
    public function checkAccess($action){
        global $master_group;
        if(($action == 'createForm') || ($action == 'create') || ($action == '')){
            return true;
        }
        if(!in_array($_SESSION['empl_group'],$master_group)){
            $empl_id = getDataCodeBySql("claims_empl_id","db_claims"," WHERE claims_id = '$this->claims_id'","");
            $record_count = getDataCountBySql("db_claims"," WHERE claims_empl_id = '$empl_id'");

            if(((($empl_id <= 0) && ($record_count == 0)) || ($empl_id != $_SESSION['empl_id']))){
                permissionLog();
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
                exit();
            }
        }
    }
    public function doSstatus(){
        include_once 'Leave.php';
        include_once 'Empl.php';
        $e = new Empl();
        $l = new Leave();
        $l->save = $this->save;
        $l->sstatus_message = $this->sstatus_message;
        
        $r = $this->fetchClaimsDetail(" AND claims_id = '$this->claims_id'","","",2);
        if($r){
            $er = $e->fetchEmplDetail(" AND empl_id = '{$r['claims_empl_id']}'","","",2);
            $approvel_level = $e->getApprovelEmail('claims',$r['claims_empl_id']);

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
           
            
            $query = getDataBySql('COUNT(*) as total, approved_id','db_leave_approved'," WHERE type_code = 'claims' AND type_id = '{$r['claims_id']}'");
            if($row = mysql_fetch_array($query)){
                $totalapproverecord = $row['total'];
                $approved_id = $row['approved_id'];
            }
           
            if($l->updateApproveLevel($ap,$approved_id)){
                if($l->checkAllApproved($r['claims_id'],'claims')){
                    $this->sstatus_action = $l->sstatus_action;
                    $this->updateApproveStatus($this->sstatus_action);
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
                $msg = "Your Claims have been <span style = '$css' >" . $this->sstatus_action . "</span>.";
                break;
            default:
                break;
        }
        
    ob_start();
    ?>
    <html>
        <head>
          <title>Notification of Claims Status</title>
        </head>
        <body>
         <p>Greetings <?php echo $er['empl_name']?>,</p>
         <p><?php echo $msg;?></p>
         <br>
         <table width = '100%' style = 'width:100%' rules="all" style="border-color: #666;" cellpadding="10">
              <tr >
                 <td style='background: #eee;width:80px'><strong>Amount</strong></td>
                 <td><?php echo num_format($r['claims_amount']);?></td>
             </tr>
              <tr>
                 <td style='background: #eee;width:80px'><strong>Remarks</strong></td>
                 <td><?php echo nl2br($r['claims_remark']);?></td>
             </tr>
         </table>
         <br>
         <table width = '100%' style = 'width:100%' rules="all" style="border-color: #666;" cellpadding="10">
             <tr style='background: #eee;'>
                 <td><strong>Date</strong></td>
                 <td><strong>Type of Claims</strong></td>
                 <td><strong>Description</strong></td>
                 <td><strong>Amount</strong></td>
             </tr>
             <?php 
                $sql = "SELECT * FROM db_claimsline WHERE claimsline_id > 0 AND claimsline_claims_id > 0 AND claimsline_claims_id = '$this->claims_id' ORDER BY claimsline_seqno";
                $query = mysql_query($sql);
                while($row = mysql_fetch_array($query)){
             ?>
             <tr>
                 <td><?php echo format_date($row['claimsline_date']);?></td>
                 <td><?php echo getDataCodeBySql("claimstype_code","db_claimstype"," WHERE claimstype_id = '{$row['claimsline_type']}'");?></td>
                 <td><?php echo $row['claimsline_desc'];?></td>
                 <td><?php echo $row['claimsline_amount'];?></td>
             </tr>
             <?php
                }
             ?>
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
    $my_subject = "Claims Request By " . $er['empl_name'];
       

    mail_attachment($my_file,$my_path,$staff_email,$my_mail,$my_name, $my_replyto,$my_subject,$message,$cc_email);
    }
    public function duplicateClaim(){
        
        $sql = "INSERT INTO db_claims (claims_title,claims_empl_id,claims_date,claims_remark,claims_amount,claims_approvalstatus,claims_status,insertBy,insertDateTime,updateBy,updateDateTime) 
                SELECT claims_title,claims_empl_id,now(),claims_remark,claims_amount,'Draft','1',insertBy,now(),updateBy,now() FROM db_claims WHERE claims_id = '$this->claims_id'
                ";

        if(mysql_query($sql)){
            $this->newclaims_id = mysql_insert_id();
            
        $sql = "INSERT INTO db_claimsline (claimsline_claims_id,claimsline_date,claimsline_type,claimsline_desc,claimsline_receiptno,claimsline_amount,claimsline_amount_gst,insertBy,insertDateTime,updateBy,updateDateTime) 
                SELECT '$this->newclaims_id',claimsline_date,claimsline_type,claimsline_desc,claimsline_receiptno,claimsline_amount,claimsline_amount_gst,insertBy,now(),updateBy,now() FROM db_claimsline WHERE claimsline_claims_id = '$this->claims_id'
                ";
        mysql_query($sql);
            return true;
        }else{
            return false;
        }
    }
}
?>
