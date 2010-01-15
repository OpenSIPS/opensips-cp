<?php
require_once("../../../../config/tools/admin/add_admin/db.inc.php");
require_once("../../../../config/db.inc.php");
	require_once("MDB2.php");

        global $config;
        if (isset($config->db_host_add_admin) && isset($config->db_user_add_admin) && isset($config->db_name_add_admin) ) {
                $config->db_host = $config->db_host_add_admin;
                $config->db_port = $config->db_port_add_admin;
                $config->db_user = $config->db_user_add_admin;
                $config->db_pass = $config->db_pass_add_admin;
                $config->db_name = $config->db_name_add_admin;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::connect($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
