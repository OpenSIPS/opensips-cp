<?php
/*
 * $Id: local.inc.php,v 1.3 2007-04-19 14:27:57 bogdan Exp $
 */ 

 $config->results_per_page = 10;
 $config->results_page_range = 5;
 
 # Gateways
 // default gateway type
 $config->default_gw_type = 1;
 
 
 # Rules
 // "static" (from file) or "dynamic" (from table) group ids 
 $config->group_id_method = "static";
 
 
 # Groups
 // default domain
 $config->default_domain = "yourdomain.net";

###############################################################################
# Attention : advanced options !!

 //database tables
 $config->table_gateways = "dr_gateways";
 $config->table_groups = "dr_groups";
 $config->table_rules = "dr_rules";
 
 $talk_to_this_assoc_id = 1 ;

?>
