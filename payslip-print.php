<?php

    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Payroll.php'; 
    include_once 'class/ApplicantPayroll.php';     
    $o = new Payroll();
    $ao = new ApplicantPayroll();
    
    $payroll_id = escape($_REQUEST['payroll_id']);
    $py = explode(",",$payroll_id);
    if(sizeof($py) > 0){
        foreach($py as $y){
            $k .= "'$y',";
        }
        $k = trim($k,",");
    }else{
        $k = "'$payroll_id'";
    }
    

    $empl_id = escape($_REQUEST['empl_id']);
    
    if(!in_array($_SESSION['empl_group'],$master_group)){
        if($empl_id != $_SESSION['empl_id']){
            permissionLog();
            rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            exit();
        }
    }
    if(!$o->fetchPayrollDetail(" AND payroll_id = '$payroll_id'","","",1)){
        rediectUrl("payroll.php",getSystemMsg(0,'Fetch Data fail'));
        exit();
    }

?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Payslip Print</title>
    <?php
     include_once 'css.php';
     
     if($_REQUEST['action'] != 'adminprint'){
         $wherestring = " AND ple.payline_empl_id = '$empl_id'";
     }
     
     if($_REQUEST['type'] != 1){
     $sql = "SELECT pl.payroll_startdate,pl.payroll_enddate,ple.payline_empl_id,empl.empl_bank,empl.empl_bank_acc_no,pl.payroll_salary_date,ple.payline_cpf_employer,ple.payline_cpf_employee,
             empl.empl_name,empl.empl_code,empl.empl_department,empl.empl_confirmationdate,empl.empl_joindate,empl.empl_prdate,empl.empl_resigndate
             FROM db_payroll pl
             INNER JOIN db_payline ple ON ple.payline_payroll_id = pl.payroll_id
             INNER JOIN db_empl empl ON empl.empl_id = ple.payline_empl_id 
             WHERE pl.payroll_id IN ($k) $wherestring";
     }
     else 
     {

        $sql = "SELECT pl.payroll_startdate,pl.payroll_department,pl.payroll_client,pl.payroll_enddate,ple.payline_empl_id,empl.applicant_bank,
                 empl.applicant_bank_acc_no,pl.payroll_salary_date,ple.payline_cpf_employer,ple.payline_cpf_employee, 
                 empl.applicant_name,empl.applicant_code, empl.applicant_id 
                 FROM db_payroll pl 
                 INNER JOIN db_payline ple ON ple.payline_payroll_id = pl.payroll_id 
                 INNER JOIN db_applicant empl ON empl.applicant_id = ple.payline_empl_id 
                 WHERE pl.payroll_id IN ($k) $wherestring";
     }
     
     $query = mysql_query($sql);
     //$_REQUEST['print_size'] = 'A5';
    ?>
    <style>
        <?php
        if($_REQUEST['print_size'] == 'A5'){
        ?>
        @page {
          size: A5;
         
        }
        <?php }?>

        .borderless tbody > tr > td{
            border: none;
        }
        <?php
        if($_REQUEST['print_size'] == 'A5'){
        ?>
        table{
             font-size:10px !important;
        }
        .page-header{
            font-size:14px !important;
        }
        <?php }?>
    </style>
  </head>
  <!--onload="window.print();"-->
  <body onload="window.print();" >
    <?php
    if(mysql_num_rows($query) <=0){
        echo "<div class='wrapper'><h2 style = 'text-align:center;' >Record Not Found.</h2></div>";
    }
     while($row = mysql_fetch_array($query)){
         $payroll_salary_date = $row['payroll_salary_date'];
         $payroll_startdate = $row['payroll_startdate'];
         $payroll_enddate = $row['payroll_enddate'];
         $payline_empl_id = $row['payline_empl_id'];
         $payline_cpf_employer = $row['payline_cpf_employer'];
         $payline_cpf_employee = $row['payline_cpf_employee'];
         if($_REQUEST['type'] != 1){
             $empl_bank_acc_no = $row['empl_bank_acc_no'];
             $bank_code = getDataCodeBySql("bank_code","db_bank"," WHERE bank_id = '{$row['empl_bank']}'");
             $department_code = getDataCodeBySql("department_code","db_department"," WHERE department_id = '{$row['empl_department']}'");
             $empl_name = $row['empl_name'];
             $empl_code = $row['empl_code'];
             $empl_confirmationdate = $row['empl_confirmationdate'];
             $empl_joindate = $row['empl_joindate'];
             $empl_prdate = $row['empl_prdate'];
             $empl_resigndate = $row['empl_resigndate'];
             $empl_company = $com_info['cprofile_name'];
         }
         else {
             $empl_id = $row['applicant_id'];
             $empl_bank_acc_no = $row['applicant_bank_acc_no'];
             $bank_code = getDataCodeBySql("bank_code","db_bank"," WHERE bank_id = '{$row['applicant_bank']}'");
             $sql5 = "SELECT t.timeshift_department FROM db_timeshift t INNER JOIN db_followup f ON f.fol_department = t.timeshift_id WHERE f.applfollow_id = '$empl_id' AND f.follow_type = '0'";
             $query5 = mysql_query($sql5);
             $row5 = mysql_fetch_array($query5);
             $department_code = $row5['timeshift_department'];
             $empl_name = $row['applicant_name'];
             $empl_code = $row['applicant_code'];
             $empl_confirmationdate = '0000-00-00';
             
             $sql7 = "SELECT fol_available_date FROM db_followup WHERE follow_type = '0' AND applfollow_id = '$empl_id'";
             $query7 = mysql_query($sql7);
             $row7 = mysql_fetch_array($query7);
             $empl_joindate = $row7['fol_available_date'];
             
             $empl_prdate = '0000-00-00';
             $empl_resigndate = '0000-00-00'; 
             $sql6 = "SELECT * FROM db_partner WHERE partner_id = '$row[payroll_client]'";
             $query6 = mysql_query($sql6);
             $row6 = mysql_fetch_array($query6);
             $empl_company = $row6['partner_name'];
         }
    ?>
    <div class="wrapper">
      <!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              <?php echo $empl_company;
                    //echo $com_info['cprofile_name'];?>
              <small class="pull-right">Date: <?php echo format_date(system_date);?></small>
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            
          <div class="col-xs-12 table-responsive">
              <table class="table transaction-detail borderless">
                  <tr>
                      <td><b>Name</b></td>
                      <td> <?php echo $empl_name;?></td>
                      <td><b>Department</b></td>
                      <td> <?php echo $department_code;?></td>
                  </tr>
                  <tr>
                      <td><b>Join Date</b></td>
                      <td> <?php echo format_date($empl_joindate);?></td>
                      <td><b>Confirmation Date</b></td>
                      <td> <?php if($empl_confirmationdate == '0000-00-00'){ echo ' - ';}else{echo format_date($empl_confirmationdate);}?></td>
                  </tr>
                  <tr>
                      <td><b>Payslip Period</b></td>
                      <td> <?php echo format_date($payroll_startdate) . " To " . format_date($payroll_enddate);?></td>
                      <?php if($empl_prdate != '0000-00-00'){?>
                      <td><b>PR Date</b></td>
                      <td> <?php echo format_date($empl_prdate);?></td>
                      <?php }?>
                  </tr>
                  <?php
                  if($empl_resigndate != '0000-00-00'){
                  ?>
                  <tr>
                      <td><b>Termination Date</b></td>
                      <td> <?php echo format_date($empl_resigndate);?></td>
                  </tr>
                  <?php
                  }
                  ?>
              </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="">
            <table width = '100%' style ='min-height:300px'> 
                <tr>
                    <td style = 'vertical-align:top;width:50%;border:1px solid black;' >
<table class="table transaction-detail borderless">
              <thead>
                <tr>
                  <th colspan = '4' style = 'text-align:center;font-weight:bold;border-bottom: 1px solid black;background-color:white;color:black' >Earnings</th>
                </tr>
              </thead>
              <tbody>
                  <?php

                $sql1 = "
                    SELECT pi.*
                    FROM db_payline pl
                    INNER JOIN db_payitem pi ON pi.payitem_payline_id = pl.payline_id
                    WHERE pl.payline_payroll_id = '$payroll_id' AND payitem_type = '1' AND pl.payline_empl_id = '$payline_empl_id'
                    ";
                    $query1 = mysql_query($sql1);
                    $i = 1;
                    while($row1 = mysql_fetch_array($query1)){
                    ?>
                        <tr>
                          <td colspan = '3'><?php echo strtoupper($row1['payitem_remark']);?></td>
                          <td style = 'text-align:right' ><?php echo "$ ".num_format($row1['payitem_amount']);?></td>
                        </tr>
                    <?php
                    $i++;
                    $Additional_Items = $Additional_Items + $row1['payitem_amount'];
                    }
                  ?>
              </tbody>
            </table>
                    </td>
                    <td style = 'vertical-align:top;width:50%;border:1px solid black'>
                <table class="table transaction-detail borderless" >
                  <thead>
                    <tr>
                      <th colspan = '4' style = 'text-align:center;font-weight:bold;border-bottom: 1px solid black;background-color:white;color:black' >Deductions</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php

                        $sql2 = "
                            SELECT pi.*
                            FROM db_payline pl
                            INNER JOIN db_payitem pi ON pi.payitem_payline_id = pl.payline_id
                            WHERE pl.payline_payroll_id = '$payroll_id' AND payitem_type = '0' AND payline_empl_id = '$payline_empl_id' 
                            ";

                        $query2= mysql_query($sql2);
                        $i = 1;
                        while($row2 = mysql_fetch_array($query2)){
                        ?>
                            <tr style="height:20px;">
                              <td style="height:20px;" colspan = '3'><?php echo strtoupper($row2['payitem_remark']);?></td>
                              <td style = 'height:20px;text-align:right' ><?php echo "$ ".num_format($row2['payitem_amount']);?></td>
                            </tr>
                        <?php
                        $i++;
                        $Deductions_Items = $Deductions_Items + $row2['payitem_amount'];
                        }
                      ?>
                  </tbody>
                </table>
                    </td>
                </tr>
            </table>

            <div style = 'clear:both' ></div>
            </br></br>
             <div class="col-xs-6 table-responsive">
                <table class="table transaction-detail borderless"  width = '100%'>
                    <tbody>
                            <tr>
                                <td style = 'width:60%;font-weight: 700'>Total Gross</td>
                                <td style = 'width:38%;text-align:right'><?php echo "$ ".num_format($Additional_Items);?></td>

                            </tr>
                            <tr>
                                <td style = 'font-weight: 700' >CPF Gross</td>
                                <td style = 'text-align:right' ><?php echo "$ ".num_format($payline_cpf_employee);?></td>

                            </tr>
                            <tr>
                                <td style = 'font-weight: 700'>Employer CPF</td>
                                <td style = 'text-align:right' ><?php echo "$ ".num_format(round($payline_cpf_employer));?></td>

                            </tr>
                            <tr>
                                <td style = 'font-weight: 700'>Date Of Payment</td>
                                <td style = 'text-align:right' ><?php echo format_date($payroll_salary_date);?></td>

                            </tr>
                            <tr>
                                <td style = 'font-weight: 700'>Mode Of Payment</td>
                                <td style = 'text-align:right' ><?php echo $bank_code . " - " . $empl_bank_acc_no;?></td>

                            </tr>
                    </tbody>
                </table>
            </div>
              <div class="col-xs-6 table-responsive">
                    <table id="detail_table" class="table transaction-detail borderless" width = '100%' >

                        <tbody>
                                <tr>
                                    <td style = 'width:60%;font-weight: 700'>Total Deduction</td>
                                    <td style = 'width:38%;text-align:right'><?php echo "$ ".num_format($Deductions_Items);?></td>
                                </tr>
                                <tr>
                                    <td style = 'font-weight: 700'>Net Payment</td>
                                    <td style = 'text-align:right' ><?php echo "$ ".num_format($Additional_Items - $Deductions_Items);?></td>
                                </tr>
                                <tr>
                                    <td style = 'font-weight: 700'>Year To Date Net Pay</td>
                                    <td style = 'text-align:right' >
                                        <?php 
                                            if($_REQUEST['type'] != 1){
                                                echo "$ ".num_format($o->getYearAmount($payroll_startdate,$empl_id,'net_pay',$payroll_enddate));
                                            }
                                            else{
                                                echo "$ ".num_format($ao->getYearAmount($payroll_startdate,$empl_id,'net_pay',$payroll_enddate));
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style = 'font-weight: 700'>Year To Date Employer CPF</td>
                                    <td style = 'text-align:right' >
                                        <?php 
                                        if($_REQUEST['type'] != 1){
                                            echo "$ ".num_format($o->getYearAmount($payroll_startdate,$empl_id,'employer',$payroll_enddate));
                                        }
                                        else{
                                            echo "$ ".num_format($ao->getYearAmount($payroll_startdate,$empl_id,'employer',$payroll_enddate));
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style = 'font-weight: 700'>Year To Date Employee CPF</td>
                                    <td style = 'text-align:right' >
                                        <?php 
                                        if($_REQUEST['type'] != 1){
                                            echo "$ ".num_format($o->getYearAmount($payroll_startdate,$empl_id,'employee',$payroll_enddate));
                                        }
                                        else{
                                            echo "$ ".num_format($ao->getYearAmount($payroll_startdate,$empl_id,'employee',$payroll_enddate));
                                        }
                                         
                                        ?>
                                    </td>
                                </tr>
                        </tbody>
                    </table>
              </div>
            <div style = 'clear:both' ></div>
        </div><!-- /.row -->

        
      </section><!-- /.content -->
    </div><!-- ./wrapper -->
      <?php }?>
    <!-- AdminLTE App -->
    <script src="../../dist/js/app.min.js"></script>
  </body>
</html>
