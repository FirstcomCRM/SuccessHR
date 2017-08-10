<?php
/*
 * To change this tpartnerate, choose Tools | Tpartnerates
 * and open the tpartnerate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Partner {

    public function Partner(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();


    }
    public function create(){
        $this->empl_login_password = md5("@#~x?\$" . $this->empl_login_password . "?\$");
        $table_field = array('partner_code','partner_name','partner_iscustomer','partner_issupplier',
                             'partner_bill_address','partner_ship_address','partner_sales_person','partner_tel',
                             'partner_fax','partner_email','partner_currency','partner_outlet',
                             'partner_remark','partner_website','partner_credit_limit','partner_industry',
                             'partner_debtor_account','partner_creditor_account','partner_seqno','partner_status',
                             'partner_tel2','partner_postal_code','partner_unit_no',
                             'partner_account_name1','partner_account_name2','partner_account_name3','partner_account_name4',
                             'partner_house_no','partner_suburb','partner_address_type','partner_group',
                             'partner_name_cn','partner_name_thai','partner_bill_address_cn','partner_bill_address_thai',
                             'partner_tax_no','partner_branch_no','partner_pulldatafromoffice');
        $table_value = array($this->partner_code,$this->partner_name,$this->partner_iscustomer,$this->partner_issupplier,
                             $this->partner_bill_address,$this->partner_ship_address,$this->partner_sales_person,$this->partner_tel,
                             $this->partner_fax,$this->partner_email,$this->partner_currency,$this->partner_outlet,
                             $this->partner_remark,$this->partner_website,$this->partner_credit_limit,$this->partner_industry,
                             $this->partner_debtor_account,$this->partner_creditor_account,$this->partner_seqno,$this->partner_status,
                             $this->partner_tel2,$this->partner_postal_code,$this->partner_unit_no,
                             $this->partner_account_name1,$this->partner_account_name2,$this->partner_account_name3,$this->partner_account_name4,
                             $this->partner_house_no,$this->partner_suburb,$this->partner_address_type,$this->partner_group,
                             $this->partner_name_cn,$this->partner_name_thai,$this->partner_bill_address_cn,$this->partner_bill_address_thai,
                             $this->partner_tax_no,$this->partner_branch_no,$this->partner_pulldatafromoffice);
        $remark = "Insert Partner.";
        if(!$this->save->SaveData($table_field,$table_value,'db_partner','partner_id',$remark)){
           return false;
        }else{
           $this->partner_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $new_password = $this->empl_login_password;
        $empl_id = $this->empl_id;
        $empl_login_email = $this->empl_login_email;

        if($this->empl_oldpassword != $new_password){
          $this->empl_login_password = md5("@#~x?\$" . $new_password . "?\$");
        }
        
        $table_field = array('partner_code','partner_name','partner_iscustomer','partner_issupplier',
                             'partner_bill_address','partner_ship_address','partner_sales_person','partner_tel',
                             'partner_fax','partner_email','partner_currency','partner_outlet',
                             'partner_remark','partner_website','partner_credit_limit','partner_industry',
                             'partner_debtor_account','partner_creditor_account','partner_seqno','partner_status',
                             'partner_tel2','partner_postal_code','partner_unit_no',
                             'partner_account_name1','partner_account_name2','partner_account_name3','partner_account_name4',
                             'partner_house_no','partner_suburb','partner_address_type','partner_group',
                             'partner_name_cn','partner_name_thai','partner_bill_address_cn','partner_bill_address_thai',
                             'partner_tax_no','partner_branch_no','partner_pulldatafromoffice');
        $table_value = array($this->partner_code,$this->partner_name,$this->partner_iscustomer,$this->partner_issupplier,
                             $this->partner_bill_address,$this->partner_ship_address,$this->partner_sales_person,$this->partner_tel,
                             $this->partner_fax,$this->partner_email,$this->partner_currency,$this->partner_outlet,
                             $this->partner_remark,$this->partner_website,$this->partner_credit_limit,$this->partner_industry,
                             $this->partner_debtor_account,$this->partner_creditor_account,$this->partner_seqno,$this->partner_status,
                             $this->partner_tel2,$this->partner_postal_code,$this->partner_unit_no,
                             $this->partner_account_name1,$this->partner_account_name2,$this->partner_account_name3,$this->partner_account_name4,
                             $this->partner_house_no,$this->partner_suburb,$this->partner_address_type,$this->partner_group,
                             $this->partner_name_cn,$this->partner_name_thai,$this->partner_bill_address_cn,$this->partner_bill_address_thai,
                             $this->partner_tax_no,$this->partner_branch_no,$this->partner_pulldatafromoffice);
        $remark = "Update Partner.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_partner','partner_id',$remark,$this->partner_id)){
           return false;
        }else{
           return true;
        }
    }
    public function createContact(){
        $table_field = array('contact_partner_id','contact_name','contact_tel','contact_email',
                             'contact_address','contact_remark','contact_cellphone','contact_department',
                             'contact_position','contact_jobtitle','contact_forename','contact_lastname',
                             'contact_seqno','contact_status','contact_fax');
        $table_value = array($this->partner_id,$this->contact_name,$this->contact_tel,$this->contact_email,
                             $this->contact_address,$this->contact_remark,$this->contact_cellphone,$this->contact_department,
                             $this->contact_position,$this->contact_jobtitle,$this->contact_forename,$this->contact_lastname,
                             $this->contact_seqno,$this->contact_status,$this->contact_fax);
        $remark = "Insert Contact.";
        if(!$this->save->SaveData($table_field,$table_value,'db_contact','contact_id',$remark)){
           return false;
        }else{
           $this->contact_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function updateContact(){
        $table_field = array('contact_partner_id','contact_name','contact_tel','contact_email',
                             'contact_address','contact_remark','contact_cellphone','contact_department',
                             'contact_position','contact_jobtitle','contact_forename','contact_lastname',
                             'contact_seqno','contact_status','contact_fax');
        $table_value = array($this->partner_id,$this->contact_name,$this->contact_tel,$this->contact_email,
                             $this->contact_address,$this->contact_remark,$this->contact_cellphone,$this->contact_department,
                             $this->contact_position,$this->contact_jobtitle,$this->contact_forename,$this->contact_lastname,
                             $this->contact_seqno,$this->contact_status,$this->contact_fax);
        $remark = "Update Contact.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_contact','contact_id',$remark,$this->contact_id)){
           return false;
        }else{
           return true;
        }
    }
    public function createShippingAddress(){
        $table_field = array('shipping_partner_id','shipping_address','shipping_remark','shipping_name',
                             'shipping_seqno','shipping_status');
        $table_value = array($this->partner_id,$this->shipping_address,$this->shipping_remark,$this->shipping_name,
                             $this->shipping_seqno,$this->shipping_status);
        $remark = "Insert Shipping Address.";
        if(!$this->save->SaveData($table_field,$table_value,'db_shipaddress','shipping_id',$remark)){
           return false;
        }else{
           $this->shipping_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function updateShippingAddress(){
        $table_field = array('shipping_partner_id','shipping_address','shipping_remark','shipping_name',
                             'shipping_seqno','shipping_status');
        $table_value = array($this->partner_id,$this->shipping_address,$this->shipping_remark,$this->shipping_name,
                             $this->shipping_seqno,$this->shipping_status);
        $remark = "Update Shipping Address.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_shipaddress','shipping_id',$remark,$this->shipping_id)){
           return false;
        }else{
           return true;
        }
    }
    public function pictureManagement(){
        if(!file_exists("images/partner")){
           mkdir('images/partner/');
        }
        $isimage = false;
        if($this->image_input['type'] == 'image/png' || $this->image_input['type'] == 'image/jpeg' || $this->image_input['type'] == 'image/gif'){
           $isimage = true;
        }
        if($this->image_input['size'] > 0 && $isimage == true){
            if($this->action == 'update'){
                unlink("images/partner/{$this->partner_id}.jpeg");
            }
                move_uploaded_file($this->image_input['tmp_name'],"images/partner/{$this->partner_id}.jpeg");
        }
    }
    public function fetchPartnerDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_partner WHERE partner_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->partner_id = $row['partner_id'];
            $this->partner_code = $row['partner_code'];
            $this->partner_name = $row['partner_name'];
            $this->partner_iscustomer = $row['partner_iscustomer'];
            $this->partner_issupplier = $row['partner_issupplier'];
            $this->partner_debtor_account = $row['partner_debtor_account'];
            $this->partner_creditor_account = $row['partner_creditor_account'];
            $this->partner_bill_address = $row['partner_bill_address'];
            $this->partner_ship_address = $row['partner_ship_address'];
            $this->partner_sales_person = $row['partner_sales_person'];
            $this->partner_tel = $row['partner_tel'];
            $this->partner_tel2 = $row['partner_tel2'];
            $this->partner_fax = $row['partner_fax'];
            $this->partner_email = $row['partner_email'];
            $this->partner_currency = $row['partner_currency'];
            $this->partner_outlet = $row['partner_outlet'];
            $this->partner_remark = $row['partner_remark'];
            $this->partner_website = $row['partner_website'];
            $this->partner_credit_limit = $row['partner_credit_limit'];
            $this->partner_industry = $row['partner_industry'];
            $this->partner_seqno = $row['partner_seqno'];
            $this->partner_status = $row['partner_status'];
            $this->partner_postal_code = $row['partner_postal_code'];
            $this->partner_unit_no = $row['partner_unit_no'];
            $this->partner_account_name1 = $row['partner_account_name1'];
            $this->partner_account_name2 = $row['partner_account_name2'];
            $this->partner_account_name3 = $row['partner_account_name3'];
            $this->partner_account_name4 = $row['partner_account_name4'];
            $this->partner_house_no = $row['partner_house_no'];
            $this->partner_suburb = $row['partner_suburb'];
            $this->partner_address_type = $row['partner_address_type'];
            $this->partner_name_cn = $row['partner_name_cn'];
            $this->partner_name_thai = $row['partner_name_thai'];
            $this->partner_bill_address_cn = $row['partner_bill_address_cn'];
            $this->partner_bill_address_thai = $row['partner_bill_address_thai'];
            $this->partner_tax_no = $row['partner_tax_no'];
            $this->partner_branch_no = $row['partner_branch_no'];
            $this->partner_pulldatafromoffice = $row['partner_pulldatafromoffice'];
        }
        return $query;
    }
    public function fetchContactDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_contact WHERE contact_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->contact_id = $row['contact_id'];
            $this->contact_name = $row['contact_name'];
            $this->contact_tel = $row['contact_tel'];
            $this->contact_email = $row['contact_email'];
            $this->contact_address = $row['contact_address'];
            $this->contact_remark = $row['contact_remark'];
            $this->contact_cellphone = $row['contact_cellphone'];
            $this->contact_department = $row['contact_department'];
            $this->contact_position = $row['contact_position'];
            $this->contact_jobtitle = $row['contact_jobtitle'];
            $this->contact_forename = $row['contact_forename'];
            $this->contact_lastname = $row['contact_lastname'];
            $this->contact_seqno = $row['contact_seqno'];
            $this->contact_status = $row['contact_status'];
            $this->contact_fax = $row['contact_fax'];
        }
        return $query;
    }
    public function fetchShippingAddress($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_shipaddress WHERE shipping_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->shipping_id = $row['shipping_id'];
            $this->shipping_partner_id = $row['shipping_partner_id'];
            $this->shipping_address = $row['shipping_address'];
            $this->shipping_remark = $row['shipping_remark'];
            $this->shipping_seqno = $row['shipping_seqno'];
            $this->shipping_status = $row['shipping_status'];
            $this->shipping_name = $row['shipping_name'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_partner"," WHERE partner_id = '$this->partner_id'","Delete Partner.")){
            return true;
        }else{
            return false;
        }
    }
    public function deleteContact(){
        if($this->save->DeleteData("db_contact"," WHERE contact_id = '$this->contact_id'","Delete Contact.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->partner_seqno = 10;
            $this->partner_status = 1;
        }

        $this->countryCrtl = $this->select->getCountrySelectCtrl($this->partner_outlet);
        $this->currencyCrtl = $this->select->getCurrencySelectCtrl($this->partner_currency,'N');
        $this->debtorCrtl = $this->select->getAccountSelectCtrl($this->partner_debtor_account,'N');
        $this->creditorCrtl = $this->select->getAccountSelectCtrl($this->partner_creditor_account,'N');
        $this->employeeCrtl = $this->select->getEmployeeSelectCtrl($this->partner_sales_person,'Y');
        $this->industryCrtl = $this->select->getIndustrySelectCtrl($this->partner_industry,'Y');
        $this->addresstypeCrtl = $this->select->getAddressTypeSelectCtrl($this->partner_address_type,'N');
        $this->departmentCrtl = $this->getDepartment($this->empl_department);

        $label_col_sm = "col-sm-2";
        $field_col_sm = "col-sm-3";
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Customer Management</title>
    <?php
    include_once 'css.php';

    ?>
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <!-- include header-->
      <?php include_once 'header.php';?>
      <!-- Full Width Column -->
      <div class="content-wrapper">

          <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Client Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->partner_id > 0){ echo "Update Client";}
                else{ echo "Create New Client";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='partner.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='partner.php?action=createForm'">Create New</button>
                <?php }?>
              </div>

                <form id = 'partner_form' class="form-horizontal" action = 'partner.php?action=create' method = "POST">
                  <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li class="<?php if($this->tab == ""){ echo 'active';}?>"><a href="#general" data-toggle="tab">General</a></li>
                          <!--<li><a href="#account" data-toggle="tab">Account</a></li>-->
                          <li class="<?php if($this->tab == "address"){ echo 'active';}?>"><a href="#address" data-toggle="tab">Address</a></li>
                          <?php if($this->partner_id > 0){ ?>
                          <li class="<?php if($this->tab == "followup"){ echo 'active';}?>"><a href="#followup" data-toggle="tab">Follow Up</a></li>
                          <li class="<?php if($this->tab == "timeshift"){ echo 'active';}?>"><a href="#timeshift" data-toggle="tab">Timesheet</a></li>
                          <li class="<?php if($this->tab == "employee"){ echo 'active';}?>"><a href="#employee" data-toggle="tab">Employee</a></li>
                          <li class="<?php if($this->tab == "candidate"){ echo 'active';}?>"><a href="#candidate" data-toggle="tab">New Staff Assigned</a></li>
                          <!--<li class="<?php if($this->tab == "iv_history"){ echo 'active';}?>"><a href="#iv_history" data-toggle="tab">Sales Invoice History</a></li>-->
                          <?php }?>
                        </ul>
                <div class="tab-content">
                  <div class="<?php if($this->tab == ""){ echo 'active';}?> tab-pane" id="general">
                        <div class="form-group">

                          <label for="partner_code" class="<?php echo $label_col_sm;?> control-label">AR Number <?php echo $mandatory;?></label>
                          
                          <?php //echo "<pre>";
                                //var_dump($_SESSION);
                                if ($_SESSION['empl_group'] == "7") {?>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="partner_code" name="partner_code" value = "<?php echo $this->partner_code;?>" placeholder="Account Code">
                                    </div>

                            <?php }
                            else {?>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="partner_code" name="partner_code" value = "<?php echo $this->partner_code;?>" placeholder="Account Code" readonly>
                                    </div>
                            <?php } ?>
                                    
                                
                          <label for="partner_name" class="<?php echo $label_col_sm;?> control-label">Customer Name<?php echo $mandatory;?></label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_name" name="partner_name" value = "<?php echo $this->partner_name;?>" placeholder="Customer Name">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="partner_sales_person" class="<?php echo $label_col_sm;?> control-label">Sales Person</label>
                          <div class="col-sm-3">
                               <select class="form-control select2" id="partner_sales_person" name="partner_sales_person">
                                   <?php echo $this->employeeCrtl;?>
                               </select>
                          </div>
                          <label for="partner_industry" class="<?php echo $label_col_sm;?> control-label">Industry</label>
                          <div class="col-sm-3">
                               <select class="form-control select2" id="partner_industry" name="partner_industry">
                                   <?php echo $this->industryCrtl;?>
                               </select>
                          </div>
                        </div>

<!--                        <div class="form-group">
                          <label for="partner_currency" class="<?php echo $label_col_sm;?> control-label">Currency</label>
                          <div class="col-sm-3">
                               <select class="form-control select2" id="partner_currency" name="partner_currency">
                                   <?php echo $this->currencyCrtl;?>
                               </select>
                          </div>

                        </div>-->
                        <div class="form-group">
                          <label for="partner_seqno" class="<?php echo $label_col_sm;?> control-label">Seq No</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_seqno" name="partner_seqno" value = "<?php echo $this->partner_seqno;?>" placeholder="Seq No">
                          </div>
                          <label for="partner_status" class="<?php echo $label_col_sm;?> control-label">Status</label>
                          
                          <?php
                                if ($_SESSION['empl_group'] == "7") {?>
                          <div class="col-sm-3">
                               <select class="form-control select2" id="partner_status" name="partner_status">
                                 <option value = '1' <?php if($this->partner_status == 1){ echo 'SELECTED';}?>>Active</option>
                                 <option value = '0' <?php if($this->partner_status == 0){ echo 'SELECTED';}?>>In-active</option>
                                 <option value = '2' <?php if($this->partner_status == 2){ echo 'SELECTED';}?>>Close</option>
                               </select>
                          </div>                         
                          <?php }
                          else { ?>
                          <div class="col-sm-3">
                               <select class="form-control select2" id="partner_status" name="partner_status" readonly>
                                <?php
                                if ($this->partner_status == 1) {?>
                                 <option value = '1' <?php  echo 'SELECTED';?>>Active</option>
                                <?php }
                                if ($this->partner_status == 0) { ?> 
                                 <option value = '0' <?php echo 'SELECTED';?>>In-active</option>
                                 <?php }
                                if ($this->partner_status == 2) { ?> 
                                 <option value = '2' <?php echo 'SELECTED';?>>Close</option>
                                 <?php } ?>
                               </select>
                          </div>
                          <?php } ?>
                        </div>
<!--                        <div class="form-group">

                          <label for="partner_credit_limit" class="<?php echo $label_col_sm;?> control-label">Credit Limit</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_credit_limit" name="partner_credit_limit" value = "<?php echo $this->partner_credit_limit;?>" placeholder="Credit Limit">
                          </div>
                        </div>-->
                        <div class="form-group">
                          <label for="partner_remark" class="<?php echo $label_col_sm;?> control-label">Remark</label>
                          <div class="col-sm-3">
                                <textarea class="form-control" rows="3" id="partner_remark" name="partner_remark" placeholder="Remark"><?php echo $this->partner_remark;?></textarea>
                          </div>
<!--                          <label for="partner_tax_no" class="<?php echo $label_col_sm;?> control-label">Tax No</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_tax_no" name="partner_tax_no" value = "<?php echo $this->partner_tax_no;?>" placeholder="Tax No">
                          </div>-->
                        </div>
<!--                        <div class="form-group">
                          <label for="partner_branch_no" class="<?php echo $label_col_sm;?> control-label">Branch No.</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_branch_no" name="partner_branch_no" value = "<?php echo $this->partner_branch_no;?>" placeholder="Branch No">
                          </div>
                          <label for="partner_pulldatafromoffice" class="<?php echo $label_col_sm;?> control-label">Pull Data from office</label>
                          <div class="col-sm-3">
                            <input type="checkbox"  id="partner_pulldatafromoffice" name="partner_pulldatafromoffice" value = "1" <?php if($this->partner_pulldatafromoffice == 1){ echo 'CHECKED';}?>>
                          </div>
                        </div>-->

<!--                      <h4>Language Setting</h4>
                      <div class="form-group">
                          <div class="col-sm-5">
                              <p><u>Chinese</u></p>
                          </div>
                          <div class="col-sm-6">
                              <p><u>THAI</u></p>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="partner_name_cn" class="<?php echo $label_col_sm;?> control-label">Name(CN)</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_name_cn" name="partner_name_cn" value = "<?php echo $this->partner_name_cn;?>" placeholder="Chinese Name">
                          </div>
                          <label for="partner_name_thai" class="<?php echo $label_col_sm;?> control-label">Name (THAI)</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_name_thai" name="partner_name_thai" value = "<?php echo $this->partner_name_thai;?>" placeholder="Thailand Name">
                          </div>
                       </div>
                        <div class="form-group">
                          <label for="partner_bill_address_cn" class="<?php echo $label_col_sm;?> control-label">Bill Address (CN)</label>
                          <div class="col-sm-3">
                                <textarea class="form-control" rows="3" id="partner_bill_address_cn" name="partner_bill_address_cn" placeholder="Chinese Bill Address"><?php echo $this->partner_bill_address_cn;?></textarea>
                          </div>
                          <label for="partner_bill_address_thai" class="<?php echo $label_col_sm;?> control-label">Bill Address (THAI)</label>
                          <div class="col-sm-3">
                                <textarea class="form-control" rows="3" id="partner_bill_address_thai" name="partner_bill_address_thai" placeholder="Thailand Bill Address"><?php echo $this->partner_bill_address_thai;?></textarea>
                          </div>
                        </div>-->
                  </div><!-- /.general -->
<!--                   <div class=" tab-pane" id="account">
                        <div class="form-group">
                          <label for="partner_iscustomer" class="<?php echo $label_col_sm;?> control-label">Is Customer</label>
                          <div class="col-sm-3">
                            <input type="checkbox" class="minimal" id = 'partner_iscustomer' name = 'partner_iscustomer' value = '1' <?php if($this->partner_iscustomer == 1){ echo 'CHECKED';}?>>
                          </div>
                          <label for="partner_issupplier" class="<?php echo $label_col_sm;?> control-label">Is Supplier</label>
                          <div class="col-sm-3">
                            <input type="checkbox" class="minimal" id = 'partner_issupplier' name = 'partner_issupplier' value = '1' <?php if($this->partner_issupplier == 1){ echo 'CHECKED';}?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="partner_debtor_account" class="<?php echo $label_col_sm;?> control-label">Debtor Account</label>
                          <div class="col-sm-3">
                               <select class="form-control select2" id="partner_debtor_account" name="partner_debtor_account">
                                   <?php echo $this->debtorCrtl;?>
                               </select>
                          </div>
                          <label for="partner_creditor_account" class="<?php echo $label_col_sm;?> control-label">Creditor Account</label>
                          <div class="col-sm-3">
                               <select class="form-control select2" id="partner_creditor_account" name="partner_creditor_account">
                                   <?php echo $this->creditorCrtl;?>
                               </select>
                          </div>
                        </div>
                  </div>-->
                  <div class=" tab-pane" id="address">
                        <div class="form-group">
                          <label for="partner_postal_code" class="<?php echo $label_col_sm;?> control-label">Postal Code</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" onkeyup="checkEnter()" id="partner_postal_code" name="partner_postal_code" value = "<?php echo $this->partner_postal_code;?>" placeholder="Postal Code">
                          </div>
                          <label for="partner_website" class="<?php echo $label_col_sm;?> control-label">Web Site</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_website" name="partner_website" value = "<?php echo $this->partner_website;?>" placeholder="Web Site">
                          </div>

                        </div>
<!--                        <div class="form-group">
                          <label for="partner_house_no" class="<?php echo $label_col_sm;?> control-label">House Tel</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_house_no" name="partner_house_no" value = "<?php echo $this->partner_house_no;?>" placeholder="House Tel">
                          </div>
                          <label for="partner_account_name2" class="<?php echo $label_col_sm;?> control-label">Account Name 2</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_account_name2" name="partner_account_name2" value = "<?php echo $this->partner_account_name2;?>" placeholder="Account Name 2">
                          </div>

                        </div>-->
                        <div class="form-group">
                          <label for="partner_unit_no" class="<?php echo $label_col_sm;?> control-label">Unit No</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_unit_no" name="partner_unit_no" value = "<?php echo $this->partner_unit_no;?>" placeholder="Unit No">
                          </div>
                          <label for="partner_tel" class="<?php echo $label_col_sm;?> control-label">Tel</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_tel" name="partner_tel" value = "<?php echo $this->partner_tel;?>" placeholder="Tel">
                          </div>
<!--                          <label for="partner_account_name3" class="<?php echo $label_col_sm;?> control-label">Account Name 3</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_account_name3" name="partner_account_name3" value = "<?php echo $this->partner_account_name3;?>" placeholder="Account Name 3">
                          </div>-->
                        </div>
                        <div class="form-group">
                          <label for="partner_bill_address" class="<?php echo $label_col_sm;?> control-label">Street</label>
                          <div class="col-sm-3">
                              <textarea class="form-control" rows="3" id="partner_bill_address" name="partner_bill_address" placeholder="Billing Address" readonly><?php echo $this->partner_bill_address;?></textarea>
                          </div>

                          <label for="partner_tel2" class="<?php echo $label_col_sm;?> control-label">Mobile Number</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_tel2" name="partner_tel2" value = "<?php echo $this->partner_tel2;?>" placeholder="Mobile Number">
                          </div>
                        </div>
                        <div class="form-group">
                            
                          <label for="partner_fax" class="<?php echo $label_col_sm;?> control-label">Fax</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_fax" name="partner_fax" value = "<?php echo $this->partner_fax;?>" placeholder="Fax">
                          </div>
                           <label for="partner_email" class="<?php echo $label_col_sm;?> control-label">Email</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_email" name="partner_email" value = "<?php echo $this->partner_email;?>" placeholder="Email">
                          </div>
<!--                          <label for="partner_account_name4" class="<?php echo $label_col_sm;?> control-label">Account Name 4</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_account_name4" name="partner_account_name4" value = "<?php echo $this->partner_account_name4;?>" placeholder="Account Name 4">
                          </div>-->
                        </div>
<!--                        <div class="form-group">
                          <label for="partner_suburb" class="<?php echo $label_col_sm;?> control-label">Suburb</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="partner_suburb" name="partner_suburb" value = "<?php echo $this->partner_suburb;?>" placeholder="Suburb">
                          </div>
                        </div>-->
<!--                        <div class="form-group">

                          <label for="partner_ship_address" class="<?php echo $label_col_sm;?> control-label">Shipping Address</label>
                          <div class="col-sm-3">
                                <textarea class="form-control" rows="3" id="partner_ship_address" name="partner_ship_address" placeholder="Shipping Address"><?php echo $this->partner_ship_address;?></textarea>
                          </div>
                        </div>-->
                  </div><!-- /.address -->
                  
                  <div class=" tab-pane <?php if($this->tab == "followup"){ echo 'active';}?>" id="followup">
                              <?php echo $this->getFollowUpForm();?>
                  </div>
                  <div class=" tab-pane <?php if($this->tab == "timeshift"){ echo 'active';}?>" id="timeshift">
                              <?php echo $this->getTimeShiftForm();?>
                  </div>
                  <div class=" tab-pane <?php if($this->tab == "employee"){ echo 'active';}?>" id="employee">
                              <?php echo $this->getEmployeeForm();?>
                  </div>     
                  <div class=" tab-pane <?php if($this->tab == "candidate"){ echo 'active';}?>" id="candidate">
                              <?php echo $this->getCandidateForm();?>
                  </div>     
                  <div class=" tab-pane <?php if($this->tab == "approval"){ echo 'active';}?>" id="approval">
                              <?php echo $this->getApprovalForm();?>
                  </div>  
                  <div class="<?php if($this->tab == "iv_history"){ echo 'active';}?> tab-pane" id="iv_history">
                        <?php //$this->invoiceHistoryTable("SI");?>
                  </div><!-- /.invoice history -->
                </div><!-- /.tab-content -->
              </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer" id="box-footer" style = 'clear:both'>
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->partner_id;?>" name = "partner_id" id = "partner_id"/>
                    <?php
                    if($this->partner_id > 0){
                        $prm_code = "update";
                    }
                    else{
                        $prm_code = "create";
                    }
                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){
                    ?>
                    <button type = "submit" class="btn btn-info">Save</button>
                    <?php }?>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
          </section><!-- /.content -->
        
      </div><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';

    ?>
    <script>
      $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('editor1');
        //bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();
      });
    </script>
    
    
    <script>
    $(document).ready(function() {
        //iCheck for checkbox and radio inputs
//        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
//          checkboxClass: 'icheckbox_minimal-blue',
//          radioClass: 'iradio_minimal-blue'
//        });
        
        
        $("#partner_form").validate({
                  rules:
                  {
                      partner_name:
                      {
                          required: true
                      },
                      partner_account_code:
                      {
                          required: true,
                          remote: {
                                  url: "partner.php?action=validate_partner",
                                  type: "post",
                                  data:
                                        {
                                            partner_id: function()
                                            {
                                                return $("#partner_id").val();
                                            }
                                        }
                              }
                      },
                      partner_account_name1:
                      {
                          required: true,
                      },
                      partner_outlet:
                      {
                          required: true,
                      }
                  },
                  messages:
                  {
                      partner_name:
                      {
                          required: "Please enter name."
                      },
                      partner_account_code:
                      {
                          required: "Please enter Partner Account Code.",
                          remote: "Partner Account Code duplicate."
                      },
                      partner_outlet:
                      {
                          required: "Please select country."
                      }
                  }
              });
              
             

});

        $('#applicant_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
        
        $('#empl_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
        $('#timeshift_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
        $('#partnerfollowup_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
    </script>
    
    
        <script>
    $(document).ready(function() {   
            
        $('#empl_email').keyup(function(){
            $('#empl_login_email').val($(this).val());
        });
        $("#partner_form").validate({
                  rules: 
                  {
                      empl_name:
                      {
                          required: true
                      },
                      empl_group:
                      {
                          required: true
                      },
                      empl_nric:
                      {
                          required: true
                      },
                      empl_login_email:
                      {
                          required: true,
                          remote: {
                                  url: "partner.php?action=validate_email",
                                  type: "post",
                                  data: 
                                        {
                                            empl_id: function()
                                            {
                                                return $("#empl_id").val();
                                            }
                                        }
                              }
                      },
                      empl_login_password:
                      {
                        required: true,
                      },
                      empl_login_password_cm:
                      {
                        required: true,
                        minlength : 5,
                        equalTo : "#empl_login_password"
                      },
                      empl_sex:
                      {
                        required: true,
                      }
                      
                  },
                  messages:
                  {
                      empl_name:
                      {
                          required: "Please enter name."
                      },
                      customer_login_id:
                      {
                          required: "Please enter customer login email.",
                          remote: "Login email duplicate."
                      },
                      customer_login_password:
                      {
                            required: "Please enter Password."
                      },
                      customer_confirmpassword:
                      {
                            required: "Please enter Confirm Password."
                      }
                  }
              });




            $('.save_partner_follow_btn').click(function(){
                var description = CKEDITOR.instances['editor1'].getData();
                var data = "action=saveFollowup&partner_id=<?php echo $this->partner_id;?>&pfollow_createby="+$('#pfollow_createby').val()+"&pfollow_create_time="+$('#pfollow_create_time').val()+"&pfollow_create_date="+$('#pfollow_create_date').val();
                    data = data + "&pfollow_description="+description+ "&pfollow_id="+$('#pfollow_id').val();  
                $.ajax({ 
                    type: 'POST',
                    url: 'partner.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&partner_id={$_REQUEST['partner_id']}&tab=followup";?>&current_tab=followup';
                           window.location.href = url + "&current_tab=" + $('#current_tab').val();
                       }
                       else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
         
            $('.save_timeshift_btn').click(function(){
                var data = "action=saveTimeShift&partner_id=<?php echo $this->partner_id;?>&department_name="+$('#department_name').val()+"&working_day="+$('#working_day').val()+"&start_time="+$('#start_time').val()+"&end_time="+$('#end_time').val();
                    data = data + "&ot_rate="+$('#ot_rate').val() + "&salary_date=" +$('#salary_date').val() + "&timeshift_description="+$('#timeshift_description').val() + "&timeshift_id="+$('#timeshift_id').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'partner.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&partner_id={$_REQUEST['partner_id']}&tab=timeshift";?>&current_tab=timeshift';
                           window.location.href = url;
                       }
                       else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });       
            
            $('.save_empl_btn').click(function(){
                var x = document.getElementById("empl_name").value;
                var y = document.getElementById("empl_nric").value;
                var z = document.getElementById("empl_sex").value;
                var d = document.getElementById("empl_department").value;
                var login_email = document.getElementById("empl_login_email").value;
                var login_password = document.getElementById("empl_login_password").value;
                var login_conform_Password = document.getElementById("empl_login_password_cm").value;
                if (x == "") {
                    alert("Name must be filled out");
                    return false;
                }
                if (y == "") {
                    alert("NRIC must be filled out");
                    return false;
                }
                if (z == "") {
                    alert("Please select your gender");
                    return false;
                }
                if (d == "") {
                    alert("Please select your department");
                    return false;
                }
                if (login_email == "") {
                    alert("Login Email must be filled out");
                    return false;
                }
                if (login_password == "") {
                    alert("Login Password must be filled out");
                    return false;
                }
                if (login_password != login_conform_Password) {
                    alert("Please enter the same password in Conform Password.");
                    return false;
                }
                var data = "action=saveEmpl&partner_id=<?php echo $this->partner_id;?>&empl_name="+$('#empl_name').val()+"&empl_nric="+$('#empl_nric').val()+"&empl_sex="+$('#empl_sex').val()+"&empl_mobile="+$('#empl_mobile').val();
                    data = data + "&empl_email="+$('#empl_email').val() + "&empl_department="+$('#empl_department').val() + "&empl_remark="+$('#empl_remark').val() + "&empl_login_email="+$('#empl_login_email').val()+ "&empl_login_password="+$('#empl_login_password').val();
                    data = data + "&empl_id="+$('#empl_id').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'partner.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&partner_id={$_REQUEST['partner_id']}&tab=employee";?>&current_tab=employee';
                           window.location.href = url;
                       }
                       else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                      if($('#empl_department :selected').val() != null) {
                           $("#empl_department").select2().select2('val',$('#empl_department :selected').val());
                        }
                       
                    }		
                 });
                 return false;
            });   
            
            $('.save_candidate_btn').click(function(){
                var data = "action=updateCandidate&partner_id=<?php echo $this->partner_id;?>&applicant_leave_approved1="+$('#applicant_leave_approved1').val()+"&applicant_leave_approved2="+$('#applicant_leave_approved2').val();
                    data = data +"&applicant_leave_approved3="+$('#applicant_leave_approved3').val()+"&applicant_claims_approved1="+$('#applicant_claims_approved1').val();
                    data = data +"&applicant_claims_approved2="+$('#applicant_claims_approved2').val()+"&applicant_claims_approved3="+$('#applicant_claims_approved3').val()+"&applicant_id="+$('#applicant_id').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'partner.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&partner_id={$_REQUEST['partner_id']}&tab=candidate";?>';
                           window.location.href = url;
                       }
                       else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                      if($('#empl_department :selected').val() != null) {
                           $("#empl_department").select2().select2('val',$('#empl_department :selected').val());
                        }
                       
                    }		
                 });
                 return false;
            });
            

});
    </script>
    
    
    
<script type = "text/javascript">
			function checkEnter() {
				var x =  document.getElementById('partner_postal_code').value;
				function Cloud(){
					jQuery.ajax({
						url: 'https://developers.onemap.sg/commonapi/search?searchVal='+x+'&returnGeom=Y&getAddrDetails=Y&pageNum=1',
						success: function(result){
							var TrueResult = JSON.stringify(result);
							jQuery.each(jQuery.parseJSON(TrueResult), function (item, value) {
								if (item == "results") {
									jQuery.each(value, function (i, object) {
										jQuery.each(object, function (subI, subObject) {
											if (subI == 'ADDRESS'){
												jQuery('#partner_bill_address').val(subObject); 
											} 
										});
									});
								}
							});
						}});
				}
				Cloud();
			}
</script>
		
    
    
    
    
  </body>
</html>
        <?php

    }
    public function getListing(){
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Customer Management</title>
    <?php
    include_once 'css.php';

    ?>
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <!-- include header-->
      <?php include_once 'header.php';?>
      <!-- Full Width Column -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Customer Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Customer Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='partner.php?action=createForm'">Create New + </button>
                <?php }?>
                <button style = 'margin-right:10px;' class="btn btn-primary pull-right import_btn" data-toggle="modal" data-target="#myModal">Import + </button>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="partner_table" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                        <th>Cust. Code</th>
                        <th>Cust. Name</th>
                        <th>Sales Person</th>
                        <th>Tel</th>
                        <th>Industry</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $sql = "SELECT partner.*,cy.currency_code,country.country_code
                              FROM db_partner partner
                              LEFT JOIN db_currency cy ON cy.currency_id = partner.partner_currency
                              LEFT JOIN db_country country ON country.country_id = partner.partner_outlet
                              WHERE partner.partner_id > 0 ORDER BY partner.partner_code";
//                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                    <?php
                        $i++;
                      }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Acc. Code</th>
                        <th>Name</th>
                        <th>Sales Person</th>
                        <th>Tel</th>
                        <th>Industry</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </tfoot>
                  </table>
                    
                            
                    <div class="modal-body">
                         <h4 class="modal-title">Clients Remarks</h4>
                        <div id = 'premarks_content'></div>
                    </div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper --><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    
    
         <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="width:70%; margin-top: 10%">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;background-color: #377506; color:fff">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Employer Remarks</h4>
        </div>
        <div class="modal-body" style="background-color: #ecfff2;height: 550px;overflow-y: scroll;">
            <div id = 'premarks_content'></div>

        </div>
        <div class="modal-footer" style="background-color: #377506; padding: 10px;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
    
    
    
    
    <?php
    include_once 'js.php';
    ?>
    <script type="text/javascript" src="http://www.sanwebe.com/assets/public/js/jquery.form.min.js"></script>
    <script>
 jQuery.fn.dataTableExt.oApi.fnReloadAjax = function ( oSettings, sNewSource, fnCallback, bStandingRedraw )
{
    // DataTables 1.10 compatibility - if 1.10 then `versionCheck` exists.
    // 1.10's API has ajax reloading built in, so we use those abilities
    // directly.
    if ( jQuery.fn.dataTable.versionCheck ) {
        var api = new jQuery.fn.dataTable.Api( oSettings );

        if ( sNewSource ) {
            api.ajax.url( sNewSource ).load( fnCallback, !bStandingRedraw );
        }
        else {
            api.ajax.reload( fnCallback, !bStandingRedraw );
        }
        return;
    }

    if ( sNewSource !== undefined && sNewSource !== null ) {
        oSettings.sAjaxSource = sNewSource;
    }

    // Server-side processing should just call fnDraw
    if ( oSettings.oFeatures.bServerSide ) {
        this.fnDraw();
        return;
    }

    this.oApi._fnProcessingDisplay( oSettings, true );
    var that = this;
    var iStart = oSettings._iDisplayStart;
    var aData = [];

    this.oApi._fnServerParams( oSettings, aData );

    oSettings.fnServerData.call( oSettings.oInstance, oSettings.sAjaxSource, aData, function(json) {
        /* Clear the old information from the table */
        that.oApi._fnClearTable( oSettings );

        /* Got the data - add it to the table */
        var aData =  (oSettings.sAjaxDataProp !== "") ?
            that.oApi._fnGetObjectDataFn( oSettings.sAjaxDataProp )( json ) : json;

        for ( var i=0 ; i<aData.length ; i++ )
        {
            that.oApi._fnAddData( oSettings, aData[i] );
        }

        oSettings.aiDisplay = oSettings.aiDisplayMaster.slice();

        that.fnDraw();

        if ( bStandingRedraw === true )
        {
            oSettings._iDisplayStart = iStart;
            that.oApi._fnCalculateEnd( oSettings );
            that.fnDraw( false );
        }

        that.oApi._fnProcessingDisplay( oSettings, false );

        /* Callback user function - for event handlers etc */
        if ( typeof fnCallback == 'function' && fnCallback !== null )
        {
            fnCallback( oSettings );
        }
    }, oSettings );
    

    
};
      $(function () {

          
          
          
        var partner_table = $('#partner_table').DataTable({
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "partner.php?action=getDataTable",
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "iDisplayLength": 25,
                "aoColumns": [
                      null,
                      null,
                      null,
                      null,
                      null,
                      null,
                      {"sClass": "text-align-right" }
                  ],
                  "fnDrawCallback": function( oSettings ) {
                             $('.premarks').on('click',function(){
                                
                                var data = "action=getRemarkDetail&partner_id="+$(this).attr("pid");
  
                                     $.ajax({ 
                                        type: 'POST',
                                        url: 'partner.php',
                                        cache: false,
                                        data:data,
                                        error: function(xhr) {
                                            alert("System Error.");
                                            issend = false;
                                        },
                                        success: function(data) {
                                           var jsonObj = eval ("(" + data + ")");
                                           
                                           var table = "";

                                            if(jsonObj['pRemarks'] != null)
                                            {
                                               // table = "<a href='partner.php?action=edit&tab=followup&partner_id=" + jsonObj['pRemarks']['partner_id'][0] + "'><button type='button' class='btn btn-info' style= 'float:right'>Add Remarks</button></a><br><br>";
                                           table = table + "<table id='" + "empl_remark_table' " + "class=" + "'table table-bordered table-hover'" + "><thead><tr> <th style = 'width:2%'>No</th><th style = 'width:5%'>Create By</th><th style = 'width:15%'>Description</th><th style = 'width:5%'>Create Time</th>";
                                               table = table + "<th style = 'width:5%'>Create Date</th></tr></thead><tbody>";

                    //                    console.log(jsonObj['createby']);
                    //                    console.log(jsonObj['assign_time']);
                                            var n = 1;
                                        for(var i=0;i<jsonObj['pRemarks']['empl_name'].length; i++){
                                        table = table + "<tr><td>" + "<a href='partner.php?action=edit&tab=followup&partner_id=" + jsonObj['pRemarks']['partner_id'][i] +"&pfollow_id=" + jsonObj['pRemarks']['pfollow_id'][i] + "&edit=1'>" + n +"</a></td><td>" + jsonObj['pRemarks']['empl_name'][i] +"</td><td>" + jsonObj['pRemarks']['pfollow_description'][i] + "</td><td>" + jsonObj['pRemarks']['time'][i] + "</td><td>" + jsonObj['pRemarks']['date'][i] + "</td></tr>" 
                                        n++;
                                        }
                                        table = table + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th><th style = 'width:5%'>Create By</th><th style = 'width:15%'>Description</th><th style = 'width:5%'>Create Time</th>";
                                               table = table + "<th style = 'width:5%'>Create Date</th></tr></tfoot></tbody></table>";
                                           }
                                           else
                                           {
                                               table = table + "<p>No have any remarks.</p>";
                                           }
                                               
                                        $('#premarks_content').html(table);

                                              $(function () {
                                                $('#empl_remark_table').DataTable({
                                                  "paging": true,
                                                  "lengthChange": false,
                                                  "searching": true,
                                                  "ordering": true,
                                                  "info": true,
                                                  "autoWidth": false
                                                });
                                              });      
                                        
                                                
                                           issend = false;
                                        }		
                                     });
                                     return false;
                                
                                
                                
                                
                              })
                  }
        });

           $('#uploadForm').submit(function(e) {
               if($('#import_action').val() == ""){
                   alert('Please Select Import Type.');
                   $('#import_action').focus();
                   return false;
               }
                if($('#import_file').val()){
                    e.preventDefault();
                    $('#loader-icon').show();
                    $(this).ajaxSubmit({
                        target:   '#targetLayer',
                        beforeSubmit: function() {
                            $("#targetLayer").html("<img style = 'width:100px;' src = 'dist/img/LoaderIcon.gif'/>");
                            $(".import_btn").val("Importing.......");
//                            $(".import_btn").attr("disabled",true);
                        },
                        success:function (data){
                            jsonObj = eval('('+ data +')');
                            if(jsonObj.status == 1){
                                $("#targetLayer").html("<font color = 'green'><b>Import Success. &nbsp;&nbsp;&nbsp;" + jsonObj.data + " rows effect.</b></font>");
                                partner_table.ajax.url("partner.php?action=getDataTable");
                                partner_table.draw();
                            }else{
                                $("#targetLayer").html("<font color = 'red'><b>Import Fail.</b></font>");
                            }
                            $(".import_btn").val("Import");
//                            $(".import_btn").attr("disabled",false);
                        },
                        resetForm: true
                    });
                    return false;
                }
            });
            
            
      });

    </script>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Import Product</h4>
            </div>
                <div id="bar_blank">
   <div id="bar_color"></div>
  </div>
              <div id="status"></div>
            <form id = 'uploadForm' action = 'partner.php' method = "POST" >
                <div class="modal-body">
                    <b>Import Type</b>
                    <select name = 'import_action' id = 'import_action' class = 'form-control'>
                        <option value = ''>Select One</option>
                        <option value = 'Customer'>Customer</option>
                        <option value = 'Contact'>Contact</option>
                    </select>
                    <br>
                    <input type = "file" name = 'import_file' id = 'import_file' style = 'display:inline;'/>

                    <input type = 'hidden' value ='import'  name = 'action' style = 'display:inline;'/>

                    <div id="targetLayer" style = 'display:inline;'></div>
                    <br>Xls,Csv file only.
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <input type = 'submit' class="btn btn-primary pull-right import_btn" value ='Import' />
                </div>
            </form>
          </div>

        </div>
    </div>
  </body>
