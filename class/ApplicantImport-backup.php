<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



class ApplicantImport {

    public function ApplicantImport(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();
        
    }
    public function saveImportFile(){

            if(isset($_FILES['files'])){
                $table_field = array('file_name','file_type');
                
                $errors= array();
                    foreach($_FILES['files']['tmp_name'] as $key => $tmp_name ){
                            $file_name = $_FILES['files']['name'][$key];
                            $file_size =$_FILES['files']['size'][$key];
                            $file_tmp =$_FILES['files']['tmp_name'][$key];
                            $file_type=$_FILES['files']['type'][$key];	
                                    
                            $fileFormat = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
                            $isfile = false;
                            if($fileFormat != "pdf" && $fileFormat != "txt" && $fileFormat != "docx" && $fileFormat != "doc") {
                            
                                rediectUrl("applicantimport.php",getSystemMsg(0,'Please upload pdf, txt or docx file'));
                            }
                            else
                            {
                           
                            $table_value = array($file_name,$fileFormat);
                            $remark = "Import Applicant File";
                            $this->save->SaveData($table_field,$table_value,'db_importfile','file_id',$remark);
                            
                            move_uploaded_file($file_tmp ,"dist/import/$file_name");
                            }
                        }
                    }
                } 
    public function showImportDetail(){
        $this->assignCrtl = $this->select->getAssignSelectCtrl();
        ?>
        
        <br><br><br>           
        <div class="tab">

        <?php
        
        header('Content-Type: text/plain');
        $sql = "SELECT * FROM db_importfile order by file_name";
        $query = mysql_query($sql);
        $i = 1;
        $importNumber = 1;
            while($row = mysql_fetch_array($query)){
                            
            ?>
            <button type = 'button' class="tablinks" file_name = "<?php echo $row['file_name'] ?>" onclick="openTabs(event, '<?php echo $row['file_name'] ?>')" id="defaultOpen">
            <input type="checkbox" name="file_name[]" value="<?php echo $row['file_name'] ?>" checked><?php echo "&nbsp". "&nbsp". $i.". ". $row['file_name'] ?></button>
            <?php $i++;
            } ?>
            </div>
            <?php
            $sql2 = "SELECT * FROM db_importfile order by file_name";
            $query2 = mysql_query($sql2);
            while($row = mysql_fetch_array($query2)){
            ?>
            <div id="<?php echo $row['file_name'] ?>"  class="tabcontent tab_<?php echo $row['file_name'] ?>">
            <div class="content_left">
                
        <?php            
        $filerword = "";
        $fileFormat = strtolower(pathinfo($row['file_name'],PATHINFO_EXTENSION));
                if($row['file_type'] == "doc" || $row['file_type'] == "docx"){
                    $filename = $row['file_name'];

                    $content = '';
                    $detail = '';
                    
                    if(!$filename || !file_exists('dist/import/'.$filename)) return false;
                    $zip = zip_open('dist/import/'.$filename);

                    if (!$zip || is_numeric($zip)) return false;
                    while ($zip_entry = zip_read($zip)) {
                        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
                        if (zip_entry_name($zip_entry) != "word/document.xml") continue;
                        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                        zip_entry_close($zip_entry);
                    }
                    zip_close($zip);
                    
                    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
                    $content = str_replace('</w:r></w:p>', "\r\n", $content);
                    $filterWords = strip_tags($content);
                    $filterWords = str_replace(" : ", " ", $filterWords);
                }
                else if($row['file_type'] == "txt"){
                    $file = "dist/import/".$row['file_name'];
                    $document = file_get_contents($file);
                    $fileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));
                    $filterWords = str_replace(" : ", " ", $document);
                }
                else if($row['file_type'] == "pdf"){
                   $a = new PDF2Text();
                   $a->setFilename('dist/import/'.$row['file_name']);    
                   $a->decodePDF();
                   $filterWords = $a->output();
                   $filterWords = str_replace(" : ", " ", $filterWords);
                }
                
                $filterWords = str_replace(": ", " ", $filterWords);
                $filterWords = str_replace(" :", " ", $filterWords);
                $filterWords = str_replace("  ", " ", $filterWords);
                $filterWords = str_replace("   ", " ", $filterWords);
                
                
                $words = preg_split("/[\s,:]/", $filterWords);
                
                
                $arrlength = count($words);
                
