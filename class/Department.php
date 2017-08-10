<?php
/*
 * To change this tdepartmentate, choose Tools | Tdepartmentates
 * and open the tdepartmentate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Department {

    public function Department(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('department_code','department_desc','department_seqno','department_status');
        $table_value = array($this->department_code,$this->department_desc,$this->department_seqno,$this->department_status);
        $remark = "Insert Department.";
        if(!$this->save->SaveData($table_field,$table_value,'db_department','department_id',$remark)){
           return false;
        }else{
           $this->department_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $r = $this->fetchDepartmentDetail(" AND department_id = '$this->department_id'","","",0);
        if($r['department_default'] == 1){
            $this->department_code = $r['department_code'];
        }
            $table_field = array('department_code','department_desc','department_seqno','department_status');
            $table_value = array($this->department_code,$this->department_desc,$this->department_seqno,$this->department_status);
        $remark = "Update Department.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_department','department_id',$remark,$this->department_id)){
           return false;
        }else{
           return true;
        }
    }
    public function fetchDepartmentDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_department WHERE department_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->department_id = $row['department_id'];
            $this->department_code = $row['department_code'];
            $this->department_desc = $row['department_desc'];
            $this->department_seqno = $row['department_seqno'];
            $this->department_status = $row['department_status'];
            $this->department_default = $row['department_default'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_department"," WHERE department_id = '$this->department_id'","Delete Department.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->department_seqno = 10;
            $this->department_status = 1;
        }
        if($this->department_id > 0){
           if($this->department_default){
               $readonly = " READONLY";
               $script_text = " onclick = 'alert(\"System default value, cannot be delete.\")'";
           }
        }
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Department Management</title>
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
            <h1>Department Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->currency_id > 0){ echo "Update Department";}else{ echo "Create New Department";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='department.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='department.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'department_form' class="form-horizontal" action = 'department.php?action=create' method = "POST">
                  <div class="box-body">
                        <div class="form-group">
                          <label for="department_code" class="col-sm-2 control-label">Department Code</label>
                          <div class="col-sm-3">
                            <input type="text" class="form-control" id="department_code" name="department_code" placeholder="Departmentet Code" value = "<?php echo $this->department_code;?>" <?php echo $readonly;?> <?php echo $script_text;?>>
                          </div>
                        </div>  
                        
                    
                    <div class="form-group">
                      <label for="department_seqno" class="col-sm-2 control-label">Seq No</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="department_seqno" name="department_seqno" value = "<?php echo $this->department_seqno;?>" placeholder="Seq No">
                      </div>
                    </div> 
                    <div class="form-group">
                      <label for="department_status" class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-3">
                           <select class="form-control" id="department_status" name="department_status">
                             <option value = '1' <?php if($this->department_status == 1){ echo 'SELECTED';}?>>Active</option>
                             <option value = '0' <?php if($this->department_status == 0){ echo 'SELECTED';}?>>In-active</option>
                           </select>
                      </div>
                    </div> 
                    <div class="form-group">
                      <label for="department_desc" class="col-sm-2 control-label">Description</label>
                      <div class="col-sm-3">
                            <textarea class="form-control" rows="3" id="department_desc" name="department_desc" placeholder="Remark"><?php echo $this->department_desc;?></textarea>
                      </div>
                    </div> 
                    
                     
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->department_id;?>" name = "department_id"/>
                    <?php 
                    if($this->department_id > 0){
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
        $("#department_form").validate({
                  rules: 
                  {
                      department_code:
                      {
                          required: true
                      }
                  },
                  messages:
                  {
                      department_code:
                      {
                          required: "Please enter Department Code."
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
    <title>Department Management</title>
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
            <h1>Department Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Department Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='department.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="department_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:15%'>Department Code</th>
                        <th style = 'width:40%'>Description</th>
                        <th style = 'width:10%'>Seq No</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT department.*
                              FROM db_department department 
                              WHERE department.department_id > 0 ORDER BY department.department_seqno,department.department_code";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['department_code'];?></td>
                            <td><?php echo nl2br($row['department_desc']);?></td>
                            <td><?php echo $row['department_seqno'];?></td>
                            <td><?php if($row['department_status'] == 1){ echo 'Active';}else{ echo 'In-Active';}?></td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'department.php?action=edit&department_id=<?php echo $row['department_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                        if($row['department_default'] > 0){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "alert('System default value, cannot be delete.')">Delete</button>
                                <?php
                                        }else{
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('department.php?action=delete&department_id=<?php echo $row['department_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:15%'>Department Code</th>
                        <th style = 'width:40%'>Description</th>
                        <th style = 'width:10%'>Seq No</th>
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
        $('#department_table').DataTable({
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
