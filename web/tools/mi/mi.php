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

require("../../common/mi_comm.php");
require("../../../config/tools/mi/local.inc.php");
require("lib/functions.inc.php");

session_start();
get_priv();
$xmlrpc_host="";
$xmlrpc_port="";
$fifo_file="";


$current_box=$_SESSION['mi_current_box'];
if (empty($current_box))
$current_box="";

$boxlist=array();
$boxlist=inspect_config_mi();


if (!empty($_POST['box_val'])) {

	$current_box=$_POST['box_val'];
	$_SESSION['mi_current_box']=$current_box ;
}

if (!empty($_SESSION['mi_current_box']) && empty($current_box)) {
	$current_box=$_SESSION['mi_current_box'];
}


$current_box=show_boxes($boxlist,$current_box,'mi_current_box');
$_SESSION['mi_current_box']=$current_box;

$comm_type=params($current_box);
$_SESSION['comm_type']=$comm_type;


require("template/header.php");

if (empty($_SESSION['mi_command_list']))
get_command_list();

if ($_GET['action']=="execute")
{
	$error=false;
	$command=$_POST['mi_cmd'];
	if ($command=="") $error=true;
	if (!$error ) {

		$message=mi_command($command,$errors,$status);

		$_SESSION['mi_time'][]=date("H:i:s");
		$_SESSION['mi_command'][]=$command." ".$arguments;
		$_SESSION['mi_box'][]=$current_box ;
		if ($errors) $_SESSION['mi_response'][]=$errors[0];
		else {
			if (substr($status,0,1)!="2") $_SESSION['mi_response'][]=$status;
			else {
				if ($message!="") $_SESSION['mi_response'][]=$message;
				else $_SESSION['mi_response'][]="Successfully executed, no output generated";
			}
		}
	}
}

if ($_GET['action']=="clear_history")
{
	unset($_SESSION['mi_time']);
	unset($_SESSION['mi_command']);
	unset($_SESSION['mi_response']);
}

if ($_GET['action']=="change_box")
{

	$current_box=$_POST['box_val'];
	$_SESSION['mi_current_box']=$current_box;
	echo $comm_type ."<br>";
	echo $current_box ."<br>";
	get_command_list();
}

require("template/".$page_id.".main.php");
require("template/footer.php");
exit();

?>