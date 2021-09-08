<?php

$config->dialplan = array(
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
    "talk_to_this_assoc_id" => array(
		"default" => 1,
		"name"    => "Talk to this assoc id",
		"options" => get_assoc_id(),
		"type"    => "dropdown",
	),
    "table_dialplan" => array(
		"default" => "dialplan",
		"name"    => "Table Dialplan",
		"type"    => "text",
		"validation_regex" => null,
	),
    "attrs_cb" => array(
        "default" => array(
            "a" => "Descr a",
            "b" => "Descr b",
            "c" => "Descr c",
        ),
        "name" => "Attributes cb",
        "type" => "json"
    ),
	"dialplan_attributes_mode" => array(
		"default" => 1,
		"name"    => "Dialplan attributes mode",
		"options" => array('Checkboxes'=>'0', 'Text'=>'1'),
		"type"    => "dropdown",
	),
);