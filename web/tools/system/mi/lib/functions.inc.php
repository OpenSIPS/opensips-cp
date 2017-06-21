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


function get_command_list($mi_url)
{
	$message = mi_command( "which", $mi_url, $errors, $status);

	$message = json_decode($message,true);
	//TODO - this might change 
	$message = $message[null];
	$cmds = array();
	for ($i=0;$i<count($message);$i++){
		$cmds [] = $message[$i]['value'];
	}
	$_SESSION['mi_command_list'] = $cmds;
	return;
}

function print_command_list()
{
	$command = $_SESSION['mi_command_list'];
	echo('<select name="comm_list" class="formInput" onChange="form.mi_cmd.value=form.comm_list.value">');
	echo('<option value="">- select -</option>');
	sort($command);
	for ($i=0; $i<sizeof($command); $i++)
	if ($command[$i]!="") echo('<option value="'.$command[$i].'">'.$command[$i].'</option>');
	echo('</select>');
	return;
}

function show_boxes($boxen,$current_box,$hold){

	global $page_name ;

	echo ('<form action="'.$page_name.'?action=change_box&box_val="'.$box_val.' method="post" name="boxen_select" >');
	echo ('<input type="hidden" name="box_val" class="formInput" method="post" value="">');
	echo ('<table><tr><td>');
	echo ('<select name="box_list" class="formInput" onChange=boxen_select.box_val.value=boxen_select.box_list.value;boxen_select.submit() >');

	if (empty($current_box)) {
		$current_box=key($boxen);
		$_SESSION[$hold]=$current_box ;
	}
	foreach ( $boxen as $val )
	if (!empty($val)) {
		echo '<option value="'.key($boxen).'"' ;
		if ((key($boxen))==$current_box) echo ' selected';
		echo '>'.$val.'</option>';
		next($boxen);
	}

	echo ('</select></td><td>');
	echo $current_box;
	echo ('</td></table></form>');

	return $current_box ;

}

?>
