<?php
/*
 * To change this tleaveate, choose Tools | Tleaveates
 * and open the tleaveate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Dashboard {

    public function Dashboard(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
    
    }

    public function getInputForm($action){
        global $mandatory;
        include_once 'class/Empl.php';
        $e = new Empl(); 
        $hr_dashboard = false;
        $sales_purchase_dashboard = false;

        if(($_SESSION['empl_group'] == 2) || ($_SESSION['empl_group'] == 1) || ($_SESSION['empl_group'] == -1)){//HR OR Admin OR webmaster
            $hr_dashboard = true;
            $staff_dashboard = true;
        }
        else{//Others Staff
            $hr_dashboard = false;
            $staff_dashboard = true;
        }
       
    ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard</title>
    
    <?php
    include_once 'css.php';
    include_once 'js.php';
    ?>
    <!-- jQuery UI 1.11.4 -->
    <!--<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>-->
    <?php
        //HR Part
        
        if($hr_dashboard){
            //total Pending Leave
            $dataLeave['data'] = getDataCountBySql("db_leave e", " WHERE e.leave_approvalstatus = 'Pending' AND e.leave_status = '1' ");
            $dataLeave['color'] = "bg-aqua";
            $dataLeave['icon'] = "ion-stats-bars";
            $dataLeave['title'] = "Number of leave pending approved";
            $dataLeave['link'] = "leave.php";
            $pending_div_Leaves = $this->getDivCountData($dataLeave);
            
            //total Pending Claims
            $dataClaims['data'] = getDataCountBySql("db_claims e", " WHERE e.claims_approvalstatus = 'Pending' AND e.claims_status = '1' ");
            $dataClaims['color'] = "bg-green";
            $dataClaims['icon'] = "ion-shuffle";
            $dataClaims['title'] = "Number of claim pending approved";
            $dataClaims['link'] = "claims.php";
            $pending_div_Claims = $this->getDivCountData($dataClaims);
            
            //total Employee
            $dataEmployee['data'] = getDataCountBySql("db_empl e", " WHERE e.empl_status = '1' ");
            $dataEmployee['color'] = "bg-yellow";
            $dataEmployee['icon'] = "ion-person-add";
            $dataEmployee['title'] = "Total No. employee";
            $dataEmployee['link'] = "empl.php";
            $total_div_employee = $this->getDivCountData($dataEmployee);
            
            //Passes Expiring
            $dataRenewal['data'] = $this->RenewalData();
            $dataRenewal['color'] = "box-warning";
            $dataRenewal['title'] = "Passes Expiring";
            $dataRenewal['col'] = "col-md-6";
            $renewal_div = $this->tableListing($dataRenewal);
            
            //Probation Period Expiring
            $dataProbation['data'] = $this->ProbationData();
            $dataProbation['color'] = "box-warning";
            $dataProbation['title'] = "Probation Period Expiring";
            $dataProbation['col'] = "col-md-6";
            $probation_div = $this->tableListing($dataProbation);
            
            //Employee Birthday
            $dataBirthday['data'] = $this->BirthdayData();
            $dataBirthday['color'] = "box-warning";
            $dataBirthday['title'] = "Employee Birthday";
            $dataBirthday['col'] = "col-md-6";
            $birthday_div = $this->tableListing($dataBirthday);
        }
        
        if($staff_dashboard){ 
            //Leave Status
            $dataLeaveStaff['data'] = $this->LeaveTable();
            $dataLeaveStaff['color'] = "box-warning";
            $dataLeaveStaff['title'] = "Leave Status";
            $dataLeaveStaff['col'] = "col-md-6";
            $dataLeaveStaff['display'] = "block";
            $LeaveStaff_div = $this->tableListing($dataLeaveStaff);
            
            //Claims Status
            $dataClaimsStaff['data'] = $this->ClaimsTable();
            $dataClaimsStaff['color'] = "box-success";
            $dataClaimsStaff['title'] = "Claims Status Between " . format_date(system_date_monthstart) . " & " . format_date(system_date_monthend);
            $dataClaimsStaff['col'] = "col-md-6";
            $dataClaimsStaff['display'] = "block";
            $ClaimsStaff_div = $this->tableListing($dataClaimsStaff);
            
            //Balance Leave
            $dataBalanceLeaveStaff['data'] = $this->BalanceLeaveTable();
            $dataBalanceLeaveStaff['color'] = "box-danger";
            $dataBalanceLeaveStaff['title'] = "Balance Leave";
            $dataBalanceLeaveStaff['col'] = "col-md-6";
            $dataBalanceLeaveStaff['display'] = "block";
            $BalanceLeaveStaff_div = $this->tableListing($dataBalanceLeaveStaff);
            
            //Staff Directory
            $dataDirectoryStaff['data'] = '<embed src="Staff Directory.pdf" height = "100%" width = "100%">';
            $dataDirectoryStaff['color'] = "box-pp";
            $dataDirectoryStaff['title'] = "Staff Directory";
            $dataDirectoryStaff['col'] = "col-md-6";
            $dataDirectoryStaff['display'] = "none";
            $DirectoryStaff_div = $this->tableListing($dataDirectoryStaff);
        }
    ?>
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="hold-transition skin-blue sidebar-mini">
      <a href="../../Bulking/demo/catalog/view/theme/default/template/checkout/checkout.tpl"></a>

    <div class="wrapper">
      <!-- include header-->
      <?php include_once 'header.php';?>
      <!-- Full Width Column -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
<!--          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"></li>
          </ol>-->
        </section>
        <section class="content" >
            
                    <!--admin-->
                    <?php 
                    if ($_SESSION['empl_group'] == "1" || $_SESSION['empl_group'] == "-1"){
                       $this->getAdminDashboard(); 
                       $this->getRemarkDashboard();
                       $this->getCalender();
                    }?>
     
                    <!--Manager-->
                    <?php
                    if ($_SESSION['empl_group'] == "4")
                    { 
                        $this->getManagerDashboard();  
                        $this->getRemarkDashboard();
                    }?>

                    <!--consultant_-->
                    <?php if($_SESSION['empl_group'] == "8") {
                        $this->getConsultantDashboard();
                        $this->getRemarkDashboard();
                        $this->getCalender();
                     } ?>

                    <!-- candidate and client empl-->
                    <?php if($_SESSION['empl_group'] == "5" || $_SESSION['empl_group'] == "9" || $_SESSION['empl_group'] == "4") { 
                        $this->getCalender();
                        $this->getCalenderForm();
                    } ?>
                                        
                    <!--Payroll-->
                    <?php if($_SESSION['empl_group'] == "7") { 
                        $this->getPayrollForm();
                        $this->getCalender();
                    } ?>            
            
        </section>  
      </div>
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';
    ?>
    
 <div class="modal fade " id="sstatusModal" role="dialog">
    <div class="modal-dialog ">
        <form action = 'payroll.php?selfview=1' method = "POST">
            <!-- Modal content-->
            <div class="modal-content ">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title sstatus_title"></h4>
              </div>
              <div class="modal-body">
                  Your Password <?php echo $mandatory;?>  :  <input type = "password" name = "varify_password" id = 'password' />
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <input type = 'hidden' name = 'action' id = 'varify_action' value = '' />
                  <input type = 'hidden' name = 'empl_id' id = 'empl_id' value = '' />
                  <input type = 'hidden' name = 'payroll_id' id = 'payroll_id' value = '' />
              </div>
            </div>
        </form>
    </div>
  </div>  
    
    <script>
      $(function () {
        $('#payroll_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
 
       $(function () {
        $('#otherManager_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      }); 
      $(function () {
        $('#job_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });  
      $(function () {
        $('#interview_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });  
      $(function () {
        $('#applicant_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });        
      $(function () {
        $('#client_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });       
      
      
  
        $('.close_alert_payslip').click(function(){
            var data = "selfview=1&action=updateSelfView&payroll_id=" + $(this).attr('pid') + "&empl_id="+ $(this).attr('eid');
            $.ajax({ 
               type: 'POST',
               url: 'payroll.php',
               cache: false,
               data:data,
               success: function(data) {
                   
               }
            });
        });
        
        $('.astatus').click(function(){
                $('#payroll_id').val($(this).attr('payroll_id'));
                $('#empl_id').val($(this).attr('empl_id'));
                $('#varify_action').val('varify_password');
        });
        
        $('.delete').on('click',function(){
            var r = confirm("Confirm Delete");
            if (r == true) {
               $(this).closest('tr').remove();
                var data = "action=updatePartnerDashboardDisplay&partner_id=" + $(this).attr("pid");

                        $.ajax({ 
                        type: 'POST',
                        url: 'dashboard.php',
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
             }
        });      
 
        $('.delete-applicant').on('click',function(){
            var r = confirm("Confirm Delete");
            if (r == true) {
               $(this).closest('tr').remove();
                var data = "action=updateApplicantDashboardDisplay&partner_id=" + $(this).attr("pid");

                        $.ajax({ 
                        type: 'POST',
                        url: 'dashboard.php',
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
             }
        });  
        
//        function activeCandidate(empl_id){
//            var data = "action=getRemarkDetail&empl_id="+ empl_id;
//                $.ajax({ 
//                    type: 'POST',
//                    url: 'dashboard.php',
//                    cache: false,
//                    data:data,
//                    error: function(xhr) {
//                        alert("System Error.");
//                        issend = false;
//                    },
//                    success: function(data) {
//                       var jsonObj = eval ("(" + data + ")");
//                    
//                    var activeApplTable = "";
//
//                    if( jsonObj['applicant']!=null){
//                        activeApplTable = "<h4>"+jsonObj['aRemarks']['empl_name'][0]+"</h4><table class= 'table table-bordered table-hover' id='active_appl_table'" + "><thead><tr> <th style = 'width:2%'>No</th>";
//                        activeApplTable = activeApplTable + "<th style = 'width:7%'>Candidate Name</th><th style = 'width:6%'>Mobile</th><th style = 'width:6%'>Email</th><th style = 'width:8%'></th></tr></thead><tbody>";
//                        var pn = 1;
//                        for(var i=0;i<jsonObj['applicant']['applicant_name'].length;i++){
//                        activeApplTable = activeApplTable + "<tr><td><a href='applicant.php?action=edit&applicant_id=" + jsonObj['applicant']['applicant_id'][i] + "'>" + pn + "</a></td><td>" + jsonObj['applicant']['applicant_name'][i] + "</td><td>" + jsonObj['applicant']['applicant_mobile'][i] + "</td><td>" + jsonObj['applicant']['applicant_email'][i] + "</td>";
//                        activeApplTable = activeApplTable + "<td><button type='button' class='btn btn-warning btn-client showremarks'  style='margin-right: 2px' pid='&string=and a.applicant_id=" + jsonObj['applicant']['applicant_id'][i] +"' id = '" + jsonObj['aRemarks']['empl_id'][0] + "'>R</button>";
//                        activeApplTable = activeApplTable + "<button type='button' class='btn btn-info btn-client showremarks' style='margin-right: 2px'  data-toggle='tooltip' title='Clear' pid='&string=and a.applicant_id=" + jsonObj['applicant']['applicant_id'][i] + " and f.follow_type=2' id = '" + jsonObj['aRemarks']['empl_id'][0] + "'>I</button>";
//                        activeApplTable = activeApplTable + "<button type='button' class='btn btn-success btn-client showremarks' style='margin-right: 2px' pid='&string=and a.applicant_id=" + jsonObj['applicant']['applicant_id'][i] + " and f.follow_type=0' id = '" + jsonObj['aRemarks']['empl_id'][0] + "'>S</button>";
//                        activeApplTable = activeApplTable + "<button type='button' class='btn btn-danger btn-client delete' style='margin-right: 2px' pid='" + jsonObj['applicant']['follow_id'][i] + "' id='" + jsonObj['aRemarks']['empl_id'][0] + "'><i class='fa fa-dw fa-close'></i></button></td></tr>";
//                                
//                        pn++;
//                    }
//                    activeApplTable = activeApplTable + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th>";
//                           activeApplTable = activeApplTable + "<th style = 'width:7%'>Candidate Name</th><th style = 'width:7%'>Mobile</th><th style = 'width:7%'>Email</th><th style = 'width:7%'></th></tr></tfoot></table>";
//                    }
//                    else
//                    {
//                        activeApplTable = "<p>No have any applicant.</p>";
//                    }
//                    
//                     
//                    $('#pRemarks_content').html(activeApplTable);
//                    
//                    $(function () {
//                        $('#active_appl_table').DataTable({
//                          "paging": true,
//                          "lengthChange": false,
//                          "searching": true,
//                          "ordering": true,
//                          "info": true,
//                          "autoWidth": false
//                        });
//                      });  
//                    }
//            });
//        }
         
         function remarks(id){
                   
            var data = "action=getRemarkDetail&empl_id="+id;
                $.ajax({ 
                    type: 'POST',
                    url: 'dashboard.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                    
                    var table = "";
                    
                    
                    if( jsonObj['aRemarks']!=null){
                        table_head = "<h4>"+jsonObj['aRemarks']['empl_name'][0]+"</h4>";
                        table = "<h4>"+jsonObj['aRemarks']['empl_name'][0]+"</h4><table class= 'table table-bordered table-hover' id='app_remark_table'" + "><thead><tr> <th style = 'width:2%'>No</th>";
                        table = table + "<th style = 'width:5%'>Candidate</th><th style = 'width:5%'>Client</th><th style = 'width:5%'>Follow Type</th><th style = 'width:15%'>Description</th>";
                        table = table + "<th style = 'width:5%'>Time & Date</th></tr></thead><tbody>";
                    var n = 1; 
                    for(var i=0;i<jsonObj['aRemarks']['applicant_name'].length;i++){
                        table = table + "<tr><td><a href='applicant.php?action=edit&current_tab=followup&applicant_id=" + jsonObj['aRemarks']['applicant_id'][i] + "&follow_id=" + jsonObj['aRemarks']['follow_id'][i] + "'>" + n +"</a></td><td>" + jsonObj['aRemarks']['applicant_name'][i] + "</td><td>" + jsonObj['aRemarks']['interview_company'][i] + "</td><td>" + jsonObj['aRemarks']['follow_type'][i] + "</td><td>" + jsonObj['aRemarks']['comments'][i] + "</td><td>" + jsonObj['aRemarks']['time'][i] +"<br>" + jsonObj['aRemarks']['date'][i] + "</td></tr>"; 
                        n++;
                        }
                        table = table + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th><th style = 'width:5%'>Candidate</th><th style = 'width:5%'>Client</th>";
                               table = table + "<th style = 'width:5%'>Follow Type</th><th style = 'width:15%'>Description</th><th style = 'width:5%'>Time & Date</th></tr></tfoot></table>";
                    }
                    else
                    {
                        table = "<p>No have any remarks.</p>";
                    }
                    $('#aRemarks_content').html(table);

                    var activeApplTable = "";

                    if( jsonObj['applicant']!=null){
                        activeApplTable = "<h4>"+jsonObj['aRemarks']['empl_name'][0]+"</h4><table class= 'table table-bordered table-hover' id='active_appl_table'" + "><thead><tr> <th style = 'width:2%'>No</th>";
                        activeApplTable = activeApplTable + "<th style = 'width:7%'>Candidate Name</th><th style = 'width:6%'>Mobile</th><th style = 'width:6%'>Email</th><th style = 'width:8%'></th></tr></thead><tbody>";
                        var pn = 1;
                        for(var i=0;i<jsonObj['applicant']['applicant_name'].length;i++){
                        activeApplTable = activeApplTable + "<tr id='" + jsonObj['applicant']['applicant_id'][i] + "'><td><a href='applicant.php?action=edit&applicant_id=" + jsonObj['applicant']['applicant_id'][i] + "'>" + pn + "</a></td><td>" + jsonObj['applicant']['applicant_name'][i] + "</td><td>" + jsonObj['applicant']['applicant_mobile'][i] + "</td><td>" + jsonObj['applicant']['applicant_email'][i] + "</td>";
                        activeApplTable = activeApplTable + "<td><button type='button' class='btn btn-warning btn-client showremarks' data-toggle='tooltip' title='Show Remark' style='margin-right: 2px' pid='&string=and a.applicant_id=" + jsonObj['applicant']['applicant_id'][i] +"' id = '" + jsonObj['aRemarks']['empl_id'][0] + "'>R</button>";
                        activeApplTable = activeApplTable + "<button type='button' class='btn btn-info btn-client showremarks' data-toggle='tooltip' title='Show Interview' style='margin-right: 2px' pid='&string=and a.applicant_id=" + jsonObj['applicant']['applicant_id'][i] + " and f.follow_type=2' id = '" + jsonObj['aRemarks']['empl_id'][0] + "'>I</button>";
                        activeApplTable = activeApplTable + "<button type='button' class='btn btn-success btn-client showremarks' data-toggle='tooltip' title='Assigned' style='margin-right: 2px' pid='&string=and a.applicant_id=" + jsonObj['applicant']['applicant_id'][i] + " and f.follow_type=0' id = '" + jsonObj['aRemarks']['empl_id'][0] + "'>S</button>";
                        activeApplTable = activeApplTable + "<button type='button' class='btn btn-danger btn-client delete' data-toggle='tooltip' title='Clear' style='margin-right: 2px' pid='" + jsonObj['applicant']['follow_id'][i] + "'><i class='fa fa-dw fa-close'></i></button></td></tr>";
                                
                        pn++;
                    }
                    activeApplTable = activeApplTable + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th>";
                           activeApplTable = activeApplTable + "<th style = 'width:7%'>Candidate Name</th><th style = 'width:7%'>Mobile</th><th style = 'width:7%'>Email</th><th style = 'width:7%'></th></tr></tfoot></table>";
                    }
                    else
                    {
                        activeApplTable = "<p>No have any applicant.</p>";
                    }
                    
                     
                    $('#pRemarks_content').html(activeApplTable);
                        
//                    var ptable = "";
//
//                    if( jsonObj['pRemarks']!=null){
//                        ptable = "<h4>"+jsonObj['pRemarks']['empl_name'][0]+"</h4><table class= 'table table-bordered table-hover' id='empl_remark_table'" + "><thead><tr> <th style = 'width:2%'>No</th>";
//                        ptable = ptable + "<th style = 'width:7%'>Client</th><th style = 'width:15%'>Description</th><th style = 'width:5%'>Time & Date</th></tr></thead><tbody>";
//                        var pn = 1;
//                        for(var i=0;i<jsonObj['pRemarks']['partner_name'].length;i++){
//                        ptable = ptable + "<tr><td><a href='partner.php?action=edit&tab=followup&partner_id=" + jsonObj['pRemarks']['partner_id'][i] + "&pfollow_id=" + jsonObj['pRemarks']['pfollow_id'][i] + "'>" + pn + "</a></td><td>" + jsonObj['pRemarks']['partner_name'][i] + "</td><td>" + jsonObj['pRemarks']['pfollow_description'][i] + "</td><td>" + jsonObj['pRemarks']['time'][i] + "<br>" + jsonObj['pRemarks']['date'][i] +"</td></tr>";
//                        pn++;
//                    }
//                    ptable = ptable + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th>";
//                           ptable = ptable + "<th style = 'width:7%'>Client</th><th style = 'width:15%'>Description</th><th style = 'width:5%'>Time & Date</th></tr></tfoot></table>";
//                    }
//                    else
//                    {
//                        ptable = "<p>No have any remarks.</p>";
//                    }
//                    
//                     
//                    $('#pRemarks_content').html(ptable);
                      $(function () {
                        $('#app_remark_table').DataTable({
                          "paging": true,
                          "lengthChange": false,
                          "searching": true,
                          "ordering": true,
                          "info": true,
                          "autoWidth": false
                        });
                      });
                      
                      $(function () {
                        $('#active_appl_table').DataTable({
                          "paging": true,
                          "lengthChange": false,
                          "searching": true,
                          "ordering": true,
                          "info": true,
                          "autoWidth": false
                        });
                      });  
                    
//                      $(function () {
//                        $('#empl_remark_table').DataTable({
//                          "paging": true,
//                          "lengthChange": false,
//                          "searching": true,
//                          "ordering": true,
//                          "info": true,
//                          "autoWidth": false
//                        });
//                      });                      
                    
                    $('.showremarks').on('click',function(){
                        var data = "action=getRemarkDetail&empl_id="+$(this).attr("id")+"&applicant_id="+$(this).attr("pid");
                                $.ajax({ 
                                type: 'POST',
                                url: 'dashboard.php',
                                cache: false,
                                data:data,
                                error: function(xhr) {
                                    alert("System Error.");
                                    issend = false;
                                },
                                success: function(data) {
                                   var jsonObj = eval ("(" + data + ")");

                                var table = "";


                                if( jsonObj['aRemarks']!=null){
                                    table_head = "<h4>"+jsonObj['aRemarks']['empl_name'][0]+"</h4>";
                                    table = "<h4>"+jsonObj['aRemarks']['empl_name'][0]+"</h4><table class= 'table table-bordered table-hover' id='app_remark_table'" + "><thead><tr> <th style = 'width:2%'>No</th>";
                                    table = table + "<th style = 'width:5%'>Candidate</th><th style = 'width:5%'>Client</th><th style = 'width:5%'>Follow Type</th><th style = 'width:15%'>Description</th>";
                                    table = table + "<th style = 'width:5%'>Time & Date</th></tr></thead><tbody>";
                                var n = 1; 
                                for(var i=0;i<jsonObj['aRemarks']['applicant_name'].length;i++){
                                    table = table + "<tr><td><a href='applicant.php?action=edit&current_tab=followup&applicant_id=" + jsonObj['aRemarks']['applicant_id'][i] + "&follow_id=" + jsonObj['aRemarks']['follow_id'][i] + "'>" + n +"</a></td><td>" + jsonObj['aRemarks']['applicant_name'][i] + "</td><td>" + jsonObj['aRemarks']['interview_company'][i] + "</td><td>" + jsonObj['aRemarks']['follow_type'][i] + "</td><td>" + jsonObj['aRemarks']['comments'][i] + "</td><td>" + jsonObj['aRemarks']['time'][i] +"<br>" + jsonObj['aRemarks']['date'][i] + "</td></tr>"; 
                                    n++;
                                    }
                                    table = table + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th><th style = 'width:5%'>Candidate</th><th style = 'width:5%'>Client</th>";
                                           table = table + "<th style = 'width:5%'>Follow Type</th><th style = 'width:15%'>Description</th><th style = 'width:5%'>Time & Date</th></tr></tfoot></table>";

                                }
                                else
                                {
                                    table = "<p>No have any remarks.</p>";
                                }
                                $('#aRemarks_content').html(table);
                                
                                $(function () {
                                    $('#app_remark_table').DataTable({
                                      "paging": true,
                                      "lengthChange": false,
                                      "searching": true,
                                      "ordering": true,
                                      "info": true,
                                      "autoWidth": false
                                    });
                                  });
                                }		
                             });
                        });
      
      
                    $('.delete').on('click',function(){
                        var r = confirm("Confirm Delete");
                        if (r == true) {
                           $(this).closest('tr').remove();
                            var data = "action=updateDashboardDisplay&follow_id=" + $(this).attr("pid");

                                    $.ajax({ 
                                    type: 'POST',
                                    url: 'dashboard.php',
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
                         }
                    });   
                        
                        
                       issend = false;
                    }		
                 });
                 return false;
         }
         
         var id = <?php echo $_SESSION['empl_group'];?>;
         if(id == "8"){
             remarks(<?php echo $_SESSION['empl_id'];?>);
         }
         
        $('.remarks').click(function(e){
                e.preventDefault();
                remarks($(this).attr("pid"));
        });
            
            $('.clickTable').on('click',function(){
                var data = "action=getclickTableDetail&applicant_id="+$(this).attr("pid");
                        $.ajax({ 
                        type: 'POST',
                        url: 'dashboard.php',
                        cache: false,
                        data:data,
                        error: function(xhr) {
                            alert("System Error.");
                            issend = false;
                        },
                        success: function(data) {
                           var jsonObj = eval ("(" + data + ")");

                            var table = "";

                            if(jsonObj['aRemarks'] != null)
                             {
                                  //table ="<a href='applicant.php?action=edit&current_tab=followup&applicant_id=" + jsonObj['aRemarks']['applicant_id'][0] + "'><button type='button' class='btn btn-info' style= 'float:right'>Add Remarks</button></a><br><br>";
                           table = table + "<h4>Candidate (" + jsonObj['aRemarks']['applicant_name'][0] +") Remark</h4><table id='" + "applicant_remark_table' " + "class=" + "'table table-bordered table-hover'" + "><thead><tr> <th style = 'width:2%'>No</th><th style = 'width:5%'>Create By</th><th style = 'width:5%'>Assign</th>";
                               table = table + "<th style = 'width:5%'>Client</th><th style = 'width:7%'>Remark Type</th><th style = 'width:15%'>Comments</th><th style = 'width:5%'>Status</th><th style = 'width:5%'>Create Time & Date</th></tr></thead><tbody>";

                            var n = 1;
                        for(var i=0;i<jsonObj['aRemarks']['empl_name'].length;i++){
                        table = table + "<tr><td>" + "<a href='applicant.php?action=edit&current_tab=followup&applicant_id=" + jsonObj['aRemarks']['applicant_id'][i] + "&follow_id=" + jsonObj['aRemarks']['follow_id'][i] + "&edit=" + jsonObj['aRemarks']['edit'][i] + "'>" + n + "</a></td><td>" + jsonObj['aRemarks']['empl_name'][i] +"</td><td>" + jsonObj['aRemarks']['assign_to'][i] + "</td><td>" + jsonObj['aRemarks']['interview_company'][i] + "</td><td>" + jsonObj['aRemarks']['follow_type'][i] + "</td><td>" + jsonObj['aRemarks']['comments'][i] + "</td><td>" + jsonObj['aRemarks']['status'][i] + "</td><td>" + jsonObj['aRemarks']['time'][i] + " " + jsonObj['aRemarks']['date'][i] + "</td></tr>" 
                        n++;
                        }
                        table = table + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th><th style = 'width:5%'>Create By</th><th style = 'width:5%'>Assign</th>";
                               table = table + "<th style = 'width:5%'>Client</th><th style = 'width:7%'>Remark Type</th><th style = 'width:15%'>Comments</th><th style = 'width:5%'>Status</th><th style = 'width:5%'>Create Time & Date</th></tr></tfoot></table>";
                           }
                        else
                       {
                           table = table + "No have any remarks.";
                       }
                           $('#aRemarks_content').html(table);
                       
                           $(function () {
                            $('#applicant_remark_table').DataTable({
                              "paging": true,
                              "lengthChange": false,
                              "searching": true,
                              "ordering": true,
                              "info": true,
                              "autoWidth": false
                            });
                          });  
                        }		
                     });
                });
             
            $('.clientApplicant').on('click',function(){
                var data = "action=getClientApplicant&client_id="+$(this).attr("pid");
                $.ajax({ 
                    type: 'POST',
                    url: 'dashboard.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                    
                    var clientApplTable = "";

                    if(jsonObj['client_applicant']!=null){
                        clientApplTable = "<table class= 'table table-bordered table-hover' id='cappl_table'><thead><tr> <th style = 'width:2%'>No</th>";
                        clientApplTable = clientApplTable + "<th style = 'width:7%'>Candidate Name</th><th style = 'width:6%'>Mobile</th><th style = 'width:6%'>Email</th></tr></thead><tbody>";
                        var pn = 1;
                        for(var i=0;i<jsonObj['client_applicant']['applicant_name'].length;i++){
                        clientApplTable = clientApplTable + "<tr><td><a href='applicant.php?action=edit&applicant_id=" + jsonObj['client_applicant']['applicant_id'][i] + "'>" + pn + "</a></td><td>" + jsonObj['client_applicant']['applicant_name'][i] + "</td><td>" + jsonObj['client_applicant']['applicant_mobile'][i] + "</td><td>" + jsonObj['client_applicant']['applicant_email'][i] + "</td></tr>";
                                
                            pn++;
                        }
                        clientApplTable = clientApplTable + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th>";
                        clientApplTable = clientApplTable + "<th style = 'width:7%'>Candidate Name</th><th style = 'width:7%'>Mobile</th><th style = 'width:7%'>Email</th></tr></tfoot></table>";
                    }
                    else
                    {
                        clientApplTable = "<p>No have any applicant.</p>";
                    }
                    
                    $('#client_applicant_content').html(clientApplTable);
                    
                           $(function () {
                            $('#cappl_table').DataTable({
                              "paging": true,
                              "lengthChange": false,
                              "searching": true,
                              "ordering": true,
                              "info": true,
                              "autoWidth": false
                            });
                          }); 
                    }
                });
            });
             
             
            $('.leave').click(function(){
                var data = "action=createForm&leave_datefrom="+$('#dateStart').val()+"&leave_dateto="+$('#dateEnd').val(); 
                $.ajax({ 
                    type: 'POST',
                    url: 'leave.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       //var jsonObj = eval ("(" + data + ")");

                           var url = 'leave.php?action=createForm&leave_datefrom='+$('#dateStart').val()+"&leave_dateto="+$('#dateEnd').val();
                           window.location.href = url;

                       //issend = false;
                    }		
                 });
                 return false;
            });          
            
            $('.attendanceform').hide();
            $('.attendanceform1').hide();
            $('.attendanceform2').hide();
            $('.supervisor').hide();
            
            $('.attendance').click(function(){
                $('.dateEnd').hide();
                $('.attendanceform').show();
                $('.attendanceform1').show();
                $('.modal-title').text("Create Attendance");
                $('.LAbutton').hide();
                $('.supervisor').hide();
                document.getElementById('dateStartLabel').innerHTML = 'Date :';
            });

            $('.back').click(function(){
                $('.dateEnd').show();
                $('.attendanceform').hide();
                $('.attendanceform1').hide();
                $('.attendanceform2').hide();
                $('.modal-title').text("Apply Leave or Attendance");
                $('.LAbutton').show();
                $('.supervisor').hide();
                document.getElementById('dateStartLabel').innerHTML = 'Date Start :';
            });
            
            
            $('.saveattendance').click(function(){
                var data = "action=saveAttendance&applicant_id=<?php echo $_SESSION['empl_id'];?>&datefrom="+$('#dateStart').val()+"&dateto="+$('#dateEnd').val();
                    data = data + "&lunchhour="+$('#lunchHour').val()+"&overtimehour="+$('#overtimeHour').val()+"&timein="+$('#timeIn').val()+"&timeout="+$('#timeOut').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'dashboard.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");

                           var url = 'dashboard.php';
                           window.location.href = url;
                       if(jsonObj.status == 1){
                           var url = 'dashboard.php';
                           window.location.href = url;
                       }else{
                           alert("Duplicate Insert.");
                       }



                       issend = false;
                    }		
                 });
                 return false;
            });   

            $('.updateattendance').click(function(){
                var data = "action=updateAttendance&applicant_id=<?php echo $_SESSION['empl_id'];?>&datefrom="+$('#dateStart').val()+"&dateto="+$('#dateEnd').val()+"&attendance_remark="+$('#attendance_remark').val();
                    data = data + "&lunchhour="+$('#lunchHour').val()+"&overtimehour="+$('#overtimeHour').val()+"&timein="+$('#timeIn').val()+"&timeout="+$('#timeOut').val()+"&attendance_id="+$('#attendance_id').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'dashboard.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");

                           var url = 'dashboard.php';
                           window.location.href = url;
                       if(jsonObj.status == 1){
                           var url = 'dashboard.php';
                           window.location.href = url;
                       }else{
                           alert("Duplicate Insert.");
                       }

                       issend = false;
                    }		
                 });
                 return false;
            });   

            $('.applicant_follow').click(function(){
                
                //alert('asdsad');
                var url = $(this).attr("pid");
                window.location.href = url;                
            });
            
            $('.applicant_assign').click(function(){
                
                //alert('asdsad');
                var url = $(this).attr("pid");
                window.location.href = url;                
            });            
      });
    </script>
    
<script>
      $(function () {

        /* initialize the external events
         -----------------------------------------------------------------*/
        function ini_events(ele) {
          ele.each(function () {

            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
              title: $.trim($(this).text()) // use the element's text as the event title
            };

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);

            // make the event draggable using jQuery UI
//            $(this).draggable({
//              zIndex: 1070,
//              revert: true, // will cause the event to go back to its
//              revertDuration: 0  //  original position after the drag
//            });

          });
        }
        ini_events($('#external-events div.external-event'));

        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date();
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear();
        $('#calendar').fullCalendar({
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month'
          },
          navLinks: true,
          selectable: true,
          selectHelper: true,
          //editable: true,
          
          events: 'dashboard.php?action=getCalenderDetail',
			select: function(start, end) {
                        
                            var fromDate = moment(start).format('YYYY/MM/DD');

                            var currentDate = $('#calendar').fullCalendar('YYYY/MM/DD');
                            if(start.isBefore(currentDate)) {
                                $('#calendar').fullCalendar('unselect');
                                alert('Pass Date Cannot Edit')
                                return false;
                            }
                            
                            
                    
                             fromDate = fromDate.replace("/", "-");
                             fromDate = fromDate.replace("/", "-");
                            var endDate = moment(end).format('YYYY/MM/DD');
                             endDate = endDate.replace("/", "-");
                             endDate = endDate.replace("/", "-");
                             endDate.toString();

                            var tempyear = parseInt(endDate.substring(4,0));
                            var tempmonth = endDate.substring(7,5);
                            var tempday = endDate.substring(10,8);
                            
                            if(tempmonth == "01" && tempday == "01")
                            {
                                tempmonth = "12";
                                tempday = "31";
                                tempyear = parseInt(tempyear)-1;
                            }
                            else{
                                    if(tempyear%4 == 0){
                                        if(tempmonth == "03"){
                                            if(tempday == "01"){
                                                tempday = "28";
                                                tempmonth = parseInt(tempmonth)-1;
                                            }else 
                                                tempday = parseInt(tempday)-1 ;
                                        }
                                        else if(tempmonth == "02" || tempmonth == "04" || tempmonth == "06" || tempmonth == "08" || tempmonth == "09" || tempmonth == "11"){
                                            if(tempday == "01"){
                                                tempday = "31";
                                                tempmonth = parseInt(tempmonth)-1;
                                            }
                                            else{
                                                    tempday = parseInt(tempday)-1 ;
                                            }
                                        }
                                        else{
                                            if(tempday == "01"){
                                                tempday = "30";
                                                tempmonth = parseInt(tempmonth)-1;
                                            }else{
                                                tempday = parseInt(tempday)-1;
                                            }                                    
                                        }          
                                    }
                                    else{
                                        if(tempmonth == "03"){
                                            if(tempday == "01"){
                                                tempday = "28";
                                                tempmonth = parseInt(tempmonth)-1;
                                            }else 
                                                tempday = parseInt(tempday)-1 ;
                                        }
                                        else if(tempmonth == "02" || tempmonth == "04" || tempmonth == "06" || tempmonth == "08"|| tempmonth == "09" || tempmonth == "11"){
                                            if(tempday == "01"){
                                                tempday = "31";
                                                tempmonth = parseInt(tempmonth)-1;
                                            }
                                            else{
                                                    tempday = parseInt(tempday)-1 ;
                                            }
                                        }
                                        else{
                                            if(tempday == "01"){
                                                tempday = "30";
                                                tempmonth = parseInt(tempmonth)-1;
                                            }else{
                                                tempday = parseInt(tempday)-1 ;
                                            }                                    
                                        }                                
                                    }
                                }
                                if(tempday < 10){
                                    tempday = "0" + tempday;
                                }

                            var finishDate = tempyear + "-" + tempmonth + "-" + tempday;
                            
                            document.getElementById("dateStart").value =  moment(fromDate).format('DD-MMMM-YYYY');
                            document.getElementById("dateEnd").value =  moment(finishDate).format('DD-MMMM-YYYY');

                            document.getElementById('dateStartLabel').innerHTML = 'Date Start :';
                            $('.dateEnd').show();
                            $('.LAbutton').show();
                            $('.attendanceform').hide();
                            $('.attendanceform1').hide();
                            $('.attendanceform2').hide();
                            $('.supervisor').hide();
                            $('#myModal').modal('show');

				var eventData;
				if (title) {
					eventData = {
                                                //url: url,
						//title: title,
						start: start,
						end: end
					};
					$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
				}
				$('#calendar').fullCalendar('unselect');
			},     
                        
                        
                        
                eventRender: function (event, element) {
                    //element.attr('href', 'javascript:void(0);');
                    element.click(function () {
                        
                        var user = <?php echo $_SESSION['empl_group']; ?>;
                        var event_date = moment(event.start).format('DD-MMMM-YYYY');
                        document.getElementById("dateStart").value = event_date;
                        document.getElementById("dateEnd").value = event_date;             
                        document.getElementById("lunchHour").value = event.lunchHour;
                        document.getElementById("overtimeHour").value = event.overtimeHour; 
                        document.getElementById("timeIn").value = event.timeIn;
                        document.getElementById("timeOut").value = event.timeOut; 
                        document.getElementById("attendance_id").value = event.attendance_id; 
                        document.getElementById("attendance_remark").value = event.attendance_remark;
                        var today = $('#calendar').fullCalendar('getDate').format('MM/DD/YYYY');
                        var eventdate = moment(event.start).format('MM/DD/YYYY');
                        
                        document.getElementById('dateStartLabel').innerHTML = 'Date :';  
                        today = new Date(today);
                        eventdate = new Date(eventdate);


                        if(today.getUnixTime() <= eventdate.getUnixTime()){

                            $('.attendanceform').show();
                            $('.dateEnd').hide();
                            $('.attendanceform1').hide();
                            $('.attendanceform2').show();
                            $('.LAbutton').hide();
                            $('.supervisor').hide();
                            $('#myModal').modal('show'); 

                        }else{
                            if(user == "9"){
                                $('.attendanceform').show();
                                $('.dateEnd').hide();
                                $('.attendanceform1').hide();
                                $('.attendanceform2').show();
                                $('.LAbutton').hide();
                                $('.supervisor').show();
                                $('#myModal').modal('show');      
                            }
                            if(user == "5"){
                                $('.dateEnd').hide();
                                $('.attendanceform').show();
                                $('.attendanceform1').hide();
                                $('.attendanceform2').hide();
                                $('.LAbutton').hide();
                                $('.supervisor').hide();
                                $('#myModal').modal('show');
                            }
                            else{ 
                                $('.dateEnd').hide();
                                $('.attendanceform').hide();
                                $('.attendanceform1').hide();
                                $('.attendanceform2').hide();
                                $('.LAbutton').hide();
                                $('.supervisor').hide();
                                $('#myModal').modal('hide');
                            }
                        }
                    });
                },       
                
                dayRender: function (date, cell) {

                    var today = new Date();
                    var end = new Date();
                    end.setDate(today.getDate() - 1);
                    if (date < end) {
                        cell.css("background-color", "#c5c5c5");
//                        cell.css("background-color", "rgb(4, 51, 43)");
                    }

                },
          
          buttonText: {
            today: 'today',
            month: 'month',
            week: 'week',
            day: 'day'
          },
          //Random default events
//          events: [
//            {
//              title: 'Birthday Party',
//              start: new Date(y, m, d + 1, 19, 0),
//              end: new Date(y, m, d + 1, 22, 30),
//              allDay: false,
//              backgroundColor: "#00a65a", //Success (green)
//              borderColor: "#00a65a" //Success (green)
//            },
//            {
//              title: 'Click for Google',
//              start: new Date(y, m, 28),
//              end: new Date(y, m, 29),
//              url: 'http://google.com/',
//              backgroundColor: "#3c8dbc", //Primary (light-blue)
//              borderColor: "#3c8dbc" //Primary (light-blue)
//            }
//          ],

//          droppable: false, // this allows things to be dropped onto the calendar !!!
//          drop: function (date, allDay) { // this function is called when something is dropped
//
//            // retrieve the dropped element's stored Event Object
//            var originalEventObject = $(this).data('eventObject');
//
//            // we need to copy it, so that multiple events don't have a reference to the same object
//            var copiedEventObject = $.extend({}, originalEventObject);
//
//            // assign it the date that was reported
//            copiedEventObject.start = date;
//            copiedEventObject.allDay = allDay;
//            copiedEventObject.backgroundColor = $(this).css("background-color");
//            copiedEventObject.borderColor = $(this).css("border-color");
//
//            // render the event on the calendar
//            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
//            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
//
//            // is the "remove after drop" checkbox checked?
//            if ($('#drop-remove').is(':checked')) {
//              // if so, remove the element from the "Draggable Events" list
//              $(this).remove();
//            }
//
//          }
        });

