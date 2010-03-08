<?php
/*
 * $Id$
 * Copyright (C) 2008-2010 Voice Sistem SRL
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for 
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


$path_to_smonitor="/var/www/opensips-cp/web/tools/system/smonitor";
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
		$history=get_config_var('chart_history',$box_id);
	
		if ($history=="auto") {
			$sampling_time=get_config_var('sampling_time',$box_id);
			$chart_size=get_config_var('chart_size',$box_id);
			$oldest_time = $time - 60*($sampling_time * $chart_size);
		} else {
			$oldest_time = $time - 24*60*60*$history;
		}
		$sql = "DELETE FROM ".$config->table_monitoring." WHERE box_id=".$box_id." and time<".$oldest_time);
		$resultset = $link->exec($sql);
		echo "DELETE FROM ".$config->table_monitoring." WHERE box_id=".$box_id." and time<".$oldest_time."\n";

	}
$box_id++;
} 

?>
