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
 require("../../../common/mi_comm.php");
 require("../../../../config/tools/system/smonitor/db.inc.php");
 require("../../../../config/db.inc.php");
 require("lib/functions.inc.php");
 require("lib/functions.inc.js");
 require("template/header.php");
 
 session_load();
 
 csrfguard_validate();

 $stat_classes = get_stats_classes();
 $current_page="current_statistics";


 $table_monitoring=get_settings_value("table_monitoring");
 $table_monitored=get_settings_value("table_monitored");
 
 include("lib/db_connect.php");

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;

if ($action == "import_statistic") {
	$stat_class = $_GET['class'];
    require("template/".$page_id.".import_stat.php");
	require("template/footer.php");
	exit();
}

if ($action == "edit_statistic") {
	$stat_id = $_GET['stat_id'];
	$stat_class = $_GET['class'];
	require("template/".$page_id.".edit.php");
	require("template/footer.php");
	exit();
}
 
if ($action == "delete") {
	$stat_id = $_GET['stat_id'];

	$sql = "DELETE from ".$table_monitored." where name = (SELECT CONCAT('custom:', tool, ':',name) from ocp_extra_stats where id = ?)";
	$stm = $link->prepare($sql);
	if ($stm === false) {
	die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	if ($stm->execute( array( $stat_id)) == false) {
		die("Updating record in DB failed: ".print_r($stm->errorInfo(), true)); 
	}	else {
		$info="Stat was deleted";
	}

	$sql = "DELETE from ocp_extra_stats where id = ?;";
		$stm = $link->prepare($sql);
		if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		if ($stm->execute( array( $stat_id)) == false) {
			die("Updating record in DB failed: ".print_r($stm->errorInfo(), true)); 
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
	
	$form_input = $_POST;
	unset($form_input["CSRFName"]);
	unset($form_input["CSRFToken"]);
	unset($form_input["save"]);

	$input = json_encode($form_input);
	
	$sql = "REPLACE INTO ocp_extra_stats (`name`, `input`, `tool`, `class`, box_id) VALUES (?,?,?,?,?);";
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		
		if ($stm->execute( array( $_POST['name_id'], $input, $stat_class::get_tool(), $stat_class, $_SESSION['box_id'])) == false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($stm->errorInfo(), true));
		}	else {
			$info="Stat was added";
	}
}
 
if ($action == "modify_statistic") { 
	$id = $_GET['id'];
	$form_input = $_POST;
	unset($form_input["CSRFName"]);
	unset($form_input["CSRFToken"]);
	unset($form_input["save"]);

	$input = json_encode($form_input);
	
	$sql = "UPDATE ocp_monitoring_stats SET name= CONCAT('custom:', (SELECT tool from ocp_extra_stats where id = ?), ':', ?) where name=  
	(SELECT CONCAT('custom:', tool, ':',name) from ocp_extra_stats where id = ?)";
	$stm = $link->prepare($sql);
	if ($stm === false) {
	die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	
	if ($stm->execute( array($id, $_POST['name_id'], $id)) == false) {
		die("Updating record in DB failed: ".print_r($stm->errorInfo(), true)); 
	}	else {
		$info="Stat was added";
	}

	$sql = "UPDATE ocp_monitored_stats SET name= CONCAT('custom:', (SELECT tool from ocp_extra_stats where id = ?), ':', ?) where name=  
	(SELECT CONCAT('custom:', tool, ':',name) from ocp_extra_stats where id = ?)";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	
	if ($stm->execute( array($id, $_POST['name_id'], $id)) == false) {
		die("Updating record in DB failed: ".print_r($stm->errorInfo(), true)); 
	}	else {
		$info="Stat was added";
	}

	$sql = "UPDATE ocp_extra_stats SET `name`=?, `input`=? where id = ?";
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}
	
	if ($stm->execute( array( $_POST['name_id'], $input, $id)) == false) {
		die("Updating record in DB failed: ".print_r($stm->errorInfo(), true)); 
	}	else {
		$info="Stat was added";
	}
} 
 
 require("template/".$page_id.".main.php");
 require("template/footer.php");
 exit();
 
?>
