<?php
/*
 * $Id$
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

###############################################################################

 
	//database tables
	$config->table_users = "subscriber";
	$config->table_location = "location";

	$config->results_per_page = 25;
	$config->results_page_range = 10;

	// The system ID of the SIP servers. We need this
	// in order to query via MI for registration information
	$talk_to_this_assoc_id = 1 ;

	// the array containing the alias tables
	$config->table_aliases = array("DBaliases"=>"dbaliases");


	// Password for the SIP account can be saved in plain text
	// by setting $config->passwd_mode to 0 or chyphered (HA1), by setting it to 1
	$config->passwd_mode=0;

	// Array with optional extra fields for 'subscriber' table
	// Key is the column name, the value is the Display name
	$config->subs_extra = array(
	/*
		"first_name" => array(
			"header"		=> "First Name",
			"info"			=> "User's first name",
			"show_in_main_form" 	=> true,
			"show_in_add_form" 	=> true,
			"show_in_edit_form"	=> true,
			"is_optional"           => "y",
			"searchable"            => true,
			"validation_regex" 	=> "^.*$",
			"default_value" 	=> NULL,
		),
		"last_name" => array(
			"header"		=> "Last Name",
			"info"			=> "User's last name",
			"show_in_main_form" 	=> true,
			"show_in_add_form" 	=> true,
			"show_in_edit_form"	=> true,
			"is_optional"           => "y",
			"searchable"            => true,
			"validation_regex" 	=> "^.*$",
			"default_value" 	=> NULL,
		),
		"email_address" => array(
			"header"		=> "Email",
			"info"			=> "User's email address",
			"show_in_main_form" 	=> true,
			"show_in_add_form" 	=> true,
			"show_in_edit_form"	=> true,
			"is_optional"           => "y",
			"searchable"            => true,
			"validation_regex" 	=> "^.*$",
			"default_value" 	=> NULL,
		),
	*/
	);
?>
