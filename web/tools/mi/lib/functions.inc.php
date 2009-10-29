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


function get_command_list()
{
	global $config;
	global $xmlrpc_host ;
	global $xmlrpc_port ;
	global $comm_type ;

	$command = "which";
	$message = mi_command($command, $errors, $status);

	$_SESSION['mi_command_list'] = explode("\n", $message);
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

function get_priv()
{
	if ($_SESSION['user_tabs']=="*") $_SESSION['read_only'] = false;
	else {
		$available_tabs = explode(",", $_SESSION['user_tabs']);
		$available_priv = explode(",", $_SESSION['user_priv']);
		$key = array_search("mi", $available_tabs);
		if ($available_priv[$key]=="read-only") $_SESSION['read_only'] = true;
		if ($available_priv[$key]=="read-write") $_SESSION['read_only'] = false;
	}
	return;
}

function inspect_config_mi(){
	global $opensips_boxes ;
	global $box_count ;
	$a=0; $b=0 ;



	$global='../../../config/boxes.global.inc.php';
	require ($global);

	foreach ( $boxes as $ar ){


		$box_val=$ar['mi']['conn'];

		if (!empty($box_val)){

				$b++ ;

				if (  is_file($box_val) || strpos($box_val,"/") || !(strpos($box_val,":"))  )
				$a++;
				$boxlist[$ar['mi']['conn']]=$ar['desc'];

			}

		}

		if ($a > 1) {
			echo "ERR: multiple fifo hosts declared in $global " . "<br>" ;
			echo "IT CAN BE ONLY ONE "."<br>" ;
			exit();
		}

	$box_count=$b;


	return $boxlist;
	
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
