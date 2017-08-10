<header class="main-header">
        <!-- Logo -->
        <a href="dashboard.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><img src = 'dist/img/Success-Logo.png'  /></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src = 'dist/img/Success-Logo.png' style = 'height:100%;padding-top:5px;padding-bottom:5px'/></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                
                
                
              <!-- Messages: style can be found in dropdown.less-->
              

            <?php 
            if($_SESSION['empl_group'] == '8' || $_SESSION['empl_group'] == '4'){ 
            ?>         
              
                    <?php 
                        $todayDate = date("Y-m-d");
                        if($_SESSION['empl_group'] == '8'){
                            $sql2 = "SELECT * FROM db_followup WHERE follow_type = '2' AND interview_date = '$todayDate' AND insertBy = '$_SESSION[empl_id]' AND fol_status = '0'";
                        }
                        if($_SESSION['empl_group'] == '4'){
                            $sql2 = "SELECT * FROM db_notification n INNER JOIN db_followup f ON n.noti_parent_id = f.follow_id INNER JOIN db_empl e ON f.insertBy = e.empl_id WHERE n.noti_desc = 'Assign New Candidate to own' AND n.noti_view_status = '0' AND n.noti_type = '0' AND e.empl_manager = '$_SESSION[empl_id]'";
                        }
                        $query2 = mysql_query($sql2);
                        $row2 = mysql_num_rows($query2);
                        
                        $i = 0;
                        if($_SESSION['empl_group'] == '8'){
                            $sql = "SELECT applicant.*, LEFT(follow.insertDateTime, 10) as Date, right(follow.insertDateTime, 8) as Time FROM db_applicant applicant join db_followup follow on applicant_id = applfollow_id WHERE follow.follow_type AND follow.assign_to = '$_SESSION[empl_id]' AND follow.fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                        }
                        if($_SESSION['empl_group'] == '4'){
                            $sql = "SELECT applicant.*, LEFT(follow.insertDateTime, 10) as Date, right(follow.insertDateTime, 8) as Time FROM db_applicant applicant join db_followup follow on applicant_id = applfollow_id INNER JOIN db_empl e ON follow.insertBy = e.empl_id WHERE follow.follow_type AND (follow.assign_to = '$_SESSION[empl_id]' OR e.empl_manager = '$_SESSION[empl_id]') AND follow.fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                        }
                        $query = mysql_query($sql);
                        while($row = mysql_fetch_array($query)){
                            $sql3 = "SELECT * FROM db_dashbroad_display WHERE display_type = 'new assign table' AND display_parent_id = '$row[applicant_id]' AND display_empl_id = '$_SESSION[empl_id]'";
                            $query3 = mysql_query($sql3);
                            $row3 = mysql_num_rows($query3);
                            if($row3 == 0){                           
                                $i++;
                            }
                        }
                        $total = (double)$row2 + $i;
                   ?>   
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning" style="background-color: #dd4b39!important"><?php echo $total;?></span>
                </a>
                <ul class="dropdown-menu ">
                  <li class="header">You have <?php echo $total;?> notifications</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                        <?php if($_SESSION['empl_group'] == '8'){?> 
                          <li>
                            <a href="dashboard.php">
                        <?php } if($_SESSION['empl_group'] == '4'){?> 
                                <li class="list">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                        <?php } ?>        
                            <?php                       
//                             $i = 0;
//                              $empl_id = $_SESSION['empl_id'];
//                              $sql = "SELECT applicant.*, LEFT(follow.insertDateTime, 10) as Date, right(follow.insertDateTime, 8) as Time FROM db_applicant applicant join db_followup follow on applicant_id = applfollow_id and assign_to = '$empl_id' WHERE fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
//                              $query = mysql_query($sql);
//                              while($row = mysql_fetch_array($query)){
//
//                                $nowDate = date("Y-m-d");
//                                $assignDate = $row['Date'];
//
//                                $datetime1 = date_create($assignDate);
//                                $datetime2 = date_create($nowDate);
//                                $interval = date_diff($datetime1, $datetime2);
//                                $day = $interval->format('%a');
//                                if($day <= "7"){
//                                    $i++;
//                                }   
//                              }
                             
                             
                                $i = 0;
                                if($_SESSION['empl_group'] == '8'){
                                $sql = "SELECT applicant.*, LEFT(follow.insertDateTime, 10) as Date, right(follow.insertDateTime, 8) as Time FROM db_applicant applicant join db_followup follow on applicant_id = applfollow_id WHERE follow.follow_type AND follow.assign_to = '$_SESSION[empl_id]' AND follow.fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                                }
                                if($_SESSION['empl_group'] == '4'){
                                    $sql = "SELECT * FROM db_notification n INNER JOIN db_followup f ON n.noti_parent_id = f.follow_id INNER JOIN db_empl e ON f.insertBy = e.empl_id WHERE n.noti_desc = 'Assign New Candidate to own' AND n.noti_view_status = '0' AND n.noti_type = '0' AND e.empl_manager = '$_SESSION[empl_id]'";
                                }
                                $query = mysql_query($sql);
                                while($row = mysql_fetch_array($query)){
                                    $sql3 = "SELECT * FROM db_dashbroad_display WHERE display_type = 'new assign table' AND display_parent_id = '$row[applicant_id]' AND display_empl_id = '$_SESSION[empl_id]'";
                                    $query3 = mysql_query($sql3);
                                    $row3 = mysql_num_rows($query3);
                                    if($row3 == 0){
                                       $i++;
                                    }
                                }                             
                             ?>       
                            <?php if($_SESSION['empl_group'] == '8'){?>     
                                  <i class="fa fa-users text-red"></i> <?php echo $i; ?> new candidate assign to you
                            <?php }
                            if($_SESSION['empl_group'] == '4'){?>  
                                  <i class="fa fa-users text-red"></i> <?php echo $i; ?> new candidate inform you
                            <?php } ?>
                        </a>
                      </li>
<!--                      <li>
                        <a href="dashboard.php">
                             <?php                       
                              $empl_id = $_SESSION['empl_id'];
                              $sql = "SELECT *, LEFT(insertDateTime,10) AS Date, right(insertDateTime, 8) as Time FROM db_jobs where job_person_incharge = '$empl_id' AND job_delete = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                              $query = mysql_query($sql);
                              while($row = mysql_fetch_array($query)){

                                $nowDate = date("Y-m-d");
                                $assignDate = $row['Date'];

                                $datetime1 = date_create($assignDate);
                                $datetime2 = date_create($nowDate);
                                $interval = date_diff($datetime1, $datetime2);
                                $day = $interval->format('%a');

                                if($day <= "30"){
                                    $a++;
                                }   
                              }?>                            
                          <i class="fa fa-warning text-yellow"></i> <?php echo $a;?> New Jobs
                        </a>
                      </li>-->
                      <li>
                        <a href="#calendar">
                           <?php 
                                $todayDate = date("Y-m-d");
                                if($_SESSION['empl_group'] == '8'){
                                    $sql = "SELECT applicant.*, LEFT(follow.insertDateTime, 10) as Date, right(follow.insertDateTime, 8) as Time FROM db_applicant applicant join db_followup follow on applicant_id = applfollow_id WHERE follow.follow_type AND follow.assign_to = '$_SESSION[empl_id]' AND follow.fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                                }
                                if($_SESSION['empl_group'] == '4'){
                                    $sql = "SELECT applicant.*, LEFT(follow.insertDateTime, 10) as Date, right(follow.insertDateTime, 8) as Time FROM db_applicant applicant join db_followup follow on applicant_id = applfollow_id INNER JOIN db_empl e ON follow.insertBy = e.empl_id WHERE follow.follow_type AND (follow.assign_to = '$_SESSION[empl_id]' OR e.empl_manager = '$_SESSION[empl_id]') AND follow.fol_status = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                                }
                                $query = mysql_query($sql);
                                $row = mysql_num_rows($query);
                           ?>  
                          <i class="fa fa-user text-light-blue"></i> <?php echo $row; ?> candidate interview today
                        </a>
                      </li>
                    </ul>
                  </li>
                  
                </ul>
              </li>              
              
            <?php } ?>
              

            <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
            <?php 
                $empl_id = $_SESSION['empl_id'];
                if($_SESSION['empl_group'] == "9"){
                    $sql = "SELECT * FROM db_notification WHERE noti_to = '$empl_id' AND noti_view_status = '0' AND noti_type = '1'";
                }
                else{
                    $sql = "SELECT * FROM db_notification WHERE noti_to = '$empl_id' AND noti_view_status = '0' AND noti_type = '0'";
                }
                $query = mysql_query($sql);        
                $row = mysql_num_rows($query);
            ?>
                  <?php if($row > 0){?>
                  <span class="label label-success" style="background-color: #00a65a!important;"><?php echo $row?></span>
                  <?php } else {}?>
               </a>
                <ul class="dropdown-menu">
                  <li class="header">You have <?php echo $row?> messages</li>
                  <li>
                     <!--inner menu: contains the actual data-->
                    <ul class="menu">
                        
                        
            <?php 
                if($_SESSION['empl_group'] == '9'){
                    $sql2 = "SELECT n.noti_id, n.noti_to, n.noti_url ,n.noti_desc, n.noti_view_status, n.insertBy, left(n.insertDateTime,10) as date, right(n.insertDateTime, 8) as time FROM db_notification n WHERE noti_to = '$empl_id' AND noti_type = '1' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                }
                else{
                    $sql2 = "SELECT n.noti_id, n.noti_to, n.noti_url ,n.noti_desc, n.noti_view_status, n.insertBy, left(n.insertDateTime,10) as date, right(n.insertDateTime, 8) as time FROM db_notification n WHERE noti_to = '$empl_id' AND noti_type = '0' ORDER BY YEAR(date) DESC, MONTH(date) DESC, DAY(date) DESC, time DESC";
                }
                $query2 = mysql_query($sql2);        
                while($row2 = mysql_fetch_array($query2)){
                    $insertBy = $row2['insertBy'];
                
                    $status = $row2['noti_view_status'];
                    if($status == 1){
                    ?>
                      <li> <!--start message--> 
                    <?php }
                    else{ ?>
                        <li style="background-color: #a7ffd7;"> <!--start message-->                      
                    <?php }
                    ?>      
                          <a href="#" id="<?php echo $row2['noti_url']?>" pid="<?php echo $row2['noti_id']; ?>" class="notice_linking" >
                         <!--<a href="#" pid="<?php echo $row2['noti_id']; ?>" value="<?php echo $row4['applicant_id']; ?>" name="<?php //echo $row2['follow_id']; ?>" class="notice_linking">-->
                          <div class="pull-left">
                                <?php 
                                if($_SESSION['empl_group'] == '9'){
                                    $sql3 = "SELECT applicant_id, applicant_name FROM db_applicant WHERE applicant_id = '$insertBy'";
                                }
                                else{
                                    $sql3 = "SELECT empl_id, empl_name from db_empl where empl_id = '$insertBy'";
                                }
                                $query3 = mysql_query($sql3);        
                                $row3 = mysql_fetch_array($query3);
                                
                                if($_SESSION['empl_group'] == '9'){       
                                ?>
                                    <img src="dist/images/applicant/<?php echo $row3['applicant_id']?>.png" class="img-circle" alt="User Image">
                                <?php } 
                                else if($_SESSION['empl_group'] == '5'){
                                    if($row2['noti_desc'] == "Your leave has been Approved"){
                                    ?>
                                            <img src="dist/images/leave/approved.jpg" class="img-circle" alt="User Image">
                                    <?php }
                                    else{ ?>
                                           <img src="dist/images/leave/reject.jpg" class="img-circle" alt="User Image">
                                    <?php } ?>
                                <?php } 
                                else {
                                ?>
                                    <img src="dist/images/empl/<?php echo $row3['empl_id']?>.png" class="img-circle" alt="User Image">
                                <?php } ?>
                          </div>
                          <h4>
                                <?php 
                                if($_SESSION['empl_group'] == '9'){
                                    echo $row3['applicant_name'];
                                }
                                else{
                                    echo $row3['empl_name'];
                                }
                                ?>
                            <small><i class="fa fa-clock-o"></i><?php echo "  ".$row2['time']."  ".format_date($row2['date']);?></small>
                          </h4>
                          <p><?php echo $row2['noti_desc']?></p>
                        </a>
                      </li> <!--end message-->                       
                    <?php                   
                    }?>
                      
                    </ul>
                  </li>
<!--                  <li class="footer"><a href="#">See All Messages</a></li>-->
                </ul>
              </li>
              
              <!-- Notifications: style can be found in dropdown.less -->
              <!--<li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning">10</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 10 notifications</li>
                  <li>
                     inner menu: contains the actual data 
                    <ul class="menu">
                      <li>
                        <a href="#">
                          <i class="fa fa-users text-aqua"></i> 5 new members joined today
                        </a>
                      </li>

                      <li>
                        <a href="#">
                          <i class="fa fa-users text-red"></i> 5 new members joined
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <i class="fa fa-user text-light-blue"></i> You changed your username
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">View all</a></li>
                </ul>
              </li>-->
              <!-- Tasks: style can be found in dropdown.less -->
              <!--<li class="dropdown tasks-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-flag-o"></i>
                  <span class="label label-danger">9</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 9 tasks</li>
                  <li>
                     inner menu: contains the actual data 
                    <ul class="menu">
                      <li> Task item 
                        <a href="#">
                          <h3>
                            Design some buttons
                            <small class="pull-right">20%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">20% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li> end task item 
                      <li> Task item 
                        <a href="#">
                          <h3>
                            Create a nice theme
                            <small class="pull-right">40%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">40% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li> end task item 
                      <li> Task item 
                        <a href="#">
                          <h3>
                            Some task I need to do
                            <small class="pull-right">60%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">60% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li> end task item 
                      <li> Task item 
                        <a href="#">
                          <h3>
                            Make beautiful transitions
                            <small class="pull-right">80%</small>
                          </h3>
                          <div class="progress xs">
                            <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                              <span class="sr-only">80% Complete</span>
                            </div>
                          </div>
                        </a>
                      </li> end task item 
                    </ul>
                  </li>
                  <li class="footer">
                    <a href="#">View all tasks</a>
                  </li>
                </ul>
              </li>
              -->
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <?php if(file_exists("dist/images/empl/{$_SESSION['empl_id']}.png")){?>
                     <img src="dist/images/empl/<?php echo $_SESSION['empl_id'];?>.png" class="user-image" alt="User Image">
                  <?php }else{?>
                     <img src="dist/img/thumb.gif" class="user-image" alt="User Image">
                    <?php }?>
                  <span class="hidden-xs"><?php echo $_SESSION['empl_name'];?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">

                  <?php if(file_exists("dist/images/empl/{$_SESSION['empl_id']}.png")){?>
                     <img src="dist/images/empl/<?php echo $_SESSION['empl_id'];?>.png" class="img-circle" alt="User Image">
                  <?php }else{?>
                     <img src="dist/img/thumb.gif" class="user-circle" alt="User Image">
                    <?php }?>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="dashboard.php?action=getchangepassword" class="btn btn-default btn-flat">Change Password</a>
                    </div>

                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="login.php?action=logout" title = 'Sign Out'><i class="fa fa-sign-out"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
<?php include_once 'leftsidebar.php';?>