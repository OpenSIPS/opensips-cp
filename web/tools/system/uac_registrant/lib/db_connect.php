<?php
require_once("../../../../config/db.inc.php");
require_once("../../../../config/tools/".$branch."/".$module_id."/db.inc.php");

global $config;
if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_host']) && isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_user']) && isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_name']) ) {
	$config->db_host = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_host'];
	$config->db_port = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_port'];
	$config->db_user = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_user'];
	$config->db_pass = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_pass'];
	$config->db_name = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_name'];

	if (isset($config->db_port) && is_int((int)$config->db_port) && 1 < $config->db_port && $config->db_port < 65535) 
		$config->db_host = $config->db_host.";port=".$config->db_port;
}

$dsn = $config->db_driver . ':host=' . $config->db_host . ';dbname='. $config->db_name;
try {
	$link = new PDO($dsn, $config->db_user, $config->db_pass);
} catch (PDOException $e) {
	error_log(print_r("Failed to connect to: ".$dsn, true));
	print "Error!: " . $e->getMessage() . "<br/>";
	die;
}

?>
