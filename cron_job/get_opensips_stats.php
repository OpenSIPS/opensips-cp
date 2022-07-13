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
require("../../../../web/common/mi_comm.php");
require("../../../../config/boxes.global.inc.php");
require("lib/db_connect.php");

$sampling_time=get_settings_value_from_tool('sampling_time', 'smonitor');
$table_monitored=get_settings_value_from_tool('table_monitored', 'smonitor');
$table_monitoring=get_settings_value_from_tool('table_monitoring', 'smonitor');
get_stats_classes();
$custom_stats = [];

foreach ($boxes as $idx => $ar){
	if ($ar['smonitor']['charts']==1){
		$time=time();
		$id = $ar["id"];
		if (($time / 60) % $sampling_time == 0) {
			
			$sql = "SELECT * FROM ocp_extra_stats WHERE box_id=? ORDER BY name ASC";
			$stm = $link->prepare($sql);
			if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
			}
			$stm->execute( array($id) );
			$extra_stats = $stm->fetchAll(PDO::FETCH_ASSOC);

			// Get the name of the needed statistics
			$sql = "SELECT * FROM ".$table_monitored." WHERE box_id=? ORDER BY name ASC";
			$stm = $link->prepare($sql);
			if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
			}
			$stm->execute( array($id) );
			$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

			// Compile the list and fetch them via MI
			$stats_name = "";
			for ($i=0;count($resultset)>$i;$i++){
				$arr = explode( ":", $resultset[$i]['name'] );
				// some stats name may contain ':', so better simply trim out the name of the module
				if ($arr[0] == "custom") {
					$custom_stats[] = $arr[2];
				} else
					$stats_name = $stats_name.($i==0?"":" ").substr( $resultset[$i]['name'] , 1+strlen($arr[0]));
			}
			
			if ($stats_name == "")
				continue;

			$stats = get_all_vars( $ar['mi']['conn'] , $stats_name );

			// insert values into DB
			preg_match_all("/(.+):: ([0-9]*)/i", $stats, $regs);

			$sql = "INSERT INTO ".$table_monitoring." (name,value,time,box_id) VALUES (?,?,?,?)";
			$stm = $link->prepare($sql);
					if ($stm === false) {
				die('Failed to issue query ['.$sql.'], error message : ' . print_r($link->errorInfo(), true));
					}

			for ($i=0;count($regs[0])>$i;$i++) {
				$var_value=$regs[2][$i];
				if ($var_value==NULL)
					$var_value="0";
				if ($stm->execute( array($regs[1][$i],$var_value,$time,$id) ) == false ) {
					error_log("Insert query failed :".print_r($stm->errorInfo(), true));
				}
			}
			
			foreach($extra_stats as $entry) {
				if (in_array($entry['name'], $custom_stats)) { // custom_stats are fetched from ocp_monitored_stats
					$temp_stat = new $entry['class'](json_decode($entry['input'], true));
					$stat_value = $temp_stat->get_statistics();
					if (!is_null($stat_value)) {
						if ($stm->execute( array("custom:".$entry['tool'].":".$entry['name'],$stat_value,$time,$id) ) == false ) {
							error_log("Insert query failed :".print_r($stm->errorInfo(), true));
						}
					}
				}
			}
		}
	}
}

?>
