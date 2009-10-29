<?php
/*
 * $Id$
 */
 

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
