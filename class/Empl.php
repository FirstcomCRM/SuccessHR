<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Empl {

    public function Empl(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $this->empl_login_password = md5("@#~x?\$" . $this->empl_login_password . "?\$");
        $table_field = array('empl_code','empl_name','empl_nric','empl_tel','empl_birthday',
                             'empl_group','empl_manager','empl_joindate','empl_postal_code','empl_unit_number',
                             'empl_street','empl_postal_code2','empl_unit_number2','empl_street2','empl_remark',
                             'empl_login_email','empl_login_password','empl_seqno','empl_status',
                             'empl_outlet','empl_email','empl_department','empl_bank',
                             'empl_bank_acc_no','empl_nationality','empl_emplpass',
                             'empl_resigndate','empl_confirmationdate','empl_mobile',
                             'empl_levy_amt','empl_pass_issuance','empl_pass_renewal',
                             'empl_language','empl_leave_approved1','empl_leave_approved2','empl_leave_approved3',
                             'empl_claims_approved1','empl_claims_approved2','empl_claims_approved3',
            
                             'empl_marital_status','empl_religion','empl_sex','empl_card',
                             'empl_iscpf','empl_cpf_account_no','empl_income_taxid','empl_race',
                             'empl_cpf_first_half','empl_sld_opt_out','empl_fund_opt_out','empl_fund_first_half',
                             'empl_emer_contact','empl_emer_relation','empl_emer_phone1','empl_emer_phone2',
                             'empl_emer_address','empl_emer_remarks','empl_probation','empl_prdate','empl_resignreason',
                             'empl_paymode','empl_bank_acc_name','empl_work_permit','empl_work_permit_date_arrival',
                             'empl_work_permit_application_date','empl_numberofchildren','empl_isovertime',
                             'empl_work_time_start','empl_work_time_end','empl_levegroup','empl_designation',
                             'empl_extentionprobation'
            );
        $table_value = array(get_prefix_value("Empl code",true),$this->empl_name,$this->empl_nric,$this->empl_tel,format_date_database($this->empl_birthday),
                             $this->empl_group,$this->empl_manager,format_date_database($this->empl_joindate),$this->empl_postal_code,$this->empl_unit_no,
                             $this->empl_address,$this->empl_postal_code2,$this->empl_unit_no2,$this->empl_address2,$this->empl_remark,
                             $this->empl_login_email,$this->empl_login_password,$this->empl_seqno,$this->empl_status,
                             $this->empl_outlet,$this->empl_email,$this->empl_department,$this->empl_bank,
                             $this->empl_bank_acc_no,$this->empl_nationality,$this->empl_emplpass,
                             format_date_database($this->empl_resigndate),format_date_database($this->empl_confirmationdate),$this->empl_mobile,
                             $this->empl_levy_amt,format_date_database($this->empl_pass_issuance),format_date_database($this->empl_pass_renewal),
                             $this->empl_language,$this->empl_leave_approved1,$this->empl_leave_approved2,$this->empl_leave_approved3,
                             $this->empl_claims_approved1,$this->empl_claims_approved2,$this->empl_claims_approved3,

                             $this->empl_marital_status,$this->empl_religion,$this->empl_sex,$this->empl_card,
                             $this->empl_iscpf,$this->empl_cpf_account_no,$this->empl_income_taxid,$this->empl_race,
                             $this->empl_cpf_first_half,$this->empl_sld_opt_out,$this->empl_fund_opt_out,$this->empl_fund_first_half,
                             $this->empl_emer_contact,$this->empl_emer_relation,$this->empl_emer_phone1,$this->empl_emer_phone2,
                             $this->empl_emer_address,$this->empl_emer_remarks,$this->empl_probation,format_date_database($this->empl_prdate),$this->empl_resignreason,
                             $this->empl_paymode,$this->empl_bank_acc_name,$this->empl_work_permit,format_date_database($this->empl_work_permit_date_arrival),
                             format_date_database($this->empl_work_permit_application_date),$this->empl_numberofchildren,$this->empl_isovertime,
                             $this->empl_work_time_start,$this->empl_work_time_end,$this->empl_levegroup,$this->empl_designation,
                             $this->empl_extentionprobation
                );
        $remark = "Insert Employee.";
        if(!$this->save->SaveData($table_field,$table_value,'db_empl','empl_id',$remark)){
           return false;
        }else{
           $this->empl_id = $this->save->lastInsert_id;
           $this->pictureManagement();
           $count = getDataCountBySql("db_leavetype", " WHERE status = 1");
          
           for($i=0;$i<sizeof($this->emplleave_days);$i++){
                    $this->createLeave(escape($this->emplleave_leavetype[$i]),escape($this->emplleave_days[$i]),escape($this->emplleave_disabled[$this->emplleave_leavetype[$i]]),escape($this->emplleave_entitled[$i]));
           }
           //$this->email($this->empl_email);
           return true;
        }
    }
    public function update(){
        $new_password = $this->empl_login_password;
        $empl_id = $this->empl_id;
        $empl_login_email = $this->empl_login_email;

        if($this->empl_oldpassword != $new_password){
          $this->empl_login_password = md5("@#~x?\$" . $new_password . "?\$");
        }

        $table_field = array('empl_code','empl_name','empl_nric','empl_tel','empl_birthday',
                             'empl_group','empl_manager','empl_joindate','empl_postal_code','empl_unit_number',
                             'empl_street','empl_postal_code2','empl_unit_number2','empl_street2','empl_remark',
                             'empl_login_email','empl_login_password','empl_seqno','empl_status',
                             'empl_outlet','empl_email','empl_department','empl_bank',
                             'empl_bank_acc_no','empl_nationality','empl_emplpass',
                             'empl_resigndate','empl_confirmationdate','empl_mobile',
                             'empl_levy_amt','empl_pass_issuance','empl_pass_renewal',
                             'empl_language','empl_leave_approved1','empl_leave_approved2','empl_leave_approved3',
                             'empl_claims_approved1','empl_claims_approved2','empl_claims_approved3',
            
                             'empl_marital_status','empl_religion','empl_sex','empl_card',
                             'empl_iscpf','empl_cpf_account_no','empl_income_taxid','empl_race',
                             'empl_cpf_first_half','empl_sld_opt_out','empl_fund_opt_out','empl_fund_first_half',
                             'empl_emer_contact','empl_emer_relation','empl_emer_phone1','empl_emer_phone2',
                             'empl_emer_address','empl_emer_remarks','empl_probation','empl_prdate','empl_resignreason',
                             'empl_paymode','empl_bank_acc_name','empl_work_permit','empl_work_permit_date_arrival',
                             'empl_work_permit_application_date','empl_numberofchildren','empl_isovertime',
                             'empl_work_time_start','empl_work_time_end'
            );
        $table_value = array(get_prefix_value("Empl code",true),$this->empl_name,$this->empl_nric,$this->empl_tel,format_date_database($this->empl_birthday),
                             $this->empl_group,$this->empl_manager,format_date_database($this->empl_joindate),$this->empl_postal_code,$this->empl_unit_no,
                             $this->empl_address,$this->empl_postal_code2,$this->empl_unit_no2,$this->empl_address2,$this->empl_remark,
                             $this->empl_login_email,$this->empl_login_password,$this->empl_seqno,$this->empl_status,
                             $this->empl_outlet,$this->empl_email,$this->empl_department,$this->empl_bank,
                             $this->empl_bank_acc_no,$this->empl_nationality,$this->empl_emplpass,
                             format_date_database($this->empl_resigndate),format_date_database($this->empl_confirmationdate),$this->empl_mobile,
                             $this->empl_levy_amt,format_date_database($this->empl_pass_issuance),format_date_database($this->empl_pass_renewal),
                             $this->empl_language,$this->empl_leave_approved1,$this->empl_leave_approved2,$this->empl_leave_approved3,
                             $this->empl_claims_approved1,$this->empl_claims_approved2,$this->empl_claims_approved3,

                             $this->empl_marital_status,$this->empl_religion,$this->empl_sex,$this->empl_card,
                             $this->empl_iscpf,$this->empl_cpf_account_no,$this->empl_income_taxid,$this->empl_race,
                             $this->empl_cpf_first_half,$this->empl_sld_opt_out,$this->empl_fund_opt_out,$this->empl_fund_first_half,
                             $this->empl_emer_contact,$this->empl_emer_relation,$this->empl_emer_phone1,$this->empl_emer_phone2,
                             $this->empl_emer_address,$this->empl_emer_remarks,$this->empl_probation,format_date_database($this->empl_prdate),$this->empl_resignreason,
                             $this->empl_paymode,$this->empl_bank_acc_name,$this->empl_work_permit,format_date_database($this->empl_work_permit_date_arrival),
                             format_date_database($this->empl_work_permit_application_date),$this->empl_numberofchildren,$this->empl_isovertime,
                             $this->empl_work_time_start,$this->empl_work_time_end
            );
        $remark = "Update Employee.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_empl','empl_id',$remark,$this->empl_id)){
           return false;
        }else{
           $this->pictureManagement();

           for($i=0;$i<sizeof($this->emplleave_days);$i++){             
                if($this->emplleave_id[$i] > 0){
                     $this->updateLeave(escape($this->emplleave_days[$i]),escape($this->emplleave_id[$i]),escape($this->emplleave_disabled[$this->emplleave_leavetype[$i]]),escape($this->emplleave_entitled[$i]));
                }else{
                     $this->createLeave(escape($this->emplleave_leavetype[$i]),escape($this->emplleave_days[$i]),escape($this->emplleave_disabled[$this->emplleave_leavetype[$i]]),escape($this->emplleave_entitled[$i]));
                }
           }
           return true;
        }
    }
    public function createLeave($emplleave_leavetype,$emplleave_days,$emplleave_disabled,$emplleave_entitled){
        $table_field = array('emplleave_empl','emplleave_leavetype','emplleave_days','emplleave_year','emplleave_status','emplleave_disabled','emplleave_entitled');
        $table_value = array($this->empl_id,$emplleave_leavetype,$emplleave_days,date("Y"),1,$emplleave_disabled,$emplleave_entitled);
        $remark = "Create Employee Leaves.";
        if(!$this->save->SaveData($table_field,$table_value,'db_emplleave','emplleave_id',$remark)){
           
        }else{
          
        }
    }
    public function updateLeave($emplleave_days,$emplleave_id,$emplleave_disabled,$emplleave_entitled){


        $table_field = array('emplleave_days','emplleave_disabled','emplleave_entitled');
        $table_value = array($emplleave_days,$emplleave_disabled,$emplleave_entitled);
        $remark = "Update Employee Leaves.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_emplleave','emplleave_id',$remark,$emplleave_id," AND emplleave_empl = '$this->empl_id'")){
           return false;
        }else{
           return true;
        }
    }
    public function createSalary(){
        $table_field = array('emplsalary_empl_id','emplsalary_date','emplsalary_amount','emplsalary_remark','emplsalary_status',
                             'emplsalary_workday','emplsalary_hourly','emplsalary_overtime');
        $table_value = array($this->empl_id,format_date_database($this->emplsalary_date),$this->emplsalary_amount,$this->emplsalary_remark,1,
                             $this->emplsalary_workday,$this->emplsalary_hourly,$this->emplsalary_overtime);
        $remark = "Create Salary Adjustment.";
        if(!$this->save->SaveData($table_field,$table_value,'db_emplsalary','emplsalary_id',$remark)){
            return false;
        }else{
            return true;
        }
    }
    public function updateSalary(){

        $table_field = array('emplsalary_empl_id','emplsalary_date','emplsalary_amount','emplsalary_remark',
                             'emplsalary_workday','emplsalary_hourly','emplsalary_overtime');
        $table_value = array($this->empl_id,format_date_database($this->emplsalary_date),$this->emplsalary_amount,$this->emplsalary_remark,
                             $this->emplsalary_workday,$this->emplsalary_hourly,$this->emplsalary_overtime);
        $remark = "Update Salary Adjustment.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_emplsalary','emplsalary_id',$remark,$this->emplsalary_id," AND emplsalary_empl_id = '$this->empl_id'")){
           return false;
        }else{
           return true;
        }
    }
    public function pictureManagement(){
        if(!file_exists("dist/images/empl")){
           mkdir('dist/images/empl/');
        }
        $isimage = false;
        if($this->image_input['type'] == 'image/png' || $this->image_input['type'] == 'image/jpeg' || $this->image_input['type'] == 'image/gif'){
           $isimage = true;
        }

        if($this->image_input['size'] > 0 && $isimage == true){
            if($this->action == 'update'){
                unlink("dist/images/empl/{$this->empl_id}.png");
            }

                move_uploaded_file($this->image_input['tmp_name'],"dist/images/empl/{$this->empl_id}.png");
        }
    }
    public function fetchEmplDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_empl WHERE empl_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->empl_id = $row['empl_id'];
            $this->empl_code = $row['empl_code'];
            $this->empl_name = $row['empl_name'];
            $this->empl_nric = $row['empl_nric'];
            $this->empl_tel = $row['empl_tel'];
            $this->empl_mobile = $row['empl_mobile'];
            $this->empl_email = $row['empl_email'];
            $this->empl_postal_code = $row['empl_postal_code'];
            $this->empl_unit_no = $row['empl_unit_number'];
            $this->empl_address = $row['empl_street'];
            $this->empl_postal_code2 = $row['empl_postal_code2'];
            $this->empl_unit_no2 = $row['empl_unit_number2'];
            $this->empl_address2 = $row['empl_street2'];
            $this->empl_remark = $row['empl_remark'];
            $this->empl_birthday = $row['empl_birthday'];
            $this->empl_joindate = $row['empl_joindate'];
            $this->empl_group = $row['empl_group'];
            $this->empl_manager = $row['empl_manager'];
            $this->empl_seqno = $row['empl_seqno'];
            $this->empl_outlet = $row['empl_outlet'];
            $this->empl_status = $row['empl_status'];
            $this->empl_login_email = $row['empl_login_email'];
            $this->empl_login_password = $row['empl_login_password'];
            $this->empl_department = $row['empl_department'];
            $this->empl_bank = $row['empl_bank'];
            $this->empl_bank_acc_no = $row['empl_bank_acc_no'];
            $this->empl_nationality = $row['empl_nationality'];
            $this->empl_emplpass = $row['empl_emplpass'];
            $this->empl_resigndate = $row['empl_resigndate'];
            $this->empl_confirmationdate = $row['empl_confirmationdate'];
            $this->empl_levy_amt = $row['empl_levy_amt'];
            $this->empl_pass_issuance = $row['empl_pass_issuance'];
            $this->empl_pass_renewal = $row['empl_pass_renewal'];
            $this->empl_language = $row['empl_language'];
            $this->empl_leave_approved1 = $row['empl_leave_approved1'];
            $this->empl_leave_approved2 = $row['empl_leave_approved2'];
            $this->empl_leave_approved3 = $row['empl_leave_approved3'];
            $this->empl_claims_approved1 = $row['empl_claims_approved1'];
            $this->empl_claims_approved2 = $row['empl_claims_approved2'];
            $this->empl_claims_approved3 = $row['empl_claims_approved3'];
            
            $this->empl_marital_status = $row['empl_marital_status'];
            $this->empl_religion = $row['empl_religion'];
            $this->empl_sex = $row['empl_sex'];
            $this->empl_card = $row['empl_card'];
            $this->empl_iscpf = $row['empl_iscpf'];
            $this->empl_cpf_account_no = $row['empl_cpf_account_no'];
            $this->empl_income_taxid = $row['empl_income_taxid'];
            $this->empl_race = $row['empl_race'];
            $this->empl_cpf_first_half = $row['empl_cpf_first_half'];
            $this->empl_sld_opt_out = $row['empl_sld_opt_out'];
            $this->empl_fund_opt_out = $row['empl_fund_opt_out'];
            $this->empl_fund_first_half = $row['empl_fund_first_half'];
            $this->empl_emer_contact = $row['empl_emer_contact'];
            $this->empl_emer_relation = $row['empl_emer_relation'];
            $this->empl_emer_phone1 = $row['empl_emer_phone1'];
            $this->empl_emer_phone2 = $row['empl_emer_phone2'];
            $this->empl_emer_address = $row['empl_emer_address'];
            
            $this->empl_emer_remarks = $row['empl_emer_remarks'];
            $this->empl_probation = $row['empl_probation'];
            $this->empl_prdate = $row['empl_prdate'];
            $this->empl_resignreason = $row['empl_resignreason'];
            $this->empl_paymode = $row['empl_paymode'];
            $this->empl_bank_acc_name = $row['empl_bank_acc_name'];
            $this->empl_work_permit = $row['empl_work_permit'];
            $this->empl_work_permit_date_arrival = $row['empl_work_permit_date_arrival'];
            $this->empl_work_permit_application_date = $row['empl_work_permit_application_date'];
            $this->empl_numberofchildren = $row['empl_numberofchildren'];
            $this->empl_isovertime = $row['empl_isovertime'];
            $this->empl_work_time_start = $row['empl_work_time_start'];
            $this->empl_work_time_end = $row['empl_work_time_end'];
            $this->empl_levegroup = $row['empl_levegroup'];
            $this->empl_designation = $row['empl_designation'];
            $this->empl_extentionprobation = $row['empl_extentionprobation'];
            
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }
       
    }
    public function fetchSalaryDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_emplsalary WHERE emplsalary_id > 0 AND emplsalary_status = '1' $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->empl_id = $row['emplsalary_empl_id'];
            $this->emplsalary_date = $row['emplsalary_date'];
            $this->emplsalary_amount = $row['emplsalary_amount'];
            $this->emplsalary_overtime = $row['emplsalary_overtime'];
            $this->emplsalary_hourly = $row['emplsalary_hourly'];
            $this->emplsalary_workday = $row['emplsalary_workday'];
            $this->emplsalary_remark = $row['emplsalary_remark'];
            $this->emplsalary_empl_id = $row['emplsalary_empl_id'];
            $this->updateBy = $row['updateBy'];
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }
       
    }
    public function delete(){
        $table_field = array('empl_status');
        $table_value = array(0);
        $remark = "Delete Employee.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_empl','empl_id',$remark,$this->empl_id)){
           return false;
        }else{
           return true;
        }
    }
    public function deleteSalary(){
        $table_field = array('emplsalary_status');
        $table_value = array(0);
        $remark = "Delete Employee Salary.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_emplsalary','emplsalary_id',$remark,$this->emplsalary_id," AND emplsalary_empl_id = '$this->empl_id'")){
           return false;
        }else{
           return true;
        }
    }
    public function getInputForm($action){
        global $mandatory; 
        if($action == 'create'){
            $this->empl_seqno = 10;
            $this->empl_code = "-- System Generate --";
            $this->empl_status = 1;
        }
        $this->nationalityCrtl = $this->select->getNationalitySelectCtrl($this->empl_nationality);
        $this->bankCrtl = $this->select->getBankSelectCtrl($this->empl_bank);
        $this->emplpassCrtl = $this->select->getEmplPassSelectCtrl($this->empl_emplpass);
        $this->groupCrtl = $this->select->getGroupSelectCtrl($this->empl_group);
        $this->managerCrtl = $this->select->getManagerSelectCtrl($this->empl_manager);        
        $this->outletCrtl = $this->select->getOutletSelectCtrl($this->empl_outlet);
        $this->departmentCrtl = $this->select->getDepartmentSelectCtrl($this->empl_department);
        $this->religionCrtl = $this->select->getReligionSelectCtrl($this->empl_religion);
        $this->raceCrtl = $this->select->getRaceSelectCtrl($this->empl_race);
        $this->designationCrtl = $this->select->getDesignationSelectCtrl($this->empl_designation);
        
        //empl group 1 = admin
        $this->emplLeaveApproved1Crtl = $this->select->getEmployeeSelectCtrl($this->empl_leave_approved1,'Y'," AND empl_group = '1'");
        $this->emplLeaveApproved2Crtl = $this->select->getEmployeeSelectCtrl($this->empl_leave_approved2,'Y'," AND empl_group = '1'");
        $this->emplLeaveApproved3Crtl = $this->select->getEmployeeSelectCtrl($this->empl_leave_approved3,'Y'," AND empl_group = '1'");
        
        $this->emplClaimsApproved1Crtl = $this->select->getEmployeeSelectCtrl($this->empl_claims_approved1,'Y'," AND empl_group = '1'");
        $this->emplClaimsApproved2Crtl = $this->select->getEmployeeSelectCtrl($this->empl_claims_approved2,'Y'," AND empl_group = '1'");
        $this->emplClaimsApproved3Crtl = $this->select->getEmployeeSelectCtrl($this->empl_claims_approved3,'Y'," AND empl_group = '1'");
        
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Employee Management</title>
    <?php
    include_once 'css.php';
    
    ?>    
    <style>
    .hide{
    display:none;
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
            <h1>Employee Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->empl_id > 0){ echo "Update Employee";}else{ echo "Create New Employee";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = '' onclick = "window.location.href='empl.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'margin-right:10px;' onclick = "window.location.href='empl.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'empl_form' class="form-horizontal" action = 'empl.php?action=create' method = "POST" enctype="multipart/form-data">
                    <input type ='hidden' name = 'current_tab' id = 'current_tab' value = "<?php echo $this->current_tab?>"/>
                  <div class="box-body">
                      
                      <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li tab = "General Info" class="tab_header <?php if(($this->current_tab == "") || ($this->current_tab == "General Info")){ echo 'active';}?>"><a href="#general" data-toggle="tab">General Info</a></li>
                          <li tab = "Contact Info" class="tab_header <?php if($this->current_tab == "Contact Info"){ echo 'active';}?>" ><a href="#contact" data-toggle="tab">Contact Info</a></li>
                          <li tab = "Job Info" class="tab_header <?php if($this->current_tab == "Job Info"){ echo 'active';}?>" ><a href="#job_info" data-toggle="tab">Job Info</a></li>
                          <li tab = "Bank Info" class="tab_header <?php if($this->current_tab == "Bank Info"){ echo 'active';}?>" ><a href="#bank" data-toggle="tab">Bank Info</a></li>
                          <?php // if($this->empl_id > 0){?>
                          <li tab = "Salary Info" class="tab_header <?php if($this->current_tab == "Salary Info"){ echo 'active';}?>"><a href="#salary" data-toggle="tab">Salary Info</a></li>
                          <?php // }?>
                          <li tab = "Foreign Worker" class="tab_header <?php if($this->current_tab == "Foreign Worker"){ echo 'active';}?>"><a href="#foreign_worker" data-toggle="tab">Foreign Worker</a></li>
                          <li tab = "Leave" class="tab_header <?php if($this->current_tab == "Leave"){ echo 'active';}?>"><a href="#leave" data-toggle="tab">Leave</a></li>
                        </ul>
                      </div>
                      <div class="tab-content">
                          <div class=" tab-pane <?php if(($this->current_tab == "") || ($this->current_tab == "General Info")){ echo 'active';}?>" id="general">
                              <div class="col-sm-8">
                              <?php echo $this->getGeneralForm();?>
                              </div>
                              <div class="col-sm-4">
                                 <?php

//                                 echo file_get_contents(include_webroot . "dist/qrcode/?data=$this->empl_code");
                                 ?>
                                   
                              
                                     <p></p>
                                    <?php if(file_exists("dist/images/empl/$this->empl_id.png")){?>
                                    <img src ="dist/images/empl/<?php echo $this->empl_id;?>.png" style = 'width:215px;height:215px;'/>
                                  <?php }else{?>
                                    <img src ='dist/img/avatar5.png'  />
                                   
                                  <?php }?>
                                     <p></p>
                                    <input  data-toggle="tooltip" title="Please upload image in 128 x 128 pixels " type = "file" name = 'image_input' />
                              </div>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "Contact Info"){ echo 'active';}?>" id="contact">
                              <?php echo $this->getContactForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "Job Info"){ echo 'active';}?>" id="job_info">
                              <?php echo $this->getJobInfoForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "Bank Info"){ echo 'active';}?>" id="bank">
                              <?php echo $this->getBankForm();?>
                          </div>
                          <?php // if($this->empl_id > 0){?>
                          <div class=" tab-pane <?php if($this->current_tab == "Salary Info"){ echo 'active';}?>" id="salary">
                              <?php echo $this->getSalaryForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "Foreign Worker"){ echo 'active';}?>" id="foreign_worker">
                              <?php echo $this->getForeignWorkerForm();?>
                          </div>
                          <?php // }?>
                          <div class=" tab-pane <?php if($this->current_tab == "Leave"){ echo 'active';}?>" id="leave">
                              <?php echo $this->getLeaveForm();?>
                          </div>
                      </div>
                        
                    
                     
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Back</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->empl_id;?>" name = "empl_id" id = "empl_id"/>
                    <?php
                    if($this->empl_id > 0){
                        $prm_code = "update";
                    }else{
                        $prm_code = "create";
                    }
                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){
                    ?>
                    <button type = "submit" class="btn btn-info">Submit</button>
                    <?php }?>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
          </section><!-- /.content -->
        </div><!-- /.container -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';
    
    ?>
    <script>
    $(document).ready(function() {
        <?php
        if($this->empl_id > 0){
            echo " getBankCode($this->empl_bank);";
        }
        ?>
        $('#empl_emplpass').change(function(){
        
           if(($("#empl_emplpass option:selected").text() == 'WP') || ($("#empl_emplpass option:selected").text() == 'SP')){
                if($('#levy_div').hasClass('hide')){
                    $('#levy_div').removeClass('hide');
                }
           }else{
                    $('#levy_div').addClass('hide');
           }
        });
        $('#empl_email').keyup(function(){
            $('#empl_login_email').val($(this).val());
        });
        $("#empl_form").validate({
                  rules: 
                  {
                      empl_name:
                      {
                          required: true
                      },
                      empl_group:
                      {
                          required: true
                      },
                      empl_nric:
                      {
                          required: true
                      },
                      empl_login_email:
                      {
                          required: true,
                          remote: {
                                  url: "empl.php?action=validate_email",
                                  type: "post",
                                  data: 
                                        {
                                            empl_id: function()
                                            {
                                                return $("#empl_id").val();
                                            }
                                        }
                              }
                      },
                      empl_login_password:
                      {
                        required: true,
                      },
                      empl_login_password_cm:
                      {
                        required: true,
                        minlength : 5,
                        equalTo : "#empl_login_password"
                      },
                      empl_sex:
                      {
                        required: true,
                      },
                      empl_outlet:
                      {
                        required: true,          
                      }
                      
                  },
                  messages:
                  {
                      empl_name:
                      {
                          required: "Please enter name."
                      },
                      customer_login_id:
                      {
                          required: "Please enter customer login email.",
                          remote: "Login email duplicate."
                      },
                      customer_login_password:
                      {
                            required: "Please enter Password."
                      },
                      customer_confirmpassword:
                      {
                            required: "Please enter Confirm Password."
                      },
                      empl_outlet:
                      {
                        required: "Please choose your outlet."        
                      }
                  }
              });
            $('.tab_header').click(function(){
                $('#current_tab').val($(this).attr('tab'));
            }); 
            $('.save_salary_btn').click(function(){
                var data = "action=saveSalary&empl_id=<?php echo $this->empl_id;?>&emplsalary_date="+$('#emplsalary_date').val()+"&emplsalary_amount="+$('#emplsalary_amount').val()+"&emplsalary_remark="+encodeURIComponent($('#emplsalary_remark').val());
                    data = data + "&emplsalary_workday="+encodeURIComponent($('#emplsalary_workday').val())+"&emplsalary_hourly="+encodeURIComponent($('#emplsalary_hourly').val())+"&emplsalary_overtime="+encodeURIComponent($('#emplsalary_overtime').val());
                    data = data + "&emplsalary_id="+$('#emplsalary_id').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'empl.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&empl_id={$_REQUEST['empl_id']}";?>';
                           window.location.href = url + "&current_tab=" + $('#current_tab').val();
                       }else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            $('#empl_bank').change(function(){
            
                getBankCode($(this).val());
                
            });
            
            $('.manager').hide();
            
            $('#empl_group').change(function(){
                var data = $(this);
                console.log(data);
                if(data.val() == "8"){
                $('.manager').show();
                }
                else
                {
                    $('.manager').hide();
                }
                
                
            });
            
            
            
});
function getBankCode(bank){

                var data = "action=getBankCode&empl_bank="+bank;
                $.ajax({ 
                    type: 'POST',
                    url: 'empl.php',
                    cache: false,
                    data:data,
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       $('#empl_bank_no').val(jsonObj.bank_no);
                    }		
                 });
}
    </script>
        <script type = "text/javascript">
			function checkEnter() {
				var x =  document.getElementById('empl_postal_code').value;
				function Cloud(){
					jQuery.ajax({
						url: 'https://developers.onemap.sg/commonapi/search?searchVal='+x+'&returnGeom=Y&getAddrDetails=Y&pageNum=1',
						success: function(result){
							var TrueResult = JSON.stringify(result);
							jQuery.each(jQuery.parseJSON(TrueResult), function (item, value) {
								if (item == "results") {
									jQuery.each(value, function (i, object) {
										jQuery.each(object, function (subI, subObject) {
											if (subI == 'ADDRESS'){
												jQuery('#empl_address').val(subObject); 
											} 
										});
									});
								}
							});
						}});
				}
				Cloud();
			}

			function checkEnter2() {
				var x =  document.getElementById('empl_postal_code2').value;
				function Cloud(){
					jQuery.ajax({
						url: 'https://developers.onemap.sg/commonapi/search?searchVal='+x+'&returnGeom=Y&getAddrDetails=Y&pageNum=1',
						success: function(result){
							var TrueResult = JSON.stringify(result);
							jQuery.each(jQuery.parseJSON(TrueResult), function (item, value) {
								if (item == "results") {
									jQuery.each(value, function (i, object) {
										jQuery.each(object, function (subI, subObject) {
											if (subI == 'ADDRESS'){
												jQuery('#empl_address2').val(subObject); 
											} 
										});
									});
								}
							});
						}});
				}
				Cloud();
			}
    </script>
  </body>
