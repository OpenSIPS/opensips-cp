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

require_once(__DIR__."/../../../../common/mi_comm.php");
require_once(__DIR__."/../../../../common/cfg_comm.php");

function mi_boxes()
{
	require(__DIR__."/../../../../../config/boxes.global.inc.php");

	$list = array();
	foreach ($boxes as $box) {
		if (empty($box['mi']['conn']))
			continue;
		$name = $box['name'];
		if ($name === "" || $name === NULL)
			$name = $box['desc'];
		if ($name === "" || $name === NULL)
			$name = $box['mi']['conn'];
		$list[] = array("name" => $name, "url" => $box['mi']['conn']);
	}

	return $list;
}

/*
 * The shared csrfguard_* helpers issue single-use tokens, which cannot work
 * here: the console never reloads and may have several commands in flight at
 * once, so they would all carry the same token and only the first would pass.
 * One token per session instead.
 */
function mi_csrf_token()
{
	if (empty($_SESSION['mi_csrf']))
		$_SESSION['mi_csrf'] = bin2hex(random_bytes(32));

	return $_SESSION['mi_csrf'];
}

function mi_csrf_ok()
{
	return isset($_POST['csrf']) && !empty($_SESSION['mi_csrf'])
		&& hash_equals($_SESSION['mi_csrf'], $_POST['csrf']);
}

/* split on whitespace, except inside a [ ] list or a " " quote */
function mi_tokenize($line)
{
	$tokens = array();
	$cur = "";
	$depth = 0;
	$quoted = false;

	for ($i = 0; $i < strlen($line); $i++) {
		$c = $line[$i];
		if ($c == '"') {
			$quoted = !$quoted;
		} else if (!$quoted) {
			if ($c == '[')
				$depth++;
			if ($c == ']' && --$depth < 0)
				return NULL;

			if ($depth == 0 && ctype_space($c)) {
				if ($cur !== "") {
					$tokens[] = $cur;
					$cur = "";
				}
				continue;
			}
		}
		$cur .= $c;
	}

	if ($depth != 0 || $quoted)
		return NULL;
	if ($cur !== "")
		$tokens[] = $cur;

	return $tokens;
}

function mi_value($raw)
{
	/*
	 * The outer quotes delimit the value and are not part of it -- quoting is
	 * the only way to keep a space in a value, and "" is how the empty string
	 * is asked for. They come off first, so what is left is read exactly as
	 * if it had been typed bare: "[a,b]" is the list [a,b].
	 */
	if (strlen($raw) >= 2 && $raw[0] == '"' && substr($raw, -1) == '"')
		$raw = substr($raw, 1, -1);

	if (strlen($raw) < 2 || $raw[0] != '[' || substr($raw, -1) != ']')
		return $raw;

	$inner = trim(substr($raw, 1, -1));

	return $inner === "" ? array() : array_map('trim', explode(",", $inner));
}

/*
 * Parameters that are lists, as name => position in the signature.
 *
 * "which" reports parameter names and nothing about their types, so a list
 * cannot be told from a value by asking the box -- this is read off the module
 * documentation. Both spellings are kept, the pre-3.0 name and the
 * module:command one, since a box may be either.
 *
 * Checked against OpenSIPS 4.0. b2b_logic:trigger_scenario is deliberately
 * absent: the scenario_params it used to take is gone, and what sits in that
 * position now is "context", a plain string. To add a command, run
 * "core:which <cmd>" for the position and read the module docs for the type.
 */
