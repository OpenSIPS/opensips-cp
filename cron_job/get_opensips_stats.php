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
require("../../../../config/tools/system/smonitor/local.inc.php");
require("lib/functions.inc.php");
require("../../../../web/common/mi_comm.php");
require("../../../../config/boxes.global.inc.php");
require("lib/db_connect.php");


foreach ($boxes as $idx => $ar){

	if ($ar['smonitor']['charts']==1){
		$time=time();
		$sampling_time=get_config_var('sampling_time',$idx);

		// Get the name of the needed statistics
		$sql = "SELECT * FROM ".$config->table_monitored." WHERE extra='' AND box_id=? ORDER BY name ASC";
		$stm = $link->prepare($sql);
		if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
		}
		$stm->execute( array($idx) );
		$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

		// Compile the list and fetch them via MI
		$stats_name = "";
		for ($i=0;count($resultset)>$i;$i++){
			$arr = explode( ":", $resultset[$i]['name'] );
			// some stats name may contain ':', so better simply trim out the name of the module
			$stats_name = $stats_name.($i==0?"":" ").substr( $resultset[$i]['name'] , 1+strlen($arr[0]));
		}
		if ($stats_name == "")
			return;

		$stats = get_all_vars( $ar['mi']['conn'] , $stats_name );

		// insert values into DB
		preg_match_all("/(.+):: ([0-9]*)/i", $stats, $regs);

		$sql = "INSERT INTO ".$config->table_monitoring." (name,value,time,box_id) VALUES (?,?,?,?)";
		$stm = $link->prepare($sql);
                if ($stm === false) {
			die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
               	}

		for ($i=0;count($regs[0])>$i;$i++) {
			$var_value=$regs[2][$i];
			if ($var_value==NULL)
				$var_value="0";
			if ($stm->execute( array($regs[1][$i],$var_value,$time,$idx) ) == false ) {
				error_log("Insert query failed :".print_r($stm->errorInfo(), true));
			}

		}
	}
}

?>
