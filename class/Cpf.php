<?php
/*
 * To change this tcpfate, choose Tools | Tcpfates
 * and open the tcpfate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Cpf {

    public function Cpf(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('cpf_from_age','cpf_to_age','cpf_employer_percent','cpf_employee_percent','cpf_desc','cpf_status');
        $table_value = array($this->cpf_from_age,$this->cpf_to_age,$this->cpf_employer_percent,$this->cpf_employee_percent,$this->cpf_desc,$this->cpf_status);
        $remark = "Insert CPF.";
        if(!$this->save->SaveData($table_field,$table_value,'db_cpf','cpf_id',$remark)){
           return false;
        }else{
           $this->cpf_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $table_field = array('cpf_from_age','cpf_to_age','cpf_employer_percent','cpf_employee_percent','cpf_desc','cpf_status');
        $table_value = array($this->cpf_from_age,$this->cpf_to_age,$this->cpf_employer_percent,$this->cpf_employee_percent,$this->cpf_desc,$this->cpf_status);
        $remark = "Update CPF.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_cpf','cpf_id',$remark,$this->cpf_id)){
           return false;
        }else{
           return true;
        }
    }
    public function fetchCpfDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_cpf WHERE cpf_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->cpf_id = $row['cpf_id'];
            $this->cpf_from_age = $row['cpf_from_age'];
            $this->cpf_to_age = $row['cpf_to_age'];
            $this->cpf_employer_percent = $row['cpf_employer_percent'];
            $this->cpf_status = $row['cpf_status'];
            $this->cpf_employee_percent = $row['cpf_employee_percent'];
            $this->cpf_desc = $row['cpf_desc'];
        }else if($type == 2){
            return mysql_fetch_array($query);
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_cpf"," WHERE cpf_id = '$this->cpf_id'","Delete Cpf.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->cpf_seqno = 10;
            $this->cpf_status = 1;
        }
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>CPF Management</title>
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
            <h1>CPF Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->cpf_id > 0){ echo "Update CPF";}else{ echo "Create New CPF";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='cpf.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='cpf.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'cpf_form' class="form-horizontal" action = 'cpf.php?action=create' method = "POST">
                  <div class="box-body">
                        <div class="form-group">
                          <label for="cpf_from_age" class="col-sm-2 control-label">From Age</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="cpf_from_age" name="cpf_from_age" placeholder="From Age" value = "<?php echo $this->cpf_from_age;?>" <?php echo $readonly;?>>
                          </div>
                        </div>  
                        <div class="form-group">
                          <label for="cpf_to_age" class="col-sm-2 control-label">To Age</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="cpf_to_age" name="cpf_to_age" placeholder="To Age" value = "<?php echo $this->cpf_to_age;?>" <?php echo $readonly;?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="cpf_employer_percent" class="col-sm-2 control-label">Employer (% of wage)</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="cpf_employer_percent" name="cpf_employer_percent" placeholder="Employer (% of wage)" value = "<?php echo $this->cpf_employer_percent;?>" <?php echo $readonly;?>>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="cpf_employee_percent" class="col-sm-2 control-label">Employee (% of wage)</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="cpf_employee_percent" name="cpf_employee_percent" placeholder="Employee (% of wage)" value = "<?php echo $this->cpf_employee_percent;?>" <?php echo $readonly;?> >
                          </div>
                        </div>
                    <div class="form-group">
                      <label for="cpf_status" class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-3">
                           <select class="form-control" id="cpf_status" name="cpf_status">
                             <option value = '1' <?php if($this->cpf_status == 1){ echo 'SELECTED';}?>>Active</option>
                             <option value = '0' <?php if($this->cpf_status == 0){ echo 'SELECTED';}?>>In-active</option>
                           </select>
                      </div>
                    </div> 
                    <div class="form-group">
                      <label for="cpf_desc" class="col-sm-2 control-label">Description</label>
                      <div class="col-sm-3">
                            <textarea class="form-control" rows="3" id="cpf_desc" name="cpf_desc" placeholder="Remark"><?php echo $this->cpf_desc;?></textarea>
                      </div>
                    </div> 
                    
                     
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->cpf_id;?>" name = "cpf_id"/>
                    <?php 
                    if($this->cpf_id > 0){
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
    <?php
    include_once 'js.php';
    
    ?>
    <script>
    $(document).ready(function() {
        $("#cpf_form").validate({
                  rules: 
                  {
                      cpf_code:
                      {
                          required: true
                      }
                  },
                  messages:
                  {
                      cpf_code:
                      {
                          required: "Please enter Cpf Code."
                      }
                  }
              });
    
    
});
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
    <title>CPF Management</title>
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
            <h1>CPF Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">CPF Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='cpf.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="cpf_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:15%'>From Age</th>
                        <th style = 'width:15%'>To Age</th>
                        <th style = 'width:10%'>Employer %</th>
                        <th style = 'width:10%'>Employee %</th>
                        <th style = 'width:20%'>Description</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT cpf.*
                              FROM db_cpf cpf 
                              WHERE cpf.cpf_id > 0 ORDER BY cpf.cpf_from_age";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['cpf_from_age'];?></td>
                            <td><?php echo $row['cpf_to_age'];?></td>
                            <td><?php echo $row['cpf_employer_percent'];?></td>
                            <td><?php echo $row['cpf_employee_percent'];?></td>
                            <td><?php echo nl2br($row['cpf_desc']);?></td>
                            <td><?php if($row['cpf_status'] == 1){ echo 'Active';}else{ echo 'In-Active';}?></td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'cpf.php?action=edit&cpf_id=<?php echo $row['cpf_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                        if($row['cpf_default'] > 0){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "alert('System default value, cannot be delete.')">Delete</button>
                                <?php
                                        }else{
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('cpf.php?action=delete&cpf_id=<?php echo $row['cpf_id'];?>','Confirm Delete?')">Delete</button>
                                <?php
                                        }
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
                        <th style = 'width:15%'>From Age</th>
                        <th style = 'width:15%'>To Age</th>
                        <th style = 'width:10%'>Employer %</th>
                        <th style = 'width:10%'>Employee %</th>
                        <th style = 'width:20%'>Description</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
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
        $('#cpf_table').DataTable({
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

}
?>
