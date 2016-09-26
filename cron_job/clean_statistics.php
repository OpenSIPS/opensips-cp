<?php
/*
 * $Id$
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
require("../../../../config/tools/system/smonitor/local.inc.php");
require("lib/functions.inc.php");
require("../../../../web/common/mi_comm.php");
require("../../../../config/boxes.global.inc.php");
require("lib/db_connect.php");


foreach ($boxes as $idx => $ar){

	if ($ar['smonitor']['charts']==1)
	{
		$time=time();
		$history=get_config_var('chart_history',$idx);
	
		if ($history=="auto") {
			$oldest_time = $time - 24*60*60*3 /*3 days in seconds */;
		} else {
			$oldest_time = $time - 24*60*60*$history;
		}
		$sql = "DELETE FROM ".$config->table_monitoring." WHERE box_id=".$idx." and time<".$oldest_time;
		$resultset = $link->exec($sql);
	}
} 

?>
