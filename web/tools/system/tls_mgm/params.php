<?php

$config->tls_mgm = array(
	"module_id" => array(
		"default" => "tls_mgm",
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

	"custom_table" => array(
		"default" => "tls_mgm",
		"name" => "Custom table",
		"type" => "text"
	),
	"custom_table_primary_key" => array(
		"default" => "id",
		"name" => "Custom table primary key",
		"type" => "text"
	),
	"per_page" => array(
		"default" => 25,
		"name"    => "Results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
	"page_range" => array(
		"default" => 3,
		"name"    => "Results page range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
);