<?php
    include_once '../connect.php';
    include_once 'system.php';
    include_once '../include_function.php';
    include_once 'class/Luckydraw.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Luckydraw();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->luckydraw_id = escape($_REQUEST['luckydraw_id']);
    $o->luckydraw_code = escape($_POST['luckydraw_code']);
    $o->luckydraw_desc = escape($_POST['luckydraw_desc']);
    $o->luckydraw_mt_title = escape($_POST['luckydraw_mt_title']);
    $o->luckydraw_mt_keyword = escape($_POST['luckydraw_mt_keyword']);
    $o->luckydraw_mt_desc = escape($_POST['luckydraw_mt_desc']);
    $o->luckydraw_remark = escape($_POST['luckydraw_remark']);
    $o->luckydraw_status = escape($_POST['luckydraw_status']);
    $o->luckydraw_shortdesc = escape($_POST['luckydraw_shortdesc']);
    $o->rluckydraw_id = escape($_POST['rluckydraw_id']);
    $o->image_input = $_FILES['input_file'];
    $o->luckydraw_stars = escape($_POST['luckydraw_stars']);
    $o->luckydraw_end_date = escape($_POST['luckydraw_end_date']);
    $o->luckydraw_url = escape($_POST['luckydraw_url']);
    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("luckydraw.php?action=edit&luckydraw_id=$o->luckydraw_id",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("luckydraw.php",getSystemMsg(0,'Create data fail'));
            }
            exit();
            break;
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("luckydraw.php?action=edit&luckydraw_id=$o->luckydraw_id",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("luckydraw.php?action=edit&luckydraw_id=$o->luckydraw_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;  
        case "edit":
            if($o->fetchLuckydrawDetail(" AND luckydraw_id = '$o->luckydraw_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("luckydraw.php",getSystemMsg(0,'Fetch Data fail'));
            }
            exit();
            break;  
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("luckydraw.php",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("luckydraw.php",getSystemMsg(0,'Delete data fail'));
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


