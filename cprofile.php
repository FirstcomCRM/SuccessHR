<?php


    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Cprofile.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';

    $o = new Cprofile();
    $s = new SavehandlerApi();
    
    $o->save = $s;
    $o->files = $_FILES['files'];
    
    $o->time_minute = escape($_POST['time_minute']);
    
    $o->outlet_id = escape($_REQUEST['outlet_id']);
    $o->outlet_code = escape($_POST['outlet_code']);
    $o->outlet_desc = escape($_POST['outlet_desc']);
    $o->outlet_tel = escape($_POST['outlet_tel']);
    $o->outlet_fax = escape($_POST['outlet_fax']);
    $o->outlet_email = escape($_POST['outlet_email']);
    $o->outlet_address = escape($_POST['outlet_address']);
    $o->outlet_gst_no = escape($_POST['outlet_gst_no']);
    $o->outlet_gst = escape($_POST['outlet_gst']);
    $o->outlet_country = escape($_POST['outlet_country']);
    $o->outlet_website = escape($_POST['outlet_website']);
    

    
    
    $action = escape($_REQUEST['action']);

    
    switch ($action) {
        case "create":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                if($o->create()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Create success.";
                    rediectUrl("cprofile.php",getSystemMsg(1,'Create data successfully'));
                }
                else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("cprofile.php",getSystemMsg(0,'Create data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("cprofile.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "update":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                if($o->update()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Update success.";
                    rediectUrl("cprofile.php",getSystemMsg(1,'Update data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Update fail.";
                    rediectUrl("cprofile.php?action=edit&category_id=$o->time_id",getSystemMsg(0,'Update data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("cprofile.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;  
        case "createForm":
            //$o->companyProfileForm();
            $o->getInputForm();
            exit();
        case "edit":
            $o->getInputForm("update");
            //if(($o->fetchTime(" AND time_id = '$o->time_id'","","",1)) && ($o->time_id > 0)){
            //$o->companyProfileForm("update");
            //}
            exit();
        case "saveOutlet":
            if($o->outlet_id > 0){
                if($o->updateOutlet()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }else{
                if($o->createOutlet()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }
            exit();
            break; 
        case "deleteOutlet": 
            $o->deleteOutlet();
            rediectUrl("cprofile.php",getSystemMsg(1,'Delete data successfully'));
            exit();     
        default:     
            $o->getlisting();
    }    