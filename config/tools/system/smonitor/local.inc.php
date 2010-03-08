<?php
 /*
 * $Id: local.inc.php 40 2009-04-13 14:59:22Z iulia_bublea $
 */ 
 $config->sampling_time = 10; // minutes
 $config->chart_size = 100;
 $config->chart_history = "auto";

###############################################################################
# Attention : advanced options !!

 $config->table_monitored = "monitored_stats";

 $config->table_monitoring = "monitoring_stats";

 $config_type = "global";

 $gauge_arr=array("shmem:total_size","shmem:used_size","shmem:real_used_size",
  "shmem:max_used_size","shmem:free_size","shmem:fragments",
  "tm:inuse_transactions",
  "usrloc:registered_users","usrloc:location-users","usrloc:location-contacts",
  "registrar:max_expires","registrar:default_expire","registrar:max_contacts" );
 
?>
