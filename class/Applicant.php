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
class Applicant {

    public function Applicant(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();

    }
    public function create(){
        //$this->applicant_login_password = md5("@#~x?\$" . $this->applicant_login_password . "?\$");
        $this->applicant_login_password = md5("@#~x?\$" . $this->applicant_nric . "?\$");
        $this->applicant_login_email = $this->applicant_email;
        $table_field = array('applicant_code','applicant_name','applicant_nric','applicant_tel','applicant_birthday',
                             'applicant_group','applicant_joindate','applicant_postal_code','applicant_unit_number',
                             'applicant_street','applicant_postal_code2','applicant_unit_number2','applicant_street2','applicant_black_list',
                             'applicant_height', 'applicant_weight', 'applicant_applpass',
                             'applicant_login_email','applicant_login_password','applicant_seqno','applicant_status',
                             'applicant_outlet','applicant_email','applicant_department','applicant_bank',
                             'applicant_bank_acc_no','applicant_nationality',
                             'applicant_mobile','applicant_position',
            
                             'applicant_marital_status','applicant_religion','applicant_sex',
                             'applicant_race','applicant_emer_contact','applicant_emer_relation','applicant_emer_phone1','applicant_emer_phone2',
                             'applicant_emer_address','applicant_emer_remarks','applicant_probation','applicant_prdate','applicant_resignreason',
                             'applicant_paymode','applicant_bank_acc_name','applicant_numberofchildren','applicant_isovertime',
                             'applicant_work_time_start','applicant_work_time_end',
            
                              'referee_name1', 'referee_occupation1', 'referee_year_know1', 'referee_contact_no1',
                              'referee_name2', 'referee_occupation2', 'referee_year_know2', 'referee_contact_no2',
                              'referee_present_employer', 'referee_previous_employer',
            
                              'declaration_bankrupt','declaration_physical','declaration_lt_medical','declaration_law','declaration_warning','declaration_applied',
                              'appl_declaration_b_specify','appl_declaration_p_specify','appl_declaration_ltm_specify','appl_declaration_l_specify','appl_declaration_w_specify',  
                              'appl_declaration_a_specify', 'tc_date',
            
                              'appl_o_level', 'appl_n_level', 'appl_a_level', 'appl_degree', 'appl_diploma', 'appl_other_qualification',
                              'appl_written', 'appl_spoken',
                              'overall_impression', 'communication_skill', 'other_comments', 'official_consultant','official_date','official_time',
            );
        $table_value = array(get_prefix_value("Applicant code",true),$this->applicant_name,$this->applicant_nric,$this->applicant_tel,format_date_database($this->applicant_birthday),
                             5,format_date_database($this->applicant_joindate),$this->applicant_postal_code,$this->applicant_unit_no,$this->applicant_address,
                             $this->applicant_postal_code2,$this->applicant_unit_no2,$this->applicant_address2,$this->applicant_black_list,
                             $this->applicant_height, $this->applicant_weight,$this->applicant_applicantpass,
                             $this->applicant_login_email,$this->applicant_login_password,$this->applicant_seqno,$this->applicant_status,
                             $this->applicant_outlet,$this->applicant_email,$this->applicant_department,$this->applicant_bank,
                             $this->applicant_bank_acc_no,$this->applicant_nationality,
                             $this->applicant_mobile,$this->applicant_position,

                             $this->applicant_marital_status,$this->applicant_religion,$this->applicant_sex,
                             $this->applicant_race,$this->applicant_emer_contact,$this->applicant_emer_relation,$this->applicant_emer_phone1,$this->applicant_emer_phone2,
                             $this->applicant_emer_address,$this->applicant_emer_remarks,$this->applicant_probation,format_date_database($this->applicant_prdate),$this->applicant_resignreason,
                             $this->applicant_paymode,$this->applicant_bank_acc_name,$this->applicant_numberofchildren,$this->applicant_isovertime,
                             $this->applicant_work_time_start,$this->applicant_work_time_end,
            
                             $this->referee_name1,  $this->referee_occupation1,  $this->referee_year_know1,  $this->referee_contact_no1,
                             $this->referee_name2,  $this->referee_occupation2,  $this->referee_year_know2,  $this->referee_contact_no2,
                             $this->referee_present_employer,  $this->referee_previous_employer,
                             $this->declaration_bankrupt, $this->declaration_physical, $this->declaration_lt_medical, $this->declaration_law, $this->declaration_warning, $this->declaration_applied, 
                             $this->db_specify, $this->dp_specify, $this->dltm_specify, $this->dl_specify, $this->dw_specify, $this->da_specify,
                             format_date_database($this->tc_date),
            
                              $this->appl_o_level, $this->appl_n_level, $this->appl_a_level, $this->appl_degree, $this->appl_diploma, $this->appl_other_qualification,
                              $this->appl_written, $this->appl_spoken,
                             $this->overall_impression, $this->communication_skills, $this->other_comments, $this->official_consultant, format_date_database($this->official_date), $this->official_time,
                );
    
        $remark = "Insert Candidate.";
        $familyremark = "Insert Family.";

        if(!$this->save->SaveData($table_field,$table_value,'db_applicant','applicant_id',$remark)){
           return false;
        }else{
           $this->applicant_id = $this->save->lastInsert_id;
           $this->pictureManagement();
//        $sql = "INSERT INTO db_family (family_id, family_name, family_relationship, family_contact_no, family_age, family_occupation, applicant_family_id) VALUES ('null' , '$this->appfamily_name', '$this->appfamily_relation', '$this->appfamily_contact', '$this->appfamily_age', '$this->appfamily_occupation', '$this->applicant_id');";
//        mysql_query($sql);
        
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
//        $new_password = $this->applicant_login_password;
//        $applicant_id = $this->applicant_id;
//        $applicant_login_email = $this->applicant_login_email;

//        if($this->applicant_oldpassword != $new_password){
//          $this->applicant_login_password = md5("@#~x?\$" . $new_password . "?\$");
//        }

        $this->applicant_login_password = md5("@#~x?\$" . $this->applicant_nric . "?\$");
        $this->applicant_login_email = $this->applicant_email;
        $table_field = array('applicant_code','applicant_name','applicant_nric','applicant_tel','applicant_birthday',
                             'applicant_group','applicant_joindate','applicant_postal_code','applicant_unit_number',
                             'applicant_street','applicant_postal_code2','applicant_unit_number2','applicant_street2','applicant_black_list',
                             'applicant_height', 'applicant_weight', 'applicant_applpass',
                             'applicant_login_email','applicant_login_password','applicant_seqno','applicant_status',
                             'applicant_outlet','applicant_email','applicant_department','applicant_bank',
                             'applicant_bank_acc_no','applicant_nationality',
                             'applicant_mobile','applicant_position',
            
                             'applicant_marital_status','applicant_religion','applicant_sex',
                             'applicant_race','applicant_emer_contact','applicant_emer_relation','applicant_emer_phone1','applicant_emer_phone2',
                             'applicant_emer_address','applicant_emer_remarks','applicant_probation','applicant_prdate','applicant_resignreason',
                             'applicant_paymode','applicant_bank_acc_name','applicant_numberofchildren','applicant_isovertime',
                             'applicant_work_time_start','applicant_work_time_end',
            
                              'referee_name1', 'referee_occupation1', 'referee_year_know1', 'referee_contact_no1',
                              'referee_name2', 'referee_occupation2', 'referee_year_know2', 'referee_contact_no2',
                              'referee_present_employer', 'referee_previous_employer',
            
                              'declaration_bankrupt','declaration_physical','declaration_lt_medical','declaration_law','declaration_warning','declaration_applied',
                              'appl_declaration_b_specify','appl_declaration_p_specify','appl_declaration_ltm_specify','appl_declaration_l_specify','appl_declaration_w_specify',  
                              'appl_declaration_a_specify', 'tc_date',
                                          
                              'appl_o_level', 'appl_n_level', 'appl_a_level', 'appl_degree', 'appl_diploma', 'appl_other_qualification',
                              'appl_written', 'appl_spoken',
                              'overall_impression', 'communication_skill', 'other_comments', 'official_consultant','official_date','official_time'
            
            );
        $table_value = array(get_prefix_value("Applicant code",true),$this->applicant_name,$this->applicant_nric,$this->applicant_tel,format_date_database($this->applicant_birthday),
                             5,format_date_database($this->applicant_joindate),$this->applicant_postal_code,$this->applicant_unit_no,$this->applicant_address,
                             $this->applicant_postal_code2,$this->applicant_unit_no2,$this->applicant_address2,$this->applicant_black_list,
                             $this->applicant_height, $this->applicant_weight, $this->applicant_applicantpass,
                             $this->applicant_login_email,$this->applicant_login_password,$this->applicant_seqno,$this->applicant_status,
                             $this->applicant_outlet,$this->applicant_email,$this->applicant_department,$this->applicant_bank,
                             $this->applicant_bank_acc_no,$this->applicant_nationality,
                             $this->applicant_mobile,$this->applicant_position,

                             $this->applicant_marital_status,$this->applicant_religion,$this->applicant_sex,
                             $this->applicant_race,$this->applicant_emer_contact,$this->applicant_emer_relation,$this->applicant_emer_phone1,$this->applicant_emer_phone2,
                             $this->applicant_emer_address,$this->applicant_emer_remarks,$this->applicant_probation,format_date_database($this->applicant_prdate),$this->applicant_resignreason,
                             $this->applicant_paymode,$this->applicant_bank_acc_name,$this->applicant_numberofchildren,$this->applicant_isovertime,
                             $this->applicant_work_time_start,$this->applicant_work_time_end,
         
                             $this->referee_name1,  $this->referee_occupation1,  $this->referee_year_know1,  $this->referee_contact_no1,
                             $this->referee_name2,  $this->referee_occupation2,  $this->referee_year_know2,  $this->referee_contact_no2,
                             $this->referee_present_employer,  $this->referee_previous_employer,
                             $this->declaration_bankrupt, $this->declaration_physical, $this->declaration_lt_medical, $this->declaration_law, $this->declaration_warning, $this->declaration_applied,
                             $this->db_specify, $this->dp_specify, $this->dltm_specify, $this->dl_specify, $this->dw_specify, $this->da_specify,
                             format_date_database($this->tc_date),
                              $this->appl_o_level, $this->appl_n_level, $this->appl_a_level, $this->appl_degree, $this->appl_diploma, $this->appl_other_qualification,
                              $this->appl_written, $this->appl_spoken,
                             $this->overall_impression, $this->communication_skills, $this->other_comments, $this->official_consultant, format_date_database($this->official_date), $this->official_time
            );      
    
        $remark = "Update Candidate.";
        $familyremark = "Update Family.";

        if(!$this->save->UpdateData($table_field,$table_value,'db_applicant','applicant_id',$remark,$this->applicant_id)){
           return false;
        }else{
           $this->pictureManagement();
           $this->resumeManagement();
           return true;
        }
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
    public function createLeave($applicantleave_leavetype,$applicantleave_days,$applicantleave_disabled,$applicantleave_entitled){
        $table_field = array('applleave_appl','applleave_leavetype','applleave_days','applleave_year','applleave_status','applleave_disabled','applleave_entitled');
        $table_value = array($this->applicant_id,$applicantleave_leavetype,$applicantleave_days,date("Y"),1,$applicantleave_disabled,$applicantleave_entitled);
        $remark = "Create Applicant Leaves.";
        if(!$this->save->SaveData($table_field,$table_value,'db_applleave','applleave_id',$remark)){
           return false;
        }else{
          return true;
        }
    }
    public function updateLeave($applicantleave_days,$applicantleave_id,$applicantleave_disabled,$applicantleave_entitled){


        $table_field = array('applleave_days','applleave_disabled','applleave_entitled');
        $table_value = array($applicantleave_days,$applicantleave_disabled,$applicantleave_entitled);
        $remark = "Update Applicant Leaves.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_applleave','applleave_id',$remark,$applicantleave_id," AND applleave_appl = '$this->applicant_id'")){
           return false;
        }else{
           return true;
        }
    }
    
    public function getSalaryForm(){
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
        <h3>Update Applicant Salary  <button type="button" class="btn btn-primary" style="width:150px;margin-right:10px;" onclick="window.location.href='applicant.php?action=edit&current_tab=Salary Info&applicant_id=12'">Create New Salary &nbsp; <i class="fa fa-plus-square" aria-hidden="true"></i></button></h3>
        <?php }else{?>
        <h3>Create New Applicant Salary</h3> 
        <?php }?>
        <div class="form-group">
              <label for="applicantsalary_date" class="col-sm-2 control-label">Date</label>
              <div class="col-sm-2">
               <input type="text" class="form-control datepicker" id="applicantsalary_date" name="applicantsalary_date" value = "<?php echo format_date($this->applicantsalary_date);?>" placeholder="Adjustment Date">
              </div>
        </div>
        <div class="form-group">
              <label for="applicantsalary_amount" class="col-sm-2 control-label">Salary ($)</label>
              <div class="col-sm-3">
                  <input type="text" style = 'text-align:right' class="form-control" id="applicantsalary_amount" name="applicantsalary_amount" value = "<?php echo num_format($this->applicantsalary_amount);?>" placeholder="Salary ($)">
              </div>
              <label for="applicantsalary_workday" class="col-sm-2 control-label">	No of Work Days in Month</label>
              <div class="col-sm-3">
               <input type="text" style = 'text-align:right' class="form-control" id="applicantsalary_workday" name="applicantsalary_workday" value = "<?php echo $this->applicantsalary_workday;?>" placeholder="Total Working Days">
              </div>
        </div>
        <div class="form-group">
              <label for="applicantsalary_hourly" class="col-sm-2 control-label">Hourly Salary Rate</label>
              <div class="col-sm-3">
                <input type="text" style = 'text-align:right' class="form-control" id="applicantsalary_hourly" name="applicantsalary_hourly" value = "<?php echo $this->applicantsalary_hourly;?>" placeholder="Hourly Salary">
              </div>
              <label for="applicantsalary_overtime" class="col-sm-2 control-label">Overtime Hourly Rate</label>
              <div class="col-sm-3">
               <input type="text" style = 'text-align:right' class="form-control" id="applicantsalary_overtime" name="applicantsalary_overtime" value = "<?php echo $this->applicantsalary_overtime;?>" placeholder="Overtime Hourly ">
              </div>
        </div>

        <div class="form-group">
          <label for="applicantsalary_remark" class="col-sm-2 control-label">Remark</label>
          <div class="col-sm-3">
                <textarea class="form-control" rows="3" id="applicantsalary_remark" name="applicantsalary_remark" placeholder="Remark"><?php echo $this->applicantsalary_remark;?></textarea>
          </div>
          <div class="col-sm-3 "></div>
          <div class="col-sm-3 ">
              <button type = "button" class="btn btn-info save_salary_btn" >
                  <?php if($this->applicantsalary_id > 0){?>
                  Update
                  <?php }else{?>
                  Save
                  <?php }?>
              </button>
              <input type = 'hidden' value = '<?php echo $this->applicantsalary_id;?>' name = 'applicantsalary_id' id = 'applicantsalary_id'/>
          </div>
        </div>
        <table id="applicant_table" class="table table-bordered table-hover dataTable">
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
                              FROM db_applicantsalary ey 
                              WHERE ey.applicantsalary_applicant_id = '{$this->applicant_id}' AND ey.applicantsalary_status = 1";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo format_date($row['applicantsalary_date']);?></td>
                            <td><?php echo num_format($row['applicantsalary_amount']);?></td>
                            <td><?php echo nl2br($row['applicantsalary_remark']);?></td>
                            <td><?php echo getDataCodeBySql("applicant_name","db_applicant","WHERE  applicant_id = '{$row['updateBy']}'","");?></td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicant.php?action=edit&current_tab=Salary Info&applicant_id=<?php echo $this->applicant_id;?>&applicantsalary_id=<?php echo $row['applicantsalary_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('applicant.php?action=deletesalary&current_tab=Salary Info&applicant_id=<?php echo $this->applicant_id;?>&applicantsalary_id=<?php echo $row['applicantsalary_id'];?>','Confirm Delete?')">Delete</button>
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
    public function createSalary(){
        $table_field = array('applicantsalary_applicant_id','applicantsalary_date','applicantsalary_amount','applicantsalary_remark','applicantsalary_status',
                             'applicantsalary_workday','applicantsalary_hourly','applicantsalary_overtime');
        $table_value = array($this->applicant_id,format_date_database($this->applicantsalary_date),$this->applicantsalary_amount,$this->applicantsalary_remark,1,
                             $this->applicantsalary_workday,$this->applicantsalary_hourly,$this->applicantsalary_overtime);
        $remark = "Create Salary Adjustment.";
        if(!$this->save->SaveData($table_field,$table_value,'db_applicantsalary','applicantsalary_id',$remark)){
            return false;
        }else{
            return true;
        }
    }
    public function updateSalary(){

        $table_field = array('applicantsalary_applicant_id','applicantsalary_date','applicantsalary_amount','applicantsalary_remark',
                             'applicantsalary_workday','applicantsalary_hourly','applicantsalary_overtime');
        $table_value = array($this->applicant_id,format_date_database($this->applicantsalary_date),$this->applicantsalary_amount,$this->applicantsalary_remark,
                             $this->applicantsalary_workday,$this->applicantsalary_hourly,$this->applicantsalary_overtime);
        $remark = "Update Salary Adjustment.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_applicantsalary','applicantsalary_id',$remark,$this->applicantsalary_id," AND applicantsalary_applicant_id = '$this->applicant_id'")){
           return false;
        }else{
           return true;
        }
    }
    public function deleteSalary(){
        $table_field = array('applicantsalary_status');
        $table_value = array(0);
        $remark = "Delete Applicant Salary.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_applicantsalary','applicantsalary_id',$remark,$this->applicantsalary_id," AND applicantsalary_applicant_id = '$this->applicant_id'")){
           return false;
        }else{
           return true;
        }
    }
    