</html>
    <?php
    }
    public function getContact(){
        global $mandatory;
        if($this->contact_id <= 0){
            $this->contact_seqno = 10;
            $this->contact_status = 1;
            $action = "create_contact";
        }else{
            $action = "update_contact";
        }

    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Customer Management</title>
    <?php
    include_once 'css.php';

    ?>
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <!-- include header-->
      <?php include_once 'header.php';?>
      <!-- Full Width Column -->
      <div class="">
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Customer Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->contact_id > 0){ echo "Update Contact Person";}else{ echo "Create New Contact Person";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='partner.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='partner.php?action=createForm'">Create New Customer</button>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='partner.php?action=contact&partner_id=<?php echo $this->partner_id;?>'">Create New Contact</button>
                <?php }?>
              </div>

                <form id = 'contact_form' class="form-horizontal" action = 'partner.php?action=create_contact' method = "POST">
                  <div class="box-body">
                        <div class="form-group">
                          <label for="contact_tel" class="col-sm-1 control-label">Partner Code</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" value = "<?php echo $this->partner_code;?>"  disabled>
                          </div>
                          <label for="contact_email" class="col-sm-1 control-label">Partner Name</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" value = "<?php echo $this->partner_name;?>"  disabled>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="contact_name" class="col-sm-1 control-label">Name <?php echo $mandatory;?></label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="contact_name" name="contact_name" value = "<?php echo $this->contact_name;?>" placeholder="Contact Name">
                          </div>
                          <label for="contact_fax" class="col-sm-1 control-label">Fax</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="contact_fax" name="contact_fax" value = "<?php echo $this->contact_fax;?>" placeholder="Contact Fax">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="contact_tel" class="col-sm-1 control-label">Tel</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="contact_tel" name="contact_tel" value = "<?php echo $this->contact_tel;?>" placeholder="Contact Tel">
                          </div>
                          <label for="contact_email" class="col-sm-1 control-label">Email</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="contact_email" name="contact_email" value = "<?php echo $this->contact_email;?>" placeholder="Contact Email">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="contact_seqno" class="col-sm-1 control-label">Seq No</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="contact_seqno" name="contact_seqno" value = "<?php echo $this->contact_seqno;?>" placeholder="Contact Seq No">
                          </div>
                          <label for="contact_status" class="col-sm-1 control-label">Status</label>
                          <div class="col-sm-3">
                               <select class="form-control select2" id="contact_status" name="contact_status">
                                 <option value = '1' <?php if($this->contact_status == 1){ echo 'SELECTED';}?>>Active</option>
                                 <option value = '0' <?php if($this->contact_status == 0){ echo 'SELECTED';}?>>In-active</option>
                               </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="contact_address" class="col-sm-1 control-label">Address</label>
                          <div class="col-sm-3">
                                <textarea class="form-control" rows="3" id="contact_address" name="contact_address" placeholder="Contact Address"><?php echo $this->contact_address;?></textarea>
                          </div>
                          <label for="contact_remark" class="col-sm-1 control-label">Remark</label>
                          <div class="col-sm-3">
                                <textarea class="form-control" rows="3" id="contact_remark" name="contact_remark" placeholder="Contact Remark"><?php echo $this->contact_remark;?></textarea>
                          </div>
                        </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->contact_id;?>" name = "contact_id" id = "contact_id"/>
                    <input type = "hidden" value = "<?php echo $this->partner_id;?>" name = "partner_id" id = "partner_id"/>
                    <?php
                    if($this->partner_id > 0){
                        $prm_code = "update";
                    }else{
                        $prm_code = "create";
                    }
                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){
                    ?>
                    <button type = "submit" class="btn btn-info">Save</button>
                    <?php }?>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
          </section><!-- /.content -->

          <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Contact Person Table</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="partner_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:15%'>Name</th>
                        <th style = 'width:10%'>Tel</th>
                        <th style = 'width:15%'>Email</th>
                        <th style = 'width:15%'>Address</th>
                        <th style = 'width:15%'>Remark</th>
                        <th style = 'width:10%'>Status</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $sql = "SELECT contact.*
                              FROM db_contact contact
                              WHERE contact.contact_partner_id = '$this->partner_id' AND contact.contact_status = '1'";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['contact_name'];?></td>
                            <td><?php echo $row['contact_tel'];?></td>
                            <td><?php echo $row['contact_email'];?></td>
                            <td><?php echo nl2br($row['contact_address']);?></td>
                            <td><?php echo nl2br($row['contact_remark']);?></td>
                            <td><?php if($row['contact_status'] == 1){ echo 'Active';}else{ echo 'In-Active';}?></td>
                            <td class = "text-align-right">
                                <?php
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'partner.php?action=edit_contact&partner_id=<?php echo $this->partner_id;?>&contact_id=<?php echo $row['contact_id'];?>'">Edit</button>
                                <?php }?>
                                <?php
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('partner.php?action=delete_contact&partner_id=<?php echo $this->partner_id;?>&contact_id=<?php echo $row['contact_id'];?>','Confirm Delete?')">Delete</button>
                                <?php }?>
                            </td>
                        </tr>
                    <?php
                        $i++;
                      }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Tel</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Remark</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
        </div><!-- /.container -->
      </div><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';

    ?>
    <script>
    $(document).ready(function() {
        $('#partner_table').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "iDisplayLength": 50
        });
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        $("#contact_form").validate({
                  rules:
                  {
                      contact_name:
                      {
                          required: true
                      }
                  },
                  messages:
                  {
                      contact_name:
                      {
                          required: "Please enter Contact Name."
                      }
                  }
              });


});
    </script>
  </body>