                $Noname = 0;
                $Noemail = 0;
                $Nophone = 0;
                $Nogender = 0;
                $c = 0;
                if($row['file_type'] == "pdf"){                
                    for($i = 0; $i < $arrlength; $i++) {
                        if($words[$i] == ""){ }
                        else
                        {
                            $convert[$c] = ucfirst(strtolower($words[$i]));
                            $c++;
                        }
                    }
                }
                else 
                {
                    for($i = 0; $i < $arrlength; $i++) {
                            $convert[$i] = ucfirst(strtolower($words[$i]));
                    }
                }
                $convertlength = count($convert);
                
                for($x = 0; $x < $convertlength; $x++) {
                  if ($convert[$x] == "Name" && $Noname == 0){
                      ?><label for="import_name<?php echo $importNumber ?>" class="col-sm-2 control-label">Name1 </label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control " id="import_name<?php echo $importNumber ?>" name="import_name[<?php echo $row['file_name'] ?>]" value = "<?php echo $convert[$x+1]; ?>" placeholder="Name">
                        </div><br>
                      <?php $Noname++;
                  }
                  else if ($convert[$x] == "Phone" && $convert[$x+1] == "Number" && $Nophone == 0 || $convert[$x] == "Phone" && $convert[$x+1] == "No" && $Nophone == 0){
                      ?><label for="import_phone<?php echo $importNumber ?>" class="col-sm-2 control-label">Phone </label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control " id="import_phone<?php echo $importNumber ?>" name="import_phone[<?php echo $row['file_name'] ?>]" value = "<?php echo $convert[$x+2]; ?>" placeholder="Phone">
                        </div>
                      <?php $Nophone++;
                  }
                  else if ($convert[$x] == "Phone" && $Nophone == 0){
                      ?><label for="import_phone<?php echo $importNumber ?>" class="col-sm-2 control-label">Phone </label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control " id="import_phone<?php echo $importNumber ?>" name="import_phone[<?php echo $row['file_name'] ?>]" value = "<?php echo $convert[$x+1]; ?>" placeholder="Phone">
                        </div>
                      <?php $Nophone++;
                  }
                  else if ($convert[$x] == "Mobile" && $convert[$x+1] == "Number" && $Nophone == 0 || $convert[$x] == "Mobile" && $convert[$x+1] == "No" && $Nophone == 0){
                      ?><label for="import_phone<?php echo $importNumber ?>" class="col-sm-2 control-label">Phone </label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control " id="import_phone<?php echo $importNumber ?>" name="import_phone[<?php echo $row['file_name'] ?>]" value = "<?php echo $convert[$x+2]; ?>" placeholder="Phone">
                        </div>
                      <?php $Nophone++;
                  }
                  else if ($convert[$x] == "Mobile" && $Nophone == 0){
                      ?><label for="import_phone<?php echo $importNumber ?>" class="col-sm-2 control-label">Phone </label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control " id="import_phone<?php echo $importNumber ?>" name="import_phone[<?php echo $row['file_name'] ?>]" value = "<?php echo $convert[$x+1]; ?>" placeholder="Phone">
                        </div>
                      <?php $Nophone++;
                  }
                  else if ($convert[$x] == "Tel" && $convert[$x+1] == "Number" && $Nophone == 0 || $convert[$x] == "Tel" && $convert[$x+1] == "No" && $Nophone == 0){
                      ?><label for="import_phone<?php echo $importNumber ?>" class="col-sm-2 control-label">Phone </label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control " id="import_phone<?php echo $importNumber ?>" name="import_phone[<?php echo $row['file_name'] ?>]" value = "<?php echo $convert[$x+2]; ?>" placeholder="Phone">
                        </div>
                      <?php $Nophone++;
                  }
                  else if ($convert[$x] == "Tel" && $Nophone == 0){
                      ?><label for="import_phone<?php echo $importNumber ?>" class="col-sm-2 control-label">Phone </label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control " id="import_phone<?php echo $importNumber ?>" name="import_phone[<?php echo $row['file_name'] ?>]" value = "<?php echo $convert[$x+1]; ?>" placeholder="Phone">
                        </div>
                      <?php $Nophone++;
                  }
                  else if ($convert[$x] == "Email" && $Noemail == 0){
                      ?><label for="import_email<?php echo $importNumber ?>" class="col-sm-2 control-label">Email </label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control " id="import_email<?php echo $importNumber ?>" name="import_email[<?php echo $row['file_name'] ?>]" value = "<?php echo $convert[$x+1]; ?>" placeholder="Email">
                        </div>
                      <?php $Noemail++;
                  }
                  else if ($convert[$x] == "Male" && $Nogender == 0 || $convert[$x] == "Man" && $Nogender == 0 || $convert[$x] == "Boy" && $Nogender == 0){
                      ?><label for="import_gender<?php echo $importNumber ?>" class="col-sm-2 control-label">Gender </label>
                        <div class="col-sm-2">
                            <select class="form-control select2" id="import_gender<?php echo $importNumber ?>" name="import_gender[<?php echo $row['file_name'] ?>]" style = 'width:100%'>
                                    <option value="">Select One</option>
                                    <option value="M"  SELECTED >Male</option>
                                    <option value="F">Female</option>
                            </select>
                        </div>
                      <?php $Nogender++;
                  }
                  else if ($convert[$x] == "Female" && $Nogender == 0 || $convert[$x] == "Girl" && $Nogender == 0 || $convert[$x] == "Women" && $Nogender == 0){
                      ?><label for="import_gender<?php echo $importNumber ?>" class="col-sm-2 control-label">Gender </label>
                        <div class="col-sm-2">
                            <select class="form-control select2" id="import_gender<?php echo $importNumber ?>" name="import_gender[<?php echo $row['file_name'] ?>]" style = 'width:100%'>
                                    <option value="">Select One</option>
                                    <option value="M" >Male</option>
                                    <option value="F" SELECTED>Female</option>
                            </select>
                        </div>
                      <?php $Nogender++;
                  }
                  $detail = $detail . " " . $convert[$x];
                }
                      ?>
                      <input type="hidden" class="form-control " id="import_renamefile<?php echo $imporNumber ?>" name="import_renamefile[<?php echo $row['file_name'] ?>]" value = "<?php echo $filename; ?>">               
                      <input type="hidden" class="form-control " id="import_type<?php echo $imporNumber ?>" name="import_type[<?php echo $row['file_name'] ?>]" value = "<?php echo $fileFormat; ?>">
                      <input type="hidden" class="form-control " id="import_detail<?php echo $imporNumber ?>" name="import_detail[<?php echo $row['file_name'] ?>]" value = "<?php echo $detail; ?>">
                      <?php
                  if ($Noname == 0){
                      ?><label for="import_name<?php echo $importNumber ?>" class="col-sm-2 control-label">Name </label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control " id="import_name<?php echo $importNumber ?>" name="import_name[<?php echo $row['file_name'] ?>]" value = "" placeholder="Name">
                        </div><br>
                      <?php $Noname++;
                  }
                  if ($Nophone == 0){
                      ?><label for="import_phone<?php echo $importNumber ?>" class="col-sm-2 control-label">Phone </label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control " id="import_phone<?php echo $importNumber ?>" name="import_phone[<?php echo $row['file_name'] ?>]" value = "" placeholder="Phone">
                        </div>
                      <?php $Nophone++;
                  }
                  if ($Noemail == 0){
                      ?><label for="import_email<?php echo $importNumber ?>" class="col-sm-2 control-label">Email </label>
                        <div class="col-sm-2">
                        <input type="text" class="form-control " id="import_email<?php echo $importNumber ?>" name="import_email[<?php echo $row['file_name'] ?>]" value = "" placeholder="Email">
                        </div>
                      <?php $Noemail++;
                  }
                  if ($Nogender == 0){
                      ?><label for="import_gender<?php echo $importNumber ?>" class="col-sm-2 control-label">Gender </label>
                        <div class="col-sm-2">
                            <select class="form-control select2" id="import_gender<?php echo $importNumber ?>" name="import_gender[<?php echo $row['file_name'] ?>]" style = 'width:100%'>
                                    <option value="">Select One</option>
                                    <option value="M" >Male</option>
                                    <option value="F">Female</option>
                            </select>
                        </div>
                      <?php $Nogender++;
                  }                 
                ?> 
                      
