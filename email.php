<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Email.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Email();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;
    
    $action = escape($_REQUEST['action']);
    $o->additionaltype_id = escape($_REQUEST['additionaltype_id']);
    $o->additionaltype_code = escape($_POST['additionaltype_code']);
    $o->additionaltype_desc = escape($_POST['additionaltype_desc']);
    $o->additionaltype_seqno = escape($_POST['additionaltype_seqno']);
    $o->additionaltype_status = escape($_POST['additionaltype_status']);

    
    $o->send_to = escape($_POST['send_to']);
    $o->subject = escape($_POST['subject']);
    $o->message = escape($_POST['message']);

    switch ($action) {
        case "filter_sent":
        case "filter_trash":
        case "filter_drafts":
        case "filter_junk":
            $o->filter_action = $action;
            $o->getListing($action);
            exit();
            break;
        case "read_email":
            
            $o->readEmail();
            exit();
            break;
        case "compose":
            
            $o->Compose();
            exit();
            break;
        case "email":
            
            if($o->email_action()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("email.php",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("email.php",getSystemMsg(0,'Create data fail'));
            }
           
            exit();
            break;
        default: 
            $o->getListing();
            exit();
            break;
    }


