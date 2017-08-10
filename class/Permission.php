<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of permission
 *
 * @author jason
 */
class permission {
    public function permission(){
        
    }
    public function indexPage(){
        global $language,$lang;
      include_once 'class/SelectControl.php';
      $this->select = new SelectControl();
      $this->groupCrtl = $this->select->getGroupSelectCtrl($this->group_id,'N');
?>             
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>User Control Management</title>
    <?php
    include_once 'css.php';
    
    ?>
   <style>
    input[type='checkbox'],input[type='radio']{
    width:17px; height:17px;
    }
    .table-hover tr th{
        text-align:right;
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
            <h1>User Control Management</h1>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header with-border">
                    <form id = 'empl_form' class="form-horizontal" action = 'empl.php?action=create' method = "POST">
                        <div class="form-group">
                              <label for="employee_group" class="col-sm-2 control-label">Employee Group</label>
                              <div class="col-sm-2">
                                   <select class="form-control" id="employee_group" name="employee_group">
                                       <?php echo $this->groupCrtl;?>
                                   </select>
                              </div>
                              <label for="modules" class="col-sm-1 control-label">Module</label>
                              <div class="col-sm-2">
                                   <select class="form-control" id="modules" name="modules">
                                       <?php echo $this->getModules();?>
                                   </select>
                              </div>
                              <div class="col-sm-2">
                              <button type="button" data-loading-text="Loading..." id = 'search_btn' onclick = 'getResult()' class="btn btn-primary" autocomplete="off">Search</button>
                              </div>
                        </div>
                    </form>
                </div>   
                <div class="box-body">
                    <form id = 'form1' >
                        <table style = 'margin-top:20px;float:left' class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th style = 'width:400px;text-align:left' >File Name</th>
                                    <th >Access</th>
                                    <th >Create</th>
                                    <th >Update</th>
                                    <th >Delete</th>
                                    <th >View</th>
                                    <th >Print</th>
                                    <th >Approved</th>
                                    <th >Select All</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id = 'lasttr'></tr>

                            </tbody>
                            <tfoot>
                           <tr>
                                    <td colspan = '10' align = 'right'><button id = 'savebtn' type = 'button' onclick = 'saveResult()' class="btn btn-primary" autocomplete="off" disabled>Save</button></td>
                                </tr>
                            </tfoot>
                        </table> 
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
      $(function () {

      });
    
       function checkAll(){
            $('.parent_check').click(function(){
                var hid = $(this).attr('hid');
                if($(this).is(':checked')){
                    $('.children_check_'+hid).prop('checked',true);
                }else{
                    $('.children_check_'+hid).prop('checked',false);
                }
            });
       }
       function getResult(){
          $('#search_btn').button('loading');  
          var data ="action=getResult&employee_group="+$('#employee_group').val()+"&modules="+$('#modules').val()+"&modules_text="+$('#modules option:selected').text();              
            $.ajax({
                url:'permission.php',
                type:'POST',
                data:data,
                cache:false,
                success:function(xml){
                jsonObj = eval('('+ xml +')');
                    $('#search_btn').button('reset');
                    $('.class_tr').remove();
                    $('#lasttr').before(jsonObj.html);
                    checkAll();
                    $('#savebtn').attr('disabled',false);
                }   
            });      
       }
       function saveResult(){
          $('#savebtn').button('loading');  
          var data ="action=saveResult&employee_group="+$('#employee_group').val()+"&modules="+$('#modules').val()+"&"+$('#form1').serialize();              
            $.ajax({
                url:'permission.php',
                type:'POST',
                data:data,
                cache:false,
                success:function(xml){
                    $('#savebtn').button('reset');
                    alert("Update Success");
                }   
          }); 
       }
    </script>
  </body>
</html>
      <?php

       
    }
    public function getResult(){
      $sql = "SELECT *
              FROM db_menu
              WHERE menu_parent = '$this->modules' AND menu_status = '1'";
      $query = mysql_query($sql);
      $html = "";
      $i = 0;
      $align = " text-align:right;";      
      while($row = mysql_fetch_array($query)){
          
          $sql1 = "SELECT menuprm_prmcode
                   FROM db_menuprm
                   WHERE menuprm_group_id = '$this->employee_group' AND menuprm_menu_id = '{$row['menu_id']}'";

          $query1 = mysql_query($sql1);
          $a1 = "";
          $a2 = "";
          $a3 = "";
          $a4 = "";
          $a5 = "";
          $a6 = "";
          $a7 = "";

          while($row1 = mysql_fetch_array($query1)){
                if($row1['menuprm_prmcode'] == 'access'){
                    $a1 = " CHECKED = 'CHECKED'";
                }
                if($row1['menuprm_prmcode'] == 'create'){
                    $a2 = " CHECKED = 'CHECKED'";
                }
                if($row1['menuprm_prmcode'] == 'update'){
                    $a3 = " CHECKED = 'CHECKED'";
                }
                if($row1['menuprm_prmcode'] == 'delete'){
                    $a4 = " CHECKED = 'CHECKED'";
                }
                if($row1['menuprm_prmcode'] == 'view'){
                    $a5 = " CHECKED = 'CHECKED'";
                }
                if($row1['menuprm_prmcode'] == 'print'){
                    $a6 = " CHECKED = 'CHECKED'";
                }
                if($row1['menuprm_prmcode'] == 'approved'){
                    $a7 = " CHECKED = 'CHECKED'";
                }

          }

          $html .= <<<EOF
                <tr class = 'class_tr' >
                    <td>{$row['menu_name']}</td>
                    <td style = '$align'>
                        <input type="checkbox" $a1 name = 'check[$i][]' value="access" class = 'children_check_$i' />
                    </td>
                    <td style = '$align'>
                        <input type="checkbox" $a2 name = 'check[$i][]' value="create" class = 'children_check_$i' />
                    </td>
                    <td style = '$align'>
                        <input type="checkbox" $a3 name = 'check[$i][]' value="update" class = 'children_check_$i' />
                    </td>
                    <td style = '$align'>
                        <input type="checkbox" $a4 name = 'check[$i][]' value="delete" class = 'children_check_$i' />
                    </td>
                    <td style = '$align'>
                        <input type="checkbox" $a5 name = 'check[$i][]' value="view" class = 'children_check_$i' />
                    </td>  
                    <td style = '$align'>
                        <input type="checkbox" $a6 name = 'check[$i][]' value="print" class = 'children_check_$i' />
                    </td>     
                    <td style = '$align'>
                        <input type="checkbox" $a7 name = 'check[$i][]' value="approved" class = 'children_check_$i' />
                    </td>             
                    <td style = '$align'>
                        <input type="checkbox"  hid = '$i' class = 'parent_check'>
                        <input type = 'hidden' name = 'line_modules_$i' value = '{$row['menu_id']}'/>    
                    </td>
                </tr>         
EOF;
                        
       $i++;
      }
      $sql5 = "SELECT menuprm_prmcode
                   FROM db_menuprm
                   WHERE menuprm_group_id = '$this->employee_group' AND menuprm_menu_id = '$this->modules'";

      $query5 = mysql_query($sql5);
      $a1 = "";
      $a2 = "";
      $a3 = "";
      $a4 = "";
      $a5 = "";
      $a6 = "";
      $a7 = "";
     while($row5 = mysql_fetch_array($query5)){

        if($row5['menuprm_prmcode'] == 'access'){
            $a1 = " CHECKED = 'CHECKED'";
        }
        if($row5['menuprm_prmcode'] == 'create'){
            $a2 = " CHECKED = 'CHECKED'";
        }
        if($row5['menuprm_prmcode'] == 'update'){
            $a3 = " CHECKED = 'CHECKED'";
        }
        if($row5['menuprm_prmcode'] == 'delete'){
            $a4 = " CHECKED = 'CHECKED'";
        }
        if($row5['menuprm_prmcode'] == 'view'){
            $a5 = " CHECKED = 'CHECKED'";
        }
        if($row5['menuprm_prmcode'] == 'print'){
            $a6 = " CHECKED = 'CHECKED'";
        }
        if($row5['menuprm_prmcode'] == 'approved'){
            $a7 = " CHECKED = 'CHECKED'";
        }
     }
      $html =  <<<EOF
              <tr class = 'class_tr' >
                <td>$this->modules_text - Modules</td>
                    <td style = '$align'>
                        <input type="checkbox" $a1 name = 'check[$i][]' value="access" class = 'children_check_$i' />
                    </td>
                    <td style = '$align'>
                        <input type="checkbox" $a2 name = 'check[$i][]' value="create" class = 'children_check_$i' />
                    </td>
                    <td style = '$align'>
                        <input type="checkbox" $a3 name = 'check[$i][]' value="update" class = 'children_check_$i' />
                    </td>
                    <td style = '$align'>
                        <input type="checkbox" $a4 name = 'check[$i][]' value="delete" class = 'children_check_$i' />
                    </td>
                    <td style = '$align'>
                        <input type="checkbox" $a5 name = 'check[$i][]' value="view" class = 'children_check_$i' />
                    </td>
                    <td style = '$align'>
                        <input type="checkbox" $a6 name = 'check[$i][]' value="print" class = 'children_check_$i' />
                    </td>     
                    <td style = '$align'>
                        <input type="checkbox" $a7 name = 'check[$i][]' value="approved" class = 'children_check_$i' />
                    </td>             
                    <td style = '$align'>
                        <input type="checkbox"  hid = '$i' class = 'parent_check'>
                        <input type = 'hidden' name = 'line_modules_$i' value = '{$this->modules}'/>    
                    </td></tr>" . $html;
EOF;
                        
      echo json_encode(array('html'=>$html));
    }
    public function saveResult(){
      $sql = "SELECT COUNT(*) as total FROM db_menu WHERE menu_parent = '$this->modules' AND menu_status = '1' ";
      $query = mysql_query($sql);
      $total = 0;
      if($row = mysql_fetch_array($query)){
          $total = $row['total'];
      }else{
          $total = 0;
      }
      $total = $total + 1;
      for($i=0;$i<$total;$i++){
          $check = $_POST["check"];     
          $line_modules = $_REQUEST["line_modules_$i"];
          $this->clearOldData($line_modules);

          for($p=0;$p<sizeof($check[$i]);$p++){
            $table_field = array('menuprm_group_id','menuprm_menu_id','menuprm_prmcode');
            $table_value = array($this->employee_group,$line_modules,$check[$i][$p]);
            $remark = "Insert Permission code.";
            $this->save->SaveData($table_field,$table_value,'db_menuprm','menuprm_id',$remark);
     
          }    
      }
    }
    public function clearOldData($menu_id){
        $this->save->DeleteData("db_menuprm"," WHERE menuprm_menu_id = '$menu_id' AND menuprm_group_id = '$this->employee_group'","Delete Menu Permission .");
    }
    public function getModules(){
      $sql = "SELECT * FROM db_menu WHERE menu_parent = 0 AND menu_status = 1 ORDER BY menu_seqno";
      $query = mysql_query($sql);
      $html = "";
      while($row = mysql_fetch_array($query)){
          $html .= "<option value = '{$row['menu_id']}' >{$row['menu_name']}</option>";
      }
      return $html;
    }   
 
}