<?php
/*
 * $Id: local.inc.php 72 2009-07-03 12:13:58Z iulia_bublea $
 */ 

 $config->results_per_page = 20;
 $config->results_page_range = 5;
 
###############################################################################

 //database tables
 $config->table_dispatcher = "dispatcher";
 
 $talk_to_this_assoc_id = 1 ;
 $config->reply_fifo_filename="webfifo_".rand();
 $config->reply_fifo_path="/tmp/".$config->reply_fifo_filename;

 //status
 $config->status = array('A'=>'Active','I'=>'Inactive','P'=>'Probing');
?>
