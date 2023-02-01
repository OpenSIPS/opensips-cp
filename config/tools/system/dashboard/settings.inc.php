<?php
/*
 * Copyright (C) 2023 OpenSIPS Solutions
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

$config->dashboard = array(
	"title0" => array(
		"type" => "title",
		"title" => "Database"
	),
	"db_config" => array(
		"default" => 0,
		"name" => "DB configuration",
		"type" => "dropdown",
		"options" => get_db_configs(),
		"tip" => "DB configuration to use for this tool"
	),
	"custom_table" => array(
		"default" => "ocp_dashboard",
		"name" => "Dashboard table",
		"validation_regex" => $table_regex,
		"type" => "text"
	),
	"title1" => array(
		"type" => "title",
		"title" => "Layout"
	),
	"results_per_page" => array(
		"default" => 20,
		"name"    => "Results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"results_page_range" => array(
		"default" => 5,
		"name"    => "Results page range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
	),
);
