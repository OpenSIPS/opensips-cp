<?php
require_once("../../../../config/tools/system/loadbalancer/db.inc.php");
require_once("../../../../config/db.inc.php");

	require_once("MDB2.php");
        global $config;
        if (isset($config->db_host_loadbalancer) && isset($config->db_user_loadbalancer) && isset($config->db_name_loadbalancer) ) {
                $config->db_host = $config->db_host_loadbalancer;
                $config->db_port = $config->db_port_loadbalancer;
                $config->db_user = $config->db_user_loadbalancer;
                $config->db_pass = $config->db_pass_loadbalancer;
                $config->db_name = $config->db_name_loadbalancer;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::connect($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
