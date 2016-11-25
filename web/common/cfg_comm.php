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




function get_proxys_by_assoc_id($my_assoc_id){

	$global="../../../../config/boxes.global.inc.php";
	require($global);

	$mi_connectors=array();

	for ($i=0;$i<count($boxes);$i++){

		if ($boxes[$i]['assoc_id']==$my_assoc_id){

			$mi_connectors[]=$boxes[$i]['mi']['conn'];

		}

	}

	return $mi_connectors;
}


function get_all_proxys_by_assoc_id($my_assoc_id){

	$global="../../../../config/boxes.global.inc.php";
	require($global);

	$mi_connectors=array();

	for ($i=0;$i<count($boxes);$i++){

		if ($boxes[$i]['assoc_id']==$my_assoc_id){

			$mi_connectors[]=$boxes[$i]['mi']['conn'];

		}

	}

	return $mi_connectors;
}


function get_priv($my_tool) {

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
				if( ($key = array_search($my_tool, $available_tabs))!==false) {
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


function get_modules() {
         $modules=array();
         $mod = array();
         if (file_exists('../../../tools/admin/') && $handle=opendir('../../../tools/admin/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!=".git"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/admin/".$file."/tool.name"));
           }
         closedir($handle);
        }
        $mod['Admin'] = $modules;

         $modules=array();
         if (file_exists('../../../tools/users/') && $handle=opendir('../../../tools/users/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!=".git"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/users/".$file."/tool.name"));
           }
          closedir($handle);
         }
         $mod['Users'] = $modules;

         $modules=array();
         if (file_exists('../../../tools/system/') && $handle=opendir('../../../tools/system/'))
         {
          while (false!==($file=readdir($handle)))
           if (($file!=".") && ($file!="..") && ($file!=".git"))
           {
            $modules[$file]=trim(file_get_contents("../../../tools/system/".$file."/tool.name"));
           }
          closedir($handle);
         }
         $mod['System'] = $modules;
     return $mod;
}


function inspect_config_mi(){
	global $opensips_boxes ;
	global $box_count ;
	$a=0; $b=0 ;

	$global='../../../../config/boxes.global.inc.php';
	require ($global);

	$my_mis = array();

	foreach ( $boxes as $ar ) {

		$mi_url=$ar['mi']['conn'];

		if (!empty($mi_url)){

			$b++ ;

			if ( in_array( $mi_url , $my_mis) ) {
				echo "Re-usage of MI URL $mi_url in box ".$ar['desc']." in $global " . "<br>" ;
				echo "MI URLs must be uniques"."<br>" ;
				exit();
			}

			$my_mis[] = $mi_url;

			$boxlist[$ar['mi']['conn']]=$ar['desc'];
		}

	}

	$box_count=$b;

	return $boxlist;
}



?>
