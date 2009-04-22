<?php
/*
 * $Id$
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

 //database tables
 $config->table_gateways = "dr_gateways";
 $config->table_groups = "dr_groups";
 $config->table_rules = "dr_rules";
 $config->table_lists = "dr_gw_lists";
 
 $talk_to_this_assoc_id = 1 ;

?>
