<?php
/*
 * Copyright (C) 2021 OpenSIPS Project
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

$dr_sort_alg = array(
	"N" => "none",
	"W" => "weight",
	"Q" => "quality",
);


function dr_get_name_of_sort_alg($char)
{
	global $dr_sort_alg;
	if (isset($dr_sort_alg[$char]))
		return $dr_sort_alg[$char];
	return "unknown";
} 


function dr_get_options_of_list_sort($selected) 
{
	
	if ($selected==NULL || $selected=="") {
		$selected = 'N';
		$out = "";
	} else if ($selected!="N" && $selected!="W" && $selected!="Q" ){
		$out = "<option value='".$selected."' selected>".$selected."(unknown)</option>";
	}

	$out .= "<option value='N' ".($selected=='N'?"selected":"").">none</option>";
	$out .= "<option value='W' ".($selected=='W'?"selected":"").">weight</option>";
	$out .= "<option value='Q' ".($selected=='Q'?"selected":"").">quality</option>";
	print $out;
}

function dr_get_attrs_map($attrs)
{
	$ret = array();
	$s = explode(";", $attrs);
	foreach ($s as $k) {
		$split = explode("=", $k, 2);
		if (count($split) == 2)
			$ret[$split[0]] = $split[1];
		else
			$ret[$k] = "true";
	}
	return $ret;
}

function dr_get_attrs_val($attr_map, $key, $value)
{
	if (isset($attr_map[$key])) {
		switch($value["type"]) {
		case "text":
			return $attr_map[$key];
		case "checkbox":
			return true;
		case "combo":
			$attr = $attr_map[$key];
			$options = dr_get_combo_attrs($value, $attr);
			return $options[$attr];
		}
	} else {
		return NULL;
	}
}

function dr_build_attrs($attrs)
{
	$ret = "";
	foreach ($attrs as $key=>$value) {
		if (!isset($_POST["extra_".$key]) || $_POST["extra_".$key] == "")
			continue;
		switch ($value["type"]) {
		case "text":
		case "combo":
			$ret .= ($ret!=""?";":"").$key."=".$_POST["extra_".$key];
			break;
		case "checkbox":
			$ret .= ($ret!=""?";":"").$key;
			break;
		}
	}
	return $ret;
}

function dr_get_combo_attrs($value, $filter_val = null)
{
	global $link;

	$ret = array();
	$query = "SELECT " .$value["combo_display_col"]. " AS name, ".
		$value["combo_value_col"]. " AS id FROM ". $value["combo_table"];

	if ($filter_val != null)
		$query .= " WHERE ".$value["combo_value_col"]." = ".$filter_val;

	$stm = $link->prepare($query);
	if ($stm===FALSE) {
		die('Failed to issue query [' . $query . '], error message : ' . $link->errorInfo()[2]);
	}
	$stm->execute();
	$results = $stm->fetchAll();
	foreach ($results as $key => $value)
		$ret[$value['id']] = $value['name'];
	return $ret;
}

?>