                <?php if($_SESSION['empl_group'] == "4") {?>      
                    <label for="assign_to<?php echo $importNumber ?>" class="col-sm-2 control-label">Assign To</label>
                    <div class="col-sm-2">
                        <select class="form-control select2" id="import_assign<?php echo $importNumber ?>" name="import_assign[<?php echo $row['file_name'] ?>]" style = 'width:100%'>
                                <?php echo $this->assignCrtl;?>
                        </select>
                    </div>
                <?php } 
                else {?>
                    <label for="assign_to<?php echo $importNumber ?>" class="col-sm-2 control-label">Assign To</label>
                    <div class="col-sm-2">
                        <select class="form-control select2" id="import_assign<?php echo $importNumber ?>" name="import_assign[<?php echo $row['file_name'] ?>]" style = 'width:100%'>
                                
                        </select>
                    </div>
                <?php }?>
                    <label for="assign_comments" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-3">
                                <textarea class="form-control" rows="5" id="import_comments<?php echo $importNumber ?>" name="import_comments[<?php echo $row['file_name'] ?>]" placeholder="Description"></textarea>
                    </div>
                </div>
                <div class="content_right">
                    <?php if($row['file_type'] == "pdf" || $row['file_type'] == "txt"){ ?>
                            <iframe style="width:100%; height:500px;" src="dist/import/<?php echo $row['file_name']?>" readable>
                            </iframe>
                    <?php } else {?>
                            <p style="color: red; text-align: center;font-size: 18px;">Microsoft Words cant show</p>
                    <?php }?>
               
