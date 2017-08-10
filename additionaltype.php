<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Additionaltype.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Additionaltype();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;
    
    $action = escape($_REQUEST['action']);
    $o->additionaltype_id = escape($_REQUEST['additionaltype_id']);
    $o->additionaltype_code = escape($_POST['additionaltype_code']);
    $o->additionaltype_desc = escape($_POST['additionaltype_desc']);
    $o->additionaltype_seqno = escape($_POST['additionaltype_seqno']);
    $o->additionaltype_status = escape($_POST['additionaltype_status']);

    
    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("additionaltype.php?action=edit&additionaltype_id=$o->additionaltype_id",getSystemMsg(1,'Create data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("additionaltype.php",getSystemMsg(0,'Create data'));
            }
            exit();
            break; 
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("additionaltype.php?action=edit&additionaltype_id=$o->additionaltype_id",getSystemMsg(1,'Update data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("additionaltype.php?action=edit&additionaltype_id=$o->additionaltype_id",getSystemMsg(0,'Update data'));
            }
            exit();
            break;    
        case "edit":
            if($o->fetchAdditionaltypeDetail(" AND additionaltype_id = '$o->additionaltype_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("additionaltype.php",getSystemMsg(0,'Fetch Data'));
            }
            exit();
            break;
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("additionaltype.php",getSystemMsg(1,'Delete data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("additionaltype.php?action=edit&additionaltype_id=$o->additionaltype_id",getSystemMsg(0,'Delete data'));
            }
            exit();
            break; 
        case "validate_additionaltype":
            if($o->validateAdditionaltype($o->additionaltype_code,$o->additionaltype_id)){
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


