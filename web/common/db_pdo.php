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

// for sqlite, db_name holds the path to the database file
function db_dsn($config) {
	if ($config->db_driver == "sqlite")
		return "sqlite:" . $config->db_name;
	return $config->db_driver . ':host=' . $config->db_host . ';dbname='. $config->db_name;
}

// quote a column name (needed for reserved words like order, desc):
// backticks on mysql, standard double quotes on pgsql and sqlite
function db_ident($name) {
	global $config;
	return $config->db_driver == "mysql" ? "`".$name."`" : '"'.$name.'"';
}

function db_pdo($dsn, $user, $pass, $attr = NULL) {
	if (strncmp($dsn, "sqlite:", 7) != 0)
		return new PDO($dsn, $user, $pass, $attr);

	// sqlite has no REGEXP or NOW() of its own, and its CONCAT() (3.44+) skips NULLs;
	// provide the mysql behaviour
	$regexp = function ($pattern, $subject) {
		if ($subject === NULL)
			return NULL;
		return preg_match('/'.str_replace('/', '\/', $pattern).'/i', $subject) ? 1 : 0;
	};
	$now = function () {
		return date('Y-m-d H:i:s');
	};
	$concat = function (...$args) {
		return in_array(NULL, $args, true) ? NULL : implode('', $args);
	};

	// open read-write only, so a wrong path fails instead of creating an empty database
	if (class_exists('Pdo\Sqlite')) {
		$link = new Pdo\Sqlite($dsn, NULL, NULL, array(Pdo\Sqlite::ATTR_OPEN_FLAGS => Pdo\Sqlite::OPEN_READWRITE));
		$link->createFunction('regexp', $regexp, 2);
		$link->createFunction('now', $now, 0);
		$link->createFunction('concat', $concat);
	} else {
		$link = new PDO($dsn, NULL, NULL, array(PDO::SQLITE_ATTR_OPEN_FLAGS => PDO::SQLITE_OPEN_READWRITE));
		$link->sqliteCreateFunction('regexp', $regexp, 2);
		$link->sqliteCreateFunction('now', $now, 0);
		$link->sqliteCreateFunction('concat', $concat);
	}
	return $link;
}

// the database $tool is set to use, as db_host, db_port, db_user, db_pass, db_name
// (and db_attr): the DB Config profile picked by its $profile_setting setting, else the
// $config->db_*_<tool> values of config/tools/<tool>/db.inc.php; empty means db.inc.php's
function db_tool_settings($tool, $profile_setting = "db_config") {
	global $config, $custom_config;
	require_once(__DIR__."/../../config/db.inc.php");

	// not every page connecting for $tool loads its db.inc.php (e.g. dashboard widgets);
	// callers passing no $profile_setting (admin tools) load it themselves, maybe before cfg_comm.php
	if ($profile_setting && ($path = get_tool_path($tool)) &&
			file_exists(__DIR__."/../../config/tools/".$path."/db.inc.php"))
		require_once(__DIR__."/../../config/tools/".$path."/db.inc.php");

	if ($profile_setting && ($id = get_settings_value_from_tool($profile_setting, $tool))) {
		if (!isset($_SESSION['db_config']))
			load_db_config();
		if (isset($_SESSION['db_config'][$id]))
			return $_SESSION['db_config'][$id];
		error_log("DB configuration ".$id." used by ".$tool." does not exist, using the default database");
	}

	$db = array();
	foreach (array("host", "port", "user", "pass", "name") as $param) {
		$name = "db_".$param."_".$tool;
		if (isset($config->$name))
			$db["db_".$param] = $config->$name;
	}
	return $db;
}

// connect to $db (as returned by db_tool_settings()) or, if it does not name a
// database, to the config/db.inc.php one; $config itself is never changed, so a
// tool's database does not leak into the next connection opened by the same page
function db_connect($db = NULL) {
	global $config;
	require_once(__DIR__."/../../config/db.inc.php");

	$c = clone $config;
	if (isset($db['db_host'], $db['db_user'], $db['db_name'])) {
		$c->db_host = $db['db_host'];
		$c->db_user = $db['db_user'];
		$c->db_pass = isset($db['db_pass']) ? $db['db_pass'] : '';
		$c->db_name = $db['db_name'];
		$c->db_attr = isset($db['db_attr']) ? $db['db_attr'] : NULL;
		// the per-tool db.inc.php samples add the port to the host themselves
		if (!empty($db['db_port']) && strpos($c->db_host, ";port=") === false)
			$c->db_host .= ";port=".$db['db_port'];
	}

	$dsn = db_dsn($c);
	try {
		return db_pdo($dsn, $c->db_user, $c->db_pass, isset($c->db_attr) ? $c->db_attr : NULL);
	} catch (PDOException $e) {
		error_log(print_r("Failed to connect to: ".$dsn, true));
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
}

?>