</html>
        <?php

    }
    public function getShippingAddress(){
        global $mandatory;
        if($this->shipping_id <= 0){
            $this->shipping_seqno = 10;
            $this->shipping_status = 1;
            $action = "create_shipping";
        }else{
            $action = "update_shipping";
        }

    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Customer Management</title>
    <?php
    include_once 'css.php';

    ?>
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
      <!-- include header-->
      <?php include_once 'header.php';?>
      <!-- Full Width Column -->
      <div class="content-wrapper">
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Customer Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->contact_id > 0){ echo "Update Shipping Address";}else{ echo "Create New Shipping Address";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='partner.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='partner.php?action=createForm'">Create New Partner</button>
                <button type = "button" class="btn btn-primary btn-warning pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='partner.php?action=contact&partner_id=<?php echo $this->partner_id;?>'">Create New Contact</button>
                <button type = "button" class="btn  bg-purple pull-right" style = 'margin-right:10px;' onclick = "window.location.href='partner.php?action=shipping_address&partner_id=<?php echo $this->partner_id;?>'">Create New Shipping Address</button>
                <?php }?>
              </div>

                <form id = 'contact_form' class="form-horizontal" action = 'partner.php?action=create_contact' method = "POST">
                  <div class="box-body">
                        <div class="form-group">
                          <label for="partner_code" class="col-sm-1 control-label">Partner Code</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" value = "<?php echo $this->partner_code;?>"  disabled>
                          </div>
                          <label for="partner_name" class="col-sm-1 control-label">Partner Name</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" value = "<?php echo $this->partner_name;?>"  disabled>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="shipping_name" class="col-sm-1 control-label">Shipping Name</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="shipping_name" name="shipping_name" value = "<?php echo $this->shipping_name;?>"  >
                          </div>

                        </div>
                        <div class="form-group">
                          <label for="shipping_seqno" class="col-sm-1 control-label">Seq No</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="shipping_seqno" name="shipping_seqno" value = "<?php echo $this->shipping_seqno;?>" placeholder="Shipping Seq No">
                          </div>
                          <label for="shipping_status" class="col-sm-1 control-label">Status</label>
                          <div class="col-sm-3">
                               <select class="form-control select2" id="contact_status" name="shipping_status">
                                 <option value = '1' <?php if($this->shipping_status == 1){ echo 'SELECTED';}?>>Active</option>
                                 <option value = '0' <?php if($this->shipping_status == 0){ echo 'SELECTED';}?>>In-active</option>
                               </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="shipping_address" class="col-sm-1 control-label">Shipping Address</label>
                          <div class="col-sm-3">
                                <textarea class="form-control" rows="3" id="contact_address" name="shipping_address" placeholder="Shipping Address"><?php echo $this->shipping_address;?></textarea>
                          </div>
                          <label for="shipping_remark" class="col-sm-1 control-label">Remark</label>
                          <div class="col-sm-3">
                                <textarea class="form-control" rows="3" id="contact_remark" name="shipping_remark" placeholder="Shipping Remark"><?php echo $this->shipping_remark;?></textarea>
                          </div>
                        </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->shipping_id;?>" name = "shipping_id" id = "shipping_id"/>
                    <input type = "hidden" value = "<?php echo $this->partner_id;?>" name = "partner_id" id = "partner_id"/>
                    <?php
                    if($this->partner_id > 0){
                        $prm_code = "update";
                    }else{
                        $prm_code = "create";
                    }
                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){
                    ?>
                    <button type = "submit" class="btn btn-info">Save</button>
                    <?php }?>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
          </section><!-- /.content -->

          <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Shipping Address Table</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="partner_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                         <th style = 'width:15%'>Shipping Name</th>
                        <th style = 'width:25%'>Shipping Address</th>
                        <th style = 'width:35%'>Remark</th>
                        <th style = 'width:10%'>Status</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $sql = "SELECT sp.*
                              FROM db_shipaddress sp
                              WHERE sp.shipping_partner_id = '$this->partner_id' AND sp.shipping_status = '1'";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['shipping_name'];?></td>
                            <td><?php echo nl2br($row['shipping_address']);?></td>
                            <td><?php echo nl2br($row['shipping_remark']);?></td>
                            <td><?php if($row['shipping_status'] == 1){ echo 'Active';}else{ echo 'In-Active';}?></td>
                            <td class = "text-align-right">
                                <?php
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'partner.php?action=edit_shipping_address&partner_id=<?php echo $this->partner_id;?>&shipping_id=<?php echo $row['shipping_id'];?>'">Edit</button>
                                <?php }?>
                                <?php
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('partner.php?action=delete_shipping_address&partner_id=<?php echo $this->partner_id;?>&shipping_id=<?php echo $row['shipping_id'];?>','Confirm Delete?')">Delete</button>
                                <?php }?>
                            </td>
                        </tr>
                    <?php
                        $i++;
                      }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:15%'>Shipping Name</th>
                        <th style = 'width:25%'>Shipping Address</th>
                        <th style = 'width:35%'>Remark</th>
                        <th style = 'width:10%'>Status</th>
                        <th></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
        </div><!-- /.container -->
      </div><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';

    ?>
    <script>
    $(document).ready(function() {
        $('#partner_table').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "iDisplayLength": 50
        });
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        $("#contact_form").validate({
                  rules:
                  {
                      contact_name:
                      {
                          required: true
                      }
                  },
                  messages:
                  {
                      contact_name:
                      {
                          required: "Please enter Contact Name."
                      }
                  }
              });


});
    </script>
  </body>
