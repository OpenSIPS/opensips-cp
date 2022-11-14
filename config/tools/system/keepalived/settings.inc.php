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

if (!isset($config)) 
    $config = new stdClass();

$config->keepalived = array(
    "title0" => array(
		"type" => "title",
		"title" => "General settings"
	),
	"machines" => array(
		"default" => "",
		"name"	=> "Machines",
		"type"	=> "json",
		"tip" 	  => "TODO",
        "example" => "[
{
    \"name\": \"IP\"
    \"boxes\": [
    { \"box\": \"BOX\",
        \"ssh_ip\": \"defaults_to_mi_conn_ip\",
        \"ssh_port\": \"defaults_to_22\",
        \"ssh_user\": \"defaults_to_root\",
        \"ssh_key\": \"defaults_to_id_rsa\",
        \"exec\": \"defaults_to_/etc/init.d/keepalived\"
    }
    ]
    }
]"
	),
);
