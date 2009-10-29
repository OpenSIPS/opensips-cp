<?php
/*
 * $Id$
 */

######################
# Database Functions #
######################
//require('../../common/mi_comm.php');
function db_connect()
{
 global $config;

 if (isset($config->db_host_smonitor) && isset($config->db_user_smonitor) && isset($config->db_name_smonitor) ) {
	 $config->db_host = $config->db_host_smonitor;
         $config->db_port = $config->db_port_smonitor;
         $config->db_user = $config->db_user_smonitor;
         $config->db_pass = $config->db_pass_smonitor;
         $config->db_name = $config->db_name_smonitor;
 }
 
 $link = @mysql_connect($config->db_host, $config->db_user, $config->db_pass);
 
 if (!$link) {
              die("Could not connect to MySQL Server: " . mysql_error());
              exit();
             }
 
 $selected = @mysql_select_db($config->db_name, $link);
 if (!$selected) {
                  die("Could not select '$config->db_name' database." . mysql_error());
                  exit();
                 } 
}

function db_close()
{
 mysql_close();
}

##########################
# End Database Functions #
##########################


#################
# FIFO Function #
#################


function get_priv()
{
 if ($_SESSION['user_tabs']=="*") $_SESSION['read_only'] = false;
 else {
       $available_tabs = explode(",", $_SESSION['user_tabs']);
       $available_priv = explode(",", $_SESSION['user_priv']);
       $key = array_search("smonitor", $available_tabs);
       if ($available_priv[$key]=="read-only") $_SESSION['read_only'] = true;
       if ($available_priv[$key]=="read-write") $_SESSION['read_only'] = false;
      }
 return;
}

function get_config_var($var_name,$box_id)
{
 global $config;
 $result=mysql_query("SELECT * FROM ".$config->table_monitored." WHERE name='".$var_name."' and box_id=".$box_id) or die(mysql_error());
 $row=mysql_fetch_array($result);
 $value=$row['extra'];
 if ($value==null) $value=$config->$var_name;
 return $value;
}

function get_modules()
{
 global $config;
 global $comm_type ;
 global $xmlrpc_host ;
 global $xmlrpc_port ;
 
	  $command="get_statistics all";
	  $message=mi_command($command, $errors, $status);
 		   

 if ($errors) {echo($errors[0]); return;}

//      preg_match_all("/Module name = (.*?); statistics=([0-9]*)/i", $message, $regs);
	preg_match_all("/(.*?):(.*?) = ([0-9]*)/i",$message,$regs);
	// uniq 

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

function get_vars($module)
{
 global $config;
 global $comm_type;  
 global $xmlrpc_host ;
 global $xmlrpc_port ;
 
 
  $command="get_statistics ".$module.":";
  $message=mi_command($command,$errors,$status);

  
 if ($errors) {echo($errors[0]); return;}
 /* we accept any 2xx as ok */
// if (substr($status,0,1)!="2") {echo($status); return;}
//  else {
        preg_match_all("/".$module.":(.*?) = ([0-9]*)/i", $message, $regs);
        for ($i=0; $i<sizeof($regs[0]); $i++)
        {
         $out[0][$i] = $regs[1][$i];
         $out[1][$i] = $regs[2][$i];
        }
//      }
 return $out;
}

function get_all_vars()
{
 global $config;
 global $comm_type ; 
 global $xmlrpc_host ;
 global $xmlrpc_port ;
 
		$command="get_statistics all";
		$message=mi_command($command,$errors,$status);

if ($errors) {echo($errors[0]); return;}
 /* we accept any 2xx as ok */
 //if (substr($status,0,1)!="2") {echo($status); return;}
  else return $message;
}

function reset_var($stats)
{
 global $config;
 global $comm_type ; 
 global $xmlrpc_host ;
 global $xmlrpc_port ;
 
 	$command="reset_statistics ".$stats;
 	$message=mi_command($command,$errors,$status);

 
 if ($errors) {echo($errors[0]); return;}
 /* we accept any 2xx as ok */
// if (substr($status,0,1)!="2") {echo($status); return;}
 return;
}

function clean_stats_table()
{
 global $config;

 db_connect();

 $global='../../../config/boxes.global.inc.php';
 require ($global);

 foreach ($boxes as $ar) {

 $box_id=key($boxes);

 next($boxes);

 if ($ar['smonitor']['charts']==1){
    
 $chart_history=get_config_var('chart_history',$box_id);
 if ($chart_history=="auto") $chart_history=3;
 $last_date=$current_time=time();
 $last_date -= 24*60*60*($chart_history-1);
 $last_date -= 60*60*date("H",$current_time);
 $last_date -= 60*date("i",$current_time);
 $last_date -= date("s",$current_time);
 mysql_query("DELETE FROM ".$config->table_monitoring." WHERE time<'".$last_date."' and box_id=".$box_id) or die(mysql_error());
//echo "DELETE FROM ".$config->table_monitoring." WHERE time<'".$last_date."' and box_id=".$box_id;
 $i++; 
 }

 }


 db_close();

}


function inspect_config_mi(){

global $config_type ; 
global $opensips_boxes ; 
global $box_count ; 

$a=0; $b=0 ; 
    
    $global='../../../config/boxes.global.inc.php';
    require ($global);

    foreach ( $boxes as $ar ){

    $box_val=$ar['mi']['conn'];

    if (!empty($box_val)){ 

	$b++ ;
	if ( is_file($box_val) || strpos($box_val,"/") || !(strpos($box_val,":")) )   
    	    							$a++;
	    $boxlist[$ar['mi']['conn']]=$ar['desc'];
     }

    }

    if ($a > 1) {
	echo "ERR: multiple fifo hosts declared in $global " . "<br>" ;
	echo "IT CAN BE ONLY ONE "."<br>" ;
	exit();
    }

$box_count=$b;


return $boxlist;

}

function show_boxes($boxen){

global $current_box;
global $page_name ;  

echo ('<form action="'.$page_name.'?action=change_box&box_val="'.$box_val.' method="post" name="boxen_select" >');
echo ('<input type="hidden" name="box_val" class="formInput" method="post" value="">');
echo ('<table><tr><td>');
echo ('<select name="box_list" class="formInput" onChange=boxen_select.box_val.value=boxen_select.box_list.value;boxen_select.submit() >');

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

echo ('</select></td><td>');
//echo $current_box;
echo ('</td></table></form>');

return $current_box; 
}

function params($box_val){

global $xmlrpc_host; 
global $xmlrpc_port; 
global $fifo_file; 

$a=explode(":",$box_val);    

if (!empty($a[1]))
    {
	$comm_type="xmlrpc";
	$xmlrpc_host=$a[0];
	$xmlrpc_port=$a[1];
    } else {
    	$comm_type="fifo";
	$fifo_file=$box_val ;
    }

return $comm_type;
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

    $global='../../../config/boxes.global.inc.php';
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


	$var = $stat;
	$box_id = $box_id;
	require("../../../config/tools/smonitor/db.inc.php");
	require("../../../config/tools/smonitor/local.inc.php");

	
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
