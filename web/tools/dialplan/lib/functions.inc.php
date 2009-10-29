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

######################
# Database Functions #
######################

function db_connect()
{
	global $config;

        if (isset($config->db_host_dialplan) && isset($config->db_user_dialplan) && isset($config->db_name_dialplan) ) {
                $config->db_host = $config->db_host_dialplan;
                $config->db_port = $config->db_port_dialplan;
                $config->db_user = $config->db_user_dialplan;
                $config->db_pass = $config->db_pass_dialplan;
                $config->db_name = $config->db_name_dialplan;
        }

	$link = @mysql_connect($config->db_host, $config->db_user, $config->db_pass);

	if (!$link) {
		die("Could not connect to MySQL Server: " . mysql_error());
		exit();
	}
	$selected = @mysql_select_db($config->db_name, $link);
	if (!$selected) {
		die("Could not select '$config->db_name' database." . mysql_error());
		exit();
	}
	return $link;
}

function db_close()
{
	mysql_close();
}

##########################
# End Database Functions #
##########################


function get_priv()
{
	if ($_SESSION['user_tabs']=="*") $_SESSION['read_only'] = false;
	else {
		$available_tabs = explode(",", $_SESSION['user_tabs']);
		$available_priv = explode(",", $_SESSION['user_priv']);

		$key = array_search("dialplan", $available_tabs);


		if ($available_priv[$key]=="read-only"){
			$_SESSION['read_only'] = true;
		}
		if ($available_priv[$key]=="read-write"){
			$_SESSION['read_only'] = false;
		}
	}
	return;
}

function get_proxys_by_assoc_id($my_assoc_id){

	$global="../../../config/boxes.global.inc.php";
	require($global);

	$mi_connectors=array();

	for ($i=0;$i<count($boxes);$i++){

		if ($boxes[$i]['assoc_id']==$my_assoc_id){

			$mi_connectors[]=$boxes[$i]['mi']['conn'];

		}

	}

	return $mi_connectors;
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
