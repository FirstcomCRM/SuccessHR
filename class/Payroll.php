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
class Payroll {

    public function Payroll(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('payroll_outlet','payroll_department','payroll_salary_date','payroll_startdate',
                             'payroll_enddate','payroll_total_working_days','payroll_status');
        $table_value = array($this->payroll_outlet,$this->payroll_department,format_date_database($this->payroll_salary_date),format_date_database($this->payroll_startdate),
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
        include_once 'class/Empl.php';
        $e = new Empl();
        
        $this->return_array = 1;
        $b = $this->previewPayslip();

        foreach($b as $c){

            $table_field = array('payline_payroll_id','payline_empl_id','payline_department_id','payline_salary',
                                 'payline_additional','payline_deductions','payline_cpf_employer','payline_cpf_employee',
                                 'payline_levy_employee','payline_netpay');
            $table_value = array($this->payroll_id,$c['empl_id'],$c['department_id'],$c['empl_salary'],
                                 $c['empl_addtional'],$c['empl_deductions'],$c['cpf_employer'],$c['cpf_employee'],
                                 $c['Levy_employee'],$c['payline_netpay']);
            $remark = "Insert Payroll Lines.";
            if($this->save->SaveData($table_field,$table_value,'db_payline','payline_id',$remark)){
                $this->payline_id = $this->save->lastInsert_id;
                
                $sql = "SELECT * FROM db_additional INNER JOIN db_additionaltype ON additional_type = additionaltype_id WHERE additional_empl_id = '$c[empl_id]' AND additional_date BETWEEN '$this->payroll_startdate' AND '$this->payroll_enddate' AND additional_empl_type = '0' AND additional_status = '1'";
                $query = mysql_query($sql);
                
                $payitem_table_field = array('payitem_payline_id','payitem_type','payitem_remark','payitem_amount');
                $payitem_table_value = array($this->payline_id, 1, 'BASIC PAY', $c['empl_salary']);
                
                $this->save->SaveData($payitem_table_field,$payitem_table_value,'db_payitem','payitem_id',$remark);

                $sql2 = "SELECT payline_levy_employee, payline_cpf_employee FROM db_payline WHERE payline_id = '$this->payline_id'";
                $query2 = mysql_query($sql2);
                $row2 = mysql_fetch_array($query2);
                
                $payitemD_table_field = array('payitem_payline_id','payitem_type','payitem_remark','payitem_amount');
                $payitemD_table_value = array($this->payline_id, 0, 'UNPAID LEAVE', $row2['payline_levy_employee']);
                $this->save->SaveData($payitemD_table_field,$payitemD_table_value,'db_payitem','payitem_id',$remark);
                
                
                while($row = mysql_fetch_array($query)){
                    $payitem_table_value = array($this->payline_id, 1, strtoupper($row['additionaltype_code']), $row['additional_amount']);
                    $remark = "Insert Pay Item.";
                    $this->save->SaveData($payitem_table_field,$payitem_table_value,'db_payitem','payitem_id',$remark);
                }
                
                $sql = "SELECT * FROM db_deductions WHERE deductions_empl_id = '$c[empl_id]' AND deductions_date BETWEEN '$this->payroll_startdate' AND '$this->payroll_enddate' AND deduction_empl_type = '0' AND deductions_status = '1'";
                $query = mysql_query($sql);
                $payitem_table_field = array('payitem_payline_id','payitem_type','payitem_remark','payitem_amount');
                while($row = mysql_fetch_array($query)){
                    $payitem_table_value = array($this->payline_id, 0, strtoupper($row['deductions_title']), $row['deductions_amount']);
                    $remark = "Insert Pay Item.";
                    $this->save->SaveData($payitem_table_field,$payitem_table_value,'db_payitem','payitem_id',$remark);
                }                
                
//                if($c['empl_id'] == '18'){
//                    echo $this->summary["calculateforcdacadd_amt"];die;
//                }
//                $deductions_sql = $this->getDeductionsSql($salary_info,$c['empl_id'],$this->payroll_startdate,$this->payroll_enddate);
//
//                $this->createPayitem($deductions_sql,0,'deductions_amt');
                
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
                <h3 class="box-title"><?php if($this->payroll_id > 0){ echo "Update Payroll";}else{ echo "Apply New Payroll";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='payroll.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='payroll.php?action=createForm'">Create New</button>
                <?php }?>
              </div>

                <form id = 'payroll_form' class="form-horizontal" action = 'payroll.php?action=create' method = "POST">
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
                      <label for ="payroll_department" class="col-sm-2 control-label">Departments</label>
                      <div class="col-sm-2">
                        <select class="form-control select2" id="payroll_department" name="payroll_department" <?php echo $disabled;?>>
                          <?php echo $this->departmentCrtl;?>
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
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->payroll_id;?>" name = "payroll_id"/>
                    <?php 
                    if($this->payroll_id > 0){
                        $prm_code = "update";
                    }else{
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
        <form action = 'payroll.php' method = "POST" id = 'varify_form' >
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
    });
    var issend = false;
    function getPayslipsListing(){
        if(issend){
            alert("Please wait...");
            return false;
        }

        issend = true;


        var data = "payroll_outlet="+$('#payroll_outlet').val();
            data += "&payroll_department="+$('#payroll_department').val();
            data += "&payroll_salary_date="+$('#payroll_salary_date').val();
            data += "&payroll_startdate="+$('#payroll_startdate').val();
            data += "&payroll_enddate="+$('#payroll_enddate').val();
            data += "&payroll_total_working_days="+$('#payroll_total_working_days').val();
            data += "&action=previewPayslip";

        $.ajax({ 
            type: 'POST',
            url: 'payroll.php',
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
                         + "<td style = 'text-align:right'>" + changeNumberFormat(RoundNum(jsonObj[i]['salary'],2)) + "</td>"
                         + "<td style = 'text-align:right'>" + changeNumberFormat(RoundNum(jsonObj[i]['empl_addtional'],2)) + "</td>"
                         + "<td style = 'text-align:right'>" + changeNumberFormat(RoundNum(jsonObj[i]['empl_deductions'],2)) + "</td>"
                         + "<td style = 'text-align:right'>" + changeNumberFormat(RoundNum(jsonObj[i]['cpf_employee'],2)) + "</td>"
                         + "<td style = 'text-align:right'>" + changeNumberFormat(RoundNum(((parseFloat(jsonObj[i]['empl_addtional']) + parseFloat(jsonObj[i]['salary'])) - (parseFloat(jsonObj[i]['empl_deductions']) + parseFloat(jsonObj[i]['cpf_employee']))),2)) + "</td>"
                         + "<td style = 'text-align:right'><a href = '#' class='btn btn-info astatus' payroll_id = '' empl_id = '" + jsonObj[i]['empl_id'] + "' data-toggle='modal' data-target='#sstatusModal' target = '_blank'> View </a></td>"
                         + "</tr>";
                         grand_amt = parseFloat(grand_amt) + ((parseFloat(jsonObj[i]['empl_addtional']) + parseFloat(jsonObj[i]['salary'])) - (parseFloat(jsonObj[i]['empl_deductions']) + parseFloat(jsonObj[i]['cpf_employee'])));
                         
                 b++;
               }
               row = row + "<tr class = 'payslipslisting'><td colspan = '7' style = 'text-align:right;font-weight:bold;font-size:16px;'> Total : </td><td style = 'text-align:right;font-weight:bold;font-size:16px;' >" + changeNumberFormat(RoundNum(grand_amt,2)) + "</td><td></td></tr>";
               }else{
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
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="payroll.php">Payroll Month Listing</a></li>
            <li class="active">Payroll Listing</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    
                  <h3 class="box-title">Payroll Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>

                <button class="btn btn-primary pull-right" onclick = "window.location.href='payroll.php?action=createForm'">Create New <i class="fa fa-fw fa-plus"></i></button>
               
                <button class="btn btn-primary pull-right btn-success" style = 'margin-right:5px;' id = "confirm_btn">Confirm <i class="fa fa-fw fa-thumbs-up"></i></button> 

                 <button class="btn btn-primary pull-right btn-warning" style = 'margin-right:5px;' id = "print_btn">Print <i class="fa fa-fw fa-print"></i></button> 
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="payroll_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:2%'><input type = "checkbox" name = 'payroll_checkbox' class = 'payroll_checkbox_parent' /></th>  
                        <th style = 'width:5%'>No</th>
<!--                        <th style = 'width:13%'>Company</th>
                        --><th style = 'width:10%'>Department</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:10%'>Date From</th>
                        <th style = 'width:10%'>Date To</th>
                        <th style = 'width:10%'>Working Days</th>
                        <th style = 'width:8%'>Confirmed</th>
                        <th style = 'width:13%'>Status</th>
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
                             
                              WHERE l.payroll_id > 0 AND l.payroll_status = '1' AND l.payroll_client < 1 $wherestring
                              ORDER BY l.payroll_startdate DESC";

                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><input type = "checkbox" name = 'payroll_checkbox' class = 'payroll_checkbox_child' value = '<?php echo $row['payroll_id'];?>'/></td>
                            <td><?php echo $i;?></td>
<!--                            <td><?php if($row['payroll_outlet'] == 0){ echo 'All Company'; }?></td>-->
                            <td><?php 
                                if($row['payroll_department'] == 0){ 
                                    echo 'All Department'; 
                                }else{
                                    echo getDataCodeBySql('department_code','db_department'," WHERE department_id = '{$row['payroll_department']}'", $orderby);
                                }
                            ?>
                            </td>
                            <td><?php echo format_date($row['payroll_salary_date']);?></td>
                            <td><?php echo format_date($row['payroll_startdate']);?></td>
                            <td><?php echo format_date($row['payroll_enddate']);?></td>
                            <td><?php echo $row['payroll_total_working_days'];?></td>
                            <td><?php if($row['payroll_isapproved'] == 1){ echo "<b><font color = 'green' >YES</font></b>";}else{ echo "<b><font color = 'red'>NO</font></b>";}?></td>
                            <td>
                                <?php 
                                if($row['payroll_status'] == 1){
                                    echo 'Active';
                                }
                                ?>
                            </td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'payroll.php?action=edit&payroll_id=<?php echo $row['payroll_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){

                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('payroll.php?action=delete&payroll_id=<?php echo $row['payroll_id'];?>','Confirm Delete?')">Delete</button>
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
<!--                        <th style = 'width:13%'>Company</th>
                        --><th style = 'width:10%'>Department</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:10%'>Date From</th>
                        <th style = 'width:10%'>Date To</th>
                        <th style = 'width:10%'>Working Days</th>
                        <th style = 'width:8%'>Confirmed</th>
                        <th style = 'width:13%'>Status</th>
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
                url: 'payroll.php',
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
            window.open("payslip-print.php?action=adminprint&payroll_id="+payroll_id, "_blank");


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
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Payroll Month Listing</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Payroll Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                 
                <button class="btn btn-primary pull-right" onclick = "window.location.href='payroll.php?action=createForm'">Create New <i class="fa fa-fw fa-plus"></i></button>
               
                <button class="btn btn-primary pull-right btn-success" style = 'margin-right:5px;' id = "confirm_btn">Confirm <i class="fa fa-fw fa-thumbs-up"></i></button> 
                <?php }?>
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
                       
                      $sql = "SELECT l.*,LEFT(payroll_salary_date,7) as month
                                  
                              FROM db_payroll l 
                              WHERE l.payroll_id > 0 AND l.payroll_status = '1' AND l.payroll_client < '1' $wherestring
                              GROUP BY YEAR(payroll_salary_date), MONTH(payroll_salary_date)
                              ORDER BY l.payroll_startdate DESC";

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
                            $sql2 = "SELECT SUM(pl.payline_netpay) as total
                              FROM db_payline pl
                              INNER JOIN db_payroll pyl ON pyl.payroll_id = pl.payline_payroll_id
                              WHERE pyl.payroll_status = '1' AND LEFT(pyl.payroll_salary_date,7) = '{$row['month']}' AND pyl.payroll_client = '0'";
                             $query2 = mysql_query($sql2);
                             if($row2 = mysql_fetch_array($query2)){
                                 echo "$ ".num_format($row2['total']);
                             }else{
                                 echo "$ ".num_format(0);
                             }
                            
                            ?>
                            </td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'payroll.php?action=listing&filter_month_date=<?php echo $row['month'];?>'">View</button>
                                <?php }?>

                                 
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
                url: 'payroll.php',
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
                               <a class="btn btn-primary btn-info astatus" data-toggle="modal" data-target="#sstatusModal" payroll_id = '<?php echo $row['payroll_id'];?>' empl_id = '<?php echo $_SESSION['empl_id'];?>' href = "#">View</button>
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
        <form action = 'payroll.php' method = "POST">
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
            $sql = "SELECT pl.*,empl.empl_name,dp.department_code,p.payroll_startdate,p.payroll_enddate
                   FROM db_payline pl
                   INNER JOIN db_payroll p ON p.payroll_id = pl.payline_payroll_id
                   LEFT JOIN db_empl empl ON empl.empl_id = pl.payline_empl_id
                   LEFT JOIN db_department dp ON dp.department_id = empl.empl_department
                   WHERE payline_id > 0 AND payline_payroll_id = '$this->payroll_id' ";

            $query = mysql_query($sql);
            $i=0;
            while($row = mysql_fetch_array($query)){
                $i++;
                $total = ($row['payline_salary'] + $row['payline_additional']) - ($row['payline_deductions'] + $row['payline_cpf_employee']);
            ?>
                <tr id = "line_<?php echo $line;?>" class="tbl_grid_odd" line = "<?php echo $line;?>">
                    <td style="width:5%;padding-left:5px"><?php echo $i;?></td>
                    <td style="padding-left:5px"><?php echo $row['empl_name'];?></td>
                    <td style="padding-left:5px"><?php echo $row['department_code'];?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo "$ ". num_format($row['payline_salary']);?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo "$ ". num_format($row['payline_additional']);?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo "$ ". num_format($row['payline_deductions']);?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo "$ ". num_format($row['payline_cpf_employee']);?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo "$ ". num_format($total);?></td>
                    <td style="padding-left:5px" align = 'right'><a href = '#' class='btn btn-info astatus' data-toggle="modal" data-target="#sstatusModal" payroll_id = '<?php echo $this->payroll_id;?>' empl_id = '<?php echo $row['payline_empl_id'];?>' target = '_blank'> View </a></td>
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
        include_once 'class/Empl.php';
        include_once 'class/Cpf.php';
        
        $e = new Empl();
        $c = new Cpf();
        //create filter
        if($this->payroll_outlet > 0){
            $wherestring = " AND empl.empl_outlet = '$this->payroll_outlet'"; 
        }
        if($this->payroll_department > 0){
            $wherestring .= " AND empl.empl_department = '$this->payroll_department'"; 
        }
        //convert date to sql
        $this->payroll_startdate = format_date_database($this->payroll_startdate);
        $this->payroll_enddate = format_date_database($this->payroll_enddate);
        
        //subsql for get employee salary at between payroll start date & end date, and get latest salary data.
        $subsql = "SELECT emplsalary_amount FROM db_emplsalary WHERE  emplsalary_empl_id = empl.empl_id AND emplsalary_status = '1' order BY emplsalary_date DESC,emplsalary_id DESC limit 0,1";
        
        //subsql2 for get employee addtional at between payroll start date & end date, and get latest data.
        $subsql2 = "SELECT SUM(claims_amount) FROM db_claims WHERE claims_date BETWEEN '$this->payroll_startdate' AND '$this->payroll_enddate' AND claims_empl_id = empl.empl_id and claims_status = 1 AND claims_approvalstatus = 'Approved'";
        

        $sql = "SELECT empl.empl_id,empl.empl_name,ed.department_code,ed.department_id,COALESCE(($subsql),0) as empl_salary,empl.empl_birthday,empl.empl_levy_amt,empl.empl_iscpf,
                COALESCE(($subsql2),0) as empl_addtional
                FROM db_empl empl
                LEFT JOIN db_department ed ON ed.department_id = empl.empl_department
                LEFT JOIN db_emplpass ep ON ep.emplpass_id = empl.empl_emplpass
                WHERE empl.empl_status = '1' $wherestring AND empl.empl_client < 1 AND empl_group != '6'";

        $query = mysql_query($sql);
        $i = 0;    
        while($row = mysql_fetch_array($query)){
            $empl_salary_array = $e->fetchSalaryDetail(" AND emplsalary_empl_id = '{$row['empl_id']}' ","ORDER BY emplsalary_date DESC,emplsalary_id DESC","limit 0,1",2);
            $b[$i]['empl_name'] = $row['empl_name'];
            $b[$i]['empl_id'] = $row['empl_id'];
            $b[$i]['department_code'] = $row['department_code'];
            $b[$i]['department_id'] = $row['department_id'];
            $b[$i]['empl_salary'] = $empl_salary_array['emplsalary_amount'];
            
            $b[$i]['empl_addtional'] = $this->getAddtionalSalary($row['empl_id'],$this->payroll_startdate,$this->payroll_enddate);
//            $b[$i]['empl_deductions'] = $this->getDeductionsSalary($row['empl_id'],$this->payroll_startdate,$this->payroll_enddate,$empl_salary_array);
            
            $b[$i]['empl_addtional_withoutclaim'] = $this->getAddtionalSalary($row['empl_id'],$this->payroll_startdate,$this->payroll_enddate,true,true);
//            $b[$i]['empl_deductions_withoutdeduct'] = $this->getDeductionsSalary($row['empl_id'],$this->payroll_startdate,$this->payroll_enddate,$empl_salary_array,true,true);


            $deductions_array = $this->getDeductionsSalary($row['empl_id'],$this->payroll_startdate,$this->payroll_enddate, $empl_salary_array);


            $b[$i]['empl_deductions'] = $deductions_array['deductions_total_amt'];
            $b[$i]['empl_deductions_without_leave'] = $deductions_array['deductions_amt'];
            $b[$i]['Levy_employee'] = $deductions_array['leave_amt'];
            $b[$i]['empl_deductions_with_cpf'] = $deductions_array['deductions_with_cpf'];            

       
            $from = new DateTime($row['empl_birthday']);
            $to = new DateTime('today');
            $empl_age = $from->diff($to)->y;

            
            
            if($row['empl_iscpf'] == 1){
                $cpf_array = $c->fetchCpfDetail(" AND cpf_from_age <= '$empl_age' AND cpf_to_age >= '$empl_age' ", "", "",2);

                $total_cpfamt = ROUND(($empl_salary_array['emplsalary_amount'] + $b[$i]['empl_addtional_withoutclaim'] - ($b[$i]['empl_deductions_with_cpf'])) * (($cpf_array['cpf_employee_percent']/100) + ($cpf_array['cpf_employer_percent']/100))) ;
                
                $b[$i]['cpf_employee'] = floor(($empl_salary_array['emplsalary_amount'] + $b[$i]['empl_addtional_withoutclaim'] - ($b[$i]['empl_deductions_with_cpf']))*($cpf_array['cpf_employee_percent']/100));// employee is round down.
                $b[$i]['cpf_employer'] = $total_cpfamt - $b[$i]['cpf_employee'];
                $b[$i]['cpf_employee_percent'] = $cpf_array['cpf_employee_percent'];
                $b[$i]['cpf_employer_percent'] = $cpf_array['cpf_employer_percent'];
            }
            else{
                $b[$i]['cpf_employee'] = 0;
                $b[$i]['cpf_employer'] = 0;
                $b[$i]['cpf_employee_percent'] = 0;
                $b[$i]['cpf_employer_percent'] = 0;
            }
            

                
            $b[$i]['payline_netpay'] = (($empl_salary_array['emplsalary_amount'] + $b[$i]['empl_addtional']) - $b[$i]['empl_deductions']) - $b[$i]['cpf_employee'];
            
            $b[$i]['salary'] = $empl_salary_array['emplsalary_amount'];
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
    public function getAddtionalSalary($empl_id,$datefrom,$dateto,$notinclude_claims = false,$include_deductcpf){
        if($notinclude_claims){
            $wherestring = " AND claims_id = '-1'";
        }
        if($include_deductcpf){
            $wherestring_additional = " AND additional_affect_cpf = 1";
        }
        $month = substr($datefrom,0,7);

        $sql = "SELECT SUM(additional_amount) as additional_amt FROM db_additional where additional_empl_id = '$empl_id' AND additional_date Between '$datefrom' AND '$dateto' $wherestring_additional";
   
        $query = mysql_query($sql);
        $additional_amt = 0;
        if($row = mysql_fetch_array($query)){
            $additional_amt = $row['additional_amt'];
        }
        else{
            $additional_amt = 0;
        }
        return ROUND($additional_amt,2);
    }
    public function getDeductionsSalarybackup($empl_id,$datefrom,$dateto,$empl_salary_array,$include_deductcpf,$iscalcdac = false){

        if($include_deductcpf){
            $wherestring = " AND deductions_affect_cpf = 1";
        }
        $salary_montly = $empl_salary_array['emplsalary_amount'];
        $total_workingdays = $empl_salary_array['emplsalary_workday'];
        $hourly_salary = $empl_salary_array['emplsalary_hourly'];
        $total_workinghours = $empl_salary_array['emplsalary_hourly'];
        
        if(($hourly_salary == "") || ($hourly_salary <=0)){
            $hourly_salary = ROUND(($salary_montly / $total_workingdays)/$total_workinghours,2);
        }
        if(!$iscalcdac){
            $deduct_amt_without_cdac = $this->getDeductionsSalary($empl_id, $datefrom, $dateto,$empl_salary_array,false,true);
            $cdac = $this->getCDAC(($empl_salary_array['emplsalary_amount']+$this->summary["calculateforcdacadd_amt"])-$deduct_amt_without_cdac);
        }
        else{
            $cdac = 0;
        }
            //5 = Singaporean
            //3 = PR
        $month = substr($datefrom,0,7);
        $day_salary =  $empl_salary_array['emplsalary_amount'] / $total_workingdays;
        
        $salary_amount_type = $this->getEmployeeSalaryTime($empl_salary_array['emplsalary_amount'],$this->payroll_total_working_days);
        if($day_salary <=0){
            $day_salary = 0;
        }
        $sql = "
                SELECT SUM(a.deductions_amt) as deductions_amt FROM (
                SELECT COALESCE(SUM(leave_unpaid)*$day_salary,0) as deductions_amt
                FROM db_leave l
                INNER JOIN db_leavetype lt ON lt.leavetype_id = l.leave_type
                WHERE ((l.leave_datefrom BETWEEN '$datefrom' AND '$dateto') OR (l.leave_dateto BETWEEN '$datefrom' AND '$dateto'))
                AND l.leave_empl_id = '$empl_id' and l.leave_status = 1 AND l.leave_approvalstatus = 'Approved'  
                    
                UNION ALL
                
                SELECT '$cdac' as deductions_amt
                FROM db_empl empl
                WHERE empl.empl_id = '$empl_id' AND empl.empl_fund_opt_out = 0
                    
                UNION ALL
                
                SELECT (attendance_latetotal*{$salary_amount_type['min_amount']}) as deductions_amt
                FROM db_attendance att
                WHERE att.attendance_empl = '$empl_id' AND att.attendance_status = 1 AND LEFT(attendance_date,7) = '$month'

                UNION ALL
                
                SELECT SUM(l.deductions_amount) as deductions_amt
                FROM db_deductions l
                WHERE l.deductions_date BETWEEN '$datefrom' AND '$dateto' AND l.deductions_empl_id = '$empl_id' and l.deductions_status = 1 $wherestring
                    )a
                ";
             
        $query = mysql_query($sql);
        $deductions_amt = 0;
        if($row = mysql_fetch_array($query)){
            $deductions_amt = $row['deductions_amt'];
        }else{
            $deductions_amt = 0;
        }
        return ROUND($deductions_amt,2);
    }
    public function getDeductionsSalary($empl_id,$datefrom,$dateto,$empl_salary_array){

        $salary_montly = $empl_salary_array['emplsalary_amount'];
        $total_workingdays = $empl_salary_array['emplsalary_workday'];
        $hourly_salary = $empl_salary_array['emplsalary_hourly'];
        $total_workinghours = $empl_salary_array['emplsalary_hourly'];
        

//        if(($hourly_salary == "") || ($hourly_salary <=0)){
//            $hourly_salary = ROUND(($salary_montly / $total_workingdays)/$total_workinghours,2);
//        }
//        if(!$iscalcdac){
//            $deduct_amt_without_cdac = $this->getDeductionsSalary($empl_id, $datefrom, $dateto,$empl_salary_array,false,true);
//            $cdac = $this->getCDAC(($empl_salary_array['emplsalary_amount']+$this->summary["calculateforcdacadd_amt"])-$deduct_amt_without_cdac);
//        }
//        else{
//            $cdac = 0;
//        }
            //5 = Singaporean
            //3 = PR
        $month = substr($datefrom,0,7);

        $day_salary =  $empl_salary_array['emplsalary_amount'] / $total_workingdays;

//        $salary_amount_type = $this->getEmployeeSalaryTime($empl_salary_array['emplsalary_amount'],$this->payroll_total_working_days);
//        if($day_salary <=0){
//            $day_salary = 0;
//        }        
        
        $sql = "SELECT SUM(deductions_amount) as deductions_amt FROM db_deductions WHERE deduction_empl_type = '0' AND deductions_status = '1' AND deductions_empl_id = '$empl_id' AND deductions_date BETWEEN '$datefrom' AND '$dateto'"; 
        $query = mysql_query($sql);
        $deductions_amt = 0;
        if($row = mysql_fetch_array($query)){
            $deductions_amt = $row['deductions_amt'];
        }else{
            $deductions_amt = 0;
        }
        
        //just cpf deductions
        $sql = "SELECT SUM(deductions_amount) as deductions_amt FROM db_deductions WHERE deduction_empl_type = '0' AND deductions_status = '1' AND deductions_empl_id = '$empl_id' AND deductions_date BETWEEN '$datefrom' AND '$dateto' AND deductions_affect_cpf = '1'"; 
        $query = mysql_query($sql);
        $deduction_with_cpf = 0;
        if($row = mysql_fetch_array($query)){
            $deduction_with_cpf = $row['deductions_amt'];
        }else{
            $deduction_with_cpf = 0;
        }
        
        $deductions_array['deductions_amt'] = $deductions_amt;
        
        $deductions_array['deductions_with_cpf'] = $deduction_with_cpf;

        $leave_amt = 0;
        $total_leave = 0;
        $sql4 = "SELECT * FROM db_leave WHERE leave_empl_id = '$empl_id' AND leave_approvalstatus = 'Approved' AND ((leave_datefrom BETWEEN '$datefrom' AND '$dateto') OR (leave_dateto BETWEEN '$datefrom' AND '$dateto')) AND leave_empl_type = '0' AND leave_type !='9' AND leave_unpaid >'0'";
        $query4 = mysql_query($sql4);
        while($row4 = mysql_fetch_array($query4)){
            $dateStart = $row4['leave_datefrom'];
            $dateEnd = $row4['leave_dateto'];
         
            $dateStartMonth = substr($dateStart, 5,2);
            $dateEndMonth = substr($dateEnd, 5,2);
            $dateCurrent = substr($datefrom, 5,2);

            if($dateStartMonth < $dateEndMonth){;
                if($dateStartMonth == $dateCurrent){
                    $lastDay = date("Y-m-t", strtotime($datefrom));
                    $date1=date_create($dateStart);
                    $date2=date_create($lastDay);
                    $diff=date_diff($date1,$date2);

                    $diff->format("%a");
                    $day = (double)$diff->format("%a");
                    $day++;
                    if(($row4['leave_total_day'] - $day) < $row4['leave_unpaid']){
                        
                        $this_month_leave = $row4['leave_unpaid'] - ($row4['leave_total_day'] - $day);

                        $leave_amt = $day_salary * $this_month_leave;
                        $total_extra_leave = $total_extra_leave + $leave_amt;         
                    }
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
                    if(($day2) >= $row4['leave_unpaid']){
                        $leave_amt = $day_salary * $row4['leave_unpaid'];
                        $total_extra_leave = $total_extra_leave + $leave_amt;         
                    }else
                    {
                        $leave_amt = $day_salary * $day2;
                        $total_extra_leave = $total_extra_leave + $leave_amt; 
                    }
                } 
            }
            else{
                $leave_amt = $day_salary * (double)$row4['leave_unpaid'];
                $total_extra_leave = $total_extra_leave + $leave_amt;
            }
        }

        $sql1 = "SELECT * FROM db_leave WHERE leave_status = '1' AND leave_empl_type = '0' AND leave_type = '9' AND leave_empl_id = '$empl_id' AND leave_duration = 'full_day' AND leave_approvalstatus = 'Approved' AND  ((leave_datefrom BETWEEN '$datefrom' AND '$dateto') OR (leave_dateto BETWEEN '$datefrom' AND '$dateto'))";
        $query1 = mysql_query($sql1);
        
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
                }                   
                    $total_leave = $total_leave + $leave_amt;
            }
            else{
                $leave_amt = $day_salary * (double)$row1['leave_total_day'];
                $total_leave = $total_leave + $leave_amt;
            }
            $deductions_amt = $deductions_amt + $leave_amt;
        }
        //echo $total_extra_leave;
        
        $deductions_array['deductions_with_cpf'] = $deductions_array['deductions_with_cpf'] + $total_leave+$total_extra_leave;
                
        $deductions_array['deductions_total_amt'] = ROUND($deductions_amt+$total_extra_leave,1);
        $deductions_array['leave_amt'] = ROUND($total_leave+$total_extra_leave,1);
        
        //return ROUND($deductions_amt,2);
        return $deductions_array;
    }
    
    public function previewPayslipDetail(){
    include_once 'class/Empl.php';
    include_once 'class/Cpf.php';
        $c = new Cpf();
        $e = new Empl();
        $empl_salary_array = $e->fetchSalaryDetail(" AND emplsalary_empl_id = '$this->empl_id' ","ORDER BY emplsalary_date DESC,emplsalary_id DESC","limit 0,1",2);
        $empl_array = $e->fetchEmplDetail(" AND empl_id = '$this->empl_id'","","",2);
        $this->emplpass_cpf = $empl_array['empl_iscpf'];
        $department_code = getDataCodeBySql("department_code","db_department"," WHERE department_id = '{$empl_array['empl_department']}'");
        $outlet_code = getDataCodeBySql("outl_code","db_outl"," WHERE outl_id = '{$empl_array['empl_outlet']}'");
        $bank_code = getDataCodeBySql("bank_code","db_bank"," WHERE bank_id = '{$empl_array['empl_bank']}'");
        $empl_array['bank_code'] = $bank_code;
        
        $this->fetchPayrollLineDetail(" AND payline_payroll_id = '$this->payroll_id' AND payline_empl_id = '$this->empl_id' ","","",2);

        
        $from = new DateTime($empl_array['empl_birthday']);
        $to = new DateTime('today');
        $empl_age = $from->diff($to)->y;


        $cpf_array = $c->fetchCpfDetail(" AND cpf_from_age <= '$empl_age' AND cpf_to_age >= '$empl_age' ", "", "",2);
        define('CPF_employer',$cpf_array['cpf_employer_percent']/100);
        define('CPF_employee',$cpf_array['cpf_employee_percent']/100);
        define('Levy_employee',$empl_array['empl_levy_amt']);
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
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='payroll.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='payroll.php?action=createForm'">Create New</button>
                <?php }?>
              </div>

                <form id = 'payroll_form' class="form-horizontal" action = 'payroll.php?action=create' method = "POST">
                  <div class="box-body">
                    <div class="col-sm-12">  
                       <div class="col-sm-12" style = 'text-align:center'>  
                        <h3>Salary Payslip of <?php echo $empl_array['empl_name'];?></h3>
                       </div>
                    <h4>Salary Information</h4>
                    <div class="form-group">
                      <label for ="payroll_outlet" class="col-sm-2 control-label">Employee Code : </label>
                      <div class="col-sm-2">
                       <label for ="payroll_outlet" class="col-sm-7 control-label" style = 'font-weight: inherit;' ><?php echo $empl_array['empl_code'];?></label>
                      </div>
                      <label for ="payroll_outlet" class="col-sm-2 control-label">Outlet : </label>
                      <div class="col-sm-4">
                       <label for ="payroll_outlet" class="col-sm-6 control-label" style = 'font-weight: inherit;'><?php echo $outlet_code;?></label>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for ="payroll_outlet" class="col-sm-2 control-label">Employee Name : </label>
                      <div class="col-sm-2">
                       <label for ="payroll_outlet" class="col-sm-7 control-label" style = 'font-weight: inherit;'><?php echo $empl_array['empl_name'];?></label>
                      </div>
                      <label for ="payroll_outlet" class="col-sm-2 control-label">Department : </label>
                      <div class="col-sm-4">
                       <label for ="payroll_outlet" class="col-sm-6 control-label" style = 'font-weight: inherit;'><?php echo $department_code;?></label>
                      </div>
                    </div>
                    <div class="form-group">  
                      <label for ="payroll_department" class="col-sm-2 control-label">Salary Date : </label>
                      <div class="col-sm-2">
                          <label for ="payroll_outlet" class="col-sm-7 control-label" style = 'font-weight: inherit;'><?php echo format_date($this->payroll_salary_date);?></label>
                      </div>
                      <label for ="payroll_outlet" class="col-sm-2 control-label">Join Date : </label>
                      <div class="col-sm-4">
                       <label for ="payroll_outlet" class="col-sm-6 control-label" style = 'font-weight: inherit;'><?php echo format_date($empl_array['empl_joindate']);?></label>
                      </div>         
                    </div>
                    <div class="form-group">  
                      <label for ="payroll_department" class="col-sm-2 control-label">Salary : </label>
                      <div class="col-sm-2">
                          <label for ="payroll_outlet" class="col-sm-7 control-label" style = 'font-weight: inherit;'>
                              <?php 
                              echo "$ ".num_format($empl_salary_array['emplsalary_amount']);
                              $this->summary['Basic_Salary'] = $empl_salary_array['emplsalary_amount'];
                              ?>
                          </label>
                      </div>
                      <label for ="payroll_outlet" class="col-sm-2 control-label">Confirmation Date : </label>
                      <div class="col-sm-4">
                       <label for ="payroll_outlet" class="col-sm-6 control-label" style = 'font-weight: inherit;'><?php if($empl_array['empl_confirmationdate'] == '0000-00-00'){ echo ' - ';}else{echo format_date($empl_array['empl_confirmationdate']);}?></label>
                      </div>  
                    </div>
                     <h4>Salary Payslip Period</h4>
                     <div class="form-group">
                         <label for="payroll_startdate" class="col-sm-2 control-label">Payslip Period : </label>
                      <div class="col-sm-5">
                          <label for ="payroll_outlet" class="col-sm-7 control-label" style = 'font-weight: inherit;'><?php echo format_date($this->payroll_startdate);?> <b>To</b> <?php echo format_date($this->payroll_enddate);?></label>
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
                     
                     <div class="form-group">
                        <div class="col-sm-12" style = 'text-align:center' >    
                            <table class = 'table table-bordered  dataTable ' >
                                <tr>
                                <th>Leave Type</th>   
                                <th>Entitled</th>
                                <th>Taken</th>
                                <th>Pending</th>
                                <th>Available Balance</th>
                                </tr>
                            <?php
                            $year = date("Y",strtotime($this->payroll_startdate));
                            $year_start = system_date_yearstart;
                            $year_end = system_date_yearend;
                            $wherestring = "AND el.emplleave_empl = '$this->empl_id'";
                            $wherestring2 = "AND leave_empl_id = '$this->empl_id'";
                            $sql = "SELECT lt.*,el.emplleave_days,el.emplleave_id,el.emplleave_entitled
                                    FROM db_leavetype lt
                                    INNER JOIN db_emplleave el ON el.emplleave_leavetype = lt.leavetype_id AND el.emplleave_year = '$year' AND el.emplleave_disabled = 0 $wherestring 
                                    WHERE lt.leavetype_status = 1 
                                    ORDER BY lt.leavetype_seqno,lt.leavetype_code";

                            $query = mysql_query($sql);
                            while($row = mysql_fetch_array($query)){
                               $taken = getDataCodeBySql("SUM(leave_total_day)",'db_leave'," WHERE leave_approvalstatus = 'Approved' AND leave_type = '{$row['leavetype_id']}' $wherestring2 AND leave_datefrom BETWEEN '$year_start' AND '$year_end'");
                               $pending = getDataCodeBySql("SUM(leave_total_day)",'db_leave'," WHERE leave_approvalstatus = 'Pending' AND leave_type = '{$row['leavetype_id']}' $wherestring2 AND leave_datefrom BETWEEN '$year_start' AND '$year_end'");
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
                                    <td><?php echo num_format($row['emplleave_entitled'] - $taken);?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </table>

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
                    <a href="payslip-print.php?payroll_id=<?php echo $this->payroll_id;?>&empl_id=<?php echo $_REQUEST['empl_id'];?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
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
            window.location.href = 'payroll.php?f=d';
        });
    });

