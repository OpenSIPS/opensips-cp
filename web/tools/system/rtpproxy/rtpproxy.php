<?php
/*
* Copyright (C) 2018 OpenSIPS Project
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
require ("../../../common/mi_comm.php");

$table=get_settings_value("table_rtpproxy");
$current_page="current_page_rtpproxy";

session_load();

csrfguard_validate();

include("lib/db_connect.php");

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

###############################
# 		state change		  #
###############################

if ($action=="change_state"){

	$state= $_GET['state'];
	$sock = $_GET['sock'];

	$mi_connectors=get_proxys_by_assoc_id(get_settings_value('talk_to_this_assoc_id'));
	for ($i=0;$i<count($mi_connectors);$i++) {
		if ($state=="0") {
			mi_command("rtpproxy_enable",array("url"=>$sock,"enable"=> "0") , $mi_connectors[$i], $errors);
		} else {
			mi_command("rtpproxy_enable",array("url"=>$sock,"enable"=> "1") , $mi_connectors[$i], $errors);
		}
	}

} 

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
# start add verify #
####################
if ($action=="add_verify")
{
	$info="";
	$errors="";

	if(!$_SESSION['read_only']){

		$rtpproxy_sock=$_POST['rtpproxy_sock'];
		$set_id=$_POST['set_id'];

		if($rtpproxy_sock=="" || $set_id=="") {
			print "Invalid data!!";
		}

		if ($errors=="") {
			$sql = "SELECT * FROM ".$table." WHERE set_id = ? AND rtpproxy_sock = ?";
			$stm = $link->prepare($sql);
			if ($stm->execute(array($set_id, $rtpproxy_sock)) === false)
				die('Failed to issue query, error message : ' .
					print_r($stm->errorInfo(), true));
			$row = $stm->fetchAll();

			if (count($row)>0) {
				$errors="Duplicate rule";
			} else {
				$sql_command = "INSERT INTO ".$table."
				(set_id, rtpproxy_sock) VALUES (?, ?)";
				$stm = $link->prepare($sql_command);
				if ($stm->execute(array($set_id, $rtpproxy_sock)) === false) {
					$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
				} else{
					$info="The new record was added";
				}
			}
		}
	}else{
		$errors= "User with Read-Only Rights";
	}

}

##################
# end add verify #
##################

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

	$info="";
	$errors="";

	if(!$_SESSION['read_only']){

		$set_id=$_POST['set_id'];
		$rtpproxy_sock=$_POST['rtpproxy_sock'];
		$id=$_GET['id'];

		if ($set_id=="" || $rtpproxy_sock==""){
			$errors = "Invalid data, the entry was not modified in the database";
		}
		if ($errors=="") {
			$sql = "SELECT * FROM ".$table." WHERE set_id = ? AND rtpproxy_sock = ? AND id != ?";
			$stm = $link->prepare($sql);
			if ($stm->execute(array($set_id, $rtpproxy_sock, $id)) === false)
				die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
			$row = $stm->fetchAll();

			if (count($row)>0) {
				$errors="Duplicate rule";
			} else {
				$sql_command = "UPDATE ".$table." SET set_id = ?, rtpproxy_sock = ? WHERE id = ?";
				$stm = $link->prepare($sql_command);
				if ($stm->execute(array($set_id, $rtpproxy_sock, $id)) === false) {
					$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true);
				} else {
					$info="The new rule was modified";
				}
			}
		}
	}else{

		$errors= "User with Read-Only Rights";
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
		$sql = "DELETE FROM ".$table." WHERE id = ?";
		$stm = $link->prepare($sql);
		if ($stm->execute(array($id)) === false)
			die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
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
if ($action=="dp_act")
{
$query="";
	$_SESSION['rtpproxy_sock']  = $_POST['rtpproxy_sock'];
	$_SESSION['rtpproxy_setid']= $_POST['rtpproxy_setid'];

	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['rtpproxy_setid']="";
		$_SESSION['rtpproxy_sock']="";
	} else if($search=="Search"){
		$_SESSION['rtpproxy_setid']=$_POST['rtpproxy_setid'];
		$_SESSION['rtpproxy_sock'] =$_POST['rtpproxy_sock'];
	} else if($_SESSION['read_only']){

		$errors= "User with Read-Only Rights";

	}else if($delete=="Delete RTPproxy Sock"){
		$set_id = $_POST['rtpproxy_setid'];
		$rtpproxy_sock = $_POST['rtpproxy_sock'];
		$qvalues = array();

		if($rtpproxy_sock =="") { 
			$query .= " AND rtpproxy_sock LIKE %";
		}else {
			$query .= " AND rtpproxy_sock LIKE ?"; 
			$qvalues[] = "%" . $rtpproxy_sock . "%";
		}

		if ($set_id!=""){
			$query .= " AND set_id = ?";
			$qvalues[] = $set_id;
		}

		$sql = "SELECT * FROM " . $table . " WHERE (1=1) " . $query;
		$stm = $link->prepare($sql);
		if ($stm->execute($qvalues) === false)
			die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
		$row = $stm->fetchAll();

		if (count($row)==0) {
			$errors="No Rule with such RTPproxy Sock ID";
			$_SESSION['rtpproxy_setid']="";
			$_SESSION['rtpproxy_sock']="";

		}else{
			$sql = "DELETE FROM ".$table." WHERE (1=1) " .$query;
			$stm = $link->prepare($sql);
			if ($stm->execute($qvalues) === false)
				die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
		}
		print $result;
	}
}
##############
# end search #
##############

##############
# start main #
##############

require("template/".$page_id.".main.php");
if (!empty($errors)) {
	echo "Error stack: ";
	print_r($errors);
}

require("template/footer.php");
exit();

##############
# end main   #
##############
?>
