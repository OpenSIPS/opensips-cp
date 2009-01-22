<?php
 /*
 * $Id: local.inc.php,v 1.3 2008-01-11 13:58:30 dragos Exp $
 */ 
 $config->sampling_time = 10; // minutes
 $config->chart_size = 100;
 $config->chart_history = "auto";

###############################################################################
# Attention : advanced options !!

 $config->fifo_server = "/tmp/opensips_fifo";
 $config->reply_fifo_filename = "webfifo_".rand();
 $config->reply_fifo_path = "/tmp/".$config->reply_fifo_filename;

 $config->table_monitored = "monitored_stats";

 $config->table_monitoring = "monitoring_stats";


 $gauge_arr=array("shmem:total_size","shmem:used_size","shmem:real_used_size",
  	"shmem:max_used_size","shmem:free_size","shmem:fragments",
	"tm:inuse_transactions",
	"usrloc:registered_users","usrloc:location-users","usrloc:location-contacts",
	"registrar:max_expires","registrar:default_expire","registrar:max_contacts" );
 
?>
