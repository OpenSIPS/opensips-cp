<?php
$module_id = $_SESSION['module_id'];
$branch = $_SESSION['branch'];
$db_config_submenu = "db_config_" . $_SESSION[$module_id]['submenu_item_id'];

require_once("../../../../config/tools/".$branch."/".$module_id."/db.inc.php");
require_once(__DIR__."/../../../db_pdo.php");

global $config;
$db = isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]) ?
	$custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']] : NULL;
if (!isset($db['db_host'], $db['db_user'], $db['db_name']))
	$db = db_tool_settings($module_id,
		get_settings_value_from_tool($db_config_submenu, $module_id) ? $db_config_submenu : "db_config");
$link = db_connect($db);

?>
