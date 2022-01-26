<?php

$config->callcenter = array(
	"module_id" => array(
		"default" => "callcenter",
		"name"    => "Module ID",
		"type"    => "text",
		"validation_regex" => null,
	),
    "talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
	),
	"custom_name" => array(
		"default" => "Call Center",
		"name" => "Custom name",
		"tip" => "A custom global name for the tool",
		"type" => "text"
	),
	"submenu_items" => array(
		"default" => array(
			"0"	=> "Agents",
			"1"	=> "Flows",
			"2"	=> "CDRs"),
		"name" => "Submenu Items",
		"type" => "json"
	),
	
	"title0" => array(
		"type" => "title",
		"title" => "Agents"
	),
	"agents_custom_table" => array(
		"default" => "cc_agents",
		"name" => "Agents custom table",
		"type" => "text"
	),
	"agents_custom_table_primary_key" => array(
		"default" => "id",
		"name" => "Agents custom table primary key",
		"type" => "text"
	),
	"agents_per_page" => array(
		"default" => 5,
		"name"    => "Results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
	"agents_page_range" => array(
		"default" => 3,
		"name"    => "Results page range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
	
	"title1" => array(
		"type" => "title",
		"title" => "Flows"
	),
	"flows_custom_table" => array(
		"default" => "cc_flows",
		"name" => "Flows custom table",
		"type" => "text"
	),
	"flows_custom_table_primary_key" => array(
		"default" => "id",
		"name" => "Flows custom table primary key",
		"type" => "text"
	),
	"flows_per_page" => array(
		"default" => 5,
		"name"    => "Results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
	"flows_page_range" => array(
		"default" => 3,
		"name"    => "Results page range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
	
	"title2" => array(
		"type" => "title",
		"title" => "CDRs"
	),
	"cdrs_custom_table" => array(
		"default" => "cc_cdrs",
		"name" => "CDRs custom table",
		"type" => "text"
	),
	"cdrs_custom_table_primary_key" => array(
		"default" => "id",
		"name" => "CDRs custom table primary key",
		"type" => "text"
	),
	"cdrs_per_page" => array(
		"default" => 5,
		"name"    => "Results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
	"cdrs_page_range" => array(
		"default" => 3,
		"name"    => "Results page range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
);