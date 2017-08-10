<?php
error_reporting(E_ALL ^ E_DEPRECATED);
$db_server = "localhost";
//$db_name = "hr";
//$db_user = "root";
//$db_passwd = "";
$db_name = "crm_successhr";
$db_user = "root";
$db_passwd = "";
$connection = mysql_connect($db_server,$db_user,$db_passwd);
$db = mysql_select_db($db_name,$connection) or die("couldn't select Database");

/*
* 
* Cal System Database
* 
*/

$db_name_cal = "";
$db_user_cal = "";
$db_passwd_cal = "";