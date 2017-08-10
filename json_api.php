<?php
   include_once 'connect.php';
   include_once 'config.php';
   include_once 'include_function.php';
   include_once 'class/AutoComplete.php';
   include_once 'class/SavehandlerApi.php';

   $o = new AutoComplete();
   $s = new SavehandlerApi();
   $system_date = system_date;
   $action = $_REQUEST['action'];
   $o->save = $s;
   $o->empl_code = escape($_REQUEST['empl_code']);
   $o->project_id = escape($_REQUEST['project_id']);
   $o->pd = escape($_REQUEST['ps']);

//   $ps = "firstcom" . str_replace('-','',$system_date) . $action;
//   if($o->pd != md5($ps)){
//      echo json_encode(array('status'=>1,'msg'=>'Validation Fail.'));
//      exit();
//   }
   switch($action){
       case "add_attendance":
           include_once 'class/Attendance.php';
           $attn = new Attendance();
           $attn->attendance_empl = getDataCodeBySql("empl_id","db_empl"," WHERE empl_code = '$o->empl_code'","");
           $attn->save = $o->save;
           
           $isin = getDataCountBySql("db_attendance"," WHERE attendance_timein <> '' AND attendance_timeout = '0000-00-00 00:00:00' AND attendance_date = '$system_date' AND attendance_empl = '$attn->attendance_empl'");

           if($isin == 0){
                $attn->attendance_timein = system_datetime;
                $attn->attendance_project = $o->project_id;
                if($attn->create()){
                    echo json_encode(array('status'=>1,'msg'=>'Create Success'));
                }else{
                    echo json_encode(array('status'=>0));
                }
           }else{
                $attn->attendance_timeout = system_datetime;
                $attn->attendance_id = getDataCodeBySql("attendance_id","db_attendance","WHERE attendance_timein <> '' AND attendance_timeout = '0000-00-00 00:00:00' AND attendance_date = '$system_date' AND attendance_empl = '$attn->attendance_empl'", $orderby);
                if($attn->updateTimeOut()){
                    echo json_encode(array('status'=>1,'msg'=>'Update Success'));
                }else{
                    echo json_encode(array('status'=>0));
                }
           }
           exit;
           break;
       case "get_project":
           include_once 'class/Project.php';
           $pro = new Project();
           
           $query_project = $pro->fetchProjectDetail(" AND '$system_date' >= p.project_startdate AND p.project_enddate >= '$system_date'","","",1);
           $data = array();
           while($row = mysql_fetch_array($query_project)){
               $d['project_id'] = $row['project_id'];
               $d['project_code'] = $row['project_code'];
               $d['project_name'] = $row['project_name'];
               $d['project_desc'] = $row['project_desc'];
               $d['project_startdate'] = $row['project_startdate'];
               $d['project_enddate'] = $row['project_enddate'];
               $data[] = $d;
           }

           echo json_encode($data);
           exit;
           break;
       case "checkAttendance":
           include_once 'class/Attendance.php';
           $attn = new Attendance();
           
           $query_attendance = $attn->fetchAttendanceDetail(" AND c.attendance_date = '$system_date'","","",1);
           $data = array();
           while($row = mysql_fetch_array($query_attendance)){
               $d['attendance_id'] = $row['attendance_id'];
               $d['attendance_empl'] = $row['attendance_empl'];
               $d['attendance_timein'] = $row['attendance_timein'];
               $d['attendance_timeout'] = $row['attendance_timeout'];
               $d['attendance_project'] = $row['attendance_project'];
               $d['attendance_date'] = $row['attendance_date'];
               $data[] = $d;
           }

           echo json_encode($data);
           exit;
           break;
       case "uom":
           $q = $_REQUEST['q'];
           $o->getUomAutoComplete($q);
           exit;
           break;
       case "getCalendarEvent";
           $start = escape($_REQUEST['start']);
           $end = escape($_REQUEST['end']);
           
           $sql = "SELECT leave_reason,leave_datefrom,leave_dateto,leave_total_day,leave_type,leave_empl_id,leave_id
                   FROM db_leave 
                   WHERE leave_datefrom BETWEEN '$start' AND '$end' AND leave_approvalstatus = 'Approved'";
           $query = mysql_query($sql);
           $b = array();
           while($row = mysql_fetch_array($query)){
               $leave_code = getDataCodeBySql("leavetype_code","db_leavetype"," WHERE leavetype_id = '{$row['leave_type']}'", $orderby);
               $empl_name = getDataCodeBySql("empl_name","db_empl"," WHERE empl_id = '{$row['leave_empl_id']}'", $orderby);
               $row['title'] = $empl_name . " (" . $leave_code . ")";
               $url = webroot . "leave.php?action=edit&leave_id=" . $row['leave_id'];
               
               $b[] = array("title"=>$row['title'],"start"=>$row['leave_datefrom'],'end'=>date('Y-m-d', strtotime($row['leave_dateto'] . ' +1 day')),'url'=>$url);
           }
           echo json_encode($b);
           exit();
           break;
       default:
           
   }
?>
