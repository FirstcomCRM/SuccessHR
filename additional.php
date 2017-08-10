<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Additional.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Additional();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->additional_id = escape($_REQUEST['additional_id']);
    $o->additional_type = escape($_POST['additional_type']);
    $o->additional_empl_id = escape($_POST['additional_empl_id']);
    $o->additional_date = escape($_POST['additional_date']);
    $o->additional_remark = escape($_POST['additional_remark']);
    $o->additional_amount = str_replace(",", "",escape($_POST['additional_amount']));
    $o->additional_status = escape($_POST['additional_status']);
    $o->additional_affect_cpf = escape($_POST['additional_affect_cpf']);
    $o->submit_btn = escape($_POST['submit_btn']);

    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("additional.php",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("additional.php",getSystemMsg(0,'Create data fail'));
            }
            exit();
            break;
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("additional.php",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("additional.php?action=edit&additional_id=$o->additional_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;  
        case "edit":
            if($o->fetchAdditionalDetail(" AND additional_id = '$o->additional_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("additional.php",getSystemMsg(0,'Fetch Data fail'));
            }
            exit();
            break;  
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("additional.php",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("additional.php",getSystemMsg(0,'Delete data fail'));
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


