<?php
/*
 * $Id$
 * Copyright (C) 2008 Voice Sistem SRL
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

function get_priv() {

        $modules = get_modules();

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
                if( ($key = array_search("pdt", $available_tabs))!==false) {
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

function get_proxys_by_assoc_id($my_assoc_id){

	$global= '../../../../config/boxes.global.inc.php';
	require($global);
	 
	$mi_connectors=array();
	
	for ($i=0;$i<count($boxes);$i++){
  
		if ($boxes[$i]['assoc_id']==$my_assoc_id){
		
			$mi_connectors[]=$boxes[$i]['mi']['conn'];			

		}		

	}

	return $mi_connectors; 	
}

function del_pdt_multiple($prefix, $sdomain)
{
include("db_connect.php");
	global $config;
	global $comm_type ; 
	global $xmlrpc_host ;
	global $xmlrpc_port ;
	global $fifo_file ;
	global $talk_to_this_assoc_id; 
	if ($config->sdomain) $sql="SELECT domain FROM ".$config->table_pdts." WHERE prefix='".$prefix."' AND sdomain='".$sdomain."' LIMIT 1";
	 else $sql="SELECT domain FROM ".$config->table_pdts." WHERE prefix='".$prefix."' LIMIT 1";
	$resultset = $link->queryAll($sql);
	if(PEAR::isError($resultset)) {
        	die('Failed to issue query, error message : ' . $resultset->getMessage());
	}
	$link->disconnect();

	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);
	
	if ($config->sdomain) { 
	
	$command="pdt_delete ".$sdomain." ".$resultset[0]['domain'] ;

	
	for ($i=0;$i<count($mi_connectors);$i++){	

		$comm_type=params($mi_connectors[$i]);	
		mi_command($command,$errors,$status);

	}
	
	}else {
	
	$command="pdt_delete ".$resultset[0]['domain'] ;


		for ($i=0;$i<count($mi_connectors);$i++){	

			$comm_type=params($mi_connectors[$i]);	
			mi_command($command,$errors,$status);

		}

	
	}

	if ($errors) return false;
	return;
}

function add_pdt_multiple($prefix, $sdomain, $domain)
{
include("db_connect.php");
	global $config;
	global $comm_type ; 
	global $xmlrpc_host ;
	global $xmlrpc_port ;
	global $fifo_file ;
	global $talk_to_this_assoc_id;

	$mi_connectors=get_proxys_by_assoc_id($talk_to_this_assoc_id);

	if ($config->sdomain) { 
	
	$command="pdt_add ".$sdomain." ".$prefix." ".$domain;
	

	for ($i=0;$i<count($mi_connectors);$i++){
			
			$comm_type=params($mi_connectors[$i]);	
			mi_command($command,$errors,$status);

	}	


	} else {
	
		$command="pdt_add ".$prefix." ".$domain ;
	
		for ($i=0;$i<count($mi_connectors);$i++){
			
			$comm_type=params($mi_connectors[$i]);	
			mi_command($command,$errors,$status);

	
		}	
	
	}


	if ($errors) return false;
	return;
}

function get_modules() {
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
