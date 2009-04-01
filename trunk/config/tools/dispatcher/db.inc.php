<?php
/*
 * $Id$
 */ 

 //database host
 $config->db_host = "localhost";
 
 //database port - leave empty for default
 $config->db_port = "";
 
 //database connection user
 $config->db_user = "opensips";
 
 //database connection password
 $config->db_pass = "opensipsrw";
 
 //database name
 $config->db_name = "opensips";
 
 if ($config->db_port != "") $config->db_host = $config->db_host . ":" . $config->db_port;
 
?>
