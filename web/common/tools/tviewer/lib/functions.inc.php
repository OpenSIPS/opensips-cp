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

	require_once("../../../../web/common/cfg_comm.php");
	require_once("../../../../config/db.inc.php");
	require_once("../../../../config/tools/".$branch."/".$module_id."/db.inc.php");
	require("db_connect.php");
		
	session_load_from_tool($module_id);
	if (file_exists("../../../../config/tools/".$branch."/".$module_id."/tviewer.inc.php"))
		require_once("../../../../config/tools/".$branch."/".$module_id."/tviewer.inc.php");
	$options = array();

	if ( isset($combo['combo_table']) && $combo['combo_table']!="" ){
		if (!isset($combo['combo_display_col']) || $combo['combo_display_col']=="")
			$display_col = $combo['combo_value_col'];
		else
			$display_col = $combo['combo_display_col'];

		if (!isset($combo['combo_hook_col']) || $combo['combo_hook_col']=="")
			$hook_col = NULL;
		else
			$hook_col = $combo['combo_hook_col'];

	        $sql="select ".$combo['combo_value_col'].", ".$display_col.(($hook_col==NULL)?"":(", ".$hook_col))." from ".$combo['combo_table'];
        	$stm = $link->query($sql);
        	if($stm === false) {
                	die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
       		}
			$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $k=>$v) {
			$options[ $v[$combo['combo_value_col']] ]['display'] = $v[$display_col] ;
			if ($hook_col!=NULL)
				$options[ $v[$combo['combo_value_col']] ]['hook'] = $v[$hook_col] ;
		}

	} else if (isset($combo['combo_default_values']) && $combo['combo_default_values']!=NULL) {
		foreach ($combo['combo_default_values'] as $k=>$v) {
			$options[ $k ]['display'] = $v ;
			if (isset($combo["combo_default_hooks"]) && $combo["combo_default_hooks"]!=NULL)
				$options[ $k ]['hook'] = $combo["combo_default_hooks"][$k] ;
		}

	}

	return $options;
}

function get_custom_checklist_options($checklist)
{
	global $config;
	global $custom_config;
	global $module_id;
	global $branch;

	require_once("../../../../web/common/cfg_comm.php");
	require_once("../../../../config/db.inc.php");
	require_once("../../../../config/tools/".$branch."/".$module_id."/db.inc.php");
	require("db_connect.php");

	session_load_from_tool($module_id);
	if (file_exists("../../../../config/tools/".$branch."/".$module_id."/tviewer.inc.php"))
		require_once("../../../../config/tools/".$branch."/".$module_id."/tviewer.inc.php");

	$options = array();

	if (isset($checklist['checklist_table']) && $checklist['checklist_table']!="") {
		if (!isset($checklist['checklist_display_col']) || $checklist['checklist_display_col']=="")
			$display_col = $checklist['checklist_value_col'];
		else
			$display_col = $checklist['checklist_display_col'];

		if (!isset($checklist['checklist_hook_col']) || $checklist['checklist_hook_col']=="")
			$hook_col = NULL;
		else
			$hook_col = $checklist['checklist_hook_col'];

		$sql="select ".$checklist['checklist_value_col'].", ".$display_col.(($hook_col==NULL)?"":(", ".$hook_col))." from ".$checklist['checklist_table']." order by ".$checklist['checklist_value_col'];
		$stm = $link->query($sql);
		if($stm === false) {
			die('Failed to issue query, error message : ' . print_r($link->errorInfo(), true));
		}
		$result = $stm->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $k=>$v) {
			$options[$v[$checklist['checklist_value_col']]]['display'] = $v[$display_col] ;
			if ($hook_col!=NULL)
				$options[$v[$checklist['checklist_value_col']]]['hook'] = $v[$hook_col] ;
		}

	} else if (isset($checklist['checklist_default_values']) && $checklist['checklist_default_values']!=NULL) {
		foreach ($checklist['checklist_default_values'] as $k=>$v) {
			$options[$k]['display'] = $v ;
			if (isset($checklist["checklist_default_hooks"]) && $checklist["checklist_default_hooks"]!=NULL)
				$options[$k]['hook'] = $checklist["checklist_default_hooks"][$k] ;
		}

	}

	return $options;
}

function build_custom_checklist_options($values, $checklist)
{
	$sep = ($checklist == NULL || !isset($checklist['checklist_separator']))?"":$checklist['checklist_separator'];
	return implode($sep, $values);
}

function get_custom_checklist_selected($values, $checklist=NULL)
{
	$sep = ($checklist == NULL || !isset($checklist['checklist_separator']))?"":$checklist['checklist_separator'];
	if ($sep != "")
		return explode($sep, $values);
	else
		return str_split($values);
}

function display_custom_checklist($selected, $checklist, &$cache=NULL)
{
	if (!isset($checklist['checklist_display_col']))
		return $selected;
	if ($cache != NULL && isset($cache[$checklist['header']])) {
		$values = $cache[$checklist['header']];
	} else {
		$values = get_custom_checklist_options($checklist);
		if ($cache)
			$cache[$checklist['header']] = $values;
	}
	$selected = get_custom_checklist_selected($selected, $checklist);
	$sep = ($checklist == NULL || !isset($checklist['checklist_separator']))?"":$checklist['checklist_separator'];
	$ret = array();
	foreach ($selected as $val) {
		$ret[] = $values[$val]['display'];
	}
	return implode($sep, $ret);
}

function print_custom_checklist($name, $checklist, $selected)
{
	$values = get_custom_checklist_options($checklist);
	$selected = get_custom_checklist_selected($selected, $checklist);
	print ("
       <table style='width:100%' class='container'><tr><td>");
	foreach ($values as $key=>$val) {
print("
       <input type='checkbox' name='".$name."[]' value='".$key."' id='".$name.$key."' ".((in_array($key, $selected))?"checked":"").">
       <label for=".$name.$key." class='dataRecord'>".$val['display']."</label><br> ");
	}
	print("
       </td>
       <td width='20'>
       <div id='".$name."_ok'></div>
       </td></tr></table>
");
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
			echo('<option value="'.$k.(isset($v['hook'])?('" hook="'.$v['hook']):"").'"> '.$v['display'].'</option>');
		} else {
			echo('<option value="'.$k.(isset($v['hook'])?('" hook="'.$v['hook']):"").'" selected>'.$v['display'].'</option>');
			$selected_set=true;
		}
	}

	if ($selected_set==false && isset($init_val) && $init_val != "") {
		echo '<option value="'.$init_val. '" selected >['.$init_val.']</option>';
	}
	
	echo '</select>';
}

function get_checklist($key, $values, $valueNames = false) {
	global $config;
	global $custom_config;
	global $module_id;
	global $branch;
	
    require_once("../../../../web/common/cfg_comm.php");

	session_load_from_tool($module_id);
	if (file_exists("../../../../config/tools/".$branch."/".$module_id."/tviewer.inc.php"))
		require_once("../../../../config/tools/".$branch."/".$module_id."/tviewer.inc.php");
	
	foreach ($custom_config[$module_id][$_SESSION[$module_id]['submenu_item_id']]['custom_table_column_defs'] as $k => $v) {
		if ($k == $key) {
			$opts = $v['options'];
			$separator = $v['separator'];
		}
	}

	$values = explode($separator, $values);
	
	if (!$valueNames) return $values;

	$keyvalues = array();
	foreach ($values as $el) {

		$keyvalues[] = array_search($el, $opts);
	}
	return $keyvalues;
}

?>