                </div>
             </div>
             
             <?php 
             $importNumber++;
                  }?>
                       
  
                <script>
                    
//                    $(document).ready(function(){
//                        
//
//                        
//                    });
//                function openTabs(evt, filename) {
//                    
//                    $('.tabcontent').css('display','none');
//                    $('#'+filename).css('display','');
//                    var i, tabcontent, tablinks;
//                    tabcontent = document.getElementsByClassName("tabcontent");
//                    for (i = 0; i < tabcontent.length; i++) {
//                        tabcontent[i].style.display = "none";
//                    }
//                    tablinks = document.getElementsByClassName("tablinks");
//                    for (i = 0; i < tablinks.length; i++) {
//                        tablinks[i].className = tablinks[i].className.replace(" active", "");
//                    }
//                    document.getElementById(filename).style.display = "block";
//                    evt.currentTarget.className += " active";
//                }

                function openTabs(evt, filename) {
                    var i, tabcontent, tablinks;
                    tabcontent = document.getElementsByClassName("tabcontent");
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tablinks");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                    }
                    document.getElementById(filename).style.display = "block";
                    evt.currentTarget.className += " active";
                }




                // Get the element with id="defaultOpen" and click on it
                 document.getElementById("defaultOpen").click();
                </script>  
        
     <?php   
    }      
    public function showImportData(){
        
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
                <h1>Show Import Data</h1>
                </section>

                <!-- Main content -->
                <section class="content">
                <div class="row">
                <div class="col-xs-12">
                <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Import Applicant Table</h3>

                </div><!-- /.box-header -->


                
                <div class="box-body table-responsive">     
                    <form id = 'upload_resume' class="form-horizontal" method="post" action="applicantimport.php?action=importResume" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="upload_resume" class="col-sm-2 control-label">Upload Resume (max 20 file) (Please use PDF, MicrosoftWord.docx or txt file)</label>
                       <input style="margin-left:19%;"  data-toggle="tooltip" title="Please upload pdf, docx or txt file only and different file name" type="file" name="files[]" id="resume_file" type="file" onchange="makeFileList();" multiple/>
                       <br><br>


                        <ul id="fileList" class="list_file" style="list-style-type:none; font-size: 15px; list-style-position:inside;">
                        </ul>    
                    </div>
                    <div class="col-sm-4">
                         <input type="submit" class="btn btn-info hide_btn" value="Import">               
                    </div>
                    
                    <script type="text/javascript">   
                        
		function makeFileList() {
			var input = document.getElementById("resume_file");
			var ul = document.getElementById("fileList");
			while (ul.hasChildNodes()) {
				ul.removeChild(ul.firstChild);
			}
			for (var i = 0; i < input.files.length; i++) {
				var li = document.createElement("li");
				li.innerHTML = i+1 + ". "  + input.files[i].name;
				ul.appendChild(li);
			}
			if(!ul.hasChildNodes()) {
				var li = document.createElement("li");
				li.innerHTML = 'No Files Selected';
				ul.appendChild(li);
			}
                    }
                    </script>
                    </form>
                    <form id = 'upload_resume' method="post" action ='applicantimport.php?action=saveImportData'>
                    <?php $this->showImportDetail();?>       
                                
                                
                                <div class="col-sm-3 "><br><br>
              <button type = "submit" class="btn btn-info save_appfamily_btn">
                  Save
              </button>
              <input type = 'hidden' value = '<?php echo $this->appfamily_id;?>' name = 'appfamily_id' id = 'appfamily_id'/>
              </div></form>
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
    public function saveImportData(){
        global $notification_desc;
        $table_field = array('applicant_name','applicant_tel','applicant_group', 'applicant_status', 'applicant_email', 'applicant_sex', 'applicant_tmpfile_name');
        
        $ftable_field = array('assign_to','follow_type','interview_time','interview_date',
                              'interview_company','interview_by','fol_available_date','fol_position_offer','fol_expected_salary',
                              'fol_offer_salary','attend_interview','received_offer','follow_group','comments',
                              'follow_notice','applfollow_id');
 
        $ntable_field = array('noti_id', 'noti_to', 'noti_follow_id', 'noti_desc', 'noti_view_status');
        
        $rtable_field = array('resume_name', 'resume_appl_id', 'resume_type', 'resume_url', 'resume_detail');
        
        for($i=0;$i<sizeof($_POST['import_name']);$i++){
            $file_name = $_POST['file_name'][$i];

            if($_POST['file_name'][$i] != "")
            {
            $table_value = array($_POST['import_name'][$file_name], $_POST['import_phone'][$file_name], 5, 1, $_POST['import_email'][$file_name], $_POST['import_gender'][$file_name], $_POST['file_name'][$i]);

            //save applicant
            $remark = "Save Import Applicant Data";
            $this->save->SaveData($table_field,$table_value,'db_applicant','applicant_id',$remark);
            $this->applicant_id = $this->save->lastInsert_id;


            $remark = "Save Import Followup Data";

                //save followup
                if($_SESSION['empl_group'] == "4") {

                    $ftable_value = array($_POST['import_assign'][$file_name], '3', '', '', '', '', '', '', '', '', '', '', 3, $_POST['import_comments'][$file_name], 0, $this->applicant_id);
                    $this->save->SaveData($ftable_field,$ftable_value,'db_followup','follow_id',$remark);
                    $this->follow_id = $this->save->lastInsert_id;

                    $ntable_value = array('', $_POST['import_assign'][$file_name], $this->follow_id, $notification_desc['3'], 0); 
                    $remark = "Save Notification Data";
                    $this->save->SaveData($ntable_field,$ntable_value,'db_notification','noti_id',$remark);

                }
                else{
                    $ftable_value = array('', '1', '', '', '', '', '', '', '', '', '', '', 3, $_POST['import_comments'][$file_name], 0, $this->applicant_id);    
                    $this->save->SaveData($ftable_field,$ftable_value,'db_followup','follow_id',$remark);
                    $this->follow_id = $this->save->lastInsert_id;

                    $empl_id = $_SESSION['empl_group'];
                    $sql5 = "select empl_manager from db_empl where empl_id = '$empl_id";
                    $query5 = mysql_query($sql5);
                    $row5 = mysql_fetch_array($query5);

                    $ntable_value = array('', $row5['empl_manager'], $this->follow_id, $notification_desc['1'], 0); 
                    $remark = "Save Notification Data";
                    $this->save->SaveData($ntable_field,$ntable_value,'db_notification','noti_id',$remark);
                }
                          mkdir("dist/file/$this->applicant_id", 0755, true);
                          $file = "dist/import/$file_name";
                          $file1 = "dist/file/$this->applicant_id/$file_name";
                          copy($file, $file1);
                        $remark = "Update Import Applicant Data";
                        $rtable_value = array($file_name, $this->applicant_id, $_POST['import_type'][$file_name], $file1, $_POST['import_detail'][$file_name]);
                        $this->save->SaveData($rtable_field,$rtable_value,'db_resume','resume_id',$remark);
                
            }
        
        }            
            $sql3 = "select file_name from db_importfile";
            $query3 = mysql_query($sql3);
               while($row = mysql_fetch_array($query3)){
                    $tmpFname = $row['file_name'];
                    unlink("dist/import/$tmpFname");
               }
            $sql2 = "TRUNCATE TABLE db_importfile";
            mysql_query($sql2);
    }
}
?>

