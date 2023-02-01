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

$config->group_management = array(
	"title0" => array(
		"type" => "title",
		"title" => "General settings"
	),
	"grps" => array(
		"default" =>  array(),
		"name"    => "Groups",
		"tip"     => "A list containing the groups that you are using in your OpenSIPS config file. These are custom values and they are define by the script writer.",
		"type"    => "json",
		"example" => "[
    \"grp_one\",
    \"grp_two\",
    \"grp_three\"
]"
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
	"table_groups" => array(
		"default" => "grp",
		"name"    => "Groups Table",
		"validation_regex" => $table_regex,
		"tip"     => "The name of the DB table where the groups (and mapping to SIP users) are stored.",
		"type"    => "text"
	),
	"title2" => array(
		"type" => "title",
		"title" => "Display settings"
	),
	"results_per_page" => array(
		"default" => 30,
		"name"    => "Results per page",
		"tip"     => "Number of results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"results_page_range" => array(
		"default" => 10,
		"name"    => "Results page range",
		"tip"     => "Sets number of pages per range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
);
