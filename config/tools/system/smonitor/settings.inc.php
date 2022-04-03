<?php
/*
 * Copyright (C) 2022 OpenSIPS Solutions
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

$config->smonitor = array(
	"title0" => array(
		"type" => "title",
		"title" => "General settings"
	),
	"sampling_time" => array(
		"default" => 1,
		"name"	=> "Sampling Time",
		"type"	=> "number",
		"validation_regex" => "^[0-9]+$",
		"tip"	 => "Default value for the sampling interval (in minutes)"
	),
	"chart_size" => array(
		"default" => 100,
		"name"	=> "Chart Size",
		"type"	=> "number",
		"validation_regex" => "^[0-9]+$",
		"tip"	 => "The horizontal size of the charts (in hours)"
	),
	"chart_history" => array(
		"default" => 'auto',
		"name"	=> "Chart History",
		"type"	=> "text",
		"validation_regex" => "^(auto|[0-9]+)$",
		"tip"	 => "Amount of time (in hours) to keep samples before deleting them"
	),
	"groups" => array(
		"default" => "",
		"name" => "Groups for multi-line charts",
		"type" => "json",
		"example" => "
\"group1\": {
	\"stats\": {
		\"0\": \"load:load\",
		\"1\": \"shmem:fragments\"
	},
	\"scale\": 2
}"
	),
	"charting_url" => array(
		"default" => "https://d3js.org/d3.v4.js",
		"name"	=> "Charting library URL",
		"tip"	=> "URL to d3 charting library version",
		"type"	=> "text"
	),

	"title1" => array(
		"type" => "title",
		"title" => "DB settings"
	),
	"table_monitored" => array(
		"default" => "ocp_monitored_stats",
		"name"	=> "Table Monitored",
		"type"	=> "text",
		"validation_regex" => null,
		"tip"	 => "Database table name for storing the monitoring data"
	),
	"table_monitoring" => array(
		"default" => "ocp_monitoring_stats",
		"name"	=> "Table monitoring",
		"type"	=> "text",
		"validation_regex" => null,
		"tip"	 => "Database table name for storing the monitoring data"
	),

	"title2" => array(
		"type" => "title",
		"title" => "Display settings"
	),
	"tabs" => array(
		"default" => "rt_stats.php,charts.php",
		"name"	=> "Tabs",
		"options" => array("Realtime Statistics" => "rt_stats.php", "Statistics Charts" => "charts.php"),
		"tip"	=> "List of available tabs for smonitor tool",
		"type"	=> "checklist"
	),
);
