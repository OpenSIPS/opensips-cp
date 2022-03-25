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

session_start();

require_once("../../../../config/session.inc.php");
require_once("lib/functions.inc.php");
require("../../../common/cfg_comm.php");
get_priv("monit");
session_load();
require("template/header.php");

$box=$_SESSION['monit_current_box'];
$var=$_GET['var'];
if (isset($_POST['action'])==NULL)
	$action=NULL;
else
	$action=$_POST['action'];

$boxen=inspect_config_monit();
$boxenlist=prepare_for_select($boxen);

if (!empty($_POST['box_val'])) {

	$current_box=$_POST['box_val'];
	$_SESSION['monit_current_box']=$current_box ;
}

if (!empty($_SESSION['monit_current_box']) && empty($current_box)) {
	$current_box=$_SESSION['monit_current_box'];
}


$current_box=show_boxes($boxenlist,$current_box);
$_SESSION['monit_current_box']=$current_box;

$foo=get_params_for_this_box($current_box);

show_button();

display_settings_button();
echo_header();

if (empty($var) || ($var==".")) {


	$var="/";
	$source=get_monit_page($foo['host'],$foo['port'],$foo['user'],$foo['pass'],$var,$action,$foo['has_ssl']);
	$page=(substr($source,strpos($source,"\r\n\r\n")+4)) ;

	$newpage = monit_html_replace($page);

	echo $newpage;

	exit();

}


$foo=get_params_for_this_box(trim($box));

if (is_array($foo)) {

	if ($source=get_monit_page($foo['host'],$foo['port'],$foo['user'],$foo['pass'],$var,$action,$foo['has_ssl'])){

		$page=(substr($source,strpos($source,"\r\n\r\n")+4)) ;
		$newpage = monit_html_replace($page);

		if (!empty($action)) {


			$var="/";
			$source=get_monit_page($foo['host'],$foo['port'],$foo['user'],$foo['pass'],$var,$action,$foo['has_ssl']);
			$page=(substr($source,strpos($source,"\r\n\r\n")+4)) ;
			$newpage = monit_html_replace($page);

			echo $newpage;


		} else {

			echo $newpage;

		}
	} else {

		echo "I can't connect!"."<br>";
		exit();

	}

}else {

	echo "no such monit host"."<br>";
	exit();
}

?>
