<?php
/*
 * To change this tleavecontrolate, choose Tools | Tleavecontrolates
 * and open the tleavecontrolate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Leavecontrol {

    public function Leavecontrol(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();

    }
    public function create(){
        
        for($i=0;$i<sizeof($this->leavecontrol_bringoverday);$i++){
            $table_field = array('emplleave_empl','emplleave_leavetype','emplleave_days',
                                 'emplleave_entitled','emplleave_year','emplleave_status');
            $table_value = array($this->leavecontrol_empl_id[$i],$this->leavecontrol_leave_type[$i],$this->leavecontrol_bringoverday[$i],
                                 $this->leavecontrol_bringoverday[$i],$this->leavecontrol_toyear,1);
            
            $remark = "Insert Bring Over Leave.";
            $this->save->SaveData($table_field,$table_value,'db_emplleave','emplleave_id',$remark);
            
            
            // update previous year status to zero
            
//            $sql = "UPDATE db_emplleave SET emplleave_status = '0' WHERE emplleave_year = '$this->leavecontrol_fromyear' AND emplleave_empl = '{$this->leavecontrol_empl_id[$i]}' AND emplleave_leavetype = '{$this->leavecontrol_leave_type[$i]}'";
//            mysql_query($sql);
        }
        $table_field = array('leavecontrol_department','leavecontrol_fromyear','leavecontrol_toyear','leavecontrol_status');
        $table_value = array($this->leavecontrol_department,$this->leavecontrol_fromyear,$this->leavecontrol_toyear,1);
        $remark = "Insert Leave Control Header.";
        $this->save->SaveData($table_field,$table_value,'db_leavecontrol','leavecontrol_id',$remark);
        


    }
    public function update(){
        $table_field = array('leavecontrol_code','leavecontrol_desc','leavecontrol_seqno','leavecontrol_status');
        $table_value = array($this->leavecontrol_code,$this->leavecontrol_desc,$this->leavecontrol_seqno,$this->leavecontrol_status);
        
        $remark = "Update Leavecontrol.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_leavecontrol','leavecontrol_id',$remark,$this->leavecontrol_id)){
           return false;
        }else{
           return true;
        }
    }
    public function fetchLeavecontrolDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_leavecontrol WHERE leavecontrol_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->leavecontrol_id = $row['leavecontrol_id'];
            $this->leavecontrol_code = $row['leavecontrol_code'];
            $this->leavecontrol_desc = $row['leavecontrol_desc'];
            $this->leavecontrol_seqno = $row['leavecontrol_seqno'];
            $this->leavecontrol_status = $row['leavecontrol_status'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_leavecontrol"," WHERE leavecontrol_id = '$this->leavecontrol_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->leavecontrol_status = 1;
        }
        $this->departmentCrtl = $this->select->getDepartmentSelectCtrl($this->payroll_department,'Y','','All');
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Leave Control Management</title>
    <?php
    include_once 'css.php';
    include_once 'js.php';
    ?>

    <script src="dist/ckeditor/ckeditor.js"></script>
    <script>
    $(document).ready(function() {
//        CKEDITOR.replace('leavecontrol_desc');
    
    
    });
    </script>
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
            <h1>Leave Control Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->leavecontrol_id > 0){ echo "Update Leavecontrol";}else{ echo "Create New Leave Control";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='leavecontrol.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='leavecontrol.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'leavecontrol_form' class="form-horizontal" action = 'leavecontrol.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">  
                      <label for ="leavecontrol_department" class="col-sm-2 control-label">Departments</label>
                      <div class="col-sm-2">
                        <select class="form-control select2" id="leavecontrol_department" name="leavecontrol_department" <?php echo $disabled;?>>
                          <?php echo $this->departmentCrtl;?>
                        </select>
                      </div>
                    </div>  
                    <div class="form-group">  
                      <label for ="leavecontrol_fromyear" class="col-sm-2 control-label">From Year</label>
                      <div class="col-sm-2">
                        <select class="form-control select2" id="leavecontrol_fromyear" name="leavecontrol_fromyear" <?php echo $disabled;?>>
                         <option value = "<?php echo date("Y");?>" ><?php echo date("Y");?></option>
                         <option value = "<?php echo date("Y");?>" ><?php echo date("Y")-1;?></option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">  
                      <label for ="leavecontrol_toyear" class="col-sm-2 control-label">To Year</label>
                      <div class="col-sm-2">
                        <select class="form-control select2" id="leavecontrol_toyear" name="leavecontrol_toyear" <?php echo $disabled;?>>
                         <option value = "<?php echo date("Y")+1;?>" ><?php echo date("Y")+1;?></option>
                         <option value = "<?php echo date("Y")+1;?>" ><?php echo date("Y");?></option>
                        </select>
                      </div>
                    </div>
                     <div class="form-group">
                      <div class="col-sm-2">
                          
                      </div>
                      <div class="col-sm-2">
                          <?php if($this->leavecontrol <=0){?>
                          <button type = 'button' class = 'btn btn-info preview' style = 'margin-top:15px;' >Preview</button>
                          <?php }?>
                      </div>
                     </div>
                      <div style = 'clear:both'></div>  
                        
                        <?php echo $this->getAddItemDetailForm();?>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->leavecontrol_id;?>" name = "leavecontrol_id"/>
                    <?php 
                    if($this->leavecontrol_id > 0){
                        $prm_code = "update";
                    }else{
                        $prm_code = "create";
                    }
                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){?>
                    <button type = "submit" class="btn btn-info">Submit</button>
                    <?php }?>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
          </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <script>
    $(document).ready(function() { 
        $('.preview').on('click',function(){
            previewLeaveControl();
        });
        
    });   
    var issend = false;
    function previewLeaveControl(){
        if(issend){
            alert("Please wait...");
            return false;
        }

        issend = true;


       var  data = "leavecontrol_department="+$('#leavecontrol_department').val();
            data += "&leavecontrol_fromyear="+$('#leavecontrol_fromyear').val();
            data += "&leavecontrol_toyear="+$('#leavecontrol_toyear').val();
            data = "&action=previewLeaveControl";

        $.ajax({ 
            type: 'POST',
            url: 'leavecontrol.php',
            cache: false,
            data:data,
            error: function(xhr) {
                alert("System Error.");
                issend = false;
            },
            success: function(data) {
                issend = false;
               var jsonObj = eval ("(" + data + ")");
               var row = "";
               var b = 1;
               $('.payslipslisting').remove();
               var grand_amt = 0;
               if(jsonObj){
               for(var i = 0;i<jsonObj.length;i++){
                   row = row + "<tr class = 'payslipslisting'>"
                         + "<td style='padding-left:5px'>" + b + "</td>"
                         + "<td style='padding-left:5px'>" + jsonObj[i]['department_code'] + "</td>"
                         + "<td style='padding-left:5px'>" + jsonObj[i]['empl_name'] + "</td>"
                         + "<td style='padding-left:5px'>" + jsonObj[i]['leavetype_code'] + "</td>"
                         + "<td style='padding-left:5px'>" + jsonObj[i]['fromyear_entitled'] + "</td>"
                         + "<td style='padding-left:5px'>" + jsonObj[i]['emplleave_days'] + "</td>"
                         + "<td style='padding-left:5px'>" + 
                                "<input style = 'text-align:right' name = 'leavecontrol_empl_id[]' type = 'hidden' value = '" + jsonObj[i]['empl_id'] + "' class = 'form-control' />"+
                                "<input style = 'text-align:right' name = 'leavecontrol_leave_type[]' type = 'hidden' value = '" + jsonObj[i]['leavetype_id'] + "' class = 'form-control' />"+
                                "<input style = 'text-align:right' name = 'leavecontrol_bringoverday[]' type = 'text' value = '" + jsonObj[i]['toyear_leave'] + "' class = 'form-control' /></td>"
                         + "</tr>"; 
                 b++;
               }

               }else{
                   row = row + "<tr class = 'payslipslisting'><td colspan = '7' style = 'text-align:center;font-weight:bold;font-size:16px;'> No Record Found.</td></tr>";
               }
               $('#detail_last_tr').before(row);
               
                $('.astatus').click(function(){

                        $('#payroll_id').val($(this).attr('payroll_id'));
                        $('#empl_id').val($(this).attr('empl_id'));
                        $('#varify_action').val('varify_password_bypass');
                        $('#varify_form').attr('target','_blank');
                        $('#varify_payroll_startdate').val($('#payroll_startdate').val());
                        $('#varify_payroll_enddate').val($('#payroll_enddate').val());
                        $('#varify_payroll_total_working_days').val($('#payroll_total_working_days').val());
                });
            }		
         });
         return false;
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
    <title>Leave Control Management</title>
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
            <h1>Leave Control Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Leave Control Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='leavecontrol.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="leavecontrol_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:35%'>Department</th>
                        <th style = 'width:10%'>From Year</th>
                        <th style = 'width:10%'>To Year</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT leavecontrol.*
                              FROM db_leavecontrol leavecontrol 
                              WHERE leavecontrol.leavecontrol_id > 0 
                              ORDER BY leavecontrol.leavecontrol_toyear DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td>
                                <?php 
                                if($row['leavecontrol_department'] == 0){
                                    echo " All Department";
                                }else{
                                    echo getDataCodeBySql("leavetype_code","db_leavetype"," WHERE leavetype_id = '{$row['leavecontrol_department']}'", $orderby);
                                }
                                ?>
                            </td>
                            <td><?php echo $row['leavecontrol_fromyear'];?></td>
                            <td><?php echo $row['leavecontrol_toyear'];?></td>
                        </tr>
                    <?php    
                        $i++;
                      }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:35%'>Department</th>
                        <th style = 'width:10%'>From Year</th>
                        <th style = 'width:10%'>To Year</th>
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
        $('#leavecontrol_table').DataTable({
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
    public function previewLeaveControl(){
        include_once 'class/Empl.php';

        
        $e = new Empl();

        //create filter

        if($this->leavecontrol_department > 0){
            $wherestring = " AND empl.empl_department = '$this->leavecontrol_department'"; 
        }

        


        $sql = "SELECT empl.empl_id,empl.empl_name,el.emplleave_days,el.emplleave_entitled as fromyear_entitled,lt.leavetype_code,d.department_code,lt.leavetype_default,lt.leavetype_bringover,lt.leavetype_id
                FROM db_empl empl
                INNER JOIN db_emplleave el ON el.emplleave_empl = empl.empl_id AND el.emplleave_status = 1 AND el.emplleave_year = '$this->leavecontrol_fromyear' AND el.emplleave_disabled = '0'
                INNER JOIN db_leavetype lt ON lt.leavetype_id = el.emplleave_leavetype
                INNER JOIN db_department d ON d.department_id = empl.empl_department 
                WHERE empl.empl_status = '1' AND empl.empl_department > 0 $wherestring
                ORDER BY d.department_code,empl.empl_name ";

        $query = mysql_query($sql);
        $i = 0;    
        while($row = mysql_fetch_array($query)){
           
            $b[$i]['empl_name'] = $row['empl_name'];
            $b[$i]['empl_id'] = $row['empl_id'];
            $b[$i]['department_code'] = $row['department_code'];
            
            $b[$i]['leavetype_code'] = $row['leavetype_code'];
            $b[$i]['emplleave_days'] = $row['emplleave_days'];
            $b[$i]['fromyear_entitled'] = $row['fromyear_entitled'];
            $b[$i]['leavetype_id'] = $row['leavetype_id'];
            if($row['leavetype_bringover'] == 1){
                $b[$i]['toyear_leave'] = $row['emplleave_days'] + $row['leavetype_default'];
            }else{
                $b[$i]['toyear_leave'] = $row['leavetype_default'];
            }

            $i++;
        }
     if($this->return_array == 1){
        return $b;
     }else{
        echo json_encode($b);
     }
     exit();
    }
    public function getAddItemDetailForm(){
    $line = 0;  
    if($this->payroll_approvalstatus <> 'Draft'){
        $disabled = " DISABLED";
    }
    ?>    
    <table id="detail_table" class="table transaction-detail">
        <thead>
          <tr>
            <th class = "" style="width:5%;padding-left:5px">No</th>
            <th class = "" style = 'width:10%;'>Department</th>
            <th class = "" style = 'width:25%;'>Employee Name</th>
            <th class = "" style = 'width:15%;'>Leave Type</th>
            <th class = "" style = 'width:10%;'>Entitled (<?php echo date("Y")?>)</th>
            <th class = "" style = 'width:10%;'>Balance (<?php echo date("Y")?>)</th>
            <th class = "" style = 'width:15%;'>Bring Over Next Year (<?php echo date("Y")+1?>)</th>
          </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT pl.*,empl.empl_name,dp.department_code,p.payroll_startdate,p.payroll_enddate
                   FROM db_payline pl
                   INNER JOIN db_payroll p ON p.payroll_id = pl.payline_payroll_id
                   LEFT JOIN db_empl empl ON empl.empl_id = pl.payline_empl_id
                   LEFT JOIN db_department dp ON dp.department_id = empl.empl_department
                   WHERE payline_id > 0 AND payline_payroll_id = '$this->payroll_id'";

            $query = mysql_query($sql);
            $i=0;
            while($row = mysql_fetch_array($query)){
                $i++;
                $total = ($row['payline_salary'] + $row['payline_additional']) - ($row['payline_deductions'] + $row['payline_cpf_employee']);
            ?>
                <tr id = "line_<?php echo $line;?>" class="tbl_grid_odd" line = "<?php echo $line;?>">
                    <td style="width:5%;padding-left:5px"><?php echo $i;?></td>
                    <td style="padding-left:5px"><?php echo $row['empl_name'];?></td>
                    <td style="padding-left:5px"><?php echo $row['department_code'];?></td>
                    <td style="padding-left:5px" align = 'right' ><?php echo num_format($row['payline_salary']);?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo num_format($row['payline_additional']);?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo num_format($row['payline_deductions']);?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo num_format($row['payline_cpf_employee']);?></td>
                    <td style="padding-left:5px" align = 'right'><?php echo num_format($total);?></td>
                    <td style="padding-left:5px" align = 'right'><a href = '#' class='btn btn-info astatus' data-toggle="modal" data-target="#sstatusModal" payroll_id = '<?php echo $this->payroll_id;?>' empl_id = '<?php echo $row['payline_empl_id'];?>' target = '_blank'> View </a></td>
                    </td>
                </tr>
            
            <?php
            }
            ?>
            <tr id = 'detail_last_tr'></tr>
        </tbody>
    </table>
    <?php    
    }

}
?>
