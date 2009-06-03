<?php
/*
 * $Id$
 */ 

 //database host
 $config->db_host_permissions = "";
 
 //database port - leave empty for default
 $config->db_port_permissions = "";
 
 //database connection user
 $config->db_user_permissions = "";
 
 //database connection password
 $config->db_pass_permissions = "";
 
 //database name
 $config->db_name_permissions = "";
 
 if ($config->db_port_permissions != "") $config->db_host_permissions = $config->db_host_permissions . ":" . $config->db_port_permissions;
 
?>
