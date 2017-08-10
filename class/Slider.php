<?php
/*
 * To change this tsliderate, choose Tools | Tsliderates
 * and open the tsliderate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Slider {

    public function Slider(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('slider_title','slider_remark','slider_url','slider_status','slider_isprimary');
        $table_value = array($this->slider_title,$this->slider_remark,$this->slider_url,$this->slider_status,$this->slider_isprimary);
        $remark = "Insert Slider.";
        if(!$this->save->SaveData($table_field,$table_value,'db_slider','slider_id',$remark)){
           return false;
        }else{
           $this->slider_id = $this->save->lastInsert_id;
           $this->create_image($this->slider_id,"slider","db_slider");
           return true;
        }
    }
    public function update(){
        $table_field = array('slider_title','slider_remark','slider_url','slider_status','slider_isprimary');
        $table_value = array($this->slider_title,$this->slider_remark,$this->slider_url,$this->slider_status,$this->slider_isprimary);
        $remark = "Update Slider.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_slider','slider_id',$remark,$this->slider_id)){
           return false;
        }else{
           $this->create_image($this->slider_id,"slider","db_slider"); 
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
    public function fetchSliderDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_slider WHERE slider_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->slider_id = $row['slider_id'];
            $this->slider_title = $row['slider_title'];
            $this->slider_remark = $row['slider_remark'];
            $this->slider_status = $row['slider_status'];
            $this->slider_url = $row['slider_url'];
            $this->slider_isprimary = $row['slider_isprimary'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_slider"," WHERE slider_id = '$this->slider_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->slider_status = 1;
        }
//        $readonly = " READONLY";
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Slider Banner Management</title>
    <?php
    include_once 'css.php';
    ?>   
    <?php
    include_once 'js.php';

    ?>
    <script src="dist/ckeditor/ckeditor.js"></script>
    <script>
    $(document).ready(function() {
    
    
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
            <h1>Slider Banner Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->slider_id > 0){ echo "Update Sliderertisement";}else{ echo "Create New Sliderertisement";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='slider.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='slider.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'slider_form' class="form-horizontal" action = 'slider.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="slider_title" class="col-sm-2 control-label">Title</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control " id="slider_title" name="slider_title" placeholder="Title" value = "<?php echo $this->slider_title;?>" <?php echo $readonly;?>>
                      </div>
                        <label for="slider_isprimary" class="col-sm-2 control-label">Is Primary</label>
                        <div class="col-sm-3">
                             <select class="form-control" id="slider_isprimary" name="slider_isprimary">
                                  <option value = '0' <?php if($this->slider_isprimary == 0){ echo 'SELECTED';}?>>No</option>
                                  <option value = '1' <?php if($this->slider_isprimary == 1){ echo 'SELECTED';}?>>Yes</option>
                             </select>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label for="slider_url" class="col-sm-2 control-label">Link URL</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="slider_url" name="slider_url" value = "<?php echo $this->slider_url;?>" placeholder="Link URL" <?php echo $readonly;?>>
                        </div>
                        <label for="slider_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-3">
                             <select class="form-control" id="slider_status" name="slider_status">
                                  <option value = '0' <?php if($this->slider_status == 0){ echo 'SELECTED';}?>>In-Active</option>
                                  <option value = '1' <?php if($this->slider_status == 1){ echo 'SELECTED';}?>>Active</option>
                             </select>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="rslider_desc" class="col-sm-2 control-label">Internal Remark</label>
                      <div class="col-sm-3">
                      <textarea id="rslider_desc" name="slider_remark" class="form-control" rows="3" placeholder="Internal Remark" <?php echo $readonly;?>><?php echo $this->slider_remark;?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="slider_shortdesc" class="col-sm-2 control-label">Image</label>
                      <div class="box-body pad col-sm-8">
                          <input type ='file' name = "input_file"/>
                          <p class = 'help-block'>900px x 400px</p>
                          <br>
                          <?php
                          if(file_exists("../upload/slider/$this->slider_id.jpeg")){
                              echo "<a href = '../upload/slider/$this->slider_id.jpeg' target = '_blank'><img style = 'height:210px;width:210px' src = '../upload/slider/$this->slider_id.jpeg'/></a>";
                          }
                          ?>
                      </div>    
                    </div>
                      
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $this->rslider_id;?>" name = "rslider_id"/>
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->slider_id;?>" name = "slider_id"/>
                    <?php 
                    if($this->slider_id > 0){
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
    <title>Slider Banner Management</title>
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
            <h1>Slider Banner Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Slider Banner Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='slider.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="slider_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:10%'>Title</th>
                        <th style = 'width:30%'>Image</th>
                        <th style = 'width:30%'>Remark</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:13%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT slider.*
                              FROM db_slider slider
                              WHERE slider.slider_id > 0 ORDER BY slider.insertDateTime DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['slider_title'];?></td>
                            <td><?php echo "<a href = '../upload/slider/{$row['slider_id']}.jpeg' target = '_blank'><img style = 'height:300px;width:400px' src='../upload/slider/{$row['slider_id']}.jpeg' /></a>";?></td>
                            <td><?php echo $row['slider_remark'];?></td>
                            <td><?php 
                            if($row['slider_status'] == 1){ 
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
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'slider.php?action=edit&slider_id=<?php echo $row['slider_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('slider.php?action=delete&slider_id=<?php echo $row['slider_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:10%'>Title</th>
                        <th style = 'width:30%'>Image</th>
                        <th style = 'width:30%'>Remark</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:13%'></th>
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
        $('#slider_table').DataTable({
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