    public function getFollowUpForm(){   
        if($this->follow_id > 0){
           $this->fetchFollowDetail(" AND follow_id = '$this->follow_id'","","",1);
           $this->assignCrtl = $this->select->getAssignSelectCtrl($this->assign_id);
           $this->clientCrtl = $this->select->getClientSelectCtrl($this->interview_company);
           $this->payrollCrtl = $this->select->getPayrollEmplSelectCtrl($this->fol_payroll_empl);
           $edit = escape($_REQUEST['edit']);
           $this->managerCrtl = $this->select->getManagerCtrl($this->assign_manager);
        }
        else
        {
            $this->assignCrtl = $this->select->getAssignSelectCtrl($this->assign_to);
            $this->clientCrtl = $this->select->getClientSelectCtrl($this->interview_company);
            $this->payrollCrtl = $this->select->getPayrollEmplSelectCtrl($this->fol_payroll_empl);
            $this->managerCrtl = $this->select->getManagerCtrl($this->assign_manager);
        }
    ?>
        <?php if($this->follow_id > 0){?>
        <h3>Update Remarks</h3>
        <?php }else{?>
        <h3>Create Remarks</h3> 
        <?php }?>
        <button type="button" class="btn btn-primary" style="width:150px;margin-right:10px;" onclick="window.location.href='applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $this->applicant_id; ?>'">Create New Remarks &nbsp; <i class="fa fa-plus-square" aria-hidden="true"></i></button>
        <br><br>
        <div class="form-group ">
            <?php if($_SESSION['empl_group'] == "4") {?>
            <label for="assign_to" class="col-sm-2 control-label">Assign To</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="assign_to" name="assign_to" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                        <?php echo $this->assignCrtl;?>
                 </select>
            </div>
            <?php }
            else {?>
            <div class='default'>
                <label for="assign_to" class="col-sm-2 control-label">Assign To</label>
                <div class="col-sm-3">
                     <select class="form-control select2" id="assign_to" name="assign_to" style = 'width:100%' disabled>
                         <option> </option>
                     </select>
                </div>
            </div>
            <div class='assign'>
                <label for="assign_to2" class="col-sm-2 control-label">Assign To</label>
                <div class="col-sm-3">
                     <select class="form-control select2" id="assign_to2" name="assign_to2" style = 'width:100%' readonly <?php if ($edit == "1"){echo "disabled";}?>>>
                         <option value="<?php echo $_SESSION['empl_id'];?>"><?php echo $_SESSION['empl_name'];?></option>
                     </select>
                </div>
            </div>
            <?php }?>            
            
            
            
            <?php if($_SESSION['empl_group'] == "4") {?>
            <label for="follow_type" class="col-sm-2 control-label">Remark Type</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="follow_type" name="follow_type" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                     <?php 
                     if($this->follow_type == '0'){?>
                        <option value="0">Assign to Client</option>                        
                     <?php }
                     else if($this->follow_type =='1')
                     { ?>
                        <option value="1">Candidate Follow Up</option>
                     <?php }
                     else if ($this->follow_type == '2')
                     { ?>
                         <option value="2">Assign Interview</option>
                     <?php }
                     else if ($this->follow_type == '3')
                     { ?>
                         <option value="3">Assign Candidate to Employer (Manager)</option>
                     <?php }
                     else if ($this->follow_type == '4')
                     { ?>
                         <option value="4">Assign Candidate to Own</option>
                     <?php }
                     else {?>
                     <option value="0">Assign to Client</option>
                     <option value="1">Candidate Follow Up</option>
                     <option value="2">Assign Interview</option>
                     <option value="3">Assign Candidate to Employer (Manager)</option>      
                     <?php }?>
                 </select>
            </div>
            <?php }
            else {?>
                <label for="follow_type" class="col-sm-2 control-label">Remark Type</label>
                <div class="col-sm-3">
                     <select class="form-control select2" id="follow_type" name="follow_type" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                         <?php 
                         if($this->follow_type == '0'){?>
                            <option value="0">Assign to Client</option>                        
                         <?php }
                         else if($this->follow_type =='1')
                         { ?>
                            <option value="1">Candidate Follow Up</option>
                         <?php }
                         else if ($this->follow_type == '2')
                         { ?>
                             <option value="2">Assign Interview</option>
                         <?php }
                         else if ($this->follow_type == '3')
                         { ?>
                             <option value="3">Assign Candidate to Employer (Manager)</option>
                         <?php }       
                         else if ($this->follow_type == '4')
                         { ?>
                             <option value="4">Assign Candidate to Own</option>
                         <?php }
                         else {?>
                         <option value="0">Assign to Client</option>
                         <option value="1">Candidate Follow Up</option>
                         <option value="2">Assign Interview</option>
                         <option value="4">Assign Candidate to Own</option>  
                         <?php }?>
                     </select>
                </div>
            <?php }?>
        </div>
        
        <!--Assign to Client-->
        <div id="assigntoclient" class="assigntoclient">
            <div class="form-group">
                <label for="f_company" class="col-sm-2 control-label">Company</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="f_company" name="<?php echo $this->fol_job_title; ?>" pid="<?php echo $this->fol_department; ?>" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                            <?php echo $this->clientCrtl;?>
                     </select>
                </div>
                <label for="fol_department" class="col-sm-2 control-label">Department</label>
                <div class="col-sm-3 change">

                     <select class="form-control select2" id="fol_department" name="fol_department" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                     </select>

                </div>
            </div>
            <div class="form-group">
                <label for="fol_job_title" class="col-sm-2 control-label">Job Title</label>
                <div class="col-sm-3 change">
                     <select class="form-control select2" id="fol_job_title" name="fol_job_title" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                     </select>
                </div>
                <label for="f_salary" class="col-sm-2 control-label">Salary</label>
                <div class="col-sm-3">
                   <input type="text" class="form-control " id="f_salary" name="f_salary" value = "<?php echo $this->offer_salary;?>" placeholder="Salary" <?php if ($edit == "1"){echo "disabled";}?>>
                </div>
            </div>
            <?php if($_SESSION['empl_group'] == "4") {?>
            <div class="form-group">
                <label for="job_type" class="col-sm-2 control-label">Job Type</label>
                <div class="col-sm-3">
                     <select class="form-control select2" id="job_type" name="job_type" style = 'width:100%' <?php if($this->fol_approved == 'Y' || $this->fol_approved == 'N'){ echo 'disabled';} ?>>
                        <option value="">Select One</option>
                        <option value="PH" <?php if($this->fol_job_type == 'PH'){ echo 'SELECTED';}?>>Part Time (Hourly)</option>
                        <option value="PD" <?php if($this->fol_job_type == 'PD'){ echo 'SELECTED';}?>>Part Time (Daily)</option>
                        <option value="F" <?php if($this->fol_job_type == 'F'){ echo 'SELECTED';}?>>Full Time (Permanent)</option>
                        <option value="C" <?php if($this->fol_job_type == 'C'){ echo 'SELECTED';}?>>Contract</option>
                     </select>
                </div>
                <label for="probation_period" class="col-sm-2 control-label">Probation  Period</label>
                <div class="col-sm-3">
                     <select class="form-control select2" id="probation_period" name="probation_period" style = 'width:100%'>
                        <option value="">Select One</option>
                        <option value="0" <?php if($this->fol_probation_period == '0'){ echo 'SELECTED';}?>>No Probation</option>
                        <option value="3" <?php if($this->fol_probation_period == '3'){ echo 'SELECTED';}?>>3 Months</option>
                        <option value="6" <?php if($this->fol_probation_period == '6'){ echo 'SELECTED';}?>>6 Months</option>
                     </select>
                </div>
            </div>
            <?php }?>
            <div class="form-group">
                <label for="p_offer" class="col-sm-2 control-label">Position Offer</label>
                <div class="col-sm-3">
                   <input type="text" class="form-control " id="p_offer" name="p_offer" value = "<?php echo $this->position_offer;?>" placeholder="Position" <?php if ($edit == "1"){echo "disabled";}?>>
                </div>
                <label for="admin_fee" class="col-sm-2 control-label">Admin Fee</label>
                <div class="col-sm-3">
                   <input type="text" class="form-control " id="admin_fee" name="admin_fee" value = "<?php echo $this->admin_fee;?>" placeholder="Admin Fee" <?php if ($edit == "1"){echo "disabled";}?>>
                </div>
            </div>
            <div class="form-group">
                <label for="fol_payroll_empl" class="col-sm-2 control-label">Payroll In Charge Person</label>
                <div class="col-sm-3">
                     <select class="form-control select2" id="fol_payroll_empl" name="fol_payroll_empl" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                         <?php echo $this->payrollCrtl;?>
                     </select>
                </div>
                <label for="s_date" class="col-sm-2 control-label">Start Date</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control datepicker" id="s_date" name="s_date" value = "<?php echo format_date($this->available_date);?>" placeholder="Date" <?php if ($edit == "1"){echo "disabled";}?>>
                </div>                
            </div>
            <div class="form-group">
                <label for="fol_assign_expiry_date" class="col-sm-2 control-label">Assign Expiry Date</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control datepicker" id="fol_assign_expiry_date" name="fol_assign_expiry_date" value = "<?php echo format_date($this->fol_assign_expiry_date);?>" placeholder="Date">
                </div>  
            <?php //if($_SESSION['empl_group'] == "4") {?>
                <?php
                  if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],"approved")){
                ?>
                <label for="fol_approved" class="col-sm-2 control-label" style="color:red">Approved</label>
                <div class="col-sm-3">
                     <select class="form-control select2" id="fol_approved" name="fol_approved" style = 'width:100%' <?php if($this->fol_approved == 'Y' || $this->fol_approved == 'N'){ echo 'disabled';} ?>>
                        <option value="">Select One</option>
                        <option value="0" <?php if($this->fol_approved == '0'){ echo 'SELECTED';}?>>Pending</option>
                        <option value="Y" <?php if($this->fol_approved == 'Y'){ echo 'SELECTED';}?>>Yes</option>
                        <option value="N" <?php if($this->fol_approved == 'N'){ echo 'SELECTED';}?>>Not Suitable</option>
                     </select>
                </div>
            <?php }?>
            </div>
            
            
            
            
            
        </div>
        
        
        <!--Assign Interview-->
        <div id="assigninterview" class="assigninterview">
            <div class="form-group">
                <label for="interview_time" class="col-sm-2 control-label">Interview Time</label>
                <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;'>
                    <input type="text" class="form-control timepicker" id="interview_time" name="interview_time" value = "<?php echo $this->interview_time;?>" placeholder="Time">
                    <div class="input-group-addon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                </div>
                <label for="interview_date" class="col-sm-2 control-label">Interview Date</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control datepicker" id="interview_date" name="interview_date" value = "<?php echo format_date($this->interview_date);?>" placeholder="Date">
                </div>
            </div>
            <div class="form-group">
                <label for="interview_company" class="col-sm-2 control-label">Interview Company</label>
                <div class="col-sm-3">
                     <select class="form-control select2" id="interview_company" name="interview_company" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                            <?php echo $this->clientCrtl;?>
                     </select>
                </div>
                <label for="interview_by" class="col-sm-2 control-label">Interview By</label>
                <div class="col-sm-3">
                   <input type="text" class="form-control " id="interview_by" name="interview_by" value = "<?php echo $this->interview_by;?>" placeholder="Name" >
                </div>
            </div>
           <div class="form-group">
                <label for="expected_salary" class="col-sm-2 control-label">Expected Salary</label>
                <div class="col-sm-3">
                   <input type="text" class="form-control" id="expected_salary" name="expected_salary" value = "<?php echo $this->expected_salary;?>" placeholder="Salary" <?php if ($edit == "1"){echo "disabled";}?>>
                </div>
                <label for="offer_salary" class="col-sm-2 control-label">Jobs Offer Salary</label>
                <div class="col-sm-3">
                   <input type="text" class="form-control " id="offer_salary" name="offer_salary" value = "<?php echo $this->offer_salary;?>" placeholder="Salary" <?php if ($edit == "1"){echo "disabled";}?>>
                </div>
            </div>
            <div class="form-group">
                <label for="position_offer" class="col-sm-2 control-label">Position Offer</label>
                <div class="col-sm-3">
                   <input type="text" class="form-control " id="position_offer" name="position_offer" value = "<?php echo $this->position_offer;?>" placeholder="Position" <?php if ($edit == "1"){echo "disabled";}?>>
                </div>
                <label for="applicant_attend" class="col-sm-2 control-label">Attend Interview</label>

                    <div class="radio col-sm-3">
                     <select class="form-control select2" id="applicant_attend" name="applicant_attend" style = 'width:100%'>
                     <?php 
                     if($this->applicant_attend == '0'){?>
                        <option value="0">Yes</option>
                        <option value="1">No</option>
                     <?php }
                     else
                     { ?>
                        <option value="1">No</option>
                        <option value="0">Yes</option>
                     <?php }
                     ?>

                     </select>
                     </div>
            </div>
            <div class="form-group">
                <label for="received_offer" class="col-sm-2 control-label">Received Job Offer</label>

                    <div class="radio col-sm-3">
                     <select class="form-control select2" id="received_offer" name="received_offer" style = 'width:100%'>
                     <?php 
                     if($this->received_offer == '0'){?>
                        <option value="0">Pending</option>
                        <option value="1">Yes</option>
                        <option value="2">No</option>
                     <?php }
                     else if($this->received_offer =='1')
                     { ?>
                        <option value="1">Yes</option>
                        <option value="0">Pending</option>
                        <option value="2">No</option>
                     <?php }
                     else if($this->received_offer =='2')
                     { ?>
                        <option value="2">No</option>
                        <option value="0">Pending</option>
                        <option value="1">Yes</option>
                     <?php }
                     else {?>
                        <option value="0">Pending</option>
                        <option value="1">Yes</option>
                        <option value="2">No</option>
                     <?php }?>
                     </select>
                    </div>
                <label for="available_date" class="col-sm-2 control-label">Available Date</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control datepicker" id="available_date" name="available_date" value = "<?php echo format_date($this->available_date);?>" placeholder="Date">
                </div>
            </div>
            <div class="form-group">
                <label for="not_suitable" class="col-sm-2 control-label">Not Suitable</label>

                    <div class="radio col-sm-3">
                     <select class="form-control select2" id="not_suitable" name="not_suitable" style = 'width:100%'>

                        <option value="0" <?php if($this->not_suitable == '0'){echo "SELECTED";}?>>No</option>
                        <option value="1" <?php if($this->not_suitable == '1'){echo "SELECTED";}?>>Yes</option>

                     </select>
                     </div>
            </div>
        </div>
        
        <!--Assign Candidate To Empl-->
        <div id="assignCandidateToEmpl" class="assignCandidateToEmpl">
            <?php if($_SESSION['empl_group'] == "4") {?>
            <label for="assign_manager" class="col-sm-2 control-label">Manager</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="assign_manager" name="assign_manager" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                        <option value="<?php echo $_SESSION['empl_id'];?>"><?php echo $_SESSION['empl_name'];?></option>
                 </select>
            </div>
            <?php }
            else {?>
            <label for="assign_manager" class="col-sm-2 control-label">Manager</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="assign_manager" name="assign_manager" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                        <?php echo $this->managerCrtl;?>
                 </select>
            </div>
            <?php }?>             
        </div>
        
            <div class="form-group">
                <?php if($_SESSION['empl_group'] == "4") {?>
                    <label for="follow_notice" class="col-sm-2 control-label">Notice to Consultant</label>
                    <div class="radio col-sm-3">
                     <select class="form-control select2" id="follow_notice" name="follow_notice" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                        <option value="0">Yes</option>
                     </select>
                     </div>
                <?php }
                else {?>
                <label for="follow_notice" class="col-sm-2 control-label">Notice to Head</label>
                    <div class="radio col-sm-3">
                     <select class="form-control select2" id="follow_notice" name="follow_notice" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                     <?php 
                     if($this->follow_notice == '1'){?>
                        <option value="1">No</option>
                        <option value="0">Yes</option>
                     <?php }
                     else
                     { ?>
                        <option value="0">Yes</option>
                        <option value="1">No</option>
                     <?php }?>
                     </select>
                     </div>
                <?php }?>
                <div id="assigntoclient" class="assigntoclient">
                    <div class="radio col-sm-3">
                        <?php if($this->follow_id > 0){?>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Leave</button>
                        <?php }?>
                    </div>
                </div>    
            </div>
        <div class="form-group">
                    <label for="followup_comments" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-8">
                    <textarea id="editor1" class="followup_comments" name="followup_comments" placeholder="Description" rows="10" cols="80" <?php if ($edit == "1"){echo "disabled";}?>><?php echo $this->followup_comments;?>
                    </textarea>
                    </div>
        </div>
          <div class="col-sm-3 ">
              <button type = "button" class="btn btn-info save_followup_btn" >
                  <?php if($this->follow_id > 0){?>
                  Update
                  <?php }else{?>
                  Save
                  <?php }?>    
              </button>
              <input type = 'hidden' value = '<?php echo $this->follow_id;?>' name = 'follow_id' id = 'follow_id'/>
