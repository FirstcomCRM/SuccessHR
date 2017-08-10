<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Attendance.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    include_once 'class/Excel_reader2.php';
    include_once 'class/Empl.php';
    $o = new Attendance();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $e = new Empl();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->attendance_id = escape($_REQUEST['attendance_id']);
    $o->attendance_type = escape($_POST['attendance_type']);

    $o->attendance_date = escape($_POST['attendance_date']);
    $o->attendance_remark = escape($_POST['attendance_remark']);
    $o->attendance_amount = str_replace(",", "",escape($_POST['attendance_amount']));
    $o->attendance_status = escape($_POST['attendance_status']);
    $o->submit_btn = escape($_POST['submit_btn']);
    $o->attendance_autofilter = escape($_POST['attendance_autofilter']);
    $o->attendance_empl = escape($_POST['attendance_empl']);
    $o->attendance_ottotal = escape($_POST['attendance_ottotal']);
    $o->attendance_latetotal = escape($_POST['attendance_latetotal']);
    $o->file = $_FILES['file'];

    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("attendance.php?action=edit&attendance_id=$o->attendance_id",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("attendance.php",getSystemMsg(0,'Create data fail'));
            }
            exit();
            break;
        case "update":


            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("attendance.php?action=edit&attendance_id=$o->attendance_id",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("attendance.php?action=edit&attendance_id=$o->attendance_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;  
        case "edit":
            if($o->fetchAttendanceDetail(" AND attendance_id = '$o->attendance_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("attendance.php",getSystemMsg(0,'Fetch Data fail'));
            }
            exit();
            break;  
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("attendance.php",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("attendance.php",getSystemMsg(0,'Delete data fail'));
            }
            exit();
            break;   
        case "check_attendance";
                       
            $data = new Spreadsheet_Excel_Reader($_FILES["file"]["tmp_name"]);

            $o->database = "db_timetemp";
            $json = $o->calculateAttendance($data);
            echo json_encode($json);
            exit();
        case "createForm":
            $o->getInputForm('create');
            exit();
            break;   
        default: 
            $o->getListing();
            exit();
            break;
    }


