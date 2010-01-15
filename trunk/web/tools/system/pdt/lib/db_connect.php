<?php
require_once("../../../../config/tools/system/pdt/db.inc.php");
require_once("../../../../config/db.inc.php");
require_once("MDB2.php");

        global $config;
        if (isset($config->db_host_pdt) && isset($config->db_user_pdt) && isset($config->db_name_pdt) ) {
                $config->db_host = $config->db_host_pdt;
                $config->db_port = $config->db_port_pdt;
                $config->db_user = $config->db_user_pdt;
                $config->db_pass = $config->db_pass_pdt;
                $config->db_name = $config->db_name_pdt;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::connect($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
