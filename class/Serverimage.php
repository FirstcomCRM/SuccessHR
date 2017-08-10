<?php
/*
 * To change this tserverimageate, choose Tools | Tserverimageates
 * and open the tserverimageate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Serverimage {

    public function Serverimage(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function pictureManagement($ref_id,$folder_name){
        $name = date('Ymdhis', time());
        if(!file_exists("../upload/images")){
           mkdir("../upload/images/");
        }
        $isimage = false;
        if($this->image_input['type'] == 'image/png' || $this->image_input['type'] == 'image/jpeg' || $this->image_input['type'] == 'image/gif'){
           $isimage = true;
        }
        if($this->image_input['size'] > 0 && $isimage == true){
            if($this->action == 'update'){
                unlink("../upload/images/$name.jpeg");
            }

                move_uploaded_file($this->image_input['tmp_name'],"../upload/images/$name.jpeg");
            return true;
        }else{
            return false;
        }

    }
    public function deleteImage(){
        if(unlink($this->imagepath)){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->serverimage_status = 1;
        }
//        $readonly = " READONLY";
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Server Image Management</title>
    <?php
    include_once 'css.php';
    ?>   
    <?php
    include_once 'js.php';
    
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
            <h1>Server Image Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->serverimage_id > 0){ echo "Update Server Image";}else{ echo "Create New Server Image";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='serverimage.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='serverimage.php?action=createNewLuckyDraw&pid='">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'serverimage_form' class="form-horizontal" action = 'serverimage.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="serverimage_code" class="col-sm-2 control-label">Upload</label>
                      <div class="col-sm-3">
                        <input type="file" name = "input_file">
                      </div>

                    </div>   
                    
                      
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $_REQUEST['rserverimage_id'];?>" name = "rserverimage_id"/>
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->serverimage_id;?>" name = "serverimage_id"/>
                    <?php 
                    if($this->serverimage_id > 0){
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
    <title>Server Image Management</title>
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
            <h1>Server Image Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Server Image Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='serverimage.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="serverimage_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:65%'>Image</th>
                        <th style = 'width:15%'>Path</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $dir = "../upload/images/*.jpeg";

                      $images = glob( $dir );
                      $i = 1;
               
                      foreach($images as $image){
                            $btn = "<button type='button' class='btn btn-primary btn-danger' onclick = 'confirmAlertHref(\"serverimage.php?action=delete&imagepath=$image\",\"Confirm Delete?\")'>Delete</button>";
                            echo "<tr><td>$i</td>";
                            echo "<td><a href = '$image' target = '_blank'><img style = 'width:500px' src='" . $image . "' /></a></td>";
                            $image = str_replace("../","",$image);
                            echo "<td>" . include_webroot . $image . "</td>";
                            echo "<td>$btn</td></tr>";
                            $i++;
                      }
                    ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th style = 'width:5%'>No</th>
                        <th style = 'width:65%'>Image</th>
                        <th style = 'width:15%'>Path</th>
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
        $('#serverimage_table').DataTable({
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
