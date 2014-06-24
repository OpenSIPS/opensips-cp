<?php
/*
* $Id: functions.inc.php 133 2009-10-29 18:05:56Z iulia_bublea $
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
* You should hav$modules$modulese received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

function build_unique_check_query($custom_config,$table,$post_data,$id=NULL){
	$build_query = NULL;
	$build_mul = NULL;
	foreach ($custom_config['custom_table_column_defs'] as $key => $value){
		if (isset($value['show_in_add_form']) && $value['show_in_add_form'] == true && isset($value['key']) && in_array($value['key'],array("PRI","UNI","MUL")) ){
			switch ($value['key']) {
				case "PRI":
					$build_query = ($build_query == NULL) ? " ".$key."='".$post_data[$key]."'" : " OR ".$key."='".$post_data[$key]."'";
					break;

				case "UNI":
					$build_query = ($build_query == NULL) ? " ".$key."='".$post_data[$key]."'" : " OR ".$key."='".$post_data[$key]."'";
					break;

				case "MUL":
					$build_mul .= ($build_mul == NULL) ? " ( ".$key."='".$post_data[$key]."'" : " AND ".$key."='".$post_data[$key]."'";

			}
		}
	}

	//build check query
	$query = NULL;
	if ($build_mul != NULL) 
		$build_mul .= " )";
	if ($build_query != NULL || $build_mul != NULL){
		$query = "select count(*) from ".$table." where"; 
		$query .= $build_query;
		if ($build_query != NULL && $build_mul != NULL){
			$build_mul = " OR ".$build_mul;
		}
		$query .= $build_mul;
		if ($id != NULL){
			$query .= " AND ".$custom_config['custom_table_primary_key']." != ".$id;
		}
	}

	return $query;
}

function get_priv() {
	global $module_id;

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
                foreach ($all_tools as $label=>$val) {
                        $available_tabs[]=$label;
                }
        } else {
                $available_tabs=explode(",",$_SESSION['user_tabs']);
        }

        if ($_SESSION['user_priv']=="*") {
                $_SESSION['read_only'] = false;
                $_SESSION['permission'] = "Read-Write";
        } else {
                $available_privs=explode(",",$_SESSION['user_priv']);
                if( ($key = array_search($module_id, $available_tabs))!==false) {
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


function params($box_val){

	global $xmlrpc_host;
	global $xmlrpc_port;
	global $fifo_file;
	global $udp_host;
	global $udp_port;
	global $json_url;

	$a=explode(":",$box_val);

	switch ($a[0]) {
		case "udp":
			$comm_type="udp";
			$udp_host = $a[1];
			$udp_port = $a[2];
			break;
		case "xmlrpc":
			$comm_type="xmlrpc";
			$xmlrpc_host = $a[1];
			$xmlrpc_port = $a[2];
			break;
		case "fifo":
			$comm_type="fifo";
			$fifo_file = $a[1];
			break;
		case "json":
			$comm_type="json";
			$json_url = substr($box_val,5);
			break;
	}

	return $comm_type;
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


function search($array, $key, $value){
    $results = array();

    if (is_array($array))
    {
        if (isset($array[$key]) && $array[$key] == $value)
            $results[] = $array;

        foreach ($array as $subarray)
            $results = array_merge($results, search($subarray, $key, $value));
    }

    return $results;
}



function print_custom_combo($name,$value,$display,$default_values,$table,$value_col,$display_col,$disabled=false)
{
	global $config;
	global $custom_config;
	global $module_id;
	global $branch;

        require("../../../../config/tools/".$branch."/".$module_id."/local.inc.php");
        require("../../../../config/db.inc.php");
        require("../../../../config/tools/".$branch."/".$module_id."/db.inc.php");
        require("db_connect.php");

	$options = array();

	if ($table != NULL){
	        $sql="select ".$value_col.", ".$display_col." from ".$table;
        	$result = $link->queryAll($sql);
        	if(PEAR::isError($result)) {
                	die('Failed to issue query, error message : ' . $result->getMessage());
       		}
		foreach ($result as $k=>$v) {
			$options[]=array("label"=>$v[$display_col],"value"=>$v[$value_col]);
			if ($value == $v[$value_col])
				$display = $v[$display_col];
		}
	}
	if (is_array($default_values) && !empty($default_values)){
		foreach ($default_values as $k => $v) {
			if ($table != NULL){
				if (!count(search($options,"value",$k))){
					$options[]=array("label"=>$v,"value"=>$k);
					if ($value == $k)
						$display = $v;
				}
			}
			else{
				$options[]=array("label"=>$v,"value"=>$k);
				if ($value == $k)
					$display = $v;
			}
			
		}
	}


	$dis = ($disabled)?"disabled":"";
	echo '<select name="'.$name.'" id="'.$name.'" size="1" '.$dis.'>';

	if ($value!=NULL && $value != "") {
		$display = (!isset($display) || $display == "")?$value:$display;
		echo '<option value="">Empty...</option>';
		echo '<option value="'.$value. '" selected > '.$display.'</option>';
	} else {
		echo '<option value="" selected >Empty...</option>';
	}

	for ($i=0;$i<sizeof($options);$i++){
		if ((string)$options[$i]['value'] != (string)$value) {
			echo('<option value="'.$options[$i]['value']. '"> '.$options[$i]['label'].'</option>');
		}
	}
	
	
	echo '</select>';
}



?>
