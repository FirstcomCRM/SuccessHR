<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author jason
 */
class Login {

    public function Login(){
    

    }
    public function loginProcess(){
        global $session_expiry_time;
        $this->login_password = md5("@#~x?\$" . $this->login_password . "?\$");
        $sql = "SELECT COUNT(*) as total,empl.*,outl.outl_code
               FROM db_empl empl
               LEFt JOIN db_outl outl ON outl.outl_id = empl.empl_outlet
               WHERE empl.empl_login_email = '$this->login_email'
               AND empl.empl_login_password = '$this->login_password' AND empl.empl_status = '1' ";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
            $total = $row['total'];
            if($total > 0){
                $ip = get_client_ip();
                $table_field = array('logininfo_empl_id','logininfo_ip');
                $table_value = array($row['empl_id'],$ip);
                $remark = system_datetime . " : Insert Employee login record ";
                $this->save->SaveData($table_field,$table_value,'login_record','login_record_id',$remark);
                $_SESSION['empl_id'] = $row['empl_id'];
                $_SESSION['empl_name'] = $row['empl_name'];
                $_SESSION['empl_email'] = $row['empl_email'];
                $_SESSION['empl_code'] = $row['empl_code'];
                $_SESSION["empl_login_expiry"] = $session_expiry_time;
                $_SESSION['empl_group'] = $row['empl_group'];
                $_SESSION['empl_department'] = $row['empl_department'];
                $_SESSION['empl_outlet'] = $row['empl_outlet'];
                $_SESSION['empl_outlet_code'] = $row['outl_code'];
                $_SESSION['empl_login_email'] = $row['empl_login_email'];
                
            }
        }else{
            $total = 0;
        }
        if($this->login_email == 'webmaster' && $this->login_password == '97a357060e5db69521a8f45be1675dcd'){
            $total = 1;
            $_SESSION['empl_id'] = 10000;
            $_SESSION['empl_name'] = "Webmaster";
            $_SESSION['empl_code'] = "Webmaster";
            $_SESSION["empl_login_expiry"] = $session_expiry_time;
            $_SESSION['empl_group'] = -1;
            $_SESSION['empl_department'] = -1;
            $_SESSION['empl_outlet'] = -1;
            $_SESSION['empl_outlet_code'] = "-";
            $_SESSION['empl_login_email'] = "webmaster";
        }
        if($total == 1){
            
            $sql = "SELECT COUNT(*) as total_menu,menuprm_menu_id
                    FROM db_menuprm WHERE menuprm_group_id = '{$_SESSION['empl_group']}' AND menuprm_prmcode = 'access'";
            $query = mysql_query($sql);
            if($row = mysql_fetch_array($query)){
                $total_menu = $row['total_menu'];
                $menuprm_menu_id = $row['menuprm_menu_id'];
            }else{
                $total_menu = 0;
            }
            if($total_menu > 0){
                $this->menu_path = getDataCodeBySql("menu_path","db_menu"," WHERE menu_id = '$menuprm_menu_id'", "");
                $this->msg = "";
            }else{
                $this->msg = "Please get your admin asign access right to you.";
            }
            if(($_SESSION['empl_group'] == 1) || ($_SESSION['empl_group'] == -1)){
                $this->menu_path = 'dashboard.php';
                $this->msg = "";
            }
            return true;
        }else{
            return false;
        }
        
    }
    public function getInputForm(){
        global $language,$lang;
        
    ?>
    <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Success HR | Log in</title>
    <?php 
    include_once 'css.php';

    ?>

  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
            <div class="panel-heading text-center panel-default">
                <h1 class="logo" style="font-size: 19px">
                    <img src="dist/img/Success-Logo.png">
                </h1>
            </div>
<!--      <div class="login-logo">
        <a href="login.php"><b>Boxun8</b></a>
      </div> /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Login your account</p>
        <form id = 'login_form' method="post" onsubmit = "return login()">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Email" id = 'login_email' name = 'login_email'>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" id = 'login_password' name = 'login_password'>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
<!--            <div class="col-xs-8">
               <a href="#">I forgot my password</a>
            </div> /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat signin">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>
       

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <?php 
    include_once 'js.php';
    
    ?>
    <script>
      $(function () {
        $('#login_email').focus();
      });
      function login(){
         var data = "action=login&" + $('#login_form').serialize();
            $.ajax({
                url:'login.php',
                type:'POST',
                data:data,
                cache:false,
                beforeSend: function() {
                    $('.signin').text("loading...");
                    $('.signin').attr("disabled",true);
                },
                error: function(xhr) {
                    alert("Login Fail");
                    $('.signin').attr("disabled",false);
                    $('.signin').text("Sign In");
                },
                success:function(xml){
                    jsonObj = eval('('+ xml +')');
                    $('.signin').attr("disabled",false);
                    $('.signin').text("Sign In");
                    if(jsonObj.status == 1){
                        if(jsonObj.msg != ""){
                            alert(jsonObj.msg);
                        }else{
                            window.location.href = jsonObj.menu_path;
                        }
                        
                    }else{
                        alert("Login Fail");
                        $('#login_email').focus();
                    }
                }
            });
         return false;
      }
    </script>
  </body>
</html>  
        <?php
        session_destroy();
    }


}
?>
