<?php
$mandatory = "<font color = 'red'>*</font>";
validation_magic_text();
$com_info = getCompanyInfo();

$notification_desc['0'] = "Assign candidate to client ";
$notification_desc['1'] = "Candidate follow up";
$notification_desc['2'] = "Assign interview to client ";
$notification_desc['3'] = "Assign candidate to consultant ";
$notification_desc['4'] = "Assign New Candidate to own";
$notification_desc['5'] = "Assign New Jobs to you";

$sql = "SELECT * FROM db_aboutus WHERE aboutus_id = 1 ";
$query = mysql_query($sql);
if($row = mysql_fetch_array($query)){
    $website_desc = $row['aboutus_desc'];
    $aboutus_tnc = $row['aboutus_tnc'];
    $aboutus_policy = $row['aboutus_policy'];
    $aboutus_contact = $row['aboutus_contact'];
    $aboutus_mt_title = $row['aboutus_mt_title'];
    $aboutus_mt_keyword = $row['aboutus_mt_keyword'];
    $aboutus_mt_desc = $row['aboutus_mt_desc'];
    $aboutus_notice = $row['aboutus_notice'];
}

if($_SESSION['customer_id'] > 0){
    $customer_id = escape($_SESSION['customer_id']);
    $sql = "SELECT COUNT(*) as total FROM customer WHERE customer_id = '$customer_id'";
    $query = mysql_query($sql);
    $total = 0;
    if($row = mysql_fetch_array($query)){
        $total = $row['total'];
    }else{
        $total = 0;
    }
    if($total != 1){
       header ("Location: " . webroot . "404.php"); 
       session_destroy();
    }
}


