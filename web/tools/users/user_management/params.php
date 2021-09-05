<?php

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
		"tip"    => "Number of results page range",
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
		"tip"    => "Password mode",
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
		"tip"    => "Extra columns",
		"type"    => "json",
	)
);