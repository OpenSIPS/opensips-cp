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

session_start();
require_once("../../cfg_comm.php");
$module_id = isset($_GET['module_id'])?$_GET['module_id']:$_SESSION['module_id'];
get_priv($module_id);
require("template/header.php");
require("lib/".$page_id.".main.js");

$current_page="current_page_tviewer";

include("lib/db_connect.php");

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;
if (!isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table']) || $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table'] == ""){
	echo "<font color='red'>THIS MODULE HAS NOT BEEN CONFIGURED YET - PLEASE UPDATE CONFIG FILE:</font> <br> <b> config/tools/".$branch."/".$module_id."/tviewer.inc.php<b>";
	exit();
}
else {
	$table=$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table'];
}

##############################
# get current action         #
##############################
if (isset($_POST['action'])) 
	$action=$_POST['action'];
else if (isset($_GET['action'])) 
	$action=$_GET['action'];
else {
	$action="";
	unset($_GET);
	unset($_POST);
	foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $key => $value)
	        unset($_SESSION[$key]);
}

##############################
# end get current action     #
##############################


##########################################
# include custom columns action scripts  #
##########################################

if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns']) {
	for ($i=0; $i<count($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns']); $i++) {
		if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$i]['action_script']) && $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$i]['action_script']!="" && file_exists($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$i]['action_script']))
			require($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_columns'][$i]['action_script']);
	}
}

##############################################
# end include custom columns action scripts  #
##############################################



##########################################
# include custom buttons action scripts  #
##########################################

if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'])) {
	for ($i=0; $i<count($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons']); $i++) {
		if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'][$i]['action_script']) && $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'][$i]['action_script']!="" && file_exists($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'][$i]['action_script']))
			require($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_action_buttons'][$i]['action_script']);
	}
}



######################################
# include custom search - if enabled #
######################################

if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_search']['enabled']) &&
		$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_search']['enabled'])
	require($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_search']['action_script']);

##############
# start main #
##############

require("template/".$page_id.".main.php");
require("template/footer.php");
exit();

##############
# end main   #
##############
?>
