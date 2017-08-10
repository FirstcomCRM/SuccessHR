<?php
    include_once '../connect.php';
    include_once 'system.php';
    include_once '../include_function.php';
    include_once 'class/Aboutus.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Aboutus();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->aboutus_id = escape($_REQUEST['aboutus_id']);
    $o->aboutus_facebook = escape($_POST['aboutus_facebook']);
    $o->aboutus_email = escape($_POST['aboutus_email']);
    $o->aboutus_skype = escape($_POST['aboutus_skype']);
    $o->aboutus_qq = escape($_POST['aboutus_qq']);
    $o->aboutus_mt_title = escape($_POST['aboutus_mt_title']);
    $o->aboutus_mt_keyword = escape($_POST['aboutus_mt_keyword']);
    $o->aboutus_mt_desc = escape($_POST['aboutus_mt_desc']);
    $o->aboutus_desc = escape($_POST['aboutus_desc']);
    $o->aboutus_tnc = escape($_POST['aboutus_tnc']);
    $o->aboutus_policy = escape($_POST['aboutus_policy']);
    $o->aboutus_contact = escape($_POST['aboutus_contact']);
    $o->aboutus_notice = escape($_POST['aboutus_notice']);
    $o->image_input = $_FILES['input_file'];
    
    switch ($action) {
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("aboutus.php?action=edit&aboutus_id=$o->aboutus_id",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("aboutus.php?action=edit&aboutus_id=$o->aboutus_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;  
        case "edit":
            if($o->fetchAboutusDetail(" AND aboutus_id = '$o->aboutus_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("aboutus.php",getSystemMsg(0,'Fetch Data fail'));
            }
            exit();
            break;  
        default: 
            $o->getInputForm('create');
            exit();
            break;
    }


