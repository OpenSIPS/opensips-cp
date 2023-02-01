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
global $table_regex;
global $config;

$config->domains = array(
	"title0" => array(
		"type" => "title",
		"title" => "General settings"
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Linked system",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
		"tip" 	  => "As OCP can manage multiple OpenSIPS instances, this is the association 
		ID pointing to the group of servers (system) which needs to be provision with this domain information."
	),
	"attributes" => array(
		"default" => "0",
		"name"    => "Attributes",
		"options" => array('No'=>'0', 'Yes'=>'1'),
		"type"    => "dropdown",
		"tip"     => "Indicates whether the domains use attributes"
	),
	"attributes_regex" => array(
		"default" => null,
		"name"    => "Attributes Format",
		"type"    => "text",
		"opt"     => "y",
		"tip"     => "Regular expression for enforcing when an attribute is provided"
	),

	"title1" => array(
		"type" => "title",
		"title" => "DB settings"
	),
	"db_config" => array(
			"default" => 0,
			"name" => "DB configuration",
			"type" => "dropdown",
			"options" => get_db_configs(),
			"tip" => "DB configuration to use for this tool"
	),
	"table_domains" => array(
		"default" => "domain",
		"name" => "Table Domains",
		"type" => "text",
		"validation_regex" => $table_regex,
		"tip"  => "The database table name for storing the domain entries"
	),
);
