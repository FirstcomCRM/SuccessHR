<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Report.php'; 
    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';
    $o = new Report();
    $s = new SavehandlerApi();
    $gf = new GeneralFunction();
    $o->save = $s;

    $o->action = escape($_REQUEST['action']);
    $o->menu_id = escape($_POST['menu_id']);
    $o->filter_datefrom = escape($_POST['filter_datefrom']);
    $o->filter_dateto = escape($_POST['filter_dateto']);


    switch ($o->action) {   
        case "engineer_report_export":
            if($_POST['submit'] == 'Export'){
        include("dist/excelwriter.inc.php");
        include("class/Servicesorder.php");
        $c = new Servicesorder();
	$fileName = "dist/mynewxls.xls";
	$excel = new ExcelWriter($fileName);
        if($o->filter_datefrom == ""){
            $o->filter_datefrom = "0000-00-00";
        }
        if($o->filter_dateto == ""){
            $o->filter_dateto = "9999-12-31";
        }
	$myArr=array("Services Order","Services Quotation No.","Engineers Report No.","Customer","Attention To",
                     "Address","Attention Phone","CRM Number","Work-Kind","Machine Number",
                     "Charge Code","Contents Of Work","Spare Parts","Remote","Backup",
                     "Operating Hours (H)","Running Meter (m) / Spindle Runtime (H)","Cause / Action","Soution / Result","Traning",
                     "Malware Protection","Work Piece / Numbers of parts","Machine Status (%)","Engineer Name","Total Hours (Travel Time CPL)",
                     "Date","Travel Time from","Travel Time to","Travel Break","Total Hours (Work Time CPL)",
                     "Work Time From","Work Time To","Work Time Break",
                     "Ret.Trav.Time From","Ret.Trav.Time To","Total Hours (Ret.Trav.Time)",
	            );
	

	$excel->writeLine($myArr, array('text-align'=>'center', 'color'=> 'red'));
        $sql = "SELECT * FROM db_engreport WHERE engineer_id > 0  AND engineer_date BETWEEN '$o->filter_datefrom' AND '$o->filter_dateto' $orderstring $wherelimit";
        $query = mysql_query($sql);
        include_once 'class/Timesheet.php';
        $t = new Timesheet();

        while($row = mysql_fetch_array($query)){
        if($row['engineer_sparepart'] == '1'){
            $row['engineer_sparepart'] = 'Y';
        }else{
            $row['engineer_sparepart'] = 'N';
        }
        if($row['engineer_remote'] == '1'){
            $row['engineer_remote'] = 'Y';
        }else{
            $row['engineer_remote'] = 'N';
        }
        if($row['engineer_backup'] == '1'){
            $row['engineer_backup'] = 'Y';
        }else{
            $row['engineer_backup'] = 'N';
        }
        if($row['engineer_traning'] == '1'){
            $row['engineer_traning'] = 'None';
        }else if($row['engineer_traning'] == '0'){
            $row['engineer_traning'] = 'Maintance';
        }else if($row['engineer_traning'] == '2'){
            $row['engineer_traning'] = 'Operation';
        }
        if($row['engineer_malware'] == '1'){
            $row['engineer_malware'] = 'Y';
        }else{
            $row['engineer_malware'] = 'N';
        }
            $sql2 = "SELECT ss.sorder_no
                    FROM db_sorder s
                    LEFT JOIN db_sorder ss ON ss.sorder_id = s.sorder_generate_from
                    WHERE s.sorder_id = '{$row['engineer_sorder']}'";
            $query2 = mysql_query($sql2);
            if($row2 = mysql_fetch_array($query2)){
                $row['sorder_quotation_no'] = $row2['sorder_no'];
            }
            $row['engineer_customer'] = getDataCodeBySql("partner_account_name1","db_partner"," WHERE partner_id = '{$row['engineer_customer']}'","");
            $row['engineer_customer_code'] = getDataCodeBySql("partner_account_code","db_partner"," WHERE partner_id = '{$row['engineer_customer']}'","");
            $row['engineer_attentionto'] = getDataCodeBySql("contact_name","db_contact"," WHERE contact_id = '{$row['engineer_attentionto']}'","");
            $row['engineer_workkind'] = getDataCodeBySql("workkind_code","db_workkind"," WHERE workkind_id = '{$row['engineer_workkind']}'","");
            $row['sorder_no'] = getDataCodeBySql("sorder_no","db_sorder"," WHERE sorder_id = '{$row['engineer_sorder']}'","");
            $row['engineer_name'] = getDataCodeBySql("empl_name","db_empl"," WHERE empl_id = '{$row['insertBy']}'","");
            $row['charge_code'] = getDataCodeBySql("chargecode_code","db_chargecode"," WHERE chargecode_id = '{$row['engineer_charging']}'","");
            $timesheetquery = $t->fetchTimesheetDetail(" AND timesheet_engineer_id = '{$row['engineer_id']}'", $orderstring, $wherelimit, 0);
            while($r = mysql_fetch_array($timesheetquery)){
				$r['timesheet_engineers'] = getDataCodeBySql("empl_name","db_empl"," WHERE empl_id = '{$r['timesheet_engineers']}'","");

                $myArr=array($row['sorder_no'],$row['sorder_quotation_no'],$row['engineer_no'],$row['engineer_customer'],$row['engineer_attentionto'],
                             $row['engineer_billaddress'],$row['engineer_enginit'],$row['engineer_crmno'],$row['engineer_workkind'],$row['engineer_serfees'],
                             $row['charge_code'],$row['engineer_workcontent_desc'],$row['engineer_sparepart'],$row['engineer_remote'],$row['engineer_backup'],
                             $row['engineer_opreationhours'],$row['engineer_runtime'],$row['engineer_cause'],$row['engineer_result'],$row['engineer_traning'],
                             $row['engineer_malware'],$row['engineer_workpiece'],$row['engineer_machinestatus'], $row['engineer_name'],$r['timesheet_travtimecpl'],
                             $r['timesheet_date'],$r['timesheet_travtimefrom'],$r['timesheet_travtimeto'],$r['timesheet_breaktimetrav'],$r['timesheet_worktimecpl'],
                             $r['timesheet_workingtimefrom'],$r['timesheet_workingtimeto'],$r['timesheet_breaktimework'],
                    
                             $r['timesheet_rettravtimefrom'],$r['timesheet_rettravtimeto'],$r['timesheet_rettravtimetotal'],
                            );
                
                $excel->writeLine($myArr, array('text-align'=>'center', 'color'=> 'black'));
               
            }


 $excel->writeRow();
        
        }
            
	
$attachment_location = $fileName;
if (file_exists($attachment_location)) {

    header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
    header("Cache-Control: public"); // needed for internet explorer
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: Binary");
    header("Content-Length:".filesize($attachment_location));
    header("Content-Disposition: attachment; filename=Engineer_report.xls");
    readfile($attachment_location);
    $o->engineer_report_export();
    die();
} else {
    die("Error: File not found.");
}
            }else{
                $o->engineer_report_export();
            }
            exit();
            break;    
        default: 
           
            exit();
            break;
    }


