<?php

$opensips_path="/var/www/opensips-cp/";
require($opensips_path."config/tools/smonitor/db.inc.php");
require($opensips_path."config/db.inc.php");
require($opensips_path."config/tools/smonitor/local.inc.php");
require($opensips_path."web/tools/smonitor/lib/functions.inc.php");
require($opensips_path."web/common/mi_comm.php");
require($opensips_path."config/boxes.global.inc.php");
require($opensips_path."web/tools/smonitor/lib/db_connect.php");


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
		$sampling_time=get_config_var('sampling_time',$box_id);

		if (date("i",$time) % $sampling_time == 0)
		{
			$comm_type=params($ar['mi']['conn']); 		
 			$stats=get_all_vars();
	
			$sql = "SELECT * FROM ".$config->table_monitored." WHERE extra='' AND box_id=".$box_id." ORDER BY name ASC";
			$resultset = $link->queryAll($sql);
			if(PEAR::isError($resultset))
		                die('Failed to issue query, error message : ' . $resultset->getMessage());
 			echo "SELECT * FROM ".$config->table_monitored." WHERE extra='' AND box_id=".$box_id." ORDER BY name ASC\n";			
 			for ($i=0;$count($resultset)>$i;$i++)
 				{
  					$var_name=$resultset[$i]['name'];
  					preg_match("/".$var_name." = ([0-9]*)/i", $stats, $regs);
  					$var_value=$regs[1];
  					if ($var_value==NULL) $var_value="0"; 
	    			$sql = "INSERT INTO ".$config->table_monitoring." (name,value,time,box_id) VALUES ('".$var_name."','".$var_value."','".$time."',".$box_id.")";
				$result = $link->prepare($sql);
				$result->execute();
				$result->free();
 				echo "INSERT INTO ".$config->table_monitoring." (name,value,time,box_id) VALUES ('".$var_name."','".$var_value."','".$time."',".$box_id.")\n";
 				}

 		echo("* ");
	}
	
	}
$box_id++;
} 

?>
