<?php


?>
<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
            <?php if(file_exists("dist/images/empl/{$_SESSION['empl_id']}.png")){?>
                     <img src="dist/images/empl/<?php echo $_SESSION['empl_id'];?>.png" class="img-circle" alt="User Image">
            <?php }else{?>
                     <img src="dist/img/thumb.gif" class="img-circle" alt="User Image">
            <?php }?>
          
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION['empl_name'];?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
          </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
       <!--<li class="header">MAIN NAVIGATION</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span></i>
          </a>

        </li>
        <li class="treeview <?php if(($sub == 'ronlinecasino.php') || ($sub == 'onlinecasino.php')){ echo 'Active';}?>">
            <a href="empl.php">
                <i class="fa fa-gamepad"></i>
                <span>Employee</span>
            </a>
        </li>
        <li class="treeview <?php if(($sub == 'rluckydraw.php') || ($sub == 'luckydraw.php')){ echo 'Active';}?>">
          <a href="#">
            <i class="fa fa-chrome"></i> <span>Leave</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class = '<?php if($sub == 'rluckydraw.php'){ echo 'Active';}?>'><a href="rluckydraw.php"><i class="fa fa-circle-o"></i>Request Lucky Draw</a></li>
            <li class = '<?php if($sub == 'luckydraw.php'){ echo 'Active';}?>'><a href="luckydraw.php"><i class="fa fa-circle-o"></i>Lucky Draw </a></li>
          </ul>
        </li>
        <li class="treeview <?php if(($sub == 'rblacklist.php') || ($sub == 'blacklist.php')){ echo 'Active';}?>">
          <a href="#">
            <i class="fa fa-user-times"></i>
            <span>Claims</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class = '<?php if($sub == 'rblacklist.php'){ echo 'Active';}?>'><a href="rblacklist.php"><i class="fa fa-circle-o"></i>Request Black List</a></li>
            <li class = '<?php if($sub == 'blacklist.php'){ echo 'Active';}?>'><a href="blacklist.php"><i class="fa fa-circle-o"></i>Black List </a></li>
          </ul>
        </li>
        <li class="treeview <?php if($sub == 'provider.php'){ echo 'Active';}?>">
          <a href="provider.php">
            <i class="fa fa-user-plus"></i>
            <span>Payroll</span>
          </a>
        </li>
        <li class="treeview <?php if(($sub == 'radv.php') || ($sub == 'adv.php')){ echo 'Active';}?>">
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>Report</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class = '<?php if($sub == 'radv.php'){ echo 'Active';}?>'><a href="radv.php"><i class="fa fa-circle-o"></i>Request Advertisement</a></li>
            <li class = '<?php if($sub == 'adv.php'){ echo 'Active';}?>'><a href="adv.php"><i class="fa fa-circle-o"></i>Advertisement </a></li>
          </ul>
        </li>
        
        <li class="treeview <?php if(($sub == 'slider.php') || ($sub == 'aboutus.php')){ echo 'Active';}?>">
          <a href="#">
            <i class="fa fa-map-signs"></i>
            <span>Setup</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li class = '<?php if($sub == 'permission.php'){ echo 'Active';}?>'><a href="permission.php"><i class="fa fa-circle-o"></i> User Control</a></li> 
            <li class = '<?php if($sub == 'leavetype.php'){ echo 'Active';}?>'><a href="leavetype.php"><i class="fa fa-circle-o"></i> Leave Type</a></li>
            <li class = '<?php if($sub == 'expenses.php'){ echo 'Active';}?>'><a href="expenses.php"><i class="fa fa-circle-o"></i> Expenses</a></li>
            <li class = '<?php if($sub == 'department.php'){ echo 'Active';}?>'><a href="department.php"><i class="fa fa-circle-o"></i> Department</a></li>
          </ul>
        </li>-->
        
        
        <?php 
        $sql = "SELECT *
                FROM db_menu 
                WHERE menu_parent = 0 AND menu_status = 1 ORDER BY menu_seqno";
        $query = mysql_query($sql);
        $i = 0;
        $_SESSION['m'][$_SESSION['empl_id']] = 0;
        $file_server_name = explode('/',$_SERVER["PHP_SELF"]);
        while($row = mysql_fetch_array($query)){
            if(!getWindowPermission($row['menu_id'],'access')){
                continue;
            }
            $wherestring = " menu_parent = '{$row['menu_id']}' AND menu_path = '{$file_server_name[2]}'";
            if(($file_server_name[2] == $row['menu_path']) || (checkMenuChildren($wherestring) > 0)){
                $_SESSION['m'][$_SESSION['empl_id']] = $row['menu_id'];
                $active = " Active"; 
            }else{
                $active = ""; 
            }
?>
        <li class="treeview <?php echo $active;?>">
          <a href="<?php if($row['menu_path'] == ""){ echo '#'; }else{echo $row['menu_path'];}?>">
            <i class="<?php echo $row['menu_icon'];?>"></i>
            <span><?php echo $row['menu_name'];?></span>
             <?php if($row['menu_path'] == ""){?>  
            <i class="fa fa-angle-left pull-right"></i>
             <?php }?>
          </a> 
          <?php if($row['menu_path'] == ""){?>  
           
          <ul class="treeview-menu">
              <?php 
                $sql1 = "SELECT * FROM db_menu WHERE menu_parent = '{$row['menu_id']}' AND menu_status = 1 ORDER BY menu_seqno";
                $query1 = mysql_query($sql1);
                while($row1 = mysql_fetch_array($query1)){
                    if(!getWindowPermission($row1['menu_id'],'access')){
                        continue;
                    }
                    if($file_server_name[2] == $row1['menu_path']){
                        $_SESSION['m'][$_SESSION['empl_id']] = $row1['menu_id'];
                    }
                    if($file_server_name[2] == $row1['menu_path']){
                        $_SESSION['m'][$_SESSION['empl_id']] = $row1['menu_id'];
                        $sub_active = " Active"; 
                    }else{
                        $sub_active = ""; 
                    }
              ?>
                 <li class = '<?php echo $sub_active;?>' ><a href="<?php echo $row1['menu_path'];?>"><i class="<?php echo $row1['menu_icon'];?>"></i><?php echo $row1['menu_name']; ?></a></li>
              <?php  
                }
             ?>      
          </ul>
          <?php }?>
        </li>
<?php        
        }
        ?>
      </ul>
    </section>
        <!-- /.sidebar -->
</aside>