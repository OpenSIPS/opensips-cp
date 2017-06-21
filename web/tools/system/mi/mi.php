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

require("../../../common/mi_comm.php");
require("../../../common/cfg_comm.php");
require("../../../../config/tools/system/mi/local.inc.php");
require("lib/functions.inc.php");

session_start();
get_priv("mi");

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

require("template/header.php");

if (empty($_SESSION['mi_command_list']))
	get_command_list( $current_box );

if ($_GET['action']=="execute")
{
	$error=false;
	$command=$_POST['mi_cmd'];
	if ($command=="") $error=true;
	if (!$error ) {

		$message=mi_command($command,$current_box,$errors,$status);

		$stupidtags = array("&lt;","&gt;");
		$goodtags = array("<",">");
		$message=str_replace($goodtags,$stupidtags,$message);

		$_SESSION['mi_time'][]=date("H:i:s");
		$_SESSION['mi_command'][]=$command." ".$arguments;
		$_SESSION['mi_box'][]=$current_box ;

		if (count($errors)>0) {
			$_SESSION['mi_response'][]="<font color='red'>".$errors[0]."</font>";
		} else {
			if ($message!="") {
				$res = json_decode($message,true);
				if (count($res) == 0){
					$_SESSION['mi_response'][]="Successfully executed, no output generated";
				} else {
					$_SESSION['mi_response'][]=json_encode($res,JSON_PRETTY_PRINT);
				}
			} else {
				$_SESSION['mi_response'][]="Successfully executed, no output generated";
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
	get_command_list($current_box);
}

require("template/".$page_id.".main.php");
require("template/footer.php");
exit();

?>
