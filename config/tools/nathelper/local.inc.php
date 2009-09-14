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
 $config->table_nathelper = "nh_sockets";
 
 $talk_to_this_assoc_id = 1 ;

 // sip proxy - ip:port
 $proxy_list=array("udp:192.168.2.133:5060","udp:127.0.0.1:5060");

 $config->reply_fifo_filename = "webfifo_".rand();
 $config->reply_fifo_path = "/tmp/".$config->reply_fifo_filename;

?>
