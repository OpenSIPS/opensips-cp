<?php
/*
 * $Id$
 */ 

 //database host
 $config->db_host_drouting = "";
 
 //database port - leave empty for default
 $config->db_port_drouting = "";
 
 //database connection user
 $config->db_user_drouting = "";
 
 //database connection password
 $config->db_pass_drouting = "";
 
 //database name
 $config->db_name_drouting = "";
 
 if ($config->db_port_drouting != "") $config->db_host_drouting = $config->db_host_drouting . ":" . $config->db_port_drouting;
 
?>
