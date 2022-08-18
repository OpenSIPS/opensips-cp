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


######################
# Database Functions #
######################
//require('../../common/mi_comm.php');
include("db_connect.php");
require_once("../../../../config/db.inc.php");
require_once("../../../../config/tools/system/statusreport/db.inc.php");

function show_boxes($boxen){

	global $current_box;
	global $page_name;
	global $box_val;  

	echo ('<form action="'.$page_name.'?action=change_box&box_val="'.$box_val.' method="post" name="boxen_select" style="margin:0px!important">');
	csrfguard_generate();
	echo ('<input type="hidden" name="box_val" class="formInput" method="post" value="">');
	echo ('<select name="box_list" class="boxSelect" onChange=boxen_select.box_val.value=boxen_select.box_list.value;boxen_select.submit() >');

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

	echo ('</select></form>');

	return $current_box; 
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
	require_once('../../../../config/boxes.load.php');
	foreach ($boxes as $ar) {
		if ($ar['mi']['conn']==$current_box)
			return $ar["id"];
	}
	return null;
}

function get_box_id_by_name($box_name){
	require('../../../../config/boxes.load.php');
	foreach ($boxes as $ar) {
		if ($ar['name']==$box_name)
			return $ar["id"];
	}
	return null;
}

function get_box_id_default(){
	/* returns first box_id */
	require('../../../../config/boxes.load.php');
	if (count($boxes) == 0)
		return null;
	return $boxes[0]["id"];
}


?>
