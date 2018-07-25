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

	$sql="SELECT * FROM ".$config->table_monitored." WHERE name = ? AND box_id = ?";
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
 
	$message=mi_command("get_statistics all", $mi_url, $errors, $status);
	if ($errors)
		return;

	$message = json_decode($message,true);
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

	$command="get_statistics ".$module.":";
	$message=mi_command($command,$mi_url,$errors,$status);
	if ($errors)
		return;

	$message = json_decode($message,true);
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
 
	$message=mi_command("list_statistics", $mi_url, $errors,$status);
	if ($errors)
		return;

	$gauge_arr = array();

	$message = json_decode($message,true);
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
		$message=mi_command("get_statistics all", $mi_url, $errors, $status);
	} else {
		$message=mi_command("get_statistics ".$stats_list, $mi_url, $errors,$status);
	}
	if ($errors) 
		return;

	$message = json_decode($message,true);
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
 
 	$message=mi_command("reset_statistics ".$stats, $mi_url, $errors,$status);

	return;
}

function clean_stats_table(){
	include("db_connect.php");
	global $config;
	$global='../../../../config/boxes.global.inc.php';
	require ($global);
	for($box_id=0 ; $box_id<sizeof($boxes) ; $box_id++ ) {
		if ($boxes[$box_id]['smonitor']['charts']==1){
			$chart_history=get_config_var('chart_history',$box_id);
			if ($chart_history=="auto") $chart_history=3;
			$last_date=$current_time=time();
			$last_date -= 24*60*60*($chart_history-1);
			$last_date -= 60*60*date("H",$current_time);
			$last_date -= 60*date("i",$current_time);
			$last_date -= date("s",$current_time);
			$sql="DELETE FROM ".$config->table_monitoring." WHERE time < ? AND box_id = ?";
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
	$box_id = $box_id;
	require("../../../../config/tools/system/smonitor/db.inc.php");
	require("../../../../config/db.inc.php");
	require("../../../../config/tools/system/smonitor/local.inc.php");
	require("db_connect.php");

	$chart_size = get_config_var('chart_size',$box_id)+1;
	
	$chart[ 'chart_data' ] [0] [0] = "";
	$chart[ 'chart_data' ] [1] [0] = $var;
	
	for($k=1; $k<=$chart_size; $k++)
	{
	$chart[ 'chart_data' ] [0] [$k] = "";
	$chart[ 'chart_data' ] [1] [$k] = null;
	}
	
	$index = $chart_size;
	$sql = "SELECT * FROM ".$config->table_monitoring." WHERE name = ? AND box_id = ? ORDER BY time DESC LIMIT ".$index;
	$stm = $link->prepare($sql);
	if ($stm->execute(array($var, $box_id)) === false)
		die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
	$row = $stm->fetchAll(PDO::FETCH_ASSOC);

	$normal_chart = false ;
	if (in_array($var , $gauge_arr ))  $normal_chart = true ;
			
	if ($normal_chart) {

		for($i=0;count($row)>$i;$i++)
		{
			if ($row[$i]['value']!=NULL) $chart[ 'chart_data' ] [1] [$index] = $row[$i]['value'];
			else $chart[ 'chart_data' ] [1] [$index] = 0;
			$chart[ 'chart_data' ] [0] [$index] = date("d/m/y\nH:i:s",$row[$i]['time']);
			if ($index==$chart_size) {$axis_min = $row[$i]['value']; $axis_max = $row[$i]['value'];}
			if ($row[$i]['value']>$axis_max) $axis_max = $row[$i]['value'];
			if ($row[$i]['value']<$axis_min) $axis_min = $row[$i]['value'];
			$index--;
		}
	
	} else {
		$prev_field_val =  ""; 		
		if ($stm->execute(array($var, $box_id)) === false)
			die('Failed to issue query, error message : ' . print_r($stm->errorInfo(), true));
		$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		$prev_field_val = $result[0]['value'];
	
		for($i=0;count($result)>$i;$i++)
		{
	
			$plot_val = $prev_field_val - $result[$i]['value']  ;
		
			if ($plot_val < 0 )  $plot_val = 0 ; 
		
			if ($plot_val!=NULL) 
				
				$chart[ 'chart_data' ] [1] [$index] = $plot_val;
			else 
		
				$chart[ 'chart_data' ] [1] [$index] = 0;
				
			$chart[ 'chart_data' ] [0] [$index] = date("d/m/y\nH:i:s",$result[$i]['time']);
		
			if ($index==$chart_size) {
		
				$axis_min = $plot_val; 
				$axis_max = $plot_val;
		
			}
			if ($plot_val>$axis_max) 
				$axis_max = $plot_val;
		
			if ($plot_val<$axis_min) 
				$axis_min = $plot_val;
		
			$index--;
		
			$prev_field_val=$result[$i]['value'];
		
		}
	
	}	

	include "lib/libchart/classes/libchart.php";

	$graph_chart = new LineChart();

	$dataSet = new XYDataSet();
	
	for($k=1; $k<=$chart_size; $k++)
	{
 		$dataSet->addPoint(new Point($chart[ 'chart_data' ] [0] [$k],$chart[ 'chart_data' ] [1] [$k] ));
			
	}	
	$graph_chart->setDataSet($dataSet);
	
	$graph_chart->setTitle($stat);
	$graph_chart->render("generated/".$stat.".png");



	echo '<img alt="Line chart" src="generated/'.$stat.'.png" style="border: 1px solid gray;"/>';

}

?>
