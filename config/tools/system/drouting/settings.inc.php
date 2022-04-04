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

$config->drouting = array(
	"title0" => array(
		"type" => "title",
		"title" => "General settings"
	),
	"routing_partition" => array(
		"default" => "",
		"opt"     => "y",
		"name"    => "Routing partition",
		"type"    => "text",
		"tip"     => "The name of the DR partition to work with; if empty, it will assume no partition support in OpenSIPS",
		"validation_regex" => null,
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Linked system",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
		"tip"     => "As OCP can manage multiple OpenSIPS instances, this is the association
		 ID pointing to the group of servers (system) which needs to be provision with this drouting information."
	),


	"title1" => array(
		"type" => "title",
		"title" => "Gateway settings"
	),
	"gateway_types_file" => array(
		"default" => array(
			"0" => "Gateway",
        	),
		"name"    => "Gateway's Types",
		"type"    => "json",
		"json_format" => "object",
		"example" => "{
	\"0\" : \"In Gateway\",
	\"1\" : \"Out Gateway\",
	\"2\" : \"Core Router\",
	\"3\" : \"Other\"
}"
	),
	"default_gw_type" => array(
		"default" => 1,
		"name"    => "Default gateway type",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"gw_attributes" => array(
		"default" => array(),
		"name"    => "Gateway attributes",
		"type"    => "json",
		"example" => "{
	\"display_name\" : \"Attributes\",
	\"add_prefill_value\" : \"\",
	\"validation_regexp\" : NULL,
	\"validation_error\" : NULL,
}"
	),

	"title2" => array(
		"type" => "title",
		"title" => "Rules settings"
	),
	"group_ids_file" => array(
		"default" => array(
			0 => "Default"
		),
		"name"    => "Routing groups",
		"type"    => "json",
		"json_format" => "object",
		"example" => "{
	\"0\" : \"Default\",
	\"1\" : \"Free\",
	\"2\" : \"Premium\",
}"
	),

	"title3" => array(
		"type" => "title",
		"title" => "Group settings"
	),
	"group_id_method" => array(
		"default" => "static",
		"name"    => "Group ID method",
		"type"    => "text",
		"validation_regex" => null,
		"tip"     => "How the handle the drouting groups : 'static; -
		 the groups are statically configured via the 'Settings' tab ; 'dynamic' - the groups are read from the DB group table."
	),
	"default_domain" => array(
		"default" => "yourdomain.net",
		"name"    => "Default domain",
		"type"    => "text",
		"tip"     => "The SIP domain to be used for users when inserting a new record into the group table",
		"validation_regex" => null,
	),

	"title4" => array(
		"type" => "title",
		"title" => "DB settings"
	),
	"table_gateways" => array(
		"default" => "dr_gateways",
		"name"    => "Table gateways",
		"type"    => "text",
		"validation_regex" => $table_regex,
		"tip"     => "Database table for storing the drouting data"
	),
	"table_groups" => array(
		"default" => "dr_groups",
		"name"    => "Table groups",
		"type"    => "text",
		"validation_regex" => $table_regex,
		"tip"     => "Database table for storing the drouting data"
	),
	"table_rules" => array(
		"default" => "dr_rules",
		"name"    => "Table rules",
		"type"    => "text",
		"validation_regex" => $table_regex,
		"tip"     => "Database table for storing the drouting data"
	),
	"table_carriers" => array(
		"default" => "dr_carriers",
		"name"    => "Table carriers",
		"type"    => "text",
		"validation_regex" => $table_regex,
		"tip"     => "Database table for storing the drouting data"
	),

	"title5" => array(
		"type" => "title",
		"title" => "Display settings"
	),
	"tabs" => array(
		"default" => "gateways.php,carriers.php,rules.php,groups.php",
		"name"    => "Tool's Tabs",
		"options" => array('Gateways'=>'gateways.php', 'Carriers'=>'carriers.php', 'Rules' => 'rules.php', 'Groups' => 'groups.php'),
		"tip"    => "List of available tabs for dynamic routing tool",
		"type"    => "checklist"
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
		"tip"    => "Control over the pagination when displaying the dynamic routing rules",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),


);