//        /* ADDING EVENTS */
//        var currColor = "#3c8dbc"; //Red by default
//        //Color chooser button
//        var colorChooser = $("#color-chooser-btn");
//        $("#color-chooser > li > a").click(function (e) {
//          e.preventDefault();
//          //Save color
//          currColor = $(this).css("color");
//          //Add color effect to button
//          $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
//        });
//        $("#add-new-event").click(function (e) {
//          e.preventDefault();
//          //Get value and make sure it is not null
//          var val = $("#new-event").val();
//          if (val.length == 0) {
//            return;
//          }
//
//          //Create events
//          var event = $("<div />");
//          event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
//          event.html(val);
//          $('#external-events').prepend(event);
//
//          //Add draggable funtionality
//          ini_events(event);
//
//          //Remove event from text input
//          $("#new-event").val("");
//        });
      });
      Date.prototype.getUnixTime = function() { return this.getTime()/1000|0 };

    </script>    
       
    
        <script>
      $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
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
    public function getHrDashboard($action){
        global $mandatory;
        include_once 'class/Empl.php';
        $e = new Empl(); 
        
        //total Pending Leave
        $total_pending_leave = getDataCountBySql("db_leave e", " WHERE e.leave_approvalstatus = 'Pending' AND e.leave_status = '1' ");
        
        //total Pending Purchase Order
        $total_pending_PO = getDataCountBySql("db_order oe", " WHERE oe.order_prefix_type = 'PO' AND oe.order_status = '1' AND oe.order_id NOT IN (SELECT invoice_generate_from FROM db_invoice WHERE invoice_prefix_type = 'PI' AND invoice_status = '1' )");
        
        //total Product
        $total_product = getDataCountBySql("db_product", " WHERE product_status = '1'");
         
        //total Partner
        $total_partner = getDataCountBySql("db_partner", " WHERE partner_status = '1'");
    ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Zal HR</title>
    <?php
    include_once 'css.php';
    include_once 'js.php';
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

          <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo $total_pending_leave;?></h3>
                  <p>Pending Request Leave</p>
                </div>
                <div class="icon">
                  <i class="fa ion-android-friends"></i>
                </div>
                <a href="#" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo $total_pending_PO;?></h3>
                  <p>Pending Purchase Order</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $total_product;?></h3>
                  <p>Total Products</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bug"></i>
                </div>
                <a href="product.php?action=createForm" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo $total_partner;?></h3>
                  <p>Total Partners</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="partner.php?action=createForm" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div><!-- ./col -->
          </div>
        <div class="row">
            <div class="col-md-6">
                <?php //echo $this->getCalendar();?>
            </div><!-- /.col (LEFT) -->   
            <div class="col-md-6">


            </div><!-- /.col (RIGHT) -->
        </div><!-- /.row -->
    </section>
        </div><!-- /.container -->
      </div><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->


  </body>
</html>
        <?php
        
    }

    public function getAreaChart(){
    ?>
    <script>
      $(function () {   
          
        var areaChartCanvas = $("#areaChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var areaChart = new Chart(areaChartCanvas);

        var areaChartData = {
          labels: ["January", "February", "March", "April", "May", "June", "July"],
          datasets: [
            {
              label: "Electronics",
              fillColor: "rgba(210, 214, 222, 1)",
              strokeColor: "rgba(210, 214, 222, 1)",
              pointColor: "rgba(210, 214, 222, 1)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: [65, 59, 80, 81, 56, 55, 40]
            },
            {
              label: "Digital Goods",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: [28, 48, 40, 19, 86, 27, 90]
            }
          ]
        };

        var areaChartOptions = {
          //Boolean - If we should show the scale at all
          showScale: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: false,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - Whether the line is curved between points
          bezierCurve: true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension: 0.3,
          //Boolean - Whether to show a dot for each point
          pointDot: false,
          //Number - Radius of each point dot in pixels
          pointDotRadius: 4,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth: 1,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius: 20,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke: true,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth: 2,
          //Boolean - Whether to fill the dataset with a color
          datasetFill: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true
        };

        //Create the line chart
        areaChart.Line(areaChartData, areaChartOptions);
        
      });  
     </script>
        <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Area Chart</h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <div class="box-body">
                <div class="chart">
                  <canvas id="areaChart" style="height:250px"></canvas>
                </div>
              </div><!-- /.box-body -->
        </div><!-- /.box -->      
            
    <?php        
    }
    public function getDonutChart(){
    ?>
    <script>
      $(function () {   
          
        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [
          {
            value: 700,
            color: "#f56954",
            highlight: "#f56954",
            label: "Chrome"
          },
          {
            value: 500,
            color: "#00a65a",
            highlight: "#00a65a",
            label: "IE"
          },
          {
            value: 400,
            color: "#f39c12",
            highlight: "#f39c12",
            label: "FireFox"
          },
          {
            value: 600,
            color: "#00c0ef",
            highlight: "#00c0ef",
            label: "Safari"
          },
          {
            value: 300,
            color: "#3c8dbc",
            highlight: "#3c8dbc",
            label: "Opera"
          },
          {
            value: 100,
            color: "#d2d6de",
            highlight: "#d2d6de",
            label: "Navigator"
          }
        ];
        var pieOptions = {
          //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke: true,
          //String - The colour of each segment stroke
          segmentStrokeColor: "#fff",
          //Number - The width of each segment stroke
          segmentStrokeWidth: 2,
          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout: 50, // This is 0 for Pie charts
          //Number - Amount of animation steps
          animationSteps: 100,
          //String - Animation easing effect
          animationEasing: "easeOutBounce",
          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate: true,
          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale: false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
          // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
        
      });  
     </script>
        <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Donut Chart</h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <div class="box-body">
                  <canvas id="pieChart" style="height:250px"></canvas>
              </div><!-- /.box-body -->
        </div><!-- /.box -->      
            
    <?php        
    }
    public function getLineChart(){
    ?>
    <script>
      $(function () {   
          
        var lineChartData = {
          labels: ["January", "February", "March", "April", "May", "June", "July"],
          datasets: [
            {
              label: "Electronics",
              fillColor: "rgba(210, 214, 222, 1)",
              strokeColor: "rgba(210, 214, 222, 1)",
              pointColor: "rgba(210, 214, 222, 1)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: [65, 59, 80, 81, 56, 55, 40]
            },
            {
              label: "Digital Goods",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: [28, 48, 40, 19, 86, 27, 90]
            }
          ]
        }; 
        var lineChartOptions = {
          //Boolean - If we should show the scale at all
          showScale: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: false,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - Whether the line is curved between points
          bezierCurve: true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension: 0.3,
          //Boolean - Whether to show a dot for each point
          pointDot: false,
          //Number - Radius of each point dot in pixels
          pointDotRadius: 4,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth: 1,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius: 20,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke: true,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth: 2,
          //Boolean - Whether to fill the dataset with a color
          datasetFill: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true
        };

        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
        var lineChart = new Chart(lineChartCanvas);
        lineChartOptions.datasetFill = false;
        lineChart.Line(lineChartData, lineChartOptions);
        
      });  
     </script> 
        
        <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Line Chart</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="lineChart" style="height:250px"></canvas>
              </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->   
    <?php    
    }
    public function getBarChart(){
    ?>
    <script>
      $(function () {   
          
        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = {
          labels: ["January", "February", "March", "April", "May", "June", "July"],
          datasets: [
            {
              label: "Electronics",
              fillColor: "rgba(210, 214, 222, 1)",
              strokeColor: "rgba(210, 214, 222, 1)",
              pointColor: "rgba(210, 214, 222, 1)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: [65, 59, 80, 81, 56, 55, 40]
            },
            {
              label: "Digital Goods",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: [28, 48, 40, 19, 86, 27, 90]
            }
          ]
        };
        barChartData.datasets[1].fillColor = "#00a65a";
        barChartData.datasets[1].strokeColor = "#00a65a";
        barChartData.datasets[1].pointColor = "#00a65a";
        var barChartOptions = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: true,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - If there is a stroke on each bar
          barShowStroke: true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth: 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing: 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing: 1,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to make the chart responsive
          responsive: true,
          maintainAspectRatio: true
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
        
      });  
     </script> 
        <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Bar Chart</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="barChart" style="height:230px"></canvas>
              </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->   
    <?php    
    }
    public function getDivCountData($data){
        
        $html = <<<EOF
                
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box {$data['color']}">
                <div class="inner">
                  <h3>{$data['data']}</h3>
                  <p>{$data['title']}</p>
                </div>
                <div class="icon">
                  <i class="fa {$data['icon']}"></i>
                </div>
                <a href="{$data['link']}" class="small-box-footer">
                  More info <i class="fa fa-arrow-circle-right"></i>
                </a>
              </div>
            </div>     
EOF;
        
            return $html;
    }
    public function RenewalData(){
        $myDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+1 month" ) );
        $todaydate = date( "Y-m-d");
        $sql = "SELECT * FROM db_empl WHERE empl_pass_renewal BETWEEN '$todaydate' AND '$myDate' AND empl_status = 1";
        $query = mysql_query($sql);
        $data = "<table style = 'width:100%' class = 'table'  >" . 
                "<tr>".
                    "<th>Name</th>".
                    "<th>FIN/WP No</th>".
                    "<th>Type</th>".
                    "<th>Exp Date</th>".
                "</tr>";
        while($row = mysql_fetch_array($query)){
            $data .= "<tr>".
                        "<td><a href = 'empl.php?action=edit&empl_id={$row['empl_id']}' target = '_blank'>{$row['empl_name']}</a></td>".
                        "<td>{$row['empl_work_permit']}</td>".        
                        "<td>" . getDataCodeBySql("emplpass_code","db_emplpass"," WHERE emplpass_id = '{$row['empl_emplpass']}' ","") . "</td>".
                        "<td>" . format_date($row['empl_pass_renewal']) . "</td>".
                    "</tr>";
        }
        if(mysql_num_rows($query) <=0){
            $data .= "<tr>".
                        "<td colspan = '4' align = 'center' >No Record</td>".
                    "</tr>";
        }
        $data .= "</table>";
        return $data;
    }
    public function ProbationData(){
        $myDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+1 month" ) );
        $todaydate = date( "Y-m-d");
        $sql = "SELECT empl_id,empl_name,empl_joindate,empl_probation,empl_extentionprobation,DATE_ADD(empl_joindate, INTERVAL (empl_extentionprobation + empl_probation) MONTH) as probation_date
                 FROM db_empl 
                 WHERE DATE_ADD(empl_joindate, INTERVAL (empl_extentionprobation + empl_probation) MONTH) BETWEEN '$todaydate' AND '$myDate' AND empl_status = 1";
        $query = mysql_query($sql);
        $data = "<table style = 'width:100%' class = 'table'  >" . 
                "<tr>".
                    "<th>Name</th>".
                    "<th>Join Date</th>".
                    "<th>Prob Exp Date</th>".
                    "<th>Prob period</th>".
                "</tr>";
        while($row = mysql_fetch_array($query)){
            $empl_probation = $row['empl_probation'];
            if($row['empl_extentionprobation'] > 0){
                $empl_probation = $empl_probation . " + " . $row['empl_extentionprobation'];
            }
            $data .= "<tr>".
                        "<td><a href = 'empl.php?action=edit&empl_id={$row['empl_id']}' target = '_blank'>{$row['empl_name']}</a></td>".
                        "<td>" . format_date($row['empl_joindate']) . "</td>".        
                        "<td>" . format_date($row['probation_date']) . "</td>".
                        "<td>" . $empl_probation . "</td>".       
                    "</tr>";
        }
        if(mysql_num_rows($query) <=0){
            $data .= "<tr>".
                        "<td colspan = '4' align = 'center' >No Record</td>".
                    "</tr>";
        }
        $data .= "</table>";
        return $data;
    }
    public function BirthdayData(){

        $sql = "SELECT * FROM db_empl WHERE month(empl_birthday) BETWEEN '" . date("m") . "' AND '" . date("m", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+1 month" ) ) . "' AND empl_status = 1";
        $query = mysql_query($sql);
        $data = "<table style = 'width:100%' class = 'table'  >" . 
                "<tr>".
                    "<th>Name</th>".
                    "<th>Date Of Birth</th>".
                "</tr>";
        while($row = mysql_fetch_array($query)){
            $empl_probation = $row['empl_probation'];
            if($row['empl_extentionprobation'] > 0){
                $empl_probation = $empl_probation . " + " . $row['empl_extentionprobation'];
            }
            $data .= "<tr>".
                        "<td><a href = 'empl.php?action=edit&empl_id={$row['empl_id']}' target = '_blank'>{$row['empl_name']}</a></td>".     
                        "<td>" . format_date($row['empl_birthday']) . "</td>".  
                    "</tr>";
        }
        if(mysql_num_rows($query) <=0){
            $data .= "<tr>".
                        "<td colspan = '4' align = 'center' >No Record</td>".
                    "</tr>";
        }
        $data .= "</table>";
        return $data;
    }
    public function tableListing($data){
        if($data['display'] == 'none'){
            $icon = "fa fa-plus";
            $collapsed_box = " collapsed-box";
        }else{
            $icon = "fa fa-minus";
            $collapsed_box = "";
        }
        $html = <<<EOF
            <div class="{$data['col']} ">
                <div class="box {$data['color']} box-solid $collapsed_box">
                    <div class="box-header with-border">
                      <h3 class="box-title">{$data['title']}</h3>
                      <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="$icon"></i></button>
                      </div>
                    </div>
                    <div class="box-body" style="display: {$data['display']};">
                      {$data['data']}
                    </div>
                </div>
            </div>       
                
EOF;
        return $html;
    }
    public function LeaveTable(){
        $todaydate = date( "Y-m-d");
        $sql = "SELECT *
                FROM db_leave 
                WHERE leave_datefrom >= '$todaydate' AND leave_status = 1 AND leave_empl_id = '{$_SESSION['empl_id']}'";
        $query = mysql_query($sql);
        $data = "<table style = 'width:100%' class = 'table'  >" . 
                "<tr>".
                    "<th>Type</th>".
                    "<th>Leave Start Date</th>".
                    "<th>Leave End Date</th>".
                    "<th>Days</th>".
                    "<th>Status</th>".
                    "<th>&nbsp;</th>".
                "</tr>";
        while($row = mysql_fetch_array($query)){
            if($row['leave_approvalstatus'] == 'Approved'){
                $style = "style = 'color:green;font-weight:bold' ";
            }else if($row['leave_approvalstatus'] == 'Rejected'){
                $style = "style = 'color:red;font-weight:bold' ";
            }else{
                $style = "style = 'width:10%' ";
            }
            $data .= "<tr>".
                        "<td>" . getDataCodeBySql("leavetype_code","db_leavetype"," WHERE leavetype_id = '{$row['leave_type']}' ","") . "</td>".
                        "<td>" . format_date($row['leave_datefrom']) . "</td>".
                        "<td>" . format_date($row['leave_dateto']) . "</td>".
                        "<td>" . $row['leave_total_day'] . "</td>".
                        "<td $style>" . $row['leave_approvalstatus'] . "</td>".
                        "<td style = 'vertical-align:bottom;font-size:1.5em;width:30px' ><a href = 'leave.php?action=edit&leave_id={$row['leave_id']}' ><i class='fa fa-fw fa-edit'></i></a></td>".
                    "</tr>";
        }
        if(mysql_num_rows($query) <=0){
            $data .= "<tr>".
                        "<td colspan = '5' align = 'center' >No Record</td>".
                    "</tr>";
        }
        $data .= "</table>";
        return $data;
    }
    public function ClaimsTable(){
        $month_start = system_date_monthstart;
        $month_end = system_date_monthend;
        
        $sql = "SELECT *
                FROM db_claims
                WHERE claims_date BETWEEN '$month_start' AND '$month_end' AND claims_status = 1 AND claims_empl_id = '{$_SESSION['empl_id']}'";
        $query = mysql_query($sql);
        $data = "<table style = 'width:100%' class = 'table'  >" . 
                "<tr>".
                    "<th>Date</th>".
                    "<th>Remark</th>".
                    "<th>Amount</th>".
                    "<th>Status</th>".
                    "<th>&nbsp;</th>".
                "</tr>";
        while($row = mysql_fetch_array($query)){
            if($row['claims_approvalstatus'] == 'Approved'){
                $style = "style = 'color:green;font-weight:bold;width:10%' ";
            }else if($row['claims_approvalstatus'] == 'Rejected'){
                $style = "style = 'color:red;font-weight:bold;width:10%' ";
            }else{
                $style = "style = 'width:10%' ";
            }
            $data .= "<tr>".
                        "<td style = 'width:10%' >" . format_date($row['claims_date']) . "</td>".
                        "<td style = 'width:30%'>" . nl2br($row['claims_remark']) . "</td>".
                        "<td style = 'width:10%'>" . num_format($row['claims_amount']) . "</td>".
                        "<td $style>" . $row['claims_approvalstatus'] . "</td>".
                        "<td style = 'vertical-align:bottom;font-size:1.5em;width:30px;width:10%' ><a href = 'claims.php?action=edit&claims_id={$row['claims_id']}' ><i class='fa fa-fw fa-edit'></i></a></td>".
                    "</tr>";
        }
        if(mysql_num_rows($query) <=0){
            $data .= "<tr>".
                        "<td colspan = '5' align = 'center' >No Record</td>".
                    "</tr>";
        }
        $data .= "</table>";
        return $data;
    }
    public function BalanceLeaveTable(){
        $year_start = system_date_yearstart;
        $year_end = system_date_yearend;
        $year = date("Y");   
        $sql = "SELECT lt.*,el.emplleave_days,el.emplleave_id,el.emplleave_entitled
                FROM db_leavetype lt
                LEFT JOIN db_emplleave el ON el.emplleave_leavetype = lt.leavetype_id AND el.emplleave_empl = '{$_SESSION['empl_id']}'
                WHERE lt.leavetype_status = 1 AND el.emplleave_disabled = 0 AND el.emplleave_year = '$year' AND leavetype_id <> '10'
                ORDER BY lt.leavetype_seqno,lt.leavetype_code";

        $query = mysql_query($sql);
        $data = "<table style = 'width:100%' class = 'table'  >" . 
                "<tr>".
                    "<th>Leave Name</th>".
                    "<th>Entitled</th>".
                    "<th>Taken</th>".
                    "<th>Pending</th>".
                    "<th>Available Balance</th>".
                "</tr>";
        while($row = mysql_fetch_array($query)){

//leave type id 10 = "Urgent Leave";
//leave type id 1 = "Annual Leave";

// Customer mention urgent leave same with annual leave , calculate together at 2016-12-07 (Zen)
if($row['leavetype_id'] == 1){
   $wheresub = " AND leave_type IN ('1','10') "; 
}else{
   $wheresub = " AND leave_type = '{$row['leavetype_id']}' ";
}

            $taken = getDataCodeBySql("SUM(leave_total_day)",'db_leave'," WHERE leave_approvalstatus = 'Approved'  AND leave_empl_id = '{$_SESSION['empl_id']}' AND LEFT(leave_datefrom,4) = '$year' $wheresub");
            $pending = getDataCodeBySql("SUM(leave_total_day)",'db_leave'," WHERE leave_approvalstatus = 'Pending'  AND leave_empl_id = '{$_SESSION['empl_id']}' AND LEFT(leave_datefrom,4) = '$year' $wheresub");
            $data .= "<tr>".
                        "<td style = 'width:15%' >" . $row['leavetype_code'] . "</td>".
                        "<td style = 'width:10%'>" . $row['emplleave_entitled'] . "</td>".
                        "<td style = 'width:10%'>" . num_format($taken) . "</td>".
                        "<td style = 'width:10%'>" . num_format($pending) . "</td>".
                        "<td style = 'width:15%' >" . num_format($row['emplleave_entitled'] - $taken) . "</td>".
                    "</tr>";
        }
        if(mysql_num_rows($query) <=0){
            $data .= "<tr>".
                        "<td colspan = '5' align = 'center' >No Record</td>".
                    "</tr>";
        }
        $data .= "</table>";
        return $data;
    }
    public function getPayslipAlert(){
        
        $wherestring = " AND payline_empl_id = '" . escape($_SESSION['empl_id']) . "'";
        $sql = "SELECT py.payroll_startdate,py.payroll_id
                FROM db_payline pl
                INNER JOIN db_payroll py ON py.payroll_id = pl.payline_payroll_id
                WHERE py.payroll_status = '1' AND pl.payline_selfview = 0 $wherestring 
                ORDER BY py.payroll_startdate LIMIT 0,1";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
          
    ?>
    <div class="pad margin no-print">

        <div class="alert alert-warning alert-dismissable"  style = 'color:black !important'>
            <button type="button" class="close close_alert_payslip" pid = "<?php echo $row['payroll_id'];?>" eid = "<?php echo $_SESSION['empl_id'];?>" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-warning" style = 'color:black !important'></i> Alert!</h4>
           
            Your <b><?php echo date("M", strtotime($row['payroll_startdate']));?></b> of payslip is ready for viewing and print. <a class = 'astatus' data-toggle="modal" data-target="#sstatusModal" style = 'color:#3c8dbc !important' href = '#' payroll_id = '<?php echo $row['payroll_id'];?>' empl_id = '<?php echo $_SESSION['empl_id'];?>'> View </a>
         </div>
    </div>
    <?php
        }
    }
    public function getChangePasswordForm(){
    ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Employee Management</title>
    <?php
    include_once 'css.php';
    
    ?>    
    <style>
    .hide{
    display:none;
    }
    </style>
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

        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">Change Password</h3>

              </div>
                
                <form id = 'empl_form' class="form-horizontal" action = 'dashboard.php?action=changepassword' method = "POST" enctype="multipart/form-data">
                    <input type ='hidden' name = 'current_tab' id = 'current_tab' value = "<?php echo $this->current_tab?>"/>
                  <div class="box-body">
                        <div class="form-group">
                              <label for="empl_login_password" class="col-sm-2 control-label" >New Password <?php echo $mandatory;?></label>
                              <div class="col-sm-3">
                                <input type="password" class="form-control" id="empl_login_password" name="empl_login_password" value = "<?php echo $this->empl_login_password;?>" placeholder="Password">
                              </div>
                        </div>
                        <div class="form-group">
                              <label for="empl_login_password_cm" class="col-sm-2 control-label" >Confirm New Password <?php echo $mandatory;?></label>
                              <div class="col-sm-3">
                                <input type="password" class="form-control" id="empl_login_password_cm" name="empl_login_password_cm" value = "<?php echo $this->empl_login_password;?>" placeholder="Confirm Password">
                              </div>
                        </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Back</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "changepassword" name = "action"/>
                    <button type = "submit" class="btn btn-info">Submit</button>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
          </section><!-- /.content -->
        </div><!-- /.container -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';
    
    ?>
    <script>
    $(document).ready(function() {
        $('#empl_login_password').focus();
        $("#empl_form").validate({
                  rules: 
                  {

                      empl_login_password:
                      {
                        required: true,
                      },
                      empl_login_password_cm:
                      {
                        required: true,
                        minlength : 5,
                        equalTo : "#empl_login_password"
                      }  
                  },
                  messages:
                  {
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
});

    </script>
  </body>
</html>
    <?php    
    }
    public function ChangePassword(){
        $this->empl_login_password = md5("@#~x?\$" . $this->empl_login_password . "?\$");

        $table_field = array('empl_login_password');
        $table_value = array($this->empl_login_password);
        $remark = "Change Password Employeeself.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_empl','empl_id',$remark,$_SESSION['empl_id'])){
           return false;
        }else{
           return true;
        }
    }
    public function getApplicantRemarks(){
        global $notification_desc; 
//            $sql = "select * from db_followup where insertby = '$this->empl_id'";
        
            $this->string = escape($_REQUEST['string']);
            if($this->string == ""){
                $whereString = "";
            }else{
                $whereString = $this->string;
            }
        
            $sql = "select left(f.insertDateTime, 10) as date, right(f.insertDateTime,8) as time, f.interview_company, f.follow_type, f.comments ,a.applicant_id, a.applicant_name, e.empl_name, e.empl_id, f.follow_id from db_followup f inner join db_applicant a inner join db_empl e on f.applfollow_id = a.applicant_id and e.empl_id = f.insertBy where f.insertby = '$this->empl_id' AND f.fol_status = '0' $whereString ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
            $query = mysql_query($sql);
              $i = 0;
            while($row = mysql_fetch_array($query)){
                $data['follow_id'][$i] = $row['follow_id'];
                $data['applicant_id'][$i] = $row['applicant_id'];
                $data['assign_by'][$i] = $row['assign_by'];
                $data['insertDateTime'][$i] = $row['insertDateTime'];
                $data['follow_type'][$i] = $notification_desc[$row['follow_type']];
                            
                $partner_id = $row['interview_company'];
                $sql2 = "select partner_name from db_partner where partner_id = '$partner_id'";
                $query2 = mysql_query($sql2);
                $row2 = mysql_fetch_array($query2);
                if($row2['partner_name'] != "" || $row2['partner_name'] != null){
                    $data ['interview_company'][$i] = $row2['partner_name'];
                }else
                {
                    $data ['interview_company'][$i] = " - ";
                }
                $data['comments'][$i] = preg_replace('#<script(.*?)>(.*?)</script>#is', '',htmlspecialchars_decode($row['comments']));
                $data['empl_id'][$i] = $row['empl_id'];
                $data['empl_name'][$i] = $row['empl_name'];
                $data['applicant_name'][$i] = $row['applicant_name'];
                $data['date'][$i] = format_date($row['date']);
                $data['time'][$i] = $row['time'];
        $i++;
        }
        return $data;  
    }
    public function getPartnerRemarks(){
//        $sql = "select * from db_partnerfollow where insertby = '$this->empl_id'";
          $sql = "select left(p.insertDateTime, 10) as date, right(p.insertDateTime,8) as time, p.pfollow_description, pt.partner_name, e.empl_name, pt.partner_id, p.pfollow_id from db_partnerfollow p inner join db_partner pt inner join db_empl e on p.partner_id = pt.partner_id and e.empl_id = p.insertBy where p.insertBy = '$this->empl_id' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
        $query = mysql_query($sql);
        
        $i = 0;
        while($row = mysql_fetch_array($query)){
            $data['partner_id'][$i] = $row['partner_id'];
            $data['partner_name'][$i] = $row['partner_name'];
            $data['insertDateTime'][$i] = $row['insertDateTime'];
            $data['empl_name'][$i] = $row['empl_name'];
            $data['date'][$i] = format_date($row['date']);
            $data['pfollow_id'][$i] = $row['pfollow_id'];
            $data['time'][$i] = $row['time'];
            $data['pfollow_description'][$i] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', htmlspecialchars_decode($row['pfollow_description']));

        $i++;
        }
        return $data;
    }
    public function getActiveApplicant(){
        $sql = "SELECT a.*, f.follow_id, left(f.insertDateTime, 10) as date, right(f.insertDateTime,8) as time FROM db_applicant a INNER JOIN db_followup f ON a.applicant_id = f.applfollow_id WHERE f.follow_type = '3' AND f.assign_to = '$this->empl_id' AND f.fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
          //$sql = "select left(p.insertDateTime, 10) as date, right(p.insertDateTime,8) as time, p.pfollow_description, pt.partner_name, e.empl_name, pt.partner_id, p.pfollow_id from db_partnerfollow p inner join db_partner pt inner join db_empl e on p.partner_id = pt.partner_id and e.empl_id = p.insertBy where p.insertBy = '$this->empl_id' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
        $query = mysql_query($sql);
        
        $i = 0;
        while($row = mysql_fetch_array($query)){
            $sql3 = "SELECT * FROM db_dashbroad_display WHERE display_type = 'active candidate' AND display_parent_id = '$row[follow_id]' AND display_empl_id = '$_SESSION[empl_id]'";
            $query3 = mysql_query($sql3);
            $row3 = mysql_num_rows($query3);
            if($row3 == 0){
                $data['applicant_id'][$i] = $row['applicant_id'];
                $data['applicant_name'][$i] = $row['applicant_name'];
                $data['applicant_mobile'][$i] = $row['applicant_mobile'];
                $data['applicant_email'][$i] = $row['applicant_email'];
                $data['follow_id'][$i] = $row['follow_id']; 
                $i++;
            }
        }
        return $data;
    }
    public function getConsultantDashboard2(){
             ?>
                <div class="box-body table-responsive">
                  <table id="applicant_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Name</th>
                        <th style = 'width:10%'>Email</th>
                        <th style = 'width:10%'>Mobile</th>
<!--                        <th style = 'width:10%'>Address</th>
                        <th style = 'width:10%'>Manager By</th>
                        <th style = 'width:10%'>Create By</th>-->
                        <th style = 'width:10%'>Create Date</th>
                        <th style = 'width:20%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php                           
                      $sql = "SELECT applicant.*, left(applicant.insertDateTime,10) as date, right(applicant.insertDateTime, 8) as time ,gp.group_code,dp.department_code,outl.outl_code
                              FROM db_applicant applicant 
                              INNER JOIN db_group gp ON gp.group_id = applicant.applicant_group
                              LEFT JOIN db_department dp ON dp.department_id = applicant.applicant_department
                              LEFT JOIN db_outl outl ON outl.outl_id = applicant.applicant_outlet
                              WHERE applicant.applicant_id > 0 AND applicant.applicant_status = '1'
                              ORDER BY applicant.applicant_seqno,applicant.applicant_name";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['applicant_name'];?></td>
                            <td><?php echo $row['applicant_email'];?></td>
                            <td><?php echo $row['applicant_mobile'];?></td>                         
<!--                            <td><?php echo $row['applicant_street'];?></td>
                            <td>
                            <?php 
                            $appl_id = $row['applicant_id'];
                            $sql2 = "SELECT empl_name from db_empl inner join db_followup on assign_to = empl_id where applfollow_id = '$appl_id' AND fol_status = '0' group by empl_name";
                            $query2 = mysql_query($sql2);
                            while($row2 = mysql_fetch_array($query2)){
                                echo $row2['empl_name'];
                                ?><br>
                                <?php
                            }
                            ?>                            
                            </td>
                            <?php 
                            $insert_id = $row['insertBy'];
                            $sql3 = "select empl_name from db_empl where empl_id = '$insert_id'";
                            $query3 = mysql_query($sql3);
                            $row3 = mysql_fetch_array($query3);
                            ?>
                            <td><?php echo $row3['empl_name'];?></td>-->
                            <td><?php echo format_date($row['insertDateTime']) . " " . $row['time']?>
                                
                            <?php 
                            $app_id = $row['applicant_id'];
                            $sql4 = "SELECT * FROM db_followup f inner join db_resume r on f.applfollow_id = r.resume_appl_id inner join db_experience e on r.resume_appl_id = e.exp_appl_id inner join db_family a on e.exp_appl_id = a.applicant_family_id inner join db_applicant p on a.applicant_family_id = p.applicant_id where p.applicant_id = '$app_id' AND f.fol_status = '0'";
                            $query4 = mysql_query($sql4);
                            $row4 = mysql_fetch_array($query4);
                            ?>                             
                                
                            <input type="hidden" value = "<?php echo print_r($row4);?>"> 
                            </td>

                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'view')){
                                ?>
                                <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row['applicant_id'];?>"><button type="button" id="Btn" class="btn btn-primary btn-warning" >Add Remarks</button></a>
                                <button type="button" id="Btn" class="btn btn-primary btn-warning remarks" style="background-color: #8BC34A; border-color: #4CAF50" pid="<?php echo $row['applicant_id'];?>" >Remarks</button>
                                <?php }?>
                                <?php
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'applicant.php?action=edit&applicant_id=<?php echo $row['applicant_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('applicant.php?action=delete&applicant_id=<?php echo $row['applicant_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:10%'>Name</th>
                        <th style = 'width:10%'>Email</th>
                        <th style = 'width:10%'>Mobile</th>
<!--                        <th style = 'width:15%'>Address</th>
                        <th style = 'width:10%'>Manager By</th>
                        <th style = 'width:10%'>Create By</th>-->
                        <th style = 'width:10%'>Create Date</th>
                        <th style = 'width:20%'></th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
    <?php }
    public function getCalender(){
            ?>
       <!-- Main content -->
        <section class="content">
          <div class="row">
              
            <?php if($_SESSION['empl_group'] == "5" || $_SESSION['empl_group'] == "9"){ ?>
                <div class="col-md-9">
            <?php } else { ?>
                <div class="col-md-11">
            <?php } ?>        
              <div class="box box-success">
                <div class="box-body no-padding">
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section> <!-- /.content --> 
            <?php
    }
    public function getCalenderForm(){
        ?>
        


  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog"  style="width:50%">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background-color: #5cb85c;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Apply Leave or Attendance</h4>
        </div>
        <div class="modal-body" style="text-align: center; height:400px; border-style:none; overflow-y: hidden;">

            <label for="dateStart" id="dateStartLabel" class="col-sm-3 control-label" style="margin-top: 8px;">Date Start :</label>
            <input type="text" class="form-control datepicker" id="dateStart" name="dateStart" value = "" placeholder="Start Date" style="width:70%"><br>

            <div class = "dateEnd">
                <label for="dateEnd" class="col-sm-3 control-label" style="margin-top: 8px;">Date End :</label>
                <input type="text" class="form-control datepicker" id="dateEnd" name="dateEnd" value = "" placeholder="Start Date" style="width:70%"><br>
            </div>
            
            <div class = "attendanceform">

                <label for="timeIn" class="col-sm-3 control-label" style="margin-top: 8px;">Time In :</label>
                    <div class="col-sm-8 input-group bootstrap-timepicker">
                        <input type="text" class="form-control timepicker" id="timeIn" name="timeIn" value = "<?php echo $this->timeIn;?>" placeholder="Time In">
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                <br>
                <label for="timeOut" class="col-sm-3 control-label" style="margin-top: 8px;">Time Out :</label>
                    <div class="col-sm-8 input-group bootstrap-timepicker">
                        <input type="text" class="form-control timepicker" id="timeOut" name="timeOut" value = "<?php echo $this->timeOut;?>" placeholder="Time">
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                   <br>                 
                
                
                <label for="lunchHour" class="col-sm-3 control-label" style="margin-top: 8px;">Lunch Hour :</label>
                <input type="text" class="form-control" id="lunchHour" name="lunchHour" value = "" placeholder="Eg : 1" style="width:70%"><br>


                <label for="overtimeHour" class="col-sm-3 control-label" style="margin-top: 8px;">Overtime Hour :</label>
                <input type="text" class="form-control" id="overtimeHour" name="overtimeHour" value = "" placeholder="Eg : 2" style="width:70%"><br>  
                <input type="hidden" class="form-control" id="attendance_id" name="attendance_id" value = "">  
            </div>

            <div class ="supervisor">
                <label for="attendance_remark" class="col-sm-3 control-label" style="margin-top: 8px;">Remark :</label>
                <input type="text" class="form-control" id="attendance_remark" name="attendance_remark" value = "" placeholder="Remark" style="width:70%"><br>                
            </div>

            <div class="attendanceform1">
                <button type="button" class="btn btn-default back" style="background-color: #5cb85c; width:120px; margin-right: 20px; font-size: larger">Back</button>
                <button type="button" class="btn btn-default saveattendance" style="background-color: #5cb85c; width:120px; font-size: larger">Save</button>   
            </div><br>
            
            <div class="attendanceform2">
                <button type="button" class="btn btn-default back" style="background-color: #5cb85c; width:120px; margin-right: 20px; font-size: larger">Back</button>
                <button type="button" class="btn btn-default updateattendance" style="background-color: #5cb85c; width:120px; font-size: larger">Update</button>   
            </div><br>            
            
            <div class ="LAbutton">
              <button type="button" class="btn btn-default leave" data-dismiss="modal" style="background-color: #5cb85c; width:120px; margin-right: 20px; font-size: larger">Leave</button>
              <button type="button" class="btn btn-default attendance" style="background-color: #5cb85c; width:120px; font-size: larger">Attendance</button>

            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
       
        
        
  <?php
    }
    public function saveAttendance(){
        $this->empl_id = $_SESSION['empl_id'];
        $this->attendance_time_start = format_date_database(escape($_REQUEST['datefrom']));
//        $this->attendance_time_end = format_date_database(escape($_REQUEST['dateto']));
        $this->lunchHour = escape($_REQUEST['lunchhour']);
        $this->overtimeHour = escape($_REQUEST['overtimehour']);
        $this->timeIn = escape($_REQUEST['timein']);
        $this->timeOut = escape($_REQUEST['timeout']);

            $sql = "SELECT * FROM db_attendance WHERE attendance_empl = '$_SESSION[empl_id]' AND attendance_date_start = '$this->attendance_time_start'";
            $query = mysql_query($sql);
            $row = mysql_num_rows($query);
            if ($row > 0){
                return false;
            }else{
                $table_field = array('attendance_id','attendance_empl','attendance_date_start','attendance_date_end','attendance_remark',
                                     'attendance_lunch_hour','attendance_ot_hour','attendance_timein','attendance_timeout','attendance_status');
                $table_value = array('',$this->empl_id ,$this->attendance_time_start,$this->attendance_time_start,"Attendance",
                                     $this->lunchHour, $this->overtimeHour, $this->timeIn, $this->timeOut ,1);
                $remark = "Create Attendance.";
                if(!$this->save->SaveData($table_field,$table_value,'db_attendance','attendance_id',$remark)){
                    return false;
                }
                else{
                    return true;
                }     
        }   
    }
    public function updateAttendance(){
        $this->empl_id = $_SESSION['empl_id'];
        $this->attendance_time_start = format_date_database(escape($_REQUEST['datefrom']));
        $this->attendance_time_end = format_date_database(escape($_REQUEST['dateto']));
        $this->lunchHour = escape($_REQUEST['lunchhour']);
        $this->overtimeHour = escape($_REQUEST['overtimehour']);
        $this->attendance_remark = escape($_REQUEST['attendance_remark']);
        $this->timeIn = escape($_REQUEST['timein']);
        $this->timeOut = escape($_REQUEST['timeout']);
        $this->attendance_id = escape($_REQUEST['attendance_id']);
        
            $sql = "SELECT * FROM db_attendance WHERE attendance_empl = '$_SESSION[empl_id]' AND attendance_date_start = '$this->attendance_time_start'";
            $query = mysql_query($sql);
            $row = mysql_num_rows($query);
            if ($row > 0){
                return false;
            }else{
                $table_field = array('attendance_date_start','attendance_date_end','attendance_remark',
                                     'attendance_lunch_hour','attendance_ot_hour','attendance_timein','attendance_timeout','attendance_remark');
                $table_value = array($this->attendance_time_start,$this->attendance_time_end,'Attendance',
                                     $this->lunchHour, $this->overtimeHour, $this->timeIn, $this->timeOut,$this->attendance_remark);
            
                $remark = "Update Attendance";
                if(!$this->save->UpdateData($table_field,$table_value,'db_attendance','attendance_id',$remark,$this->attendance_id,"")){
                   return false;
                }else{
                   return true;
                }
            }
    }
    public function getCalendarDetail(){
        $startDate = $_GET['start'];
        $endDate = $_GET['end'];
        $id = $_SESSION['empl_id'];
        
        if($_SESSION['empl_group'] == "5" || $_SESSION['empl_group'] == "9")
        {
                if($_SESSION['empl_group'] == "5"){
                    $sql = "SELECT * FROM db_attendance a INNER JOIN db_applicant p ON p.applicant_id = a.attendance_empl WHERE (a.attendance_date_start BETWEEN '$startDate' AND '$endDate') AND p.applicant_id = '$id'";
                }
                if($_SESSION['empl_group'] == "9"){
                    $sql = "SELECT * FROM db_attendance a INNER JOIN db_applicant p ON p.applicant_id = a.attendance_empl WHERE (a.attendance_date_start BETWEEN '$startDate' AND '$endDate') AND (p.applicant_leave_approved1 = '$id' OR p.applicant_leave_approved2 = '$id' OR p.applicant_leave_approved3 = '$id')";
                }
                    $data = array();
                    $i =0;
                    $query = mysql_query($sql);
                    while($row = mysql_fetch_array($query)){
                        $data[$i]['attendance_id']= $row['attendance_id'];
                        $data[$i]['attendance_empl'] = $row['applicant_name'];
                        $data[$i]['start'] = $row['attendance_date_start'];
                        $data[$i]['end'] = $row['attendance_date_start'];

                        $data[$i]['lunchHour']= $row['attendance_lunch_hour'];
                        $data[$i]['overtimeHour'] = $row['attendance_ot_hour'];
                        $data[$i]['attendance_remark'] = $row['attendance_remark'];
                        $data[$i]['timeIn'] = $row['attendance_timein'];
                        $data[$i]['timeOut'] = $row['attendance_timeout'];

                        $data[$i]['title'] = $row['attendance_remark']." - ". $row['applicant_name'];
                        $data[$i]['backgroundColor'] = "#0088ff";
                        $data[$i]['borderColor'] = "#000000";
                        $i++;
                    }

                if($_SESSION['empl_group'] == "5"){
                    $sql2 = "SELECT * FROM db_leave l INNER JOIN db_applicant p ON p.applicant_id = l.leave_empl_id INNER JOIN db_leavetype lt ON l.leave_type = lt.leavetype_id WHERE (l.leave_datefrom BETWEEN '$startDate' AND '$endDate') AND p.applicant_id = '$id' AND l.leave_empl_type = '1' AND (leave_approvalstatus = 'pending' OR leave_approvalstatus = 'approved')";
                }
                if($_SESSION['empl_group'] == "9"){
                    $sql2 = "SELECT * FROM db_leave l INNER JOIN db_applicant p ON p.applicant_id = l.leave_empl_id INNER JOIN db_leavetype lt ON l.leave_type = lt.leavetype_id WHERE (l.leave_datefrom BETWEEN '$startDate' AND '$endDate') AND (p.applicant_leave_approved1 = '$id' OR p.applicant_leave_approved2 = '$id' OR p.applicant_leave_approved3 = '$id') AND l.leave_empl_type = '1' AND (leave_approvalstatus = 'pending' OR leave_approvalstatus = 'approved')";
                }
                    $query2 = mysql_query($sql2);
                    while($row2 = mysql_fetch_array($query2)){
                        $data[$i]['leave_id']= $row2['leave_id'];
                        $data[$i]['leave_empl'] = $row2['applicant_name'];
                        $data[$i]['start'] = $row2['leave_datefrom'];
                        $data[$i]['end'] = $row2['leave_dateto']. " 24:00";
                        $data[$i]['title'] = $row2['leavetype_code']." - ". $row2['applicant_name'];
                        if($row2['leave_approvalstatus'] == "Approved"){
                            $data[$i]['backgroundColor'] = "#0a5d0a";
                            $data[$i]['borderColor'] = "#000000";
                        }
                        else 
                        {
                            $data[$i]['backgroundColor'] = "#FF0000";
                            $data[$i]['borderColor'] = "#000000";
                        }
                        $data[$i]['url'] = "leave.php?action=edit&leave_id=".$row2['leave_id'];
                        $i++;
                    }
        }
        else{
            if($_SESSION['empl_group'] == "4"){
                $sql = "SELECT n.*, LEFT(n.insertDateTime,10) AS date, e.*, a.applicant_name FROM db_notification n INNER JOIN db_empl e ON e.empl_id = n.insertBy INNER JOIN db_followup f ON n.noti_parent_id = f.follow_id INNER JOIN db_applicant a ON f.applfollow_id = a.applicant_id WHERE (n.insertBy = '$id' or e.empl_manager = '$id') AND n.noti_type = '0' AND LEFT(n.noti_url,9) = 'applicant' AND (LEFT(n.insertDateTime,10) BETWEEN '$startDate' AND '$endDate') AND f.fol_status = '0'";
                    $data = array();
                    $i =0;
                    $query = mysql_query($sql);
                    while($row = mysql_fetch_array($query)){
                        $data[$i]['empl_name'] = $row['empl_name'];
                        $data[$i]['parent_id'] = $row['parent_id'];
                        $data[$i]['title'] = $row['empl_name'] ." - " . $row['noti_desc'] ." (" . $row['applicant_name'] . ")";
                        $data[$i]['start'] = $row['date'];
                        $data[$i]['end'] = $row['date']. " 24:00";                        
                        $data[$i]['url'] = $row['noti_url'];
                        
                        $data[$i]['backgroundColor'] = "#0088ff";
                        $data[$i]['borderColor'] = "#000000";
                        $i++;                    
                    }
                    $sql2 = "SELECT * FROM db_leave l INNER JOIN db_empl e ON e.empl_id = l.leave_empl_id INNER JOIN db_leavetype lt ON l.leave_type = lt.leavetype_id WHERE (e.empl_id = '$id' or e.empl_manager = '$id') AND l.leave_empl_type = '0' AND (leave_approvalstatus = 'pending' OR leave_approvalstatus = 'approved') AND (leave_datefrom BETWEEN '$startDate' AND '$endDate')";

                    $query2 = mysql_query($sql2);
                    while($row2 = mysql_fetch_array($query2)){
                        $data[$i]['leave_id']= $row2['leave_id'];
                        $data[$i]['leave_empl'] = $row2['applicant_name'];
                        $data[$i]['start'] = $row2['leave_datefrom'];
                        $data[$i]['end'] = $row2['leave_dateto']. " 24:00";
                        $data[$i]['title'] = $row2['leavetype_code']." - ". $row2['empl_name'];
                        if($row2['leave_approvalstatus'] == "Approved"){
                            $data[$i]['backgroundColor'] = "#0a5d0a";
                            $data[$i]['borderColor'] = "#000000";
                        }
                        else 
                        {
                            $data[$i]['backgroundColor'] = "#FF0000";
                            $data[$i]['borderColor'] = "#000000";
                        }
                        $data[$i]['url'] = "leave.php?action=edit&leave_id=".$row2['leave_id'];
                        $i++;
                    }

                    $sql3 = "SELECT * FROM db_followup f INNER JOIN db_applicant a ON f.applfollow_id = a.applicant_id INNER JOIN db_partner p ON f.interview_company = p.partner_id INNER JOIN db_empl e ON f.insertBy = e.empl_id WHERE (f.insertBy = '$id' or e.empl_manager = '$id') AND f.follow_type = '2' AND (f.interview_date BETWEEN '$startDate' AND '$endDate') AND f.fol_status = '0'";
                    $query3 = mysql_query($sql3);
                    while($row3 = mysql_fetch_array($query3)){
                        $data[$i]['applicant_name'] = $row3['applicant_name'];
                        $data[$i]['applicant_id'] = $row3['applicant_id'];
                        $data[$i]['title'] = "Candidate (" . $row3['applicant_name'] .") interview - Company (" . $row3['partner_name'] . ") at Time ". $row3['interview_time'];
                        $data[$i]['start'] = $row3['interview_date'];
                        $data[$i]['end'] = $row3['interview_date']. " 24:00";                        
                        $data[$i]['url'] = "applicant.php?action=edit&current_tab=followup&applicant_id=".$row3['applicant_id']."&follow_id=".$row3['follow_id']."&edit=1";
                        
                        $data[$i]['backgroundColor'] = "#a27600";
                        $data[$i]['borderColor'] = "#000000";
                        $i++;                    
                    }
                }
            if($_SESSION['empl_group'] == "8"){
                $sql = "SELECT n.*, LEFT(n.insertDateTime,10) AS date, e.*, a.applicant_name FROM db_notification n INNER JOIN db_empl e ON e.empl_id = n.insertBy INNER JOIN db_followup f ON n.noti_parent_id = f.follow_id INNER JOIN db_applicant a ON f.applfollow_id = a.applicant_id WHERE (n.insertBy = '$id' or e.empl_manager = '$id') AND n.noti_type = '0' AND LEFT(n.noti_url,9) = 'applicant' AND (LEFT(n.insertDateTime,10) BETWEEN '$startDate' AND '$endDate') AND f.fol_status = '0'";
                    $data = array();
                    $i =0;
                    $query = mysql_query($sql);
                    while($row = mysql_fetch_array($query)){
                        $data[$i]['empl_name'] = $row['empl_name'];
                        $data[$i]['parent_id'] = $row['parent_id'];
                        $data[$i]['title'] = $row['empl_name'] ." - " . $row['noti_desc'] ." (" . $row['applicant_name'] . ")";
                        $data[$i]['start'] = $row['date'];
                        $data[$i]['end'] = $row['date']. " 24:00";                        
                        $data[$i]['url'] = $row['noti_url'];
                        
                        $data[$i]['backgroundColor'] = "#0088ff";
                        $data[$i]['borderColor'] = "#000000";
                        $i++;                    
                    }
                    $sql2 = "SELECT * FROM db_leave l INNER JOIN db_empl e ON e.empl_id = l.leave_empl_id INNER JOIN db_leavetype lt ON l.leave_type = lt.leavetype_id WHERE (e.empl_id = '$id' or e.empl_manager = '$id') AND l.leave_empl_type = '0' AND (leave_approvalstatus = 'pending' OR leave_approvalstatus = 'approved') AND (leave_datefrom BETWEEN '$startDate' AND '$endDate')";

                    $query2 = mysql_query($sql2);
                    while($row2 = mysql_fetch_array($query2)){
                        $data[$i]['leave_id']= $row2['leave_id'];
                        $data[$i]['leave_empl'] = $row2['applicant_name'];
                        $data[$i]['start'] = $row2['leave_datefrom'];
                        $data[$i]['end'] = $row2['leave_dateto']. " 24:00";
                        $data[$i]['title'] = $row2['leavetype_code']." - ". $row2['empl_name'];
                        if($row2['leave_approvalstatus'] == "Approved"){
                            $data[$i]['backgroundColor'] = "#0a5d0a";
                            $data[$i]['borderColor'] = "#000000";
                        }
                        else 
                        {
                            $data[$i]['backgroundColor'] = "#FF0000";
                            $data[$i]['borderColor'] = "#000000";
                        }
                        $data[$i]['url'] = "leave.php?action=edit&leave_id=".$row2['leave_id'];
                        $i++;
                    }

                    $sql3 = "SELECT * FROM db_followup f INNER JOIN db_applicant a ON f.applfollow_id = a.applicant_id INNER JOIN db_partner p ON f.interview_company = p.partner_id WHERE f.follow_type = '2' AND (f.interview_date BETWEEN '$startDate' AND '$endDate') AND f.fol_status = '0' AND f.insertBy = '$_SESSION[empl_id]'";
                    $query3 = mysql_query($sql3);
                    while($row3 = mysql_fetch_array($query3)){
                        $data[$i]['applicant_name'] = $row3['applicant_name'];
                        $data[$i]['applicant_id'] = $row3['applicant_id'];
                        $data[$i]['title'] = "Candidate (" . $row3['applicant_name'] .") interview - Company (" . $row3['partner_name'] . ") at Time ". $row3['interview_time'];
                        $data[$i]['start'] = $row3['interview_date'];
                        $data[$i]['end'] = $row3['interview_date']. " 24:00";                        
                        $data[$i]['url'] = "applicant.php?action=edit&current_tab=followup&applicant_id=".$row3['applicant_id']."&follow_id=".$row3['follow_id']."&edit=1";
                        
                        $data[$i]['backgroundColor'] = "#a27600";
                        $data[$i]['borderColor'] = "#000000";
                        $i++;                    
                    }
                }
            if($_SESSION['empl_group'] == "1" || $_SESSION['empl_group'] == "-1"){
                $sql = "SELECT n.*, LEFT(n.insertDateTime,10) AS date, e.*, a.applicant_name FROM db_notification n INNER JOIN db_empl e ON e.empl_id = n.insertBy INNER JOIN db_followup f ON n.noti_parent_id = f.follow_id INNER JOIN db_applicant a ON f.applfollow_id = a.applicant_id WHERE n.noti_type = '0' AND LEFT(n.noti_url,9) = 'applicant' AND (LEFT(n.insertDateTime,10) BETWEEN '$startDate' AND '$endDate') AND f.fol_status = '0'";
                    $data = array();
                    $i =0;
                    $query = mysql_query($sql);
                    while($row = mysql_fetch_array($query)){
                        $data[$i]['empl_name'] = $row['empl_name'];
                        $data[$i]['parent_id'] = $row['parent_id'];
                        $data[$i]['title'] = $row['empl_name'] ." - " . $row['noti_desc'] ." (" . $row['applicant_name'] . ")";
                        $data[$i]['start'] = $row['date'];
                        $data[$i]['end'] = $row['date']. " 24:00";                        
                        $data[$i]['url'] = $row['noti_url'];
                        
                        $data[$i]['backgroundColor'] = "#0088ff";
                        $data[$i]['borderColor'] = "#000000";
                        $i++;                    
                    }
                    $sql2 = "SELECT * FROM db_leave l INNER JOIN db_empl e ON e.empl_id = l.leave_empl_id INNER JOIN db_leavetype lt ON l.leave_type = lt.leavetype_id WHERE l.leave_empl_type = '0' AND (leave_approvalstatus = 'pending' OR leave_approvalstatus = 'approved') AND (leave_datefrom BETWEEN '$startDate' AND '$endDate')";

                    $query2 = mysql_query($sql2);
                    while($row2 = mysql_fetch_array($query2)){
                        $data[$i]['leave_id']= $row2['leave_id'];
                        $data[$i]['leave_empl'] = $row2['applicant_name'];
                        $data[$i]['start'] = $row2['leave_datefrom'];
                        $data[$i]['end'] = $row2['leave_dateto']. " 24:00";
                        $data[$i]['title'] = $row2['leavetype_code']." - ". $row2['empl_name'];
                        if($row2['leave_approvalstatus'] == "Approved"){
                            $data[$i]['backgroundColor'] = "#0a5d0a";
                            $data[$i]['borderColor'] = "#000000";
                        }
                        else 
                        {
                            $data[$i]['backgroundColor'] = "#FF0000";
                            $data[$i]['borderColor'] = "#000000";
                        }
                        $data[$i]['url'] = "leave.php?action=edit&leave_id=".$row2['leave_id'];
                        $i++;
                    }

                    $sql3 = "SELECT * FROM db_followup f INNER JOIN db_applicant a ON f.applfollow_id = a.applicant_id INNER JOIN db_partner p ON f.interview_company = p.partner_id INNER JOIN db_empl e ON f.insertBy = e.empl_id WHERE f.follow_type = '2' AND (f.interview_date BETWEEN '$startDate' AND '$endDate') AND f.fol_status = '0'";
                    $query3 = mysql_query($sql3);
                    while($row3 = mysql_fetch_array($query3)){
                        $data[$i]['applicant_name'] = $row3['applicant_name'];
                        $data[$i]['applicant_id'] = $row3['applicant_id'];
                        $data[$i]['title'] = "Candidate (" . $row3['applicant_name'] .") interview - Company (" . $row3['partner_name'] . ") at Time ". $row3['interview_time'];
                        $data[$i]['start'] = $row3['interview_date'];
                        $data[$i]['end'] = $row3['interview_date']. " 24:00";                        
                        $data[$i]['url'] = "applicant.php?action=edit&current_tab=followup&applicant_id=".$row3['applicant_id']."&follow_id=".$row3['follow_id']."&edit=1";
                        
                        $data[$i]['backgroundColor'] = "#a27600";
                        $data[$i]['borderColor'] = "#000000";
                        $i++;                    
                    }
                }                
            if($_SESSION['empl_group'] == "7"){
                $sql = "SELECT p.*, LEFT(p.insertDateTime,10) AS date, e.* FROM db_payroll p INNER JOIN db_empl e ON e.empl_id = p.insertBy WHERE payroll_client <1 AND (LEFT(p.insertDateTime,10) BETWEEN '$startDate' AND '$endDate') AND p.payroll_status = '1' AND p.payroll_isapproved= '1'";
                    $data = array();
                    $i =0;
                    $query = mysql_query($sql);
                    while($row = mysql_fetch_array($query)){
                        $sql2 = "SELECT SUM(payline_netpay) AS total, COUNT(*) AS line FROM db_payline WHERE payline_payroll_id = '$row[payroll_id]'";
                        $query2 = mysql_query($sql2);
                        $row2 = mysql_fetch_array($query2);
                        if($row['payroll_department'] == "0"){
                            $department = "All Department";
                        }
                        else{ 
                            $sql3 = "SELECT department_code FROM db_department WHERE department_id = '$row[payroll_department]'";
                            $query3 = mysql_query($sql3);
                            $row3 = mysql_fetch_array($query3);
                            $department = $row3['department_code'];
                        }
                        $data[$i]['title'] = "Create Payroll ". $department ." ".$row2['line'] ." Employee ( $" . $row2['total'] ." ) ( " . $row['payroll_salary_date'] . " ) - ".$row['empl_name'];
                        $data[$i]['start'] = $row['date'];
                        $data[$i]['end'] = $row['date']. " 24:00";                        
                        $data[$i]['url'] = "payroll.php?action=edit&payroll_id=".$row['payroll_id'];
                        
                        $data[$i]['backgroundColor'] = "#0088ff";
                        $data[$i]['borderColor'] = "#000000";
                        $i++;                    
                    }
                $sql = "SELECT p.*, LEFT(p.insertDateTime,10) AS date, e.* FROM db_payroll p INNER JOIN db_empl e ON e.empl_id = p.insertBy WHERE payroll_client > 0 AND (LEFT(p.insertDateTime,10) BETWEEN '$startDate' AND '$endDate') AND p.payroll_status = '1' AND p.payroll_isapproved= '1'";

                    $query = mysql_query($sql);
                    while($row = mysql_fetch_array($query)){
                        $sql2 = "SELECT SUM(payline_netpay) AS total, COUNT(*) AS line FROM db_payline WHERE payline_payroll_id = '$row[payroll_id]'";
                        $query2 = mysql_query($sql2);
                        $row2 = mysql_fetch_array($query2);
                        if($row['payroll_department'] == "0"){
                            $sql3 = "SELECT partner_name FROM db_partner WHERE partner_id = '$row[payroll_client]'";
                            $query3 = mysql_query($sql3);
                            $row3 = mysql_fetch_array($query3);
                            $department = "All Department";
                        }
                        else{ 
                            $sql3 = "SELECT timeshift_department, partner_name FROM db_timeshift INNER JOIN db_partner ON partner_id = timeshift_company WHERE timeshift_id = '$row[payroll_department]'";
                            $query3 = mysql_query($sql3);
                            $row3 = mysql_fetch_array($query3);
                            $department = $row3['timeshift_department'];
                        }
                        $data[$i]['title'] = "Create Candidate Payroll ". $department ." ".$row2['line'] ." Candidate ( ". $row3['partner_name'] ." ) ( $" . $row2['total'] ." ) ( " . $row['payroll_salary_date'] . " ) - ".$row['empl_name'];
                        $data[$i]['start'] = $row['date'];
                        $data[$i]['end'] = $row['date']. " 24:00";                         
                            $data[$i]['backgroundColor'] = "#0a5d0a";
                            $data[$i]['borderColor'] = "#000000";

                        $data[$i]['url'] = "applicantpayroll.php?action=edit&payroll_id=".$row['payroll_id'];
                        $i++;
                    }
                $sql = "SELECT i.*,e.*,p.partner_name, LEFT(i.insertDateTime,10) AS date, e.* FROM db_invoice i INNER JOIN db_empl e ON e.empl_id = i.insertBy INNER JOIN db_partner p ON p.partner_id = i.invoice_client WHERE invoice_status = '0' AND (LEFT(i.insertDateTime,10) BETWEEN '$startDate' AND '$endDate')";

                    $query = mysql_query($sql);
                    while($row = mysql_fetch_array($query)){
                        $data[$i]['title'] = "Create Invoice ". $row['invoice_no'] . " ( ". $row['partner_name'] ." ) ( $" . $row['invoice_amount'] ." ) ( " . $row['invoice_date'] . " ) - ".$row['empl_name'];
                        $data[$i]['start'] = $row['date'];
                        $data[$i]['end'] = $row['date']. " 24:00";                         
                            $data[$i]['backgroundColor'] = "#a27600";
                            $data[$i]['borderColor'] = "#000000";

                        $data[$i]['url'] = "invoice.php?action=edit&invoice_id=".$row['invoice_id']."&edit=1";
                        $i++;
                    }                    
                }                
        }      
        return $data;
    }
    public function getPayrollForm(){
        ?>
            <div class="col-md-9">
                    
                <!-- USERS LIST -->
                  <div class="box box-success">
                    <div class="box-header with-border">
                        <?php   
                          $today = Date('Y-m-d');
                          $month = Date('M-Y');
                        ?>
                      <h3 class="box-title">New Candidate assign to Client for this month <?php echo "( ".$month . " )"?></h3>
                      <div class="box-tools pull-right">      
                     <?php                       
                      
                      $firstDay = date('Y-m-01', strtotime($today));
                      $lastDay = date("Y-m-t", strtotime($today));
                      
                      $sql = "SELECT * FROM db_followup WHERE follow_type = '0' AND LEFT(insertDateTime,10) BETWEEN '$firstDay' AND '$lastDay' AND fol_assign_expiry_date >= '$firstDay' AND fol_available_date <= '$lastDay' AND fol_approved = 'Y' AND fol_payroll_empl = '$_SESSION[empl_id]'";
                      $query = mysql_query($sql);
                      $row = mysql_num_rows($query);

                      ?>
                          
                        <span class="label label-success"><?php echo $row; ?> Candidate assign to Client</span>
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                    
                    <div class="box-body table-responsive">
                  <table id="otherManager_table" class="table table-bordered table-hover table-cursor">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Name</th>
                        <th style = 'width:10%'>Client</th>
                        <th style = 'width:10%'>Department</th>
                        <th style = 'width:10%'>Position</th>
                        <th style = 'width:10%'>Salary</th>
                        <th style = 'width:10%'>Admin Fee</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php                           
                      $sql = "SELECT f.*,a.applicant_id, a.applicant_name, p.partner_name FROM db_followup f INNER JOIN db_applicant a ON f.applfollow_id = a.applicant_id INNER JOIN db_partner p ON p.partner_id = f.interview_company WHERE f.follow_type = '0' AND LEFT(f.insertDateTime,10) BETWEEN '$firstDay' AND '$lastDay' AND f.fol_assign_expiry_date >= '$firstDay' AND f.fol_available_date <= '$lastDay' AND f.fol_approved = 'Y' AND f.fol_payroll_empl = '$_SESSION[empl_id]'";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr class='applicant_assign' pid='applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row['applicant_id'];?>&follow_id=<?php echo $row['follow_id'];?>&edit=1' > 
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['applicant_name'];?></td>
                            <td><?php echo $row['partner_name'];?></td>
                            <td>
                                <?php
                                $sql3 = "SELECT timeshift_department FROM db_timeshift where timeshift_id = '$row[fol_department]'";
                                $query3 = mysql_query($sql3);
                                $row3 = mysql_fetch_array($query3);
                                echo $row3['timeshift_department'];
                                ?>
                            </td>     
                            <td>
                               <?php echo $row['fol_position_offer']?>
                            </td>                       
                            <td><?php echo "$ ". $row['fol_offer_salary'] ?>
                            </td>
                            <td><?php echo "$ ". $row['fol_admin_fee'] ?>
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
                        <th style = 'width:10%'>Client</th>
                        <th style = 'width:10%'>Department</th>
                        <th style = 'width:10%'>Position</th>
                        <th style = 'width:10%'>Salary</th>
                        <th style = 'width:10%'>Admin Fee</th>
                      </tr>
                    </tfoot>
                  </table>

                </div><!-- /.box-body -->

                    
                    <div class="box-footer text-center">
                    </div><!-- /.box-footer -->
                  </div><!--/.box -->
            </div>  
  
      
      
  
            <div class="col-md-9">
                    
                <!-- USERS LIST -->
                  <div class="box box-success">
                    <div class="box-header with-border">
                        <?php   
                          $today = Date('Y-m-d');
                          $month = Date('Y-m');
                        ?>
                      <h3 class="box-title">Total Candidate</h3>
                      <div class="box-tools pull-right">      
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                    
                    <div class="box-body table-responsive">
                  <table id="interview_table" class="table table-bordered table-hover table-cursor">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Name</th>
                        <th style = 'width:10%'>Client</th>
                        <th style = 'width:10%'>Department</th>
                        <th style = 'width:10%'>Position</th>
                        <th style = 'width:10%'>Salary</th>
                        <th style = 'width:10%'>Admin Fee</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php                           
                      $sql = "SELECT f.*,a.applicant_id, a.applicant_name, p.partner_name FROM db_followup f INNER JOIN db_applicant a ON f.applfollow_id = a.applicant_id INNER JOIN db_partner p ON p.partner_id = f.interview_company WHERE f.follow_type = '0' AND f.fol_assign_expiry_date >= '$today' AND LEFT(f.fol_available_date,7) <= '$today'  AND f.fol_payroll_empl = '$_SESSION[empl_id]' AND f.fol_approved = 'Y'";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr class='applicant_assign' pid='applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row['applicant_id'];?>&follow_id=<?php echo $row['follow_id'];?>&edit=1' > 
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['applicant_name'];?></td>
                            <td><?php echo $row['partner_name'];?></td>
                            <td>
                                <?php
                                $sql3 = "SELECT timeshift_department FROM db_timeshift where timeshift_id = '$row[fol_department]'";
                                $query3 = mysql_query($sql3);
                                $row3 = mysql_fetch_array($query3);
                                echo $row3['timeshift_department'];
                                ?>
                            </td>     
                            <td>
                               <?php echo $row['fol_position_offer']?>
                            </td>                       
                            <td><?php echo "$ ". $row['fol_offer_salary'] ?>
                            </td>
                            <td><?php echo "$ ". $row['fol_admin_fee'] ?>
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
                        <th style = 'width:10%'>Client</th>
                        <th style = 'width:10%'>Department</th>
                        <th style = 'width:10%'>Position</th>
                        <th style = 'width:10%'>Salary</th>
                        <th style = 'width:10%'>Admin Fee</th>
                      </tr>
                    </tfoot>
                  </table>

                </div><!-- /.box-body -->

                    
                    <div class="box-footer text-center">
                    </div><!-- /.box-footer -->
                  </div><!--/.box -->
            </div>   
  
              
              <div class="col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo  "Total Client";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body">
                          <table id="client_table" class="table table-bordered table-hover table-cursor">
                            <thead>
                              <tr>
                                <th style = 'width:3%'>No</th>
                                <th style = 'width:13%'>Client Name</th>
                                <th style = 'width:4%'>No. Candidate Assigned</th>
                                <th style = 'width:2%'></th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $sql = "SELECT * FROM db_partner p INNER JOIN db_empl e ON p.partner_sales_person = e.empl_id WHERE (p.partner_sales_person != '$_SESSION[empl_id]' AND e.empl_manager != '$_SESSION[empl_id]') AND partner_dashboard_display = '0'";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                    $sql3 = "SELECT * FROM db_dashbroad_display WHERE display_type = 'partner table' AND display_parent_id = '$row[partner_id]' AND display_empl_id = '$_SESSION[empl_id]'";
                                    $query3 = mysql_query($sql3);
                                    $row3 = mysql_num_rows($query3);
                                    if($row3 == 0){
                                        ?>
                                        <tr class="clientApplicant" pid="<?php echo $row['partner_id'];?>">
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $row['partner_name'];?></td>
                                            <td>
                                                <?php
                                                    $sql2 = "SELECT a.* FROM db_followup f INNER JOIN db_partner p ON f.interview_company = p.partner_id INNER JOIN db_applicant a ON a.applicant_id = f.applfollow_id WHERE f.follow_type = '0' AND f.fol_status = '0' AND f.interview_company = '$row[partner_id]' AND f.fol_approved = 'Y'";
                                                    $query2 = mysql_query($sql2);
                                                    $row2 = mysql_num_rows($query2);
                                                    echo $row2;
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-client delete" pid="<?php echo $row['partner_id']?>"><i class="fa fa-dw fa-close"></i></button>
        <!--                                        <button type="button" class="btn btn-info btn-client"><i class="fa fa-dw fa-check"></i></button>-->
                                            </td>
                                        </tr>                               
                                    <?php $i++;
                                    }
                                } ?>                                
                            </tbody>
                                
                        </table>       
                    </div>
            </div>       
          </div>
  
                  <div class="col-md-7" >
                    <div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title">Client Candidate</h3>
                          <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                          </div>
                        </div><!-- /.box-header -->

                        <div class="box-body">   
                                    <div class="col-xs-12">
                                      <div class="box" style="padding: 10px; height:305px; overflow-y: scroll;border-top:0px;">
                                        <div class="box-header" style="background-color: #00a65a; color:#fff;">
                                          <h3 class="box-title">Candidate Detail</h3>
                                          <div class="box-tools">          
                                          </div>
                                        </div><!-- /.box-header -->
                                        <div class="box-body table-responsive no-padding" id="client_applicant_content">
                                        </div><!-- /.box-body -->
                                      </div><!-- /.box -->
                                    </div>
                        </div>
                    </div>
              </div>
  
  
  
  
  
        <?php 
    }
    public function getAdminDashboard(){
        ?>
        <div class = "col-md-4">
  
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Administrator";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body no-padding">
                        <table class="table table-bordered text-center">
                            <tr>
                                <th><h4>Manager List</h4></th>
                            </tr>                

                            <?php 
                            $id = $_SESSION['empl_id'];
                            $sql = "Select * from db_empl where empl_group = '4'";
                            $query = mysql_query($sql);
                            while($row = mysql_fetch_array($query)){
                            ?>
                                <tr>
                                  <td>
                                      <!--<button type="button" pid="<?php echo $row['empl_id'];?>" id="Btn" class="btn btn-block btn-success btn-lg remarks"><?php echo $row['empl_name']. " - " .$row['empl_code'];?></button>-->
                                  
                                      <div class="btn-group">
                                      <button type="button" pid="<?php echo $row['empl_id'];?>" class="btn btn-success btn-lg remarks" style="width:250px; height:40px;"><?php echo $row['empl_name']. " - " .$row['empl_code'];?></button>
                                      <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" style="height:40px;">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                      </button>
                                      <ul class="dropdown-menu" role="menu" style="background-color:#2dc17d; width:283px;">
                                          <li style="text-align:center">Consultant List</li>
                                      <?php $sql2 = "SELECT * FROM db_empl WHERE empl_manager = '$row[empl_id]'";   
                                            $query2 = mysql_query($sql2);
                                            while($row2 = mysql_fetch_array($query2)){?>
                                            <li><a href="#" class ="remarks" pid="<?php echo $row2['empl_id']?>"><?php echo $row2[empl_name];?></a></li>
                                       <?php }
                                       ?> 
                                      </ul>
                                    </div>                                
                                  </td>
                                </tr>
                            <?php } ?>
                        </table>       
                    </div>
            </div>                
            
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo " Total Resume";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body">
                          <table id="applicant_table" class="table table-bordered table-hover table-cursor">
                            <thead>
                              <tr>
                                <th style = 'width:3%'>No</th>
                                <th style = 'width:13%'>Candidate Name</th>
                                <th style = 'width:5%'>Job Assign</th>
                              </tr>
                            </thead>
                            <tbody>             
                                <?php 
                                $i = 1;
                                $id = $_SESSION['empl_id'];
                                $sql = "Select a.* from db_applicant a INNER JOIN db_followup f ON a.applicant_id = f.applfollow_id where (f.follow_type = '3' OR f.follow_type = '4') and f.fol_status = '0'";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                ?>
                                <tr class="clickTable" pid="<?php echo $row['applicant_id'];?>">
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $row['applicant_name'];?></td>
                                    <td>
                                        <?php
                                        $today = date("Y-m-d");
                                        $sql11 = "SELECT * FROM db_followup WHERE follow_type = '0' AND fol_assign_expiry_date >= '$today' AND fol_status = '0' AND applfollow_id = '$row[applicant_id]'"; 
                                        //echo $sql11;
                                        $query11 = mysql_query($sql11);
                                        if($row11 = mysql_fetch_array($query11)){ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>&follow_id=<?php echo $row11['follow_id'];?>&edit=1"><button type='button' style='background-color: #3c5bf7; border-color: #0c4da0; width:85px;' class='btn btn-primary btn-warning'>Assigned</button></a>
                                        <?php }else{ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>"><button type='button' style='background-color: #009688; border-color: #007368;width:85px;' class='btn btn-primary btn-warning'>Unassigned</button></a>
                                        <?php } 
                                        ?>
                                    </td>                                        
                                </tr>
                                
                            <?php $i++;
                                } ?>
                            </tbody>
                        </table>       
                    </div>
            </div>   
            
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo  "Total Client";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body">
                          <table id="client_table" class="table table-bordered table-hover table-cursor">
                            <thead>
                              <tr>
                                <th style = 'width:3%'>No</th>
                                <th style = 'width:13%'>Client Name</th>
                                <th style = 'width:4%'>No. Candidate Assigned</th>
                                <th style = 'width:2%'></th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $sql = "SELECT * FROM db_partner p INNER JOIN db_empl e ON p.partner_sales_person = e.empl_id WHERE (p.partner_sales_person != '$_SESSION[empl_id]' AND e.empl_manager != '$_SESSION[empl_id]') AND partner_dashboard_display = '0'";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                    $sql3 = "SELECT * FROM db_dashbroad_display WHERE display_type = 'partner table' AND display_parent_id = '$row[partner_id]' AND display_empl_id = '$_SESSION[empl_id]'";
                                    $query3 = mysql_query($sql3);
                                    $row3 = mysql_num_rows($query3);
                                    if($row3 == 0){
                                        ?>
                                        <tr class="clientApplicant" pid="<?php echo $row['partner_id'];?>">
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $row['partner_name'];?></td>
                                            <td>
                                                <?php
                                                    $sql2 = "SELECT a.* FROM db_followup f INNER JOIN db_partner p ON f.interview_company = p.partner_id INNER JOIN db_applicant a ON a.applicant_id = f.applfollow_id WHERE f.follow_type = '0' AND f.fol_status = '0' AND f.interview_company = '$row[partner_id]' AND f.fol_approved = 'Y'";
                                                    $query2 = mysql_query($sql2);
                                                    $row2 = mysql_num_rows($query2);
                                                    echo $row2;
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-client delete" pid="<?php echo $row['partner_id']?>"><i class="fa fa-dw fa-close"></i></button>
        <!--                                        <button type="button" class="btn btn-info btn-client"><i class="fa fa-dw fa-check"></i></button>-->
                                            </td>
                                        </tr>                               
                                    <?php $i++;
                                    }
                                } ?>                                
                            </tbody>
                                
                        </table>       
                    </div>
            </div>             
            
            
        </div>

        <?php 
    }
    public function getAdminDashboardBackup(){
                $sql6 = "select empl_id, empl_name from db_empl where empl_group = '4'";
                $query6 = mysql_query($sql6);
                while($row6 = mysql_fetch_array($query6)){
                    ?>
                                        
                    <div class="box box-success" style="border-top-color:#00a7d0">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo "Manager : " . $row6['empl_name'];?></h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div><!-- /.box-header -->    
            
                        <div class="box-body no-padding">
                        <?php 
                        $id = $row6['empl_id'];
                        $sql = "Select * from db_empl where empl_group = '8' and empl_manager = '$id'";
                        $query = mysql_query($sql);
                        while($row = mysql_fetch_array($query)){
                        ?>


                        <div class="col-md-6">
                          <!-- Widget: user widget style 1 -->
                          <div class="box box-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-aqua-active">
                              <h3 class="widget-user-username"><?php echo $row['empl_name']; ?></h3>
                              <h5 class="widget-user-desc"><?php echo "Consultant" ?></h5>
                              <button type="button" id="Btn" class="btn btn-primary btn-warning remarks" style="background-color: #8BC34A; border-color: #4CAF50; margin-top:-50px; float:right;" pid="<?php echo $row['empl_id'];?>" >Remarks</button>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle" src="dist/images/empl/<?php echo  $row['empl_id'];?>.png" alt="User Avatar">
                            </div>
                            <div class="box-footer">
                              <div class="row">
                                <div class="col-sm-4 border-right">
                                  <div class="description-block">
                                      <h5 class="description-header">
                                          <?php
                                          $id2 = $row['empl_id'];
                                          $sql2 = "SELECT * FROM db_followup where insertBy ='$id2' AND fol_status = '0'";
                                          $query2 = mysql_query($sql2);
                                          $row2 = mysql_num_rows($query2);
                                          echo $row2;
                                          ?>
                                      </h5>
                                    <span class="description-text" style="text-transform:none;">Candidate Remarks</span>
                                  </div><!-- /.description-block -->
                                </div><!-- /.col -->
                                <div class="col-sm-4 border-right">
                                  <div class="description-block">
                                    <h5 class="description-header">
                                          <?php
                                          $sql3 = "SELECT * FROM db_partnerfollow where insertBy ='$id2'";
                                          $query3 = mysql_query($sql3);
                                          $row3 = mysql_num_rows($query3);
                                          echo $row3;
                                          ?>


                                    </h5>
                                    <span class="description-text" style="text-transform:none;">Client Remarks</span>
                                  </div><!-- /.description-block -->
                                </div><!-- /.col -->
                                <div class="col-sm-4">
                                  <div class="description-block">
                                    <h5 class="description-header">
                                          <?php
                                          echo $row2 + $row3;
                                          ?>
                                    </h5>
                                      <span class="description-text" style="text-transform:none;">Total Remarks</span>
                                  </div><!-- /.description-block -->
                                </div><!-- /.col -->
                              </div><!-- /.row -->
                            </div>
                          </div><!-- /.widget-user -->
                        </div><!-- /.col -->

                        <?php } ?>
                        </div>
                    </div>                        
                        <?php }        
    }
    public function getManagerDashboardBackup(){
        ?>      <div class = "col-md-7">
                    <div class="box box-success" style="border-top-color:#00a7d0">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo "Manager : " .$_SESSION['empl_name'];?></h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div><!-- /.box-header -->    
            
                        <div class="box-body no-padding">
                        <?php 
                        $id = $_SESSION['empl_id'];
                        $sql = "Select * from db_empl where empl_group = '8' and empl_manager = '$id'";
                        $query = mysql_query($sql);
                        while($row = mysql_fetch_array($query)){
                        ?>


                        <div class="col-md-6">
                          <!-- Widget: user widget style 1 -->
                          <div class="box box-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-aqua-active">
                              <h3 class="widget-user-username"><?php echo $row['empl_name']; ?></h3>
                              <h5 class="widget-user-desc"><?php echo "Consultant" ?></h5>
                              <button type="button" id="Btn" class="btn btn-primary btn-warning remarks" style="background-color: #8BC34A; border-color: #4CAF50; margin-top:-50px; float:right;" pid="<?php echo $row['empl_id'];?>" >Remarks</button>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle" src="dist/images/empl/<?php echo  $row['empl_id'];?>.png" alt="User Avatar">
                            </div>
                            <div class="box-footer">
                              <div class="row">
                                <div class="col-sm-4 border-right">
                                  <div class="description-block">
                                      <h5 class="description-header">
                                          <?php
                                          $id2 = $row['empl_id'];
                                          $sql2 = "SELECT * FROM db_followup where insertBy ='$id2' AND fol_status = '0'";
                                          $query2 = mysql_query($sql2);
                                          $row2 = mysql_num_rows($query2);
                                          echo $row2;
                                          ?>
                                      </h5>
                                    <span class="description-text" style="text-transform:none;">Candidate Remarks</span>
                                  </div><!-- /.description-block -->
                                </div><!-- /.col -->
                                <div class="col-sm-4 border-right">
                                  <div class="description-block">
                                    <h5 class="description-header">
                                          <?php
                                          $sql3 = "SELECT * FROM db_partnerfollow where insertBy ='$id2'";
                                          $query3 = mysql_query($sql3);
                                          $row3 = mysql_num_rows($query3);
                                          echo $row3;
                                          ?>


                                    </h5>
                                    <span class="description-text" style="text-transform:none;">Client Remarks</span>
                                  </div><!-- /.description-block -->
                                </div><!-- /.col -->
                                <div class="col-sm-4">
                                  <div class="description-block">
                                    <h5 class="description-header">
                                          <?php
                                          echo $row2 + $row3;
                                          ?>
                                    </h5>
                                      <span class="description-text" style="text-transform:none;">Total Remarks</span>
                                  </div><!-- /.description-block -->
                                </div><!-- /.col -->
                              </div><!-- /.row -->
                            </div>
                          </div><!-- /.widget-user -->
                        </div><!-- /.col -->

                        <?php } ?>
                        </div>
                    </div> 
                </div>
        <?php
    }
    public function getManagerDashboard(){
        ?>
        <div class = "col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Manager : " .$_SESSION['empl_name'];?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body no-padding">
                        <table class="table table-bordered text-center">
                            <tr>
                                <th><h4>Consultants List</h4></th>
                            </tr>                

                            <?php 
                            $id = $_SESSION['empl_id'];
                            $sql = "Select * from db_empl where empl_group = '8' and empl_manager = '$id'";
                            $query = mysql_query($sql);
                            while($row = mysql_fetch_array($query)){
                            ?>
                                <tr>
                                  <td>
                                      <!--<button type="button" pid="<?php echo $row['empl_id'];?>" id="Btn" class="btn btn-block btn-success btn-lg remarks"><?php echo $row['empl_name']. " - " .$row['empl_code'];?></button>-->
                                  
                                      <div class="btn-group">
                                      <button type="button" pid="<?php echo $row['empl_id'];?>" class="btn btn-success btn-lg remarks" style="width:250px; height:40px;"><?php echo $row['empl_name']. " - " .$row['empl_code'];?></button>
<!--                                      <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" style="height:40px;">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                      </button>-->
                                      <ul class="dropdown-menu" role="menu" style="background-color:#2dc17d; width:283px;">
<!--                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>-->
                                      </ul>
                                    </div>                                
                                  </td>
                                </tr>
                            <?php } ?>
                        </table>       
                    </div>
            </div>
            
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $_SESSION['empl_name']." : Total Resume";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body">
                          <table id="applicant_table" class="table table-bordered table-hover table-cursor">
                            <thead>
                              <tr>
                                <th style = 'width:3%'>No</th>
                                <th style = 'width:13%'>Candidate Name</th>
                                <th style = 'width:5%'>Job Assign</th>
                              </tr>
                            </thead>
                            <tbody>             
                                <?php 
                                $i = 1;
                                $id = $_SESSION['empl_id'];
                                $sql = "Select a.* from db_applicant a INNER JOIN db_followup f ON a.applicant_id = f.applfollow_id where (f.follow_type = '3' OR f.follow_type = '4') and fol_assign_manager = '$id' and f.fol_status = '0'";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                ?>
                                <tr class="clickTable" pid="<?php echo $row['applicant_id'];?>">
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $row['applicant_name'];?></td>
                                    <td>
                                        <?php
                                        $today = date("Y-m-d");
                                        $sql11 = "SELECT * FROM db_followup WHERE follow_type = '0' AND fol_assign_expiry_date >= '$today' AND fol_status = '0' AND applfollow_id = '$row[applicant_id]' AND fol_approved = 'Y'"; 
                                        //echo $sql11;
                                        $query11 = mysql_query($sql11);
                                        if($row11 = mysql_fetch_array($query11)){ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>&follow_id=<?php echo $row11['follow_id'];?>&edit=1"><button type='button' style='background-color: #3c5bf7; border-color: #0c4da0; width:85px;' class='btn btn-primary btn-warning'>Assigned</button></a>
                                        <?php }else{ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>"><button type='button' style='background-color: #009688; border-color: #007368;width:85px;' class='btn btn-primary btn-warning'>Unassigned</button></a>
                                        <?php } 
                                        ?>
                                    </td>                                        
                                </tr>
                                
                            <?php $i++;
                                } ?>
                            </tbody>
                        </table>       
                    </div>
            </div>   
  
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Other Manager : Total Resume";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body">
                          <table id="otherManager_table" class="table table-bordered table-hover table-cursor">
                            <thead>
                              <tr>
                                <th style = 'width:3%'>No</th>
                                <th style = 'width:13%'>Candidate Name</th>
                                <th style = 'width:5%'>Job Assign</th>
                              </tr>
                            </thead>
                            <tbody>             
                                <?php 
                                $i = 1;
                                $id = $_SESSION['empl_id'];
                                $sql = "Select a.* from db_applicant a INNER JOIN db_followup f ON a.applicant_id = f.applfollow_id where (f.follow_type = '3' OR f.follow_type = '4') and fol_assign_manager != '$id' and f.fol_status = '0'";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                ?>
                                <tr class="clickTable" pid="<?php echo $row['applicant_id'];?>">
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $row['applicant_name'];?></td>
                                    <td>
                                        <?php
                                        $today = date("Y-m-d");
                                        $sql11 = "SELECT * FROM db_followup WHERE follow_type = '0' AND fol_assign_expiry_date >= '$today' AND fol_status = '0' AND applfollow_id = '$row[applicant_id]'"; 
                                        //echo $sql11;
                                        $query11 = mysql_query($sql11);
                                        if($row11 = mysql_fetch_array($query11)){ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>&follow_id=<?php echo $row11['follow_id'];?>&edit=1"><button type='button' style='background-color: #3c5bf7; border-color: #0c4da0; width:85px;' class='btn btn-primary btn-warning'>Assigned</button></a>
                                        <?php }else{ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>"><button type='button' style='background-color: #009688; border-color: #007368;width:85px;' class='btn btn-primary btn-warning'>Unassigned</button></a>
                                        <?php } 
                                        ?>
                                    </td>                                        
                                </tr>
                                
                            <?php $i++;
                                } ?>
                            </tbody>
                        </table>       
                    </div>
            </div>   
            
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo  $_SESSION['empl_name']." : Client";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body">
                          <table id="client_table" class="table table-bordered table-hover table-cursor">
                            <thead>
                              <tr>
                                <th style = 'width:3%'>No</th>
                                <th style = 'width:13%'>Client Name</th>
                                <th style = 'width:4%'>No. Candidate Assigned</th>
                                <th style = 'width:2%'></th>
                              </tr>
                            </thead>
                            <tbody>             
                                <?php 
                                $i = 1;
                                $sql = "SELECT * FROM db_partner p INNER JOIN db_empl e ON p.partner_sales_person = e.empl_id WHERE (p.partner_sales_person = '$_SESSION[empl_id]' OR e.empl_manager = '$_SESSION[empl_id]') AND partner_dashboard_display = '0'";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                    $sql3 = "SELECT * FROM db_dashbroad_display WHERE display_type = 'partner table' AND display_parent_id = '$row[partner_id]' AND display_empl_id = '$_SESSION[empl_id]'";
                                    $query3 = mysql_query($sql3);
                                    $row3 = mysql_num_rows($query3);
                                    if($row3 == 0){
                                        ?>
                                        <tr class="clientApplicant" pid="<?php echo $row['partner_id'];?>">
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $row['partner_name'];?></td>
                                            <td>
                                                <?php
                                                    $sql2 = "SELECT a.* FROM db_followup f INNER JOIN db_partner p ON f.interview_company = p.partner_id INNER JOIN db_applicant a ON a.applicant_id = f.applfollow_id WHERE f.follow_type = '0' AND f.fol_status = '0' AND f.interview_company = '$row[partner_id]' AND f.fol_approved = 'Y'";
                                                    $query2 = mysql_query($sql2);
                                                    $row2 = mysql_num_rows($query2);
                                                    echo $row2;
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-client delete" data-toggle="tooltip" title="Clear" pid="<?php echo $row['partner_id']?>"><i class="fa fa-dw fa-close"></i></button>
                                                <!--<button type="button" class="btn btn-info btn-client"><i class="fa fa-dw fa-check"></i></button>-->
                                            </td>
                                        </tr>                               
                                    <?php $i++;
                                    }
                                } ?>                              
                            </tbody>
                            <tbody>
                                <tr style="background-color: #49a078">
                                    <td></td>
                                    <td>Other Manager Client</td>
                                    <td></td>
                                    <td></td>
                                </tr> 
                                <?php 
                                $sql = "SELECT * FROM db_partner p INNER JOIN db_empl e ON p.partner_sales_person = e.empl_id WHERE (p.partner_sales_person != '$_SESSION[empl_id]' AND e.empl_manager != '$_SESSION[empl_id]') AND partner_dashboard_display = '0'";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                    $sql3 = "SELECT * FROM db_dashbroad_display WHERE display_type = 'partner table' AND display_parent_id = '$row[partner_id]' AND display_empl_id = '$_SESSION[empl_id]'";
                                    $query3 = mysql_query($sql3);
                                    $row3 = mysql_num_rows($query3);
                                    if($row3 == 0){
                                        ?>
                                        <tr class="clientApplicant" pid="<?php echo $row['partner_id'];?>">
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $row['partner_name'];?></td>
                                            <td>
                                                <?php
                                                    $sql2 = "SELECT a.* FROM db_followup f INNER JOIN db_partner p ON f.interview_company = p.partner_id INNER JOIN db_applicant a ON a.applicant_id = f.applfollow_id WHERE f.follow_type = '0' AND f.fol_status = '0' AND f.interview_company = '$row[partner_id]' AND f.fol_approved = 'Y'";
                                                    $query2 = mysql_query($sql2);
                                                    $row2 = mysql_num_rows($query2);
                                                    echo $row2;
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-client delete" data-toggle="tooltip" title="Clear" pid="<?php echo $row['partner_id']?>"><i class="fa fa-dw fa-close"></i></button>
        <!--                                        <button type="button" class="btn btn-info btn-client"><i class="fa fa-dw fa-check"></i></button>-->
                                            </td>
                                        </tr>                               
                                    <?php $i++;
                                    }
                                } ?>                                
                            </tbody>
                                
                        </table>       
                    </div>
            </div>             
            
            
        </div>

        <?php
    }
    public function getRemarkDashboard(){
        ?>
            <div class="col-md-7" >
                <?php if($_SESSION['empl_group'] == "4" || $_SESSION['empl_group'] == "8"){?>
                    <div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title">Active Resume</h3>
                          <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                          </div>
                        </div><!-- /.box-header -->

                        <div class="box-body"> 
                                <div class="col-xs-12">
                                  <div class="box" style="padding: 10px; height:450px; overflow-y : scroll;border-top:0px;">
                                    <div class="box-header" style="background-color: #00a65a; color:#fff;">
                                      <h3 class="box-title">Candidates Detail</h3>
                                      <div class="box-tools">
                                          <?php if($_SESSION['empl_group'] == "8"){?>
                                            <a href pid="<?php echo $_SESSION['empl_id'];?>" class="btn btn-success btn-lg remarks" id="remarks" style="width:1px; height:1px;visibility: hidden;"></a>
                                          <?php }?>
                                      </div>
                                    </div>
                                    <div class="box-body table-responsive no-padding" id="pRemarks_content">
                                    </div>
                                  </div>
                                </div> 
                        </div>
                    </div>  
                <?php } ?>
                
                    <div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title">Latest Remarks</h3>
                          <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                          </div>
                        </div><!-- /.box-header -->

                        <div class="box-body">   
                                    <div class="col-xs-12">
                                      <div class="box" style="padding: 10px; height:450px; overflow-y: scroll;border-top:0px;">
                                        <div class="box-header" style="background-color: #00a65a; color:#fff;">
                                          <h3 class="box-title">Candidate Remarks</h3>
                                          <div class="box-tools">          
                                          </div>
                                        </div><!-- /.box-header -->
                                        <div class="box-body table-responsive no-padding" id="aRemarks_content">
                                        </div><!-- /.box-body -->
                                      </div><!-- /.box -->
                                    </div>
                        </div>
                    </div>
                    
                    <div class="box box-success">
                        <div class="box-header with-border">
                          <h3 class="box-title">Client Candidate</h3>
                          <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                          </div>
                        </div><!-- /.box-header -->

                        <div class="box-body">   
                                    <div class="col-xs-12">
                                      <div class="box" style="padding: 10px; height:450px; overflow-y: scroll;border-top:0px;">
                                        <div class="box-header" style="background-color: #00a65a; color:#fff;">
                                          <h3 class="box-title">Candidate Detail</h3>
                                          <div class="box-tools">          
                                          </div>
                                        </div><!-- /.box-header -->
                                        <div class="box-body table-responsive no-padding" id="client_applicant_content">
                                        </div><!-- /.box-body -->
                                      </div><!-- /.box -->
                                    </div>
                        </div>
                    </div>
            </div> 
        <?php
    }
    public function getRemarkDashboardBackup(){
        ?>
            <div class="col-md-7" >
                    <div class="box box-info" style="border-top-color:#00a7d0">
                        <div class="box-header with-border">
                          <h3 class="box-title">Latest Remarks</h3>
                          <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                          </div>
                        </div><!-- /.box-header -->

                        <div class="box-body">   
                                    <div class="col-xs-12">
                                      <div class="box" style="padding: 10px; height:450px; overflow-y: scroll;border-top:0px;">
                                        <div class="box-header" style="background-color: #00a7d0; color:#fff;">
                                          <h3 class="box-title">Candidate Remarks</h3>
                                          <div class="box-tools">          
                                          </div>
                                        </div><!-- /.box-header -->
                                        <div class="box-body table-responsive no-padding" id="aRemarks_content">
                                        </div><!-- /.box-body -->
                                      </div><!-- /.box -->
                                    </div>
                        </div>
                    </div>

                    <div class="box box-info" style="border-top-color:#00a7d0">
                        <div class="box-header with-border">
                          <h3 class="box-title">Latest Remarks</h3>
                          <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                          </div>
                        </div><!-- /.box-header -->

                        <div class="box-body"> 
                                <div class="col-xs-12">
                                  <div class="box" style="padding: 10px; height:450px; overflow-y : scroll;border-top:0px;">
                                    <div class="box-header" style="background-color: #00a7d0; color:#fff;">
                                      <h3 class="box-title">Client Remarks</h3>
                                      <div class="box-tools">

                                      </div>
                                    </div>
                                    <div class="box-body table-responsive no-padding" id="pRemarks_content">
                                    </div>
                                  </div>
                                </div> 
                        </div>
                    </div>
            </div>  
        <?php
    }
    public function getConsultantDashboardBackup(){?>
            <div class="col-md-6">
                <div class="col-md-12">
                    
                <!-- USERS LIST -->
                  <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title">Latest Candidates Assign to You for last 7 days</h3>
                      <div class="box-tools pull-right">      
                     <?php                       
                     $a = 0;
                     $i = 0;
                      $empl_id = $_SESSION['empl_id'];
                      $sql = "SELECT applicant.*, LEFT(follow.insertDateTime, 10) as Date, right(follow.insertDateTime, 8) as Time FROM db_applicant applicant join db_followup follow on applicant_id = applfollow_id and assign_to = '$empl_id' AND fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                      $query = mysql_query($sql);
                      while($row = mysql_fetch_array($query)){
                     
                        $nowDate = date("Y-m-d");
                        $assignDate = $row['Date'];

                        $datetime1 = date_create($assignDate);
                        $datetime2 = date_create($nowDate);
                        $interval = date_diff($datetime1, $datetime2);
                        $day = $interval->format('%a');
                        if($day <= "7"){
                            $i++;
                        }   
                      }?>
                          
                        <span class="label label-danger"><?php echo $i; ?> New Candidates</span>
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                    
                    <div class="box-body no-padding">
                      <ul class="users-list clearfix">
                    <?php                       
                      $empl_id = $_SESSION['empl_id'];
                      $sql = "SELECT applicant.*, LEFT(follow.insertDateTime, 10) as Date, right(follow.insertDateTime, 8) as Time FROM db_applicant applicant join db_followup follow on applicant_id = applfollow_id and assign_to = '$empl_id' AND fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                      $query = mysql_query($sql);
                      while($row = mysql_fetch_array($query)){
                     
                        $nowDate = date("Y-m-d");
                        $assignDate = $row['Date'];

                        $datetime1 = date_create($assignDate);
                        $datetime2 = date_create($nowDate);
                        $interval = date_diff($datetime1, $datetime2);
                        $day = $interval->format('%a');
                        if($day <= "7"){
                        ?>  
                            <li>
                                <img src="<?php echo "dist/images/applicant/".$row['applicant_id'].".png"?>" alt="User Image" style="width:120px; height:120px">
                                <a class="users-list-name" href="applicant.php?action=edit&applicant_id=171"><?php echo $row['applicant_name']?></a>
                                <span class="users-list-date" style="margin-bottom: 5px;"><?php echo $row['Date']?></span>
                                <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row['applicant_id'];?>"><button type="button" id="Btn" class="btn btn-primary btn-warning" >Add Remarks</button></a>
                            </li>
                        <?php }
                        } ?>
                      </ul><!-- /.users-list -->
                    </div><!-- /.box-body -->
                    <div class="box-footer text-center">
                    </div><!-- /.box-footer -->
                  </div><!--/.box -->
                </div>
            </div>
            
            
            
            
            
            <div class="col-md-6">
                <div class="col-md-12">
                    
                <!-- USERS LIST -->
                  <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title">Job Assign to You for last 30 days</h3>
                      <div class="box-tools pull-right">      
                     <?php                       
                      $empl_id = $_SESSION['empl_id'];
                      $sql = "SELECT *, LEFT(insertDateTime,10) AS Date, right(insertDateTime, 8) as Time FROM db_jobs where job_person_incharge = '$empl_id' AND job_delete = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                      $query = mysql_query($sql);
                      while($row = mysql_fetch_array($query)){
                     
                        $nowDate = date("Y-m-d");
                        $assignDate = $row['Date'];

                        $datetime1 = date_create($assignDate);
                        $datetime2 = date_create($nowDate);
                        $interval = date_diff($datetime1, $datetime2);
                        $day = $interval->format('%a');
                        
                        if($day <= "30"){
                            $a++;
                        }   
                      }?>
                          
                        <span class="label label-danger"><?php echo $a; ?> New Jobs</span>
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                    
                    <div class="box-body table-responsive">
                  <table id="job_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Title</th>
                        <th style = 'width:10%'>Category</th>
                        <th style = 'width:10%'>Owner</th>
                        <th style = 'width:10%'>Job Status</th>
                        <th style = 'width:10%'>Create Date</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php                           
                      $sql = "SELECT *, LEFT(insertDateTime,10) AS Date, RIGHT(insertDateTime, 8) AS Time FROM db_jobs where job_person_incharge = '$empl_id' AND job_delete = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['job_title'];?></td>
                            <?php $category_id = $row['job_category'];
                                $sql2 = "SELECT category_name FROM db_category_job where category_id = '$category_id'";
                                $query2 = mysql_query($sql2);
                                $row2 = mysql_fetch_array($query2);
                            ?>
                            <td><?php echo $row2['category_name'];?></td>
                            <td>
                                <?php $partner_id = $row['job_owner'];
                                $sql3 = "SELECT partner_name FROM db_partner where partner_id = '$partner_id'";
                                $query3 = mysql_query($sql3);
                                $row3 = mysql_fetch_array($query3);
                                echo $row3['partner_name'];
                                ?>
                            </td>     
                            <td>
                               <?php if ($row['job_status'] == "P"){ echo "Public";}
                                else if($row['job_status'] == "D"){ echo "Draft";}
                                else {echo "Close";}?>
                            </td>                       
                            <td><?php echo format_date($row['Date']) . " " . $row['Time']?>     
                            <input type="hidden" value = "<?php echo print_r($row);?>"> 
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
                        <th style = 'width:10%'>Title</th>
                        <th style = 'width:10%'>Category</th>
                        <th style = 'width:10%'>Owner</th>
                        <th style = 'width:10%'>Job Status</th>
                        <th style = 'width:10%'>Create Date</th>
                      </tr>
                    </tfoot>
                  </table>

                </div><!-- /.box-body -->

                    
                    <div class="box-footer text-center">
                    </div><!-- /.box-footer -->
                  </div><!--/.box -->
                </div>
            </div>          

            
            

            <div class="col-md-6">
                <div class="col-md-12">
                    
                <!-- USERS LIST -->
                  <div class="box box-danger">
                    <div class="box-header with-border">
                      <h3 class="box-title">Candidate interview today</h3>
                      <div class="box-tools pull-right">      
                     <?php                       
                      $empl_id = $_SESSION['empl_id'];
                      $sql = "SELECT * FROM db_followup WHERE follow_type = '2' AND interview_date = '$todayDate' AND insertBy = '$empl_id' AND fol_status = '0'";
                      $query = mysql_query($sql);
                      $row = mysql_num_rows($query);

                      ?>
                          
                        <span class="label label-danger"><?php echo $row; ?> candidate interview today</span>
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                      </div>
                    </div><!-- /.box-header -->
                    
                    <div class="box-body table-responsive">
                  <table id="interview_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Name</th>
                        <th style = 'width:10%'>Phone</th>
                        <th style = 'width:10%'>Company</th>
                        <th style = 'width:10%'>Position</th>
                        <th style = 'width:10%'>Interview Time</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php                           
                        $today = Date('Y-m-d');
                      $sql = "SELECT f.*,a.applicant_id, a.applicant_name, a.applicant_mobile FROM db_followup f INNER JOIN db_applicant a ON f.applfollow_id = a.applicant_id WHERE f.follow_type = '2' AND f.interview_date = '$today' AND f.insertBy = '$empl_id' AND f.fol_status = '0'";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr class='applicant_follow' pid='applicant.php?action=edit&applicant_id=<?php echo $row['applicant_id'];?>&current_tab=followup&follow_id=<?php echo $row['follow_id'];?>' > 
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['applicant_name'];?></td>
                            <td><?php echo $row['applicant_mobile'];?></td>
                            <td>
                                <?php
                                $sql3 = "SELECT partner_name FROM db_partner where partner_id = '$row[interview_company]'";
                                $query3 = mysql_query($sql3);
                                $row3 = mysql_fetch_array($query3);
                                echo $row3['partner_name'];
                                ?>
                            </td>     
                            <td>
                               <?php echo $row['fol_position_offer']?>
                            </td>                       
                            <td><?php echo $row['interview_time'] ?>
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
                        <th style = 'width:10%'>Phone</th>
                        <th style = 'width:10%'>Company</th>
                        <th style = 'width:10%'>Position</th>
                        <th style = 'width:10%'>Interview Time</th>
                      </tr>
                    </tfoot>
                  </table>

                </div><!-- /.box-body -->

                    
                    <div class="box-footer text-center">
                    </div><!-- /.box-footer -->
                  </div><!--/.box -->
                </div>
            </div>    
  
    <?php
    }
    public function getConsultantDashboard(){
        ?>

        <div class = "col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Consultant : ". $_SESSION['empl_name'] . " - New Candidate Assign";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body">
                          <table id="client_table" class="table table-bordered table-hover">
                            <thead>
                              <tr>
                                <th style = 'width:1%'>No</th>
                                <th style = 'width:13%'>Consultant Name</th>
                                <th style = 'width:4%'>Date</th>
                                <th style = 'width:3%'></th>
                                <th style = 'width:2%'></th>
                              </tr>
                            </thead>
                            <tbody>             
                                <?php 
                                $i = 1;
                                $sql = "SELECT applicant.*, LEFT(follow.insertDateTime, 10) as Date, right(follow.insertDateTime, 8) as Time FROM db_applicant applicant join db_followup follow on applicant_id = applfollow_id WHERE follow.follow_type AND follow.assign_to = '$_SESSION[empl_id]' AND follow.fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                    $sql3 = "SELECT * FROM db_dashbroad_display WHERE display_type = 'new assign table' AND display_parent_id = '$row[applicant_id]' AND display_empl_id = '$_SESSION[empl_id]'";
                                    $query3 = mysql_query($sql3);
                                    $row3 = mysql_num_rows($query3);
                                    if($row3 == 0){
                                        ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $row['applicant_name'];?></td>
                                            <td><?php echo $row['Date']?></td>
                                            <td><a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row['applicant_id'];?>"><button type="button" id="Btn" class="btn btn-primary btn-warning" style="padding:2px 6px">Add Remarks</button></a></td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-client delete-applicant"  data-toggle="tooltip" title="Clear" pid="<?php echo $row['applicant_id']?>"><i class="fa fa-dw fa-close"></i></button>
                                                <!--<button type="button" class="btn btn-info btn-client"><i class="fa fa-dw fa-check"></i></button>-->
                                            </td>
                                        </tr>                               
                                    <?php $i++;
                                    }
                                } ?>                             
                            </tbody>
                        </table>       
                    </div>
                </div> 
            
