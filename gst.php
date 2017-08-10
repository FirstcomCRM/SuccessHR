<?php


    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/GST.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';

    $o = new GST();
    $s = new SavehandlerApi();
    
    $o->save = $s;
    $o->files = $_FILES['files'];
    
    $o->gst_id = escape($_REQUEST['gst_id']);
    $o->gst_percentage = escape($_POST['gst_percentage']);

    

    $action = escape($_REQUEST['action']);

    
    switch ($action) {
        case "create":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                if($o->create()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Create success.";
                    rediectUrl("gst.php",getSystemMsg(1,'Create data successfully'));
                }
                else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("gst.php",getSystemMsg(0,'Create data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("gst.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "update":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                if($o->update()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Update success.";
                    rediectUrl("gst.php",getSystemMsg(1,'Update data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Update fail.";
                    rediectUrl("gst.php?action=edit&category_id=$o->gst_id",getSystemMsg(0,'Update data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("gst.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;  
        case "edit":
            if(($o->fetchGST(" AND gst_id = '$o->gst_id'","","",1)) && ($o->gst_id > 0)){
                $o->showGSTForm("update");
            }
            exit();
        default:     
            $o->showGSTForm();
    }    