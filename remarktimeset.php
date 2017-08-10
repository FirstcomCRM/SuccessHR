<?php


    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/RemarkTimeSet.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';

    $o = new RemarkTimeSet();
    $s = new SavehandlerApi();
    
    $o->save = $s;
    $o->files = $_FILES['files'];
    
    $o->time_id = escape($_REQUEST['time_id']);
    $o->time_minute = escape($_POST['time_minute']);

    

    $action = escape($_REQUEST['action']);

    
    switch ($action) {
        case "create":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                if($o->create()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Create success.";
                    rediectUrl("remarktimeset.php",getSystemMsg(1,'Create data successfully'));
                }
                else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("remarktimeset.php",getSystemMsg(0,'Create data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("remarktimeset.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "update":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                if($o->update()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Update success.";
                    rediectUrl("remarktimeset.php",getSystemMsg(1,'Update data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Update fail.";
                    rediectUrl("remarktimeset.php?action=edit&category_id=$o->time_id",getSystemMsg(0,'Update data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("remarktimeset.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;  
        case "edit":
            if(($o->fetchTime(" AND time_id = '$o->time_id'","","",1)) && ($o->time_id > 0)){
                $o->showTimeForm("update");
            }
            exit();
        default:     
            $o->showTimeForm();
    }    