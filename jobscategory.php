<?php


    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/JobsCategory.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';

    $o = new JobsCategory();
    $s = new SavehandlerApi();
    
    $o->save = $s;
    $o->files = $_FILES['files'];
    
    $o->category_id = escape($_REQUEST['category_id']);
    $o->category_name = escape($_POST['category_name']);
    $o->category_parent = escape($_POST['category_parent']);
    $o->category_seqno = escape($_POST['category_seqno']);
    

    $action = escape($_REQUEST['action']);

    
    switch ($action) {
        case "create":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                if($o->create()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Create success.";
                    rediectUrl("jobscategory.php",getSystemMsg(1,'Create data successfully'));
                }
                else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("jobscategory.php",getSystemMsg(0,'Create data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("jobscategory.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "update":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                if($o->update()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Update success.";
                    rediectUrl("jobscategory.php",getSystemMsg(1,'Update data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Update fail.";
                    rediectUrl("jobscategory.php?action=edit&category_id=$o->category_id",getSystemMsg(0,'Update data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("jobscategory.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;  
        case "edit":
            if(($o->fetchCategoryDetail(" AND category_id = '$o->category_id'","","",1)) && ($o->category_id > 0)){
                $o->showCategoryForm("update");
            }
            exit();
        case "delete":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                if($o->delete()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Delete success.";
                    rediectUrl("jobscategory.php",getSystemMsg(1,'Delete data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Delete fail.";
                    rediectUrl("jobscategory.php",getSystemMsg(0,'Delete data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Delete fail.";
                    rediectUrl("jobscategory.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break; 
        default:     
            $o->showCategoryForm();
    }    