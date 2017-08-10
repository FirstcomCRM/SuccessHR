<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Deductions.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Deductions();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->deductions_id = escape($_REQUEST['deductions_id']);
    $o->deductions_title = escape($_POST['deductions_title']);
    $o->deductions_empl_id = escape($_POST['deductions_empl_id']);
    $o->deductions_date = escape($_POST['deductions_date']);
    $o->deductions_remark = escape($_POST['deductions_remark']);
    $o->deductions_amount = str_replace(",", "",escape($_POST['deductions_amount']));
    $o->deductions_status = escape($_POST['deductions_status']);
    $o->submit_btn = escape($_POST['submit_btn']);
    
    $o->deductions_affect_cpf = escape($_POST['deductions_affect_cpf']);

    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("deductions.php",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("deductions.php",getSystemMsg(0,'Create data fail'));
            }
            exit();
            break;
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("deductions.php",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("deductions.php?action=edit&deductions_id=$o->deductions_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;  
        case "edit":
            if($o->fetchDeductionsDetail(" AND deductions_id = '$o->deductions_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("deductions.php",getSystemMsg(0,'Fetch Data fail'));
            }
            exit();
            break;  
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("deductions.php",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("deductions.php",getSystemMsg(0,'Delete data fail'));
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


