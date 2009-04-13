<?php
/*
 * $Id$
 */

$var = $_REQUEST['stat'];
$box_id = $_REQUEST['box_id'];
require("../../../../config/tools/smonitor/db.inc.php");
require("../../../../config/tools/smonitor/local.inc.php");
require("functions.inc.php");

$link = mysql_connect($config->db_host, $config->db_user, $config->db_pass);
mysql_select_db($config->db_name, $link);
$chart_size = get_config_var('chart_size',$box_id)+1;

$chart[ 'chart_data' ] [0] [0] = "";
$chart[ 'chart_data' ] [1] [0] = $var;

for($k=1; $k<=$chart_size; $k++)
{
 $chart[ 'chart_data' ] [0] [$k] = "";
 $chart[ 'chart_data' ] [1] [$k] = null;
}

$index = $chart_size;
$result = mysql_query("SELECT * FROM ".$config->table_monitoring." WHERE name='".$var."' and box_id=".$box_id." ORDER BY time DESC LIMIT 0, ".$index);


$normal_chart = false ;
if (in_array($var , $gauge_arr ))  $normal_chart = true ;
		
if ($normal_chart) {

while($row = mysql_fetch_array($result))
{
 if ($row['value']!=NULL) $chart[ 'chart_data' ] [1] [$index] = $row['value'];
  else $chart[ 'chart_data' ] [1] [$index] = 0;
 $chart[ 'chart_data' ] [0] [$index] = date("d/m/y\nH:i:s",$row['time']);
 if ($index==$chart_size) {$axis_min = $row['value']; $axis_max = $row['value'];}
 if ($row['value']>$axis_max) $axis_max = $row['value'];
 if ($row['value']<$axis_min) $axis_min = $row['value'];
 $index--;
}

} else {

$prev_field_val =  ""; 		
$row = mysql_fetch_array($result) ; 
$prev_field_val = $row['value'];


while($row = mysql_fetch_array($result))
{

 $plot_val = $prev_field_val - $row['value']  ;

 if ($plot_val < 0 )  $plot_val = 0 ; 
 
  if ($plot_val!=NULL) 
 		
 		$chart[ 'chart_data' ] [1] [$index] = $plot_val;
  else 
  
  		$chart[ 'chart_data' ] [1] [$index] = 0;
   		
  $chart[ 'chart_data' ] [0] [$index] = date("d/m/y\nH:i:s",$row['time']);
 
if ($index==$chart_size) {

				$axis_min = $plot_val; 
				$axis_max = $plot_val;

}
 if ($plot_val>$axis_max) 
 		$axis_max = $plot_val;

 if ($plot_val<$axis_min) 
 		$axis_min = $plot_val;

 $index--;

$prev_field_val=$row['value'];

}

}


?>
