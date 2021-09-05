<?php

$config->dispatcher = array(
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
    "table_dispatcher" => array(
        "default" => "dispatcher",
        "name" => "Table Dispatcher",
        "type" => "text"
    ),
	"talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
	),
    "status" => array(
        "default" => array('Active'=>'Active','Inactive'=>'Inactive','Probing'=>'Probing'),
        "name" => "Status",
        "type" => "json"
    )
    );