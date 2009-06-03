<?php
/*
 * $Id$
 */ 

 //database host
 $config->db_host_nathelper = "";
 
 //database port - leave empty for default
 $config->db_port_nathelper = "";
 
 //database connection user
 $config->db_user_nathelper = "";
 
 //database connection password
 $config->db_pass_nathelper = "";
 
 //database name
 $config->db_name_nathelper = "";
 
 if ($config->db_port_nathelper != "") $config->db_host_nathelper = $config->db_host_nathelper . ":" . $config->db_port_nathelper;
 
?>
