<?php
/*
 * To change this tadditionaltypeate, choose Tools | Tadditionaltypeates
 * and open the tadditionaltypeate in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Email {

    public function Email(){

        

    }
    public function create(){
        $table_field = array('additionaltype_code','additionaltype_desc','additionaltype_seqno','additionaltype_status');
        $table_value = array($this->additionaltype_code,$this->additionaltype_desc,$this->additionaltype_seqno,$this->additionaltype_status);
        $remark = "Insert Email.";
        if(!$this->save->SaveData($table_field,$table_value,'db_additionaltype','additionaltype_id',$remark)){
           return false;
        }else{
           $this->additionaltype_id = $this->save->lastInsert_id;
           return true;
        }
    }
    public function update(){
        $table_field = array('additionaltype_code','additionaltype_desc','additionaltype_seqno','additionaltype_status');
        $table_value = array($this->additionaltype_code,$this->additionaltype_desc,$this->additionaltype_seqno,$this->additionaltype_status);
        
        $remark = "Update Email.";
        if(!$this->save->UpdateData($table_field,$table_value,'db_additionaltype','additionaltype_id',$remark,$this->additionaltype_id)){
           return false;
        }else{
           return true;
        }
    }
    public function fetchEmailDetail($wherestring,$orderstring,$wherelimit,$type){
        $sql = "SELECT * FROM db_additionaltype WHERE additionaltype_id > 0  $wherestring $orderstring $wherelimit";
        $query = mysql_query($sql);
        if($type > 0){
            $row = mysql_fetch_array($query);
            $this->additionaltype_id = $row['additionaltype_id'];
            $this->additionaltype_code = $row['additionaltype_code'];
            $this->additionaltype_desc = $row['additionaltype_desc'];
            $this->additionaltype_seqno = $row['additionaltype_seqno'];
            $this->additionaltype_status = $row['additionaltype_status'];
        }
        return $query;
    }
    public function delete(){
        if($this->save->DeleteData("db_additionaltype"," WHERE additionaltype_id = '$this->additionaltype_id'","Delete Prod-Grp.")){
            return true;
        }else{
            return false;
        }
    }
    public function getInputForm($action){
        global $mandatory;
        if($action == 'create'){
            $this->additionaltype_status = 1;
        }

    ?>
   <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Additional Type Management</title>
    <?php
    include_once 'css.php';
    include_once 'js.php';
    ?>

    <script src="dist/ckeditor/ckeditor.js"></script>
    <script>
    $(document).ready(function() {
//        CKEDITOR.replace('additionaltype_desc');
    
    
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
            <h1>Additional Type Management</h1>
        </section>
          <!-- Main content -->
          <section class="content">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><?php if($this->additionaltype_id > 0){ echo "Update Additional Type";}else{ echo "Create New Additional Type";}?></h3>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;' onclick = "window.location.href='additionaltype.php'">Search</button>
                <?php if(getWindowPermission($_SESSION['m'][$_SESSION['empl_id']],'create')){?>
                <button type = "button" class="btn btn-primary pull-right" style = 'width:150px;margin-right:10px;' onclick = "window.location.href='additionaltype.php?action=createForm'">Create New</button>
                <?php }?>
              </div>
                
                <form id = 'additionaltype_form' class="form-horizontal" action = 'additionaltype.php?action=create' method = "POST" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="additionaltype_code" class="col-sm-2 control-label">Additional Type Code</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="additionaltype_code" name="additionaltype_code" placeholder="Email Code" value = "<?php echo $this->additionaltype_code;?>" <?php echo $readonly;?>>
                      </div>
                      <label for="additionaltype_seqno" class="col-sm-2 control-label">Seq No</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" id="additionaltype_seqno" name="additionaltype_seqno" placeholder="Seq No" value = "<?php echo $this->additionaltype_seqno;?>" <?php echo $readonly;?>>
                      </div>
                    </div>   
                    <div class="form-group">
                      <label for="additionaltype_desc" class="col-sm-2 control-label">Description</label>
                      <div class="col-sm-3">
                      <textarea id="radditionaltype_desc" name="additionaltype_desc" class="form-control" rows="3" placeholder="Description" <?php echo $readonly;?>><?php echo $this->additionaltype_desc;?></textarea>
                      </div>
                        <label for="additionaltype_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-3">
                             <select class="form-control" id="additionaltype_status" name="additionaltype_status">
                                  <option value = '0' <?php if($this->additionaltype_status == 0){ echo 'SELECTED';}?>>In-Active</option>
                                  <option value = '1' <?php if($this->additionaltype_status == 1){ echo 'SELECTED';}?>>Active</option>
                             </select>
                        </div>
                    </div>

                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <button type="button" class="btn btn-default" onclick = "history.go(-1)">Cancel</button>
                    &nbsp;&nbsp;&nbsp;
                    <input type = "hidden" value = "<?php echo $action;?>" name = "action"/>
                    <input type = "hidden" value = "<?php echo $this->additionaltype_id;?>" name = "additionaltype_id"/>
                    <?php 
                    if($this->additionaltype_id > 0){
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
    public function getListing($filter_action){
  
$hostname = "{mail.j2websolution.com:993/imap/ssl/novalidate-cert}";
$username = 'support@j2websolution.com';
$password = '';



/* try to connect */
//$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
//
///* grab emails */
//$emails = imap_search($inbox,'ALL');
//
//$total_mail_inmailbox = imap_num_msg($inbox);
//$boxes = imap_list($inbox, "{imap.j2websolution.com}", "*");




  $srv = $hostname;
  $conn = imap_open($srv,$username,$password);
  

  
  
  $boxes = imap_list($conn, $srv, '*');

  
  $total_mailbox = array();

  foreach($boxes as $box){
      //{mail.j2websolution.com:993/imap/ssl/novalidate-cert}INBOX
      //{mail.j2websolution.com:993/imap/ssl/novalidate-cert}

   imap_reopen($conn, $box);
   $mailboxarray = explode("}",$box);
   $total_mailbox[$mailboxarray[1]] = imap_num_msg($conn);
//   echo "number of mailbox - {$mailboxarray[1]} : " . imap_num_msg($conn) . "</br>";




//   $emails = imap_search($conn, "ALL");
if($emails) {
    echo <<<EOF

<style>
div.toggler				{ border:1px solid #ccc; background:url(gmail2.jpg) 10px 12px #eee no-repeat; cursor:pointer; padding:10px 32px; }
div.toggler .subject	{ font-weight:bold; }
div.read					{ color:#666; }
div.toggler .from, div.toggler .date { font-style:italic; font-size:11px; }
div.body					{ padding:10px 20px; }
</style>
EOF;




	/* begin output var */
	$output = '';
	
	/* put the newest emails on top */
	rsort($emails);

	/* for every email... */
	foreach($emails as $email_number) {
		
		/* get information specific to this email */
		$overview = imap_fetch_overview($conn,$email_number,0);

		$message = imap_fetchbody($conn,$email_number,2);
		//var_dump($overview);die;
		/* output the email header information */
		$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
		$output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
		$output.= '<span class="from">'.$overview[0]->from.'</span>';
		$output.= '<span class="date">on '.$overview[0]->date.'</span>';
		$output.= '</div>';
		
		/* output the email body */
		$output.= '<div class="body">'.$message.'</div>';
	}
	
	echo $output;
  
}  }
//   die;
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Email Management</title>
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
            <h1>Email Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <?php echo $this->mail_side_menu($total_mailbox);?>
            <div class="col-md-9">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Inbox</h3>
                  <div class="box-tools pull-right">
                    <div class="has-feedback">
                      <input type="text" class="form-control input-sm" placeholder="Search Mail">
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      1-50/200
                      <div class="btn-group">
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                  <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                      <tbody>
                          
            <?php
                switch ($filter_action) {
                    case "filter_sent":

                        $mailbox_ref = $hostname . "INBOX.Sent";
                        break;
                    case "filter_trash":

                        $mailbox_ref = $hostname . "INBOX.Trash";
                    case "filter_junk":

                        $mailbox_ref = $hostname . "INBOX.Junk";
                    case "filter_drafts":

                        $mailbox_ref = $hostname . "INBOX.Drafts";
                        break;
                    default:
                         $mailbox_ref = $hostname . "INBOX";
                        break;
                }        
        imap_reopen($conn,$mailbox_ref);        
        $emails = imap_search($conn, "ALL");

        if($emails) {
            
	/* put the newest emails on top */
	rsort($emails);

                /* for every email... */
                foreach($emails as $email_number) {

                        /* get information specific to this email */
                        $overview = imap_fetch_overview($conn,$email_number,0);

                        $message = imap_fetchbody($conn,$email_number,1);
                      
	
            ?>             
                        <tr style = 'font-size:14px;'>
                          <td><input type="checkbox"></td>
                          <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                          <td class="mailbox-name"  ><a href="email.php?action=read_email&email_type=<?php echo $mailbox_ref;?>&email_number=<?php echo $email_number;?>"><?php echo $overview[0]->from;?></a></td>
                          <td class="mailbox-subject">
                              <b>
                                  <?php 
                                    echo mb_substr($overview[0]->subject,0,30,'UTF-8'); 
                                    if(mb_strlen($overview[0]->subject,'UTF-8') > 35){
                                      echo "....";
                                    }
                               
                                  ?>
                              </b>
                          </td>
                          <td class="mailbox-attachment"></td>
                          <td class="mailbox-date" style = 'text-align:right' ><?php echo $overview[0]->date;?></td>
                        </tr>
         <?php
                }
            }
         ?>
                      </tbody>
                    </table><!-- /.table -->
                  </div><!-- /.mail-box-messages -->
                </div><!-- /.box-body -->
                <div class="box-footer no-padding">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      1-50/200
                      <div class="btn-group">
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                </div>
              </div><!-- /. box -->
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
        $('#additionaltype_table').DataTable({
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
    public function readEmail(){
 
$hostname = "{mail.j2websolution.com:993/imap/ssl/novalidate-cert}";
$username = 'support@j2websolution.com';
$password = '';



/* try to connect */
//$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
//
///* grab emails */
//$emails = imap_search($inbox,'ALL');
//
//$total_mail_inmailbox = imap_num_msg($inbox);
//$boxes = imap_list($inbox, "{imap.j2websolution.com}", "*");




  $srv = $hostname;
  $conn = imap_open($srv,$username,$password);
  

  
  
  $boxes = imap_list($conn, $srv, '*');

  
  $total_mailbox = array();

  foreach($boxes as $box){
      //{mail.j2websolution.com:993/imap/ssl/novalidate-cert}INBOX
      //{mail.j2websolution.com:993/imap/ssl/novalidate-cert}

   imap_reopen($conn, $box);
   $mailboxarray = explode("}",$box);
   $total_mailbox[$mailboxarray[1]] = imap_num_msg($conn);
//   echo "number of mailbox - {$mailboxarray[1]} : " . imap_num_msg($conn) . "</br>";




//   $emails = imap_search($conn, "ALL");
if($emails) {
    echo <<<EOF

<style>
div.toggler				{ border:1px solid #ccc; background:url(gmail2.jpg) 10px 12px #eee no-repeat; cursor:pointer; padding:10px 32px; }
div.toggler .subject	{ font-weight:bold; }
div.read					{ color:#666; }
div.toggler .from, div.toggler .date { font-style:italic; font-size:11px; }
div.body					{ padding:10px 20px; }
</style>
EOF;




	/* begin output var */
	$output = '';
	
	/* put the newest emails on top */
	rsort($emails);

	/* for every email... */
	foreach($emails as $email_number) {
		
		/* get information specific to this email */
		$overview = imap_fetch_overview($conn,$email_number,0);

		$message = imap_fetchbody($conn,$email_number,2);
		//var_dump($overview);die;
		/* output the email header information */
		$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
		$output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
		$output.= '<span class="from">'.$overview[0]->from.'</span>';
		$output.= '<span class="date">on '.$overview[0]->date.'</span>';
		$output.= '</div>';
		
		/* output the email body */
		$output.= '<div class="body">'.$message.'</div>';
	}
	
	echo $output;
  
}  }
//   die;
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Email Management</title>
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
            <h1>Email Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <?php echo $this->mail_side_menu($total_mailbox);?>
            <div class="col-md-9">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Read Mail</h3>
                  <div class="box-tools pull-right">
                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                  </div>
                </div><!-- /.box-header -->
                
            <?php
                $mailbox_ref = $hostname . "INBOX";
                imap_reopen($conn,$mailbox_ref);        
                $emails = imap_search($conn, "ALL");
                
        if($emails){  

            
            $overview = imap_fetch_overview($conn,$email_number,0);

            $message = imap_fetchbody($conn,$email_number,1);
            
            ?>
                
                <div class="box-body no-padding">
                  <div class="mailbox-read-info">
                    <h3>Message Subject Is Placed Here</h3>
                    <h5>From: support@almsaeedstudio.com <span class="mailbox-read-time pull-right">15 Feb. 2015 11:03 PM</span></h5>
                  </div><!-- /.mailbox-read-info -->
                  <div class="mailbox-controls with-border text-center">
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Delete"><i class="fa fa-trash-o"></i></button>
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Reply"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Forward"><i class="fa fa-share"></i></button>
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i></button>
                  </div><!-- /.mailbox-controls -->
                  <div class="mailbox-read-message">
                   
                  </div><!-- /.mailbox-read-message -->
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <ul class="mailbox-attachments clearfix">
                    
                  </ul>
                </div><!-- /.box-footer -->
                <div class="box-footer">
                  <div class="pull-right">
                    <button class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
                    <button class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
                  </div>
                  <button class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
                  <button class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                </div><!-- /.box-footer -->
                
    <?php }?>      
              </div><!-- /. box -->
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
        $('#additionaltype_table').DataTable({
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
    public function compose(){
 
$hostname = "{mail.j2websolution.com:993/imap/ssl/novalidate-cert}";
$username = 'support@j2websolution.com';
$password = '';


  $srv = $hostname;
  $conn = imap_open($srv,$username,$password);
  

  
  
  $boxes = imap_list($conn, $srv, '*');

  
  $total_mailbox = array();

  foreach($boxes as $box){

   imap_reopen($conn, $box);
   $mailboxarray = explode("}",$box);
   $total_mailbox[$mailboxarray[1]] = imap_num_msg($conn);


  }
//   die;
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Email Management</title>
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
            <h1>Email Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <?php echo $this->mail_side_menu($total_mailbox);?>
            <div class="col-md-9">
              <div class="box box-primary">
                <form id = 'email_form' action = 'email.php?action=email' method = "POST">  
                    <div class="box-header with-border">
                      <h3 class="box-title">Compose New Message</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                      <div class="form-group">
                        <input class="form-control" name = "send_to" placeholder="To:">
                      </div>
                      <div class="form-group">
                        <input class="form-control" name = "subject" placeholder="Subject:">
                      </div>
                      <div class="form-group">
                        <textarea id="compose-textarea" name = "message" class="form-control" style="height: 300px">

                        </textarea>
                      </div>
                      <div class="form-group">
                        <div class="btn btn-default btn-file">
                          <i class="fa fa-paperclip"></i> Attachment
                          <input type="file" name="attachment">
                        </div>
                        <p class="help-block">Max. 32MB</p>
                      </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                      <div class="pull-right">
                        <button class="btn btn-default"><i class="fa fa-pencil"></i> Draft</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send</button>
                      </div>
                      <button class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
                    </div><!-- /.box-footer -->
                </form>
              </div><!-- /. box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper --><!-- /.content-wrapper -->
      <?php include_once 'footer.php';?>
    </div><!-- ./wrapper -->
    <?php
    include_once 'js.php';
    ?>
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script>
      $(function () {
        $("#compose-textarea").wysihtml5();  


      });
    </script>
  </body>
</html>
    <?php
    }
    public function mail_side_menu($total_mailbox){
?>
        <div class="col-md-3">
              <a href="email.php?action=compose" class="btn btn-primary btn-block margin-bottom">Compose</a>
              <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Folders</h3>
                  <div class="box-tools">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
                </div>
                <div class="box-body no-padding">
                  <ul class="nav nav-pills nav-stacked">
                    <li class = "<?php if($this->filter_action == ''){ echo 'active ';}?>" ><a href="email.php"><i class="fa fa-inbox"></i> Inbox <span class="label label-primary pull-right"><?php echo $total_mailbox['INBOX'];?></span></a></li>
                    <li class = "<?php if($this->filter_action == 'filter_sent'){ echo 'active ';}?>"><a href="email.php?action=filter_sent"><i class="fa fa-envelope-o"></i> Sent <span class="label label-primary pull-right"><?php echo $total_mailbox['INBOX.Sent'];?></span></a></li>
                    <li class = "<?php if($this->filter_action == 'filter_drafts'){ echo 'active ';}?>"><a href="email.php?action=filter_drafts"><i class="fa fa-file-text-o"></i> Drafts <span class="label label-primary pull-right"><?php echo $total_mailbox['INBOX.Drafts'];?></span></a></li>
                    <li class = "<?php if($this->filter_action == 'filter_junk'){ echo 'active ';}?>"><a href="email.php?action=filter_junk"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right"><?php echo $total_mailbox['INBOX.Junk'];?></span></a></li>
                    <li class = "<?php if($this->filter_action == 'filter_trash'){ echo 'active ';}?>"><a href="email.php?action=filter_trash"><i class="fa fa-trash-o"></i> Trash <span class="label label-primary pull-right"><?php echo $total_mailbox['INBOX.Trash'];?></span></a></li>
                  </ul>
                </div><!-- /.box-body -->
              </div><!-- /. box -->

        </div><!-- /.col -->
<?php
    }
    public function email_action(){
        require 'dist/PHPMailerAutoload.php';
        
        include_once "class/Empl.php";
        $e = new Empl();
        
        $e->fetchEmplDetail($wherestring, $orderstring, $wherelimit, $type);
        
        $mail = new PHPMailer;
        
        // Set PHPMailer to use the sendmail transport
        $mail->isSendmail();
        $mail->IsSMTP(); 
        //Set who the message is to be sent from
        if($_SESSION['empl_email'] != ""){
            $mail->setFrom($_SESSION['empl_email'], 'No-Reply');
        }else{
            $mail->setFrom('system-' . $_SESSION['empl_id'] . '@successhrc.com', 'No-Reply');
        }
        
        $to_array = explode(",",$this->message);

        foreach($to_array as $t){
            $mail->addAddress($t);
        }

        
        $mail->Subject = $this->subject;
        $mail->message = $this->message;
        
        if($attachfile != ""){
            $mail->addAttachment($attachfile);
        }
        
        if($mail->send()){
            return true;
        }else{
            return false;
        }
    }
}
?>
