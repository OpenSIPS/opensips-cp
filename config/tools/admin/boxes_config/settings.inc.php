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
global $config;
$config->boxes = array(
    "mi_conn" => array(
		"default" => "json:127.0.0.1:8888/mi",
		"name"    => "MI connector",
		"nodes"  => array("mi", "conn"),
		"type"    => "text",
		'show_in_edit_form' => true,
	),
	"monit_conn" => array(
		"default" => "127.0.0.1:2812",
		"name"    => "Monit connector",
		"nodes"  => array("monit", "conn"),
		"type"    => "text",
		'show_in_edit_form' => true,
    ),
	"monit_user" => array(
		"default" => "",
		"name"    => "Monit username",
		"nodes"  => array("monit", "user"),
		"type"    => "text",
		"validation_regex" => null,
		'show_in_edit_form' => true,
	),
	"monit_pass" => array(
		"default" => "",
		"name"    => "Monit password",
		"type"    => "password",
		"nodes"  => array("monit", "pass"),
		"validation_regex" => null,
		'show_in_edit_form' => true,
	),
	"monit_ssl" => array(
		"default" => "/",
		"name"    => "Monit SSL",
        "options" => array('Disabled'=>'0', 'Enabled'=>'1'),
		"nodes"  => array("monit", "has_ssl"),
		"type"    => "dropdown",
		'show_in_edit_form' => true,
		"validation_regex" => null,
    ),
	"desc" => array(
		"default" => "",
		"name"    => "Box description",
		"type"    => "text",
		"validation_regex" => null,
		'show_in_edit_form' => true,
    ),
	"smonitcharts" => array(
		"default" => '1',
		"name"    => "Smonitor charting",
		"nodes"  => array("smonitor", "charts"),
        "options" => array('Off'=>'0', 'On'=>'1'),
		'show_in_edit_form' => true,
		"type"    => "dropdown",
		"validation_regex" => null,
    ),
	"assoc_id" => array(
		"default" => "",
		"name"    => "System name",
		"options" => get_assoc_id(),
		'show_in_edit_form' => true,
		"type"    => "dropdown",
		"validation_regex" => null,
    ),
);





 ?>