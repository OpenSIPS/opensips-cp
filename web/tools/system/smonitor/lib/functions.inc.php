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


######################
# Database Functions #
######################
//require('../../common/mi_comm.php');
include("db_connect.php");
require_once("../../../../config/db.inc.php");
require_once("../../../../config/tools/system/smonitor/db.inc.php");

function get_config_var($var_name,$box_id)
{
	include("db_connect.php");
	global $config;

	$sql="SELECT * FROM ".get_settings_value("table_monitored")." WHERE name = ? AND box_id = ?";
	$stm = $link->prepare($sql);
	if ($stm->execute(array($var_name, $box_id)) == false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);

	$value=$resultset[0]['extra'];
	if ($value==null) $value=$config->$var_name;
	return $value;
}

function get_mi_modules($mi_url)
{
	global $config;
 
	$message=mi_command("get_statistics", array("statistics"=>array("all")), $mi_url, $errors);
	if (!empty($errors))
		return;

	ksort($message);

	$temp = array();
	foreach ($message as $module_stat => $value){
		$temp [] = $module_stat.":: ".$value;
	}
	$message = implode("\n",$temp);

	preg_match_all("/(.*?):(.*?):: ([0-9]*)/i",$message,$regs);

	$modules=array();
	$a=0; $j=0 ;
	$modules[0][$a]=$regs[1][0];

	for ($i=0;$i<sizeof($regs[0])+1;$i++){
		
		if ($modules[0][$a]!=$regs[1][$i]){
		    $modules[1][$a]=$j ; 
		    $a++ ; 
		    $modules[0][$a]=$regs[1][$i] ;
		    $j=0;	
		} 
		
		$j++;
		
	}
        $_SESSION['modules_no']=count($modules[0]) - 1 ;
        for ($i=0; $i<(count($modules[0])) && (!empty($modules[0][$i])); $i++)
        {
	 $_SESSION['module_name'][$i] = $modules[0][$i];
         $_SESSION['module_vars'][$i] = $modules[1][$i];
         $_SESSION['module_open'][$i] = "no";
        }
 return;
}


function get_custom_modules()
{
	include("db_connect.php");

	$sql_command="select * from ocp_extra_stats ORDER BY id;";
	$stm = $link->prepare( $sql_command );
	if ($stm===FALSE)
	       die('Failed to issue query ['.$sql_command.'], error message : ' . print_r($link->errorInfo(), true));
	$stm->execute();
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	$modules = $resultset;
	$module_counter = [];

	foreach($modules as $module) {
		if (isset($module_counter[$module['tool']]))
			$module_counter[$module['tool']]++;
		else $module_counter[$module['tool']] = 1;
	}
	$_SESSION['custom_modules_no']=count($module_counter) ;
	$i = 0;
	foreach ($module_counter as $module => $count)
	{	
		$_SESSION['custom_module_name'][$i] = $module;
		$_SESSION['custom_module_vars'][$i] = $count;
		$_SESSION['custom_module_open'][$i] = "no";
		$i++;
	}
	return;
}

function get_custom_vars($tool, $box_id)
{
	include("db_connect.php");

	$sql_command="select * from ocp_extra_stats where tool = ? ORDER BY id;";
	$stm = $link->prepare( $sql_command );
	if ($stm===FALSE)
	       die('Failed to issue query ['.$sql_command.'], error message : ' . print_r($link->errorInfo(), true));
	$stm->execute(array($tool));
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	$out = [];
	$i = 0;
	foreach($resultset as $var) {
		$out[0][$i] = "custom:".$var['tool'].":".$var['name'];
		$out[1][$i] = 0;
		$i++;
	}
	return $out;
}

function get_vars($module, $mi_url)
{
	global $config;

	$message=mi_command( "get_statistics", array("statistics"=>array($module.":")), $mi_url, $errors);
	if (!empty($errors))
		return;

	ksort($message);

	$temp = array();
	$i=0;
	foreach ($message as $module_stat => $value){
		$out[0][$i] = substr( $module_stat, 1+strpos($module_stat,":"));
		$out[1][$i] = $value;
		$i++;
	}
	return $out;
}


function get_vars_type( $mi_url )
{
	global $config;
 
	$message=mi_command("list_statistics", NULL, $mi_url, $errors);
	if (!empty($errors))
		return;

	$gauge_arr = array();

	ksort($message);
	foreach ($message as $module_stat => $value){
		if ($value == "non-incremental"){
			$gauge_arr [] = $module_stat;
		}
	}
	 
	 return $gauge_arr;
}

