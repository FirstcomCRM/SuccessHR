<?php
/*
 * To change this tadditionalate, choose Tools | Tadditionalates
 * and open the tadditionalate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Additional {

    public function Additional(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        if($_SESSION['empl_group'] == "9"){
            $additional_empl_type = 1;
        }
        else{
            $additional_empl_type = 0;
        } 
            
        $table_field = array('additional_type','additional_date','additional_remark','additional_status','additional_empl_id','additional_empl_type',
                             'additional_amount','additional_affect_cpf');
        $table_value = array($this->additional_type,format_date_database($this->additional_date),$this->additional_remark,1,$this->additional_empl_id,$additional_empl_type,
                             $this->additional_amount,$this->additional_affect_cpf);
        $remark = "Insert Apply Additional.";
        if(!$this->save->SaveData($table_field,$table_value,'db_additional','additional_id',$remark)){
           return false;
        }else{
           $this->additional_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $table_field = array('additional_type','additional_date','additional_remark','additional_status','additional_empl_id',
                             'additional_amount','additional_affect_cpf');
        $table_value = array($this->additional_type,format_date_database($this->additional_date),$this->additional_remark,1,$this->additional_empl_id,
                             $this->additional_amount,$this->additional_affect_cpf);
        $remark = "Update Apply Additional.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_additional','additional_id',$remark,$this->additional_id)){
           return false;
        }else{
           return true;
        }
    }
    public function fetchAdditionalDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_additional WHERE additional_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->additional_id = $row['additional_id'];
            $this->additional_empl_id = $row['additional_empl_id'];
            $this->additional_type = $row['additional_type'];
            $this->additional_date = $row['additional_date'];
            $this->additional_amount = $row['additional_amount'];
            $this->additional_remark = $row['additional_remark'];
            $this->additional_status = $row['additional_status'];
            $this->additional_affect_cpf = $row['additional_affect_cpf'];
        }
        return $query;
    }
    public function delete(){
        $table_field = array('additional_status');
        $table_value = array(0);
        $remark = "Delete Additional.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_additional','additional_id',$remark,$this->additional_id)){
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
            $this->additional_seqno = 10;
            $this->additional_status = 1;
            $this->additional_amount = 0;
            $this->additional_date = system_date;
            $this->additional_affect_cpf = 1;
        }

    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Additional Management</title>
    <?php
    include_once 'css.php';
    if($_SESSION['empl_group'] == "9"){
        $this->employeestypeCrtl = $this->select->getClientApplicantSelectCtrl($this->additional_empl_id, $_SESSION['empl_id']);
    }
    else{
        $this->employeestypeCrtl = $this->select->getEmployeeSelectCtrl($this->additional_empl_id);
    }
    $this->additionalCrtl = $this->select->getAdditionalTypeSelectCtrl($this->additional_type);
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
            <h1>Additional Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->additional_id > 0){ echo "Update Additional";}else{ echo "Apply New Additional";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = '' onclick = "window.location.href='additional.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'margin-right:10px;' onclick = "window.location.href='additional.php?action=createForm'">Create New</button>
                <?php }?>
              </div>

                <form id = 'additional_form' class="form-horizontal" action = 'additional.php?action=create' method = "POST">
                  <div class="box-body">
                    <div class="col-sm-8">  
                    <div class="form-group">
                      <label for = "additional_empl_id" class="col-sm-2 control-label">Employee <?php echo $mandatory;?></label>
                      <div class="col-sm-4">
                          <select class="form-control select2" id="additional_empl_id" name="additional_empl_id">
                            <?php echo $this->employeestypeCrtl;?>
                          </select>
                      </div>

                    </div>
                    <div class="form-group">
                      <label for="additional_type" class="col-sm-2 control-label">Additional Type <?php echo $mandatory;?></label>
                      <div class="col-sm-4">
                          <select class="form-control select2" id="additional_type" name="additional_type">
                            <?php echo $this->additionalCrtl;?>
                          </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="additional_amount" class="col-sm-2 control-label">Amount</label>
                      <div class="col-sm-4">
                          <input type="text" class="form-control" id="additional_amount" name="additional_amount" value = "<?php echo num_format($this->additional_amount);?>" placeholder="Amount" <?php echo $disabled;?> >
                      </div>
                    </div>
                     <div class="form-group">
                         <label for="additional_date" class="col-sm-2 control-label">Date</label>
                      <div class="col-sm-4">
                          <input type="text" class="form-control datepicker" id="additional_date" name="additional_date" value = "<?php echo format_date($this->additional_date);?>" placeholder="Date" <?php echo $disabled;?>>
                      </div>
                     </div> 
                     <div class="form-group">
                         <label for="additional_affect_cpf" class="col-sm-2 control-label">Affect CPF </label>
                      <div class="col-sm-4">
                          <input type="checkbox" id="additional_affect_cpf" name="additional_affect_cpf" value = "1" <?php if($this->additional_affect_cpf == '1'){ echo 'CHECKED';}?> <?php echo $disabled;?>>
                      </div>
                     </div> 
                    <div class="form-group">
                      <label for="additional_remark" class="col-sm-2 control-label">Remark</label>
                      <div class="col-sm-4">
                            <textarea class="form-control" rows="3" id="additional_remark" name="additional_remark" placeholder="Remark" <?php echo $disabled;?>><?php echo $this->additional_remark;?></textarea>
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
                    <input type = "hidden" value = "<?php echo $this->additional_id;?>" name = "additional_id"/>
                    <?php 
                    if($this->additional_id > 0){
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
                     <?php if(file_exists("dist/images/empl/{$this->additional_empl_id}.png")){?>
                     <img src="dist/images/empl/<?php echo $this->additional_empl_id;?>.png" class="img-circle" alt="User Image"  >
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
       
        $("#additional_form").validate({
                  rules: 
                  {
                      additional_type:
                      {
                          required: true
                      },
                      additional_empl_id:
                      {
                          required: true
                      }
                  },
                  messages:
                  {
                      
                      additional_empl_id:
                      {
                          required: "Please select Employee."
                      }
                  }
        });
});
    var issend = false;
    function saveline(line,additionalline_id){
        if(issend){
            alert("Please wait...");
            return false;
        }

        issend = true;
        if(additionalline_id != ""){
            var action = 'updateline';
        }else{
            var action = 'saveline';
        }

        var data = "additionalline_seqno="+$('#additionalline_seqno_'+line).val();
            data += "&additionalline_date="+$('#additionalline_date_'+line).val();
            data += "&additionalline_type="+$('#additionalline_type_'+line).val();
            data += "&additionalline_desc="+encodeURIComponent($('#additionalline_desc_'+line).val());
            data += "&additionalline_receiptno="+$('#additionalline_receiptno_'+line).val();
            data += "&additionalline_amount="+$('#additionalline_amount_'+line).val();
            data += "&action="+action;
            data += "&additionalline_id="+additionalline_id;
            data += "&additional_id=<?php echo $this->additional_id;?>";

        $.ajax({ 
            type: 'POST',
            url: 'additional.php',
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
    function deleteline(additional_id){
        var data = "action=deleteline&additional_id=<?php echo $this->additional_id;?>&additionalline_id="+additional_id;
        $.ajax({ 
            type: 'POST',
            url: 'additional.php',
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
        $('#additionalline_seqno_'+nextvalue).val(nextvalue*10);
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
        var total_additional_amount = 0;
        $('.calculate').each(function(){
            var line = $(this).attr('line');
            var line_amount = $(this).val().replace(/,/gi, "");
            if(isNaN(line_amount)){
                line_amount = 0;
            }
            if($('#additionalline_type_'+line).val() > 0){
                checkAdditionalLimit($('#additionalline_type_'+line).val(),line);
                total_additional_amount = parseFloat(total_additional_amount) + parseFloat(line_amount);
            }
        });
        $('#additional_amount').val(changeNumberFormat(total_additional_amount));
    }
    function checkAdditionalLimit(additionaltype_id,line){

        var data = "action=checkAdditionalLimit&additionaltype_id=" + additionaltype_id;
        $.ajax({ 
            type: 'POST',
            url: 'additionaltype.php',
            cache: false,
            data:data,
            error: function(xhr) {
                alert("System Error.");
                issend = false;
            },
            success: function(data) {
               var jsonObj = eval ("(" + data + ")");
               if(jsonObj.status == 1){
                   var line_amount = $('#additionalline_amount_'+line).val().replace(/,/gi, "");
                   if(jsonObj.additional_limit > 0){
                       if(parseFloat(line_amount) > parseFloat(jsonObj.additional_limit)){
                           
                           $('#additionalline_amount_'+line).css('border','2px solid red');
                           if(!$('#additionalline_amount_'+line).hasClass('limit_error')){
                            alert('Additional Limit.');
                           }
                           $('#additionalline_amount_'+line).addClass('limit_error');
                           return false;
                       }else{
                           $('#additionalline_amount_'+line).css('border','1px solid #d2d6de');
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
    <title>Additional Management</title>
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
            <h1>Additional Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Additional Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='additional.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table id="additional_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:13%'>Employee</th>
                        <th style = 'width:10%'>Type</th>
                        <th style = 'width:10%'>Date</th>
                        <th style = 'width:25%'>Remark</th>
                        <th style = 'width:10%'>Amount</th>
                        <th style = 'width:14%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                    if($_SESSION['empl_group'] == "9"){
                      $sql = "SELECT l.*,appl.applicant_name as empl_name,alt.additionaltype_code 
                              FROM db_additional l 
                              INNER JOIN db_applicant appl ON appl.applicant_id = l.additional_empl_id
                              LEFT JOIN db_additionaltype alt ON alt.additionaltype_id = l.additional_type
                              WHERE l.additional_id > 0 AND l.additional_status = '1' AND l.insertBy = '$_SESSION[empl_id]' AND l.additional_empl_type = '1'
                              ORDER BY l.updateDateTime";
                    }
                    else{
                      $sql = "SELECT l.*,empl.empl_name,alt.additionaltype_code
                              FROM db_additional l 
                              INNER JOIN db_empl empl ON empl.empl_id = l.additional_empl_id
                              LEFT JOIN db_additionaltype alt ON alt.additionaltype_id = l.additional_type
                              WHERE l.additional_id > 0 AND l.additional_status = '1' AND l.additional_empl_type = '0'
                              ORDER BY l.updateDateTime";
                    }
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['empl_name'];?></td>
                            <td><?php echo $row['additionaltype_code'];?></td>
                            <td><?php echo format_date($row['additional_date']);?></td>
                            <td><?php echo nl2br($row['additional_remark']);?></td>
                            <td><?php echo "$ ".num_format($row['additional_amount']);?></td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'additional.php?action=edit&additional_id=<?php echo $row['additional_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('additional.php?action=delete&additional_id=<?php echo $row['additional_id'];?>','Confirm Delete?')">Delete</button>
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
        $('#additional_table').DataTable({
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
