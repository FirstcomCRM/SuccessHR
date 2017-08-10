<?php
/*
 * To change this tsbemailate, choose Tools | Tsbemailates
 * and open the tsbemailate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Sbemail {

    public function Sbemail(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('sbemail_content','sbemail_remark','sbemail_title');
        $table_value = array($this->sbemail_content,$this->sbemail_remark,$this->sbemail_title);
        $remark = "Insert Subscribe Email.";
        if(!$this->save->SaveData($table_field,$table_value,'db_sbemail','sbemail_id',$remark)){
           return false;
        }else{
           $this->sbemail_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $table_field = array('sbemail_content','sbemail_remark','sbemail_title');
        $table_value = array($this->sbemail_content,$this->sbemail_remark,$this->sbemail_title);
        
        $remark = "Update Subscribe Email.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_sbemail','sbemail_id',$remark,$this->sbemail_id)){
           return false;
        }else{
           return true;
        }
    }
    public function fetchSbemailDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_sbemail WHERE sbemail_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->sbemail_id = $row['sbemail_id'];
            $this->sbemail_content = $row['sbemail_content'];
            $this->sbemail_remark = $row['sbemail_remark'];
            $this->sbemail_title = $row['sbemail_title'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_sbemail"," WHERE sbemail_id = '$this->sbemail_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->sbemail_status = 1;
        }
//        $readonly = " READONLY";
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Subscribe Email Management</title>
    <?php
    include_once 'css.php';
    include_once 'js.php';
    ?> 
    <script src="<?php echo include_webroot;?>dist/js/jquery.rateit.js"></script>
    <script src="dist/ckeditor/ckeditor.js"></script>
    <script>
    $(document).ready(function() {
        CKEDITOR.replace('sbemail_content');
    
        $('.email').click(function(){
            if(confirm("Confirm Email All subscribe user?")){
                var data = "action=emailall&sbemail_id=<?php echo $this->sbemail_id;?>"
                $.ajax({
                   type: "POST",
                   url: "sbemail.php",      
                   data:data,
                   beforeSend: function() {
                        $('.email').attr('disabled',true);
                   },
                   error: function(xhr) { // if error occured
                        alert("Error occured.please try again");
                        $('.email').attr('disabled',false);
                   },
                   success: function(data) {

                       $('.email').attr('disabled',false);

                   }
                });
            }
        });
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
            <h1>Subscribe Email Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->sbemail_id > 0){ echo "Update Subscribe Email";}else{ echo "Create New Subscribe Email";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='sbemail.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='sbemail.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'sbemail_form' class="form-horizontal" action = 'sbemail.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="sbemail_title" class="col-sm-2 control-label">Email Title</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="sbemail_title" name="sbemail_title" placeholder="Email Title" value = "<?php echo $this->sbemail_title;?>" <?php echo $readonly;?>>
                      </div>
                    </div>
                   <div class="form-group">
                    <label for="sbemail_remark" class="col-sm-2 control-label">Internal Remark</label>
                    <div class="col-sm-3">
                    <textarea id="sbemail_remark" name="sbemail_remark" class="form-control" rows="3" placeholder="Internal Remark" <?php echo $readonly;?>><?php echo $this->sbemail_remark;?></textarea>
                    </div>
                    <div class="col-sm-2">
                        <?php if($this->sbemail_id > 0){?>
                          <a href = 'sbe_email.php?sbemail_id=<?php echo $this->sbemail_id;?>' target = '_blank' class = 'btn bg-orange btn-flat margin' style="width:100%">Preview Template</a>
                          <a href = 'javascript:void(0)' class = 'btn bg-purple btn-flat margin email' style="width:100%">Email All Subscribe User</a>
                         <?php }?>
                    </div>
                   </div>
                   <div class="form-group">
                      <label for="sbemail_content" class="col-sm-2 control-label">Description</label>

                   </div>   
                   <div class="box-body pad">
                        <textarea id="sbemail_content" name="sbemail_content"><?php echo $this->sbemail_content;?></textarea>
                   </div>
                      
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;

                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->sbemail_id;?>" name = "sbemail_id"/>
                    <?php 
                    if($this->sbemail_id > 0){
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
    <title>Subscribe Email Management</title>
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
            <h1>Subscribe Email Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Subscribe Email Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='sbemail.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="sbemail_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:50%'>Remark</th>
                        <th style = 'width:10%'>Status</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT sbemail.*
                              FROM db_sbemail sbemail 
                              WHERE sbemail.sbemail_id > 0 
                              ORDER BY sbemail.insertDateTime DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['sbemail_remark'];?></td>
                            <td><?php 
                            if($row['sbemail_ismail'] == 1){ 
                                echo 'Emailed';
                            }else{
                                echo 'Pending';
                            }
                            ?>
                            </td>
                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'sbemail.php?action=edit&sbemail_id=<?php echo $row['sbemail_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('sbemail.php?action=delete&sbemail_id=<?php echo $row['sbemail_id'];?>','Confirm Delete?')">Delete</button>
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
        $('#sbemail_table').DataTable({
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
    public function emailAll(){
        $this->fetchSbemailDetail(" AND sbemail_id = '$this->sbemail_id'","","",1);
        $sql = "SELECT * FROM db_subscribe WHERE subscribe_discdate = '0000-00-00'";
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
$subject = "$this->sbemail_title";
	        $headers = "From: " . "博讯吧_Boxun8.CN <webmaster@boxun8.cn>" . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=utf-8\r\n";
$sbemail_id = $this->sbemail_id;

		
ob_start();
include 'sbe_email.php';
$message = ob_get_clean();
       mail("{$row['subscribe_email']}",$subject, $message, $headers);
        }
        
        $sql1 = "UPDATE db_sbemail SET sbemail_ismail = '1' WHERE sbemail_id = '$this->sbemail_id'";
        mysql_query($sql1);
    }

}
?>
