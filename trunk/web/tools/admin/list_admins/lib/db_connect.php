<?php
require_once("../../../../config/tools/admin/list_admins/db.inc.php");
require_once("../../../../config/db.inc.php");
require_once("MDB2.php");

        global $config;
        if (isset($config->db_host_list_admins) && isset($config->db_user_list_admins) && isset($config->db_name_list_admins) ) {
                $config->db_host = $config->db_host_list_admins;
                $config->db_port = $config->db_port_list_admins;
                $config->db_user = $config->db_user_list_admins;
                $config->db_pass = $config->db_pass_list_admins;
                $config->db_name = $config->db_name_list_admins;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::connect($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