function mi_list_params($cmd)
{
	static $lists = array(
		"get_statistics"                    => array("statistics" => 0),
		"statistics:get"                    => array("statistics" => 0),
		"list_statistics"                   => array("statistics" => 0),
		"statistics:list"                   => array("statistics" => 0),
		"reset_statistics"                  => array("statistics" => 0),
		"statistics:reset"                  => array("statistics" => 0),
		"fs_subscribe"                      => array("events" => 1),
		"freeswitch_scripting:subscribe"    => array("events" => 1),
		"fs_unsubscribe"                    => array("events" => 1),
		"freeswitch_scripting:unsubscribe"  => array("events" => 1),
		"raise_event"                       => array("params" => 1),
		"evi:raise"                         => array("params" => 1),
		"dlg_push_var"                      => array("DID" => 2),
		"dialog:push_var"                   => array("DID" => 2),
		"cluster_broadcast_mi"              => array("cmd_params" => 2),
		"clusterer:broadcast_mi"            => array("cmd_params" => 2),
		"trace_start"                       => array("filter" => 2),
		"tracer:start"                      => array("filter" => 2),
		"dfks_set_feature"                  => array("values" => 4),
		"presence_dfks:set_feature"         => array("values" => 4)
	);

	return isset($lists[$cmd]) ? $lists[$cmd] : array();
}

/*
 * "cmd a b"           -> params [a, b]              (positional)
 * "cmd x=1 y=2"       -> params {x:1, y:2}          (named)
 * "cmd [a,b]"         -> params [[a, b]]            (a list argument)
 * "cmd f=[a,b]"       -> params {f:[a, b]}
 *
 * OpenSIPS distinguishes a positional array from a named object purely by the
 * JSON type, so the two forms must never be blended into one params value.
 *
 * $group is the position of a parameter that swallows everything from there on
 * as one list -- "statistics:get shmem: net:" with group 0 sends
 * [["shmem:", "net:"]], which is the array that command wants. The console
 * works it out from the signature the box reported and passes it in; a line
 * with fewer values than that never groups, so brackets stay the way to write
 * a list anywhere else.
 */
function parse_command($line, $group = -1)
{
	$tokens = mi_tokenize($line);
	if ($tokens === NULL)
		return array("error" => "Unbalanced [ ] or \" in the command line");
	if (empty($tokens))
		return array("error" => "Empty command");

	$cmd = array_shift($tokens);
	$named = array();
	$positional = array();

	foreach ($tokens as $t) {
		$eq = strpos($t, '=');
		if ($eq > 0 && $t[0] != '[')
			$named[substr($t, 0, $eq)] = mi_value(substr($t, $eq + 1));
		else
			$positional[] = mi_value($t);
	}

	if (!empty($named) && !empty($positional))
		return array("error" => "Cannot mix named and positional parameters");

	if ($group >= 0 && empty($named) && count($positional) > $group + 1)
		array_splice($positional, $group, count($positional),
			array(array_slice($positional, $group)));

	/*
	 * One value for a list parameter is still a list -- "statistics:get shmem:"
	 * asks for one statistic, not for a statistic named shmem: in the singular.
	 * Nothing to do when it is already one, which is how a folded tail and the
	 * bracket form arrive here.
	 */
	foreach (mi_list_params($cmd) as $name => $at) {
		if (isset($named[$name]) && !is_array($named[$name]))
			$named[$name] = array($named[$name]);
		if (isset($positional[$at]) && !is_array($positional[$at]))
			$positional[$at] = array($positional[$at]);
	}

	$params = empty($named) ? $positional : $named;

	return array("cmd" => $cmd, "params" => empty($params) ? NULL : $params);
}

/*
 * mi_command() reports failures two different ways: transport problems append a
 * string to $errors, while OpenSIPS-level failures replace $errors wholesale
 * with a ['code'=>..,'message'=>..] object. Normalize both into one envelope.
 */
function mi_call($command, $params, $url)
{
	$errors = array();
	$data = mi_command($command, $params, $url, $errors, true);

	if (empty($errors))
		return array("ok" => true, "data" => $data);

	if (isset($errors['code'])) {
		$text = "[".$errors['code']."] ".mi_error_text($errors);

		/*
		 * "Invalid params" on its own leaves the user guessing which one. The
		 * JSON-RPC "data" member is where OpenSIPS says -- "Bad PID", "Bad
		 * log level" -- and mi_error_text() only reads "message", so it is
		 * appended here rather than thrown away.
		 */
		if (isset($errors['data']) && $errors['data'] !== "")
			$text .= ": ".(is_string($errors['data'])
				? $errors['data'] : json_encode($errors['data']));

		return array("ok" => false, "error" => $text);
	}

	return array("ok" => false, "error" => mi_error_text($errors));
}
