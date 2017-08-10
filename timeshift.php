<?php


    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Timeshift.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';

    
    $o = new Timeshift();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $action = escape($_REQUEST['action']);
    $o->job_id = escape($_REQUEST['job_id']);
    if($action == 'update'){
        $o->fetchJobsPostingDetail(" AND job_id = '$o->job_id'","","",1);
    }
   
    $o->job_owner = escape($_POST['job_owner']);
    $o->job_person_incharge = escape($_POST['job_person_incharge']);
    $o->job_title = escape($_POST['job_title']);
    $o->job_category = escape($_POST['job_category']);
    $o->job_short_remarks = escape($_POST['job_short_remarks']);
    $o->job_internal_remarks = escape($_POST['job_internal_remarks']);
    $o->job_salary = escape($_POST['job_salary']);
    $o->job_postal_code = escape($_POST['job_postal_code']);
    $o->job_unit_no = escape($_POST['job_unit_no']);
    $o->job_street = escape($_POST['job_street']);
    $o->job_type = escape($_POST['job_type']);
    $o->job_description = escape($_POST['job_description']);
    $o->job_status = escape($_POST['job_status']);
    $o->job_seo_title = escape($_POST['job_seo_title']);
    $o->job_seo_keyword = escape($_POST['job_seo_keyword']);
    $o->job_seo_description = escape($_POST['job_seo_description']);
    
//    var_dump($_FILES);die;
    switch ($action) {
        case "create":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                if($o->create()){
                    $o->saveNotification();
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Create success.";
                    rediectUrl("timeshift.php",getSystemMsg(1,'Create data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("timeshift.php",getSystemMsg(0,'Create data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("timeshift.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "update":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                if($o->update()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Update success.";
                    rediectUrl("timeshift.php?action=edit&job_id=$o->job_id",getSystemMsg(1,'Update data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Update fail.";
                    rediectUrl("timeshift.php?action=edit&job_id=$o->job_id",getSystemMsg(0,'Update data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("timeshift.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;  
        case "edit":
            if(($o->fetchJobsPostingDetail(" AND job_id = '$o->job_id'","","",1)) && ($o->job_id > 0)){
                $o->getInputForms("update");
            }else{
               rediectUrl("timeshift.php",getSystemMsg(0,'Fetch Data Fail.'));
            }
            exit();
        case "view":
            if($o->fetchApplicantDetail(" AND applicant_id = '$o->applicant_id'","","",1)){
                $o->getInputForms("view");
            }else{
               rediectUrl("timeshift.php",getSystemMsg(0,'Fetch Data Fail.'));
            }
            exit();
            break; 
        case "delete":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                if($o->delete()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Delete success.";
                    rediectUrl("timeshift.php",getSystemMsg(1,'Delete data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Delete fail.";
                    rediectUrl("timeshift.php",getSystemMsg(0,'Delete data fail'));
                }
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Delete fail.";
                    rediectUrl("timeshift.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
        case "createForm":
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                $o->getInputForms('create');
//                  $o->testing('create');
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
        case "updateNotification":
            $o->updateNotification();
            exit();
            break;   
        case "getDepartment":
            $department_array = $o->getDepartment();
            $applicant_array = $o->getApplicant();
            $job_array = $o->getJobTitle();
            echo json_encode(array('department'=>$department_array, 'applicant'=>$applicant_array, 'job'=>$job_array));
            exit();
            break; 
        case "getTimeShiftDetail":
            $timeshift_array = $o->getTimeShiftDetail();
            echo json_encode(array('timeshift'=>$timeshift_array));
            exit();
            break; 
        default:
            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'access')){
                $o->getListing();
            }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
    }


