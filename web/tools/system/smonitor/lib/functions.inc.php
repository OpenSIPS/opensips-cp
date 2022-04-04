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
			$chart_history=get_settings_value('chart_history', $box_id);
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
global $page_name ;  

echo ('<form action="'.$page_name.'?action=change_box&box_val="'.$box_val.' method="post" name="boxen_select" style="margin:0px!important">');
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

global $config_type;

    $global='../../../../config/boxes.global.inc.php';
    require ($global);
	$i=0;	
	foreach ( $boxes as $ar ){
		if ($ar['mi']['conn']==$current_box)
			{
				return $i ;			
			}		
	$i++;	
	}

}

function show_graph($stat,$box_id){
	global $config;
	global $gauge_arr;
	$var = $stat;
	require(__DIR__."/../../../../../config/tools/system/smonitor/db.inc.php");
	require(__DIR__."/../../../../../config/db.inc.php");
	require(__DIR__."/db_connect.php");

	$_SESSION['charting_url'] = $url;
	$_SESSION['full_stat'] = $var;
	$_SESSION['stat'] = str_replace(':', '', $stat);
	$_SESSION[str_replace(':', '', $stat)] = $row;
	$_SESSION['sampling_time'] = get_settings_value_from_tool("sampling_time", "smonitor", $box_id);
	$_SESSION['chart_size'] = get_settings_value_from_tool("chart_size", "smonitor", $box_id);
	$_SESSION['box_id_graph'] = $box_id;
	$_SESSION['chart_history'] = get_settings_value_from_tool("chart_history", "smonitor", $box_id);
	$_SESSION['tmonitoring'] = get_settings_value_from_tool("table_monitoring", "smonitor", $box_id);

	$normal_chart = false ;
	if (in_array($var , $gauge_arr ))  $normal_chart = true ;
	$_SESSION['normal'] = 0;
	if ($normal_chart) {
		$_SESSION['normal'] = 1;
	}
	$_SESSION['normal'] = 1;
	require(__DIR__."/d3js.php");
}

function show_graphs($stats, $box_ids, $scale){
	global $config;
	global $gauge_arr;
	require("../../../../config/tools/system/smonitor/db.inc.php");
	require("../../../../config/db.inc.php");
	require("db_connect.php");
	$chart_size = get_settings_value('chart_size')+1;

    $divId = "";
	$_SESSION['normal'] = array();
	foreach ($stats as $var) {
		$normal_chart = 0 ;
		if (in_array($var , $gauge_arr ))  $normal_chart = 1;
		$_SESSION['normal'][] = $normal_chart;
		$divId.=str_replace(':', '', $var);
	}
	$nGraphs = sizeof($stats);
	
	$_SESSION['full_stats'] = $stats;
	$_SESSION['chart_group_id'] = $divId;
	$_SESSION['stime'] = get_settings_value("sampling_time", $box_id);
	$_SESSION['csize'] = get_settings_value("chart_size", $box_id);
	$_SESSION['boxes_list'] = $box_ids;
	$_SESSION['scale'] = $scale; // 1 e individual
	require("lib/d3jsMultiple.php");
	
}

function show_pie_chart() {
	require(__DIR__."/bar_d3js.php");
}

function get_stats_list($box_id) {
	require_once(__DIR__."/../../../../../config/tools/system/smonitor/db.inc.php");
	require_once(__DIR__."/../../../../../config/db.inc.php");
	require_once(__DIR__."/db_connect.php");
	$stats_list = [];
	
	foreach(get_settings_value_from_tool("groups", "smonitor", 0) as $key=>$group_attr) {
		$groupElements = $group_attr['stats'];
		$gName = "Group: ";
		$matches = false;
		$group = [];
		foreach ($groupElements as $g) {
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
	   $stats_list[] = $gName;
	}
	$sql = "SELECT DISTINCT name FROM ocp_monitored_stats WHERE box_id = ".$box_id." ORDER BY name ASC";
	$stm = $link->prepare($sql);
	if ($stm->execute(array()) === false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	$data_no=count($resultset);

	for($j=0;count($resultset)>$j;$j++)
	{
		$stat_chart=false;
		$stat=$resultset[$j]['name'];
		$stats_list[] = $stat;
	}
	return $stats_list;
}

function get_stats_list_all_boxes() {
	require_once(__DIR__."/../../../../../config/tools/system/smonitor/db.inc.php");
	require_once(__DIR__."/../../../../../config/db.inc.php");
	require_once(__DIR__."/db_connect.php");
	$stats_list = [];

	$sql = "SELECT DISTINCT name, box_id FROM ocp_monitored_stats ORDER BY name ASC";
	$stm = $link->prepare($sql);
	if ($stm->execute(array()) === false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	$resultset = $stm->fetchAll(PDO::FETCH_ASSOC);
	$data_no=count($resultset);

	foreach($resultset as $key => $value) {
		$stats_list[$value['box_id']][] = $value['name'];
	}
	return $stats_list;
}

function show_widget_graphs($stats_list){ $box_id = 0;
	require_once(__DIR__."/../../../../../config/tools/system/smonitor/db.inc.php");
	require_once(__DIR__."/../../../../../config/db.inc.php");
	require_once(__DIR__."/db_connect.php");
	$group =[];
	foreach(get_settings_value_from_tool("groups", "smonitor", 0) as $key=>$group_attr) {
		$boxes = [];
		$groupElements = $group_attr['stats'];
		$scale = $group_attr['scale'];
		$gName = "Group: ";
		$matches = false;
		$group = [];
		foreach ($groupElements as $g) {
			$boxes[] = $g['box_id'];
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
	   if ($gName == $stats_list)
	   	continue;
	}
	$stats = $group;

	global $config;
	global $gauge_arr;
	$box_id = $box_id;
	$chart_size = get_settings_value_from_tool('chart_size', "smonitor", $box_id)+1;

    $divId = "";
	$_SESSION['normal'] = array();
	foreach ($stats as $var) {
		$normal_chart = 1 ;
		if (in_array($var , $gauge_arr ))  $normal_chart = 1;
		$_SESSION['normal'][] = $normal_chart;
		$divId.=str_replace(':', '', $var);
	}
	$nGraphs = sizeof($stats);
	
	$_SESSION['full_stats'] = $stats;
	$_SESSION['chart_group_id'] = $divId;
	$_SESSION['stime'] = get_settings_value_from_tool("sampling_time", "smonitor", $box_id);
	$_SESSION['csize'] = get_settings_value_from_tool("chart_size", "smonitor", $box_id);
	$_SESSION['box_id_graph'] = $box_id;
	$_SESSION['scale'] = $scale; // 1 e individual
	require(__DIR__."/d3jsMultiple.php");
}

?>
