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

$config->callcenter = array(
	"db_config" => array(
		"default" => 0,
		"name" => "DB configuration",
		"type" => "dropdown",
		"options" => get_db_configs(),
		"tip" => "DB configuration to use for this tool"
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Linked system",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
	),
	"submenu_items" => array(
		"default" => array(
			"0"	=> "Agents",
			"1"	=> "Flows",
			"2"	=> "CDRs"),
		"name" => "Submenu Items",
		"type" => "json"
	),

	"title0" => array(
		"type" => "title",
		"title" => "Agents"
	),
	"agents_custom_table" => array(
		"default" => "cc_agents",
		"name" => "Agents custom table",
		"validation_regex" => $table_regex,
		"type" => "text"
	),
	"agents_per_page" => array(
		"default" => 5,
		"name"    => "Results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"agents_page_range" => array(
		"default" => 3,
		"name"    => "Results page range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),

	"title1" => array(
		"type" => "title",
		"title" => "Flows"
	),
	"flows_custom_table" => array(
		"default" => "cc_flows",
		"name" => "Flows custom table",
		"validation_regex" => $table_regex,
		"type" => "text"
	),
	"flows_per_page" => array(
		"default" => 5,
		"name"    => "Results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"flows_page_range" => array(
		"default" => 3,
		"name"    => "Results page range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),

	"title2" => array(
		"type" => "title",
		"title" => "CDRs"
	),
	"cdrs_custom_table" => array(
		"default" => "cc_cdrs",
		"name" => "CDRs custom table",
		"validation_regex" => $table_regex,
		"type" => "text"
	),
	"cdrs_per_page" => array(
		"default" => 5,
		"name"    => "Results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"cdrs_page_range" => array(
		"default" => 3,
		"name"    => "Results page range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
);
