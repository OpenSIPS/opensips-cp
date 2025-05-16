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

// $table_regex is used to validate custom tables names, you can leave this here
// even if you don't add custom tables
global $table_regex;
global $config;

$config->config = array(
	"title0" => array(
		"type" => "title",
		"title" => "Database"
	),
	"db_config" => array(
		"default" => 0,
		"name" => "DB configuration",
		"type" => "dropdown",
		"options" => get_db_configs(),
		"tip" => "DB configuration to use for this tool."
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"	=> "Linked system",
		"options" => get_assoc_id(),
		"type"	=> "dropdown",
		"tip"	 => "As OCP can manage multiple OpenSIPS instances, this is the association 
		ID pointing to the group of servers (system) which needs to be provision with this siptrace status (on or off)."
	),

	"table" => array(
		"default" => "config",
		"name" => "Table",
		"type" => "text",
		"validation_regex" => $table_regex,
		"tip" => "Table that stores the configuration data."
	),
	"id" => array(
		"default" => "id",
		"name" => "ID Column",
		"type" => "text",
		"validation_regex" => "^[0-9]+$",
		"tip" => "The column used as a primary id for a configuration entry."
	),
	"name" => array(
		"default" => "name",
		"name" => "Name Column",
		"type" => "text",
		"validation_regex" => "^[a-zA-Z][a-zA-Z0-9]*$",
		"tip" => "The column used for the configuration's name."
	),
	"value" => array(
		"default" => "value",
		"name" => "Value Column",
		"type" => "text",
		"validation_regex" => "^[a-zA-Z][a-zA-Z0-9]*$",
		"tip" => "The column used for the configuration's value."
	),
	"description" => array(
		"default" => "description",
		"name" => "Description Column",
		"type" => "text",
		"validation_regex" => "^[a-zA-Z][a-zA-Z0-9]*$",
		"tip" => "The column used for configuration entry description.",
	),
);
