<?php
    include_once '../connect.php';
    include_once 'system.php';
    include_once '../include_function.php';
    include_once 'class/Onlinecasino.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Onlinecasino();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->onlinecasino_id = escape($_REQUEST['onlinecasino_id']);
    $o->onlinecasino_code = escape($_POST['onlinecasino_code']);
    $o->onlinecasino_desc = escape($_POST['onlinecasino_desc']);
    $o->onlinecasino_mt_title = escape($_POST['onlinecasino_mt_title']);
    $o->onlinecasino_mt_keyword = escape($_POST['onlinecasino_mt_keyword']);
    $o->onlinecasino_mt_desc = escape($_POST['onlinecasino_mt_desc']);
    $o->onlinecasino_remark = escape($_POST['onlinecasino_remark']);
    $o->onlinecasino_status = escape($_POST['onlinecasino_status']);
    $o->ronlinecasino_id = escape($_POST['ronlinecasino_id']);
    $o->onlinecasino_shortdesc = escape($_POST['onlinecasino_shortdesc']);
    $o->onlinecasino_stars = escape($_POST['onlinecasino_stars']);
    $o->onlinecasino_url = escape($_POST['onlinecasino_url']);
    
    $o->image_input = $_FILES['input_file'];
    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("onlinecasino.php?action=edit&onlinecasino_id=$o->onlinecasino_id",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("onlinecasino.php",getSystemMsg(0,'Create data fail'));
            }
            exit();
            break;
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("onlinecasino.php?action=edit&onlinecasino_id=$o->onlinecasino_id",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("onlinecasino.php?action=edit&onlinecasino_id=$o->onlinecasino_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;  
        case "edit":
            if($o->fetchOnlinecasinoDetail(" AND onlinecasino_id = '$o->onlinecasino_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("onlinecasino.php",getSystemMsg(0,'Fetch Data fail'));
            }
            exit();
            break;  
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("onlinecasino.php",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("onlinecasino.php",getSystemMsg(0,'Delete data fail'));
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


