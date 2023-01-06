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

$config->dialplan = array(
	"title0" => array(
		"type" => "title",
		"title" => "General settings"
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Linked System",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
		"tip"     => "As OCP can manage multiple OpenSIPS instances, this is the association 
		ID pointing to the group of servers (system) which needs to be provision with this dialplan information.",
	),
	"dialplan_attributes_mode" => array(
		"default" => 1,
		"name"    => "Dialplan attributes mode",
		"options" => array('Checkboxes'=>'0', 'Text'=>'1'),
		"type"    => "dropdown",
		"tip" 	  => "How the interpret the attributes of the rules: 0 - an checkbox with predefined value; 1 - an opaque string",
	),
	"attrs_cb" => array(
        	"default" => array(),
		"name" => "Attributes list",
		"type" => "json",
		"tip" => "If \$dialplan_attributes_mode is set to 1, this array must define the possible attribute options.
		 Each options is a char, the resulting string being the set of the options/chars that are enabled.",
		"example" => "{
	\"attr A\": \"Description attr A\",
	\"attr B\": \"Description attr B\",
	\"attr C\": \"Description attr C\",
}"
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
	"table_dialplan" => array(
		"default" => "dialplan",
		"name"    => "Table Dialplan",
		"type"    => "text",
		"validation_regex" => $table_regex,
		"tip"     => "The database table name for storing the diaplan rules",
	),

 	"title2" => array(
 		"type" => "title",
		"title" => "Dialplan ID settings"
	),

	"dialplan_groups_mode" => array(
		"name" => "Dialplan ID Mode",
		"type" => "dropdown",
		"options" => array('Input'=>'input', 'Static'=>'static','Pre-Defined values'=>'array','Database'=>'database'),
		"tip"	  => "Naming of the Dialplan groups (versus IDs) is possible here, in a static (hardcoded), array (pre-defined values) or dynamic (via DB) way.",
		"default" => "input"
	),

	"dialplan_groups" => array(
		"default" => array(),
		"name" => "Dialplan ID descripton",
		"type" => "json",
		"json_format" => "object",
		"tip"	  => "Mandatory if 'Dialplan ID Mode' is not 'input', represents the JSON description of the groups.",
		"example" => "
/* Static way - simply specify the global Dialplan ID to be used */
1

/* Array way - specify the possible Dialplan IDs and their name */
{
	\"2\": \"Set 2\",
	\"4\": \"Set 4\"
}

/* Dynamic way */
/* The following config presumes that a
 * dialplan_maps table exists with two fields:
 * - id: stores the group id
 * - name: stores the name of the dialplan id
 */
{
	\"table\": \"dialplan_maps\",
	\"id\"	: \"id\",
	\"name\": \"name\",
}"
	),

	"dialplan_partition" => array(
		"default" => "",
		"opt"     => "y",
		"name"    => "Dialplan partition",
		"type"    => "text",
		"tip"     => "The name of the dialplan partition to work with; if empty, it will assume no partition support in OpenSIPS",
		"validation_regex" => null,
	),

	"title3" => array(
		"type" => "title",
		"title" => "Display settings"
	),
	"results_per_page" => array(
		"default" => 30,
		"name"    => "Results per page",
		"tip"    => "Number of results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"results_page_range" => array(
		"default" => 10,
		"name"    => "Results page range",
		"tip"    => "Control over the pagination when displaying the dialplan rules",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
);
