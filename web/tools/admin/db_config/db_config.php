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

require_once("../../../common/cfg_comm.php");
require("../../../../config/db.inc.php");
require("template/header.php");
require("../../../../config/tools/admin/db_config/db.inc.php");
require("../../../../config/tools/admin/db_config/settings.inc.php");
include("lib/db_connect.php");
require("../../../../config/globals.php");

csrfguard_validate();

$table=$config->table_db_config; 
$current_page="current_page_db_config";
load_db_config();

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

if ($action=="modify_params")
{ 
	if(!$_SESSION['read_only']){
		extract($_POST);
		$id = $_GET['db_id'];
		$sql = "UPDATE ".$table." SET config_name=?, db_host=?, db_pass=?,
		 db_user=?, db_name=?, db_port=? WHERE id=?";
		$stm = $link->prepare($sql);

		if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute(array($config_name, $db_host, $db_pass, $db_user, $db_name, $db_port, $id)) == false) {
			$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true); 
		}    else {
			$info="Configuration was modified";
		}
		unset($_SESSION['db_config']);
		load_db_config();
	}   else {
   		$errors= "User with Read-Only Rights";
   	} 
	//header('Location: ../../'.$_SESSION['current_group'].'/'.$_SESSION['current_tool'].'/index.php');
}

if ($action=="edit_db")
{
	$id = $_GET['db_id'];
 require("template/".$page_id.".edit_db.php");
	require("template/footer.php");
	exit();
}

if ($action=="delete")
{
	if(!$_SESSION['read_only']){

		$id = $_GET['db_id'];

		$sql = "DELETE FROM ".$table." WHERE id=?";
      		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( array($id) );
		unset($_SESSION['db_config']);
		load_db_config();
	}else{

		$errors= "User with Read-Only Rights";
	}
}

if ($action == "details") {
	
	$id = $_GET['db_id'];
	require("template/".$page_id.".details.php");
	require("template/footer.php");
	exit();
}

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

if ($action == "add_verify") { 
	if(!$_SESSION['read_only']){
		extract($_POST);
	
		$sql = 'INSERT INTO '.$table.' (config_name, db_host, db_name,
		db_port, db_pass, db_user) VALUES (?,?,?,?,?,?)';
				$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$form_valid = true;
		if ($stm->execute(array($config_name, $db_host, $db_name, $db_port, $db_pass, $db_user)) == false) {
			$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
			$form_valid=false;
		} 
		unset ($_SESSION['db_config']);
		load_db_config();
	
		if ($form_valid) {
		  print "New configuration added!";
		  $action="add";
		}
   } else {
	   $errors= "User with Read-Only Rights";
	  }
}

require("template/".$page_id.".main.php");
if(isset($errors)) echo($errors);
require("template/footer.php");
exit();

?>
