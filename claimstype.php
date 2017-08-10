<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Claimstype.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Claimstype();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;
    
    $action = escape($_REQUEST['action']);
    $o->claimstype_id = escape($_REQUEST['claimstype_id']);
    $o->claimstype_code = escape($_POST['claimstype_code']);
    $o->claimstype_desc = escape($_POST['claimstype_desc']);
    $o->claimstype_seqno = escape($_POST['claimstype_seqno']);
    $o->claimstype_status = escape($_POST['claimstype_status']);
    $o->claimstype_maxamt = escape($_POST['claimstype_maxamt']);


    
    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("claimstype.php?action=edit&claimstype_id=$o->claimstype_id",getSystemMsg(1,'Create data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("claimstype.php",getSystemMsg(0,'Create data'));
            }
            exit();
            break; 
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("claimstype.php?action=edit&claimstype_id=$o->claimstype_id",getSystemMsg(1,'Update data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("claimstype.php?action=edit&claimstype_id=$o->claimstype_id",getSystemMsg(0,'Update data'));
            }
            exit();
            break;    
        case "edit":
            if($o->fetchClaimstypeDetail(" AND claimstype_id = '$o->claimstype_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("claimstype.php",getSystemMsg(0,'Fetch Data'));
            }
            exit();
            break;
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("claimstype.php",getSystemMsg(1,'Delete data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("claimstype.php?action=edit&claimstype_id=$o->claimstype_id",getSystemMsg(0,'Delete data'));
            }
            exit();
            break; 
        case "validate_claimstype":
            if($o->validateClaimstype($o->claimstype_code,$o->claimstype_id)){
                echo "true";
            }else{
                echo "false";
            }
            exit();
            break;
        case "checkClaimsLimit":
             if($o->fetchClaimstypeDetail(" AND claimstype_id = '$o->claimstype_id'","","",1)){
                echo json_encode(array('status'=>1,'claims_limit'=>$o->claimstype_maxamt));
            }else{
                echo json_encode(array('status'=>0,'claims_limit'=>0));
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


