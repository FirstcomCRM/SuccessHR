<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Invoice.php'; 
    include_once 'class/Partner.php';
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    include_once 'language.php';
    include_once 'class/fpdf/fpdf.php';
    include_once 'class/fpdi/fpdi.php'; 
    include_once 'class/NumberToWord.php';
    $o = new Invoice();
    $b = new Partner();
    $s = new SavehandlerApi();
    $o->save = $s;
    $o->document_type = 'SI';
    $o->document_name = 'Tax Invoice Management';
    $o->document_code = 'Invoice';
    $o->document_url = 'invoice.php';
    $o->menu_id = 14;
    $o->isstock = 0;

    $o->invoice_id = escape($_REQUEST['invoice_id']);
    $o->invoice_no = escape($_POST['invoice_no']);
    $o->invoice_type = escape($_POST['invoice_type']);
    $o->invoice_date = escape($_POST['invoice_date']);
    $o->client_name = escape($_POST['client_name']);
    $o->invoice_no = escape($_POST['invoice_no']);
    $o->invoice_terms = escape($_POST['invoice_terms']);
    $o->invoice_gst_reg_no = escape($_POST['invoice_gst_reg_no']);
    $o->invoice_attention_person = escape($_POST['invoice_attention_person']);
    $o->invoice_postal_code = escape($_POST['invoice_postal_code']);
    $o->invoice_unit_no = escape($_POST['invoice_unit_no']);
    $o->invoice_address = escape($_POST['invoice_address']);
    $o->invoice_remark = escape($_POST['invoice_remark']);
    $o->invoice_amount = escape($_POST['invoice_amount']);
    $o->invoice_gst = escape($_POST['invoice_gst']);
    $o->invoice_outlet = escape($_POST['invoice_outlet']);

    $o->invoice_currency = escape($_POST['invoice_currency']);
    $o->invoice_currencyrate = escape($_POST['invoice_currencyrate']);
    if($o->invoice_currencyrate <= 1){
        $o->invoice_currencyrate = "1.0000";
    }
    $o->invoice_status = $_REQUEST['invoice_status'];

    $o->invl_id = escape($_POST['invl_id']);
    $o->invl_invoice_id = escape($_POST['invl_invoice_id']);
    $o->invl_pro_no = escape($_POST['invl_pro_no']);
    $o->invl_pro_id = escape($_POST['invl_pro_id']);
    $o->invl_pro_desc = escape($_POST['invl_pro_desc']);
    $o->invl_uom = escape($_POST['invl_uom']);
    $o->invl_qty = str_replace(",", "",$_POST['invl_qty']);
    $o->invl_uprice = str_replace(",", "",$_POST['invl_uprice']);
    $o->invl_fuprice = str_replace(",", "",$_POST['invl_fuprice']);
    $o->invl_disc = str_replace(",", "",$_POST['invl_disc']);
    $o->invl_taxamt = str_replace(",", "",$_POST['invl_taxamt']);
    $o->invl_istax = str_replace(",", "",$_POST['invl_istax']);
    $o->invl_seqno = escape($_POST['invl_seqno']);
    $o->invl_markup = str_replace(",", "",$_POST['invl_markup']);
    $o->invl_enable = escape($_POST['invl_enable']);
    $o->invoice_attentionto_name = escape($_POST['invoice_attentionto_name']);
    
    $o->generate_document_type = escape($_POST['generate_document_type']);
    $o->order_id = escape($_POST['order_id']);
    $o->report_no = $_REQUEST['report_no'];
    
