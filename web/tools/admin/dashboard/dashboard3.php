<?php 

show_graph("load:load", 0);

function show_graph($stat,$box_id){
  require_once("../../../common/cfg_comm.php");
  session_load_from_tool("smonitor", 0);
    $widget = $_GET['widget'];
	global $config;
	global $gauge_arr;
	$var = $stat;
	require("../../../../config/tools/system/smonitor/db.inc.php");
	require("../../../../config/db.inc.php");
	require("db_connect.php");

	$_SESSION['charting_url'] = $url;
	$_SESSION['full_stat'] = $var;
	$_SESSION['stat'] = str_replace(':', '', $stat);
	$_SESSION[str_replace(':', '', $stat)] = $row;
	$_SESSION['sampling_time'] = get_settings_value_from_tool("sampling_time", "smonitor", $box_id);
	$_SESSION['chart_size'] = get_settings_value_from_tool("chart_size", "smonitor",$box_id);
	$_SESSION['box_id_graph'] = $box_id;
	$_SESSION['chart_history'] = get_settings_value_from_tool("chart_history", "smonitor",$box_id);
	$_SESSION['tmonitoring'] = get_settings_value_from_tool("table_monitoring", "smonitor",$box_id);

		$_SESSION['normal'] = 1;
	

	require("lib/d3js.php");
}
    ?>