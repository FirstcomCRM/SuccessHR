<?php
/*
 * To change this tclaimstypeate, choose Tools | Tclaimstypeates
 * and open the tclaimstypeate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Claimstype {

    public function Claimstype(){

        

    }
    public function create(){
        $table_field = array('claimstype_code','claimstype_desc','claimstype_seqno','claimstype_maxamt','claimstype_status');
        $table_value = array($this->claimstype_code,$this->claimstype_desc,$this->claimstype_seqno,$this->claimstype_maxamt,$this->claimstype_status);
        $remark = "Insert Claimstype.";
        if(!$this->save->SaveData($table_field,$table_value,'db_claimstype','claimstype_id',$remark)){
           return false;
        }else{
           $this->claimstype_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $table_field = array('claimstype_code','claimstype_desc','claimstype_seqno','claimstype_maxamt','claimstype_status');
        $table_value = array($this->claimstype_code,$this->claimstype_desc,$this->claimstype_seqno,$this->claimstype_maxamt,$this->claimstype_status);
        
        $remark = "Update Claimstype.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_claimstype','claimstype_id',$remark,$this->claimstype_id)){
           return false;
        }else{
           return true;
        }
    }
    public function fetchClaimstypeDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_claimstype WHERE claimstype_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->claimstype_id = $row['claimstype_id'];
            $this->claimstype_code = $row['claimstype_code'];
            $this->claimstype_desc = $row['claimstype_desc'];
            $this->claimstype_seqno = $row['claimstype_seqno'];
            $this->claimstype_maxamt = $row['claimstype_maxamt'];
            $this->claimstype_status = $row['claimstype_status'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_claimstype"," WHERE claimstype_id = '$this->claimstype_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->claimstype_status = 1;
        }

    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Claims Type Management</title>
    <?php
    include_once 'css.php';
    include_once 'js.php';
    ?>

    <script src="dist/ckeditor/ckeditor.js"></script>
    <script>
    $(document).ready(function() {
//        CKEDITOR.replace('claimstype_desc');
    
    
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
            <h1>Claims Type Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->claimstype_id > 0){ echo "Update Claims Type";}else{ echo "Create New Claims Type";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='claimstype.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='claimstype.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'claimstype_form' class="form-horizontal" action = 'claimstype.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="claimstype_code" class="col-sm-2 control-label">Claims Type Code <?php echo $mandatory;?></label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="claimstype_code" name="claimstype_code" placeholder="Claimstype Code" value = "<?php echo $this->claimstype_code;?>" <?php echo $readonly;?>>
                      </div>
                      <label for="claimstype_seqno" class="col-sm-2 control-label">Seq No</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="claimstype_seqno" name="claimstype_seqno" placeholder="Seq No" value = "<?php echo $this->claimstype_seqno;?>" <?php echo $readonly;?>>
                      </div>
                    </div>  
                    <div class="form-group">
                      <label for="claimstype_maxamt" class="col-sm-2 control-label">Claims Limits </label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="claimstype_maxamt" name="claimstype_maxamt" placeholder="Claims Limits" value = "<?php echo $this->claimstype_maxamt;?>" <?php echo $readonly;?>>
                      </div>

                    </div>
                    <div class="form-group">
                      <label for="claimstype_desc" class="col-sm-2 control-label">Description</label>
                      <div class="col-sm-3">
                      <textarea id="rclaimstype_desc" name="claimstype_desc" class="form-control" rows="3" placeholder="Description" <?php echo $readonly;?>><?php echo $this->claimstype_desc;?></textarea>
                      </div>
                        <label for="claimstype_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-3">
                             <select class="form-control" id="claimstype_status" name="claimstype_status">
                                  <option value = '0' <?php if($this->claimstype_status == 0){ echo 'SELECTED';}?>>In-Active</option>
                                  <option value = '1' <?php if($this->claimstype_status == 1){ echo 'SELECTED';}?>>Active</option>
                             </select>
                        </div>
                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->claimstype_id;?>" name = "claimstype_id"/>
                    <?php 
                    if($this->claimstype_id > 0){
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
    <title>Claims Type Management</title>
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
            <h1>Claims Type Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Claims Type Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='claimstype.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="claimstype_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:15%'>Claims Type</th>
                        <th style = 'width:15%'>Claims Limits</th>
                        <th style = 'width:40%'>Description</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT claimstype.*
                              FROM db_claimstype claimstype 
                              WHERE claimstype.claimstype_id > 0 
                              ORDER BY claimstype.claimstype_code";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['claimstype_code'];?></td>
                            <td><?php echo $row['claimstype_maxamt'];?></td>
                            <td><?php echo $row['claimstype_desc'];?></td>
                            <td><?php 
                            if($row['claimstype_status'] == 1){ 
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
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'claimstype.php?action=edit&claimstype_id=<?php echo $row['claimstype_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('claimstype.php?action=delete&claimstype_id=<?php echo $row['claimstype_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:15%'>Claims Type</th>
                        <th style = 'width:15%'>Claims Limits</th>
                        <th style = 'width:40%'>Description</th>
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
        $('#claimstype_table').DataTable({
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
