<?php
/*
 * To change this taboutusate, choose Tools | Taboutusates
 * and open the taboutusate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Aboutus {

    public function Aboutus(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        

    }
    public function create(){
        $table_field = array('aboutus_facebook','aboutus_email','aboutus_skype',
                             'aboutus_qq','aboutus_mt_title','aboutus_mt_keyword',
                             'aboutus_mt_desc','aboutus_desc','aboutus_tnc',
                             'aboutus_policy','aboutus_notice');
        $table_value = array($this->aboutus_facebook,$this->aboutus_email,$this->aboutus_skype,
                             $this->aboutus_qq,$this->aboutus_mt_title,$this->aboutus_mt_keyword,
                             $this->aboutus_mt_desc,$this->aboutus_desc,$this->aboutus_tnc,
                             $this->aboutus_policy,$this->aboutus_notice);
        $remark = "Insert About Us.";
        if(!$this->save->SaveData($table_field,$table_value,'db_aboutus','aboutus_id',$remark)){
           return false;
        }else{
           $this->aboutus_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $table_field = array('aboutus_facebook','aboutus_email','aboutus_skype',
                             'aboutus_qq','aboutus_mt_title','aboutus_mt_keyword',
                             'aboutus_mt_desc','aboutus_desc','aboutus_tnc',
                             'aboutus_policy','aboutus_contact','aboutus_notice');
        $table_value = array($this->aboutus_facebook,$this->aboutus_email,$this->aboutus_skype,
                             $this->aboutus_qq,$this->aboutus_mt_title,$this->aboutus_mt_keyword,
                             $this->aboutus_mt_desc,$this->aboutus_desc,$this->aboutus_tnc,
                             $this->aboutus_policy,$this->aboutus_contact,$this->aboutus_notice);
        
        $remark = "Update About Us.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_aboutus','aboutus_id',$remark,$this->aboutus_id)){
           return false;
        }else{
           $this->create_image($this->aboutus_id,"subscribe","db_aboutus");  
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
    public function fetchAboutusDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_aboutus WHERE aboutus_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->aboutus_id = $row['aboutus_id'];
            $this->aboutus_facebook = $row['aboutus_facebook'];
            $this->aboutus_email = $row['aboutus_email'];
            $this->aboutus_skype = $row['aboutus_skype'];
            $this->aboutus_qq = $row['aboutus_qq'];
            $this->aboutus_mt_title = $row['aboutus_mt_title'];
            $this->aboutus_mt_keyword = $row['aboutus_mt_keyword'];
            $this->aboutus_mt_desc = $row['aboutus_mt_desc'];
            $this->aboutus_desc = $row['aboutus_desc'];
            $this->aboutus_tnc = $row['aboutus_tnc'];
            $this->aboutus_policy = $row['aboutus_policy'];
            $this->aboutus_contact = $row['aboutus_contact'];
            $this->aboutus_notice = $row['aboutus_notice'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_aboutus"," WHERE aboutus_id = '$this->aboutus_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;

    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Website Setup Management</title>
    <?php
    include_once 'css.php';
    ?>   
    <?php
    include_once 'js.php';
    
    ?>

    <script src="dist/ckeditor/ckeditor.js"></script>
    <script>
    $(document).ready(function() {
        CKEDITOR.replace('aboutus_desc');
        CKEDITOR.replace('aboutus_contact');

        CKEDITOR.replace('aboutus_notice');
    
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
            <h1>Website Setup Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->aboutus_id > 0){ echo "Update Website Setup";}else{ echo "Create New Website Setup";}?></h3>

              </div>
                
                <form id = 'aboutus_form' class="form-horizontal" action = 'aboutus.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="aboutus_facebook" class="col-sm-2 control-label">Facebook Page</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="aboutus_facebook" name="aboutus_facebook" placeholder="Facebook Page" value = "<?php echo $this->aboutus_facebook;?>" <?php echo $readonly;?>>
                      </div>
                      <label for="aboutus_email" class="col-sm-2 control-label">Contact Email</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="aboutus_url" name="aboutus_email" placeholder="Contact Email" value = "<?php echo $this->aboutus_email;?>" <?php echo $readonly;?>>
                      </div>
                    </div>   
                    <div class="form-group">
                      <label for="aboutus_skype" class="col-sm-2 control-label">Skype</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="aboutus_skype" name="aboutus_skype" placeholder="Skype" value = "<?php echo $this->aboutus_skype;?>" <?php echo $readonly;?>>
                      </div>
                      <label for="aboutus_qq" class="col-sm-2 control-label">QQ</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="aboutus_qq" name="aboutus_qq" placeholder="QQ" value = "<?php echo $this->aboutus_qq;?>" <?php echo $readonly;?>>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="aboutus_mt_title" class="col-sm-2 control-label">Meta Tag Title</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="aboutus_mt_title" name="aboutus_mt_title" value = "<?php echo $this->aboutus_mt_title;?>" placeholder="Meta Tag Title" <?php echo $readonly;?>>
                      </div>
                        <label for="aboutus_mt_keyword" class="col-sm-2 control-label">Meta Tag Keyword</label>
                        <div class="col-sm-3">
                          <input type="text" class="form-control" id="aboutus_mt_keyword" name="aboutus_mt_keyword" value = "<?php echo $this->aboutus_mt_keyword;?>" placeholder="Meta Tag Keyword" <?php echo $readonly;?>>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="aboutus_mt_desc" class="col-sm-2 control-label">Meta Tag Description</label>
                      <div class="col-sm-3">
                      <textarea id="raboutus_desc" name="aboutus_mt_desc" class="form-control" rows="3" placeholder="Meta Tag Description" <?php echo $readonly;?>><?php echo $this->aboutus_mt_desc;?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="onlinecasino_shortdesc" class="col-sm-2 control-label">Image</label>
                      <div class="box-body pad col-sm-8">
                          <input  type ='file' name = "input_file"/>
                          <br>
                          <?php
                          if(file_exists("../upload/subscribe/$this->aboutus_id.jpeg")){
                              echo "<a href = '../upload/subscribe/$this->aboutus_id.jpeg' target = '_blank'><img style='width:100%;' src = '../upload/subscribe/$this->aboutus_id.jpeg'/></a>";
                          }
                          ?>
                      </div>    
                    </div>  
                   <div class="form-group">
                      <label for="aboutus_notice" class="col-sm-2 control-label">Website Notice</label>
                   </div>   
                   <div class="box-body pad">
                        <textarea id="aboutus_notice" name="aboutus_notice"><?php echo $this->aboutus_notice;?></textarea>
                   </div>
                   <div class="form-group">
                      <label for="aboutus_desc" class="col-sm-2 control-label">Website Description</label>
                   </div>   
                   <div class="box-body pad">
                        <textarea id="aboutus_desc" name="aboutus_desc"><?php echo $this->aboutus_desc;?></textarea>
                   </div>
                    <div class="form-group">
                      <label for="aboutus_contact" class="col-sm-2 control-label">Contact Us</label>
                   </div>   
                   <div class="box-body pad">
                        <textarea id="aboutus_contact" name="aboutus_contact"><?php echo $this->aboutus_contact;?></textarea>
                   </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "update" name = "action"/>
                    <input type = "hidden" value = "1" name = "aboutus_id"/>
                    <?php 
                    if($this->aboutus_id > 0){
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

}
?>
