<?php
/*
 * Copyright (C) 2026 OpenSIPS Project
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

require("lib/mi.inc.php");

session_start();

function reply($payload, $code = 200)
{
	http_response_code($code);
	header('Content-Type: application/json');
	echo json_encode($payload);
	exit();
}

function fail($code, $message)
{
	reply(array("ok" => false, "error" => $message), $code);
}

if (!isset($_SESSION['user_login']))
	fail(401, "Session expired, please log in again");

// get_priv() renders an HTML error page when the tool is not granted; test the
// same condition first so this endpoint can answer with JSON instead
if ($_SESSION['user_priv'] != "*" && $_SESSION['user_tabs'] != "*" &&
    !in_array("mi", explode(",", $_SESSION['user_tabs'])))
	fail(403, "You do not have permissions to access this tool");

get_priv("mi");

// a box is addressed by its position in mi_boxes(), never by its MI URL, so a
// caller can only ever reach a box an administrator already configured
$boxes = mi_boxes();
$box_id = isset($_GET['box']) ? $_GET['box'] : "0";
if (!ctype_digit((string)$box_id) || !isset($boxes[(int)$box_id]))
	fail(400, "Unknown box");
$box = $boxes[(int)$box_id];

$op = isset($_GET['op']) ? $_GET['op'] : "";

if ($op == "commands") {
	$res = mi_call("which", NULL, $box['url']);
	if (!$res['ok'])
		fail(502, $res['error']);
	reply(array("ok" => true, "commands" => $res['data']));
}

/*
 * "which <cmd>" is also the only reliable existence test: the plain command
 * list holds just canonical names (core:uptime), while aliases such as
 * "uptime" or "dlg_end_dlg" are absent from it yet perfectly runnable. A
 * command that exists answers with at least one signature -- an empty one
 * when it takes no parameters -- and an unknown one fails outright.
 */
if ($op == "params") {
	$cmd = isset($_GET['command']) ? $_GET['command'] : "";
	if ($cmd === "")
		fail(400, "Missing command");

	$res = mi_call("which", array($cmd), $box['url']);
	$known = $res['ok'] && is_array($res['data']) && count($res['data']) > 0;

	reply(array(
		"ok"         => true,
		"known"      => $known,
		"signatures" => $known ? $res['data'] : array()
	));
}

if ($op == "run") {
	if ($_SESSION['read_only'])
		fail(403, "User with Read-Only Rights");

	if (!mi_csrf_ok())
		fail(403, "Invalid CSRF token");

	$line = isset($_POST['line']) ? $_POST['line'] : "";
	$parsed = parse_command($line);
	if (isset($parsed['error']))
		fail(400, $parsed['error']);

	reply(mi_call($parsed['cmd'], $parsed['params'], $box['url']));
}

fail(400, "Unknown op");
