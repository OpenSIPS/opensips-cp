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


$var = $_REQUEST['stat'];
$box_id = $_REQUEST['box_id'];
require("../../../../config/tools/smonitor/db.inc.php");
require("../../../../config/db.inc.php");
require("../../../../config/tools/smonitor/local.inc.php");
require("functions.inc.php");
require("db_connect.php");
global $config;

$chart_size = get_config_var('chart_size',$box_id)+1;

$chart[ 'chart_data' ] [0] [0] = "";
$chart[ 'chart_data' ] [1] [0] = $var;

for($k=1; $k<=$chart_size; $k++)
{
 $chart[ 'chart_data' ] [0] [$k] = "";
 $chart[ 'chart_data' ] [1] [$k] = null;
}

$index = $chart_size;
$sql = "SELECT * FROM ".$config->table_monitoring." WHERE name='".$var."' and box_id=".$box_id." ORDER BY time DESC LIMIT ".$index;
$row = $link->queryAll($sql);
if(PEAR::isError($row)) {
        die('Failed to issue query, error message : ' . $row->getMessage());
}


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
$prev_field_val = $row[0]['value'];


for($i=1;count($row)>$i;$i++)
{

 $plot_val = $prev_field_val - $row[$i]['value']  ;

 if ($plot_val < 0 )  $plot_val = 0 ; 
 
  if ($plot_val!=NULL) 
 		
 		$chart[ 'chart_data' ] [1] [$index] = $plot_val;
  else 
  
  		$chart[ 'chart_data' ] [1] [$index] = 0;
   		
  $chart[ 'chart_data' ] [0] [$index] = date("d/m/y\nH:i:s",$row[$i]['time']);
 
if ($index==$chart_size) {

				$axis_min = $plot_val; 
				$axis_max = $plot_val;

}
 if ($plot_val>$axis_max) 
 		$axis_max = $plot_val;

 if ($plot_val<$axis_min) 
 		$axis_min = $plot_val;

 $index--;

$prev_field_val=$row[$i]['value'];

}

}


?>
