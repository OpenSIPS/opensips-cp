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

require("../../../common/forms.php");
$set_cache = array();

$perm_group_mode = get_settings_value("addresses_groups_mode");
$perm_group = get_settings_value("addresses_groups");
switch ($perm_group_mode) {
	case "database":
		$query = "SELECT " . $perm_group['id'] . " AS id, " .
			$perm_group['name'] . " AS name " .
			"FROM " . $perm_group['table'];

		$set_values = array();
		// fetch only the subset we need  for the groups that might match
		$stm = $link->prepare($query);
		if ($stm===FALSE) {
			die('Failed to issue query [' . $query . '], error message : ' . $link->errorInfo()[2]);
		}
		$stm->execute($set_values);
		$results = $stm->fetchAll();
		foreach ($results as $key => $value)
			$set_cache[$value['id']] = $value['name'];
		break;

	case "array":
		$set_cache = $perm_group;
		break;
}

switch ($perm_group_mode) {
	case "input":
		form_generate_input_text("Group", "The numerical ID of the addresses group",
			"grp", "n", $perm_set['grp'], 32, "^[0-9]+$");
		break;
	case "static":
		break;
	default:
		form_generate_select("Group", "The ID of the addresses group",
			"grp", "n", $perm_set['grp'],
			array_keys($set_cache), array_values($set_cache));
		break;
}

form_generate_input_text("IP", "Network IP",
	"ip", "n", $perm_set['ip'], 128, $re_ip);

form_generate_input_text("Mask", "Network Mask",
	"mask", "y", $perm_set['mask'], 4, "^[0-9]+$");

form_generate_input_text("Port", "Network Port, '0' means any port",
	"port", "y", $perm_set['port'], 5, "^[0-9]+$");

form_generate_select("Protocol", "Network Protocol",
	"proto", "n", $perm_set['proto'],
	array("any", "udp", "tcp", "tls"));

form_generate_input_text("Pattern", "Pattern used during IP matching",
	"pattern", "y", $perm_set['pattern'], 128, ".*");

form_generate_input_text("Context Info", "Context Provided when IP is matched",
	"context_info", "y", $perm_set['context_info'], 128, ".*");
