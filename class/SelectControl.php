<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SelectControl
 *
 * @author jason
 */
class SelectControl {
    public function SelectControl(){
     
    }
    public function getNationalitySelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT nationality_id,nationality_code from db_nationality WHERE (nationality_id = '$pid' or nationality_id >0) and nationality_status = 1 $wherestring
                ORDER BY nationality_seqno,nationality_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['nationality_id'];
            $code = $row['nationality_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getBankSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT bank_id,bank_code from db_bank WHERE (bank_id = '$pid' or bank_id >0) and bank_status = 1 $wherestring
                ORDER BY bank_seqno,bank_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['bank_id'];
            $code = $row['bank_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getEmplPassSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT emplpass_id,emplpass_code from db_emplpass WHERE (emplpass_id = '$pid' or emplpass_id >0) and emplpass_status = 1 $wherestring
                ORDER BY emplpass_seqno,emplpass_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['emplpass_id'];
            $code = $row['emplpass_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getLeaveTypeSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT leavetype_id,leavetype_code from db_leavetype INNER JOIN db_emplleave ON leavetype_id = emplleave_leavetype WHERE (leavetype_id = '$pid' or leavetype_id >0) and leavetype_status = 1 and emplleave_empl = $_SESSION[empl_id] and emplleave_disabled = '0' $wherestring
                ORDER BY leavetype_seqno,leavetype_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['leavetype_id'];
            $code = $row['leavetype_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getClaimsTypeSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT claimstype_id,claimstype_code from db_claimstype WHERE (claimstype_id = '$pid' or claimstype_id >0) and claimstype_status = 1 $wherestring
                ORDER BY claimstype_seqno,claimstype_code ASC";
        if($shownull =="Y"){
            $selectctrl .='<option value = "" SELECTED="SELECTED">Select One</option>';
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['claimstype_id'];
            $code = $row['claimstype_code'];
            if($id == $pid){
                $selected = 'SELECTED = "SELECTED"';
            }else{
                $selected = "";
            }
            $selectctrl .='<option value = "' . $id . '" ' . $selected . '>' . $code . '</option>'; 
        }
        return $selectctrl;
    }
    public function getEmployeeSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT empl_id,CONCAT(empl_code,' - ',empl_name) as empl_name from db_empl WHERE (empl_id = '$pid' or empl_id >0) and empl_status = 1 and empl_client = '0' and (empl_group = '4' or empl_group = '8') $wherestring
                ORDER BY empl_seqno,empl_name ASC"; 

        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['empl_id'];
            $code = $row['empl_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    //get client supervise applicant 
    public function getClientApplicantSelectCtrl($pid,$id,$shownull="Y",$wherestring=''){
        $sql = "SELECT applicant_id, applicant_name from db_applicant WHERE applicant_id = '$pid' or (applicant_leave_approved1 = '$id' or applicant_leave_approved2 = '$id' or applicant_leave_approved3 = '$id') and applicant_status = 1 ORDER BY applicant_name ASC"; 

        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['applicant_id'];
            $code = $row['applicant_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }    
    public function getAdditionalTypeSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT additionaltype_id,CONCAT(additionaltype_code,' - ',additionaltype_code) as additionaltype_code from db_additionaltype WHERE (additionaltype_id = '$pid' or additionaltype_id >0) and additionaltype_status = 1 $wherestring
                ORDER BY additionaltype_seqno,additionaltype_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['additionaltype_id'];
            $code = $row['additionaltype_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getGroupSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT group_id,group_code from db_group WHERE (group_id = '$pid' or group_id >0) and group_status = 1 $wherestring
                ORDER BY group_seqno,group_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['group_id'];
            $code = $row['group_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }

        return $selectctrl;
    }
    public function getManagerSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT empl_id, empl_name FROM db_empl where empl_group = '4'";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['empl_id'];
            $code = $row['empl_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }

        return $selectctrl;
    }
    public function getMenuSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT * from db_menu WHERE (menu_id = '$pid' or menu_id >0) and menu_status = 1 $wherestring
                ORDER BY menu_seqno,menu_name ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['menu_id'];
            $code = $row['menu_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }

        return $selectctrl;
    }
    public function getOutletSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT outl_id,outl_code from db_outl WHERE (outl_id = '$pid' or outl_id >0) and outl_status = 1 $wherestring
                ORDER BY outl_seqno,outl_code ASC";

        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['outl_id'];
            $code = $row['outl_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getCurrencySelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT currency_id,currency_code from db_currency WHERE (currency_id = '$pid' or currency_id >0) and currency_status = 1 $wherestring
                ORDER BY currency_seqno,currency_code ASC";

        if($shownull =="Y"){
            $selectctrl .='<option value = "" SELECTED="SELECTED">Select One</option>';
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['currency_id'];
            $code = $row['currency_code'];
            if($id == $pid){
                $selected = 'SELECTED = "SELECTED"';
            }else{
                $selected = "";
            }
            $selectctrl .='<option value = "' . $id . '"' . $selected . ">$code</option>"; 
        }
        return $selectctrl;
    }
    public function getAccountSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT cacc_id,CONCAT(cacc_code,' - ',cacc_name) as cacc_code from db_cacc WHERE (cacc_id = '$pid' or cacc_id >0) and cacc_status = 1 $wherestring
                ORDER BY cacc_seqno,cacc_code ASC";

        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['cacc_id'];
            $code = $row['cacc_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getIndustrySelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT industry_id,industry_code from db_industry WHERE (industry_id = '$pid' or industry_id >0) and industry_status = 1 $wherestring
                ORDER BY industry_seqno,industry_code ASC";

        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['industry_id'];
            $code = $row['industry_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getContactSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT contact_id,contact_name from db_contact WHERE (contact_id = '$pid' or contact_id >0) and contact_status = 1 $wherestring
                ORDER BY contact_seqno,contact_name ASC";

        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['contact_id'];
            $code = $row['contact_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getUomSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT uom_id,uom_code from db_uom WHERE (uom_id = '$pid' or uom_id >0) and uom_status = 1 $wherestring
                ORDER BY uom_seqno,uom_code ASC";

        if($shownull =="Y"){
            $selectctrl .='<option value = "" SELECTED="SELECTED">Select One</option>';
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['uom_id'];
            $code = $row['uom_code'];
            if($id == $pid){
                $selected = 'SELECTED = "SELECTED"';
            }else{
                $selected = "";
            }
            $selectctrl .='<option value = "' . $id . '"' . $selected . ">$code</option>"; 
        }
        return $selectctrl;
    }
    public function getMachineTypeSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT machinetype_id,machinetype_code from db_machinetype WHERE (machinetype_id = '$pid' or machinetype_id >0) and machinetype_status = 1 $wherestring
                ORDER BY machinetype_seqno,machinetype_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['machinetype_id'];
            $code = $row['machinetype_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getManufacturerSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT manufacturer_id,manufacturer_code from db_manufacturer WHERE (manufacturer_id = '$pid' or manufacturer_id >0) and manufacturer_status = 1 $wherestring
                ORDER BY manufacturer_seqno,manufacturer_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['manufacturer_id'];
            $code = $row['manufacturer_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getCountrySelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT country_id,country_code from db_country WHERE (country_id = '$pid' or country_id >0) and country_status = 1 $wherestring
                ORDER BY country_seqno,country_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['country_id'];
            $code = $row['country_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getMachineSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT machine_id,machine_no from db_machine WHERE (machine_id = '$pid' or machine_id >0) and machine_status = 1 $wherestring
                ORDER BY machine_seqno,machine_no ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['machine_id'];
            $code = $row['machine_no'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getGroupCompSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT groupcomp_id,groupcomp_code from db_groupcomp WHERE (groupcomp_id = '$pid' or groupcomp_id >0) and groupcomp_status = 1 $wherestring
                ORDER BY groupcomp_seqno,groupcomp_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['groupcomp_id'];
            $code = $row['groupcomp_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getRaceSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT race_id,race_code from db_race WHERE (race_id = '$pid' or race_id >0) and race_status = 1 $wherestring
                ORDER BY race_seqno,race_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['race_id'];
            $code = $row['race_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getReligionSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT religion_id,religion_code from db_religion WHERE (religion_id = '$pid' or religion_id >0) and religion_status = 1 $wherestring
                ORDER BY religion_seqno,religion_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = ''>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['religion_id'];
            $code = $row['religion_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getServicefeesSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT serfees_id,serfees_code from db_serfees WHERE (serfees_id = '$pid' or serfees_id >0) and serfees_status = 1 $wherestring
                ORDER BY serfees_seqno,serfees_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['serfees_id'];
            $code = $row['serfees_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getDepartmentSelectCtrl($pid,$shownull="Y",$wherestring='',$text = "One"){
        $sql = "SELECT department_id,department_code from db_department WHERE (department_id = '$pid' or department_id >0) and department_status = 1 $wherestring
                ORDER BY department_seqno,department_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select $text</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['department_id'];
            $code = $row['department_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getAssignSelectCtrl($pid,$shownull="Y",$wherestring='',$text = "One"){
        $manager_id = $_SESSION['empl_id'];
        $sql = "SELECT empl_id , empl_name FROM `db_empl` WHERE (empl_id = '$pid' or empl_group = '8') and empl_manager = '$manager_id'";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select $text</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['empl_id'];
            $code = $row['empl_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }
            else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getPayrollEmplSelectCtrl($pid,$shownull="Y",$wherestring='',$text = "One"){
        $sql = "SELECT empl_id , empl_name FROM `db_empl` WHERE (empl_id = '$pid' or empl_group = '7')";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select $text</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['empl_id'];
            $code = $row['empl_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }
            else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }  
    public function getInChargePersonSelectCtrl($pid,$shownull="Y",$wherestring='',$text = "One"){
        $sql = "SELECT empl_id , empl_name FROM `db_empl` WHERE (empl_id = '$pid' or empl_group = '8')";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select $text</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['empl_id'];
            $code = $row['empl_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }
            else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }    
    public function getCategoryParentSelectCtrl($pid,$shownull="Y",$wherestring='',$text = "Parent"){
        $sql = "SELECT category_id , category_name FROM `db_category_job` WHERE (category_id = '$pid' or category_id > 0)";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Assign as $text</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['category_id'];
            $code = $row['category_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }
            else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }     
    public function getCategorySelectCtrl($pid,$shownull="Y",$wherestring='',$text = "One"){
        $sql = "SELECT category_id , category_name FROM `db_category_job` WHERE (category_id = '$pid' or category_id > 0) and category_parent > '0'";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select $text</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['category_id'];
            $code = $row['category_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }
            else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    } 
    public function getClientSelectCtrl($pid,$shownull="Y",$wherestring='',$text = "One"){
        $sql = "SELECT partner_id , partner_name FROM `db_partner` WHERE (partner_id = '$pid' or partner_id >0)";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select $text</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['partner_id'];
            $code = $row['partner_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getServiceOrderSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT sorder_id,sorder_no from db_sorder WHERE (sorder_id = '$pid' or sorder_id >0) and sorder_status = 1 $wherestring
                ORDER BY sorder_no DESC,sorder_date DESC";

        if($shownull =="Y"){
            $selectctrl .='<option value = "" SELECTED="SELECTED">Select One</option>';
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['sorder_id'];
            $code = $row['sorder_no'];
            if($id == $pid){
                $selected = 'SELECTED = "SELECTED"';
            }else{
                $selected = "";
            }
            $selectctrl .='<option value = "' . $id . '"' . $selected . ">$code</option>"; 
        }
        return $selectctrl;
    }
    public function getExpensesSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT expenses_id,expenses_code from db_expenses WHERE (expenses_id = '$pid' or expenses_id >0) and expenses_status = 1 $wherestring
                ORDER BY expenses_seqno,expenses_code ASC";

        if($shownull =="Y"){
            $selectctrl .='<option value = "" SELECTED="SELECTED">Select One</option>';
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['expenses_id'];
            $code = $row['expenses_code'];
            if($id == $pid){
                $selected = 'SELECTED = "SELECTED"';
            }else{
                $selected = "";
            }
            $selectctrl .='<option value = "' . $id . '"' . $selected . ">$code</option>"; 
        }
        return $selectctrl;
    }
    public function getEmplLeaveSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT lt.leavetype_id,lt.leavetype_code 
                from db_emplleave el
                INNER JOIN db_leavetype lt ON lt.leavetype_id = el.emplleave_leavetype
                WHERE (lt.leavetype_id = '$pid' or lt.leavetype_id >0) and lt.leavetype_status = 1 $wherestring
                ORDER BY lt.leavetype_seqno,lt.leavetype_code ASC"; 
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['leavetype_id'];
            $code = $row['leavetype_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }

    public function getDesignationSelectCtrl($pid,$shownull="Y",$wherestring='',$text = "One"){
        $sql = "SELECT designation_id,designation_code from db_designation WHERE (designation_id = '$pid' or designation_id >0) and designation_status = 1 $wherestring
                ORDER BY designation_seqno,designation_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select $text</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['designation_id'];
            $code = $row['designation_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }
    public function getAddressTypeSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT partneraddresstype_id,partneraddresstype_code from db_partneraddresstype WHERE (partneraddresstype_id = '$pid' or partneraddresstype_id >0) and partneraddresstype_status = 1 $wherestring
                ORDER BY partneraddresstype_seqno,partneraddresstype_code ASC";

        if($shownull =="Y"){
            $selectctrl .='<option value = "" SELECTED="SELECTED">Select One</option>';
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['partneraddresstype_id'];
            $code = $row['partneraddresstype_code'];
            if($id == $pid){
                $selected = 'SELECTED = "SELECTED"';
            }else{
                $selected = "";
            }
            $selectctrl .='<option value = "' . $id . '"' . $selected . ">$code</option>";
        }
        return $selectctrl;
    }
    public function getManagerCtrl($pid,$shownull="Y",$wherestring='',$text = "One"){
        $empl_id = $_SESSION['empl_id'];
        $sql = "SELECT empl_id, empl_manager FROM db_empl WHERE empl_id = '$empl_id'";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);
        
        $sql2 = "SELECT empl_id , empl_name FROM `db_empl` WHERE (empl_id = '$pid' or empl_id = '$row[empl_manager]')";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select $text</option>";
        }
        $query2 = mysql_query($sql2);
        while($row2 = mysql_fetch_array($query2)){
            $id = $row2['empl_id'];
            $code = $row2['empl_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }
            else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        
        if ($pid != ""){
            $where = 'AND empl_id != $pid';
        }
        else {
            $where = "";
        }
        $sql2 = "SELECT empl_id , empl_name FROM db_empl WHERE  empl_id != '$row[empl_manager]' AND empl_group = '4' $where";
        $query2 = mysql_query($sql2);
        while($row2 = mysql_fetch_array($query2)){
            $id = $row2['empl_id'];
            $code = $row2['empl_name'];

            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        
        
        return $selectctrl;
    }    
    public function getApplicantLeaveTypeSelectCtrl($pid,$shownull="Y",$wherestring=''){
        $sql = "SELECT leavetype_id,leavetype_code from db_leavetype INNER JOIN db_applleave ON leavetype_id = applleave_leavetype WHERE (leavetype_id = '$pid' or leavetype_id >0) and leavetype_status = 1 and applleave_appl = $_SESSION[empl_id] and applleave_disabled = '0' $wherestring
                ORDER BY leavetype_seqno,leavetype_code ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select One</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['leavetype_id'];
            $code = $row['leavetype_code'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }    
    public function getSHROutletSelectCtrl($pid,$shownull="Y",$wherestring='',$text = "One"){
        $sql = "SELECT outlet_id , outlet_name FROM `db_company_outlet` WHERE (outlet_id = '$pid' or outlet_id >0) ORDER BY outlet_seqno ASC";
        if($shownull =="Y"){
            $selectctrl .="<option value = '' SELECTED='SELECTED'>Select $text</option>";
        }
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query)){
            $id = $row['outlet_id'];
            $code = $row['outlet_name'];
            if($id == $pid){
                $selected = "SELECTED = 'SELECTED'";
            }else{
                $selected = "";
            }
            $selectctrl .="<option value = '$id' $selected>$code</option>"; 
        }
        return $selectctrl;
    }    
    
}

?>
