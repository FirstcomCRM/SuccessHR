<?php
/*
 * To change this tadditionaltypeate, choose Tools | Tadditionaltypeates
 * and open the tadditionaltypeate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Additionaltype {

    public function Additionaltype(){

        

    }
    public function create(){
        $table_field = array('additionaltype_code','additionaltype_desc','additionaltype_seqno','additionaltype_status');
        $table_value = array($this->additionaltype_code,$this->additionaltype_desc,$this->additionaltype_seqno,$this->additionaltype_status);
        $remark = "Insert Additionaltype.";
        if(!$this->save->SaveData($table_field,$table_value,'db_additionaltype','additionaltype_id',$remark)){
           return false;
        }else{
           $this->additionaltype_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $table_field = array('additionaltype_code','additionaltype_desc','additionaltype_seqno','additionaltype_status');
        $table_value = array($this->additionaltype_code,$this->additionaltype_desc,$this->additionaltype_seqno,$this->additionaltype_status);
        
        $remark = "Update Additionaltype.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_additionaltype','additionaltype_id',$remark,$this->additionaltype_id)){
           return false;
        }else{
           return true;
        }
    }
    public function fetchAdditionaltypeDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_additionaltype WHERE additionaltype_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->additionaltype_id = $row['additionaltype_id'];
            $this->additionaltype_code = $row['additionaltype_code'];
            $this->additionaltype_desc = $row['additionaltype_desc'];
            $this->additionaltype_seqno = $row['additionaltype_seqno'];
            $this->additionaltype_status = $row['additionaltype_status'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_additionaltype"," WHERE additionaltype_id = '$this->additionaltype_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->additionaltype_status = 1;
        }

    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Additional Type Management</title>
    <?php
    include_once 'css.php';
    include_once 'js.php';
    ?>

    <script src="dist/ckeditor/ckeditor.js"></script>
    <script>
    $(document).ready(function() {
//        CKEDITOR.replace('additionaltype_desc');
    
    
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
            <h1>Additional Type Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->additionaltype_id > 0){ echo "Update Additional Type";}else{ echo "Create New Additional Type";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='additionaltype.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='additionaltype.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'additionaltype_form' class="form-horizontal" action = 'additionaltype.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="additionaltype_code" class="col-sm-2 control-label">Additional Type Code</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="additionaltype_code" name="additionaltype_code" placeholder="Additionaltype Code" value = "<?php echo $this->additionaltype_code;?>" <?php echo $readonly;?>>
                      </div>
                      <label for="additionaltype_seqno" class="col-sm-2 control-label">Seq No</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="additionaltype_seqno" name="additionaltype_seqno" placeholder="Seq No" value = "<?php echo $this->additionaltype_seqno;?>" <?php echo $readonly;?>>
                      </div>
                    </div>   
                    <div class="form-group">
                      <label for="additionaltype_desc" class="col-sm-2 control-label">Description</label>
                      <div class="col-sm-3">
                      <textarea id="radditionaltype_desc" name="additionaltype_desc" class="form-control" rows="3" placeholder="Description" <?php echo $readonly;?>><?php echo $this->additionaltype_desc;?></textarea>
                      </div>
                        <label for="additionaltype_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-3">
                             <select class="form-control" id="additionaltype_status" name="additionaltype_status">
                                  <option value = '0' <?php if($this->additionaltype_status == 0){ echo 'SELECTED';}?>>In-Active</option>
                                  <option value = '1' <?php if($this->additionaltype_status == 1){ echo 'SELECTED';}?>>Active</option>
                             </select>
                        </div>
                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->additionaltype_id;?>" name = "additionaltype_id"/>
                    <?php 
                    if($this->additionaltype_id > 0){
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
    <title>Additional Type Management</title>
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
            <h1>Additional Type Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Additional Type Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='additionaltype.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="additionaltype_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:15%'>Additional Type</th>
                        <th style = 'width:50%'>Description</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT additionaltype.*
                              FROM db_additionaltype additionaltype 
                              WHERE additionaltype.additionaltype_id > 0 
                              ORDER BY additionaltype.additionaltype_code";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['additionaltype_code'];?></td>
                            <td><?php echo $row['additionaltype_desc'];?></td>
                            <td><?php 
                            if($row['additionaltype_status'] == 1){ 
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
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'additionaltype.php?action=edit&additionaltype_id=<?php echo $row['additionaltype_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('additionaltype.php?action=delete&additionaltype_id=<?php echo $row['additionaltype_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:15%'>Additional Type</th>
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
        $('#additionaltype_table').DataTable({
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
