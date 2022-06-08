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

// $table_regex is used to validate custom tables names, you can leave this here
// even if you don't add custom tables
global $table_regex;

$config->your_module = array(
/*
	"custom_table" => array(
		"default" => "registrant",
		"name" => "Custom table",
		"validation_regex" => $table_regex,
		"type" => "text"
	),
	"per_page" => array(
		"default" => 40,
		"name"	=> "Results per page",
		"type"	=> "number",
		"validation_regex" => "^[0-9]+$",
	),
	"page_range" => array(
		"default" => 5,
		"name"	=> "Results page range",
		"type"	=> "number",
		"validation_regex" => "^[0-9]+$",
	),
*/
);
