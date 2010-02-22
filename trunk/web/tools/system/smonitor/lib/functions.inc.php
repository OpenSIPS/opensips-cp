<?php
/*
 * $Id: functions.inc.php 84 2009-07-15 11:24:09Z iulia_bublea $
 */

######################
# Database Functions #
######################
//require('../../common/mi_comm.php');
include("db_connect.php");
require_once("../../../../config/db.inc.php");
require_once("../../../../config/tools/system/smonitor/db.inc.php");
#################
# FIFO Function #
#################

function get_priv() {

        $modules = get_mods();

        foreach($modules['Admin'] as $key=>$value) {
                $all_tools[$key] = $value;
        }
        foreach($modules['Users'] as $key=>$value) {
                $all_tools[$key] = $value;
        }
        foreach($modules['System'] as $key=>$value) {
                $all_tools[$key] = $value;
        }

        if($_SESSION['user_tabs']=="*") {
                foreach ($all_tools as $lable=>$val) {
                        $available_tabs[]=$lable;
                }
        } else {
                $available_tabs=explode(",",$_SESSION['user_tabs']);
        }

        if ($_SESSION['user_priv']=="*") {
                $_SESSION['read_only'] = false;
		$_SESSION['permission'] = "Read-Write";
        } else {
                $available_privs=explode(",",$_SESSION['user_priv']);
                if( ($key = array_search("smonitor", $available_tabs))!==false) {
                        if ($available_privs[$key]=="read-only"){
                                $_SESSION['read_only'] = true;
				$_SESSION['permission'] = "Read-Only";
                        }
                        if ($available_privs[$key]=="read-write"){
                                $_SESSION['read_only'] = false;
				$_SESSION['permission'] = "Read-Write";
                        }

                }
        }

        return;

}

function get_config_var($var_name,$box_id)
{
include("db_connect.php");
 global $config;
 $sql="SELECT * FROM ".$config->table_monitored." WHERE name='".$var_name."' and box_id=".$box_id;
 $resultset=$link->queryAll($sql);	
 if(PEAR::isError($resultset)) {
          die('Failed to issue query, error message : ' . $resultset->getMessage());
  }
 $value=$resultset[0]['extra'];
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

function clean_stats_table(){
	include("db_connect.php");
	global $config;
	$global='../../../config/boxes.global.inc.php';
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
			$sql="DELETE FROM ".$config->table_monitoring." WHERE time<'".$last_date."' and box_id=".$box_id;
			//echo "DELETE FROM ".$config->table_monitoring." WHERE time<'".$last_date."' and box_id=".$box_id;
			$link->exec($sql);
			$i++;
		}
	}
}


function inspect_config_mi(){

global $config_type ; 
global $opensips_boxes ; 
global $box_count ; 

$a=0; $b=0 ; 
    
    $global='../../../../config/boxes.global.inc.php';
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
	$sql = "SELECT * FROM ".$config->table_monitoring." WHERE name='".$var."' and box_id=".$box_id." ORDER BY time DESC LIMIT 0, ".$index;
	$row=$link->queryAll($sql);
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
		$result = $link->queryAll($sql) ;
	        if(PEAR::isError($result)) {
        	        die('Failed to issue query, error message : ' . $result->getMessage());
        	} 
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

function get_mods() {
         $modules=array();
         $mod = array();
         if ($handle=opendir('../../../tools/admin/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/admin/".$file."/tool.name"));
           }
         closedir($handle);
         $mod['Admin'] = $modules;
        }

         $modules=array();
         if ($handle=opendir('../../../tools/users/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/users/".$file."/tool.name"));
           }
          closedir($handle);
          $mod['Users'] = $modules;
         }

         $modules=array();
         if ($handle=opendir('../../../tools/system/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!="CVS")  && ($file!=".svn"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/system/".$file."/tool.name"));
           }
          closedir($handle);
          $mod['System'] = $modules;
          }
     return $mod;
}


?>
