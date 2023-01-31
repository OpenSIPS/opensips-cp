<?php
/*
 * Copyright (C) 2011 OpenSIPS Project
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


chdir("web/tools/system/smonitor");
require("../../../../config/db.inc.php");
require("../../../../web/common/cfg_comm.php");
session_load_from_tool("smonitor");
require("lib/functions.inc.php");
require("../../../../config/boxes.global.inc.php");
require("lib/db_connect.php");

$history = get_settings_value_from_tool("chart_history", "smonitor");
if ($history == "auto")
	$history = 3;
$history *= 24*60*60; # convert days to seconds


foreach ($boxes as $idx => $ar){

	if ($ar['smonitor']['charts']==1)
	{
		$time=time();
	
		$oldest_time = $time - $history;
		$sql = "DELETE FROM ".$config->table_monitoring." WHERE box_id=".$idx." and time<".$oldest_time;
		$resultset = $link->exec($sql);
	}
} 

?>
