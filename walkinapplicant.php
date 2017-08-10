<?php


    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/WalkInApplicant.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    include_once 'class/fpdf/fpdf.php';
    include_once 'class/fpdi/fpdi.php';
    
    $o = new WalkInApplicant();
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
    

    //Family
    $o->family_id = escape($_REQUEST['family_id']);
    $o->family_name = escape($_POST['family_name']);
    $o->family_relationship = escape($_POST['family_relationship']);
    $o->family_contact_no = escape($_POST['family_contact_no']);
    $o->family_age = escape($_POST['family_age']);
    $o->family_occupation = escape($_POST['family_occupation']);
    $o->applicant_family_id = escape($_POST['applicant_family_id']);



    
    switch ($action) {
        case "create":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                if($o->create()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Create success.";
                    rediectUrl("walkinapplicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=family",getSystemMsg(1,'Create data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("walkinapplicant.php",getSystemMsg(0,'Create data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("walkinapplicant.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "update":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                if($o->update()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Update success.";
                    rediectUrl("walkinapplicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=$o->current_tab",getSystemMsg(1,'Update data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Update fail.";
                    rediectUrl("walkinapplicant.php?action=edit&applicant_id=$o->applicant_id&current_tab=$o->current_tab",getSystemMsg(0,'Update data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("walkinapplicant.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;  
        case "edit":
            if(($o->fetchApplicantDetail(" AND applicant_id = '$o->applicant_id'","","",1)) && ($o->applicant_id > 0)){
                $o->getInputForms("update");
            }else{
               rediectUrl("walkinapplicant.php",getSystemMsg(0,'Fetch Data Fail.'));
            }
            exit();
        case "view":
            if($o->fetchApplicantDetail(" AND applicant_id = '$o->applicant_id'","","",1)){
                $o->getInputForms("view");
            }else{
               rediectUrl("walkinapplicant.php",getSystemMsg(0,'Fetch Data Fail.'));
            }
            exit();
            break; 
        case "delete":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                if($o->delete()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Delete success.";
                    rediectUrl("walkinapplicant.php",getSystemMsg(1,'Delete data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Delete fail.";
                    rediectUrl("walkinapplicant.php",getSystemMsg(0,'Delete data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("walkinapplicant.php",getSystemMsg(0,'Permission Denied'));
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
        case "getBankCode":
            $bank_no = $o->getBankCode();
            echo json_encode(array('bank_no'=>$bank_no));
            exit();
            break;
        case "deleteFamily": 
            $o->deleteFamily();
            rediectUrl("walkinapplicant.php?action=create&applicant_id=$o->applicant_id&current_tab=family",getSystemMsg(1,'Delete data successfully'));
            exit();
            break;
        case "saveFamily":
            
            if($o->appfamily_id > 0){
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
        case "printPDF":
            $o->printPDF();
            exit();
            break;        
        default:
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                $o->getInputForms('create');
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
    }


