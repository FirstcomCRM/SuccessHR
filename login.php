<?php

    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Login.php'; 
    include_once 'class/SavehandlerApi.php';
    $o = new Login();
    $s = new SavehandlerApi();
    $o->save = $s;
    $action = escape($_REQUEST['action']);
    $o->login_email = escape($_POST['login_email']);
    $o->login_password = escape($_POST['login_password']);

    switch ($action) {
        case "login":
            if($o->loginProcess()){
                echo json_encode(array('status'=>1,'menu_path'=>$o->menu_path,'msg'=>$o->msg));
            }else{
                echo json_encode(array('status'=>0,'msg'=>$o->msg));
            }
            exit();
            break;
        case "logout":
            unset($_SESSION['empl_id']);
            unset($_SESSION['empl_name']);
            unset($_SESSION['empl_code']);
            unset($_SESSION['empl_login_expiry']);  
            unset($_SESSION['empl_group']);
            unset($_SESSION['empl_department']);
            unset($_SESSION['empl_outlet']);  
            unset($_SESSION['empl_outlet_code']);  
            unset($_SESSION['empl_login_email']);  
            $_SESSION["error_msg"] = "<font color = 'green' >Success Logout.</font>";
            header ("Location: " . webroot . "login.php");
            exit();
            break;   
        default: 
            $o->getInputForm();
            exit();
            break;
    }
?>

