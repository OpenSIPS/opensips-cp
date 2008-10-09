<?php
/*
 * $Id: functions.inc.php,v 1.2 2007-04-19 14:06:54 bogdan Exp $
 */
 
######################
# Database Functions #
######################

function db_connect()
{
 global $config;
 
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


function get_priv()
{
 if ($_SESSION['user_tabs']=="*") $_SESSION['read_only'] = false;
 else {
       $available_tabs = explode(",", $_SESSION['user_tabs']);
       $available_priv = explode(",", $_SESSION['user_priv']);
       $key = array_search("drouting", $available_tabs);
       if ($available_priv[$key]=="read-only") $_SESSION['read_only'] = true;
       if ($available_priv[$key]=="read-write") $_SESSION['read_only'] = false;
      }
 return;
}

function get_proxys_by_assoc_id($my_assoc_id){

	$global="../../../config/boxes.global.inc.php";	 
	require($global);	 
	 
	$mi_connectors=array();
	
	for ($i=0;$i<count($boxes);$i++){

		if ($boxes[$i]['assoc_id']==$my_assoc_id){
		
			$mi_connectors[]=$boxes[$i]['mi']['conn'];			

		}		

	}

	return $mi_connectors; 	
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


?>