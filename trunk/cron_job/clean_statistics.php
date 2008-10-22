<?php

$openser_path="/var/www/opensips-cp/";
require($openser_path."config/tools/smonitor/db.inc.php");
require($openser_path."config/tools/smonitor/local.inc.php");
require($openser_path."web/tools/smonitor/lib/functions.inc.php");
require($openser_path."web/common/mi_comm.php");
require($openser_path."config/boxes.global.inc.php");


$box_id=0;

$xmlrpc_host=""; 
$xmlrpc_port=""; 
$fifo_file=""; 
$comm_type="";

foreach ($boxes as $ar){

	if ($ar['smonitor']['charts']==1)
	{
		$time=time();
		db_connect();
		$history=get_config_var('chart_history',$box_id);

		if ($history=="auto") {
			$sampling_time=get_config_var('sampling_time',$box_id);
			$chart_size=get_config_var('chart_size',$box_id);
			$oldest_time = $time - 60*($sampling_time * $chart_size);
		} else {
			$oldest_time = $time - 24*60*60*$history;
		}
		mysql_query("DELETE FROM ".$config->table_monitoring." WHERE box_id=".$box_id." and time<".$oldest_time) or die(mysql_error());
		echo "DELETE FROM ".$config->table_monitoring." WHERE box_id=".$box_id." and time<".$oldest_time."\n";

		db_close();
	}
$box_id++;
} 

?>
