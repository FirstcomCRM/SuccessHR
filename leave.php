<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Leave.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Leave();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;
    $o->menu_id = 3;

    $action = escape($_REQUEST['action']);
    $o->leave_id = escape($_REQUEST['leave_id']);
    $o->leave_type = escape($_POST['leave_type']);
    $o->leave_duration = escape($_POST['leave_duration']);
    $o->leave_datefrom = escape($_POST['leave_datefrom']);
    $o->leave_dateto = escape($_POST['leave_dateto']);
    $o->leave_period_half = escape($_POST['leave_period_half']);
    $o->leave_period_hourly = escape($_POST['leave_period_hourly']);
    $o->leave_reason = escape($_POST['leave_reason']);
    $o->leave_status = escape($_POST['leave_status']);
    $o->submit_btn = escape($_POST['submit_btn']);
    $o->org_leave_approvalstatus = escape($_POST['org_leave_approvalstatus']);
    $o->sstatus_action = escape($_POST['sstatus_action']);
    $o->sstatus_message = escape($_POST['sstatus_message']);
    $o->image_input = $_FILES['image_input'];

    if($o->submit_btn == 'Confirm'){
        $o->leave_approvalstatus = "Pending";
    }else{
        $o->leave_approvalstatus = "Draft";
    }

    if(($o->leave_duration == "first_half") || ($o->leave_duration == "second_half")){
        $o->leave_dateto = $o->leave_datefrom;
        $o->leave_period_hourly = "";
        $o->leave_total_day = 0.5;
    }else if($o->leave_duration == "hourly"){
        $o->leave_dateto = $o->leave_datefrom;
        $o->leave_period_half = "";
        $o->leave_total_day = 0;
    }else{
        $o->leave_period_half = "";
        $o->leave_period_hourly = "";
        
        $o->leave_total_day = $o->calculateDateDifferent($o->leave_datefrom,$o->leave_dateto);
    }
//    $o->checkAccess($action);
    switch ($action) {
        case "create":
            if(getWindowPermission($o->menu_id,'create')){
                if($o->create()){
                    if(($o->leave_approvalstatus == 'Pending') || ($o->leave_approvalstatus == 'Approved')){
                        $o->email();
                    }
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Create success.";
                    rediectUrl("leave.php?action=edit&leave_id=$o->leave_id",getSystemMsg(1,'Create data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("leave.php",getSystemMsg(0,'Create data fail'));
                }
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("leave.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "update":
            if(getWindowPermission($o->menu_id,'update')){
                if($o->update()){
                    if($o->leave_approvalstatus == 'Pending'){
                        $o->email();
                    }
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Update success.";
                    rediectUrl("leave.php?action=edit&leave_id=$o->leave_id",getSystemMsg(1,'Update data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Update fail.";
                    rediectUrl("leave.php?action=edit&leave_id=$o->leave_id",getSystemMsg(0,'Update data fail'));
                }
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("leave.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "sstatus":
            
            if(!in_array($_SESSION['empl_group'],$master_group)){
                permissionLog();
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
                exit();
            }
            
            if($o->doSstatus()){
                rediectUrl("leave.php?action=edit&leave_id=$o->leave_id",getSystemMsg(1,'Update data successfully'));
            }else{
                rediectUrl("leave.php?action=edit&leave_id=$o->leave_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;
        case "edit":  
            if(getWindowPermission($o->menu_id,'update')){
                if(!$o->fetchLeaveDetail(" AND leave_id = '$o->leave_id'","","",1)){
                    rediectUrl("leave.php",getSystemMsg(0,'Fetch Data fail'));
                    exit();
                }else{
                    $o->getInputForm("update");
                }
            }else{
                permissionLog();
                rediectUrl("leave.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;  
        case "delete":
            if(getWindowPermission($o->menu_id,'delete')){
                if($o->delete()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Delete success.";
                    rediectUrl("leave.php",getSystemMsg(1,'Delete data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Delete fail.";
                    rediectUrl("leave.php",getSystemMsg(0,'Delete data fail'));
                }
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("leave.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
        case "createForm":
            if(getWindowPermission($o->menu_id,'create')){
                    $o->getInputForm('create');
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("leave.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
        case "validate_email":
            $t = $gf->checkDuplicate("leave",'leave_login_email',$o->leave_login_email,'leave_id',$o->leave_id);
            if($t > 0){
                echo "false";
            }else{
                echo "true";
            }
            exit();
            break;  
        case "saveNotification":
            $o->saveNotification();
            exit();
            break;              
        case "getBalance":
            $balance = $o->getBalance();
            echo json_encode(array('balance'=>$balance));
            exit();
            break; 
        default: 
            if(getWindowPermission($o->menu_id,'access')){
                $o->getListing();
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
    }