function getRate($comment_rate){
    switch ($comment_rate) {
       case 1:
           return "<span><i class='fa fa-star'></i></span>";
           break;
       case 1.5:
           return "<span><i class='fa fa-star'></i><i class='fa fa-star-half'></i></span>";
           break;
       case 2:
           return "<span><i class='fa fa-star'></i><i class='fa fa-star'></i></span>";
           break;
       case 2.5:
           return "<span><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star-half'></i></span>";
           break;
       case 3:
           return "<span><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i></span>";
           break;
       case 3.5:
           return "<span><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star-half'></i></span>";
           break;
       case 4:
           return "<span><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i></span>";
           break;
       case 4.5:
           return "<span><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star-half'></i></span>";
           break;
       case 5:
           return "<span><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i><i class='fa fa-star'></i></span>";
           break;
       default:
           break;
   }
}
function getRateIndex($comment_rate){
    switch ($comment_rate) {
       case 1:
           return "<i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>";
           break;
       case 1.5:
           return "<i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star-half-o' style = 'color:gold'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>";
           break;
       case 2:
           return "<i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>";
           break;
       case 2.5:
           return "<i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star-half-o' style = 'color:gold'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>";
           break;
       case 3:
           return "<i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>";
           break;
       case 3.5:
           return "<i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star-half-o' style = 'color:gold'></i>
                   <i class='fa fa-star-o'></i>";
           break;
       case 4:
           return "<i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star-o'></i>";
           break;
       case 4.5:
           return "<i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star-o'></i>";
           break;
       case 5:
           return "<i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>
                   <i class='fa fa-star' style = 'color:gold'></i>";
           break;
       default:
           return "<i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>
                   <i class='fa fa-star-o'></i>";
           break;
   }
}
function getCommentCount($comment_type,$posts_id){
    $sql = "SELECT COUNT(*) as total FROM db_comment WHERE posts_id = '$posts_id' AND comment_type = '$comment_type'";
    $query = mysql_query($sql);
    if($row = mysql_fetch_array($query)){
        $total = $row['total'];
    }else{
        $total = 0;
    }
    return $total;
}   
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

    
function getCompanyInfo(){
    $sql = "SELECT * FROM db_cprofile "; 
    $query = mysql_query($sql);
    $com_info = mysql_fetch_array($query);

    if($com_info['cprofile_contactemail'] == ""){
       $com_info['cprofile_contactemail'] = $com_info['cprofile_email']; 
    }
    return $com_info;
}
function getDataBySql($field,$table,$wherestring,$orderby){
    $sql = "SELECT $field FROM $table $wherestring $orderby";
    $query = mysql_query($sql);
    return $query;
}
function getDataCodeBySql($field,$table,$wherestring,$orderby){
    $sql = "SELECT $field FROM $table $wherestring $orderby";
    $query = mysql_query($sql);
    if($row = mysql_fetch_array($query)){
        return $row["$field"];
    }else{
        return null;
    }
}
function getDataCountBySql($table,$wherestring){
    $sql = "SELECT count(*) as total FROM $table $wherestring ";
    $query = mysql_query($sql);
    if($row = mysql_fetch_array($query)){
        return $row["total"];
    }else{
        return 0;
    }
}
function num_format($sIn){
    return number_format($sIn, 2, ".", ",");
}
function validation_magic_text(){
   foreach($_REQUEST as $datas => $data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
         $_REQUEST[$datas] = escape($data);
   }
   return $data;
}
function escape($sTemp){
    if (get_magic_quotes_gpc()){
        $sTemp = stripslashes($sTemp);
    }

    $sTemp = mysql_real_escape_string($sTemp);
    $sTemp = rescape($sTemp);
    return $sTemp;
}
function rescape($sTemp){
    return htmlentities($sTemp);
}
function getData($table,$wherestring,$field_get){
    $sql = "SELECT $field_get FROM $table $wherestring ";
    $query = mysql_query($sql);
    
    return $query;
}
function rediectUrl($url,$msg,$time=1){
    global $include_webroot;
    

        header("Refresh: $time;url=$url");
        include_once 'css.php';
               echo <<<EOF
    
	<style type="text/css">
	  body {
		padding-bottom: 40px;
	  }
	  .sidebar-nav {
		padding: 9px 0;
	  }
	</style>
   	

EOF;
        echo $msg;
        exit();
}
function get_client_ip() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
function getSystemMsg($status,$type){
     global $include_webroot;
     
    if($status == 1){
        $html .= <<<EOF
              <div class="box-content">   
                <div align = 'center' class="alert alert-success">
                          <strong>$type .</strong>
                </div>  
              </div>
EOF;
                
    }else if($status == 0){
        $html .= <<<EOF
              <div class="box-content">   
                <div align = 'center' class="alert alert-error">
                          <strong>$type .</strong>
                </div>  
              </div>
EOF;
        
    }
    
    return $html;
}
function get_prefix_value($refn_name,$use = false){

        // get the current value first
        $sql = "SELECT * FROM db_refn WHERE refn_name = '$refn_name'";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
            // gets current value
            $current_value = $row['refn_value'];
            $return_value = $row['refn_value'];
            $refn_prefix = $row['refn_prefix'];

            // increments current value
            $new_value = intval(intval($current_value) + 1);

            if($use){
                // updates the new value back into the database
                $sql = "UPDATE db_refn SET refn_value = '$new_value' WHERE refn_name = '$refn_name'";
                if (mysql_query($sql)){

                    // before returning value to user, applies padding (if required)
                   $return_value = str_pad($return_value, 4, "0", STR_PAD_LEFT);
                   $return_value = $refn_prefix . $return_value;

                    // returns the current value to the user.
                    return $return_value;
                }else{
                    return -1;
                }
            }else{
                    //display value only
                    // before returning value to user, applies padding (if required)
                   $return_value = str_pad($return_value, 4, "0", STR_PAD_LEFT);
                   $return_value = $refn_prefix . $return_value;

                    // returns the current value to the user.
                    return $return_value;
            }
        }else{
            return -1;
        }
}
    function getListingStatus($status){

        switch ($status) {
        case 1:
            echo "<span class='label label-success'>Active</span>";
        break;
        case 0:
            echo "<span class='label label-important'>In-Active</span>";   
        break;
        default:
            echo "<span class='label label-warning'>Un-know status</span>";   
        break;
        }
    }
    function getWindowPermission($menu_id,$status){
    $group_id = $_SESSION["empl_group"];
    if($group_id == -1 && $_SESSION['empl_code'] == 'Webmaster' && $_SESSION['empl_id'] == 10000){
        return true;
    }
    $sql1 = "SELECT COUNT(*) as total 
                FROM db_menuprm 
                WHERE menuprm_prmcode = '$status' AND 
                menuprm_menu_id = '$menu_id' AND menuprm_group_id = '$group_id'";
    $query1 = mysql_query($sql1);
    $user_prm = array();
    $total = 0;
    if($row1 = mysql_fetch_array($query1)){
        $total = $row1['total'];
    }else{
        $total = 0;
    }
    $total1 = 1;
    if($status != 'access'){
       $sql2 = "SELECT COUNT(*) as total 
                FROM db_menuprm WHERE menuprm_prmcode = 'access' AND 
                menuprm_menu_id = '$menu_id' AND menuprm_group_id = '$group_id'";
       $query2 = mysql_query($sql2); 
       $total1 = 0;
        if($row2 = mysql_fetch_array($query2)){
            $total1 = $row2['total'];
        }else{
            $total1 = 0;
        }
    }

    if($total > 0 && $total1 > 0){
        return true;
    }else{
        return false;
    }
}
function generateTimeSheet($from,$to,$plus,$selectvalue){
    $html = "";
    $newfrom = $from;
    for($i=0;$i<$to;$i++){
        if($i > 0){
            $newfrom = date("H.i", strtotime($newfrom)+(60*$plus));
        }else{
            $newfrom = date("H.i", strtotime($newfrom));
        }
        if($selectvalue == $newfrom){
            $selected = " SELECTED";
        }else{
            $selected = " ";
        }
        $html .= "<option value = '$newfrom' $selected>$newfrom</option>";  
    }
    return $html;
}
function checkMenuChildren($wherestring){
    $sql = "SELECT COUNT(*) as total FROM db_menu WHERE $wherestring";
    $query = mysql_query($sql);
    if($row = mysql_fetch_array($query)){
        $total = $row['total'];
    }else{
        $total = 0;
    }
    return $total;
}
function format_date($datetime, $separator="-"){
    if ((strcasecmp($datetime, "0000-00-00") == 0) || (strcasecmp($datetime, "0000-00-00 00:00:00") == 0)){
        return "";
    }else{
        if (substr_count($datetime, "-") >= 2){
            $timestamp = get_timestamp($datetime);
        }else{
            $timestamp = $datetime;
        }
        return date("d" . $separator . "M" . $separator . "Y", $timestamp);
    }
}
function format_datetime($datetime, $separator="-"){
    if(strcasecmp($datetime, "0000-00-00 00:00:00") != 0){
        $timestamp = get_timestamp($datetime);
        return date("d" . $separator . "M" . $separator . "Y H:i", $timestamp);
    }else{
	    return "";
    }
}
function format_date_database($datetime){
   $timestamp = strtotime($datetime);
   $new_date_format = date('Y-m-d', $timestamp);
   if($new_date_format == '1970-01-01'){
       return "";
   }else{
       return $new_date_format;
   }
   
}
function get_timestamp($datetime){
    $arr_datetime = explode(" ", $datetime);
    if (sizeof($arr_datetime) >= 2){
        $arr_time = explode(":", $arr_datetime[1]);
        if (sizeof($arr_time) >= 3){
            $hour = $arr_time[0];
            $minute = $arr_time[1];
            $second = $arr_time[2];
        }else{
            $hour = 0;
            $minute = 0;
            $second = 0;
        }
        $arr_date = explode("-", $arr_datetime[0]);
    }else{
        $hour = 0;
        $minute = 0;
        $second = 0;
        $arr_date = explode("-", $datetime);
    }
    $timestamp = mktime($hour, $minute, $second, $arr_date[1], $arr_date[2], $arr_date[0]);
    return $timestamp;
}
     function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message,$cc) {
     $file = $path.$filename;
     $file_size = filesize($file);
     $handle = fopen($file, "r");
     $content = fread($handle, $file_size);
     fclose($handle);
     $content = chunk_split(base64_encode($content));
     $uid = md5(uniqid(time()));
     $header = "From: ".$from_name." <".$from_mail.">\r\n";
//     $header .= "Reply-To: ".$replyto."\r\n";
     if($cc != ""){
     $header .= "Cc: $cc\r\n";
     }
     $header .= "MIME-Version: 1.0\r\n";
if($filename){
     $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
     $header .= "This is a multi-part message in MIME format.\r\n";
     $header .= "--".$uid."\r\n";
}
     $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
if($filename){
     $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
     $header .= $message."\r\n\r\n";
     $header .= "--".$uid."\r\n";

     $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
     $header .= "Content-Transfer-Encoding: base64\r\n";
     $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
     $header .= $content."\r\n\r\n";
     $header .= "--".$uid."--";
}

     if (mail($mailto, $subject,$message, $header)) {
//     echo "mail send ... OK"; // or use booleans here
     } else {
//     echo "mail send ... ERROR!";
     }
    }
function permissionLog(){
    
    include_once 'class/SavehandlerApi.php';
    $s = new SavehandlerApi();
    
    $log_desc = "Employee ID : " . $_SESSION['empl_id'] . "\r\n";
    $log_desc .= "Employee Code : " . $_SESSION['empl_code'] . "\r\n";
    $log_desc .= "Employee Name : " . $_SESSION['empl_name'] . "\r\n";
    $log_desc .= "Employee Department : " . $_SESSION['empl_department'] . "\r\n";
    $log_desc .= "Page : " . getDataCodeBySql("menu_path","db_menu"," WHERE menu_id = '{$_SESSION['m'][$_SESSION['empl_id']]}'","") . "\r\n";
    $log_desc .= "URL : " . $_SERVER['REQUEST_URI'] . "\r\n";

    $table_field = array('log_empl_id','log_desc');
    $table_value = array(escape($_SESSION['empl_id']),escape($log_desc));
    $remark = "Insert Permission Log.";
    $s->SaveData($table_field,$table_value,'db_permision_log','log_id',$remark);
}
