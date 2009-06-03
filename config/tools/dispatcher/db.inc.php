<?php
/*
 * $Id$
 */ 

  //database host
 $config->db_host_dispatcher = "";
 
 //database port - leave empty for default
 $config->db_port_dispatcher = "";
 
 //database connection user
 $config->db_user_dispatcher = "";
 
 //database connection password
 $config->db_pass_dispatcher = "";
 
 //database name
 $config->db_name_dispatcher = "";
 
 if ($config->db_port_dispatcher != "") $config->db_host_dispatcher = $config->db_host_dispatcher .":" . $config->db_port_dispatcher;
 
?>
