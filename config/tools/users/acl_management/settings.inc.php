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

$config->acl_management = array(
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
		"tip"    => "Sets number of pages per range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
    "table_acls" => array(
        "default" => "grp",
        "name" => "Table ACLs",
        "tip" => "The name of the DB table where the groups (and mapping to SIP users) are stored.",
        "type" => "text"
    ),
    "grps" => array(
        "default" =>  array("grp_one","grp_two","grp_three"),
        "name" => "Groups",
        "tip" => "A list with the groups that you are using in your OpenSIPS config file. The value are custom and they are define by the script writer.",
        "type" => "json"
    )
    );