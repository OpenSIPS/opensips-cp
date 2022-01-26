<?php

$config->uac_registrant = array(
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
	),
	"custom_table" => array(
		"default" => "registrant",
		"name" => "Custom table",
		"type" => "text"
	),
	"per_page" => array(
		"default" => 40,
		"name"    => "Results per page",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
	"page_range" => array(
		"default" => 5,
		"name"    => "Results page range",
		"type"    => "number",
		"validation_regex" => "^[0-9]+$",
    ),
);