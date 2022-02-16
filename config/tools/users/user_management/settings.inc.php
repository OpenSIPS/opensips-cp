<?php
/*
 * Copyright (C) 2011 OpenSIPS Project
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

$config->user_management = array(
	"table_users" => array(
		"default" => "subscriber",
		"name"    => "Table Users",
		"type"    => "text",
		"validation_regex" => null,
	),
	"table_location" => array(
		"default" => "location",
		"name"    => "Table Location",
		"type"    => "text",
		"validation_regex" => null,
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
		"tip"    => "The number of pages per range ",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"table_aliases" => array(
		"default" => array( 
			"DBaliases" => "dbaliases"
		),
		"name"    => "Table aliases",
		"type"    => "json",
	),
	"passwd_mode" => array(
		"default" => 0,
		"name"    => "Password mode",
		"options" => array('Plain Text'=>'0', 'HA1'=>'1'),
		"tip"    => "This array controls the way the SIP user password is going to be saved in the database",
		"type"    => "dropdown",
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
	),
	"subs_extra" => array(
		"default" => array(),
		"name"    => "Extra columns",
		"tip"    => "This option allow you to define extra fields in the subscriber table (other than the ones created by default by OpenSIPS) 
		- these additional fields will be managed (added, displaied and modified) by the tool, for each user",
		"type"    => "json",
	)
);