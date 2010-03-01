<?php
$path_to_smonitor="/var/www/pmwiki/opensips-cp/web/tools/system/smonitor";
chdir($path_to_smonitor);
require("../../../../config/db.inc.php");
require("../../../../config/tools/system/smonitor/local.inc.php");
require("lib/functions.inc.php");
require("../../../../web/common/mi_comm.php");
require("../../../../config/boxes.global.inc.php");
require("lib/db_connect.php");


$box_id=0;

$xmlrpc_host=""; 
$xmlrpc_port=""; 
$fifo_file=""; 
$comm_type="";

foreach ($boxes as $ar){

	if ($ar['smonitor']['charts']==1)
	{
		$time=time();
		$sampling_time=get_config_var('sampling_time',$box_id);

	//	if (date("i",$time) % $sampling_time == 0)
		{
			$comm_type=params($ar['mi']['conn']); 		
 			$stats=get_all_vars();
	
			$sql = "SELECT * FROM ".$config->table_monitored." WHERE extra='' AND box_id=".$box_id." ORDER BY name ASC";
			$resultset = $link->queryAll($sql);
			if(PEAR::isError($resultset))
		                die('Failed to issue query, error message : ' . $resultset->getMessage());
 			for ($i=0;count($resultset)>$i;$i++)
 				{
  					$var_name=$resultset[$i]['name'];
  					preg_match("/".$var_name." = ([0-9]*)/i", $stats, $regs);
  					$var_value=$regs[1];
  					if ($var_value==NULL) $var_value="0"; 
	    			$sql = "INSERT INTO ".$config->table_monitoring." (name,value,time,box_id) VALUES ('".$var_name."','".$var_value."','".$time."',".$box_id.")";
				$result = $link->prepare($sql);
				$result->execute();
				$result->free();
 				}

	}
	
	}
$box_id++;
} 

?>
