<?php


    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Applicant.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    include_once 'class/PDF2Text.php';
    include_once 'class/fpdf/fpdf.php';
    include_once 'class/fpdi/fpdi.php';
    
    $o = new Applicant();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->applicant_id = escape($_REQUEST['applicant_id']);
    if($action == 'update'){
        $o->fetchApplicantDetail(" AND applicant_id = '$o->applicant_id'","","",1);
        $o->applicant_oldpassword = $o->applicant_login_password;
    }
   
    $o->applicant_name = escape($_POST['applicant_name']);
    $o->applicant_nric = escape($_POST['applicant_nric']);
    $o->applicant_tel = escape($_POST['applicant_tel']);
    $o->applicant_mobile = escape($_POST['applicant_mobile']);
    $o->applicant_birthday = escape($_POST['applicant_birthday']);
    $o->applicant_joindate = escape($_POST['applicant_joindate']);
    $o->applicant_group = escape($_POST['applicant_group']);
    $o->applicant_black_list = escape($_POST['applicant_black_list']);
    $o->image_input = $_FILES['image_input'];
    $o->file_input = $_FILES['file_input'];
    $o->applicant_seqno = escape($_POST['applicant_seqno']);
    $o->applicant_status = escape($_POST['applicant_status']);
    $o->applicant_email = escape($_POST['applicant_email']);
    $o->applicant_outlet = escape($_POST['applicant_outlet']);
    $o->applicant_login_email = escape($_POST['applicant_login_email']);
    $o->applicant_login_password = escape($_POST['applicant_login_password']);
    $o->applicant_department = escape($_POST['applicant_department']);
    $o->applicant_bank = escape($_POST['applicant_bank']);
    $o->applicant_bank_acc_no = escape($_POST['applicant_bank_acc_no']);
    $o->applicant_nationality = escape($_POST['applicant_nationality']);
    $o->applicant_applicantpass = escape($_POST['applicant_applicantpass']);
    $o->applicant_resigndate = escape($_POST['applicant_resigndate']);
    $o->applicant_confirmationdate = escape($_POST['applicant_confirmationdate']);
    $o->applicantleave_leavetype = $_POST['applicantleave_leavetype'];
    $o->applicantleave_id = $_POST['applicantleave_id'];
    $o->applicant_height = $_POST['applicant_height'];
    $o->applicant_weight = $_POST['applicant_weight'];
    $o->applicant_position = $_POST['applicant_position'];
    $o->applicant_postal_code = $_POST['applicant_postal_code'];
    $o->applicant_unit_no = $_POST['applicant_unit_no'];    
    $o->applicant_postal_code2 = $_POST['applicant_postal_code2'];
    $o->applicant_unit_no2 = $_POST['applicant_unit_no2']; 
    $o->applicant_address = escape($_POST['applicant_address']);
    $o->applicant_address2 = escape($_POST['applicant_address2']);
    
    $o->applicant_levy_amt = escape($_POST['applicant_levy_amt']);
    $o->applicant_pass_issuance = escape($_REQUEST['applicant_pass_issuance']);
    $o->applicant_pass_renewal = escape($_POST['applicant_pass_renewal']);
    $o->applicant_language = escape($_POST['applicant_language']);
    $o->current_tab = escape($_REQUEST['current_tab']);
    
    $o->applicant_marital_status = escape($_POST['applicant_marital_status']);
    $o->applicant_religion = escape($_POST['applicant_religion']);
    $o->applicant_sex = escape($_POST['applicant_sex']);
    $o->applicant_card = escape($_POST['applicant_card']);
    $o->applicant_iscpf = escape($_POST['applicant_iscpf']);
    $o->applicant_cpf_account_no = escape($_POST['applicant_cpf_account_no']);
    $o->applicant_income_taxid = escape($_POST['applicant_income_taxid']);
    $o->applicant_race = escape($_POST['applicant_race']);
    $o->applicant_cpf_first_half = escape($_POST['applicant_cpf_first_half']);
    $o->applicant_sld_opt_out = escape($_POST['applicant_sld_opt_out']);
    $o->applicant_fund_opt_out = escape($_POST['applicant_fund_opt_out']);
    $o->applicant_fund_first_half = escape($_POST['applicant_fund_first_half']);


    $o->applicant_emer_contact = escape($_POST['applicant_emer_contact']);
    $o->applicant_emer_relation = escape($_POST['applicant_emer_relation']);
    $o->applicant_emer_phone1 = escape($_POST['applicant_emer_phone1']);
    $o->applicant_emer_phone2 = escape($_POST['applicant_emer_phone2']);
    $o->applicant_emer_address = escape($_POST['applicant_emer_address']);
    $o->applicant_emer_remarks = escape($_POST['applicant_emer_remarks']);
    $o->applicant_probation = escape($_POST['applicant_probation']);
    $o->applicant_prdate = escape($_POST['applicant_prdate']);
    $o->applicant_resignreason = escape($_POST['applicant_resignreason']);
    $o->applicant_paymode = escape($_POST['applicant_paymode']);
    $o->applicant_bank_acc_name = escape($_POST['applicant_bank_acc_name']);
    
    $o->applicant_work_permit = escape($_POST['applicant_work_permit']);
    $o->applicant_work_permit_date_arrival = escape($_POST['applicant_work_permit_date_arrival']);
    $o->applicant_work_permit_application_date = escape($_POST['applicant_work_permit_application_date']);
    $o->applicant_numberofchildren = escape($_POST['applicant_numberofchildren']);
    $o->applicant_isovertime = escape($_POST['applicant_isovertime']);
    $o->applicant_work_time_start = escape($_POST['applicant_work_time_start']);
    $o->applicant_work_time_end = escape($_POST['applicant_work_time_end']);
    
   
    $o->referee_name1 = escape($_POST['referee_name1']);
    $o->referee_occupation1 = escape($_POST['referee_occupation1']);
    $o->referee_year_know1 = escape($_POST['referee_year_know1']);
    $o->referee_contact_no1 = escape($_POST['referee_contact_no1']);
    $o->referee_name2 = escape($_POST['referee_name2']);
    $o->referee_occupation2 = escape($_POST['referee_occupation2']);
    $o->referee_year_know2 = escape($_POST['referee_year_know2']);
    $o->referee_contact_no2 = escape($_POST['referee_contact_no2']);
    $o->referee_present_employer = escape($_POST['referee_present_employer']);
    $o->referee_previous_employer = escape($_POST['referee_previous_employer']);
    
    $o->declaration_bankrupt = escape($_POST['declaration_bankrupt']);
    $o->declaration_physical = escape($_POST['declaration_physical']);
    $o->declaration_lt_medical = escape($_POST['declaration_lt_medical']);
    $o->declaration_law = escape($_POST['declaration_law']);
    $o->declaration_warning = escape($_POST['declaration_warning']);
    $o->declaration_applied = escape($_POST['declaration_applied']);
    $o->tc_date = escape($_POST['tc_date']);
    $o->db_specify = escape($_POST['db_specify']);
    $o->dp_specify = escape($_POST['dp_specify']);
    $o->dltm_specify = escape($_POST['dltm_specify']);
    $o->dl_specify = escape($_POST['dl_specify']);
    $o->dw_specify = escape($_POST['dw_specify']);
    $o->da_specify = escape($_POST['da_specify']);
                        
    $o->appl_n_level = escape($_POST['appl_n_level']);
    $o->appl_o_level = escape($_POST['appl_o_level']);
    $o->appl_a_level = escape($_POST['appl_a_level']);
    $o->appl_diploma = escape($_POST['appl_diploma']);
    $o->appl_degree = escape($_POST['appl_degree']);
    $o->appl_other_qualification = escape($_POST['appl_other_qualification']);    
    $o->appl_written = escape($_POST['appl_written']);
    $o->appl_spoken = escape($_POST['appl_spoken']);
    
    $o->overall_impression = escape($_POST['overall_impression']);
    $o->communication_skills = escape($_POST['communication_skills']);
    $o->other_comments = escape($_POST['other_comments']);
    $o->official_consultant = escape($_POST['official_consultant']);
    $o->official_date = escape($_POST['official_date']);
    $o->official_time = escape($_POST['official_time']);
    
    //Salary
    $o->applicantsalary_date = escape($_POST['applicantsalary_date']);
    $o->applicantsalary_amount = str_replace(",", "",escape($_POST['applicantsalary_amount']));
    $o->applicantsalary_overtime = str_replace(",", "",escape($_POST['applicantsalary_overtime']));
    $o->applicantsalary_hourly = str_replace(",", "",escape($_POST['applicantsalary_hourly']));
    $o->applicantsalary_workday = escape($_POST['applicantsalary_workday']);
    $o->applicantsalary_id = escape($_REQUEST['applicantsalary_id']);
    $o->applicantsalary_remark = escape($_POST['applicantsalary_remark']);
    
    //Leave Or claims Approved
    $o->applicant_leave_approved1 = str_replace(",", "",escape($_POST['applicant_leave_approved1']));
    $o->applicant_leave_approved2 = str_replace(",", "",escape($_POST['applicant_leave_approved2']));
    $o->applicant_leave_approved3 = str_replace(",", "",escape($_POST['applicant_leave_approved3']));
    $o->applicant_claims_approved1 = escape($_POST['applicant_claims_approved1']);
    $o->applicant_claims_approved2 = escape($_REQUEST['applicant_claims_approved2']);
    $o->applicant_claims_approved3 = escape($_POST['applicant_claims_approved3']);

    //Family
    $o->family_id = escape($_REQUEST['family_id']);
    $o->family_name = escape($_POST['family_name']);
    $o->family_relationship = escape($_POST['family_relationship']);
    $o->family_contact_no = escape($_POST['family_contact_no']);
    $o->family_age = escape($_POST['family_age']);
    $o->family_occupation = escape($_POST['family_occupation']);
    $o->applicant_family_id = escape($_POST['applicant_family_id']);

    //follow up
    $o->assign_to = escape($_POST['assign_to']);
    $o->follow_type = escape($_POST['follow_type']);
    $o->interview_time = escape($_POST['interview_time']);
    $o->interview_date = escape($_POST['interview_date']);
    $o->interview_company = escape($_REQUEST['interview_company']);
    
    $o->f_company = escape($_REQUEST['company']);
    $o->p_offer = escape($_POST['p_offer']);
    $o->f_salary = escape($_POST['salary']);
    $o->s_date = escape($_POST['s_date']);
    
    $o->fol_job_title = escape($_POST['fol_job_title']);
    $o->fol_assign_expiry_date = escape($_POST['fol_assign_expiry_date']);
    $o->fol_department = escape($_POST['fol_department']);
    $o->fol_payroll_empl = escape($_POST['fol_payroll_empl']);
    $o->interview_by = escape($_POST['interview_by']);
    $o->available_date = escape($_POST['available_date']);
    $o->expected_salary = escape($_POST['expected_salary']);
    $o->offer_salary = escape($_POST['offer_salary']);
    $o->admin_fee = escape($_POST['admin_fee']);
    $o->position_offer = escape($_POST['position_offer']);
    $o->applicant_attend = escape($_POST['applicant_attend']);
    $o->received_offer = escape($_POST['received_offer']);
    $o->followup_comments = escape($_POST['followup_comments']);
    $o->assign_manager = escape($_POST['assign_manager']);
    $o->follow_notice = escape($_POST['follow_notice']);
    $o->follow_id = escape($_REQUEST['follow_id']);
    $o->applfollow_id = escape($_REQUEST['applfollow_id']);
    $o->fol_probation_period = escape($_POST['probation_period']);
    $o->fol_job_type = escape($_POST['job_type']);
    $o->fol_approved = escape($_POST['fol_approved']);
    $o->not_suitable = escape($_POST['not_suitable']);
    
    $o->previous_company = escape($_POST['previous_company']);
    $o->previous_position = escape($_POST['previous_position']);
    $o->previous_salary = escape($_POST['previous_salary']);
    $o->reason_leave = escape($_POST['reason_leave']);
    $o->duration_conform = escape($_POST['duration_conform']);
    $o->previous_start_date = escape($_POST['previous_start_date']);
    $o->previous_end_date = escape($_POST['previous_end_date']);
    $o->responsibilities = escape($_POST['responsibilities']);
    $o->exp_id = escape($_REQUEST['exp_id']);
    $o->previous_appl_id = escape($_REQUEST['previous_appl_id']);
    $o->file_id = escape($_REQUEST['file_id']);
    $o->file_type = escape($_POST['file_type']);
    
    $o->resume_id = escape($_REQUEST['resume_id']);
    $o->file_name = escape($_REQUEST['file_name']);
    
    $o->applleave_leavetype = $_POST['applleave_leavetype'];
    $o->applleave_id = $_POST['applleave_id'];
    $o->applleave_days = $_POST['applleave_days'];
    $o->applleave_entitled = $_POST['applleave_entitled'];
    $o->applleave_disabled = $_POST['applleave_disabled'];
    
