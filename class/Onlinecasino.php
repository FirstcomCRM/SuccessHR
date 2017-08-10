<?php
/*
 * To change this tonlinecasinoate, choose Tools | Tonlinecasinoates
 * and open the tonlinecasinoate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Onlinecasino {

    public function Onlinecasino(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('onlinecasino_code','onlinecasino_desc','onlinecasino_mt_title',
                             'onlinecasino_mt_keyword','onlinecasino_mt_desc','onlinecasino_remark',
                             'onlinecasino_shortdesc','ronlinecasino_id','onlinecasino_stars',
                             'onlinecasino_status','onlinecasino_url');
        $table_value = array($this->onlinecasino_code,$this->onlinecasino_desc,$this->onlinecasino_mt_title,
                             $this->onlinecasino_mt_keyword,$this->onlinecasino_mt_desc,$this->onlinecasino_remark,
                             $this->onlinecasino_shortdesc,$this->ronlinecasino_id,$this->onlinecasino_stars,
                             $this->onlinecasino_status,$this->onlinecasino_url);
        $remark = "Insert Online Casino.";
        if(!$this->save->SaveData($table_field,$table_value,'db_onlinecasino','onlinecasino_id',$remark)){
           return false;
        }else{
           $this->onlinecasino_id = $this->save->lastInsert_id;
           $this->create_image($this->onlinecasino_id,"onlinecasino","db_onlinecasino");
           return true;
        }
    }
    public function update(){
        $table_field = array('onlinecasino_code','onlinecasino_desc','onlinecasino_mt_title',
                             'onlinecasino_mt_keyword','onlinecasino_mt_desc','onlinecasino_remark',
                             'onlinecasino_shortdesc','ronlinecasino_id','onlinecasino_stars',
                             'onlinecasino_status','onlinecasino_url');
        $table_value = array($this->onlinecasino_code,$this->onlinecasino_desc,$this->onlinecasino_mt_title,
                             $this->onlinecasino_mt_keyword,$this->onlinecasino_mt_desc,$this->onlinecasino_remark,
                             $this->onlinecasino_shortdesc,$this->ronlinecasino_id,$this->onlinecasino_stars,
                             $this->onlinecasino_status,$this->onlinecasino_url);
        
        $remark = "Update Online Casino.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_onlinecasino','onlinecasino_id',$remark,$this->onlinecasino_id)){
           return false;
        }else{
           $this->create_image($this->onlinecasino_id,"onlinecasino","db_onlinecasino"); 
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
    public function fetchOnlinecasinoDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_onlinecasino WHERE onlinecasino_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->onlinecasino_id = $row['onlinecasino_id'];
            $this->onlinecasino_code = $row['onlinecasino_code'];
            $this->onlinecasino_desc = $row['onlinecasino_desc'];
            $this->onlinecasino_mt_title = $row['onlinecasino_mt_title'];
            $this->onlinecasino_mt_keyword = $row['onlinecasino_mt_keyword'];
            $this->onlinecasino_mt_desc = $row['onlinecasino_mt_desc'];
            $this->onlinecasino_remark = $row['onlinecasino_remark'];
            $this->onlinecasino_status = $row['onlinecasino_status'];
            $this->ronlinecasino_id = $row['ronlinecasino_id'];
            $this->onlinecasino_shortdesc = $row['onlinecasino_shortdesc'];
            $this->onlinecasino_stars = $row['onlinecasino_stars'];
            $this->onlinecasino_url = $row['onlinecasino_url'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_onlinecasino"," WHERE onlinecasino_id = '$this->onlinecasino_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->onlinecasino_status = 1;
        }
//        $readonly = " READONLY";
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Online Casino Management</title>
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
        CKEDITOR.replace('onlinecasino_desc');
        $('.rateit').rateit({ step: 1});
        $(".rateit").bind('rated', function (event, value) { $('#rate_'+$(this).attr('to_field')).val(value); });
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
            <h1>Online Casino Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->onlinecasino_id > 0){ echo "Update Online Casino";}else{ echo "Create New Online Casino";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='onlinecasino.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='onlinecasino.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'onlinecasino_form' class="form-horizontal" action = 'onlinecasino.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="onlinecasino_code" class="col-sm-2 control-label">Online Casino Title</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="onlinecasino_code" name="onlinecasino_code" placeholder="Online Casino" value = "<?php echo $this->onlinecasino_code;?>" <?php echo $readonly;?>>
                      </div>
                      <label for="onlinecasino_stars" class="col-sm-2 control-label">Online Casino Stars</label>
                      <div class="col-sm-3">
                            <div class="rateit" to_field = "design" data-rateit-backingfld="#rate_design"></div>
                            <input type = 'hidden' id = 'rate_design' name = 'onlinecasino_stars' value = "<?php echo $this->onlinecasino_stars;?>"/>
                      </div>
                    </div>
                    <div class="form-group">  
                        <label for="onlinecasino_url" class="col-sm-2 control-label">Online Casino Website</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="onlinecasino_url" name="onlinecasino_url" value = "<?php echo $this->onlinecasino_url;?>" placeholder="Online Casino Website" <?php echo $readonly;?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="onlinecasino_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-3">
                             <select class="form-control" id="onlinecasino_status" name="onlinecasino_status">
                                  <option value = '0' <?php if($this->onlinecasino_status == 0){ echo 'SELECTED';}?>>In-Active</option>
                                  <option value = '1' <?php if($this->onlinecasino_status == 1){ echo 'SELECTED';}?>>Active</option>
                             </select>
                        </div>
                        <label for="onlinecasino_mt_title" class="col-sm-2 control-label">Meta Tag Title</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="onlinecasino_mt_title" name="onlinecasino_mt_title" value = "<?php echo $this->onlinecasino_mt_title;?>" placeholder="Meta Tag Title" <?php echo $readonly;?>>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="ronlinecasino_desc" class="col-sm-2 control-label">Internal Remark</label>
                      <div class="col-sm-3">
                      <textarea id="ronlinecasino_desc" name="onlinecasino_remark" class="form-control" rows="3" placeholder="Internal Remark" <?php echo $readonly;?>><?php echo $this->onlinecasino_remark;?></textarea>
                      </div>
                        <label for="onlinecasino_mt_keyword" class="col-sm-2 control-label">Meta Tag Keyword</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="onlinecasino_mt_keyword" name="onlinecasino_mt_keyword" value = "<?php echo $this->onlinecasino_mt_keyword;?>" placeholder="Meta Tag Keyword" <?php echo $readonly;?>>
                        </div>

                    </div>
                    <div class="form-group">
                      <label for="onlinecasino_shortdesc" class="col-sm-2 control-label">Short Description</label>
                      <div class="col-sm-3">
                      <textarea id="onlinecasino_shortdesc" name="onlinecasino_shortdesc" class="form-control" rows="3" placeholder="Short Description" <?php echo $readonly;?>><?php echo $this->onlinecasino_shortdesc;?></textarea>
                      </div>
                      <label for="onlinecasino_mt_desc" class="col-sm-2 control-label">Meta Tag Description</label>
                      <div class="col-sm-3">
                      <textarea id="ronlinecasino_desc" name="onlinecasino_mt_desc" class="form-control" rows="3" placeholder="Meta Tag Description" <?php echo $readonly;?>><?php echo $this->onlinecasino_mt_desc;?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="onlinecasino_shortdesc" class="col-sm-2 control-label">Image</label>
                      <div class="box-body pad col-sm-8">
                          <input  type ='file' name = "input_file"/><p class="help-block">237px x 200px</p>
                          <br>
                          <?php
                          if(file_exists("../upload/onlinecasino/$this->onlinecasino_id.jpeg")){
                              echo "<a href = '../upload/onlinecasino/$this->onlinecasino_id.jpeg' target = '_blank'><img style='width:100%;' src = '../upload/onlinecasino/$this->onlinecasino_id.jpeg'/></a>";
                          }
                          ?>
                      </div>    
                    </div>
                    <div class="form-group">
                      <label for="onlinecasino_desc" class="col-sm-2 control-label">Online Casino Description</label>
                   </div>   
                   <div class="box-body pad">
                        <textarea id="onlinecasino_desc" name="onlinecasino_desc"><?php echo $this->onlinecasino_desc;?></textarea>
                   </div>
                      
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $this->ronlinecasino_id;?>" name = "ronlinecasino_id"/>
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->onlinecasino_id;?>" name = "onlinecasino_id"/>
                    <?php 
                    if($this->onlinecasino_id > 0){
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
    <title>Online Casino Management</title>
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
            <h1>Online Casino Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Online Casino Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='onlinecasino.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="onlinecasino_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:15%'>Online Casino Code</th>
                        <th style = 'width:50%'>Remark</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT onlinecasino.*
                              FROM db_onlinecasino onlinecasino 
                              WHERE onlinecasino.onlinecasino_id > 0 
                              ORDER BY onlinecasino.insertDateTime DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['onlinecasino_code'];?></td>
                            <td><?php echo $row['onlinecasino_remark'];?></td>
                            <td><?php 
                            if($row['onlinecasino_status'] == 1){ 
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
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'onlinecasino.php?action=edit&onlinecasino_id=<?php echo $row['onlinecasino_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('onlinecasino.php?action=delete&onlinecasino_id=<?php echo $row['onlinecasino_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:15%'>Online Casino Code</th>
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
        $('#onlinecasino_table').DataTable({
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
