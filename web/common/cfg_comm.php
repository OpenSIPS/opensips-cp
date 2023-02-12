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

	foreach ($boxes as $box) {

		if ($box['assoc_id']==$my_assoc_id){

			$mi_connectors[]=$box['mi']['conn'];

		}

	}

	return $mi_connectors;
}


function get_all_proxys_by_assoc_id($my_assoc_id){

	$global="../../../../config/boxes.global.inc.php";
	require($global);

	$mi_connectors=array();

	foreach ($boxes as $box) {

		if ($box['assoc_id']==$my_assoc_id){

			$mi_connectors[]=$box['mi']['conn'];

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
			$_SESSION['permission'] = "Admin";
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
				if ($available_privs[$key]=="admin"){
					$_SESSION['read_only'] = false;
					$_SESSION['permission'] = "Admin";
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

function get_assoc_id() {
	require_once("".__DIR__."/../../config/boxes.global.inc.php");
	$assoc_ids = array();
	if (isset($_SESSION['systems'])) {
		foreach( $_SESSION['systems'] as $el) {
			$assoc_ids[$el['name']] = $el['assoc_id'];
		}
	}
	return $assoc_ids;
}



function get_tool_name($tool = null, $group = null) {
	if ($tool == null)
		$tool = $_SESSION['current_tool'];
	if ($group == null)
		$group = $_SESSION['current_group'];
	require("../../../../config/modules.inc.php");
	if (isset($config_admin_modules) && isset($config_admin_modules[$_SESSION['current_tool']]))
		return $config_admin_modules[$tool];
	return $config_modules[$group]['modules'][$tool]['name'];
}

function get_group_name() {
	require("../../../../config/modules.inc.php");
	return $config_modules[$_SESSION['current_group']]['name'];
}

function get_tool_path($tool) {
	require("".__DIR__."/../../config/modules.inc.php");
	foreach ($config_modules as $group=>$group_attr) {
		foreach ($group_attr['modules'] as $module=>$module_attr) {
			if ($module != $tool)
			       continue;
			if (isset($module_attr["path"]))
				return $module_attr["path"];
			else
				return $group . "/" . $tool;
		}
	}
}

function get_group() {
	require("".__DIR__."/../../config/modules.inc.php");
	$tool = $_SESSION['current_tool'];
	foreach ($config_modules as $group=>$group_attr) {
		foreach ($group_attr['modules'] as $module=>$module_attr) {
			if ($module == $tool) return $group;
		}
	}
}

function load_widgets() {
	$tools = get_tools();
	$widgets = [];
	foreach ($tools as $tool => $group) {
		$files = glob('../../'.get_tool_path($tool).'/template/dashboard_widgets/*.php');
		foreach ($files as $file) {
			require_once($file);
			$file_name = basename($file);
			$wname = substr($file_name, 0, strlen($file_name) - 4);
			if ($wname::$ignore != 1)
				$widgets[] = $wname;
		}
	}

	return $widgets;
}

function display_settings_button($box_id=null) {
	if (file_exists(__DIR__."/../../config/tools/".get_tool_path($_SESSION['current_tool'])."/settings.inc.php") && $_SESSION['permission'] == 'Admin') {
		require(__DIR__."/../../config/tools/".get_tool_path($_SESSION['current_tool'])."/settings.inc.php");
		if (!is_null($config))
			if(is_null($box_id))
				echo("
					<td align=right style=\"border-bottom: 1px solid #ccc!important\">
						<a  onclick=\"top.frames['main_body'].location.href='../../admin/tools_config/tools_config.php?action=edit_tools';\" href=\"#\"   id=\"config_admin\"></a>
					</td 
				");    
			else 
				echo("
					<td align=right style=\"border-bottom: 1px solid #ccc!important\">
					<a  onclick=\"top.frames['main_body'].location.href='../../admin/tools_config/tools_config.php?action=edit_tools&box_id=$box_id';\" href=\"#\"   id=\"config_admin\"></a> 
					</td>
				");    
	}
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

function isAssoc(array $arr)
{
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
}

function get_params() {
	return get_params_from_tool($_SESSION['current_tool']);
}

function get_params_from_tool($current_tool) {
	require("".__DIR__."/../../config/tools/".get_tool_path($current_tool)."/settings.inc.php");
	return $config->$current_tool;
}

function get_boxes_params() {
	require(__DIR__."/../../config/tools/admin/boxes_config/settings.inc.php");
	return $config->boxes;
}

function get_system_params() {
	require(__DIR__."/../../config/tools/admin/boxes_config/settings.inc.php");
	return $config->systems;
}

function load_panels() {
	require("".__DIR__."/../tools/system/dashboard/lib/db_connect.php");
	require("".__DIR__."/../../config/tools/system/dashboard/settings.inc.php");
	unset($_SESSION['config']['panels']);
	$max_order = -1;
	$sql = 'select `name`, id, content, positions, `order` from ocp_dashboard';
	$stm = $link->prepare($sql);
	if ($stm === false) {
		die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
	}

	if ($stm->execute( array()) == false)
		echo('<tr><td align="center"><div class="formError">'.print_r($stm->errorInfo(), true).'</div></td></tr>');
	else {
		$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
		foreach ($resultset as $elem) {
			$max_widget_no = 0;
			$_SESSION['config']['panels'][$elem['id']]['name'] = $elem['name'];
			$_SESSION['config']['panels'][$elem['id']]['content'] = $elem['content'];
			$_SESSION['config']['panels'][$elem['id']]['positions'] = $elem['positions'];
			foreach (json_decode($elem['content']) as $widget_id => $widget) {
				$id_details = explode("_", $widget_id);
				if ($id_details[3] > $max_widget_no )
					$max_widget_no = $id_details[3]; // for creation of new ids
				$_SESSION['config']['panels'][$elem['id']]['widgets'][$widget_id]['content'] = $widget;
				foreach (json_decode($elem['positions']) as $el) {
					if ($el->id == $widget_id) {
						$_SESSION['config']['panels'][$elem['id']]['widgets'][$widget_id]['positions'] = json_encode($el);
					}
				}
			}
			$_SESSION['config']['panels'][$elem['id']]['order'] = $elem['order'];
			$_SESSION['config']['panels'][$elem['id']]['id'] = $elem['id'];
			$_SESSION['config']['panels'][$elem['id']]['widget_no'] = $max_widget_no;
			if ($elem['order'] > $max_order) $max_order = $elem['order'];
		}
	}
	$_SESSION['config']['panels_max_order'] = $max_order;
}

function get_db_configs() {
	$configs = array();
	if (isset($_SESSION['db_config'])) {
		foreach($_SESSION['db_config'] as $id => $configuration) {
			$configs[$configuration['config_name']] = $id;
		}
	}
	$configs["Default config"] = 0;
	return $configs;
}

function load_db_config() {
	require("".__DIR__."/../tools/admin/db_config/lib/db_connect.php");
	require("".__DIR__."/../../config/tools/admin/db_config/settings.inc.php");
	global $config;
	if (!isset($_SESSION['db_config'])) {
		$sql = 'select * from '.$config->table_db_config;
		$stm = $link->prepare($sql);
		if ($stm === false) {
						die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( array() );
		$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
		foreach($resultset as $elem) {
			$_SESSION['db_config'][$elem['id']] = $elem;
		}
	}
}

function get_settings_value_from_tool($current_param, $current_tool, $box_id = null) {
	require("".__DIR__."/../../config/tools/".get_tool_path($current_tool)."/settings.inc.php");
	if (is_null($box_id)){
		if (isset($_SESSION['config'][$current_tool][$current_param])){ 
			return $_SESSION['config'][$current_tool][$current_param];}}

	else {
		if (!is_null($_SESSION['config'][$current_tool][$box_id][$current_param])) {
			return $_SESSION['config'][$current_tool][$box_id][$current_param];}}
	foreach($config->$current_tool as $module=>$params) {
		if ($module == $current_param && $params['type'] != "title") return $params['default'];
	}

	return null;
}

function get_settings_value($current_param, $box_id = null) {
	$current_tool = $_SESSION['current_tool'];

	return get_settings_value_from_tool($current_param, $current_tool, $box_id);
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
				echo "Re-usage of MI URL $mi_url in box ".$ar['name']." in $global " . "<br>" ;
				echo "MI URLs must be uniques"."<br>" ;
				exit();
			}

			$my_mis[] = $mi_url;

			$boxlist[$ar['mi']['conn']]=$ar['name'];
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
	echo("<form method=\"get\" action=\"$previous?action=back\"><button class=\"formButton\" type=\"submit\">Back</button></form>");
}

function print_back_input() {
	$previous = "javascript:history.go(-1)";
	if(isset($_SERVER['HTTP_REFERER'])) {
		$previous = strtok($_SERVER['HTTP_REFERER'],'?');
	}
	echo("<input onclick=\"window.location.href='$previous?action=back';\" class=\"formButton\" value=\"Back\" type=\"button\"/>");
}

function session_load($box_id = null) {
	session_load_from_tool($_SESSION['current_tool'], $box_id);
}

function session_load_from_tool($tool, $box_id = null) {
	require("".__DIR__."/../tools/admin/tools_config/lib/db_connect.php");
	require("".__DIR__."/../../config/tools/admin/tools_config/local.inc.php");
	global $config;
	$table_tools_config = $config->table_tools_config;
	$module_params = get_params_from_tool($tool);
	if (!isset($_SESSION['config'][$tool])) {
		if (is_null($box_id)) {
			$sql = 'select param, value from '.$table_tools_config.' where module=? ';
			$stm = $link->prepare($sql);
			if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
			}
			if ($stm->execute( array($tool)) == false)
				echo('<tr><td align="center"><div class="formError">'.print_r($stm->errorInfo(), true).'</div></td></tr>');
			else {
				$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
				foreach ($resultset as $elem) {
					if ($module_params[$elem['param']]['type'] == "json") {
						$_SESSION['config'][$tool][$elem['param']] = json_decode($elem['value'], true);
					}
					else $_SESSION['config'][$tool][$elem['param']] = $elem['value'];
				}
			} 
		} else { 
			$sql = 'select param, value, box_id from '.$table_tools_config.' where module=? ';
			$stm = $link->prepare($sql);
			if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
			}
		
			if ($stm->execute( array($tool)) == false)
				echo('<tr><td align="center"><div class="formError">'.print_r($stm->errorInfo(), true).'</div></td></tr>');
			else {
				$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
				foreach ($resultset as $elem) {
					if ($module_params[$elem['param']]['type'] == "json") {
						$_SESSION['config'][$tool][$elem['box_id']][$elem['param']] = json_decode($elem['value'], true);
					}
					else $_SESSION['config'][$tool][$elem['box_id']][$elem['param']] = $elem['value'];
				}
			}
		} 
	}
	foreach ($module_params as $module=>$params) {
		$config->$module = get_settings_value_from_tool($module, $tool); 
	}
}


function get_tools() {
	require("../../../../config/modules.inc.php");
	$tools = [];
	foreach ($config_modules as $group => $modules) {
		if (!$modules['enabled'])
			continue;
		foreach ($modules['modules'] as $name => $attrs) {
			if (!$attrs['enabled'])
				continue;
			$tools[$name] = $group;
		}
	}
	return $tools;
}


function store_in_session($key,$value)
{
	if (isset($_SESSION))
		$_SESSION[$key]=$value;
}

function unset_session($key)
{
	$_SESSION[$key]=' ';
	unset($_SESSION[$key]);
}

function get_from_session($key)
{
	if (isset($_SESSION[$key]))
		return $_SESSION[$key];
	else
		return false;
}

function csrfguard_generate_token($unique_form_name)
{
	if( !function_exists('random_bytes') )
	{
		function random_bytes($length = 6)
		{
			$characters = '0123456789';
			$characters_length = strlen($characters);
			$output = '';
			for ($i = 0; $i < $length; $i++)
				$output .= $characters[rand(0, $characters_length - 1)];

			return $output;
		}
	}
	$token = bin2hex(random_bytes(64));
	store_in_session($unique_form_name,$token);
	return $token;
}

function csrfguard_validate_token($unique_form_name,$token_value)
{
	$token = get_from_session($unique_form_name);
	if (!is_string($token))
		return false;
	$result = ($token == $token_value);
	unset_session($unique_form_name);
	return $result;
}

function csrfguard_generate($prefix="")
{
	$name="CSRFGuard_".$prefix.mt_rand(0,mt_getrandmax());
	$token=csrfguard_generate_token($name);
	echo("<input type='hidden' name='CSRFName' value='{$name}' />
		<input type='hidden' name='CSRFToken' value='{$token}' />");
}

function csrfguard_validate($deny = true)
{
	if (count($_POST) == 0)
		return true;
	if (isset($_POST['CSRFName']) and isset($_POST['CSRFToken']) ) {
		$name = $_POST['CSRFName'];
		$token = $_POST['CSRFToken'];
		$valid = csrfguard_validate_token($name, $token);
	} else {
		$valid = false;
	}
	
	if ($valid == false && $deny == true) {
		//header("HTTP/1.1 401 Unauthorized");
		throw new ErrorException("401 Unauthorized");
		exit;
	}
	return $valid;
}


?>
