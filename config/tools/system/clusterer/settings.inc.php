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
global $config;

$config->clusterer = array(
	"title0" => array(
		"type" => "title",
		"title" => "General settings"
	),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Linked system",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
		"tip"     => "As OCP can manage multiple OpenSIPS instances, this is the association ID
		pointing to the group of servers (system) which needs to be provision with this clusterer information.",
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
	"table_clusterer" => array(
		"default" => "clusterer",
		"name" => "Table Clusterer",
		"tip" => "The name of the DB table holding the cluster configuration
		(this needs to be correlated with the OpenSIPS configuration). The default value is 'clusterer'.",
		"validation_regex" => $table_regex,
		"type" => "text"
	),
	"title2" => array(
		"type" => "title",
		"title" => "Cluster Bridges"
	),
	"bridge_enabled" => array(
		"default" => "0",
		"name" => "Enable Bridges tab",
		"type" => "dropdown",
		"options" => array("Disabled" => "0", "Enabled" => "1"),
		"tip" => "Show the 'Cluster Bridges' tab for managing the clusterer_bridge table
		(replication links between separate OpenSIPS clusters, available since OpenSIPS 4.0)."
	),
	"table_bridge" => array(
		"default" => "clusterer_bridge",
		"name" => "Table Bridges",
		"tip" => "The name of the DB table holding the cluster bridge configuration.
		The default value is 'clusterer_bridge'.",
		"validation_regex" => $table_regex,
		"type" => "text"
	),
	"title3" => array(
		"type" => "title",
		"title" => "Display settings"
	),
	"per_page" => array(
		"default" => 20,
		"name" => "Results per page",
		"type" => "number",
		"validation_regex" => "^[0-9]+$",
	),
	"page_range" => array(
		"default" => 5,
		"name" => "Results page range",
		"type" => "number",
		"validation_regex" => "^[0-9]+$",
	)
);