<!--              <button type="button" class="btn btn-primary btn-info " onclick = "confirmAlertHref('applicant.php?action=saveFamily&applicant_id=<?php echo $this->applicant_id;?>&appfamily_name=<?php echo $this->appfamily_name;?>','Confirm Save?')">Save</button>-->
          </div><br><br><br>
               
          <?php $this->getApplicantLeaveForm(); ?>
          
        <table id="followup_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Create By</th>
                        <th style = 'width:10%'>Assign</th>
                        <th style = 'width:10%'>Remark Type</th>
                        <th style = 'width:10%'>Company</th>
                        <th style = 'width:10%'>Received Job</th>
                        <th style = 'width:20%'>Comments</th>
                        <th style = 'width:5%'>Status</th>
                        <th style = 'width:7%'>Create Time</th>
                        <th style = 'width:7%'>Create Date</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "select f.*, f.follow_id, f.follow_type, f.assign_to, left(f.insertDateTime,10) as date, right(f.insertDateTime, 8) as time, f.interview_company, f.attend_interview, f.received_offer, f.comments, f.insertBy, e.empl_name from db_followup f inner join db_empl e on f.insertBy = e.empl_id where f.applfollow_id = '$this->applicant_id' and f.fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['empl_name'];?></td>
                            <?php
                            $id = $row['assign_to'];
                            $sql2 = "select empl_name as empl_name from db_empl where empl_id = '$id' group by empl_name";
                            $query2 = mysql_query($sql2);
                            $row2 = mysql_fetch_array($query2)?>
                            
                            <td>
                                <?php 
                                if($row['assign_to'] != ""){echo $row2['empl_name'];}
                                else { echo "Own follow up";}
                                ?>
                            </td>
                            <td>
                                <?php 
                                if ($row['follow_type']=="0"){
                                echo "Assign to Client";        
                                }
                                if ($row['follow_type']=="1"){
                                echo "Candidate Follow Up";
                                }
                                if ($row['follow_type']=="2"){
                                echo "Assign Interview";
                                }
                                if ($row['follow_type']=="3"){
                                echo "Assign Candidate to Employer";
                                }
                                if ($row['follow_type']=="4"){
                                echo "Assign Candidate to Own";
                                }
                                ;?>
                            </td>
                            
                            <?php
                            $partner_id = $row['interview_company'];
                            $sql3 = "select partner_name from db_partner where partner_id = '$partner_id'";
                            $query3 = mysql_query($sql3);
                            $row3 = mysql_fetch_array($query3)?>
                            
                            <td><?php if($row['interview_company'] != ""){echo $row3['partner_name'];}
                                        else {echo "-";}
                                ?>
                            </td>
                            
                            
                            
                            <td>
                                <?php 
                                     if($row['follow_type']=="0"){
                                       echo "Job Assigned";
                                     }
                                     else{
                                          if ($row['received_offer']=='0'){echo "Pending";} 
                                          else if ($row['received_offer']=='1'){echo "Yes";} 
                                          else if ($row['received_offer']=='2'){echo "No";} 
                                          else{ echo "-"; }
                                     }
                                ?>
                            
                            </td>
                            <td><?php echo preg_replace('#<script(.*?)>(.*?)</script>#is', '',htmlspecialchars_decode($row['comments']));?></td>
                            <td>
                            <?php 
                            if($row['follow_type'] == "0")
                            {
                                if($row['fol_approved'] == "Y"){echo "Approved";}
                                else if($row['fol_approved'] == "N"){echo "Not Suitable";}
                                //if($row['fol_approved'] == "0"){echo "Pending";}
                                else echo "Pending";
                            }
                            else if($row['follow_type'] == "2")  
                            {
                                echo "Interview";
                            }
                            else {echo "-";}
                            ?>
                            </td>
                            <td><?php echo $row['time'];?></td>
                            <td><?php echo $row['date'];?></td>
                            <td class = "text-align-right">
 
                                <input type = 'hidden' value = '<?php echo $row['follow_id'];?>' name = "follow" id = "follow"/>
                                
                                
                                <?php
                                $manager_id = $row['insertBy'];
                                $sql4 = "select empl_group from db_empl where empl_id = '$manager_id'";
                                $query4 = mysql_query($sql4);
                                $row4 = mysql_fetch_array($query4);
                                
                                $nowtime = date("H:i:s");
                                $nowDate = date("Y-m-d");
                                
                                $datetime1 = new DateTime($row['time']);
                                $datetime2 = new DateTime($nowtime);
                                
                                $normal_hour = $datetime1->diff($datetime2);
                                $hour = $normal_hour->format('%h');
                                $hour = $hour * 60;
                                $minute = $normal_hour->format('%i');
                                $minute = $minute + $hour;

                                $datetime1 = new DateTime($row['date']);
                                $datetime2 = new DateTime($nowDate);
                                
                                $normal_day = $datetime1->diff($datetime2);
                                $day = $normal_day->format('%a');                              

                                $sql5 = "SELECT outlet_time_minute from db_outlet WHERE outl_id = (SELECT empl_outlet FROM db_empl WHERE empl_id = '$_SESSIO[empl_id]')";
                                $query5 = mysql_query($sql5);
                                $row5 = mysql_fetch_array($query5);
                                
                                if($row4['empl_group'] == '4'){
                                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'view')){?>
                                            <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $this->applicant_id;?>&follow_id=<?php echo $row['follow_id'];?>&edit=1'">View</button>
                                    <?php } 
                                    if($_SESSION['empl_group'] == "4") {
                                        if($day == 0 && $minute <= $row5['outlet_time_minute']){
                                            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){?>
                                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $this->applicant_id;?>&follow_id=<?php echo $row['follow_id'];?>'">Edit</button>
                                            <?php }
                                        }
                                    }
                                } 
                                else 
                                {   
                                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'view')){?>
                                            <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $this->applicant_id;?>&follow_id=<?php echo $row['follow_id'];?>&edit=1'">View</button>
                                        <?php } 
                                        if($day == 0 && $minute <= $row5['outlet_time_minute']){
                                            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){?>
                                                <button id="editfollowup" type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $this->applicant_id;?>&follow_id=<?php echo $row['follow_id'];?>'">Edit</button>
                                            <?php }
                                        } 
                                }
                                if($_SESSION['empl_group'] == '10'){ 
                                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){?>
                                            <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('applicant.php?action=deleteFollow&current_tab=followup&applicant_id=<?php echo $this->applicant_id;?>&follow_id=<?php echo $row['follow_id'];?>','Confirm Delete?')">Delete</button>
                                        <?php } 
                                }?>        
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
                        <th style = 'width:10%'>Create By</th>
                        <th style = 'width:10%'>Assign</th>
                        <th style = 'width:10%'>Remark Type</th>
                        <th style = 'width:15%'>Company</th>
                        <th style = 'width:10%'>Received Job</th>
                        <th style = 'width:20%'>Comments</th>
                        <th style = 'width:5%'>Status</th>
                        <th style = 'width:7%'>Create Time</th>
                        <th style = 'width:7%'>Create Date</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </tfoot>
                  </table>
             
                <div class="form-group">

        </div>
    <?php
    } 
    public function createFollowUp(){
        if($this->follow_type=='0'){
        $table_field = array('follow_id','assign_to','follow_type','interview_time','interview_date','fol_department','fol_job_assign','fol_payroll_empl','fol_admin_fee','fol_job_type','fol_probation_period','fol_approved',
                             'interview_company','interview_by','fol_available_date','fol_position_offer','fol_expected_salary','fol_offer_salary','attend_interview','received_offer','fol_assign_expiry_date','comments','follow_notice','applfollow_id');
        $table_value = array('', $this->assign_to,$this->follow_type,$this->interview_time,format_date_database($this->interview_date),$this->fol_department,$this->fol_job_title,$this->fol_payroll_empl,$this->admin_fee,$this->fol_job_type,$this->fol_probation_period,$this->fol_approved,
                             $this->f_company, $this->interview_by,format_date_database($this->s_date),$this->p_offer,$this->expected_salary,$this->f_salary,$this->applicant_attend, $this->received_offer, format_date_database($this->fol_assign_expiry_date), $this->followup_comments,
                             $this->follow_notice, $this->applicant_id);           
        }
        else{
        $table_field = array('follow_id','assign_to','follow_type','interview_time','interview_date','fol_assign_manager','fol_not_suitable',
                             'interview_company','interview_by','fol_available_date','fol_position_offer','fol_expected_salary','fol_offer_salary','attend_interview','received_offer','comments','follow_notice','applfollow_id');
        $table_value = array('', $this->assign_to,$this->follow_type,$this->interview_time,format_date_database($this->interview_date),$this->assign_manager,$this->not_suitable,
                             $this->interview_company, $this->interview_by,format_date_database($this->available_date),$this->position_offer,$this->expected_salary,$this->offer_salary,$this->applicant_attend, $this->received_offer, $this->followup_comments,
                             $this->follow_notice, $this->applicant_id);
        }
        $remark = "Create Remarks.";
        if(!$this->save->SaveData($table_field,$table_value,'db_followup','follow_id',$remark)){
            return false;
        }else{
            $this->follow_id = $this->save->lastInsert_id;
            if($this->follow_notice == "0"){
                if($this->follow_type == "0"){
                    $sql = "SELECT partner_name FROM db_partner WHERE partner_id = '$this->f_company'";
                    $query = mysql_query($sql);
                    $row = mysql_fetch_array($query);
                    $name = $row['partner_name'];
                }
                else if($this->follow_type == "2"){
                    $sql = "SELECT partner_name FROM db_partner WHERE partner_id = '$this->interview_company'";
                    $query = mysql_query($sql);
                    $row = mysql_fetch_array($query);
                    $name = $row['partner_name'];
                }
                else if($this->follow_type == "3"){
                    $sql = "SELECT empl_name FROM db_empl WHERE empl_id = '$this->assign_to'";
                    $query = mysql_query($sql);
                    $row = mysql_fetch_array($query);
                    $name = $row['empl_name'];
                }
                else{
                    $name  = "";
                }
                    $this->saveNotification($name);
            }
            return true;
            
        }
    }
    public function updateFollowUp(){
        
        if($_REQUEST['edit'] == '1'){
            if($this->follow_type=='0'){
            $table_field = array('fol_assign_expiry_date','fol_job_type','fol_probation_period','fol_approved');
            $table_value = array(format_date_database($this->fol_assign_expiry_date,$this->fol_job_type,$this->fol_probation_period,$this->fol_approved));  
            }
            else{
                if($_SESSION['empl_group'] == "4"){
                $table_field = array( 'assign_to','follow_type','interview_time','interview_date','fol_not_suitable',
                                     'interview_company','interview_by','fol_available_date','fol_position_offer','fol_expected_salary','fol_offer_salary','attend_interview','received_offer','comments','follow_notice');
                $table_value = array( $this->assign_to,$this->follow_type,$this->interview_time,format_date_database($this->interview_date),$this->not_suitable,
                                     $this->interview_company, $this->interview_by,format_date_database($this->available_date),$this->position_offer,$this->expected_salary,$this->offer_salary,$this->applicant_attend, $this->received_offer, $this->followup_comments,
                                     $this->follow_notice);
                }
                else {
                $table_field = array('interview_time','interview_date','fol_not_suitable',
                                     'interview_by','fol_available_date','attend_interview','received_offer');
                $table_value = array($this->interview_time,format_date_database($this->interview_date),$this->not_suitable,
                                     $this->interview_by,format_date_database($this->available_date),$this->applicant_attend, $this->received_offer);
                }
            }
        }
        else{
            if($this->follow_type=='0'){
            $table_field = array('follow_type','interview_time','interview_date','fol_department','fol_job_assign','fol_payroll_empl','fol_admin_fee','fol_job_type','fol_probation_period','fol_approved',
                                 'interview_company','interview_by','fol_available_date','fol_position_offer','fol_expected_salary','fol_offer_salary','attend_interview','received_offer','fol_assign_expiry_date','comments');
            $table_value = array($this->follow_type,$this->interview_time,format_date_database($this->interview_date),$this->fol_department,$this->fol_job_title,$this->fol_payroll_empl,$this->admin_fee,$this->fol_job_type,$this->fol_probation_period,$this->fol_approved,
                                 $this->f_company, $this->interview_by,format_date_database($this->s_date),$this->p_offer,$this->expected_salary,$this->f_salary,$this->applicant_attend, $this->received_offer, format_date_database($this->fol_assign_expiry_date), $this->followup_comments,
                                 $this->follow_notice);  
            }
            else{
                if($_SESSION['empl_group'] == "4"){
                $table_field = array( 'assign_to','follow_type','interview_time','interview_date','fol_not_suitable',
                                     'interview_company','interview_by','fol_available_date','fol_position_offer','fol_expected_salary','fol_offer_salary','attend_interview','received_offer','comments','follow_notice');
                $table_value = array( $this->assign_to,$this->follow_type,$this->interview_time,format_date_database($this->interview_date),$this->not_suitable,
                                     $this->interview_company, $this->interview_by,format_date_database($this->available_date),$this->position_offer,$this->expected_salary,$this->offer_salary,$this->applicant_attend, $this->received_offer, $this->followup_comments,
                                     $this->follow_notice);
                }
                else {
                $table_field = array('follow_type','interview_time','interview_date','fol_not_suitable',
                                     'interview_company','interview_by','fol_available_date','fol_position_offer','fol_expected_salary','fol_offer_salary','attend_interview','received_offer','comments');
                $table_value = array($this->follow_type,$this->interview_time,format_date_database($this->interview_date),$this->not_suitable,
                                     $this->interview_company, $this->interview_by,format_date_database($this->available_date),$this->position_offer,$this->expected_salary,$this->offer_salary,$this->applicant_attend, $this->received_offer, $this->followup_comments,
                                     $this->follow_notice);
                }
            }
        }
        
        $remark = "Update Remarks";
        if(!$this->save->UpdateData($table_field,$table_value,'db_followup','follow_id',$remark,$this->follow_id," AND applfollow_id = '$this->applicant_id'")){
           return false;
        }else{
//           $this->saveNotification();
           return true;
        }
        
    }
    public function deleteFollowUp(){
        $table_field = array('fol_status');
        $table_value = array(1);
        $remark = "Delete Follow Up $this->follow_id";
        if(!$this->save->UpdateData($table_field,$table_value,'db_followup','follow_id',$remark,$this->follow_id," AND applfollow_id = '$this->applicant_id'")){
           return false;
        }else{
           //$this->saveNotification();
           return true;
        }
    }
    public function getRemarks(){
        $sql = "select f.*, f.follow_id, f.assign_to, f.interview_company, f.follow_type, left(f.insertDateTime,10) as date, right(f.insertDateTime, 8) as time, f.interview_company, f.received_offer, f.comments, e.empl_name from db_followup f inner join db_empl e on f.insertBy = e.empl_id where f.applfollow_id = '$this->applicant_id' and f.fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
        $query = mysql_query($sql);
        
        $i = 0;
        while($row = mysql_fetch_array($query)){
            
                $data['applicant_id'][$i] = $this->applicant_id;
                $data['applicant_name'][$i] = $row['applicant_name'];
                $data['follow_id'][$i] = $row['follow_id'];
                $data['edit'][$i] = "1";
                if ($row['follow_type']=="0"){
                $data['follow_type'][$i] = "Assign to Client";  
                }
                if ($row['follow_type']=="1"){
                $data['follow_type'][$i] =  "Candidate Follow Up";
                }
                if ($row['follow_type']=="2"){
                $data['follow_type'][$i] =  "Assign interview";
                }
                if ($row['follow_type']=="3"){
                $data['follow_type'][$i] =  "Assign Candidate to Employer";
                }
                if ($row['follow_type']=="4"){
                $data['follow_type'][$i] =  "Assign New Candidate to Own";
                }
       
                if($row['follow_type'] == "0")
                {
                    if($row['fol_approved'] == "Y"){$data['status'][$i] = "Approved";}
                    else if($row['fol_approved'] == "N"){$data['status'][$i] = "Not Suitable";}
                    //if($row['fol_approved'] == "0"){echo "Pending";}
                    else $data['status'][$i] = "Pending";
                }
                else if($row['follow_type'] == "2")  
                {
                    $data['status'][$i] = "Interview";
                }
                else {$data['status'][$i] = "-";}
                        
                $id = $row['assign_to'];
                $sql2 = "select empl_name as assign_to from db_empl where empl_id = '$id' group by empl_name";
                $query2 = mysql_query($sql2);
                $row2 = mysql_fetch_array($query2);
                if ($row2['assign_to']=="" || $row2['assign_to']==null){
                    $data['assign_to'][$i] = "Own follow up";
                }
                else{
                $data['assign_to'][$i] = $row2['assign_to'];
                }
                $data['empl_name'][$i] = $row['empl_name'];
                $data['date'][$i] = format_date($row['date']);
                $data['time'][$i] = $row['time'];


                $partner_id = $row['interview_company'];
                $sql3 = "select partner_name from db_partner where partner_id = '$partner_id'";
                $query3 = mysql_query($sql3);
                $row3 = mysql_fetch_array($query3);
                if ($row3['partner_name']== "" || $row3['partner_name']== null){
                    $data['interview_company'][$i] = "-";
                }        
                else{
                    $data['interview_company'][$i] = $row3['partner_name'];
                }
                if ($row['received_offer']=="0"){
                $data['received_offer'][$i] = "Pending";        
                }
                else if ($row['received_offer']=="1"){
                $data['received_offer'][$i] =  "Yes";
                }
                else if ($row['received_offer']=="2"){
                $data['received_offer'][$i] =  "No";
                }
                else{
                   if($row['follow_type']=="0")
                       $data['received_offer'][$i] =  "Assigned Job";
                   else{
                       $data['received_offer'][$i] =  "-";
                   }
                }

                $data['comments'][$i] = preg_replace('#<script(.*?)>(.*?)</script>#is', '',htmlspecialchars_decode($row['comments']));
            $i++;
        }
//        $data['data'] = array('remarks_id'=>12,'content')
        return $data;
    }
    public function getApplicantLeaveForm(){
        ?>
        


  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog"  style="width:70%">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: #5cb85c;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Insert Candidate Leave</h4>
        </div>
        <div class="modal-body" style="text-align: center; height:600px; border-style:none; overflow-y: hidden;">
                    <div class="form-group">
                        <label for="applleave_leavetype" class="col-sm-2 control-label"></label>
                        <label for="applleave_leavetype" class="col-sm-2 control-label">Leave Title</label>
                        <label for="applleave_leavetype" class="col-sm-2 control-label" style="text-align: center">Hide</label>
                        <label for="applleave_leavetype" class="col-sm-4 control-label">Balance (<?php echo date("Y");?>)</label>
                        <!--<label for="applleave_leavetype" class="col-sm-3 control-label">Entitled (<?php echo date("Y");?>)</label>-->
                    </div>
                    <?php    
                    $year = date("Y");
                    $sql = "SELECT lt.*,al.applleave_days,al.applleave_id,al.applleave_disabled,al.applleave_entitled
                            FROM db_leavetype lt
                            LEFT JOIN db_applleave al ON al.applleave_leavetype = lt.leavetype_id AND al.applleave_appl = '$this->applicant_id' AND al.applleave_year = '$year'
                            WHERE lt.leavetype_status = 1 
                            GROUP BY lt.leavetype_id 
                            ORDER BY lt.leavetype_seqno,lt.leavetype_code ";

                    $query = mysql_query($sql);
                    while($row = mysql_fetch_array($query)){
                        if($row['applleave_id'] <= 0){
                            $row['applleave_days'] = $row['leavetype_default'];
                        }
                    ?>
                    <div class="form-group">
                        <label class="col-sm-2"></label>
                          <label for="applleave_leavetype" class="col-sm-2 control-label"><?php echo $row['leavetype_code'];?></label>
                          <div class="col-sm-2">
                              <input type="checkbox" id="applleave_disabled" name="applleave_disabled[<?php echo $row['leavetype_id'];?>]" value = "1" <?php if($row['applleave_disabled'] == 1){ echo 'CHECKED';}?>>
                          </div>
                          <div class="col-sm-4">
                            <input type="hidden" class="form-control" id="applleave_leavetype" name="applleave_leavetype[]" value = "<?php echo $row['leavetype_id'];?>">  
                            <input type="hidden" class="form-control" id="applleave_id" name="applleave_id[]" value = "<?php echo $row['applleave_id'];?>">  
                            <input type="text" class="form-control" id="applleave_days" name="applleave_days[]" value = "<?php echo $row['applleave_days'];?>" placeholder="Days">
                          </div>
<!--                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="applleave_entitled" name="applleave_entitled[]" value = "<?php echo $row['applleave_entitled'];?>" placeholder="Days" >
                          </div>-->
                    </div>
                    <?php }?>

              <button type = "button" class="btn btn-info save_applleave" >
                  <?php if($row['applleave_id'] <= 0){?>
                  Update
                  <?php }else{?>
                  Save
                  <?php }?>    
              </button>
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
       
        
        
  <?php
    }
    
    public function getFamilyForm(){
    ?>
        <?php if($this->family_id > 0){
            $this->fetchFamilyDetail(" AND family_id = '$this->family_id'","","",1);
        ?>
        
        <h3>Update Family Background</h3>
        <?php }else{?>
        <h3>Create Family Background</h3> 
        <?php }?>
        <div class="form-group">
              <label for="family_name" class="col-sm-2 control-label">Name </label>
              <div class="col-sm-2">
               <input type="text" class="form-control " id="family_name" name="family_name" value = "<?php echo $this->family_name;?>" placeholder="Name">
              </div>
        </div>
        <div class="form-group">
              <label for="family_relationship" class="col-sm-2 control-label">Relationship</label>
              <div class="col-sm-3">
                  <input type="text" class="form-control" id="family_relationship" name="family_relationship" value = "<?php echo $this->family_relationship?>" placeholder="Relationship">
              </div>
              <label for="family_age" class="col-sm-2 control-label">Age</label>
              <div class="col-sm-3">
               <input type="text" class="form-control" id="family_age" name="family_age" value = "<?php echo $this->family_age;?>" placeholder="Age">
              </div>
        </div>
        <div class="form-group">
              <label for="family_contact_no" class="col-sm-2 control-label">Contact No</label>
              <div class="col-sm-3">
                <input type="text"  class="form-control" id="family_contact_no" name="family_contact_no" value = "<?php echo $this->family_contact_no;?>" placeholder="Contact No">
              </div>
              <label for="family_occupation" class="col-sm-2 control-label">Occupation</label>
              <div class="col-sm-3">
               <input type="text"  class="form-control" id="family_occupation" name="family_occupation" value = "<?php echo $this->family_occupation;?>" placeholder="Occupation">
              </div>
              <input type = 'hidden' value = '<?php echo $this->family_id;?>' name = 'family_id' id = 'family_id'/>
        </div>
          <div class="col-sm-3 ">
              <button type = "button" class="btn btn-info save_appfamily_btn" >
                  <?php if($this->family_id > 0){?>
                  Update
                  <?php }else{?>
                  Save
                  <?php }?>           
              </button>
<!--              <button type="button" class="btn btn-primary btn-info " onclick = "confirmAlertHref('applicant.php?action=saveFamily&applicant_id=<?php echo $this->applicant_id;?>&appfamily_name=<?php echo $this->appfamily_name;?>','Confirm Save?')">Save</button>-->
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
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicant.php?action=edit&current_tab=family&applicant_id=<?php echo $this->applicant_id;?>&family_id=<?php echo $row['family_id'];?>'">Edit</button>
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
    public function addFamily(){
        $table_field = array('family_id','family_name','family_relationship','family_contact_no','family_age',
                             'family_occupation','applicant_family_id');
        $table_value = array('',$this->family_name,$this->family_relationship,$this->family_contact_no,$this->family_age,
                             $this->family_occupation, $this->applicant_id);
        $remark = "Add Family.";
        if(!$this->save->SaveData($table_field,$table_value,'db_family','family_id',$remark)){
            return false;
        }else{
            return true;
        }
    }
    public function updateFamily(){

        $table_field = array('family_name','family_relationship','family_contact_no','family_age',
                             'family_occupation');
        $table_value = array($this->family_name,$this->family_relationship,$this->family_contact_no,$this->family_age,
                             $this->family_occupation);
        $remark = "Update Family";
        if(!$this->save->UpdateData($table_field,$table_value,'db_family','family_id',$remark,$this->family_id," AND applicant_family_id = '$this->applicant_id'")){
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
    
    public function getWorkExperienceForm(){
        if($this->exp_id > 0){
           $this->fetchWorkExperience(" AND exp_id = '$this->exp_id'","","",1);
        }else{
//           $this->applicantsalary_overtime = "0.00";
//           $this->applicantsalary_hourly = "0.00";
//           $this->applicantsalary_workday = 20;
//           $this->applicantsalary_amount = 0;
        }
    ?>
        <?php if($this->exp_id > 0){?>
        <h3>Update Experience  </h3>
        <?php }else{?>
        <h3>Create Experience</h3> 
        <?php }?>
        <div class="form-group ">

            <label for="previous_company" class="col-sm-2 control-label">Company</label>
            <div class="col-sm-3">
                 <input type="text" class="form-control " id="previous_company" name="previous_company" value = "<?php echo $this->previous_company;?>" placeholder="Company">
            </div>
            <label for="previous_position" class="col-sm-2 control-label">Position</label>
            <div class="col-sm-3">
                 <input type="text" class="form-control " id="previous_position" name="previous_position" value = "<?php echo $this->previous_position;?>" placeholder="Position">
            </div>
        </div>
        <div class="form-group ">

            <label for="previous_salary" class="col-sm-2 control-label">Salary</label>
            <div class="col-sm-3">
                 <input type="text" class="form-control " id="previous_salary" name="previous_salary" value = "<?php echo $this->previous_salary;?>" placeholder="Salary">
            </div>
            <label for="duration_conform" class="col-sm-2 control-label">Duration of Conformation</label>
            <div class="col-sm-3">
                 <input type="text" class="form-control " id="duration_conform" name="duration_conform" value = "<?php echo $this->duration_conform;?>" placeholder="Duration in Month">
            </div>
        </div>
        <div class="form-group">
            <label for="previous_start_date" class="col-sm-2 control-label">Start Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="previous_start_date" name="previous_start_date" value = "<?php echo format_date($this->previous_start_date);?>" placeholder="Start Date">
            </div>
            <label for="previous_end_date" class="col-sm-2 control-label">End Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="previous_end_date" name="previous_end_date" value = "<?php echo format_date($this->previous_end_date);?>" placeholder="End Date">
            </div>
        </div>
        <div class="form-group">
            <label for="responsibilities" class="col-sm-2 control-label">Responsibilities</label>
            <div class="col-sm-3">
                <textarea class="form-control" rows="5" id="responsibilities" name="responsibilities" placeholder="Responsibilities"><?php echo $this->responsibilities;?></textarea>
            </div>
            <label for="reason_leave" class="col-sm-2 control-label">Reason of leave</label>
            <div class="col-sm-3">
                <textarea class="form-control" rows="5" id="reason_leave" name="reason_leave" placeholder="Reason"><?php echo $this->reason_leave;?></textarea>
            </div>
        </div>
          <div class="col-sm-3 ">
              <button type = "button" class="btn btn-info save_experience_btn" >
                  <?php if($this->exp_id > 0){?>
                  Update
                  <?php }else{?>
                  Save
                  <?php }?>    
              </button>
              <input type = 'hidden' value = '<?php echo $this->exp_id;?>' name = 'experience_id' id = 'experience_id'/>
          </div><br><br><br>
               
          
          
        <table id="workexp_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Company</th>
                        <th style = 'width:10%'>Position</th>
                        <th style = 'width:7%'>Salary</th>
                        <th style = 'width:10%'>Reason of Leave</th>
                        <th style = 'width:20%'>Responsibilities</th>
                        <th style = 'width:7%'>Date Start</th>
                        <th style = 'width:7%'>Date End</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "select * from db_experience where exp_appl_id = '$this->applicant_id'";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['exp_company'];?></td>
                            <td><?php echo $row['exp_position'];?></td> 
                            <td><?php echo $row['exp_salary'];?></td>
                            <td><?php echo $row['exp_reason_leave'];?></td>
                            <td><?php echo nl2br($row['exp_responsibilities']);?></td>
                            <td><?php echo format_date($row['exp_start_date']);?></td>
                            <td><?php echo format_date($row['exp_end_date']);?></td>
                            <td class = "text-align-right">

                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicant.php?action=edit&current_tab=WorkingExperience&applicant_id=<?php echo $this->applicant_id;?>&exp_id=<?php echo $row['exp_id'];?>'">Edit</button>
                                <input type = 'hidden' value = '<?php echo $row['exp_id'];?>' name = "exp" id = "exp"/>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('applicant.php?action=deleteWorkExperience&current_tab=WorkingExperience&applicant_id=<?php echo $this->applicant_id;?>&exp_id=<?php echo $row['exp_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:10%'>Company</th>
                        <th style = 'width:10%'>Position</th>
                        <th style = 'width:7%'>Salary</th>
                        <th style = 'width:10%'>Reason of Leave</th>
                        <th style = 'width:20%'>Responsibilities</th>
                        <th style = 'width:7%'>Date Start</th>
                        <th style = 'width:7%'>Date End</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </tfoot>
                  </table>
             
                <div class="form-group">

        </div>

    <?php
    }
    public function createWorkExperience(){
        $table_field = array('exp_id','exp_company','exp_position','exp_salary','exp_reason_leave','exp_duration_conformation','exp_start_date',
                             'exp_end_date','exp_responsibilities','exp_appl_id');
        $table_value = array('', $this->previous_company,$this->previous_position,$this->previous_salary,$this->reason_leave,$this->duration_conform,format_date_database($this->previous_start_date),
                             format_date_database($this->previous_end_date),$this->responsibilities,$this->applicant_id);
        $remark = "Create Experience.";
        if(!$this->save->SaveData($table_field,$table_value,'db_experience','experience_id',$remark)){
            return false;
        }
        else{
            return true;
        }
    }
    public function updateWorkExperience(){
        
        $table_field = array('exp_company','exp_position','exp_salary','exp_reason_leave','exp_duration_conformation','exp_start_date',
                             'exp_end_date','exp_responsibilities');
        $table_value = array($this->previous_company,$this->previous_position,$this->previous_salary,$this->reason_leave,$this->duration_conform,format_date_database($this->previous_start_date),
                             format_date_database($this->previous_end_date),$this->responsibilities);
        
        $remark = "Update Experience";
        if(!$this->save->UpdateData($table_field,$table_value,'db_experience','exp_id',$remark,$this->exp_id," AND exp_appl_id = '$this->applicant_id'")){
           return false;
        }
        else{
           return true;
        }
    }
    public function deleteWorkExperience(){
        $sql = "DELETE FROM db_experience WHERE exp_id = $this->exp_id";
        mysql_query($sql);
        return true;
    }
    
    public function getResumeForm(){
        ?>
        <div class="box-body table-responsive">     
         
                <div class="form-group">
                    <label for="upload_resume" class="col-sm-2 control-label">Upload Resume<?php echo $mandatory;?></label>
                    <input style="margin-left:19%;"  data-toggle="tooltip" title="Please upload pdf, docx or txt file only" type="file" name="files[]" id="resume_file" type="file" onchange="makeFileList();" multiple/><br>


                    <ul id="fileList" class="list_file" style="list-style-type:none; font-size: 15px; list-style-position:inside;">
                    </ul>    
                </div>

                <div class="col-sm-4">
                  <button type = "button" class="btn btn-info upload_resume_btn" onclick = "'applicant.php?action=edit&current_tab=ResumeFile&applicant_id=<?php echo $this->applicant_id;?>'">Save</button>
                </div>
    
            <script type="text/javascript">   
                        
		function makeFileList() {
			var input = document.getElementById("resume_file");
			var ul = document.getElementById("fileList");
			while (ul.hasChildNodes()) {
				ul.removeChild(ul.firstChild);
			}
			for (var i = 0; i < input.files.length; i++) {
				var li = document.createElement("li");
				li.innerHTML = i+1 + ". "  + input.files[i].name;
				ul.appendChild(li);
			}
			if(!ul.hasChildNodes()) {
				var li = document.createElement("li");
				li.innerHTML = 'No Files Selected';
				ul.appendChild(li);
			}
                    }
            </script>

        </div>
        
                <table id="resume_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:20%'>Document Title</th>
                        <th style = 'width:10%'>Document Type</th>
                        <th style = 'width:15%'>File Name</th>
                        <th style = 'width:10%'>Create Time</th>
                        <th style = 'width:10%'>Create Date</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT *, left(insertDateTime,10) as date, right(insertDateTime, 8) as time FROM `db_resume` WHERE resume_appl_id='$this->applicant_id' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo ucfirst(pathinfo($row['resume_name'],PATHINFO_FILENAME));?></td>
                            <td><?php echo $row['resume_type'];?></td>
                            <td><?php echo $row['resume_name'];?></td>
                            <td><?php echo $row['time'];?></td>
                            <td><?php echo nl2br($row['date']);?></td>
                            <td class = "text-align-right">
                                <?php if( $row['resume_type']=="pdf" ||  $row['resume_type']=="txt")
                                {?>
                                <a href="<?php echo $row['resume_url'];?>"target="_blank"><button type="button" class="btn btn-primary btn-warning" >View</button></a>
                                <?php }?>
                                <a href="<?php echo $row['resume_url'];?>" download><button type="button" class="btn btn-primary btn-info">Download</button></a>
                                <input type = 'hidden' value = '<?php echo $row['resume_id'];?>' name = "resume" id = "resume"/>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('applicant.php?action=deleteResume&current_tab=ResumeFile&applicant_id=<?php echo $this->applicant_id;?>&resume_id=<?php echo $row['resume_id'];?>&file_name=<?php echo $row['resume_name'];?>','Confirm Delete?') ">Delete</button>
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
                        <th style = 'width:20%'>Document Title</th>
                        <th style = 'width:10%'>Document Type</th>
                        <th style = 'width:15%'>File Name</th>
                        <th style = 'width:10%'>Create Time</th>
                        <th style = 'width:10%'>Create Date</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </tfoot>
                  </table>
    <?php    
    }
    public function saveResume(){
            if(isset($_FILES['files'])){
                $table_field = array('resume_name','resume_appl_id','resume_type','resume_url');
                $errors= array();
                    foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
                            $file_name = $_FILES['files']['name'][$key];
                            $file_size =$_FILES['files']['size'][$key];
                            $file_tmp =$_FILES['files']['tmp_name'][$key];
                            $file_type=$_FILES['files']['type'][$key];	
                                    
                            $fileFormat = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
                            $isfile = false;
                            if($fileFormat != "pdf" && $fileFormat != "txt" && $fileFormat != "docx") {
                                rediectUrl("applicant.php",getSystemMsg(0,'Please upload pdf, txt, doc or docx file'));
                            }
                            else{    
                                if(file_exists("dist/file/$this->applicant_id/$file_name")){
                                    $dop = strpos($file_name, ".");
                                    $duplicater_name = substr($file_name,0,$dop).'-'.date('y-m-d').'.'.$fileFormat;
                                    $table_value = array($duplicater_name,$this->applicant_id,$fileFormat,"dist/file/$this->applicant_id/$duplicater_name");
                                    $remark = "Upload candidate resume";
                                    $this->save->SaveData($table_field,$table_value,'db_resume','resume_id',$remark);
                                    $this->resume_id = $this->save->lastInsert_id;
                                    mkdir("dist/file/$this->applicant_id", 0755, true);
                                    move_uploaded_file($file_tmp ,"dist/file/$this->applicant_id/$duplicater_name");
                                }
                                else{
                                    $table_value = array($file_name,$this->applicant_id,$fileFormat,"dist/file/$this->applicant_id/$file_name");
                                    $remark = "Upload candidate resume";
                                    $this->save->SaveData($table_field,$table_value,'db_resume','resume_id',$remark);
                                    $this->resume_id = $this->save->lastInsert_id;
                                    mkdir("dist/file/$this->applicant_id", 0755, true);
                                    move_uploaded_file($file_tmp ,"dist/file/$this->applicant_id/$file_name");
                                }

                                header('Content-Type: text/plain');
                                $sql = "Select * from db_resume where resume_id = '$this->resume_id'";
                                $query =  mysql_query($sql);
                                $row = mysql_fetch_array($query);
                                
                                $filerword = "";
                                if($row['resume_type'] == "doc" || $row['resume_type'] == "docx"){
                                    $filename = $row['resume_name'];

                                    $content = '';
                                    $detail = '';

                                    $zip = zip_open('dist/file/'.$this->applicant_id.'/'.$filename);

                                    if (!$zip || is_numeric($zip)) return false;
                                    while ($zip_entry = zip_read($zip)) {
                                        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
                                        if (zip_entry_name($zip_entry) != "word/document.xml") continue;
                                        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                                        zip_entry_close($zip_entry);
                                    }
                                    zip_close($zip);

                                    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
                                    $content = str_replace('</w:r></w:p>', "\r\n", $content);
                                    $filterWords = strip_tags($content);
                                    $filterWords = str_replace(" : ", " ", $filterWords);
                                }
                                else if($row['resume_type'] == "txt"){
                                    $file = "dist/file/".$this->applicant_id.'/'.$row['resume_name'];
                                    $document = file_get_contents($file);
                                    $fileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));
                                    $filterWords = str_replace(" : ", " ", $document);
                                }
                                else if($row['resume_type'] == "pdf"){
                                   $a = new PDF2Text();
                                   $a->setFilename('dist/file/'.$this->applicant_id.'/'.$row['resume_name']);    
                                   $a->decodePDF();
                                   $filterWords = $a->output();
                                   $filterWords = str_replace(" : ", " ", $filterWords);
                                }

                                $filterWords = str_replace(": ", " ", $filterWords);
                                $filterWords = str_replace(" :", " ", $filterWords);
                                $filterWords = str_replace("  ", " ", $filterWords);
                                $filterWords = str_replace("   ", " ", $filterWords);


                                $words = preg_split("/[\s,:]/", $filterWords);
                                $arrlength = count($words);

                                $c = 0;
                                if($row['resume_type'] == "pdf"){                
                                    for($i = 0; $i < $arrlength; $i++) {
                                        if($words[$i] == ""){ }
                                        else
                                        {
                                            $convert[$c] = ucfirst(strtolower($words[$i]));
                                            $c++;
                                        }
                                    }
                                }
                                else 
                                {
                                    for($i = 0; $i < $arrlength; $i++) {
                                            $convert[$i] = ucfirst(strtolower($words[$i]));
                                    }
                                }
                                $convertlength = count($convert); 

                                for($x = 0; $x < $convertlength; $x++) {
                                     $detail = $detail . " " . $convert[$x];
                                }
                                $update = "UPDATE db_resume SET resume_detail = '$detail' where resume_id = '$this->resume_id'";
                                mysql_query($update);
                            }
                        }
                    }
            return true;      
}
    public function deleteResume(){
        $sql = "DELETE FROM db_resume WHERE resume_id = $this->resume_id";
        mysql_query($sql);
        unlink("dist/file/$this->applicant_id/$this->file_name");
        return true;
    }
    
    public function getUploadFileForm(){
        ?>
        <div class="box-body table-responsive">     
         
                <div class="form-group">
                    <label for="upload_file" class="col-sm-2 control-label">Upload File<?php echo $mandatory;?></label>
                    <input style="margin-left:19%;"  type="file" name="files[]" id="upload_file" type="file"/><br>
                </div>

                <div class="form-group">
                    <label for="file_type" class="col-sm-2 control-label">File Type</label>
                    <div class="radio col-sm-3">
                     <select class="form-control select2" id="file_type" name="file_type" style = 'width:100%'>
                        <option value="Resume">Resume</option>
                        <option value="NRIC">NRIC</option>
                        <option value="Passport">Passport</option>
                        <option value="Certificate">Education Cert</option>
                     </select>
                     </div>
                </div>
                
                <div class="col-sm-4">
                  <button type = "button" class="btn btn-info upload_file_btn">Save</button>
                </div>

        </div>
        
                <table id="uploadfile_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:20%'>Document Title</th>
                        <th style = 'width:10%'>Document Type</th>
                        <th style = 'width:15%'>File Name</th>
                        <th style = 'width:10%'>Create Time</th>
                        <th style = 'width:10%'>Create Date</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT *, left(insertDateTime,10) as date, right(insertDateTime, 8) as time FROM `db_file` WHERE file_appl_id='$this->applicant_id' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo ucfirst(pathinfo($row['file_name'],PATHINFO_FILENAME));?></td>
                            <td><?php echo $row['file_type'];?></td>
                            <td><?php echo $row['file_name'];?></td>
                            <td><?php echo $row['time'];?></td>
                            <td><?php echo nl2br($row['date']);?></td>
                            <td class = "text-align-right">
                                <a href="<?php echo $row['file_url'];?>"target="_blank"><button type="button" class="btn btn-primary btn-warning" >View</button></a>
                                <a href="<?php echo $row['file_url'];?>" download><button type="button" class="btn btn-primary btn-info">Download</button></a>
                                <input type = 'hidden' value = '<?php echo $row['file_id'];?>' name = "file" id = "file"/>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('applicant.php?action=deleteFile&current_tab=UploadFile&applicant_id=<?php echo $this->applicant_id;?>&file_id=<?php echo $row['file_id'];?>&file_name=<?php echo $row['file_name'];?>','Confirm Delete?') ">Delete</button>
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
                        <th style = 'width:20%'>Document Title</th>
                        <th style = 'width:10%'>Document Type</th>
                        <th style = 'width:15%'>File Name</th>
                        <th style = 'width:10%'>Create Time</th>
                        <th style = 'width:10%'>Create Date</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </tfoot>
                  </table>
    <?php    
    }
    public function saveFile(){
            if(isset($_FILES['files'])){
                $table_field = array('file_name','file_appl_id','file_type','file_url');
                $errors= array();
                    foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
                            $file_name = $_FILES['files']['name'][$key];
                            $file_size =$_FILES['files']['size'][$key];
                            $file_tmp =$_FILES['files']['tmp_name'][$key];
                            $file_type=$_FILES['files']['type'][$key];	
                                    
                    }
                            $isfile = false;
                            
                            $fileFormat = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
                            
                            if($fileFormat == "") {
                                echo "Please choose your file";
                            }
                            else{    
                                if(file_exists("dist/document/$this->applicant_id/$file_name")){
                                    $dop = strpos($file_name, ".");
                                    $duplicater_name = substr($file_name,0,$dop).'-'.date('y-m-d').'.'.$fileFormat;
                                    $table_value = array($duplicater_name,$this->applicant_id,$this->file_type,"dist/document/$this->applicant_id/$duplicater_name");
                                    $remark = "Upload candidate document";
                                    $this->save->SaveData($table_field,$table_value,'db_file','file_id',$remark);
                                    $this->file_id = $this->save->lastInsert_id;
                                    mkdir("dist/document/$this->applicant_id", 0755, true);
                                    move_uploaded_file($file_tmp ,"dist/document/$this->applicant_id/$duplicater_name");
                                }
                                else{
                                    $table_value = array($file_name,$this->applicant_id,$this->file_type,"dist/document/$this->applicant_id/$file_name");
                                    $remark = "Upload candidate document";
                                    $this->save->SaveData($table_field,$table_value,'db_file','file_id',$remark);
                                    $this->file_id = $this->save->lastInsert_id;
                                    mkdir("dist/document/$this->applicant_id", 0755, true);
                                    move_uploaded_file($file_tmp ,"dist/document/$this->applicant_id/$file_name");
                                }
                            }
                    }
            return true;      
}
    public function deleteFile(){
        $sql = "DELETE FROM db_file WHERE file_id = $this->file_id";
        mysql_query($sql);
        unlink("dist/document/$this->applicant_id/$this->file_name");
        return true;
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
    public function resumeManagement(){

    $target_dir = "uploads/";

    $target_file = $target_dir . basename($_FILES["file_input"]["name"]);
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $this->applicant_resume_url = $fileType;
    $sql = "UPDATE db_applicant set applicant_resume_url = '$this->applicant_resume_url' where applicant_id = '$this->applicant_id'";
    mysql_query($sql);
    if(!file_exists("dist/file")){
           mkdir('dist/file/');
        }
        $isfile = false;
        if($fileType != "pdf" && $fileType != "txt" && $fileType != "docx" && $fileType != "doc") {
//             echo "Sorry, only PDF, TXT & DOCX files are allowed.";
            $isfile = false;
        }
        else
            $isfile = true;

        if($this->file_input['size'] > 0 && $isfile == true){
            if($this->action == 'update'){
                unlink("dist/file/{$this->applicant_id}.$fileType");
            }
                move_uploaded_file($this->file_input['tmp_name'],"dist/file/{$this->applicant_id}.$fileType");
        }   
    }
    
    public function fetchApplicantDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT *, LPAD(applicant_id,7,'0') as appl_code FROM db_applicant WHERE applicant_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->appl_code = $row['appl_code'];
            $this->applicant_id = $row['applicant_id'];
            $this->applicant_code = $row['applicant_code'];
            $this->applicant_name = $row['applicant_name'];
            $this->applicant_nric = $row['applicant_nric'];
            $this->applicant_tel = $row['applicant_tel'];
            $this->applicant_mobile = $row['applicant_mobile'];
            $this->applicant_email = $row['applicant_email'];
            $this->applicant_postal_code = $row['applicant_postal_code'];
            $this->applicant_unit_no = $row['applicant_unit_number'];
            $this->applicant_address = $row['applicant_street'];
            $this->applicant_postal_code2 = $row['applicant_postal_code2'];
            $this->applicant_unit_no2 = $row['applicant_unit_number2'];
            $this->applicant_address2 = $row['applicant_street2'];
            $this->applicant_black_list = $row['applicant_black_list'];
            $this->applicant_resume_url = $row['applicant_resume_url'];
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
            $this->applicant_height = $row['applicant_height'];
            $this->applicant_weight = $row['applicant_weight'];
            $this->applicant_applicantpass = $row['applicant_applpass'];
            
            $this->applicant_marital_status = $row['applicant_marital_status'];
            $this->applicant_religion = $row['applicant_religion'];
            $this->applicant_sex = $row['applicant_sex'];
            $this->applicant_race = $row['applicant_race'];
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
            $this->applicant_work_time_end = $row['applicant_work_time_end'];
            $this->applicant_position = $row['applicant_position'];
            
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
            
            $this->db_specify = $row['appl_declaration_b_specify'];
            $this->dp_specify = $row['appl_declaration_p_specify'];
            $this->dltm_specify = $row['appl_declaration_ltm_specify'];
            $this->dl_specify = $row['appl_declaration_l_specify'];
            $this->dw_specify = $row['appl_declaration_w_specify'];
            $this->da_specify = $row['appl_declaration_a_specify'];
            
            $this->appl_o_level = $row['appl_o_level'];
            $this->appl_n_level = $row['appl_n_level'];
            $this->appl_a_level = $row['appl_a_level'];
            $this->appl_degree = $row['appl_degree'];
            $this->appl_diploma = $row['appl_diploma'];
            $this->appl_other_qualification = $row['appl_other_qualification'];
            $this->appl_written = $row['appl_written'];
            $this->appl_spoken = $row['appl_spoken'];
            
            $this->overall_impression = $row['overall_impression'];
            $this->communication_skills = $row['communication_skill'];
            $this->other_comments = $row['other_comments'];
            $this->official_consultant = $row['official_consultant'];
            $this->official_date = $row['official_date'];
            $this->official_time = $row['official_time'];  
            
            
            
            
            $this->tc_date = $row['tc_date'];
            
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }
       
    }
    public function fetchSalaryDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_applicantsalary WHERE applicantsalary_id > 0 AND applicantsalary_status = '1' $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->applicant_id = $row['applicantsalary_applicant_id'];
            $this->applicantsalary_date = $row['applicantsalary_date'];
            $this->applicantsalary_amount = $row['applicantsalary_amount'];
            $this->applicantsalary_overtime = $row['applicantsalary_overtime'];
            $this->applicantsalary_hourly = $row['applicantsalary_hourly'];
            $this->applicantsalary_workday = $row['applicantsalary_workday'];
            $this->applicantsalary_remark = $row['applicantsalary_remark'];
            $this->applicantsalary_applicant_id = $row['applicantsalary_applicant_id'];
            $this->updateBy = $row['updateBy'];
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }
       
    }
    public function fetchFollowDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_followup WHERE follow_id > 0 $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->follow_id = $row['follow_id'];
            $this->assign_id = $row['assign_to'];
            $id = $row['assign_to'];
            
            $sql2 = "select empl_name as assign_to from db_empl where empl_id = '$id' group by empl_name";
            $query2 = mysql_query($sql2);
            $row2 = mysql_fetch_array($query2);
            
            //var_dump($row);
            
            
            $this->assign_to = $row2['assign_to'];
            $this->assign_manager = $row['fol_assign_manager'];
            $this->follow_type = $row['follow_type'];
            $this->fol_assign_expiry_date = $row['fol_assign_expiry_date'];
            $this->interview_time = $row['interview_time'];
            $this->interview_date = $row['interview_date'];
            $this->interview_company = $row['interview_company'];
            $this->interview_by = $row['interview_by'];
            $this->fol_job_title = $row['fol_job_assign'];
            $this->fol_department = $row['fol_department'];
            $this->fol_payroll_empl = $row['fol_payroll_empl'];            
            $this->available_date = $row['fol_available_date'];
            $this->not_suitable = $row['fol_not_suitable'];
            $this->position_offer = $row['fol_position_offer'];
            $this->expected_salary = $row['fol_expected_salary'];
            $this->fol_job_type = $row['fol_job_type'];
            $this->fol_approved = $row['fol_approved'];
            $this->fol_probation_period = $row['fol_probation_period'];
            $this->offer_salary = $row['fol_offer_salary'];
            $this->admin_fee = $row['fol_admin_fee'];
            $this->applicant_attend = $row['attend_interview'];
            $this->follow_notice = $row['follow_notice'];
            $this->received_offer = $row['received_offer'];
            $this->followup_comments = $row['comments'];
            $this->applfollow_id = $row['applfollow_id'];
            $this->updateBy = $row['updateBy'];
            return true;
        }
        else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }
       
    }
    public function fetchFamilyDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_family WHERE family_id > 0 $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->family_id = $row['family_id'];
            $this->family_name = $row['family_name'];
            $this->family_relationship = $row['family_relationship'];
            $this->family_contact_no = $row['family_contact_no'];
            $this->family_age = $row['family_age'];
            $this->family_occupation = $row['family_occupation'];
            $this->applicant_family_id = $row['applicant_family_id'];
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }
       
    }
    public function fetchWorkExperience($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_experience WHERE exp_id > 0 $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->exp_id = $row['exp_id'];
            $this->previous_company = $row['exp_company'];
            $this->previous_position = $row['exp_position'];
            $this->previous_salary = $row['exp_salary'];
            $this->reason_leave = $row['exp_reason_leave'];
            $this->duration_conform = $row['exp_duration_conformation'];
            $this->previous_start_date = $row['exp_start_date'];
            $this->previous_end_date = $row['exp_end_date'];
            $this->responsibilities = $row['exp_responsibilities'];
            $this->previous_appl_id = $row['exp_appl_id'];
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

    public function getInputForms($action){
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
        $this->religionCrtl = $this->select->getReligionSelectCtrl($this->applicant_religion);
        $this->raceCrtl = $this->select->getRaceSelectCtrl($this->applicant_race);
        $this->designationCrtl = $this->select->getDesignationSelectCtrl($this->applicant_designation);
        $this->applicantpassCrtl = $this->select->getEmplPassSelectCtrl($this->applicant_applicantpass);

        
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Candidate Management</title>
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
            <h1>Candidate Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->applicant_id > 0){ echo "Update Candidate";}
                else{ echo "Create New Candidate";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = '' onclick = "window.location.href='applicant.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'margin-right:10px;' onclick = "window.location.href='applicant.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'applicant_form' class="form-horizontal" action = 'applicant.php?action=create' method = "POST" enctype="multipart/form-data">
                    <input type ='hidden' name = 'current_tab' id = 'current_tab' value = "<?php echo $this->current_tab?>"/>
                  <div class="box-body">
                      
                      <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li tab = "General Info" class="tab_header <?php if(($this->current_tab == "") || ($this->current_tab == "General Info")){ echo 'active';}?>"><a href="#general" data-toggle="tab">General Info</a></li>
                          <li tab = "Contact Info" class="tab_header <?php if($this->current_tab == "Contact Info"){ echo 'active';}?>" ><a href="#contact" data-toggle="tab">Contact Info</a></li>
                          <li tab = "Bank Info" class="tab_header <?php if($this->current_tab == "Bank Info"){ echo 'active';}?>" ><a href="#bank" data-toggle="tab">Bank Info</a></li>
                          <li tab = "Referee Info" class="tab_header <?php if($this->current_tab == "Referee Info"){ echo 'active';}?>"><a href="#referee_info" data-toggle="tab">Character Referee's</a></li>
                         
                          <li tab = "Declaration Info" class="tab_header <?php if($this->current_tab == "Declaration Info"){ echo 'active';}?>"><a href="#declaration_info" data-toggle="tab">Declaration</a></li>
                          <li tab = "Qualification" class="tab_header <?php if($this->current_tab == "Qualification"){ echo 'active';}?>"><a href="#qualification" data-toggle="tab">Qualification</a></li>
                          <li tab = "TermCondition Info" class="tab_header <?php if($this->current_tab == "TermCondition Info"){ echo 'active';}?>"><a href="#termcondition_info" data-toggle="tab">Terms & Conditions</a></li>
                          
                          <?php if($action == "update"){?>
                          <li tab = "family" class="tab_header <?php if($this->current_tab == "family"){ echo 'active';}?>"><a href="#family" data-toggle="tab">Family</a></li>
                          <li tab = "WorkingExperience" class="tab_header <?php if($this->current_tab == "WorkingExperience"){ echo 'active';}?>"><a href="#workingexperience" data-toggle="tab">Work Experience</a></li>
                          <li tab = "ResumeFile" class="tab_header <?php if($this->current_tab == "ResumeFile"){ echo 'active';}?>"><a href="#resumefile" data-toggle="tab">Upload Resume</a></li>
                          <li tab = "UploadFile" class="tab_header <?php if($this->current_tab == "UploadFile"){ echo 'active';}?>"><a href="#uploadfile" data-toggle="tab">Upload File</a></li>
                          <li tab = "followup" class="tab_header <?php if($this->current_tab == "followup"){ echo 'active';}?>"><a href="#followup" data-toggle="tab">Remarks</a></li>
                          <!--<li tab = "Official Use" class="tab_header <?php if($this->current_tab == "Official Use"){ echo 'active';}?>"><a href="#official_use" data-toggle="tab">Official Use</a></li>-->
                          <?php }?>
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
                                     <input data-toggle="tooltip" title="Please upload image in 128 x 128 pixels " type = "file" name = 'image_input' /><br>
                                     
                                    
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
                          <div class=" tab-pane <?php if($this->current_tab == "ResumeFile"){ echo 'active';}?>" id="resumefile">
                              <?php echo $this->getResumeForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "Official Use"){ echo 'active';}?>" id="official_use">
                              <?php echo $this->getOfficialUse();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "WorkingExperience"){ echo 'active';}?>" id="workingexperience">
                              <?php echo $this->getWorkExperienceForm();?>
                          </div>
                          <?php // if($this->applicant_id > 0){?>
                          <div class=" tab-pane <?php if($this->current_tab == "Qualification"){ echo 'active';}?>" id="qualification">
                              <?php echo $this->getQualificationForm();?>
                          </div>
                          <div class=" tab-pane <?php if($this->current_tab == "UploadFile"){ echo 'active';}?>" id="uploadfile">
                              <?php echo $this->getUploadFileForm();?>
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
                    <button type="button" class="btn btn-primary btn-warning " target="_blank" style="margin-left:10px" onclick = "window.open('applicant.php?action=printPDF&applicant_id=<?php echo $this->applicant_id;?>')">Print</button>
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
      $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('editor1');
        //bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();
      });
    </script>
    
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
                                  url: "applicant.php?action=validate_email",
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
                    url: 'applicant.php',
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
                       }
                       else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            $('.save_appfamily_btn').click(function(){
                var data = "action=saveFamily&applicant_id=<?php echo $this->applicant_id;?>&family_id="+$('#family_id').val()+"&family_name="+$('#family_name').val()+"&family_relationship="+$('#family_relationship').val()+"&family_age="+$('#family_age').val();
                    data = data + "&family_occupation="+$('#family_occupation').val()+"&family_contact_no="+$('#family_contact_no').val();
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
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>&current_tab=family';
                           window.location.href = url + "&current_tab=" + $('#current_tab').val();
                       }
                       else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            
            $('.save_followup_btn').click(function(){
            var command = CKEDITOR.instances['editor1'].getData();
            
//                console.log($('#follow_type').val());
                
                if ($('#follow_type').val()=='0'){
                var data = "action=saveFollowup&applicant_id=<?php echo $this->applicant_id;?>&assign_to="+$('#assign_to').val()+"&follow_type="+$('#follow_type').val();
                    data = data + "&company="+$('#f_company').val()+"&s_date="+$('#s_date').val()+"&p_offer="+$('#p_offer').val()+"&salary="+$('#f_salary').val()+"&fol_assign_expiry_date="+$('#fol_assign_expiry_date').val(); 
                    data = data + "&fol_job_title="+$('#fol_job_title').val()+"&fol_department="+$('#fol_department').val()+"&fol_payroll_empl="+$('#fol_payroll_empl').val(); 
                    data = data + "&followup_comments="+command+"&follow_notice="+$('#follow_notice').val()+"&follow_id="+$('#follow_id').val()+"&admin_fee="+$('#admin_fee').val();
                    data = data + "&probation_period="+$('#probation_period').val()+"&job_type="+$('#job_type').val()+"&fol_approved="+$('#fol_approved').val();
                }
                else if ($('#follow_type').val()=='2'){
                var data = "action=saveFollowup&applicant_id=<?php echo $this->applicant_id;?>&assign_to="+$('#assign_to').val()+"&follow_type="+$('#follow_type').val()+"&interview_time="+$('#interview_time').val();
                    data = data + "&interview_date="+$('#interview_date').val()+"&interview_company="+$('#interview_company').val()+"&interview_by="+$('#interview_by').val()+"&available_date="+$('#available_date').val()+"&position_offer="+$('#position_offer').val()+"&expected_salary="+$('#expected_salary').val()+"&offer_salary="+$('#offer_salary').val(); 
                    data = data + "&applicant_attend="+$('#applicant_attend').val()+"&received_offer="+$('#received_offer').val()+"&followup_comments="+command+"&follow_notice="+$('#follow_notice').val()+"&follow_id="+$('#follow_id').val()+"&not_suitable="+$('#not_suitable').val();
                }
                else if ($('#follow_type').val()=='4'){
                var data = "action=saveFollowup&applicant_id=<?php echo $this->applicant_id;?>&assign_to="+$('#assign_to2').val()+"&follow_type="+$('#follow_type').val()+"&interview_time=";
                    data = data + "&interview_date="+"&interview_company="+"&interview_by="+"&available_date="+"&position_offer="+"&expected_salary="+"&offer_salary="; 
                    data = data + "&applicant_attend="+"&received_offer="+"&followup_comments="+command+"&follow_notice="+$('#follow_notice').val()+"&follow_id="+$('#follow_id').val()+"&assign_manager="+$('#assign_manager').val();
                }
                else{
                var data = "action=saveFollowup&applicant_id=<?php echo $this->applicant_id;?>&assign_to="+$('#assign_to').val()+"&follow_type="+$('#follow_type').val()+"&interview_time=";
                    data = data + "&interview_date="+"&interview_company="+"&interview_by="+"&available_date="+"&position_offer="+"&expected_salary="+"&offer_salary="; 
                    data = data + "&applicant_attend="+"&received_offer="+"&followup_comments="+command+"&follow_notice="+$('#follow_notice').val()+"&follow_id="+$('#follow_id').val()+"&assign_manager="+$('#assign_manager').val();
                }
                
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
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>&current_tab=followup';
                           window.location.href = url + "&current_tab=" + $('#current_tab').val();
                       }else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            $('.save_experience_btn').click(function(){
                var data = "action=saveWorkExperience&applicant_id=<?php echo $this->applicant_id;?>&previous_company="+$('#previous_company').val()+"&previous_position="+$('#previous_position').val();
                    data = data + "&previous_salary="+$('#previous_salary').val()+"&reason_leave="+$('#reason_leave').val()+"&exp_id="+$('#experience_id').val()+"&duration_conform="+$('#duration_conform').val();
                    data = data + "&previous_start_date="+$('#previous_start_date').val()+"&previous_end_date="+$('#previous_end_date').val()+"&responsibilities="+$('#responsibilities').val(); 
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
            
            $('#follow_type').change(function(){
                var data = $(this);
                if(data.val() == "0"){
                    $('#assign_to').attr("disabled","disabled");
                    $('.assigntoclient').show();
                    $('.assigninterview').hide();
                    $('.assignCandidateToEmpl').hide();
                    $('.assign').hide();
                    $('.default').show();
                    CKEDITOR.instances['editor1'].setData("Assign candidate to client");
                }
                else if(data.val() == "2"){
                    $('#assign_to').attr("disabled","disabled"); 
                    $('.assigninterview').show();
                    $('.assigntoclient').hide();
                    $('.assignCandidateToEmpl').hide();
                    $('.assign').hide();
                    $('.default').show();

                    CKEDITOR.instances['editor1'].setData("Assign candidate to interview");
                }
                else if(data.val() == "3" || data.val() == "4"){
                    $('#assign_to').attr("disabled",false);
                    $('.assigntoclient').hide();
                    $('.assigninterview').hide();
                    $('.assignCandidateToEmpl').show();
                    $('.assign').show();
                    $('.default').hide();
                    CKEDITOR.instances['editor1'].setData("Assign candidate to employee");
                }
                else
                {
                    $('#assign_to').attr("disabled","disabled");
                    $('.assigntoclient').hide();
                    $('.assigninterview').hide();
                    $('.assignCandidateToEmpl').hide();
                    $('.assign').hide();
                    $('.default').show();
                    CKEDITOR.instances['editor1'].setData("");
                }
            });    
            
            var follow_id = <?php if($this->follow_id > 0){echo $this->follow_id;} else echo 0; ?>;
  
            if ($('#follow_type').val() == "0"){
                $('#assign_to').attr("disabled","disabled");
                $('.assigntoclient').show();
                $('.assigninterview').hide();
                $('.assignCandidateToEmpl').hide();
                $('.assign').hide();
                $('.default').show();
                if(follow_id < 1){
                    CKEDITOR.instances['editor1'].setData("Assign candidate to client");
                }
            }
            else if($('#follow_type').val() == "2"){
                $('#assign_to').attr("disabled","disabled");
                $('.assigninterview').show();
                $('.assigntoclient').hide();
                $('.assignCandidateToEmpl').hide();
                $('.assign').hide();
                $('.default').show();
                if(follow_id < 1){
                    CKEDITOR.instances['editor1'].setData("Assign candidate to interview");
                }
            }
            else if($('#follow_type').val() == "3" || $('#follow_type').val() == "4"){
                    $('.assigninterview').hide();
                    $('.assigntoclient').hide();
                    $('.assignCandidateToEmpl').show();
                    $('.assign').show();
                    $('.default').hide();
                if(follow_id < 1){
                    CKEDITOR.instances['editor1'].setData("Assign candidate to employee");
                }
            }
            else
            {
                    $('#assign_to').attr("disabled","disabled");
                    $('.assigninterview').hide();
                    $('.assigntoclient').hide();
                    $('.assignCandidateToEmpl').hide();
                    $('.assign').hide();
                    $('.default').show();
                if(follow_id < 1){
                    CKEDITOR.instances['editor1'].setData("");
                }                    
            }               
            
            
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
            
            $('.save_applleave').click(function(){
                var data = new FormData(document.getElementById("applicant_form"));
                data.append('action','saveApplicantLeave');
                data.append('applicant_id','<?php echo $this->applicant_id;?>');
                $.ajax({ 
                    type: 'POST',
                    url: 'applicant.php',
                    cache: false,
                    data:data,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    async: false,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       issend = false;
                    }		
                 });
                 var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&current_tab=followup&applicant_id={$_REQUEST['applicant_id']}&follow_id=$this->follow_id&edit=1";?>';
                 window.location.href = url;
                 return false;
            });
            
            $('.upload_resume_btn').click(function(){
                var data = "action=uploadResume&applicant_id=<?php echo $this->applicant_id;?>&"+$('#applicant_form').serialize();
                var fd = new FormData(document.getElementById("applicant_form"));
                fd.append('action','uploadResume');
                fd.append('applicant_id','<?php echo $this->applicant_id;?>');
                $.ajax({ 
                    type: 'POST',
                    url: 'applicant.php',
                    cache: false,
                    data:fd,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    async: false,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(fd) {
                       var jsonObj = eval ("(" + fd + ")");
                       issend = false;
                    }		
                 });
                 var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>';
                 window.location.href = url + "&current_tab=" + $('#current_tab').val();
                 return false;
            });            
            
            $('.upload_file_btn').click(function(){
                var data = "action=uploadFile&applicant_id=<?php echo $this->applicant_id;?>&"+$('#applicant_form').serialize();
                var fd = new FormData(document.getElementById("applicant_form"));
                fd.append('action','uploadFile');
                fd.append('applicant_id','<?php echo $this->applicant_id;?>');
                fd.append('upload_file','#file_type');
                $.ajax({ 
                    type: 'POST',
                    url: 'applicant.php',
                    cache: false,
                    data:fd,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    async: false,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(fd) {
                       var jsonObj = eval ("(" + fd + ")");

                       issend = false;
                    }		
                 });
                 var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>';
                 window.location.href = url + "&current_tab=" + $('#current_tab').val();
                 return false;
            });             
            
                $('#f_company').change(function(){
                var value = $(this);
                var data = "action=getDepartment&client_id="+value.val()+"&department_id="+$(this).attr('pid')+"&job_id="+$(this).attr('name');
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
                        var jsonObj = eval ("(" + data + ")");
                        if(jsonObj['department'] != null){    
                            $('#fol_department').empty();
                           for(var i=0;i<jsonObj['department'].length; i++){
                                $('#fol_department').append(jsonObj['department'][i]);
                           }
                        }
                        else {
                            $('#fol_department').empty();
                        }
                        if(jsonObj['job'] != null){    
                           $('#fol_job_title').empty();
                           for(var i=0;i<jsonObj['job'].length; i++){
                                $('#fol_job_title').append(jsonObj['job'][i]);
                           }
                        }
                        else {
                            $('#fol_job_title').empty();
                        }                        
                       issend = false;
                       if($('#fol_department :selected').val() != null) {
                           $("#fol_department").select2().select2('val',$('#fol_department :selected').val());
                        }
                        if($('#fol_job_title :selected').val() != null) {
                           $("#fol_job_title").select2().select2('val',$('#fol_job_title :selected').val());
                        }
                    }		
                 });
                 return false;
            });          
            
            $('#f_company').trigger('change');
            
            
        $('#followup_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
        $('#workexp_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
        $('#uploadfile_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
        $('#resume_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
        $('#family_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
            
});
function getBankCode(bank){

                var data = "action=getBankCode&applicant_bank="+bank;
                $.ajax({ 
                    type: 'POST',
                    url: 'applicant.php',
                    cache: false,
                    data:data,
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       $('#applicant_bank_no').val(jsonObj.bank_no);
                    }		
                 });
}
    </script>
        <script type = "text/javascript">
			function checkEnter() {
				var x =  document.getElementById('applicant_postal_code').value;
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
												jQuery('#applicant_address').val(subObject); 
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
				var x =  document.getElementById('applicant_postal_code2').value;
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
												jQuery('#applicant_address2').val(subObject); 
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
                   <option value = '2' <?php if($this->applicant_status == 2){ echo 'SELECTED';}?>>Close</option>
                   
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
              <label for="applicant_postal_code" class="col-sm-2 control-label">Postal Code 1</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" onkeyup="checkEnter()" id="applicant_postal_code" name="applicant_postal_code" value = "<?php echo $this->applicant_postal_code;?>" placeholder="Postal Code">
              </div>
              <label for="applicant_postal_code2" class="col-sm-2 control-label">Postal Code 2</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" onkeyup="checkEnter2()" id="applicant_postal_code2" name="applicant_postal_code2" value = "<?php echo $this->applicant_postal_code2;?>" placeholder="Postal Code">
              </div>
        </div>
        <div class="form-group">
              <label for="applicant_unit_no" class="col-sm-2 control-label">Unit No 1</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_unit_no" name="applicant_unit_no" value = "<?php echo $this->applicant_unit_no;?>" placeholder="Unit No">
              </div>
              <label for="applicant_unit_no2" class="col-sm-2 control-label">Unit No 2</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_unit_no2" name="applicant_unit_no2" value = "<?php echo $this->applicant_unit_no2;?>" placeholder="Unit No">
              </div>
        </div>
        <div class="form-group">
            <label for="applicant_address" class="col-sm-2 control-label">Address 1</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="applicant_address" name="applicant_address" placeholder="Address" readonly><?php echo $this->applicant_address;?></textarea>
            </div>
            <label for="applicant_address2" class="col-sm-2 control-label">Address 2</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="applicant_address2" name="applicant_address2" placeholder="Address 2" readonly><?php echo $this->applicant_address2;?></textarea>
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
                   <?php $sql = "SELECT empl_name FROM `db_empl` WHERE empl_group = '8'";
                   $query = mysql_query($sql);
                    while($row = mysql_fetch_array($query)){
                   echo $row['empl'];
                    }
                    ?>
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
    <title>Candidate Management</title>
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
            <h1>Candidate Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12" >
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Candidate Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='applicant.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table id="applicant_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:1%'>No</th>
                        <th style = 'width:8%'>Name</th>
                        <th style = 'width:8%'>Email</th>
                        <th style = 'width:5%'>Mobile</th>
<!--                        <th style = 'width:10%'>Address</th>
                        <th style = 'width:10%'>Manager By</th>
                        <th style = 'width:10%'>Create By</th>-->
                        <th style = 'width:10%'>Create Date</th>
                        <th style = 'width:10%'>Job Assign</th>
                        <th style = 'width:25%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php                           
                      $sql = "SELECT applicant.*, left(applicant.insertDateTime,10) as date, right(applicant.insertDateTime, 8) as time ,gp.group_code,dp.department_code,outl.outl_code
                              FROM db_applicant applicant 
                              INNER JOIN db_group gp ON gp.group_id = applicant.applicant_group
                              LEFT JOIN db_department dp ON dp.department_id = applicant.applicant_department
                              LEFT JOIN db_outl outl ON outl.outl_id = applicant.applicant_outlet
                              WHERE applicant.applicant_id > 0
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
<!--                            <td><?php echo $row['applicant_street'];?></td>
                            <td>
                            <?php 
                            $appl_id = $row['applicant_id'];
                            $sql2 = "SELECT empl_name from db_empl inner join db_followup on assign_to = empl_id where applfollow_id = '$appl_id' group by empl_name";
                            $query2 = mysql_query($sql2);
                            while($row2 = mysql_fetch_array($query2)){
                                echo $row2['empl_name'];
                                ?><br>
                                <?php
                            }
                            ?>                            
                            </td>
                            <?php 
                            $insert_id = $row['insertBy'];
                            $sql3 = "select empl_name from db_empl where empl_id = '$insert_id'";
                            $query3 = mysql_query($sql3);
                            $row3 = mysql_fetch_array($query3);
                            ?>
                            <td><?php echo $row3['empl_name'];?></td>-->
                            <td><?php echo format_date($row['insertDateTime']) . " " . $row['time']?>
                                
                            <input type="hidden" value = "
                            <?php echo print_r($row); 
                            //$sql4 = "SELECT * FROM db_followup f inner join db_resume r on f.applfollow_id = r.resume_appl_id inner join db_experience e on r.resume_appl_id = e.exp_appl_id inner join db_family a on e.exp_appl_id = a.applicant_family_id inner join db_applicant p on a.applicant_family_id = p.applicant_id where p.applicant_id = '$appl_id'";
                            $sql7 ="SELECT * FROM db_experience where exp_appl_id = '$appl_id'";
                            $query7 = mysql_query($sql7);
                            while($row7 = mysql_fetch_array($query7)){
                                $arrayObject = new ArrayObject($row7);
                                $copy = $arrayObject->getArrayCopy();
                                echo print_r($copy);
                            }
                            $sql8 = "SELECT * FROM db_followup where applfollow_id = '$appl_id'";
                            $query8 = mysql_query($sql8);
                            while($row8 = mysql_fetch_array($query8)){
                                $arrayObject2 = new ArrayObject($row8);
                                $copy2 = $arrayObject2->getArrayCopy();
                                echo print_r($copy2);
                            }
                            $sql9 ="SELECT * FROM db_family where applicant_family_id = '$appl_id'";
                            $query9 = mysql_query($sql9);
                            while($row9 = mysql_fetch_array($query9)){
                                $arrayObject3 = new ArrayObject($row9);
                                $copy3 = $arrayObject3->getArrayCopy();
                                echo print_r($copy3);
                            }
                            ?>"> 
                            <input type="hidden" value = "
                            <?php
                            $sql10 ="SELECT * FROM db_resume where resume_appl_id = '$appl_id'";
                            $query10 = mysql_query($sql10);
                            while($row10 = mysql_fetch_array($query10)){
                                $arrayObject4 = new ArrayObject($row10);
                                $copy4 = $arrayObject4->getArrayCopy();
                                echo print_r($copy4);
                            }?>"> 
                            </td>
                            <td>
                                <?php
                                $today = date("Y-m-d");
                                $sql11 = "SELECT * FROM db_followup WHERE follow_type = '0' AND fol_assign_expiry_date >= '$today' AND fol_status = '0' AND applfollow_id = '$appl_id' AND fol_approved = 'Y'"; 
                                //echo $sql11;
                                $query11 = mysql_query($sql11);
                                if($row11 = mysql_fetch_array($query11)){ ?>
                                    <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $appl_id;?>&follow_id=<?php echo $row11['follow_id'];?>&edit=1"><button type='button' style='background-color: #3c5bf7; border-color: #0c4da0; width:85px;' class='btn btn-primary btn-warning'>Assigned</button></a>
                                <?php }else{ ?>
                                    <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $appl_id;?>"><button type='button' style='background-color: #009688; border-color: #007368;width:85px;' class='btn btn-primary btn-warning'>Unassigned</button></a>
                                <?php } 
                                ?>
                            </td>
                            

                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'view')){

                                $sql5 = "SELECT MAX(insertDateTime) AS Date FROM db_resume where resume_appl_id = '$appl_id'";
                                $query5 = mysql_query($sql5);
                                $row5 = mysql_fetch_array($query5); 
                                $date = $row5['Date'];
                                $sql6 = "SELECT resume_url FROM db_resume where insertDateTime = '$date'";
                                $query6 = mysql_query($sql6);
                                $row6 = mysql_fetch_array($query6); 
                                if($row6['resume_url'] != ""){
                                ?>
                                    <a href="<?php echo $row6['resume_url'];?>" download><button type='button' style='background-color: #7da1a7; border-color: #6b7e96;' class='btn btn-primary btn-warning'>Resume</button></a>
                                <?php }
                                else{   
                                }
                                ?>
                                <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row['applicant_id'];?>"><button type="button" id="Btn" class="btn btn-primary btn-warning" >Add Remarks</button></a>
                                <button type="button" id="Btn" class="btn btn-primary btn-warning remarks" style="background-color: #8BC34A; border-color: #4CAF50" pid="<?php echo $row['applicant_id'];?>" >Remarks</button>
                                
                                <?php }
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicant.php?action=edit&applicant_id=<?php echo $row['applicant_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('applicant.php?action=delete&applicant_id=<?php echo $row['applicant_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:1%'>No</th>
                        <th style = 'width:8%'>Name</th>
                        <th style = 'width:8%'>Email</th>
                        <th style = 'width:5%'>Mobile</th>
<!--                        <th style = 'width:15%'>Address</th>
                        <th style = 'width:10%'>Manager By</th>
                        <th style = 'width:10%'>Create By</th>-->
                        <th style = 'width:10%'>Create Date</th>
                        <th style = 'width:10%'>Job Assign</th>
                        <th style = 'width:25%'></th>
                      </tr>
                    </tfoot>
                  </table>    

                                <div class="modal-body">
                                    <h4 class="modal-title">Candidate Remarks</h4>
                                    <div id = 'remarks_content'></div>

                                </div>

                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper --><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    
     <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="width:70%; margin-top: 10%">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;background-color: #377506; color:fff">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Candidate Remarks</h4>
        </div>
        <div class="modal-body" style="background-color: #ecfff2;height: 550px;overflow-y: scroll;">
            <div id = 'remarks_content'></div>

        </div>
        <div class="modal-footer" style="background-color: #377506; padding: 10px;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>    
     
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
        
        $('#myModal').modal('hide');

//        $('#myModal').modal({
//        show: 'false'
//        });
        
        $('.remarks').click(function(){
   
  
                    var data = "action=getRemarkDetail&applicant_id="+$(this).attr("pid");
                    
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
                       var jsonObj = eval ("(" + data + ")");
                       
                       var table ="";
                     
                        if(jsonObj['aRemarks'] != null)
                         {
                              //table ="<a href='applicant.php?action=edit&current_tab=followup&applicant_id=" + jsonObj['aRemarks']['applicant_id'][0] + "'><button type='button' class='btn btn-info' style= 'float:right'>Add Remarks</button></a><br><br>";
                       table = table + "<table id='" + "applicant_remark_table' " + "class=" + "'table table-bordered table-hover'" + "><thead><tr> <th style = 'width:2%'>No</th><th style = 'width:5%'>Create By</th><th style = 'width:5%'>Assign</th>";
                           table = table + "<th style = 'width:5%'>Client</th><th style = 'width:7%'>Remark Type</th><th style = 'width:5%'>Received Offer</th><th style = 'width:15%'>Comments</th><th style = 'width:5%'>Status</th><th style = 'width:5%'>Create Time</th><th style = 'width:5%'>Create Date</th></tr></thead><tbody>";

//                    console.log(jsonObj['assign_to']);
//                    console.log(jsonObj['assign_time']);
                        var n = 1;
                    for(var i=0;i<jsonObj['aRemarks']['empl_name'].length;i++){
                    table = table + "<tr><td>" + "<a href='applicant.php?action=edit&current_tab=followup&applicant_id=" + jsonObj['aRemarks']['applicant_id'][i] + "&follow_id=" + jsonObj['aRemarks']['follow_id'][i] + "&edit=" + jsonObj['aRemarks']['edit'][i] + "'>" + n + "</a></td><td>" + jsonObj['aRemarks']['empl_name'][i] +"</td><td>" + jsonObj['aRemarks']['assign_to'][i] + "</td><td>" + jsonObj['aRemarks']['interview_company'][i] + "</td><td>" + jsonObj['aRemarks']['follow_type'][i] + "</td><td>" + jsonObj['aRemarks']['received_offer'][i] + "</td><td>" + jsonObj['aRemarks']['comments'][i] + "</td><td>" + jsonObj['aRemarks']['status'][i] + "</td><td>" + jsonObj['aRemarks']['time'][i] + "</td><td>" + jsonObj['aRemarks']['date'][i] + "</td></tr>" 
                    n++;
                    }
                    table = table + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th><th style = 'width:5%'>Create By</th><th style = 'width:5%'>Assign</th>";
                           table = table + "<th style = 'width:5%'>Client</th><th style = 'width:7%'>Remark Type</th><th style = 'width:5%'>Received Offer</th><th style = 'width:15%'>Comments</th><th style = 'width:5%'>Status</th><th style = 'width:5%'>Create Time</th><th style = 'width:5%'>CreateDate</th></tr></tfoot></table>";
                       }
                    else
                   {
                       table = table + "No have any remarks.";
                   }
                                               
                    $('#remarks_content').html(table);
                    
                      $(function () {
                        $('#applicant_remark_table').DataTable({
                          "paging": true,
                          "lengthChange": false,
                          "searching": true,
                          "ordering": true,
                          "info": true,
                          "autoWidth": false
                        });
                      });  
                      
                       issend = false;
                    }		
                 });
                 return false;
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
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_bankrupt" value = '1' <?php if(($this->declaration_bankrupt == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_bankrupt" value = '0' <?php if($this->declaration_bankrupt == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                     </div>
                     <div class="col-sm-3">
                            <input type="text" class="form-control" id="db_specify" name="db_specify" value = "<?php echo $this->db_specify;?>" placeholder="If Yes, Please specify">
                     </div>
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_physical" class=" control-label">2. &nbsp;Are you suffering from any physical / mental impairment or chronic / pre-existing illness?</label>
                 <div class="radio">
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_physical" value = '1' <?php if(($this->declaration_physical == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_physical" value = '0' <?php if($this->declaration_physical == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                     </div>
                     <div class="col-sm-3">
                            <input type="text" class="form-control" id="dp_specify" name="dp_specify" value = "<?php echo $this->dp_specify;?>" placeholder="If Yes, Please specify">
                     </div>
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_lt_medical" class=" control-label">3. &nbsp;Are you currently undergoing long-term medical treatment?</label>
                 <div class="radio">
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_lt_medical" value = '1' <?php if(($this->declaration_lt_medical == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_lt_medical" value = '0' <?php if($this->declaration_lt_medical == '0'){ echo 'CHECKED';}?>>No
                        </label>
                     </div>
                     <div class="col-sm-3">
                            <input type="text" class="form-control" id="dltm_specify" name="dltm_specify" value = "<?php echo $this->dltm_specify;?>" placeholder="If Yes, Please specify">
                     </div>
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_law" class=" control-label">4. &nbsp;Have you ever been convicted or found guilty of an offence in Court Of Law in any country?</label>
                 <div class="radio">
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_law" value = '1' <?php if(($this->declaration_law == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_law" value = '0' <?php if($this->declaration_law == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                      </div>
                      <div class="col-sm-3">
                            <input type="text" class="form-control" id="dl_specify" name="dl_specify" value = "<?php echo $this->dl_specify;?>" placeholder="If Yes, Please specify">
                     </div>
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_warning" class=" control-label">5. &nbsp;Have you ever been issued warning letters, suspended or dismissed from employment before?</label>
                 <div class="radio">
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_warning" value = '1' <?php if(($this->declaration_warning == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_warning" value = '0' <?php if($this->declaration_warning == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                     </div>
                     <div class="col-sm-3">
                            <input type="text" class="form-control" id="dw_specify" name="dw_specify" value = "<?php echo $this->dw_specify;?>" placeholder="If Yes, Please specify">
                     </div>
                </div>
        </div>
         <div class="form-group">
          <label for="declaration_applied" class=" control-label">6. &nbsp;Have you applied for any job with this company before?</label>
                 <div class="radio">
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_applied" value = '1' <?php if(($this->declaration_applied == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_applied" value = '0' <?php if($this->declaration_applied == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                     </div>
                     <div class="col-sm-3">
                            <input type="text" class="form-control" id="da_specify" name="da_specify" value = "<?php echo $this->da_specify;?>" placeholder="If Yes, Please specify">
                     </div>
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
    public function getQualificationForm(){
    ?> 
        <h3><u>Qualification and Skill</u></h3>
        <div class="form-group">
            <label for="appl_n_level" class="col-sm-2 control-label">N-Levels</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_n_level" name="appl_n_level" value = "<?php echo $this->appl_n_level;?>" placeholder="Course">
            </div>
            <label for="appl_diploma" class="col-sm-2 control-label">Diploma</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_diploma" name="appl_diploma" value = "<?php echo $this->appl_diploma;?>" placeholder="Course">
            </div>
        </div>
        <div class="form-group">
            <label for="appl_o_level" class="col-sm-2 control-label">O-Levels</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_o_level" name="appl_o_level" value = "<?php echo $this->appl_o_level;?>" placeholder="Course">
            </div>
            <label for="appl_degree" class="col-sm-2 control-label">Degree</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_degree" name="appl_degree" value = "<?php echo $this->appl_degree;?>" placeholder="Course">
            </div>
        </div>        
        <div class="form-group">
            <label for="appl_a_level" class="col-sm-2 control-label">A-Levels</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_a_level" name="appl_a_level" value = "<?php echo $this->appl_a_level;?>" placeholder="Course">
            </div>
            <label for="appl_other_qualification" class="col-sm-2 control-label">Other</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_other_qualification" name="appl_other_qualification" value = "<?php echo $this->appl_other_qualification;?>" placeholder="Course">
            </div>
        </div>

        <br><br>
        
        <h5>Please state languages and proficiency level, e.g. excellent, good, fair, poor</5><br><br>
        <div class="form-group">
            <label for="appl_written" class="col-sm-2 control-label">Written</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_written1" name="appl_written" value = "<?php echo $this->appl_written;?>" placeholder="eg: English - good">
            </div>
            <label for="appl_spoken" class="col-sm-2 control-label">Spoken</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_spoken" name="appl_spoken" value = "<?php echo $this->appl_spoken;?>" placeholder="eg: English - good">
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
                  <textarea class="form-control" rows="3" id="other_comments" name="other_comments" placeholder="Comments"><?php echo $this->other_comments;?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="official_consultant" class="col-sm-2 control-label">Consultant</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="official_consultant" name="official_consultant" value = "<?php echo $this->official_consultant;?>" placeholder="Consultant Name">
            </div>
            <label for="official_date" class="col-sm-2 control-label">Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="walkin_date" name="official_date" value = "<?php echo format_date($this->official_date);?>" placeholder="Date">
            </div>
        </div>
        <div class="form-group">
                    <label for="official_time" class="col-sm-2 control-label">Time</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;'>
                <input type="text" class="form-control timepicker" id="official_time" name="official_time" value = "<?php echo $this->official_time;?>">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
    <?php
    }
    
    public function saveNotification($name){
        global $notification_desc; 
        $table_field = array('noti_id','noti_to','noti_url','noti_parent_id','noti_desc','noti_view_status');

        if($_SESSION['empl_group'] == "4" || $_SESSION['empl_group'] == "-1" || $_SESSION['empl_group'] == "1"){
            
            $sql = "select assign_to from db_followup where follow_id = '$this->follow_id'";
            $query = mysql_query($sql);
            $row = mysql_fetch_array($query);
            $assign_id = $row['assign_to'];
//            $table_value = array('', $assign_id, $this->follow_id,$notification_desc['assign_to'],0);       
            $table_value = array('', $assign_id,'applicant.php?action=edit&current_tab=followup&applicant_id='.$this->applicant_id.'&follow_id='.$this->follow_id.'&edit=1' , $this->follow_id,$notification_desc[$this->follow_type] . $name,0);   
            
        }
        else{
            if($this->follow_type == "4"){
                $user_id = $_SESSION['empl_id'];
                    $manager_id = $this->assign_manager;
                $table_value = array('', $manager_id,'applicant.php?action=edit&current_tab=followup&applicant_id='.$this->applicant_id.'&follow_id='.$this->follow_id.'&edit=1' ,$this->follow_id,$notification_desc[$this->follow_type] . $name,0);   
            }
            else{
                $user_id = $_SESSION['empl_id'];
                $sql = "select empl_manager from db_empl where empl_id = '$user_id'";
                $query = mysql_query($sql);
                $row = mysql_fetch_array($query);
                    $manager_id = $row['empl_manager'];
                $table_value = array('', $manager_id,'applicant.php?action=edit&current_tab=followup&applicant_id='.$this->applicant_id.'&follow_id='.$this->follow_id.'&edit=1' ,$this->follow_id,$notification_desc[$this->follow_type] . $name,0);   
            }
        }
        $remark = "Create Notification.";
        $this->save->SaveData($table_field,$table_value,'db_notification','noti_id',$remark);
        $this->noti_id = $this->save->lastInsert_id;
    }
    public function updateNotification(){
        $this->noti_id = $_POST['noti_id'];
        $table_field = array('noti_view_status');
        $table_value = array(1);
   
        $remark = "Update status notification";
        if(!$this->save->UpdateData($table_field,$table_value,'db_notification','noti_id',$remark,$this->noti_id," ")){
           return false;
        }else{
           return true;
        }
        
    }
    public function printPDF(){
        //$pdf = new FPDF('P', 'pt', 'Letter');
        //$pdf->AddPage(); 
        
        $pdf = new FPDI();

        $pageCount = $pdf->setSourceFile('dist/PDF/Application.pdf');
        

            $tplIdx = $pdf->importPage(1, '/MediaBox');
            $pdf->addPage();
            $pdf->useTemplate($tplIdx, 0, 0, 0);
            $pdf->SetAutoPageBreak(off, 0);
            $sql = "SELECT *, left(applicant_nric,1) as nric_left, mid(applicant_nric,2,7) as nric_mid, right(applicant_nric, 1) as nric_right, left(applicant_birthday,4) as year FROM `db_applicant` WHERE applicant_id = '$this->applicant_id'";
            $query = mysql_query($sql);
            $row = mysql_fetch_array($query);

            //general info
                $pdf->SetFont('Arial', '', 10);

                $pdf->SetTextColor(0,0,255);
                $pdf->Cell(27, 5, "");
                
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(120, 53, $row['applicant_position']); 
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(-118, 53, format_date(date("Y-m-d")));

                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(110, 88, $row['applicant_name']); 
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(5, 89, $row['nric_left']. " - " );
                $pdf->Cell(17, 89, $row['nric_mid']. " - " );
                $pdf->Cell(-135, 89, $row['nric_right'] );
                
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(-6, 103, $row['applicant_unit_number']." ".$row['applicant_street'] );
                
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(90, 117, format_date($row['applicant_birthday']));
                $year = $row['year'];
                $now = date("Y");
                
                
                $pdf->Cell(50, 117, $now-$year );
                if($row['applicant_sex'] == "M")
                {
                    $pdf->Cell(-110, 117, "MALE" );
                }
                else
                    $pdf->Cell(-110, 117, "FEMALE" );
            
                if($row['applicant_race']  == "1"){
                $pdf->Cell(105, 131.5, "CHINESE" );}
                else if($row['applicant_race']  == "2"){
                $pdf->Cell(105, 131.5, "MALAY" );}
                else if($row['applicant_race']  == "3"){
                $pdf->Cell(105, 131.5, "INDIAN" );}                   
                else{
                    $pdf->Cell(105, 131.5, "" );
                }   
                
                if($row['applicant_religion']  == "1"){
                $pdf->Cell(-90, 131.5, "BUDDHIST" );}
                else if($row['applicant_religion']  == "2"){
                $pdf->Cell(-90, 131.5, "CHRISTIAN" );}
                else if($row['applicant_religion']  == "3"){
                $pdf->Cell(-90, 131.5, "HINDU" );}       
                else if($row['applicant_religion']  == "4"){
                $pdf->Cell(-90, 131.5, "MUSLIM" );} 
                else{
                    $pdf->Cell(-90, 131.5, "" );
                } 
                
                if($row['applicant_nationality'] == "1"){
                    $pdf->Cell(100, 145.5, "MALAYSIAN" );}
                else if($row['applicant_nationality'] == "2"){
                    $pdf->Cell(100, 145.5, "SINGAPOREAN" );}
                else if($row['applicant_nationality'] == "3"){
                    $pdf->Cell(100, 145.5, "FILIPINO" );}
                else{
                    $pdf->Cell(100, 145.5, "" );
                }
                 
                if($row['applicant_marital_status']  == "S"){
                $pdf->Cell(-120, 145.5, "SINGLE" );}
                else if($row['applicant_marital_status']  == "M"){
                $pdf->Cell(-120, 145.5, "MARRIED" );}
                else if($row['applicant_marital_status']  == "D"){
                $pdf->Cell(-120, 145.5, "DIVORCED" );}       
                else if($row['applicant_marital_status']  == "W"){
                $pdf->Cell(-120, 145.5, "WIDOWER" );} 
                else if($row['applicant_marital_status']  == "WE"){
                $pdf->Cell(-120, 145.5, "WIDOWEE" );} 
                else{
                    $pdf->Cell(-120, 145.5, "" );
                }      
                
                $pdf->Cell(50, 160, $row['applicant_mobile']); 
                $pdf->Cell(75, 160, $row['applicant_tel'] );
                $pdf->Cell(-148, 160, $row['applicant_numberofchildren'] );
                
                
                $pdf->Cell(133, 175, $row['applicant_email']); 
                $pdf->Cell(-89, 175, $row['applicant_height']." cm " . $row['applicant_weight']." kg" );
                
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(79, 189, $row['applicant_emer_contact']); 
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(-108, 189, $row['applicant_emer_phone1'] );
                
                $pdf->Cell(-8, 204, $row['applicant_emer_relation']); 
                
            //Qualification
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(88, 244, $row['appl_n_level']); 
                $pdf->Cell(-88, 244, $row['appl_diploma']);    
                $pdf->Cell(88, 259, $row['appl_o_level']); 
                $pdf->Cell(-88, 259, $row['appl_degree']);  
                $pdf->Cell(88, 274, $row['appl_a_level']); 
                $pdf->Cell(-88, 274, $row['appl_other_qualification']);  
                $pdf->SetFont('Arial', '', 10);
            //family
                $pdf->SetY(165);
                $pdf->Cell(5, 0, ""); 
                $line = 2;
                $sql2 = "SELECT * FROM db_family WHERE applicant_family_id = '$this->applicant_id'";
                $query2 = mysql_query($sql2);
                while ($row2 = mysql_fetch_array($query2))
                {
                    
                    $pdf->Cell(70, $line, $row2['family_name']); 
                    $pdf->Cell(43, $line, $row2['family_relationship'] );
                    $pdf->Cell(18, $line, $row2['family_age']); 
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(-131, $line, $row2['family_occupation'] );
                    $pdf->SetFont('Arial', '', 10);
                    $line = $line + 10;
                    if($line > 45)
                    {
                        break;
                    }
                }
                $pdf->Cell(15, 81, ""); 
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(93, 81, $row['appl_written']); 
                $pdf->Cell(-93, 81, $row['appl_spoken']);   
                $pdf->SetFont('Arial', '', 10);
                
            //referee
                $pdf->SetFont('Arial', '', 9);
                $pdf->SetY(240);
                $pdf->Cell(4, 0, "");
                $pdf->Cell(52, 8, $row['referee_name1']); 
                $pdf->Cell(59, 8, $row['referee_occupation1']); 
                $pdf->Cell(30, 8, $row['referee_year_know1']);
                $pdf->cell(-141, 8, $row['referee_contact_no1']);
                $pdf->Cell(52, 22, $row['referee_name2']); 
                $pdf->Cell(59, 22, $row['referee_occupation2']); 
                $pdf->Cell(30, 22, $row['referee_year_know2']);
                $pdf->cell(-53, 22, $row['referee_contact_no2']);
                
                if($row['referee_present_employer'] == '1'){
                    $pdf->Cell(-10, 48, "Yes");}
                else
                {
                    $pdf->Cell(-10, 48, "No");
                }
                $pdf->Cell(10, 0, "");
                if($row['referee_previous_employer'] == '1'){
                    $pdf->Cell(-22.5, 57, "Yes");}
                else
                {
                    $pdf->Cell(-22.5, 57, "No");
                }
                
            $tplIdx = $pdf->importPage(2, '/MediaBox');
            $pdf->addPage();
            $pdf->useTemplate($tplIdx, 0, 0, 0);
            $pdf->Cell(10, 0, "");
            
                if($row['declaration_bankrupt'] == '0'){
                    $pdf->Cell(60, 17, "No");}
                else
                {
                    $pdf->Cell(60, 17, "Yes");
                }
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(-60, 17, $row['appl_declaration_b_specify']);
                $pdf->SetFont('Arial', '', 10);
                if($row['declaration_physical'] == '0'){
                    $pdf->Cell(60, 38, "No");}
                else
                {
                    $pdf->Cell(60, 38, "Yes");
                }    
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(-60, 38, $row['appl_declaration_p_specify']);
                $pdf->SetFont('Arial', '', 10);
                if($row['declaration_lt_medical'] == '0'){
                    $pdf->Cell(60, 59, "No");}
                else
                {
                    $pdf->Cell(60, 59, "Yes");
                }
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(-60, 59, $row['appl_declaration_ltm_specify']);
                $pdf->SetFont('Arial', '', 10);
                if($row['declaration_law'] == '0'){
                    $pdf->Cell(60, 80, "No");}
                else
                {
                    $pdf->Cell(60, 80, "Yes");
                }    
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(-60, 80, $row['appl_declaration_l_specify']);
                $pdf->SetFont('Arial', '', 10);
                if($row['declaration_warning'] == '0'){
                    $pdf->Cell(60, 101, "No");}
                else
                {
                    $pdf->Cell(60, 101, "Yes");
                }
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(-60, 101, $row['appl_declaration_w_specify']);
                $pdf->SetFont('Arial', '', 10);
                if($row['declaration_applied'] == '0'){
                    $pdf->Cell(60, 122, "No");}
                else
                {
                    $pdf->Cell(60, 122, "Yes");
                }          
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(-60, 122, $row['appl_declaration_a_specify']);
                $pdf->SetFont('Arial', '', 10);
            
            //TC
                
                $pdf->SetY(222);
                $pdf->Cell(20, 0, "");
                $pdf->Cell(20, 0, format_date($row['tc_date']));
            
            
            
            
//        $pdf->SetFont('Arial', '', 13);
//      
//        $pdf->Cell(130, 5, "");
//        $pdf->Cell(25, 5, "SUCCESS HUMAN RESOURCE CENTRE PTE LTD"); 
//        $pdf->Cell(-1, 32, "SUCCESS RESOURCE CENTRE PTE LTD");
//        
//        $pdf->SetFont('Arial', '', 10);
//        $pdf->Cell(30, 60, "Sophia Road #06-23/29 Peace Centre Singapore 228149");
//        $pdf->Cell(22, 83, "Tel: 63373183    Fax: 63370329 / 63370425");
//        $pdf->Cell(-200, 105, "Website: www.successhrc.com.sg");
//        
//        $pdf->Cell(330, 150, "POSITION : ");
//        $pdf->Cell(-330, 150, "DATE : ");
//        
//        $pdf->SetLineWidth(1);
//        $pdf->SetFillColor(00, 00, 00);
//        $pdf->SetLink(100, 300);
//        $pdf->SetTextColor(255);
//        $pdf->SetY(120);
//        $pdf->Cell(540, 15, "PART A: PERSONAL PARTICULARS",1, 0, C, true);
//        
//        $pdf->SetTextColor(000);
//        $pdf->Cell(-537, 5, "");
//        $pdf->Cell(300, 70, "FULL NAME : ");
//        $pdf->Cell(-300, 70, "NRIC : ");
//        
//        $pdf->Cell(400, 100, "ADDRESS : ");
//        $pdf->Cell(-400, 100, "S'PORE : ");
//        
//        $pdf->Cell(220, 130, "D.O.B :");
//        $pdf->Cell(100, 130, "AGE :");
//        $pdf->Cell(-320, 130, "GENDER :");
//        
//        $pdf->Cell(320, 160, "RACE :");
//        $pdf->Cell(-320, 160, "RELIGION :");
//        
//        $pdf->Cell(320, 190, "NATIONALITY :");
//        $pdf->Cell(-320, 190, "MARITAL STATUL :");
//        
//        $pdf->Cell(190, 220, "CONTACT NO (HP):");
//        $pdf->Cell(130, 220, "(H):");
//        $pdf->Cell(-320, 220, "NO. OF CHILDREN:");
//      
//        $pdf->Cell(320, 250, "EMAIL :");
//        $pdf->Cell(-320, 250, "HEIGHT & WEIGHT :");
//        
//        $pdf->Cell(320, 280, "EMERGENCY CONTACT PERSON :");
//        $pdf->Cell(-320, 280, "(HP):");
//        
//        $pdf->Cell(320, 310, "RELATIONSHIP :");
//        $pdf->Cell(-320, 310, "ORD (For Male Only) :");        
//        
//        $pdf->SetLink(100, 300);
//        $pdf->SetTextColor(255);
//        $pdf->SetY(300);
//        $pdf->Cell(540, 15, "PART B: QUALIFICATION",1, 0, C, true);        
//        
//        $pdf->SetTextColor(000);
//        $pdf->Cell(-460, 5, "");
//        $pdf->Cell(-75, 70, "Please tick the qualifications obtained. State the course of study and/or name of school. ");
//        
//        $pdf->Cell(250, 120, "o N-Levels : ");
//        $pdf->Cell(-250, 120, "o  Diploma  : ");
//        $pdf->Cell(250, 150, "o  O-Levels : ");
//        $pdf->Cell(-250, 150, "o  Degree  : ");
//        $pdf->Cell(250, 180, "o  A-Levels : ");
//        $pdf->Cell(-250, 180, "o  Others  : ");        
//        
//        $pdf->SetLink(100, 300);
//        $pdf->SetTextColor(255);
//        $pdf->SetY(410);
//        $pdf->Cell(540, 15, "PART C: FAMILY BACKGROUND",1, 0, C, true);           
//        
//        $pdf->SetTextColor(000);
//        $pdf->Cell(-480, 5, "");
//        $pdf->Cell(130, 70, "Name ");
//        $pdf->Cell(140, 70, "Relationship");
//        $pdf->Cell(100, 70, "Age ");
//        $pdf->Cell(-500, 70, "Occupation");
//        
//        $pdf->SetLink(100, 300);
//        $pdf->SetFillColor(255, 255, 255);
//        $pdf->SetY(460);
//        $pdf->Cell(540, 15, "PART C: FAMILY BACKGROUND",1, 0, C, true);         
//        
//        
        
        
        
        $pdf->Output();
    }
    public function getDepartment(){
        $pid = escape($_REQUEST['department_id']);
        $this->client_id = escape($_REQUEST['client_id']);
        $data[0] ="<option value = '' SELECTED='SELECTED'>Select One</option>";
        $sql = "SELECT timeshift_id, timeshift_department FROM db_timeshift where timeshift_company = '$this->client_id'";
        $query = mysql_query($sql);
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
    public function getJobTitle(){
        $pid = escape($_REQUEST['job_id']);
        $this->client_id = escape($_REQUEST['client_id']);
        $shownull =="Y";
        $data[0] ="<option value = '' SELECTED='SELECTED'>Select One</option>";
        if($pid >0){
            $sql = "SELECT job_id, job_title FROM db_jobs where (job_id = '$pid' or job_owner = '$this->client_id')";
        }else{
            $sql = "SELECT job_id, job_title FROM db_jobs where (job_id = '$pid' or job_owner = '$this->client_id') AND job_status = 'A'";
        }
        
        $query = mysql_query($sql);
        $i = 1;
        while($row = mysql_fetch_array($query)){
            $id = $row['job_id'];
            $code = $row['job_title'];
            
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
    
    
    
    
}

?>
