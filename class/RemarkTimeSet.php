<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



class RemarkTimeSet {

    public function RemarkTimeSet(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        
    }
     
    public function create(){

        $table_field = array('time_minute');
        $table_value = array($this->time_minute);
    
        $remark = "Insert Remark Time Set.";

        if(!$this->save->SaveData($table_field,$table_value,'db_remark_time','time_id',$remark)){
           return false;
        }else{
           $this->time_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){

        $table_field = array('time_minute');
        $table_value = array($this->time_minute);
    
        $remark = "Update Remark Time Set.";

        if(!$this->save->UpdateData($table_field,$table_value,'db_remark_time','time_id',$remark,$this->time_id)){
           return false;
        }
        else{
           return true;
        }
    }    

    public function fetchTime($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_remark_time WHERE time_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->time_id = $row['time_id'];
            $this->time_minute = $row['time_minute'];
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }            
    }    
    
    
    public function showTimeForm(){
        
        ?>
          <html>
          <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Remarks Time Set</title>
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
                      echo "Remarks Time Set"; 
                      ?> </h3>

                </div><!-- /.box-header -->
                <?php 
                $this->time_id = 1; 
                $this->fetchTime("AND time_id = '1'","","","1");
                        ?>
                
                <div class="box-body table-responsive">     
                    <?php if($this->time_id >0){ ?>
                        <form id = 'category_form' class="form-horizontal" action = 'remarktimeset.php?action=update' method = "POST" enctype="multipart/form-data">
                    <?php } else {?>
                        <form id = 'category_form' class="form-horizontal" action = 'remarktimeset.php?action=create' method = "POST" enctype="multipart/form-data">
                    <?php }?>
                    <div class="form-group">
                        <label for="time_minute" class="col-sm-2 control-label">Time Set (in Minute)</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control " id="category_name" name="time_minute" value = "<?php echo $this->time_minute; ?>" placeholder="Minute eg : 10">
                            </div>
                    </div>
                   
                        <input type = 'hidden' value = '<?php echo $this->time_id;?>' name = 'time_id' id = 'time_id'/>
                                <div class="col-sm-3 "><br><br>
                              <button type = "submit" class="btn btn-info">
                                  <?php if($this->time_id > 0){ echo "Update";} 
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