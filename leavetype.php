<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Leavetype.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Leavetype();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;
    
    $action = escape($_REQUEST['action']);
    $o->leavetype_id = escape($_REQUEST['leavetype_id']);
    $o->leavetype_code = escape($_POST['leavetype_code']);
    $o->leavetype_desc = escape($_POST['leavetype_desc']);
    $o->leavetype_seqno = escape($_POST['leavetype_seqno']);
    $o->leavetype_status = escape($_POST['leavetype_status']);
    $o->leavetype_default = escape($_POST['leavetype_default']);
    $o->leavetype_bringover = escape($_POST['leavetype_bringover']);
    
    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("leavetype.php?action=edit&leavetype_id=$o->leavetype_id",getSystemMsg(1,'Create data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("leavetype.php",getSystemMsg(0,'Create data'));
            }
            exit();
            break; 
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("leavetype.php?action=edit&leavetype_id=$o->leavetype_id",getSystemMsg(1,'Update data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("leavetype.php?action=edit&leavetype_id=$o->leavetype_id",getSystemMsg(0,'Update data'));
            }
            exit();
            break;    
        case "edit":
            if($o->fetchLeavetypeDetail(" AND leavetype_id = '$o->leavetype_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("leavetype.php",getSystemMsg(0,'Fetch Data'));
            }
            exit();
            break;
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("leavetype.php",getSystemMsg(1,'Delete data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("leavetype.php?action=edit&leavetype_id=$o->leavetype_id",getSystemMsg(0,'Delete data'));
            }
            exit();
            break; 
        case "validate_leavetype":
            if($o->validateLeavetype($o->leavetype_code,$o->leavetype_id)){
                echo "true";
            }else{
                echo "false";
            }
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


