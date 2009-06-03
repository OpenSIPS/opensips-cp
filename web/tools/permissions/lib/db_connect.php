<?php
require_once("MDB2.php");
        global $config;
        if (!empty($config->db_host_permissions) && !empty($config->db_user_permissions) && !empty($config->db_name_permissions) ) {
                $config->db_host = $config->db_host_permissions;
                $config->db_port = $config->db_port_permissions;
                $config->db_user = $config->db_user_permissions;
                $config->db_pass = $config->db_pass_permissions;
                $config->db_name = $config->db_name_permissions;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::connect($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
