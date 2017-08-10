<?php 
$sql_debug = 0;
//error_reporting(0);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$lang = 'en';
session_start();
date_default_timezone_set("Asia/Singapore"); 

define('system_datetime',date('Y-m-d H:i:s'));
define('system_date',date('Y-m-d'));
define('webroot',"http://localhost/hr/");
define('include_webroot',"http://localhost/hr/");
define('system_gst_percent',7);

$_SERVER["PHP_SELF"] = htmlspecialchars($_SERVER["PHP_SELF"]);

$session_expiry_time = time() + (60*30); // 30mins expiry ;

$url_path = explode("/",$_SERVER['PHP_SELF']);


if($_SESSION['empl_id'] > 0 && $url_path[4] != 'login.php'){
    if($_SESSION["empl_login_expiry"] < time()){
        unset($_SESSION['empl_id']);
        $_SESSION["error_msg"] = "<font color = 'red' >Your session has expired.</font>";
        header ("Location: " . webroot . "login.php");
    }else{
        if($_SESSION['empl_id'] <= 0 || $url_path[4] == 'index.php'){
            unset($_SESSION['empl_id']);
            unset($_SESSION['empl_name']);
            unset($_SESSION['empl_code']);
            unset($_SESSION['empl_login_expiry']);  
            unset($_SESSION['empl_group']);
            unset($_SESSION['empl_department']);
            unset($_SESSION['empl_outlet']);  
            unset($_SESSION['empl_outlet_code']);  
          $_SESSION["error_msg"] = "<font color = 'red' >Your session has expired.</font>";
          header ("Location: " . webroot . "login.php");  
        }
        $_SESSION["empl_login_expiry"] = $session_expiry_time;
        unset($_SESSION['error_msg']);
    }
}else{
   unset($_SESSION['empl_id']);
   
   if($url_path[4] != 'login.php'){
     if($_SESSION["empl_login_expiry"] < time()){
        $_SESSION["error_msg"] = "<font color = 'red' >Your session has expired.</font>";
        unset($_SESSION['empl_id']);
        unset($_SESSION['empl_name']);
        unset($_SESSION['empl_code']);   
        unset($_SESSION['empl_login_expiry']);
        unset($_SESSION['empl_group']);
        unset($_SESSION['empl_department']);
        unset($_SESSION['empl_outlet']);  
        unset($_SESSION['empl_outlet_code']); 
        header ("Location: " . webroot . "login.php");
     }  
   }
   
        unset($_SESSION['empl_id']);
        unset($_SESSION['empl_name']);
        unset($_SESSION['empl_code']);   
        unset($_SESSION['empl_login_expiry']);
        unset($_SESSION['empl_group']);
        unset($_SESSION['empl_department']);
        unset($_SESSION['empl_outlet']);  
        unset($_SESSION['empl_outlet_code']); 
}