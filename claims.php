<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Claims.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Claims();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;
    $o->menu_id = 4;

    $action = escape($_REQUEST['action']);
    $o->claims_id = escape($_REQUEST['claims_id']);
    $o->claims_title = escape($_POST['claims_title']);
    $o->claims_empl_id = escape($_POST['claims_empl_id']);
    $o->claims_date = escape($_POST['claims_date']);
    $o->claims_remark = escape($_POST['claims_remark']);
    $o->claims_status = escape($_POST['claims_status']);
    $o->submit_btn = escape($_POST['submit_btn']);
    $o->org_claims_approvalstatus = escape($_POST['org_claims_approvalstatus']);
    $o->image_input = $_FILES['claimsline_receiptno'];
    $o->sstatus_action = escape($_POST['sstatus_action']);
    $o->sstatus_message = escape($_POST['sstatus_message']);

    //Line item
    $o->claimsline_seqno_array = $_POST['claimsline_seqno'];
    $o->claimsline_date_array = $_POST['claimsline_date'];
    $o->claimsline_type_array = $_POST['claimsline_type'];
    $o->claimsline_desc_array = $_POST['claimsline_desc'];
    $o->claimsline_receiptno_array = $_POST['claimsline_receiptno'];
    $o->claimsline_amount_array = $_POST['claimsline_amount'];
    $o->claimsline_amount_gst_array = $_POST['claimsline_amount_gst'];
    $o->claimsline_gst_reg_no_array = $_POST['claimsline_gst_reg_no'];
    $o->claimsline_id_array = $_POST['claimsline_id'];
    $o->claimsline_id = escape($_REQUEST['claimsline_id']);
    
    if($o->submit_btn == 'Confirm'){
        $o->claims_approvalstatus = "Pending";
    }else{
        $o->claims_approvalstatus = "Draft";
    }
    
    $o->checkAccess($action);
    switch ($action) {
        case "create":
            if(getWindowPermission($o->menu_id,'create')){
                if($o->create()){
                    $o->createUpdateClaimsLine();
                    if(($o->claims_approvalstatus == 'Pending') || ($o->claims_approvalstatus == 'Approved')){
                        $o->email();
                    }
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Create success.";
                    rediectUrl("claims.php?action=edit&claims_id=$o->claims_id",getSystemMsg(1,'Create data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Create fail.";
                    rediectUrl("claims.php",getSystemMsg(0,'Create data fail'));
                }
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("claims.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "update":
            if(getWindowPermission($o->menu_id,'update')){

                if($o->update()){
                    if(($o->claims_approvalstatus == 'Pending') || ($o->claims_approvalstatus == 'Approved')){
                        $o->email();
                    }
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Update success.";
                    rediectUrl("claims.php?action=edit&claims_id=$o->claims_id",getSystemMsg(1,'Update data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Update fail.";
                    rediectUrl("claims.php?action=edit&claims_id=$o->claims_id",getSystemMsg(0,'Update data fail'));
                }
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("claims.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;  
        case "sstatus":
            
            if(!in_array($_SESSION['empl_group'],$master_group)){
                permissionLog();
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
                exit();
            }
            if($o->doSstatus()){
                rediectUrl("claims.php?action=edit&claims_id=$o->claims_id",getSystemMsg(1,'Update data successfully'));
            }else{
                rediectUrl("claims.php?action=edit&claims_id=$o->claims_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;
        case "edit":
            if(getWindowPermission($o->menu_id,'update')){
                if(!$o->fetchClaimsDetail(" AND claims_id = '$o->claims_id'","","",1)){
                   rediectUrl("claims.php",getSystemMsg(0,'Fetch Data fail'));
                }else{
                   $o->getInputForm("update");
                }
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;  
        case "delete":
            if(getWindowPermission($o->menu_id,'delete')){
                if($o->delete()){
                    $_SESSION['status_alert'] = 'alert-success';
                    $_SESSION['status_msg'] = "Delete success.";
                    rediectUrl("claims.php",getSystemMsg(1,'Delete data successfully'));
                }else{
                    $_SESSION['status_alert'] = 'alert-error';
                    $_SESSION['status_msg'] = "Delete fail.";
                    rediectUrl("claims.php",getSystemMsg(0,'Delete data fail'));
                }
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("claims.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
        case "createForm":
            if(getWindowPermission($o->menu_id,'create')){
                $o->getInputForm('create');
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;   
        case "updateline":
            if(getWindowPermission($o->menu_id,'update')){
                if($o->UpdateClaimsSingleLine()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "deleteline":
            if(getWindowPermission($o->menu_id,'delete')){
                if($o->deleteClaimsLine()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
        case "duplicate":
            
            if($o->claims_id > 0){
                if($o->duplicateClaim()){
                    echo json_encode(array('status'=>1,'msg'=>'Duplicate data successfully','newclaims_id'=>$o->newclaims_id));
                }else{
                    echo json_encode(array('status'=>0,'msg'=>'Duplicate data fail.'));
                }
            }else{
                echo json_encode(array('status'=>0,'msg'=>'Record not found.'));
            }
            
            exit();
            break;
        default: 
            if(getWindowPermission($o->menu_id,'access')){
                $o->getListing();
            }else{
                permissionLog();
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            }
            exit();
            break;
    }


