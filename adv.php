<?php
    include_once '../connect.php';
    include_once 'system.php';
    include_once '../include_function.php';
    include_once 'class/Adv.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Adv();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->adv_id = escape($_REQUEST['adv_id']);
    $o->adv_datefrom = escape($_POST['adv_datefrom']);
    $o->adv_dateto = escape($_POST['adv_dateto']);
    $o->adv_remark = escape($_POST['adv_remark']);
    $o->adv_url = escape($_POST['adv_url']);
    $o->adv_status = escape($_POST['adv_status']);
    $o->radv_id = escape($_POST['radv_id']);
    $o->image_input = $_FILES['input_file'];
    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("adv.php?action=edit&adv_id=$o->adv_id",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("adv.php",getSystemMsg(0,'Create data fail'));
            }
            exit();
            break;
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("adv.php?action=edit&adv_id=$o->adv_id",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("adv.php?action=edit&adv_id=$o->adv_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;  
        case "edit":
            if($o->fetchAdvDetail(" AND adv_id = '$o->adv_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("adv.php",getSystemMsg(0,'Fetch Data fail'));
            }
            exit();
            break;  
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("adv.php",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("adv.php",getSystemMsg(0,'Delete data fail'));
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


