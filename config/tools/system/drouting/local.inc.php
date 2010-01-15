<?php
/*
 * $Id: local.inc.php 72 2009-07-03 12:13:58Z iulia_bublea $
 */ 

 $config->results_per_page = 20;
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

 $config->reply_fifo_filename="webfifo_".rand();
 $config->reply_fifo_path="/tmp/".$config->reply_fifo_filename;

?>
