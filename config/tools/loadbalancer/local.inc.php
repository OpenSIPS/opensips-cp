<?php
/*
 * $Id: local.inc.php 72 2009-07-03 12:13:58Z iulia_bublea $
 */ 

 $config->results_per_page = 10;
 $config->results_page_range = 5;
 
###############################################################################

 //database tables
 $config->table_lb = "load_balancer";
 
 $talk_to_this_assoc_id = 1 ;

 // sip proxy - ip:port
 $proxy_list=array("udp:192.168.2.133:5060","udp:127.0.0.1:5060");

 $config->reply_fifo_filename="webfifo_".rand();
 $config->reply_fifo_path="/tmp/".$config->reply_fifo_filename;


?>
