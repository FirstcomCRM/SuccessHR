<?php
    include_once '../connect.php';
    include_once 'system.php';
    include_once '../include_function.php';
    include_once 'class/Subscribe.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Subscribe();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->subscribe_id = escape($_REQUEST['subscribe_id']);
    $o->subscribe_code = escape($_POST['subscribe_code']);
    $o->subscribe_desc = escape($_POST['subscribe_desc']);
    $o->subscribe_start_date = escape($_POST['subscribe_start_date']);
    $o->subscribe_end_date = escape($_POST['subscribe_end_date']);
    $o->subscribe_result_date = escape($_POST['subscribe_result_date']);
    $o->subscribe_tnc = escape($_POST['subscribe_tnc']);
    $o->subscribe_status = escape($_POST['subscribe_status']);
    
    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("subscribe.php?action=edit&subscribe_id=$o->subscribe_id",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("subscribe.php",getSystemMsg(0,'Create data fail'));
            }
            exit();
            break;
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("subscribe.php?action=edit&subscribe_id=$o->subscribe_id",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("subscribe.php?action=edit&subscribe_id=$o->subscribe_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;  
        case "edit":
            if($o->fetchSubscribeDetail(" AND subscribe_id = '$o->subscribe_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("subscribe.php",getSystemMsg(0,'Fetch Data fail'));
            }
            exit();
            break;  
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("subscribe.php",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("subscribe.php",getSystemMsg(0,'Delete data fail'));
            }
            exit();
            break;   
        case "emailCustomer":
            $o->emailCustomer();
            exit();
            break;
        case "createForm":
            $o->getInputForm('create');
            exit();
            break;
        case "em":
            $o->getListingEmailTemplate();
            exit();
            break;
        default: 
            $o->getListing();
            exit();
            break;
    }


