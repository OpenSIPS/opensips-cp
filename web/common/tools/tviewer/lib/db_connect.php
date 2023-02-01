<?php
$module_id = $_SESSION['module_id'];
$branch = $_SESSION['branch'];
$db_config_submenu = "db_config_" . $_SESSION[$module_id]['submenu_item_id'];

$configuration = null;
if (get_settings_value_from_tool($db_config_submenu, $module_id))
	$configuration = get_settings_value_from_tool($db_config_submenu, $module_id);
elseif (get_settings_value_from_tool("db_config", $module_id))
	$configuration = get_settings_value_from_tool("db_config", $module_id);
else
	$configuation = null;

if ($configuration) {
	if (!isset($_SESSION['db_config']))
		load_db_config();
	$configuration = $_SESSION['db_config'][$configuration];
}

require_once("../../../../config/db.inc.php");
require_once("../../../../config/tools/".$branch."/".$module_id."/db.inc.php");

global $config;
if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_host']) && isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_user']) && isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_name']) ) {
	$config->db_host = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_host'];
	$config->db_port = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_port'];
	$config->db_user = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_user'];
	$config->db_pass = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_pass'];
	$config->db_name = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_name'];
	$config->db_attr = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_attr'];

	if (isset($config->db_port) && is_int((int)$config->db_port) && 1 < $config->db_port && $config->db_port < 65535) 
		$config->db_host = $config->db_host.";port=".$config->db_port;
} else if ($configuration && isset($configuration["db_host"]) && isset($configuration["db_user"]) && isset($configuration["db_name"])) {
	$config->db_host = $configuration['db_host'];
	$config->db_user = $configuration['db_user'];
	$config->db_pass = isset($configuration['db_pass'])?$configuration['db_pass']:'';
	$config->db_name = $configuration['db_name'];
	$config->db_attr = isset($configuration['db_attr'])?$configuration['db_attr']:NULL;

	if (isset($configuration->db_port) && is_int((int)$configuration->db_port) && 1 < $configuration->db_port && $configuration->db_port < 65535) 
		$config->db_host = $config->db_host.";port=".$configuration->db_port;
}

$dsn = $config->db_driver . ':host=' . $config->db_host . ';dbname='. $config->db_name;
try {
	$link = new PDO($dsn, $config->db_user, $config->db_pass, isset($config->db_attr)?$config->db_attr:NULL);
} catch (PDOException $e) {
	error_log(print_r("Failed to connect to: ".$dsn, true));
	print "Error!: " . $e->getMessage() . "<br/>";
	die;
}

?>
