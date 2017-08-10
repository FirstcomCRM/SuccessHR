<?php
/*
 * To change this tsubscribeate, choose Tools | Tsubscribeates
 * and open the tsubscribeate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Subscribe {

    public function Subscribe(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('subscribe_url','subscribe_name','subscribe_email','subscribe_desc','subscribe_status');
        $table_value = array($this->subscribe_url,$this->subscribe_name,$this->subscribe_email,$this->subscribe_desc,$this->subscribe_status);
        $remark = "Insert Subscribe .";
        if(!$this->save->SaveData($table_field,$table_value,'db_subscribe','subscribe_id',$remark)){
           return false;
        }else{
           $this->subscribe_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $table_field = array('subscribe_status');
        $table_value = array($this->subscribe_status);
        
        $remark = "Update Subscribe .";
        if(!$this->save->UpdateData($table_field,$table_value,'db_subscribe','subscribe_id',$remark,$this->subscribe_id)){
           return false;
        }else{
           return true;
        }
    }
    public function fetchSubscribeDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_subscribe WHERE subscribe_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->subscribe_id = $row['subscribe_id'];
            $this->subscribe_discdate = $row['subscribe_discdate'];
            $this->subscribe_date = $row['subscribe_date'];
            $this->subscribe_email = $row['subscribe_email'];
            $this->subscribe_name = $row['subscribe_name'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_subscribe"," WHERE subscribe_id = '$this->subscribe_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->subscribe_status = 0;
        }
        $readonly = " READONLY";
        $this->countryCrtl = $this->select->getCountrySelectCtrl($this->subscribe_country,'Y');
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Subscribe Management</title>
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
            <h1>Subscribe Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title">View Subscribe</h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='subscribe.php'">Search</button>
              </div>
                
                <form id = 'subscribe_form' class="form-horizontal" action = 'subscribe.php?action=create' method = "POST">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="subscribe_name" class="col-sm-2 control-label">Name</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="subscribe_name" name="subscribe_name" placeholder="Name" value = "<?php echo $this->subscribe_name;?>" <?php echo $readonly;?>>
                      </div>
                      <label for="subscribe_email" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="subscribe_email" name="subscribe_email" placeholder="Email" value = "<?php echo $this->subscribe_email;?>" <?php echo $readonly;?>>
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="subscribe_date" class="col-sm-2 control-label">Subscribe Date</label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="subscribe_date" name="subscribe_date" value = "<?php echo $this->subscribe_date;?>" placeholder="Subscribe Date" <?php echo $readonly;?>>
                        </div>
                      <label for="subscribe_discdate" class="col-sm-2 control-label">Un-Subscribe Date</label>
                      <div class="col-sm-3">
                           <input type="text" class="form-control" id="subscribe_discdate" name="subscribe_discdate" value = "<?php echo $this->subscribe_discdate;?>" placeholder="Un-Subscribe Date" <?php echo $readonly;?>>
                      </div>
                    </div>   
    
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->subscribe_id;?>" name = "subscribe_id"/>
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
    <title>Subscribe Management</title>
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
            <h1>Subscribe Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Subscribe Table</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="subscribe_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:15%'>Name</th>
                        <th style = 'width:15%'>Email</th>
                        <th style = 'width:15%'>Start Date</th>
                        <th style = 'width:15%'>Unsubscribe Date</th>
                        <th style = 'width:13%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT bt.*
                              FROM db_subscribe bt
                              WHERE bt.subscribe_id > 0 
                              ORDER BY bt.insertDateTime";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['subscribe_name'];?></td>
                            <td><?php echo $row['subscribe_email'];?></td>
                            <td><?php echo $row['subscribe_date'];?></td>
                            <td><?php echo $row['subscribe_discdate'];?></td>
                            <td class = "text-align-right">
                                <!--<button type="button" class="btn btn-primary <?php if($row['subscribe_emailcount'] > 0){ echo 'btn-warning';}else{ echo 'btn-success';}?> " onclick = "emailCustomer('<?php echo $row['subscribe_id'];?>')">Email</button>-->
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'subscribe.php?action=edit&subscribe_id=<?php echo $row['subscribe_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('subscribe.php?action=delete&subscribe_id=<?php echo $row['subscribe_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:15%'>Name</th>
                        <th style = 'width:15%'>Email</th>
                        <th style = 'width:15%'>Start Date</th>
                        <th style = 'width:15%'>Unsubscribe Date</th>
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
        $('#subscribe_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });

      });
      function emailCustomer(subscribe_id){
          if(confirm("Confirm email customer?")){
            var data = "action=emailCustomer&subscribe_id="+subscribe_id;
            $.ajax({
               type: "POST",
               url: "subscribe.php",      
               data:data,
               success: function(data) {
                   var jsonObj = eval ("(" + data + ")");
                   if(jsonObj.status > 0){
                       alert('Email Success.');
                   }else{
                       alert('Email Fail');
                   }
               }
            });
        }
      }
    </script>
  </body>
</html>
    <?php
    }
    public function emailCustomer(){
     $this->fetchSubscribeDetail(" AND subscribe_id = '$this->subscribe_id'","","",1);
     $url = include_webroot;

$subscribe_name = $this->subscribe_name;
$subscribe_url = $this->subscribe_url;
$subscribe_desc = $this->subscribe_desc;
$submit_date = $this->insertDateTime;
$include_webroot = include_webroot;
$type = 'bl';
$subject = "恭喜你,黑庄娱乐城已被审核通过!";
	        $headers = "From: " . "博讯吧_Boxun8.CN <webmaster@boxun8.cn>" . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=utf-8\r\n";

		
ob_start();
include 'oc_email.php';
$message = ob_get_clean();


    
       if(mail("jasonchong3329@gmail.com",$subject, $message, $headers)){
           $this->updateEmailCount();
           echo json_encode(array('status'=>1));
       }else{
           echo json_encode(array('status'=>0));
       }
    }
    public function updateEmailCount(){
        $sql = "UPDATE db_subscribe SET subscribe_emailcount = subscribe_emailcount+1 WHERE subscribe_id = '$this->subscribe_id'";
        $query = mysql_query($sql);
    }
    public function getListingEmailTemplate(){
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Subscribe Email Template Management</title>
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
            <h1>Subscribe Email Template Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Subscribe Email Template Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='subscribe.php?action=createFormEmail'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="subscribe_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:15%'>Name</th>
                        <th style = 'width:15%'>Email</th>
                        <th style = 'width:15%'>Start Date</th>
                        <th style = 'width:15%'>Unsubscribe Date</th>
                        <th style = 'width:13%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT bt.*
                              FROM db_subscribe bt
                              WHERE bt.subscribe_id > 0 
                              ORDER BY bt.insertDateTime DESC";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['subscribe_name'];?></td>
                            <td><?php echo $row['subscribe_email'];?></td>
                            <td><?php echo $row['subscribe_date'];?></td>
                            <td><?php echo $row['subscribe_discdate'];?></td>
                            <td class = "text-align-right">
                                <!--<button type="button" class="btn btn-primary <?php if($row['subscribe_emailcount'] > 0){ echo 'btn-warning';}else{ echo 'btn-success';}?> " onclick = "emailCustomer('<?php echo $row['subscribe_id'];?>')">Email</button>-->
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'subscribe.php?action=edit&subscribe_id=<?php echo $row['subscribe_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('subscribe.php?action=delete&subscribe_id=<?php echo $row['subscribe_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:15%'>Name</th>
                        <th style = 'width:15%'>Email</th>
                        <th style = 'width:15%'>Start Date</th>
                        <th style = 'width:15%'>Unsubscribe Date</th>
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
        $('#subscribe_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });

      });
      function emailCustomer(subscribe_id){
          if(confirm("Confirm email customer?")){
            var data = "action=emailCustomer&subscribe_id="+subscribe_id;
            $.ajax({
               type: "POST",
               url: "subscribe.php",      
               data:data,
               success: function(data) {
                   var jsonObj = eval ("(" + data + ")");
                   if(jsonObj.status > 0){
                       alert('Email Success.');
                   }else{
                       alert('Email Fail');
                   }
               }
            });
        }
      }
    </script>
  </body>
</html>
    <?php
    }

}
?>