function get_all_vars( $mi_url , $stats_list)
{
	global $config;

	if ( strlen($stats_list)==0 ) {
		$list = array("all");
	} else {
		$list = explode(" ",$stats_list);
	}
	$message=mi_command( "get_statistics", array("statistics"=>$list), $mi_url, $errors);
	if (!empty($errors)) 
		return;

	ksort($message);

	$temp = array();
	foreach ($message as $module_stat => $value){
		$temp [] = $module_stat.":: ".$value;
	}
	$message = implode("\n",$temp);

	return $message;
}

function reset_var($stats, $mi_url)
{
 	global $config;
 
 	$message=mi_command("reset_statistics", array("statistics"=>array($stats)), $mi_url, $errors);

	return;
}

function clean_stats_table(){
	include("db_connect.php");
	global $config;
	$global='../../../../config/boxes.global.inc.php';
	require ($global);
	for($box_id=0 ; $box_id<sizeof($boxes) ; $box_id++ ) {
		if ($boxes[$box_id]['smonitor']['charts']==1){
			$chart_history=get_settings_value('chart_history');
			if ($chart_history=="auto") $chart_history=3*24;
			$last_date=$current_time=time();
			$last_date -= 60*60*($chart_history-24);
			$last_date -= 60*60*date("H",$current_time);
			$last_date -= 60*date("i",$current_time);
			$last_date -= date("s",$current_time);
			$sql="DELETE FROM ".get_settings_value("table_monitoring")." WHERE time < ? AND box_id = ?";
			$stm = $link->prepare($sql);
			if ($stm->execute(array($last_date, $box_id)) === false)
				die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
			$i++;
		}
	}
}


function show_boxes($boxen){

global $current_box;
global $page_name;
global $box_val;  

echo ('<form action="'.$page_name.'?action=change_box&box_val="'.$box_val.' method="post" name="boxen_select" style="margin:0px!important">');
csrfguard_generate();
echo ('<input type="hidden" name="box_val" class="formInput" method="post" value="">');
echo ('<select name="box_list" class="boxSelect" onChange=boxen_select.box_val.value=boxen_select.box_list.value;boxen_select.submit() >');

if (empty($current_box)){

	$current_box=key($boxen);
	$_SESSION['smon_current_box']=$current_box ; 
}
 foreach ( $boxen as $val )
    if (!empty($val)) {
	    echo '<option value="'.key($boxen).'"' ;
	    if ((key($boxen))==$current_box) echo ' selected';
	    echo '>'.$val.'</option>';
	    next($boxen);
    }

echo ('</select></form>');

return $current_box; 
}

function prepare_for_select($boxlis){

$i=0;
foreach ($boxlis as $arr){
    $newarr[key($boxlis[$i])]=$arr[key($boxlis[$i])];
    $i++;
}

return $newarr;
}

function get_box_id($current_box){
	require('../../../../config/boxes.load.php');
	foreach ($boxes as $ar) {
		if ($ar['mi']['conn']==$current_box)
			return $ar["id"];
	}
	return null;
}

function get_box_id_by_name($box_name){
	require('../../../../config/boxes.load.php');
	foreach ($boxes as $ar) {
		if ($ar['name']==$box_name)
			return $ar["id"];
	}
	return null;
}

function get_box_id_default(){
	/* returns first box_id */
	require('../../../../config/boxes.load.php');
	if (count($boxes) == 0)
		return null;
	return $boxes[0]["id"];
}

function get_box_id_url($box) {
	require('../../../../config/boxes.load.php');
	foreach ($boxes as $ar) {
		if ($ar['id']==$box)
			return $ar['mi']["conn"];
	}
	return null;
}


function show_graph($id,$stat,$box_id,$refresh=null){
	global $config;
	if (!isset($gauge_arr) || !isset($gauge_arr[$box_id]))
		$gauge_arr[$box_id] = get_vars_type(get_box_id_url($box_id));
	$chart_history = get_settings_value_from_tool("chart_history", "smonitor");
	if ($chart_history == "auto") $chart_history = 3 * 24;
	require("../../../../config/tools/system/smonitor/db.inc.php");
	require("../../../../config/db.inc.php");
	require("db_connect.php");

	$_SESSION['id'] = $id;
	$_SESSION['stat'] = $stat;
  
	$_SESSION['sampling_time'] = get_settings_value_from_tool("sampling_time", "smonitor");
	$_SESSION['chart_size'] = get_settings_value_from_tool("chart_size", "smonitor");
	$_SESSION['box_id_graph'] = $box_id;
	$_SESSION['chart_history'] = $chart_history;
	$_SESSION['tmonitoring'] = get_settings_value_from_tool("table_monitoring", "smonitor");
	$_SESSION['normal'] = (in_array($stat, $gauge_arr[$box_id])?0:1);

	$_SESSION['refreshInterval'] = ($refresh?$refresh:get_settings_value_from_tool("refresh_period", "smonitor") * 1000);
	
	require(__DIR__."/../../../../common/charting/d3js.php");
}

