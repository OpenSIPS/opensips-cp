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

function build_unique_check_query($custom_config,$table,$post_data,$id=NULL){
	$build_query = NULL;
	$build_mul = NULL;
	$query_vals = array();
	$mul_vals = array();
	foreach ($custom_config['custom_table_column_defs'] as $key => $value){
		if (isset($value['show_in_add_form']) && $value['show_in_add_form'] == true && isset($value['key']) && in_array($value['key'],array("PRI","UNI","MUL")) ){
			switch ($value['key']) {
				case "PRI":
					$build_query = ($build_query == NULL) ? " ".$key."=?" : " OR ".$key."=?";
					$query_vals[] = $post_data[$key];
					break;

				case "UNI":
					$build_query = ($build_query == NULL) ? " ".$key."=?" : " OR ".$key."=?";
					$query_vals[] = $post_data[$key];
					break;

				case "MUL":
					$build_mul .= ($build_mul == NULL) ? " ( ".$key."=?" : " AND ".$key."=?";
					$mul_vals[] = $post_data[$key];
			}
		}
	}

	//build check query
	$query = NULL;
	if ($build_mul != NULL) {
		$build_mul .= " )";
		$query_vals = array_merge($query_vals, $mul_vals);
	}

	if ($build_query != NULL || $build_mul != NULL){
		$query = "select count(*) from ".$table." where"; 
		$query .= $build_query;
		if ($build_query != NULL && $build_mul != NULL){
			$build_mul = " OR ".$build_mul;
		}
		$query .= $build_mul;
		if ($id != NULL){
			$query .= " AND ".$custom_config['custom_table_primary_key']." != ?";
			$query_vals[] = $id;
		}
	}

	return array($query, $query_vals);
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


function get_custom_combo_options($combo)
{
	global $config;
	global $custom_config;
	global $module_id;
	global $branch;

        require_once("../../../../config/tools/".$branch."/".$module_id."/local.inc.php");
        require_once("../../../../config/db.inc.php");
        require_once("../../../../config/tools/".$branch."/".$module_id."/db.inc.php");
        require("db_connect.php");

	$options = array();

	if ( isset($combo['combo_table']) && $combo['combo_table']!="" ){
		if (!isset($combo['combo_display_col']) || $combo['combo_display_col']=="")
			$display_col = $combo['combo_value_col'];
		else
			$display_col = $combo['combo_display_col'];

		if (!isset($combo['combo_label_col']) || $combo['combo_label_col']=="")
			$label_col = NULL;
		else
			$label_col = $combo['combo_label_col'];

	        $sql="select ".$combo['combo_value_col'].", ".$display_col.(($label_col==NULL)?"":(", ".$label_col))." from ".$combo['combo_table'];
        	$stm = $link->query($sql);
        	if($stm === false) {
                	die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
       		}
			$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $k=>$v) {
			$options[ $v[$combo['combo_value_col']] ]['display'] = $v[$display_col] ;
			if ($label_col!=NULL)
				$options[ $v[$combo['combo_value_col']] ]['label'] = $v[$label_col] ;
		}

	} else if (isset($combo['combo_default_values']) && $combo['combo_default_values']!=NULL) {
		foreach ($combo['combo_default_values'] as $k=>$v) {
			$options[ $k ]['display'] = $v ;
			if (isset($combo["combo_default_labels"]) && $combo["combo_default_labels"]!=NULL)
				$options[ $k ]['label'] = $combo["combo_default_labels"][$k] ;
		}

	}

	return $options;
}


function print_custom_combo($name, $combo, $init_val, $force_empty=FALSE)
{
	$options = get_custom_combo_options($combo);

	$dis = ($combo['disabled'])?"disabled":"";

	echo '<select name="'.$name.'" id="'.$name.'" size="1" style="width: 205px" class="dataSelect" '.
		(isset($combo['events'])?$combo['events'].' ':'').$dis.'>';
	if ((isset($combo['is_optional']) && $combo['is_optional']=='y') || $force_empty==TRUE)
		echo '<option value="">Empty...</option>';

	$selected_set = false;
	foreach ($options as $k=>$v) {
		if ((string)$k != (string)$init_val) {
			echo('<option value="'.$k.(isset($v['label'])?('" label="'.$v['label']):"").'"> '.$v['display'].'</option>');
		} else {
			echo('<option value="'.$k.(isset($v['label'])?('" label="'.$v['label']):"").'" selected>'.$v['display'].'</option>');
			$selected_set=true;
		}
	}

	if ($selected_set==false && isset($init_val) && $init_val != "") {
		echo '<option value="'.$init_val. '" selected >['.$init_val.']</option>';
	}
	
	echo '</select>';
}


?>
