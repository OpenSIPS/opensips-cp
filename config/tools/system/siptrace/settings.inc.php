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

$config->siptrace = array(
	"title0" => array(
		"type" => "title",
		"title" => "General settings"
	),
	"proxy_list" => array(
		"default" => array(),
		"name" => "Proxy list",
		"type" => "json",
		"tip"  => "An array of SIP interfaces (protocol, IP address and port) to be recognized as belonging to your OpenSIPS servers -
		 you must provide at least one entry. This iss very important to be correctly provision, otherwise the tool will not be able to properly graph the SIP flow (as it will not know which entity in the flow is your OpenSIPS).",
		"example" => "[
    \"udp:1.2.3.4:5060\",
    \"tcp:1.2.3.4:5060\"
]"
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"	=> "Linked system",
		"options" => get_assoc_id(),
		"type"	=> "dropdown",
		"tip"	 => "As OCP can manage multiple OpenSIPS instances, this is the association 
		ID pointing to the group of servers (system) which needs to be provision with this siptrace status (on or off)."
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
	"table_trace" => array(
		"default" => "sip_trace",
		"name" => "Table Trace",
		"type" => "text",
		"validation_regex" => $table_regex,
		"tip"  => "the database table name for storing the siptrace data"
	),


	"title0" => array(
		"type" => "title",
		"title" => "Display settings"
	),
	"results_per_page" => array(
		"default" => 30,
		"name"	=> "Results per page",
		"tip"	=> "Number of results per page",
		"type"	=> "number",
		"validation_regex" => "^[0-9]+$",
	),
	"results_page_range" => array(
		"default" => 10,
		"name"	=> "Results page range",
		"tip"	=> "Control over the pagination when displaying the siptrace records",
		"type"	=> "number",
		"validation_regex" => "^[0-9]+$",
	),
	"from_color" => array(
		"default" => "black",
		"name" => "From color",
		"type" => "text"
	),
	"to_color" => array(
		"default" => "white",
		"name" => "To color",
		"type" => "text"
	),
	"callid_color" => array(
		"default" => "black",
		"name" => "Call ID color",
		"type" => "text"
	),
	"cseq_color" => array(
		"default" => "white",
		"name" => "CSeq color",
		"type" => "text"
	),
	"regexp_color" => array(
		"default" => "navy",
		"name" => "Regex color",
		"type" => "text"
	),
	"from_bgcolor" => array(
		"default" => "yellow",
		"name" => "From bgcolor",
		"type" => "text"
	),
	"to_bgcolor" => array(
		"default" => "blue",
		"name" => "To bgcolor",
		"type" => "text"
	),
	"callid_bgcolor" => array(
		"default" => "orange",
		"name" => "Call ID bgcolor",
		"type" => "text"
	),
	"cseq_bgcolor" => array(
		"default" => "navy",
		"name" => "CSeq bgcolor",
		"type" => "text"
	),
	"regexp_bgcolor" => array(
		"default" => "red",
		"name" => "Regex bgcolor",
		"type" => "text"
	),
);
