<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AutoComplete
 *
 * @author jason
 */
class AutoComplete {
    public function AutoComplete(){

    }
    public function getItemAutoComplete($term){
        
        $bpartner_id = $_REQUEST['bpartner_id'];
        $category_id = $_REQUEST['category_id'];
        $warehouse_id = $_REQUEST['warehouse_id'];
        if($category_id > 0){
            $wherestring = " AND i.product_category = '$category_id'"; 
        }
        if($warehouse_id > 0){
            $wherestring = " AND i.product_outlet = '$warehouse_id'";
        }
        
        $sql = "SELECT i.*
                FROM db_product i 
                WHERE i.product_code LIKE '%$term%' $wherestring AND product_status = 1";
        $query = mysql_query($sql);
        $type = $_REQUEST['type'];
        while($row = mysql_fetch_array($query)){
            $results[] = array('id'=>$row['product_id'],'value'=>$row['product_code'],'text'=>$row['product_code']);
        }
        if(sizeof($results) <=0){
         $results[] = array('id'=>0,'value'=>"",'text'=>"No Record Found.");   
        }
        
        echo json_encode(array("items"=>$results));
    }
    public function getUomAutoComplete($term){
        
        $sql = "SELECT * FROM uom WHERE uom_code LIKE '%$term%'"; 
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $results[] = array('label'=>$row['uom_code'],"label_id"=>$row['uom_id']);
        }
        echo json_encode($results);
    }
    public function getProcessAutoComplete($term){
        
        $sql = "SELECT p.process_code,p.process_name,p.process_id,l.location_code as productionloc_code 
                FROM process p
                LEFT JOIN location l ON l.location_id = p.productionlocation_id
                WHERE p.process_code LIKE '%$term%'"; 
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $results[] = array('label'=>$row['process_code'],"label_name"=>$row['process_name'],"label_id"=>$row['process_id'],"label_productionloc_code"=>$row['productionloc_code']);
        }
        echo json_encode($results);
    }
    public function getLocationAutoComplete($term){
        $sql = "SELECT location_id,location_code
                FROM location
                WHERE location_code LIKE '%$term%'"; 
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $results[] = array('label'=>$row['location_code'],"label_name"=>$row['location_code'],"label_id"=>$row['location_id']);
        }
        echo json_encode($results);
    }
    public function getBpartnerAutoComplete($term){
        $group = $_REQUEST['group'];
        if($group > 0){
            $wherestring = " AND group_id = '$group'";
        }
        $sql = "SELECT b.bpartner_id,b.bpartner_code,b.bpartner_name,b.street,b.postcode,r.region_name,c.country_name
                FROM bpartner b
                LEFT JOIN country c ON c.country_id = b.country_id
                LEFT JOIN region r ON r.region_id = b.region_id
                WHERE bpartner_code LIKE '%$term%' $wherestring"; 
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $address = $row['street'] . "\n" . $row['postcode'] ." " .  $row['region_name'] . "\n" . $row['country_name'];
            $results[] = array('label'=>$row['bpartner_code'],"label_name"=>$row['bpartner_name'],"label_id"=>$row['bpartner_id'],"label_address"=>$address);
        }
        echo json_encode($results);
    }
    public function getEmployeeAutoComplete($term){
        $type = $_REQUEST['type'];
        if($type != ""){
            $wherestring = " AND group_id = '$type'";
        }
        $sql = "SELECT employee_id,employee_name
                FROM employee
                WHERE employee_name LIKE '%$term%' $wherestring"; 
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $results[] = array('label'=>$row['employee_name'],"label_name"=>$row['employee_name'],"label_id"=>$row['employee_id']);
        }
        echo json_encode($results);
    }
}

?>
