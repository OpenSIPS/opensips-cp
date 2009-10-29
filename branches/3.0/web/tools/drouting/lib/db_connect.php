<?php
require_once("../../../config/tools/drouting/db.inc.php");
require_once("../../../config/db.inc.php");
	require_once("MDB2.php");
        global $config;
        if (isset($config->db_host_drouting) && isset($config->db_user_drouting) && isset($config->db_name_drouting) ) {
                $config->db_host = $config->db_host_drouting;
                $config->db_port = $config->db_port_drouting;
                $config->db_user = $config->db_user_drouting;
                $config->db_pass = $config->db_pass_drouting;
                $config->db_name = $config->db_name_drouting;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::factory($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
