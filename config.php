<?php 
$sql_debug = 0;
error_reporting(0);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$lang = 'en';
session_start();
date_default_timezone_set("Asia/Singapore"); 

define('system_datetime',date('Y-m-d H:i:s'));
define('system_date',date('Y-m-d'));
define('system_date_monthstart',date('Y-m-01'));
define('system_date_monthend',date('Y-m-t'));
define('system_date_yearstart',date('Y-01-01'));
define('system_date_yearend',date('Y-12-t'));
define('webroot',"http://localhost/successhr/");
define('include_webroot',"http://localhost/successhr/");
//define('webroot',"http://successhrc2803.firstcomdemolinks.com/successhr/");
//define('include_webroot',"http://successhrc2803.firstcomdemolinks.com/successhr/");
define('system_gst_percent',7);
define('hospitalisation_leave',60);
define('leave_approved_need',2);
define('claims_approved_need',1);
define('leave_insert_cal',0);

//if(($_SESSION['empl_id'] != '10000') || ($_SESSION['empl_id'] == "1")){
 //  echo "<center>System Under Maintenance.</center>";
//   die;
//}

$_SERVER["PHP_SELF"] = htmlspecialchars($_SERVER["PHP_SELF"]);

$session_expiry_time = time() + (60*30); // 30mins expiry ;

$url_path = explode("/",$_SERVER['PHP_SELF']);


$master_group = array('1','2','-1');

if($_SESSION['empl_group'] == "9"){
    array_push($master_group,$_SESSION['empl_group']);
}
if($_SESSION['empl_id'] > 0 && $url_path[2] != 'login.php'){
    if($_SESSION["empl_login_expiry"] < time()){
        unset($_SESSION['empl_id']);
        $_SESSION["error_msg"] = "<font color = 'red' >Your session has expired.</font>";
        header ("Location: " . webroot . "login.php");
    }else{
        if($_SESSION['empl_id'] <= 0 || $url_path[2] == 'index.php'){
            unset($_SESSION['empl_id']);
            unset($_SESSION['empl_name']);
            unset($_SESSION['empl_code']);
            unset($_SESSION['empl_login_expiry']);  
            unset($_SESSION['empl_group']);
            unset($_SESSION['empl_department']);
            unset($_SESSION['empl_outlet']);  
            unset($_SESSION['empl_outlet_code']);  
            unset($_SESSION['empl_login_email']);
            unset($_SESSION['empl_varify_password']);
          $_SESSION["error_msg"] = "<font color = 'red' >Your session has expired.</font>";
          header ("Location: " . webroot . "login.php");  
        }
        $_SESSION["empl_login_expiry"] = $session_expiry_time;
        unset($_SESSION['error_msg']);
    }
}else{
   unset($_SESSION['empl_id']);
   
   if($url_path[2] != 'login.php'){
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
        unset($_SESSION['empl_login_email']);
        unset($_SESSION['empl_varify_password']);
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
        unset($_SESSION['empl_login_email']);
        unset($_SESSION['empl_varify_password']);
}