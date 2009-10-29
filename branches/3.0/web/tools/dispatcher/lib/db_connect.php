<?php
require_once("../../../config/tools/dispatcher/db.inc.php");
require_once("../../../config/db.inc.php");
require_once("MDB2.php");

        global $config;
        if (isset($config->db_host_dispatcher) && isset($config->db_user_dispatcher) && isset($config->db_name_dispatcher) ) {
                $config->db_host = $config->db_host_dispatcher;
                $config->db_port = $config->db_port_dispatcher;
                $config->db_user = $config->db_user_dispatcher;
                $config->db_pass = $config->db_pass_dispatcher;
                $config->db_name = $config->db_name_dispatcher;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::connect($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
