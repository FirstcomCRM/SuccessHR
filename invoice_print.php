<?php
    include_once 'connect.php';
    include_once 'config.php';
    include_once 'class/Invoice.php';
    include_once 'include_function.php';

    include_once 'class/SavehandlerApi.php';
    include_once 'class/GeneralFunction.php';

    $s = new SavehandlerApi();
    $gf = new GeneralFunction();

    $action = escape($_REQUEST['action']);
    $invoice_id = escape($_REQUEST['invoice_id']);
    $invoice_date = escape($_REQUEST['date']);
    $client_id = escape($_REQUEST['client_id']);
    $invoice_gst = escape($_REQUEST['invoice_gst']);
    
    $o = new Invoice();
    $job_type = $o->getJobType(escape($_REQUEST['job_type']));
    
    
    switch ($action){
        case "print_invoice":

        include("dist/excelwriter.inc.php");
        
	$fileName = "dist/mynewxls.xls";
	$excel = new ExcelWriter($fileName);


        
	$myArr=array("No","Name","Nric / Fin no","SIP / Adhoc","Duration",
                     "University","Division / Department","Undergrad / Postgrad","Daily Rate","No. of Worked Days","No. of Required Days",
                     "Monthly Salary","Rate Per Hour","Normal Hours (1x)","Normal Amount (1x) Non CPF",
                     "Normal Amount (1x) CPF","OT Hours (1.5x)","OT Amount (1.5x) Non CPF","OT Amount (1.5x) CPF","OT Hours (2x)",
                     "OT Amount (2x) Non CPF","OT Amount (2x) CPF","Additional", "Additional CPF", "Deductions", "Deductions CPF",
                     "Total CPF", "SUB TOTAL","Admin Fee","Invoice Amount","GST (".$invoice_gst. " %)",
                     "Total Invoice Amount"
	            );
//	,"Medical Reimbursement Non CPF","Medical ReimbursementCPF","Meal Allowance Non CPF","Meal Allowance CPF"
        
	$excel->writeLine($myArr, array('text-align'=>'center', 'color'=> 'black', 'font-weight' => 'bold'));
        
        $firstDay = date('Y-m-01', strtotime($invoice_date));
        $lastDay = date("Y-m-t", strtotime($invoice_date));
             
        $startDate = substr($invoice_date,0,7);

        $sql = "SELECT a.*, t.*, f.* FROM db_applicant a INNER JOIN db_followup f ON a.applicant_id = f.applfollow_id INNER JOIN db_timeshift t ON t.timeshift_id = f.fol_department WHERE f.follow_type = '0' AND f.interview_company = '$client_id' AND f.fol_job_type = '$job_type' AND f.fol_available_date <= '$lastDay' AND f.fol_status = '0' AND LEFT(f.fol_assign_expiry_date,7) >= '$startDate'";
        
        $i =1;
        $query = mysql_query($sql);

        while($row = mysql_fetch_array($query)){

            $sql2 = "SELECT pr.*, pl.* FROM db_payroll pr INNER JOIN db_payline pl ON pr.payroll_id = pl.payline_payroll_id WHERE pr.payroll_client = '$client_id' AND pl.payline_empl_id = '$row[applicant_id]' AND pl.payline_empl_type = '1' AND pr.payroll_startdate = '$firstDay' AND pr.payroll_enddate = '$lastDay'";
            $query2 = mysql_query($sql2);
            $row2 = mysql_fetch_array($query2);
            
            $sql3 = "SELECT * FROM db_attendance WHERE attendance_empl = '$row[applicant_id]' AND attendance_date_start BETWEEN '$firstDay' AND '$lastDay'";
            $query3 = mysql_query($sql3);
            $row3 = mysql_num_rows($query3);
            
            $daily_rate = (double)$row2['payline_salary'] / (double)$row['timeshift_work_day'];
            $daily_rate = ROUND($daily_rate,2);
            
            $timeIn = new DateTime($row['timeshift_start_time']);
            $timeOut = new DateTime($row['timeshift_end_time']);
            $normal_hour = $timeIn->diff($timeOut);
            $hour = (double)$normal_hour->format('%h');
            $hour = $hour - (double)$row['timeshift_lunch_hour'];
            
            $hour_rate = (double)$daily_rate / ((double)$hour);
            $hour_rate = ROUND($hour_rate,2);
            
            include_once 'class/Cpf.php';
            $c = new Cpf();

            $from = new DateTime($row['applicant_birthday']);
            $to = new DateTime('today');
            $applicant_age = $from->diff($to)->y;
            (double)$applicant_age;

            $sql4 = "SELECT SUM(attendance_ot_hour) AS total FROM db_attendance WHERE attendance_empl = '$row[applicant_id]' AND attendance_date_start BETWEEN '$firstDay' AND '$lastDay'";
            $query4 = mysql_query($sql4);
            $row4 = mysql_fetch_array($query4);
            $total_ot = $row4['total'];
            
            if($row['applicant_nationality'] == "2"){
                $cpf_array = $c->fetchCpfDetail(" AND cpf_from_age <= '$applicant_age' AND cpf_to_age >= '$applicant_age' ", "", "",2);

                $normalCPF = (double)$row2['payline_salary'] * ($cpf_array['cpf_employee_percent']/100);
                $normalnonCPF = "0";
            }

            else{
                $cpf = 0;
                $normalCPF = "0";
                $normalnonCPF = "0"; 
            }
            
            $sql8 = "SELECT SUM(additional_amount) as total FROM db_additional WHERE additional_empl_id = '$row[applicant_id]' AND additional_date  BETWEEN '$firstDay' AND '$lastDay' AND additional_type = '3' AND additional_status = '1' AND additional_empl_type = '1' AND additional_affect_cpf = '1'";
            $query8 = mysql_query($sql8);
            $row8 = mysql_fetch_array($query8);
            
            if($row['timeshift_ot_rate'] == "1.5"){
                $total_hour15x = $total_ot;
                $ot_cpf15x = $row8['total'] * ($cpf_array['cpf_employee_percent']/100);
                $ot_cpf15x = ROUND($ot_cpf15x,2);
                $ot_noncpf15x = $row8['total'] - $ot_cpf15x;
                $total_hour2x = "0";
                $ot_cpf2x = "0";
                $ot_noncpf2x = "0";   
            }else{
                $total_hour15x = "0";
                $ot_cpf15x = "0";
                $ot_noncpf15x = "0";                
                $total_hour2x = $total_ot;
                $ot_cpf2x = $row8['total'] * ($cpf_array['cpf_employee_percent']/100);
                $ot_cpf2x = ROUND($ot_cpf2x,2);
                $ot_noncpf2x = $row8['total'] - $ot_cpf2x;;                
            }
            
            $sql9 = "SELECT SUM(additional_amount) as total FROM db_additional WHERE additional_empl_id = '$row[applicant_id]' AND additional_date  BETWEEN '$firstDay' AND '$lastDay' AND additional_type = '3' AND additional_status = '1' AND additional_empl_type = '1' AND additional_affect_cpf = '0'";
            $query9 = mysql_query($sql9);
            $row9 = mysql_fetch_array($query9);
            
            $ot_noncpf2x = $ot_noncpf2x + $row9['total'];
            
            
            $sql5 = "SELECT SUM(additional_amount) as add_cpf_totol FROM db_additional WHERE additional_empl_id = '$row[applicant_id]' AND additional_empl_type = '1' AND additional_affect_cpf = '1' AND additional_date BETWEEN '$firstDay' AND '$lastDay'"; 
            $query5 = mysql_query($sql5);
            $row5 = mysql_fetch_array($query5);

            $addition_cpf = (double)$row5['add_cpf_totol'] * ($cpf_array['cpf_employee_percent']/100);
            
            $sql6 = "SELECT SUM(deductions_amount) as duc_cpf_totol FROM db_deductions WHERE deductions_empl_id = '$row[applicant_id]' AND deduction_empl_type = '1' AND deductions_affect_cpf = '1' AND deductions_date BETWEEN '$firstDay' AND '$lastDay'"; 
            $query6 = mysql_query($sql6);
            $row6 = mysql_fetch_array($query6);
            
            $deduction_cpf = $row5['duc_cpf_totol'] * ($cpf_array['cpf_employee_percent']/100);
            
            
            $invoice_amount = (double)$row2['payline_netpay'] + (double)$row['fol_admin_fee'];
            $gst = $invoice_amount * ($invoice_gst/100);
            $gst = ROUND($gst,2);
            $total_invoice = $invoice_amount + $gst;
            
            
            $data = array($i, $row['applicant_name'], $row['applicant_nric'], "", format_date($firstDay)." to ".format_date($lastDay),
                          "", $row['timeshift_department'], "", "$ ".$daily_rate, $row3." day", $row['timeshift_work_day']. " day",
                          "$ ".$row2['payline_salary'], "$ ".$hour_rate, $hour." Hour", "$ ".$normalnonCPF, "$ ".$normalCPF, 
                          $total_hour15x." Hour", "$ ".$ot_noncpf15x, "$ ".$ot_cpf15x, $total_hour2x." Hour", "$ ".$ot_noncpf2x,
                          "$ ".$ot_cpf2x,
                          "$ ".$row2['payline_additional'], "$ ".$addition_cpf, "$ ".$row2['payline_deductions'], "$ ".$deduction_cpf, "$ ".$row2['payline_cpf_employee'],
                          "$ ".$row2['payline_netpay'], "$ ".$row['fol_admin_fee'], "$ ".$invoice_amount, "$ ".$gst, "$ ".$total_invoice
                         );
            
            
            $excel->writeLine($data, array('text-align'=>'center', 'color'=> 'black'));
            
            $total_Monthly_Salary = $total_Monthly_Salary + (double)$row2['payline_salary'];
            $total_Addtional = $total_Addtional + (double)$row2['payline_additional'];
            $total_Addtional_Cpf = $total_Addtional_Cpf + (double)$addition_cpf;
            $total_Deduction = $total_Deduction + (double)$row2['payline_deductions'];
            $total_Deduction_Cpf = $total_Deduction_Cpf + (double)$deduction_cpf;
            
            $total_CPF = $total_CPF + (double)$row2['payline_cpf_employee'];
            $sub_total = $sub_total + (double)$row2['payline_netpay'];
            $total_Admin_Fee = $total_Admin_Fee + (double)$row['fol_admin_fee'];
            $total_Amount = $total_Amount + $invoice_amount;
            $total_gst = $total_gst + $gst;
            $total_i = $total_i + $total_invoice;

            
            $i++;
        }

         $data = array("", "", "", "", "",
              "", "", "", "", "", "Total Amount",
              "$ ".$total_Monthly_Salary, "", "", "", "", 
              "", "", "", "", "",
              "",
              "$ ".$total_Addtional, "$ ".$total_Addtional_Cpf, "$ ".$total_Deduction, "$ ".$total_Deduction_Cpf, "$ ".$total_CPF,
              "$ ".$sub_total, "$ ".$total_Admin_Fee, "$ ".$total_Amount, "$ ".$total_gst, "$ ".$total_i
             );   
        
        $excel->writeRow(); 
        $excel->writeLine($data, array('text-align'=>'center', 'color'=> 'black', 'font-weight' => 'bold'));
        
        
        
	
        $attachment_location = $fileName;
        if (file_exists($attachment_location)) {

            header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
            header("Cache-Control: public"); // needed for internet explorer
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: Binary");
            header("Content-Length:".filesize($attachment_location));
            header("Content-Disposition: attachment; filename=CandidateSummary.xls");
            readfile($attachment_location);

            die();
        } else {
            die("Error: File not found.");
        }
}

