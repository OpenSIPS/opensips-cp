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

 require("template/header.php");
 include("lib/db_connect.php");
 require ("../../../common/mi_comm.php");
 require("../../../common/cfg_comm.php");
 require("../../../../config/db.inc.php");
 $table=$config->table_gateways;
 $current_page="current_page_gateways";
 
 if (isset($_POST['action'])) $action=$_POST['action'];
 else if (isset($_GET['action'])) $action=$_GET['action'];
      else $action="";

 if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
 else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

#################
# start details #
#################
 if ($action=="details")
 {
  $sql = "select * from ".$table." where gwid=?";
  $stm = $link->prepare($sql);
  if ($stm === false) {
    die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
  }
  $stm->execute( array($_GET['gwid']) );
  $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
  require("lib/".$page_id.".functions.inc.php");
  require("template/".$page_id.".details.php");
  require("template/footer.php");
  exit();
 }
###############
# end details #
###############


######################
# start enable gw    #
######################
if ($action=="enablegw"){
	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

	$params = array("gw_id"=>$_GET['gwid'],"status"=>"1");
	if (isset($config->routing_partition) && $config->routing_partition != "")
		$params['partition_name'] = $config->routing_partition;

    	for ($i=0;$i<count($mi_connectors);$i++){
		$message=mi_command("dr_gw_status", $params, $mi_connectors[$i], $errors);
	}
	if (!empty($errors))
		echo "Error while enabling gateway ".$_GET['gwid']." (".$errors[0].")";
}
##################
# end enable gw  #
##################


#######################
# start disable gw    #
#######################
if ($action=="disablegw"){
	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

	$params = array("gw_id"=>$_GET['gwid'],"status"=>"0");
	if (isset($config->routing_partition) && $config->routing_partition != "")
		$params['partition_name'] = $config->routing_partition;

    	for ($i=0;$i<count($mi_connectors);$i++){
		$message=mi_command("dr_gw_status", $params, $mi_connectors[$i], $errors);
	}
	if (!empty($errors))
		echo "Error while enabling gateway ".$_GET['gwid']." (".$errors[0].")";
}
##################
# end disable gw  #
##################

######################
# start probing gw   #
######################
if ($action=="probegw"){
	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

	$params = array("gw_id"=>$_GET['gwid'],"status"=>"2");
	if (isset($config->routing_partition) && $config->routing_partition != "")
		$params['partition_name'] = $config->routing_partition;

    	for ($i=0;$i<count($mi_connectors);$i++){
		$message=mi_command("dr_gw_status", $params, $mi_connectors[$i], $errors);
	}
	if (!empty($errors))
		echo "Error while enabling gateway ".$_GET['gwid']." (".$errors[0].")";
}
##################
# end probing gw #
##################


