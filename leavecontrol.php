<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Leavecontrol.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Leavecontrol();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;
    
    $action = escape($_REQUEST['action']);
    $o->leavecontrol_id = escape($_REQUEST['leavecontrol_id']);
    $o->leavecontrol_department = escape($_POST['leavecontrol_department']);
    $o->leavecontrol_fromyear = escape($_POST['leavecontrol_fromyear']);
    $o->leavecontrol_toyear = escape($_POST['leavecontrol_toyear']);
    $o->leavecontrol_leave_type = $_POST['leavecontrol_leave_type'];
    $o->leavecontrol_empl_id = $_POST['leavecontrol_empl_id'];
    $o->leavecontrol_bringoverday = $_POST['leavecontrol_bringoverday'];

    $o->leavecontrol_fromyear = "2016";
    $o->leavecontrol_toyear = "2017";
    switch ($action) {
        case "create":
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                $o->create(); 
                rediectUrl("leavecontrol.php",getSystemMsg(1,'Create data'));
            exit();
            break; 
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("leavecontrol.php?action=edit&leavecontrol_id=$o->leavecontrol_id",getSystemMsg(1,'Update data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("leavecontrol.php?action=edit&leavecontrol_id=$o->leavecontrol_id",getSystemMsg(0,'Update data'));
            }
            exit();
            break;    
        case "edit":
            if($o->fetchLeavecontrolDetail(" AND leavecontrol_id = '$o->leavecontrol_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("leavecontrol.php",getSystemMsg(0,'Fetch Data'));
            }
            exit();
            break;
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("leavecontrol.php",getSystemMsg(1,'Delete data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("leavecontrol.php?action=edit&leavecontrol_id=$o->leavecontrol_id",getSystemMsg(0,'Delete data'));
            }
            exit();
            break; 
        case "validate_leavecontrol":
            if($o->validateLeavecontrol($o->leavecontrol_code,$o->leavecontrol_id)){
                echo "true";
            }else{
                echo "false";
            }
            exit();
            break;
        case "previewLeaveControl":
            $o->previewLeaveControl();
            exit();
            break;
        case "createForm":
            $o->getInputForm('create');
            exit();
            break;
        default: 
            $o->getListing();
            exit();
            break;
    }


