<?php
/*
 * To change this tapplicantate, choose Tools | Tapplicantates
 * and open the tapplicantate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class JobsPosting {

    public function JobsPosting(){
        include_once 'class/SelectControl.php';
        $this->select = new SelectControl();

    }
    public function create(){

        $table_field = array('job_owner','job_person_incharge','job_title','job_category','job_short_remarks',
                             'job_internal_remarks','job_salary','job_postal_code','job_unit_no','job_street',
                             'job_type','job_description','job_status','job_delete',
                             'job_seo_title','job_seo_keyword','job_seo_description'
                            );
        $table_value = array($this->job_owner,$this->job_person_incharge,$this->job_title,$this->job_category,$this->job_short_remarks,
                             $this->job_internal_remarks,$this->job_salary,$this->job_postal_code,$this->job_unit_no,$this->job_street,
                             $this->job_type,$this->job_description,$this->job_status,0,
                             $this->job_seo_title,$this->job_seo_keyword,$this->job_seo_description
                            );
    
        $remark = "Insert Jobs Posting.";

        if(!$this->save->SaveData($table_field,$table_value,'db_jobs','job_id',$remark)){
           return false;
        }else{
           $this->job_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){

        $table_field = array('job_owner','job_person_incharge','job_title','job_category','job_short_remarks',
                             'job_internal_remarks','job_salary','job_postal_code','job_unit_no','job_street',
                             'job_type','job_description','job_status','job_delete',
                             'job_seo_title','job_seo_keyword','job_seo_description'
                            );
        $table_value = array($this->job_owner,$this->job_person_incharge,$this->job_title,$this->job_category,$this->job_short_remarks,
                             $this->job_internal_remarks,$this->job_salary,$this->job_postal_code,$this->job_unit_no,$this->job_street,
                             $this->job_type,$this->job_description,$this->job_status,0,
                             $this->job_seo_title,$this->job_seo_keyword,$this->job_seo_description
                            ); 
    
        $remark = "Update Jobs Posting.";

        if(!$this->save->UpdateData($table_field,$table_value,'db_jobs','job_id',$remark,$this->job_id)){
           return false;
        }
        else{
           return true;
        }
    }

    public function pictureManagement(){
        if(!file_exists("dist/images/applicant")){
           mkdir('dist/images/applicant/');
        }
        $isimage = false;
        if($this->image_input['type'] == 'image/png' || $this->image_input['type'] == 'image/jpeg' || $this->image_input['type'] == 'image/gif'){
           $isimage = true;
        }

        if($this->image_input['size'] > 0 && $isimage == true){
            if($this->action == 'update'){
                unlink("dist/images/applicant/{$this->applicant_id}.png");
            }

                move_uploaded_file($this->image_input['tmp_name'],"dist/images/applicant/{$this->applicant_id}.png");
        }
    }    
    public function fetchJobsPostingDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_jobs WHERE job_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type == 1){
            $row = mysql_fetch_array($query);
            $this->job_id = $row['job_id'];
            $this->job_owner = $row['job_owner'];
            $this->job_person_incharge = $row['job_person_incharge'];
            $this->job_title = $row['job_title'];
            $this->job_category = $row['job_category'];
            $this->job_short_remarks = $row['job_short_remarks'];
            $this->job_internal_remarks = $row['job_internal_remarks'];
            $this->job_salary = $row['job_salary'];
            $this->job_postal_code = $row['job_postal_code'];
            $this->job_unit_no = $row['job_unit_no'];
            $this->job_street = $row['job_street'];
            $this->job_type = $row['job_type'];
            $this->job_description = $row['job_description'];
            $this->job_status = $row['job_status'];
            $this->job_seo_title = $row['job_seo_title'];
            $this->job_seo_keyword = $row['job_seo_keyword'];
            $this->job_seo_description = $row['job_seo_description'];
            
            return true;
        }else if($type == 2){
            $row = mysql_fetch_array($query);
            return $row;
        }
        else{
             return $query;
        }
    }
    
    public function delete(){
        $table_field = array('job_delete');
        $table_value = array(1);
        $remark = "Delete Jobs.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_jobs','job_id',$remark,$this->job_id)){
           return false;
        }
        else{
           return true;
        }
    }
    public function getInputForms($action){
        global $mandatory; 
        $this->job_categoryCrtl = $this->select->getCategorySelectCtrl($this->job_category);
        $this->jobownerCrtl = $this->select->getClientSelectCtrl($this->job_owner);
        $this->assignCrtl = $this->select->getEmployeeSelectCtrl($this->job_person_incharge,'Y');

//        $this->assignCrtl = $this->select->getAssignSelectCtrl($this->job_person_incharge);
    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Candidate Management</title>
    <?php
    include_once 'css.php';
    
    ?>    
    <style>
    .hide{
    display:none;
    }
    </style>
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
            <h1>Job Posting Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->job_id > 0){ echo "Update Job Posting";}
                else{ echo "Create Job Posting";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = '' onclick = "window.location.href='jobsposting.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'margin-right:10px;' onclick = "window.location.href='jobsposting.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'applicant_form' class="form-horizontal" action = 'jobsposting.php?action=create' method = "POST" enctype="multipart/form-data">
                    <input type ='hidden' name = 'current_tab' id = 'current_tab' value = "<?php echo $this->current_tab?>"/>
                  <div class="box-body">
                      
<!--                      <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                          <li tab = "General Info" class="tab_header <?php if(($this->current_tab == "") || ($this->current_tab == "General Info")){ echo 'active';}?>"><a href="#general" data-toggle="tab">General Info</a></li>
                          
                        </ul>
                      </div>-->
                      <div class="tab-content">
                          <div class=" tab-pane <?php if(($this->current_tab == "") || ($this->current_tab == "General Info")){ echo 'active';}?>" id="general">        
                              <?php echo $this->getGeneralForm();?> 
                          </div>
                      </div>

                     
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Back</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->job_id;?>" name = "job_id" id = "job_id"/>
                    <?php
                    if($this->job_id > 0){
                        $prm_code = "update";
                    }
                    else{
                        $prm_code = "create";
                    }
                    if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],$prm_code)){
                    ?>
                    <button type = "submit" class="btn btn-info">Submit</button>
                    <?php }?>
                  </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
          </section><!-- /.content -->
        </div><!-- /.container -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';
    
    ?>
        <script>
      $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('editor1');
        //bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();
      });
    </script>
    
    <script>
    $(document).ready(function() {
        <?php
        if($this->applicant_id > 0){
            echo " getBankCode($this->applicant_bank);";
        }
        ?>
        $('#applicant_applicantpass').change(function(){
        
           if(($("#applicant_applicantpass option:selected").text() == 'WP') || ($("#applicant_applicantpass option:selected").text() == 'SP')){
                if($('#levy_div').hasClass('hide')){
                    $('#levy_div').removeClass('hide');
                }
           }else{
                    $('#levy_div').addClass('hide');
           }
        });
        $('#applicant_email').keyup(function(){
            $('#applicant_login_email').val($(this).val());
        });
        $("#applicant_form").validate({
                  rules: 
                  {
                      applicant_name:
                      {
                          required: true
                      },
                      applicant_group:
                      {
                          required: true
                      },
                      applicant_nric:
                      {
                          required: true
                      },
                      applicant_login_email:
                      {
                          required: true,
                          remote: {
                                  url: "jobsposting.php?action=validate_email",
                                  type: "post",
                                  data: 
                                        {
                                            applicant_id: function()
                                            {
                                                return $("#applicant_id").val();
                                            }
                                        }
                              }
                      },
                      applicant_login_password:
                      {
                        required: true,
                      },
                      applicant_login_password_cm:
                      {
                        required: true,
                        minlength : 5,
                        equalTo : "#applicant_login_password"
                      },
                      applicant_sex:
                      {
                        required: true,
                      }
                      
                  },
                  messages:
                  {
                      applicant_name:
                      {
                          required: "Please enter name."
                      },
                      customer_login_id:
                      {
                          required: "Please enter customer login email.",
                          remote: "Login email duplicate."
                      },
                      customer_login_password:
                      {
                            required: "Please enter Password."
                      },
                      customer_confirmpassword:
                      {
                            required: "Please enter Confirm Password."
                      }
                  }
              });
            $('.tab_header').click(function(){
                $('#current_tab').val($(this).attr('tab'));
            }); 
            $('.save_salary_btn').click(function(){
                var data = "action=saveSalary&applicant_id=<?php echo $this->applicant_id;?>&applicantsalary_date="+$('#applicantsalary_date').val()+"&applicantsalary_amount="+$('#applicantsalary_amount').val()+"&applicantsalary_remark="+encodeURIComponent($('#applicantsalary_remark').val());
                    data = data + "&applicantsalary_workday="+encodeURIComponent($('#applicantsalary_workday').val())+"&applicantsalary_hourly="+encodeURIComponent($('#applicantsalary_hourly').val())+"&applicantsalary_overtime="+encodeURIComponent($('#applicantsalary_overtime').val());
                    data = data + "&applicantsalary_id="+$('#applicantsalary_id').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'jobsposting.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>';
                           window.location.href = url + "&current_tab=" + $('#current_tab').val();
                       }else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            $('.save_appfamily_btn').click(function(){
                var data = "action=saveFamily&applicant_id=<?php echo $this->applicant_id;?>&family_id="+$('#family_id').val()+"&family_name="+$('#family_name').val()+"&family_relationship="+$('#family_relationship').val()+"&family_age="+$('#family_age').val();
                    data = data + "&family_occupation="+$('#family_occupation').val()+"&family_contact_no="+$('#family_contact_no').val();
                $.ajax({ 
                    type: 'POST',
                    url: 'jobsposting.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>&current_tab=family';
                           window.location.href = url + "&current_tab=" + $('#current_tab').val();
                       }else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            
            $('.save_followup_btn').click(function(){
            var command = CKEDITOR.instances['editor1'].getData();
            
//                console.log($('#follow_type').val());
                
                if ($('#follow_type').val()=='0'){
                var data = "action=saveFollowup&applicant_id=<?php echo $this->applicant_id;?>&assign_to="+$('#assign_to').val()+"&follow_type="+$('#follow_type').val();
                    data = data + "&company="+$('#f_company').val()+"&s_date="+$('#s_date').val()+"&p_offer="+$('#p_offer').val()+"&salary="+$('#f_salary').val(); 
                    data = data + "&followup_comments="+command+"&follow_notice="+$('#follow_notice').val()+"&follow_id="+$('#follow_id').val();
                }
                else if ($('#follow_type').val()=='2'){
                var data = "action=saveFollowup&applicant_id=<?php echo $this->applicant_id;?>&assign_to="+$('#assign_to').val()+"&follow_type="+$('#follow_type').val()+"&interview_time="+$('#interview_time').val();
                    data = data + "&interview_date="+$('#interview_date').val()+"&interview_company="+$('#interview_company').val()+"&interview_by="+$('#interview_by').val()+"&available_date="+$('#available_date').val()+"&position_offer="+$('#position_offer').val()+"&expected_salary="+$('#expected_salary').val()+"&offer_salary="+$('#offer_salary').val(); 
                    data = data + "&applicant_attend="+$('#applicant_attend').val()+"&received_offer="+$('#received_offer').val()+"&followup_comments="+command+"&follow_notice="+$('#follow_notice').val()+"&follow_id="+$('#follow_id').val();
                }
                else{
                var data = "action=saveFollowup&applicant_id=<?php echo $this->applicant_id;?>&assign_to="+$('#assign_to').val()+"&follow_type="+$('#follow_type').val()+"&interview_time=";
                    data = data + "&interview_date="+"&interview_company="+"&interview_by="+"&available_date="+"&position_offer="+"&expected_salary="+"&offer_salary="; 
                    data = data + "&applicant_attend="+"&received_offer="+"&followup_comments="+command+"&follow_notice="+$('#follow_notice').val()+"&follow_id="+$('#follow_id').val();
                }
                
                $.ajax({ 
                    type: 'POST',
                    url: 'jobsposting.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>&current_tab=followup';
                           window.location.href = url + "&current_tab=" + $('#current_tab').val();
                       }else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            $('.save_experience_btn').click(function(){
                var data = "action=saveWorkExperience&applicant_id=<?php echo $this->applicant_id;?>&previous_company="+$('#previous_company').val()+"&previous_position="+$('#previous_position').val();
                    data = data + "&previous_salary="+$('#previous_salary').val()+"&reason_leave="+$('#reason_leave').val()+"&exp_id="+$('#experience_id').val()+"&duration_conform="+$('#duration_conform').val();
                    data = data + "&previous_start_date="+$('#previous_start_date').val()+"&previous_end_date="+$('#previous_end_date').val()+"&responsibilities="+$('#responsibilities').val(); 
                $.ajax({ 
                    type: 'POST',
                    url: 'jobsposting.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       if(jsonObj.status == 1){
                           var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>';
                           window.location.href = url + "&current_tab=" + $('#current_tab').val();
                       }else{
                           alert("Fail to add line.");
                       }
                       issend = false;
                    }		
                 });
                 return false;
            });
            
            $('#follow_type').change(function(){
                var data = $(this);
                console.log(data);
                if(data.val() == "0"){
                $('.assigntoclient').show();
                $('.assigninterview').hide();
                }
                else if(data.val() == "2"){
                $('.assigninterview').show();
                $('.assigntoclient').hide();
                }
                else
                {
                    $('.assigntoclient').hide();
                    $('.assigninterview').hide();
                }
            });    
            
            if ($('#follow_type').val() == "0"){
                $('.assigntoclient').show();
                $('.assigninterview').hide();
                
            }
            else if($('#follow_type').val() == "2"){
                $('.assigninterview').show();
                $('.assigntoclient').hide();
            }
            else
            {
                    $('.assigninterview').hide();
                    $('.assigntoclient').hide();
            }               
            
            $('#applicant_success_assign').change(function(){
                var data = $(this);
                console.log(data);
                if(data.val() == "1"){
                $('.success_assign_job').show();
                }
                else
                {
                    $('.success_assign_job').hide();
                }
            });  
            
            if ($('#applicant_success_assign').val() == "1")
            {
                $('.success_assign_job').show();
            }
            else
            {
                    $('.success_assign_job').hide();
            }    
            
            
            $('.upload_resume_btn').click(function(){
                var data = "action=uploadResume&applicant_id=<?php echo $this->applicant_id;?>&"+$('#applicant_form').serialize();
                var fd = new FormData(document.getElementById("applicant_form"));
                fd.append('action','uploadResume');
                fd.append('applicant_id','<?php echo $this->applicant_id;?>');
                $.ajax({ 
                    type: 'POST',
                    url: 'jobsposting.php',
                    cache: false,
                    data:fd,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    async: false,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(fd) {
                       var jsonObj = eval ("(" + fd + ")");

                       issend = false;
                    }		
                 });
                 var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>';
                 window.location.href = url + "&current_tab=" + $('#current_tab').val();
                 return false;
            });            
            
            $('#job_owner').change(function(){
                var value = $(this);
                var data = "action=getAddress&partner_id="+value.val();
                $.ajax({ 
                    type: 'POST',
                    url: 'jobsposting.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                      
                       document.getElementById("job_postal_code").value = jsonObj['address']['postal_code'];
                       document.getElementById("job_unit_no").value = jsonObj['address']['unit_no'];
                       document.getElementById("job_street").value = jsonObj['address']['address'];
                       
                       issend = false;
                    }		
                 });
            });
            
            $('.upload_file_btn').click(function(){
                var data = "action=uploadFile&applicant_id=<?php echo $this->applicant_id;?>&"+$('#applicant_form').serialize();
                var fd = new FormData(document.getElementById("applicant_form"));
                fd.append('action','uploadFile');
                fd.append('applicant_id','<?php echo $this->applicant_id;?>');
                fd.append('upload_file','#file_type');
                $.ajax({ 
                    type: 'POST',
                    url: 'jobsposting.php',
                    cache: false,
                    data:fd,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    async: false,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(fd) {
                       var jsonObj = eval ("(" + fd + ")");

                       issend = false;
                    }		
                 });
                 var url = '<?php echo $_SERVER['PHP_SELF'] . "?action=edit&applicant_id={$_REQUEST['applicant_id']}";?>';
                 window.location.href = url + "&current_tab=" + $('#current_tab').val();
                 return false;
            });             
            
            $('#applicant_bank').change(function(){
            
                getBankCode($(this).val());
            });
});
function getBankCode(bank){

                var data = "action=getBankCode&applicant_bank="+bank;
                $.ajax({ 
                    type: 'POST',
                    url: 'jobsposting.php',
                    cache: false,
                    data:data,
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       $('#applicant_bank_no').val(jsonObj.bank_no);
                    }		
                 });
}
    </script>
        <script type = "text/javascript">
			function checkEnter() {
				var x =  document.getElementById('job_postal_code').value;
				function Cloud(){
					jQuery.ajax({
						url: 'https://developers.onemap.sg/commonapi/search?searchVal='+x+'&returnGeom=Y&getAddrDetails=Y&pageNum=1',
						success: function(result){
							var TrueResult = JSON.stringify(result);
							jQuery.each(jQuery.parseJSON(TrueResult), function (item, value) {
								if (item == "results") {
									jQuery.each(value, function (i, object) {
										jQuery.each(object, function (subI, subObject) {
											if (subI == 'ADDRESS'){
												jQuery('#job_street').val(subObject); 
											} 
										});
									});
								}
							});
						}});
				}
				Cloud();
			}

			function checkEnter2() {
				var x =  document.getElementById('applicant_postal_code2').value;
				function Cloud(){
					jQuery.ajax({
						url: 'https://developers.onemap.sg/commonapi/search?searchVal='+x+'&returnGeom=Y&getAddrDetails=Y&pageNum=1',
						success: function(result){
							var TrueResult = JSON.stringify(result);
							jQuery.each(jQuery.parseJSON(TrueResult), function (item, value) {
								if (item == "results") {
									jQuery.each(value, function (i, object) {
										jQuery.each(object, function (subI, subObject) {
											if (subI == 'ADDRESS'){
												jQuery('#applicant_address2').val(subObject); 
											} 
										});
									});
								}
							});
						}});
				}
				Cloud();
			}
    </script>
  </body>
</html>
        <?php
                
    }
    public function getGeneralForm(){
        global $mandatory;
        
        if($this->applicant_id <=0){
            $this->applicant_joindate = system_date;
            
        }
        $this->applicantsalary_date = system_date;
    ?>
            <div class="form-group">
                  <label for="job_title" class="col-sm-2 control-label">Job Title </label>
                  <div class="col-sm-3">
                    <input type="text" class="form-control" id="job_title" name="job_title" value = "<?php echo $this->job_title;?>">
                  </div>
                  <label for="job_owner" class="col-sm-2 control-label">Client</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" id="job_owner" name="job_owner" style = 'width:100%'>
                         <?php echo $this->jobownerCrtl;?>
                    </select>
                  </div>
            </div>  
            <div class="form-group">
                <label for="job_category" class="col-sm-2 control-label" >Job Category</label>
                <div class="col-sm-3">
                 <select class="form-control select2" id="job_category" name="job_category" style = 'width:100%'>
                     <?php echo $this->job_categoryCrtl; ?>
                 </select>
                </div>
                <label for="job_person_incharge" class="col-sm-2 control-label">Person In Charge</label>
                <div class="col-sm-3">
                <?php //if($_SESSION['empl_group'] == "4") { ?>
                 <select class="form-control select2" id="job_person_incharge" name="job_person_incharge" style = 'width:100%'>
                     <?php echo $this->assignCrtl;?>
                 </select>
                <?php //}
                //else { ?>
<!--                    <select class="form-control select2" id="job_person_incharge" name="job_person_incharge" style = 'width:100%' disabled="">
                     <?php// echo $this->assignCrtl;?>
                 </select>-->
                <?php //} ?>
                    </div> 
            </div>
        <div class="form-group">
            <label for="job_salary" class="col-sm-2 control-label" >Job Salary</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="job_salary" name="job_salary" value = "<?php echo $this->job_salary;?>" placeholder="Salary">
            </div>
            <label for="job_type" class="col-sm-2 control-label">Job Type</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="job_type" name="job_type" style = 'width:100%'>
                    <option value="">Select One</option>
                    <option value="T" <?php if($this->job_type == 'T'){ echo 'SELECTED';}?>>Temporary</option>
                    <option value="F" <?php if($this->job_type == 'F'){ echo 'SELECTED';}?>>Permanent</option>
                    <option value="C" <?php if($this->job_type == 'C'){ echo 'SELECTED';}?>>Contract</option>
                 </select>
            </div>
        </div>
        <div class="form-group">
            <label for="job_internal_remarks" class="col-sm-2 control-label">Internal Remark</label>
            <div class="col-sm-3">
                 <textarea class="form-control" rows="3" id="job_internal_remarks" name="job_internal_remarks" placeholder="Internal Remarks"><?php echo $this->job_internal_remarks;?></textarea>
            </div>
            <label for="job_short_remarks" class="col-sm-2 control-label">Short Description</label>
            <div class="col-sm-3">
                 <textarea class="form-control" rows="3" id="job_short_remarks" name="job_short_remarks" placeholder="Short Description"><?php echo $this->job_short_remarks;?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="job_status" class="col-sm-2 control-label">Job Status</label>
             <div class="col-sm-3">
                 <select class="form-control select2" id="job_status" name="job_status" style = 'width:100%'>
                    <option value="">Select One</option>
                    <option value="A" <?php if($this->job_status == 'A'){ echo 'SELECTED';}?>>Active</option>
                    <option value="C" <?php if($this->job_status == 'C'){ echo 'SELECTED';}?>>Close</option>
                 </select>
             </div>
            <label for="job_postal_code" class="col-sm-2 control-label" >Postal Code</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="job_postal_code" onkeyup="checkEnter()" name="job_postal_code" value = "<?php echo $this->job_postal_code;?>" placeholder="Postal Code">
            </div>
        </div>
        <div class="form-group">
            <label for="job_unit_no" class="col-sm-2 control-label" >Unit No</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="job_unit_no" name="job_unit_no" value = "<?php echo $this->job_unit_no;?>" placeholder="Unit No">
            </div>
            <label for="job_street" class="col-sm-2 control-label">Street</label>
            <div class="col-sm-3">
                 <textarea class="form-control" rows="3" id="job_street" name="job_street" placeholder="Street" readonly><?php echo $this->job_street;?></textarea>
            </div>
        </div>
        <div class="form-group">
                <label for="co_owner" class="col-sm-2 control-label">Co Owner</label>
                <div class="col-sm-3">
                <?php //if($_SESSION['empl_group'] == "4") { ?>
                 <select class="form-control select2" id="co_owner" name="co_owner" style = 'width:100%'>
                     <?php echo $this->assignCrtl;?>
                 </select>
                </div>
        </div>
        <div class="form-group">
                    <label for="job_description" class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-8">
                    <textarea id="editor1" class="job_description" name="job_description" placeholder="Description" rows="10" cols="80"><?php echo $this->job_description;?>
                    </textarea>
                    </div>
        </div>
        <h3><u>SEO Search</u></h3>
        <div class="form-group">
            <label for="job_seo_title" class="col-sm-2 control-label">Meta Tag Title</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="job_seo_title" name="job_seo_title" value = "<?php echo $this->job_seo_title;?>" placeholder="Search Title">
            </div>
            <label for="job_seo_keyword" class="col-sm-2 control-label">Meta Tag Keyword</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="job_seo_keyword" name="job_seo_keyword" value = "<?php echo $this->job_seo_keyword;?>" placeholder="Search Keyword">
            </div>
        </div>
        <div class="form-group">
            <label for="job_seo_description" class="col-sm-2 control-label">Meta Tag Description</label>
            <div class="col-sm-3">
                <textarea class="form-control" rows="3" id="job_seo_description" name="job_seo_description" placeholder="Search Description"><?php echo $this->job_seo_description;?></textarea>
            </div>
        </div>
    <?php
    }
    public function getContactForm(){
    ?> 
        <h3><u>Address Information</u></h3>
        <div class="form-group">
              <label for="applicant_postal_code" class="col-sm-2 control-label">Postal Code 1</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" onkeyup="checkEnter()" id="applicant_postal_code" name="applicant_postal_code" value = "<?php echo $this->applicant_postal_code;?>" placeholder="Postal Code">
              </div>
              <label for="applicant_postal_code2" class="col-sm-2 control-label">Postal Code 2</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" onkeyup="checkEnter2()" id="applicant_postal_code2" name="applicant_postal_code2" value = "<?php echo $this->applicant_postal_code2;?>" placeholder="Postal Code">
              </div>
        </div>
        <div class="form-group">
              <label for="applicant_unit_no" class="col-sm-2 control-label">Unit No 1</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_unit_no" name="applicant_unit_no" value = "<?php echo $this->applicant_unit_no;?>" placeholder="Unit No">
              </div>
              <label for="applicant_unit_no2" class="col-sm-2 control-label">Unit No 2</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_unit_no2" name="applicant_unit_no2" value = "<?php echo $this->applicant_unit_no2;?>" placeholder="Unit No">
              </div>
        </div>
        <div class="form-group">
            <label for="applicant_address" class="col-sm-2 control-label">Address 1</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="applicant_address" name="applicant_address" placeholder="Address" readonly><?php echo $this->applicant_address;?></textarea>
            </div>
            <label for="applicant_address2" class="col-sm-2 control-label">Address 2</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="applicant_address2" name="applicant_address2" placeholder="Address 2" readonly><?php echo $this->applicant_address2;?></textarea>
            </div>
        </div>
        <h3><u>Emergency Contact Address Information</u></h3>
        <div class="form-group">
            <label for="applicant_emer_contact" class="col-sm-2 control-label">Contact Person</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_emer_contact" name="applicant_emer_contact" value = "<?php echo $this->applicant_emer_contact;?>" placeholder="Contact Person">
            </div>
            <label for="applicant_emer_relation" class="col-sm-2 control-label">Relationship</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_emer_relation" name="applicant_emer_relation" value = "<?php echo $this->applicant_emer_relation;?>" placeholder="Relationship">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_emer_phone1" class="col-sm-2 control-label">Phone 1</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_emer_phone1" name="applicant_emer_phone1" value = "<?php echo $this->applicant_emer_phone1;?>" placeholder="Phone 1">
            </div>
            <label for="applicant_emer_phone2" class="col-sm-2 control-label">Phone 2</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_emer_phone2" name="applicant_emer_phone2" value = "<?php echo $this->applicant_emer_phone2;?>" placeholder="Phone 2">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_emer_address" class="col-sm-2 control-label">Address</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_emer_address" name="applicant_emer_address" value = "<?php echo $this->applicant_emer_address;?>" placeholder="Address">
            </div>
            <label for="applicant_emer_remarks" class="col-sm-2 control-label">Remarks</label>
            <div class="col-sm-3">
                <textarea class="form-control" rows="3" id="applicant_emer_remarks" name="applicant_emer_remarks" placeholder="Remarks"><?php echo $this->applicant_emer_remarks;?></textarea>
            </div>
        </div>
    <?php
    }
    public function getJobInfoForm(){
    ?> 
        <h3><u>Job Information</u></h3>
        <div class="form-group ">
            <label for="applicant_department" class="col-sm-2 control-label">Department</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="applicant_department" name="applicant_department" style = 'width:100%'>
                   <?php $sql = "SELECT empl_name FROM `db_empl` WHERE empl_group = '8'";
                   $query = mysql_query($sql);
                    while($row = mysql_fetch_array($query)){
                   echo $row['empl'];
                    }
                    ?>
                 </select>
            </div>
            <label for="applicant_designation" class="col-sm-2 control-label">Designation</label>
            <div class="col-sm-3">
                 <select class="form-control select2" id="applicant_designation" name="applicant_designation" style = 'width:100%'>
                   <?php echo $this->designationCrtl;?>
                 </select>
            </div>
        </div>
        <div class="form-group ">
            <label for="applicant_work_time_start" class="col-sm-2 control-label">Work time (Start)</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;' >
                <input type="text" class="form-control timepicker" id="applicant_work_time_start" name="applicant_work_time_start" value = "<?php echo $this->applicant_work_time_start;?>">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>

            <label for="applicant_work_time_end" class="col-sm-2 control-label">Work time (End)</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;'>
                <input type="text" class="form-control timepicker" id="applicant_work_time_end" name="applicant_work_time_end" value = "<?php echo $this->applicant_work_time_end;?>">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_joindate" class="col-sm-2 control-label">Join Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_joindate" name="applicant_joindate" value = "<?php echo format_date($this->applicant_joindate);?>" placeholder="Join Date">
            </div>
            <label for="applicant_address2" class="col-sm-2 control-label">Probation Period</label>
            <div class="col-sm-3">
                <select class="form-control " id="applicant_probation" name="applicant_probation">
                    <option value = '0' <?php if($this->applicant_probation == 1){ echo 'SELECTED';}?>>Select One</option>
                    <option value = '1' <?php if($this->applicant_probation == 1){ echo 'SELECTED';}?>>1</option>
                    <option value = '2' <?php if($this->applicant_probation == 2){ echo 'SELECTED';}?>>2</option>
                    <option value = '3' <?php if($this->applicant_probation == 3){ echo 'SELECTED';}?>>3</option>
                    <option value = '4' <?php if($this->applicant_probation == 4){ echo 'SELECTED';}?>>4</option>
                    <option value = '5' <?php if($this->applicant_probation == 5){ echo 'SELECTED';}?>>5</option>
                    <option value = '6' <?php if($this->applicant_probation == 6){ echo 'SELECTED';}?>>6</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-3">
                
            </div>
            <label for="applicant_extentionprobation" class="col-sm-2 control-label">Extention of Probation</label>
            <div class="col-sm-3">
                <select class="form-control " id="applicant_extentionprobation" name="applicant_extentionprobation">
                    <option value = '0' <?php if($this->applicant_extentionprobation == 1){ echo 'SELECTED';}?>>Select One</option>
                    <option value = '1' <?php if($this->applicant_extentionprobation == 1){ echo 'SELECTED';}?>>1</option>
                    <option value = '2' <?php if($this->applicant_extentionprobation == 2){ echo 'SELECTED';}?>>2</option>
                    <option value = '3' <?php if($this->applicant_extentionprobation == 3){ echo 'SELECTED';}?>>3</option>
                    <option value = '4' <?php if($this->applicant_extentionprobation == 4){ echo 'SELECTED';}?>>4</option>
                    <option value = '5' <?php if($this->applicant_extentionprobation == 5){ echo 'SELECTED';}?>>5</option>
                    <option value = '6' <?php if($this->applicant_extentionprobation == 6){ echo 'SELECTED';}?>>6</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_confirmationdate" class="col-sm-2 control-label">Confirmation Date</label>
            <div class="col-sm-3">
              <input type="text" class="form-control datepicker" id="applicant_confirmationdate" name="applicant_confirmationdate" value = "<?php echo format_date($this->applicant_confirmationdate);?>" placeholder="Confirmation Date">
            </div>
            <label for="applicant_joindate" class="col-sm-2 control-label">Termination / Resignation Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_resigndate" name="applicant_resigndate" value = "<?php echo format_date($this->applicant_resigndate);?>" placeholder="Termination / Resignation Date">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_prdate" class="col-sm-2 control-label">PR Date</label>
            <div class="col-sm-3">
              <input type="text" class="form-control datepicker" id="applicant_prdate" name="applicant_prdate" value = "<?php echo format_date($this->applicant_prdate);?>" placeholder="PR Date">
            </div>
            <label for="applicant_resignreason" class="col-sm-2 control-label">Terminate Reason</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="applicant_resignreason" name="applicant_resignreason" placeholder="Terminate Reason"><?php echo $this->applicant_remark;?></textarea>
            </div>
        </div>
        <!--<h3><u>Alert Supervisor</u></h3>-->
        <?php //echo $this->getApprovalPermissionForm();?>
        
    <?php
    }
    public function getBankForm(){
    ?>
        <div class="form-group">
              <label for="applicant_paymode" class="col-sm-2 control-label">Pay Mode</label>
              <div class="col-sm-3">
               <select class="form-control" id="applicant_paymode" name="applicant_paymode" style = 'width:100%' >
                    <option value = '' <?php if($this->applicant_paymode == ''){ echo 'SELECTED';}?>>Select One</option>
                    <option value = 'Cash' <?php if($this->applicant_paymode == 'Cash'){ echo 'SELECTED';}?>>Cash</option>
                    <option value = 'Cheque' <?php if($this->applicant_paymode == 'Cheque'){ echo 'SELECTED';}?>>Cheque</option>
               </select>
              </div>
              <label for="applicant_bank" class="col-sm-2 control-label">GIRO Bank Code</label>
              <div class="col-sm-3">
                  <select class="form-control select2" id="applicant_bank" name="applicant_bank" style = 'width:100%'>
                        <?php echo $this->bankCrtl;?>
                  </select>
              </div>
              <div class="col-sm-1">
                  <input type="text" class="form-control" id="applicant_bank_no"  value = "<?php echo $this->applicant_bank_no;?>" readonly>
              </div>
        </div>
        <div class="form-group">
              <label for="applicant_bank_acc_name" class="col-sm-2 control-label">GIRO Account Name</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_bank_acc_name" name="applicant_bank_acc_name" value = "<?php echo $this->applicant_bank_acc_name;?>" placeholder="GIRO Account Name">
              </div>
              <label for="applicant_bank_acc_no" class="col-sm-2 control-label">GIRO Account No.</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" id="applicant_bank_acc_no" name="applicant_bank_acc_no" value = "<?php echo $this->applicant_bank_acc_no;?>" placeholder="GIRO Account No.">
              </div>
        </div>
        <div class="form-group">

        </div>
    <?php
    }
    public function getForeignWorkerForm(){
    ?> 
        <h3><u>Work Permit Information</u></h3>
        <div class="form-group">
            <label for="applicant_work_permit" class="col-sm-2 control-label">Work Permit Number</label>
            <div class="col-sm-3">
                  <input type="text" class="form-control" id="applicant_work_permit" name="applicant_work_permit" value = "<?php echo $this->applicant_work_permit;?>" placeholder="Work Permit Number">
            </div>
            <label for="applicant_work_permit_date_arrival" class="col-sm-2 control-label">Date of Arrival</label>
            <div class="col-sm-3">
                  <input type="text" class="form-control datepicker" id="applicant_work_permit_date_arrival" name="applicant_work_permit_date_arrival" value = "<?php echo format_date($this->applicant_work_permit_date_arrival);?>" placeholder="Date of Arrival">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_work_permit_application_date" class="col-sm-2 control-label">Application Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_work_permit_application_date" name="applicant_work_permit_application_date" value = "<?php echo $this->applicant_work_permit_application_date;?>" placeholder="Application Date">
            </div>
            <label for="applicant_pass_issuance" class="col-sm-2 control-label">Issue Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_pass_issuance" name="applicant_pass_issuance" value = "<?php echo format_date($this->applicant_pass_issuance);?>" placeholder="Pass Issuance Date">
            </div>
        </div>
        <div class="form-group">
            <label for="applicant_pass_renewal" class="col-sm-2 control-label">Renewal Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="applicant_pass_renewal" name="applicant_pass_renewal" value = "<?php echo format_date($this->applicant_pass_renewal);?>" placeholder="Pass Renewal Date">
            </div>
            <label for="applicant_levy_amt" class="col-sm-2 control-label">Levy Amount</label>
            <div class="col-sm-3">
              <input type="text" class="form-control" id="applicant_levy_amt" name="applicant_levy_amt" value = "<?php echo $this->applicant_levy_amt;?>" placeholder="Levy Amount">
            </div>
        </div>
    <?php
    }
    public function getApprovalPermissionForm(){
    ?>
            <div class="form-group">
                <div class="col-sm-5">
                <h4>Leave</h4>
                </div>
                <div class="col-sm-5">
                <h4>Claims</h4>
                </div>
            </div>
            <div class="form-group">
                <label for="applicant_leave_approved1" class="col-sm-2 control-label">Approved level 1</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_leave_approved1" name="applicant_leave_approved1" style = 'width:100%' >
                        <?php echo $this->applicantLeaveApproved1Crtl;?>
                    </select>
                </div>
                <label for="applicant_claims_approved1" class="col-sm-2 control-label">Approved level 1</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_claims_approved1" name="applicant_claims_approved1" style = 'width:100%' >
                        <?php echo $this->applicantClaimsApproved1Crtl;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="applicant_leave_approved2" class="col-sm-2 control-label">Approved level 2</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_leave_approved2" name="applicant_leave_approved2" style = 'width:100%' >
                        <?php echo $this->applicantLeaveApproved2Crtl;?>
                    </select>
                </div>
                <label for="applicant_claims_approved2" class="col-sm-2 control-label">Approved level 2</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_claims_approved2" name="applicant_claims_approved2" style = 'width:100%' >
                        <?php echo $this->applicantClaimsApproved2Crtl;?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="applicant_leave_approved3" class="col-sm-2 control-label">Approved level 3</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_leave_approved3" name="applicant_leave_approved3" style = 'width:100%' >
                        <?php echo $this->applicantLeaveApproved3Crtl;?>
                    </select>
                </div>
                <label for="applicant_claims_approved3" class="col-sm-2 control-label">Approved level 3</label>
                <div class="col-sm-3">
                    <select class="form-control select2" id="applicant_claims_approved3" name="applicant_claims_approved3" style = 'width:100%' >
                        <?php echo $this->applicantClaimsApproved3Crtl;?>
                    </select>
                </div>
            </div>
    <?php
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
                <button class="btn btn-primary pull-right" onclick = "window.location.href='jobsposting.php?action=createForm'">Create New + </button>
                <?php }?>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  <table id="applicant_table" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style = 'width:3%'>No</th>
                        <th style = 'width:10%'>Title</th>
                        <th style = 'width:10%'>Category</th>
                        <th style = 'width:10%'>Owner</th>
                        <th style = 'width:10%'>Job Status</th>
                        <th style = 'width:10%'>Job Type</th>
                        <th style = 'width:10%'>Create Date</th>
                        <th style = 'width:20%'></th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php                           
                      $sql = "SELECT *, LEFT(insertDateTime,10) AS Date, RIGHT(insertDateTime, 8) AS Time FROM db_jobs where job_delete = '0'";
                      $query = mysql_query($sql);
                      $i = 1;
                      while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                            <td><?php echo $i;?></td>
                            <td><?php echo $row['job_title'];?></td>
                            <?php $category_id = $row['job_category'];
                                $sql2 = "SELECT category_name FROM db_category_job where category_id = '$category_id'";
                                $query2 = mysql_query($sql2);
                                $row2 = mysql_fetch_array($query2);
                            ?>
                            <td><?php echo $row2['category_name'];?></td>
                            <td>
                                <?php $partner_id = $row['job_owner'];
                                $sql3 = "SELECT partner_name FROM db_partner where partner_id = '$partner_id'";
                                $query3 = mysql_query($sql3);
                                $row3 = mysql_fetch_array($query3);
                                echo $row3['partner_name'];
                                ?>
                            </td>     
                            <td>
                               <?php if ($row['job_status'] == "P"){ echo "Public";}
                                else if($row['job_status'] == "D"){ echo "Draft";}
                                else {echo "Close";}?>
                            </td>
                            <td>
                                <?php if ($row['job_type'] == "P"){ echo "Part Time";}
                                else if($row['job_type'] == "F"){ echo "Full Time";}
                                else {echo "Contract";}?>
                            </td>                          
                            <td><?php echo format_date($row['Date']) . " " . $row['Time']?>     
                            <input type="hidden" value = "<?php echo print_r($row);?>"> 
                            </td>

                            <td class = "text-align-right">
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'update')){
                                ?>
                                <button type="button" class="btn btn-primary btn-info " onclick = "location.href = 'jobsposting.php?action=edit&job_id=<?php echo $row['job_id'];?>'">Edit</button>
                                <?php }?>
                                <?php 
                                if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'delete')){
                                ?>
                                <button type="button" class="btn btn-primary btn-danger " onclick = "confirmAlertHref('jobsposting.php?action=delete&job_id=<?php echo $row['job_id'];?>','Confirm Delete?')">Delete</button>
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
                        <th style = 'width:10%'>Title</th>
                        <th style = 'width:10%'>Category</th>
                        <th style = 'width:10%'>Owner</th>
                        <th style = 'width:10%'>Job Status</th>
                        <th style = 'width:10%'>Job Type</th>
                        <th style = 'width:10%'>Create Date</th>
                        <th style = 'width:20%'></th>
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
        
        $('#myModal').modal('hide');

//        $('#myModal').modal({
//        show: 'false'
//        });
        
        $('.remarks').click(function(){
   
  
                    var data = "action=getRemarkDetail&applicant_id="+$(this).attr("pid");
                    
                $.ajax({ 
                    type: 'POST',
                    url: 'jobsposting.php',
                    cache: false,
                    data:data,
                    error: function(xhr) {
                        alert("System Error.");
                        issend = false;
                    },
                    success: function(data) {
                       var jsonObj = eval ("(" + data + ")");
                       
                       var table ="";
                     
                        if(jsonObj['aRemarks'] != null)
                         {
                              //table ="<a href='jobsposting.php?action=edit&current_tab=followup&applicant_id=" + jsonObj['aRemarks']['applicant_id'][0] + "'><button type='button' class='btn btn-info' style= 'float:right'>Add Remarks</button></a><br><br>";
                       table = table + "<table id='" + "applicant_table' " + "class=" + "'table table-bordered table-hover'" + "><thead><tr> <th style = 'width:2%'>No</th><th style = 'width:5%'>Create By</th><th style = 'width:5%'>Assign</th>";
                           table = table + "<th style = 'width:5%'>Client</th><th style = 'width:7%'>Remark Type</th><th style = 'width:5%'>Received Offer</th><th style = 'width:15%'>Comments</th><th style = 'width:5%'>Create Time</th><th style = 'width:5%'>Create Date</th></tr></thead><tbody>";

//                    console.log(jsonObj['assign_to']);
//                    console.log(jsonObj['assign_time']);
                        var n = 1;
                    for(var i=0;i<jsonObj['aRemarks']['empl_name'].length;i++){
                    table = table + "<tr><td>" + n + "</td><td>" + jsonObj['aRemarks']['empl_name'][i] +"</td><td>" + jsonObj['aRemarks']['assign_to'][i] + "</td><td>" + jsonObj['aRemarks']['interview_company'][i] + "</td><td>" + jsonObj['aRemarks']['follow_type'][i] + "</td><td>" + jsonObj['aRemarks']['received_offer'][i] + "</td><td>" + "<a href='jobsposting.php?action=edit&current_tab=followup&applicant_id=" + jsonObj['aRemarks']['applicant_id'][i] + "&follow_id=" + jsonObj['aRemarks']['follow_id'][i] + "'>" + jsonObj['aRemarks']['comments'][i] + "</a></td><td>" + jsonObj['aRemarks']['time'][i] + "</td><td>" + jsonObj['aRemarks']['date'][i] + "</td></tr>" 
                    n++;
                    }
                    table = table + "</tbody><tfoot><tr> <th style = 'width:2%'>No</th><th style = 'width:5%'>Create By</th><th style = 'width:5%'>Assign</th>";
                           table = table + "<th style = 'width:5%'>Client</th><th style = 'width:7%'>Remark Type</th><th style = 'width:5%'>Received Offer</th><th style = 'width:15%'>Comments</th><th style = 'width:5%'>Create Time</th><th style = 'width:5%'>CreateDate</th></tr></tfoot></table>";
                       }
                    else
                   {
                       table = table + "No have any remarks.";
                   }
                                               
                    $('#remarks_content').html(table);
//                          $('#myModal').modal('show');
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
    public function email($staff_email){

        ob_start();
        ?>
        <html>
        <head>
          <title>Welcome Abroad FCS</title>
        </head>
        <body>
         <p>Hi New Staff,</p>

        <p>Welcome aboard! Please find the attached forms for your usage.</p>

        <p><b>Leaves</b>: All leaves are to be applied online via <a href = 'http://10.1.1.252:90/index.aspx' target = '_blank'>http://10.1.1.252:90/index.aspx</a>, 2 weeks in advance.</p>
        <p>Regardless paid or unpaid leaves, it will be subjected to approval.</p>
        <p>HR will send in a separate email on the log in credentials to you.</p>
        <br>
        <p><b>Claims</b>: Transport claims have to be submitted on the last day of every month.</p>
        <p>All original receipts have to be kept, photocopied and attached to the claim form.</p>
        <br>
        <p>The above forms are to be submitted to the HR-in charge.</p> 
        <br>
        <p><b>Pay Day</b>: All salaries will be issued on the 7th of every month via giro.</p>
        <br>
        <p><b>Access Card</b>: Please remember to tap your access card every day as it tracks your attendance.</p>
        <p>For those who forget to tap, on MC or on urgent leave, please record it down to your own online calendar at <b>Website</b>: <a href = 'http://cal.firstcom.com.sg/SOGo/' target = '_blank'>http://cal.firstcom.com.sg/SOGo/</a> </p>
        <p>Please look for HR-in charge to subscribe your personal online calendar.</p>
        <p><b><u>You are not allowed to pass your access card to anyone to tap for you.</u></b></p>
        <br>
        <p><b>SSC Form</b> : All new orders are to be submitted with the Sales Submission Checklist & any other relevant documents needed to be process. </p>
        <p>There will be no clocking of GP should there be any lack of documents.</p>
        <p>Please get advice from your team leader if you are unsure. </p>
        <br>
        <p><b>For Sales/Project Management Team - Quotation/Invoice Tapplicantate</b> (Make sure company name, address and item description is in CAPS)</p>
        <br>
        <p>Eg.</p> 
        <br>
        <p>Company name - SKM LUGGAGE PTE LTD</p>
        <br>
        <table>
            <tr>
                <td rowspan = '4' style = 'vertical-align:top'>Address - </td>
            </tr>
            <tr>
                <td>NO.5 KAKI BUKIT ROAD 2,</td>
            </tr>
            <tr>
                <td>#01-07 & 02-07, CITY WAREHOUSE,</td>
            </tr>
            <tr>
                <td>SINGAPORE 417839</td>
            </tr>
        </table>
        <br>

        <table>
            <tr>
                <td rowspan = '3' style = 'vertical-align:top'  >Item - </td>
            </tr>
            <tr>
                <td>CMS WEBSITE= $2800,</td>
            </tr>
            <tr>
                <td>SERVER RENTAL= $598 (2 years) (5GB)</td>
            </tr>
        </table>
<br>
           
<p>Total = $3398</p>
<br>
<p>Payment = 100%</p>
<br>
<p><b>MC/ Urgent Leave</b>: Please report to your respective team leaders and Gwen at 96714536. Take note of your leader's contact no. on your first day. Such reports are to be informed <b>before</b> your working hours.</p>
<br>
<p><b>Last Day of Work</b>: Please fill up the clearance form and return access card and laptop to the management on your last day of work.</p>
<br>
<p>Lastly, please be reminded to recycle paper. Set to <i>Print on Both Sides</i> when required. No color images is allowed.</p>
<br>
</body>
</html>
<?php
$message = ob_get_clean(); 
    
    $my_file = "Staff Claim Form.xlsx";
    $my_path = "";
    $my_name = "Gwendolyn Wu | FirstCom Solutions";
    $my_mail = "gwendolynwu@firstcom.com.sg";
    $my_replyto = "my_reply_to@mail.net";
    $my_subject = "Welcome Abroad FCS";
        

    //mail_attachment($my_file,$my_path,$staff_email,$my_mail,$my_name, $my_replyto,$my_subject,$message);

    // Mail it
//    mail("jasonchong@firstcom.com.sg", $subject, $message, $headers);
    }
    public function getApprovelEmail($type,$applicant_id){
        $sql = "SELECT applicant_{$type}_approved1,applicant_{$type}_approved2,applicant_{$type}_approved3
                FROM db_applicant 
                WHERE applicant_id = '$applicant_id'";
        $query = mysql_query($sql);
        $i = 0;
        if($row = mysql_fetch_array($query)){
            $level1_id = $row["applicant_{$type}_approved1"];
            $level2_id = $row["applicant_{$type}_approved2"];
            $level3_id = $row["applicant_{$type}_approved3"];
            $level1_email = getDataCodeBySql("applicant_email","db_applicant"," WHERE applicant_id = '$level1_id'");
            $level2_email = getDataCodeBySql("applicant_email","db_applicant"," WHERE applicant_id = '$level2_id'");
            $level3_email = getDataCodeBySql("applicant_email","db_applicant"," WHERE applicant_id = '$level3_id'");
            
            if($level1_id > 0){
                $i++;
            }
            if($level2_id > 0){
                $i++;
            }
            if($level3_id > 0){
                $i++;
            }
            
            return array('level1_id'=>$level1_id,'level2_id'=>$level2_id,'level3_id'=>$level3_id,
                         'level1_email'=>$level1_email,'level2_email'=>$level2_email,'level3_email'=>$level3_email,
                         'total_need_approved'=>$i);
        }else{
            return null;
        }

    }
    public function checkAccess($action){
        global $master_group;
        if(($action == 'createForm') || ($action == 'create') || ($action == '')){
            return true;
        }
        if(!in_array($_SESSION['applicant_group'],$master_group)){
            $applicant_id = getDataCodeBySql("applicant_id","db_applicant"," WHERE applicant_id = '$this->applicant_id'","");
            $record_count = getDataCountBySql("db_applicant"," WHERE applicant_id = '$applicant_id'");

            if(((($applicant_id <= 0) && ($record_count == 0)) || ($applicant_id != $_SESSION['empl_id']))){
                permissionLog();
                rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
                exit();
            }
        }
    }
    public function getBankCode(){
        $sql = "SELECT * FROM db_bank WHERE bank_id = '$this->applicant_bank'";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
            $bank_no = $row['bank_no'];
        }
        
        return $bank_no;
    }
    public function getDeclarationForm(){
        global $mandatory;
        
        if($this->applicant_id <=0){
            $this->applicant_joindate = system_date;
            
        }
        $this->applicantsalary_date = system_date;
    ?>
        <h3><u>Declaration</u></h3>
        <div class ="declaration-div">
        
        <div class="form-group">
            <label for="declaration_bankrupt" class=" control-label">1. &nbsp;Are you / Have you ever been an undischarged bankrupt?</label>
                 <div class="radio">
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_bankrupt" value = '1' <?php if(($this->declaration_bankrupt == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_bankrupt" value = '0' <?php if($this->declaration_bankrupt == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                     </div>
                     <div class="col-sm-3">
                            <input type="text" class="form-control" id="db_specify" name="db_specify" value = "<?php echo $this->db_specify;?>" placeholder="If Yes, Please specify">
                     </div>
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_physical" class=" control-label">2. &nbsp;Are you suffering from any physical / mental impairment or chronic / pre-existing illness?</label>
                 <div class="radio">
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_physical" value = '1' <?php if(($this->declaration_physical == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_physical" value = '0' <?php if($this->declaration_physical == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                     </div>
                     <div class="col-sm-3">
                            <input type="text" class="form-control" id="dp_specify" name="dp_specify" value = "<?php echo $this->dp_specify;?>" placeholder="If Yes, Please specify">
                     </div>
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_lt_medical" class=" control-label">3. &nbsp;Are you currently undergoing long-term medical treatment?</label>
                 <div class="radio">
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_lt_medical" value = '1' <?php if(($this->declaration_lt_medical == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_lt_medical" value = '0' <?php if($this->declaration_lt_medical == '0'){ echo 'CHECKED';}?>>No
                        </label>
                     </div>
                     <div class="col-sm-3">
                            <input type="text" class="form-control" id="dltm_specify" name="dltm_specify" value = "<?php echo $this->dltm_specify;?>" placeholder="If Yes, Please specify">
                     </div>
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_law" class=" control-label">4. &nbsp;Have you ever been convicted or found guilty of an offence in Court Of Law in any country?</label>
                 <div class="radio">
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_law" value = '1' <?php if(($this->declaration_law == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_law" value = '0' <?php if($this->declaration_law == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                      </div>
                      <div class="col-sm-3">
                            <input type="text" class="form-control" id="dl_specify" name="dl_specify" value = "<?php echo $this->dl_specify;?>" placeholder="If Yes, Please specify">
                     </div>
                 </div>
        </div>
        <div class="form-group">
          <label for="declaration_warning" class=" control-label">5. &nbsp;Have you ever been issued warning letters, suspended or dismissed from employment before?</label>
                 <div class="radio">
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_warning" value = '1' <?php if(($this->declaration_warning == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_warning" value = '0' <?php if($this->declaration_warning == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                     </div>
                     <div class="col-sm-3">
                            <input type="text" class="form-control" id="dw_specify" name="dw_specify" value = "<?php echo $this->dw_specify;?>" placeholder="If Yes, Please specify">
                     </div>
                </div>
        </div>
         <div class="form-group">
          <label for="declaration_applied" class=" control-label">6. &nbsp;Have you applied for any job with this company before?</label>
                 <div class="radio">
                     <div class="col-sm-3">
                        <label>
                          <input type="radio" name = "declaration_applied" value = '1' <?php if(($this->declaration_applied == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "declaration_applied" value = '0' <?php if($this->declaration_applied == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                     </div>
                     <div class="col-sm-3">
                            <input type="text" class="form-control" id="da_specify" name="da_specify" value = "<?php echo $this->da_specify;?>" placeholder="If Yes, Please specify">
                     </div>
                 </div>
          </div>
        </div>
    <?php        
    }
    public function getReferesForm(){
    ?> 
        <h3><u>Character Referee's</u></h3>
        <h5>Name 2 persons who are not your relatives</h5>
        <div class="form-group">
            <label for="referee_name1" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_name1" name="referee_name1" value = "<?php echo $this->referee_name1;?>" placeholder="Name">
            </div>
            <label for="referee_occupation1" class="col-sm-2 control-label">Occupation</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_occupation1" name="referee_occupation1" value = "<?php echo $this->referee_occupation1;?>" placeholder="Occupation">
            </div>
        </div>
        <div class="form-group">
            <label for="referee_year_know1" class="col-sm-2 control-label">Years Known</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_year_know1" name="referee_year_know1" value = "<?php echo $this->referee_year_know1;?>" placeholder="Year">
            </div>
            <label for="referee_contact_no1" class="col-sm-2 control-label">Contact No / Email Address</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_contact_no1" name="referee_contact_no1" value = "<?php echo $this->referee_contact_no1;?>" placeholder="Contact No">
            </div>
        </div>
        
        <br>
        <br>
        <br>
        
        <div class="form-group">
            <label for="referee_name2" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_name2" name="referee_name2" value = "<?php echo $this->referee_name2;?>" placeholder="Name">
            </div>
            <label for="referee_occupation2" class="col-sm-2 control-label">Occupation</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_occupation2" name="referee_occupation2" value = "<?php echo $this->referee_occupation2;?>" placeholder="Occupation">
            </div>
        </div>
        <div class="form-group">
            <label for="referee_year_know2" class="col-sm-2 control-label">Years Known</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_year_know2" name="referee_year_know2" value = "<?php echo $this->referee_year_know2;?>" placeholder="Year">
            </div>
            <label for="referee_contact_no2" class="col-sm-2 control-label">Contact No / Email Address</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="referee_contact_no2" name="referee_contact_no2" value = "<?php echo $this->referee_contact_no2;?>" placeholder="Contact No">
            </div>
        </div>
        <br><br>
        <h5>May we write to the following for a reference?</h5>
        <div class="form-group">
            <label for="referee_present_employer" class="col-sm-2 control-label">Your present employer</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "referee_present_employer" value = '1' <?php if(($this->referee_present_employer == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "referee_present_employer" value = '0' <?php if($this->referee_present_employer == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>

        </div>
        <div class="form-group">
            <label for="referee_previous_employer" class="col-sm-2 control-label">Your previous employer(s)</label>
                 <div class="radio">
                        <label>
                          <input type="radio" name = "referee_previous_employer" value = '1' <?php if(($this->referee_previous_employer == '1')){ echo 'CHECKED';}?>>Yes
                        </label> 
                      &nbsp;
                        <label>
                          <input type="radio" name = "referee_previous_employer" value = '0' <?php if($this->referee_previous_employer == '0'){ echo 'CHECKED';}?>>No
                        </label> 
                 </div>
        </div>

    <?php
    }
    public function saveNotification(){
        global $notification_desc; 
        $table_field = array('noti_id','noti_to','noti_url','noti_parent_id','noti_desc','noti_view_status');
            
            $sql = "select job_person_incharge from db_jobs where job_id = '$this->job_id'";
            $query = mysql_query($sql);
            $row = mysql_fetch_array($query);
            $assign_id = $row['job_person_incharge'];
     
            $table_value = array('', $assign_id, 'jobsposting.php?action=edit&job_id='.$this->job_id,$this->job_id ,$notification_desc['5'] ,0);   
 
        $remark = "Create Notification.";
        $this->save->SaveData($table_field,$table_value,'db_notification','noti_id',$remark);
        $this->noti_id = $this->save->lastInsert_id;
    }
    public function updateNotification(){
        $this->noti_id = $_POST['noti_id'];
        $table_field = array('noti_view_status');
        $table_value = array(1);
   
        $remark = "Update status notification";
        if(!$this->save->UpdateData($table_field,$table_value,'db_notification','noti_id',$remark,$this->noti_id," ")){
           return false;
        }else{
           return true;
        }
        
    }    
    
    public function getQualificationForm(){
    ?> 
        <h3><u>Qualification and Skill</u></h3>
        <div class="form-group">
            <label for="appl_n_level" class="col-sm-2 control-label">N-Levels</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_n_level" name="appl_n_level" value = "<?php echo $this->appl_n_level;?>" placeholder="Course">
            </div>
            <label for="appl_diploma" class="col-sm-2 control-label">Diploma</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_diploma" name="appl_diploma" value = "<?php echo $this->appl_diploma;?>" placeholder="Course">
            </div>
        </div>
        <div class="form-group">
            <label for="appl_o_level" class="col-sm-2 control-label">O-Levels</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_o_level" name="appl_o_level" value = "<?php echo $this->appl_o_level;?>" placeholder="Course">
            </div>
            <label for="appl_degree" class="col-sm-2 control-label">Degree</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_degree" name="appl_degree" value = "<?php echo $this->appl_degree;?>" placeholder="Course">
            </div>
        </div>        
        <div class="form-group">
            <label for="appl_a_level" class="col-sm-2 control-label">A-Levels</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_a_level" name="appl_a_level" value = "<?php echo $this->appl_a_level;?>" placeholder="Course">
            </div>
            <label for="appl_other_qualification" class="col-sm-2 control-label">Other</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_other_qualification" name="appl_other_qualification" value = "<?php echo $this->appl_other_qualification;?>" placeholder="Course">
            </div>
        </div>

        <br><br>
        
        <h5>Please state languages and proficiency level, e.g. excellent, good, fair, poor</5><br><br>
        <div class="form-group">
            <label for="appl_written" class="col-sm-2 control-label">Written</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_written1" name="appl_written" value = "<?php echo $this->appl_written;?>" placeholder="eg: English - good">
            </div>
            <label for="appl_spoken" class="col-sm-2 control-label">Spoken</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_spoken" name="appl_spoken" value = "<?php echo $this->appl_spoken;?>" placeholder="eg: English - good">
            </div>
        </div>
<!--        <div class="form-group">
            <label for="appl_written2" class="col-sm-2 control-label">Written 2</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_written2" name="appl_written2" value = "<?php echo $this->appl_written2;?>" placeholder="eg: English - good">
            </div>
            <label for="appl_spoken2" class="col-sm-2 control-label">Spoken 2</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_spoken2" name="appl_spoken2" value = "<?php echo $this->appl_spoken2;?>" placeholder="eg: English - good">
            </div>
        </div>
        <div class="form-group">
            <label for="appl_written3" class="col-sm-2 control-label">Written 3</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_written3" name="appl_written3" value = "<?php echo $this->appl_written3;?>" placeholder="eg: English - good">
            </div>
            <label for="appl_spoken3" class="col-sm-2 control-label">Spoken 3</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_spoken3" name="appl_spoken3" value = "<?php echo $this->appl_spoken3;?>" placeholder="eg: English - good">
            </div>
        </div>-->
<!--        <div class="form-group">
            <label for="appl_written3" class="col-sm-2 control-label">Written 3</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_written3" name="appl_written3" value = "<?php echo $this->appl_written3;?>" placeholder="eg: English - good">
            </div>
            <label for="appl_spoken3" class="col-sm-2 control-label">Spoken</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="appl_spoken3" name="appl_spoken3" value = "<?php echo $this->appl_spoken3;?>" placeholder="eg: English - good">
            </div>
        </div>-->

    <?php
    }    
    public function getOfficialUse(){
    ?> 
        <h3><u>Official Use</u></h3>
        <div class="form-group">
            <label for="overall_impression" class="col-sm-2 control-label">Overall Impression</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="overall_impression" name="overall_impression" placeholder="Comments"><?php echo $this->overall_impression;?></textarea>
            </div>
            <label for="communication_skills" class="col-sm-2 control-label">Communication Skills</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="communication_skills" name="communication_skills" placeholder="Comments"><?php echo $this->communication_skills;?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="other_comments" class="col-sm-2 control-label">Other</label>
            <div class="col-sm-3">
                  <textarea class="form-control" rows="3" id="other_comments" name="other_comments" placeholder="Comments"><?php echo $this->other_comments;?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="official_consultant" class="col-sm-2 control-label">Consultant</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="official_consultant" name="official_consultant" value = "<?php echo $this->official_consultant;?>" placeholder="Consultant Name">
            </div>
            <label for="official_date" class="col-sm-2 control-label">Date</label>
            <div class="col-sm-3">
                <input type="text" class="form-control datepicker" id="walkin_date" name="official_date" value = "<?php echo format_date($this->official_date);?>" placeholder="Date">
            </div>
        </div>
        <div class="form-group">
                    <label for="official_time" class="col-sm-2 control-label">Time</label>
            <div class="col-sm-3 input-group bootstrap-timepicker" style = 'float:left;padding-right: 15px;padding-left: 15px;'>
                <input type="text" class="form-control timepicker" id="official_time" name="official_time" value = "<?php echo $this->official_time;?>">
                <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
    <?php
    }
    public function getAddress(){
        $partner_id = $_REQUEST['partner_id'];
        $sql = "SELECT * FROM db_partner WHERE partner_id = '$partner_id'";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);
        $data = array();
        $data['postal_code'] = $row['partner_postal_code'];
        $data['unit_no'] = $row['partner_unit_no'];
        $data['address'] = $row['partner_bill_address'];
                
        return $data;
    }
 
}

?>
