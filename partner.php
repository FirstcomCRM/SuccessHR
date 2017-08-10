<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Partner.php';
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    include_once 'class/SelectControl.php';
    include_once 'language.php';
    
    $o->select = new SelectControl();
    $o = new Partner();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    
    if($action == 'update'){
        $o->fetchEmplDetail(" AND empl_id = '$o->empl_id'","","",1);
        $o->empl_oldpassword = $o->empl_login_password;
    }
    
    $action = escape($_REQUEST['action']);
    $o->partner_id = escape($_REQUEST['partner_id']);
    $o->partner_code = escape($_POST['partner_code']);
    $o->partner_name = escape($_POST['partner_name']);
    $o->partner_iscustomer = escape($_POST['partner_iscustomer']);
    $o->partner_issupplier = escape($_POST['partner_issupplier']);
    $o->partner_debtor_account = escape($_POST['partner_debtor_account']);
    $o->partner_creditor_account = escape($_POST['partner_creditor_account']);
    $o->partner_bill_address = escape($_POST['partner_bill_address']);
    $o->partner_ship_address = escape($_POST['partner_ship_address']);
    $o->partner_sales_person = escape($_POST['partner_sales_person']);
    $o->partner_tel = escape($_POST['partner_tel']);
    $o->partner_tel2 = escape($_POST['partner_tel2']);
    $o->partner_fax = escape($_POST['partner_fax']);
    $o->partner_email = escape($_POST['partner_email']);
    $o->partner_currency = escape($_POST['partner_currency']);
    $o->partner_outlet = escape($_POST['partner_outlet']);
    $o->partner_remark = escape($_POST['partner_remark']);
    $o->partner_website = escape($_POST['partner_website']);
    $o->partner_credit_limit = escape($_POST['partner_credit_limit']);
    $o->partner_industry = escape($_POST['partner_industry']);
    $o->partner_seqno = escape($_POST['partner_seqno']);
    $o->partner_status = escape($_POST['partner_status']);
    $o->partner_postal_code = escape($_POST['partner_postal_code']);
    $o->partner_unit_no = escape($_POST['partner_unit_no']);
    $o->partner_account_name1 = escape($_POST['partner_account_name1']);
    $o->partner_account_name2 = escape($_POST['partner_account_name2']);
    $o->partner_account_name3 = escape($_POST['partner_account_name3']);
    $o->partner_account_name4 = escape($_POST['partner_account_name4']);
    $o->partner_house_no = escape($_POST['partner_house_no']);
    $o->partner_suburb = escape($_POST['partner_suburb']);
    $o->partner_address_type = escape($_POST['partner_address_type']);
    $o->tab = escape($_REQUEST['tab']);

    $o->partner_name_cn = escape($_POST['partner_name_cn']);
    $o->partner_name_thai = escape($_POST['partner_name_thai']);
    $o->partner_bill_address_cn = escape($_POST['partner_bill_address_cn']);
    $o->partner_bill_address_thai = escape($_REQUEST['partner_bill_address_thai']);
    $o->partner_tax_no = escape($_REQUEST['partner_tax_no']);
    $o->partner_branch_no = escape($_REQUEST['partner_branch_no']);
    $o->partner_pulldatafromoffice = escape($_REQUEST['partner_pulldatafromoffice']);

    if($o->tab == 'QT'){
        $o->tab = "qt_history";
    }else if($o->tab == 'SO'){
        $o->tab = "so_history";
    }else if($o->tab == 'DO'){
        $o->tab = "do_history";
    }else if($o->tab == 'SI'){
        $o->tab = "iv_history";
    }

    //contact
    $o->contact_id = escape($_REQUEST['contact_id']);
    $o->contact_name = escape($_POST['contact_name']);
    $o->contact_tel = escape($_POST['contact_tel']);
    $o->contact_email = escape($_POST['contact_email']);
    $o->contact_address = escape($_POST['contact_address']);
    $o->contact_remark = escape($_POST['contact_remark']);
    $o->contact_seqno = escape($_POST['contact_seqno']);
    $o->contact_status = escape($_POST['contact_status']);
    $o->contact_fax = escape($_POST['contact_fax']);

    //Shipping Address
    $o->shipping_id = escape($_REQUEST['shipping_id']);
    $o->shipping_remark = escape($_POST['shipping_remark']);
    $o->shipping_address = escape($_POST['shipping_address']);
    $o->shipping_seqno = escape($_POST['shipping_seqno']);
    $o->shipping_status = escape($_POST['shipping_status']);
    $o->shipping_name = escape($_POST['shipping_name']);


    $o->pfollow_description = escape($_POST['pfollow_description']);
    $o->pfollow_id = escape($_REQUEST['pfollow_id']);   
    
    //TimeShift
    $o->timeshift_id = escape($_REQUEST['timeshift_id']);
    $o->department_name = escape($_REQUEST['department_name']);
    $o->working_day = escape($_POST['working_day']);
    $o->start_time = escape($_POST['start_time']);
    $o->end_time = escape($_POST['end_time']);
    $o->ot_rate = escape($_POST['ot_rate']);
    $o->timeshift_description = escape($_POST['timeshift_description']);
    //$o->salary_date = escape($_POST['salary_date']);
    
    //Employee
    $o->empl_id = escape($_REQUEST['empl_id']);
    $o->empl_name = escape($_REQUEST['empl_name']);
    $o->empl_nric = escape($_POST['empl_nric']);
    $o->empl_sex = escape($_POST['empl_sex']);
    $o->empl_remark = escape($_POST['empl_remark']);
    $o->empl_mobile = escape($_POST['empl_mobile']);
    $o->empl_email = escape($_POST['empl_email']);
    $o->empl_department = escape($_POST['empl_department']);
    $o->empl_email = escape($_POST['empl_email']);
    $o->empl_department = escape($_POST['empl_department']);    
    $o->empl_login_email = escape($_POST['empl_login_email']);
    $o->empl_login_password = escape($_POST['empl_login_password']);
    
    //Leave Or claims Approved
    $o->applicant_id = escape($_REQUEST['applicant_id']);
    $o->applicant_leave_approved1 = escape($_POST['applicant_leave_approved1']);
    $o->applicant_leave_approved2 = escape($_POST['applicant_leave_approved2']);
    $o->applicant_leave_approved3 = escape($_POST['applicant_leave_approved3']);
    $o->applicant_claims_approved1 = escape($_POST['applicant_claims_approved1']);
    $o->applicant_claims_approved2 = escape($_REQUEST['applicant_claims_approved2']);
    $o->applicant_claims_approved3 = escape($_POST['applicant_claims_approved3']);
    
     $o->machine_id = escape($_REQUEST['machine_id']);
    switch ($action) {
        case "create":
            if($o->create()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("partner.php?action=edit&partner_id=$o->partner_id",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("partner.php",getSystemMsg(0,'Create data fail'));
            }
            exit();
            break;
        case "update":
            if($o->update()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("partner.php?action=edit&partner_id=$o->partner_id",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("partner.php?action=edit&partner_id=$o->partner_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;
        case "edit":
            if($o->fetchPartnerDetail(" AND partner_id = '$o->partner_id'","","",1)){
                $o->getInputForm("update");
            }else{
               rediectUrl("partner.php",getSystemMsg(0,'Fetch Data'));
            }
            exit();
            break;
        case "delete":
            if($o->delete()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("partner.php",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("partner.php",getSystemMsg(0,'Delete data fail'));
            }
            exit();
            break;
        case "createForm":
                $o->getInputForm('create');
            exit();
            break;
        case "contact":
            if($o->fetchPartnerDetail(" AND partner_id = '$o->partner_id'","","",1)){
                $o->getContact();
            }else{
               rediectUrl("partner.php",getSystemMsg(0,'Fetch Data'));
            }
            exit();
            break;
        case "create_contact":
            if($o->createContact()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("partner.php?action=edit_contact&partner_id=$o->partner_id&contact_id=$o->contact_id",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("partner.php",getSystemMsg(0,'Create data fail'));
            }
            exit();
            break;
        case "update_contact":
            if($o->updateContact()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("partner.php?action=edit_contact&partner_id=$o->partner_id&contact_id=$o->contact_id",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("partner.php?action=contact&partner_id=$o->partner_id&contact_id=$o->contact_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;
        case "edit_contact":
            if($o->fetchContactDetail(" AND contact_id = '$o->contact_id'","","",1)){
                if($o->fetchPartnerDetail(" AND partner_id = '$o->partner_id'","","",1)){
                    $o->getContact();
                }else{
                    rediectUrl("partner.php",getSystemMsg(0,'Fetch Data'));
                }
            }else{
               rediectUrl("partner.php",getSystemMsg(0,'Fetch Data'));
            }
            exit();
            break;
        case "delete_contact":
            if($o->deleteContact()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("partner.php?action=contact&partner_id=$o->partner_id",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("partner.php?action=contact&partner_id=$o->partner_id",getSystemMsg(0,'Delete data fail'));
            }
            exit();
            break;
        case "shipping_address":
            if($o->fetchPartnerDetail(" AND partner_id = '$o->partner_id'","","",1)){
                $o->getShippingAddress();
            }else{
               rediectUrl("partner.php",getSystemMsg(0,'Fetch Data'));
            }
            exit();
            break;
        case "create_shipping":
            if($o->createShippingAddress()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Create success.";
                rediectUrl("partner.php?action=edit_shipping_address&partner_id=$o->partner_id&shipping_id=$o->shipping_id",getSystemMsg(1,'Create data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Create fail.";
                rediectUrl("partner.php",getSystemMsg(0,'Create data fail'));
            }
            exit();
            break;
        case "update_shipping":
            if($o->updateShippingAddress()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Update success.";
                rediectUrl("partner.php?action=edit_shipping_address&partner_id=$o->partner_id&shipping_id=$o->shipping_id",getSystemMsg(1,'Update data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Update fail.";
                rediectUrl("partner.php?action=shipping_address&partner_id=$o->partner_id&shipping_id=$o->shipping_id",getSystemMsg(0,'Update data fail'));
            }
            exit();
            break;
        case "edit_shipping_address":
            if($o->fetchShippingAddress(" AND shipping_id = '$o->shipping_id'","","",1)){
                if($o->fetchPartnerDetail(" AND partner_id = '$o->partner_id'","","",1)){
                    $o->getShippingAddress();
                }else{
                    rediectUrl("partner.php",getSystemMsg(0,'Fetch Data'));
                }
            }else{
               rediectUrl("partner.php",getSystemMsg(0,'Fetch Data'));
            }
            exit();
            break;
        case "delete_shipping_address":
            if($o->deleteContact()){
                $_SESSION['status_alert'] = 'alert-success';
                $_SESSION['status_msg'] = "Delete success.";
                rediectUrl("partner.php?action=shipping_address&partner_id=$o->partner_id",getSystemMsg(1,'Delete data successfully'));
            }else{
                $_SESSION['status_alert'] = 'alert-error';
                $_SESSION['status_msg'] = "Delete fail.";
                rediectUrl("partner.php?action=shipping_address&partner_id=$o->partner_id",getSystemMsg(0,'Delete data fail'));
            }
            exit();
            break;
        case "validate_partner":
            $t = $gf->checkDuplicate("db_partner",'partner_account_code',$o->partner_account_code,'partner_id',$o->partner_id);
            if($t > 0){
                echo "false";
            }else{
                echo "true";
            }
            exit();
            break;
        case "getDataTable":
            $o->getDataTable();
            exit();
            break;
        case "getShippingAddress":
            if($o->fetchShippingAddress(" AND shipping_id = '$o->shipping_id'","","",1)){
               echo json_encode(array('status'=>1,'shipping_address'=>$o->shipping_address,'shipping_name'=>$o->shipping_name));
            }else{
               echo json_encode(array('status'=>0));
            }
            exit();
            break;
        case "getContactJson":
            if($o->fetchContactDetail(" AND contact_id = '$o->contact_id'","","",1)){
               echo json_encode(array('status'=>1,'contact_tel'=>$o->contact_tel,
                                      'contact_email'=>$o->contact_email,'contact_address'=>$o->contact_address,
                                      'contact_remark'=>$o->contact_remark,'contact_id'=>$o->contact_id,
                                      'contact_fax'=>$o->contact_fax,'contact_name'=>$o->contact_name));
            }else{
               echo json_encode(array('status'=>0));
            }
            exit();
            break;
        case "getPartnerDetailTransaction":
//            $sql3 = "SELECT * FROM db_partner WHERE partner_status = '1'";
//            $query3 = mysql_query($sql3);
//            while($row3 = mysql_fetch_array($query3)){
            $partner_bill_address = "";

            $r = $o->getPartnerDetailTransaction();
            $contact_option = $o->select->getContactSelectCtrl($o->contact_id,"Y"," AND contact_partner_id = '$o->partner_id'");
            $machine_option = $o->select->getMachineSelectCtrl($o->machine_id,"Y"," AND machine_account_no = '{$r['partner_account_code']}'");
            $shipping_option = $o->select->getShippingAddressSelectCtrl("","Y"," AND shipping_partner_id = '$o->partner_id'");

            $partner_bill_address .= $r['partner_account_name1'];
            if($r['partner_account_name2'] != ""){
                if($partner_bill_address != ""){
                    $partner_bill_address .= "\n";
                }
                $partner_bill_address .= $r['partner_account_name2'];
            }

            if($r['partner_account_name3'] != ""){
                if($partner_bill_address != ""){
//                    $partner_bill_address .= "\n";
                }
//                $partner_bill_address .= $r['partner_account_name3'];
            }
            if($r['partner_account_name4'] != ""){
                if($partner_bill_address != ""){
                   $partner_bill_address .= "\n";
                }
                $partner_bill_address .= $r['partner_account_name4'];
            }
            if($r['partner_bill_address'] != ""){
                if($partner_bill_address != ""){
                   $partner_bill_address .= "\n";
                }
                $partner_bill_address .= $r['partner_bill_address'];
            }
            if($r['partner_house_no'] != ""){
                if($partner_bill_address != ""){
                    $partner_bill_address .= "\n";
                }
                $partner_bill_address .= $r['partner_house_no'];
            }
            if($r['partner_unit_no'] != ""){
                if($partner_bill_address != ""){
                    $partner_bill_address .= "\n";
                }
                $partner_bill_address .= $r['partner_unit_no'];
            }
            if($r['partner_postal_code'] != 0){
                //if($partner_bill_address != ""){
                //    $partner_bill_address .= "\n";
                //}
                $partner_bill_address .= " " . $r['partner_postal_code'];
            }
            if($r['partner_outlet'] != ""){
                if($partner_bill_address != ""){
                    $partner_bill_address .= "\n";
                }
                $partner_bill_address .= getDataCodeBySql("country_code","db_country"," WHERE country_id = '".$r['partner_outlet']."'","");
            }
//            $partner_bill_address = escape($partner_bill_address);
//            $sql = "UPDATE db_partner SET partner_bill_address = '$partner_bill_address' WHERE partner_id = '$o->partner_id'";
//
//            mysql_query($sql);
//            }
//            echo 'done';
//            die;
            if(($_SESSION['empl_outlet'] == 14) && ($r['partner_bill_address_cn'] != "")){//taiwan
                $partner_bill_address = $r['partner_bill_address_cn'];
            }else if(($_SESSION['empl_outlet'] == 13) && ($r['partner_bill_address_thai'] != "")){//thailand
                $partner_bill_address = $r['partner_bill_address_thai'];
            }

            echo json_encode(array('partner_bill_address'=>$partner_bill_address,'partner_ship_address'=>$r['partner_ship_address'],
                                   'partner_tel'=>$r['partner_tel'],'partner_email'=>$r['partner_email'],
                                   'partner_currency'=>$r['partner_currency'],'partner_credit_limit'=>$r['partner_credit_limit'],
                                   'partner_name'=>$r['partner_name'],'partner_code'=>$r['partner_code'],
                                   'partner_sales_person'=>$r['partner_sales_person'],'contact_option'=>$contact_option,
                                   'machine_option'=>$machine_option,'shipping_option'=>$shipping_option,
                                   'partner_fax'=>$r['partner_fax'],'tax_no'=>$r['partner_tax_no'],
                                   'branch_no'=>$r['partner_branch_no'],'partner_pulldatafromoffice'=>$r['partner_pulldatafromoffice']));
            exit();
            break;
        case"import":

           if($_FILES["import_file"]["size"] > 0){

                $file = $_FILES["import_file"]["tmp_name"];
                $handle = fopen($file,"r");

                if($_FILES["import_file"]['type'] == 'text/csv'){
                    $seq = 10;
                    $s = 0;
                    do{
                        if($_REQUEST['import_action'] == 'Customer'){
                            if(($data[0] == 'Account number') || ($data[0] == '')){
                                  continue;
                            }

                            if($data[0]){
                                 $partner_account_code = escape($data[0]);
                                 if($partner_account_code != ""){
                                    if($o->fetchPartnerDetail(" AND partner_account_code = '$partner_account_code'","","",1)){
                                         if($o->partner_id > 0){
                                           $o->generateImportData($data,"partner");
                                           $o->update();
                                         }else{
                                           $o->generateImportData($data,"partner");
                                           $o->partner_account_code = $partner_account_code;
                                           $o->partner_status = 1;
                                           $o->partner_iscustomer = 1;
                                           if($partner_account_code == '30012'){
                                               $o->partner_issupplier = 1;
                                           }
                                           $o->partner_seqno = $seq;
                                           $o->create();
                                         }
                                    }else{
                                           $o->generateImportData($data,"partner");
                                           $o->create();
                                    }
                                    $seq = $seq + 10;
                                    $s++;
                                 }
                            }
                        }else if($_REQUEST['import_action'] == 'Contact'){
                            if(($data[0] == 'Account number (Parent customer)') || ($data[0] == '')){
                                  continue;
                            }
                            if($data[0]){
                                 $partner_account_code = escape($data[0]);
                                 if($partner_account_code != ""){
                                    if($o->fetchPartnerDetail(" AND partner_account_code = '$partner_account_code'","","",1)){
                                         if($o->partner_id > 0){
                                           $o->generateImportData($data,"contact");
                                           $o->contact_partner_id = $o->partner_id;
                                           $o->contact_status = 1;
                                           $o->contact_seqno = $seq;
                                           $o->createContact();
                                           $seq = $seq + 10;
                                           $s++;
                                         }
                                    }
                                 }
                            }
                        }
                    }while($data = fgetcsv($handle,20000));
                }else if($_FILES["import_file"]['type'] == 'application/vnd.ms-excel'){


                }

                echo json_encode(array('status'=>1,'data'=>$s));
           }else{
               echo json_encode(array('status'=>0,'data'=>0));
           }
                exit();
                break;
        case "deleteFollow": 
            $o->deleteFollowUp();
            rediectUrl("partner.php?action=edit&partner_id=$o->partner_id&tab=followup",getSystemMsg(1,'Delete data successfully'));
            exit();
            break;
        case "saveFollowup":
            if($o->pfollow_id > 0){
                if($o->updateFollowUp()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }else{
                if($o->createFollowUp()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }
            exit();
            break;   
        case "validate_email":
            $t = $gf->checkDuplicate("db_empl",'empl_login_email',$o->empl_login_email,'empl_id',$o->empl_id);
            if($t > 0){
                echo "false";
            }else{
                echo "true";
            }
            exit();
            break;             
        case "deleteTimeShift": 
            $o->deleteTimeShift();
            rediectUrl("partner.php?action=edit&partner_id=$o->partner_id&tab=timeshift",getSystemMsg(1,'Delete data successfully'));
            exit();
            break;
        case "saveTimeShift":
            if($o->timeshift_id > 0){
                if($o->updateTimeShift()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }else{
                if($o->createTimeShift()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }
            exit();
            break;          
        case "deleteEmpl": 
            $o->deleteEmployee();
            rediectUrl("partner.php?action=edit&partner_id=$o->partner_id&tab=employee",getSystemMsg(1,'Delete data successfully'));
            exit();
            break;
        case "saveEmpl":
            if($o->empl_id > 0){
                if($o->updateEmployee()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }else{
                if($o->createEmployee()){
                    echo json_encode(array('status'=>1));
                }else{
                    echo json_encode(array('status'=>0));
                }
            }
            exit();
            break;    
        case "updateCandidate":
            $o->updateCandidate();
            echo json_encode(array('status'=>1));
            exit();
            break;
        case "getRemarkDetail":
            $remarks_array = $o->getRemarks();
            echo json_encode(array('pRemarks'=>$remarks_array));
            exit();
            break;            
        default:
            $o->getListing();
            exit();
            break;
    }
