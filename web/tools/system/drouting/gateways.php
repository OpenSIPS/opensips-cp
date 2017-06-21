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
  $sql = "select * from ".$table." where gwid='".$_GET['gwid']."'";
  $resultset = $link->queryAll($sql);
  if(PEAR::isError($resultset)) {
  	die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
  $link->disconnect();
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
	$command="dr_gw_status ".$_GET['gwid']." 1";

    	for ($i=0;$i<count($mi_connectors);$i++){
		$message=mi_command($command, $mi_connectors[$i], $errors, $status);
	}
	if (substr(trim($status),0,3)!="200")
		echo "Error while enabling gateway ".$_GET['gwid'];
}
##################
# end enable gw  #
##################


#######################
# start disable gw    #
#######################
if ($action=="disablegw"){
    $mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
    $command="dr_gw_status ".$_GET['gwid']." 0";

    for ($i=0;$i<count($mi_connectors);$i++){
        $message=mi_command($command, $mi_connectors[$i], $errors, $status);
    }
    if (substr(trim($status),0,3)!="200")
        echo "Error while disabling gateway ".$_GET['gwid'];
}
##################
# end disable gw  #
##################

######################
# start probing gw   #
######################
if ($action=="probegw"){
	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
	$command="dr_gw_status ".$_GET['gwid']." 2";

	for ($i=0;$i<count($mi_connectors);$i++){
		$message=mi_command($command, $mi_connectors[$i], $errors, $status);
	}

    if (substr(trim($status),0,3)!="200")
		echo "Error while probing gateway ".$_GET['gwid'];
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
                $sql = "update ".$table." set gwid='".$gwid."',type='".$type."', attrs='".$attrs."',address='".$address."', strip='".$strip."', pri_prefix='".$pri_prefix."', probe_mode='".$probe_mode."', socket='".$socket."',state='".$state."', description='".$description."' where id='".$_GET['id']."'";
		$resultset = $link->prepare($sql);
		$resultset->execute();
		$resultset->free();		
                $link->disconnect();
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
  $sql = "select * from ".$table." where id='".$_GET['id']."' limit 1";
  $resultset = $link->queryAll($sql);
  if(PEAR::isError($resultset)) {
  	die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
  $link->disconnect();
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
	$sql = "insert into ".$table." (gwid, type, address, attrs,strip, pri_prefix, probe_mode, socket, state, description) values ('".$gwid."','".$type."', '".$address."','".$attrs."', '".$strip."', '".$pri_prefix."', '".$probe_mode."','".$socket."','".$state."', '".$description."')";
	
	$result = $link->exec($sql);
	if(PEAR::isError($result)) {
		die('Failed to issue query, error message : ' . $result->getMessage());
	}

	$sql = "select count(*) from ".$table." where (1=1)";
	$result = $link->queryOne($sql);
	if(PEAR::isError($result)) {
			 die('Failed to issue query, error message : ' . $result->getMessage());
	}	

	$data_no=$result;
	$link->disconnect();
	$page_no=ceil($data_no/10);
	$_SESSION[$current_page]=$page_no;
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
	$sql = "delete from ".$table." where gwid='".$del_id."'";
	$link->exec($sql);	

	$sql_regex = "'(^".$del_id."(=[^,]+)?,)|(,".$del_id."(=[^,]+)?$)|(^".$del_id."(=[^,]+)?$)|(,".$del_id."(=[^,]+)?,)'";

	$preg_exp1 = "'(^".$del_id."(=[^,]+)?,)|(,".$del_id."(=[^,]+)?$)|(^".$del_id."(=[^,]+)?$)'";
	$preg_exp2 = "'(,".$del_id."(=[^,]+)?,)'";

	//remove GW from dr_rules
	if ($config->db_driver == "mysql") 
		$sql = "select ruleid,gwlist from ".$config->table_rules." where gwlist regexp ".$sql_regex;
	else if ($config->db_driver == "pgsql")
		$sql = "select ruleid,gwlist from ".$config->table_rules." where gwlist ~* ".$sql_regex;

  	$resultset = $link->queryAll($sql);

  	if(PEAR::isError($resultset)) {
 		die('Failed to issue query, error message : ' . $resultset->getMessage());
	}
	for($i=0;count($resultset)>$i;$i++){
  		$list=$resultset[$i]['gwlist'];
		if (preg_match($preg_exp1,$list))
		  	$list = preg_replace($sql_regex,'',$list);
		else if (preg_match($preg_exp2,$list))
			$list = preg_replace($sql_regex,',',$list);
		$sql = "update ".$config->table_rules." set gwlist='".$list."' where ruleid='".$resultset[$i]['ruleid']."' limit 1";
		$result = $link->exec($sql);
		if(PEAR::isError($result)) {
			die('Failed to issue query, error message : ' . $result->getMessage());
		}
  	}
	
	//remove GW from dr_carriers
    if ($config->db_driver == "mysql")
        $sql = "select carrierid,gwlist from ".$config->table_carriers." where gwlist regexp ".$sql_regex;
    else if ($config->db_driver == "pgsql")
        $sql = "select carrierid,gwlist from ".$config->table_rules." where gwlist ~* ".$sql_regex;

    $resultset = $link->queryAll($sql);

    if(PEAR::isError($resultset)) {
        die('Failed to issue query, error message : ' . $resultset->getMessage());
    }
    for($i=0;count($resultset)>$i;$i++){
        $list = $resultset[$i]['gwlist'];
		if (preg_match($preg_exp1,$list))
            $list = preg_replace($sql_regex,'',$list);
        else if (preg_match($preg_exp2,$list))
            $list = preg_replace($sql_regex,',',$list);
        $sql = "update ".$config->table_carriers." set gwlist='".$list."' where carrierid='".$resultset[$i]['carrierid']."' limit 1";
        $result = $link->exec($sql);
        if(PEAR::isError($result)) {
            die('Failed to issue query, error message : ' . $result->getMessage());
        }
    }
		
	$link->disconnect();
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