function countdown() {
        setInterval(function () {
            countDown = $('#countDown').val();
            if (countDown == 30) {
                $('#countdownmodel').modal('show');
                $('#continue').click(function(){
                   $('#countdownmodel').modal('hide');
                   $('#countDown').val(60);
                  
                });
            }
            countDown--;
            if(countDown == 0){
                window.location.href = 'payroll.php?f=d';
            }
            document.getElementById('countdown').innerHTML = countDown;
            $('#countDown').val(countDown);
            return countDown;
        }, 1000);
    }

countdown();
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
                    ";
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
                    <td style = 'text-align:right' ><?php echo "$ ".num_format(round($employee_cpf_amt));?></td>

                </tr>
                <tr>
                    <td></td>
                    <td style = 'font-weight: 700'>Employer CPF</td>
                    <td style = 'text-align:right' ><?php echo "$ ".num_format(round($employer_cpf_amt));?></td>

                </tr>
                <tr>
                    <td></td>
                    <td style = 'font-weight: 700'>Date Of Payment</td>
                    <td style = 'text-align:right' ><?php echo format_date($this->payroll_salary_date);?></td>

                </tr>
                <tr>
                    <td></td>
                    <td style = 'font-weight: 700'>Mode Of Payment</td>
                    <td style = 'text-align:right' ><?php echo $empl_array['bank_code'] . " - " . $empl_array['empl_bank_acc_no'];?></td>

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
            WHERE pl.payline_empl_id = '$empl_id' AND LEFT(py.payroll_startdate,4) = '$year' AND py.payroll_startdate <='$payroll_enddate' AND py.payroll_status = '1' AND pl.payline_empl_type = '0'";

    $query = mysql_query($sql);
    if($row = mysql_fetch_array($query)){
        $total_cpf = $row['total_cpf'];
    }else{
        $total_cpf = 0;
    }
    
    if($this->payroll_id <=0){
        if($type == 'employer'){
               $total_cpf = $this->employer_cpf_amt;
        }else if($type == 'employee'){
               $total_cpf = $this->employee_cpf_amt;
        }else if($type == 'net_pay'){
               $total_cpf = $this->employee_net_payment;
        }
    }
    
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
}
?>