<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



class Cprofile {

    public function Cprofile(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        
    }
     
    public function create(){

        $table_field = array('outl_code','outl_desc','outl_status','outlet_tel','outlet_fax','outlet_email','outlet_country','outlet_website','outlet_gst_no','outlet_gst','outlet_address','outlet_time_minute');
        $table_value = array($this->outlet_code,$this->outlet_desc,1,$this->outlet_tel,$this->outlet_fax,$this->outlet_email,$this->outlet_country,$this->outlet_website,$this->outlet_gst_no,$this->outlet_gst,$this->outlet_address,$this->time_minute);
    
        $remark = "Insert Outlet";

        if(!$this->save->SaveData($table_field,$table_value,'db_outl','outl_id',$remark)){
           return false;
        }else{
           $this->outlet_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){

        $table_field = array('outl_code','outl_desc','outl_status','outlet_tel','outlet_fax','outlet_email','outlet_country','outlet_website','outlet_gst_no','outlet_gst','outlet_address','outlet_time_minute');
        $table_value = array($this->outlet_code,$this->outlet_desc,1,$this->outlet_tel,$this->outlet_fax,$this->outlet_email,$this->outlet_country,$this->outlet_website,$this->outlet_gst_no,$this->outlet_gst,$this->outlet_address,$this->time_minute);
  
        $remark = "Update Outlet";

        if(!$this->save->UpdateData($table_field,$table_value,'db_outl','outl_id',$remark,$this->outlet_id)){
           return false;
        }
        else{
           return true;
        }
    }    
    public function deleteOutlet(){
        $table_field = array('outl_status');
        $table_value = array(0);
    
        $remark = "Delete Outlet";

        if(!$this->save->UpdateData($table_field,$table_value,'db_outl','outl_id',$remark,$this->outlet_id)){
           return false;
        }
        else{
           return true;
        }
    }    
    
    public function getListing(){
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Candidate Management</title>
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
            <h1>Candidate Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12" >
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Candidate Table</h3>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button class="btn btn-primary pull-right" onclick = "window.location.href='cprofile.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    
                  <table id="family_table" class="table table-bordered table-hover dataTable">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:15%'>Company Name</th>
                        <th style = 'width:5%'>Country</th>
                        <th style = 'width:5%'>Tel No</th>
                        <th style = 'width:5%'>Website</th>
                        <th style = 'width:15%'>Description</th>
                        <th style = 'width:7%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php   
                      $sql = "SELECT *, nationality_code
                              FROM db_outl INNER JOIN db_nationality ON nationality_id = outlet_country
                              WHERE outl_status = '1'
                              ORDER BY outl_seqno";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['outl_code'];?></td>
                            <td><?php echo $row['nationality_code'];?></td>
                            <td><?php echo $row['outlet_tel'];?></td>
                            <td><?php echo $row['outlet_website'];?></td>
                            <td><?php echo $row['outl_desc'];?></td>
                            <td class = "text-align-right">
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'cprofile.php?action=edit&outlet_id=<?php echo $row['outl_id'];?>'">Edit</button>
                                <input type = 'hidden' value = '<?php echo $row['outl_id'];?>' name = "outlet_id" id = "outlet_id"/>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('cprofile.php?action=deleteOutlet&outlet_id=<?php echo $row['outl_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:15%'>Company Name</th>
                        <th style = 'width:5%'>Country</th>
                        <th style = 'width:5%'>Tel No</th>
                        <th style = 'width:5%'>Website</th>
                        <th style = 'width:15%'>Description</th>
                        <th style = 'width:7%'></th>
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
    
     <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="width:70%; margin-top: 10%">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;background-color: #377506; color:fff">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Candidate Remarks</h4>
        </div>
        <div class="modal-body" style="background-color: #ecfff2;height: 550px;overflow-y: scroll;">
            <div id = 'remarks_content'></div>

        </div>
        <div class="modal-footer" style="background-color: #377506; padding: 10px;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
    
    
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
    
    public function fetchOutlet($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_outl WHERE outl_status = '1' $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->outlet_id = $row['outl_id'];
            $this->outlet_code = $row['outl_code'];
            $this->outlet_seqno = $row['outl_seqno'];
            $this->outlet_desc = $row['outl_desc'];
            $this->outlet_tel = $row['outlet_tel'];
            
            $this->outlet_fax = $row['outlet_fax'];
            $this->outlet_email = $row['outlet_email'];
            $this->outlet_country = $row['outlet_country'];
            $this->outlet_website = $row['outlet_website'];
            $this->outlet_gst_no = $row['outlet_gst_no'];
            
            $this->outlet_gst = $row['outlet_gst'];
            $this->outlet_address = $row['outlet_address'];
            $this->time_minute = $row['outlet_time_minute'];

            
            
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }else{
             return $query;
        }
       
    }    
    
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->cprofile_status = 1;
        }
        $this->fetchOutlet(" AND outl_id = '$this->outlet_id'","","",1);
        $this->countryCrtl = $this->select->getNationalitySelectCtrl($this->outlet_country);
    ?>
   <html>
          <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Company Profile</title>
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
                <h1>Company Profile</h1>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                <div class="col-xs-12">
                <div class="box">
                <div class="box-header">
                  <h3 class="box-title">
                      <?php 
                      echo "Company Profile"; 
                      ?> </h3>

                </div><!-- /.box-header -->

                
                <div class="box-body table-responsive">     
                <form id = 'cprofile_form' class="form-horizontal" action = 'cprofile.php?action=create' method = "POST">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="outlet_code" class="col-sm-2 control-label">Company Name <?php echo $mandatory;?></label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="outlet_code" name="outlet_code" placeholder="Company Name" value = "<?php echo $this->outlet_code;?>" >
                      </div>
                      <label for="outlet_tel" class="col-sm-2 control-label">Tel</label>
                      <div class="col-sm-3">
                          <input type="text" class="form-control" id="outlet_tel" name="outlet_tel" value = "<?php echo $this->outlet_tel;?>" placeholder="Tel">
                       </div>
                    </div>  
                    <div class="form-group">
                      <label for="outlet_fax" class="col-sm-2 control-label">Fax</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="outlet_fax" name="outlet_fax" value = "<?php echo $this->outlet_fax;?>" placeholder="Fax">
                      </div>
                      <label for="outlet_email" class="col-sm-2 control-label">Email</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="outlet_email" name="outlet_email" value = "<?php echo $this->outlet_email;?>" placeholder="Email">
                      </div>
                    </div> 
                    <div class="form-group">
                      <label for="outlet_country" class="col-sm-2 control-label">Country <?php echo $mandatory;?></label>
                      <div class="col-sm-3">
                               <select class="form-control select2" id="outlet_country" name="outlet_country">
                                   <?php echo $this->countryCrtl;?>
                               </select>
                      </div>
                      <label for="outlet_website" class="col-sm-2 control-label">Website</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="outlet_website" name="outlet_website" value = "<?php echo $this->outlet_website;?>" placeholder="Website">
                      </div>
                    </div> 
                    <div class="form-group">
                      <label for="outlet_gst_no" class="col-sm-2 control-label">GST No</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="outlet_gst_no" name="outlet_gst_no" value = "<?php echo $this->outlet_gst_no;?>" placeholder="GST No">
                      </div>
                      <label for="outlet_gst" class="col-sm-2 control-label">GST %</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="outlet_gst" name="outlet_gst" value = "<?php echo $this->outlet_gst;?>" placeholder="7.00">
                      </div>
                    </div> 
                    <div class="form-group">
                      <label for="outlet_address" class="col-sm-2 control-label">Address</label>
                      <div class="col-sm-3">
                            <textarea class="form-control" rows="3" id="outlet_address" name="outlet_address" placeholder="Address"><?php echo $this->outlet_address;?></textarea>
                      </div>
                      <label for="outlet_desc" class="col-sm-2 control-label">Remark</label>
                      <div class="col-sm-3">
                            <textarea class="form-control" rows="3" id="outlet_desc" name="outlet_desc" placeholder="Remark"><?php echo $this->outlet_desc;?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="time_minute" class="col-sm-2 control-label">Remark Time Set (in Minute)</label>
                            <div class="col-sm-3">
                            <input type="text" class="form-control " id="time_minute" name="time_minute" value = "<?php echo $this->time_minute; ?>" placeholder="Minute eg : 10">
                            </div>
                    </div>  

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->outlet_id;?>" name = "outlet_id"/>
                    <?php 
                    if($this->outlet_id > 0){
                        $prm_code = "update";
                    }else{
                        $prm_code = "create";
                    }
                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){?>
                    <button type = "submit" class="btn btn-info">Submit</button>
                    <?php }?>
                  </div><!-- /.box-footer -->
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
    <script>
    $(document).ready(function() {
        $("#cprofile_form").validate({
                  rules: 
                  {
                      outlet_code:
                      {
                          required: true
                      },
                      outlet_country:
                      {
                          required: true
                      }
                  },
                  messages:
                  {
                      outlet_code:
                      {
                          required: "Please enter Company Name."
                      },
                      outlet_country:
                      {
                          required: "Please select country."
                      }
                  }
              });
    
    
    });
    </script>

    <script>
    $(document).ready(function() {
          
            $('.save_outlet_btn').click(function(){
                var data = "action=saveOutlet&outlet_id=<?php echo $this->outlet_id;?>&outlet_code="+$('#outlet_code').val()+"&outlet_seqno="+$('#outlet_seqno').val()+"&outlet_desc="+$('#outlet_desc').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'cprofile.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF']?>';
                           window.location.href = url;
                       }
                       else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });          
    });
    </script>
    
  </body>
  

</html>

        <?php
        
    }
}
?>