</html>
        <?php
        
    }
    public function getGeneralForm(){
        global $mandatory;
        
        if($this->empl_id <=0){
            $this->empl_joindate = system_date;
            
        }
        $this->emplsalary_date = system_date;
    ?>
            <div class="form-group">
                  <label for="empl_code" class="col-sm-2 control-label">Code </label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="empl_code" name="empl_code" value = "<?php echo $this->empl_code;?>" disabled  >
                  </div>
                  <label for="empl_group" class="col-sm-2 control-label">User Right <?php echo $mandatory;?></label>
                  <div class="col-sm-3">
                       <select class="form-control select2" id="empl_group" name="empl_group" style = 'width:100%'>
                           <?php echo $this->groupCrtl;?>
                       </select>
                  </div>
            </div>  
            <div class="form-group">
                <label for="empl_name" class="col-sm-2 control-label" >Name <?php echo $mandatory;?></label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="empl_name" name="empl_name" value = "<?php echo $this->empl_name;?>" placeholder="Name">
                </div>
                <label for="empl_manager" class="manager col-sm-2 control-label">Manager</label>
                <div class="manager col-sm-3">
                     <select class="manager form-control select2" id="empl_manager" name="empl_manager" style = 'width:100%' >
                       <?php echo $this->managerCrtl;?>
                     </select>
                </div>
                </div>
        <div class="form-group">
            <label for="empl_nric" class="col-sm-2 control-label" >NRIC <?php echo $mandatory;?></label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="empl_nric" name="empl_nric" value = "<?php echo $this->empl_nric;?>" placeholder="NRIC">
            </div>
            <label for="empl_emplpass" class="col-sm-2 control-label">Type Of Pass</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="empl_emplpass" name="empl_emplpass" style = 'width:100%'>
                     <?php echo $this->emplpassCrtl;?>
                 </select>
            </div>
        </div>
        <div class="form-group">
            <label for="empl_sex" class="col-sm-2 control-label">Sex <?php echo $mandatory;?></label>
             <div class="col-sm-3">
                 <select class="form-control select2" id="empl_sex" name="empl_sex" style = 'width:100%'>
                    <option value="">Select One</option>
                    <option value="M" <?php if($this->empl_sex == 'M'){ echo 'SELECTED';}?>>Male</option>
                    <option value="F" <?php if($this->empl_sex == 'F'){ echo 'SELECTED';}?>>Female</option>
                 </select>
             </div>
              <label for="empl_levegroup" class="col-sm-2 control-label">Employee Leave Group</label>
              <div class="col-sm-3">
                 <select class="form-control select2" id="empl_levegroup" name="empl_levegroup" style = 'width:100%'>
                    <option value="12" <?php if($this->empl_levegroup == '12'){ echo 'SELECTED';}?>>12 Days</option>
                    <option value="14" <?php if($this->empl_levegroup == '14'){ echo 'SELECTED';}?>>14 Days</option>
                 </select>
              </div>
        </div>

        <div class="form-group">
            <label for="empl_mobile" class="col-sm-2 control-label">Mobile</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="empl_mobile" name="empl_mobile" value = "<?php echo $this->empl_mobile;?>" placeholder="Mobile">
            </div>  
            <label for="empl_religion" class="col-sm-2 control-label">Religion</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="empl_religion" name="empl_religion" style = 'width:100%'>
                   <?php echo $this->religionCrtl;?>
                 </select>
            </div>
        </div>
        <div class="form-group">
            <label for="empl_tel" class="col-sm-2 control-label">Home Tel</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="empl_tel" name="empl_tel" value = "<?php echo $this->empl_tel;?>" placeholder="Home Tel">
            </div>
            <label for="empl_race" class="col-sm-2 control-label">Race</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="empl_race" name="empl_race" style = 'width:100%'>
                   <?php echo $this->raceCrtl;?>
                 </select>
            </div>
        </div>
        <div class="form-group">
            <label for="empl_email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="empl_email" name="empl_email" value = "<?php echo $this->empl_email;?>" placeholder="Email">
            </div>
            <label for="empl_card" class="col-sm-2 control-label">Card/Swipe/Punch ID</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="empl_card" name="empl_card" value = "<?php echo $this->empl_card;?>" placeholder="Card/Swipe/Punch ID">
            </div>
        </div>
        <div class="form-group">
            <label for="empl_birthday" class="col-sm-2 control-label">Birthday</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="empl_birthday" name="empl_birthday" value = "<?php echo format_date($this->empl_birthday);?>" placeholder="Birthday">
            </div>
            <label for="empl_confirmationdate" class="col-sm-2 control-label">Language Prefer</label>
            <div class="col-sm-3">
                  <div class="radio">
                        <label>
                          <input type="radio" name = "empl_language" value = 'english' <?php if(($this->empl_language == 'english') || ($this->empl_language == '')){ echo 'CHECKED';}?>>English
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "empl_language" value = 'chinese' <?php if($this->empl_language == 'chinese'){ echo 'CHECKED';}?>>Chinese
                        </label> 
                  </div>
            </div>
        </div>

        <div class="form-group">
         <label for="empl_marital_status" class="col-sm-2 control-label">Marital Status</label>
          <div class="col-sm-3">
              <select class="form-control select2" id="empl_marital_status" name="empl_marital_status" style = 'width:100%'>
                    <option value="S" <?php if($this->empl_marital_status == 'S'){ echo 'SELECTED';}?>>Single</option>
                    <option value="M" <?php if($this->empl_marital_status == 'M'){ echo 'SELECTED';}?>>Married </option>
                    <option value="D" <?php if($this->empl_marital_status == 'D'){ echo 'SELECTED';}?>>Divorced </option>
                    <option value="W" <?php if($this->empl_marital_status == 'W'){ echo 'SELECTED';}?>>Widower </option>
                    <option value="WE" <?php if($this->empl_marital_status == 'WE'){ echo 'SELECTED';}?>>Widowee </option>
              </select>
          </div>
            <label for="empl_outlet" class="col-sm-2 control-label">Company <?php echo $mandatory;?></label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="empl_outlet" name="empl_outlet" style = 'width:100%'>
                   <?php echo $this->outletCrtl;?>
                 </select>
            </div>
        </div>
        <div class="form-group">
            <label for="empl_numberofchildren" class="col-sm-2 control-label">Numbers of Children</label>
            <div class="col-sm-3">
             <input type="text" class="form-control" id="empl_numberofchildren" name="empl_numberofchildren" value = "<?php echo $this->empl_numberofchildren;?>" placeholder="Numbers of Children">
            </div>
              <label for="empl_iscpf" class="col-sm-2 control-label">CPF Entitled</label>
              <div class="col-sm-3">
                 <select class="form-control select2" id="empl_iscpf" name="empl_iscpf" style = 'width:100%'>
                    <option value="1" <?php if($this->empl_iscpf == '1'){ echo 'SELECTED';}?>>Yes</option>
                    <option value="0" <?php if($this->empl_iscpf == '0'){ echo 'SELECTED';}?>>No</option>
                 </select>
              </div>
        </div>
        <div class="form-group">
            <label for="empl_income_taxid" class="col-sm-2 control-label">Income Tax ID</label>
            <div class="col-sm-3">
             <input type="text" class="form-control" id="empl_income_taxid" name="empl_income_taxid" value = "<?php echo $this->empl_income_taxid;?>" placeholder="Income Tax ID">
            </div>
              <label for="empl_cpf_account_no" class="col-sm-2 control-label">Employee CPF A/c No</label>
              <div class="col-sm-3">
               <input type="text" class="form-control" id="empl_cpf_account_no" name="empl_cpf_account_no" value = "<?php echo $this->empl_cpf_account_no;?>" placeholder="Employee CPF A/c No">
              </div>
        </div>
        <div class="form-group">
            <label for="empl_cpf_first_half" class="col-sm-2 control-label">Compute CPF First Half</label>
            <div class="col-sm-3">
                <input type="checkbox" id="empl_cpf_first_half" name="empl_cpf_first_half" <?php if($this->empl_cpf_first_half == 1){ echo 'CHECKED';}?> value = '1'>
            </div>
            <label for="empl_sld_opt_out" class="col-sm-2 control-label">SDL Opt Out</label>
            <div class="col-sm-3">
                 <input type="checkbox" id="empl_sld_opt_out" name="empl_sld_opt_out" <?php if($this->empl_sld_opt_out == 1){ echo 'CHECKED';}?> value = '1'>
            </div>
        </div>
        <div class="form-group">
            <label for="empl_fund_opt_out" class="col-sm-2 control-label">Fund OPT Out</label>
            <div class="col-sm-3">
                <input type="checkbox" id="empl_fund_opt_out" name="empl_fund_opt_out" <?php if($this->empl_fund_opt_out == 1){ echo 'CHECKED';}?> value = '1'>
            </div>
            <label for="empl_fund_first_half" class="col-sm-2 control-label">Compute Fund First Half</label>
            <div class="col-sm-3">
                 <input type="checkbox" id="empl_fund_first_half" name="empl_fund_first_half" <?php if($this->empl_fund_first_half == 1){ echo 'CHECKED';}?> value = '1'>
            </div>
        </div>
        <div class="form-group">
            <label for="empl_status" class="col-sm-2 control-label">Status</label>
            <div class="col-sm-3">
                 <select class="form-control" id="empl_status" name="empl_status">
                   <option value = '1' <?php if($this->empl_status == 1){ echo 'SELECTED';}?>>Active</option>
                   <option value = '0' <?php if($this->empl_status == 0){ echo 'SELECTED';}?>>In-active</option>
                 </select>
            </div>
              <label for="empl_isovertime" class="col-sm-2 control-label">Overtime Entitled</label>
              <div class="col-sm-3">
                 <select class="form-control select2" id="empl_isovertime" name="empl_isovertime" style = 'width:100%'>
                    <option value="1" <?php if($this->empl_isovertime == '1'){ echo 'SELECTED';}?>>Yes</option>
                    <option value="0" <?php if($this->empl_isovertime == '0'){ echo 'SELECTED';}?>>No</option>
                 </select>
              </div>
        </div>
        <div class="form-group">
          <label for="empl_remark" class="col-sm-2 control-label">Remarks</label>
          <div class="col-sm-3">
                <textarea class="form-control" rows="3" id="empl_remark" name="empl_remark" placeholder="Remark"><?php echo $this->empl_remark;?></textarea>
          </div>
            <label for="empl_tel" class="col-sm-2 control-label">Nationality</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="empl_nationality" name="empl_nationality" style = 'width:100%' >
                   <?php echo $this->nationalityCrtl;?>
                 </select>
            </div>
        </div>

        <h3>Login Information</h3>

        <div class="form-group">
              <label for="empl_login_email" class="col-sm-2 control-label">Login ID <?php echo $mandatory;?></label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="empl_login_email" name="empl_login_email" value = "<?php echo $this->empl_login_email;?>" placeholder="Login Email">
              </div>
        </div>
        <div class="form-group">
              <label for="empl_login_password" class="col-sm-2 control-label" >Password <?php echo $mandatory;?></label>
              <div class="col-sm-3">
                <input type="password" class="form-control" id="empl_login_password" name="empl_login_password" value = "<?php echo $this->empl_login_password;?>" placeholder="Password">
              </div>
        </div>
        <div class="form-group">
              <label for="empl_login_password_cm" class="col-sm-2 control-label" >Confirm Password <?php echo $mandatory;?></label>
              <div class="col-sm-3">
                <input type="password" class="form-control" id="empl_login_password_cm" name="empl_login_password_cm" value = "<?php echo $this->empl_login_password;?>" placeholder="Confirm Password">
              </div>
        </div>
    <?php
    }
    public function getContactForm(){
    ?> 
        <h3><u>Address Information</u></h3>
        <div class="form-group">
              <label for="empl_postal_code" class="col-sm-2 control-label">Postal Code 1</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" onkeyup="checkEnter()" id="empl_postal_code" name="empl_postal_code" value = "<?php echo $this->empl_postal_code;?>" placeholder="Postal Code">
              </div>
              <label for="empl_postal_code2" class="col-sm-2 control-label">Postal Code 2</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" onkeyup="checkEnter2()" id="empl_postal_code2" name="empl_postal_code2" value = "<?php echo $this->empl_postal_code2;?>" placeholder="Postal Code">
              </div>
        </div>
        <div class="form-group">
              <label for="empl_unit_no" class="col-sm-2 control-label">Unit No 1</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="empl_unit_no" name="empl_unit_no" value = "<?php echo $this->empl_unit_no;?>" placeholder="Unit No">
              </div>
              <label for="empl_unit_no2" class="col-sm-2 control-label">Unit No 2</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="empl_unit_no2" name="empl_unit_no2" value = "<?php echo $this->empl_unit_no2;?>" placeholder="Unit No">
              </div>
        </div>
        <div class="form-group">
            <label for="empl_address" class="col-sm-2 control-label">Address 1</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="empl_address" name="empl_address" placeholder="Address" readonly ><?php echo $this->empl_address;?></textarea>
            </div>
            <label for="empl_address2" class="col-sm-2 control-label">Address 2</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="empl_address2" name="empl_address2" placeholder="Address 2" readonly><?php echo $this->empl_address2;?></textarea>
            </div>
        </div>
        <h3><u>Emergency Contact Address Information</u></h3>
        <div class="form-group">
            <label for="empl_emer_contact" class="col-sm-2 control-label">Contact Person</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="empl_emer_contact" name="empl_emer_contact" value = "<?php echo $this->empl_emer_contact;?>" placeholder="Contact Person">
            </div>
            <label for="empl_emer_relation" class="col-sm-2 control-label">Relationship</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="empl_emer_relation" name="empl_emer_relation" value = "<?php echo $this->empl_emer_relation;?>" placeholder="Relationship">
            </div>
        </div>
        <div class="form-group">
            <label for="empl_emer_phone1" class="col-sm-2 control-label">Phone 1</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="empl_emer_phone1" name="empl_emer_phone1" value = "<?php echo $this->empl_emer_phone1;?>" placeholder="Phone 1">
            </div>
            <label for="empl_emer_phone2" class="col-sm-2 control-label">Phone 2</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="empl_emer_phone2" name="empl_emer_phone2" value = "<?php echo $this->empl_emer_phone2;?>" placeholder="Phone 2">
            </div>
        </div>
        <div class="form-group">
            <label for="empl_emer_address" class="col-sm-2 control-label">Address</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="empl_emer_address" name="empl_emer_address" value = "<?php echo $this->empl_emer_address;?>" placeholder="Address">
            </div>
            <label for="empl_emer_remarks" class="col-sm-2 control-label">Remarks</label>
            <div class="col-sm-3">
                <textarea class="form-control" rows="3" id="empl_emer_remarks" name="empl_emer_remarks" placeholder="Remarks"><?php echo $this->empl_emer_remarks;?></textarea>
            </div>
        </div>
    <?php
    }
    public function getJobInfoForm(){
    ?> 
        <h3><u>Job Information</u></h3>
        <div class="form-group ">
            <label for="empl_department" class="col-sm-2 control-label">Department</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="empl_department" name="empl_department" style = 'width:100%'>
                   <?php echo $this->departmentCrtl;?>
                 </select>
            </div>
            <label for="empl_designation" class="col-sm-2 control-label">Designation</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="empl_designation" name="empl_designation" style = 'width:100%'>
                   <?php echo $this->designationCrtl;?>
                 </select>
            </div>
        </div>
        <div class="form-group ">
            <label for="empl_work_time_start" class="col-sm-2 control-label">Work time (Start)</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;' >
                <input type="text" class="form-control timepicker" id="empl_work_time_start" name="empl_work_time_start" value = "<?php echo $this->empl_work_time_start;?>">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>

            <label for="empl_work_time_end" class="col-sm-2 control-label">Work time (End)</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;'>
                <input type="text" class="form-control timepicker" id="empl_work_time_end" name="empl_work_time_end" value = "<?php echo $this->empl_work_time_end;?>">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="empl_joindate" class="col-sm-2 control-label">Join Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="empl_joindate" name="empl_joindate" value = "<?php echo format_date($this->empl_joindate);?>" placeholder="Join Date">
            </div>
            <label for="empl_address2" class="col-sm-2 control-label">Probation Period</label>
            <div class="col-sm-3">
                <select class="form-control " id="empl_probation" name="empl_probation">
                    <option value = '0' <?php if($this->empl_probation == 1){ echo 'SELECTED';}?>>Select One</option>
                    <option value = '1' <?php if($this->empl_probation == 1){ echo 'SELECTED';}?>>1</option>
                    <option value = '2' <?php if($this->empl_probation == 2){ echo 'SELECTED';}?>>2</option>
                    <option value = '3' <?php if($this->empl_probation == 3){ echo 'SELECTED';}?>>3</option>
                    <option value = '4' <?php if($this->empl_probation == 4){ echo 'SELECTED';}?>>4</option>
                    <option value = '5' <?php if($this->empl_probation == 5){ echo 'SELECTED';}?>>5</option>
                    <option value = '6' <?php if($this->empl_probation == 6){ echo 'SELECTED';}?>>6</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-3">
                
            </div>
            <label for="empl_extentionprobation" class="col-sm-2 control-label">Extention of Probation</label>
            <div class="col-sm-3">
                <select class="form-control " id="empl_extentionprobation" name="empl_extentionprobation">
                    <option value = '0' <?php if($this->empl_extentionprobation == 1){ echo 'SELECTED';}?>>Select One</option>
                    <option value = '1' <?php if($this->empl_extentionprobation == 1){ echo 'SELECTED';}?>>1</option>
                    <option value = '2' <?php if($this->empl_extentionprobation == 2){ echo 'SELECTED';}?>>2</option>
                    <option value = '3' <?php if($this->empl_extentionprobation == 3){ echo 'SELECTED';}?>>3</option>
                    <option value = '4' <?php if($this->empl_extentionprobation == 4){ echo 'SELECTED';}?>>4</option>
                    <option value = '5' <?php if($this->empl_extentionprobation == 5){ echo 'SELECTED';}?>>5</option>
                    <option value = '6' <?php if($this->empl_extentionprobation == 6){ echo 'SELECTED';}?>>6</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="empl_confirmationdate" class="col-sm-2 control-label">Confirmation Date</label>
            <div class="col-sm-3">
              <input type="text" class="form-control datepicker" id="empl_confirmationdate" name="empl_confirmationdate" value = "<?php echo format_date($this->empl_confirmationdate);?>" placeholder="Confirmation Date">
            </div>
            <label for="empl_joindate" class="col-sm-2 control-label">Termination / Resignation Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="empl_resigndate" name="empl_resigndate" value = "<?php echo format_date($this->empl_resigndate);?>" placeholder="Termination / Resignation Date">
            </div>
        </div>
        <div class="form-group">
            <label for="empl_prdate" class="col-sm-2 control-label">PR Date</label>
            <div class="col-sm-3">
              <input type="text" class="form-control datepicker" id="empl_prdate" name="empl_prdate" value = "<?php echo format_date($this->empl_prdate);?>" placeholder="PR Date">
            </div>
            <label for="empl_resignreason" class="col-sm-2 control-label">Terminate Reason</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="empl_resignreason" name="empl_resignreason" placeholder="Terminate Reason"><?php echo $this->empl_remark;?></textarea>
            </div>
        </div>
        <h3><u>Alert Supervisor</u></h3>
        <?php echo $this->getApprovalPermissionForm();?>
        
    <?php
    }
    public function getBankForm(){
    ?>
        <div class="form-group">
              <label for="empl_paymode" class="col-sm-2 control-label">Pay Mode</label>
              <div class="col-sm-3">
               <select class="form-control" id="empl_paymode" name="empl_paymode" style = 'width:100%' >
                    <option value = '' <?php if($this->empl_paymode == ''){ echo 'SELECTED';}?>>Select One</option>
                    <option value = 'Cash' <?php if($this->empl_paymode == 'Cash'){ echo 'SELECTED';}?>>Cash</option>
                    <option value = 'Cheque' <?php if($this->empl_paymode == 'Cheque'){ echo 'SELECTED';}?>>Cheque</option>
               </select>
              </div>
              <label for="empl_bank" class="col-sm-2 control-label">GIRO Bank Code</label>
              <div class="col-sm-3">
                  <select class="form-control select2" id="empl_bank" name="empl_bank" style = 'width:100%'>
                        <?php echo $this->bankCrtl;?>
                  </select>
              </div>
              <div class="col-sm-1">
                  <input type="text" class="form-control" id="empl_bank_no"  value = "<?php echo $this->empl_bank_no;?>" readonly>
              </div>
        </div>
        <div class="form-group">
              <label for="empl_bank_acc_name" class="col-sm-2 control-label">GIRO Account Name</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="empl_bank_acc_name" name="empl_bank_acc_name" value = "<?php echo $this->empl_bank_acc_name;?>" placeholder="GIRO Account Name">
              </div>
              <label for="empl_bank_acc_no" class="col-sm-2 control-label">GIRO Account No.</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="empl_bank_acc_no" name="empl_bank_acc_no" value = "<?php echo $this->empl_bank_acc_no;?>" placeholder="GIRO Account No.">
              </div>
        </div>
        <div class="form-group">

        </div>
    <?php
    }
    public function getSalaryForm(){
        if($this->emplsalary_id > 0){
           $this->fetchSalaryDetail(" AND emplsalary_id = '$this->emplsalary_id'","","",1);
        }else{
           $this->emplsalary_overtime = "0.00";
           $this->emplsalary_hourly = "0.00";
           $this->emplsalary_workday = 20;
           $this->emplsalary_amount = 0;
        }
    ?>
        <?php if($this->emplsalary_id > 0){?>
        <h3>Update Employee Salary  <button type="button" class="btn btn-primary" style="width:150px;margin-right:10px;" onclick="window.location.href='empl.php?action=edit&current_tab=Salary Info&empl_id=12'">Create New Salary &nbsp; <i class="fa fa-plus-square" aria-hidden="true"></i></button></h3>
        <?php }else{?>
        <h3>Create New Employee Salary</h3> 
        <?php }?>
        <div class="form-group">
              <label for="emplsalary_date" class="col-sm-2 control-label">Date</label>
              <div class="col-sm-2">
               <input type="text" class="form-control datepicker" id="emplsalary_date" name="emplsalary_date" value = "<?php echo format_date($this->emplsalary_date);?>" placeholder="Adjustment Date">
              </div>
        </div>
        <div class="form-group">
              <label for="emplsalary_amount" class="col-sm-2 control-label">Salary ($)</label>
              <div class="col-sm-3">
                  <input type="text" style = 'text-align:right' class="form-control" id="emplsalary_amount" name="emplsalary_amount" value = "<?php echo num_format($this->emplsalary_amount);?>" placeholder="Salary ($)">
              </div>
              <label for="emplsalary_workday" class="col-sm-2 control-label">	No of Work Days in Month</label>
              <div class="col-sm-3">
               <input type="text" style = 'text-align:right' class="form-control" id="emplsalary_workday" name="emplsalary_workday" value = "<?php echo $this->emplsalary_workday;?>" placeholder="Total Working Days">
              </div>
        </div>
        <div class="form-group">
              <label for="emplsalary_hourly" class="col-sm-2 control-label">Hourly Salary Rate</label>
              <div class="col-sm-3">
                <input type="text" style = 'text-align:right' class="form-control" id="emplsalary_hourly" name="emplsalary_hourly" value = "<?php echo $this->emplsalary_hourly;?>" placeholder="Hourly Salary">
              </div>
              <label for="emplsalary_overtime" class="col-sm-2 control-label">Overtime Hourly Rate</label>
              <div class="col-sm-3">
               <input type="text" style = 'text-align:right' class="form-control" id="emplsalary_overtime" name="emplsalary_overtime" value = "<?php echo $this->emplsalary_overtime;?>" placeholder="Overtime Hourly ">
              </div>
        </div>

        <div class="form-group">
          <label for="emplsalary_remark" class="col-sm-2 control-label">Remark</label>
          <div class="col-sm-3">
                <textarea class="form-control" rows="3" id="emplsalary_remark" name="emplsalary_remark" placeholder="Remark"><?php echo $this->emplsalary_remark;?></textarea>
          </div>
          <div class="col-sm-3 "></div>
          <div class="col-sm-3 ">
              <button type = "button" class="btn btn-info save_salary_btn" >
                  <?php if($this->emplsalary_id > 0){?>
                  Update
                  <?php }else{?>
                  Save
                  <?php }?>
              </button>
              <input type = 'hidden' value = '<?php echo $this->emplsalary_id;?>' name = 'emplsalary_id' id = 'emplsalary_id'/>
          </div>
        </div>
        <table id="empl_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:10%'>Salary ($)</th>
                        <th style = 'width:25%'>Remark</th>
                        <th style = 'width:10%'>Update By</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT ey.*
                              FROM db_emplsalary ey 
                              WHERE ey.emplsalary_empl_id = '{$this->empl_id}' AND ey.emplsalary_status = 1";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo format_date($row['emplsalary_date']);?></td>
                            <td><?php echo num_format($row['emplsalary_amount']);?></td>
                            <td><?php echo nl2br($row['emplsalary_remark']);?></td>
                            <td><?php echo getDataCodeBySql("empl_name","db_empl","WHERE  empl_id = '{$row['updateBy']}'","");?></td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'empl.php?action=edit&current_tab=Salary Info&empl_id=<?php echo $this->empl_id;?>&emplsalary_id=<?php echo $row['emplsalary_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('empl.php?action=deletesalary&current_tab=Salary Info&empl_id=<?php echo $this->empl_id;?>&emplsalary_id=<?php echo $row['emplsalary_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:10%'>Salary ($)</th>
                        <th style = 'width:25%'>Remark</th>
                        <th style = 'width:10%'>Update By</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </tfoot>
                  </table>
    <?php
    }
    public function getForeignWorkerForm(){
    ?> 
        <h3><u>Work Permit Information</u></h3>
        <div class="form-group">
            <label for="empl_work_permit" class="col-sm-2 control-label">Work Permit Number</label>
            <div class="col-sm-3">
                  <input type="text" class="form-control" id="empl_work_permit" name="empl_work_permit" value = "<?php echo $this->empl_work_permit;?>" placeholder="Work Permit Number">
            </div>
            <label for="empl_work_permit_date_arrival" class="col-sm-2 control-label">Date of Arrival</label>
            <div class="col-sm-3">
                  <input type="text" class="form-control datepicker" id="empl_work_permit_date_arrival" name="empl_work_permit_date_arrival" value = "<?php echo format_date($this->empl_work_permit_date_arrival);?>" placeholder="Date of Arrival">
            </div>
        </div>
        <div class="form-group">
            <label for="empl_work_permit_application_date" class="col-sm-2 control-label">Application Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="empl_work_permit_application_date" name="empl_work_permit_application_date" value = "<?php echo $this->empl_work_permit_application_date;?>" placeholder="Application Date">
            </div>
            <label for="empl_pass_issuance" class="col-sm-2 control-label">Issue Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="empl_pass_issuance" name="empl_pass_issuance" value = "<?php echo format_date($this->empl_pass_issuance);?>" placeholder="Pass Issuance Date">
            </div>
        </div>
        <div class="form-group">
            <label for="empl_pass_renewal" class="col-sm-2 control-label">Renewal Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="empl_pass_renewal" name="empl_pass_renewal" value = "<?php echo format_date($this->empl_pass_renewal);?>" placeholder="Pass Renewal Date">
            </div>
            <label for="empl_levy_amt" class="col-sm-2 control-label">Levy Amount</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="empl_levy_amt" name="empl_levy_amt" value = "<?php echo $this->empl_levy_amt;?>" placeholder="Levy Amount">
            </div>
        </div>
    <?php
    }
    public function getLeaveForm(){
    ?>
        <div class="form-group">
            <label for="emplleave_leavetype" class="col-sm-2 control-label">Leave Title</label>
            <label for="emplleave_leavetype" class="col-sm-1 control-label">Hide</label>
            <label for="emplleave_leavetype" class="col-sm-3 control-label">Balance (<?php echo date("Y");?>)</label>
            <label for="emplleave_leavetype" class="col-sm-3 control-label">Entitled (<?php echo date("Y");?>)</label>
            <?php if($this->empl_id > 0){?>
            <label for="emplleave_leavetype" class="col-sm-3 control-label">Balance (<?php echo date("Y")-1;?>)</label>
            <?php }?>
        </div>
        <?php    
        $year = date("Y");
        $sql = "SELECT lt.*,el.emplleave_days,el.emplleave_id,el.emplleave_disabled
                FROM db_leavetype lt
                LEFT JOIN db_emplleave el ON el.emplleave_leavetype = lt.leavetype_id AND el.emplleave_empl = '$this->empl_id' AND el.emplleave_year = '$year'
                WHERE lt.leavetype_status = 1 
                GROUP BY lt.leavetype_id 
                ORDER BY lt.leavetype_seqno,lt.leavetype_code ";

        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            if($this->empl_id <=0 ){
                $row['emplleave_days'] = $row['leavetype_default'];
            }
        ?>
        <div class="form-group">
              <label for="emplleave_leavetype" class="col-sm-2 control-label"><?php echo $row['leavetype_code'];?></label>
              <div class="col-sm-1">
                  <input type="checkbox" id="emplleave_disabled" name="emplleave_disabled[<?php echo $row['leavetype_id'];?>]" value = "1" <?php if($row['emplleave_disabled'] == 1){ echo 'CHECKED';}?>>
              </div>
              <div class="col-sm-3">
                <input type="hidden" class="form-control" id="emplleave_leavetype" name="emplleave_leavetype[]" value = "<?php echo $row['leavetype_id'];?>">  
                <input type="hidden" class="form-control" id="emplleave_id" name="emplleave_id[]" value = "<?php echo $row['emplleave_id'];?>">  
                <input type="text" class="form-control" id="emplleave_days" name="emplleave_days[]" value = "<?php echo $row['emplleave_days'];?>" placeholder="Days">
              </div>
              <div class="col-sm-3">
                <?php
                if($this->empl_id > 0){
                $balance1 = "0.00";
                $year1 = date("Y");
                if(getDataCodeBySql("COALESCE(emplleave_entitled,0)","db_emplleave"," WHERE emplleave_empl = '$this->empl_id' AND emplleave_leavetype = '{$row['leavetype_id']}' AND emplleave_year = '$year1' AND emplleave_status = '1'", $orderby)){
                    $balance1 = getDataCodeBySql("COALESCE(emplleave_entitled,0)","db_emplleave"," WHERE emplleave_empl = '$this->empl_id' AND emplleave_leavetype = '{$row['leavetype_id']}' AND emplleave_year = '$year1'", $orderby);
                }
                }else{
                    $balance1 = $row['emplleave_days'];
                }
                ?>
                <input type="text" class="form-control" id="emplleave_entitled" name="emplleave_entitled[]" value = "<?php echo $balance1;?>" placeholder="Days" >
              </div>
              <?php if($this->empl_id > 0){?>
              <div class="col-sm-3">
                <?php
                $balance2 = "0.00";
                $year2 = date("Y")-1;
                if(getDataCodeBySql("COALESCE(emplleave_days,0)","db_emplleave"," WHERE emplleave_empl = '$this->empl_id' AND emplleave_leavetype = '{$row['leavetype_id']}' AND emplleave_year = '$year2' AND emplleave_status = '1'", $orderby)){
                    $balance2 = getDataCodeBySql("COALESCE(emplleave_days,0)","db_emplleave"," WHERE emplleave_empl = '$this->empl_id' AND emplleave_leavetype = '{$row['leavetype_id']}' AND emplleave_year = '$year2'", $orderby);
                }
                
                ?>
                <input type="text" class="form-control" value = "<?php echo $balance2;?>" placeholder="Days" READONLY>
              </div>
              <?php }?>

        </div>
        <?php
        }
    }
    public function getApprovalPermissionForm(){
    ?>
            <div class="form-group">
                <div class="col-sm-5">
                <h4>Leave</h4>
                </div>
                <div class="col-sm-5">
                <h4>Claims</h4>
                </div>
            </div>
            <div class="form-group">
                <label for="empl_leave_approved1" class="col-sm-2 control-label">Approved level 1</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="empl_leave_approved1" name="empl_leave_approved1" style = 'width:100%' >
                        <?php echo $this->emplLeaveApproved1Crtl;?>
                    </select>
                </div>
                <label for="empl_claims_approved1" class="col-sm-2 control-label">Approved level 1</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="empl_claims_approved1" name="empl_claims_approved1" style = 'width:100%' >
                        <?php echo $this->emplClaimsApproved1Crtl;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="empl_leave_approved2" class="col-sm-2 control-label">Approved level 2</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="empl_leave_approved2" name="empl_leave_approved2" style = 'width:100%' >
                        <?php echo $this->emplLeaveApproved2Crtl;?>
                    </select>
                </div>
                <label for="empl_claims_approved2" class="col-sm-2 control-label">Approved level 2</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="empl_claims_approved2" name="empl_claims_approved2" style = 'width:100%' >
                        <?php echo $this->emplClaimsApproved2Crtl;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="empl_leave_approved3" class="col-sm-2 control-label">Approved level 3</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="empl_leave_approved3" name="empl_leave_approved3" style = 'width:100%' >
                        <?php echo $this->emplLeaveApproved3Crtl;?>
                    </select>
                </div>
                <label for="empl_claims_approved3" class="col-sm-2 control-label">Approved level 3</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="empl_claims_approved3" name="empl_claims_approved3" style = 'width:100%' >
                        <?php echo $this->emplClaimsApproved3Crtl;?>
                    </select>
                </div>
            </div>
    <?php
    }
    public function getListing(){
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Employee Management</title>
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
            <h1>Employee Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Employee Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='empl.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table id="empl_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:25%'>Name</th>
                        <th style = 'width:15%'>Email</th>
                        <th style = 'width:10%'>Mobile</th>
                        <th style = 'width:8%'>User Right</th>
                        <th style = 'width:8%'>Department</th>
                        <th style = 'width:8%'>Company</th>
                        <th style = 'width:8%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT empl.*,gp.group_code,dp.department_code,outl.outl_code
                              FROM db_empl empl 
                              INNER JOIN db_group gp ON gp.group_id = empl.empl_group
                              LEFT JOIN db_department dp ON dp.department_id = empl.empl_department
                              LEFT JOIN db_outl outl ON outl.outl_id = empl.empl_outlet
                              WHERE empl.empl_id > 0 AND empl.empl_status = '1' AND empl.empl_client = '0'
                              ORDER BY empl.empl_seqno,empl.empl_name";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['empl_name'];?></td>
                            <td><?php echo $row['empl_email'];?></td>
                            <td><?php echo $row['empl_mobile'];?></td>
                            <td><?php echo $row['group_code'];?></td>
                            <td><?php echo $row['department_code'];?></td>
                            <td><?php echo $row['outl_code'];?></td>
                            <td><?php if($row['empl_status'] == 1){ echo 'Active';}else{ echo 'In-Active';}?>
                            <input type="hidden" value = "<?php echo print_r($row); ?>"> 
                            </td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'view')){
                                ?>
                                <!--<button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'empl.php?action=view&empl_id=<?php echo $row['empl_id'];?>'">View</button>-->
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'empl.php?action=edit&empl_id=<?php echo $row['empl_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('empl.php?action=delete&empl_id=<?php echo $row['empl_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:25%'>Name</th>
                        <th style = 'width:15%'>Email</th>
                        <th style = 'width:10%'>Mobile</th>
                        <th style = 'width:8%'>User Right</th>
                        <th style = 'width:8%'>Department</th>
                        <th style = 'width:8%'>Company</th>
                        <th style = 'width:8%'>Status</th>
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
        $('#empl_table').DataTable({
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
    public function email($staff_email){
    
ob_start();
?>
<html>
<head>
  <title>Welcome Abroad FCS</title>
</head>
<body>
 <p>Hi New Staff,</p>

<p>Welcome aboard! Please find the attached forms for your usage.</p>

<p><b>Leaves</b>: All leaves are to be applied online via <a href = 'http://10.1.1.252:90/index.aspx' target = '_blank'>http://10.1.1.252:90/index.aspx</a>, 2 weeks in advance.</p>
<p>Regardless paid or unpaid leaves, it will be subjected to approval.</p>
<p>HR will send in a separate email on the log in credentials to you.</p>
<br>
<p><b>Claims</b>: Transport claims have to be submitted on the last day of every month.</p>
<p>All original receipts have to be kept, photocopied and attached to the claim form.</p>
<br>
<p>The above forms are to be submitted to the HR-in charge.</p> 
<br>
<p><b>Pay Day</b>: All salaries will be issued on the 7th of every month via giro.</p>
<br>
<p><b>Access Card</b>: Please remember to tap your access card every day as it tracks your attendance.</p>
<p>For those who forget to tap, on MC or on urgent leave, please record it down to your own online calendar at <b>Website</b>: <a href = 'http://cal.firstcom.com.sg/SOGo/' target = '_blank'>http://cal.firstcom.com.sg/SOGo/</a> </p>
<p>Please look for HR-in charge to subscribe your personal online calendar.</p>
<p><b><u>You are not allowed to pass your access card to anyone to tap for you.</u></b></p>
<br>
<p><b>SSC Form</b> : All new orders are to be submitted with the Sales Submission Checklist & any other relevant documents needed to be process. </p>
<p>There will be no clocking of GP should there be any lack of documents.</p>
<p>Please get advice from your team leader if you are unsure. </p>
<br>
<p><b>For Sales/Project Management Team - Quotation/Invoice Template</b> (Make sure company name, address and item description is in CAPS)</p>
<br>
<p>Eg.</p> 
<br>
<p>Company name - SKM LUGGAGE PTE LTD</p>
<br>
<table>
    <tr>
        <td rowspan = '4' style = 'vertical-align:top'>Address - </td>
    </tr>
    <tr>
        <td>NO.5 KAKI BUKIT ROAD 2,</td>
    </tr>
    <tr>
        <td>#01-07 & 02-07, CITY WAREHOUSE,</td>
    </tr>
    <tr>
        <td>SINGAPORE 417839</td>
    </tr>
</table>
<br>

<table>
    <tr>
        <td rowspan = '3' style = 'vertical-align:top'  >Item - </td>
    </tr>
    <tr>
        <td>CMS WEBSITE= $2800,</td>
    </tr>
    <tr>
        <td>SERVER RENTAL= $598 (2 years) (5GB)</td>
    </tr>
</table>
<br>
           
<p>Total = $3398</p>
<br>
<p>Payment = 100%</p>
<br>
<p><b>MC/ Urgent Leave</b>: Please report to your respective team leaders and Gwen at 96714536. Take note of your leader's contact no. on your first day. Such reports are to be informed <b>before</b> your working hours.</p>
<br>
<p><b>Last Day of Work</b>: Please fill up the clearance form and return access card and laptop to the management on your last day of work.</p>
<br>
<p>Lastly, please be reminded to recycle paper. Set to <i>Print on Both Sides</i> when required. No color images is allowed.</p>
<br>
</body>
</html>
<?php
$message = ob_get_clean(); 
    
    $my_file = "Staff Claim Form.xlsx";
    $my_path = "";
    $my_name = "Gwendolyn Wu | FirstCom Solutions";
    $my_mail = "gwendolynwu@firstcom.com.sg";
    $my_replyto = "my_reply_to@mail.net";
    $my_subject = "Welcome Abroad FCS";
        

    //mail_attachment($my_file,$my_path,$staff_email,$my_mail,$my_name, $my_replyto,$my_subject,$message);

    // Mail it
//    mail("jasonchong@firstcom.com.sg", $subject, $message, $headers);
    }
    public function getApprovelEmail($type,$empl_id){

        if($_SESSION['empl_group'] == "5" || $_SESSION['empl_group']==9){
            $sql = "SELECT applicant_{$type}_approved1,applicant_{$type}_approved2,applicant_{$type}_approved3
                    FROM db_applicant 
                    WHERE applicant_id = '$empl_id'";
            $query = mysql_query($sql);
            $i = 0;
            if($row = mysql_fetch_array($query)){
                $level1_id = $row["applicant_{$type}_approved1"];
                $level2_id = $row["applicant_{$type}_approved2"];
                $level3_id = $row["applicant_{$type}_approved3"];
                $level1_email = getDataCodeBySql("empl_email","db_empl"," WHERE empl_id = '$level1_id'");
                $level2_email = getDataCodeBySql("empl_email","db_empl"," WHERE empl_id = '$level2_id'");
                $level3_email = getDataCodeBySql("empl_email","db_empl"," WHERE empl_id = '$level3_id'");

                if($level1_id > 0){
                    $i++;
                }
                if($level2_id > 0){
                    $i++;
                }
                if($level3_id > 0){
                    $i++;
                }

                return array('level1_id'=>$level1_id,'level2_id'=>$level2_id,'level3_id'=>$level3_id,
                             'level1_email'=>$level1_email,'level2_email'=>$level2_email,'level3_email'=>$level3_email,
                             'total_need_approved'=>$i);
            }
            else{
                return null;
            }            
        }
        else{
            $sql = "SELECT empl_{$type}_approved1,empl_{$type}_approved2,empl_{$type}_approved3
                    FROM db_empl 
                    WHERE empl_id = '$empl_id'";
            $query = mysql_query($sql);
            $i = 0;
            if($row = mysql_fetch_array($query)){
                $level1_id = $row["empl_{$type}_approved1"];
                $level2_id = $row["empl_{$type}_approved2"];
                $level3_id = $row["empl_{$type}_approved3"];
                $level1_email = getDataCodeBySql("empl_email","db_empl"," WHERE empl_id = '$level1_id'");
                $level2_email = getDataCodeBySql("empl_email","db_empl"," WHERE empl_id = '$level2_id'");
                $level3_email = getDataCodeBySql("empl_email","db_empl"," WHERE empl_id = '$level3_id'");

                if($level1_id > 0){
                    $i++;
                }
                if($level2_id > 0){
                    $i++;
                }
                if($level3_id > 0){
                    $i++;
                }

                return array('level1_id'=>$level1_id,'level2_id'=>$level2_id,'level3_id'=>$level3_id,
                             'level1_email'=>$level1_email,'level2_email'=>$level2_email,'level3_email'=>$level3_email,
                             'total_need_approved'=>$i);
            }else{
                return null;
            }
        }

    }
    public function checkAccess($action){
        global $master_group;
        if(($action == 'createForm') || ($action == 'create') || ($action == '')){
            return true;
        }
        if(!in_array($_SESSION['empl_group'],$master_group)){
            $empl_id = getDataCodeBySql("empl_id","db_empl"," WHERE empl_id = '$this->empl_id'","");
            $record_count = getDataCountBySql("db_empl"," WHERE empl_id = '$empl_id'");

            if(((($empl_id <= 0) && ($record_count == 0)) || ($empl_id != $_SESSION['empl_id']))){
                permissionLog();
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
                exit();
            }
        }
    }
    public function getBankCode(){
        $sql = "SELECT * FROM db_bank WHERE bank_id = '$this->empl_bank'";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
            $bank_no = $row['bank_no'];
        }
        
        return $bank_no;
    }
}

?>
