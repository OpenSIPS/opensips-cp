
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

require("../../../common/cfg_comm.php");
require("../../../common/mi_comm.php");
require("../../../../config/tools/system/tracer/db.inc.php");
require("../../../../config/db.inc.php");
require_once('../../../../config/boxes.load.php');
require("../../../../config/session.inc.php");
require("template/header.php");
require("lib/HEPpacket.php");
require("../../../common/forms.php");

if(isset($_GET['action'])) {
	$action = $_GET['action'];

	if ($action == "start") {
		session_write_close();

		set_time_limit(0);              // making maximum execution time unlimited
		ob_implicit_flush(1);           // Send content immediately to the browser on every statement which produces output
		ob_end_flush(); 


	}
} else {
	$action = NULL;
}
echo ('
<form action="tracer.php?action=start" method="post">
<table width="400" cellspacing="2" cellpadding="2" border="0" name="filters_table">
<tr align="center">
<td colspan="2" height="10" class="mainTitle">Set filters</td>
</tr>
');
form_generate_input_text("Caller", "Caller", "caller_id", "y", (isset($_GET['action'])?$_POST['caller_id']:NULL), 100, NULL);
form_generate_input_text("Callee", "Callee", "callee_id", "y", (isset($_GET['action'])?$_POST['callee_id']:NULL), 100, NULL);
form_generate_input_text("IP", "IP address", "ip_id", "y", (isset($_GET['action'])?$_POST['ip_id']:NULL), 100, $re_ip);

echo (' <tr>
<td colspan="2">
  <table cellspacing=20>
	<tr>
	  <td class="dataRecord" align="left" width="50%"><input type="submit" name="setFilters" onclick="return confirmStart()" value="Start" class="formButton" ></td>');
	  if ($action == "start") echo ('<td class="dataRecord" align="right" width="50%"><input onclick="window.location.href=\'tracer.php\';" class="formButton" value="Stop" type="button"></td>');
echo ('
	</tr>
  </table>
</tr>
</table>
</form>');
$i = 0;
$test_pack;
$spawn = null;
$socket = null;
if ($action == "start") {
	echo ('
	<table class="ttable" width="95%" cellspacing="1" cellpadding="1" border="1" align="right">
	<tr align="center">
	<th class="listTitle" align="center">Data</th>
	</tr>
	');
	echo ("Tracing started");
	$host = get_settings_value("hep_bind_ip");
	if (is_null($host))
		$host = 0;
	$port = get_settings_value("hep_bind_port");
	if (is_null($port))
		$port = 9060;
	$adv_ip = get_settings_value("hep_advertised_ip");
	$adv_port = get_settings_value("hep_advertised_port");
	$prefix = get_settings_value("hep_trace_identifier_prefix");
	$random = substr(sha1(rand()), 0, 6);

	register_shutdown_function(function() {
		$res = mi_command("trace_stop", array("id" => $prefix.$random), $boxes[0]['mi_conn'], $errors);
	});

	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("create fail\n");
	if (!socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1) ||
	!socket_set_option($socket, SOL_SOCKET, SO_REUSEPORT, 1)) {
		echo socket_strerror(socket_last_error($socket));
		exit;
	}
	$result = socket_bind($socket, $host, $port) or die(" bind fail\n");
	$filter = [];
	if (isset($_POST['caller_id'])) {
		$filter['caller'] = $_POST['caller_id'];
	}
	if (isset($_POST['callee_id'])) {
		$filter['callee'] = $_POST['callee_id'];
	}
	if (isset($_POST['ip_id'])) {
		$filter['ip'] = $_POST['ip_id'];
	}
	$res = mi_command("trace_start", array("id" => $prefix.$random, "uri" => "hep:".$adv_ip.":".$adv_port.";transport=tcp;version=3", "filter" => $filter), $boxes[0]['mi_conn'], $errors);

	$result = socket_listen($socket, 3) or die(" listen fail\n");
	$i = 0;
	while (true) {
		$spawn = socket_accept($socket) or die(" accept fail\n");
		echo ("<br>Accepted<br>");
		$remaining = "";
		do {
			if (false === ($buf = socket_read($spawn, 2048, PHP_NORMAL_READ))) {
				echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
				break 2;
			}
			
			$remaining .= bin2hex($buf);
			if (substr($remaining, 0, 8) != "48455033") {
				break 2;
				echo "Big red error!!!";
			} else {
				$pack_length = hexdec(substr($remaining, 8, 4));
				if (strlen($remaining)/2 > $pack_length) {
					$bytes = array_map(function($in) {
						return pack("H*", $in);
					}, str_split(substr($remaining, 12), 2));
					 $hep = new HEPpacket($bytes);
					 $hep->parse();
					 $remaining = substr($remaining, $pack_length * 2);
					 echo ("<tr><td>");
					 echo (str_replace("\n", "<br>", $hep->get_meta()));
					 echo ("<br>");
					 echo (str_replace("\n", "<br>", $hep->get_data()));
					 echo ("</td></tr>");
					 $i++;
					 if ($i == 5)
					 	break 2;
				} else continue;
			}
		} while (true);
		if ($spawn)
			socket_close($spawn);
	}
	$res = mi_command("trace_stop", array("id" => $prefix.$random), $boxes[0]['mi_conn'], $errors);

}



if ($spawn)
	socket_close($spawn);
if ($socket)
	socket_close($socket);
die(1);
?>