//    var_dump($_FILES);die;
    switch ($action) {
        case "create":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                if($o->create()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Create success.";
                    rediectUrl("applicant.php",getSystemMsg(1,'Create data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("applicant.php",getSystemMsg(0,'Create data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("applicant.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "update":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                if($o->update()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Update success.";
                    rediectUrl("applicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=$o->current_tab",getSystemMsg(1,'Update data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Update fail.";
                    rediectUrl("applicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=$o->current_tab",getSystemMsg(0,'Update data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("applicant.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;  
        case "edit":
            if(($o->fetchApplicantDetail(" AND applicant_id = '$o->applicant_id'","","",1)) && ($o->applicant_id > 0)){
//                $o->getEditInputForm("update");
                $o->getInputForms("update");
            }else{
               rediectUrl("applicant.php",getSystemMsg(0,'Fetch Data Fail.'));
            }
            exit();
        case "view":
            if($o->fetchApplicantDetail(" AND applicant_id = '$o->applicant_id'","","",1)){
                $o->getInputForms("view");
            }else{
               rediectUrl("applicant.php",getSystemMsg(0,'Fetch Data Fail.'));
            }
            exit();
            break; 
        case "delete":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                if($o->delete()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Delete success.";
                    rediectUrl("applicant.php",getSystemMsg(1,'Delete data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Delete fail.";
                    rediectUrl("applicant.php",getSystemMsg(0,'Delete data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Delete fail.";
                    rediectUrl("applicant.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
        case "createForm":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                $o->getInputForms('create');
//                  $o->testing('create');
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
        case "validate_email":
            $t = $gf->checkDuplicate("db_applicant",'applicant_login_email',$o->applicant_login_email,'applicant_id',$o->applicant_id);
            if($t > 0){
                echo "false";
            }else{
                echo "true";
            }
            exit();
            break; 
        case "saveSalary":
            if($o->applicantsalary_id > 0){
                if($o->updateSalary()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }else{
                if($o->createSalary()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }
            exit();
            break;
        case "deletesalary":
            if($o->deleteSalary()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("applicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=$o->current_tab",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("applicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=$o->current_tab",getSystemMsg(0,'Delete data fail'));
            }
            exit();
            break; 
        case "getBankCode":
            $bank_no = $o->getBankCode();
            echo json_encode(array('bank_no'=>$bank_no));
            exit();
            break;
        case "deleteFamily": 
            $o->deleteFamily();
            rediectUrl("applicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=family",getSystemMsg(1,'Delete data successfully'));
            exit();
            break;
        case "saveFamily":
            if($o->family_id > 0){
                if($o->updateFamily()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }else{
                if($o->addFamily()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }
           
            exit();
            break;
        case "deleteFollow": 
            $o->deleteFollowUp();
            rediectUrl("applicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=followup",getSystemMsg(1,'Delete data successfully'));
            exit();
            break;
        case "saveFollowup":
            if($o->follow_id > 0){
                if($o->updateFollowUp()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }else{
                if($o->createFollowUp()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }
            exit();
            break;
        case "deleteWorkExperience": 
            $o->deleteWorkExperience();
            rediectUrl("applicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=WorkingExperience",getSystemMsg(1,'Delete data successfully'));
            exit();
            break;
        case "saveWorkExperience":
            if($o->exp_id > 0){
                if($o->updateWorkExperience()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }else{
                if($o->createWorkExperience()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }
            exit();
            break;
        case "getRemarkDetail":
            $remarks_array = $o->getRemarks();
            echo json_encode(array('aRemarks'=>$remarks_array));
            exit();
            break;
        case "updateNotification":
            $o->updateNotification();
            exit();
            break;   
        case "uploadResume":
            $o->saveResume();
            rediectUrl("applicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=ResumeFile",getSystemMsg(1,'Upload File successfully'));
            exit();
            break;
        case "deleteResume":
            $o->deleteResume();
            rediectUrl("applicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=ResumeFile",getSystemMsg(1,'Delete data successfully'));
            exit();
            break;
        case "uploadFile":
            $o->saveFile();
            rediectUrl("applicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=UploadFile",getSystemMsg(1,'Upload File successfully'));
            exit();
            break;
        case "deleteFile":
            $o->deleteFile();
            rediectUrl("applicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=UploadFile",getSystemMsg(1,'Delete data successfully'));
            exit();
            break;
        case "saveApplicantLeave":
            if($o->applleave_id[0] > 0){
                for($i=0;$i<sizeof($o->applleave_days);$i++){
                    $value = 0;
                    if($o->applleave_disabled[$o->applleave_leavetype[$i]] == 1){
                        $value = 1;
                    }
                    $o->updateLeave(escape($o->applleave_days[$i]),escape($o->applleave_id[$i]),$value,escape($o->applleave_entitled[$i]));
                }
            }else{
                for($i=0;$i<sizeof($o->applleave_days);$i++){
                    $value = 0;
                    if($o->applleave_disabled[$o->applleave_leavetype[$i]] == 1){
                        $value = 1;
                    }                    
                    $o->createLeave(escape($o->applleave_leavetype[$i]),escape($o->applleave_days[$i]),escape($o->applleave_disabled[$i]),escape($o->applleave_entitled[$i]));
                }
            }
            exit();
            break;        
        case "printPDF":
            $o->printPDF();
            exit();
            break;
        case "getDepartment":
            $department_array = $o->getDepartment();
            $job_array = $o->getJobTitle();
            echo json_encode(array('department'=>$department_array, 'job'=>$job_array));
            exit();
            break; 
        default:
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'access')){
                $o->getListing();
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
    }


