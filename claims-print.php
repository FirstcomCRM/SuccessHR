<?php

    include_once 'connect.php';
    include_once 'config.php';
    include_once 'include_function.php';
    include_once 'class/Claims.php'; 
    $o = new Claims();
    
    $claims_id = escape($_REQUEST['claims_id']);
    if(!$o->fetchClaimsDetail(" AND claims_id = '$claims_id'","","",1)){
        rediectUrl("payroll.php",getSystemMsg(0,'Fetch Data fail'));
        exit();
    }
    if(!in_array($_SESSION['empl_group'],$master_group)){
        if($o->claims_empl_id != $_SESSION['empl_id']){
            permissionLog();
            rediectUrl("dashboard.php",getSystemMsg(0,'Permission Denied'));
            exit();
        }
    }
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Claims Print</title>
    <?php
     include_once 'css.php';
    ?>
<style>
.table > tbody > tr > td{
padding:3px !important;
}
</style>
  </head>
  <!--onload="window.print();"-->
  <body onload="window.print();">
    <div class="wrapper">
      <!-- Main content -->
      <section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">
              Zal Interiors Pte Ltd
              <small class="pull-right">Date: <?php echo format_date(system_date);?></small>
            </h2>
          </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            
          <div class="col-sm-4 invoice-col" style = 'font-size:15px;' >
            <?php
            
            $sql = "SELECT * FROM db_claims WHERE claims_id > 0 AND claims_id = '$claims_id'";
            $query = mysql_query($sql);
            if($row = mysql_fetch_array($query)){
            ?>
                <b>Employee Code : <?php echo getDataCodeBySql("empl_code","db_empl"," WHERE empl_id = '{$row['claims_empl_id']}'", $orderby);?></b><br>
                <b>Employee Name : </b> <?php echo getDataCodeBySql("empl_name","db_empl"," WHERE empl_id = '{$row['claims_empl_id']}'", $orderby);?><br>
                <b>Amount : $<?php echo num_format($row['claims_amount']);?></b><br>
                <b>Remarks : </b> <?php echo nl2br($row['claims_remark']);?>
            <?php }?>
          </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
          <div class="col-xs-12 ">
            <table class="table table-striped" style = 'font-size:14px;' >
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Date</th>
                  <th>Type of Claims</th>
                  <th>Description</th>
                  <th style = 'width:80px;' >Amt. Before GST</th>
                  <th style = 'width:80px;'>GST</th>
                  <th style = 'width:80px;'>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                  <?php
                    
                    $sql = "SELECT cl.*,img.image_type,img.image as file_name,ct.claimstype_code
                            FROM db_claimsline cl
                            LEFT JOIN db_image img ON img.ref_id = cl.claimsline_id
                            LEFt JOIN db_claimstype ct ON ct.claimstype_id = cl.claimsline_type
                            WHERE claimsline_id > 0 AND claimsline_claims_id > 0 AND claimsline_claims_id = '$claims_id' ORDER BY claimsline_seqno";
                    $query = mysql_query($sql);
                    $i = 1;
                    while($row = mysql_fetch_array($query)){
                    ?>
                        <tr>
                          <td><?php echo $i;?></td>
                          <td><?php echo format_date($row['claimsline_date']);?></td>
                          <td><?php echo $row['claimstype_code'];?></td>
                          <td><?php echo nl2br($row['claimsline_desc']);?></td>
                          <td><?php echo num_format($row['claimsline_amount']);?></td>
                          <td><?php echo num_format($row['claimsline_amount_gst']);?></td>
                          <td><?php echo num_format($row['claimsline_amount'] + $row['claimsline_amount_gst']);?></td>
                        </tr>
                    <?php
                    $i++;
                    }
                  ?>
              </tbody>
            </table>
          </div><!-- /.col -->
        </div><!-- /.row -->

        
      </section><!-- /.content -->
    </div><!-- ./wrapper -->

    <!-- AdminLTE App -->
    <script src="../../dist/js/app.min.js"></script>
  </body>
</html>
