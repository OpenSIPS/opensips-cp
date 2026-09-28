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

require_once(__DIR__."/../../../../config/session.inc.php");
require(__DIR__."/../../../../config/db.inc.php");
require(__DIR__."/../../../../web/common/cfg_comm.php");

// the details include the password: only for users who may edit profiles
get_priv("db_config");
if ($_SESSION['read_only']) {
	echo("<b>You do not have permissions to view this configuration</b>");
	exit();
}

$db_id = $_GET['db_id'] ?? "";
if ($db_id == "0") {
	$host = $config->db_host;
	$port = $config->db_port;
	$user = $config->db_user;
	$name = $config->db_name;
	$pass = $config->db_pass;
} else {
	load_db_config();
	if (!isset($_SESSION['db_config'][$db_id])) {
		echo("<b>Unknown configuration</b>");
		exit();
	}
	$profile = $_SESSION['db_config'][$db_id];
	$host = $profile['db_host'];
	$port = $profile['db_port'];
	$user = $profile['db_user'];
	$name = $profile['db_name'];
	$pass = $profile['db_pass'];
}
?>
	<table width="400" border="0">
		<tr>
			<td class="mainTitle">
					Configuration details
			</td>
		</tr>

		<tr>
			<td>

				<table class="ttable" width="100%" cellspacing="2" cellpadding="2" border="0">
				<?php
                echo("<tr><td>DB host</td><td>".htmlspecialchars($host ?? "")."</td></tr>");
                echo("<tr><td>DB port</td><td>".htmlspecialchars($port ?? "")."</td></tr>");
                echo("<tr><td>DB user</td><td>".htmlspecialchars($user ?? "")."</td></tr>");
                echo("<tr><td>DB name</td><td>".htmlspecialchars($name ?? "")."</td></tr>");
                echo("<tr><td>DB pass</td><td>".htmlspecialchars($pass ?? "")."</td></tr>");
				?>
				</table>

			</td>
		</tr>

		<tr>
			<td align="center">
				<? print_back_input(); ?>
			</td>
		</tr>
	

	</table>

