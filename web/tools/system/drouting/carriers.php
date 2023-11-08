<?php
/*
 * Copyright (C) 2011 OpenSIPS Project
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for 
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

 
 require("../../../common/cfg_comm.php");
 require("template/header.php");
 include("lib/db_connect.php");
 require_once("lib/common.functions.inc.php");
 require ("../../../common/mi_comm.php");
 $table=get_settings_value("table_carriers");
 $current_page="current_page_lists";
 
 csrfguard_validate();

 if (isset($_POST['action'])) $action=$_POST['action'];
 else if (isset($_GET['action'])) $action=$_GET['action'];
      else $action="";

 if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
 else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;
 
 require("lib/".$page_id.".functions.inc.php");

#################
# start details #
#################
 if ($action=="details")
 {
  $sql = "select * from ".$table." where carrierid=?";
  $stm = $link->prepare($sql);
  if ($stm === false) {
  	die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
  }
  $stm->execute( array($_GET['carrierid']) );
  $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

  $resultset[0]['useweights']   = ($resultset[0]['sort_alg']=="W") ? "Yes" : "No";
  $resultset[0]['useonlyfirst'] = (fmt_binary((int)$resultset[0]['flags'],4,4)) ? "Yes" : "No";

  $mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));

  $params = array("carrier_id"=>$_GET['carrierid']);
  if (get_settings_value("routing_partition") && get_settings_value("routing_partition") != "")
    $params['partition_name'] = get_settings_value("routing_partition");

  $message=mi_command( "dr_carrier_status", $params, $mi_connectors[0], $errors);
  $resultset[0]['enabled'] = $message['Enabled']=="yes"?"enabled":"disabled";

  require("template/".$page_id.".details.php");
  require("template/footer.php");
  exit();
 }
###############
# end details #
###############


#########################
# start enable carrier  #
#########################
if ($action=="enablecar"){
    $mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));

    $params = array("carrier_id"=>$_GET['carrierid'],"status"=>"1");
    if (get_settings_value("routing_partition") && get_settings_value("routing_partition") != "")
       $params['partition_name'] = get_settings_value("routing_partition");

    for ($i=0;$i<count($mi_connectors);$i++){
        $message=mi_command( "dr_carrier_status", $params, $mi_connectors[$i], $errors);
    }
    if (!empty($errors))
        echo "Error while enabling carrier ".$_GET['carrierid']." (".$errors[0].")";
}
######################
# end enable carrier #
######################


##########################
# start disable carrier  #
#########################
if ($action=="disablecar"){
    $mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));

    $params = array("carrier_id"=>$_GET['carrierid'],"status"=>"0");
    if (get_settings_value("routing_partition") && get_settings_value("routing_partition") != "")
       $params['partition_name'] = get_settings_value("routing_partition");

    for ($i=0;$i<count($mi_connectors);$i++){
        $message=mi_command( "dr_carrier_status", $params, $mi_connectors[$i], $errors);
    }
    if (!empty($errors))
        echo "Error while enabling carrier ".$_GET['carrierid']." (".$errors[0].")";
}
########################
# end disable carrier  #
########################


################
# start modify #
################
 if ($action=="modify")
 {
  require("lib/".$page_id.".test.inc.php");
  if ($form_valid) {
	$flags = bindec($useonlyfirst);
	if (get_settings_value("carrier_attributes_mode") == "params")
		$attrs = dr_build_attrs(get_settings_value("carrier_attributes"));

	$sql = "update ".$table." set gwlist=?, flags=?, sort_alg=?, state=?, description=?, attrs=? where carrierid=?";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	if ($stm->execute( array($gwlist,$flags,$sort_alg,$state,$description,$attrs,$_GET['carrierid']) ) == FALSE)
		echo "Updating DB record failed with: ". print_r($stm->errorInfo(), true);
  }
  if ($form_valid) $action="";
   else $action="edit";
 }
##############
# end modify #
##############

##############
# start edit # 
##############
 if ($action=="edit")
 {
  $sql = "select * from ".$table." where carrierid=?";
  $stm = $link->prepare($sql);
  if ($stm === false) {
  	die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
  }
  $stm->execute( array($_GET['carrierid']) );
  $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

  if (is_numeric((int)$resultset[$i]['flags'])) {
        $resultset[0]['useonlyfirst'] = (fmt_binary((int)$resultset[0]['flags'],4,3));
        $resultset[0]['enabled']      = (fmt_binary((int)$resultset[0]['flags'],4,4));
    }
    else{
        $resultset[0]['useonlyfirst'] = "error";
        $resultset[0]['enabled'] = "error";
    }
  
  require("lib/".$page_id.".add.edit.js");
  require("template/".$page_id.".edit.php");
  require("template/footer.php");
  exit();
 }
############
# end edit #
############

####################
# start add verify #
####################
 if ($action=="add_verify")
 {
  require("lib/".$page_id.".test.inc.php");
  if ($form_valid) {
 	$flags = bindec($enabled.$useonlyfirst);

	if (get_settings_value("carrier_attributes_mode") == "params")
		$attrs = dr_build_attrs(get_settings_value("carrier_attributes"));
                    
	$_SESSION['rules_search_gwlist']="";
        $_SESSION['rules_search_description']="";
                  
	$sql = "insert into ".$table." (carrierid, gwlist, flags, sort_alg, state, description,attrs) values (?,?,?,?,?,?,?)";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	if ($stm->execute( array($carrierid,$gwlist,$flags,$sort_alg,$state,$description,$attrs) ) == FALSE)
		echo "Inserting the record into DB failed with: ". print_r($stm->errorInfo(), true);
  }
  if ($form_valid) $action="";
   else $action="add";
 }
##################
# end add verify #
##################

#################
# start add new # 
#################
 if ($action=="add")
 {
  if ($_POST['add']=="Add") extract($_POST);
  require("lib/".$page_id.".add.edit.js");
  require("template/".$page_id.".add.php");
  require("template/footer.php");
  exit();
 }
###############
# end add new #
###############

################
# start delete #
################
 if ($action=="delete"){
  $del_id = $_GET['carrierid'];
  $sql = "delete from ".$table." where carrierid=?";
  $stm = $link->prepare($sql);
  if ($stm === false) {
  	die('Failed to issue query ['.$sql.'], error message : ' . print_r($stm->errorInfo(), true));
  }
  $stm->execute( array($del_id) );

    $sql_regex = "'(^#".$del_id."(=[^,]+)?,)|(,#".$del_id."(=[^,]+)?$)|(^#".$del_id."(=[^,]+)?$)|(,#".$del_id."(=[^,]+)?,)'";

    $preg_exp1 = "'(^#".$del_id."(=[^,]+)?,)|(,#".$del_id."(=[^,]+)?$)|(^#".$del_id."(=[^,]+)?$)'";
    $preg_exp2 = "'(,#".$del_id."(=[^,]+)?,)'";

    //remove Carriers from dr_rules
    if ($config->db_driver == "mysql")
        $sql = "select ruleid,gwlist from ".get_settings_value("table_rules")." where gwlist regexp ?";
    else if ($config->db_driver == "pgsql")
        $sql = "select ruleid,gwlist from ".get_settings_value("table_rules")." where gwlist ~* ?";

    $stm = $link->prepare($sql);
    if ($stm === false) {
    	die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
    }
    $stm->execute( array($sql_regex) );
    $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);


    $sql = "update ".get_settings_value("table_rules")." set gwlist=? where ruleid=?";
    $stm = $link->prepare($sql);
    if ($stm === false) {
       die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
    }
    for($i=0;count($resultset)>$i;$i++){
        $list=$resultset[$i]['gwlist'];
        if (preg_match($preg_exp1,$list))
            $list = preg_replace($sql_regex,'',$list);
        else if (preg_match($preg_exp2,$list))
            $list = preg_replace($sql_regex,',',$list);
	if ($stm->execute( array($list,$resultset[$i]['ruleid']) ) == FALSE)
		echo "Updating DB record failed with: ". print_r($stm->errorInfo(), true);
    }  

 }
##############
# end delete #
##############

################
# start search #
################
if ($action=="search")
{
	$_SESSION[$current_page]=1;
	if ($_POST['show_all']=="Show All") {
		$_SESSION['rules_search_gwlist']="";
		$_SESSION['rules_search_description']="";
		$sql_search="";
	}
	else {
		extract($_POST);
		if ($search=="Search") {
			$_SESSION['rules_search_gwlist']=$search_gwlist;
			$_SESSION['rules_search_description']=$search_description;
		}
		if ($delete=="Delete Matching") {
			$sql_search="";
			$qvalues = array();
			$search_gwlist=$_SESSION['rules_search_gwlist'];
			if ($search_gwlist!="") {
				$sql_search.=" and gwlist like ?";
				$qvalues[] = "%".$search_gwlist."%";
			}
			$search_description=$_SESSION['rules_search_description'];
			if ($search_description!="") {
				$sql_search.=" and description like ?";
				$qvalues[] = "%".$search_description."%";
			}
			$sql = "delete from ".$table." where (1=1) ".$sql_search;
			$stm = $link->prepare($sql);
			if ($stm->execute($qvalues) === false)
				die('Failed to issue delete, error message : ' . print_r($stm->errorInfo(), true));
		}
	}
}
##############
# end search #
##############

##############
# start main #
##############
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
############
# end main #
############
?>
