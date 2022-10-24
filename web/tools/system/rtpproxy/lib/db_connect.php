<?php
/*
 * Copyright (C) 2018 OpenSIPS Project
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
if (get_settings_value("db_config")) {
	$configuration = get_settings_value("db_config");
	foreach($_SESSION['db_config'][$configuration] as $param => $value) {
		$param_name = $param."_".$_SESSION['current_tool'];
		$config->$param_name = $value;
	}
}
require_once("../../../../config/db.inc.php");

global $config;
if (isset($config->db_host_rtpproxy) && isset($config->db_user_rtpproxy) && isset($config->db_name_rtpproxy) ) {
	$config->db_host = $config->db_host_rtpproxy;
	$config->db_port = $config->db_port_rtpproxy;
	$config->db_user = $config->db_user_rtpproxy;
	$config->db_pass = $config->db_pass_rtpproxy;
	$config->db_name = $config->db_name_rtpproxy;
}

$dsn = $config->db_driver . ':host=' . $config->db_host . ';dbname='. $config->db_name;
try {
	$link = new PDO($dsn, $config->db_user, $config->db_pass);
} catch (PDOException $e) {
	error_log(print_r("Failed to connect to: ".$dsn, true));
	print "Error!: " . $e->getMessage() . "<br/>";
	die;
}

?>
