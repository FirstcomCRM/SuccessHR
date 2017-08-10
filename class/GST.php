<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



class GST {

    public function GST(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        
    }
     
    public function create(){

        $table_field = array('gst_percentage');
        $table_value = array($this->gst_percentage);
    
        $remark = "Insert GST Set.";

        if(!$this->save->SaveData($table_field,$table_value,'db_gst','gst_id',$remark)){
           return false;
        }else{
           $this->gst_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){

        $table_field = array('gst_percentage');
        $table_value = array($this->gst_percentage);
    
        $remark = "Update GST Set.";

        if(!$this->save->UpdateData($table_field,$table_value,'db_gst','gst_id',$remark,$this->gst_id)){
           return false;
        }
        else{
           return true;
        }
    }    

    public function fetchGST($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_gst WHERE gst_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->gst_id = $row['gst_id'];
            $this->gst_percentage = $row['gst_percentage'];
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }            
    }    
    
    
    public function showGSTForm(){
        
        ?>
          <html>
          <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>GST Set</title>
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
                <h1>Remarks Time Set</h1>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                <div class="col-xs-12">
                <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                      <?php 
                      echo "GST Set"; 
                      ?> </h3>

                </div><!-- /.box-header -->
                <?php 
                $this->gst_id = 1; 
                $this->fetchGST("AND gst_id = '1'","","","1");
                        ?>
                
                <div class="box-body table-responsive">     
                    <?php if($this->gst_id >0){ ?>
                        <form id = 'category_form' class="form-horizontal" action = 'gst.php?action=update' method = "POST" enctype="multipart/form-data">
                    <?php } else {?>
                        <form id = 'category_form' class="form-horizontal" action = 'gst.php?action=create' method = "POST" enctype="multipart/form-data">
                    <?php }?>
                    <div class="form-group">
                        <label for="gst_percentage" class="col-sm-2 control-label">GST Set (in Percentage)</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control " id="gst_percentage" name="gst_percentage" value = "<?php echo $this->gst_percentage; ?>" placeholder="Percentage eg : 7">
                            </div>
                    </div>
                   
                        <input type = 'hidden' value = '<?php echo $this->gst_id;?>' name = 'gst_id' id = 'gst_id'/>
                                <div class="col-sm-3 "><br><br>
                              <button type = "submit" class="btn btn-info">
                                  <?php if($this->gst_id > 0){ echo "Update";} 
                                  else {
                                      echo "Save";
                                  } ?>
                              </button>
                    
                </div>
                    </form>
                          
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
    
    
  </body>
  

</html>

<?php

    }

}
?>