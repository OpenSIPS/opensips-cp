<?php
        global $config;
        if (!empty($config->db_host_domains) && !empty($config->db_user_domains) && !empty($config->db_name_domains) ) {
                $config->db_host = $config->db_host_domains;
                $config->db_port = $config->db_port_domains;
                $config->db_user = $config->db_user_domains;
                $config->db_pass = $config->db_pass_domains;
                $config->db_name = $config->db_name_domains;
        }
        $dsn = $config->db_driver.'://' . $config->db_user.':'.$config->db_pass . '@' . $config->db_host . '/'. $config->db_name.'';
        $link = & MDB2::connect($dsn);
        $link->setFetchMode(MDB2_FETCHMODE_ASSOC);
        if(PEAR::isError($link)) {
            die("Error while connecting : " . $link->getMessage());
        }
?>
