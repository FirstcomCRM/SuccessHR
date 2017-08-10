<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Payroll.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Payroll();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;
    $o->menu_id = 5;

    $action = escape($_REQUEST['action']);
    $o->payroll_id = escape($_REQUEST['payroll_id']);
    $o->payroll_outlet = escape($_POST['payroll_outlet']);
    $o->payroll_department = escape($_POST['payroll_department']);
    $o->payroll_salary_date = escape($_POST['payroll_salary_date']);
    $o->payroll_startdate = escape($_REQUEST['payroll_startdate']);
    $o->payroll_enddate = escape($_REQUEST['payroll_enddate']);
    $o->action = escape($_POST['action']);
    $o->empl_id = escape($_REQUEST['empl_id']);
    $o->f = escape($_REQUEST['f']);
    
    $o->selfview = escape($_REQUEST['selfview']);
    $o->varify_password = escape($_POST['varify_password']);
    
    $o->payroll_array = $_POST['payroll_array'];
    $o->filter_month_date = escape($_GET['filter_month_date']);

    $o->payroll_total_working_days = escape($_REQUEST['payroll_total_working_days']);
    switch ($action) {
        case "create":
            if(getWindowPermission($o->menu_id,'create')){
                if($o->create()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Create success.";
                    rediectUrl("payroll.php?action=edit&payroll_id=$o->payroll_id",getSystemMsg(1,'Create data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("payroll.php",getSystemMsg(0,'Create data fail'));
                }
            }
            exit();
            break;
        case "update":
            if(getWindowPermission($o->menu_id,'update')){
                if($o->update()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Update success.";
                    rediectUrl("payroll.php?action=edit&payroll_id=$o->payroll_id",getSystemMsg(1,'Update data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Update fail.";
                    rediectUrl("payroll.php?action=edit&payroll_id=$o->payroll_id",getSystemMsg(0,'Update data fail'));
                }
            }
            exit();
            break;  
        case "edit":
            if(getWindowPermission($o->menu_id,'update')){
                if(!$o->fetchPayrollDetail(" AND payroll_id = '$o->payroll_id'","","",1)){
                    rediectUrl("payroll.php",getSystemMsg(0,'Fetch Data fail'));
                    exit();
                }else{
                    $o->getInputForm("update");
                }
            }
            exit();
            break;  
        case "delete":
            if(getWindowPermission($o->menu_id,'delete')){
                if($o->delete()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Delete success.";
                    rediectUrl("payroll.php",getSystemMsg(1,'Delete data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Delete fail.";
                    rediectUrl("payroll.php",getSystemMsg(0,'Delete data fail'));
                }
            }
            exit();
            break;   
        case "createForm":
            if(getWindowPermission($o->menu_id,'create')){
                $o->getInputForm('create');
            }else{
                permissionLog();
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
        case "previewPayslip":
//            if(!in_array($_SESSION['empl_group'],$master_group)){
//                if($o->empl_id != $_SESSION['empl_id']){
//                    permissionLog();
//                    rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
//                }
//            }
            if($o->previewPayslip()){
                echo json_encode(array('status'=>1));
            }else{
                echo json_encode(array('status'=>0));
            }
            exit();
            break;  
        case "previewPayslipDetail":
            
            if(!in_array($_SESSION['empl_group'],$master_group)){
                if($o->empl_id != $_SESSION['empl_id']){
                    permissionLog();
                    rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
                    exit();
                }
            }
            
            if($o->payroll_id > 0){
                if(!$o->fetchPayrollDetail(" AND payroll_id = '$o->payroll_id'","","",1)){
                    rediectUrl("payroll.php",getSystemMsg(0,'Fetch Data fail'));
                    exit();
                }
            }
            if($_SESSION['empl_varify_password'] == 1){
                $o->previewPayslipDetail();
            }else{
                rediectUrl("payroll.php",getSystemMsg(0,'Password verification fail.'));
            }
            exit();
            break; 
        case "updateSelfView":
            if($o->selfview == 1){
                $o->UpdateSelfView();
            }
            exit();
            break;  
        case "varify_password":
        case "varify_password_bypass":
            if($o->empl_id <= 0){
                rediectUrl("payroll.php",getSystemMsg(0,'Employee data not found.'));
                exit();
            }
            if($o->action != "varify_password_bypass"){
                if($o->payroll_id <= 0){
                    rediectUrl("payroll.php",getSystemMsg(0,'Fetch Data fail'));
                    exit();
                }
            }
            if($o->varify_password == ""){
                rediectUrl("payroll.php",getSystemMsg(0,'Password cannot be empty.'));
                exit();
            }
            
            if($o->getVarifyPassword()){
                if($o->selfview == 1){
                    $o->UpdateSelfView();
                }
                rediectUrl("payroll.php?action=previewPayslipDetail&empl_id=$o->empl_id&payroll_id=$o->payroll_id&payroll_startdate=" . format_date_database($o->payroll_startdate) . "&payroll_enddate=" . format_date_database($o->payroll_enddate) . "&payroll_total_working_days=$o->payroll_total_working_days",getSystemMsg(1,'Password verification successfully.'));
            }else{
                rediectUrl("payroll.php",getSystemMsg(0,'Password verification fail.'));
            }
            
            exit();
            break;  
        case "confirmedPayroll":
            $o->confirmedPayroll();
            echo json_encode(array('status'=>1));
            exit();
            break;
        case "listing":
//            if(!in_array($_SESSION['empl_group'],$master_group)){
//                $o->getStaffViewListing();
//            }else{
                $o->getListing();
//            }
            exit();
            break;
        default: 
            if($o->f == 'd'){
                unset($_SESSION['empl_varify_password']);
            }
//            if(!in_array($_SESSION['empl_group'],$master_group)){
//                $o->getStaffViewListing();
//            }else{
                $o->getListingMonth();
//                $o->getListing();
//            }
            exit();
            break;
    }


