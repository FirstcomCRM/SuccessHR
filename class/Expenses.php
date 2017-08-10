<?php
/*
 * To change this texpensesate, choose Tools | Texpensesates
 * and open the texpensesate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Expenses {

    public function Expenses(){

        

    }
    public function create(){
        $table_field = array('expenses_code','expenses_desc','expenses_seqno','expenses_status');
        $table_value = array($this->expenses_code,$this->expenses_desc,$this->expenses_seqno,$this->expenses_status);
        $remark = "Insert Expenses.";
        if(!$this->save->SaveData($table_field,$table_value,'db_expenses','expenses_id',$remark)){
           return false;
        }else{
           $this->expenses_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $table_field = array('expenses_code','expenses_desc','expenses_seqno','expenses_status');
        $table_value = array($this->expenses_code,$this->expenses_desc,$this->expenses_seqno,$this->expenses_status);
        
        $remark = "Update Expenses.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_expenses','expenses_id',$remark,$this->expenses_id)){
           return false;
        }else{
           return true;
        }
    }
    public function fetchExpensesDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_expenses WHERE expenses_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->expenses_id = $row['expenses_id'];
            $this->expenses_code = $row['expenses_code'];
            $this->expenses_desc = $row['expenses_desc'];
            $this->expenses_seqno = $row['expenses_seqno'];
            $this->expenses_status = $row['expenses_status'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_expenses"," WHERE expenses_id = '$this->expenses_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->expenses_status = 1;
        }

    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Expenses Management</title>
    <?php
    include_once 'css.php';
    include_once 'js.php';
    ?>

    <script src="dist/ckeditor/ckeditor.js"></script>
    <script>
    $(document).ready(function() {
//        CKEDITOR.replace('expenses_desc');
    
    
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
            <h1>Expenses Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->expenses_id > 0){ echo "Update Expenses";}else{ echo "Create New Expenses";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='expenses.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='expenses.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'expenses_form' class="form-horizontal" action = 'expenses.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="expenses_code" class="col-sm-2 control-label">Expenses Code</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="expenses_code" name="expenses_code" placeholder="Expenses Code" value = "<?php echo $this->expenses_code;?>" <?php echo $readonly;?>>
                      </div>
                      <label for="expenses_seqno" class="col-sm-2 control-label">Seq No</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="expenses_seqno" name="expenses_seqno" placeholder="Seq No" value = "<?php echo $this->expenses_seqno;?>" <?php echo $readonly;?>>
                      </div>
                    </div>   
                    <div class="form-group">
                      <label for="expenses_desc" class="col-sm-2 control-label">Description</label>
                      <div class="col-sm-3">
                      <textarea id="rexpenses_desc" name="expenses_desc" class="form-control" rows="3" placeholder="Description" <?php echo $readonly;?>><?php echo $this->expenses_desc;?></textarea>
                      </div>
                        <label for="expenses_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-3">
                             <select class="form-control" id="expenses_status" name="expenses_status">
                                  <option value = '0' <?php if($this->expenses_status == 0){ echo 'SELECTED';}?>>In-Active</option>
                                  <option value = '1' <?php if($this->expenses_status == 1){ echo 'SELECTED';}?>>Active</option>
                             </select>
                        </div>
                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->expenses_id;?>" name = "expenses_id"/>
                    <?php 
                    if($this->expenses_id > 0){
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
    <title>Expenses Management</title>
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
            <h1>Expenses Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Expenses Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='expenses.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="expenses_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:15%'>Expenses</th>
                        <th style = 'width:50%'>Description</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT expenses.*
                              FROM db_expenses expenses 
                              WHERE expenses.expenses_id > 0 
                              ORDER BY expenses.expenses_code";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['expenses_code'];?></td>
                            <td><?php echo $row['expenses_desc'];?></td>
                            <td><?php 
                            if($row['expenses_status'] == 1){ 
                                echo 'Active';
                            }else{
                                echo 'In-Active';
                            }
                            ?>
                            </td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'expenses.php?action=edit&expenses_id=<?php echo $row['expenses_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('expenses.php?action=delete&expenses_id=<?php echo $row['expenses_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:15%'>Expenses</th>
                        <th style = 'width:50%'>Description</th>
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
        $('#expenses_table').DataTable({
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
