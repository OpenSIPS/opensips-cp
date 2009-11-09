<?php
/*
 * $Id$
 */ 

 $config->results_per_page = 20;
 $config->results_page_range = 5;
 
###############################################################################

 //database tables
 $config->table_address = "address";
 
 $talk_to_this_assoc_id = 1 ;

 $config->reply_fifo_filename="webfifo_".rand();
 $config->reply_fifo_path="/tmp/".$config->reply_fifo_filename;


?>