function show_graphs($id,$key,$refresh=null){
	global $config;
	global $gauge_arr;
	$box_ids = [];
	$stats = [];
	$group_attr = get_settings_value_from_tool("groups", "smonitor")[$key];

	$groupElements = $group_attr['stats'];
	$scale = $group_attr['scale'];

	foreach ($groupElements as $g) { // retrieve info about group, build stats boxes array etc
		if (isset($g['box_name']) && !is_null(get_box_id_by_name($g['box_name'])))
		  $stat_box_id = get_box_id_by_name($g['box_name']);
		else
		  $stat_box_id = get_box_id_default();
		$box_ids[] = $stat_box_id;
		if (!isset($gauge_arr) || !isset($gauge_arr[$stat_box_id]))
			$gauge_arr[$stat_box_id] = get_vars_type(get_box_id_url($stat_box_id));
		if (preg_match("/^\/.+\/[a-z]*$/i",$g['name'])) {
		  foreach ($monitored_stats as $name => $id) {//first var is not available TODO
			if (preg_match($g['name'], $name, $matches))
			  $stats[] = $name;
		  }
		} else {
		  $stats[] = $g['name'];
		}
	  }

	$chart_history = get_settings_value("chart_history");
	if ($chart_history == "auto") $chart_history = 3 * 24;
  
	require("../../../../config/tools/system/smonitor/db.inc.php");
	require("../../../../config/db.inc.php");
	require("db_connect.php");
	$chart_size = get_settings_value_from_tool('chart_size', 'smonitor')+1;

    $divId = "";
	$_SESSION['normal'] = array();
	$box_id = 0;
	foreach ($stats as $var) {
		$normal_chart = 1 ;
		if (in_array($var , $gauge_arr[$box_ids[$box_id++]]))  $normal_chart = 0;
		$_SESSION['normal'][] = $normal_chart;
	}
	$nGraphs = sizeof($stats);
	
	$_SESSION['id'] = $id;
	$_SESSION['stats'] = $stats;
	$_SESSION['stime'] = get_settings_value_from_tool("sampling_time", "smonitor");
	$_SESSION['csize'] = get_settings_value_from_tool("chart_size", "smonitor");
	$_SESSION['chart_history'] = $chart_history;
	$_SESSION['boxes_list'] = $box_ids;
	$_SESSION['scale'] = $scale; // 1 is individual
	$_SESSION['refreshInterval'] = ($refresh?$refresh:get_settings_value_from_tool("refresh_period", "smonitor") * 1000);
  
	require(__DIR__."/../../../../common/charting/d3jsMultiple.php");
	
}

function get_stats_classes() {
	$stats_options = array();
	$tools = get_tools();
	foreach ($tools as $tool => $group) {
		$files = glob('../../'.get_tool_path($tool).'/statistics/*.php');
		foreach ($files as $file) {
			require_once($file);
			$file_name = basename($file);
			$stats_options[] = substr($file_name, 0, strlen($file_name) - 4);
		}
	}

	return $stats_options;
}

function get_custom_statistics() {
	include("db_connect.php");
	require_once("../../../../config/db.inc.php");
	require_once("../../../../config/tools/system/smonitor/db.inc.php");
	$sql_command="select * from ocp_extra_stats;";
	$stm = $link->prepare( $sql_command );
	if ($stm===FALSE)
	       die('Failed to issue query ['.$sql_command.'], error message : ' . print_r($link->errorInfo(), true));
	$stm->execute();
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	return $resultset;
}

function show_pie_chart() {
	require(__DIR__."/../../../../common/charting/bar_d3js.php");
}
	
