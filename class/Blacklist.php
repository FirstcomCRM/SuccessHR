<?php
/*
 * To change this tblacklistate, choose Tools | Tblacklistates
 * and open the tblacklistate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Blacklist {

    public function Blacklist(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('blacklist_code','blacklist_desc','blacklist_mt_title',
                             'blacklist_mt_keyword','blacklist_mt_desc','blacklist_remark',
                             'blacklist_shortdesc','rblacklist_id','blacklist_status',
                             'blacklist_stars','blacklist_url');
        $table_value = array($this->blacklist_code,$this->blacklist_desc,$this->blacklist_mt_title,
                             $this->blacklist_mt_keyword,$this->blacklist_mt_desc,$this->blacklist_remark,
                             $this->blacklist_shortdesc,$this->rblacklist_id,$this->blacklist_status,
                             $this->blacklist_stars,$this->blacklist_url);
        $remark = "Insert Black List.";
        if(!$this->save->SaveData($table_field,$table_value,'db_blacklist','blacklist_id',$remark)){
           return false;
        }else{
           $this->blacklist_id = $this->save->lastInsert_id;
           $this->create_image($this->blacklist_id,"blacklist","db_blacklist");
           return true;
        }
    }
    public function update(){
        $table_field = array('blacklist_code','blacklist_desc','blacklist_mt_title',
                             'blacklist_mt_keyword','blacklist_mt_desc','blacklist_remark',
                             'blacklist_shortdesc','rblacklist_id','blacklist_status',
                             'blacklist_stars','blacklist_url');
        $table_value = array($this->blacklist_code,$this->blacklist_desc,$this->blacklist_mt_title,
                             $this->blacklist_mt_keyword,$this->blacklist_mt_desc,$this->blacklist_remark,
                             $this->blacklist_shortdesc,$this->rblacklist_id,$this->blacklist_status,
                             $this->blacklist_stars,$this->blacklist_url);
        
        $remark = "Update Black List.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_blacklist','blacklist_id',$remark,$this->blacklist_id)){
           return false;
        }else{
           $this->create_image($this->blacklist_id,"blacklist","db_blacklist"); 
           return true;
        }
    }
    public function pictureManagement($ref_id,$folder_name){
        if(!file_exists("../upload/$folder_name")){
           mkdir("../upload/$folder_name/");
        }
        $isimage = false;
        if($this->image_input['type'] == 'image/png' || $this->image_input['type'] == 'image/jpeg' || $this->image_input['type'] == 'image/gif'){
           $isimage = true;
        }
        if($this->image_input['size'] > 0 && $isimage == true){
            if($this->action == 'update'){
                unlink("../upload/$folder_name/$ref_id.jpeg");
            }
                move_uploaded_file($this->image_input['tmp_name'],"../upload/$folder_name/$ref_id.jpeg");
        }
    }
    public function create_image($ref_id,$folder_name,$db_name){
        $table_field = array('ref_table','ref_id','image','status');
        $table_value = array($db_name,$ref_id,$this->image_input['name'],1);
        $remark = "Insert $db_name's Image.";
        if(!$this->save->SaveData($table_field,$table_value,'db_image','image_id',$remark)){
           return false;
        }else{
           $this->image_id = $this->save->lastInsert_id;
           $this->pictureManagement($ref_id,$folder_name);
           return true;
        }
    }
    public function fetchBlacklistDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_blacklist WHERE blacklist_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->blacklist_id = $row['blacklist_id'];
            $this->blacklist_code = $row['blacklist_code'];
            $this->blacklist_desc = $row['blacklist_desc'];
            $this->blacklist_mt_title = $row['blacklist_mt_title'];
            $this->blacklist_mt_keyword = $row['blacklist_mt_keyword'];
            $this->blacklist_mt_desc = $row['blacklist_mt_desc'];
            $this->blacklist_remark = $row['blacklist_remark'];
            $this->blacklist_status = $row['blacklist_status'];
            $this->rblacklist_id = $row['rblacklist_id'];
            $this->blacklist_shortdesc = $row['blacklist_shortdesc'];
            $this->blacklist_stars = $row['blacklist_stars'];
            $this->blacklist_url = $row['blacklist_url'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_blacklist"," WHERE blacklist_id = '$this->blacklist_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->blacklist_status = 1;
        }
//        $readonly = " READONLY";
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Black List Management</title>
    <?php
    include_once 'css.php';
    ?>   
    <?php
    include_once 'js.php';
    
    ?>
     <link rel="stylesheet" href="<?php echo include_webroot;?>dist/css/rateit.css">       
     <script src="<?php echo include_webroot;?>dist/js/jquery.rateit.js"></script>
    <script src="dist/ckeditor/ckeditor.js"></script>
    <script>
    $(document).ready(function() {
        CKEDITOR.replace('blacklist_desc');
    
    
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
            <h1>Black List Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->blacklist_id > 0){ echo "Update Black List";}else{ echo "Create New Black List";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='blacklist.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='blacklist.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'blacklist_form' class="form-horizontal" action = 'blacklist.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="blacklist_code" class="col-sm-2 control-label">Black List Title</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="blacklist_code" name="blacklist_code" placeholder="Black List" value = "<?php echo $this->blacklist_code;?>" <?php echo $readonly;?>>
                      </div>
                      <label for="blacklist_url" class="col-sm-2 control-label">Black List Website</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="blacklist_url" name="blacklist_url" placeholder="Black List Website" value = "<?php echo $this->blacklist_url;?>" <?php echo $readonly;?>>
                      </div>
                    </div>   
                    <div class="form-group">
                        <label for="blacklist_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-3">
                             <select class="form-control" id="blacklist_status" name="blacklist_status">
                                  <option value = '0' <?php if($this->blacklist_status == 0){ echo 'SELECTED';}?>>In-Active</option>
                                  <option value = '1' <?php if($this->blacklist_status == 1){ echo 'SELECTED';}?>>Active</option>
                             </select>
                        </div>
                        <label for="blacklist_mt_title" class="col-sm-2 control-label">Meta Tag Title</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="blacklist_mt_title" name="blacklist_mt_title" value = "<?php echo $this->blacklist_mt_title;?>" placeholder="Meta Tag Title" <?php echo $readonly;?>>
                        </div>

                    </div>
                    <div class="form-group">
                      <label for="rblacklist_desc" class="col-sm-2 control-label">Internal Remark</label>
                      <div class="col-sm-3">
                      <textarea id="rblacklist_desc" name="blacklist_remark" class="form-control" rows="3" placeholder="Internal Remark" <?php echo $readonly;?>><?php echo $this->blacklist_remark;?></textarea>
                      </div>
                        <label for="blacklist_mt_keyword" class="col-sm-2 control-label">Meta Tag Keyword</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="blacklist_mt_keyword" name="blacklist_mt_keyword" value = "<?php echo $this->blacklist_mt_keyword;?>" placeholder="Meta Tag Keyword" <?php echo $readonly;?>>
                        </div>

                    </div>
                    <div class="form-group">
                      <label for="blacklist_shortdesc" class="col-sm-2 control-label">Short Description</label>
                      <div class="col-sm-3">
                      <textarea id="blacklist_shortdesc" name="blacklist_shortdesc" class="form-control" rows="3" placeholder="Short Description" <?php echo $readonly;?>><?php echo $this->blacklist_shortdesc;?></textarea>
                      </div>
                      <label for="blacklist_mt_desc" class="col-sm-2 control-label">Meta Tag Description</label>
                      <div class="col-sm-3">
                      <textarea id="rblacklist_desc" name="blacklist_mt_desc" class="form-control" rows="3" placeholder="Meta Tag Description" <?php echo $readonly;?>><?php echo $this->blacklist_mt_desc;?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="blacklist_shortdesc" class="col-sm-2 control-label">Image</label>
                      <div class="box-body pad col-sm-8">
                          <input type ='file' name = "input_file"/><p class="help-block">237px x 200px</p>
                          <br>
                          <?php
                          if(file_exists("../upload/blacklist/$this->blacklist_id.jpeg")){
                              echo "<a href = '../upload/blacklist/$this->blacklist_id.jpeg' target = '_blank'><img style = 'width:100%;' src = '../upload/blacklist/$this->blacklist_id.jpeg'/></a>";
                          }
                          ?>
                      </div>    
                    </div>
                    <div class="form-group">
                      <label for="blacklist_desc" class="col-sm-2 control-label">Black List Description</label>
                   </div>   
                   <div class="box-body pad">
                        <textarea id="blacklist_desc" name="blacklist_desc"><?php echo $this->blacklist_desc;?></textarea>
                   </div>
                      
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php $this->rblacklist_id;?>" name = "rblacklist_id"/>
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->blacklist_id;?>" name = "blacklist_id"/>
                    <?php 
                    if($this->blacklist_id > 0){
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
    <title>Black List Management</title>
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
            <h1>Black List Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Black List Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='blacklist.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="blacklist_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:15%'>Black List Code</th>
                        <th style = 'width:50%'>Remark</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT blacklist.*
                              FROM db_blacklist blacklist 
                              WHERE blacklist.blacklist_id > 0 
                              ORDER BY blacklist.insertDateTime DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['blacklist_code'];?></td>
                            <td><?php echo $row['blacklist_remark'];?></td>
                            <td><?php 
                            if($row['blacklist_status'] == 1){ 
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
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'blacklist.php?action=edit&blacklist_id=<?php echo $row['blacklist_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('blacklist.php?action=delete&blacklist_id=<?php echo $row['blacklist_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:15%'>Black List Code</th>
                        <th style = 'width:50%'>Remark</th>
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
        $('#blacklist_table').DataTable({
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
