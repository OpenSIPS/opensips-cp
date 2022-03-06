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

function consoole_log( $data ){
	echo '<script>';
	echo 'console.log('. json_encode( $data ) .')';
	echo '</script>';
  } //  DE_STERS
require_once("../../../common/cfg_comm.php");
require_once("template/functions.inc.php");
require_once("template/functions.inc.js");
require("../../../../config/db.inc.php");
require("template/header.php");
require("../../../../config/tools/admin/dashboard/db.inc.php");
require("../../../../config/tools/admin/dashboard/local.inc.php");
include("lib/db_connect.php");
require("../../../../config/globals.php");
error_log(json_decode($_POST, true));
$table=$config->table_dashboard; 
$box_id = $_GET['box_id'];
if ($box_id == '') $box_id = null;

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

if ($action=="edit_panel")
{  	
    require("template/".$page_id.".edit_panel.php");
	require("template/footer.php");
	exit();
}

if ($action=="delete")
{
	if(!$_SESSION['read_only']){

		$id = $_GET['panel_id'];

		$sql = "DELETE FROM ".$table." WHERE id=?";
      		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( array($id) );
		unset($_SESSION['config']['panels'][$id]);
	}else{

		$errors= "User with Read-Only Rights";
	}
}

if ($action == "details") {
	$id = $_GET['box_id'];
	require("template/".$page_id.".details.php");
	require("template/footer.php");
	exit();
}

if ($action == "display_panel") {
	$panel_id = $_GET['panel_id'];
	require("template/".$page_id.".main.php");
	require("template/footer.php");
	exit();
}

if ($action == "add_widget") {
	$panel_id = $_GET['panel_id'];
	require("template/".$page_id.".addWidget.php");
	require("template/footer.php");
	exit();
}

if ($action=="add_blank_panel")
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

if ($action == "add_widget_verify") { 
	if(!$_SESSION['read_only']){
		$panel_id = $_GET['panel_id'];
		require("template/".$page_id.".main.php");
		require("template/footer.php");
		exit();
  
   } else {
	   $errors= "User with Read-Only Rights";
	  }
}


if ($action == "add_verify") { 
	if(!$_SESSION['read_only']){
		extract($_POST);
		$sql = 'INSERT INTO '.$table.' (`name`) VALUES (?) ';
				$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array($_POST['panel_name'])) == false) {
			$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
			$form_valid=false;
		} 
		if ($form_valid) {
		  print "New Panel added!";
		  $action="add_blank_panel";
		} else {
		  print $form_error;
		  $action="add_verify";
		}
  
   } else {
	   $errors= "User with Read-Only Rights";
	  }
}
if ($action == "clone_panel") { 
	if(!$_SESSION['read_only']){
		$panel_id = $_GET['panel_id'];
		$sql = 'INSERT INTO '.$table.' (`name`, content) VALUES (?,?) ';
				$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array($_SESSION['config']['panels'][$panel_id]['name'],$_SESSION['config']['panels'][$panel_id]['content'] )) == false) {
			$errors= "Inserting record into DB failed: ".print_r($stm->errorInfo(), true);
			$form_valid=false;
		} 
		if ($form_valid) {
		  print "New Panel added!";
		  $action="edit_panel";
		} else {
		  print $form_error;
		  $action="add_verify";
		}
  
   } else {
	   $errors= "User with Read-Only Rights";
	  }
}

require("template/".$page_id.".main.php");
if($errors) echo($errors);
require("template/footer.php");
exit();

?>