// returns array with names and starting sampling dates for each box stat
// that is actively sampled and has db sampled history
function get_stats_list($box_id) {
	require(__DIR__."/db_connect.php");
	$stats_list = [];
	$i = 0;
	
	foreach(get_settings_value_from_tool("groups", "smonitor") as $key=>$group_attr) {
	   $stats_list[$i]['name'] = "Group: ".$key;
	   $stats_list[$i]['from_time'] = "1300";
	   $i++;
	}

	$sql = "SELECT DISTINCT name FROM ocp_monitored_stats WHERE box_id = ".$box_id." ORDER BY name ASC";
	$stm = $link->prepare($sql);
	if ($stm->execute(array()) === false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	$data_no=count($resultset);

	require(__DIR__."/db_connect.php");
	$sql = "SELECT name, time FROM ocp_monitoring_stats WHERE name = ? AND box_id = ? group by name order by time asc";
	$stm = $link->prepare($sql);
	for($j=0;count($resultset)>$j;$j++)
	{	
		if ($stm->execute(array($resultset[$j]['name'] , $box_id)) === false)
		  die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
		$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		if (!is_null($result)) {
			foreach($result as $stat_name) {
				$stats_list[$i]['name'] = $stat_name['name'];
				$from_time=date('j M Y, H:i:s',$stat_name['time']);
				$stats_list[$i]['from_time'] = $from_time;
				$i++;
			}
		}
	}
	return $stats_list;
}

function get_stats_list_all_boxes() {
	require_once(__DIR__."/../../../../../config/tools/system/smonitor/db.inc.php");
	require_once(__DIR__."/../../../../../config/db.inc.php");
	require_once(__DIR__."/db_connect.php");
	$stats_list = [];

	foreach(get_settings_value_from_tool("groups", "smonitor") as $key=>$group_attr) {
		$stats_list['Group'][] = "Group: ".$key;
	 }

	$sql = "SELECT DISTINCT name, box_id FROM ocp_monitored_stats ORDER BY name ASC";
	$stm = $link->prepare($sql);
	if ($stm->execute(array()) === false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	
	require_once(__DIR__."/../../../../../config/tools/system/smonitor/db.inc.php");
	require_once(__DIR__."/../../../../../config/db.inc.php");
	require_once(__DIR__."/db_connect.php");

	$sql = "SELECT * FROM ocp_monitoring_stats WHERE name = ? AND box_id = ? ORDER BY time ASC LIMIT 1";
	$stm = $link->prepare($sql);

	foreach($resultset as $key => $value) {
		if ($stm->execute(array($value['name'], $value['box_id'])) === false)
			die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
		$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		if (isset($result[0]['time']))
			$stats_list[$value['box_id']][] = $value['name'];
	}
	return $stats_list;
}

function show_widget_graphs($id, $group_name, $refresh=null){
	global $config;
	global $gauge_arr;
	require_once(__DIR__."/../../../../../config/tools/system/smonitor/db.inc.php");
	require_once(__DIR__."/../../../../../config/db.inc.php");
	require_once(__DIR__."/db_connect.php");
	$group =[];
	foreach(get_settings_value_from_tool("groups", "smonitor") as $key=>$group_attr) {
		$boxes = [];
		$groupElements = $group_attr['stats'];
		$scale = $group_attr['scale'];
		$gName = "Group: ";
		$matches = false;
		$group = [];
		foreach ($groupElements as $g) {
			if (isset($g['box_name']) && !is_null(get_box_id_by_name($g['box_name'])))
				$stat_box_id = get_box_id_by_name($g['box_name']);
			else
				$stat_box_id = get_box_id_default();
			$box_ids[] = $stat_box_id;
			if (!isset($gauge_arr) || !isset($gauge_arr[$stat_box_id]))
				$gauge_arr[$stat_box_id] = get_vars_type(get_box_id_url($stat_box_id));
			if( preg_match("/^\/.+\/[a-z]*$/i",$g['name'])) {
			foreach ($monitored_stats as $name => $id) {
				if (preg_match($g['name'], $name, $matches))
					$group[] = $name;
			}
			}
			else $group[] = $g['name'];
		}
	   foreach($group as $gr) {
		 $gName.=$gr.", ";
	   }
	   if ($key == $group_name)
	   	continue;
	}
	$stats = $group;
	
	$chart_size = get_settings_value_from_tool('chart_size', "smonitor")+1;

	$_SESSION['normal'] = array();
	$box_id = 0;
	foreach ($stats as $var) {
		$normal_chart = 1 ;
		if (in_array($var , $gauge_arr[$box_ids[$box_id++]]))  $normal_chart = 0;
		$_SESSION['normal'][] = $normal_chart;
	}
	$nGraphs = sizeof($stats);
	
	$_SESSION['id'] = $id;
	$_SESSION['stats'] = $stats;
	$_SESSION['stime'] = get_settings_value_from_tool("sampling_time", "smonitor");
	$_SESSION['csize'] = get_settings_value_from_tool("chart_size", "smonitor");
	$_SESSION['boxes_list'] = $box_ids;
	$_SESSION['scale'] = $scale; // 1 e individual
	$_SESSION['refreshInterval'] = ($refresh?$refresh:get_settings_value_from_tool("refresh_period", "smonitor") * 1000);

	require(__DIR__."/../../../../common/charting/d3jsMultiple.php");
}

?>
