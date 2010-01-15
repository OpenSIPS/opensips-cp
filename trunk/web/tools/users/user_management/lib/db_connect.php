<?php
require_once("../../../../config/tools/users/user_management/db.inc.php");
require_once("../../../../config/db.inc.php");
require_once("MDB2.php");

        global $config;
        if (isset($config->db_host_user_management) && isset($config->db_user_user_management) && isset($config->db_name_user_management) ) {
                $config->db_host = $config->db_host_user_management;
                $config->db_port = $config->db_port_user_management;
                $config->db_user = $config->db_user_user_management;
                $config->db_pass = $config->db_pass_user_management;
                $config->db_name = $config->db_name_user_management;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::connect($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
