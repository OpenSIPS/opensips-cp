<?php
/*
* Copyright (C) 2011-2017 OpenSIPS Project
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
require("lib/".$page_id.".main.js");
require("../../../common/mi_comm.php");
session_load();

csrfguard_validate();

$table=get_settings_value("table_dispatcher");
$current_page="current_page_dispatcher";

include("lib/db_connect.php");

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;


$info="";
$errors="";

#################
# start add new #
#################

if ($action=="add")
{
	extract($_POST);
	if(!$_SESSION['read_only'])
	{
		require("template/".$page_id.".add.php");
		require("template/footer.php");
		exit();
	}else {
		$errors= "User with Read-Only Rights";
	}

}

#################
# end add new   #
#################


####################
# start do_add     #
####################
if ($action=="do_add")
{
	if ($_SESSION['read_only']) {
		$errors= "User with Read-Only Rights";
	} else {
		$setid=$_POST['setid'];
		if (!isset($setid))
			$setid = get_settings_value("dispatcher_groups");
		$destination=$_POST['destination'];
		$socket = $_POST['socket'];
		$state = $_POST['state'];
		$weight = $_POST['weight'];
		$attrs = $_POST['attrs'];
		$description = $_POST['description'];

		$sql = "INSERT INTO ".$table." (setid, destination, socket, state, weight, attrs, description) VALUES ".
			"(?, ?, ?, ?, ?, ?, ?)";

		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array($setid,$destination,$socket,$state,$weight,$attrs,$description) ) == FALSE) {
			$errors="Adding record to DB failed with: ". print_r($stm->errorInfo(), true);
		} else {
			$info="The new record was added";
		}
	}

}

##################
# end add verify #
##################

#################
# start edit	#
#################
if ($action=="edit")
{

	if(!$_SESSION['read_only']){

		extract($_POST);

		require("template/".$page_id.".edit.php");
		require("template/footer.php");
		exit();
	}else{
		$errors= "User with Read-Only Rights";
	}
}
#############
# end edit  #
#############

#################
# start modify	#
#################
if ($action=="modify")
{

	if ($_SESSION['read_only']) {
		$errors= "User with Read-Only Rights";
	} else {
		$id = $_GET['id'];
		$setid=$_POST['setid'];
		if (!isset($setid))
			$setid = get_settings_value("dispatcher_groups");
		$destination=$_POST['destination'];
		$socket = $_POST['socket'];
		$state = $_POST['state'];
		$weight = $_POST['weight'];
		$attrs = $_POST['attrs'];
		$description = $_POST['description'];


		$sql = "UPDATE ".$table." SET ". 
			"setid=?, destination = ?, socket = ?, state = ?, weight = ?, attrs = ?, description = ?".
			"WHERE id=?";
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array($setid,$destination,$socket,$state,$weight,$attrs,$description,$id) )==FALSE) {
			$errors="Updating record to DB failed with: ". print_r($stm->errorInfo(), true);
		} else {
			$info="Record has been updated";
		}
	}

}
#################
# end modify	#
#################



################
# start delete #
################
if ($action=="delete")
{
	if(!$_SESSION['read_only']){

		$id=$_GET['id'];

		$sql = "DELETE FROM ".$table." WHERE id=?";
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( array($id) );
		$info="Record has been deleted";
	}else{
		$errors= "User with Read-Only Rights";
	}
}
##############
# end delete #
##############


################
# start search #
################
if ($action=="ds_search")
{

	$_SESSION[$current_page]=1;
	extract($_GET);
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['dispatcher_setid']="";
		$_SESSION['dispatcher_dest']="";
		$_SESSION['dispatcher_descr']="";
	} else if($search=="Search"){
		if (isset($_GET['dispatcher_setid']))
			$_SESSION['dispatcher_setid']=$_GET['dispatcher_setid'];
		else if (isset($_POST['dispatcher_setid']))
			$_SESSION['dispatcher_setid']=$_POST['dispatcher_setid'];
		else
			$_SESSION['dispatcher_setid']="";
		if (isset($_GET['dispatcher_dest']))
			$_SESSION['dispatcher_dest']=$_GET['dispatcher_dest'];
		else if (isset($_POST['dispatcher_dest']))
			$_SESSION['dispatcher_dest']=$_POST['dispatcher_dest'];
		else
			$_SESSION['dispatcher_dest']="";
		if (isset($_GET['dispatcher_descr']))
			$_SESSION['dispatcher_descr']=$_GET['dispatcher_descr'];
		else if (isset($_POST['dispatcher_descr']))
			$_SESSION['dispatcher_descr']=$_POST['dispatcher_descr'];
		else
			$_SESSION['dispatcher_descr']="";
	}
}
##############
# end search #
##############

################
#change state   #
################
if ($action=="change_state") {


	if ($_GET['state']=='Active') {
		$desired_state = 'I';
	} else if ($_GET['state']=='Inactive'){
		$desired_state = 'A';
	} else if ($_GET['state']=='Probing'){
		$desired_state = 'A';
	}

	$group = $_GET['group'];
	$address = $_GET['address'];

	$mi_connectors=get_all_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));
	$params = array("state"=>$desired_state,"group"=>$group,"address"=>$address);
	$dispatcher_partition = get_settings_value("dispatcher_partition");
	if ($dispatcher_partition && $dispatcher_partition != "")
		$params["partition"] = $dispatcher_partition;
	for ($i=0;$i<count($mi_connectors);$i++){
	        $message=mi_command("ds_set_state", $params, $mi_connectors[$i],$errors);
	}
	if ($errors)
		$errors=join(", ", $errors);


}

##############
# start main #
##############

require("template/".$page_id.".main.php");
if (!empty($errors)) echo('<tr><td align="center"><div class="formError">'.join(", ", $errors).'</div></td></tr>');
if ($info!="") echo('<tr><td  align="center"><div class="formInfo">'.$info.'</div></td></tr>');
require("template/footer.php");
exit();

##############
# end main   #
##############
?>
