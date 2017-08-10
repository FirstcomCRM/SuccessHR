<?php
/*
 * To change this tapplicantate, choose Tools | Tapplicantates
 * and open the tapplicantate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class WalkInApplicant {

    public function WalkInApplicant(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();

    }
    public function create(){
        $this->applicant_login_password = md5("@#~x?\$" . $this->applicant_login_password . "?\$");
        $table_field = array('applicant_code','applicant_name','applicant_nric','applicant_tel','applicant_birthday',
                             'applicant_group','applicant_joindate','applicant_address','applicant_black_list',
                             'applicant_height', 'applicant_weight',
                             'applicant_login_email','applicant_login_password','applicant_seqno','applicant_status',
                             'applicant_outlet','applicant_email','applicant_department','applicant_bank',
                             'applicant_bank_acc_no','applicant_nationality',
                             'applicant_mobile','applicant_position',
            
                             'applicant_marital_status','applicant_religion','applicant_sex',
                             'applicant_race',
                             'applicant_address2','applicant_emer_contact','applicant_emer_relation','applicant_emer_phone1','applicant_emer_phone2',
                             'applicant_emer_address','applicant_emer_remarks','applicant_probation','applicant_prdate','applicant_resignreason',
                             'applicant_paymode','applicant_bank_acc_name','applicant_numberofchildren','applicant_isovertime',
                             'applicant_work_time_start','applicant_work_time_end',
            
                              'referee_name1', 'referee_occupation1', 'referee_year_know1', 'referee_contact_no1',
                              'referee_name2', 'referee_occupation2', 'referee_year_know2', 'referee_contact_no2',
                              'referee_present_employer', 'referee_previous_employer',
                              'declaration_bankrupt','declaration_physical','declaration_lt_medical','declaration_law','declaration_warning','declaration_applied','tc_date',
                              'overall_impression', 'communication_skill', 'other_comments', 'official_consultant','official_date','official_time',
            );
        $table_value = array(get_prefix_value("Applicant code",true),$this->applicant_name,$this->applicant_nric,$this->applicant_tel,format_date_database($this->applicant_birthday),
                             5,format_date_database($this->applicant_joindate),$this->applicant_address,$this->applicant_black_list,
                             $this->applicant_height, $this->applicant_weight,
                             $this->applicant_login_email,$this->applicant_login_password,$this->applicant_seqno,$this->applicant_status,
                             $this->applicant_outlet,$this->applicant_email,$this->applicant_department,$this->applicant_bank,
                             $this->applicant_bank_acc_no,$this->applicant_nationality,
                             $this->applicant_mobile,$this->applicant_position,

                             $this->applicant_marital_status,$this->applicant_religion,$this->applicant_sex,
                             $this->applicant_race,
                             $this->applicant_address2,$this->applicant_emer_contact,$this->applicant_emer_relation,$this->applicant_emer_phone1,$this->applicant_emer_phone2,
                             $this->applicant_emer_address,$this->applicant_emer_remarks,$this->applicant_probation,format_date_database($this->applicant_prdate),$this->applicant_resignreason,
                             $this->applicant_paymode,$this->applicant_bank_acc_name,$this->applicant_numberofchildren,$this->applicant_isovertime,
                             $this->applicant_work_time_start,$this->applicant_work_time_end,
            
                             $this->referee_name1,  $this->referee_occupation1,  $this->referee_year_know1,  $this->referee_contact_no1,
                             $this->referee_name2,  $this->referee_occupation2,  $this->referee_year_know2,  $this->referee_contact_no2,
                             $this->referee_present_employer,  $this->referee_previous_employer,
                             $this->declaration_bankrupt, $this->declaration_physical, $this->declaration_lt_medical, $this->declaration_law, $this->declaration_warning, $this->declaration_applied, format_date_database($this->tc_date),
                             $this->overall_impression, $this->communication_skills, $this->other_comments, $this->official_consultant, format_date_database($this->official_date), $this->official_time,
                );
    
        $remark = "Insert Applicant.";
        $familyremark = "Insert Family.";

        if(!$this->save->SaveData($table_field,$table_value,'db_applicant','applicant_id',$remark)){
           return false;
        }else{
           $this->applicant_id = $this->save->lastInsert_id;
           $this->pictureManagement();
        
           return true;
        }
        if(!$this->save->SaveData($family_table_field, $applicantfamily_table_value, 'db_family','family_id', $familyremark)){
           return false;
        }else{
           $this->family_id = $this->save->lastInsert_id;
           $this->pictureManagement();
           $this->resumeManagement();

           return true;
        }
    }
    public function update(){
        $new_password = $this->applicant_login_password;
        $applicant_id = $this->applicant_id;
        $applicant_login_email = $this->applicant_login_email;

        if($this->applicant_oldpassword != $new_password){
          $this->applicant_login_password = md5("@#~x?\$" . $new_password . "?\$");
        }

        $table_field = array('applicant_code','applicant_name','applicant_nric','applicant_tel','applicant_birthday',
                             'applicant_group','applicant_joindate','applicant_address','applicant_black_list',
                             'applicant_height', 'applicant_weight',
                             'applicant_login_email','applicant_login_password','applicant_seqno','applicant_status',
                             'applicant_outlet','applicant_email','applicant_department','applicant_bank',
                             'applicant_bank_acc_no','applicant_nationality',
                             'applicant_mobile','applicant_position',
            
                             'applicant_marital_status','applicant_religion','applicant_sex',
                             'applicant_race',
                             'applicant_address2','applicant_emer_contact','applicant_emer_relation','applicant_emer_phone1','applicant_emer_phone2',
                             'applicant_emer_address','applicant_emer_remarks','applicant_probation','applicant_prdate','applicant_resignreason',
                             'applicant_paymode','applicant_bank_acc_name','applicant_numberofchildren','applicant_isovertime',
                             'applicant_work_time_start','applicant_work_time_end',
            
                              'referee_name1', 'referee_occupation1', 'referee_year_know1', 'referee_contact_no1',
                              'referee_name2', 'referee_occupation2', 'referee_year_know2', 'referee_contact_no2',
                              'referee_present_employer', 'referee_previous_employer',
                              'declaration_bankrupt','declaration_physical','declaration_lt_medical','declaration_law','declaration_warning','declaration_applied','tc_date',
                              'overall_impression', 'communication_skill', 'other_comments', 'official_consultant','official_date','official_time'
            
            );
        $table_value = array(get_prefix_value("Applicant code",true),$this->applicant_name,$this->applicant_nric,$this->applicant_tel,format_date_database($this->applicant_birthday),
                             5,format_date_database($this->applicant_joindate),$this->applicant_address,$this->applicant_black_list,
                             $this->applicant_height, $this->applicant_weight,
                             $this->applicant_login_email,$this->applicant_login_password,$this->applicant_seqno,$this->applicant_status,
                             $this->applicant_outlet,$this->applicant_email,$this->applicant_department,$this->applicant_bank,
                             $this->applicant_bank_acc_no,$this->applicant_nationality,
                             $this->applicant_mobile,$this->applicant_position,

                             $this->applicant_marital_status,$this->applicant_religion,$this->applicant_sex,
                             $this->applicant_race,
                             $this->applicant_address2,$this->applicant_emer_contact,$this->applicant_emer_relation,$this->applicant_emer_phone1,$this->applicant_emer_phone2,
                             $this->applicant_emer_address,$this->applicant_emer_remarks,$this->applicant_probation,format_date_database($this->applicant_prdate),$this->applicant_resignreason,
                             $this->applicant_paymode,$this->applicant_bank_acc_name,$this->applicant_numberofchildren,$this->applicant_isovertime,
                             $this->applicant_work_time_start,$this->applicant_work_time_end,
         
                             $this->referee_name1,  $this->referee_occupation1,  $this->referee_year_know1,  $this->referee_contact_no1,
                             $this->referee_name2,  $this->referee_occupation2,  $this->referee_year_know2,  $this->referee_contact_no2,
                             $this->referee_present_employer,  $this->referee_previous_employer,
                             $this->declaration_bankrupt, $this->declaration_physical, $this->declaration_lt_medical, $this->declaration_law, $this->declaration_warning, $this->declaration_applied, format_date_database($this->tc_date),
                             $this->overall_impression, $this->communication_skills, $this->other_comments, $this->official_consultant, format_date_database($this->official_date), $this->official_time
            );    
    
        $remark = "Update Applicant.";
        $familyremark = "Update Family.";

        if(!$this->save->UpdateData($table_field,$table_value,'db_applicant','applicant_id',$remark,$this->applicant_id)){
           return false;
        }else{
           $this->pictureManagement();
           return true;
        }
    }
    public function createLeave($applicantleave_leavetype,$applicantleave_days,$applicantleave_disabled,$applicantleave_entitled){
        $table_field = array('applicantleave_applicant','applicantleave_leavetype','applicantleave_days','applicantleave_year','applicantleave_status','applicantleave_disabled','applicantleave_entitled');
        $table_value = array($this->applicant_id,$applicantleave_leavetype,$applicantleave_days,date("Y"),1,$applicantleave_disabled,$applicantleave_entitled);
        $remark = "Create Applicant Leaves.";
        if(!$this->save->SaveData($table_field,$table_value,'db_applicantleave','applicantleave_id',$remark)){
           
        }else{
          
        }
    }
    public function updateLeave($applicantleave_days,$applicantleave_id,$applicantleave_disabled,$applicantleave_entitled){


        $table_field = array('applicantleave_days','applicantleave_disabled','applicantleave_entitled');
        $table_value = array($applicantleave_days,$applicantleave_disabled,$applicantleave_entitled);
        $remark = "Update Applicant Leaves.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_applicantleave','applicantleave_id',$remark,$applicantleave_id," AND applicantleave_applicant = '$this->applicant_id'")){
           return false;
        }else{
           return true;
        }
    }

    public function addFamily(){
        $table_field = array('family_id','family_name','family_relationship','family_contact_no','family_age',
                             'family_occupation','applicant_family_id');
        $table_value = array('',$this->appfamily_name,$this->appfamily_relation,$this->appfamily_age,$this->appfamily_contact,
                             $this->appfamily_occupation, $this->applicant_id);
        $remark = "Add Family.";
        if(!$this->save->SaveData($table_field,$table_value,'db_family','family_id',$remark)){
            return false;
        }else{
            return true;
        }
    }
    public function updateFamily(){

        $table_field = array('family_id','family_name','family_relationship','family_contact_no','family_age',
                             'family_occupation','applicant_family_id');
        $table_value = array('',$this->appfamily_name,$this->appfamily_relation,$this->appfamily_age,$this->appfamily_contact,
                             $this->appfamily_occupation, $this->applicant_id);
        $remark = "Update Family";
        if(!$this->save->UpdateData($table_field,$table_value,'db_family','family_id',$remark,$this->applicantsalary_id," AND applicantsalary_applicant_id = '$this->applicant_id'")){
           return false;
        }else{
           return true;
        }
    }
    public function pictureManagement(){
        if(!file_exists("dist/images/applicant")){
           mkdir('dist/images/applicant/');
        }
        $isimage = false;
        if($this->image_input['type'] == 'image/png' || $this->image_input['type'] == 'image/jpeg' || $this->image_input['type'] == 'image/gif'){
           $isimage = true;
        }

        if($this->image_input['size'] > 0 && $isimage == true){
            if($this->action == 'update'){
                unlink("dist/images/applicant/{$this->applicant_id}.png");
            }

                move_uploaded_file($this->image_input['tmp_name'],"dist/images/applicant/{$this->applicant_id}.png");
        }
    }
    public function fetchApplicantDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_applicant WHERE applicant_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->applicant_id = $row['applicant_id'];
            $this->applicant_code = $row['applicant_code'];
            $this->applicant_name = $row['applicant_name'];
            $this->applicant_nric = $row['applicant_nric'];
            $this->applicant_tel = $row['applicant_tel'];
            $this->applicant_mobile = $row['applicant_mobile'];
            $this->applicant_email = $row['applicant_email'];
            $this->applicant_address = $row['applicant_address'];
            $this->applicant_remark = $row['applicant_remark'];
            $this->applicant_black_list = $row['applicant_black_list'];
            $this->applicant_birthday = $row['applicant_birthday'];
            $this->applicant_joindate = $row['applicant_joindate'];
            $this->applicant_group = $row['applicant_group'];
            $this->applicant_seqno = $row['applicant_seqno'];
            $this->applicant_outlet = $row['applicant_outlet'];
            $this->applicant_status = $row['applicant_status'];
            $this->applicant_login_email = $row['applicant_login_email'];
            $this->applicant_login_password = $row['applicant_login_password'];
            $this->applicant_department = $row['applicant_department'];
            $this->applicant_bank = $row['applicant_bank'];
            $this->applicant_bank_acc_no = $row['applicant_bank_acc_no'];
            $this->applicant_nationality = $row['applicant_nationality'];
            
            $this->applicant_marital_status = $row['applicant_marital_status'];
            $this->applicant_religion = $row['applicant_religion'];
            $this->applicant_sex = $row['applicant_sex'];
            $this->applicant_race = $row['applicant_race'];
            $this->applicant_address2 = $row['applicant_address2'];
            $this->applicant_emer_contact = $row['applicant_emer_contact'];
            $this->applicant_emer_relation = $row['applicant_emer_relation'];
            $this->applicant_emer_phone1 = $row['applicant_emer_phone1'];
            $this->applicant_emer_phone2 = $row['applicant_emer_phone2'];
            $this->applicant_emer_address = $row['applicant_emer_address'];
            
            $this->applicant_emer_remarks = $row['applicant_emer_remarks'];
            $this->applicant_probation = $row['applicant_probation'];
            $this->applicant_prdate = $row['applicant_prdate'];
            $this->applicant_resignreason = $row['applicant_resignreason'];
            $this->applicant_paymode = $row['applicant_paymode'];
            $this->applicant_bank_acc_name = $row['applicant_bank_acc_name'];
            $this->applicant_numberofchildren = $row['applicant_numberofchildren'];
            $this->applicant_isovertime = $row['applicant_isovertime'];
            $this->applicant_work_time_start = $row['applicant_work_time_start'];
            
            $this->referee_name1 = $row['referee_name1'];
            $this->referee_occupation1 = $row['referee_occupation1'];
            $this->referee_year_know1 = $row['referee_year_know1'];
            $this->referee_contact_no1 = $row['referee_contact_no1'];
            $this->referee_name2 = $row['referee_name2'];
            $this->referee_occupation2 = $row['referee_occupation2'];
            $this->referee_year_know2 = $row['referee_year_know2'];
            $this->referee_contact_no2 = $row['referee_contact_no2'];
            $this->referee_present_employer = $row['referee_present_employer'];
            $this->referee_previous_employer = $row['referee_previous_employer'];
    
            $this->declaration_bankrupt = $row['declaration_bankrupt'];
            $this->declaration_physical = $row['declaration_physical'];
            $this->declaration_lt_medical = $row['declaration_lt_medical'];
            $this->declaration_law = $row['declaration_law'];
            $this->declaration_warning = $row['declaration_warning'];
            $this->declaration_applied = $row['declaration_applied'];
                       
            $this->overall_impression = $row['overall_impression'];
            $this->communication_skills = $row['communication_skills'];
            $this->other_comments = $row['other_comments'];
            $this->official_consultant = $row['official_consultant'];
            $this->official_date = $row['official_date'];
            $this->official_time = $row['official_time'];            
            
            $this->applicant_height = $row['applicant_height'];
            $this->applicant_weight = $row['applicant_weight'];
            
            $this->tc_date = $row['tc_date'];
            
            $this->applicant_work_time_end = $row['applicant_work_time_end'];
            $this->applicant_position = $row['applicant_position'];
            
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }
       
    }

    public function delete(){
        $table_field = array('applicant_status');
        $table_value = array(0);
        $remark = "Delete Applicant.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_applicant','applicant_id',$remark,$this->applicant_id)){
           return false;
        }else{
           return true;
        }
    }
    public function deleteFamily(){
        $sql = "DELETE FROM db_family WHERE family_id = $this->family_id";
        mysql_query($sql);
        return true;
    }
    public function getInputForm($action){
        global $mandatory; 
        if($action == 'create'){
            $this->applicant_seqno = 10;
            $this->applicant_code = "-- System Generate --";
            $this->applicant_status = 1;
        }
        $this->nationalityCrtl = $this->select->getNationalitySelectCtrl($this->applicant_nationality);
        $this->bankCrtl = $this->select->getBankSelectCtrl($this->applicant_bank);
        $this->groupCrtl = $this->select->getGroupSelectCtrl($this->applicant_group);
        $this->outletCrtl = $this->select->getOutletSelectCtrl($this->applicant_outlet);
        $this->departmentCrtl = $this->select->getDepartmentSelectCtrl($this->applicant_department);
        $this->clientCrtl = $this->select->getClientSelectCtrl($this->interview_company);
        $this->religionCrtl = $this->select->getReligionSelectCtrl($this->applicant_religion);
        $this->raceCrtl = $this->select->getRaceSelectCtrl($this->applicant_race);
        $this->designationCrtl = $this->select->getDesignationSelectCtrl($this->applicant_designation);
    
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Applicant Management</title>
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
            <h1>Walk In Candidate Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->applicant_id > 0){ echo "Update Candidate";}else{ echo "Walk In Candidate";}?></h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <?php }?>
              </div>
                <form id = 'applicant_form' class="form-horizontal" action = 'walkinapplicant.php?action=create' method = "POST" enctype="multipart/form-data">
                    <input type ='hidden' name = 'current_tab' id = 'current_tab' value = "<?php echo $this->current_tab?>"/>
                  <div class="box-body">
                      
                      <div class="nav-tabs-custom">
                      <?php if($this->applicant_id > 0){ ?>
                        <ul class="nav nav-tabs">
                          <li tab = "General Info" class="tab_header <?php if(($this->current_tab == "") || ($this->current_tab == "General Info")){ echo 'active';}?>"><a href="#general" data-toggle="tab">General Info</a></li>
                          <li tab = "Contact Info" class="tab_header <?php if($this->current_tab == "Contact Info"){ echo 'active';}?>" ><a href="#contact" data-toggle="tab">Contact Info</a></li>
<!--                          <li tab = "Job Info" class="tab_header <?php if($this->current_tab == "Job Info"){ echo 'active';}?>" ><a href="#job_info" data-toggle="tab">Job Info</a></li>-->
                          <li tab = "Bank Info" class="tab_header <?php if($this->current_tab == "Bank Info"){ echo 'active';}?>" ><a href="#bank" data-toggle="tab">Bank Info</a></li>
                          <?php // if($this->applicant_id > 0){?>
                         <li tab = "family" class="tab_header <?php if($this->current_tab == "family"){ echo 'active';}?>"><a href="#family" data-toggle="tab">Family</a></li>
                          <li tab = "Referee Info" class="tab_header <?php if($this->current_tab == "Referee Info"){ echo 'active';}?>"><a href="#referee_info" data-toggle="tab">Character Referee's</a></li>
                          <li tab = "Declaration Info" class="tab_header <?php if($this->current_tab == "Declaration Info"){ echo 'active';}?>"><a href="#declaration_info" data-toggle="tab">Declaration</a></li>
                          <li tab = "TermCondition Info" class="tab_header <?php if($this->current_tab == "TermCondition Info"){ echo 'active';}?>"><a href="#termcondition_info" data-toggle="tab">Terms & Conditions</a></li>
                          <li tab = "Official Use" class="tab_header <?php if($this->current_tab == "Official Use"){ echo 'active';}?>"><a href="#official_use" data-toggle="tab">Official Use</a></li>
<!--                          <li tab = "followup" class="tab_header <?php if($this->current_tab == "followup"){ echo 'active';}?>"><a href="#followup" data-toggle="tab">Follow Up</a></li>-->
<!--                          <li tab = "Salary Info" class="tab_header <?php if($this->current_tab == "Salary Info"){ echo 'active';}?>"><a href="#salary" data-toggle="tab">Salary Info</a></li>-->
                          <?php // }?>
                          <!--<li tab = "Foreign Worker" class="tab_header <?php if($this->current_tab == "Foreign Worker"){ echo 'active';}?>"><a href="#foreign_worker" data-toggle="tab">Foreign Worker</a></li>-->
                          <!--<li tab = "Leave" class="tab_header <?php if($this->current_tab == "Leave"){ echo 'active';}?>"><a href="#leave" data-toggle="tab">Leave</a></li>-->
                        </ul>
                        <?php }
                        else { ?>
                        <ul class="nav nav-tabs">
                          <li tab = "General Info" class="tab_header <?php if(($this->current_tab == "") || ($this->current_tab == "General Info")){ echo 'active';}?>"><a href="#general" data-toggle="tab">General Info</a></li>
                          <li tab = "Contact Info" class="tab_header <?php if($this->current_tab == "Contact Info"){ echo 'active';}?>" ><a href="#contact" data-toggle="tab">Contact Info</a></li>
<!--                          <li tab = "Job Info" class="tab_header <?php if($this->current_tab == "Job Info"){ echo 'active';}?>" ><a href="#job_info" data-toggle="tab">Job Info</a></li>-->
                          <li tab = "Bank Info" class="tab_header <?php if($this->current_tab == "Bank Info"){ echo 'active';}?>" ><a href="#bank" data-toggle="tab">Bank Info</a></li>
                          <?php // if($this->applicant_id > 0){?>
<!--                          <li tab = "family" class="tab_header <?php if($this->current_tab == "family"){ echo 'active';}?>"><a href="#family" data-toggle="tab">Family</a></li>-->
                          <li tab = "Referee Info" class="tab_header <?php if($this->current_tab == "Referee Info"){ echo 'active';}?>"><a href="#referee_info" data-toggle="tab">Character Referee's</a></li>
                          <li tab = "Declaration Info" class="tab_header <?php if($this->current_tab == "Declaration Info"){ echo 'active';}?>"><a href="#declaration_info" data-toggle="tab">Declaration</a></li>
                          <li tab = "TermCondition Info" class="tab_header <?php if($this->current_tab == "TermCondition Info"){ echo 'active';}?>"><a href="#termcondition_info" data-toggle="tab">Terms & Conditions</a></li>
                          <li tab = "Official Use" class="tab_header <?php if($this->current_tab == "Official Use"){ echo 'active';}?>"><a href="#official_use" data-toggle="tab">Official Use</a></li>
<!--                          <li tab = "followup" class="tab_header <?php if($this->current_tab == "followup"){ echo 'active';}?>"><a href="#followup" data-toggle="tab">Follow Up</a></li>-->
<!--                          <li tab = "Salary Info" class="tab_header <?php if($this->current_tab == "Salary Info"){ echo 'active';}?>"><a href="#salary" data-toggle="tab">Salary Info</a></li>-->
                          <?php // }?>
                          <!--<li tab = "Foreign Worker" class="tab_header <?php if($this->current_tab == "Foreign Worker"){ echo 'active';}?>"><a href="#foreign_worker" data-toggle="tab">Foreign Worker</a></li>-->
                          <!--<li tab = "Leave" class="tab_header <?php if($this->current_tab == "Leave"){ echo 'active';}?>"><a href="#leave" data-toggle="tab">Leave</a></li>-->
                        </ul>
                        <?php } ?>
                      </div>
                      <div class="tab-content">
                          <div class=" tab-pane <?php if(($this->current_tab == "") || ($this->current_tab == "General Info")){ echo 'active';}?>" id="general">
                              <div class="col-sm-8">
                              <?php echo $this->getGeneralForm();?>
                              </div>
                              <div class="col-sm-4">
                                 <?php

//                                 echo file_get_contents(include_webroot . "dist/qrcode/?data=$this->applicant_code");
                                 ?>
                                   
                              
                                     <p></p>
                                    <?php if(file_exists("dist/images/applicant/$this->applicant_id.png")){?>
                                    <img src ="dist/images/applicant/<?php echo $this->applicant_id;?>.png" style = 'width:215px;height:215px;'/>
                                  <?php }else{?>
                                    <img src ='dist/img/avatar5.png'  />
                                   
                                  <?php }?>
                                     <p></p>
                                     <input type = "file" name = 'image_input' /><br>
                                     
                                     
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
                          <div class=" tab-pane <?php if($this->current_tab == "family"){ echo 'active';}?>" id="family">
                              <?php echo $this->getFamilyForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "followup"){ echo 'active';}?>" id="followup">
                              <?php echo $this->getFollowUpForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "Referee Info"){ echo 'active';}?>" id="referee_info">
                              <?php echo $this->getReferesForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "Declaration Info"){ echo 'active';}?>" id="declaration_info">
                              <?php echo $this->getDeclarationForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "TermCodition Info"){ echo 'active';}?>" id="termcondition_info">
                              <?php echo $this->getTermsConditions();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "Official Use"){ echo 'active';}?>" id="official_use">
                              <?php echo $this->getOfficialUse();?>
                          </div>
                          <?php // if($this->applicant_id > 0){?>
                          <div class=" tab-pane <?php if($this->current_tab == "Salary Info"){ echo 'active';}?>" id="salary">
                              <?php echo $this->getSalaryForm();?>
                          </div>
<!--                          <div class=" tab-pane <?php if($this->current_tab == "Foreign Worker"){ echo 'active';}?>" id="foreign_worker">
                              <?php echo $this->getForeignWorkerForm();?>
                          </div>
                          <?php // }?>
                          <div class=" tab-pane <?php if($this->current_tab == "Leave"){ echo 'active';}?>" id="leave">
                              <?php echo $this->getLeaveForm();?>
                          </div>-->
                      </div>
                        
                    
                     
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Back</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->applicant_id;?>" name = "applicant_id" id = "applicant_id"/>
                    <?php
                    if($this->applicant_id > 0){
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
        if($this->applicant_id > 0){
            echo " getBankCode($this->applicant_bank);";
        }
        ?>
        $('#applicant_applicantpass').change(function(){
        
           if(($("#applicant_applicantpass option:selected").text() == 'WP') || ($("#applicant_applicantpass option:selected").text() == 'SP')){
                if($('#levy_div').hasClass('hide')){
                    $('#levy_div').removeClass('hide');
                }
           }else{
                    $('#levy_div').addClass('hide');
           }
        });
        $('#applicant_email').keyup(function(){
            $('#applicant_login_email').val($(this).val());
        });
        $("#applicant_form").validate({
                  rules: 
                  {
                      applicant_name:
                      {
                          required: true
                      },
                      applicant_group:
                      {
                          required: true
                      },
                      applicant_nric:
                      {
                          required: true
                      },
                      applicant_login_email:
                      {
                          required: true,
                          remote: {
                                  url: "walkinapplicant.php?action=validate_email",
                                  type: "post",
                                  data: 
                                        {
                                            applicant_id: function()
                                            {
                                                return $("#applicant_id").val();
                                            }
                                        }
                              }
                      },
                      applicant_login_password:
                      {
                        required: true,
                      },
                      applicant_login_password_cm:
                      {
                        required: true,
                        minlength : 5,
                        equalTo : "#applicant_login_password"
                      },
                      applicant_sex:
                      {
                        required: true,
                      }
                      
                  },
                  messages:
                  {
                      applicant_name:
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
                      }
                  }
              });
            $('.tab_header').click(function(){
                $('#current_tab').val($(this).attr('tab'));
            }); 
            $('.save_salary_btn').click(function(){
                var data = "action=saveSalary&applicant_id=<?php echo $this->applicant_id;?>&applicantsalary_date="+$('#applicantsalary_date').val()+"&applicantsalary_amount="+$('#applicantsalary_amount').val()+"&applicantsalary_remark="+encodeURIComponent($('#applicantsalary_remark').val());
                    data = data + "&applicantsalary_workday="+encodeURIComponent($('#applicantsalary_workday').val())+"&applicantsalary_hourly="+encodeURIComponent($('#applicantsalary_hourly').val())+"&applicantsalary_overtime="+encodeURIComponent($('#applicantsalary_overtime').val());
                    data = data + "&applicantsalary_id="+$('#applicantsalary_id').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'walkinapplicant.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>';
//                           window.location.href = url + "&current_tab=" + $('#current_tab').val();
                       }else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            $('#applicant_success_assign').change(function(){
                var data = $(this);
                console.log(data);
                if(data.val() == "1"){
                $('.success_assign_job').show();
                }
                else
                {
                    $('.success_assign_job').hide();
                }
            });  
            
            if ($('#applicant_success_assign').val() == "1")
            {
                $('.success_assign_job').show();
            }
            else
            {
                    $('.success_assign_job').hide();
            }  

            
            $('.save_wappfamily_btn').click(function(){

                var data = "action=saveFamily&applicant_id=<?php echo $this->applicant_id;?>&appfamily_name="+$('#appfamily_name').val()+"&appfamily_relation="+$('#appfamily_relation').val()+"&appfamily_age="+$('#appfamily_age').val();
                    data = data + "&appfamily_occupation="+$('#appfamily_occupation').val()+"&appfamily_contact="+$('#appfamily_contact').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'walkinapplicant.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>&current_tab=family';
                           window.location.href = url + "&current_tab=" + $('#current_tab').val();
                       }else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            $('#applicant_bank').change(function(){
            
                getBankCode($(this).val());
            });
});
function getBankCode(bank){

                var data = "action=getBankCode&applicant_bank="+bank;
                $.ajax({ 
                    type: 'POST',
                    url: 'walkinapplicant.php',
                    cache: false,
                    data:data,
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       $('#applicant_bank_no').val(jsonObj.bank_no);
                    }		
                 });
}
    </script>
  </body>
</html>
        <?php
        
    }
    public function getEditInputForm($action){
        global $mandatory; 
        if($action == 'create'){
            $this->applicant_seqno = 10;
            $this->applicant_code = "-- System Generate --";
            $this->applicant_status = 1;
        }
        $this->nationalityCrtl = $this->select->getNationalitySelectCtrl($this->applicant_nationality);
        $this->bankCrtl = $this->select->getBankSelectCtrl($this->applicant_bank);
        $this->groupCrtl = $this->select->getGroupSelectCtrl($this->applicant_group);
        $this->outletCrtl = $this->select->getOutletSelectCtrl($this->applicant_outlet);
        $this->departmentCrtl = $this->select->getDepartmentSelectCtrl($this->applicant_department);
        $this->religionCrtl = $this->select->getReligionSelectCtrl($this->applicant_religion);
        $this->raceCrtl = $this->select->getRaceSelectCtrl($this->applicant_race);
        $this->designationCrtl = $this->select->getDesignationSelectCtrl($this->applicant_designation);
        

        
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Applicant Management</title>
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
            <h1>Applicant Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->applicant_id > 0){ echo "Update Applicant";}else{ echo "Create New Applicant";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = '' onclick = "window.location.href='walkinapplicant.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'margin-right:10px;' onclick = "window.location.href='walkinapplicant.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'applicant_form' class="form-horizontal" action = 'walkinapplicant.php?action=create' method = "POST" enctype="multipart/form-data">
                    <input type ='hidden' name = 'current_tab' id = 'current_tab' value = "<?php echo $this->current_tab?>"/>
                  <div class="box-body">
                      
                      <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li tab = "General Info" class="tab_header <?php if(($this->current_tab == "") || ($this->current_tab == "General Info")){ echo 'active';}?>"><a href="#general" data-toggle="tab">General Info</a></li>
                          <li tab = "Contact Info" class="tab_header <?php if($this->current_tab == "Contact Info"){ echo 'active';}?>" ><a href="#contact" data-toggle="tab">Contact Info</a></li>
                          <li tab = "Job Info" class="tab_header <?php if($this->current_tab == "Job Info"){ echo 'active';}?>" ><a href="#job_info" data-toggle="tab">Job Info</a></li>
                          <li tab = "Bank Info" class="tab_header <?php if($this->current_tab == "Bank Info"){ echo 'active';}?>" ><a href="#bank" data-toggle="tab">Bank Info</a></li>
                          <?php // if($this->applicant_id > 0){?>
                          <li tab = "family" class="tab_header <?php if($this->current_tab == "family"){ echo 'active';}?>"><a href="#family" data-toggle="tab">Family</a></li>
                          <li tab = "Referee Info" class="tab_header <?php if($this->current_tab == "Referee Info"){ echo 'active';}?>"><a href="#referee_info" data-toggle="tab">Character Referee's</a></li>
                          <li tab = "Declaration Info" class="tab_header <?php if($this->current_tab == "Declaration Info"){ echo 'active';}?>"><a href="#declaration_info" data-toggle="tab">Declaration</a></li>
                          <li tab = "TermCondition Info" class="tab_header <?php if($this->current_tab == "TermCondition Info"){ echo 'active';}?>"><a href="#termcondition_info" data-toggle="tab">Terms & Conditions</a></li>
                          <li tab = "Official Use" class="tab_header <?php if($this->current_tab == "Official Use"){ echo 'active';}?>"><a href="#official_use" data-toggle="tab">Official Use</a></li>
                          <li tab = "followup" class="tab_header <?php if($this->current_tab == "followup"){ echo 'active';}?>"><a href="#followup" data-toggle="tab">Follow Up</a></li>
                          <?php // }?>
                          <!--<li tab = "Foreign Worker" class="tab_header <?php if($this->current_tab == "Foreign Worker"){ echo 'active';}?>"><a href="#foreign_worker" data-toggle="tab">Foreign Worker</a></li>-->
                          <!--<li tab = "Leave" class="tab_header <?php if($this->current_tab == "Leave"){ echo 'active';}?>"><a href="#leave" data-toggle="tab">Leave</a></li>-->
                        </ul>
                      </div>
                      <div class="tab-content">
                          <div class=" tab-pane <?php if(($this->current_tab == "") || ($this->current_tab == "General Info")){ echo 'active';}?>" id="general">
                              <div class="col-sm-8">
                              <?php echo $this->getGeneralForm();?>
                              </div>
                              <div class="col-sm-4">
                                 <?php

//                                 echo file_get_contents(include_webroot . "dist/qrcode/?data=$this->applicant_code");
                                 ?>
                                   
                              
                                     <p></p>
                                    <?php if(file_exists("dist/images/applicant/$this->applicant_id.png")){?>
                                    <img src ="dist/images/applicant/<?php echo $this->applicant_id;?>.png" style = 'width:215px;height:215px;'/>
                                  <?php }else{?>
                                    <img src ='dist/img/avatar5.png'  />
                                   
                                  <?php }?>
                                     <p></p>
                                     <input type = "file" name = 'image_input' /><br>
                                     
                                    
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
                          <div class=" tab-pane <?php if($this->current_tab == "family"){ echo 'active';}?>" id="family">
                              <?php echo $this->getFamilyForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "followup"){ echo 'active';}?>" id="followup">
                              <?php echo $this->getFollowUpForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "Referee Info"){ echo 'active';}?>" id="referee_info">
                              <?php echo $this->getReferesForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "Declaration Info"){ echo 'active';}?>" id="declaration_info">
                              <?php echo $this->getDeclarationForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "TermCodition Info"){ echo 'active';}?>" id="termcondition_info">
                              <?php echo $this->getTermsConditions();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "Official Use"){ echo 'active';}?>" id="official_use">
                              <?php echo $this->getOfficialUse();?>
                          </div>
                          <?php // if($this->applicant_id > 0){?>
                          <div class=" tab-pane <?php if($this->current_tab == "Salary Info"){ echo 'active';}?>" id="salary">
                              <?php echo $this->getSalaryForm();?>
                          </div>
<!--                          <div class=" tab-pane <?php if($this->current_tab == "Foreign Worker"){ echo 'active';}?>" id="foreign_worker">
                              <?php echo $this->getForeignWorkerForm();?>
                          </div>
                          <?php // }?>
                          <div class=" tab-pane <?php if($this->current_tab == "Leave"){ echo 'active';}?>" id="leave">
                              <?php echo $this->getLeaveForm();?>
                          </div>-->
                      </div>
                        
                    
                     
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Back</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->applicant_id;?>" name = "applicant_id" id = "applicant_id"/>
                    <?php
                    if($this->applicant_id > 0){
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
alert('123');
        <?php
        if($this->applicant_id > 0){
            echo " getBankCode($this->applicant_bank);";
        }
        ?>
        $('#applicant_applicantpass').change(function(){
        
           if(($("#applicant_applicantpass option:selected").text() == 'WP') || ($("#applicant_applicantpass option:selected").text() == 'SP')){
                if($('#levy_div').hasClass('hide')){
                    $('#levy_div').removeClass('hide');
                }
           }else{
                    $('#levy_div').addClass('hide');
           }
        });
        $('#applicant_email').keyup(function(){
            $('#applicant_login_email').val($(this).val());
        });
        $("#applicant_form").validate({
                  rules: 
                  {
                      applicant_name:
                      {
                          required: true
                      },
                      applicant_group:
                      {
                          required: true
                      },
                      applicant_nric:
                      {
                          required: true
                      },
                      applicant_login_email:
                      {
                          required: true,
                          remote: {
                                  url: "walkinapplicant.php?action=validate_email",
                                  type: "post",
                                  data: 
                                        {
                                            applicant_id: function()
                                            {
                                                return $("#applicant_id").val();
                                            }
                                        }
                              }
                      },
                      applicant_login_password:
                      {
                        required: true,
                      },
                      applicant_login_password_cm:
                      {
                        required: true,
                        minlength : 5,
                        equalTo : "#applicant_login_password"
                      },
                      applicant_sex:
                      {
                        required: true,
                      }
                      
                  },
                  messages:
                  {
                      applicant_name:
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
                      }
                  }
              });
            $('.tab_header').click(function(){
                $('#current_tab').val($(this).attr('tab'));
            }); 
            $('.save_salary_btn').click(function(){
                var data = "action=saveSalary&applicant_id=<?php echo $this->applicant_id;?>&applicantsalary_date="+$('#applicantsalary_date').val()+"&applicantsalary_amount="+$('#applicantsalary_amount').val()+"&applicantsalary_remark="+encodeURIComponent($('#applicantsalary_remark').val());
                    data = data + "&applicantsalary_workday="+encodeURIComponent($('#applicantsalary_workday').val())+"&applicantsalary_hourly="+encodeURIComponent($('#applicantsalary_hourly').val())+"&applicantsalary_overtime="+encodeURIComponent($('#applicantsalary_overtime').val());
                    data = data + "&applicantsalary_id="+$('#applicantsalary_id').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'walkinapplicant.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>';
                           window.location.href = url + "&current_tab=" + $('#current_tab').val();
                       }else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            $('.save_wappfamily_btn').click(function(){
            alert('123');
                var data = "action=saveFamily&applicant_id=<?php echo $this->applicant_id;?>&appfamily_name="+$('#appfamily_name').val()+"&appfamily_relation="+$('#appfamily_relation').val()+"&appfamily_age="+$('#appfamily_age').val();
                    data = data + "&appfamily_occupation="+$('#appfamily_occupation').val()+"&appfamily_contact="+$('#appfamily_contact').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'walkinapplicant.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=saveFamily&applicant_id={$_REQUEST['applicant_id']}";?>';
                           window.location.href = url + "&current_tab=family" ;
                       }else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            $('#applicant_bank').change(function(){
            
                getBankCode($(this).val());
            });
});
function getBankCode(bank){

                var data = "action=getBankCode&applicant_bank="+bank;
                $.ajax({ 
                    type: 'POST',
                    url: 'walkinapplicant.php',
                    cache: false,
                    data:data,
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       $('#applicant_bank_no').val(jsonObj.bank_no);
                    }		
                 });
}
    </script>
  </body>
</html>
        <?php
        
    }
    public function getGeneralForm(){
        global $mandatory;
        
        if($this->applicant_id <=0){
            $this->applicant_joindate = system_date;
            
        }
        $this->applicantsalary_date = system_date;
    ?>
            <div class="form-group">
                  <label for="applicant_code" class="col-sm-2 control-label">Candidate Code </label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="applicant_code" name="applicant_code" value = "<?php echo $this->applicant_code;?>" disabled  >
                  </div>
                  <label for="applicant_position" class="col-sm-2 control-label">Position Applied</label>
                  <div class="col-sm-3">
                       <input type="text" class="form-control" id="applicant_position" name="applicant_position" value = "<?php echo $this->applicant_position;?>"   >
                       
                  </div>
            </div>  
            <div class="form-group">
                <label for="applicant_name" class="col-sm-2 control-label" >Name <?php echo $mandatory;?></label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="applicant_name" name="applicant_name" value = "<?php echo $this->applicant_name;?>" placeholder="Name">
                </div>
                <label for="applicant_birthday" class="col-sm-2 control-label">Birthday</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control datepicker" id="applicant_birthday" name="applicant_birthday" value = "<?php echo format_date($this->applicant_birthday);?>" placeholder="Birthday">
                </div>
            </div>
        <div class="form-group">
            <label for="applicant_nric" class="col-sm-2 control-label" >NRIC/FIN No: <?php echo $mandatory;?></label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="applicant_nric" name="applicant_nric" value = "<?php echo $this->applicant_nric;?>" placeholder="NRIC">
            </div>
            <label for="applicant_applicantpass" class="col-sm-2 control-label">Type Of Pass</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="applicant_applicantpass" name="applicant_applicantpass" style = 'width:100%'>
                     <?php echo $this->applicantpassCrtl;?>
                 </select>
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_sex" class="col-sm-2 control-label">Gender  <?php echo $mandatory;?></label>
             <div class="col-sm-3">
                 <select class="form-control select2" id="applicant_sex" name="applicant_sex" style = 'width:100%'>
                    <option value="">Select One</option>
                    <option value="M" <?php if($this->applicant_sex == 'M'){ echo 'SELECTED';}?>>Male</option>
                    <option value="F" <?php if($this->applicant_sex == 'F'){ echo 'SELECTED';}?>>Female</option>
                 </select>
             </div>
            <label for="applicant_tel" class="col-sm-2 control-label">Nationality</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="applicant_nationality" name="applicant_nationality" style = 'width:100%' >
                   <?php echo $this->nationalityCrtl;?>
                 </select>
            </div>
        </div>

        <div class="form-group">
            <label for="applicant_mobile" class="col-sm-2 control-label">Mobile</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="applicant_mobile" name="applicant_mobile" value = "<?php echo $this->applicant_mobile;?>" placeholder="Mobile">
            </div>  
            <label for="applicant_religion" class="col-sm-2 control-label">Religion</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="applicant_religion" name="applicant_religion" style = 'width:100%'>
                   <?php echo $this->religionCrtl;?>
                 </select>
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_tel" class="col-sm-2 control-label">Home Tel</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="applicant_tel" name="applicant_tel" value = "<?php echo $this->applicant_tel;?>" placeholder="Home Tel">
            </div>
            <label for="applicant_race" class="col-sm-2 control-label">Race</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="applicant_race" name="applicant_race" style = 'width:100%'>
                   <?php echo $this->raceCrtl;?>
                 </select>
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="applicant_email" name="applicant_email" value = "<?php echo $this->applicant_email;?>" placeholder="Email">
            </div>
            <label for="applicant_marital_status" class="col-sm-2 control-label">Marital Status</label>
             <div class="col-sm-3">
                 <select class="form-control select2" id="applicant_marital_status" name="applicant_marital_status" style = 'width:100%'>
                       <option value="S" <?php if($this->applicant_marital_status == 'S'){ echo 'SELECTED';}?>>Single</option>
                       <option value="M" <?php if($this->applicant_marital_status == 'M'){ echo 'SELECTED';}?>>Married </option>
                       <option value="D" <?php if($this->applicant_marital_status == 'D'){ echo 'SELECTED';}?>>Divorced </option>
                       <option value="W" <?php if($this->applicant_marital_status == 'W'){ echo 'SELECTED';}?>>Widower </option>
                       <option value="WE" <?php if($this->applicant_marital_status == 'WE'){ echo 'SELECTED';}?>>Widowee </option>
                 </select>
             </div>
        </div>
        <div class="form-group">
            <label for="applicant_status" class="col-sm-2 control-label">Status</label>
            <div class="col-sm-3">
                 <select class="form-control" id="applicant_status" name="applicant_status">
                   <option value = '1' <?php if($this->applicant_status == 1){ echo 'SELECTED';}?>>Active</option>
                   <option value = '0' <?php if($this->applicant_status == 0){ echo 'SELECTED';}?>>In-active</option>
                 </select>
            </div>

            <label for="applicant_numberofchildren" class="col-sm-2 control-label">Numbers of Children</label>
            <div class="col-sm-3">
             <input type="text" class="form-control" id="applicant_numberofchildren" name="applicant_numberofchildren" value = "<?php echo $this->applicant_numberofchildren;?>" placeholder="Numbers of Children">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_height" class="col-sm-2 control-label">Height</label>
            <div class="col-sm-3">
             <input type="text" class="form-control" id="applicant_height" name="applicant_height" value = "<?php echo $this->applicant_height;?>" placeholder="Height">
            </div>

            <label for="applicant_weight" class="col-sm-2 control-label">Weight</label>
            <div class="col-sm-3">
             <input type="text" class="form-control" id="applicant_weight" name="applicant_weight" value = "<?php echo $this->applicant_weight;?>" placeholder="Weight">
            </div>
        </div>
    <div class="form-group">   
          <?php if (($this->applicant_black_list == '1'))
          {?>
          <label for="applicant_black_list" style ="color:red;" class="col-sm-2 control-label">Black List</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "applicant_black_list" value = '1' <?php if(($this->applicant_black_list == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "applicant_black_list" value = '0' <?php if($this->applicant_black_list == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>
          <?php }
          else {?>
          <label for="applicant_black_list" class="col-sm-2 control-label">Black List</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "applicant_black_list" value = '1' <?php if(($this->applicant_black_list == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "applicant_black_list" value = '0' <?php if($this->applicant_black_list == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>
            <?php }?>
              
        </div>
    <?php
    }
    public function getContactForm(){
    ?> 
        <h3><u>Address Information</u></h3>
        <div class="form-group">
            <label for="applicant_address" class="col-sm-2 control-label">Address 1</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="applicant_address" name="applicant_address" placeholder="Address"><?php echo $this->applicant_address;?></textarea>
            </div>
            <label for="applicant_address2" class="col-sm-2 control-label">Address 2</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="applicant_address2" name="applicant_address2" placeholder="Address 2"><?php echo $this->applicant_address2;?></textarea>
            </div>
        </div>
        <h3><u>Emergency Contact Address Information</u></h3>
        <div class="form-group">
            <label for="applicant_emer_contact" class="col-sm-2 control-label">Contact Person</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_emer_contact" name="applicant_emer_contact" value = "<?php echo $this->applicant_emer_contact;?>" placeholder="Contact Person">
            </div>
            <label for="applicant_emer_relation" class="col-sm-2 control-label">Relationship</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_emer_relation" name="applicant_emer_relation" value = "<?php echo $this->applicant_emer_relation;?>" placeholder="Relationship">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_emer_phone1" class="col-sm-2 control-label">Phone 1</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_emer_phone1" name="applicant_emer_phone1" value = "<?php echo $this->applicant_emer_phone1;?>" placeholder="Phone 1">
            </div>
            <label for="applicant_emer_phone2" class="col-sm-2 control-label">Phone 2</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_emer_phone2" name="applicant_emer_phone2" value = "<?php echo $this->applicant_emer_phone2;?>" placeholder="Phone 2">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_emer_address" class="col-sm-2 control-label">Address</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_emer_address" name="applicant_emer_address" value = "<?php echo $this->applicant_emer_address;?>" placeholder="Address">
            </div>
            <label for="applicant_emer_remarks" class="col-sm-2 control-label">Remarks</label>
            <div class="col-sm-3">
                <textarea class="form-control" rows="3" id="applicant_emer_remarks" name="applicant_emer_remarks" placeholder="Remarks"><?php echo $this->applicant_emer_remarks;?></textarea>
            </div>
        </div>
    <?php
    }
    public function getJobInfoForm(){
    ?> 
        <h3><u>Job Information</u></h3>
        <div class="form-group ">
            <label for="applicant_department" class="col-sm-2 control-label">Department</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="applicant_department" name="applicant_department" style = 'width:100%'>
                   <?php echo $this->departmentCrtl;?>
                 </select>
            </div>
            <label for="applicant_designation" class="col-sm-2 control-label">Designation</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="applicant_designation" name="applicant_designation" style = 'width:100%'>
                   <?php echo $this->designationCrtl;?>
                 </select>
            </div>
        </div>
        <div class="form-group ">
            <label for="applicant_work_time_start" class="col-sm-2 control-label">Work time (Start)</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;' >
                <input type="text" class="form-control timepicker" id="applicant_work_time_start" name="applicant_work_time_start" value = "<?php echo $this->applicant_work_time_start;?>">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>

            <label for="applicant_work_time_end" class="col-sm-2 control-label">Work time (End)</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;'>
                <input type="text" class="form-control timepicker" id="applicant_work_time_end" name="applicant_work_time_end" value = "<?php echo $this->applicant_work_time_end;?>">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_joindate" class="col-sm-2 control-label">Join Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_joindate" name="applicant_joindate" value = "<?php echo format_date($this->applicant_joindate);?>" placeholder="Join Date">
            </div>
            <label for="applicant_address2" class="col-sm-2 control-label">Probation Period</label>
            <div class="col-sm-3">
                <select class="form-control " id="applicant_probation" name="applicant_probation">
                    <option value = '0' <?php if($this->applicant_probation == 1){ echo 'SELECTED';}?>>Select One</option>
                    <option value = '1' <?php if($this->applicant_probation == 1){ echo 'SELECTED';}?>>1</option>
                    <option value = '2' <?php if($this->applicant_probation == 2){ echo 'SELECTED';}?>>2</option>
                    <option value = '3' <?php if($this->applicant_probation == 3){ echo 'SELECTED';}?>>3</option>
                    <option value = '4' <?php if($this->applicant_probation == 4){ echo 'SELECTED';}?>>4</option>
                    <option value = '5' <?php if($this->applicant_probation == 5){ echo 'SELECTED';}?>>5</option>
                    <option value = '6' <?php if($this->applicant_probation == 6){ echo 'SELECTED';}?>>6</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-3">
                
            </div>
            <label for="applicant_extentionprobation" class="col-sm-2 control-label">Extention of Probation</label>
            <div class="col-sm-3">
                <select class="form-control " id="applicant_extentionprobation" name="applicant_extentionprobation">
                    <option value = '0' <?php if($this->applicant_extentionprobation == 1){ echo 'SELECTED';}?>>Select One</option>
                    <option value = '1' <?php if($this->applicant_extentionprobation == 1){ echo 'SELECTED';}?>>1</option>
                    <option value = '2' <?php if($this->applicant_extentionprobation == 2){ echo 'SELECTED';}?>>2</option>
                    <option value = '3' <?php if($this->applicant_extentionprobation == 3){ echo 'SELECTED';}?>>3</option>
                    <option value = '4' <?php if($this->applicant_extentionprobation == 4){ echo 'SELECTED';}?>>4</option>
                    <option value = '5' <?php if($this->applicant_extentionprobation == 5){ echo 'SELECTED';}?>>5</option>
                    <option value = '6' <?php if($this->applicant_extentionprobation == 6){ echo 'SELECTED';}?>>6</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_confirmationdate" class="col-sm-2 control-label">Confirmation Date</label>
            <div class="col-sm-3">
              <input type="text" class="form-control datepicker" id="applicant_confirmationdate" name="applicant_confirmationdate" value = "<?php echo format_date($this->applicant_confirmationdate);?>" placeholder="Confirmation Date">
            </div>
            <label for="applicant_joindate" class="col-sm-2 control-label">Termination / Resignation Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_resigndate" name="applicant_resigndate" value = "<?php echo format_date($this->applicant_resigndate);?>" placeholder="Termination / Resignation Date">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_prdate" class="col-sm-2 control-label">PR Date</label>
            <div class="col-sm-3">
              <input type="text" class="form-control datepicker" id="applicant_prdate" name="applicant_prdate" value = "<?php echo format_date($this->applicant_prdate);?>" placeholder="PR Date">
            </div>
            <label for="applicant_resignreason" class="col-sm-2 control-label">Terminate Reason</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="applicant_resignreason" name="applicant_resignreason" placeholder="Terminate Reason"><?php echo $this->applicant_remark;?></textarea>
            </div>
        </div>
        <!--<h3><u>Alert Supervisor</u></h3>-->
        <?php //echo $this->getApprovalPermissionForm();?>
        
    <?php
    }
    public function getBankForm(){
    ?>
        <div class="form-group">
              <label for="applicant_paymode" class="col-sm-2 control-label">Pay Mode</label>
              <div class="col-sm-3">
               <select class="form-control" id="applicant_paymode" name="applicant_paymode" style = 'width:100%' >
                    <option value = '' <?php if($this->applicant_paymode == ''){ echo 'SELECTED';}?>>Select One</option>
                    <option value = 'Cash' <?php if($this->applicant_paymode == 'Cash'){ echo 'SELECTED';}?>>Cash</option>
                    <option value = 'Cheque' <?php if($this->applicant_paymode == 'Cheque'){ echo 'SELECTED';}?>>Cheque</option>
               </select>
              </div>
              <label for="applicant_bank" class="col-sm-2 control-label">GIRO Bank Code</label>
              <div class="col-sm-3">
                  <select class="form-control select2" id="applicant_bank" name="applicant_bank" style = 'width:100%'>
                        <?php echo $this->bankCrtl;?>
                  </select>
              </div>
              <div class="col-sm-1">
                  <input type="text" class="form-control" id="applicant_bank_no"  value = "<?php echo $this->applicant_bank_no;?>" readonly>
              </div>
        </div>
        <div class="form-group">
              <label for="applicant_bank_acc_name" class="col-sm-2 control-label">GIRO Account Name</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_bank_acc_name" name="applicant_bank_acc_name" value = "<?php echo $this->applicant_bank_acc_name;?>" placeholder="GIRO Account Name">
              </div>
              <label for="applicant_bank_acc_no" class="col-sm-2 control-label">GIRO Account No.</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_bank_acc_no" name="applicant_bank_acc_no" value = "<?php echo $this->applicant_bank_acc_no;?>" placeholder="GIRO Account No.">
              </div>
        </div>
        <div class="form-group">

        </div>
    <?php
    }
    public function getSalaryForm(){

    ?>



    <?php
    }
    
    public function getFamilyForm(){
    ?>
        <?php if($this->applicantsalary_id > 0){?>
        <h3>Update Family Background  
            <button type="button" class="btn btn-primary" style="width:150px;margin-right:10px;" onclick="window.location.href='walkinapplicant.php?action=edit&current_tab=family&applicant_id=12'">
                Create New Salary &nbsp; <i class="fa fa-plus-square" aria-hidden="true">
                    
                </i></button></h3>
        <?php }else{?>
        <h3>Create Family Background</h3> 
        <?php }?>
        <div class="form-group">
              <label for="appfamily_name" class="col-sm-2 control-label">Name </label>
              <div class="col-sm-2">
               <input type="text" class="form-control " id="appfamily_name" name="appfamily_name" value = "<?php echo $this->appfamily_name;?>" placeholder="Name">
              </div>
        </div>
        <div class="form-group">
              <label for="appfamily_relation" class="col-sm-2 control-label">Relationship</label>
              <div class="col-sm-3">
                  <input type="text" class="form-control" id="appfamily_relation" name="appfamily_relation" value = "<?php echo $this->appfamily_relation?>" placeholder="Relationship">
              </div>
              <label for="appfamily_age" class="col-sm-2 control-label">Age</label>
              <div class="col-sm-3">
               <input type="text" class="form-control" id="appfamily_age" name="appfamily_age" value = "<?php echo $this->appfamily_age;?>" placeholder="Age">
              </div>
        </div>
        <div class="form-group">
              <label for="appfamily_contact" class="col-sm-2 control-label">Contact No</label>
              <div class="col-sm-3">
                <input type="text"  class="form-control" id="appfamily_contact" name="appfamily_contact" value = "<?php echo $this->appfamily_contact;?>" placeholder="Contact No">
              </div>
              <label for="appfamily_occupation" class="col-sm-2 control-label">Occupation</label>
              <div class="col-sm-3">
               <input type="text"  class="form-control" id="appfamily_occupation" name="appfamily_occupation" value = "<?php echo $this->appfamily_occupation;?>" placeholder="Occupation">
              </div>
        </div>
          <div class="col-sm-3 ">
              <button type = "button" class="btn btn-info save_wappfamily_btn" >
                  Save           
              </button>
<!--              <button type="button" class="btn btn-primary btn-info " onclick = "confirmAlertHref('walkinapplicant.php?action=saveFamily&applicant_id=<?php echo $this->applicant_id;?>&appfamily_name=<?php echo $this->appfamily_name;?>','Confirm Save?')">Save</button>-->
          </div><br><br><br>
               
          
          
          
        <table id="family_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:15%'>Name</th>
                        <th style = 'width:5%'>Age</th>
                        <th style = 'width:20%'>Relationship</th>
                        <th style = 'width:10%'>Contact</th>
                        <th style = 'width:20%'>Occupation</th>
                        <th style = 'width:7%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT *
                              FROM db_family family
                              WHERE family.applicant_family_id = '$this->applicant_id'
                              ORDER BY family.family_name";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['family_name'];?></td>
                            <td><?php echo $row['family_age'];?></td>
                            <td><?php echo $row['family_relationship'];?></td>
                            <td><?php echo $row['family_contact_no'];?></td>
                            <td><?php echo $row['family_occupation'];?></td>
                            <td class = "text-align-right">
                                <input type = 'hidden' value = '<?php echo $row['family_id'];?>' name = "appfamily_id" id = "appfamily_id"/>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('applicant.php?action=deleteFamily&current_tab=family&applicant_id=<?php echo $this->applicant_id;?>&family_id=<?php echo $row['family_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:15%'>Family Name</th>
                        <th style = 'width:5%'>Age</th>
                        <th style = 'width:20%'>Relationship</th>
                        <th style = 'width:10%'>Contact</th>
                        <th style = 'width:20%'>Occupation</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </tfoot>
                  </table>
             
                <div class="form-group">

        </div>
    <?php
    }
    public function getFollowUpForm(){
        if($this->applicantsalary_id > 0){
           $this->fetchSalaryDetail(" AND applicantsalary_id = '$this->applicantsalary_id'","","",1);
           }else{
           $this->applicantsalary_overtime = "0.00";
           $this->applicantsalary_hourly = "0.00";
           $this->applicantsalary_workday = 20;
           $this->applicantsalary_amount = 0;
        }
    ?>
        <?php if($this->applicantsalary_id > 0){?>
        <h3>Update Remark  <button type="button" class="btn btn-primary" style="width:150px;margin-right:10px;" onclick="window.location.href='walkinapplicant.php?action=edit&current_tab=family&applicant_id=12'">Create New Salary &nbsp; <i class="fa fa-plus-square" aria-hidden="true"></i></button></h3>
        <?php }else{?>
        <h3>Create Remark</h3> 
        <?php }?>
        <div class="form-group ">
            <label for="assign_by" class="col-sm-2 control-label">Assign By</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="applicant_department" name="applicant_department" style = 'width:100%'>
                   <?php echo $this->departmentCrtl;?>
                 </select>
            </div>
        </div>
        <div class="form-group ">
            <label for="assign_time" class="col-sm-2 control-label">Assign_Time</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;' >
                <input type="text" class="form-control timepicker" id="applicant_work_time_start" name="assign_time" value = "<?php echo $this->applicant_work_time_start;?>">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
            <label for="assign_date" class="col-sm-2 control-label">Assign Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_joindate" name="applicant_joindate" value = "<?php echo format_date($this->applicant_joindate);?>" placeholder="Join Date">
            </div>
        </div>
        <div class="form-group">
            <label for="interview_time" class="col-sm-2 control-label">Interview Time</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;'>
                <input type="text" class="form-control timepicker" id="applicant_work_time_end" name="interview_time" value = "<?php echo $this->applicant_work_time_end;?>">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
            <label for="interview_date" class="col-sm-2 control-label">Interview Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_joindate" name="applicant_joindate" value = "<?php echo format_date($this->applicant_joindate);?>" placeholder="Join Date">
            </div>
        </div>
        <div class="form-group">
            <label for="interview_company" class="col-sm-2 control-label">Interview Company</label>
            <div class="col-sm-3">
               <input type="text" class="form-control " id="interview_company" name="interview_company" value = "<?php echo $this->interview_company;?>" placeholder="Name">
            </div>
            <label for="interview_by" class="col-sm-2 control-label">Interview By</label>
            <div class="col-sm-3">
               <input type="text" class="form-control " id="interview_by" name="interview_by" value = "<?php echo $this->interview_by;?>" placeholder="Name">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_attend" class="col-sm-2 control-label">Attend Interview</label>
            
                <div class="radio col-sm-3">
                        <label>
                          <input type="radio" name = "applicant_black_list" value = '1' <?php if(($this->applicant_attend_interview == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "applicant_black_list" value = '0' <?php if($this->applicant_attend_interview == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>
            <label for="received_offer" class="col-sm-2 control-label">Received Job Offer</label>
            
                <div class="radio col-sm-3">
                        <label>
                          <input type="radio" name = "received_offer" value = '1' <?php if(($this->received_offer == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "received_offer" value = '0' <?php if($this->received_offer == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                </div>
        </div>
          <div class="col-sm-3 ">
              <button type = "button" class="btn btn-info" >
                  Save           
              </button>
<!--              <button type="button" class="btn btn-primary btn-info " onclick = "confirmAlertHref('walkinapplicant.php?action=saveFamily&applicant_id=<?php echo $this->applicant_id;?>&appfamily_name=<?php echo $this->appfamily_name;?>','Confirm Save?')">Save</button>-->
          </div><br><br><br>
               
          
          
          
        <table id="family_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:15%'>Name</th>
                        <th style = 'width:5%'>Age</th>
                        <th style = 'width:20%'>Relationship</th>
                        <th style = 'width:10%'>Contact</th>
                        <th style = 'width:20%'>Occupation</th>
                        <th style = 'width:7%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT *
                              FROM db_family family
                              WHERE family.applicant_family_id = '$this->applicant_id'
                              ORDER BY family.family_name";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['family_name'];?></td>
                            <td><?php echo $row['family_age'];?></td>
                            <td><?php echo $row['family_relationship'];?></td>
                            <td><?php echo $row['family_contact_no'];?></td>
                            <td><?php echo $row['family_occupation'];?></td>
                            <td class = "text-align-right">
                                <input type = 'hidden' value = '<?php echo $row['family_id'];?>' name = "appfamily_id" id = "appfamily_id"/><button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('walkinapplicant.php?action=deleteFamily&family_id=<?php echo $row['family_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:15%'>Family Name</th>
                        <th style = 'width:5%'>Age</th>
                        <th style = 'width:20%'>Relationship</th>
                        <th style = 'width:10%'>Contact</th>
                        <th style = 'width:20%'>Occupation</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </tfoot>
                  </table>
             
                <div class="form-group">

        </div>
    <?php
    }    
    public function getForeignWorkerForm(){
    ?> 
        <h3><u>Work Permit Information</u></h3>
        <div class="form-group">
            <label for="applicant_work_permit" class="col-sm-2 control-label">Work Permit Number</label>
            <div class="col-sm-3">
                  <input type="text" class="form-control" id="applicant_work_permit" name="applicant_work_permit" value = "<?php echo $this->applicant_work_permit;?>" placeholder="Work Permit Number">
            </div>
            <label for="applicant_work_permit_date_arrival" class="col-sm-2 control-label">Date of Arrival</label>
            <div class="col-sm-3">
                  <input type="text" class="form-control datepicker" id="applicant_work_permit_date_arrival" name="applicant_work_permit_date_arrival" value = "<?php echo format_date($this->applicant_work_permit_date_arrival);?>" placeholder="Date of Arrival">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_work_permit_application_date" class="col-sm-2 control-label">Application Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_work_permit_application_date" name="applicant_work_permit_application_date" value = "<?php echo $this->applicant_work_permit_application_date;?>" placeholder="Application Date">
            </div>
            <label for="applicant_pass_issuance" class="col-sm-2 control-label">Issue Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_pass_issuance" name="applicant_pass_issuance" value = "<?php echo format_date($this->applicant_pass_issuance);?>" placeholder="Pass Issuance Date">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_pass_renewal" class="col-sm-2 control-label">Renewal Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_pass_renewal" name="applicant_pass_renewal" value = "<?php echo format_date($this->applicant_pass_renewal);?>" placeholder="Pass Renewal Date">
            </div>
            <label for="applicant_levy_amt" class="col-sm-2 control-label">Levy Amount</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="applicant_levy_amt" name="applicant_levy_amt" value = "<?php echo $this->applicant_levy_amt;?>" placeholder="Levy Amount">
            </div>
        </div>
    <?php
    }
    public function getLeaveForm(){
    ?>
        <div class="form-group">
            <label for="applicantleave_leavetype" class="col-sm-2 control-label">Leave Title</label>
            <label for="applicantleave_leavetype" class="col-sm-1 control-label">Hide</label>
            <label for="applicantleave_leavetype" class="col-sm-3 control-label">Balance (<?php echo date("Y");?>)</label>
            <label for="applicantleave_leavetype" class="col-sm-3 control-label">Entitled (<?php echo date("Y");?>)</label>
            <?php if($this->applicant_id > 0){?>
            <label for="applicantleave_leavetype" class="col-sm-3 control-label">Balance (<?php echo date("Y")-1;?>)</label>
            <?php }?>
        </div>
    <?php    
        $year = date("Y");
        $sql = "SELECT lt.*,el.applicantleave_days,el.applicantleave_id,el.applicantleave_disabled
                FROM db_leavetype lt
                LEFT JOIN db_applicantleave el ON el.applicantleave_leavetype = lt.leavetype_id AND el.applicantleave_applicant = '$this->applicant_id' AND el.applicantleave_year = '$year'
                WHERE lt.leavetype_status = 1 
                GROUP BY lt.leavetype_id 
                ORDER BY lt.leavetype_seqno,lt.leavetype_code ";

        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            if($this->applicant_id <=0 ){
                $row['applicantleave_days'] = $row['leavetype_default'];
            }
        ?>
        <div class="form-group">
              <label for="applicantleave_leavetype" class="col-sm-2 control-label"><?php echo $row['leavetype_code'];?></label>
              <div class="col-sm-1">
                  <input type="checkbox" id="applicantleave_disabled" name="applicantleave_disabled[<?php echo $row['leavetype_id'];?>]" value = "1" <?php if($row['applicantleave_disabled'] == 1){ echo 'CHECKED';}?>>
              </div>
              <div class="col-sm-3">
                <input type="hidden" class="form-control" id="applicantleave_leavetype" name="applicantleave_leavetype[]" value = "<?php echo $row['leavetype_id'];?>">  
                <input type="hidden" class="form-control" id="applicantleave_id" name="applicantleave_id[]" value = "<?php echo $row['applicantleave_id'];?>">  
                <input type="text" class="form-control" id="applicantleave_days" name="applicantleave_days[]" value = "<?php echo $row['applicantleave_days'];?>" placeholder="Days">
              </div>
              <div class="col-sm-3">
                <?php
                if($this->applicant_id > 0){
                $balance1 = "0.00";
                $year1 = date("Y");
                if(getDataCodeBySql("COALESCE(applicantleave_entitled,0)","db_applicantleave"," WHERE applicantleave_applicant = '$this->applicant_id' AND applicantleave_leavetype = '{$row['leavetype_id']}' AND applicantleave_year = '$year1' AND applicantleave_status = '1'", $orderby)){
                    $balance1 = getDataCodeBySql("COALESCE(applicantleave_entitled,0)","db_applicantleave"," WHERE applicantleave_applicant = '$this->applicant_id' AND applicantleave_leavetype = '{$row['leavetype_id']}' AND applicantleave_year = '$year1'", $orderby);
                }
                }else{
                    $balance1 = $row['applicantleave_days'];
                }
                ?>
                <input type="text" class="form-control" id="applicantleave_entitled" name="applicantleave_entitled[]" value = "<?php echo $balance1;?>" placeholder="Days" >
              </div>
              <?php if($this->applicant_id > 0){?>
              <div class="col-sm-3">
                <?php
                $balance2 = "0.00";
                $year2 = date("Y")-1;
                if(getDataCodeBySql("COALESCE(applicantleave_days,0)","db_applicantleave"," WHERE applicantleave_applicant = '$this->applicant_id' AND applicantleave_leavetype = '{$row['leavetype_id']}' AND applicantleave_year = '$year2' AND applicantleave_status = '1'", $orderby)){
                    $balance2 = getDataCodeBySql("COALESCE(applicantleave_days,0)","db_applicantleave"," WHERE applicantleave_applicant = '$this->applicant_id' AND applicantleave_leavetype = '{$row['leavetype_id']}' AND applicantleave_year = '$year2'", $orderby);
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
                <label for="applicant_leave_approved1" class="col-sm-2 control-label">Approved level 1</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_leave_approved1" name="applicant_leave_approved1" style = 'width:100%' >
                        <?php echo $this->applicantLeaveApproved1Crtl;?>
                    </select>
                </div>
                <label for="applicant_claims_approved1" class="col-sm-2 control-label">Approved level 1</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_claims_approved1" name="applicant_claims_approved1" style = 'width:100%' >
                        <?php echo $this->applicantClaimsApproved1Crtl;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="applicant_leave_approved2" class="col-sm-2 control-label">Approved level 2</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_leave_approved2" name="applicant_leave_approved2" style = 'width:100%' >
                        <?php echo $this->applicantLeaveApproved2Crtl;?>
                    </select>
                </div>
                <label for="applicant_claims_approved2" class="col-sm-2 control-label">Approved level 2</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_claims_approved2" name="applicant_claims_approved2" style = 'width:100%' >
                        <?php echo $this->applicantClaimsApproved2Crtl;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="applicant_leave_approved3" class="col-sm-2 control-label">Approved level 3</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_leave_approved3" name="applicant_leave_approved3" style = 'width:100%' >
                        <?php echo $this->applicantLeaveApproved3Crtl;?>
                    </select>
                </div>
                <label for="applicant_claims_approved3" class="col-sm-2 control-label">Approved level 3</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_claims_approved3" name="applicant_claims_approved3" style = 'width:100%' >
                        <?php echo $this->applicantClaimsApproved3Crtl;?>
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
    <title>Applicant Management</title>
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
            <h1>Applicant Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Applicant Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='walkinapplicant.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table id="applicant_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:25%'>Family Name</th>
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
                      $sql = "SELECT applicant.*,gp.group_code,dp.department_code,outl.outl_code
                              FROM db_applicant applicant 
                              INNER JOIN db_group gp ON gp.group_id = applicant.applicant_group
                              LEFT JOIN db_department dp ON dp.department_id = applicant.applicant_department
                              LEFT JOIN db_outl outl ON outl.outl_id = applicant.applicant_outlet
                              WHERE applicant.applicant_id > 0 AND applicant.applicant_status = '1'
                              ORDER BY applicant.applicant_seqno,applicant.applicant_name";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['applicant_name'];?></td>
                            <td><?php echo $row['applicant_email'];?></td>
                            <td><?php echo $row['applicant_mobile'];?></td>
                            <td><?php echo $row['group_code'];?></td>
                            <td><?php echo $row['department_code'];?></td>
                            <td><?php echo $row['outl_code'];?></td>
                            <td><?php if($row['applicant_status'] == 1){ echo 'Active';}else{ echo 'In-Active';}?></td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'view')){
                                ?>
                                <!--<button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'walkinapplicant.php?action=view&applicant_id=<?php echo $row['applicant_id'];?>'">View</button>-->
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'walkinapplicant.php?action=edit&applicant_id=<?php echo $row['applicant_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('walkinapplicant.php?action=delete&applicant_id=<?php echo $row['applicant_id'];?>','Confirm Delete?')">Delete</button>
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
        $('#applicant_table').DataTable({
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
<p><b>For Sales/Project Management Team - Quotation/Invoice Tapplicantate</b> (Make sure company name, address and item description is in CAPS)</p>
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
    public function getApprovelEmail($type,$applicant_id){
        $sql = "SELECT applicant_{$type}_approved1,applicant_{$type}_approved2,applicant_{$type}_approved3
                FROM db_applicant 
                WHERE applicant_id = '$applicant_id'";
        $query = mysql_query($sql);
        $i = 0;
        if($row = mysql_fetch_array($query)){
            $level1_id = $row["applicant_{$type}_approved1"];
            $level2_id = $row["applicant_{$type}_approved2"];
            $level3_id = $row["applicant_{$type}_approved3"];
            $level1_email = getDataCodeBySql("applicant_email","db_applicant"," WHERE applicant_id = '$level1_id'");
            $level2_email = getDataCodeBySql("applicant_email","db_applicant"," WHERE applicant_id = '$level2_id'");
            $level3_email = getDataCodeBySql("applicant_email","db_applicant"," WHERE applicant_id = '$level3_id'");
            
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
    public function checkAccess($action){
        global $master_group;
        if(($action == 'createForm') || ($action == 'create') || ($action == '')){
            return true;
        }
        if(!in_array($_SESSION['applicant_group'],$master_group)){
            $applicant_id = getDataCodeBySql("applicant_id","db_applicant"," WHERE applicant_id = '$this->applicant_id'","");
            $record_count = getDataCountBySql("db_applicant"," WHERE applicant_id = '$applicant_id'");

            if(((($applicant_id <= 0) && ($record_count == 0)) || ($applicant_id != $_SESSION['empl_id']))){
                permissionLog();
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
                exit();
            }
        }
    }
    public function getBankCode(){
        $sql = "SELECT * FROM db_bank WHERE bank_id = '$this->applicant_bank'";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
            $bank_no = $row['bank_no'];
        }
        
        return $bank_no;
    }
    public function getDeclarationForm(){
        global $mandatory;
        
        if($this->applicant_id <=0){
            $this->applicant_joindate = system_date;
            
        }
        $this->applicantsalary_date = system_date;
    ?>
        <h3><u>Declaration</u></h3>
        <div class ="declaration-div">
        
        <div class="form-group">
            <label for="declaration_bankrupt" class=" control-label">1. &nbsp;Are you / Have you ever been an undischarged bankrupt?</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "declaration_bankrupt" value = '1' <?php if(($this->declaration_bankrupt == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_bankrupt" value = '0' <?php if($this->declaration_bankrupt == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_physical" class=" control-label">2. &nbsp;Are you suffering from any physical / mental impairment or chronic / pre-existing illness?</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "declaration_physical" value = '1' <?php if(($this->declaration_physical == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_physical" value = '0' <?php if($this->declaration_physical == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_lt_medical" class=" control-label">3. &nbsp;Are you currently undergoing long-term medical treatment?</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "declaration_lt_medical" value = '1' <?php if(($this->declaration_lt_medical == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_lt_medical" value = '0' <?php if($this->declaration_lt_medical == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_law" class=" control-label">4. &nbsp;Have you ever been convicted or found guilty of an offence in Court Of Law in any country?</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "declaration_law" value = '1' <?php if(($this->declaration_law == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_law" value = '0' <?php if($this->declaration_law == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_warning" class=" control-label">5. &nbsp;Have you ever been issued warning letters, suspended or dismissed from employment before?</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "declaration_warning" value = '1' <?php if(($this->declaration_warning == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_warning" value = '0' <?php if($this->declaration_warning == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                </div>
        </div>
         <div class="form-group">
          <label for="declaration_applied" class=" control-label">6. &nbsp;Have you applied for any job with this company before?</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "declaration_applied" value = '1' <?php if(($this->declaration_applied == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_applied" value = '0' <?php if($this->declaration_applied == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>
          </div>
        </div>
    <?php        
    }
    public function getReferesForm(){
    ?> 
        <h3><u>Character Referee's</u></h3>
        <h5>Name 2 persons who are not your relatives</h5>
        <div class="form-group">
            <label for="referee_name1" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_name1" name="referee_name1" value = "<?php echo $this->referee_name1;?>" placeholder="Name">
            </div>
            <label for="referee_occupation1" class="col-sm-2 control-label">Occupation</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_occupation1" name="referee_occupation1" value = "<?php echo $this->referee_occupation1;?>" placeholder="Occupation">
            </div>
        </div>
        <div class="form-group">
            <label for="referee_year_know1" class="col-sm-2 control-label">Years Known</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_year_know1" name="referee_year_know1" value = "<?php echo $this->referee_year_know1;?>" placeholder="Year">
            </div>
            <label for="referee_contact_no1" class="col-sm-2 control-label">Contact No / Email Address</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_contact_no1" name="referee_contact_no1" value = "<?php echo $this->referee_contact_no1;?>" placeholder="Contact No">
            </div>
        </div>
        
        <br>
        <br>
        <br>
        
        <div class="form-group">
            <label for="referee_name2" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_name2" name="referee_name2" value = "<?php echo $this->referee_name2;?>" placeholder="Name">
            </div>
            <label for="referee_occupation2" class="col-sm-2 control-label">Occupation</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_occupation2" name="referee_occupation2" value = "<?php echo $this->referee_occupation2;?>" placeholder="Occupation">
            </div>
        </div>
        <div class="form-group">
            <label for="referee_year_know2" class="col-sm-2 control-label">Years Known</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_year_know2" name="referee_year_know2" value = "<?php echo $this->referee_year_know2;?>" placeholder="Year">
            </div>
            <label for="referee_contact_no2" class="col-sm-2 control-label">Contact No / Email Address</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_contact_no2" name="referee_contact_no2" value = "<?php echo $this->referee_contact_no2;?>" placeholder="Contact No">
            </div>
        </div>
        <br><br>
        <h5>May we write to the following for a reference?</h5>
        <div class="form-group">
            <label for="referee_present_employer" class="col-sm-2 control-label">Your present employer</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "referee_present_employer" value = '1' <?php if(($this->referee_present_employer == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "referee_present_employer" value = '0' <?php if($this->referee_present_employer == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>

        </div>
        <div class="form-group">
            <label for="referee_previous_employer" class="col-sm-2 control-label">Your previous employer(s)</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "referee_previous_employer" value = '1' <?php if(($this->referee_previous_employer == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "referee_previous_employer" value = '0' <?php if($this->referee_previous_employer == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>
        </div>

    <?php
    }
    public function getTermsConditions(){
        ?>
        <h3><u>Terms & Conditions</u></h3>
        <div class="form-group">
            <div class="terms">
            <h3>1.	Temporary Placement</h3><br>
            <h5>       
                <b>A.&nbsp;</b>	Temporary candidates are required to serve 1 week notice to <b>SUCCESS HUMAN RESOURCE CENTRE PTE LTD / SUCCESS RESOURCE CENTRE PTE LTD</b> upon resignation, failing which salary in lieu of notice will be deducted (include OT claims).<br><br>

                <b>B.&nbsp;</b>	All candidates shall not accept any employment offer directly from the client of <b>SUCCESS HUMAN RESOURCE CENTRE PTE LTD / SUCCESS RESOURCE CENTRE PTE LTD</b> within 1 year from the last working day of the assignment.
            </h5>

            <h3>2.	Permanent / Contract Placement</h3>
            <h5>       
                    The candidates shall agree to commit themselves for a period of 2 months (excluding notice period) upon accepting the job offered by <b>SUCCESS HUMAN RESOURCE CENTRE PTE LTD / SUCCESS RESOURCE CENTRE PTE LTD</b> whether by writing or verbally, expressed or implied, failing which the candidates will have to compensate 30% of their offered salary to <b>SUCCESS HUMAN RESOURCE CENTRE PTE LTD / SUCCESS RESOURCE CENTRE PTE LTD</b>. This 30% compensation clause also applies to candidates who are terminated by our clients due to misconduct, poor performance or attendance, frequent medical leave, absence without calling or habitual late coming.
            </h5><br
            <h5>       
                    All candidates shall not have any direct contact with the clients for a period of 1 year after an interview arranged by <b>SUCCESS HUMAN RESOURCE CENTRE PTE LTD / SUCCESS RESOURCE CENTRE PTE LTD</b> unless approval is granted by the Agency.
            </h5><br><br>
            <h5>       
                    I agree to all the Terms & Conditions of this employment and hereby declare that all the particulars given in this application is true, complete and accurate to the best of my knowledge and if this declaration is in any part false or incorrect, the Agency / Company will reserve the right to terminate my services instantly.
            </h5><br><br>           
            <h5>       
                    I hereby authorize/consent <b>SUCCESS HUMAN RESOURCE CENTRE PTE LTD / SUCCESS RESOURCE CENTRE PTE LTD</b> to obtain and share all the information given in this application form/resume to any clients for job search purposes only.  I understand and agree that all modes of communication (Call, SMS, Email and Fax) may be necessary to execute the job search.  In order to opt out in the future, an email has to be submitted and acknowledged by <b>SUCCESS HUMAN RESOURCE CENTRE PTE LTD / SUCCESS RESOURCE CENTRE PTE LTD</b>, who will duly comply with the request, failing which, I will have no claim or recourse against the above-mentioned companies.
            </h5><br><br> 
            <div class="form-group">
            <label for="tc_date" class="col-sm-2 control-label">Join Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="tc_date" name="tc_date" value = "<?php echo format_date($this->tc_date);?>" placeholder="Date">
            </div>
            </div>
            </div>
        </div>
          <?php      
    }
    public function getOfficialUse(){
    ?> 
        <h3><u>Official Use</u></h3>
        <div class="form-group">
            <label for="overall_impression" class="col-sm-2 control-label">Overall Impression</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="overall_impression" name="overall_impression" placeholder="Comments"><?php echo $this->overall_impression;?></textarea>
            </div>
            <label for="communication_skills" class="col-sm-2 control-label">Communication Skills</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="communication_skills" name="communication_skills" placeholder="Comments"><?php echo $this->communication_skills;?></textarea>
            </div>
        </div>
            <div class="form-group">
            <label for="other_comments" class="col-sm-2 control-label">Other</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="other_comments" name="other_comments" placeholder="Comments"><?php echo $this->other;?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="official_consultant" class="col-sm-2 control-label">Consultant</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="official_consultant" name="official_consultant" value = "<?php echo $this->consultant;?>" placeholder="Consultant Name">
            </div>
            <label for="official_date" class="col-sm-2 control-label">Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="walkin_date" name="official_date" value = "<?php echo format_date($this->applicant_joindate);?>" placeholder="Date">
            </div>
        </div>
        <div class="form-group">
                    <label for="official_time" class="col-sm-2 control-label">Time</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;'>
                <input type="text" class="form-control timepicker" id="walkin_time" name="official_time" value = "<?php echo $this->official_time;?>">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
    <?php
    }
}

?>
