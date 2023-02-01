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

$config->cdrviewer = array(
	"title0" => array(
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
	"cdr_table" => array(
		"default" => "acc",
		"name"    => "CDR table",
		"type"    => "text",
		"validation_regex" => $table_regex,
	),
	"cdr_id_field_name" => array(
		"default" => "id",
		"name"    => "CDR ID field name",
		"type"    => "text",
		"validation_regex" => null,
	),
	"sip_call_id_field_name" => array(
		"default" => "callid",
		"name"    => "SIP call ID field name",
		"type"    => "text",
		"validation_regex" => null,
	),

	"title1" => array(
		"type" => "title",
		"title" => "Display settings"
	),
	"show_field" => array(
		"default" => array(
			"time" => "Time",
			"method" => "Method",
			"callid" => "Sip Call ID",
			"sip_code" => "Sip Code",
			"sip_reason" => "Sip Reason",
			"setuptime" => "Setup Time",
			"duration" => "Duration",
			"from_tag" => "Sip From Tag",
			"to_tag" => "Sip To Tag"
		),
		"type" => "json",
		"name" => "Show Field"
	),
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
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),

	"title2" => array(
		"type" => "title",
		"title" => "CDR exporting"
	),
	"cdr_repository_path" => array(
		"default" => "/var/lib/opensips_cdrs",
		"name"    => "CDR repository path",
		"type"    => "text",
		"validation_regex" => null,
	),
	"cdr_set_field_names" => array(
		"default" => 1,
		"name"    => "CDR set field names",
		"options" => array('Off'=>'0', 'On'=>'1'),
		"type"    => "dropdown",
	),
	"delay" => array(
		"default" => 3600,
		"name"    => "Delay",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"export_csv" => array(
		"default" => array (
			"id" => "CDR ID",
			"time" => "Call Start Time",
			"method" => "SIP Method",
			"callid" => "Sip Call ID",
			"sip_code" => "Sip Code",
			"sip_reason" => "Sip Reason",
			"setuptime" => "Setup Time",
			"duration" => "Duration",
			"from_tag" => "Sip From Tag",
			"to_tag" => "Sip To Tag"
		),
		"type" => "json",
		"name" => "Export CSV fields"
	),
    );
