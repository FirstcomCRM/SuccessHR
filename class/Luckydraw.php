<?php
/*
 * To change this tluckydrawate, choose Tools | Tluckydrawates
 * and open the tluckydrawate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Luckydraw {

    public function Luckydraw(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('luckydraw_code','luckydraw_desc','luckydraw_mt_title',
                             'luckydraw_mt_keyword','luckydraw_mt_desc','luckydraw_remark',
                             'luckydraw_shortdesc','rluckydraw_id','luckydraw_status',
                             'luckydraw_stars','luckydraw_end_date','luckydraw_url');
        $table_value = array($this->luckydraw_code,$this->luckydraw_desc,$this->luckydraw_mt_title,
                             $this->luckydraw_mt_keyword,$this->luckydraw_mt_desc,$this->luckydraw_remark,
                             $this->luckydraw_shortdesc,$this->rluckydraw_id,$this->luckydraw_status,
                             $this->luckydraw_stars,$this->luckydraw_end_date,$this->luckydraw_url);
        $remark = "Insert Lucky Draw.";
        if(!$this->save->SaveData($table_field,$table_value,'db_luckydraw','luckydraw_id',$remark)){
           return false;
        }else{
           $this->luckydraw_id = $this->save->lastInsert_id;
           $this->create_image($this->luckydraw_id,"luckydraw","db_luckydraw");
           return true;
        }
    }
    public function update(){
        $table_field = array('luckydraw_code','luckydraw_desc','luckydraw_mt_title',
                             'luckydraw_mt_keyword','luckydraw_mt_desc','luckydraw_remark',
                             'luckydraw_shortdesc','rluckydraw_id','luckydraw_status',
                             'luckydraw_stars','luckydraw_end_date','luckydraw_url');
        $table_value = array($this->luckydraw_code,$this->luckydraw_desc,$this->luckydraw_mt_title,
                             $this->luckydraw_mt_keyword,$this->luckydraw_mt_desc,$this->luckydraw_remark,
                             $this->luckydraw_shortdesc,$this->rluckydraw_id,$this->luckydraw_status,
                             $this->luckydraw_stars,$this->luckydraw_end_date,$this->luckydraw_url);
        
        $remark = "Update Lucky Draw.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_luckydraw','luckydraw_id',$remark,$this->luckydraw_id)){
           return false;
        }else{
           $this->create_image($this->luckydraw_id,"luckydraw","db_luckydraw"); 
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
    public function fetchLuckydrawDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_luckydraw WHERE luckydraw_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->luckydraw_id = $row['luckydraw_id'];
            $this->luckydraw_code = $row['luckydraw_code'];
            $this->luckydraw_desc = $row['luckydraw_desc'];
            $this->luckydraw_mt_title = $row['luckydraw_mt_title'];
            $this->luckydraw_mt_keyword = $row['luckydraw_mt_keyword'];
            $this->luckydraw_mt_desc = $row['luckydraw_mt_desc'];
            $this->luckydraw_remark = $row['luckydraw_remark'];
            $this->luckydraw_status = $row['luckydraw_status'];
            $this->rluckydraw_id = $row['rluckydraw_id'];
            $this->luckydraw_shortdesc = $row['luckydraw_shortdesc'];
            $this->luckydraw_stars = $row['luckydraw_stars'];
            $this->luckydraw_end_date = $row['luckydraw_end_date'];
            $this->luckydraw_url = $row['luckydraw_url'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_luckydraw"," WHERE luckydraw_id = '$this->luckydraw_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->luckydraw_status = 1;
        }
//        $readonly = " READONLY";
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lucky Draw Management</title>
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
        CKEDITOR.replace('luckydraw_desc');
    
    
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
            <h1>Lucky Draw Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->luckydraw_id > 0){ echo "Update Lucky Draw";}else{ echo "Create New Lucky Draw";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='luckydraw.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='luckydraw.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'luckydraw_form' class="form-horizontal" action = 'luckydraw.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="luckydraw_code" class="col-sm-2 control-label">Lucky Draw Title</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="luckydraw_code" name="luckydraw_code" placeholder="Lucky Draw" value = "<?php echo $this->luckydraw_code;?>" <?php echo $readonly;?>>
                      </div>
                      <label for="luckydraw_stars" class="col-sm-2 control-label">Lucky Draw Stars</label>
                      <div class="col-sm-3">
                            <div class="rateit" to_field = "design" data-rateit-backingfld="#rate_design"></div>
                            <input type = 'hidden' id = 'rate_design' name = 'luckydraw_stars' value = "<?php echo $this->luckydraw_stars;?>"/>
                      </div>
                    </div>   
                    <div class="form-group">
                        <label for="luckydraw_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-3">
                             <select class="form-control" id="luckydraw_status" name="luckydraw_status">
                                  <option value = '0' <?php if($this->luckydraw_status == 0){ echo 'SELECTED';}?>>In-Active</option>
                                  <option value = '1' <?php if($this->luckydraw_status == 1){ echo 'SELECTED';}?>>Active</option>
                             </select>
                        </div>
                        <label for="luckydraw_url" class="col-sm-2 control-label">Lucky Draw Website</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="luckydraw_url" name="luckydraw_url" value = "<?php echo $this->luckydraw_url;?>" placeholder="Lucky Draw Website" <?php echo $readonly;?>>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="luckydraw_end_date" class="col-sm-2 control-label">End Date</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control datepicker" id="luckydraw_end_date" name="luckydraw_end_date" value = "<?php echo $this->luckydraw_end_date;?>" placeholder="End Date" <?php echo $readonly;?>>
                        </div>
                        <label for="luckydraw_mt_title" class="col-sm-2 control-label">Meta Tag Title</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="luckydraw_mt_title" name="luckydraw_mt_title" value = "<?php echo $this->luckydraw_mt_title;?>" placeholder="Meta Tag Title" <?php echo $readonly;?>>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="rluckydraw_desc" class="col-sm-2 control-label">Internal Remark</label>
                      <div class="col-sm-3">
                      <textarea id="rluckydraw_desc" name="luckydraw_remark" class="form-control" rows="3" placeholder="Internal Remark" <?php echo $readonly;?>><?php echo $this->luckydraw_remark;?></textarea>
                      </div>
                        <label for="luckydraw_mt_keyword" class="col-sm-2 control-label">Meta Tag Keyword</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="luckydraw_mt_keyword" name="luckydraw_mt_keyword" value = "<?php echo $this->luckydraw_mt_keyword;?>" placeholder="Meta Tag Keyword" <?php echo $readonly;?>>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="luckydraw_shortdesc" class="col-sm-2 control-label">Short Description</label>
                      <div class="col-sm-3">
                      <textarea id="luckydraw_shortdesc" name="luckydraw_shortdesc" class="form-control" rows="3" placeholder="Short Description" <?php echo $readonly;?>><?php echo $this->luckydraw_shortdesc;?></textarea>
                      </div>
                      <label for="luckydraw_mt_desc" class="col-sm-2 control-label">Meta Tag Description</label>
                      <div class="col-sm-3">
                      <textarea id="rluckydraw_desc" name="luckydraw_mt_desc" class="form-control" rows="3" placeholder="Meta Tag Description" <?php echo $readonly;?>><?php echo $this->luckydraw_mt_desc;?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="luckydraw_shortdesc" class="col-sm-2 control-label">Image</label>
                      <div class="box-body pad col-sm-8">
                          <input type ='file' name = "input_file"/><p class="help-block">237px x 200px</p>
                          <br>
                          <?php
                          if(file_exists("../upload/luckydraw/$this->luckydraw_id.jpeg")){
                              echo "<a href = '../upload/luckydraw/$this->luckydraw_id.jpeg' target = '_blank'><img style = 'width:100%;' src = '../upload/luckydraw/$this->luckydraw_id.jpeg'/></a>";
                          }
                          ?>
                      </div>    
                    </div>
                    <div class="form-group">
                      <label for="luckydraw_desc" class="col-sm-2 control-label">Lucky Draw Description</label>
                   </div>   
                   <div class="box-body pad">
                        <textarea id="luckydraw_desc" name="luckydraw_desc"><?php echo $this->luckydraw_desc;?></textarea>
                   </div>
                      
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $this->rluckydraw_id;?>" name = "rluckydraw_id"/>
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->luckydraw_id;?>" name = "luckydraw_id"/>
                    <?php 
                    if($this->luckydraw_id > 0){
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
    <title>Lucky Draw Management</title>
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
            <h1>Lucky Draw Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Lucky Draw Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='luckydraw.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="luckydraw_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:15%'>Lucky Draw Code</th>
                        <th style = 'width:50%'>Remark</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT luckydraw.*
                              FROM db_luckydraw luckydraw 
                              WHERE luckydraw.luckydraw_id > 0 
                              ORDER BY luckydraw.insertDateTime DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['luckydraw_code'];?></td>
                            <td><?php echo $row['luckydraw_remark'];?></td>
                            <td><?php 
                            if($row['luckydraw_status'] == 1){ 
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
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'luckydraw.php?action=edit&luckydraw_id=<?php echo $row['luckydraw_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('luckydraw.php?action=delete&luckydraw_id=<?php echo $row['luckydraw_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:15%'>Lucky Draw Code</th>
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
        $('#luckydraw_table').DataTable({
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