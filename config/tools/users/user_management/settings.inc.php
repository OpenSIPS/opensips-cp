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

$config->user_management = array(
	"title0" => array(
		"type" => "title",
		"title" => "General settings"
	),
	"passwd_mode" => array(
		"default" => 0,
		"name"    => "Password mode",
		"options" => array('Plain Text'=>'0', 'HA1'=>'1'),
		"tip"     => "Controls the way the SIP user password is going to be saved in the database",
		"type"    => "dropdown",
	),
	"user_format" => array(
		"default" => NULL,
		"name"    => "User Format Regex",
		"type"    => "text",
		"tip"     => "A regular expression that indicates how a username should look like",
		"default" => "^[a-zA-Z0-9&=+$,;?/%]+$",
		"opt"     => true
	),

	"table_aliases" => array(
		"default" => array( 
			"DBaliases" => "dbaliases"
		),
		"name"    => "Aliases Table",
		"type"    => "json",
		"example" => "{
	\"DBaliases\": \"dbaliases\"
}"
	),
	"subs_extra" => array(
		"default" => array(),
		"name"    => "Extra columns",
		"tip"     => "This option allow you to define extra fields in the subscriber table (other than the ones created by default by OpenSIPS) 
		- these additional fields will be managed (added, displaied and modified) by the tool, for each user",
		"type"    => "json",
		"example" => "{
    \"first_name\": {
        \"header\": \"First Name\",
        \"info\": \"User's first name\",
        \"show_in_main_form\": true,
        \"show_in_add_form\": true,
        \"show_in_edit_form\": true,
        \"is_optional\": \"y\",
        \"searchable\": true
    },
    \"last_name\": {
        \"header\": \"Last Name\",
        \"info\": \"User's last name\",
        \"show_in_main_form\": true,
        \"show_in_add_form\": true,
        \"show_in_edit_form\": true,
        \"is_optional\": \"y\",
        \"searchable\": true
    },
    \"email_address\": {
        \"header\": \"Email\",
        \"info\": \"User's email\",
        \"show_in_main_form\": true,
        \"show_in_add_form\": true,
        \"show_in_edit_form\": true,
        \"is_optional\": \"y\",
        \"searchable\": false
    }
}"
	),
	"subs_extra_actions" => array(
		"default" => array(),
		"name"    => "Extra actions",
		"tip"     => "This option allow you to define extra action buttons (along with the extra fields) in the main page of the tool, for each user; one can specify either an action function, which should contain a function that expands to the url to be used, or an actual url, as a string.",
		"type"    => "json",
		"example" => "{
    \"ip\": {
        \"header\": \"IP\",
        \"icon\": \"../../../images/share/tools.png\",
	\"action_func\": \"function (\$result) { return \\\"../../system/dispatcher/dispatcher.php?action=ds_search&dispatcher_setid=\\\".\$result[\\\"id\\\"];}\"
    }
}"
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "System name",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
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
	"table_users" => array(
		"default" => "subscriber",
		"name"    => "Users Table",
		"type"    => "text",
		"validation_regex" => $table_regex,
	),
	"table_location" => array(
		"default" => "location",
		"name"    => "Location Table",
		"type"    => "text",
		"validation_regex" => $table_regex,
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
		"tip"     => "The number of pages per range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	)
);