################
# start modify #
################
 if ($action=="modify")
 {
  require("lib/".$page_id.".test.inc.php");
  if ($form_valid) {
                $sql = "update ".$table." set gwid=?, type=?, attrs=?, address=?, strip=?, pri_prefix=?, probe_mode=?, socket=?, state=?, description=? where id=?";
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array($gwid,$type,$attrs,$address,$strip,$pri_prefix,$probe_mode,$socket,$state,$description,$_GET['id']) )==NULL)
			echo 'Gateway DB update failed : ' . print_r($stm->errorInfo(), true);
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
  $sql = "select * from ".$table." where id=?";
  $stm = $link->prepare($sql);
  if ($stm === false) {
    die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
  }
  $stm->execute( array($_GET['id']) );
  $resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
  require("lib/".$page_id.".functions.inc.php");
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
	$_SESSION['gateways_search_gwid']="";
	$_SESSION['gateways_search_type']="";
	$_SESSION['gateways_search_address']="";
	$_SESSION['gateways_search_pri_prefix']="";
	$_SESSION['gateways_search_probe_mode']="";
	$_SESSION['gateways_search_description']="";
	$_SESSION['gateways_search_attrs']="";
	$sql = "insert into ".$table." (gwid, type, address, attrs,strip, pri_prefix, probe_mode, socket, state, description) ".
		"values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	if ($stm->execute( array($gwid,$type,$address,$attrs,$strip,$pri_prefix,$probe_mode,$socket,$state,$description) )==NULL)
		echo 'Gateway DB update failed : ' . print_r($stm->errorInfo(), true);
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
   else $strip="0";
  require("lib/".$page_id.".functions.inc.php");
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
	$del_id=$_GET['gwid'];
	$sql = "delete from ".$table." where gwid=?";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	$stm->execute( array($del_id) ); 

	$sql_regex = "'(^|,)".$del_id."(=|,|$)'";
	$repl_regex1 = "'(,".$del_id."(=[^,]+)?,)'";
	$repl_regex2 = "'((^|,)".$del_id."(=[^,]+)?(,|$))'";

	//remove GW from dr_rules
	if ($config->db_driver == "mysql") 
		$sql = "select ruleid,gwlist from ".$config->table_rules." where gwlist regexp ";
	else if ($config->db_driver == "pgsql")
		$sql = "select ruleid,gwlist from ".$config->table_rules." where gwlist ~* ?";

	$stm = $link->query($sql.$sql_regex);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

	for($i=0;count($resultset)>$i;$i++){
		$list=$resultset[$i]['gwlist'];
		$new_list = preg_replace($repl_regex1,',',$list);
		$new_list = preg_replace($repl_regex2,'',$new_list);
		if ($new_list!=$list) {
			$sql = "update ".$config->table_rules." set gwlist=? where ruleid=?";
			$stm = $link->prepare($sql);
			if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
			}
			if ($stm->execute( array($new_list,$resultset[$i]['ruleid']) )==NULL)
				echo 'Rule DB update failed : ' . print_r($stm->errorInfo(), true);
		}
	}

	//remove GW from dr_carriers
	if ($config->db_driver == "mysql")
		$sql = "select carrierid,gwlist from ".$config->table_carriers." where gwlist regexp ?";
	else if ($config->db_driver == "pgsql")
		$sql = "select carrierid,gwlist from ".$config->table_rules." where gwlist ~* ?";

	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	$stm->execute( array($sql_regex) );
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

	for($i=0;count($resultset)>$i;$i++){
		$list = $resultset[$i]['gwlist'];
		$new_list = preg_replace($repl_regex1,',',$list);
		$new_list = preg_replace($repl_regex2,'',$new_list);
		if ($new_list!=$list) {
			$sql = "update ".$config->table_carriers." set gwlist=? where carrierid=?";
			$stm = $link->prepare($sql);
			if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
			}
			if ($stm->execute( array($list,$resultset[$i]['carrierid']) )==NULL)
				echo 'Carrier DB update failed : ' . print_r($stm->errorInfo(), true);
		}
	}
}
##############
# end delete #
##############

################
# start search #
################
if ($action=="search") {

	$_SESSION[$current_page]=1;
	extract($_POST);

	if ($show_all=="Show All") {
		$_SESSION['gateways_search_gwid']="";
		$_SESSION['gateways_search_type']="";
		$_SESSION['gateways_search_address']="";
		$_SESSION['gateways_search_pri_prefix']="";
		$_SESSION['gateways_search_probe_mode']="";
		$_SESSION['gateways_search_description']="";
		$_SESSION['gateways_search_attrs']="";
	}
	else {
         $_SESSION['gateways_search_gwid']=$search_gwid;
         $_SESSION['gateways_search_type']=$search_type;
         $_SESSION['gateways_search_address']=$search_address;
         $_SESSION['gateways_search_pri_prefix']=$search_pri_prefix;
		 $_SESSION['gateways_search_probe_mode']=$probe_mode;
         $_SESSION['gateways_search_description']=$search_description;
		 $_SESSION['gateways_search_attrs']=$search_attrs;
	}
}
##############
# end search #
##############

##############
# start main #
##############
 require("lib/".$page_id.".functions.inc.php");
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
############
# end main #
############
?>
