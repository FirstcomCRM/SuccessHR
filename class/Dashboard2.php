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
    <title>Payroll Management</title>
    
    <?php
    include_once 'css.php';
    include_once 'js.php';


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
            
                <div class = "col-md-7">
                    <?php if ($_SESSION['empl_group'] == "1" || $_SESSION['empl_group'] == "-1"){
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
                                          $sql2 = "SELECT * FROM db_followup where insertBy ='$id2'";
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
                    
                    if ($_SESSION['empl_group'] == "4")
                    { ?>
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
                                          $sql2 = "SELECT * FROM db_followup where insertBy ='$id2'";
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
                    <?php }?>
                    
                </div>
            
            

           <?php if ($_SESSION['empl_group'] == "4" || $_SESSION['empl_group'] == "1" || $_SESSION['empl_group'] == "-1")
            { ?>            
            
            
            <div class="col-md-5" >
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
            <?php }?>
            
            <?php if($_SESSION['empl_group'] == "8") { ?>
            <div class="col-md-7">
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
                      $sql = "SELECT applicant.*, LEFT(follow.insertDateTime, 10) as Date, right(follow.insertDateTime, 8) as Time FROM db_applicant applicant join db_followup follow on applicant_id = applfollow_id and assign_to = '$empl_id' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
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
                      $sql = "SELECT applicant.*, LEFT(follow.insertDateTime, 10) as Date, right(follow.insertDateTime, 8) as Time FROM db_applicant applicant join db_followup follow on applicant_id = applfollow_id and assign_to = '$empl_id' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
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
            
            
            
            
            
            <div class="col-md-5">
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
                  <table id="applicant_table" class="table table-bordered table-hover">
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
            
            <?php } ?>
            
            
            <?php if($_SESSION['empl_group'] == "5") { 
                $this->getApplicantCalender();
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
        $('#applicant_table').DataTable({
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
        
        $('.remarks').click(function(){
            var data = "action=getRemarkDetail&empl_id="+$(this).attr("pid");
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
                        table = "<h4>"+jsonObj['aRemarks']['empl_name'][0]+"</h4><table class= 'table table-bordered table-hover'" + "><thead><tr> <th style = 'width:2%'>No</th>";
                        table = table + "<th style = 'width:5%'>Candidate</th><th style = 'width:5%'>Client</th><th style = 'width:5%'>Follow Type</th><th style = 'width:15%'>Description</th>";
                        table = table + "<th style = 'width:5%'>Time & Date</th></tr></thead><tbody>";
                    var n = 1; 
                    for(var i=0;i<jsonObj['aRemarks']['applicant_name'].length;i++){
                        table = table + "<tr><td>" + n +"</td><td>" + jsonObj['aRemarks']['applicant_name'][i] + "</td><td>" + jsonObj['aRemarks']['interview_company'][i] + "</td><td>" + jsonObj['aRemarks']['follow_type'][i] + "</td><td><a href='applicant.php?action=edit&current_tab=followup&applicant_id=" + jsonObj['aRemarks']['applicant_id'][i] + "&follow_id=" + jsonObj['aRemarks']['follow_id'][i] + "'>" + jsonObj['aRemarks']['comments'][i] + "</a></td><td>" + jsonObj['aRemarks']['time'][i] +"<br>" + jsonObj['aRemarks']['date'][i] + "</td></tr>"; 
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

                    var ptable = "";

                    if( jsonObj['pRemarks']!=null){
                        ptable = "<h4>"+jsonObj['pRemarks']['empl_name'][0]+"</h4><table class= 'table table-bordered table-hover'" + "><thead><tr> <th style = 'width:2%'>No</th>";
                        ptable = ptable + "<th style = 'width:7%'>Client</th><th style = 'width:15%'>Description</th><th style = 'width:5%'>Time & Date</th></tr></thead><tbody>";
                        var pn = 1;
                        for(var i=0;i<jsonObj['pRemarks']['partner_name'].length;i++){
                        ptable = ptable + "<tr><td>" + pn + "</td><td>" + jsonObj['pRemarks']['partner_name'][i] + "</td><td><a href='partner.php?action=edit&tab=followup&partner_id=" + jsonObj['pRemarks']['partner_id'][i] + "&pfollow_id=" + jsonObj['pRemarks']['pfollow_id'][i] + "'>" + jsonObj['pRemarks']['pfollow_description'][i] + "</a></td><td>" + jsonObj['pRemarks']['time'][i] + "<br>" + jsonObj['pRemarks']['date'][i] +"</td></tr>";
                        pn++;
                    }
                    ptable = ptable + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th>";
                           ptable = ptable + "<th style = 'width:7%'>Client</th><th style = 'width:15%'>Description</th><th style = 'width:5%'>Time & Date</th></tr></tfoot></table>";
                    }
                    else
                    {
                        ptable = "<p>No have any remarks.</p>";
                    }
                    
                     
                    $('#pRemarks_content').html(ptable);
                    
                       issend = false;
                    }		
                 });
                 return false;
            });
      });
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
                <?php echo $this->getCalendar();?>
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
    public function getCalendar(){
    ?>
        <link rel="stylesheet" href="plugins/fullcalendar/fullcalendar.min.css">
        <link rel="stylesheet" href="plugins/fullcalendar/fullcalendar.print.css" media="print">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
        <script src="plugins/fullcalendar/fullcalendar.min.js"></script>
<script>
      $(function () {
          
                    $('#calendar').fullCalendar({

                    header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,listYear'
                    },

                    displayEventTime: false, // don't show the time column in list view


                    // US Holidays
                    events: 'json_api.php?action=getCalendarEvent',

                    eventClick: function(event) {
                            // opens events in a popup window

                            return false;
                    },

                    loading: function(bool) {
                            $('#loading').toggle(bool);
                    },
                     eventRender: function(event, element) {
                        $(element).tooltip({title: event.title});             
                    }

            });
      });
    </script>
        <div class="col-md-12">
            <div class="box box-info box-solid">
                <div class="box-header with-border z">
                  <h3 class="box-title">Calendar</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <div id="calendar"></div>
                  </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
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
            $sql = "select left(f.insertDateTime, 10) as date, right(f.insertDateTime,8) as time, f.interview_company, f.follow_type, f.comments ,a.applicant_id, a.applicant_name, e.empl_name, f.follow_id from db_followup f inner join db_applicant a inner join db_empl e on f.applfollow_id = a.applicant_id and e.empl_id = f.insertBy where f.insertby = '$this->empl_id' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
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

                $data ['interview_company'][$i] = $row2['partner_name'];

                $data['comments'][$i] = preg_replace('#<script(.*?)>(.*?)</script>#is', '',htmlspecialchars_decode($row['comments']));
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
    public function getConsultantDashboard(){
       
            echo "Here"; ?>
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
                            $sql2 = "SELECT empl_name from db_empl inner join db_followup on assign_to = empl_id where applfollow_id = '$appl_id' group by empl_name";
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
                            $sql4 = "SELECT * FROM db_followup f inner join db_resume r on f.applfollow_id = r.resume_appl_id inner join db_experience e on r.resume_appl_id = e.exp_appl_id inner join db_family a on e.exp_appl_id = a.applicant_family_id inner join db_applicant p on a.applicant_family_id = p.applicant_id where p.applicant_id = '$app_id'";
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
    public function getApplicantCalender(){
            ?>
            <div class="col-md-9">
              <div class="box box-primary">
                <div class="box-body no-padding">
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div><!-- /.col -->
            <?php
    }
}
?>
