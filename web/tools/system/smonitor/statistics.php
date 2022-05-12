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
 
 require("../../../common/cfg_comm.php");
 require("../../../common/mi_comm.php");
 require("../../../../config/tools/system/smonitor/db.inc.php");
 require("../../../../config/db.inc.php");
 require("lib/functions.inc.php");
 require("lib/functions.inc.js");
 require("template/header.php");
 
 session_load(); 
 $stat_classes = get_stats_classes();
 
 include("lib/db_connect.php");
 
if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if ($action == "import_statistic") {
	$stat_class = $_GET['class'];
    require("template/".$page_id.".import_stat.php");
	require("template/footer.php");
	exit();
}

if ($action == "edit_statistic") {
	$stat_id = $_GET['stat_id'];
    require("template/".$page_id.".edit.php");
	require("template/footer.php");
	exit();
}
 
if ($action == "delete") {
	$stat_id = $_GET['stat_id'];
	$sql = "DELETE from ocp_extra_stats where id = ?;";
		$stm = $link->prepare($sql);
		if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array( $stat_id)) == false) {
			$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true); 
		}	else {
			$info="Stat was deleted";
	}
}
 

if ($action == "add_statistic") {
	$stat_class = $_GET['class'];
    require("template/".$page_id.".add.php");
	require("template/footer.php");
	exit();
}

if ($action == "add_modify_statistic") { 
	$stat_class = $_GET['class'];
	
	$sql = "REPLACE INTO ocp_extra_stats (`name`, `input`, `tool`, `class`) VALUES (?,?,?,?);";
		$stm = $link->prepare($sql);
		if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		consoole_log($_POST['name_id']);
		consoole_log($_POST['input_id']);
		consoole_log($stat_class::get_tool());
		consoole_log($stat_class);
		
		if ($stm->execute( array( $_POST['name_id'], $_POST["input_id"], $stat_class::get_tool(), $stat_class)) == false) {
			$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true); 
		}	else {
			$info="Stat was added";
	}
}
 
if ($action == "modify_statistic") { 
	$id = $_GET['id'];
	
	$sql = "UPDATE ocp_extra_stats SET `name`=?, `input`=?;";
		$stm = $link->prepare($sql);
		if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		
		if ($stm->execute( array( $_POST['name_id'], $_POST["input_id"])) == false) {
			$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true); 
		}	else {
			$info="Stat was added";
	}
} 
 
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
 
?>
