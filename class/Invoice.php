<?php
/*
 * To change this tinvoiceate, choose Tools | Tinvoiceates
 * and open the tinvoiceate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Invoice {

    public function Invoice(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();


    }
    public function createInvoice(){
        $invoice_no = get_prefix_value($this->document_code,true);

        $table_field = array('invoice_id','invoice_date','invoice_type','invoice_amount','invoice_outlet',
                             'invoice_client','invoice_terms','invoice_attention_person','invoice_gst_reg_no',
                             'invoice_postal_code','invoice_unit_no','invoice_address','invoice_remark','invoice_gst');
        $table_value = array('', format_date_database($this->invoice_date),$this->invoice_type,$this->invoice_amount,$this->invoice_outlet,
                             $this->client_name,$this->invoice_terms,$this->invoice_attention_person,$this->invoice_gst_reg_no,
                             $this->invoice_postal_code,$this->invoice_unit_no,$this->invoice_address,$this->invoice_remark,$this->invoice_gst);
        $remark = "Insert Invoice";
        if(!$this->save->SaveData($table_field,$table_value,'db_invoice','invoice_id',$remark)){
           return false;
        }else{
           $this->invoice_id = $this->save->lastInsert_id;
           $this->updateInvoiceNo($this->invoice_id);
           return true;
        }
    }
    public function updateInvoice(){
        $table_field = array('invoice_amount','invoice_outlet',
                             'invoice_terms','invoice_attention_person','invoice_gst_reg_no',
                             'invoice_postal_code','invoice_unit_no','invoice_address','invoice_remark','invoice_gst');
        $table_value = array($this->invoice_amount,$this->invoice_outlet,
                             $this->invoice_terms,$this->invoice_attention_person,$this->invoice_gst_reg_no,
                             $this->invoice_postal_code,$this->invoice_unit_no,$this->invoice_address,$this->invoice_remark,$this->invoice_gst);
        $remark = "Update Invoice";
        if(!$this->save->UpdateData($table_field,$table_value,'db_invoice','invoice_id',$remark,$this->invoice_id)){
           return false;
        }else{
           return true;
        }
    }
    public function updateInvoiceTotal(){
        $this->invoice_taxtotal = ($this->invoice_subtotal - $this->invoice_disctotal) + ROUND((($this->invoice_subtotal - $this->invoice_disctotal) * (system_gst_percent/100)),2);
        $this->invoice_grandtotal = ($this->invoice_subtotal - $this->invoice_disctotal) + $this->invoice_taxtotal;
        if($_SESSION['empl_outlet'] == 14){
            $this->invoice_grandtotal = round($this->invoice_grandtotal);
        }
        $table_field = array('invoice_subtotal','invoice_disctotal','invoice_taxtotal','invoice_grandtotal');
        $table_value = array($this->invoice_subtotal,$this->invoice_disctotal,$this->invoice_taxtotal,$this->invoice_grandtotal);
        $this->fetchInvoiceDetail(" AND invoice_id = '$this->invoice_id'","","",1);
        $remark = "Update $this->document_code.<br> Document No : $this->invoice_no";
        if(!$this->save->UpdateData($table_field,$table_value,'db_invoice','invoice_id',$remark,$this->invoice_id)){
           return false;
        }else{
           return true;
        }
    }
    public function createInvoiceLine(){

        $table_field = array('invl_invoice_id','invl_pro_id','invl_pro_desc','invl_qty','invl_uom',
                             'invl_uprice','invl_disc','invl_istax','invl_taxamt','invl_total',
                             'invl_pro_no','invl_discamt','invl_seqno','invl_parent',
                             'invl_markup','invl_fdiscamt','invl_ftaxamt','invl_ftotal',
                             'invl_enable');
        $table_value = array($this->invoice_id,$this->invl_pro_id,$this->invl_pro_desc,$this->invl_qty,$this->invl_uom,
                             $this->invl_uprice,$this->invl_disc,$this->invl_istax,$this->invl_taxamt,$this->invl_total,
                             $this->invl_pro_no,$this->invl_discamt,$this->invl_seqno,$this->invl_parent,
                             $this->invl_markup,$this->invl_fdiscamt,$this->invl_ftaxamt,$this->invl_ftotal,
                             $this->invl_enable);
        $this->fetchInvoiceDetail(" AND invoice_id = '$this->invoice_id'","","",1);
        $remark = "Insert $this->document_code Line.<br> Document No : $this->invoice_no";
        if(!$this->save->SaveData($table_field,$table_value,'db_invl','invl_id',$remark)){
           return false;
        }else{
           $this->invl_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function updateInvoiceLine(){
        $table_field = array('invl_invoice_id','invl_pro_id','invl_pro_desc','invl_qty','invl_uom',
                             'invl_uprice','invl_disc','invl_istax','invl_taxamt','invl_total',
                             'invl_pro_no','invl_discamt','invl_seqno','invl_markup',
                             'invl_fdiscamt','invl_ftaxamt','invl_ftotal',
                             'invl_enable');
        $table_value = array($this->invoice_id,$this->invl_pro_id,$this->invl_pro_desc,$this->invl_qty,$this->invl_uom,
                             $this->invl_uprice,$this->invl_disc,$this->invl_istax,$this->invl_taxamt,$this->invl_total,
                             $this->invl_pro_no,$this->invl_discamt,$this->invl_seqno,$this->invl_markup,
                             $this->invl_fdiscamt,$this->invl_ftaxamt,$this->invl_ftotal,
                             $this->invl_enable);
        $this->fetchInvoiceDetail(" AND invoice_id = '$this->invoice_id'","","",1);
        $remark = "Update $this->document_code Line.<br> Document No : $this->invoice_no";
        if(!$this->save->UpdateData($table_field,$table_value,'db_invl','invl_id',$remark,$this->invl_id)){
           return false;
        }else{
           return true;
        }
    }
    public function calculateLineAmount(){

        if($this->invoice_currencyrate <= 0){
            $this->invoice_currencyrate = 1;
        }

        //foreign amount
        $this->invl_fuprice = $this->invl_fuprice;
        $subtotal = $this->invl_qty * ROUND(($this->invl_fuprice*markup_rate),2);


        if($this->invl_disc > 0){
            $this->invl_fdiscamt = ROUND($subtotal * ($this->invl_disc/100),2);
            $this->invl_discamt = ROUND($this->invl_fdiscamt * $this->invoice_currencyrate,2);
        }else{
            $this->invl_discamt = 0;
        }

        $subtotal_afterdiscount = $subtotal - $this->invl_fdiscamt;

        if($this->invl_istax > 0){
            $this->invl_ftaxamt = ROUND($subtotal_afterdiscount * (system_gst_percent/100),2);
            $this->invl_taxamt = ROUND($this->invl_ftaxamt * $this->invoice_currencyrate,2);
        }else{
            $this->invl_taxamt = 0;
        }

        $this->invl_ftotal = $subtotal_afterdiscount + $this->invl_ftaxamt;


        //base amount
        $this->invl_total = ROUND($this->invl_ftotal * $this->invoice_currencyrate,2);

        if($_SESSION['empl_outlet'] == 14){
            $this->invl_ftotal = round($this->invl_ftotal);
            $this->invl_total = round($this->invl_total);
        }
    }
    public function getTotalDiscAmt(){
        $sql = "SELECT SUM(invl_discamt) as discamt FROM db_invl WHERE invl_invoice_id = '$this->invoice_id'";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
            $total_discamt = $row['discamt'];
        }else{
            $total_discamt = 0;
        }
        return $total_discamt;
    }
    public function getSubTotalAmt(){
        $sql = "SELECT SUM(invl_markup) as subtotal FROM db_invl WHERE invl_invoice_id = '$this->invoice_id'";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
            $total_subtotal = $row['subtotal'];
        }else{
            $total_subtotal = 0;
        }
        return $total_subtotal;
    }
    public function getTotalGstAmt(){
        $sql = "SELECT SUM(invl_taxamt) as taxamt FROM db_invl WHERE invl_invoice_id = '$this->invoice_id'";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
            $total_taxamt = $row['taxamt'];
        }else{
            $total_taxamt = 0;
        }
        return $total_taxamt;
    }
    public function fetchInvoiceDetail($wherestring,$invoicestring,$wherelimit,$type){
        $sql = "SELECT * FROM db_invoice WHERE invoice_id > 0  $wherestring $invoicestring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);

            $this->invoice_id = $row['invoice_id'];
            $this->invoice_type = $row['invoice_type'];
            $this->client_name = $row['invoice_client'];
            $this->invoice_no = $row['invoice_no'];
            $this->invoice_date = $row['invoice_date'];
            $this->invoice_terms = $row['invoice_terms'];
            $this->invoice_outlet = $row['invoice_outlet'];
            $this->invoice_attention_person = $row['invoice_attention_person'];
            $this->invoice_gst_reg_no = $row['invoice_gst_reg_no'];
            $this->invoice_postal_code = $row['invoice_postal_code'];
            $this->invoice_unit_no = $row['invoice_unit_no'];
            $this->invoice_address = $row['invoice_address'];
            $this->invoice_remark = $row['invoice_remark'];
            $this->invoice_gst = $row['invoice_gst'];
        }
        return $query;
    }
    public function fetchInvoiceLineDetail($wherestring,$invoicestring,$wherelimit,$type){
        $sql = "SELECT * FROM db_invl WHERE invl_id > 0 AND invl_invoice_id = '$this->invoice_id' $wherestring $invoicestring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);

            $this->invl_id = $row['invl_id'];
            $this->invl_pro_no = $row['invl_pro_no'];
            $this->invl_pro_id = $row['invl_pro_id'];
            $this->invl_pro_desc = $row['invl_pro_desc'];
            $this->invl_qty = $row['invl_qty'];
            $this->invl_uom = $row['invl_uom'];
            $this->invl_uprice = $row['invl_uprice'];
            $this->invl_disc = $row['invl_disc'];
            $this->invl_discamt = $row['invl_discamt'];
            $this->invl_istax = $row['invl_istax'];
            $this->invl_taxamt = $row['invl_taxamt'];
            $this->invl_total = $row['invl_total'];
            $this->invl_seqno = $row['invl_seqno'];
            $this->invl_markup = $row['invl_markup'];
            $this->invl_fdiscamt = $row['invl_fdiscamt'];
            $this->invl_ftaxamt = $row['invl_ftaxamt'];
            $this->invl_ftotal = $row['invl_ftotal'];
        }
        return $query;
    }
    public function delete(){
        $table_field = array('invoice_status');
        $table_value = array(1);
        $remark = "Delete Invoice $this->invoice_no";
        if(!$this->save->UpdateData($table_field,$table_value,'db_invoice','invoice_id',$remark,$this->invoice_id)){
           return false;
        }else{
           return true;
        }
    }
    public function deleteInvoiceLine(){
        if($this->save->DeleteData("db_invl"," WHERE invl_invoice_id = '$this->invoice_id' AND invl_id = '$this->invl_id'","Delete $this->document_code Invoice Line.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        $this->invoice_id = $_REQUEST['invoice_id'];
        if($_REQUEST['client_name'] != ""){
            $this->client_name = escape($_REQUEST['client_name']);
            $this->invoice_date = escape($_REQUEST['payroll_date']);
        }
        $edit = escape($_REQUEST['edit']);
        $this->fetchInvoiceDetail("AND  invoice_id = '$this->invoice_id'", $invoicestring, $wherelimit, $type);
        $this->clientCrtl = $this->select->getClientSelectCtrl($this->client_name);
        $this->outletCrtl = $this->select->getOutletSelectCtrl($this->invoice_outlet);
        global $mandatory;
//        if($action == 'create'){
//            $this->invoice_code = "-- System Generate --";
//            $this->invoice_status = 1;
//            $this->invoice_date = system_date;
//            $this->invoice_no = get_prefix_value($this->document_code,false,$this->invoice_date);
//            $this->invoice_currency = $_SESSION['empl_currency_id'];
//            $this->invoice_currency_org = $_SESSION['empl_currency_id'];
//
//        }
//
//        $this->employeeCrtl = $this->select->getEmployeeSelectCtrl($this->invoice_salesperson,'Y');
//        $this->customerCrtl = $this->select->getCustomerSelectCtrl($this->invoice_customer,'N');
//        $this->currencyCrtl = $this->select->getCurrencySelectCtrl($this->invoice_currency,'N');
//        $this->shiptermCrtl = $this->select->getShipTermSelectCtrl($this->invoice_shipterm,'N');
//        $this->contactCrtl = $this->select->getContactSelectCtrl($this->invoice_attentionto,'Y'," AND contact_partner_id = '$this->invoice_customer'");
//        $this->uomCrtl = $this->select->getUomSelectCtrl("",'N');
//        if($_SESSION['empl_group'] >= 1){
//            $remark_selection_wherestring = " AND tranremark_country = '{$_SESSION['empl_outlet']}'";
//            $markup_selection_wherestring = " AND markup_country = '{$_SESSION['empl_outlet']}'";
//            $attn_selection_wherestring = " AND attnremark_country = '{$_SESSION['empl_outlet']}'";
//            $paymentterm_selection_wherestring = " AND paymentterm_country = '{$_SESSION['empl_outlet']}'";
//        }
//        $this->attremarkCrtl = $this->select->getAttnRemarkselectionSelectCtrl($this->invoice_attnremark_selection,'Y',$attn_selection_wherestring);
//        $this->paymenttermCrtl = $this->select->getPaymenttermSelectCtrl($this->invoice_paymentterm,'Y',$paymentterm_selection_wherestring);
//        $this->remarkselectionCrtl = $this->select->getRemarkselectionSelectCtrl($this->invoice_remark_selection,'Y',$remark_selection_wherestring);
//        $label_col_sm = "col-sm-2";
//        $field_col_sm = "col-sm-3";

        
//        if($this->invoice_countprint > 0){
//            $disabled = " DISABLED";
//        }
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $this->document_name;?></title>
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
      <div class="content-wrapper" style="margin-left:230px;">
        <div class="content-wrapper">
          <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $this->document_name;?></h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-binvoice">
                <h3 class="box-title"><?php if($this->invoice_id > 0){ echo "Update " . $this->document_code;}else{ echo "Create New " . $this->document_code;}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='<?php echo $this->document_url;?>'">Back</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create') && ($this->invoice_id > 0)){?>
                <!--<button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='<?php echo $this->document_url;?>?action=createForm'">Create New</button>-->
                <?php }?>

              </div>

                <form id = 'invoice_form' class="form-horizontal" action = '<?php echo $this->document_url;?>?action=create' method = "POST">
                  <div class="box-body col-sm-12">
                      
                      
                        <div class="form-group">                
                            <label for="invoice_type" class="col-sm-2 control-label">Invoice Type <?php echo $mandatory;?></label>
                            <div class="col-sm-3">
                                 <select class="form-control select2" id="invoice_type" name="invoice_type" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                                    <option value="DR" <?php if($this->invoice_type == 'DR'){ echo 'SELECTED';}?>>DAILY RATE</option>
                                    <option value="HR" <?php if($this->invoice_type == 'HR'){ echo 'SELECTED';}?>>HOURLY RATE</option>
                                    <option value="P" <?php if($this->invoice_type == 'P'){ echo 'SELECTED';}?>>PERMANENT</option>
                                    <option value="C" <?php if($this->invoice_type == "C"){ echo 'SELECTED';}?>>Contract</option>
<!--                                    <option value="SS" <?php if($this->invoice_type == "SS"){ echo 'SELECTED';}?>>SALES STAFF</option>
                                    <option value="PI" <?php if($this->invoice_type == 'PI'){ echo 'SELECTED';}?>>PERM INVOICE</option>
                                    <option value="R"  <?php if($this->invoice_type == 'R'){ echo 'SELECTED';}?>>Reimbursement</option>-->
                                 </select>
                            </div>              
                            <label for="invoice_outlet" class="col-sm-2 control-label">Outlet<?php echo $mandatory;?></label>
                            <div class="col-sm-3">
                                 <select class="form-control select2" id="invoice_outlet" name="invoice_outlet" style = 'width:100%'>
                                    <?php echo $this->outletCrtl; ?>
                                 </select>
                            </div>
                        </div> 
                      
                      
                      <div class =" parttime">
                        <div class="form-group">
                              <label for="invoice_date" class="col-sm-2 control-label">Date <?php echo $mandatory;?></label>
                              <div class="col-sm-3">
                                  <input type="text" class="form-control datepicker" id="invoice_date" name="invoice_date" value = "<?php if($this->invoice_date == ""){ echo format_date(date("Y-m-d")); } else { echo format_date($this->invoice_date); }?>" placeholder="Date" <?php if ($edit == "1"){echo "disabled";}?>>
                              </div>
                              <label for="invoice_no" class="col-sm-2 control-label">Invoice No</label>
                              <div class="col-sm-3">
                                <input type="text" class="form-control" id="invoice_no" name="invoice_no" value = "<?php echo $this->invoice_no;?>" readonly>
                              </div>
                        </div>           
                        <div class="form-group">
                              <label for="client_name" class="col-sm-2 control-label">Client Name <?php echo $mandatory;?></label>
                              <div class="col-sm-3">
                                     <select class="form-control select2" id="client_name" name="client_name" style = 'width:100%' <?php if ($edit == "1"){echo "disabled";}?>>
                                         <?php echo $this->clientCrtl; ?>
                                    </select>
                              </div>                            
                              <label for="invoice_terms" class="col-sm-2 control-label">Terms</label>
                              <div class="col-sm-3">
                                <input type="text" class="form-control" id="invoice_attn" name="invoice_terms" value = "<?php echo $this->invoice_terms;?>">
                              </div>                            
                        </div>                         
                        <div class="form-group">
                              <label for="invoice_attention_person" class="col-sm-2 control-label">Attention Person</label>
                              <div class="col-sm-3">
                                <textarea class="form-control" rows="3" id="invoice_attention_person" name="invoice_attention_person" placeholder=""><?php echo $this->invoice_attention_person;?></textarea>
                              </div>                              
                              <label for="invoice_gst_reg_no" class="col-sm-2 control-label">GST REG NO</label>
                              <div class="col-sm-3">
                                <input type="text" class="form-control" id="invoice_gst_reg_no" name="invoice_gst_reg_no" value = "<?php echo $this->invoice_gst_reg_no;?>">
                              </div>
                        </div>                         
                        <div class="form-group">
                              <label for="invoice_postal_code" class="col-sm-2 control-label">Postal Code</label>
                              <div class="col-sm-3">
                                <input type="text" class="form-control" onkeyup="checkEnter()" id="invoice_postal_code" name="invoice_postal_code" value = "<?php echo $this->invoice_postal_code;?>" placeholder="Postal Code">
                              </div>
                              <label for="invoice_unit_no" class="col-sm-2 control-label">Unit No</label>
                              <div class="col-sm-3">
                                <input type="text" class="form-control" id="invoice_unit_no" name="invoice_unit_no" value = "<?php echo $this->invoice_unit_no;?>" placeholder="Unit No">
                              </div>
                        </div>
                        <div class="form-group">
                            <label for="invoice_address" class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-3">
                                  <textarea class="form-control" rows="3" id="invoice_address" name="invoice_address" placeholder="Address" readonly><?php echo $this->invoice_address;?></textarea>
                            </div>
                            <label for="invoice_remark" class="col-sm-2 control-label">Remarks</label>
                            <div class="col-sm-3">
                                  <textarea class="form-control" rows="3" id="invoice_remark" name="invoice_remark" placeholder="eg: Being billing for PERMANENT placement"><?php echo $this->invoice_remark;?></textarea>
                            </div>
                            <input type = "hidden" value = "<?php echo $this->invoice_amount;?>" id = "invoice_amount" name = "invoice_amount"/>
                        </div>
                        <div class="form-group">
                              <label for="invoice_gst" class="col-sm-2 control-label">GST Charge %</label>
                              <div class="col-sm-3">
                                <input type="text" class="form-control" id="invoice_gst" name="invoice_gst" value = "<?php echo $this->invoice_gst;?>" placeholder="7" readonly>
                              </div>
                        </div>
                          
                         <div class="col-xs-12">
                                <div class="box-body table-responsive no-padding" id="applicant_invoice_content">
                                </div>
                          </div>       
                      </div>
                  </div>  
                  

                      
                      
                      

                <div class="box-body col-sm-9">
                    <?php if($_SESSION['empl_outlet'] == 14){?>
                    <div class="form-group">
                        <label for="invoice_shipment" class="<?php echo $label_col_sm;?> control-label">Shipment</label>
                        <div class="<?php echo $field_col_sm;?>">
                              <input type = "checkbox" id = 'invoice_shipment' name = 'invoice_shipment' value = '1' <?php if($this->invoice_shipment == 1){ echo 'checked';}?>/>
                        </div>
                        <label style = '<?php if($this->invoice_shipment == 0){ echo 'display:none'; }?>' for="invoice_shipment_remark" class="<?php echo $label_col_sm;?> control-label shipment_class">Shipment Remark </label>
                        <div style = '<?php if($this->invoice_shipment == 0){ echo 'display:none'; }?>' class="<?php echo $field_col_sm;?> shipment_class">
                              <textarea class="form-control " rows="3" id="invoice_shipment_remark" name="invoice_shipment_remark" placeholder="Shipment Remark" <?php echo $disabled;?>><?php echo $this->invoice_shipment_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group shipment_class" style = '<?php if($this->invoice_shipment == 0){ echo 'display:none'; }?>'>
                          <label for="invoice_shipment_amount" class="<?php echo $label_col_sm;?> control-label"></label>
                          <div class="<?php echo $field_col_sm;?>">

                          </div>
                          <label for="invoice_shipment_amount" class="<?php echo $label_col_sm;?> control-label">Shipment Amount</label>
                          <div class="<?php echo $field_col_sm;?>">
                            <input type="text" class="form-control" id="invoice_shipment_amount" name="invoice_shipment_amount" value = "<?php echo $this->invoice_shipment_amount;?>" placeholder="Shipment Amount" <?php echo $disabled;?>>
                          </div>
                    </div>
                    <?php }?>
                  </div><!-- /.box-body -->
<!--                  <div class="box-body col-sm-3">
                    <div class="form-group">
                        <label for="invoice_subtotal" class="col-sm-5 control-label">Total (<span class = 'base_currency_span'><?php echo $this->invoice_currency_code;?></span>)</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control text-align-right" id="invoice_subtotal" name="invoice_subtotal" value = "<?php echo num_format($this->invoice_subtotal);?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="invoice_disctotal" class="col-sm-5 control-label">Disc (<span class = 'base_currency_span'><?php echo $this->invoice_currency_code;?></span>)</label>
                        <div class="col-sm-7">
                              <input type="text" class="form-control text-align-right" id="invoice_disctotal" name="invoice_disctotal" value = "<?php echo num_format($this->invoice_disctotal);?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-5 control-label">Sub Total (<span class = 'base_currency_span'><?php echo $this->invoice_currency_code;?></span>)</label>
                        <div class="col-sm-7">
                              <input type="text" class="form-control text-align-right"  value = "<?php echo num_format($this->invoice_subtotal - $this->invoice_disctotal);?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="invoice_taxtotal" class="col-sm-5 control-label">Tax Amount (<span class = 'base_currency_span'><?php echo $this->invoice_currency_code;?></span>)</label>
                        <div class="col-sm-7">
                              <input type="text" class="form-control text-align-right" id="invoice_taxtotal" name="invoice_taxtotal" value = "<?php echo num_format($this->invoice_taxtotal);?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="invoice_grandtotal" class="col-sm-5 control-label">Grand Total (<span class = 'base_currency_span'><?php echo $this->invoice_currency_code;?></span>)</label>
                        <div class="col-sm-7">
                              <input type="text" class="form-control text-align-right" id="invoice_grandtotal" name="invoice_grandtotal" value = "<?php echo num_format($this->invoice_grandtotal);?>" disabled>
                        </div>
                    </div>
                  </div>-->
                  <div class="box-footer" style = 'clear:both'>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->invoice_status;?>" name = "invoice_status"/>
                    <input type = "hidden" value = "<?php echo $this->invoice_id;?>" name = "invoice_id"/>
                    <?php
                    if($this->invoice_id > 0){
                        $prm_code = "update";
                    }else{
                        $prm_code = "create";
                    }
                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){
//                        if($this->invoice_countprint == 0){
                    ?>
                    <button type = "submit" class="btn btn-info">
                    <?php 
                    if($this->invoice_id > 0){
                        echo "Update";
                    }
                    else {
                        echo "Save";
                    }
                    ?>
                    </button>
                    <?php
//                        }
                    }?>
                    &nbsp;&nbsp;&nbsp;
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'print') && ($this->invoice_id > 0)){?>
                    <button type = "button" class="btn btn-primary print"  onclick = "window.open('invoice_print.php?action=print_invoice&invoice_id=<?php echo $this->invoice_id;?>&date=<?php echo $this->invoice_date;?>&client_id=<?php echo $this->client_name;?>&job_type=<?php echo $this->invoice_type?>&invoice_gst=<?php echo $this->invoice_gst?>')">Print Summary</button>
                    &nbsp;&nbsp;&nbsp;
                    <button type = "button" class="btn bg-maroon"  onclick = "window.open('invoice.php?action=printReceiptM1&invoice_id=<?php echo $this->invoice_id;?>&date=<?php echo $this->invoice_date;?>&client_id=<?php echo $this->client_name;?>&job_type=<?php echo $this->invoice_type?>&invoice_gst=<?php echo $this->invoice_gst?>')">Print Receipt Modal 1</button>
                    <button type = "button" class="btn bg-maroon"  onclick = "window.open('invoice.php?action=printReceiptM2&invoice_id=<?php echo $this->invoice_id;?>&date=<?php echo $this->invoice_date;?>&client_id=<?php echo $this->client_name;?>&job_type=<?php echo $this->invoice_type?>&invoice_gst=<?php echo $this->invoice_gst?>')">Print Receipt Modal 2</button>


                    <?php }?>
                  </div><!-- /.box-footer -->
                </form>

            </div><!-- /.box -->
            <?php if($this->invoice_id > 0){?>
<!--            <div class="box box-success">
                <div class="nav-tabs-custom" style = 'margin-top:5px;'>
                    <ul class="nav nav-tabs">
                      <li <?php if($_REQUEST['tab'] == "" || $_REQUEST['tab'] == 'detail_tab'){ echo 'class="active"';}?>><a href="#detail_tab" data-toggle="tab">Detail</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane <?php if($_REQUEST['tab'] == "" || $_REQUEST['tab'] == 'detail_tab'){ echo 'active';}?>" id="detail_tab">
                            <?php echo $this->getAddItemDetailForm();?>
                        </div>
                    </div>
                </div>
            </div> /.box -->
            <?php }?>
          </section><!-- /.content -->
        </div><!-- /.container -->
      </div><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';
    ?>
    <script>

    var line_copy = '<tr id = "line_@i" class="tbl_grid_odd" line = "@i">' +
                    '<td style = "width:30px;padding-left:5px">@i</td>' +
                    '<td style = "width:60px;"><input type = "text" id = "invl_seqno_@i" class="form-control" value=""/></td>'+
                    '<td style = "width:180px;"><select id = "invl_pro_id_@i" class="form-control invt_autocomplete "></select></td>'+
                    '<td class = ""><textarea id = "invl_pro_desc_@i" class="form-control"></textarea></td>'+
                    '<td style = "width:60px;"><input type = "text" id = "invl_qty_@i" class="form-control calculate" value="1.00"/></td>'+
                    '<td style = "width:80px;"><select id = "invl_uom_@i" class="form-control select2"><?php echo $this->uomCrtl;?></select></td>'+
                    '<td style = "width:80px;"><input type = "text" id = "invl_fuprice_@i" class="form-control calculate text-align-right"/></td>'+
                    '<td style = "width:80px;"><input type = "text" id = "invl_uprice_@i" class="form-control calculate text-align-right"/></td>'+
                    '<td style = "width:80px;"><input type = "text" id = "invl_markup_@i" class="form-control calculate text-align-right"/><input type = "checkbox" id = "invl_enable_@i" value = "1"/>Enable</td>'+
                    '<td style = "width:60px;"><input type = "text" id = "invl_disc_@i" class="form-control calculate text-align-right"/></td>'+
                    '<td style = "width:20px;"><input type = "checkbox" style = "width:20%" id = "invl_istax_@i" class = "minimal isincludetax"/></td>'+
                    '<td style = "width:80px;"><input readonly type = "text" id = "invl_taxamt_@i" class="form-control text-align-right"/></td>'+
                    '<td style = "width:100px;"><input readonly type = "text" id = "invl_total_@i" class="form-control text-align-right"/></td>'+
                    '<td align = "center" class = "" style ="vertical-align:top;width:80px;padding-right:10px;padding-left:5px">' +
                    '<img id = "save_line_@i" invl_id = "" class = "save_line" line = "@i" src = "dist/img/add.png" style = "cursor:pointer" alt = "Add New"/>' +
                    '<img id = "delete_line_@i" invl_id = "" class = "delete_line" line = "@i" src = "dist/img/delete_icon.png" style = "cursor:pointer" alt = "Delete"/>' +
                    '</td>'+
                    '</tr>';



    $(document).ready(function() {
        //addline(); //User Request cannot add / update on invoice
        $(".select2").select2({
            placeholder: "Select One"
        });
        
        $("#invoice_form").validate({
                  rules: 
                  {
                      invoice_type:
                      {
                          required: true
                      },
                      invoice_date:
                      {
                          required: true
                      },
                      client_name:
                      {
                          required: true
                      } 
                  },
                  messages:
                  {
                      invoice_type:
                      {
                          required: "Please choose invoice type."
                      },
                      invoice_date:
                      {
                          required: "Please enter invoice date.",
                      },
                      client_name:
                      {
                            required: "Please select client."
                      }
                  }
              });








        $('.report').on("click", function(e) {
           <?php if($_SESSION['empl_outlet'] == 13){?>

                   window.open('invc_pa_print.php?action=<?php echo $this->document_type;?>&report_id=<?php echo $this->invoice_id;?>&p=1&report_name=Payment Appointment');

           <?php }?>
            var data = "action=addCountPrint&invoice_id=<?php echo $this->invoice_id;?>";
             $.ajax({
                type: "POST",
                url: "invoice.php",
                data:data,
                success: function(data) {
//                   window.location.reload();

                }
             });
        });

//        $('#invoice_type').change(function(){
//            var data = $(this);
//              if(data.val() == "DR" || data.val() == "HR" || data.val() == "MR"){
//                  $('.parttime').show();
//              }else{
//                  $('.parttime').hide();
//              }
//
//        });

        $('.applicantTable').hide();
        
        $('.getapplicant').click(function(){
                var data = "action=getApplicantDetail&client_id="+$('#client_name').val()+"&invoice_type="+$('#invoice_type').val()+"&invoice_date="+$('#invoice_date').val();

                $.ajax({ 
                    type: 'POST',
                    url: 'invoice.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                        
                       issend = false;
                    }		
                 });
                 return false;       
        });

        $('#invoice_outlet').change(function(){
            var value = $(this);
            var data = "action=getGST&outlet_id="+value.val();
            
            $.ajax({ 
                    type: 'POST',
                    url: 'invoice.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       
                       document.getElementById("invoice_gst_reg_no").value =  jsonObj['gst']['gst_no'];
                       document.getElementById("invoice_gst").value =  jsonObj['gst']['gst'];
                       
                       issend = false;
                    }		
                 });
                 return false; 
        });
        

        $('#client_name').change(function(){
            
                if($('#invoice_date').val() == ""){
                    alert("Please select valid date");
                }
                
                var data = "action=getApplicantList&client_id="+$('#client_name').val()+"&invoice_type="+$('#invoice_type').val()+"&invoice_date="+$('#invoice_date').val()+"&invoice_gst="+$('#invoice_gst').val();

                $.ajax({ 
                    type: 'POST',
                    url: 'invoice.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       
                        var table = "";


                        if( jsonObj['applicant_List'] == "0" ){
                            table = "<h4 style='color:red'>No have any candidate.</h4>";
                        }
                        else if( jsonObj['applicant_List'] == "1" ){
                            table = "<h4 style='color:green'>Please create payroll first.</h4>";
                        }
                        else{
                            table = "<h4>Candidate Detail</h4> <h6>" + "Invoice Period : " + jsonObj['applicant_List'][0]['invoice_startdate'] + " to " + jsonObj['applicant_List'][0]['invoice_lastdate'] + "</h6><table class= 'table table-bordered table-hover'" + "><thead><tr style='background-color: #3c8dbc;color: fff;'> <th style = 'width:2%'>No</th>";
                            table = table + "<th style = 'width:5%'>Candidate</th><th style = 'width:5%'>Department</th><th style = 'width:5%'>Position</th><th style = 'width:3%'>Worked Day</th>";
                            table = table + "<th style = 'width:5%'>Salary</th><th style = 'width:5%'>Additional</th><th style = 'width:5%'>Deduction</th><th style = 'width:5%'>Employee CPF</th><th style = 'width:5%'>Unpaid Leave</th>"
                            table = table + "<th style = 'width:5%'>Netpay</th>" + "<th style = 'width:5%'>Admin Fee</th>" + "<th style = 'width:5%'>GST</th>" + "<th style = 'width:5%'>Invoice Amount</th>" + "</tr></thead><tbody>";
                        var n = 1; 
                        for(var i=0;i<jsonObj['applicant_List'].length;i++){
                            table = table + "<tr><td>" + n +"</td><td>" + jsonObj['applicant_List'][i]['applicant_name'] + "</td>"; 
                            table = table + "<td>" + jsonObj['applicant_List'][i]['applicant_department'] + "</td><td>" + jsonObj['applicant_List'][i]['applicant_position'] + "</td><td>" + jsonObj['applicant_List'][i]['applicant_work_day']+" day" + "</td>";
                            table = table + "<td>$ " + jsonObj['applicant_List'][i]['applicant_salary'] + "</td><td>$ " + jsonObj['applicant_List'][i]['applicant_additional'] + "</td><td>$ " + jsonObj['applicant_List'][i]['applicant_deduction'] + "</td><td>$ " + jsonObj['applicant_List'][i]['applicant_cpf_employee'] + "</td>";
                            table = table + "<td>$ " + jsonObj['applicant_List'][i]['applicant_levy_employee'] + "</td><td>$ " + jsonObj['applicant_List'][i]['applicant_netpay'] + "</td><td>$ " + jsonObj['applicant_List'][i]['applicant_admin_fee'] + "</td><td>$ " + jsonObj['applicant_List'][i]['invoice_gst'] + "</td><td>$ " + jsonObj['applicant_List'][i]['invoice_amount'] + "</td></tr>"; 
                            n++;
                            }
                            table = table + "<tr class = 'payslipslisting'><td colspan = '9'></td><td style = 'font-weight:bold;font-size:12px;background-color:#3c8dbc;color:#fff'> Total : </td><td style = 'font-weight:bold;'>$ " + jsonObj['applicant_List'][0]['total_netpay'] + "</td>";
                            table = table + "<td style = 'font-weight:bold;'>$ " + jsonObj['applicant_List'][0]['total_admin_fee'] + "</td><td style = 'font-weight:bold;'>$ " + jsonObj['applicant_List'][0]['total_invoice_gst'] + "</td><td style = 'font-weight:bold;'>$ " + jsonObj['applicant_List'][0]['total_invoice_amount'] + "</td></tr>";
//                            table = table + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th>";
//                            table = table + "<th style = 'width:5%'>Candidate</th><th style = 'width:5%'>Department</th><th style = 'width:5%'>Position</th><th style = 'width:3%'>Worked Day</th>";
//                            table = table + "<th style = 'width:5%'>Salary</th><th style = 'width:5%'>Additional</th><th style = 'width:5%'>Deduction</th><th style = 'width:5%'>Employee CPF</th><th style = 'width:5%'>Unpaid Leave</th>"
//                            table = table + "<th style = 'width:5%'>Netpay</th>" + "<th style = 'width:5%'>Admin Fee</th>" + "<th style = 'width:5%'>Invoice Amount</th>" + "</tr></thead><tbody>";
                            document.getElementById("invoice_amount").value =  jsonObj['applicant_List'][0]['t_invoice_amount'];
                        }

                        $('#applicant_invoice_content').html(table);
                        
                       issend = false;
                    }		
                 });
                 return false;       
        });
        
        $('#invoice_type').change(function(){
                $('#client_name').trigger('change');    
        });        

        $('#invoice_date').change(function(){
                $('#client_name').trigger('change');    
        });  
        
        $('#invoice_gst').keyup(function(){
            $('#client_name').trigger('change');  
        });
        
        
        $('#client_name').trigger('change');
        
        itemCodeAutoComplete();
        $('.invt_autocomplete').on("change", function(e) {
           getProductDetail($(this).val(),$(this).closest("tr").attr('line'));
        });
        $('.save_line').on('click',function(){
            saveline($(this).attr('line'),$(this).attr('invl_id'));
        });
        $('.delete_line').on('click',function(){
            deleteline($(this).attr('invl_id'));
        });
        $('.calculate').on('keyup',function(){
            calculate($(this).closest("tr").attr('line'));
        });
        $('.isincludetax').on('ifChanged',function(){
            calculate($(this).closest("tr").attr('line'));
        });
        $('.generate_btn').on('click',function(){
            generateDocument($(this).attr('generateto'));
        });
        $('#invoice_paymentterm,#invoice_remark_selection,#invoice_attnremark_selection').on('change',function(){
            getRemark($(this).attr('pid'),$(this).val());
        });
        $('#invoice_currency').on('change',function(){
            var data = "action=getCurrencyRateDetail&crate_tcurrency_id="+$(this).val()
             $.ajax({
                type: "POST",
                url: "crate.php",
                data:data,
                success: function(data) {
                    var jsonObj = eval ("(" + data + ")");
                    $('#invoice_currencyrate').val(jsonObj.crate_rate);
                }
             });
        });
        $('#invoice_shipment').click(function(){
            if($(this).is(":checked")){
                $('.shipment_class').css('display','');
            }else{
                $('.shipment_class').css('display','none');
            }
        });
        $('#invoice_customer').change(function(){
            var data = "action=getPartnerDetailTransaction&partner_id="+$(this).val()
             $.ajax({
                type: "POST",
                url: "partner.php",
                data:data,
                success: function(data) {
                    var jsonObj = eval ("(" + data + ")");

                    $('#invoice_attentionto').select2("destroy");
                    $('#invoice_attentionto').select2();
                    $('#invoice_billaddress').html(jsonObj.partner_bill_address);
                    $('#invoice_shipaddress').html(jsonObj.partner_ship_address);
                    //Base on Login User
//                    $('#invoice_currency').val(jsonObj.partner_currency);
                    $('#invoice_salesperson').val(jsonObj.invoice_salesperson);
                    $('#invoice_attentionto').html(jsonObj.contact_option);
                    $('#invoice_attentionto').select2("val", "");
                }
             });
        });
        $('#invoice_attentionto').change(function(){
            var data = "action=getContactJson&contact_id="+$(this).val()
             $.ajax({
                type: "POST",
                url: "partner.php",
                data:data,
                success: function(data) {
                    var jsonObj = eval ("(" + data + ")");
                    $('#invoice_attentionto_phone').val(jsonObj.contact_tel);
                    $('#invoice_attentionto_name').val(jsonObj.contact_name);
                    $('#invoice_fax').val(jsonObj.contact_fax);
                }
             });
        });
        //iCheck for checkbox and radio inputs
//        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
//          checkboxClass: 'icheckbox_minimal-blue',
//          radioClass: 'iradio_minimal-blue'
//        });

        $("#invoice_form").validate({
              rules:
              {
                  invoice_name:
                  {
                      required: true
                  }
              },
              messages:
              {
                  invoice_name:
                  {
                      required: "Please enter customer first name."
                  }
              }
        });
    });
    var issend = false;
    function saveline(line,invl_id){
        if(issend){
            alert("<?php echo $language[$lang]['pleasewait'];?>");
            return false;
        }

        // Uncheck check
        if($('#invl_istax_'+line).is(':checked')){
           var invl_istax = 1;
        }else{
           var invl_istax = 0;
        }
        issend = true;
        if(invl_id != ""){
            var action = 'updateline';
        }else{
            var action = 'saveline';
        }

        var data = "invl_seqno="+$('#invl_seqno_'+line).val();
            data += "&invl_pro_id="+$('#invl_pro_id_'+line).val();
            data += "&invl_pro_no="+$('#invl_pro_id_'+line).select2('data')[0].text;
            data += "&invl_pro_desc="+encodeURIComponent($('#invl_pro_desc_'+line).val());
            data += "&invl_qty="+$('#invl_qty_'+line).val();
            data += "&invl_uom="+$('#invl_uom_'+line).val();
            data += "&invl_uprice="+$('#invl_uprice_'+line).val();
            data += "&invl_disc="+$('#invl_disc_'+line).val();
            data += "&invl_istax="+invl_istax;
            data += "&action="+action;
            data += "&invl_id="+invl_id;
            data += "&invoice_id=<?php echo $_REQUEST['invoice_id'];?>";

        $.ajax({
            type: 'POST',
            url: '<?php echo $this->document_url;?>',
            cache: false,
            data:data,
            error: function(xhr) {
                alert("<?php echo $language[$lang]['system_error']?>");
                issend = false;
            },
            success: function(data) {
               var jsonObj = eval ("(" + data + ")");
               if(jsonObj.status == 1){
                   window.location.reload();
               }else{
                   alert("<?php echo $language[$lang]['addeditline_error'];?>");
               }
               issend = false;
            }
         });
         return false;
    }
    function deleteline(invl_id){
        var data = "action=deleteline&invoice_id=<?php echo $this->invoice_id;?>&invl_id="+invl_id;
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->document_url;?>',
            cache: false,
            data:data,
            error: function(xhr) {
                alert("<?php echo $language[$lang]['system_error']?>");
                issend = false;
            },
            success: function(data) {
               var jsonObj = eval ("(" + data + ")");
               if(jsonObj.status == 1){
                   window.location.reload();
               }else{
                   alert("<?php echo $language[$lang]['deleteline_error'];?>");
               }
               issend = false;
            }
         });
         return false;
    }
    function calculate(line){
        var qty = parseFloat($('#invl_qty_'+line).val().replace(/,/gi, ""));
        var unit_price = parseFloat($('#invl_uprice_'+line).val().replace(/,/gi, ""));
        var discount = parseFloat($('#invl_disc_'+line).val().replace(/,/gi, ""));

        if(qty == ""){
           qty = 1;
        }
        if(isNaN(unit_price)){
           unit_price = 0;
        }
        if(isNaN(discount)){
           discount = 0;
        }

        var subtotal = parseFloat(qty) * parseFloat(unit_price);

        if(discount > 0){
            var disc_amt = RoundNum(parseFloat(subtotal) * (parseFloat(discount)/100),2);
        }else{
            var disc_amt = 0;
        }

        var subtotal_afterdiscount = parseFloat(subtotal) - parseFloat(disc_amt);

        if($('#invl_istax_'+line).is(':checked')){
           var gst_amt = RoundNum(parseFloat(subtotal_afterdiscount) * (parseFloat(system_gst_percent)/100),2);
        }else{
           var gst_amt = 0;
        }

        var grandtotal = RoundNum(parseFloat(subtotal_afterdiscount) + parseFloat(gst_amt),2);

        $('#invl_total_'+line).val(changeNumberFormat(grandtotal));
        $('#invl_taxamt_'+line).val(changeNumberFormat(RoundNum(gst_amt,2)));
    }
    function getProductDetail(product_id,line){
         var data = "action=getProductDetail&product_id="+product_id;
         $.ajax({
            type: "POST",
            url: "product.php",
            data:data,
            success: function(data) {
                var jsonObj = eval ("(" + data + ")");

                $('#invl_pro_desc_'+line).html(jsonObj.product_desc);
                $('#invl_uprice_'+line).val(jsonObj.product_sales_price);
                calculate(line);
            }
         });
    }
    function itemCodeAutoComplete(){

        $(".invt_autocomplete").select2({
              placeholder: "Search for a Item",
              minimumInputLength: 4,
              ajax: {
                  url: 'autocomplete.php?action=item',
                  dataType: 'json',
                  cache: true,
                    processResults: function (data, params) {
                      params.page = params.page || 1;

                      return {
                        results: data.items,
                        pagination: {
                          more: (params.page * 30) < data.total_count
                        }
                      };
                    }
              },
              initSelection: function(element, callback) {
                        var elementText = $(element)[0].textContent;
                        var data = {"value":elementText};
                        callback(data);

              },
              templateResult: function(data) {
                              return data.text;
              },
              templateSelection: function(data){
                    return data.value;
              },
        });
    }
    function generateDocument(generate_document_type){
         var data = "action=generateDocument&invoice_id=<?php echo $this->invoice_id;?>&generate_document_type="+generate_document_type;
         $.ajax({
            type: "POST",
            url: "<?php echo $this->document_url;?>",
            data:data,
            success: function(data) {
                var jsonObj = eval ("(" + data + ")");
                if(jsonObj.status == 1){
                    window.location.href = "<?php echo $this->document_url;?>?action=edit&invoice_id=<?php echo $_REQUEST['invoice_id']?>&tab="+jsonObj.tab;
                }else{
                    alert("<?php echo $language[$lang]['generate_error'];?>");
                }
            }
         });
    }
    function addline(){
        var addlinevalue = $('#total_line').val();
        var nextvalue = parseInt(addlinevalue)+1;
        var newline = line_copy.replace(/@i/g,nextvalue);
        $('#detail_last_tr').before(newline);
        $('#total_line').val(nextvalue);
        $('#invl_seqno_'+nextvalue).val(nextvalue*10);
    }
    function getRemark(pid,id){
    if(pid == "payment_term"){
        var url = "paymentterm.php";
        var parameter = "&paymentterm_id="+id;
        var field_id = "invoice_paymentterm_remark";
    }else if(pid == "attnremark"){
        var url = "attnremark.php";
        var parameter = "&attnremark_id="+id;
        var field_id = "invoice_attn_remark";
    }else{
        var url = "tranremark.php";
        var parameter = "&tranremark_id="+id;
        var field_id = "invoice_remark";
    }
            var data = "action=getJsonData"+parameter;
            $.ajax({
               type: "POST",
               url: url,
               data:data,
               success: function(data) {
                   var jsonObj = eval ("(" + data + ")");
                   $('#'+field_id).val(jsonObj.remark);
               }
            });
    }
    </script>
    <script>
        
    function checkEnter() {
            var x =  document.getElementById('invoice_postal_code').value;
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
                                                                            jQuery('#invoice_address').val(subObject); 
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
    <title><?php echo $this->document_code;?> Management</title>
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
      <div class="content-wrapper" style="margin-left:230px">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $this->document_code;?> Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $this->document_code;?> Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='<?php echo $this->document_url;?>?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="invoice_table" class="table table-binvoiceed table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:8%'>Invoice No</th>
                        <th style = 'width:10%'>Invoice Type</th>
                        <th style = 'width:10%'>Invoice Date</th>
                        <th style = 'width:15%'>Invoice Client</th>
                        <th style = 'width:10%'>GST REG No</th>
                        <th style = 'width:10%'>Attention Person</th>
                        <th style = 'width:10%'>Invoice Amount</th>
                        <th style = 'width:15%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $this->filter_month_date = escape($_REQUEST['filter_month_date']);
                      $sql = "SELECT i.*, p.partner_name FROM db_invoice i INNER JOIN db_partner p ON p.partner_id = i.invoice_client WHERE i.invoice_id > 0 AND i.invoice_status = '0' AND LEFT(i.invoice_date,7) = '$this->filter_month_date'";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['invoice_no'];?></td>
                            <td><?php if($row['invoice_type'] == "DR"){ echo "Daily Rate";}
                                      else if($row['invoice_type'] == "HR"){ echo "Hourly Rate";}
                                      else if($row['invoice_type'] == "MR"){ echo "Monthly Rate";}
                                      else if($row['invoice_type'] == "SS"){ echo "Sales Staff";}
                                      else if($row['invoice_type'] == "PI"){ echo "Perm Invoice";}
                                      else if($row['invoice_type'] == "R"){ echo "Reimbursement";}
                                ?></td>
                            <td><?php echo format_date($row['invoice_date']);?></td>
                            <td>
                                <?php
                                   echo $row['partner_name'];
                                ?>
                            </td>
                            <td><?php echo $row['invoice_gst_reg_no'];?></td>
                            <td><?php echo nl2br($row['invoice_attention_person']);?></td>
                            <td><?php echo "$ ".number_format($row['invoice_amount'],2);?></td>
                            <td class = "text-align-right">
                                <?php
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = '<?php echo $this->document_url;?>?action=edit&invoice_id=<?php echo $row['invoice_id'];?>&edit=1'">Edit</button>
                                <?php }?>
                                <?php
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('<?php echo $this->document_url;?>?action=delete&invoice_id=<?php echo $row['invoice_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:8%'>Invoice No</th>
                        <th style = 'width:10%'>Invoice Type</th>
                        <th style = 'width:10%'>Invoice Date</th>
                        <th style = 'width:15%'>Invoice Client</th>
                        <th style = 'width:10%'>GST REG No</th>
                        <th style = 'width:10%'>Attention Person</th>
                        <th style = 'width:10%'>Invoice Amount</th>
                        <th style = 'width:15%'></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper --><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';
    ?>
    <script>
      $(function () {
        $('#invoice_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });
    </script>
  </body>
</html>
    <?php
    }
    public function getListingMonth(){
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Invoice Management</title>
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
            <h1>Invoice Management</h1>
          <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Invoice Month Listing</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Invoice Month Listing</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                 
                <button class="btn btn-primary pull-right" onclick = "window.location.href='<?php echo $this->document_url;?>?action=createForm'">Create New + </button> 
                <?php  }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="month_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:10%'>Month</th>
                        <th style = 'width:10%'>Total</th>
                        <th style = 'width:12%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php    
                    
                          $sql = "SELECT *,LEFT(invoice_date,7) as month

                                  FROM db_invoice 
                                  WHERE invoice_id > 0 AND invoice_status = '0' AND invoice_client > '0'
                                  GROUP BY YEAR(invoice_date), MONTH(invoice_date)
                                  ORDER BY invoice_date DESC";

                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <!--<td><input type = "checkbox" name = 'payroll_checkbox' class = 'payroll_checkbox_child' value = '<?php echo $row['payroll_id'];?>'/></td>-->
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['month'];?></td>
                            <td>
                                <?php 
                                $sql2 = "SELECT SUM(invoice_amount) AS total FROM db_invoice WHERE invoice_status = '0' AND LEFT(invoice_date,7) = '{$row['month']}'";
                                $query2 = mysql_query($sql2);
                                $row2 = mysql_fetch_array($query2);
                                echo  "$ ".number_format($row2['total'],2);
                                ?>
                            </td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'view')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'invoice.php?action=listing&filter_month_date=<?php echo $row['month'];?>'">View</button>
                                <?php }
                                ?>
                                

                                 
                            </td>
                        </tr>
                    <?php    
                        $i++;
                      }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <!--<th style = 'width:2%'><input type = "checkbox" name = 'payroll_checkbox' class = 'payroll_checkbox_parent' /></th>-->  
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:10%'>Month</th>
                        <th style = 'width:10%'>Total</th>
                        <th style = 'width:12%'></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper --><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';
    ?>
    <script>
      $(function () {
        $('#month_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
        
        $('.payroll_checkbox_parent').click(function(){
                if($(this).is(':checked')){
                    $('.payroll_checkbox_child').prop('checked',true);
                }else{
                    $('.payroll_checkbox_child').prop('checked',false);
                }

        });
        
        $('#confirm_btn').click(function(){
            var payroll_id = [];
            
            $('.payroll_checkbox_child').each(function(){
                if($(this).is(':checked')){
                    payroll_id.push($(this).val());
                }
            });
            var data = "action=confirmedPayroll&payroll_array="+payroll_id;
            $.ajax({ 
                type: 'POST',
                url: 'applicantpayroll.php',
                cache: false,
                data:data,
                error: function(xhr) {
                    alert("System Error.");
                    issend = false;
                },
                success: function(data) {
                   issend = false;
                   var jsonObj = eval ("(" + data + ")");
                   alert('Update success.');
                   location.reload();
                }		
             });
        });
      });
    </script>
  </body>
</html>
    <?php
    }    
    public function getAddItemDetailForm(){
    $line = 0;

    ?>
    <table id="detail_table" class="table transaction-detail">
        <thead>
          <tr>
            <th class = "" style="width:30px;padding-left:5px">No</th>
            <th class = ""  style = 'width:30px;'>Seq No</th>
            <th class = "" style = 'width:150px;'>Part No</th>
            <th class = "" style = 'width:350px;'>Description</th>
            <th class = "" style = 'width:60px;'>Qty</th>
            <th class = "" style = 'width:80px;'>UOM</th>
            <th class = "" style = 'width:80px;'>U.Price(Foreign)</th>
            <th class = "" style = 'width:80px;'>U.Price(<span class = 'base_currency_span'><?php echo $this->invoice_currency_code;?></span>)</th>
            <th class = "" style = 'width:100px;'>Selling Price(<span class = 'base_currency_span'><?php echo $this->invoice_currency_code;?></span>)</th>
            <th class = "" style = 'width:60px;'>Disc %</th>
            <th class = "" style="width:20px;">Tax</th>
            <th class = "" style = 'width:80px;'>Tax Amt</th>
            <th class = "" style = 'width:80px;'>Sub Total(<span class = 'base_currency_span'><?php echo $this->invoice_currency_code;?></span>)</th>
            <!--<th class = "" style=""></th>-->
          </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM db_invl WHERE invl_id > 0 AND invl_invoice_id > 0 AND invl_invoice_id = '$this->invoice_id' ORDER BY invl_seqno";
            $query = mysql_query($sql);
            while($row = mysql_fetch_array($query)){
                $line++;
                $this->uomCrtl = $this->select->getUomSelectCtrl($row['invl_uom'],'N');
                //User Request cannot add / update on invoice
                $readonly = " READONLY";
                $disabled = " DISABLED";
            ?>
                <tr id = "line_<?php echo $line;?>" class="tbl_grid_odd" line = "<?php echo $line;?>">
                    <td style="width:30px;padding-left:5px"><?php echo $line;?></td>
                    <td style="width:60px;"><input type = "text" id = "invl_seqno_<?php echo $line;?>" class="form-control" value="<?php echo $row['invl_seqno'];?>" <?php echo $readonly;?>/></td>
                    <td style="width:120px;"><select style = 'width:100%' id = "invl_pro_id_<?php echo $line;?>" class="form-control invt_autocomplete " <?php echo $disabled;?>><option value = '<?php echo $row['invl_pro_id'];?>'><?php echo $row['invl_pro_no'];?></option></select></td>
                    <td style="width:200px;" ><textarea id = "invl_pro_desc_<?php echo $line;?>" class="form-control" <?php echo $readonly;?>><?php echo $row['invl_pro_desc'];?></textarea></td>
                    <td style="width:60px;"><input type = "text" id = "invl_qty_<?php echo $line;?>" class="form-control calculate" value="<?php echo $row['invl_qty'];?>" <?php echo $readonly;?>/></td>
                    <td style="width:80px;"><select style = 'width:100%' id = "invl_uom_<?php echo $line;?>" class="form-control select2" <?php echo $disabled;?>><?php echo $this->uomCrtl;?></select></td>
                    <td style="width:60px;"><input type = "text" id = "invl_fuprice_<?php echo $line;?>" class="form-control calculate text-align-right" value = "<?php echo num_format($row['invl_fuprice']);?>" <?php echo $readonly;?>/></td>
                    <td style="width:60px;"><input type = "text" id = "invl_uprice_<?php echo $line;?>" class="form-control calculate text-align-right" value = "<?php echo num_format($row['invl_uprice']);?>" <?php echo $readonly;?>/></td>
                    <td style="width:100px;"><input type = "text" id = "invl_markup_<?php echo $line;?>" class="form-control calculate text-align-right" value = "<?php echo num_format($row['invl_markup']);?>" <?php echo $readonly;?>/><input type = "checkbox" id = "invl_enable_<?php echo $line;?>" <?php if($row['invl_enable'] == 1){ echo 'CHECKED';}?> value = "1" disabled/>Enable</td>
                    <td style="width:60px;"><input type = "text" id = "invl_disc_<?php echo $line;?>" class="form-control calculate text-align-right" value = "<?php echo $row['invl_disc'];?>" <?php echo $readonly;?>/></td>
                    <td style="width:20px;"><input type = "checkbox" id = "invl_istax_<?php echo $line;?>" class = "minimal isincludetax" <?php if($row['invl_istax'] == 1){ echo 'CHECKED';}?> <?php echo $disabled;?>/></td>
                    <td style = "width:80px;"><input type = "text" id = "invl_taxamt_<?php echo $line;?>" class="form-control text-align-right" readonly value = "<?php echo num_format($row['invl_taxamt']);?>"/></td>
                    <td style = "width:100px;"><input type = "text" id = "invl_total_<?php echo $line;?>" class="form-control text-align-right" readonly value = "<?php echo num_format($row['invl_total']);?>"/></td>
                    <!--<td align = "center" style ="vertical-align:top;padding-right:10px;padding-left:5px">-->
                        <?php if($row['invl_id'] > 0){?>
                        <!--<img id = "save_line_<?php echo $line;?>" invl_id = "<?php echo $row['invl_id'];?>" class = "save_line" line = "<?php echo $line;?>" src = "dist/img/update.png" style = "cursor:pointer" alt = "Update"/>-->
                        <?php }else{?>
                        <!--<img id = "save_line_<?php echo $line;?>" invl_id = "<?php echo $row['invl_id'];?>" class = "save_line" line = "<?php echo $line;?>" src = "dist/img/add.png" style = "cursor:pointer" alt = "Add New"/>-->
                        <?php }?>
                        <!--<img id = "delete_line_<?php echo $line;?>" invl_id = "<?php echo $row['invl_id'];?>" class = "delete_line" line = "<?php echo $line;?>" src = "dist/img/delete_icon.png" style = "cursor:pointer" alt = "Delete"/>-->
                    <!--</td>-->
                </tr>

            <?php
            }
            ?>
            <tr id = 'detail_last_tr'></tr>
        </tbody>
    </table>
    <input type = 'hidden' id = 'total_line' name = 'total_line' value = '<?php echo $line;?>'/>
    <?php
    }
    public function getInvoiceGenerateTabTable(){

      if($this->document_type == 'QT'){
          $document_type = 'Sales Invoice';
          $generate_to = 'SO';
          $partner_field = 'Customer';
          $menu_id = '12';// sales invoice menu id is 12
          $document_url = 'invoice.php';
      }else if($this->document_type == 'SO'){
          $document_type = 'Delivery Invoice';
          $generate_to = 'DO';
          $partner_field = 'Customer';
          $menu_id = '13';// delivery invoice menu id is 13
          $document_url = 'delivery_invoice.php';
      }
    ?>
    <div class="box">
        <div class="box-header">
          <div class = "pull-left"><h3 class="box-title"><?php echo $document_type;?> Table</h3></div>
          <div class = "pull-right">
            <?php
            if(getWindowPermission($menu_id,'generate')){
            ?>
               <button type = 'button' class = "btn btn-primary generate_btn" generateto = "<?php echo $generate_to;?>">Generate <?php echo $document_type;?></button>
            <?php }?>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="partner_table" class="table table-binvoiceed table-hover">
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
              $sql = "SELECT o.*,partner.partner_name,empl.empl_name
                      FROM db_invoice o
                      INNER JOIN db_partner partner ON partner.partner_id = o.invoice_customer
                      LEFT JOIN db_empl empl ON empl.empl_id = o.invoice_salesperson
                      WHERE o.invoice_generate_from = '$this->invoice_id' AND o.invoice_status = '1'";
              $query = mysql_query($sql);
              $i = 1;
              while($row = mysql_fetch_array($query)){
            ?>
                <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $row['invoice_no'];?></td>
                    <td><?php echo $row['invoice_date'];?></td>
                    <td><?php echo $row['partner_name'];?></td>
                    <td><?php echo $row['empl_name'];?></td>
                    <td><?php echo $this->getSubTotalAmt() - $this->getTotalDiscAmt();?></td>
                    <td><?php echo $this->getTotalGstAmt();?></td>
                    <td><?php echo num_format(($this->getSubTotalAmt() - $this->getTotalDiscAmt()) + $this->getTotalGstAmt());?></td>
                    <td class = "text-align-right">
                        <?php
                        if(getWindowPermission($_SESSION['m'][$_SESSION['partner_id']],'update')){
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
    public function generateDocument(){
      include_once 'class/Order.php';
      $order = new Order();
      $query = $order->fetchOrderDetail(" AND order_id = '$this->order_id'","","",0);
      if($query){
            $this->invoice_generate_from = $this->order_id;
            $this->document_type = $this->generate_document_type;
            if($this->generate_document_type == 'SI'){
                $this->document_code = "Tax Invoice";
            }
            $r = mysql_fetch_array($query);
            if($this->generateInvoice($r)){
                $this->newinvoice_id = $this->invoice_id;
                $order->order_id = $this->order_id;
                $query = $order->fetchOrderLineDetail("","","",0);
                $this->invoice_disctotal = $order->getTotalDiscAmt();
                $this->invoice_subtotal = $order->getSubTotalAmt();
                $this->invoice_taxtotal = $order->getTotalGstAmt();

                $this->invoice_id = $this->newinvoice_id;
                $this->updateInvoiceTotal();
                while($row = mysql_fetch_array($query)){
                    $this->generateInvoiceLine($row,$this->newinvoice_id);
                }
                $this->new_url = "invoice.php";
                return true;
            }else{
                return false;
            }
      }else{
          return false;
      }
    }
    public function generateInvoice($r){

//        if($_SESSION['empl_outlet'] == 13){
             $invoice_no = get_prefix_value('Invoice',true);
//        }else{
//            $invoice_no = $this->document_type . substr($r['order_no'],2);
//        }
        //$receipt_no =  get_prefix_value("Receipt",true,system_date);
        $table_field = array('invoice_no','invoice_date','invoice_customer','invoice_salesperson',
                             'invoice_billaddress','invoice_attentionto','invoice_shipterm','invoice_term',
                             'invoice_shipaddress','invoice_customerref','invoice_remark','invoice_customerpo',
                             'invoice_currency','invoice_currencyrate','invoice_status','invoice_prefix_type',
                             'invoice_generate_from','invoice_outlet','invoice_attentionto_phone',
                             'invoice_fax','invoice_paymentterm_remark',
                             'invoice_paymentterm','invoice_remark_selection','invoice_attnremark_selection',
                             'invoice_tax_no','invoice_branch_no','invoice_attentionto_name',
                             'invoice_shipment','invoice_shipment_remark','invoice_shipment_amount','invoice_receipt_no',
                             'invoice_machine_id');
        $table_value = array($invoice_no,system_date,$r['order_customer'],$r['order_salesperson'],
                             $r['order_billaddress'],$r['order_attentionto'],$r['order_shipterm'],$r['order_term'],
                             $r['order_shipaddress'],$r['order_customerref'],$r['order_remark'],$r['order_customerpo'],
                             $r['order_currency'],$r['order_currency'],1,$this->document_type,
                             $r['order_id'],$_SESSION['empl_outlet'],$r['order_attentionto_phone'],
                             $r['order_fax'],$r['order_paymentterm_remark'],
                             $r['order_paymentterm'],$r['order_remark_selection'],$r['order_attnremark_selection'],
                             $r['order_tax_no'],$r['order_branch_no'],$r['order_attentionto_name'],
                             $r['order_shipment'],$r['order_shipment_remark'],$r['order_shipment_amount'],"",
                             $r['order_machine_id']);
        $remark = "Insert $this->document_code.<br> Document No : $invoice_no";
        if(!$this->save->SaveData($table_field,$table_value,'db_invoice','invoice_id',$remark)){
           return false;
        }else{
           $this->invoice_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function generateInvoiceLine($r,$invoice_id){
        $table_field = array('invl_invoice_id','invl_pro_id','invl_pro_desc','invl_qty','invl_uom',
                             'invl_uprice','invl_disc','invl_istax','invl_taxamt','invl_total',
                             'invl_pro_no','invl_discamt','invl_seqno','invl_parent',
                             'invl_markup','invl_fuprice','invl_ftotal',
                             'invl_fdiscamt','invl_ftaxamt','invl_enable');
        $table_value = array($invoice_id,$r['ordl_pro_id'],$r['ordl_pro_desc'],$r['ordl_qty'],$r['ordl_uom'],
                             $r['ordl_uprice'],$r['ordl_disc'],$r['ordl_istax'],$r['ordl_taxamt'],$r['ordl_total'],
                             $r['ordl_pro_no'],$r['ordl_discamt'],$r['ordl_seqno'],$r['ordl_id'],
                             $r['ordl_markup'],$r['ordl_fuprice'],$r['ordl_ftotal'],
                             $r['ordl_fdiscamt'],$r['ordl_ftaxamt'],$r['ordl_enable']);
        $this->fetchInvoiceDetail(" AND invoice_id = '$invoice_id'","","",1);
        $remark = "Insert $this->document_code Line.<br> Document No : $this->invoice_no";
        if(!$this->save->SaveData($table_field,$table_value,'db_invl','invl_id',$remark)){
           return false;
        }else{
           $this->invl_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function addCountPrint(){

           $sql = "UPDATE db_invoice SET invoice_countprint = invoice_countprint+1,
                   updateDateTime = now(),updateBy='{$_SESSION['empl_id']}' WHERE invoice_id = '$this->invoice_id'";
           mysql_query($sql);
    }
    public function getApplicantList(){
        $this->client_id = $_REQUEST['client_id'];
        $this->job_type = $this->getJobType($_REQUEST['invoice_type']);
        $invoice_date = $_REQUEST['invoice_date'];
        $this->firstDay = date('Y-m-01', strtotime($invoice_date));
        $this->lastDay = date("Y-m-t", strtotime($invoice_date));
        $this->invoice_gst = $_REQUEST['invoice_gst'];
        
        $startDate = substr(format_date_database($invoice_date),0,7);
        
        $sql = "SELECT a.*, t.timeshift_department, f.* FROM db_applicant a INNER JOIN db_followup f ON a.applicant_id = f.applfollow_id INNER JOIN db_jobs j ON f.fol_job_assign = j.job_id INNER JOIN db_timeshift t ON t.timeshift_id = f.fol_department WHERE f.follow_type = '0' AND f.interview_company = '$this->client_id' AND f.fol_job_type = '$this->job_type' AND f.fol_available_date <= '$this->lastDay' AND f.fol_status = '0' AND LEFT(f.fol_assign_expiry_date,7) >= '$startDate' AND f.fol_approved = 'Y'";
 
        $data = array();
        $i =0;
        $query = mysql_query($sql);
            if (mysql_num_rows($query) == "0"){
                return "0";
            }
        while($row = mysql_fetch_array($query)){

            $data[$i]['invoice_startdate'] = format_date($this->firstDay);
            $data[$i]['invoice_lastdate'] = format_date($this->lastDay);
            $data[$i]['applicant_id']= $row['applicant_id'];
            $data[$i]['applicant_name'] = $row['applicant_name'];
            $data[$i]['applicant_nric'] = $row['applicant_nric'];
            $data[$i]['applicant_department'] = $row['timeshift_department'];
            $data[$i]['applicant_position'] = $row['fol_position_offer'];
            $data[$i]['applicant_admin_fee'] = $row['fol_admin_fee'];
            
            $sql2 = "SELECT pr.*, pl.* FROM db_payroll pr INNER JOIN db_payline pl ON pr.payroll_id = pl.payline_payroll_id WHERE pr.payroll_client = '$this->client_id' AND pl.payline_empl_id = '$row[applicant_id]' AND pl.payline_empl_type = '1' AND pr.payroll_startdate = '$this->firstDay' AND pr.payroll_enddate = '$this->lastDay'";
            $query2 = mysql_query($sql2);
            $row2 = mysql_fetch_array($query2);
            
            if (mysql_num_rows($query2) == "0"){
                return "1";
            }
            if($row2 == null){
                $data[$i]['null'] = "YES";
            }
            
            $data[$i]['applicant_salary'] = number_format($row2['payline_salary'],2);
            $data[$i]['applicant_additional'] = number_format($row2['payline_additional'],2);
            $data[$i]['applicant_deduction'] = number_format($row2['payline_deductions'],2);
            $data[$i]['applicant_cpf_employer'] = number_format($row2['payline_cpf_employer'],2);
            $data[$i]['applicant_cpf_employee'] = number_format($row2['payline_cpf_employee'],2);
            $data[$i]['applicant_levy_employee'] = number_format($row2['payline_levy_employee'],2);
            $data[$i]['applicant_netpay'] = number_format($row2['payline_netpay'],2);
            $data[$i]['invoice_amount'] = number_format($row2['payline_netpay'] + $row['fol_admin_fee'],2);
            $data[$i]['invoice_gst'] = number_format(($row2['payline_netpay'] + $row['fol_admin_fee']) * ($this->invoice_gst/100),2);
            
            $data[0]['total_netpay'] = $data[0]['total_netpay'] + $row2['payline_netpay'];
            $data[0]['total_admin_fee'] = $data[0]['total_admin_fee'] + $row['fol_admin_fee'];
            $data[0]['total_invoice_gst'] = $data[0]['total_invoice_gst'] + $data[$i]['invoice_gst'];
            $data[0]['total_invoice_amount'] = $data[0]['total_invoice_amount'] + $row2['payline_netpay'] + $row['fol_admin_fee'] + $data[$i]['invoice_gst'];
            
            $data[0]['t_invoice_amount'] = $data[0]['total_invoice_amount'];

            $sql3 = "SELECT * FROM db_attendance WHERE attendance_empl = '$row[applicant_id]' AND attendance_date_start BETWEEN '$this->firstDay' AND '$this->lastDay'";
            $query3 = mysql_query($sql3);
            $row3 = mysql_num_rows($query3);
            $data[$i]['applicant_work_day'] = $row3;
            
            $i++;
        }
        $data[0]['total_netpay'] = number_format($data[0]['total_netpay'], 2);
        $data[0]['total_admin_fee'] = number_format($data[0]['total_admin_fee'], 2);
        $data[0]['total_invoice_gst'] = number_format($data[0]['total_invoice_gst'], 2);
        $data[0]['total_invoice_amount'] = number_format($data[0]['total_invoice_amount'], 2);
        return $data;
    }
    public function getJobType($invoice_type){
        if ($invoice_type == "DR"){
            return "PD";
        }
        else if($invoice_type == "HR"){
            return "PH";
        }
        else if($invoice_type == "P"){
            return "F";
        }
        else if($invoice_type == "C"){
            return "C";
        }
        else{
            return "";
        }
    }
    public function updateInvoiceNo($invoice_id){
        $invoice_no = "";
        $sql = "SELECT LPAD(invoice_id,7,'0') as invoice_num, invoice_type FROM db_invoice WHERE invoice_id = '$invoice_id'";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);
        if ($row['invoice_type'] == "C"){
            $invoice_no = "T".$row['invoice_num'];
        }
        else if ($row['invoice_type'] == "DR" || $row['invoice'] == "HR" || $row['invoice_type'] == "P"){
            $invoice_no = "P".$row['invoice_num'];
        }
        $table_field = array('invoice_no');
        $table_value = array($invoice_no);
        
        if(!$this->save->UpdateData($table_field,$table_value,'db_invoice','invoice_id',$remark,$this->invoice_id)){
           return false;
        }else{
           return true;
        }
    }
    public function printReceiptModal1(){

        
    $this->invoice_id = escape($_REQUEST['invoice_id']);
    $this->invoice_date = escape($_REQUEST['date']);
    $this->job_type = $this->getJobType(escape($_REQUEST['job_type']));
    $this->client_id = escape($_REQUEST['client_id']);
    $this->invoice_gst = escape($_REQUEST['invoice_gst']);
        
        $firstDay = date('Y-m-01', strtotime($this->invoice_date));
        $lastDay = date("Y-m-t", strtotime($this->invoice_date));
        
        $startDate = substr(format_date_database($this->invoice_date),0,7);
        
        $pdf = new FPDI();

        $pdf->AddPage();    
       
        $pdf->SetFont('Times', '', 14);
       
        $pdf->Cell(40, 5, "");
        $pdf->Cell(110, 6, "SUCCESS HUMAN RESOURCE CENTRE PTE LTD",0, 1, C);     
        
        $pdf->SetFont('Times', '', 11);
        $pdf->Cell(40, 5, "");
        $pdf->Cell(110, 6, "1 Sophia Road #06-23/29 Peace Centre Singapore 228149",0, 1, C);
        $pdf->Cell(40, 5, "");
        $pdf->Cell(110, 6, "Tel: 63373183        Fax: 63370329 / 63370425",0, 1, C);

        $pdf->SetY(30);
        
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(145, 5, "");
        $pdf->Cell(110, 6, "TAX INVOICE");
        
        $sql = "SELECT i.*, p.* FROM db_invoice i INNER JOIN db_partner p ON i.invoice_client = p.partner_id WHERE i.invoice_id = '$this->invoice_id'";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);

        $pdf->SetY(40);
        $pdf->Cell(15, 5, "");
        $pdf->Cell(100, 6, $row['partner_name']);    
        
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(22, 6, "INVOICE NO ");  
        $pdf->Cell(8, 6, " : "); 
        $pdf->Cell(35, 6, $row['invoice_no'], 0, 1);  
        
        $pdf->SetFont('Times', 'B', 10);  
        $pdf->Cell(15, 5, "");
        
        $pdf->Cell(100, 5, $row['invoice_unit_no'],0,1);
        
        $address = explode(" ",$row['invoice_address']); 
        $line = ROUND(count($address)/4);
        $l = 0;
        for($i = 0; $i <= $line+1; $i++){
            $pdf->Cell(15, 5, "");
                $pdf->Cell(100, 4, $address[$l]. " " .$address[$l+1]. " " .$address[$l+2]. " " .$address[$l+3] ,0,1);
                $l = $l + 4;
        }

        $pdf->SetXY(125, 50);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(22, 6, "DATE ");  
        $pdf->Cell(8, 6, " : "); 
        $pdf->Cell(35, 6, format_date($row['invoice_date']), 0, 1); 
        
        $pdf->SetXY(125, 60);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(22, 6, "TERMS ");  
        $pdf->Cell(8, 6, " : "); 
        
        $pdf->SetFont('Times', 'B', 10);  
        $pdf->Cell(35, 6, $row['invoice_terms'], 0, 1);         
        
        $pdf->SetXY(125, 70);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(22, 6, "GST REG NO ");  
        $pdf->Cell(8, 6, " : "); 
        $pdf->Cell(35, 6, $row['invoice_gst_reg_no'], 0, 1); 
        
        $pdf->SetY(70);

        $pdf->Cell(15, 4, "");
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(10, 4, "Attn : ");  
        
        $att = nl2br($row['invoice_attention_person']);
        while(1){
            $pos = strpos($att, "<br />");
            if($pos != ""){
                $name = substr($att, 0, $pos);

                $count1 = strlen($att);
                $count2 = strlen($name);

                $pdf->Cell(50, 4, $name,0,1);
                $pdf->Cell(25, 4, "");
                $att = substr($att, $pos+8, $count1-$count2);
            }
            else
            {
                break;
            }
        }
        $pdf->Cell(50, 4, $att,0,1);
        
        
        $pdf->SetY(90);
        $pdf->Cell(15, 5, "");
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(130, 6, "DESCRIPTION", 1, 0, C); 
        $pdf->Cell(30, 6, "AMOUNT", 1, 1, C); 
  
        $pdf->Cell(15, 5, "");
        
        $remark = nl2br($row['invoice_remark']);
        while(1){
            $pos = strpos($remark, "<br />");
            if($pos != ""){
                $line = substr($remark, 0, $pos);

                $count1 = strlen($remark);
                $count2 = strlen($line);

                $pdf->Cell(50, 6, $line,0,1);
                $pdf->Cell(15, 5, "");
                $remark = substr($remark, $pos+8, $count1-$count2);
            }
            else
            {
                break;
            }
        }
        $pdf->Cell(50, 6, $remark,0,1);        
       
        $pdf->SetFont('Times', '', 10);
        $sql = "SELECT a.*, t.*, f.* FROM db_applicant a INNER JOIN db_followup f ON a.applicant_id = f.applfollow_id INNER JOIN db_jobs j ON f.fol_job_assign = j.job_id INNER JOIN db_timeshift t ON t.timeshift_id = f.fol_department WHERE f.follow_type = '0' AND f.interview_company = '$this->client_id' AND f.fol_job_type = '$this->job_type' AND f.fol_available_date <= '$lastDay' AND f.fol_status = '0' AND LEFT(f.fol_assign_expiry_date,7) >= '$startDate' AND f.fol_approved = 'Y'";
        $query = mysql_query($sql);

        while($row = mysql_fetch_array($query)){
            $pdf->Cell(15, 5, "");
            $pdf->Cell(40, 6, "Name of Candidate");
            $pdf->Cell(8, 6, " : ");
            $pdf->Cell(40, 6, $row['applicant_name'], 0, 1);
            
            
            $pdf->Cell(15, 5, "");
            $pdf->Cell(40, 6, "Position"); 
            $pdf->Cell(8, 6, " : ");
            $pdf->Cell(40, 6, $row['fol_position_offer'], 0, 1); 
            
            $pdf->Cell(15, 5, "");
            $pdf->Cell(40, 6, "Commencing date"); 
            $pdf->Cell(8, 6, " : ");
            $pdf->Cell(40, 6, format_date($this->invoice_date), 0, 1);             
         
            $sql2 = "SELECT pr.*, pl.* FROM db_payroll pr INNER JOIN db_payline pl ON pr.payroll_id = pl.payline_payroll_id WHERE pr.payroll_client = '$this->client_id' AND pl.payline_empl_id = '$row[applicant_id]' AND pl.payline_empl_type = '1' AND pr.payroll_startdate = '$firstDay' AND pr.payroll_enddate = '$lastDay'";
            $query2 = mysql_query($sql2);
            $row2 = mysql_fetch_array($query2);
            $gst = ($row2['payline_netpay'] + $row['fol_admin_fee']) * ($this->invoice_gst/100);
            $sub_total = $row2['payline_netpay'] + $row['fol_admin_fee'] + $gst;
            $grand_total = $grand_total + $sub_total;  
            
            $pdf->Cell(15, 5, ""); 
            $pdf->Cell(40, 6, "Invoice Amount");
            $pdf->Cell(8, 6, " : ");
            $pdf->Cell(88, 6, "Salary ($".$row2['payline_netpay'].") + Admin Fee ($" . $row['fol_admin_fee'] .")");   
            $pdf->Cell(40, 6, "$ ".number_format($row2['payline_netpay'] + $row['fol_admin_fee'],2), 0, 1);   
            
            $pdf->Cell(55, 5, ""); 
            $pdf->Cell(8, 6, " : ");
            $pdf->Cell(88, 6, "GST (" . $this->invoice_gst. " % )");   
            $pdf->Cell(40, 6, "$ ".number_format(ROUND($gst,2),2), 0, 1);  
            
            $pdf->Cell(15, 5, ""); 
            $pdf->Cell(40, 6, "Sub Total");
            $pdf->Cell(8, 6, " : ");
            $pdf->Cell(88, 6, "");   
            $pdf->Cell(18, 6, "$ ".number_format($sub_total,2), T, 1);
            
            $pdf->Cell(40, 6, "", 0, 1);
        }
        
        $ntw = new NumberToWord();
        
        $grand_total = ROUND($grand_total,2);

        $pos = strpos($grand_total, ".");
        if($pos != ""){
            $dollar = substr($grand_total, 0, $pos);

            $count1 = strlen($grand_total); 
            $count2 = strlen($dollar); 

            $decimal = substr($grand_total, $pos+1, $count1-$count2);

            $dollar = $ntw->convert_number_to_words($dollar)." DOLLARS ";
            $decimal = $ntw->convert_number_to_words($decimal). " CENTS ONLY";       
        }
        else{
            $dollar = $ntw->convert_number_to_words($grand_total)." DOLLARS ONLY";
            $decimal = "";
        }
        
        $pdf->Cell(40, 6, "", 0, 1);
        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(40, 6, "SINGAPORE DOLLARS");
        $pdf->Cell(8, 6, " : ");
        $pdf->Cell(88, 6, "");   
        $pdf->Cell(18, 6, "", 0, 1);

        
        $pdf->SetFont('Times', 'B', 8);
        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(40, 6, strtoupper($dollar), 0, 1);   
        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(40, 6, strtoupper($decimal), 0, 1); 
        
        $pdf->Cell(40, 6, "", 0, 1);  
        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(160, 6, "", B, 1);
        
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(40, 6, "Payment should be made by crossed cheque payable to :", 0, 1);     
        
        $pdf->Cell(15, 5, "");
        $pdf->Cell(95, 6, "Success Resource Centre Pte Ltd");  
        $pdf->Cell(30, 6, "GRAND TOTAL : ");  
        $pdf->Cell(9, 6, "");   
        $pdf->Cell(18, 6, "$ ".number_format(ROUND($grand_total,2),2), T, 1);
        
        $pdf->Cell(15, 5, "");
        $pdf->Cell(95, 6, "");  
        $pdf->Cell(30, 6, "");  
        $pdf->Cell(9, 6, "");   
        $pdf->Cell(18, 6, "", T, 1);
        
        $pdf->Cell(15, 5, "",0,1);
        $pdf->Cell(15, 5, "",0,1);
        $pdf->Cell(15, 5, "",0,1);
        $pdf->Cell(15, 5, "",0,1);
        

        $pdf->Cell(15, 5, "");
        $pdf->Cell(70, 6, "for Success Resource Centre Pte Ltd", T,1, C);  
        
        $pdf->Output();
    }       
    public function printReceiptModal2(){

        
    $this->invoice_id = escape($_REQUEST['invoice_id']);
    $this->invoice_date = escape($_REQUEST['date']);
    $this->job_type = $this->getJobType(escape($_REQUEST['job_type']));
    $this->client_id = escape($_REQUEST['client_id']);
    $this->invoice_gst = escape($_REQUEST['invoice_gst']);
    
        $firstDay = date('Y-m-01', strtotime($this->invoice_date));
        $lastDay = date("Y-m-t", strtotime($this->invoice_date));
        
        $startDate = substr(format_date_database($this->invoice_date),0,7);
        
        $pdf = new FPDI();

        $pdf->AddPage();    
       
        $pdf->SetFont('Times', '', 14);
       
        $pdf->Cell(40, 5, "");
        $pdf->Cell(110, 6, "SUCCESS HUMAN RESOURCE CENTRE PTE LTD",0, 1, C);     
        
        $pdf->SetFont('Times', '', 11);
        $pdf->Cell(40, 5, "");
        $pdf->Cell(110, 6, "1 Sophia Road #06-23/29 Peace Centre Singapore 228149",0, 1, C);
        $pdf->Cell(40, 5, "");
        $pdf->Cell(110, 6, "Tel: 63373183        Fax: 63370329 / 63370425",0, 1, C);

        $pdf->SetY(30);
        
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(145, 5, "");
        $pdf->Cell(110, 6, "TAX INVOICE");
        
        $sql = "SELECT i.*, p.* FROM db_invoice i INNER JOIN db_partner p ON i.invoice_client = p.partner_id WHERE i.invoice_id = '$this->invoice_id'";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);

        $pdf->SetY(40);
        $pdf->Cell(15, 5, "");
        $pdf->Cell(100, 6, $row['partner_name']);    
        
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(22, 6, "INVOICE NO ");  
        $pdf->Cell(8, 6, " : "); 
        $pdf->Cell(35, 6, $row['invoice_no'], 0, 1);  
        
        $pdf->SetFont('Times', 'B', 10);  
        $pdf->Cell(15, 5, "");
        
        $pdf->Cell(100, 5, $row['invoice_unit_no'],0,1);
        
        $address = explode(" ",$row['invoice_address']); 
        $line = ROUND(count($address)/4);
        $l = 0;
        for($i = 0; $i <= $line+1; $i++){
            $pdf->Cell(15, 5, "");
                $pdf->Cell(100, 5, $address[$l]. " " .$address[$l+1]. " " .$address[$l+2]. " " .$address[$l+3] ,0,1);
                $l = $l + 4;
        }

        $pdf->SetXY(125, 50);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(22, 6, "DATE ");  
        $pdf->Cell(8, 6, " : "); 
        $pdf->Cell(35, 6, format_date($row['invoice_date']), 0, 1); 
        
        $pdf->SetXY(125, 60);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(22, 6, "TERMS ");  
        $pdf->Cell(8, 6, " : "); 
        
        $pdf->SetFont('Times', 'B', 10);  
        $pdf->Cell(35, 6, $row['invoice_terms'], 0, 1);         
        
        $pdf->SetXY(125, 70);
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(22, 6, "GST REG NO ");  
        $pdf->Cell(8, 6, " : "); 
        $pdf->Cell(35, 6, $row['invoice_gst_reg_no'], 0, 1); 
        
        $pdf->SetY(70);

        $pdf->Cell(15, 4, "");
        $pdf->SetFont('Times', '', 10);
        $pdf->Cell(10, 4, "Attn : ");  
        
        $att = nl2br($row['invoice_attention_person']);
        while(1){
            $pos = strpos($att, "<br />");
            if($pos != ""){
                $name = substr($att, 0, $pos);

                $count1 = strlen($att);
                $count2 = strlen($name);

                $pdf->Cell(50, 4, $name,0,1);
                $pdf->Cell(25, 4, "");
                $att = substr($att, $pos+8, $count1-$count2);
            }
            else
            {
                break;
            }
        }
        $pdf->Cell(50, 4, $att,0,1);
        
        $pdf->SetY(90);
        $pdf->Cell(15, 5, "");
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(130, 6, "DESCRIPTION", 1, 0, C); 
        $pdf->Cell(30, 6, "AMOUNT", 1, 1, C); 
 
        $pdf->Cell(15, 5, "");
        
        $remark = nl2br($row['invoice_remark']);
        while(1){
            $pos = strpos($remark, "<br />");
            if($pos != ""){
                $line = substr($remark, 0, $pos);

                $count1 = strlen($remark);
                $count2 = strlen($line);

                $pdf->Cell(50, 6, $line,0,1);
                $pdf->Cell(15, 5, "");
                $remark = substr($remark, $pos+8, $count1-$count2);
            }
            else
            {
                break;
            }
        }
        $pdf->Cell(50, 6, $remark,0,1);        
        
        $pdf->SetFont('Times', '', 10);
        $sql = "SELECT a.*, t.*, f.* FROM db_applicant a INNER JOIN db_followup f ON a.applicant_id = f.applfollow_id INNER JOIN db_jobs j ON f.fol_job_assign = j.job_id INNER JOIN db_timeshift t ON t.timeshift_id = f.fol_department WHERE f.follow_type = '0' AND f.interview_company = '$this->client_id' AND f.fol_job_type = '$this->job_type' AND f.fol_available_date <= '$lastDay' AND f.fol_status = '0' AND LEFT(f.fol_assign_expiry_date,7) >= '$startDate' AND f.fol_approved = 'Y'";
        $query = mysql_query($sql);

        while($row = mysql_fetch_array($query)){           
         
            $sql2 = "SELECT pr.*, pl.* FROM db_payroll pr INNER JOIN db_payline pl ON pr.payroll_id = pl.payline_payroll_id WHERE pr.payroll_client = '$this->client_id' AND pl.payline_empl_id = '$row[applicant_id]' AND pl.payline_empl_type = '1' AND pr.payroll_startdate = '$firstDay' AND pr.payroll_enddate = '$lastDay'";
            $query2 = mysql_query($sql2);
            $row2 = mysql_fetch_array($query2);
            $gst = ($row2['payline_netpay'] + $row['fol_admin_fee']) * ($this->invoice_gst/100);
            $sub_total = $row2['payline_netpay'] + $row['fol_admin_fee'] + $gst;
            $grand_amount = $grand_amount + $row2['payline_netpay'] + $row['fol_admin_fee'];
            $total_gst = $total_gst + $gst;
            $grand_total = $grand_total + $sub_total; 
            
        }
        
        $pdf->Cell(15, 5, "");
        $pdf->Cell(40, 6, "Please refer to the attached summary");
        $pdf->Cell(8, 6, "");
        $pdf->Cell(40, 6, "", 0, 1); 

        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(40, 6, "Gross Amount");
        $pdf->Cell(8, 6, " : ");
        $pdf->Cell(88, 6, "");   
        $pdf->Cell(18, 6, "$ ".number_format(ROUND($grand_amount,2),2), 0, 1);
        
        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(40, 6, "GST (".$this->invoice_gst." %)");
        $pdf->Cell(8, 6, " : ");
        $pdf->Cell(88, 6, "");   
        $pdf->Cell(18, 6, "$ ". number_format(ROUND($total_gst,2),2), 0, 1);        
        
        $pdf->Cell(40, 6, "", 0, 1); 
        $pdf->Cell(40, 6, "", 0, 1); 
        $pdf->Cell(40, 6, "", 0, 1); 
        $pdf->Cell(40, 6, "", 0, 1); 
        $pdf->Cell(40, 6, "", 0, 1); 
        $pdf->Cell(40, 6, "", 0, 1); 
        $pdf->Cell(40, 6, "", 0, 1); 
        $pdf->Cell(40, 6, "", 0, 1); 
        $pdf->Cell(40, 6, "", 0, 1); 
        $pdf->Cell(40, 6, "", 0, 1); 


        
        
        $ntw = new NumberToWord();
        
        $grand_total = ROUND($grand_total,2);
        $pos = strpos($grand_total, ".");
        $dollar = substr($grand_total, 0, $pos);
        
        $count1 = strlen($grand_total); 
        $count2 = strlen($dollar); 
        
        $decimal = substr($grand_total, $pos+1, $count1-$count2);
        
        $dollar = $ntw->convert_number_to_words($dollar);
        $decimal = $ntw->convert_number_to_words($decimal);       
        
        $pdf->Cell(40, 6, "", 0, 1);
        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(40, 6, "SINGAPORE DOLLARS");
        $pdf->Cell(8, 6, " : ");
        $pdf->Cell(88, 6, "");   
        $pdf->Cell(18, 6, "", 0, 1);

        
        $pdf->SetFont('Times', 'B', 8);
        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(40, 6, strtoupper($dollar)." DOLLARS ", 0, 1);   
        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(40, 6, strtoupper($decimal). " CENTS ONLY", 0, 1); 
        
        $pdf->Cell(40, 6, "", 0, 1);  
        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(160, 6, "", B, 1);
        
        
        $pdf->SetFont('Times', 'B', 10);
        $pdf->Cell(15, 5, ""); 
        $pdf->Cell(40, 6, "Payment should be made by crossed cheque payable to :", 0, 1);     
        
        $pdf->Cell(15, 5, "");
        $pdf->Cell(95, 6, "Success Resource Centre Pte Ltd");  
        $pdf->Cell(30, 6, "GRAND TOTAL : ");  
        $pdf->Cell(9, 6, "");   
        $pdf->Cell(18, 6, "$ ".number_format(ROUND($grand_total,2),2), T, 1);
        
        $pdf->Cell(15, 5, "");
        $pdf->Cell(95, 6, "");  
        $pdf->Cell(30, 6, "");  
        $pdf->Cell(9, 6, "");   
        $pdf->Cell(18, 6, "", T, 1);
        
        $pdf->Cell(15, 5, "",0,1);
        $pdf->Cell(15, 5, "",0,1);
        $pdf->Cell(15, 5, "",0,1);
        $pdf->Cell(15, 5, "",0,1);
        

        $pdf->Cell(15, 5, "");
        $pdf->Cell(70, 6, "for Success Resource Centre Pte Ltd", T,1, C);  
        
        $pdf->Output();
    }      
    
    public function getGST(){
        $outlet_id = $_REQUEST['outlet_id'];
        $sql = "SELECT outlet_gst_no, outlet_gst FROM db_outl WHERE outl_id = '$outlet_id'";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);
        
        $data = array();
        $data['gst_no'] = $row['outlet_gst_no'];
        $data['gst'] = $row['outlet_gst'];
        return $data;
    }
}
?>
