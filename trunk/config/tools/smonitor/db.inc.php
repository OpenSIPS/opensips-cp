<?php
/*
 * $Id$
 */ 

 //database host
 $config->db_host_smonitor = "";
 
 //database port - leave empty for default
 $config->db_port_smonitor = "";
 
 //database connection user
 $config->db_user_smonitor = "";
 
 //database connection password
 $config->db_pass_smonitor = "";
 
 //database name
 $config->db_name_smonitor = "";
 
 if ($config->db_port_smonitor != "") $config->db_host_smonitor = $config->db_host_smonitor . ":" . $config->db_port_smonitor;
 
?>
