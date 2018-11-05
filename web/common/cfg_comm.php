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




function get_proxys_by_assoc_id($my_assoc_id){

	$global="../../../../config/boxes.global.inc.php";
	require($global);

	$mi_connectors=array();

	for ($i=0;$i<count($boxes);$i++){

		if ($boxes[$i]['assoc_id']==$my_assoc_id){

			$mi_connectors[]=$boxes[$i]['mi']['conn'];

		}

	}

	return $mi_connectors;
}


function get_all_proxys_by_assoc_id($my_assoc_id){

	$global="../../../../config/boxes.global.inc.php";
	require($global);

	$mi_connectors=array();

	for ($i=0;$i<count($boxes);$i++){

		if ($boxes[$i]['assoc_id']==$my_assoc_id){

			$mi_connectors[]=$boxes[$i]['mi']['conn'];

		}

	}

	return $mi_connectors;
}


function get_priv($my_tool) {

		$modules = get_modules();

		foreach($modules as $mod_key=>$mod_value) {
			foreach($mod_value as $key=>$value)
				$all_tools[$key] = $value;
		}

		if($_SESSION['user_tabs']=="*") {
			foreach ($all_tools as $lable=>$val)
				$available_tabs[]=$lable;
		} else {
			$available_tabs=explode(",",$_SESSION['user_tabs']);
		}

		if ($_SESSION['user_priv']=="*") {
			$_SESSION['read_only'] = false;
			$_SESSION['permission'] = "Read-Write";
		} else {
			$available_privs=explode(",",$_SESSION['user_priv']);
			if( ($key = array_search($my_tool, $available_tabs))!==false) {
				if ($available_privs[$key]=="read-only"){
					$_SESSION['read_only'] = true;
					$_SESSION['permission'] = "Read-Only";
				}
				if ($available_privs[$key]=="read-write"){
					$_SESSION['read_only'] = false;
					$_SESSION['permission'] = "Read-Write";
				}
			} else {
				$_SESSION['permission'] = "No permissions";
				require("template/header.php");
				print("<b>You do not have permissions to access this tool</b>");
				exit();
			}
		}

		return;

}


function get_modules() {
	require("../../../../config/modules.inc.php");
	$modules = array();
	foreach ($config_modules as $tool => $tool_config) {
		if (!$tool_config['enabled'])
			continue;
		$tool_array = array();
		foreach ($tool_config['modules'] as $module => $module_config) {
			if (!$module_config['enabled'])
				continue;
			$tool_array[$module] = $module_config['name'];
		}
		if (sizeof($tool_array) > 0)
			$modules[$tool_config['name']] = $tool_array;
	}
	return $modules;
}


function inspect_config_mi(){
	global $opensips_boxes ;
	global $box_count ;
	$a=0; $b=0 ;

	$global='../../../../config/boxes.global.inc.php';
	require ($global);

	$my_mis = array();

	foreach ( $boxes as $ar ) {

		$mi_url=$ar['mi']['conn'];

		if (!empty($mi_url)){

			$b++ ;

			if ( in_array( $mi_url , $my_mis) ) {
				echo "Re-usage of MI URL $mi_url in box ".$ar['desc']." in $global " . "<br>" ;
				echo "MI URLs must be uniques"."<br>" ;
				exit();
			}

			$my_mis[] = $mi_url;

			$boxlist[$ar['mi']['conn']]=$ar['desc'];
		}

	}

	$box_count=$b;

	return $boxlist;
}


function print_back_button() {
	$previous = "javascript:history.go(-1)";
	if(isset($_SERVER['HTTP_REFERER'])) {
		$previous = strtok($_SERVER['HTTP_REFERER'],'?');
	}
	echo("<form method=\"get\" action=\"$previous\"><button class=\"formButton\" type=\"submit\">Back</button></form>");
}

function print_back_input() {
	$previous = "javascript:history.go(-1)";
	if(isset($_SERVER['HTTP_REFERER'])) {
		$previous = strtok($_SERVER['HTTP_REFERER'],'?');
	}
	echo("<input onclick=\"window.location.href='$previous';\" class=\"formButton\" value=\"Back\" type=\"button\"/>");
}


?>
