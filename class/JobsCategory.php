<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



class JobsCategory {

    public function JobsCategory(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        
    }
     
    public function create(){

        $table_field = array('category_name','category_parent','category_seqno');
        $table_value = array($this->category_name,$this->category_parent,$this->category_seqno);
    
        $remark = "Insert Jobs Category.";

        if(!$this->save->SaveData($table_field,$table_value,'db_category_job','category_id',$remark)){
           return false;
        }else{
           $this->category_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){

        $table_field = array('category_name','category_parent','category_seqno');
        $table_value = array($this->category_name,$this->category_parent,$this->category_seqno);
    
        $remark = "Update Jobs Category.";

        if(!$this->save->UpdateData($table_field,$table_value,'db_category_job','category_id',$remark,$this->category_id)){
           return false;
        }
        else{
           return true;
        }
    }    
    public function delete(){
        $table_field = array('category_status');
        $table_value = array(1);
        $remark = "Delete Category.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_category_job','category_id',$remark,$this->category_id)){
           return false;
        }
        else{
           return true;
        }
    }
    public function fetchCategoryDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_category_job WHERE category_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->category_id = $row['category_id'];
            $this->category_name = $row['category_name'];
            $this->category_parent = $row['category_parent'];
            $this->category_seqno = $row['category_seqno'];
            
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }
        else{
             return $query;
        }
    }    
    
    
    public function showCategoryForm(){
        
        ?>
          <html>
          <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Show Import Detail</title>
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
                <h1>Jobs Category</h1>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                <div class="col-xs-12">
                <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php if($this->category_id > 0){ echo "Update Category";} 
                  else {
                      echo "Create Jobs Category";
                  } ?> </h3>

                </div><!-- /.box-header -->


                <?php $this->category_parentCrtl = $this->select->getCategoryParentSelectCtrl($this->category_parent);?>
                <div class="box-body table-responsive">     
                    <?php if($this->category_id >0){ ?>
                        <form id = 'category_form' class="form-horizontal" action = 'jobscategory.php?action=update' method = "POST" enctype="multipart/form-data">
                    <?php } else {?>
                        <form id = 'category_form' class="form-horizontal" action = 'jobscategory.php?action=create' method = "POST" enctype="multipart/form-data">
                    <?php }?>
                    <div class="form-group">
                        <label for="category_name" class="col-sm-2 control-label">Category Name</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control " id="category_name" name="category_name" value = "<?php echo $this->category_name; ?>" placeholder="Name">
                            </div>

                        <label for="category_parent" class="col-sm-2 control-label">Category Parent</label>
                            <div class="col-sm-3">
                                <select class="form-control select2" id="category_parent" name="category_parent" style = 'width:100%'>
                                     <?php echo $this->category_parentCrtl;?>
                                </select>
                            </div>
                    </div>
                    <div class="form-group">
                        <label for="category_seqno" class="col-sm-2 control-label">Category Arrange</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control " id="category_seqno" name="category_seqno" value = "<?php echo $this->category_seqno; ?>" placeholder="Number">
                            </div>
                    </div>                    
                        <input type = 'hidden' value = '<?php echo $this->category_id;?>' name = 'category_id' id = 'category_id'/>
                                <div class="col-sm-3 "><br><br>
                              <button type = "submit" class="btn btn-info">
                                  <?php if($this->category_id > 0){ echo "Update";} 
                                  else {
                                      echo "Save";
                                  } ?>
                              </button>
                    
                </div>
                    </form>
                    



                    <br><br><br><br><br><br>
                  <table id="applicant_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Category</th>
                        <th style = 'width:10%'>Category Parent</th>
                        <th style = 'width:10%'>Category Arrange</th>
                        <th style = 'width:10%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php                           
                      $sql = "SELECT * FROM db_category_job where category_status = '0'";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['category_name'];?></td>
                            <?php $category_id = $row['category_parent'];
                            $sql2 = "SELECT category_name FROM db_category_job where category_id = '$category_id'";
                              $query2 = mysql_query($sql2);
                              $row2 = mysql_fetch_array($query2)
                                    
                            ?>
                            <td><?php if($row2['category_name'] != ""){echo $row2['category_name'];} else { echo "Parent";}?></td>
                            <td><?php echo $row['category_seqno'];?></td>     

                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'jobscategory.php?action=edit&category_id=<?php echo $row['category_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('jobscategory.php?action=delete&category_id=<?php echo $row['category_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Category</th>
                        <th style = 'width:10%'>Category Parent</th>
                        <th style = 'width:10%'>Category Arrange</th>
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
        $('#applicant_table').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": true,
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