//    $o->client_id = escape($_POST['client_id']);
//    $o->invoice_date = escape($_POST['invoice_date']);
//    $o->invoice_type = escape($_POST['invoice_type']);
    
    
    if($o->invl_seqno == ""){
        $o->invl_seqno = 10;
    }
    if(!is_numeric($o->invl_uprice)){
        $o->invl_uprice = 0;
    }
    if(!is_numeric($o->invl_qty)){
        $o->invl_qty = 0;
    }
    if(!is_numeric($o->invl_disc)){
        $o->invl_disc = 0;
    }
    if(!is_numeric($o->discount_amount)){
        $o->discount_amount = 0;
    }
    

    

   $action = $_REQUEST['action'];
   switch($action){
       case "create":
                if($o->createInvoice()){
                    rediectUrl("$o->document_url?action=edit&invoice_id=$o->invoice_id&edit=1",getSystemMsg(1,'Create data successfully'));
                }else{
                    rediectUrl("$o->document_url?action=create_form",getSystemMsg(0,'Create data fail'));
                }
       break;
       case "edit":
                if($o->fetchInvoiceDetail(" AND invoice_id = '$o->invoice_id'","","",1)){
                    $o->getInputForm("update");
                }else{
                   rediectUrl("$o->document_url",getSystemMsg(0,'Fetch Data'));
                }
       break;
       case "update":
               $o->status = 0;
               if($o->updateInvoice()){
                   rediectUrl("$o->document_url?action=edit&invoice_id=$o->invoice_id&edit=1",getSystemMsg(1,'Update data successfully'));
               }else{
                   rediectUrl("$o->document_url?action=edit&invoice_id=$o->invoice_id&edit=1",getSystemMsg(0,'Update data fail'));
               }
       break;
       case "delete":
               $o->invoice_status = 0;
               if($o->delete()){
                   rediectUrl("$o->document_url",getSystemMsg(1,'Delete data successfully'));
               }else{
                   rediectUrl("$o->document_url?action=edit&invoice_id=$o->invoice_id",getSystemMsg(0,'Delete data fail'));
               }
       break;
       case "saveline":
       case "updateline":    
            $o->calculateLineAmount();

            if($o->invl_id > 0 && $action == 'updateline'){
                $issuccess = $o->updateInvoiceLine();
            }else{
                $issuccess = $o->createInvoiceLine();
            }
            if($issuccess){
                $o->invoice_disctotal = $o->getTotalDiscAmt();
                $o->invoice_subtotal = $o->getSubTotalAmt();
                $o->invoice_taxtotal = $o->getTotalGstAmt();
                $o->updateInvoiceTotal();
                echo json_encode(array('status'=>1));
            }else{
                echo json_encode(array('status'=>0));
            }
            exit();
            break;
       case "deleteline":
           if($o->deleteInvoiceLine()){
                $o->invoice_disctotal = $o->getTotalDiscAmt();
                $o->invoice_subtotal = $o->getSubTotalAmt();
                $o->invoice_taxtotal = $o->getTotalGstAmt();
               echo json_encode(array('status'=>1));
           }else{
               echo json_encode(array('status'=>0));
           }
           exit();
           break;
       case "generateDocument":
           if($o->generateDocument()){
               echo json_encode(array('status'=>1,'tab'=>'sales_invoice_tab','neworder_id'=>$o->newinvoice_id,'new_url'=>$o->new_url));
           }else{
               echo json_encode(array('status'=>0,'tab'=>0));
           }
           exit();
           break;
       case "getmultipdf":
           $filename = str_replace(" - ",'_',$o->report_no);

            require('dist/fpdf/fpdf.php');
            require('dist/fpdi/fpdi.php');

            $files = ["report/thai/invoice/accounts/SI$filename.pdf","report/thai/invoice/internal/SI$filename.pdf"];
        
            $pdf1 = new FPDI();

            foreach ($files as $file) {
                $pagecount = $pdf1->setSourceFile($file);  
                for($i=0; $i<$pagecount; $i++){
                    $pdf1->AddPage();  
                    $tplidx = $pdf1->importPage($i+1, '/MediaBox');
                    $pdf1->useTemplate($tplidx, 10, 10, 200); 
                } 
            }
            $pdffile = "Filename.pdf";

            $pdf1->Output('I','merged.pdf');
 
           exit();
           break;
       case "addCountPrint":
           $o->addCountPrint();
           echo json_encode(array('status'=>1));
           exit();
           break;
       case "createForm":
            $o->getInputForm('create');
            exit();
            break;  
       case "getApplicantList":
            $applicant_List = $o->getApplicantList();     
            echo json_encode(array('applicant_List'=>$applicant_List));
            exit();
            break; 
       case "getApplicantDetail":
            $o->applicantDetail();
            exit();
            break;  
       case "listing":
            $o->getListing();
            exit();
            break; 
       case "printReceiptM1":
           $o->printReceiptModal1();
           exit();
           break;
       case "printReceiptM2":
           $o->printReceiptModal2();
           exit();
           break;
       case "getGST";
           $gst = $o->getGST();
           echo json_encode(array('gst'=>$gst));
           exit();
           break;
       default: 
            if($_SESSION['empl_group'] > 0){
                $wherestring = " AND o.invoice_outlet = '{$_SESSION['empl_outlet']}'";
            }
            $o->wherestring .= " AND o.invoice_prefix_type = '$o->document_type' $wherestring";

            $o->getListingMonth();
            exit();
            break; 
    }
    
?>
