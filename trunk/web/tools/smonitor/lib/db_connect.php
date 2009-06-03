<?php
require_once("MDB2.php");
require_once("../../../config/tools/smonitor/db.inc.php");
require_once("../../../config/db.inc.php");
        global $config;
        if (!empty($config->db_host_smonitor) && !empty($config->db_user_smonitor) && !empty($config->db_name_smonitor) ) {
                $config->db_host = $config->db_host_smonitor;
                $config->db_port = $config->db_port_smonitor;
                $config->db_user = $config->db_user_smonitor;
                $config->db_pass = $config->db_pass_smonitor;
                $config->db_name = $config->db_name_smonitor;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::connect($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
