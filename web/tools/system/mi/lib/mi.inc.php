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

/* split on whitespace, except inside a [ ] list */
function mi_tokenize($line)
{
	$tokens = array();
	$cur = "";
	$depth = 0;

	for ($i = 0; $i < strlen($line); $i++) {
		$c = $line[$i];
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
		$cur .= $c;
	}

	if ($depth != 0)
		return NULL;
	if ($cur !== "")
		$tokens[] = $cur;

	return $tokens;
}

function mi_value($raw)
{
	if (strlen($raw) < 2 || $raw[0] != '[' || substr($raw, -1) != ']')
		return $raw;

	$inner = trim(substr($raw, 1, -1));

	return $inner === "" ? array() : array_map('trim', explode(",", $inner));
}

/*
 * "cmd a b"           -> params [a, b]              (positional)
 * "cmd x=1 y=2"       -> params {x:1, y:2}          (named)
 * "cmd [a,b]"         -> params [[a, b]]            (a list argument)
 * "cmd f=[a,b]"       -> params {f:[a, b]}
 *
 * OpenSIPS distinguishes a positional array from a named object purely by the
 * JSON type, so the two forms must never be blended into one params value.
 */
function parse_command($line)
{
	$tokens = mi_tokenize($line);
	if ($tokens === NULL)
		return array("error" => "Unbalanced [ ] in the command line");
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

	if (isset($errors['code']))
		return array("ok" => false, "error" => "[".$errors['code']."] ".mi_error_text($errors));

	return array("ok" => false, "error" => mi_error_text($errors));
}
