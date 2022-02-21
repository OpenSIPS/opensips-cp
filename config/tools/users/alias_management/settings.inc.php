<?php
/*
 * Copyright (C) 2022 OpenSIPS Solutions
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

$config->alias_management = array(
    "results_per_page" => array(
		"default" => 25,
		"name"    => "Results per page",
		"tip"    => "Number of results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"results_page_range" => array(
		"default" => 10,
		"name"    => "Results page range",
		"tip"    => "Number of results page range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
	"table_aliases" => array(
		"default" => array( 
			"DBaliases" => "dbaliases"
		),
		"name"    => "Table aliases",
		"type"    => "json",
		"tip"     => "Parameter used for the aliases tables if there are more than the standard dbaliases table. The defined array has as key the label and as value the table name.For defining more 
		than one attribute/value pair, complete the list with identical elements separated by comma."
	),
	"alias_format" => array(
		"default" => "/^[0-9a-zA-Z]+/",
		"name" => "Alias Format",
		"tip" => "Pattern/regexp to validate the inserted aliases (in order to enforce a certain format for the aliases).",
		"type" => "text"
	)
    );