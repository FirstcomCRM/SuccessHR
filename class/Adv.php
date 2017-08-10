<?php
/*
 * To change this tadvate, choose Tools | Tadvates
 * and open the tadvate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Adv {

    public function Adv(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('adv_datefrom','adv_dateto','adv_remark','radv_id',
                             'adv_url','adv_status');
        $table_value = array($this->adv_datefrom,$this->adv_dateto,$this->adv_remark,$this->radv_id,
                             $this->adv_url,$this->adv_status);
        $remark = "Insert Advertisement.";
        if(!$this->save->SaveData($table_field,$table_value,'db_adv','adv_id',$remark)){
           return false;
        }else{
           $this->adv_id = $this->save->lastInsert_id;
           $this->create_image($this->adv_id,"adv","db_adv");
           return true;
        }
    }
    public function update(){
        $table_field = array('adv_datefrom','adv_dateto','adv_remark','radv_id',
                             'adv_url','adv_status');
        $table_value = array($this->adv_datefrom,$this->adv_dateto,$this->adv_remark,$this->radv_id,
                             $this->adv_url,$this->adv_status);
        
        $remark = "Update Advertisement.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_adv','adv_id',$remark,$this->adv_id)){
           return false;
        }else{
           $this->create_image($this->adv_id,"adv","db_adv"); 
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
    public function fetchAdvDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_adv WHERE adv_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->adv_id = $row['adv_id'];
            $this->adv_datefrom = $row['adv_datefrom'];
            $this->adv_dateto = $row['adv_dateto'];
            $this->adv_remark = $row['adv_remark'];
            $this->radv_id = $row['radv_id'];
            $this->adv_status = $row['adv_status'];
            $this->adv_url = $row['adv_url'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_adv"," WHERE adv_id = '$this->adv_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->adv_status = 1;
        }
//        $readonly = " READONLY";
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Advertisement Management</title>
    <?php
    include_once 'css.php';
    ?>   
    <?php
    include_once 'js.php';
    $this->adv_datefrom = system_date;
    $this->adv_dateto = system_date;
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
            <h1>Advertisement Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->adv_id > 0){ echo "Update Advertisement";}else{ echo "Create New Advertisement";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='adv.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='adv.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'adv_form' class="form-horizontal" action = 'adv.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="adv_datefrom" class="col-sm-2 control-label">Date From</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control datepicker" id="adv_datefrom" name="adv_datefrom" placeholder="Date From" value = "<?php echo $this->adv_datefrom;?>" <?php echo $readonly;?>>
                      </div>
                        <label for="adv_dateto" class="col-sm-2 control-label">Date To</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control datepicker" id="adv_dateto" name="adv_dateto" value = "<?php echo $this->adv_dateto;?>" placeholder="Date To" <?php echo $readonly;?>>
                        </div>
                    </div>   
                    <div class="form-group">
                        <label for="adv_url" class="col-sm-2 control-label">Link URL</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="adv_url" name="adv_url" value = "<?php echo $this->adv_url;?>" placeholder="Link URL" <?php echo $readonly;?>>
                        </div>
                        <label for="adv_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-3">
                             <select class="form-control" id="adv_status" name="adv_status">
                                  <option value = '0' <?php if($this->adv_status == 0){ echo 'SELECTED';}?>>In-Active</option>
                                  <option value = '1' <?php if($this->adv_status == 1){ echo 'SELECTED';}?>>Active</option>
                             </select>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="radv_desc" class="col-sm-2 control-label">Internal Remark</label>
                      <div class="col-sm-3">
                      <textarea id="radv_desc" name="adv_remark" class="form-control" rows="3" placeholder="Internal Remark" <?php echo $readonly;?>><?php echo $this->adv_remark;?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="adv_shortdesc" class="col-sm-2 control-label">Image</label>
                      <div class="box-body pad col-sm-8">
                          <input type ='file' name = "input_file"/>
                          <p class = 'help-block'>210px x 210px</p>
                          <br>
                          <?php
                          if(file_exists("../upload/adv/$this->adv_id.jpeg")){
                              echo "<a href = '../upload/adv/$this->adv_id.jpeg' target = '_blank'><img style = 'height:210px;width:210px' src = '../upload/adv/$this->adv_id.jpeg'/></a>";
                          }
                          ?>
                      </div>    
                    </div>
                      
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $this->radv_id;?>" name = "radv_id"/>
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->adv_id;?>" name = "adv_id"/>
                    <?php 
                    if($this->adv_id > 0){
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
    <title>Advertisement Management</title>
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
            <h1>Advertisement Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Advertisement Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='adv.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="adv_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:10%'>Date From</th>
                        <th style = 'width:10%'>Date To</th>
                        <th style = 'width:15%'>Contact</th>
                        <th style = 'width:10%'>Tag</th>
                        <th style = 'width:30%'>Remark</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT adv.*,radv.radv_email,radv.radv_name
                              FROM db_adv adv 
                              LEFT JOIN db_radv radv ON radv.radv_id = adv.radv_id
                              WHERE adv.adv_id > 0 
                              ORDER BY adv.insertDateTime DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['adv_datefrom'];?></td>
                            <td><?php echo $row['adv_dateto'];?></td>
                            <td><?php echo $row['radv_email'];?></td>
                            <td><a href = 'radv.php?action=edit&radv_id=<?php echo $row['radv_id'];?>' target = '_blank'><?php echo $row['radv_name'];?></a></td>
                            <td><?php echo $row['adv_remark'];?></td>
                            <td><?php 
                            if($row['adv_status'] == 1){ 
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
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'adv.php?action=edit&adv_id=<?php echo $row['adv_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('adv.php?action=delete&adv_id=<?php echo $row['adv_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:10%'>Date From</th>
                        <th style = 'width:10%'>Date To</th>
                        <th style = 'width:15%'>Contact</th>
                        <th style = 'width:10%'>Tag</th>
                        <th style = 'width:30%'>Remark</th>
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
        $('#adv_table').DataTable({
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
