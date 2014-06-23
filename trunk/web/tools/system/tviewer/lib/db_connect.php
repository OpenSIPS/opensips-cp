<?php
require_once("../../../../config/db.inc.php");
require_once("../../../../config/tools/".$branch."/".$module_id."/db.inc.php");
require_once("MDB2.php");
        global $config;
        if (isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_host']) && isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_user']) && isset($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_name']) ) {
                $config->db_host = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_host'];
                $config->db_port = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_port'];
                $config->db_user = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_user'];
                $config->db_pass = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_pass'];
                $config->db_name = $custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['db_name'];
		if (isset($config->db_port) && is_int((int)$config->db_port) && 1 < $config->db_port && $config->db_port < 65535) 
			$config->db_host = $config->db_host.":".$config->db_port;
        }
	
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::connect($dsn);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
