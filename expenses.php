<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Expenses.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Expenses();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;
    
    $action = escape($_REQUEST['action']);
    $o->expenses_id = escape($_REQUEST['expenses_id']);
    $o->expenses_code = escape($_POST['expenses_code']);
    $o->expenses_desc = escape($_POST['expenses_desc']);
    $o->expenses_seqno = escape($_POST['expenses_seqno']);
    $o->expenses_status = escape($_POST['expenses_status']);


    
    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("expenses.php?action=edit&expenses_id=$o->expenses_id",getSystemMsg(1,'Create data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("expenses.php",getSystemMsg(0,'Create data'));
            }
            exit();
            break; 
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("expenses.php?action=edit&expenses_id=$o->expenses_id",getSystemMsg(1,'Update data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("expenses.php?action=edit&expenses_id=$o->expenses_id",getSystemMsg(0,'Update data'));
            }
            exit();
            break;    
        case "edit":
            if($o->fetchExpensesDetail(" AND expenses_id = '$o->expenses_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("expenses.php",getSystemMsg(0,'Fetch Data'));
            }
            exit();
            break;
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("expenses.php",getSystemMsg(1,'Delete data'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("expenses.php?action=edit&expenses_id=$o->expenses_id",getSystemMsg(0,'Delete data'));
            }
            exit();
            break; 
        case "validate_expenses":
            if($o->validateExpenses($o->expenses_code,$o->expenses_id)){
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


