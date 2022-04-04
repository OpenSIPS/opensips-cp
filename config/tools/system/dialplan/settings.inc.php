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
	\"attr A\" => \"Description attr A\",
	\"attr B\" => \"Description attr B\",
	\"attr C\" => \"Description attr C\",
}"
	),

	"title1" => array(
		"type" => "title",
		"title" => "DB settings"
	),
	"table_dialplan" => array(
		"default" => "dialplan",
		"name"    => "Table Dialplan",
		"type"    => "text",
		"validation_regex" => null,
		"tip"     => "The database table name for storing the diaplan rules",
	),

	"title2" => array(
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