<!--                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $_SESSION['empl_name']." : Total Resume";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div> /.box-header     

                        <div class="box-body">
                          <table id="applicant_table" class="table table-bordered table-hover table-cursor">
                            <thead>
                              <tr>
                                <th style = 'width:3%'>No</th>
                                <th style = 'width:13%'>Candidate Name</th>
                                <th style = 'width:5%'>Job Assign</th>
                              </tr>
                            </thead>
                            <tbody>             
                                <?php 
                                $i = 1;
                                $id = $_SESSION['empl_id'];
                                $sql = "Select a.* from db_applicant a INNER JOIN db_followup f ON a.applicant_id = f.applfollow_id where (f.follow_type = '3' OR f.follow_type = '4') and assign_to = '$id' and f.fol_status = '0'";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                ?>
                                <tr class="clickTable" pid="<?php echo $row['applicant_id'];?>">
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $row['applicant_name'];?></td>
                                    <td>
                                        <?php
                                        $today = date("Y-m-d");
                                        $sql11 = "SELECT * FROM db_followup WHERE follow_type = '0' AND fol_assign_expiry_date >= '$today' AND fol_status = '0' AND applfollow_id = '$row[applicant_id]'"; 
                                        //echo $sql11;
                                        $query11 = mysql_query($sql11);
                                        if($row11 = mysql_fetch_array($query11)){ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>&follow_id=<?php echo $row11['follow_id'];?>&edit=1"><button type='button' style='background-color: #3c5bf7; border-color: #0c4da0; width:85px;' class='btn btn-primary btn-warning'>Assigned</button></a>
                                        <?php }else{ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>"><button type='button' style='background-color: #009688; border-color: #007368;width:85px;' class='btn btn-primary btn-warning'>Unassigned</button></a>
                                        <?php } 
                                        ?>
                                    </td>                                        
                                </tr>
                                
                            <?php $i++;
                                } ?>
                            </tbody>                           
                        </table>       
                    </div>
            </div>   -->
            
            <?php $sql = "SELECT empl_manager FROM db_empl WHERE empl_id = '$id'";
                  $query = mysql_query($sql);
                  $row = mysql_fetch_array($query);
                  $manager_id = $row['empl_manager'];
                  $sql = "SELECT empl_name FROM db_empl WHERE empl_id = '$manager_id'";
                  $query = mysql_query($sql);
                  $row = mysql_fetch_array($query);
                  $manager_name = $row['empl_name'];
            ?>
            
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo " Manager : ".$manager_name. " - Total Resume";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body">
                          <table id="applicant_table" class="table table-bordered table-hover table-cursor">
                            <thead>
                              <tr>
                                <th style = 'width:3%'>No</th>
                                <th style = 'width:13%'>Candidate Name</th>
                                <th style = 'width:5%'>Job Assign</th>
                              </tr>
                            </thead>
                            <tbody>             
                                <?php 
                                $i = 1;
                                $id = $_SESSION['empl_id'];
                                $sql = "Select a.* from db_applicant a INNER JOIN db_followup f ON a.applicant_id = f.applfollow_id where (f.follow_type = '3' OR f.follow_type = '4') and fol_assign_manager = '$manager_id' and f.fol_status = '0'";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                ?>
                                <tr class="clickTable" pid="<?php echo $row['applicant_id'];?>">
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $row['applicant_name'];?></td>
                                    <td>
                                        <?php
                                        $today = date("Y-m-d");
                                        $sql11 = "SELECT * FROM db_followup WHERE follow_type = '0' AND fol_assign_expiry_date >= '$today' AND fol_status = '0' AND applfollow_id = '$row[applicant_id]'"; 
                                        //echo $sql11;
                                        $query11 = mysql_query($sql11);
                                        if($row11 = mysql_fetch_array($query11)){ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>&follow_id=<?php echo $row11['follow_id'];?>&edit=1"><button type='button' style='background-color: #3c5bf7; border-color: #0c4da0; width:85px;' class='btn btn-primary btn-warning'>Assigned</button></a>
                                        <?php }else{ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>"><button type='button' style='background-color: #009688; border-color: #007368;width:85px;' class='btn btn-primary btn-warning'>Unassigned</button></a>
                                        <?php } 
                                        ?>
                                    </td>                                        
                                </tr>
                                
                            <?php $i++;
                                } ?>
                            </tbody>
                        </table>       
                    </div>
            </div>
            
            
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Other Manager : Total Resume";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body">
                          <table id="otherManager_table" class="table table-bordered table-hover table-cursor">
                            <thead>
                              <tr>
                                <th style = 'width:3%'>No</th>
                                <th style = 'width:13%'>Candidate Name</th>
                                <th style = 'width:5%'>Job Assign</th>
                              </tr>
                            </thead>
                            <tbody>             
                                <?php 
                                $i = 1;
                                $id = $_SESSION['empl_id'];
                                $sql = "Select a.* from db_applicant a INNER JOIN db_followup f ON a.applicant_id = f.applfollow_id where (f.follow_type = '3' OR f.follow_type = '4') and fol_assign_manager != '$manager_id' and f.fol_status = '0'";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                ?>
                                <tr class="clickTable" pid="<?php echo $row['applicant_id'];?>">
                                    <td><?php echo $i;?></td>
                                    <td><?php echo $row['applicant_name'];?></td>
                                    <td>
                                        <?php
                                        $today = date("Y-m-d");
                                        $sql11 = "SELECT * FROM db_followup WHERE follow_type = '0' AND fol_assign_expiry_date >= '$today' AND fol_status = '0' AND applfollow_id = '$row[applicant_id]'"; 
                                        //echo $sql11;
                                        $query11 = mysql_query($sql11);
                                        if($row11 = mysql_fetch_array($query11)){ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>&follow_id=<?php echo $row11['follow_id'];?>&edit=1"><button type='button' style='background-color: #3c5bf7; border-color: #0c4da0; width:85px;' class='btn btn-primary btn-warning'>Assigned</button></a>
                                        <?php }else{ ?>
                                            <a href="applicant.php?action=edit&current_tab=followup&applicant_id=<?php echo $row[applicant_id];?>"><button type='button' style='background-color: #009688; border-color: #007368;width:85px;' class='btn btn-primary btn-warning'>Unassigned</button></a>
                                        <?php } 
                                        ?>
                                    </td>                                        
                                </tr>
                                
                            <?php $i++;
                                } ?>
                            </tbody>
                        </table>       
                    </div>
            </div>            
            
            
            
            
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo  $_SESSION['empl_name']." : Client";?></h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div><!-- /.box-header -->    

                        <div class="box-body">
                          <table id="client_table" class="table table-bordered table-hover table-cursor">
                            <thead>
                              <tr>
                                <th style = 'width:3%'>No</th>
                                <th style = 'width:13%'>Client Name</th>
                                <th style = 'width:4%'>No. Candidate Assigned</th>
                                <th style = 'width:2%'></th>
                              </tr>
                            </thead>
                            <tbody>             
                                <?php 
                                $i = 1;
                                $sql = "SELECT * FROM db_partner p INNER JOIN db_empl e ON p.partner_sales_person = e.empl_id WHERE (p.partner_sales_person = '$_SESSION[empl_id]' OR e.empl_manager = '$_SESSION[empl_id]')";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                    $manager = $row['empl_manager'];
                                    $sql3 = "SELECT * FROM db_dashbroad_display WHERE display_type = 'partner table' AND display_parent_id = '$row[partner_id]' AND display_empl_id = '$_SESSION[empl_id]'";
                                    $query3 = mysql_query($sql3);
                                    $row3 = mysql_num_rows($query3);
                                    if($row3 == 0){
                                        ?>
                                        <tr class="clientApplicant" pid="<?php echo $row['partner_id'];?>">
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $row['partner_name'];?></td>
                                            <td>
                                                <?php
                                                    $sql2 = "SELECT a.* FROM db_followup f INNER JOIN db_partner p ON f.interview_company = p.partner_id INNER JOIN db_applicant a ON a.applicant_id = f.applfollow_id WHERE f.follow_type = '0' AND f.fol_status = '0' AND f.interview_company = '$row[partner_id]' AND f.fol_approved = 'Y'";
                                                    $query2 = mysql_query($sql2);
                                                    $row2 = mysql_num_rows($query2);
                                                    echo $row2;
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-client delete"  data-toggle="tooltip" title="Clear"  pid="<?php echo $row['partner_id']?>"><i class="fa fa-dw fa-close"></i></button>
                                                <!--<button type="button" class="btn btn-info btn-client"><i class="fa fa-dw fa-check"></i></button>-->
                                            </td>
                                        </tr>                               
                                    <?php $i++;
                                    }
                                } ?>                           
                            </tbody>
                            <tbody>
                                <tr style="background-color: #49a078">
                                    <td></td>
                                    <td>Own Manager Client</td>
                                    <td></td>
                                    <td></td>
                                </tr> 
                                <?php 
                                    $sql = "SELECT * FROM db_partner p INNER JOIN db_empl e ON p.partner_sales_person = e.empl_id WHERE p.partner_sales_person != '$_SESSION[empl_id]' AND (e.empl_manager = '$manager' or p.partner_sales_person = '$manager')";
                                    $query = mysql_query($sql);
                                    while($row = mysql_fetch_array($query)){
                                        $sql3 = "SELECT * FROM db_dashbroad_display WHERE display_type = 'partner table' AND display_parent_id = '$row[partner_id]' AND display_empl_id = '$_SESSION[empl_id]'";
                                        $query3 = mysql_query($sql3);
                                        $row3 = mysql_num_rows($query3);
                                        if($row3 == 0){
                                            ?>
                                            <tr class="clientApplicant" pid="<?php echo $row['partner_id'];?>">
                                                <td><?php echo $i;?></td>
                                                <td><?php echo $row['partner_name'];?></td>
                                                <td>
                                                    <?php
                                                        $sql4 = "SELECT a.* FROM db_followup f INNER JOIN db_partner p ON f.interview_company = p.partner_id INNER JOIN db_applicant a ON a.applicant_id = f.applfollow_id WHERE f.follow_type = '0' AND f.fol_status = '0' AND f.interview_company = '$row[partner_id]' AND f.fol_approved = 'Y'";
                                                        $query4 = mysql_query($sql4);
                                                        $row4 = mysql_num_rows($query4);
                                                        echo $row4;
                                                    ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-client delete"  data-toggle="tooltip" title="Clear" pid="<?php echo $row['partner_id']?>"><i class="fa fa-dw fa-close"></i></button>
            <!--                                        <button type="button" class="btn btn-info btn-client"><i class="fa fa-dw fa-check"></i></button>-->
                                                </td>
                                            </tr>                               
                                        <?php $i++;
                                        }
                                    } 
                                ?>                                
                            </tbody>                            
                            <tbody>
                                <tr style="background-color: #49a078">
                                    <td></td>
                                    <td>Other Manager or Consultant Client</td>
                                    <td></td>
                                    <td></td>
                                </tr> 
                                <?php 
                                $sql = "SELECT * FROM db_partner p INNER JOIN db_empl e ON p.partner_sales_person = e.empl_id WHERE p.partner_sales_person != '$_SESSION[empl_id]' AND (e.empl_manager != '$manager' AND p.partner_sales_person != '$manager')";
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                    $sql3 = "SELECT * FROM db_dashbroad_display WHERE display_type = 'partner table' AND display_parent_id = '$row[partner_id]' AND display_empl_id = '$_SESSION[empl_id]'";
                                    $query3 = mysql_query($sql3);
                                    $row3 = mysql_num_rows($query3);
                                    if($row3 == 0){
                                        ?>
                                        <tr class="clientApplicant" pid="<?php echo $row['partner_id'];?>">
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $row['partner_name'];?></td>
                                            <td>
                                                <?php
                                                    $sql2 = "SELECT a.* FROM db_followup f INNER JOIN db_partner p ON f.interview_company = p.partner_id INNER JOIN db_applicant a ON a.applicant_id = f.applfollow_id WHERE f.follow_type = '0' AND f.fol_status = '0' AND f.interview_company = '$row[partner_id]' AND f.fol_approved = 'Y'";
                                                    $query2 = mysql_query($sql2);
                                                    $row2 = mysql_num_rows($query2);
                                                    echo $row2;
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-client delete" data-toggle="tooltip" title="Clear"  pid="<?php echo $row['partner_id']?>"><i class="fa fa-dw fa-close"></i></button>
        <!--                                        <button type="button" class="btn btn-info btn-client"><i class="fa fa-dw fa-check"></i></button>-->
                                            </td>
                                        </tr>                               
                                    <?php $i++;
                                    }
                                } ?>                                
                            </tbody>
                                
                        </table>       
                    </div>
                </div>             
            
            
        </div>

        <?php
    }
    public function getRemarks(){
        $applicant_id = escape($_REQUEST['applicant_id']);
        $sql = "select a.*,f.*, f.follow_id, f.assign_to, f.interview_company, f.follow_type, left(f.insertDateTime,10) as date, right(f.insertDateTime, 8) as time, f.interview_company, f.received_offer, f.comments, e.empl_name from db_followup f inner join db_empl e on f.insertBy = e.empl_id inner join db_applicant a on a.applicant_id = f.applfollow_id where f.applfollow_id = '$applicant_id' and f.fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
        $query = mysql_query($sql);
        
        $i = 0;
        while($row = mysql_fetch_array($query)){
            
                $data['applicant_id'][$i] = $applicant_id;
                $data['applicant_name'][$i] = $row['applicant_name'];
                $data['follow_id'][$i] = $row['follow_id'];
                $data['edit'][$i] = "1";
                if ($row['follow_type']=="0"){
                $data['follow_type'][$i] = "Assign to Client";  
                }
                if ($row['follow_type']=="1"){
                $data['follow_type'][$i] =  "Candidate Follow Up";
                }
                if ($row['follow_type']=="2"){
                $data['follow_type'][$i] =  "Assign interview";
                }
                if ($row['follow_type']=="3"){
                $data['follow_type'][$i] =  "Assign Candidate to Employer";
                }
                if ($row['follow_type']=="4"){
                $data['follow_type'][$i] =  "Assign New Candidate to Own";
                }
                
                if($row['follow_type'] == "0")
                {
                    if($row['fol_approved'] == "Y"){$data['status'][$i] = "Approved";}
                    else if($row['fol_approved'] == "N"){$data['status'][$i] = "Not Suitable";}
                    //if($row['fol_approved'] == "0"){echo "Pending";}
                    else $data['status'][$i] = "Pending";
                }
                else if($row['follow_type'] == "2")  
                {
                    $data['status'][$i] = "Interview";
                }
                else {$data['status'][$i] = "-";}
                
                $id = $row['assign_to'];
                $sql2 = "select empl_name as assign_to from db_empl where empl_id = '$id' group by empl_name";
                $query2 = mysql_query($sql2);
                $row2 = mysql_fetch_array($query2);
                if ($row2['assign_to']=="" || $row2['assign_to']==null){
                    $data['assign_to'][$i] = "Own follow up";
                }
                else{
                $data['assign_to'][$i] = $row2['assign_to'];
                }
                $data['empl_name'][$i] = $row['empl_name'];
                $data['date'][$i] = format_date($row['date']);
                $data['time'][$i] = $row['time'];


                $partner_id = $row['interview_company'];
                $sql3 = "select partner_name from db_partner where partner_id = '$partner_id'";
                $query3 = mysql_query($sql3);
                $row3 = mysql_fetch_array($query3);
                if ($row3['partner_name'] != "" || $row3['partner_name'] != null){
                    $data['interview_company'][$i] = $row3['partner_name'];
                }        
                else{
                    $data['interview_company'][$i] = "-";
                }
                if ($row['received_offer']=="0"){
                $data['received_offer'][$i] = "Pending";        
                }
                else if ($row['received_offer']=="1"){
                $data['received_offer'][$i] =  "Yes";
                }
                else if ($row['received_offer']=="2"){
                $data['received_offer'][$i] =  "No";
                }
                else{
                   if($row['follow_type']=="0")
                       $data['received_offer'][$i] =  "Job Assigned";
                   else{
                       $data['received_offer'][$i] =  "-";
                   }
                }

                $data['comments'][$i] = preg_replace('#<script(.*?)>(.*?)</script>#is', '',htmlspecialchars_decode($row['comments']));
            $i++;
        }
//        $data['data'] = array('remarks_id'=>12,'content')
        return $data;
    }
    public function getClientApplicant(){
        $client_id = escape($_REQUEST['client_id']);
        
        $sql = "SELECT a.* FROM db_followup f INNER JOIN db_partner p ON f.interview_company = p.partner_id INNER JOIN db_applicant a ON a.applicant_id = f.applfollow_id WHERE f.follow_type = '0' AND f.fol_status = '0' AND f.interview_company = '$client_id'";
        $query = mysql_query($sql);
        $i = 0;
        while($row = mysql_fetch_array($query)){
            
            $data['applicant_id'][$i] = $row['applicant_id'];
            $data['applicant_name'][$i] = $row['applicant_name'];
            $data['applicant_email'][$i] = $row['applicant_email'];
            $data['applicant_mobile'][$i] = $row['applicant_mobile'];
            $i++;
        }
        
        return $data;
    }
    public function updateDashboardDisplay(){
        $follow_id = escape($_REQUEST['follow_id']);
        $type = "active candidate";
        $table_field = array('display_status','display_parent_id','display_type','display_empl_group','display_empl_id');
        $table_value = array(1,$follow_id, $type, $_SESSION['empl_group'],$_SESSION['empl_id']);
   
        $remark = "Create partner dashboard display";
        if(!$this->save->SaveData($table_field,$table_value,'db_dashbroad_display','display_id',$remark)){
           return false;
        }else{
           return true;
        }   
        
        
    }
    public function updatePartnerDashboardDisplay(){
        $partner_id = escape($_REQUEST['partner_id']);
        $type = "partner table";
        $table_field = array('display_status','display_parent_id','display_type','display_empl_group','display_empl_id');
        $table_value = array(1,$partner_id,$type,$_SESSION['empl_group'],$_SESSION['empl_id']);
   
        $remark = "Create partner dashboard display";
        if(!$this->save->SaveData($table_field,$table_value,'db_dashbroad_display','display_id',$remark)){
           return false;
        }else{
           return true;
        }   
    }
    public function updateApplicantDashboardDisplay(){
        $partner_id = escape($_REQUEST['partner_id']);
        $type = "new assign table";
        $table_field = array('display_status','display_parent_id','display_type','display_empl_group','display_empl_id');
        $table_value = array(1,$partner_id,$type,$_SESSION['empl_group'],$_SESSION['empl_id']);
   
        $remark = "Create partner dashboard display";
        if(!$this->save->SaveData($table_field,$table_value,'db_dashbroad_display','display_id',$remark)){
           return false;
        }else{
           return true;
        }   
    }    
}
?>
