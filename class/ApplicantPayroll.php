<?php
/*
 * To change this tpayrollate, choose Tools | Tpayrollates
 * and open the tpayrollate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class ApplicantPayroll {

    public function ApplicantPayroll(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('payroll_outlet','payroll_client','payroll_department','payroll_salary_date','payroll_startdate',
                             'payroll_enddate','payroll_total_working_days','payroll_status');
        $table_value = array($this->payroll_outlet,$this->payroll_client,$this->payroll_department,format_date_database($this->payroll_salary_date),format_date_database($this->payroll_startdate),
                             format_date_database($this->payroll_enddate),$this->payroll_total_working_days,1);
        $remark = "Insert Payroll.";
        if(!$this->save->SaveData($table_field,$table_value,'db_payroll','payroll_id',$remark)){
           return false;
        }else{
           $this->payroll_id = $this->save->lastInsert_id;
           $this->createPayrollLine();
           return true;
        }
    }
    public function update(){
        $table_field = array('payroll_title','payroll_date','payroll_remark','payroll_status',
                             'payroll_empl_id','payroll_total_working_days','payroll_approvalstatus');
        $table_value = array($this->payroll_title,format_date_database($this->payroll_date),$this->payroll_remark,1,
                             $_SESSION['empl_id'],$this->payroll_total_working_days,$this->payroll_approvalstatus);
        $remark = "Update Apply Payroll.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_payroll','payroll_id',$remark,$this->payroll_id)){
           return false;
        }else{
           return true;
        }
    }
    public function updateApproveStatus(){
        $table_field = array('payroll_approvalstatus');
        $table_value = array($this->payroll_approvalstatus);
        $remark = "Update Approve Status.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_payroll','payroll_id',$remark,$this->payroll_id)){
           return false;
        }else{
           return true;
        }
    }
    public function createPayrollLine(){
        include_once 'class/Applicant.php';
        $a = new Applicant();
        
        $this->return_array = 1;
        $b = $this->previewPayslip();
        
        $this->payroll_startdate = format_date_database($this->payroll_startdate);
        $this->payroll_enddate = format_date_database($this->payroll_enddate);

        foreach($b as $c){

            $table_field = array('payline_payroll_id','payline_empl_id','payline_department_id','payline_salary',
                                 'payline_additional','payline_deductions','payline_cpf_employer','payline_cpf_employee',
                                 'payline_levy_employee','payline_empl_type','payline_netpay');
            $table_value = array($this->payroll_id,$c['empl_id'],$c['department_id'],$c['empl_salary'],
                                 $c['empl_addtional'],$c['empl_deductions_without_leave'],$c['cpf_employer'],$c['cpf_employee'],
                                 $c['Levy_employee'],1,$c['payline_netpay']);
            $remark = "Insert Payroll Lines.";
            if($this->save->SaveData($table_field,$table_value,'db_payline','payline_id',$remark)){
                $this->payline_id = $this->save->lastInsert_id;
                
                $sql = "SELECT * FROM db_additional INNER JOIN db_additionaltype ON additional_type = additionaltype_id WHERE additional_empl_id = '$c[empl_id]' AND additional_date BETWEEN '$this->payroll_startdate' AND '$this->payroll_enddate' AND additional_empl_type = '1' AND additional_status = '1'";
                $query = mysql_query($sql);
                
                $payitem_table_field = array('payitem_payline_id','payitem_type','payitem_remark','payitem_amount');
                $payitem_table_value = array($this->payline_id, 1, 'BASIC PAY', $c['empl_salary']);
                
                $this->save->SaveData($payitem_table_field,$payitem_table_value,'db_payitem','payitem_id',$remark);
                
                
                
                
                $sql2 = "SELECT payline_levy_employee, payline_cpf_employee FROM db_payline WHERE payline_id = '$this->payline_id'";
                $query2 = mysql_query($sql2);
                $row2 = mysql_fetch_array($query2);
                
                $payitemD_table_field = array('payitem_payline_id','payitem_type','payitem_remark','payitem_amount');
                    
                if($c['empl_total_leave'] > 0){
                    $payitemD_table_value = array($this->payline_id, 0, 'UNPAID LEAVE - ' .$c['empl_total_leave']." Day", $row2['payline_levy_employee']);
                    $this->save->SaveData($payitemD_table_field,$payitemD_table_value,'db_payitem','payitem_id',$remark);
                }
                
                $CPFamount = $row2['payline_cpf_employee'];
                if($CPFamount > 0){
                    echo "HERE";
                    $payitemD_table_value = array($this->payline_id, 0, 'EMPLOYEE CPF', $row2['payline_cpf_employee']);
                    $this->save->SaveData($payitemD_table_field,$payitemD_table_value,'db_payitem','payitem_id',$remark);
                }
                
                
                while($row = mysql_fetch_array($query)){
                    $payitem_table_value = array($this->payline_id, 1, strtoupper($row['additionaltype_code']), $row['additional_amount']);
                    $remark = "Insert Pay Item.";
                    $this->save->SaveData($payitem_table_field,$payitem_table_value,'db_payitem','payitem_id',$remark);
                }
                
                $sql = "SELECT * FROM db_deductions WHERE deductions_empl_id = '$c[empl_id]' AND deductions_date BETWEEN '$this->payroll_startdate' AND '$this->payroll_enddate' AND deduction_empl_type = '1' AND deductions_status = '1'";
                $query = mysql_query($sql);
                $payitem_table_field = array('payitem_payline_id','payitem_type','payitem_remark','payitem_amount');
                while($row = mysql_fetch_array($query)){
                    $payitem_table_value = array($this->payline_id, 0, strtoupper($row['deductions_title']), $row['deductions_amount']);
                    $remark = "Insert Pay Item.";
                    $this->save->SaveData($payitem_table_field,$payitem_table_value,'db_payitem','payitem_id',$remark);
                }                
                
                
                

                //$salary_info['day_salary'] = $day_salary;
                //$salary_info['emplsalary_amount'] = $empl_salary_array['emplsalary_amount'];
//                if($c['empl_id'] == '18'){
//                    echo $this->summary["calculateforcdacadd_amt"];die;
//                }
                
                if($c['cpf_employee_percent'] > 0){

                    $table_field = array('payitem_payline_id','payitem_type','payitem_remark','payitem_amount');
                    $table_value = array($this->payline_id,0,strtoupper("Employee CPF (" . $c['cpf_employee_percent'] . "%)"),$c['cpf_employee']);
                    $remark = "Insert Pay Item.";
                    $this->save->SaveData($table_field,$table_value,'db_payitem','payitem_id',$remark);
                }
            }
        }
        
    }
    public function createPayitem($additional_sql,$type,$field){
        $this->summary["calculateforcdacadd_amt"] = 0;
        $query = mysql_query($additional_sql);
        while($row = mysql_fetch_array($query)){
            if($row['type'] == 'leaves'){
                if($row['deductions_amt'] <= 0){
                    continue;
                }
            }
            if(($field == 'claims_amount') && ($row['calculateforcdac'] == 1)){
                $this->summary["calculateforcdacadd_amt"] = $this->summary["calculateforcdacadd_amt"] + ROUND($row[$field],2);
            }
            
            $table_field = array('payitem_payline_id','payitem_type','payitem_remark','payitem_amount');
            $table_value = array($this->payline_id,$type,strtoupper($row['title']),$row[$field]);
            $remark = "Insert Pay Item.";
            $this->save->SaveData($table_field,$table_value,'db_payitem','payitem_id',$remark);
        }
    }
    public function fetchPayrollDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_payroll WHERE payroll_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->payroll_id = $row['payroll_id'];
            $this->payroll_outlet = $row['payroll_outlet'];
            $this->payroll_client = $row['payroll_client'];
            $this->payroll_department = $row['payroll_department'];
            $this->payroll_salary_date = $row['payroll_salary_date'];
            $this->payroll_startdate = $row['payroll_startdate'];
            $this->payroll_enddate = $row['payroll_enddate'];
            $this->payroll_status = $row['payroll_status'];
            $this->payroll_total_working_days = $row['payroll_total_working_days'];
        }

        if(mysql_num_rows($query) == 0 ){
            return false;
        }else{
            return $query;
        }
    }
    public function fetchPayrollLineDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_payline WHERE payline_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);

            $this->payline_cpf_employer = $row['payline_cpf_employer'];
            $this->payline_cpf_employee = $row['payline_cpf_employee'];

        }

        if(mysql_num_rows($query) == 0 ){
            return false;
        }else{
            return $query;
        }
    }
    public function delete(){
        $table_field = array('payroll_status');
        $table_value = array(0);
        $remark = "Delete Payroll.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_payroll','payroll_id',$remark,$this->payroll_id)){
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
            $this->payroll_seqno = 10;
            $this->payroll_status = 1;
            $this->payroll_startdate = system_date_monthstart;
            $this->payroll_enddate = system_date_monthend;
            $this->payroll_salary_date = system_date;
            $this->payroll_total_day = 1;
            $this->payroll_approvalstatus = 'Draft';
            $this->payroll_duration = "full_day";
            $empl_data = $e->fetchEmplDetail(" AND empl_id = '{$_SESSION['empl_id']}'","","",2);
            $empl_code = $_SESSION['empl_code'];
            $empl_name = $_SESSION['empl_name'];
            $this->payroll_total_working_days = 20;
        }else{
            $empl_data = $e->fetchEmplDetail(" AND empl_id = '$this->payroll_empl_id'","","",2);
            $empl_code = $empl_data['empl_code'];
            $empl_name = $empl_data['empl_name'];
        }
        

    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Payroll Management</title>
    <?php
    include_once 'css.php';
    $this->outletCrtl = $this->select->getOutletSelectCtrl($this->payroll_outlet);
    $this->departmentCrtl = $this->select->getDepartmentSelectCtrl($this->payroll_department);
    $this->clientCrtl = $this->select->getClientSelectCtrl($this->payroll_client);
    if($this->payroll_id > 0){
        $disabled = " DISABLED";
    }
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
            <h1>Payroll Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->payroll_id > 0){ echo "Update Payroll";}
                else{ echo "Apply New Payroll";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='applicantpayroll.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='applicantpayroll.php?action=createForm'">Create New</button>
                <?php }?>
              </div>

                <form id = 'payroll_form' class="form-horizontal" action = 'applicantpayroll.php?action=create' method = "POST">
                  <div class="box-body">
                    <div class="col-sm-12">  
                    <div class="form-group">
                      <label for ="payroll_outlet" class="col-sm-2 control-label">Outlet</label>
                      <div class="col-sm-2">
                        <select class="form-control select2" id="payroll_outlet" name="payroll_outlet" <?php echo $disabled;?>>
                          <?php echo $this->outletCrtl;?>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">  
                      <label for ="payroll_client" class="col-sm-2 control-label">Client <?php echo $mandatory;?></label>
                      <div class="col-sm-2">
                        <select class="form-control select2" id="payroll_client" name="payroll_client" <?php echo $disabled;?>>
                          <?php echo $this->clientCrtl;?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">  
                      <label for ="payroll_department" class="col-sm-2 control-label">Department</label>
                      <div class="col-sm-2">
                        <select class="form-control select2" id="payroll_department" name="payroll_department" <?php echo $disabled;?>>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="payroll_salary_date" class="col-sm-2 control-label">Salary Date <?php echo $mandatory;?></label>
                      <div class="col-sm-2">
                        <input type="text" class="form-control datepicker" id="payroll_salary_date" name="payroll_salary_date" value = "<?php echo format_date($this->payroll_salary_date);?>" placeholder="Payroll Salary Date" <?php echo $disabled;?>>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="payroll_total_working_days" class="col-sm-2 control-label">Total Working Days <?php echo $mandatory;?></label>
                      <div class="col-sm-2">
                        <input type="text" class="form-control" id="payroll_total_working_days" name="payroll_total_working_days" value = "<?php echo $this->payroll_total_working_days;?>" placeholder="Total Working Days" <?php echo $disabled;?>>
                      </div>
                    </div>
                     <div class="form-group">
                         <label for="payroll_startdate" class="col-sm-2 control-label">Payslip Period (Start Date)</label>
                      <div class="col-sm-2">
                          <input type="text" class="form-control datepicker" id="payroll_startdate" name="payroll_startdate" value = "<?php echo format_date($this->payroll_startdate);?>" placeholder="Payslip Period (Start Date)" <?php echo $disabled;?>>
                      </div>
                     </div> 
                     <div class="form-group">
                         <label for="payroll_enddate" class="col-sm-2 control-label">Payslip Period (End Date)</label>
                      <div class="col-sm-2">
                          <input type="text" class="form-control datepicker" id="payroll_enddate" name="payroll_enddate" value = "<?php echo format_date($this->payroll_enddate);?>" placeholder="Payslip Period (End Date)" <?php echo $disabled;?>>
                      </div>
                     </div>  
                     <div class="form-group">
                      <div class="col-sm-2">
                          
                      </div>
                      <div class="col-sm-2">
                          <?php if($this->payroll_id <=0){?>
                          <button type = 'button' class = 'btn btn-info preview_payslips' style = 'margin-top:15px;' >Preview Payslips</button>
                          <?php }?>
                      </div>
                     </div>
                    </div>
                      <div style = 'clear:both'></div>  
                        
                        <?php echo $this->getAddItemDetailForm();?>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Back</button>
                    <?php if($this->payroll_id) {?>
                    <a href="invoice.php?action=createForm&client_name=<?php echo $this->payroll_client;?>&payroll_date=<?php echo $this->payroll_salary_date;?>">
                        <button type='button' style='background-color: #7da1a7; border-color: #6b7e96; margin-left: 15px;' class='btn btn-primary btn-warning'>Create Invoice</button>
                    </a>
                    <?php } ?>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->payroll_id;?>" name = "payroll_id"/>
                    <?php 
                    if($this->payroll_id > 0){
                        $prm_code = "update";
                    }
                    else{
                        $prm_code = "create";
                    }
                         if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                             if($this->payroll_id <=0){
                    ?>
                            <button type = "submit" name = 'submit_btn' value = 'Save' class="btn btn-info">Save</button>
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
                     <?php if(file_exists("dist/images/empl/{$this->payroll_empl_id}.png")){?>
                     <img src="dist/images/empl/<?php echo $this->payroll_empl_id;?>.png" class="img-circle" alt="User Image"  >
                  <?php }else{?>
                     <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="img-circle">
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
                    <td>Outlet</td>
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
        <form action = 'applicantpayroll.php' method = "POST" id = 'varify_form' >
            <!-- Modal content-->
            <div class="modal-content ">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title sstatus_title"></h4>
              </div>
              <div class="modal-body">
                  Your Password <?php echo $mandatory;?>  :  <input type = "password" name = "varify_password" id = 'password' />
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <input type = 'hidden' name = 'action' id = 'varify_action' value = '' />
                  <input type = 'hidden' name = 'empl_id' id = 'empl_id' value = '' />
                  <input type = 'hidden' name = 'payroll_id' id = 'payroll_id' value = '' />
                  <input type = 'hidden' name = 'payroll_startdate' id = 'varify_payroll_startdate' value = '' />
                  <input type = 'hidden' name = 'payroll_enddate' id = 'varify_payroll_enddate' value = '' />
                  <input type = 'hidden' name = 'payroll_total_working_days' id = 'varify_payroll_total_working_days' value = '' />
              </div>
            </div>
        </form>
    </div>
  </div>
    
    <script>
    var line_copy = '<tr id = "line_@i" class="tbl_grid_odd" line = "@i">' +
                    '<td style = "width:5%;padding-left:5px">@i</td>' + 
                    '<td style = "width:10%;"><input type = "text" name = "payrollline_seqno[@i]" id = "payrollline_seqno_@i" class="form-control" value="@i"/></td>'+
                    '<td style = "width:10%;"><input type = "text" name = "payrollline_date[@i]" id = "payrollline_date_@i" class="form-control datepicker" value=""/></td>'+
                    '<td style = "width:10%;"><select name = "payrollline_type[@i]" id = "payrollline_type_@i" class="form-control select2 "><?php echo $this->payrolltypeCrtl;?></select></td>'+
                    '<td class = "width:30%;"><textarea name = "payrollline_desc[@i]" id = "payrollline_desc_@i" class="form-control"></textarea></td>'+
                    '<td style = "width:15%;"><input type = "text" name = "payrollline_receiptno[@i]" id = "payrollline_receiptno_@i" class="form-control"/></td>'+
                    '<td style = "width:10%;"><input type = "text" name = "payrollline_amount[@i]" id = "payrollline_amount_@i" line = "@i" class="form-control calculate text-align-right" /></td>'+


                    '<td align = "center" class = "" style ="vertical-align:top;min-width:10%;padding-right:10px;padding-left:5px">' +
                    //'<a style = "margin-left:10px;margin-right:10px;" href = "#" id = "save_line_@i" payrollline_id = "" class = "save_line font-icon" line = "@i" ><i class="fa fa-plus" aria-hidden="true"></i></a>' + 
                    //'<a style = "margin-left:10px;margin-right:10px;" href = "#" id = "delete_line_@i" payrollline_id = "" class = "delete_line font-icon" line = "@i" ><i class="fa fa-trash-o" aria-hidden="true"></i></a>' + 
                    '</td>'+
                    '</tr>';
    $(document).ready(function() {
        $('.preview_payslips').on('click',function(){
            getPayslipsListing();
        });
        $("#payroll_form").validate({
                  rules: 
                  {
                      payroll_client:
                      {
                          required: true
                      },
                      payroll_salary_date:
                      {
                          required: true
                      },
                      payroll_total_working_days:
                      {
                          required: true
                      }
                  }
        });
        $('.astatus').click(function(){

                $('#payroll_id').val($(this).attr('payroll_id'));
                $('#empl_id').val($(this).attr('empl_id'));
                $('#varify_action').val('varify_password');
                $('#varify_payroll_total_working_days').val($('#payroll_total_working_days').val());
        });
        
        $('#payroll_client').change(function(){
            var value = $(this);
            var data = "action=getDepartment&client_id="+value.val();
            $.ajax({ 
                type: 'POST',
                url: 'applicantpayroll.php',
                cache: false,
                data:data,
                error: function(xhr) {
                    alert("System Error.");
                    issend = false;
                },
                success: function(data) {
                    var jsonObj = eval ("(" + data + ")");
                    if(jsonObj['department'] != null){                 
                       $('#payroll_department').empty();
                       for(var i=0;i<jsonObj['department'].length; i++){
                            $('#payroll_department').append(jsonObj['department'][i]);
                       }
                    }
                    else {
                        $('#payroll_department').empty();
                    }
                   issend = false;
                }		
             });
             return false;
        });
        
        $('#payroll_department').change(function(){
            var value = $(this);
            var data = "action=getTimeShiftDetail&timeshift_id="+value.val();
            $.ajax({ 
                type: 'POST',
                url: 'applicantpayroll.php',
                cache: false,
                data:data,
                error: function(xhr) {
                    alert("System Error.");
                    issend = false;
                },
                success: function(data) {
                    var jsonObj = eval ("(" + data + ")");
                    if(jsonObj['timeshift'] != null){

                       document.getElementById("payroll_total_working_days").value = jsonObj['timeshift']['timeshift_work_day'];
                       }
                    else {

                    }
                   issend = false;
                }		
             });
             return false;
        });        
        
        
        
    });
    var issend = false;
    
    function getPayslipsListing(){
        if(issend){
            alert("Please wait...");
            return false;
        }

        issend = true;


        var data = "payroll_client="+$('#payroll_client').val();
            data += "payroll_outlet="+$('#payroll_outlet').val();
            data += "&payroll_department="+$('#payroll_department').val();
            data += "&payroll_salary_date="+$('#payroll_salary_date').val();
            data += "&payroll_startdate="+$('#payroll_startdate').val();
            data += "&payroll_enddate="+$('#payroll_enddate').val();
            data += "&payroll_total_working_days="+$('#payroll_total_working_days').val();
            data += "&action=previewPayslip";

        $.ajax({ 
            type: 'POST',
            url: 'applicantpayroll.php',
            cache: false,
            data:data,
            error: function(xhr) {
                alert("System Error.");
                issend = false;
            },
            success: function(data) {
                issend = false;
               var jsonObj = eval ("(" + data + ")");
               var row = "";
               var b = 1;
               $('.payslipslisting').remove();
               var grand_amt = 0;
               if(jsonObj){
               for(var i = 0;i<jsonObj.length;i++){
                   row = row + "<tr class = 'payslipslisting'>"
                         + "<td>" + b + "</td>"
                         + "<td>" + jsonObj[i]['empl_name'] + "</td>"
                         + "<td>" + jsonObj[i]['department_code'] + "</td>"
                         + "<td style = 'text-align:right'>$ " + changeNumberFormat(RoundNum(jsonObj[i]['empl_salary'],2)) + "</td>"
                         + "<td style = 'text-align:right'>$ " + changeNumberFormat(RoundNum(jsonObj[i]['empl_addtional'],2)) + "</td>"
                         + "<td style = 'text-align:right'>$ " + changeNumberFormat(RoundNum(jsonObj[i]['empl_deductions'],2)) + "</td>"
                         + "<td style = 'text-align:right'>$ " + changeNumberFormat(RoundNum(jsonObj[i]['cpf_employee'],2)) + "</td>"
                         + "<td style = 'text-align:right'>$ " + changeNumberFormat(RoundNum(((parseFloat(jsonObj[i]['empl_addtional']) + parseFloat(jsonObj[i]['empl_salary'])) - (parseFloat(jsonObj[i]['empl_deductions']) + parseFloat(jsonObj[i]['cpf_employee']))),2)) + "</td>"
                         + "<td style = 'text-align:right'><a href = '#' class='btn btn-info astatus' payroll_id = '' empl_id = '" + jsonObj[i]['empl_id'] + "' data-toggle='modal' data-target='#sstatusModal' target = '_blank'> View </a></td>"
                         + "</tr>";
                         grand_amt = parseFloat(grand_amt) + ((parseFloat(jsonObj[i]['empl_addtional']) + parseFloat(jsonObj[i]['empl_salary'])) - (parseFloat(jsonObj[i]['empl_deductions']) + parseFloat(jsonObj[i]['cpf_employee'])));
                         
                 b++;
               }
               row = row + "<tr class = 'payslipslisting'><td colspan = '7' style = 'text-align:right;font-weight:bold;font-size:16px;'> Total : </td><td style = 'text-align:right;font-weight:bold;font-size:16px;' >$ " + changeNumberFormat(RoundNum(grand_amt,2)) + "</td><td></td></tr>";
               }
               else{
                   row = row + "<tr class = 'payslipslisting'><td colspan = '7' style = 'text-align:center;font-weight:bold;font-size:16px;'> No Record Found.</td></tr>";
               }
               $('#detail_last_tr').before(row);
               
                $('.astatus').click(function(){

                        $('#payroll_id').val($(this).attr('payroll_id'));
                        $('#empl_id').val($(this).attr('empl_id'));
                        $('#varify_action').val('varify_password_bypass');
                        $('#varify_form').attr('target','_blank');
                        $('#varify_payroll_startdate').val($('#payroll_startdate').val());
                        $('#varify_payroll_enddate').val($('#payroll_enddate').val());
                        $('#varify_payroll_total_working_days').val($('#payroll_total_working_days').val());
                });
            }		
         });
         return false;
    }
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
    <title>Candidates Payroll Management</title>
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
            <h1>Candidates Payroll Management</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="applicantpayroll.php">Payroll Month Listing</a></li>
            <li class="active">Candidates Payroll Listing</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    
                  <h3 class="box-title">Candidates Payroll Table</h3>
                <?php // if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                 
                <button class="btn btn-primary pull-right" onclick = "window.location.href='applicantpayroll.php?action=createForm'">Create New <i class="fa fa-fw fa-plus"></i></button>
               
                <button class="btn btn-primary pull-right btn-success" style = 'margin-right:5px;' id = "confirm_btn">Confirm <i class="fa fa-fw fa-thumbs-up"></i></button> 

                 <button class="btn btn-primary pull-right btn-warning" style = 'margin-right:5px;' id = "print_btn">Print <i class="fa fa-fw fa-print"></i></button> 
                <?php // }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="payroll_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:2%'><input type = "checkbox" name = 'payroll_checkbox' class = 'payroll_checkbox_parent' /></th>  
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:13%'>Company</th>
                        <th style = 'width:10%'>Department</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:10%'>Date From</th>
                        <th style = 'width:10%'>Date To</th>
                        <th style = 'width:10%'>Working Days</th>
                        <th style = 'width:8%'>Confirmed</th>
                        <th style = 'width:12%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php    
                    
                        if(!in_array($_SESSION['empl_group'],$master_group)){
                            $wherestring = "";
                        }else{
                            $wherestring = "AND l.payroll_empl_id = '{$_SESSION['empl_id']}'";
                        }
                        if($this->filter_month_date != ""){
                            $wherestring = " AND LEFT(l.payroll_salary_date,7) = '$this->filter_month_date'";
                        }
                      $sql = "SELECT l.*
                              FROM db_payroll l 
                             
                              WHERE l.payroll_id > 0 AND l.payroll_status = '1' AND l.payroll_client > 0 $wherestring
                              ORDER BY l.payroll_startdate DESC";

                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><input type = "checkbox" name = 'payroll_checkbox' class = 'payroll_checkbox_child' value = '<?php echo $row['payroll_id'];?>'/></td>
                            <td><?php echo $i;?></td>
                            <td><?php
                                    $sql2 = "SELECT partner_name FROM db_partner WHERE partner_id = '$row[payroll_client]'";
                                    $query2 = mysql_query($sql2);
                                    $row2 = mysql_fetch_array($query2);
                                    echo $row2['partner_name'];
                                    ?></td>
                            <td><?php 
                                    $sql3 = "SELECT timeshift_department FROM db_timeshift WHERE timeshift_id = '$row[payroll_department]'";
                                    $query3 = mysql_query($sql3);
                                    $row3 = mysql_fetch_array($query3);
                                    if($row['payroll_department'] == '0'){
                                        echo "All Department";
                                    }
                                    else{
                                        echo $row3['timeshift_department'];
                                    }
                            ?>
                            </td>
                            <td><?php echo format_date($row['payroll_salary_date']);?></td>
                            <td><?php echo format_date($row['payroll_startdate']);?></td>
                            <td><?php echo format_date($row['payroll_enddate']);?></td>
                            <td><?php echo $row['payroll_total_working_days'];?></td>
                            <td><?php if($row['payroll_isapproved'] == 1){ echo "<b><font color = 'green' >YES</font></b>";}else{ echo "<b><font color = 'red'>NO</font></b>";}?></td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicantpayroll.php?action=edit&payroll_id=<?php echo $row['payroll_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){

                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('applicantpayroll.php?action=delete&payroll_id=<?php echo $row['payroll_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:2%'></th>  
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:13%'>Company</th>
                        <th style = 'width:10%'>Department</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:10%'>Date From</th>
                        <th style = 'width:10%'>Date To</th>
                        <th style = 'width:10%'>Working Days</th>
                        <th style = 'width:8%'>Confirmed</th>
                        <th style = 'width:12%'></th>
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
        $('#payroll_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
        
        $('.payroll_checkbox_parent').click(function(){
                if($(this).is(':checked')){
                    $('.payroll_checkbox_child').prop('checked',true);
                }else{
                    $('.payroll_checkbox_child').prop('checked',false);
                }

        });
        
        $('#confirm_btn').click(function(){
            var payroll_id = [];
            
            $('.payroll_checkbox_child').each(function(){
                if($(this).is(':checked')){
                    payroll_id.push($(this).val());
                }
            });
            var data = "action=confirmedPayroll&payroll_array="+payroll_id;
            $.ajax({ 
                type: 'POST',
                url: 'applicantpayroll.php',
                cache: false,
                data:data,
                error: function(xhr) {
                    alert("System Error.");
                    issend = false;
                },
                success: function(data) {
                   issend = false;
                   var jsonObj = eval ("(" + data + ")");
                   alert('Update success.');
                   location.reload();
                }		
             });
        });

        $('#print_btn').click(function(){
            var payroll_id = [];
            
            $('.payroll_checkbox_child').each(function(){
                if($(this).is(':checked')){
                    payroll_id.push($(this).val());
                }
            });
            window.open("payslip-print.php?action=adminprint&type=1&payroll_id="+payroll_id, "_blank");


        });
      });
    </script>
  </body>
</html>
    <?php
    }
    public function getListingMonth(){
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Candidates Payroll Management</title>
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
            <h1>Candidates Payroll Management</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Candidates Payroll Month Listing</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Candidates Payroll Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                 
                <button class="btn btn-primary pull-right" onclick = "window.location.href='applicantpayroll.php?action=createForm'">Create New <i class="fa fa-fw fa-plus"></i></button>
               
                <button class="btn btn-primary pull-right btn-success" style = 'margin-right:5px;' id = "confirm_btn">Confirm <i class="fa fa-fw fa-thumbs-up"></i></button> 
                <?php  }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="payroll_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
<!--                        <th style = 'width:2%'><input type = "checkbox" name = 'payroll_checkbox' class = 'payroll_checkbox_parent' /></th>  -->
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:10%'>Month</th>
                        <th style = 'width:10%'>Total</th>
                        <th style = 'width:12%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php    
                    
                        if(!in_array($_SESSION['empl_group'],$master_group)){
                            $wherestring = "";
                        }else{
                            $wherestring = "AND l.payroll_empl_id = '{$_SESSION['empl_id']}'";
                        }
                        if($_SESSION['empl_group'] == "5"){
                            $sql = "SELECT l.*,LEFT(payroll_salary_date,7) as month FROM db_payroll l INNER JOIN db_payline pl ON l.payroll_id = pl.payline_payroll_id WHERE l.payroll_id > 0 AND l.payroll_status = '1' AND l.payroll_client > '0' AND pl.payline_empl_id = '$_SESSION[empl_id]' GROUP BY YEAR(payroll_salary_date), MONTH(payroll_salary_date) ORDER BY l.payroll_startdate DESC";
                        }
                        else{
                          $sql = "SELECT l.*,LEFT(payroll_salary_date,7) as month

                                  FROM db_payroll l 
                                  WHERE l.payroll_id > 0 AND l.payroll_status = '1' AND l.payroll_client > '0' $wherestring
                                  GROUP BY YEAR(payroll_salary_date), MONTH(payroll_salary_date)
                                  ORDER BY l.payroll_startdate DESC";
                        }
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <!--<td><input type = "checkbox" name = 'payroll_checkbox' class = 'payroll_checkbox_child' value = '<?php echo $row['payroll_id'];?>'/></td>-->
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['month'];?></td>
                            <td>
                            <?php
                            if($_SESSION['empl_group'] == "5"){
                            $sql2 = "SELECT SUM(pl.payline_netpay) as total
                              FROM db_payline pl
                              INNER JOIN db_payroll pyl ON pyl.payroll_id = pl.payline_payroll_id
                              WHERE pyl.payroll_status = '1' AND LEFT(pyl.payroll_salary_date,7) = '{$row['month']}' AND pl.payline_empl_id = '$_SESSION[empl_id]'";
                            }
                            else{
                            $sql2 = "SELECT SUM(pl.payline_netpay) as total
                              FROM db_payline pl
                              INNER JOIN db_payroll pyl ON pyl.payroll_id = pl.payline_payroll_id
                              WHERE pyl.payroll_status = '1' AND LEFT(pyl.payroll_salary_date,7) = '{$row['month']}' AND pyl.payroll_client > 0";
                            }
                              $query2 = mysql_query($sql2);
                            
                             if($row2 = mysql_fetch_array($query2)){
                                 
                                    echo "$ ".num_format($row2['total']);

                             }
                             else{
                                 echo "$ ".num_format(0);
                             }
                            
                            ?>
                            </td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicantpayroll.php?action=listing&filter_month_date=<?php echo $row['month'];?>'">View</button>
                                <?php }
                                if($_SESSION['empl_group'] == "5"){ ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicantpayroll.php?action=previewPayslipDetail&empl_id=<?php echo $_SESSION['empl_id'];?>&payroll_id=<?php echo $row['payroll_id']; ?>&salary_date=<?php echo $row['payroll_salary_date']?>'">View</button>
                                <?php }
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
                        <!--<th style = 'width:2%'><input type = "checkbox" name = 'payroll_checkbox' class = 'payroll_checkbox_parent' /></th>-->  
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:10%'>Month</th>
                        <th style = 'width:10%'>Total</th>
                        <th style = 'width:12%'></th>
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
        $('#payroll_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
        
        $('.payroll_checkbox_parent').click(function(){
                if($(this).is(':checked')){
                    $('.payroll_checkbox_child').prop('checked',true);
                }else{
                    $('.payroll_checkbox_child').prop('checked',false);
                }

        });
        
        $('#confirm_btn').click(function(){
            var payroll_id = [];
            
            $('.payroll_checkbox_child').each(function(){
                if($(this).is(':checked')){
                    payroll_id.push($(this).val());
                }
            });
            var data = "action=confirmedPayroll&payroll_array="+payroll_id;
            $.ajax({ 
                type: 'POST',
                url: 'applicantpayroll.php',
                cache: false,
                data:data,
                error: function(xhr) {
                    alert("System Error.");
                    issend = false;
                },
                success: function(data) {
                   issend = false;
                   var jsonObj = eval ("(" + data + ")");
                   alert('Update success.');
                   location.reload();
                }		
             });
        });
      });
    </script>
  </body>
</html>
    <?php
    }
    public function getStaffViewListing(){
        global $mandatory;
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Payroll Management</title>
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
            <h1>Payroll Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Payroll Table</h3>
                <div class="box-body">
                  <table id="payroll_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:10%'>Date From</th>
                        <th style = 'width:10%'>Date To</th>
                        <th style = 'width:14%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php    
                    
                      $wherestring = "AND pl.payline_empl_id = '{$_SESSION['empl_id']}'";
                       
                      $sql = "SELECT pl.*,py.payroll_salary_date,py.payroll_startdate,py.payroll_enddate,py.payroll_id
                              FROM db_payline pl 
                              INNER JOIN db_payroll py ON py.payroll_id = pl.payline_payroll_id    
                              WHERE pl.payline_empl_id > 0 AND py.payroll_status = '1' AND py.payroll_isapproved = '1' $wherestring
                              ORDER BY py.payroll_startdate DESC";

                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo format_date($row['payroll_salary_date']);?></td>
                            <td><?php echo format_date($row['payroll_startdate']);?></td>
                            <td><?php echo format_date($row['payroll_enddate']);?></td>
                            <td class = "text-align-right">
                               <!--<a class="btn btn-primary btn-info astatus" data-toggle="modal" data-target="#sstatusModal" payroll_id = '<?php echo $row['payroll_id'];?>' empl_id = '<?php echo $_SESSION['empl_id'];?>' href = "#">View</button>-->
                         <a href = 'applicantpayroll.php?action=previewPayslipDetail&empl_id=<?php echo $row['payline_empl_id'];?>&payroll_id=<?php echo $row['payroll_id'];?>'   
                            class='btn btn-info ' target = '_blank'> View </a>
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
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:10%'>Date From</th>
                        <th style = 'width:10%'>Date To</th>
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
    
 <div class="modal fade " id="sstatusModal" role="dialog">
    <div class="modal-dialog ">
        <form action = 'applicantpayroll.php' method = "POST">
            <!-- Modal content-->
            <div class="modal-content ">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title sstatus_title"></h4>
              </div>
              <div class="modal-body">
                  Your Password <?php echo $mandatory;?>  :  <input type = "password" name = "varify_password" id = 'password' />
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <input type = 'hidden' name = 'action' id = 'varify_action' value = '' />
                  <input type = 'hidden' name = 'empl_id' id = 'empl_id' value = '' />
                  <input type = 'hidden' name = 'payroll_id' id = 'payroll_id' value = '' />
              </div>
            </div>
        </form>
    </div>
  </div>
    
    <script>
      $(function () {
        $('#payroll_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });


        $('.astatus').click(function(){
                $('#payroll_id').val($(this).attr('payroll_id'));
                $('#empl_id').val($(this).attr('empl_id'));
                $('#varify_action').val('varify_password');
        });
        
      });
    </script>
  </body>
</html>
    <?php
    }
    public function getAddItemDetailForm(){
    $line = 0;  
    if($this->payroll_approvalstatus <> 'Draft'){
        $disabled = " DISABLED";
    }
    ?>    
    <table id="detail_table" class="table transaction-detail">
        <thead>
          <tr>
            <th class = "" style="width:5%;padding-left:5px">No</th>
            <th class = "" style = 'width:10%;'>Employee Name</th>
            <th class = "" style = 'width:10%;'>Department</th>
            <th class = "" style = 'width:10%;text-align:right'>Salary</th>
            <th class = "" style = 'width:15%;text-align:right'>Additional</th>
            <th class = "" style = 'width:15%;text-align:right'>Deductions</th>
            <th class = "" style = 'width:15%;text-align:right'>Employee CPF</th>
            <th class = "" style = 'width:10%;text-align:right'>Total</th>
            <th class = "" style="width:10%;text-align:right"></th>
          </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT pl.*,appl.applicant_name,ts.timeshift_department,p.payroll_startdate,p.payroll_enddate
                   FROM db_payline pl
                   INNER JOIN db_payroll p ON p.payroll_id = pl.payline_payroll_id
                   LEFT JOIN db_applicant appl ON appl.applicant_id = pl.payline_empl_id
                   LEFT JOIN db_timeshift ts ON ts.timeshift_id =pl.payline_department_id
                   WHERE payline_id > 0 AND payline_payroll_id = '$this->payroll_id'";

            $query = mysql_query($sql);
            $i=0;
            while($row = mysql_fetch_array($query)){
                $i++;
                $total = ($row['payline_salary'] + $row['payline_additional']) - ($row['payline_deductions'] + $row['payline_cpf_employee']) - $row['payline_levy_employee'];
            ?>
                <tr id = "line_<?php echo $line;?>" class="tbl_grid_odd" line = "<?php echo $line;?>">
                    <td style="width:5%;padding-left:5px"><?php echo $i;?></td>
                    <td style="padding-left:5px"><?php echo $row['applicant_name'];?></td>
                    <td style="padding-left:5px"><?php echo $row['timeshift_department'];?></td>
                    <td style="padding-left:5px" align = 'right' ><?php echo "$ ".num_format($row['payline_salary']);?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo "$ ".num_format($row['payline_additional']);?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo "$ ".number_format((float)$row['payline_deductions'] + $row['payline_levy_employee'],2,'.','');?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo "$ ".num_format($row['payline_cpf_employee']);?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo "$ ".num_format($total);?></td>
                    <td style="padding-left:5px" align = 'right'>
<!--                        <a href = '#' class='btn btn-info astatus' data-toggle="modal" data-target="#sstatusModal" payroll_id = '<?php echo $this->payroll_id;?>' 
                           empl_id = '<?php echo $row['payline_empl_id'];?>' target = '_blank'> View </a>-->
                         <a href = 'applicantpayroll.php?action=previewPayslipDetail&empl_id=<?php echo $row['payline_empl_id'];?>&payroll_id=<?php echo $this->payroll_id;?>&salary_date=<?php echo $this->payroll_salary_date;?>'   
                            class='btn btn-info ' target = '_blank'> View </a>
                    </td>
                    </td>
                </tr>
            <?php
            $total_invoice = $total_invoice + $total;
            }
            ?>
                <tr class = 'payslipslisting'><td colspan = '7' style = 'text-align:right;font-weight:bold;font-size:16px;'> Total : </td><td style = 'text-align:right;font-weight:bold;font-size:16px;' ><?php echo "$ ". number_format($total_invoice,2); ?></td><td></td></tr>
            <tr id = 'detail_last_tr'></tr>
        </tbody>
    </table>
    <?php    
    }
    public function previewPayslip(){
//        include_once 'class/Empl.php';
//        include_once 'class/Cpf.php';
//
//        $e = new Empl();
//        $c = new Cpf();

        //create filte
        $wherestring = "";
        $payroll_client = escape($_REQUEST['payroll_client']);
        $this->payroll_department = escape($_REQUEST['payroll_department']);

        if($this->payroll_department > 0){
            $wherestring = " AND f.fol_department = '$this->payroll_department'"; 
        }
        //convert date to sql
        $this->payroll_startdate = format_date_database($this->payroll_startdate);
        $this->payroll_enddate = format_date_database($this->payroll_enddate);
        
        $startDate = substr($this->payroll_startdate,0,7);

        $totalworkingdate = escape($_REQUEST['payroll_total_working_days']);
        
        //subsql for get employee salary at between payroll start date & end date, and get latest salary data.
        $sql = "SELECT * FROM db_applicant a inner join db_followup f ON a.applicant_id = f.applfollow_id inner join db_partner p on f.interview_company = p.partner_id WHERE p.partner_id = '$payroll_client' AND f.fol_status = '0' AND f.fol_approved = 'Y' AND LEFT(f.fol_assign_expiry_date,7) >= '$startDate' $wherestring";
 
        
        $query = mysql_query($sql);
        $i = 0;    
        while($row = mysql_fetch_array($query)){
            $b[$i]['empl_name'] = $row['applicant_name'];
            $b[$i]['empl_id'] = $row['applicant_id'];
            
            $sql2 = "SELECT timeshift_department FROM db_timeshift WHERE timeshift_id = '$row[fol_department]'";
            $query2 = mysql_query($sql2);
            $row2 = mysql_fetch_array($query2);
            
            $b[$i]['department_code'] = $row2['timeshift_department'];
            $b[$i]['department_id'] = $row['fol_department'];
            
            $b[$i]['empl_salary'] = $row['fol_offer_salary'];
            
            $additional_array = $this->getAddtionalSalary($row['applicant_id'],$this->payroll_startdate,$this->payroll_enddate);
            $b[$i]['empl_addtional'] = $additional_array['total_additional'];
            $b[$i]['empl_addtional_with_cpf'] = $additional_array['additional_with_cpf'];
            
            $deductions_array = $this->getDeductionsSalary($row['applicant_id'],$this->payroll_startdate,$this->payroll_enddate, $row['fol_offer_salary'],$totalworkingdate);

            $b[$i]['empl_total_leave'] = $deductions_array['deductions_total_leave'];
            $b[$i]['empl_deductions'] = $deductions_array['deductions_total_amt'];
            $b[$i]['empl_deductions_without_leave'] = $deductions_array['deductions_amt'];
            $b[$i]['Levy_employee'] = $deductions_array['leave_amt'];
            $b[$i]['empl_deductions_with_cpf'] = $deductions_array['deductions_with_cpf'];

            $b[$i]['empl_addtional_withoutclaim'] = 0;
            $b[$i]['empl_deductions_withoutdeduct'] = 0;
            
            $cpf_array = $this->getCPF($row['applicant_id'],$b[$i]['empl_salary'],$b[$i]['empl_addtional_with_cpf'],$b[$i]['empl_deductions_with_cpf']);
            $b[$i]['cpf_employee'] = $cpf_array['cpfEmployee'];
            $b[$i]['cpf_employer'] = $cpf_array['cpfEmployer'];
            $b[$i]['payline_netpay'] = (($row['fol_offer_salary'] + $b[$i]['empl_addtional']) - $b[$i]['empl_deductions'] - $b[$i]['cpf_employee']);
            
            $b[$i]['payroll_startdate'] = $this->payroll_startdate;
            $b[$i]['payroll_enddate'] = $this->payroll_enddate;

            $i++;
        }
     if($this->return_array == 1){
        return $b;
     }
     else{
        echo json_encode($b);
     }
     exit();
    }
    public function getAddtionalSalary($empl_id,$datefrom,$dateto){
        $month = substr($datefrom,0,7);
        $sql = "SELECT SUM(additional_amount) as additional_amt FROM db_additional WHERE additional_empl_type = '1' AND additional_status = '1' AND additional_empl_id = '$empl_id' AND additional_date BETWEEN '$datefrom' AND '$dateto'"; 

        $query = mysql_query($sql);
        $additional_amt = 0;
        if($row = mysql_fetch_array($query)){
            $additional_amt = $row['additional_amt'];
        }else{
            $additional_amt = 0;
        }
        
        //include cpf additional
        $sql = "SELECT SUM(additional_amount) as additional_amt FROM db_additional WHERE additional_empl_type = '1' AND additional_status = '1' AND additional_empl_id = '$empl_id' AND additional_date BETWEEN '$datefrom' AND '$dateto' AND additional_affect_cpf = '1'"; 
        $query = mysql_query($sql);
        $additional_with_cpf = 0;
        if($row = mysql_fetch_array($query)){
            $additional_with_cpf = $row['additional_amt'];
        }else{
            $additional_with_cpf = 0;
        }
        
        $additional_array['total_additional'] = ROUND($additional_amt,2);
        $additional_array['additional_with_cpf'] = ROUND($additional_with_cpf,2);
        
        return $additional_array;
    }
    public function getDeductionsSalary($empl_id,$datefrom,$dateto, $salary, $total_workingdays){

        $sql = "SELECT SUM(deductions_amount) as deductions_amt FROM db_deductions WHERE deduction_empl_type = '1' AND deductions_status = '1' AND deductions_empl_id = '$empl_id' AND deductions_date BETWEEN '$datefrom' AND '$dateto'"; 
        $query = mysql_query($sql);
        $deductions_amt = 0;
        if($row = mysql_fetch_array($query)){
            $deductions_amt = $row['deductions_amt'];
        }else{
            $deductions_amt = 0;
        }
        
        //just cpf deductions
        $sql = "SELECT SUM(deductions_amount) as deductions_amt FROM db_deductions WHERE deduction_empl_type = '1' AND deductions_status = '1' AND deductions_empl_id = '$empl_id' AND deductions_date BETWEEN '$datefrom' AND '$dateto' AND deductions_affect_cpf = '1'"; 
        $query = mysql_query($sql);
        $deduction_with_cpf = 0;
        if($row = mysql_fetch_array($query)){
            $deduction_with_cpf = $row['deductions_amt'];
        }else{
            $deduction_with_cpf = 0;
        }
        
        $deductions_array['deductions_amt'] = $deductions_amt;
        
        $deductions_array['deductions_with_cpf'] = $deduction_with_cpf;
        
        $day_salary =  (double)$salary / (double)$total_workingdays;

        $sql1 = "SELECT * FROM db_leave WHERE leave_status = '1' AND leave_empl_type = '1' AND leave_type = '9' AND leave_empl_id = '$empl_id' AND leave_duration = 'full_day' AND leave_approvalstatus = 'Approved' AND  ((leave_datefrom BETWEEN '$datefrom' AND '$dateto') OR (leave_dateto BETWEEN '$datefrom' AND '$dateto'))";
        $query1 = mysql_query($sql1);
        $leave_amt = 0;
        $total_day = 0;
        $day = 0;
        $day2 = 0;
        while($row1 = mysql_fetch_array($query1)){
            $dateStart = $row1['leave_datefrom'];
            $dateEnd = $row1['leave_dateto'];
         
            $dateStartMonth = substr($dateStart, 5,2);
            $dateEndMonth = substr($dateEnd, 5,2);
            $dateCurrent = substr($datefrom, 5,2);

            if($dateStartMonth < $dateEndMonth){
                if($dateStartMonth == $dateCurrent){
                    $lastDay = date("Y-m-t", strtotime($datefrom));
                    $date1=date_create($dateStart);
                    $date2=date_create($lastDay);
                    $diff=date_diff($date1,$date2);

                    $diff->format("%a");
                    $day = (double)$diff->format("%a");
                    $day++;
                    $leave_amt = $day_salary * $day;
                    $total_day = $total_day + $day;
                }
                else
                {
                    $firstDay = date('d-m-Y', strtotime($datefrom));
                    $date3=date_create($firstDay);
                    $date4=date_create($dateEnd);
                    $diff2=date_diff($date3,$date4);
                    $diff2->format("%a");
                   
                    $day2 = (double)$diff2->format("%a");
                    $day2++;
                    $leave_amt = $day_salary * $day2; 
                    $total_day = $total_day + $day2;
                }                   
                    $total_leave = $total_leave + $leave_amt;

            }
            else{
                $leave_amt = $day_salary * (double)$row1['leave_total_day'];
                $total_leave = $total_leave + $leave_amt;
                $total_day = $total_day + (double)$row1['leave_total_day'];
            }

            $deductions_amt = $deductions_amt + $leave_amt;
        }
        
//        $sql = "SELECT lt.leavetype_code FROM db_leavetype lt INNER JOIN db_leave l ON l.leave_type = lt.leavetype_id WHERE l.leave_empl_id = '$empl_id' AND ((leave_datefrom BETWEEN '$datefrom' AND '$dateto') OR (leave_dateto BETWEEN '$datefrom' AND '$dateto'))"
        
        $deductions_array['deductions_total_leave'] = $total_day;   
        $deductions_array['deductions_with_cpf'] = $deductions_array['deductions_with_cpf'] + $total_leave;
                
        $deductions_array['deductions_total_amt'] = ROUND($deductions_amt,1);
        $deductions_array['leave_amt'] = ROUND($total_leave,1);
        
        //return ROUND($deductions_amt,2);
        return $deductions_array;
    }
    public function getCPF($empl_id, $salary, $addition, $deduction){
        include_once 'class/Cpf.php';
        $c = new Cpf();

        $sql = "SELECT * FROM db_applicant WHERE applicant_id = '$empl_id'";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);
        

        $from = new DateTime($row['applicant_birthday']);
        $to = new DateTime('today');
        $empl_age = $from->diff($to)->y;
        (double)$empl_age;
        

        if($row['applicant_nationality'] == "2"){
            $cpf_array = $c->fetchCpfDetail(" AND cpf_from_age <= '$empl_age' AND cpf_to_age >= '$empl_age' ", "", "",2);
        
            $totalCPF['cpfEmployee'] = (($salary + $addition) - $deduction) * $cpf_array['cpf_employee_percent']/100;
            $totalCPF['cpfEmployer'] = (($salary + $addition) - $deduction) * $cpf_array['cpf_employer_percent']/100;
        }
        
        else{
            $totalCPF['cpfEmployee'] = 0;
            $totalCPF['cpfEmployer'] = 0; 
        }
        return $totalCPF;
    }
    public function previewPayslipDetail(){
    include_once 'class/Applicant.php';
    include_once 'class/Cpf.php';
        $c = new Cpf();
        $a = new Applicant();
        
        $empl_array = $a->fetchApplicantDetail(" AND applicant_id = '$this->empl_id'","","",2);
        $job_array = $this->getJobDetail($this->empl_id);
        
        $this->fetchPayrollLineDetail(" AND payline_payroll_id = '$this->payroll_id' AND payline_empl_id = '$this->empl_id' ","","",2);

        
        $from = new DateTime($empl_array['applicant_birthday']);
        $to = new DateTime('today');
        $empl_age = $from->diff($to)->y;
        $this->payroll_salary_date = $_REQUEST['salary_date'];

    ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Payroll Management</title>
    <?php
    include_once 'css.php';
    $this->outletCrtl = $this->select->getOutletSelectCtrl($this->empl_outlet);
    $this->departmentCrtl = $this->select->getDepartmentSelectCtrl($this->empl_department);

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
            <h1>Payroll Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Salary Payslips</h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='applicantpayroll.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='applicantpayroll.php?action=createForm'">Create New</button>
                <?php }?>
              </div>

                <form id = 'payroll_form' class="form-horizontal" action = 'applicantpayroll.php?action=create' method = "POST">
                  <div class="box-body">
                    <div class="col-sm-12">  
                       <div class="col-sm-12" style = 'text-align:center'>  
                        <h3>Salary Payslip of <?php echo $empl_array['applicant_name'];?></h3>
                       </div>
                    <h4>Salary Information</h4>
                    <div class="form-group">
                      <label for ="payroll_client" class="col-sm-2 control-label">Employee Code : </label>
                      <div class="col-sm-2">
                       <label for ="payroll_client" class="col-sm-7 control-label" style = 'font-weight: inherit;' ><?php echo "C".$empl_array['appl_code'];?></label>
                      </div>
                      <label for ="payroll_client" class="col-sm-2 control-label">Client : </label>
                      <div class="col-sm-4">
                       <label for ="payroll_client" class="col-sm-6 control-label" style = 'font-weight: inherit;'><?php echo $job_array['company'];?></label>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for ="payroll_client" class="col-sm-2 control-label">Employee Name : </label>
                      <div class="col-sm-2">
                       <label for ="payroll_client" class="col-sm-7 control-label" style = 'font-weight: inherit;'><?php echo $empl_array['applicant_name'];?></label>
                      </div>
                      <label for ="payroll_client" class="col-sm-2 control-label">Department : </label>
                      <div class="col-sm-4">
                       <label for ="payroll_client" class="col-sm-6 control-label" style = 'font-weight: inherit;'><?php echo $job_array['fol_department'];?></label>
                      </div>
                    </div>
                    <div class="form-group">  
                      <label for ="payroll_department" class="col-sm-2 control-label">Salary Date : </label>
                      <div class="col-sm-2">
                          <label for ="payroll_client" class="col-sm-7 control-label" style = 'font-weight: inherit;'><?php echo format_date($this->payroll_salary_date);?></label>
                      </div>
                      <label for ="payroll_client" class="col-sm-2 control-label">Join Date : </label>
                      <div class="col-sm-4">
                       <label for ="payroll_client" class="col-sm-6 control-label" style = 'font-weight: inherit;'><?php echo format_date($job_array['fol_available_date']);?></label>
                      </div>         
                    </div>
                    <div class="form-group">  
                      <label for ="payroll_department" class="col-sm-2 control-label">Salary : </label>
                      <div class="col-sm-2">
                          <label for ="payroll_client" class="col-sm-7 control-label" style = 'font-weight: inherit;'>
                              <?php 
                              echo "$ ".num_format($job_array['fol_offer_salary']);
                              //$this->summary['Basic_Salary'] = $empl_salary_array['emplsalary_amount'];
                              ?>
                          </label>
                      </div>
<!--                      <label for ="payroll_client" class="col-sm-2 control-label">Confirmation Date : </label>
                      <div class="col-sm-4">
                       <label for ="payroll_client" class="col-sm-6 control-label" style = 'font-weight: inherit;'><?php if($empl_array['empl_confirmationdate'] == '0000-00-00'){ echo ' - ';}else{echo format_date($empl_array['empl_confirmationdate']);}?></label>
                      </div>  -->
                    </div>
                     <h4>Salary Payslip Period</h4>
                     <div class="form-group">
                         <label for="payroll_startdate" class="col-sm-2 control-label">Payslip Period : </label>
                      <div class="col-sm-5">
                          <label for ="payroll_client" class="col-sm-7 control-label" style = 'font-weight: inherit;'><?php echo format_date($this->payroll_startdate);?> <b>To</b> <?php echo format_date($this->payroll_enddate);?></label>
                      </div>
                     </div> 

                     <div class="form-group">
                      <div class="col-sm-6">
                        <h4>Additional Items</h4>
                        <?php echo $this->getAdditionalItemsListing($empl_salary_array);?>
                      </div>
                      <div class="col-sm-6">
                        <h4>Deductions Items</h4>
                        <?php echo $this->getDeductionsItemsListing($empl_salary_array,$empl_array);?>
                      </div>
                     </div>
                     <!--<h4>CPF</h4>-->
                         <?php 
                            if($this->emplpass_cpf == 1){// have CPF
                                $this->CPF_Employer = ($this->summary['Basic_Salary'] + $this->summary['Additional_Items'] - $this->summary['Deductions_Items']) * CPF_employer;
                                $this->CPF_Employee = ($this->summary['Basic_Salary'] + $this->summary['Additional_Items'] - $this->summary['Deductions_Items']) * CPF_employee;
                            }else{
                                $this->CPF_Employer = 0;
                                $this->CPF_Employer = 0;
                            }
                         ?>

                     <div class="form-group">
                      <div class="col-sm-6">

                        <?php echo $this->getSummaryItemsListingLeft($empl_array,$empl_salary_array);?>
                      </div>
                      <div class="col-sm-6">

                        <?php echo $this->getSummaryItemsListingRight($empl_array);?>
                      </div>
                     </div>
                     
                    </div>
                      <div style = 'clear:both'></div>
                      
                        

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Back</button>
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->payroll_id;?>" name = "payroll_id"/>
                    <?php 
                    if($this->payroll_id > 0){
                        $prm_code = "update";
                    }else{
                        $prm_code = "create";
                    }
?>
                    <!--<button type = "submit" name = 'submit_btn' value = 'Save' class="btn btn-info">Save</button>-->
                    
                    <?php if($this->payroll_id > 0){?>
                    &nbsp;&nbsp;&nbsp;
                    <a href="payslip-print.php?payroll_id=<?php echo $this->payroll_id;?>&empl_id=<?php echo $_REQUEST['empl_id'];?>&type=1" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                    <button type="button" class="btn btn-primary" target="_blank" style="margin-left:10px; background-color:#f4f4f4;color:#444;border-color:#ddd" onclick = "window.open('applicantpayroll.php?action=printTimesheet&empl_id=<?php echo $_REQUEST['empl_id'];?>&Date=<?php echo $this->payroll_enddate;?>')">Print Timesheet</button>
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
                     <?php if(file_exists("dist/images/empl/{$this->payroll_empl_id}.png")){?>
                     <img src="dist/images/empl/<?php echo $this->payroll_empl_id;?>.png" class="img-circle" alt="User Image"  >
                  <?php }else{?>
                     <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="img-circle">
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
                    <td>Outlet</td>
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
<div class="modal fade" id="countdownmodel" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
         
          <h4 class="modal-title" style = 'font-weight:bold;text-align:center'>You will be sign out in </h4>
        </div>
        <div class="modal-body" style = 'text-align:center' >
            <div id='countdown' style = 'font-weight:bold;font-size:10em' ></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" id ='sign_out'>Sign out</button>
          <button type="button" class="btn btn-default" id ='continue' >Continue</button>
        </div>
      </div>
      
    </div>
  </div>
    
    <input type="hidden" value = '60' id = 'countDown' />
    <script>
    
    $(document).ready(function() {
        $('#countdownmodel').modal({
            show: false,
             backdrop: 'static',
        });
        $('#sign_out').click(function(){
            window.location.href = 'applicantpayroll.php?f=d';
        });
    });

//function countdown() {
//        setInterval(function () {
//            countDown = $('#countDown').val();
//            if (countDown == 30) {
//                $('#countdownmodel').modal('show');
//                $('#continue').click(function(){
//                   $('#countdownmodel').modal('hide');
//                   $('#countDown').val(60);
//                  
//                });
//            }
//            countDown--;
//            if(countDown == 0){
//                window.location.href = 'applicantpayroll.php?f=d';
//            }
//            document.getElementById('countdown').innerHTML = countDown;
//            $('#countDown').val(countDown);
//            return countDown;
//        }, 1000);
//    }
//
//countdown();
    </script>
  </body>
</html>

    <?php
    }
    public function getAdditionalItemsListing($empl_salary_array){
    $line = 0;  
    $total_amt = 0;
    ?>    
    <table id="detail_table" class="table transaction-detail">
        <thead>
          <tr>
            <th class = "" style="width:5%;">No</th>
            <th class = "" style = 'width:60%;'>Items</th>
            <th class = "" style = 'width:38%;text-align:right'>Amount</th>

          </tr>
        </thead>
        <tbody>
            <?php
            if($this->payroll_id > 0){
                $sql = "
                    SELECT pi.payitem_remark as title,pi.payitem_amount as claims_amount
                    FROM db_payline pl
                    INNER JOIN db_payitem pi ON pi.payitem_payline_id = pl.payline_id
                    WHERE pl.payline_payroll_id = '$this->payroll_id' AND payline_empl_id = '$this->empl_id' AND payitem_type = '1'
                    ORDER BY pi.payitem_remark
                    ";
                //echo $sql; die;
            }else{
                $sql = $this->getAdditionalSql($empl_salary_array);
            }
            $query = mysql_query($sql);
            $this->summary["calculateforcdacadd_amt"] = 0;
            while($row = mysql_fetch_array($query)){
                $total_amt = $total_amt + $row['claims_amount'];
                $line++;
                if($row['calculateforcdac'] == 1){
                    $this->summary["calculateforcdacadd_amt"] = $this->summary["calculateforcdacadd_amt"] + ROUND($row['claims_amount'],2);
                }
            ?>
                <tr id = "line_<?php echo $line;?>" class="tbl_grid_odd" line = "<?php echo $line;?>">
                    <td><?php echo $line;?></td>
                    <td><?php echo strtoupper($row['title']);?></td>
                    <td style = 'text-align:right' ><?php echo "$ ".num_format($row['claims_amount']);?></td>
  
                </tr>
            
            <?php
            }
            $this->summary["Additional_Items"] = $total_amt;
            ?>
        </tbody>
    </table>
    <?php    
    }
    public function getDeductionsItemsListing($empl_salary_array,$empl_array){
    $line = 0;  

    ?>    
    <table id="detail_table" class="table transaction-detail">
        <thead>
          <tr>
            <th class = "" style="width:5%;">No</th>
            <th class = "" style = 'width:60%;'>Items</th>
            <th class = "" style = 'width:38%;text-align:right'>Amount</th>
          </tr>
        </thead>
        <tbody>
            <?php
        $salary_montly = $empl_salary_array['emplsalary_amount'];
        $total_workingdays = $empl_salary_array['emplsalary_workday'];
        $hourly_salary = $empl_salary_array['emplsalary_hourly'];
        $total_workinghours = $empl_salary_array['emplsalary_hourly'];
 
        if(($hourly_salary == "") || ($hourly_salary <=0)){
            $hourly_salary = ROUND(($salary_montly / $total_workingdays)/$total_workinghours,2);
        }
        $day_salary =  $empl_salary_array['emplsalary_amount'] / $total_workingdays;

            $salary_info['day_salary'] = $day_salary;
            $salary_info['emplsalary_amount'] = $empl_salary_array['emplsalary_amount'];
            
            if($this->payroll_id > 0){
                $sql = "
                    SELECT pi.payitem_remark as title,pi.payitem_amount as deductions_amt
                    FROM db_payline pl
                    INNER JOIN db_payitem pi ON pi.payitem_payline_id = pl.payline_id
                    WHERE pl.payline_payroll_id = '$this->payroll_id' AND payline_empl_id = '$this->empl_id' AND payitem_type = '0'
                    ORDER BY pi.payitem_remark
                    ";
            }else{
                $sql = $this->getDeductionsSql($salary_info);
            }
            

            $query = mysql_query($sql);
            $total_amt = 0;
            while($row = mysql_fetch_array($query)){
                if($row['type'] == 'leaves'){
                    if($row['deductions_amt'] <= 0){
                        continue;
                    }
                }
                $total_amt = $total_amt + $row['deductions_amt'];
                $line++;
            ?>
                <tr id = "line_<?php echo $line;?>" class="tbl_grid_odd" line = "<?php echo $line;?>">
                    <td ><?php echo $line;?></td>
                    <td><?php echo strtoupper($row['title']);?></td>
                    <td style = 'text-align:right' ><?php echo "$ ".num_format($row['deductions_amt']);?></td>
                    <td></td>
                </tr>
            
            <?php
            }
            if(($this->payroll_id <= 0) && ($empl_array['empl_iscpf'] == 1)){
            ?>
                <tr id = "line_<?php echo $line;?>" class="tbl_grid_odd" line = "<?php echo $line;?>">
                    <td><?php echo $line+1;?></td>
                    <td><?php echo strtoupper("Employee CPF (" . CPF_employee*100 . "%)");?></td>
                    <td style = 'text-align:right' >
                        <?php 
                        
                        $empl_addtional = $this->getAddtionalSalary($this->empl_id,$this->payroll_startdate,$this->payroll_enddate,true,true);
                        $empl_deduction = $this->getDeductionsSalary($this->empl_id,$this->payroll_startdate,$this->payroll_enddate,$salary_info,true,true);
                        $total_cpfamt = ROUND(($empl_salary_array['emplsalary_amount'] + $empl_addtional - ($empl_deduction)) * (CPF_employee + CPF_employer)) ;

                        $cpf_amt = floor(($empl_salary_array['emplsalary_amount'] + $empl_addtional - ($empl_deduction))*CPF_employee);// employee is round down.
                        echo "$ ".num_format($cpf_amt);
                        $employer_cpf_amount = $total_cpfamt - $cpf_amt;
                        $total_amt = $total_amt + $cpf_amt;
                       
                        ?>
                    </td>
                    <td></td>
                </tr>   
            <?php  
            }else{
                $cpf_amt = 0;
            }
            $this->employee_cpf_amount = $cpf_amt;
            $this->employer_cpf_amount = $employer_cpf_amount;

            $this->summary["Deductions_Items"] = $total_amt; 
            ?>
        </tbody>
    </table>
    <?php    
    }
    public function getSummaryItemsListingLeft($empl_array,$empl_salary_array){

        
    ?>    
    <table id="detail_table" class="table transaction-detail" width = '100%'>

        <tbody>
            <?php
            $payline = $_REQUEST['payroll_id'];
            $empl_id = $_REQUEST['empl_id'];
            $sql = "SELECT p.*, a.*, bank_code FROM db_payline p INNER JOIN db_applicant a ON p.payline_empl_id = a.applicant_id INNER JOIN db_bank b ON a.applicant_bank = b.bank_id WHERE payline_payroll_id = '$payline' AND payline_empl_id = '$empl_id'";

            $query = mysql_query($sql);
            $row = mysql_fetch_array($query);
            
            $empl_addtional = $this->getAddtionalSalary($this->empl_id,$this->payroll_startdate,$this->payroll_enddate,true,true);
            $empl_deductions = $this->getDeductionsSalary($this->empl_id,$this->payroll_startdate,$this->payroll_enddate,$empl_salary_array,true,$empl_addtional);
            
            if($this->payroll_id > 0){
                $this->employee_cpf_amount = $this->payline_cpf_employer;
                $this->employer_cpf_amount = $this->payline_cpf_employee;
            }
            if($empl_array['empl_iscpf'] == 1){
                $employer_cpf_amt = $this->employee_cpf_amount;
                $employee_cpf_amt = $this->employer_cpf_amount;
            }else{
                $employer_cpf_amt = 0;
                $employee_cpf_amt = 0;
            }
            $this->employer_cpf_amt = $employer_cpf_amt;
            $this->employee_cpf_amt = $employee_cpf_amt;
            ?>

                <tr>
                    <td style="width:5%;"></td>
                    <td style = 'width:60%;font-weight: 700'>Total Gross</td>
                    <td style = 'width:38%;text-align:right'><?php echo "$ ".num_format($this->summary["Additional_Items"]);?></td>
                </tr>
                <tr>
                    <td></td>
                    <td style = 'font-weight: 700' >CPF Gross</td>
                    <td style = 'text-align:right' ><?php echo "$ ".num_format(round($row['payline_cpf_employee']));?></td>
                </tr>
                <tr>
                    <td></td>
                    <td style = 'font-weight: 700'>Employer CPF</td>
                    <td style = 'text-align:right' ><?php echo "$ ".num_format(round($row['payline_cpf_employer']));?></td>
                </tr>
                <tr>
                    <td></td>
                    <td style = 'font-weight: 700'>Date Of Payment</td>
                    <td style = 'text-align:right' ><?php echo format_date($this->payroll_salary_date);?></td>
                </tr>
                <tr>
                    <td></td>
                    <td style = 'font-weight: 700'>Mode Of Payment</td>
                    <td style = 'text-align:right' ><?php echo $row['bank_code'] . " - " . $row['applicant_bank_acc_no'];?></td>

                </tr>
        </tbody>
    </table>
    <?php    
    }
    public function getSummaryItemsListingRight(){
     
      $this->employee_net_payment = $this->summary["Additional_Items"] - $this->summary["Deductions_Items"];
    ?>    
    <table id="detail_table" class="table transaction-detail" width = '100%'>

        <tbody>
                <tr>
                    <td style="width:5%;padding-left:5px"></td>
                    <td style = 'width:60%;font-weight: 700'>Total Deduction</td>
                    <td style = 'width:38%;text-align:right'><?php echo "$ ".num_format($this->summary["Deductions_Items"]);?></td>
                </tr>
                <tr>
                    <td style="padding-left:5px"></td>
                    <td style = 'font-weight: 700'>Net Payment</td>
                    <td style = 'text-align:right' ><?php echo "$ ".num_format($this->summary["Additional_Items"] - $this->summary["Deductions_Items"]);?></td>
                </tr>
                <tr>
                    <td style="padding-left:5px"></td>
                    <td style = 'font-weight: 700'>Year To Date Net Pay</td>
                    <td style = 'text-align:right' ><?php echo "$ ".num_format($this->getYearAmount($this->payroll_startdate,$this->empl_id,'net_pay',$this->payroll_enddate));?></td>
                </tr>
                <tr>
                    <td style="padding-left:5px"></td>
                    <td style = 'font-weight: 700'>Year To Date Employer CPF</td>
                    <td style = 'text-align:right' ><?php echo "$ ".num_format($this->getYearAmount($this->payroll_startdate,$this->empl_id,'employer',$this->payroll_enddate));?></td>
                </tr>
                <tr>
                    <td style="padding-left:5px"></td>
                    <td style = 'font-weight: 700'>Year To Date Employee CPF</td>
                    <td style = 'text-align:right' ><?php echo "$ ".num_format($this->getYearAmount($this->payroll_startdate,$this->empl_id,'employee',$this->payroll_enddate));?></td>
                </tr>
        </tbody>
    </table>
    <?php    
    }
    public function getYearAmount($date,$empl_id,$type,$payroll_enddate){

    $date = DateTime::createFromFormat("Y-m-d",$date);
    $year = $date->format("Y");
    $total_cpf = 0;
    
    if($type == 'employer'){
        $field = 'payline_cpf_employer';
    }else if($type == 'employee'){
        $field = 'payline_cpf_employee';
    }else if($type == 'net_pay'){
        $field = 'payline_netpay';
    }
    $sql = "SELECT COALESCE(SUM($field),0) as total_cpf
            FROM db_payline pl
            INNER JOIN db_payroll py ON py.payroll_id = pl.payline_payroll_id
            WHERE pl.payline_empl_id = '$empl_id' AND LEFT(py.payroll_startdate,4) = '$year' AND py.payroll_startdate <='$payroll_enddate' AND py.payroll_status = '1' AND pl.payline_empl_type = '1'";

    $query = mysql_query($sql);
    if($row = mysql_fetch_array($query)){
        $total_cpf = $row['total_cpf'];
    }else{
        $total_cpf = 0;
    }
    

//    if($this->payroll_id <=0){
//        if($type == 'employer'){
//               $total_cpf = $this->employer_cpf_amt;
//        }else if($type == 'employee'){
//               $total_cpf = $this->employee_cpf_amt;
//        }else if($type == 'net_pay'){
//               $total_cpf = $this->employee_net_payment;
//        }
//    }

    
    return $total_cpf;
    
    }
    public function getAdditionalSql($empl_salary_array,$empl_id,$payroll_startdate,$payroll_enddate){
        
            if($empl_id == ""){
                $empl_id = $this->empl_id;
            }
            if($payroll_startdate == ""){
                $payroll_startdate = $this->payroll_startdate;
            }
            if($payroll_enddate == ""){
                $payroll_enddate = $this->payroll_enddate;
            }
            $month = substr($payroll_startdate,0,7);
            $sql = "
                    SELECT 'basic pay' as title,'{$empl_salary_array['emplsalary_amount']}' as claims_amount,0 as calculateforcdac
                    
                    UNION ALL

                    SELECT 'Claim' as title ,SUM(cl.claimsline_amount + cl.claimsline_amount_gst) as claims_amount,0 as calculateforcdac
                    FROM db_claims c
                    INNER JOIN db_claimsline cl ON cl.claimsline_claims_id = c.claims_id
                    LEFT JOIN db_claimstype ct ON ct.claimstype_id = cl.claimsline_type
                    WHERE c.claims_date BETWEEN '$payroll_startdate' AND '$payroll_enddate' AND c.claims_empl_id = '$empl_id' and c.claims_status = 1 AND c.claims_approvalstatus = 'Approved'
                  
                    UNION ALL

                    SELECT 'OverTimes' as title,(att.attendance_ottotal*(emplsalary.emplsalary_overtime/60)) as claims_amount,1 as calculateforcdac
                    FROM db_attendance att
                    INNER JOIN db_emplsalary emplsalary ON emplsalary.emplsalary_empl_id = att.attendance_empl AND emplsalary.emplsalary_status = 1 
                    INNER JOIN db_empl empl ON empl.empl_id = att.attendance_empl
                    WHERE att.attendance_empl = '$empl_id' AND att.attendance_status = 1 AND LEFT(attendance_date,7) = '$month' AND empl.empl_isovertime = 1 

                    UNION ALL
                    
                    SELECT alt.additionaltype_code as title,l.additional_amount as claims_amount,1 as calculateforcdac
                    FROM db_additional l
                    LEFT JOIN db_additionaltype alt ON alt.additionaltype_id = l.additional_type
                    WHERE l.additional_date BETWEEN '$payroll_startdate' AND '$payroll_enddate' AND l.additional_empl_id = '$empl_id' and l.additional_status = 1
                    ";

            return $sql;
    }
    public function getDeductionsSql($salary_info,$empl_id,$payroll_startdate,$payroll_enddate){
        
            if($empl_id == ""){
                $empl_id = $this->empl_id;
            }
            if($payroll_startdate == ""){
                $payroll_startdate = $this->payroll_startdate;
            }
            if($payroll_enddate == ""){
                $payroll_enddate = $this->payroll_enddate;
            }
            //5 = Singaporean
            //3 = PR
            $month = substr($payroll_startdate,0,7);
            $deduct_amt_without_cdac = $this->getDeductionsSalary($empl_id, $payroll_startdate, $payroll_enddate,$salary_info,false,true);
            $cdac = $this->getCDAC(($salary_info['emplsalary_amount']+$this->summary["calculateforcdacadd_amt"])-$deduct_amt_without_cdac);

            $salary_amount_type = $this->getEmployeeSalaryTime($salary_info['emplsalary_amount'],$this->payroll_total_working_days);
        $sql = "
                
                SELECT 'leaves' as type,CONCAT('Unpaid Leaves',' (',COALESCE(SUM(leave_unpaid),0),') ') as title,COALESCE(SUM(leave_unpaid)*{$salary_info['day_salary']},0) as deductions_amt
                FROM db_leave l
                INNER JOIN db_leavetype lt ON lt.leavetype_id = l.leave_type
                WHERE ((l.leave_datefrom BETWEEN '$payroll_startdate' AND '$payroll_enddate') OR (l.leave_dateto BETWEEN '$payroll_startdate' AND '$payroll_enddate'))
                AND l.leave_empl_id = '$empl_id' and l.leave_status = 1 AND l.leave_approvalstatus = 'Approved'

                UNION ALL
                
                SELECT 'deductions' as type,'donation CDAC' as title,'$cdac' as claims_amount
                FROM db_empl empl
                WHERE empl.empl_id = '$empl_id' AND empl.empl_fund_opt_out = 0
                    
                UNION ALL
                
                SELECT 'deductions' as type,'Late' as title,(att.attendance_latetotal*{$salary_amount_type['min_amount']}) as deductions_amt
                FROM db_attendance att
                WHERE att.attendance_empl = '$empl_id' AND att.attendance_status = 1 AND LEFT(attendance_date,7) = '$month'

                UNION ALL
                
                SELECT 'deductions' as type,l.deductions_title as title,l.deductions_amount as deductions_amt
                FROM db_deductions l
                WHERE l.deductions_date BETWEEN '$payroll_startdate' AND '$payroll_enddate' AND l.deductions_empl_id = '$empl_id' and l.deductions_status = 1         

                ";

            return $sql;
    }
    public function UpdateSelfView(){
        $sql = "UPDATE db_payline SET payline_selfview = '1' WHERE payline_payroll_id = '$this->payroll_id' AND payline_empl_id = '$this->empl_id'";
        mysql_query($sql);
    }
    public function getCDAC($emplsalary_amount){
            

        
            if($emplsalary_amount <= 2000){
                $cdac = 0.5;
            }else if(($emplsalary_amount > 2000) && ($emplsalary_amount <= 3500)){
                $cdac = 1;
            }else if(($emplsalary_amount > 3500) && ($emplsalary_amount <= 5000)){
                $cdac = 1.5;
            }else if(($emplsalary_amount > 5000) && ($emplsalary_amount <= 7500)){
                $cdac = 2;
            }else{
                $cdac = 3;
            }
            return $cdac;
    }
    public function getVarifyPassword(){
        $this->varify_password = md5("@#~x?\$" . $this->varify_password . "?\$");
        $sql = "SELECT COUNT(*) as total,empl.*,outl.outl_code
               FROM db_empl empl
               LEFt JOIN db_outl outl ON outl.outl_id = empl.empl_outlet
               WHERE empl.empl_login_email = '{$_SESSION['empl_login_email']}'
               AND empl.empl_login_password = '$this->varify_password' AND empl.empl_status = '1' ";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
            $total = $row['total'];
            $_SESSION['empl_varify_password'] = 1;
        }else{
            $total = 0;
        }
        if($_SESSION['empl_id'] == '10000'){
             return true;
        }
        if($total > 0){
            return true;
        }else{
            return false;
        }
    }
    public function getEmployeeSalaryTime($emplsalary_amount,$total_working_days,$working_hours = 9){
        
        $day_amount = $emplsalary_amount / $total_working_days;
        $hours_amount = $day_amount / 9;
        $min_amount = $hours_amount / 60;
     
        if($day_amount <=0){
            $day_amount = 0;
        }
        if($hours_amount <=0){
            $hours_amount = 0;
        }
        if($min_amount <=0){
            $min_amount = 0;
        }
        return array('day_amount'=>$day_amount,'hours_amount'=>$hours_amount,'min_amount'=>$min_amount);
    }
    public function confirmedPayroll(){
        $c = explode(',',$this->payroll_array);
        foreach($c as $id){
            $table_field = array('payroll_isapproved');
            $table_value = array(1);
            $remark = "Update Apply Payroll approved";
            $this->save->UpdateData($table_field,$table_value,'db_payroll','payroll_id',$remark,$id);
        }
    }
    public function getDepartment(){
        $pid = escape($_REQUEST['department_id']);
        $this->client_id = escape($_REQUEST['client_id']);
        $sql = "SELECT timeshift_id, timeshift_department FROM db_timeshift where timeshift_company = '$this->client_id'";
        $query = mysql_query($sql);
        
        $data[0] ="<option value = '0' $selected>Select One</option>"; 
        $i = 1;
        while($row = mysql_fetch_array($query)){
            $id = $row['timeshift_id'];
            $code = $row['timeshift_department'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }
            else{
                $selected = "";
            }
           $data[$i] ="<option value = '$id' $selected>$code</option>"; 
        $i++;
        }
        return $data;
    }
    public function getTimeShiftDetail(){
        $this->timeshift_id = escape($_REQUEST['timeshift_id']);
        $sql = "SELECT * FROM db_timeshift where timeshift_id = '$this->timeshift_id'";
        $query = mysql_query($sql);
        $i = 0;
        $row = mysql_fetch_array($query);
            $data ['timeshift_start_time'] = $row['timeshift_start_time'];
            $data ['timeshift_end_time'] = $row['timeshift_end_time'];
            $data ['timeshift_work_day'] = $row['timeshift_work_day'];
            $data ['timeshift_ot_rate'] = format_date($row['timeshift_ot_rate']);
        return $data;
    }
    public function getJobDetail($applicant_id){
        $sql = "SELECT * FROM db_followup WHERE applfollow_id = '$applicant_id' AND follow_type = '0' AND fol_status = '0' AND fol_approved = 'Y'";
        $query = mysql_query($sql);
        $i = 0;
        $row = mysql_fetch_array($query);
            $data ['follow_id'] = $row['follow_id'];
            
            $sql1 = "SELECT partner_name FROM db_partner where partner_id = '$row[interview_company]'";
            $query1 = mysql_query($sql1);
            $row1 = mysql_fetch_array($query1);
            $data ['company'] = $row1['partner_name'];
            
            $sql2 = "SELECT timeshift_department FROM db_timeshift where timeshift_id = '$row[fol_department]'";
            $query2 = mysql_query($sql2);
            $row2 = mysql_fetch_array($query2);            
            
            $data ['fol_department'] = $row2['timeshift_department'];
            $data ['fol_job_assign'] = $row['fol_job_assign'];
            $data ['fol_payroll_empl'] = $row['fol_payroll_empl'];
            $data ['fol_position_offer'] = $row['fol_position_offer'];
            $data ['fol_offer_salary'] = $row['fol_offer_salary'];
            $data ['fol_available_date'] = $row['fol_available_date'];
        return $data;
    }
    public function printTimesheetss(){
        //$pdf = new FPDF('P', 'pt', 'Letter');
        //$pdf->AddPage(); 
        
        $pdf = new FPDI();

        $pdf->AddPage();    
       
        
        $empl_id = escape($_REQUEST['empl_id']);
        $date = $_REQUEST['Date'];
        
        $sql = "SELECT f.*, p.partner_name, a.applicant_name, a.applicant_mobile, a.applicant_nric, t.timeshift_department FROM db_followup f INNER JOIN db_partner p ON p.partner_id = f.interview_company INNER JOIN db_applicant a ON a.applicant_id = f.applfollow_id INNER JOIN db_timeshift t ON f.interview_company = t.timeshift_id WHERE f.follow_type = '0' AND f.applfollow_id = '$empl_id' AND f.fol_status = '0' AND f.fol_approved = 'Y'";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);

        $month = date("F", strtotime($date));
 
        $pdf->SetFont('Arial', 'B', 10);
        
        $pdf->Cell(10, 5, "");
        $pdf->Cell(110, 8, "SUCCESS HUMAN RESOURCE CENTRE PTE LTD",1, 0, C); 
        
        $pdf->SetTextColor(255);
        $pdf->Cell(60, 8, "TIMESHEET",1, 1, C, true); 
        
        $pdf->SetTextColor(000);
        $pdf->Cell(10, 0, "");
        $pdf->MultiCell(110, 5, "SUCCESS RESOURCE CENTRE PTE LTD \n 1 Sophia Road #06-23/29 Peace Centre Singapore 228149 \n Tel: 63373183   Fax: 63370329 / 63370425 \n Website: www.successhrc.com.sg", 1, C);
        
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(0,0,255);
        $pdf->MultiCell(50,-20,"",0,20);
        $pdf->SetXY(130, 18);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(60, 5, $month,1, 1, L); 
        $pdf->SetXY(130, 23);
        $pdf->Cell(60, 5, $row['partner_name'],1, 1, L); 
        $pdf->SetXY(130, 28);
        $pdf->Cell(60, 5, $row['fol_position_offer'],1, 1, L);
        $pdf->SetXY(130, 33);
        $pdf->Cell(60, 5, $row['timeshift_department'],1, 1, L); 
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(000);
        $pdf->Cell(10, 5, "");
        $pdf->Rect(20,38,170,8);
        $pdf->Cell(20, 8, " Full Name :"); 
        
        $pdf->SetTextColor(0,0,255);
        $pdf->Cell(89, 8, $row['applicant_name'],0,1); 
        $pdf->SetTextColor(000);
        
        
        $pdf->Cell(10, 5, "");
        $pdf->Rect(20,46,170,8);
        $pdf->Cell(20, 8, " Mobile No :",0,0);    
        
        $pdf->SetTextColor(0,0,255);
        $pdf->Cell(89, 8, $row['applicant_mobile']);  
        $pdf->SetTextColor(000);
        
        $pdf->Cell(20, 8, " NRIC No :",0,0);
        
        $pdf->SetTextColor(0,0,255);
        $pdf->Cell(89, 8, $row['applicant_nric'],0,1); 
        $pdf->SetTextColor(000);
        
        $sql3 = "SELECT t.timeshift_start_time, t.timeshift_end_time FROM db_timeshift t INNER JOIN db_followup f ON f.fol_department = t.timeshift_id WHERE f.follow_type = '0' AND f.applfollow_id = '$empl_id' AND f.fol_approved = 'Y'";
        $query3 = mysql_query($sql3);
        $row3 = mysql_fetch_array($query3);
        
        $pdf->Rect(20,54,170,10);
        $pdf->Cell(18, 5, "");
        $pdf->Cell(109, 10, " Official Working Hours : Mon to Fri : " .$row3['timeshift_start_time'] ." to " .$row3['timeshift_end_time']."  Sat :_________ to _________ ",0,1);     
        
        $x = 20;
        $y = 64;
        $z = 20;
        $pdf->Cell(10, 5, "");
        $text_array = array("DATE","DAY","TIME IN","TIME OUT");
        for($i = 0; $i <7; $i++){
            $pdf->Rect($x,$y,20,8);
            $pdf->Cell($z,8,$text_array[$i],0,0,C);
            $x = $x + 20;
        }
        //$pdf->Cell(-57, 8, "");
        $pdf->SetXY(103,65);
        $pdf->MultiCell(20, 3,"LUNCH \nHOURS", C);
        $pdf->SetXY(122,65);
        $pdf->MultiCell(20, 3,"NORMAL \n HOURS", C);
        $pdf->SetXY(140,65);
        $pdf->MultiCell(22, 3,"OVERTIME \n  HOURS", C);
        $pdf->SetXY(165,65);
        $pdf->MultiCell(22, 3,"INTERNAL \n    USE", C);        
        
        $pdf->Rect(160,$y,30,8);
        //$pdf->SetY(70,true);        

        $x = 20;
        $l = 7;
        
        $pdf->SetTextColor(0,0,255);  
        
        $pdf->SetY(71);        
        $lastDay = date("Y-m-t", strtotime($date));
        $midDay = date('Y-m-10', strtotime($date));
        $midDay2 = date('Y-m-11', strtotime($date));
        $midDay3 = date('Y-m-20', strtotime($date));
        $midDay4 = date('Y-m-21', strtotime($date));
        $firstDay = date('Y-m-01', strtotime($date));
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(12, 5, "");
        
        $timeIn = new DateTime($row3['timeshift_start_time']);
        $timeOut = new DateTime($row3['timeshift_end_time']);
        $normal_hour = $timeIn->diff($timeOut);
        $hour = (double)$normal_hour->format('%h');
        $hour = $hour-1;
        $minute = $normal_hour->format('%i');
        if($minute == "0")
        {
            $minute = "";
        }else{
            $minute = $minute." M";
        }
        
        
        
        
        $normal_h = $hour." H ".$minute;
        
        $sql2 = "SELECT * FROM db_attendance WHERE attendance_empl = '$empl_id' AND attendance_date_start BETWEEN '$firstDay' AND '$midDay' Order By attendance_date_start ASC";
        $query2 = mysql_query($sql2);

        while($row2 = mysql_fetch_array($query2)){
            $day = date('l', strtotime($row2['attendance_date_start']));
            $pdf->Cell($x,$l,$row2['attendance_date_start']);
            $pdf->Cell($x,$l,$day);
            $pdf->Cell($x,$l,$row2['attendance_timein']);
            $pdf->Cell($x,$l,$row2['attendance_timeout']);
            $pdf->Cell($x,$l,$row2['attendance_lunch_hour']); 
            $pdf->Cell($x,$l,$normal_h);      
            $pdf->Cell($x,$l,$row2['attendance_ot_hour']);     
            $pdf->Cell(-140, 5, "");
            $x = 20;
            $l = $l + 10;
            $total_normal_hour = $total_normal_hour + $hour;
            $total_normal_minute = $total_normal_minute + $minute;
        }        
        
        $x = 20;
        $l = 7;
        
        $pdf->SetY(121);
        $pdf->Cell(12, 5, "");
        $sql2 = "SELECT * FROM db_attendance WHERE attendance_empl = '$empl_id' AND attendance_date_start BETWEEN '$midDay2' AND '$midDay3' Order By attendance_date_start ASC";
        $query2 = mysql_query($sql2);
        while($row2 = mysql_fetch_array($query2)){
            $day = date('l', strtotime($row2['attendance_date_start']));
            $pdf->Cell($x,$l,$row2['attendance_date_start']);
            $pdf->Cell($x,$l,$day);
            $pdf->Cell($x,$l,$row2['attendance_timein']);
            $pdf->Cell($x,$l,$row2['attendance_timeout']);
            $pdf->Cell($x,$l,$row2['attendance_lunch_hour']);
            $pdf->Cell($x,$l,$normal_h);        
            $pdf->Cell($x,$l,$row2['attendance_ot_hour']);     
            $pdf->Cell(-140, 5, "");
            $x = 20;
            $l = $l + 10;
            $total_normal_hour = $total_normal_hour + $hour;
            $total_normal_minute = $total_normal_minute + $minute;
        }        

        $x = 20;
        $l = 7;        
        $pdf->SetY(171);
        $pdf->Cell(12, 5, "");
        $sql2 = "SELECT * FROM db_attendance WHERE attendance_empl = '$empl_id' AND attendance_date_start BETWEEN '$midDay4' AND '$lastDay' Order By attendance_date_start ASC";
        $query2 = mysql_query($sql2);
        $i = 1;
        while($row2 = mysql_fetch_array($query2)){
            if($i == 11){
                $l = 1;
                $pdf->SetY(224);
                $pdf->Cell(12, 5, "");
                $pdf->Cell($x,$l,$row2['attendance_date_start']);
                $pdf->Cell($x,$l,$day);
                $pdf->Cell($x,$l,$row2['attendance_timein']);
                $pdf->Cell($x,$l,$row2['attendance_timeout']);
                $pdf->Cell($x,$l,$row2['attendance_lunch_hour']);
                $pdf->Cell($x,$l,$normal_h);        
                $pdf->Cell($x,$l,$row2['attendance_ot_hour']); 
            }
            else{
                $day = date('l', strtotime($row2['attendance_date_start']));
                $pdf->Cell($x,$l,$row2['attendance_date_start']);
                $pdf->Cell($x,$l,$day);
                $pdf->Cell($x,$l,$row2['attendance_timein']);
                $pdf->Cell($x,$l,$row2['attendance_timeout']);
                $pdf->Cell($x,$l,$row2['attendance_lunch_hour']);
                $pdf->Cell($x,$l,$normal_h);        
                $pdf->Cell($x,$l,$row2['attendance_ot_hour']);     
                $pdf->Cell(-140, 5, "");
                $x = 20;
                $l = $l + 10;                
            }
            $total_normal_hour = $total_normal_hour + $hour;
            $total_normal_minute = $total_normal_minute + $minute;
            $i++;
        }
        
        $total_normal_hour = $total_normal_hour + (double)date('H', mktime(0,$total_normal_minute));
        $total_normal_minute = date('i', mktime(0,$total_normal_minute));
        if($total_normal_minute == "00")
        {
            $total_normal_minute = "";
        }else{
            $total_normal_minute = $total_normal_minute." M";
        }
        
        
        $pdf->SetY(227);
        $pdf->Cell(112, 5, "");
        $sql4 = "SELECT SUM(attendance_ot_hour) AS total FROM db_attendance WHERE attendance_empl = '$empl_id' AND attendance_date_start BETWEEN '$firstDay' AND '$lastDay'";
        $query4 = mysql_query($sql4);
        $row4 = mysql_fetch_array($query4);
        $pdf->Cell(20, 5, $total_normal_hour. " H ".$total_normal_minute);
        $pdf->Cell(20, 5, $row4['total']. " H");
        
        $pdf->SetTextColor(000);
        $x = 20;
        $y = 72;
        
        for($a = 0; $a <31; $a++){
            for($b = 0; $b <7; $b++){
                $pdf->Rect($x,$y,20,5);
                $x = $x + 20;
            }
            $pdf->Rect(160,$y,30,5);
            $y = $y + 5;
            $x = 20;
        }
        $pdf->Rect(120,$y,20,5);
        $pdf->Rect(140,$y,20,5);
        $pdf->Rect(160,$y,30,5);
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(80,227);
        $pdf->Cell(40,5,"Grand Total :",1,0,C);

        $pdf->SetXY(20,260);
        $pdf->Cell(50,8,"Supervisor's Name & Signature",T,0,C);
        $pdf->SetXY(100,260);
        $pdf->Cell(30,8,"Company Stamp",T,0,C);
        $pdf->SetXY(160,260);
        $pdf->Cell(30,8,"Date",T,0,C);
        
        $pdf->Output();
    }    
    public function printTimesheet(){
        //$pdf = new FPDF('P', 'pt', 'Letter');
        //$pdf->AddPage(); 
        
        $pdf = new FPDI();

        $pdf->AddPage();    
       
        
        $empl_id = escape($_REQUEST['empl_id']);
        $date = $_REQUEST['Date'];
        
        $sql = "SELECT f.*, p.partner_name, a.applicant_name, a.applicant_mobile, a.applicant_nric, t.timeshift_department FROM db_followup f INNER JOIN db_partner p ON p.partner_id = f.interview_company INNER JOIN db_applicant a ON a.applicant_id = f.applfollow_id INNER JOIN db_timeshift t ON f.interview_company = t.timeshift_id WHERE f.follow_type = '0' AND f.applfollow_id = '$empl_id' AND f.fol_status = '0' AND f.fol_approved = 'Y'";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);

        $month = date("F", strtotime($date));
 
        $pdf->SetFont('Arial', 'B', 10);
        
        $pdf->Cell(10, 5, "");
        $pdf->Cell(110, 8, "SUCCESS HUMAN RESOURCE CENTRE PTE LTD",1, 0, C); 
        
        $pdf->SetTextColor(255);
        $pdf->Cell(60, 8, "TIMESHEET",1, 1, C, true); 
        
        $pdf->SetTextColor(000);
        $pdf->Cell(10, 0, "");
        $pdf->MultiCell(110, 5, "SUCCESS RESOURCE CENTRE PTE LTD \n 1 Sophia Road #06-23/29 Peace Centre Singapore 228149 \n Tel: 63373183   Fax: 63370329 / 63370425 \n Website: www.successhrc.com.sg", 1, C);
        
        $x=$pdf->GetX();
        $y=$pdf->GetY();
        
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(0,0,255);
        $pdf->MultiCell(50,-20,"",0,20);
        $pdf->SetXY(130, 18);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(60, 5, $month,1, 1, L); 
        $pdf->SetXY(130, 23);
        $pdf->Cell(60, 5, $row['partner_name'],1, 1, L); 
        $pdf->SetXY(130, 28);
        $pdf->Cell(60, 5, $row['fol_position_offer'],1, 1, L);
        $pdf->SetXY(130, 33);
        $pdf->Cell(60, 5, $row['timeshift_department'],1, 1, L); 
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(000);
        $pdf->Cell(10, 5, "");
        $pdf->Rect(20,38,170,8);
        $pdf->Cell(20, 8, " Full Name :"); 
        
        $pdf->SetTextColor(0,0,255);
        $pdf->Cell(89, 8, $row['applicant_name'],0,1); 
        $pdf->SetTextColor(000);
        
        
        $pdf->Cell(10, 5, "");
        $pdf->Rect(20,46,170,8);
        $pdf->Cell(20, 8, " Mobile No :",0,0);    
        
        $pdf->SetTextColor(0,0,255);
        $pdf->Cell(89, 8, $row['applicant_mobile']);  
        $pdf->SetTextColor(000);
        
        $pdf->Cell(20, 8, " NRIC No :",0,0);
        
        $pdf->SetTextColor(0,0,255);
        $pdf->Cell(89, 8, $row['applicant_nric'],0,1); 
        $pdf->SetTextColor(000);
        
        $sql3 = "SELECT t.timeshift_start_time, t.timeshift_end_time FROM db_timeshift t INNER JOIN db_followup f ON f.fol_department = t.timeshift_id WHERE f.follow_type = '0' AND f.applfollow_id = '$empl_id' AND f.fol_status = '0' AND f.fol_approved = 'Y'";
        $query3 = mysql_query($sql3);
        $row3 = mysql_fetch_array($query3);
        
        $pdf->Rect(20,54,170,10);
        $pdf->Cell(18, 5, "");
        $pdf->Cell(109, 10, " Official Working Hours : Mon to Fri : " .$row3['timeshift_start_time'] ." to " .$row3['timeshift_end_time']."  Sat :_________ to _________ ",0,1);     
        
        $x = 20;
        $y = 64;
        $z = 20;
        $pdf->Cell(10, 5, "");
        $text_array = array("DATE","DAY","TIME IN","TIME OUT");
        for($i = 0; $i <7; $i++){
            $pdf->Rect($x,$y,20,8);
            $pdf->Cell($z,8,$text_array[$i],0,0,C);
            $x = $x + 20;
        }
        //$pdf->Cell(-57, 8, "");
        $pdf->SetXY(103,65);
        $pdf->MultiCell(20, 3,"LUNCH \nHOURS", C);
        $pdf->SetXY(122,65);
        $pdf->MultiCell(20, 3,"NORMAL \n HOURS", C);
        $pdf->SetXY(140,65);
        $pdf->MultiCell(22, 3,"OVERTIME \n  HOURS", C);
        $pdf->SetXY(165,65);
        $pdf->MultiCell(22, 3,"INTERNAL \n    USE", C);        
        
        $pdf->Rect(160,$y,30,8);
        //$pdf->SetY(70,true);        

        $x = 20;
        $l = 7;
        
        $pdf->SetTextColor(0,0,255);  
        
        $pdf->SetY(72);        
        $lastDay = date("Y-m-t", strtotime($date));
        $firstDay = date('Y-m-01', strtotime($date));
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(12, 5, "");
        
        $timeIn = new DateTime($row3['timeshift_start_time']);
        $timeOut = new DateTime($row3['timeshift_end_time']);
//        $normal_hour = $timeIn->diff($timeOut);
//        $hour = (double)$normal_hour->format('%h');
//        $hour = $hour-1;
//        $minute = $normal_hour->format('%i');
//        if($minute == "0")
//        {
//            $minute = "";
//        }else{
//            $minute = $minute." M";
//        }
// 
//        $normal_h = $hour." H ".$minute;
        
        $sql2 = "SELECT * FROM db_attendance WHERE attendance_empl = '$empl_id' AND attendance_date_start BETWEEN '$firstDay' AND '$lastDay' Order By attendance_date_start ASC";
        $query2 = mysql_query($sql2);

        while($row2 = mysql_fetch_array($query2)){
            
            $normal_hour = $timeIn->diff($timeOut);
            $hour = (double)$normal_hour->format('%h');
            $hour = $hour-$row2['attendance_lunch_hour'];
            $minute = $normal_hour->format('%i');
            if($minute == "0")
            {
                $minute = "";
            }else{
                $minute = $minute." M";
            }

            $normal_h = $hour." H ".$minute;
            
            $day = date('l', strtotime($row2['attendance_date_start']));
            $pdf->Cell(20,5,$row2['attendance_date_start']);
            $pdf->Cell(20,5,$day);
            $pdf->Cell(20,5,$row2['attendance_timein']);
            $pdf->Cell(20,5,$row2['attendance_timeout']);
            $pdf->Cell(20,5,$row2['attendance_lunch_hour']); 
            $pdf->Cell(20,5,$normal_h);      
            $pdf->Cell(20,5,$row2['attendance_ot_hour'],0,1);     
            $pdf->Cell(12, 5, "");
            $x = 20;
            $l = $l + 10;
            $total_normal_hour = $total_normal_hour + $hour;
            $total_normal_minute = $total_normal_minute + $minute;
        }        
        
        
        $total_normal_hour = $total_normal_hour + (double)date('H', mktime(0,$total_normal_minute));
        $total_normal_minute = date('i', mktime(0,$total_normal_minute));
        if($total_normal_minute == "00")
        {
            $total_normal_minute = "";
        }else{
            $total_normal_minute = $total_normal_minute." M";
        }
        
        
        $pdf->SetY(227);
        $pdf->Cell(112, 5, "");
        $sql4 = "SELECT SUM(attendance_ot_hour) AS total FROM db_attendance WHERE attendance_empl = '$empl_id' AND attendance_date_start BETWEEN '$firstDay' AND '$lastDay'";
        $query4 = mysql_query($sql4);
        $row4 = mysql_fetch_array($query4);
        $pdf->Cell(20, 5, $total_normal_hour. " H ".$total_normal_minute);
        $pdf->Cell(20, 5, $row4['total']. " H");
        
        $pdf->SetTextColor(000);
        $x = 20;
        $y = 72;
        
        for($a = 0; $a <31; $a++){
            for($b = 0; $b <7; $b++){
                $pdf->Rect($x,$y,20,5);
                $x = $x + 20;
            }
            $pdf->Rect(160,$y,30,5);
            $y = $y + 5;
            $x = 20;
        }
        $pdf->Rect(120,$y,20,5);
        $pdf->Rect(140,$y,20,5);
        $pdf->Rect(160,$y,30,5);
        
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetXY(80,227);
        $pdf->Cell(40,5,"Grand Total :",1,0,C);

        $pdf->SetXY(20,260);
        $pdf->Cell(50,8,"Supervisor's Name & Signature",T,0,C);
        $pdf->SetXY(100,260);
        $pdf->Cell(30,8,"Company Stamp",T,0,C);
        $pdf->SetXY(160,260);
        $pdf->Cell(30,8,"Date",T,0,C);
        
        $pdf->Output();
    }    
}
?>