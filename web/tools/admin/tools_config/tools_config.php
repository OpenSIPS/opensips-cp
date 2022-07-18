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
require("../../../../config/tools/admin/tools_config/db.inc.php");
require("../../../../config/tools/admin/tools_config/local.inc.php");
include("lib/db_connect.php");
require("../../../../config/globals.php");
$table=$config->table_tools_config; 
$current_page="current_page_tools_config";

csrfguard_validate();

unset($box_id);
if (isset($_GET['box_id'])) {
	$box_id = $_GET['box_id'];
	if ($box_id == '') $box_id = null;
}

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if ($action=="modify_params")
{
    if(!$_SESSION['read_only']){
        extract($_POST);
		$current_tool=$_GET['tool'];
        $tools_params=get_params();
		foreach($tools_params as $param => $attr) {
			if (isset($attr['validation_regex'])) {
				if (!preg_match("/".$attr['validation_regex']."/", $_POST[$param])) {
					die("Failed to validate input for ".$attr['name']);
				}
			}
		}
		if (is_null($box_id)) {
			foreach ($tools_params as $module=>$params) {
				if ($params['type'] == "title")
					continue;
				if ($params['type'] == "checklist") {
					$checklist_values = implode( ',', $_POST[$module]);
					if (is_null($checklist_values)) $checklist_values = "";
					$_POST[$module] = $checklist_values;
				}
				if ($params['type'] == "json") {
					$_POST[$module] = json_encode(json_decode($_POST[$module]));
				}
				if ($params['default'] == $_POST[$module]) {
					$sql = "DELETE FROM ".$table." where module=? and param=? and (box_id IS NULL OR box_id='')";
					$stm = $link->prepare($sql);
					if ($stm === false) {
						die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
					}
					if ($stm->execute( array( $current_tool, $module)) == false) {
						$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true); 
					}    else {
						$info="Admin credentials were modified";
					}
					continue;
				}
				$sql = "INSERT INTO ".$table." (module, param, value) VALUES (?,?,?) ON DUPLICATE KEY UPDATE module=?,param=?,value=?";
				$stm = $link->prepare($sql);
				if ($stm === false) {
					die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
				}
				if ($stm->execute( array( $current_tool, $module, $_POST[$module], $current_tool, $module, $_POST[$module])) == false) {
					$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true); 
				}    else {
					$info="Admin credentials were modified";
				}
			}
		} else {
			foreach ($tools_params as $module=>$params) {
				if ($params['type'] == "title")
					continue;
				if ($params['type'] == "checklist") {
					$checklist_values = implode( ',', $_POST[$module]);
					if (is_null($checklist_values)) $checklist_values = "";
					$_POST[$module] = $checklist_values;
				}
				$sql = "REPLACE INTO $table (module, param, value, box_id) VALUES (?,?,?,".$box_id.")";
				$stm = $link->prepare($sql);
				if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
				}
				if ($stm->execute( array( $current_tool, $module, $_POST[$module])) == false) {
					$errors= "Updating record in DB failed: ".print_r($stm->errorInfo(), true); 
				}    else {
					$info="Admin credentials were modified";
				}
			}
		}
		unset($_SESSION['config'][$current_tool]);
	}   else {
   		$errors= "User with Read-Only Rights";
   	} 

	header('Location: ../../'.get_tool_path($_SESSION['current_tool']).'/index.php');
}

if ($action=="edit_tools")
{   
    require("template/".$page_id.".edit_tools.php");
	require("template/footer.php");
	exit();
}
require("template/footer.php");
