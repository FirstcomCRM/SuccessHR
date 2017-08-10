<?php
    include_once '../connect.php';
    include_once 'system.php';
    include_once '../include_function.php';
    include_once 'class/Blacklist.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Blacklist();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->blacklist_id = escape($_REQUEST['blacklist_id']);
    $o->blacklist_code = escape($_POST['blacklist_code']);
    $o->blacklist_desc = escape($_POST['blacklist_desc']);
    $o->blacklist_mt_title = escape($_POST['blacklist_mt_title']);
    $o->blacklist_mt_keyword = escape($_POST['blacklist_mt_keyword']);
    $o->blacklist_mt_desc = escape($_POST['blacklist_mt_desc']);
    $o->blacklist_remark = escape($_POST['blacklist_remark']);
    $o->blacklist_status = escape($_POST['blacklist_status']);
    $o->blacklist_shortdesc = escape($_POST['blacklist_shortdesc']);
    $o->rblacklist_id = escape($_POST['rblacklist_id']);
    $o->blacklist_stars = escape($_POST['blacklist_stars']);
    $o->blacklist_url = escape($_POST['blacklist_url']);
    $o->image_input = $_FILES['input_file'];
    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("blacklist.php?action=edit&blacklist_id=$o->blacklist_id",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("blacklist.php",getSystemMsg(0,'Create data fail'));
            }
            exit();
            break;
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("blacklist.php?action=edit&blacklist_id=$o->blacklist_id",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("blacklist.php?action=edit&blacklist_id=$o->blacklist_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;  
        case "edit":
            if($o->fetchBlacklistDetail(" AND blacklist_id = '$o->blacklist_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("blacklist.php",getSystemMsg(0,'Fetch Data fail'));
            }
            exit();
            break;  
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("blacklist.php",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("blacklist.php",getSystemMsg(0,'Delete data fail'));
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


