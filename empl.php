<?php


    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Empl.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    
    $o = new Empl();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->empl_id = escape($_REQUEST['empl_id']);
    if($action == 'update'){
        $o->fetchEmplDetail(" AND empl_id = '$o->empl_id'","","",1);
        $o->empl_oldpassword = $o->empl_login_password;
    }
   
    $o->empl_name = escape($_POST['empl_name']);
    $o->empl_nric = escape($_POST['empl_nric']);
    $o->empl_tel = escape($_POST['empl_tel']);
    $o->empl_mobile = escape($_POST['empl_mobile']);
    $o->empl_birthday = escape($_POST['empl_birthday']);
    $o->empl_joindate = escape($_POST['empl_joindate']);
    $o->empl_group = escape($_POST['empl_group']);
    $o->empl_manager = escape($_POST['empl_manager']);
    $o->empl_postal_code = escape($_POST['empl_postal_code']);
    $o->empl_unit_no = escape($_POST['empl_unit_no']);
    $o->empl_address = escape($_POST['empl_address']);
    $o->empl_postal_code2 = escape($_POST['empl_postal_code2']);
    $o->empl_unit_no2 = escape($_POST['empl_unit_no2']);
    $o->empl_address2 = escape($_POST['empl_address2']);    
    $o->empl_remark = escape($_POST['empl_remark']);
    $o->image_input = $_FILES['image_input'];
    $o->empl_seqno = escape($_POST['empl_seqno']);
    $o->empl_status = escape($_POST['empl_status']);
    $o->empl_email = escape($_POST['empl_email']);
    $o->empl_outlet = escape($_POST['empl_outlet']);
    $o->empl_login_email = escape($_POST['empl_login_email']);
    $o->empl_login_password = escape($_POST['empl_login_password']);
    $o->empl_department = escape($_POST['empl_department']);
    $o->empl_bank = escape($_POST['empl_bank']);
    $o->empl_bank_acc_no = escape($_POST['empl_bank_acc_no']);
    $o->empl_nationality = escape($_POST['empl_nationality']);
    $o->empl_emplpass = escape($_POST['empl_emplpass']);
    $o->empl_resigndate = escape($_POST['empl_resigndate']);
    $o->empl_confirmationdate = escape($_POST['empl_confirmationdate']);
    $o->emplleave_leavetype = $_POST['emplleave_leavetype'];
    $o->emplleave_id = $_POST['emplleave_id'];
    $o->emplleave_days = $_POST['emplleave_days'];
    $o->emplleave_disabled = $_POST['emplleave_disabled'];
    $o->emplleave_entitled = $_POST['emplleave_entitled'];

    $o->empl_levy_amt = escape($_POST['empl_levy_amt']);
    $o->empl_pass_issuance = escape($_REQUEST['empl_pass_issuance']);
    $o->empl_pass_renewal = escape($_POST['empl_pass_renewal']);
    $o->empl_language = escape($_POST['empl_language']);
    $o->current_tab = escape($_REQUEST['current_tab']);
    
    $o->empl_marital_status = escape($_POST['empl_marital_status']);
    $o->empl_religion = escape($_POST['empl_religion']);
    $o->empl_sex = escape($_POST['empl_sex']);
    $o->empl_card = escape($_POST['empl_card']);
    $o->empl_iscpf = escape($_POST['empl_iscpf']);
    $o->empl_cpf_account_no = escape($_POST['empl_cpf_account_no']);
    $o->empl_income_taxid = escape($_POST['empl_income_taxid']);
    $o->empl_race = escape($_POST['empl_race']);
    $o->empl_cpf_first_half = escape($_POST['empl_cpf_first_half']);
    $o->empl_sld_opt_out = escape($_POST['empl_sld_opt_out']);
    $o->empl_fund_opt_out = escape($_POST['empl_fund_opt_out']);
    $o->empl_fund_first_half = escape($_POST['empl_fund_first_half']);

    $o->empl_emer_contact = escape($_POST['empl_emer_contact']);
    $o->empl_emer_relation = escape($_POST['empl_emer_relation']);
    $o->empl_emer_phone1 = escape($_POST['empl_emer_phone1']);
    $o->empl_emer_phone2 = escape($_POST['empl_emer_phone2']);
    $o->empl_emer_address = escape($_POST['empl_emer_address']);
    $o->empl_emer_remarks = escape($_POST['empl_emer_remarks']);
    $o->empl_probation = escape($_POST['empl_probation']);
    $o->empl_prdate = escape($_POST['empl_prdate']);
    $o->empl_resignreason = escape($_POST['empl_resignreason']);
    $o->empl_paymode = escape($_POST['empl_paymode']);
    $o->empl_bank_acc_name = escape($_POST['empl_bank_acc_name']);
    
    $o->empl_work_permit = escape($_POST['empl_work_permit']);
    $o->empl_work_permit_date_arrival = escape($_POST['empl_work_permit_date_arrival']);
    $o->empl_work_permit_application_date = escape($_POST['empl_work_permit_application_date']);
    $o->empl_numberofchildren = escape($_POST['empl_numberofchildren']);
    $o->empl_isovertime = escape($_POST['empl_isovertime']);
    $o->empl_work_time_start = escape($_POST['empl_work_time_start']);
    $o->empl_work_time_end = escape($_POST['empl_work_time_end']);
    
    
    //Salary
    $o->emplsalary_date = escape($_POST['emplsalary_date']);
    $o->emplsalary_amount = str_replace(",", "",escape($_POST['emplsalary_amount']));
    $o->emplsalary_overtime = str_replace(",", "",escape($_POST['emplsalary_overtime']));
    $o->emplsalary_hourly = str_replace(",", "",escape($_POST['emplsalary_hourly']));
    $o->emplsalary_workday = escape($_POST['emplsalary_workday']);
    $o->emplsalary_id = escape($_REQUEST['emplsalary_id']);
    $o->emplsalary_remark = escape($_POST['emplsalary_remark']);
    
    //Leave Or claims Approved
    $o->empl_leave_approved1 = str_replace(",", "",escape($_POST['empl_leave_approved1']));
    $o->empl_leave_approved2 = str_replace(",", "",escape($_POST['empl_leave_approved2']));
    $o->empl_leave_approved3 = str_replace(",", "",escape($_POST['empl_leave_approved3']));
    $o->empl_claims_approved1 = escape($_POST['empl_claims_approved1']);
    $o->empl_claims_approved2 = escape($_REQUEST['empl_claims_approved2']);
    $o->empl_claims_approved3 = escape($_POST['empl_claims_approved3']);
    $o->checkAccess($action);
    switch ($action) {
        case "create":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                if($o->create()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Create success.";
                    rediectUrl("empl.php?action=edit&empl_id=$o->empl_id&current_tab=Salary Info",getSystemMsg(1,'Create data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("empl.php",getSystemMsg(0,'Create data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("empl.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "update":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                if($o->update()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Update success.";
                    rediectUrl("empl.php?action=edit&empl_id=$o->empl_id&current_tab=$o->current_tab",getSystemMsg(1,'Update data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Update fail.";
                    rediectUrl("empl.php?action=edit&empl_id=$o->empl_id&current_tab=$o->current_tab",getSystemMsg(0,'Update data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("empl.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;  
        case "edit":
            if(($o->fetchEmplDetail(" AND empl_id = '$o->empl_id'","","",1)) && ($o->empl_id > 0)){
                $o->getInputForm("update");
            }else{
               rediectUrl("empl.php",getSystemMsg(0,'Fetch Data Fail.'));
            }
            exit();
        case "view":
            if($o->fetchEmplDetail(" AND empl_id = '$o->empl_id'","","",1)){
                $o->getInputForm("view");
            }else{
               rediectUrl("empl.php",getSystemMsg(0,'Fetch Data Fail.'));
            }
            exit();
            break; 
        case "delete":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                if($o->delete()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Delete success.";
                    rediectUrl("empl.php",getSystemMsg(1,'Delete data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Delete fail.";
                    rediectUrl("empl.php",getSystemMsg(0,'Delete data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("empl.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
        case "createForm":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                $o->getInputForm('create');
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
        case "validate_email":
            $t = $gf->checkDuplicate("db_empl",'empl_login_email',$o->empl_login_email,'empl_id',$o->empl_id);
            if($t > 0){
                echo "false";
            }else{
                echo "true";
            }
            exit();
            break; 
        case "saveSalary":
            if($o->emplsalary_id > 0){
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
                rediectUrl("empl.php?action=edit&empl_id=$o->empl_id&current_tab=$o->current_tab",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("empl.php?action=edit&empl_id=$o->empl_id&current_tab=$o->current_tab",getSystemMsg(0,'Delete data fail'));
            }
            exit();
            break; 
        case "getBankCode":
            $bank_no = $o->getBankCode();
            echo json_encode(array('bank_no'=>$bank_no));
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


