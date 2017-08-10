<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SavehandlerApi
 *
 * @author jason
 */
class SavehandlerApi {
    public function SavehandlerApi(){
    }
    public function SaveData($table_field,$table_value,$table,$table_id,$remark){
        global $sql_debug;
        if($table == ""){
            return false;
        }
       $date_time = date('Y-m-d H:i:s');
       
       for($i=0;$i<sizeof($table_field);$i++){
            $f .= $table_field[$i] . ",";
            $v .= "'" . $table_value[$i] . "',";
       }
       $f = $f . 'insertBy,insertDateTime,updateBy,updateDateTime';
       $v = $v . "'{$_SESSION['empl_id']}','$date_time','{$_SESSION['empl_id']}','$date_time'";
       $sql_insert = "INSERT INTO $table ($f) VALUES ($v) ";
       if($sql_debug){
           echo $sql_insert;die;
       }
       if(mysql_query($sql_insert)){
           $this->lastInsert_id = $this->getLastInsertId($table,$table_id,$date_time);
           $this->saveRecordInfo($table_field,$table_value,$table,$remark,$date_time,'Insert',$this->lastInsert_id);
           return true;
       }else{
           return false;
       }
    }
    public function UpdateData($table_field,$table_value,$table,$table_id,$remark,$update_id,$wherestring){
        global $sql_debug;
        if($table == ""){
            return false;
        }
       $date_time = date('Y-m-d H:i:s');
       
       for($i=0;$i<sizeof($table_field);$i++){
            if($table_field[$i] == ""){
                continue;
            }
            $v .= $table_field[$i] . " = '{$table_value[$i]}', ";
       }
       $v = $v . " updateBy = '{$_SESSION['empl_id']}', updateDateTime = '$date_time'";
       $sql_insert = "UPDATE $table SET $v WHERE $table_id = '$update_id' $wherestring";
       if($sql_debug){
           echo $sql_insert;die;
       }
       if(mysql_query($sql_insert)){
           $lastInsert_id = $update_id;
           $this->saveRecordInfo($table_field,$table_value,$table,$remark,$date_time,'Update',$lastInsert_id);
           return true;
       }else{
           return false;
       }
    }
    public function DeleteData($table,$wherestring,$remark){
        global $sql_debug;
        if($table == ""){
            return false;
        }
       $date_time = date('Y-m-d H:i:s');
       $sql_delete = "DELETE FROM $table $wherestring ";
       if($sql_debug){
           echo $sql_delete;die;
       }
       if(mysql_query($sql_delete)){
           $this->saveRecordInfo(null,null,$table,$remark,$date_time,'Delete');
           return true;
       }else{
           return false;
       }
    }
    public function saveRecordInfo($table_field,$table_value,$table,$remark,$date_time,$status,$lastInsert_id){
        $description = '';
        for($i=0;$i<sizeof($table_field);$i++){
                 $description .= $table_field[$i] . " => " . $table_value[$i] . "<br>";
        }
        if($description == ""){
            $description = $remark;
        }
            $sql = "INSERT INTO db_info
                   (info_table,info_table_id,info_action,info_desc,info_remark,insertBy,insertDateTime)
                   Value
                   ('$table','$lastInsert_id','$status','$description','$remark','{$_SESSION['empl_id']}','$date_time')";
            mysql_query($sql);
    }
    public function getLastInsertId($table,$table_id,$date_time){
        $sql = "SELECT MAX($table_id) as last_id FROM $table WHERE insertDateTime = '$date_time'";
        $query = mysql_query($sql);
        if($row = mysql_fetch_array($query)){
            $last_id = $row['last_id'];
        }else{
            $last_id = 0;
        }
        return $last_id;
    }
}
