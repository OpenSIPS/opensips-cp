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

require_once("../../../common/cfg_comm.php");
require("template/header.php");

global $config;

$current_page="current_page_cdrviewer";

session_load();

include("lib/db_connect.php");

$cdr_repository_path = get_settings_value('cdr_repository_path');
$cdr_set_field_names = get_settings_value('cdr_set_field_names');
$delay = get_settings_value('delay');
$show_field = get_settings_value('show_field');

if (isset($_POST['action'])) $action=$_POST['action'];
else if (isset($_GET['action'])) $action=$_GET['action'];
else $action="";

if (isset($_GET['page'])) $_SESSION[$current_page]=$_GET['page'];
else if (!isset($_SESSION[$current_page])) $_SESSION[$current_page]=1;




if ($action=="search")
{
	$_SESSION[$current_page]=1;

	$show_all = $_POST['show_all'];
	$search_regexp = $_POST['search_regexp'];
	$cdr_field = $_POST['cdr_field'];
	$start_year = $_POST['start_year'];
	$start_month = $_POST['start_month'];
	$start_day = $_POST['start_day'];
	$start_hour = $_POST['start_hour'];
	$start_minute = $_POST['start_minute'];
	$start_second = $_POST['start_second'];
	$end_year = $_POST['end_year'];
	$end_month = $_POST['end_month'];
	$end_day = $_POST['end_day'];
	$end_hour = $_POST['end_hour'];
	$end_minute = $_POST['end_minute'];
	$end_second = $_POST['end_second'];

	if ($show_all=="Show All") {

		$_SESSION['cdrviewer_search_val']="";
		$_SESSION['cdrviewer_search_cdr_field']="";
		$_SESSION['cdrviewer_search_start']="";
		$_SESSION['cdrviewer_search_end']="";

	}

	else {
		if ($cdr_field=="none") {
			$_SESSION['cdrviewer_search_val']="";
			$_SESSION['cdrviewer_search_cdr_field']="";
		} else {
			$_SESSION['cdrviewer_search_val']=$search_regexp;
			$_SESSION['cdrviewer_search_cdr_field'] = $cdr_field ;
		}
		if ($start_year=="none")
			$_SESSION['cdrviewer_search_start']="";
		else
			$_SESSION['cdrviewer_search_start']=$start_year."-".$start_month."-".$start_day." ".$start_hour.":".$start_minute.":".$start_second;
		if ($end_year=="none")
			$_SESSION['cdrviewer_search_end']="";
		else
			$_SESSION['cdrviewer_search_end']=$end_year."-".$end_month."-".$end_day." ".$end_hour.":".$end_minute.":".$end_second;
	}
}

if ($export == "Export") {

	$search_regexp=$_SESSION['cdrviewer_search_val'];
	$cdr_field = $_SESSION['cdrviewer_search_cdr_field'];


	if (($cdr_field!="") && ($search_regexp!="")) $sql_search.=" and ".$cdr_field.' like "%'.$search_regexp.'%"' ;


	$search_start=$_SESSION['cdrviewer_search_start'];
	$search_end=$_SESSION['cdrviewer_search_end'];

	if (($search_start != "" ) ||  ($search_start != "" ) || ($sql_search!="" ))  {
		register_shutdown_function(function(){
			$error = error_get_last();
			if(null !== $error)
			{
				require("../../../../web/common/cfg_comm.php");
				echo "Not enough resources to export the selected CDRs!<br>";
				echo "Please use a filter that will return fewer results<br>";
				echo "If that is not possible, consider increasing your PHP memory allocated and/or execution time<br>";
				print_back_input();
				exit();
			}
		});
		cdr_put_to_download($search_start,$search_end,$sql_search,"cdr-temp.csv");
	}
	exit();
}


require("lib/".$page_id.".main.js");
require("template/".$page_id.".main.php");
require("template/footer.php");
exit();

?> 
