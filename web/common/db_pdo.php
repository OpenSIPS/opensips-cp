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

?>
