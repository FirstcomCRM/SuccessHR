<?php
/*
 * To change this tdeductionsate, choose Tools | Tdeductionsates
 * and open the tdeductionsate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Deductions {

    public function Deductions(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        if($_SESSION['empl_group'] == "9"){
            $deduction_empl_type = 1;
        }
        else{
            $deduction_empl_type = 0;
        }         

        $table_field = array('deductions_title','deductions_date','deductions_remark','deductions_status','deductions_empl_id','deduction_empl_type',
                             'deductions_amount','deductions_affect_cpf');
        $table_value = array($this->deductions_title,format_date_database($this->deductions_date),$this->deductions_remark,1,$this->deductions_empl_id,$deduction_empl_type,
                             $this->deductions_amount,$this->deductions_affect_cpf);
        $remark = "Insert Apply Deductions.";
        if(!$this->save->SaveData($table_field,$table_value,'db_deductions','deductions_id',$remark)){
           return false;
        }else{
           $this->deductions_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $table_field = array('deductions_title','deductions_date','deductions_remark','deductions_status','deductions_empl_id',
                             'deductions_amount','deductions_affect_cpf');
        $table_value = array($this->deductions_title,format_date_database($this->deductions_date),$this->deductions_remark,1,$this->deductions_empl_id,
                             $this->deductions_amount,$this->deductions_affect_cpf);
        $remark = "Update Apply Deductions.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_deductions','deductions_id',$remark,$this->deductions_id)){
           return false;
        }else{
           return true;
        }
    }
    public function fetchDeductionsDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_deductions WHERE deductions_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->deductions_id = $row['deductions_id'];
            $this->deductions_empl_id = $row['deductions_empl_id'];
            $this->deductions_title = $row['deductions_title'];
            $this->deductions_date = $row['deductions_date'];
            $this->deductions_amount = $row['deductions_amount'];
            $this->deductions_remark = $row['deductions_remark'];
            $this->deductions_status = $row['deductions_status'];
            $this->deductions_affect_cpf = $row['deductions_affect_cpf'];
        }
        return $query;
    }
    public function delete(){
        $table_field = array('deductions_status');
        $table_value = array(0);
        $remark = "Delete Deductions.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_deductions','deductions_id',$remark,$this->deductions_id)){
           return false;
        }else{
           return true;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        include_once 'class/Empl.php';
        $e = new Empl();
        if($action == 'create'){
            $this->deductions_seqno = 10;
            $this->deductions_status = 1;
            $this->deductions_date = system_date;
            $this->deductions_dateto = system_date;
            $this->deductions_total_day = 1;
            $this->deductions_approvalstatus = 'Draft';
            $this->deductions_duration = "full_day";
            $empl_data = $e->fetchEmplDetail(" AND empl_id = '{$_SESSION['empl_id']}'","","",2);
            $empl_code = $_SESSION['empl_code'];
            $empl_name = $_SESSION['empl_name'];
            $this->deductions_affect_cpf = 1;
        }else{
            $empl_data = $e->fetchEmplDetail(" AND empl_id = '$this->deductions_empl_id'","","",2);
            $empl_code = $empl_data['empl_code'];
            $empl_name = $empl_data['empl_name'];
        }

    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Deductions Management</title>
    <?php
    include_once 'css.php';
    if($_SESSION['empl_group'] == "9"){
        $this->employeestypeCrtl = $this->select->getClientApplicantSelectCtrl($this->deductions_empl_id, $_SESSION['empl_id']);
    }
    else{
        $this->employeestypeCrtl = $this->select->getEmployeeSelectCtrl($this->deductions_empl_id);
    }
    ?>    
    <style>
        .tablenoborder tbody tr td{
            border:none ;
        }
        .tablenoborder tbody tr td{
            border:none ;
        }
        .table-empl-detail td:nth-child(3){
            font-weight: bold;
        }
        .empl-icon-label a i{
            font-size:22px;
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
            <h1>Deductions Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->deductions_id > 0){ echo "Update Deductions";}else{ echo "Apply New Deductions";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = '' onclick = "window.location.href='deductions.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'margin-right:10px;' onclick = "window.location.href='deductions.php?action=createForm'">Create New</button>
                <?php }?>
              </div>

                <form id = 'deductions_form' class="form-horizontal" action = 'deductions.php?action=create' method = "POST">
                  <div class="box-body">
                    <div class="col-sm-8">  
                    <div class="form-group">
                      <label for = "deductions_empl_id" class="col-sm-2 control-label">Employee <?php echo $mandatory;?></label>
                      <div class="col-sm-4">
                          <select class="form-control select2" id="deductions_empl_id" name="deductions_empl_id">
                            <?php echo $this->employeestypeCrtl;?>
                          </select>
                      </div>

                    </div>
                    <div class="form-group">
                      <label for="deductions_title" class="col-sm-2 control-label">Title <?php echo $mandatory;?></label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="deductions_title" name="deductions_title" value = "<?php echo $this->deductions_title;?>" placeholder="Title" <?php echo $disabled;?>>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="deductions_amount" class="col-sm-2 control-label">Amount</label>
                      <div class="col-sm-4">
                          <input type="text" class="form-control" id="deductions_amount" name="deductions_amount" value = "<?php echo num_format($this->deductions_amount);?>" placeholder="Amount" <?php echo $disabled;?> >
                      </div>
                    </div>
                     <div class="form-group">
                         <label for="deductions_date" class="col-sm-2 control-label">Date</label>
                      <div class="col-sm-4">
                          <input type="text" class="form-control datepicker" id="deductions_date" name="deductions_date" value = "<?php echo format_date($this->deductions_date);?>" placeholder="Date" <?php echo $disabled;?>>
                      </div>
                     </div> 
                     <div class="form-group">
                         <label for="deductions_affect_cpf" class="col-sm-2 control-label">Affect CPF </label>
                      <div class="col-sm-4">
                          <input type="checkbox" id="deductions_affect_cpf" name="deductions_affect_cpf" value = "1" <?php if($this->deductions_affect_cpf == '1'){ echo 'CHECKED';}?> <?php echo $disabled;?>>
                      </div>
                     </div> 
                    <div class="form-group">
                      <label for="deductions_remark" class="col-sm-2 control-label">Remark</label>
                      <div class="col-sm-4">
                            <textarea class="form-control" rows="3" id="deductions_remark" name="deductions_remark" placeholder="Remark" <?php echo $disabled;?>><?php echo $this->deductions_remark;?></textarea>
                      </div>

                    </div> 
                    </div>
                    <div class="col-sm-4" style = 'text-align:center' >    
                            
                   </div>
                      <div style = 'clear:both'></div>  
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Back</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->deductions_id;?>" name = "deductions_id"/>
                    <?php 
                    if($this->deductions_id > 0){
                        $prm_code = "update";
                    }else{
                        $prm_code = "create";
                    }
                            if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){
                    ?>
                            <button type = "submit" name = 'submit_btn' value = 'Save' class="btn btn-info">Save</button>
                            <?php 
                            }
?>
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
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo $empl_data['empl_name'];?></h4>
        </div>
        <div class="modal-body">
            <table class = 'table tablenoborder table-empl-detail'  >
                <tr>
                    <td rowspan = '5'>
                     <?php if(file_exists("dist/images/empl/{$this->deductions_empl_id}.png")){?>
                     <img src="dist/images/empl/<?php echo $this->deductions_empl_id;?>.png" class="img-circle" alt="User Image"  >
                  <?php }else{?>
                     <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="img-circle">
                    <?php }?>
                    </td>
                </tr>
                <tr>
                    <td>Email Address</td>
                    <td> : </td>
                    <td><?php echo $empl_data['empl_email'];?></td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td> : </td>
                    <td><?php echo getDataCodeBySql("department_code","db_department"," WHERE department_id = '{$empl_data['empl_department']}'");?></td>
                </tr>
                <tr>
                    <td>Mobiles</td>
                    <td> : </td>
                    <td><?php echo $empl_data['empl_mobile'];?></td>
                </tr>
                <tr>
                    <td>Outlet</td>
                    <td> : </td>
                    <td><?php echo getDataCodeBySql("outl_code","db_outl"," WHERE outl_id = '{$empl_data['empl_outlet']}'");?></td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
    <script>
    $(document).ready(function() {
       
        $("#deductions_form").validate({
                  rules: 
                  {
                      deductions_title:
                      {
                          required: true
                      },
                      deductions_empl_id:
                      {
                          required: true
                      }
                  },
                  messages:
                  {
                      deductions_title:
                      {
                          required: "Please enter Deductions Title."
                      },
                      deductions_empl_id:
                      {
                          required: "Please select Employee."
                      }
                  }
        });
});
    var issend = false;
    function saveline(line,deductionsline_id){
        if(issend){
            alert("Please wait...");
            return false;
        }

        issend = true;
        if(deductionsline_id != ""){
            var action = 'updateline';
        }else{
            var action = 'saveline';
        }

        var data = "deductionsline_seqno="+$('#deductionsline_seqno_'+line).val();
            data += "&deductionsline_date="+$('#deductionsline_date_'+line).val();
            data += "&deductionsline_type="+$('#deductionsline_type_'+line).val();
            data += "&deductionsline_desc="+encodeURIComponent($('#deductionsline_desc_'+line).val());
            data += "&deductionsline_receiptno="+$('#deductionsline_receiptno_'+line).val();
            data += "&deductionsline_amount="+$('#deductionsline_amount_'+line).val();
            data += "&action="+action;
            data += "&deductionsline_id="+deductionsline_id;
            data += "&deductions_id=<?php echo $this->deductions_id;?>";

        $.ajax({ 
            type: 'POST',
            url: 'deductions.php',
            cache: false,
            data:data,
            error: function(xhr) {
                alert("System Error.");
                issend = false;
            },
            success: function(data) {
               var jsonObj = eval ("(" + data + ")");
               if(jsonObj.status == 1){
                   window.location.reload();
               }else{
                   alert("Add/Update Fail.");
               }
               issend = false;
            }		
         });
         return false;
    }
    function deleteline(deductions_id){
        var data = "action=deleteline&deductions_id=<?php echo $this->deductions_id;?>&deductionsline_id="+deductions_id;
        $.ajax({ 
            type: 'POST',
            url: 'deductions.php',
            cache: false,
            data:data,
            error: function(xhr) {
                alert("System Error.");
                issend = false;
            },
            success: function(data) {
               var jsonObj = eval ("(" + data + ")");
               if(jsonObj.status == 1){
                   window.location.reload();
               }else{
                   alert("Fail to delete line.");
               }
               issend = false;
            }		
         });
         return false;
    }
    function addline(){
        var addlinevalue = $('#total_line').val();
        var nextvalue = parseInt(addlinevalue)+1;
        var newline = line_copy.replace(/@i/g,nextvalue);
        $('#detail_last_tr').before(newline);
        $('#total_line').val(nextvalue);
        $('#deductionsline_seqno_'+nextvalue).val(nextvalue*10);
        $(".select2").select2();
        $('.calculate').on('keyup',function(){
            calculate();
        });
        $('.datepicker').datepicker({
            format: 'dd-M-yyyy',
            autoclose: true,
            pickerPosition: "bottom-left"
        });
    }
    function calculate(){
        var total_deductions_amount = 0;
        $('.calculate').each(function(){
            var line = $(this).attr('line');
            var line_amount = $(this).val().replace(/,/gi, "");
            if(isNaN(line_amount)){
                line_amount = 0;
            }
            if($('#deductionsline_type_'+line).val() > 0){
                checkDeductionsLimit($('#deductionsline_type_'+line).val(),line);
                total_deductions_amount = parseFloat(total_deductions_amount) + parseFloat(line_amount);
            }
        });
        $('#deductions_amount').val(changeNumberFormat(total_deductions_amount));
    }
    function checkDeductionsLimit(deductionstype_id,line){

        var data = "action=checkDeductionsLimit&deductionstype_id=" + deductionstype_id;
        $.ajax({ 
            type: 'POST',
            url: 'deductionstype.php',
            cache: false,
            data:data,
            error: function(xhr) {
                alert("System Error.");
                issend = false;
            },
            success: function(data) {
               var jsonObj = eval ("(" + data + ")");
               if(jsonObj.status == 1){
                   var line_amount = $('#deductionsline_amount_'+line).val().replace(/,/gi, "");
                   if(jsonObj.deductions_limit > 0){
                       if(parseFloat(line_amount) > parseFloat(jsonObj.deductions_limit)){
                           
                           $('#deductionsline_amount_'+line).css('border','2px solid red');
                           if(!$('#deductionsline_amount_'+line).hasClass('limit_error')){
                            alert('Deductions Limit.');
                           }
                           $('#deductionsline_amount_'+line).addClass('limit_error');
                           return false;
                       }else{
                           $('#deductionsline_amount_'+line).css('border','1px solid #d2d6de');
                       }
                   }
               }

            }		
         });
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
    <title>Deductions Management</title>
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
            <h1>Deductions Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Deductions Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='deductions.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table id="deductions_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:13%'>Employee</th>
                        <th style = 'width:10%'>Title</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:25%'>Remark</th>
                        <th style = 'width:10%'>Amount</th>
                        <th style = 'width:14%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                    if($_SESSION['empl_group'] == "9"){
                      $sql = "SELECT l.*,appl.applicant_name as empl_name
                              FROM db_deductions l 
                              INNER JOIN db_applicant appl ON appl.applicant_id = l.deductions_empl_id
                             
                              WHERE l.deductions_id > 0 AND l.deductions_status = '1' AND l.deduction_empl_type = '1'
                              ORDER BY l.updateDateTime";                        
                    }
                    else{
                      $sql = "SELECT l.*,empl.empl_name
                              FROM db_deductions l 
                              INNER JOIN db_empl empl ON empl.empl_id = l.deductions_empl_id
                             
                              WHERE l.deductions_id > 0 AND l.deductions_status = '1' AND l.deduction_empl_type = '0'
                              ORDER BY l.updateDateTime";
                    }
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['empl_name'];?></td>
                            <td><?php echo $row['deductions_title'];?></td>
                            <td><?php echo format_date($row['deductions_date']);?></td>
                            <td><?php echo nl2br($row['deductions_remark']);?></td>
                            <td><?php echo "$ ".num_format($row['deductions_amount']);?></td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'deductions.php?action=edit&deductions_id=<?php echo $row['deductions_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('deductions.php?action=delete&deductions_id=<?php echo $row['deductions_id'];?>','Confirm Delete?')">Delete</button>
                                <?php
                                 }
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
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:13%'>Employee</th>
                        <th style = 'width:10%'>Title</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:25%'>Remark</th>
                        <th style = 'width:10%'>Amount</th>
                        <th style = 'width:14%'></th>
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
        $('#deductions_table').DataTable({
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


}
?>