</html>
        <?php

    }
    public function getDataTable(){
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */

	$aColumns = array('partner_code','partner_name','empl_code','partner_tel','industry_code','partner_status','');

        
        $bColumns = array('partner_code','partner_name','partner_iscustomer','partner_issupplier',
                     'partner_bill_address','partner_ship_address','partner_sales_person','partner_tel',
                     'partner_fax','partner_email','partner_currency','partner_outlet',
                     'partner_remark','partner_website','partner_credit_limit','partner_industry',
                     'partner_debtor_account','partner_creditor_account','partner_seqno','partner_status',
                     'partner_tel2','partner_postal_code','partner_unit_no',
                     'partner_account_name1','partner_account_name2','partner_account_name3','partner_account_name4',
                     'partner_house_no','partner_suburb','partner_address_type','partner_group',
                     'partner_name_cn','partner_name_thai','partner_bill_address_cn','partner_bill_address_thai',
                     'partner_tax_no','partner_branch_no','partner_pulldatafromoffice');
        
        
        
        
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "partner_id";

	/* DB table to use */
        $sTable = "db_partner";
        /*
	 * Paging
	 */
	$sLimit = "";
	if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
		$sLimit = "LIMIT ".mysql_real_escape_string($_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}

	/*
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if($_GET['sSearch'] != ""){
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($bColumns) ; $i++ ){
                        if($bColumns[$i] == 'No' || $bColumns[$i] == ""){
                            continue;
                        }
			$sWhere .= $bColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}

	/* Individual column filtering */
	for ($i=0;$i<count($aColumns);$i++){
                if($aColumns[$i] == 'No' || $aColumns[$i] == ""){
                    continue;
                }
		if($_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != ''){
			if ($sWhere == "" ){
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
        if(isset($_GET['iSortCol_0'])){
//            if($_GET['iSortCol_0'] != 0){
		$sOrder = "ORDER BY  ";
		for($i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ ){
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" ){
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}

		$sOrder = substr_replace( $sOrder, "", -2 );
		if($sOrder == "ORDER BY" ){
			$sOrder = "";
		}
//            }

	}else{
            $sOrder = "ORDER BY partner.partner_account_code,product.partner_account_name1";
        }

	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
                SELECT SQL_CALC_FOUND_ROWS partner.*,cy.currency_code,industry.industry_code,empl.empl_code
                FROM db_partner partner
                LEFT JOIN db_currency cy ON cy.currency_id = partner.partner_currency
                LEFT JOIN db_industry industry ON industry.industry_id = partner.partner_industry
                LEFT JOIN db_empl empl ON empl.empl_id = partner.partner_sales_person
		$sWhere
		$sOrder
		$sLimit
	";

	$rResult = mysql_query($sQuery);

	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query($sQuery);
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];

	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
	$rResultTotal = mysql_query($sQuery);
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];


	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
        $b = $_GET['iDisplayStart']+1;
	while ($aRow = mysql_fetch_array($rResult)){
		$row = array();
		for ($i=0;$i<7;$i++){
			if($aColumns[$i] == "No" ){
				$row[] = $b;
			}else if($aColumns[$i] != ""){
                            if($aColumns[$i] == 'partner_status'){
                                if($aRow[$aColumns[$i]] == 1){
                                    $row[] = "Active";
                                }else{
                                    $row[] = "In-Active";
                                }
                            }else{
                                $row[] = escape($aRow[$aColumns[$i]]);
                            }
			}else{
                           $btn = "";   
                            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){ 
                             $btn =  " <a href='partner.php?action=edit&tab=followup&partner_id={$aRow['partner_id']}\"'><button type='button' id='Btn' class='btn btn-primary btn-warning'>Add Remarks</button></a>";
                            }
                           if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'view')){
                               
                             $btn .= " <button type='button' id='Btn' class='btn btn-primary btn-warning premarks' style='background-color: #8BC34A; border-color: #4CAF50;' pid={$aRow['partner_id']}>Remarks</button>";
                           }
                           if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){
                               
//                             $btn = "<button type='button' class='btn  bg-purple ' onclick = 'location.href = \"partner.php?action=shipping_address&partner_id={$aRow['partner_id']}\"'>Shipping Address</button>";
                             $btn .= " <button type='button' style='background-color: #7da1a7; border-color: #6b7e96;' class='btn btn-primary btn-warning ' onclick = 'location.href = \"partner.php?action=contact&partner_id={$aRow['partner_id']}\"'>Contact</button>";
                           }
                           if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                             $btn .= " <button type='button' class='btn btn-primary btn-info ' onclick = 'location.href = \"partner.php?action=edit&partner_id={$aRow['partner_id']}\"'>Edit</button>";
                           }
                           if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                             $btn .= " <button type='button' class='btn btn-primary btn-danger' onclick = 'confirmAlertHref(\"partner.php?action=delete&partner_id={$aRow['partner_id']}\",\"Confirm Delete?\")'>Delete</button>";
                           }
                                $row[] = $btn;
                        }
		}
		$output['aaData'][] = $row;
                $b++;
	}

	echo json_encode($output);
    }
    public function getPartnerDetailTransaction(){

        $partner_query = $this->fetchPartnerDetail(" AND partner_id = '$this->partner_id'","","",0);

        if($row = mysql_fetch_array($partner_query)){
            return $row;
        }else{
            return null;
        }
    }
    public function orderHistoryTable($document_type){
        include_once 'class/Order.php';
        $order = new Order();
        if($document_type == 'SO'){
            $document_name = 'Sales Order';
            $partner_field = 'Customer';
            $document_url = 'sales_order.php';
        }else if($document_type == 'DO'){
            $document_name = 'Delivery Order';
            $partner_field = 'Customer';
            $document_url = 'delivery_order.php';
        }else if($document_type == 'QT'){
            $document_name = 'Quotation';
            $partner_field = 'Customer';
            $document_url = 'quotation.php';
        }else if($document_type == 'PO'){
            $document_name = 'Purchase Order';
            $partner_field = 'Supplier';
            $document_url = 'purchase_order.php';
        }
    ?>
    <div class="box">
        <div class="box-header">
          <div class = "pull-left"><h3 class="box-title"><?php echo $document_name;?> History Table</h3></div>
          <div class = "pull-right">

          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="partner_table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th style = 'width:3%'>No</th>
                <th style = 'width:15%'>Document No</th>
                <th style = 'width:10%'>Date</th>
                <th style = 'width:15%'><?php echo $partner_field;?></th>
                <th style = 'width:15%'>Sales Person</th>
                <th style = 'width:15%'>Sub Total</th>
                <th style = 'width:10%'>Tax</th>
                <th style = 'width:10%'>Grand Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <?php
              $sql = "SELECT o.*,partner.partner_account_name1 as partner_name,empl.empl_name
                      FROM db_order o
                      INNER JOIN db_partner partner ON partner.partner_id = o.order_customer
                      LEFT JOIN db_empl empl ON empl.empl_id = o.order_salesperson
                      WHERE order_prefix_type = '$document_type' AND o.order_status = '1' AND o.order_customer = '$this->partner_id'
                      ORDER BY o.order_date DESC,o.order_no DESC";
              $query = mysql_query($sql);
              $i = 1;
              while($row = mysql_fetch_array($query)){
                  $order->order_id = $row['order_id'];
                  if($row['order_revtimes'] > 0){
                      $order_no = $row['order_no'] . " (Rev {$row['order_revtimes']})";
                  }else{
                      $order_no = $row['order_no'];
                  }
            ?>
                <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $order_no;?></td>
                    <td><?php echo $row['order_date'];?></td>
                    <td><?php echo $row['partner_name'];?></td>
                    <td><?php echo $row['empl_name'];?></td>
                    <td><?php echo $order->getSubTotalAmt() - $order->getTotalDiscAmt();?></td>
                    <td><?php echo $order->getTotalGstAmt();?></td>
                    <td><?php echo num_format(($order->getSubTotalAmt() - $order->getTotalDiscAmt()) + $order->getTotalGstAmt());?></td>
                    <td class = "text-align-right">
                        <?php
                        if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                        ?>
                        <button type="button" class="btn btn-primary btn-info " onclick = "location.href = '<?php echo $document_url;?>?action=edit&order_id=<?php echo $row['order_id'];?>'">View</button>
                        <?php }?>
                    </td>
                </tr>
            <?php
                $i++;
              }
            ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    public function invoiceHistoryTable($document_type){

      include_once 'class/Invoice.php';
      $invoice = new Invoice();
      $document_name = 'Tax Invoice';
      $partner_field = 'Customer';
      $document_url = 'sales_invoice.php';
    ?>
    <div class="box">
        <div class="box-header">
          <div class = "pull-left"><h3 class="box-title"><?php echo $document_name;?> History Table</h3></div>
          <div class = "pull-right">

          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="partner_table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th style = 'width:3%'>No</th>
                <th style = 'width:15%'>Document No</th>
                <th style = 'width:10%'>Date</th>
                <th style = 'width:15%'><?php echo $partner_field;?></th>
                <th style = 'width:15%'>Sales Person</th>
                <th style = 'width:15%'>Sub Total</th>
                <th style = 'width:10%'>Tax</th>
                <th style = 'width:10%'>Grand Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <?php
              $sql = "SELECT i.*,partner.partner_name,empl.empl_name
                      FROM db_invoice i
                      INNER JOIN db_partner partner ON partner.partner_id = i.invoice_customer
                      LEFT JOIN db_empl empl ON empl.empl_id = i.invoice_salesperson
                      WHERE i.invoice_status = '1' AND i.invoice_customer = '$this->partner_id'
                      ORDER BY i.invoice_date DESC,i.invoice_no DESC";
              $query = mysql_query($sql);
              $i = 1;
              while($row = mysql_fetch_array($query)){
                  $invoice->invoice_id = $row['invoice_id'];
            ?>
                <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $row['invoice_no'];?></td>
                    <td><?php echo $row['invoice_date'];?></td>
                    <td><?php echo $row['partner_name'];?></td>
                    <td><?php echo $row['empl_name'];?></td>
                    <td><?php echo $invoice->getSubTotalAmt() - $invoice->getTotalDiscAmt();?></td>
                    <td><?php echo $invoice->getTotalGstAmt();?></td>
                    <td><?php echo num_format(($invoice->getSubTotalAmt() - $invoice->getTotalDiscAmt()) + $invoice->getTotalGstAmt());?></td>
                    <td class = "text-align-right">
                        <?php
                        if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                        ?>
                        <button type="button" class="btn btn-primary btn-info " onclick = "location.href = '<?php echo $document_url;?>?action=edit&invoice_id=<?php echo $row['invoice_id'];?>'">View</button>
                        <?php }?>
                    </td>
                </tr>
            <?php
                $i++;
              }
            ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }
    public function generateImportData($exceldata,$action){

        switch ($action) {
            case "partner":
//           $this->partner_account_name1 = escape($exceldata[1]);
//           $this->partner_account_name2 = escape($exceldata[2]);
////           $this->partner_account_name3 = escape($exceldata[3]);
//
//
//           $this->partner_tax_no = escape($exceldata[3]);
//           $this->partner_branch_no = escape($exceldata[4]);
//           $this->partner_bill_address = escape($exceldata[5]);
//           $this->partner_tel = escape($exceldata[6]);
//           $this->partner_fax = escape($exceldata[7]);

           $this->partner_account_name1 = escape($exceldata[1]);
           $this->partner_account_name2 = escape($exceldata[2]);
           $this->partner_account_name3 = escape($exceldata[3]);
           $this->partner_account_name4 = escape($exceldata[4]);
           $this->partner_bill_address = escape($exceldata[5]);
           $this->partner_house_no = escape($exceldata[6]);
           $this->partner_postal_code = escape($exceldata[7]);
           $this->partner_unit_no = escape($exceldata[8]);
           $this->partner_suburb = escape($exceldata[9]);
           $this->partner_outlet = getDataCodeBySql("country_id","db_country"," WHERE UPPER(country_code) = '".escape(strtoupper($exceldata[10]))."'","");
           $this->partner_website = escape($exceldata[11]);
           $this->partner_currency = getDataCodeBySql("currency_id","db_currency"," WHERE UPPER(currency_code) = '".escape(strtoupper($exceldata[12]))."'","");
           $this->partner_group = getDataCodeBySql("partnergroup_id","db_partnergroup"," WHERE UPPER(partnergroup_code) = '".escape(strtoupper($exceldata[13]))."'","");
           $this->partner_address_type = getDataCodeBySql("partneraddresstype_id","db_partneraddresstype"," WHERE UPPER(partneraddresstype_code) = '".escape(strtoupper($exceldata[14]))."'","");
                break;
            case "contact":
           $this->contact_name = escape($exceldata[1]);
           $this->contact_address = escape($exceldata[2]);
           $this->contact_tel = escape($exceldata[3]);
           $this->contact_cellphone = escape($exceldata[4]);
           $this->contact_department = escape($exceldata[5]);
           $this->contact_position = escape($exceldata[6]);
           $this->contact_jobtitle = escape($exceldata[7]);
           $this->contact_forename = escape($exceldata[8]);
           $this->contact_lastname = escape($exceldata[9]);
           break;
            default:
                break;
        }

    }
    
    public function getFollowUpForm(){
        if($this->pfollow_id > 0){
           $this->fetchFollowDetail(" AND pfollow_id = '$this->pfollow_id'","","",1);
           $edit = escape($_REQUEST['edit']);
        }
        else{
//           $this->applicantsalary_overtime = "0.00";
//           $this->applicantsalary_hourly = "0.00";
//           $this->applicantsalary_workday = 20;
//           $this->applicantsalary_amount = 0;
        }
    ?>
        <?php if($this->pfollow_id > 0){?>
        <h3>Update Follow Up  </h3>
        <?php }
        else{?>
        <h3>Create Follow Up</h3> 
        <?php }?>
        <div class="form-group">
                        <label for="pfollow_description" class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-8">
<!--                                <textarea class="form-control" rows="3" id="pfollow_description" name="pfollow_description" placeholder="Description"><?php echo $this->pfollow_description;?></textarea>-->
                                <textarea id="editor1" rows="10" cols="80" class="form-control" name="pfollow_description" placeholder="Description" <?php if ($edit == "1"){echo "disabled";}?>><?php echo $this->pfollow_description;?></textarea>
                        </div>
        </div>
          <div class="col-sm-3 ">
              <button type = "button" class="btn btn-info save_partner_follow_btn" >
                  <?php if($this->pfollow_id > 0){?>
                  Update
                  <?php }
                  else{?>
                  Save
                  <?php }?>            
              </button>
              <input type = 'hidden' value = '<?php echo $this->pfollow_id;?>' name = 'pfollow_id' id = 'pfollow_id'/>
<!--              <button type="button" class="btn btn-primary btn-info " onclick = "confirmAlertHref('applicant.php?action=saveFamily&applicant_id=<?php echo $this->applicant_id;?>&appfamily_name=<?php echo $this->appfamily_name;?>','Confirm Save?')">Save</button>-->
          </div><br><br><br>
               
          
          
          
        <table id="partnerfollowup_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Create By</th>
                        <th style = 'width:20%'>Description</th>
                        <th style = 'width:7%'>Create Time</th>
                        <th style = 'width:7%'>Create Date</th>
                        <th style = 'width:7%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                    $sql = "select p.pfollow_id, left(p.insertDateTime,10) as date, right(p.insertDateTime, 8) as time, p.pfollow_description, e.empl_name from db_partnerfollow p inner join db_empl e on p.insertBy = e.empl_id where partner_id = '$this->partner_id' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                    
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['empl_name'];?></td>
                            <td><?php echo preg_replace('#<script(.*?)>(.*?)</script>#is', '',htmlspecialchars_decode($row['pfollow_description']));?></td>
                            <td><?php echo $row['time'];?></td>
                            <td><?php echo format_date($row['date']);?></td>
                            <td class = "text-align-right">

                                <?php 
                                $nowtime = date("H:i:s");
                                $nowDate = date("Y-m-d");
                                
                                $datetime1 = new DateTime($row['time']);
                                $datetime2 = new DateTime($nowtime);
                                
                                $normal_hour = $datetime1->diff($datetime2);
                                $hour = $normal_hour->format('%h');
                                $hour = $hour * 60;
                                $minute = $normal_hour->format('%i');
                                $minute = $minute + $hour;

                                $datetime1 = new DateTime($row['date']);
                                $datetime2 = new DateTime($nowDate);
                                
                                $normal_day = $datetime1->diff($datetime2);
                                $day = $normal_day->format('%a');                              

                                $sql5 = "SELECT outlet_time_minute from db_outlet WHERE outl_id = (SELECT empl_outlet FROM db_empl WHERE empl_id = '$_SESSIO[empl_id]')";
                                $query5 = mysql_query($sql5);
                                $row5 = mysql_fetch_array($query5);?>
                                
                                <input type = 'hidden' value = '<?php echo $row['pfollow_id'];?>' name = "pfollow" id = "pfollow"/>
                                
                                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'view')){?>
                                    <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'partner.php?action=edit&tab=followup&partner_id=<?php echo $this->partner_id;?>&pfollow_id=<?php echo $row['pfollow_id'];?>&edit=1'">view</button>
                                <?php }
                                if($day == 0 && $minute <= $row5['outlet_time_minute']){
                                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){?>
                                        <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'partner.php?action=edit&tab=followup&partner_id=<?php echo $this->partner_id;?>&pfollow_id=<?php echo $row['pfollow_id'];?>'">Edit</button>
                                    <?php } 
                                }
                                if($_SESSION['empl_group'] == '10'){ ?>   
                                    <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('partner.php?action=deleteFollow&tab=followup&partner_id=<?php echo $this->partner_id;?>&pfollow_id=<?php echo $row['pfollow_id'];?>','Confirm Delete?')">Delete</button>
                                <?php }?>  
                            </td>
                        </tr>
                    <?php
                        $i++;
                      }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Create By</th>
                        <th style = 'width:20%'>Description</th>
                        <th style = 'width:7%'>Create Time</th>
                        <th style = 'width:7%'>Create Date</th>
                        <th style = 'width:7%'></th>
                      </tr>
                    </tfoot>
                  </table>
             
                <div class="form-group">

        </div>
    <?php
    }    
    public function deleteFollowUp(){
        $sql = "DELETE FROM db_partnerfollow WHERE pfollow_id = $this->pfollow_id";
        mysql_query($sql);
        return true;
    }
    public function createFollowUp(){
        $table_field = array('pfollow_id','pfollow_description','partner_id');
        $table_value = array('', $this->pfollow_description, $this->partner_id);
        $remark = "Create Partner Remarks.";
        if(!$this->save->SaveData($table_field,$table_value,'db_partnerfollow','pfollow_id',$remark)){
            return false;
        }else{
            return true;
        }
    }
    public function updateFollowUp(){

        $table_field = array('pfollow_description','partner_id');
        $table_value = array($this->pfollow_description, $this->partner_id);
        $remark = "Update Partner Remarks";
        if(!$this->save->UpdateData($table_field,$table_value,'db_partnerfollow','pfollow_id',$remark,$this->pfollow_id," AND partner_id = '$this->partner_id'")){
           return false;
        }else{
           return true;
        }
    } 
    public function fetchFollowDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_partnerfollow WHERE pfollow_id > 0 $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->pfollow_id = $row['pfollow_id'];
            $this->pfollow_description = $row['pfollow_description'];
            $this->partner_id = $row['partner_id'];
            $this->updateBy = $row['updateBy'];
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }
       
    }
    
    public function getTimeShiftForm(){
        if($this->timeshift_id > 0){
           $this->fetchTimeShiftDetail(" AND timeshift_id = '$this->timeshift_id'","","",1);
        }else{
//           $this->applicantsalary_overtime = "0.00";
//           $this->applicantsalary_hourly = "0.00";
//           $this->applicantsalary_workday = 20;
//           $this->applicantsalary_amount = 0;
        }
    ?>
        <?php if($this->timeshift_id > 0){?>
        <h3>Update Timesheet  </h3>
        <?php }else{?>
        <h3>Create Timesheet</h3> 
        <?php }?>
        
        <div class="form-group">
              <label for="department_name" class="col-sm-2 control-label">Department Name</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="department_name" name="department_name" value = "<?php echo $this->department_name;?>">
              </div>
              <label for="working_day" class="col-sm-2 control-label">Required working day</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="working_day" name="working_day" value = "<?php echo $this->working_day;?>">
              </div>
        </div>          
        <div class="form-group">
            <label for="start_time" class="col-sm-2 control-label">Working Start Time</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;'>
                <input type="text" class="form-control timepicker" id="start_time" name="start_time" value = "<?php echo $this->start_time;?>" placeholder="Time">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
            <label for="end_time" class="col-sm-2 control-label">Working End Time</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;'>
                <input type="text" class="form-control timepicker" id="end_time" name="end_time" value = "<?php echo $this->end_time;?>" placeholder="Time">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
        <div class="form-group">
              <label for="ot_rate" class="col-sm-2 control-label">OT Rate Type</label>
              <div class="col-sm-3">
                <select class="form-control select2" id="ot_rate" name="ot_rate" style = 'width:100%'>
                     <?php 
                     if($this->ot_rate == '2'){?>
                        <option value="2">2</option>
                        <option value="1.5">1.5</option>
                     <?php }
                     else
                     { ?>
                        <option value="1.5">1.5</option>
                        <option value="2">2</option>
                     <?php }
                     ?>
                </select>
              </div>
<!--              <label for="salary_date" class="col-sm-2 control-label">Salary Date <?php echo $mandatory;?></label>
              <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="salary_date" name="salary_date" value = "<?php echo format_date($this->salary_date);?>" placeholder="Salary Date">
              </div>              -->
<!--        </div>
        <div class="form-group"> -->
            <label for="timeshift_description" class="col-sm-2 control-label">Remarks</label>
            <div class="col-sm-3">
                 <textarea class="form-control" rows="3" id="timeshift_description" name="timeshift_description" placeholder="Remarks"><?php echo $this->timeshift_description;?></textarea>
            </div>
        </div>           
          <div class="col-sm-3 ">
              <button type = "button" class="btn btn-info save_timeshift_btn" >
                  <?php if($this->timeshift_id > 0){?>
                  Update
                  <?php }else{?>
                  Save
                  <?php }?>            
              </button>
              <input type = 'hidden' value = '<?php echo $this->timeshift_id;?>' name = 'timeshift_id' id = 'timeshift_id'/>
          </div><br><br><br>
               
          
          
          
        <table id="timeshift_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Department</th>
                        <th style = 'width:20%'>Working Days</th>
                        <th style = 'width:7%'>Start Time</th>
                        <th style = 'width:7%'>End Time</th>
                        <th style = 'width:7%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                    $sql = "select *, LEFT(insertDateTime, 10) AS Date, RIGHT(insertDateTime, 8) AS Time FROM db_timeshift where timeshift_company = '$this->partner_id' AND timeshift_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                    
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['timeshift_department'];?></td>
                            <td><?php echo $row['timeshift_work_day'];?></td>
                            <td><?php echo $row['timeshift_start_time'];?></td>
                            <td><?php echo $row['timeshift_end_time'];?></td>
                            <td class = "text-align-right">

                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'partner.php?action=edit&tab=timeshift&partner_id=<?php echo $this->partner_id;?>&timeshift_id=<?php echo $row['timeshift_id'];?>'">Edit</button>
                                <input type = 'hidden' value = '<?php echo $row['timeshift_id'];?>' name = "timeshift" id = "timeshift"/>
                                
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('partner.php?action=deleteTimeShift&tab=timeshift&partner_id=<?php echo $this->partner_id;?>&timeshift_id=<?php echo $row['timeshift_id'];?>','Confirm Delete?')">Delete</button>
                            </td>
                        </tr>
                    <?php
                        $i++;
                      }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Department</th>
                        <th style = 'width:20%'>Working Days</th>
                        <th style = 'width:7%'>Start Time</th>
                        <th style = 'width:7%'>End Time</th>
                        <th style = 'width:7%'></th>
                      </tr>
                    </tfoot>
                  </table>
             
                <div class="form-group">

        </div>
    <?php
    }    
    public function deleteTimeShift(){
   $table_field = array('timeshift_status');
        $table_value = array(1);
        $remark = "Delete TimeShift";
        if(!$this->save->UpdateData($table_field,$table_value,'db_timeshift','timeshift_id',$remark,$this->timeshift_id," AND timeshift_company = '$this->partner_id'")){
           return false;
        }else{
           return true;
        }
    } 
    public function createTimeShift(){
        $table_field = array('timeshift_id','timeshift_company','timeshift_department','timeshift_start_time','timeshift_end_time','timeshift_work_day','timeshift_ot_rate','timeshift_description','timeshift_status');
        $table_value = array('', $this->partner_id, $this->department_name, $this->start_time, $this->end_time, $this->working_day, $this->ot_rate, $this->timeshift_description,0);
        $remark = "Create TimeShift.";
        if(!$this->save->SaveData($table_field,$table_value,'db_timeshift','timeshift_id',$remark)){
            return false;
        }else{
            return true;
        }
    }
    public function updateTimeShift(){

   $table_field = array('timeshift_company','timeshift_department','timeshift_start_time','timeshift_end_time','timeshift_work_day','timeshift_description','timeshift_ot_rate');
        $table_value = array($this->partner_id, $this->department_name, $this->start_time, $this->end_time, $this->working_day, $this->timeshift_description, $this->ot_rate);
        $remark = "Update TimeShift";
        if(!$this->save->UpdateData($table_field,$table_value,'db_timeshift','timeshift_id',$remark,$this->timeshift_id," AND timeshift_company = '$this->partner_id'")){
           return false;
        }else{
           return true;
        }
    } 
    public function fetchTimeShiftDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_timeshift WHERE timeshift_id > 0 $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->timeshift_id = $row['timeshift_id'];
            $this->department_name = $row['timeshift_department'];
            $this->start_time = $row['timeshift_start_time'];
            $this->end_time = $row['timeshift_end_time'];
            $this->working_day = $row['timeshift_work_day'];
            $this->ot_rate = $row['timeshift_ot_rate'];
            //$this->salary_date = $row['timeshift_salary_date'];
            $this->timeshift_description = $row['timeshift_description'];
            return true;
        }
        else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }
       
    }
    
    public function getEmployeeForm(){
         global $mandatory;
         if($this->empl_id > 0){
           $this->fetchEmplDetail(" AND empl_id = '$this->empl_id'","","",1);
        }
        $this->departmentCrtl = $this->getDepartment($this->empl_department);
    ?>
            <div class="form-group">
                <label for="empl_name" class="col-sm-2 control-label" required>Name <?php echo $mandatory;?></label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="empl_name" name="empl_name" value = "<?php echo $this->empl_name;?>" placeholder="Name">
                </div>
                <label for="empl_nric" class="col-sm-2 control-label" required>NRIC <?php echo $mandatory;?></label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="empl_nric" name="empl_nric" value = "<?php echo $this->empl_nric;?>" placeholder="NRIC">
                </div>

            </div>
            <div class="form-group">
                <label for="empl_sex" class="col-sm-2 control-label" required>Sex <?php echo $mandatory;?></label>
                 <div class="col-sm-3">
                     <select class="form-control select2" id="empl_sex" name="empl_sex" style = 'width:100%'>
                        <option value="">Select One</option>
                        <option value="M" <?php if($this->empl_sex == 'M'){ echo 'SELECTED';}?>>Male</option>
                        <option value="F" <?php if($this->empl_sex == 'F'){ echo 'SELECTED';}?>>Female</option>
                     </select>
                 </div>
                <label for="empl_mobile" class="col-sm-2 control-label">Mobile</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="empl_mobile" name="empl_mobile" value = "<?php echo $this->empl_mobile;?>" placeholder="Mobile">
                </div>  
            </div>
            <div class="form-group">
                <label for="empl_email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="empl_email" name="empl_email" value = "<?php echo $this->empl_email;?>" placeholder="Email">
                </div>
                <label for="empl_department" class="col-sm-2 control-label">Department <?php echo $mandatory;?></label>
                 <div class="col-sm-3">
                     <select class="form-control select2" id="empl_department" name="empl_department" style = 'width:100%'>
                         <?php echo $this->departmentCrtl; ?>
                     </select>
                 </div>
            </div>
            <div class="form-group">
              <label for="empl_remark" class="col-sm-2 control-label">Remarks</label>
              <div class="col-sm-3">
                    <textarea class="form-control" rows="3" id="empl_remark" name="empl_remark" placeholder="Remark"><?php echo $this->empl_remark;?></textarea>
              </div>
            </div>

            <h3>Login Information</h3>

            <div class="form-group">
                  <label for="empl_login_email" class="col-sm-2 control-label">Login ID <?php echo $mandatory;?></label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="empl_login_email" name="empl_login_email" value = "<?php echo $this->empl_login_email;?>" placeholder="Login Email">
                  </div>
            </div>
            <div class="form-group">
                  <label for="empl_login_password" class="col-sm-2 control-label" >Password <?php echo $mandatory;?></label>
                  <div class="col-sm-3">
                    <input type="password" class="form-control" id="empl_login_password" name="empl_login_password" value = "<?php echo $this->empl_login_password;?>" placeholder="Password">
                  </div>
            </div>
            <div class="form-group">
                  <label for="empl_login_password_cm" class="col-sm-2 control-label" >Confirm Password <?php echo $mandatory;?></label>
                  <div class="col-sm-3">
                    <input type="password" class="form-control" id="empl_login_password_cm" name="empl_login_password_cm" value = "<?php echo $this->empl_login_password;?>" placeholder="Confirm Password">
                  </div>
            </div>
          <div class="col-sm-3 ">
              <button type = "button" class="btn btn-info save_empl_btn" >
                  <?php if($this->empl_id > 0){?>
                  Update
                  <?php }
                  else{?>
                  Save
                  <?php }?>            
              </button>
              <input type = 'hidden' value = '<?php echo $this->empl_id;?>' name = 'empl_id' id = 'empl_id'/>
<!--              <button type="button" class="btn btn-primary btn-info " onclick = "confirmAlertHref('applicant.php?action=saveFamily&applicant_id=<?php echo $this->applicant_id;?>&appfamily_name=<?php echo $this->appfamily_name;?>','Confirm Save?')">Save</button>-->
          </div><br><br><br>
        
        <table id="empl_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Name</th>
                        <th style = 'width:20%'>Email</th>
                        <th style = 'width:7%'>Mobile</th>
                        <th style = 'width:7%'>Department</th>
                        <th style = 'width:7%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                    $sql = "select e.*, LEFT(e.insertDateTime,10) AS Date, RIGHT(e.insertDateTime,8) AS Time, t.timeshift_department FROM db_empl e INNER JOIN db_timeshift t ON e.empl_department = t.timeshift_id WHERE empl_client = '$this->partner_id' AND empl_status = '1' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                    
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['empl_name'];?></td>
                            <td><?php echo $row['empl_email']?></td>
                            <td><?php echo $row['empl_mobile'];?></td>
                            <td><?php echo $row['timeshift_department'];?></td>
                            <td class = "text-align-right">

                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'partner.php?action=edit&tab=employee&partner_id=<?php echo $this->partner_id;?>&empl_id=<?php echo $row['empl_id'];?>'">Edit</button>
                                <!--<input type = 'hidden' value = '<?php echo $row['empl_id'];?>' name = "empl" id = "empl"/>-->
                                
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('partner.php?action=deleteEmpl&tab=employee&partner_id=<?php echo $this->partner_id;?>&empl_id=<?php echo $row['empl_id'];?>','Confirm Delete?')">Delete</button>
                            </td>
                        </tr>
                    <?php
                        $i++;
                      }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Name</th>
                        <th style = 'width:20%'>Email</th>
                        <th style = 'width:7%'>Mobile</th>
                        <th style = 'width:7%'>Department</th>
                        <th style = 'width:7%'></th>
                      </tr>
                    </tfoot>
        </table>          
          
          
    <?php
    }    
    public function deleteEmployee(){
   $table_field = array('empl_status');
        $table_value = array(0);
        $remark = "Delete Client Employee";
        if(!$this->save->UpdateData($table_field,$table_value,'db_empl','empl_id',$remark,$this->empl_id," AND empl_client = '$this->partner_id'")){
           return false;
        }else{
           return true;
        }
    } 
    public function createEmployee(){
        $this->empl_login_password = md5("@#~x?\$" . $this->empl_login_password . "?\$");
        $table_field = array('empl_name','empl_nric','empl_group','empl_remark',
                             'empl_login_email','empl_login_password','empl_status','empl_email',
                             'empl_department','empl_mobile','empl_sex','empl_client'
            );
        $table_value = array($this->empl_name,$this->empl_nric,9,$this->empl_remark,
                             $this->empl_login_email,$this->empl_login_password,1,$this->empl_email,
                             $this->empl_department, $this->empl_mobile,$this->empl_sex,$this->partner_id

                );
        $remark = "Insert Client Employee.";
        if(!$this->save->SaveData($table_field,$table_value,'db_empl','empl_id',$remark)){
           return false;
        }else{
           $this->empl_id = $this->save->lastInsert_id;
          
           for($i=0;$i<sizeof($this->emplleave_days);$i++){
                    $this->createLeave(escape($this->emplleave_leavetype[$i]),escape($this->emplleave_days[$i]),escape($this->emplleave_disabled[$this->emplleave_leavetype[$i]]),escape($this->emplleave_entitled[$i]));
           }
           //$this->email($this->empl_email);
           return true;
        }
    }
    public function updateEmployee(){
        $new_password = $this->empl_login_password;
        $empl_id = $this->empl_id;
        $empl_login_email = $this->empl_login_email;

        if($this->empl_oldpassword != $new_password){
          $this->empl_login_password = md5("@#~x?\$" . $new_password . "?\$");
        }

        $table_field = array('empl_name','empl_nric','empl_remark',
                             'empl_login_email','empl_login_password','empl_email',
                             'empl_department','empl_mobile','empl_sex'
                            );
        $table_value = array($this->empl_name,$this->empl_nric,$this->empl_remark,
                             $this->empl_login_email,$this->empl_login_password,$this->empl_email,
                             $this->empl_department, $this->empl_mobile,$this->empl_sex
                            );
        $remark = "Update Client Employee";
        if(!$this->save->UpdateData($table_field,$table_value,'db_empl','empl_id',$remark,$this->empl_id," AND empl_client = '$this->partner_id'")){
           return false;
        }else{
           return true;
        }
    } 
    public function fetchEmplDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_empl WHERE empl_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->empl_id = $row['empl_id'];
            $this->empl_name = $row['empl_name'];
            $this->empl_nric = $row['empl_nric'];
            $this->empl_mobile = $row['empl_mobile'];
            $this->empl_email = $row['empl_email'];
            $this->empl_remark = $row['empl_remark'];
            $this->empl_group = $row['empl_group'];
            $this->empl_status = $row['empl_status'];
            $this->empl_login_email = $row['empl_login_email'];
            $this->empl_login_password = $row['empl_login_password'];
            $this->empl_department = $row['empl_department'];
            $this->empl_sex = $row['empl_sex'];
            
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }
       
    }    
    
    public function getCandidateForm(){
        ?>
          <h3><u>Candidate Detail</u></h3>  
        <?php
        
         echo $this->getApprovalForm();
        
        ?>  
          
            <button type = "button" class="btn btn-info save_candidate_btn" >
                  Save        
            </button>  
        <table id="applicant_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Name</th>
                        <th style = 'width:20%'>Email</th>
                        <th style = 'width:7%'>Department</th>
                        <th style = 'width:7%'>Jobs Title</th>
                        <th style = 'width:7%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT a.*, LEFT(f.insertDateTime,10) AS Date, RIGHT(f.insertDateTime,8) AS Time, f.* FROM db_applicant a INNER JOIN db_followup f ON f.applfollow_id = a.applicant_id WHERE f.interview_company = '$this->partner_id' AND f.follow_type = '0'  ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";  
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['applicant_name'];?></td>
                            <td><?php echo $row['applicant_email']?></td>
                            
                            <?php 
                            $department_id = $row['fol_department'];
                            $sql2 = "SELECT timeshift_department FROM db_timeshift WHERE timeshift_id = '$department_id'";
                            $query2 = mysql_query($sql2);
                            $row2 = mysql_fetch_array($query2);
                                    
                            $job_id = $row['fol_job_assign'];
                            $sql3 = "SELECT job_title FROM db_jobs WHERE job_id = '$job_id'";
                            $query3 = mysql_query($sql3);
                            $row3 = mysql_fetch_array($query3)                                    
                            ?>
                            <td><?php echo $row2['timeshift_department'];?></td>
                            <td><?php echo $row3['job_title'];?></td>
                            <td class = "text-align-right">
                                
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'partner.php?action=edit&tab=candidate&partner_id=<?php echo $this->partner_id;?>&applicant_id=<?php echo $row['applicant_id'];?>&job_id=<?php echo $row3['job_title'];?>&department_id=<?php echo $row2['timeshift_department'];?>'">Add Approval</button>
                                <input type = 'hidden' value = '<?php echo $row['applicant_id'];?>' name = "applicant_id" id = "applicant_id"/>
                            </td>
                        </tr>
                    <?php
                        $i++;
                      }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Name</th>
                        <th style = 'width:20%'>Email</th>
                        <th style = 'width:7%'>Department</th>
                        <th style = 'width:7%'>Jobs Title</th>
                        <th style = 'width:7%'></th>
                      </tr>
                    </tfoot>
        </table>          
          
          
    <?php
    }   
    public function getApprovalForm(){
        
        
       $applicant_id = escape($_GET['applicant_id']);
        if($this->applicant_id > 0){
           $this->fetchCandidateDetail(" AND applicant_id = '$applicant_id'","","",1);
        }
        $job_title = escape($_GET['job_id']);
        $department = escape($_GET['department_id']);
        $sql = "SELECT * FROM db_applicant WHERE applicant_id = '$applicant_id' ";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);
        $this->applicant_name = $row['applicant_name'];
        $this->applicant_mobile = $row['applicant_mobile'];
        $this->applicant_email = $row['applicant_email'];

    ?>
            <div class="form-group">
                <label for="applicant_name" class="col-sm-2 control-label" >Name</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="empl_name" name="applicant_name" value = "<?php echo $this->applicant_name;?>" placeholder="Name" readonly>
                </div>
                <label for="applicant_mobile" class="col-sm-2 control-label" >Mobile</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="empl_nric" name="applicant_mobile" value = "<?php echo $this->applicant_mobile;?>" placeholder="Mobile" readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="applicant_email" class="col-sm-2 control-label" >Email</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="empl_nric" name="applicant_email" value = "<?php echo $this->applicant_email;?>" placeholder="Email" readonly>
                </div>
                 <label for="applicant_department" class="col-sm-2 control-label" >Department</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="empl_nric" name="applicant_department" value = "<?php echo $department;?>" placeholder="Department" readonly>
                </div>
            </div>
            <div class="form-group">
                <label for="applicant_job" class="col-sm-2 control-label" >Job Title</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="empl_nric" name="applicant_job" value = "<?php echo $job_title;?>" placeholder="Job" readonly>
                </div>
                <input type = 'hidden' value = '<?php echo $applicant_id;?>' name = "applicant_id" id = "applicant_id"/>
            </div>
            
            <h3><u>Alert Supervisor</u></h3>
          
    <?php
        $this->clientLeaveCrtl1 = $this->getClientEmplSelectCtrl($this->applicant_leave_approved1);
        $this->clientLeaveCrtl2 = $this->getClientEmplSelectCtrl($this->applicant_leave_approved2);
        $this->clientLeaveCrtl3 = $this->getClientEmplSelectCtrl($this->applicant_leave_approved3);
        $this->clientClaimsCrtl1 = $this->getClientEmplSelectCtrl($this->applicant_claims_approved1);
        $this->clientClaimsCrtl2 = $this->getClientEmplSelectCtrl($this->applicant_claims_approved2);
        $this->clientClaimsCrtl3 = $this->getClientEmplSelectCtrl($this->applicant_claims_approved3);
        
    ?>
            <div class="form-group">
                <div class="col-sm-5">
                <h4>Leave</h4>
                </div>
                <div class="col-sm-5">
                <h4>Claims</h4>
                </div>
            </div>
            <div class="form-group">
                <label for="applicant_leave_approved1" class="col-sm-2 control-label">Approved level 1</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_leave_approved1" name="applicant_leave_approved1" style = 'width:100%' >
                        <?php echo $this->clientLeaveCrtl1;?>
                    </select>
                </div>
                <label for="applicant_claims_approved1" class="col-sm-2 control-label">Approved level 1</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_claims_approved1" name="applicant_claims_approved1" style = 'width:100%' >
                        <?php echo $this->clientClaimsCrtl1;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="applicant_leave_approved2" class="col-sm-2 control-label">Approved level 2</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_leave_approved2" name="applicant_leave_approved2" style = 'width:100%' >
                        <?php echo $this->clientLeaveCrtl2;?>
                    </select>
                </div>
                <label for="applicant_claims_approved2" class="col-sm-2 control-label">Approved level 2</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_claims_approved2" name="applicant_claims_approved2" style = 'width:100%' >
                        <?php echo $this->clientClaimsCrtl2;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="applicant_leave_approved3" class="col-sm-2 control-label">Approved level 3</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_leave_approved3" name="applicant_leave_approved3" style = 'width:100%' >
                        <?php echo $this->clientLeaveCrtl3;?>
                    </select>
                </div>
                <label for="applicant_claims_approved3" class="col-sm-2 control-label">Approved level 3</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_claims_approved3" name="applicant_claims_approved3" style = 'width:100%' >
                        <?php echo $this->clientClaimsCrtl3;?>
                    </select>
                </div>
            </div>
    <?php
    }    
    public function updateCandidate(){
        $applicant_id = escape($_GET['applicant_id']);
        $table_field = array('applicant_leave_approved1','applicant_leave_approved2','applicant_leave_approved3',
                             'applicant_claims_approved1','applicant_claims_approved2','applicant_claims_approved3'
                            );
        $table_value = array($this->applicant_leave_approved1,$this->applicant_leave_approved2,$this->applicant_leave_approved3,
                             $this->applicant_claims_approved1,$this->applicant_claims_approved2,$this->applicant_claims_approved3
                            );
        $remark = "Update Client Candidate";
        if(!$this->save->UpdateData($table_field,$table_value,'db_applicant','applicant_id',$remark,$this->applicant_id,"")){
           return false;
        }else{
           return true;
        }
    } 
    public function fetchCandidateDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_applicant WHERE applicant_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->applicant_leave_approved1 = $row['applicant_leave_approved1'];
            $this->applicant_leave_approved2 = $row['applicant_leave_approved2'];
            $this->applicant_leave_approved3 = $row['applicant_leave_approved3'];
            $this->applicant_claims_approved1 = $row['applicant_claims_approved1'];
            $this->applicant_claims_approved2 = $row['applicant_claims_approved2'];
            $this->applicant_claims_approved3 = $row['applicant_claims_approved3'];
            
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }  
    }     

    public function getDepartment($pid){
        $sql = "SELECT timeshift_id , timeshift_department FROM `db_timeshift` WHERE (timeshift_id = '$pid' or timeshift_company = '$this->partner_id')";
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['timeshift_id'];
            $code = $row['timeshift_department'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }
            else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;        
    }
    public function getClientEmplSelectCtrl($pid,$shownull="Y",$wherestring='',$text = "One"){
        $sql = "SELECT empl_id , empl_name FROM `db_empl` WHERE (empl_id = '$pid' or empl_group = '9') and empl_client = '$this->partner_id'";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select $text</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['empl_id'];
            $code = $row['empl_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }
            else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }  
    public function getRemarks(){
//        $sql = "select * from db_partnerfollow where partner_id = '$this->partner_id'";
        $sql = "select p.pfollow_id, left(p.insertDateTime,10) as date, right(p.insertDateTime, 8) as time, p.pfollow_description, e.empl_name from db_partnerfollow p inner join db_empl e on p.insertBy = e.empl_id where partner_id = '$this->partner_id'  ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
        $query = mysql_query($sql);
        
        $i = 0;
        while($row = mysql_fetch_array($query)){
            $data ['partner_id'][$i] = $this->partner_id;
            $data ['pfollow_id'][$i] = $row['pfollow_id'];
            $data ['empl_name'][$i] = $row['empl_name'];
            $data ['time'][$i] = $row['time'];
            $data ['date'][$i] = format_date($row['date']);
            $data ['pfollow_description'][$i] =  preg_replace('#<script(.*?)>(.*?)</script>#is', '',htmlspecialchars_decode($row['pfollow_description']));

        $i++;
        }
        return $data;
    }
}
?>
