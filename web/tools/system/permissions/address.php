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
require("lib/".$page_id.".main.js");
require ("../../../common/mi_comm.php");
include("lib/db_connect.php");

$table=$config->table_address;
$current_page="current_page_address";

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

unset($errors);

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

	if(!$_SESSION['read_only']){

		$grp=$_POST['grp'];
		$src_ip=$_POST['src_ip'];
		$mask=$_POST['mask'];
		$port=$_POST['port'];
		$proto=$_POST['proto'];
		$from_pattern = $_POST['from_pattern'];
		$context_info= $_POST['context_info'];

		if($from_pattern=="")
		$flags = "0";

		if ( !preg_match('/^[0-9]+$/', $grp) ){
			$errors = "Invalid group (not numerical), the entry was not modified in the database";
		} else if ( !preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $src_ip) ){
			$errors = "Invalid IP address, the entry was not modified in the database";
		} else {
			$sql = "INSERT INTO ".$table." (grp, ip, mask, port, proto, pattern, context_info) VALUES 
				('".$grp."','".$src_ip."','".$mask."','".$port."','".$proto."','".$from_pattern."','".$context_info."')";
			$resultset = $link->exec($sql);
			if(PEAR::isError($resultset)) {
				die('Failed to issue query, error message : ' . $resultset->getMessage());
			}
			$info="The new record was added";
			$link->disconnect();
		}
	}else{
		$errors= "User with Read-Only Rights";
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
# end edit	#
#############

#################
# start modify	#
#################
if ($action=="modify")
{

	$info="";

	if(!$_SESSION['read_only']){

		$id = $_GET['id'];
                $grp=$_POST['grp'];
                $src_ip=$_POST['src_ip'];
                $mask=$_POST['mask'];
                $port=$_POST['port'];
                $proto=$_POST['proto'];
                $from_pattern = $_POST['from_pattern'];
                $context_info= $_POST['context_info'];

		if ( !preg_match('/^[0-9]+$/', $grp) ){
			$errors = "Invalid group (not numerical), the entry was not modified in the database";
		} else if ( !preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $src_ip) ){
			$errors = "Invalid IP address, the entry was not modified in the database";
		} else {
			$sql = "UPDATE ".$table." SET grp=".$grp.", ip='".$src_ip."', mask=".$mask.", port=".$port.", proto = '".$proto.
				"', pattern= '".$from_pattern."', context_info='".$context_info."' WHERE id=".$id;
			$resultset = $link->prepare($sql);
			$resultset->execute();
			$resultset->free();

			$info="The new rule was modified";
			$link->disconnect();
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

		$sql = "DELETE FROM ".$table." WHERE id=".$id;
		$link->exec($sql);
		$link->disconnect();
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

	$_SESSION['address_src']=$_POST['address_src'];
	$_SESSION['address_proto']=$_POST['address_proto'];
	$_SESSION['address_port']=$_POST['address_port'];
	$_SESSION[$current_page]=1;
	extract($_POST);
	if ($show_all=="Show All") {
		$_SESSION['address_src']="";
		$_SESSION['address_proto']="";
		$_SESSION['address_port']="";
	} else if($search=="Search"){
		$_SESSION['address_src']=$_POST['address_src'];
		$_SESSION['address_proto']=$_POST['address_proto'];
		$_SESSION['address_port']=$_POST['address_port'];
	} else if($_SESSION['read_only']){

		$errors= "User with Read-Only Rights";

	}else if($delete=="Delete Address"){
		$sql_query="";
		if ( $_POST['address_src'] != "" ) {
			$src_ip = $_POST['address_src'];
			$sql_query .= " AND ip like '%" . $src_ip . "%'"; 
		}
		if ( $_POST['address_proto'] != "" ) {
			$proto = $_POST['address_proto'];
			$sql_query .= " AND proto like '%" . $proto . "%'"; 
		}
		if ( $_POST['address_port'] != "" ) {
			$proto = $_POST['address_port'];
			$sql_query .= " AND port like '%" . $port . "%'"; 
		}
		$sql = "SELECT * FROM ".$table.
		" WHERE (1=1) " .$sql_query;
		$resultset = $link->queryAll($sql);
		if (count($resultset)==0) {
			$errors="No such rule";
			$_SESSION['address_src']="";
			$_SESSION['address_proto']="";
			$_SESSION['address_port']="";

		}else{
			$sql = "DELETE FROM ".$table." WHERE (1=1) ".$sql_query;
			$link->exec($sql);
		}
		$link->disconnect();
	
	}
	
}
##############
# end search #
##############

##############
# start main #
##############

require("template/".$page_id.".main.php");
if(isset($errors)) {
	echo('!!! ');
	echo($errors);
}
require("template/footer.php");
exit();

##############
# end main   #
##############
?>
